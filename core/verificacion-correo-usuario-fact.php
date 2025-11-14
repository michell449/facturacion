<?php
// core/verificacion-correo-usuario-fact.php
// Verificación de token para activar cuentas de usuario

require_once __DIR__ . '/class/db.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

function sendJsonResponse($success, $message) {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

try {
    // Obtener token
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    $token = isset($data['token']) ? trim($data['token']) : '';
    
    if (empty($token)) {
        sendJsonResponse(false, 'No se recibió el código de verificación.');
    }
    
    if (!preg_match('/^\d{6}$/', $token)) {
        sendJsonResponse(false, 'El código de verificación debe contener exactamente 6 dígitos.');
    }
    
    // Conectar a la base de datos
    $db = new Database();
    $conn = $db->getConnection();
    
    // Buscar usuario con el token
    $stmt = $conn->prepare("SELECT correo, verificacion FROM usuarios WHERE token = ? AND verificacion = 0");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // Marcar como verificado y limpiar el token
        $stmtUpdate = $conn->prepare("UPDATE usuarios SET verificacion = 1, token = NULL WHERE token = ?");
        $updateResult = $stmtUpdate->execute([$token]);
        
        if ($updateResult) {
            sendJsonResponse(true, '¡Verificación exitosa! Tu cuenta ha sido activada.');
        } else {
            sendJsonResponse(false, 'Error al actualizar la cuenta. Inténtalo de nuevo.');
        }
    } else {
        sendJsonResponse(false, 'El código de verificación es incorrecto o ya fue utilizado.');
    }
    
} catch (PDOException $e) {
    error_log('Error de base de datos en verificación: ' . $e->getMessage());
    sendJsonResponse(false, 'Error de conexión a la base de datos.');
} catch (Exception $e) {
    error_log('Error en verificación de correo: ' . $e->getMessage());
    sendJsonResponse(false, 'Error interno del servidor.');
}
?>
