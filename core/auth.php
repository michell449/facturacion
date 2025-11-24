<?php

/**
 * Comprueba si el usuario actual es un Administrador.
 */
function is_admin(): bool {
    return (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');
}

/**
 * Comprueba si el usuario actual es un Cliente (registrado).
 */
function is_cliente(): bool {
    $isRegisteredClient = (
        isset($_SESSION['loggedin']) 
        && $_SESSION['loggedin'] === true 
        && isset($_SESSION['tipo_usuario']) 
        && $_SESSION['tipo_usuario'] === 'cliente'
    );
    return $isRegisteredClient;
}

/**
 * Comprueba si el usuario es un invitado.
 */
function is_guest(): bool {
    return (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true);
}

/**
 * Función de protección de rutas basada en roles.
 * @param array $allowedRoles Array de roles permitidos (ej. ['admin', 'cliente']).
 */
function require_roles(array $allowedRoles) {
    // Verificar expiración de sesión
    check_session_expiry();
    
    if (!in_array('guest', $allowedRoles) && is_guest()) {
        header('Location: index.php?pg=facturar-login'); 
        exit();
    }
    
    if (is_guest()) {
        if (!in_array('guest', $allowedRoles)) {
            header('Location: index.php?pg=facturar-login'); 
            exit();
        }
        return;
    }

    $currentUserRole = isset($_SESSION['tipo_usuario']) ? $_SESSION['tipo_usuario'] : '';
    
    if (!in_array($currentUserRole, $allowedRoles)) {
        $redirectPage = is_admin() ? 'inicio-admin' : 'inicio';
        
        if ($currentUserRole === 'cliente' && $redirectPage === 'inicio-admin') {
            $redirectPage = 'inicio';
        }
        
        header("Location: index.php?pg=$redirectPage");
        exit();
    }
}

/**
 * Verificar si la sesión ha expirado (1 hora de inactividad)
 */
function check_session_expiry() {
    $timeout_duration = 3600; // 1 hora en segundos
    
    if (isset($_SESSION['last_access'])) {
        if ((time() - $_SESSION['last_access']) > $timeout_duration) {
            $_SESSION = array();
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
            session_destroy();
            header('Location: index.php?pg=facturar-login&expired=1');
            exit();
        }
    }
    
    $_SESSION['last_access'] = time();
}

/**
 * Verificar si el usuario está autenticado
 */
function is_authenticated(): bool {
    return (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['usuario_id']));
}

/**
 * Obtener información del usuario autenticado actual
 */
function get_authenticated_user(): array {
    if (!is_authenticated()) {
        return [];
    }
    
    return [
        'id' => $_SESSION['usuario_id'] ?? null,
        'email' => $_SESSION['correo'] ?? null,
        'tipo' => $_SESSION['tipo_usuario'] ?? null,
        'tipo_cliente' => $_SESSION['tipo_cliente'] ?? null
    ];
}

?>