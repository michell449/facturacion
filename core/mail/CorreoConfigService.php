<?php
/**
 * Servicio auxiliar para la configuración de correo SMTP.
 */


require_once __DIR__ . '/../../config.php';

if (!defined('MAIL_CONFIG_SECRET')) {
    define('MAIL_CONFIG_SECRET', 'mail_config_secret_key');
}

/**
 * Crea la tabla de configuración de correo si no existe.
 */
function correoConfigEnsureTable(PDO $conn): void
{
    $sql = "CREATE TABLE IF NOT EXISTS config_correo (
        id_config INT AUTO_INCREMENT PRIMARY KEY,
        id_usuario INT NOT NULL,
        smtp_host VARCHAR(255) NOT NULL,
        smtp_port INT NOT NULL DEFAULT 587,
        smtp_usuario VARCHAR(255) NOT NULL,
        smtp_password VARBINARY(512) NULL,
        remitente_nombre VARCHAR(255) NOT NULL,
        remitente_email VARCHAR(255) NOT NULL,
        asunto_factura VARCHAR(255) NOT NULL,
        plantilla_correo TEXT NOT NULL,
        seguridad VARCHAR(10) NOT NULL DEFAULT 'tls',
        activo TINYINT(1) NOT NULL DEFAULT 1,
        fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        fecha_actualizacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uniq_usuario (id_usuario)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    $conn->exec($sql);
}

/**
 * Genera un arreglo con valores predeterminados.
 */
function correoConfigDefaults(): array
{
    return [
        'smtp_host' => defined('MAIL_HOST') ? MAIL_HOST : 'smtp.example.com',
        'smtp_port' => defined('MAIL_PORT') ? MAIL_PORT : 587,
        'smtp_usuario' => defined('MAIL_USER') ? MAIL_USER : '',
        'smtp_password' => null,
        'remitente_nombre' => 'Sistema de Facturación',
        'remitente_email' => defined('MAIL_USER') ? MAIL_USER : 'noreply@example.com',
        'asunto_factura' => 'Factura Electrónica - Folio {FOLIO}',
        'plantilla_correo' => "Estimado(a) {CLIENTE},\r\n\r\nAdjunto encontrará su factura electrónica con los siguientes detalles:\r\n\r\n• Folio: {FOLIO}\r\n• Fecha de emisión: {FECHA}\r\n• Importe Total: {TOTAL}\r\n\r\nEsta factura ha sido generada electrónicamente y tiene plena validez fiscal.\r\n\r\nAgradecemos su preferencia.\r\n\r\nAtentamente,\r\n{EMPRESA}",
        'seguridad' => 'tls'
    ];
}

/**
 * Cifra un dato sensible usando AES-256-CBC.
 */
function correoConfigEncrypt(?string $plainValue, int $userId): ?string
{
    if ($plainValue === null || $plainValue === '') {
        return null;
    }

    $cipher = 'AES-256-CBC';
    $key = hash('sha256', MAIL_CONFIG_SECRET . '|' . $userId, true);
    $ivLength = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivLength);
    $cipherText = openssl_encrypt($plainValue, $cipher, $key, 0, $iv);

    if ($cipherText === false) {
        return null;
    }

    return base64_encode($iv . $cipherText);
}

/**
 * Descifra un valor sensible previamente cifrado.
 */
function correoConfigDecrypt(?string $cipherValue, int $userId): ?string
{
    if ($cipherValue === null || $cipherValue === '') {
        return null;
    }

    $cipher = 'AES-256-CBC';
    $key = hash('sha256', MAIL_CONFIG_SECRET . '|' . $userId, true);
    $raw = base64_decode($cipherValue, true);

    if ($raw === false) {
        return null;
    }

    $ivLength = openssl_cipher_iv_length($cipher);
    $iv = substr($raw, 0, $ivLength);
    $encrypted = substr($raw, $ivLength);

    $plain = openssl_decrypt($encrypted, $cipher, $key, 0, $iv);

    return $plain !== false ? $plain : null;
}

/**
 * Obtiene la configuración de correo del usuario.
 */
function correoConfigGet(PDO $conn, int $userId, bool $includePassword = false): ?array
{
    correoConfigEnsureTable($conn);

    $stmt = $conn->prepare("SELECT * FROM config_correo WHERE id_usuario = ? LIMIT 1");
    $stmt->execute([$userId]);
    $config = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$config) {
        return null;
    }

    if ($includePassword) {
        $config['smtp_password'] = correoConfigDecrypt($config['smtp_password'] ?? null, $userId);
    } else {
        unset($config['smtp_password']);
    }

    return $config;
}

/**
 * Guarda o actualiza la configuración de correo.
 */
function correoConfigSave(PDO $conn, int $userId, array $data): void
{
    correoConfigEnsureTable($conn);

    $existing = correoConfigGet($conn, $userId, true);

    $password = $data['smtp_password'] ?? null;
    if (($password === null || $password === '') && $existing && !empty($existing['smtp_password'])) {
        $password = $existing['smtp_password'];
    }

    $encryptedPassword = correoConfigEncrypt($password, $userId);

    if ($existing) {
        $sql = "UPDATE config_correo SET
            smtp_host = :smtp_host,
            smtp_port = :smtp_port,
            smtp_usuario = :smtp_usuario,
            smtp_password = :smtp_password,
            remitente_nombre = :remitente_nombre,
            remitente_email = :remitente_email,
            asunto_factura = :asunto_factura,
            plantilla_correo = :plantilla_correo,
            seguridad = :seguridad
        WHERE id_usuario = :id_usuario";
    } else {
        $sql = "INSERT INTO config_correo (
            id_usuario, smtp_host, smtp_port, smtp_usuario, smtp_password,
            remitente_nombre, remitente_email, asunto_factura, plantilla_correo, seguridad
        ) VALUES (
            :id_usuario, :smtp_host, :smtp_port, :smtp_usuario, :smtp_password,
            :remitente_nombre, :remitente_email, :asunto_factura, :plantilla_correo, :seguridad
        )";
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':id_usuario' => $userId,
        ':smtp_host' => $data['smtp_host'],
        ':smtp_port' => (int)$data['smtp_port'],
        ':smtp_usuario' => $data['smtp_usuario'],
        ':smtp_password' => $encryptedPassword,
        ':remitente_nombre' => $data['remitente_nombre'],
        ':remitente_email' => $data['remitente_email'],
        ':asunto_factura' => $data['asunto_factura'],
        ':plantilla_correo' => $data['plantilla_correo'],
        ':seguridad' => $data['seguridad'] ?? 'tls'
    ]);
}