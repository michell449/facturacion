<?php
/**
 * Guarda solo el formato del correo (asunto y plantilla).
 * La configuración SMTP es global y no se edita por usuario.
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/mail/CorreoConfigService.php';

header('Content-Type: application/json; charset=utf-8');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

try {
    $idUsuario = $_SESSION['usuario_id'] ?? $_SESSION['USR_ID'] ?? null;
    if (!$idUsuario) {
        throw new Exception('Sesión no válida o expirada.');
    }

    $input = file_get_contents('php://input');
    $payload = json_decode($input, true);
    if (!is_array($payload)) {
        throw new Exception('Datos inválidos.');
    }

    // Solo requerimos asunto y plantilla
    if (empty($payload['asunto_factura']) || empty($payload['plantilla_correo'])) {
        throw new Exception('El asunto y la plantilla son obligatorios.');
    }

    $db = new Database();
    $conn = $db->getConnection();
    if (!($conn instanceof PDO)) {
        throw new Exception('No se pudo conectar a la base de datos.');
    }

    // Obtener configuración existente (con SMTP configurado globalmente)
    $existing = correoConfigGet($conn, (int)$idUsuario, true);
    
    if (!$existing) {
        // Si no existe, crear con valores por defecto del config.php
        $defaults = correoConfigDefaults();
        $configData = [
            'smtp_host' => defined('MAIL_HOST') ? MAIL_HOST : $defaults['smtp_host'],
            'smtp_port' => defined('MAIL_PORT') ? MAIL_PORT : $defaults['smtp_port'],
            'smtp_usuario' => defined('MAIL_USER') ? MAIL_USER : $defaults['smtp_usuario'],
            'smtp_password' => defined('MAIL_PSWD') ? MAIL_PSWD : $defaults['smtp_password'],
            'remitente_nombre' => defined('SYSNAME') ? SYSNAME : $defaults['remitente_nombre'],
            'remitente_email' => defined('MAIL_USER') ? MAIL_USER : $defaults['remitente_email'],
            'asunto_factura' => trim($payload['asunto_factura']),
            'plantilla_correo' => trim($payload['plantilla_correo']),
            'seguridad' => defined('MAIL_SEC') ? MAIL_SEC : $defaults['seguridad']
        ];
    } else {
        // Si existe, mantener SMTP y actualizar solo formato
        $configData = [
            'smtp_host' => $existing['smtp_host'],
            'smtp_port' => $existing['smtp_port'],
            'smtp_usuario' => $existing['smtp_usuario'],
            'smtp_password' => $existing['smtp_password'],
            'remitente_nombre' => $existing['remitente_nombre'],
            'remitente_email' => $existing['remitente_email'],
            'asunto_factura' => trim($payload['asunto_factura']),
            'plantilla_correo' => trim($payload['plantilla_correo']),
            'seguridad' => $existing['seguridad']
        ];
    }

    correoConfigSave($conn, (int)$idUsuario, $configData);

    echo json_encode([
        'success' => true,
        'message' => 'Configuración guardada correctamente.'
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
