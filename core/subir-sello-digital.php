<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/sello-utils.php';
require_once __DIR__ . '/autoload-vendor.php';

use PhpCfdi\Credentials\Credential;
use PhpCfdi\Credentials\Certificate;
use PhpCfdi\Credentials\PrivateKey;
use PhpCfdi\Credentials\Exception\InvalidPrivateKeyException;
use PhpCfdi\Credentials\Exception\InvalidCertificateException;

header('Content-Type: application/json; charset=utf-8');

$respuesta = [
    'success' => false,
    'message' => 'Error desconocido.'
];

try {
    // 1. Verificaciones de Sesión y Archivos
    $id_usuario = isset($_SESSION['usuario_id']) ? (int)$_SESSION['usuario_id'] : (isset($_SESSION['USR_ID']) ? (int)$_SESSION['USR_ID'] : null);
    
    if (!$id_usuario) {
        throw new Exception('Sesión de usuario no válida.');
    }

    if (!isset($_FILES['certificado']) || !isset($_FILES['llave_privada'])) {
        throw new Exception('Faltan los archivos del sello digital (.cer y .key).');
    }

    if (empty(trim($_POST['clave_privada']))) {
        throw new Exception('La contraseña de la llave privada es requerida.');
    }

    if (empty($_POST['id_empresa'])) {
        throw new Exception('ID de sucursal no proporcionado.');
    }
    
    $id_empresa = (int)$_POST['id_empresa'];
    $archivoCer = $_FILES['certificado'];
    $archivoKey = $_FILES['llave_privada'];
    $clavePrivadaTexto = trim($_POST['clave_privada']);

    // Validaciones rápidas de tipo y tamaño
    $maxSize = 5 * 1024 * 1024; // 5MB por archivo
    $extCer = strtolower(pathinfo($archivoCer['name'] ?? '', PATHINFO_EXTENSION));
    $extKey = strtolower(pathinfo($archivoKey['name'] ?? '', PATHINFO_EXTENSION));

    if (!in_array($extCer, ['cer'])) {
        throw new Exception('El archivo del certificado debe ser .cer');
    }
    if (!in_array($extKey, ['key'])) {
        throw new Exception('El archivo de la llave privada debe ser .key');
    }
    if ($archivoCer['size'] > $maxSize || $archivoKey['size'] > $maxSize) {
        throw new Exception('Los archivos .cer y .key no deben superar los 5MB.');
    }

    // Validar errores de subida básicos
    if ($archivoCer['error'] !== UPLOAD_ERR_OK || $archivoKey['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error al subir los archivos al servidor.');
    }

    // ---------------------------------------------------------
    // 2. VALIDACIÓN Y CONVERSIÓN CON PHPCFDI/CREDENTIALS
    // ---------------------------------------------------------
    try {
        // Leemos el contenido de los archivos temporales
        $contenidoCer = file_get_contents($archivoCer['tmp_name']);
        $contenidoKey = file_get_contents($archivoKey['tmp_name']);

        if ($contenidoCer === false || $contenidoKey === false) {
            throw new Exception('No se pudo leer el contenido de los archivos.');
        }

        // Crear objetos Certificate y PrivateKey
        // Certificate detecta automáticamente el formato
        $certificate = new Certificate($contenidoCer);
        
        // PrivateKey intenta abrir la llave. Si es DER (binaria) y la contraseña es correcta, 
        // la convierte internamente para poder usarla.
        $privateKey = new PrivateKey($contenidoKey, $clavePrivadaTexto);

        // Crear credencial combinando certificado y llave privada para verificar paridad
        $credential = new Credential($certificate, $privateKey);

        // VALIDACIÓN OBLIGATORIA: Debe ser un CSD y NO una FIEL/e.firma
        if (!$credential->isCsd()) {
            throw new Exception('El certificado proporcionado es una FIEL o e.firma. Debes subir un CSD (Certificado de Sello Digital).');
        }

    } catch (\RuntimeException $e) {
        // Errores de contraseña incorrecta o archivos inválidos específicos de la librería
        $errorMsg = $e->getMessage();
        if (stripos($errorMsg, 'password') !== false || stripos($errorMsg, 'passphrase') !== false) {
            throw new Exception('La contraseña de la llave privada es incorrecta.');
        } elseif (stripos($errorMsg, 'certificate') !== false) {
            throw new Exception('El archivo .cer está dañado o no es un certificado válido.');
        } elseif (stripos($errorMsg, 'private') !== false || stripos($errorMsg, 'key') !== false) {
            throw new Exception('El archivo .key está dañado o no es una llave privada válida.');
        } else {
            throw new Exception('Error al procesar los archivos: ' . $errorMsg);
        }
    } catch (\Exception $e) {
        throw new Exception('Validación fallida: ' . $e->getMessage());
    }

    // ---------------------------------------------------------
    // 3. VALIDACIÓN DE RFC DEL CERTIFICADO
    // ---------------------------------------------------------

    $db = new Database();
    $conn = $db->getConnection();
    
    // Verificar permisos y obtener RFC registrado
    $stmt_verify = $conn->prepare("SELECT codigo_suc, file_cer, file_key, rfc FROM empresas WHERE id_empresa = ? AND id_usuario = ?");
    $stmt_verify->execute([$id_empresa, $id_usuario]);
    $sucursal = $stmt_verify->fetch(PDO::FETCH_ASSOC);
    
    if (!$sucursal) {
        throw new Exception('No tienes permisos para modificar esta sucursal.');
    }

    // Validar que el RFC del certificado coincida con el RFC registrado en la BD
    $rfcCertificado = mb_strtoupper(trim($credential->rfc()));
    $rfcRegistrado = mb_strtoupper(trim($sucursal['rfc']));

    if ($rfcCertificado !== $rfcRegistrado) {
        throw new Exception("El RFC del certificado ($rfcCertificado) no coincide con el RFC registrado en la sucursal ($rfcRegistrado). Verifique que esté subiendo el certificado correcto.");
    }

    // ---------------------------------------------------------
    // 4. GUARDADO SEGURO CON CONVERSIÓN A PEM
    // ---------------------------------------------------------
    
    $codigoSucursal = $sucursal['codigo_suc'] ?: 'sucursal_' . $id_empresa;
    
    // Encriptar la contraseña para guardarla en BD
    $claveParaGuardar = SelloUtils::cifrarClave($clavePrivadaTexto, $id_empresa);
    
    // Preparar directorio
    $uploadBaseDir = __DIR__ . '/../uploads/sellos/';
    $uploadDir = $uploadBaseDir . $codigoSucursal . '/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $nombreCer = 'certificado.cer';
    $nombreKey = 'llave_privada.key';
    
    // Eliminar archivos anteriores si existían
    if (!empty($sucursal['file_cer'])) {
        $prevCer = $uploadBaseDir . $sucursal['file_cer'];
        if (is_file($prevCer)) {
            @unlink($prevCer);
        }
    }
    if (!empty($sucursal['file_key'])) {
        $prevKey = $uploadBaseDir . $sucursal['file_key'];
        if (is_file($prevKey)) {
            @unlink($prevKey);
        }
    }

    // A) Guardar el archivo .cer (Usamos move_uploaded_file para el certificado, está bien)
    if (!move_uploaded_file($archivoCer['tmp_name'], $uploadDir . $nombreCer)) {
        throw new Exception('Error al guardar el archivo .cer');
    }

    // B) Guardar la llave privada convertida a PEM
    // NO usamos move_uploaded_file para la llave porque el archivo original suele ser binario (DER).
    // Usamos el método pem() del objeto $privateKey que ya lo tiene convertido.
    $contenidoPEM = $privateKey->pem();
    
    if (file_put_contents($uploadDir . $nombreKey, $contenidoPEM) === false) {
        throw new Exception('Error al guardar el archivo .key convertido a PEM');
    }

    // Rutas relativas para la BD
    $rutaRelativaCer = $codigoSucursal . '/' . $nombreCer;
    $rutaRelativaKey = $codigoSucursal . '/' . $nombreKey;
    
    // Actualizar BD
    $sql = "UPDATE empresas SET file_cer = ?, file_key = ?, clave = ? WHERE id_empresa = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$rutaRelativaCer, $rutaRelativaKey, $claveParaGuardar, $id_empresa]);
    
    $respuesta['success'] = true;
    $respuesta['message'] = 'Sello digital validado y guardado correctamente.';
    $vigencia = $credential->certificate()->validTo();
    if ($vigencia instanceof \DateTimeInterface) {
        $respuesta['vigencia'] = $vigencia->format('Y-m-d H:i:s');
    } else {
        $respuesta['vigencia'] = (string) $vigencia;
    }
    $respuesta['rfc_certificado'] = $credential->rfc();
    $respuesta['num_serie'] = $credential->certificate()->serialNumber()->bytes();

} catch (Exception $e) {
    $respuesta['message'] = $e->getMessage();
}

echo json_encode($respuesta);
?>