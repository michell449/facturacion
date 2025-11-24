<?php
//test-access-control.php
//Archivo de prueba para verificar el sistema de control de acceso

// Este archivo se puede usar para hacer pruebas del sistema de autenticación
// NO INCLUIR EN PRODUCCIÓN

session_start();

echo "<h1>Sistema de Control de Acceso - Pruebas</h1>";

echo "<h2>Estado Actual de Sesión:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

require_once 'core/auth.php';

echo "<h2>Funciones de Autenticación:</h2>";
echo "<p>is_guest(): " . (is_guest() ? 'true' : 'false') . "</p>";
echo "<p>is_cliente(): " . (is_cliente() ? 'true' : 'false') . "</p>";
echo "<p>is_admin(): " . (is_admin() ? 'true' : 'false') . "</p>";
echo "<p>is_authenticated(): " . (is_authenticated() ? 'true' : 'false') . "</p>";

if (is_authenticated()) {
    $user = get_authenticated_user();
    echo "<h2>Información del Usuario:</h2>";
    echo "<pre>";
    print_r($user);
    echo "</pre>";
}

echo "<h2>Pruebas de Acceso:</h2>";

$test_pages = [
    'facturar-login' => ['guest'],
    'inicio' => ['cliente'],
    'inicio-admin' => ['admin'],
    'facturar' => ['cliente'],
    'gestion-sucursales' => ['admin']
];

foreach ($test_pages as $page => $required_roles) {
    echo "<p>Página '$page' (requiere: " . implode(', ', $required_roles) . "): ";
    
    try {
        // Simular verificación de acceso sin redirección
        $hasAccess = false;
        
        if (in_array('guest', $required_roles) && is_guest()) {
            $hasAccess = true;
        } else if (is_authenticated()) {
            $currentRole = $_SESSION['tipo_usuario'] ?? '';
            if (in_array($currentRole, $required_roles)) {
                $hasAccess = true;
            }
        }
        
        echo $hasAccess ? "<span style='color: green;'>PERMITIDO</span>" : "<span style='color: red;'>DENEGADO</span>";
    } catch (Exception $e) {
        echo "<span style='color: red;'>ERROR: " . $e->getMessage() . "</span>";
    }
    echo "</p>";
}

echo "<h2>Acciones de Prueba:</h2>";
echo "<p><a href='core/logout.php' onclick='return confirm(\"¿Cerrar sesión?\")'>Cerrar Sesión (GET - para prueba)</a></p>";
echo "<p><a href='index.php?pg=facturar-login'>Ir a Login</a></p>";
echo "<p><a href='index.php?pg=inicio'>Ir a Inicio Cliente</a></p>";
echo "<p><a href='index.php?pg=inicio-admin'>Ir a Inicio Admin</a></p>";

?>
<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h1, h2 { color: #333; }
pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
p { margin: 5px 0; }
</style>