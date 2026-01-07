<?php
/**
 * Devuelve la configuración SMTP del usuario autenticado.
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

    $db = new Database();
    $conn = $db->getConnection();
    if (!($conn instanceof PDO)) {
        throw new Exception('No se pudo conectar a la base de datos.');
    }

    $config = correoConfigGet($conn, (int)$idUsuario, false);
    if (!$config) {
        $config = correoConfigDefaults();
    }

    echo json_encode([
        'success' => true,
        'config' => $config
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
