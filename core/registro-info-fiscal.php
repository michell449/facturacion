<?php

require_once __DIR__ . '/autoload-vendor.php';
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');

$respuesta = [
    'success' => false,
    'message' => 'Error desconocido.'
];

try {
    if (!isset($_SESSION['usuario_id']) || empty($_SESSION['usuario_id'])) {
        throw new Exception('Sesión de usuario no válida. ID de usuario no encontrado en la sesión.');
    }
    $id_usuario = (int)$_SESSION['usuario_id'];


    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Datos JSON inválidos: ' . json_last_error_msg());
    }

    $required_fields = ['rfc_fiscal', 'nombre_fiscal', 'regimen_fiscal', 'cp_fiscal', 'calle', 'numero_exterior', 'colonia'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            throw new Exception('Falta el campo requerido: ' . $field);
        }
    }

    $rfc          = mb_strtoupper(trim($data['rfc_fiscal'] ?? ''));
    $razon_social = trim($data['nombre_fiscal'] ?? '');
    $reg_fiscal   = trim($data['regimen_fiscal'] ?? ''); 
    $cp           = (int)trim($data['cp_fiscal'] ?? '0');
    $calle        = trim($data['calle'] ?? '');
    $num_ext      = trim($data['numero_exterior'] ?? '');
    $num_int      = empty($data['numero_interior']) ? null : trim($data['numero_interior']); 
    $colonia      = trim($data['colonia'] ?? '');

    // determinar tipo de persona 
    $rfc_length = strlen($rfc);
    if ($rfc_length === 13) {
        $tipo_pers = 'Fisica';
    } elseif ($rfc_length === 12) {
        $tipo_pers = 'Moral';
    } else {
        throw new Exception('La longitud del RFC (' . $rfc_length . ') no es válida (debe ser 12 o 13 caracteres).');
    }

    $db = new Database();
    $conn = $db->getConnection();

    $stmt_check = $conn->prepare("SELECT id_df FROM datos_fiscales_usuario WHERE id_usuario = ? LIMIT 1");
    $stmt_check->execute([$id_usuario]);
    $existing_record = $stmt_check->fetch(PDO::FETCH_ASSOC);

    $sql_fields = 'rfc = ?, razon_social = ?, reg_fiscal = ?, cp = ?, tipo_pers = ?, calle = ?, num_ext = ?, num_int = ?, col = ?';
    $params = [$rfc, $razon_social, $reg_fiscal, $cp, $tipo_pers, $calle, $num_ext, $num_int, $colonia];

    if ($existing_record) {
        $sql = "UPDATE datos_fiscales_usuario SET $sql_fields WHERE id_usuario = ?";
        $params[] = $id_usuario;
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $action = 'actualizados';
    } else {
        $sql_fields_insert = 'id_usuario, ' . str_replace(' = ?', '', $sql_fields);
        $placeholders = array_fill(0, count($params) + 1, '?');
        $placeholders_str = implode(', ', $placeholders);

        $sql = "INSERT INTO datos_fiscales_usuario ($sql_fields_insert) VALUES ($placeholders_str)";
        array_unshift($params, $id_usuario); 
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $action = 'registrados';
    }

    $respuesta['success'] = true;
    $respuesta['message'] = 'Datos fiscales ' . $action . ' correctamente.';

} catch (Exception $e) {
    $respuesta['message'] = $e->getMessage();
}

echo json_encode($respuesta);
?>