<?php
//core/verificar-sesion.php
//Verificar si el usuario tiene una sesión activa

session_start();

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    echo json_encode([
        'success' => true,
        'loggedin' => true,
        'usuario_id' => $_SESSION['usuario_id'],
        'correo' => $_SESSION['correo'],
        'tipo_usuario' => $_SESSION['tipo_usuario']
    ]);
} else {
    echo json_encode([
        'success' => false,
        'loggedin' => false,
        'message' => 'No hay sesión activa'
    ]);
}
?>