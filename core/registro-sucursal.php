<?php

require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/../config.php'; 

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
    
    $required_fields = ['rfc_fiscal', 'razon_social','nombre_comercial', 'regimen_fiscal', 'codigo_sucursal', 'codigo_postal', 'direccion', 'colonia', 'estatus', 'email'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            throw new Exception('Falta el campo requerido: ' . $field);
        }
    }
    
    $rfc          = mb_strtoupper(trim($data['rfc_fiscal'] ?? ''));
    $razon_social = trim($data['razon_social'] ?? '');
    $nombre_comercial = trim($data['nombre_comercial'] ?? '');
    $reg_fiscal   = trim($data['regimen_fiscal'] ?? '');
    $codigo_suc   = trim($data['codigo_sucursal'] ?? '');
    $cp           = (int)trim($data['codigo_postal'] ?? '0');
    $direccion    = trim($data['direccion'] ?? '');
    $colonia      = trim($data['colonia'] ?? '');
    $estatus      = trim($data['estatus'] ?? '1'); 
    $email        = trim($data['email'] ?? '');
    $logo         = trim($data['logo'] ?? '');

    if (strlen($rfc) < 12 || strlen($rfc) > 13) {
        throw new Exception('RFC debe tener entre 12 y 13 caracteres');
    }
    if (strlen($codigo_suc) > 10) {
        throw new Exception('Código de sucursal no puede exceder 10 caracteres');
    }


    $estatus_lower = strtolower($estatus);
    if (in_array($estatus_lower, ['activo', 'activa', '1', 'true']) || $estatus === 1) {
        $estatus = 1;
    } elseif (in_array($estatus_lower, ['inactivo', 'inactiva', '0', 'false']) || $estatus === 0) {
        $estatus = 0;
    } else {
        throw new Exception('El estatus proporcionado no es válido. Valores aceptados: activo/inactivo');
    }

    $db = new Database();
    $conn = $db->getConnection();

    $stmt_check_duplicidad = $conn->prepare("SELECT id_empresa FROM empresas WHERE id_usuario = ? AND codigo_suc = ? LIMIT 1");
    $stmt_check_duplicidad->execute([$id_usuario, $codigo_suc]);
    if ($stmt_check_duplicidad->fetch(PDO::FETCH_ASSOC)) {
        throw new Exception("Ya existe una sucursal registrada con el código de sucursal: " . $codigo_suc . ".Use otro código.");
    }
    
    $csf = isset($data['csf']) && !empty($data['csf']) ? trim($data['csf']) : null;
    $sello = isset($data['sello']) && !empty($data['sello']) ? trim($data['sello']) : null;

    $fields = ['rfc', 'razon_social', 'nombre', 'reg_fiscal', 'codigo_suc', 'cp', 'direccion', 'colonia', 'estatus', 'correo', 'logo'];
    $values = [$rfc, $razon_social, $nombre_comercial, $reg_fiscal, $codigo_suc, $cp, $direccion, $colonia, $estatus, $email, $logo];
    
    if ($sello !== null) {
        $fields[] = 'sello';
        $values[] = $sello;
    }
    
    $field_names = 'id_usuario, ' . implode(', ', $fields);
    $placeholders = str_repeat('?, ', count($values) + 1); 
    $placeholders = rtrim($placeholders, ', ');
    
    $stmt_insert = $conn->prepare("INSERT INTO empresas ($field_names) VALUES ($placeholders)");
    array_unshift($values, $id_usuario);
    
    if ($stmt_insert->execute($values)) {
        $respuesta['message'] = 'Nueva sucursal registrada correctamente.';
        $respuesta['id_empresa'] = $conn->lastInsertId();
        $respuesta['success'] = true;
    } else {
        throw new Exception('Error al registrar la nueva sucursal: ' . implode(', ', $stmt_insert->errorInfo()));
    }

    echo json_encode($respuesta);

} catch (Exception $e) {
    $respuesta['message'] = $e->getMessage();
    echo json_encode($respuesta);
    exit;
}