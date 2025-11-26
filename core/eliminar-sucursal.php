<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/crud.php';

header('Content-Type: application/json; charset=utf-8');

$respuesta = [
    'success' => false,
    'message' => 'Error desconocido.'
];

try {
    // 1. Obtener ID de usuario de la sesión
    $id_usuario = null;
    if (isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id'])) {
        $id_usuario = (int)$_SESSION['usuario_id'];
    } elseif (isset($_SESSION['USR_ID']) && !empty($_SESSION['USR_ID'])) {
        $id_usuario = (int)$_SESSION['USR_ID'];
    }
    
    if (!$id_usuario) {
        throw new Exception('Sesión de usuario no válida. ID de usuario no encontrado en la sesión.');
    }

    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Datos JSON inválidos.');
    }

    if (!isset($data['id_empresa']) || empty($data['id_empresa'])) {
        throw new Exception('ID de la sucursal a eliminar no proporcionado.');
    }
    $id_empresa = (int)$data['id_empresa'];

    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("DELETE FROM empresas WHERE id_empresa = ? AND id_usuario = ?");
    $stmt->execute([$id_empresa, $id_usuario]);

    if ($stmt->rowCount() > 0) {
        $respuesta['success'] = true;
        $respuesta['message'] = 'Sucursal eliminada correctamente.';
    } else {
        throw new Exception('La sucursal no existe o no se pudo localizar.');
    }

} catch (Exception $e) {
    $respuesta['message'] = $e->getMessage();
}

echo json_encode($respuesta);
?>