<?php
/**
 * Envía un correo de prueba usando la configuración SMTP global.
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/mail/CorreoConfigService.php';
require_once __DIR__ . '/mail/FacturaMailer.php';

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

    $correoDestino = $payload['email_prueba'] ?? '';
    if (!filter_var($correoDestino, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Correo de prueba inválido.');
    }

    $db = new Database();
    $conn = $db->getConnection();
    if (!($conn instanceof PDO)) {
        throw new Exception('No se pudo conectar a la base de datos.');
    }

    // Obtener configuración existente o usar defaults
    $existente = correoConfigGet($conn, (int)$idUsuario, true);
    
    if (!$existente) {
        $defaults = correoConfigDefaults();
        $existente = [
            'smtp_host' => defined('MAIL_HOST') ? MAIL_HOST : $defaults['smtp_host'],
            'smtp_port' => defined('MAIL_PORT') ? MAIL_PORT : $defaults['smtp_port'],
            'smtp_usuario' => defined('MAIL_USER') ? MAIL_USER : $defaults['smtp_usuario'],
            'smtp_password' => defined('MAIL_PSWD') ? MAIL_PSWD : '',
            'remitente_nombre' => defined('SYSNAME') ? SYSNAME : $defaults['remitente_nombre'],
            'remitente_email' => defined('MAIL_USER') ? MAIL_USER : $defaults['remitente_email'],
            'seguridad' => defined('MAIL_SEC') ? MAIL_SEC : $defaults['seguridad']
        ];
    }

    // Usar plantilla personalizada del payload o la guardada
    $config = [
        'smtp_host' => $existente['smtp_host'],
        'smtp_port' => (int)$existente['smtp_port'],
        'smtp_usuario' => $existente['smtp_usuario'],
        'smtp_password' => $existente['smtp_password'],
        'remitente_nombre' => $existente['remitente_nombre'],
        'remitente_email' => $existente['remitente_email'],
        'asunto_factura' => $payload['asunto_factura'] ?? ($existente['asunto_factura'] ?? 'Factura de prueba'),
        'plantilla_correo' => $payload['plantilla_correo'] ?? ($existente['plantilla_correo'] ?? 'Mensaje de prueba'),
        'seguridad' => $existente['seguridad']
    ];

    if (empty($config['smtp_password'])) {
        throw new Exception('La configuración SMTP no está completa. Contacte al administrador.');
    }

    $vars = [
        'folio' => 'PRUEBA-0001',
        'empresa' => $config['remitente_nombre'],
        'cliente' => 'Cliente de Prueba',
        'fecha' => date('d/m/Y H:i'),
        'total' => '$1,234.56',
        'rfc_cliente' => 'XAXX010101000',
        'rfc_empresa' => 'AAA010101AAA'
    ];

    $resultado = facturaEnviarCorreo($config, $correoDestino, 'Prueba SMTP', $vars, []);

    if (!$resultado['success']) {
        throw new Exception($resultado['message']);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Correo de prueba enviado correctamente.'
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
