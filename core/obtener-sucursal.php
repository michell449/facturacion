<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';

header('Content-Type: application/json; charset=utf-8');

$respuesta = [
    'success' => false,
    'data' => null,
    'message' => 'Error desconocido.'
];

try {
    // Verificar sesión
    $id_usuario = null;
    if (isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id'])) {
        $id_usuario = (int)$_SESSION['usuario_id'];
    } elseif (isset($_SESSION['USR_ID']) && !empty($_SESSION['USR_ID'])) {
        $id_usuario = (int)$_SESSION['USR_ID'];
    }
    
    if (!$id_usuario) {
        throw new Exception('Sesión de usuario no válida. ID de usuario no encontrado en la sesión.');
    }
    
    // Verificar que se reciba el ID de la sucursal
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        throw new Exception('ID de sucursal no proporcionado');
    }
    
    $id_empresa = (int)$_GET['id'];
    
    // Crear conexión
    $db = new Database();
    $conn = $db->getConnection();
    
    // Consultar datos de la sucursal específica
    $sql = "SELECT * FROM empresas WHERE id_empresa = ? AND id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_empresa, $id_usuario]);
    $sucursal = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$sucursal) {
        throw new Exception('Sucursal no encontrada o no tienes permisos para editarla');
    }
    
    // Respuesta exitosa
    $respuesta['success'] = true;
    $respuesta['data'] = $sucursal;
    $respuesta['message'] = 'Datos de sucursal obtenidos correctamente';
    
} catch (Exception $e) {
    $respuesta['message'] = $e->getMessage();
}

echo json_encode($respuesta);
?>