<?php
//core/logout.php
//Cerrar sesión del usuario de forma segura

// Limpiar output buffer
if (ob_get_level()) {
    ob_clean();
}

session_start();

// Configurar cabeceras para JSON
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

// Solo permitir POST requests para logout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Log del logout para auditoría (opcional)
    if (isset($_SESSION['usuario_id'])) {
        $usuario_id = $_SESSION['usuario_id'];
        $timestamp = date('Y-m-d H:i:s');
        // Aquí podrías agregar logging a base de datos si es necesario
    }
    
    // Destruir todas las variables de sesión de forma segura
    $_SESSION = array();
    
    // Eliminar cookie de sesión si existe
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destruir completamente la sesión
    session_destroy();
    
    // Regenerar ID de sesión para mayor seguridad
    session_start();
    session_regenerate_id(true);
    session_destroy();
    
    echo json_encode([
        'success' => true,
        'message' => 'Sesión cerrada correctamente',
        'redirect' => 'index.php?pg=facturar-login&logout=1'
    ]);
    exit;
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido. Use POST.'
    ]);
    exit;
}
?>