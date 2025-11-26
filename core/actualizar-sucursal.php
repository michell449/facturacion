<?php

session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';

header('Content-Type: application/json; charset=utf-8');

$respuesta = [
    'success' => false,
    'message' => 'Error desconocido.'
];

try {

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
        throw new Exception('Datos JSON inválidos: ' . json_last_error_msg());
    }

    if (!isset($data['id_empresa']) || empty($data['id_empresa'])) {
        throw new Exception('ID de sucursal a actualizar no proporcionado.');
    }
    $id_empresa = (int)$data['id_empresa'];
    
    $required_fields = ['rfc_fiscal', 'razon_social', 'regimen_fiscal', 'codigo_sucursal', 'codigo_postal', 'calle', 'numero_exterior', 'colonia'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            throw new Exception('Falta el campo requerido: ' . $field);
        }
    }
    
    $rfc          = mb_strtoupper(trim($data['rfc_fiscal'] ?? ''));
    $razon_social = trim($data['razon_social'] ?? '');
    $reg_fiscal   = trim($data['regimen_fiscal'] ?? '');
    $codigo_suc   = trim($data['codigo_sucursal'] ?? '');
    $cp           = (int)trim($data['codigo_postal'] ?? '0');
    $calle        = trim($data['calle'] ?? '');
    $num_ext      = trim($data['numero_exterior'] ?? '');
    $num_int      = empty($data['numero_interior']) ? null : trim($data['numero_interior']);
    $colonia      = trim($data['colonia'] ?? '');
    $estatus      = trim($data['estatus'] ?? '1'); 

    $estatus_lower = strtolower($estatus);
    if (in_array($estatus_lower, ['activo', 'activa', '1', 'true']) || $estatus === 1) {
        $estatus = 1;
    } elseif (in_array($estatus_lower, ['inactivo', 'inactiva', '0', 'false']) || $estatus === 0) {
        $estatus = 0;
    } else {
        $estatus = 1; 
    }

    $db = new Database();
    $conn = $db->getConnection();
    
    $stmt_check_duplicidad = $conn->prepare("SELECT id_empresa FROM empresas WHERE id_usuario = ? AND codigo_suc = ? AND id_empresa != ? LIMIT 1");
    $stmt_check_duplicidad->execute([$id_usuario, $codigo_suc, $id_empresa]);
    if ($stmt_check_duplicidad->fetch(PDO::FETCH_ASSOC)) {
        throw new Exception("Ya existe otra sucursal con el código: " . $codigo_suc);
    }
    
    $sql = "UPDATE empresas SET 
                rfc = ?, 
                razon_social = ?, 
                reg_fiscal = ?, 
                codigo_suc = ?, 
                cp = ?, 
                calle = ?, 
                num_ext = ?, 
                num_int = ?, 
                colonia = ?, 
                estatus = ?
            WHERE id_empresa = ? AND id_usuario = ?";
    
    $params = [
        $rfc, $razon_social, $reg_fiscal, $codigo_suc, $cp, $calle, $num_ext, $num_int, $colonia, $estatus,
        $id_empresa, $id_usuario
    ];

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    if ($stmt->rowCount() > 0 || $stmt->errorCode() === '00000') {
        $respuesta['success'] = true;
        $respuesta['message'] = 'Sucursal actualizada correctamente.';
    } else {
        throw new Exception('No se pudo actualizar la información. La sucursal puede no existir o no hubo cambios.');
    }

} catch (Exception $e) {
    $respuesta['message'] = $e->getMessage();
}

echo json_encode($respuesta);
?>