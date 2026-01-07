<?php
// core/consultar-sucursales.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';

header('Content-Type: application/json; charset=utf-8');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$respuesta = [
    'success' => false,
    'data'    => [],
    'message' => 'Error desconocido.'
];

try {
    // Debug: Verificar sesión
    error_log('SESSION: ' . json_encode($_SESSION));
    
    // Obtener el ID de usuario de la sesión
    $id_usuario = null;
    
    if (isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id'])) {
        $id_usuario = (int)$_SESSION['usuario_id'];
    } elseif (isset($_SESSION['USR_ID']) && !empty($_SESSION['USR_ID'])) {
        $id_usuario = (int)$_SESSION['USR_ID'];
    }
    
    error_log('ID Usuario detectado: ' . ($id_usuario ?? 'NULL'));
    
    if (!$id_usuario) {
        throw new Exception('Sesión de usuario no válida. ID de usuario no encontrado en la sesión.');
    }

    // Conectar a BD
    $db = new Database();
    $conn = $db->getConnection();
    if (!($conn instanceof PDO)) {
        throw new Exception('No se pudo conectar a la base de datos.');
    }
    
    // Consultar sucursales del usuario
    $sql = "SELECT id_empresa, razon_social, nombre, codigo_suc, rfc, reg_fiscal, cp, direccion, colonia, estatus, logo, correo, file_cer, file_key 
            FROM empresas 
            WHERE id_usuario = ? 
            ORDER BY razon_social ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_usuario]);
    $sucursales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    error_log('Sucursales encontradas: ' . count($sucursales));
    
    if (empty($sucursales)) {
        // Retornar success false pero con mensaje claro
        $respuesta['success'] = false;
        $respuesta['data'] = [];
        $respuesta['message'] = 'No hay sucursales registradas para este usuario.';
    } else {
        // Filtrar solo activas si es necesario
        $sucursales_activas = array_filter($sucursales, function($s) {
            return (int)$s['estatus'] === 1;
        });
        
        // Obtener catálogo de regímenes fiscales
        $stmt_regimenes = $conn->query("SELECT codigo, descr FROM cat_regimen_fiscal");
        $regimenes_map = $stmt_regimenes->fetchAll(PDO::FETCH_KEY_PAIR);
        
        $respuesta['success'] = true;
        $respuesta['data'] = array_values($sucursales_activas); // Reset indices del array
        $respuesta['regimenes'] = $regimenes_map;
        $respuesta['total'] = count($sucursales_activas);
    }
    
} catch (Exception $e) {
    error_log('Error en consultar-sucursales.php: ' . $e->getMessage());
    $respuesta['success'] = false;
    $respuesta['message'] = $e->getMessage();
    $respuesta['data'] = [];
}

echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
?>