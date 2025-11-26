<?php
// Funciones auxiliares para navegación y redirección

/**
 * Redirección segura que previene bucles infinitos
 */
function safe_redirect($url, $max_redirects = 3) {
    // Inicializar contador si no existe
    if (!isset($_SESSION['redirect_count'])) {
        $_SESSION['redirect_count'] = 0;
    }
    
    // Incrementar contador
    $_SESSION['redirect_count']++;
    
    // Verificar si excede el máximo
    if ($_SESSION['redirect_count'] > $max_redirects) {
        error_log("Demasiadas redirecciones detectadas. URL: $url");
        unset($_SESSION['redirect_count']);
        
        // Redireccionar a 404 en lugar de crear bucle
        header("Location: index.php?pg=404");
        exit();
    }
    
    // Realizar redirección
    header("Location: $url");
    exit();
}

/**
 * Resetear contador de redirecciones cuando se llega a una página válida
 */
function reset_redirect_count() {
    unset($_SESSION['redirect_count']);
}

/**
 * Obtener URL de panel principal según el rol del usuario
 */
function get_panel_url() {
    if (function_exists('is_admin') && is_admin()) {
        return 'panel?pg=inicio-admin';
    } elseif (function_exists('is_cliente') && is_cliente()) {
        return 'panel?pg=inicio';
    }
    return 'index.php';
}

/**
 * Verificar si una página existe y el usuario tiene permisos
 */
function page_exists_and_accessible($page, $accessMap) {
    // Verificar si la página existe físicamente
    $pageFile = "pages/$page.inc.php";
    if (!file_exists($pageFile)) {
        return false;
    }
    
    // Verificar permisos si hay mapa de acceso
    if (isset($accessMap[$page])) {
        $required_roles = $accessMap[$page];
        
        // Verificar si el usuario tiene al menos uno de los roles requeridos
        foreach ($required_roles as $role) {
            if ($role === 'guest' && !is_authenticated()) {
                return true;
            }
            if ($role === 'cliente' && is_cliente()) {
                return true;
            }
            if ($role === 'admin' && is_admin()) {
                return true;
            }
        }
        return false;
    }
    
    return true;
}

/**
 * Logging mejorado para debugging
 */
function debug_log($message, $context = []) {
    $timestamp = date('Y-m-d H:i:s');
    $contextStr = !empty($context) ? ' - Context: ' . json_encode($context) : '';
    error_log("[$timestamp] FACTURACION DEBUG: $message$contextStr");
}
?>