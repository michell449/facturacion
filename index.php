<?php
ob_start();

require_once 'config.php';
require_once 'core/auth.php'; 

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    check_session_expiry(); 
}

$uri = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https://' : 'http://';
$uri .= $_SERVER['HTTP_HOST'];
$base = dirname($_SERVER['PHP_SELF']);
$pagePath = substr($_SERVER['REQUEST_URI'], strlen($base));
$pagePath = explode('?', $pagePath);
$pagePath = $pagePath[0];
$pagePath = trim($pagePath, "/");
$pagePath = str_replace(".php", "", $pagePath);


$isPanelPage = ($pagePath === 'panel'); 

if ($isPanelPage) {
    if (isset($_GET['pg']) && !empty(trim($_GET['pg']))) {
        $pg = trim($_GET['pg']);
        $pg = str_replace('..', '', $pg); 
        $pg = trim($pg, '/'); 
        $pagePath = $pg; 
    } else {
        $pagePath = ''; 
    }
}

if ($pagePath === 'index') {
    $pagePath = '';
}

if ($pagePath === 'facturar-login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'core/login.php';
    exit(); 
}

if (empty($pagePath)) {
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        $pagePath = 'facturar-login'; 
    } else {
        if (is_admin()) {
            $redirectTarget = 'panel?pg=inicio-admin';
        } else {
            $redirectTarget = 'panel?pg=inicio'; 
        }
        header("Location: index.php?$redirectTarget");
        exit(); 
    }
}

$accessMap = [
    // Páginas invitado
    'facturar-login' => ['guest'],
    'facturar-invitado' => ['guest'],
    'registro-info-usuarios' => ['guest', 'cliente'], 
    'verificacion-sistema' => ['guest'],
    '404' => ['guest', 'cliente', 'admin'],

    // Páginas de Cliente 
    'inicio' => ['cliente'],
    'perfil' => ['cliente'],
    'historial' => ['cliente'],
    'facturar' => ['cliente'],
    'configuracion' => ['cliente'],
    'soporte' => ['cliente'],

    // Páginas de Administrador 
    'inicio-admin' => ['admin'], 
    'gestion-sucursales' => ['admin'],
    'nueva-sucursal-admin' => ['admin'],
    'facturas-generadas-admin' => ['admin'],
    'tickets-por-facturar' => ['admin'],
    'admin-dashboard' => ['admin'],
    'usuarios-admin' => ['admin'],
    'reportes-admin' => ['admin'],
];

$currentPage = $pagePath;

if (isset($accessMap[$currentPage])) {
    
    if (in_array($currentPage, ['facturar-login', 'facturar-invitado']) && is_authenticated()) {
        if (is_admin()) {
            header('Location: index.php?pg=inicio-admin');
        } else if (is_cliente()) {
            header('Location: index.php?pg=inicio');
        }
        exit();
    }
    
    require_roles($accessMap[$currentPage]); 
    
} else if (is_authenticated()) {
    if (is_admin()) {
        header('Location: index.php?pg=inicio-admin'); 
    } else if (is_cliente()) {
        header('Location: index.php?pg=inicio');
    }
    exit();
} else {
    header('Location: index.php?pg=facturar-login');
    exit();
}



$pageInclude = "pages/$currentPage.inc.php";

if (!file_exists($pageInclude)) {
    $currentPage = '404';
    $pageInclude = "pages/$currentPage.inc.php";
}

$pageTitle = str_replace('-', ' ', $currentPage);
$pageTitle = ucfirst($pageTitle);

// Encabezados
require_once 'pages/head.inc.php';

$isPanel = in_array($currentPage, ['inicio-admin', 'gestion-sucursales', 'nueva-sucursal-admin', 'facturas-generadas-admin', 'tickets-por-facturar']);

if ($isPanel) {
    echo '<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">';
} else {
    echo '<body class="d-flex flex-column min-vh-100">';
}

// Incluir headers
$noHeaderPages = ['facturar-login', 'facturar-invitado', 'registro-info-usuarios', 'verificacion-sistema', '404'];
if (!in_array($currentPage, $noHeaderPages)) {
    if (is_admin()) {
        require_once 'pages/header-admin.inc.php'; 
    } else {
        require_once 'pages/header.inc.php'; 
    }
}

// Contenido Principal
require_once $pageInclude;

// Incluir footer y scripts
$noFooterPages = ['facturar-login', 'registro-info-usuarios', 'verificacion-sistema'];
if (!in_array($currentPage, $noFooterPages)) {
    require_once 'pages/footer.inc.php';
    require_once 'pages/script.inc.php';
}

echo '</body>';
echo '</html>';

ob_end_flush();