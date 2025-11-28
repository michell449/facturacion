<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/sello-utils.php';

header('Content-Type: application/json; charset=utf-8');

$respuesta = [
    'success' => false,
    'message' => 'Error desconocido.',
    'clave_descifrada' => null
];

try {
    $id_usuario = null;
    if (isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id'])) {
        $id_usuario = (int)$_SESSION['usuario_id'];
    } elseif (isset($_SESSION['USR_ID']) && !empty($_SESSION['USR_ID'])) {
        $id_usuario = (int)$_SESSION['USR_ID'];
    }
    
    if (!$id_usuario) {
        throw new Exception('Sesión de usuario no válida.');
    }
    if (!isset($_GET['id_empresa']) || empty($_GET['id_empresa'])) {
        throw new Exception('ID de empresa no proporcionado.');
    }
    
    $id_empresa = (int)$_GET['id_empresa'];
    $db = new Database();
    $conn = $db->getConnection();
    
    $stmt_verify = $conn->prepare("SELECT id_empresa FROM empresas WHERE id_empresa = ? AND id_usuario = ?");
    $stmt_verify->execute([$id_empresa, $id_usuario]);
    if (!$stmt_verify->fetch()) {
        throw new Exception('No tienes permisos para acceder a esta empresa.');
    }
    
    $stmt = $conn->prepare("SELECT clave FROM empresas WHERE id_empresa = ? AND id_usuario = ?");
    $stmt->execute([$id_empresa, $id_usuario]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result && $result['clave']) {
        $claveDescifrada = SelloUtils::descifrarClave($result['clave'], $id_empresa);
        
        if ($claveDescifrada !== false) {
            $respuesta['success'] = true;
            $respuesta['message'] = 'Clave descifrada correctamente.';
            $respuesta['clave_descifrada'] = $claveDescifrada;
        } else {
            throw new Exception('Error al descifrar la clave privada.');
        }
    } else {
        throw new Exception('No hay clave privada configurada para esta empresa.');
    }
    
} catch (Exception $e) {
    $respuesta['message'] = $e->getMessage();
    error_log("Error en obtener-clave-sello.php: " . $e->getMessage());
}

echo json_encode($respuesta);
?>