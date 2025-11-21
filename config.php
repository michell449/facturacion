<?php
ob_start();

// Configurar headers de seguridad HTTP (corrección del problema X-Frame-Options)
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

// Headers adicionales para mejorar compatibilidad con tracking prevention
header('Cache-Control: public, max-age=3600');
header('Vary: Accept-Encoding');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inicializar variables de sesión si no existen
if (!isset($_SESSION['USR_ID'])) $_SESSION['USR_ID'] = '';
if (!isset($_SESSION['USR_NAME'])) $_SESSION['USR_NAME'] = '';
if (!isset($_SESSION['USR_TYPE'])) $_SESSION['USR_TYPE'] = '';
if (!isset($_SESSION['USR_MAIL'])) $_SESSION['USR_MAIL'] = '';
if (!isset($_SESSION['ERROR_MSG'])) $_SESSION['ERROR_MSG'] = '';
if (!isset($_SESSION['DEFAULT_MSG'])) $_SESSION['DEFAULT_MSG'] = '';

// Variables de sesión nuevas del sistema de login
if (!isset($_SESSION['usuario_id'])) $_SESSION['usuario_id'] = '';
if (!isset($_SESSION['correo'])) $_SESSION['correo'] = '';
if (!isset($_SESSION['tipo_usuario'])) $_SESSION['tipo_usuario'] = '';
if (!isset($_SESSION['loggedin'])) $_SESSION['loggedin'] = false;

$_SESSION['FISCAL_COMPLETE'] = $_SESSION['FISCAL_COMPLETE'] ?? 0;

// Check if SSL
if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
    $uri = 'https://';
} else {
    $uri = 'http://';
}

define('VERSION', '1.0');
define('SYSNAME', 'Admin-SRJ');

if (!defined('ABS_PATH')) {
    define('ABS_PATH', str_replace('\\', '/', dirname(__FILE__) . '/'));
}

if (!defined('HOMEURL')) {
    // URL donde se aloja la aplicacion
    $base = 'localhost/facturacion'; 
    define('HOMEURL', "$uri$base");
}

if (!defined('PLANTILLAS_CORREO')) {
    define('PLANTILLAS_CORREO', ABS_PATH . "/core/mail/plantillas/");
}




/**
 * Parámetros del MySQL  
 */
define('MULTISITE', 0);

/** MySQL database name */
define('DB_NAME', 'facturacion');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Table prefix */
define('DB_TABLE_PREFIX', '');

/**
 * Parámetros del Correo electrónico  
 */
define('MAIL_HOST', 'smtp.ionos.mx');
define('MAIL_PORT', 587);
define('MAIL_USER', 'noreply@xube.com.mx');
define('MAIL_PSWD', '@Xub3*761');
define('MAIL_AUT', TRUE);
define('MAIL_SEC', 'tls');




date_default_timezone_set('America/Mexico_City');
if (!isset($_SESSION['USR_ID'])) {
    $_SESSION['USR_ID'] = '';
}
if (!isset($_SESSION['USR_NAME'])) {
    $_SESSION['USR_NAME'] = '';
}

if (!isset($_SESSION['USR_MAIL'])) {
    $_SESSION['USR_MAIL'] = '';
}

if (!isset($_SESSION['USR_TYPE'])) {
    $_SESSION['USR_TYPE'] = '';
}
if (!isset($_SESSION['ERROR_MSG'])) {
    $_SESSION['ERROR_MSG'] = '';
}
if (!isset($_SESSION['DEFAULT_MSG'])) {
    $_SESSION['DEFAULT_MSG'] = '';
}

$appPath = HOMEURL;

