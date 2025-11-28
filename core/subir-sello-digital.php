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

    if (!isset($_FILES['certificado']) || !isset($_FILES['llave_privada'])) {
        throw new Exception('Faltan los archivos del sello digital. Se requiere certificado (.cer) y llave privada (.key).');
    }

    if (!isset($_POST['clave_privada']) || empty(trim($_POST['clave_privada']))) {
        throw new Exception('La contraseña de la llave privada es requerida.');
    }

    if (!isset($_POST['id_empresa']) || empty($_POST['id_empresa'])) {
        throw new Exception('ID de sucursal no proporcionado.');
    }
    
    $id_empresa = (int)$_POST['id_empresa'];
    
    $archivoCer = $_FILES['certificado'];
    $archivoKey = $_FILES['llave_privada'];
    
    if ($archivoCer['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error al subir el archivo de certificado: ' . $archivoCer['error']);
    }
    
    if ($archivoKey['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error al subir el archivo de llave privada: ' . $archivoKey['error']);
    }
    
    $extensionCer = strtolower(pathinfo($archivoCer['name'], PATHINFO_EXTENSION));
    $extensionKey = strtolower(pathinfo($archivoKey['name'], PATHINFO_EXTENSION));
    
    if ($extensionCer !== 'cer') {
        throw new Exception('El archivo de certificado debe tener extensión .cer');
    }
    
    if ($extensionKey !== 'key') {
        throw new Exception('El archivo de llave privada debe tener extensión .key');
    }
    
    // Validar tamaños
    $maxSize = 1024 * 1024; 
    
    if ($archivoCer['size'] > $maxSize) {
        throw new Exception('El archivo de certificado es muy grande. Máximo permitido: 1MB');
    }
    
    if ($archivoKey['size'] > $maxSize) {
        throw new Exception('El archivo de llave privada es muy grande. Máximo permitido: 1MB');
    }
    
    // Conectar a la base de datos primero
    $db = new Database();
    $conn = $db->getConnection();
    
    // Verificar que la sucursal pertenece al usuario y obtener el código
    $stmt_verify = $conn->prepare("SELECT id_empresa, codigo_suc FROM empresas WHERE id_empresa = ? AND id_usuario = ?");
    $stmt_verify->execute([$id_empresa, $id_usuario]);
    $sucursal = $stmt_verify->fetch(PDO::FETCH_ASSOC);
    if (!$sucursal) {
        throw new Exception('No tienes permisos para modificar esta sucursal');
    }
    
    $codigoSucursal = $sucursal['codigo_suc'] ?: 'sucursal_' . $id_empresa;
    
    // Obtener y cifrar la clave privada
    $clavePrivada = trim($_POST['clave_privada']);
    $claveParaGuardar = SelloUtils::cifrarClave($clavePrivada, $id_empresa);
    
    // Crear la estructura de carpetas
    $uploadBaseDir = __DIR__ . '/../uploads/sellos/';
    $uploadDir = $uploadBaseDir . $codigoSucursal . '/';
    
    // Crear la carpeta específica para la sucursal si no existe
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $nombreCer = 'certificado.cer';
    $nombreKey = 'llave_privada.key';
    
    $rutaCer = $uploadDir . $nombreCer;
    $rutaKey = $uploadDir . $nombreKey;
    
    // Guardar la ruta relativa en la base de datos
    $rutaRelativaCer = $codigoSucursal . '/' . $nombreCer;
    $rutaRelativaKey = $codigoSucursal . '/' . $nombreKey;
    
    if (!move_uploaded_file($archivoCer['tmp_name'], $rutaCer)) {
        throw new Exception('Error al guardar el archivo de certificado');
    }
    
    if (!move_uploaded_file($archivoKey['tmp_name'], $rutaKey)) {
        unlink($rutaCer);
        throw new Exception('Error al guardar el archivo de llave privada');
    }
    
    $stmt_old = $conn->prepare("SELECT file_cer, file_key FROM empresas WHERE id_empresa = ?");
    $stmt_old->execute([$id_empresa]);
    $oldFiles = $stmt_old->fetch(PDO::FETCH_ASSOC);
    
    $sql = "UPDATE empresas SET file_cer = ?, file_key = ?, clave = ? WHERE id_empresa = ? AND id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$rutaRelativaCer, $rutaRelativaKey, $claveParaGuardar, $id_empresa, $id_usuario]);
    
    if ($stmt->rowCount() > 0) {
        // Limpiar archivos antiguos si existen
        if ($oldFiles && $oldFiles['file_cer']) {
            $oldCerPath = $uploadBaseDir . $oldFiles['file_cer'];
            if (file_exists($oldCerPath)) {
                unlink($oldCerPath);
            }
        }
        
        if ($oldFiles && $oldFiles['file_key']) {
            $oldKeyPath = $uploadBaseDir . $oldFiles['file_key'];
            if (file_exists($oldKeyPath)) {
                unlink($oldKeyPath);
            }
        }
        
        $respuesta['success'] = true;
        $respuesta['message'] = 'Sello digital configurado correctamente';
        $respuesta['archivos'] = [
            'certificado' => $rutaRelativaCer,
            'llave_privada' => $rutaRelativaKey
        ];
        $respuesta['carpeta'] = $codigoSucursal;
    } else {
        unlink($rutaCer);
        unlink($rutaKey);
        throw new Exception('No se pudo actualizar el sello digital en la base de datos');
    }
    
} catch (Exception $e) {
    $respuesta['message'] = $e->getMessage();
}

echo json_encode($respuesta);
?>