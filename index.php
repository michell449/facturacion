<?php
require_once 'config.php';

if (!isset($_SESSION['last_access']) || (time() - $_SESSION['last_access']) > 60)
    $_SESSION['last_access'] = time();

$uri .= $_SERVER['HTTP_HOST'];
$base = dirname($_SERVER['PHP_SELF']);
$appPath = "$uri$base";
$pagePath = substr($_SERVER['REQUEST_URI'], strlen($base));
$pagePath = explode('?', $pagePath);
$pagePath = $pagePath[0];
#echo $pagePath ;
$pagePath = trim($pagePath, "/");
$pagePath = str_replace(".php", "", $pagePath);

$isPanelPage = ($pagePath == 'panel'); 

if ($isPanelPage) {
    if (isset($_GET['pg']) && !empty(trim($_GET['pg']))) {
        
        $pg = trim($_GET['pg']);
        $pg = str_replace('..', '', $pg); 
        $pg = trim($pg, '/'); 
        $pagePath = $pg; 
        
    } else {
        $pagePath = 'facturar-login'; 
    }
}

if ($pagePath == 'index') {
    $pagePath = '';
}

if (empty($pagePath)) {
    if ($_SESSION['USR_ID'] == '') {
        $pagePath = 'facturar-login';
    } else {
        $pagePath = 'inicio';
    }
}
$pageInclude = "pages/$pagePath.inc.php";
//Page not found
if (!file_exists($pageInclude)) {
    $pagePath = '404';
    $pageInclude = "pages/$pagePath.inc.php";
}

$pageTitle = str_replace('/', ' ', $pagePath);
$pageTitle = ucfirst($pageTitle);

require_once 'pages/head.inc.php';

if ($isPanelPage) {
    echo '<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">';
} else {
    echo '<body class="d-flex flex-column min-vh-100">';
}

// Incluir header
if ($pagePath !== 'facturar-login' && $pagePath !== 'facturar-invitado' && $pagePath !== 'admin-login') {
    require_once 'pages/header.inc.php';
}

require_once $pageInclude;
require_once 'pages/script.inc.php';
require_once 'pages/footer.inc.php';
ob_end_flush();