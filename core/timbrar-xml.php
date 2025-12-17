<?php
// core/timbrar-xml.php

// 1. Configuración de errores y JSON (Para que el frontend entienda la respuesta)
ini_set('display_errors', 0);
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

// 2. Cargar librerías y base de datos
require_once __DIR__ . '/autoload-vendor.php'; // Carga phpcfdi/finkok
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';

use XmlResourceRetriever\Downloader\PhpDownloader;
use PhpCfdi\Finkok\FinkokEnvironment;
use PhpCfdi\Finkok\FinkokSettings;
use PhpCfdi\Finkok\Services\Stamping\StampService;
use PhpCfdi\Finkok\Services\Stamping\StampingCommand;
use CfdiUtils\Cfdi; // Para leer el UUID del XML regresado (de eclipxe/cfdiutils)

// Verificar librerías críticas
error_log("[TIMBRAR-XML] Verificando librerías de timbrado...");

$clasesRequeridas = [
    'PhpCfdi\\Finkok\\FinkokEnvironment' => 'Finkok Environment',
    'PhpCfdi\\Finkok\\FinkokSettings' => 'Finkok Settings',
    'PhpCfdi\\Finkok\\Services\\Stamping\\StampService' => 'Stamp Service',
    'CfdiUtils\\Cfdi' => 'CFDI Utils',
    'Database' => 'Base de datos'
];

foreach ($clasesRequeridas as $clase => $descripcion) {
    if (class_exists($clase)) {
        error_log("[TIMBRAR-XML] ✓ $descripcion ($clase) cargada");
    } else {
        error_log("[TIMBRAR-XML] ✗ FALTA: $descripcion ($clase)");
        throw new Exception("Clase requerida no disponible: $clase ($descripcion)");
    }
}

error_log("[TIMBRAR-XML] Todas las librerías de timbrado están disponibles");

$respuesta = [
    'success' => false,
    'message' => 'Error desconocido',
    'uuid'    => ''
];

try {
    // ---------------------------------------------------------
    // A. OBTENER DATOS DE ENTRADA
    // ---------------------------------------------------------
    $input = file_get_contents('php://input');
    
    // Registrar entrada para debugging
    error_log("timbrar-xml.php - Input recibido: " . substr($input, 0, 200));
    
    if (empty($input)) {
        throw new Exception("No se recibieron datos en la petición de timbrado");
    }
    
    $datos = json_decode($input, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("timbrar-xml.php - JSON Error: " . json_last_error_msg() . " | Input: " . $input);
        throw new Exception("JSON inválido: " . json_last_error_msg());
    }

    if (empty($datos['id_factura'])) {
        error_log("timbrar-xml.php - Datos recibidos: " . print_r($datos, true));
        throw new Exception("No se recibió el ID de la factura a timbrar.");
    }

    $id_factura = $datos['id_factura'];
    $db = new Database();
    $conn = $db->getConnection();

    // ---------------------------------------------------------
    // B. BUSCAR EL XML QUE GENERAMOS EN EL PASO ANTERIOR
    // ---------------------------------------------------------
    // Necesitamos saber dónde está el archivo físico para leerlo
    $stmt = $conn->prepare("SELECT xml_path FROM facturas WHERE id_factura = ?");
    $stmt->execute([$id_factura]);
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$factura || empty($factura['xml_path'])) {
        throw new Exception("No se encontró el registro del archivo XML en la base de datos.");
    }

    // Construir la ruta absoluta
    $nombreArchivo = $factura['xml_path'];
    $rutaArchivo = __DIR__ . '/../uploads/xml_timbrados/' . $nombreArchivo;

    if (!file_exists($rutaArchivo)) {
        throw new Exception("El archivo XML físico no existe: $rutaArchivo");
    }

    // Leer el contenido del XML (el string que enviaremos a Finkok)
    $xmlContent = file_get_contents($rutaArchivo);

    // ---------------------------------------------------------
    // C. CONFIGURAR FINKOK (Aquí ocurre la magia)
    // ---------------------------------------------------------
    
    // IMPORTANTE: Cambia estos datos por tus credenciales REALES de prueba
    $username = 'integrador@finkok.com'; 
    $password = 'Fin2023kok*'; 

    // Definimos que usaremos el entorno de DESARROLLO (Pruebas)
    // Cuando pases a producción, cambiarás makeDevelopment() por makeProduction()
    $environment = FinkokEnvironment::makeDevelopment();

    $settings = new FinkokSettings($username, $password, $environment);
    $service = new StampService($settings);

    // ---------------------------------------------------------
    // D. EJECUTAR TIMBRADO (STAMP)
    // ---------------------------------------------------------
    // El método stamp() envía el XML, lo valida y si todo está bien,
    // regresa un objeto con el XML firmado. Si falla, lanza una excepción.
    try {
        $command = new StampingCommand($xmlContent);
        $result = $service->stamp($command);
    } catch (Exception $eSoap) {
        // Si Finkok dice que no, aquí atrapamos el error (ej: "RFC no está en la lista")
        // Limpiamos el mensaje porque a veces viene con códigos técnicos feos
        throw new Exception("Finkok rechazó el timbrado: " . $eSoap->getMessage());
    }

    // Si llegamos aquí, ¡YA TENEMOS FACTURA LEGAL!
    $xmlTimbrado = $result->xml(); // Este string ya contiene el nodo TimbreFiscalDigital

    if (empty($xmlTimbrado)) {
        throw new Exception("Finkok respondió éxito pero no devolvió el XML timbrado.");
    }

    // ---------------------------------------------------------
    // E. GUARDAR EL RESULTADO
    // ---------------------------------------------------------

    // 1. Sobrescribir el archivo XML original con la versión timbrada
    // ¿Por qué? Porque el archivo anterior no servía de nada, este es el que vale.
    if (file_put_contents($rutaArchivo, $xmlTimbrado) === false) {
        throw new Exception("Se timbró, pero hubo error al guardar el archivo XML firmado.");
    }

    // 2. Extraer el UUID (Folio Fiscal) del XML nuevo para la base de datos
    // Usamos CfdiUtils para leer el XML fácilmente
    $cfdi = Cfdi::newFromString($xmlTimbrado);
    $complemento = $cfdi->getQuickReader()->{'cfdi:Complemento'};
    $tfd = $complemento ? $complemento->{'tfd:TimbreFiscalDigital'} : null;

    $uuid = ($tfd && isset($tfd['UUID'])) ? (string) $tfd['UUID'] : '';
    $fechaTimbrado = ($tfd && isset($tfd['FechaTimbrado'])) ? (string) $tfd['FechaTimbrado'] : '';

    if (empty($uuid)) {
        throw new Exception("No se pudo leer el UUID del XML timbrado.");
    }

    // 3. Actualizar la base de datos
    $sqlUpd = "UPDATE facturas SET 
               uuid = ?, 
               fecha_timbrado = ?, 
               status = 'timbrada',
               cadena_original_sat = ? -- Opcional si tienes este campo
               WHERE id_factura = ?";
    
    $stmtUpd = $conn->prepare($sqlUpd);
    // La cadena original del timbre la puedes generar después o dejar vacía por ahora
    $stmtUpd->execute([$uuid, $fechaTimbrado, '', $id_factura]);

    $respuesta['success'] = true;
    $respuesta['message'] = "Factura timbrada correctamente. Folio Fiscal: $uuid";
    $respuesta['uuid'] = $uuid;
    $respuesta['xml_url'] = 'uploads/xml_timbrados/' . $nombreArchivo;

} catch (Exception $e) {
    $respuesta['success'] = false;
    $respuesta['message'] = $e->getMessage();
}

echo json_encode($respuesta);
?>