<?php

session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/sello-utils.php';

header('Content-Type: application/json; charset=utf-8');

$respuesta = [
    'success' => false,
    'message' => 'Error desconocido.'
];

function manejarSubidaLogo($file, $id_empresa) {
    $upload_dir = __DIR__ . '/../uploads/logos/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // validar tipo de archivo
    $allowed_types = ['image/png', 'image/jpeg', 'image/jpg', 'image/svg+xml'];
    if (!in_array($file['type'], $allowed_types)) {
        throw new Exception('Tipo de archivo no válido para el logo');
    }
    
    if ($file['size'] > 2 * 1024 * 1024) {
        throw new Exception('El logo no puede ser mayor a 2MB');
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'logo_' . $id_empresa . '_' . time() . '.' . $extension;
    $filepath = $upload_dir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return $filename;
    } else {
        throw new Exception('Error al subir el logo');
    }
}

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

    $content_type = $_SERVER['CONTENT_TYPE'] ?? '';

    $db = new Database();
    $conn = $db->getConnection();
    
    if (strpos($content_type, 'multipart/form-data') !== false) {
        $data = $_POST;
    } else {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Datos JSON inválidos: ' . json_last_error_msg());
        }
    }

    if (!isset($data['id_empresa']) || empty($data['id_empresa'])) {
        throw new Exception('ID de sucursal a actualizar no proporcionado.');
    }
    $id_empresa = (int)$data['id_empresa'];
    
    $required_fields = ['rfc_fiscal', 'razon_social', 'nombre_comercial', 'regimen_fiscal', 'codigo_sucursal', 'codigo_postal', 'direccion', 'colonia', 'email'];
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
    $clave_privada = isset($data['clave_privada']) && !empty(trim($data['clave_privada'])) ? trim($data['clave_privada']) : null;
    $logo_filename = null;
    $eliminar_logo = isset($_POST['eliminar_logo']) && $_POST['eliminar_logo'] === 'true';
    
    // VALIDACIÓN DE CERTIFICADOS DIGITALES
    // Si se proporciona un nuevo certificado, validar que el RFC coincida
    if (isset($_FILES['file_cer']) && $_FILES['file_cer']['error'] === UPLOAD_ERR_OK) {
        $rutaCerTemporal = $_FILES['file_cer']['tmp_name'];
        $validacionCert = SelloUtils::validarCertificado($rutaCerTemporal, $rfc);
        
        if (!$validacionCert) {
            throw new Exception('El certificado (.cer) no coincide con el RFC proporcionado. Verifique que esté subiendo el certificado correcto.');
        }
    }
    
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $logo_filename = manejarSubidaLogo($_FILES['logo'], $id_empresa);
    } elseif ($eliminar_logo) {
        $stmt_logo = $conn->prepare("SELECT logo FROM empresas WHERE id_empresa = ? AND id_usuario = ?");
        $stmt_logo->execute([$id_empresa, $id_usuario]);
        $logo_actual = $stmt_logo->fetchColumn();
        
        if ($logo_actual) {
            $ruta_logo = __DIR__ . '/../uploads/logos/' . $logo_actual;
            if (file_exists($ruta_logo)) {
                unlink($ruta_logo);
            }
        }
        
        $logo_filename = ''; 
    } 

    $estatus_lower = strtolower($estatus);
    if (in_array($estatus_lower, ['activo', 'activa', '1', 'true']) || $estatus === 1) {
        $estatus = 1;
    } elseif (in_array($estatus_lower, ['inactivo', 'inactiva', '0', 'false']) || $estatus === 0) {
        $estatus = 0;
    } else {
        $estatus = 1; 
    }

    $stmt_check_duplicidad = $conn->prepare("SELECT id_empresa FROM empresas WHERE id_usuario = ? AND codigo_suc = ? AND id_empresa != ? LIMIT 1");
    $stmt_check_duplicidad->execute([$id_usuario, $codigo_suc, $id_empresa]);
    if ($stmt_check_duplicidad->fetch(PDO::FETCH_ASSOC)) {
        throw new Exception("Ya existe otra sucursal con el código: " . $codigo_suc);
    }
    
    if ($logo_filename !== null) {
        $sql = "UPDATE empresas SET 
                    rfc = ?, 
                    razon_social = ?, 
                    nombre = ?, 
                    reg_fiscal = ?, 
                    codigo_suc = ?, 
                    cp = ?, 
                    direccion = ?, 
                    colonia = ?, 
                    estatus = ?,
                    correo = ?,
                    logo = ?
                WHERE id_empresa = ? AND id_usuario = ?";
        
        $logo_value = $logo_filename === '' ? null : $logo_filename;
        $params = [
            $rfc, $razon_social, $nombre_comercial, $reg_fiscal, $codigo_suc, $cp, $direccion, $colonia, $estatus, $email, $logo_value,
            $id_empresa, $id_usuario
        ];
    } else {
        $sql = "UPDATE empresas SET 
                    rfc = ?, 
                    razon_social = ?, 
                    nombre = ?, 
                    reg_fiscal = ?, 
                    codigo_suc = ?, 
                    cp = ?, 
                    direccion = ?, 
                    colonia = ?, 
                    estatus = ?,
                    correo = ?
                WHERE id_empresa = ? AND id_usuario = ?";
        
        $params = [
            $rfc, $razon_social, $nombre_comercial, $reg_fiscal, $codigo_suc, $cp, $direccion, $colonia, $estatus, $email,
            $id_empresa, $id_usuario
        ];
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    if ($stmt->rowCount() > 0 || $stmt->errorCode() === '00000') {

        if ($clave_privada !== null) {
            $clave_cifrada = SelloUtils::cifrarClave($clave_privada, $id_empresa);
            $stmt_clave = $conn->prepare("UPDATE empresas SET clave = ? WHERE id_empresa = ? AND id_usuario = ?");
            $stmt_clave->execute([$clave_cifrada, $id_empresa, $id_usuario]);
        }
        
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