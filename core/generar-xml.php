<?php
// core/generar-xml.php
/**
 * Endpoint: Generación de XML CFDI 4.0
 *
 * Entrada (JSON): { id_factura: number }
 * Proceso:
 *  - Recupera factura y conceptos desde BD
 *  - Valida existencia y descifra CSD (cer/key)
 *  - Ensambla Comprobante con CfdiUtils 4.0
 *  - Reglas clave:
 *      * CFDI40114: Si Moneda = MXN entonces TipoCambio = "1"
 *      * Valida CP del receptor contra catálogo en BD
 *      * Valida RFC y nombre del emisor (formato y no vacío)
 *  - Firma (sella) el XML y valida estructura
 *  - Guarda el archivo en uploads/xml_timbrados y actualiza BD
 *
 * Salida (JSON): { success: bool, message: string, xml_url?: string, errores_validacion?: string[] }
 */

// 1. CONFIGURACIÓN DE ERRORES PARA DEBUGGING
// Desactivamos mostrar errores en pantalla para no romper el JSON
ini_set('display_errors', 0);
// Habilitamos el reporte interno
error_reporting(E_ALL);

// Declaraciones use deben estar al inicio del archivo
use CfdiUtils\CfdiCreator40;
use CfdiUtils\Certificado\Certificado;
use XmlResourceRetriever\Downloader\PhpDownloader;
use CfdiUtils\XmlResolver\XmlResolver;


// Iniciamos buffer para capturar cualquier salida inesperada (warnings de PHP)
ob_start();

header('Content-Type: application/json; charset=utf-8');

$respuesta = [
    'success' => false,
    'message' => 'Error desconocido.',
    'debug' => []
];

try {
    // ---------------------------------------------------------
    // 2. VERIFICACIÓN DE ARCHIVOS REQUERIDOS
    // ---------------------------------------------------------
    $files = [
        'autoload' => __DIR__ . '/autoload-vendor.php',
        'config'   => __DIR__ . '/../config.php',
        'db'       => __DIR__ . '/class/db.php',
        'utils'    => __DIR__ . '/sello-utils.php'
    ];

    foreach ($files as $name => $path) {
        if (!file_exists($path)) {
            throw new Exception("Falta el archivo del sistema: $name ($path)");
        }
        require_once $path;
    }

    // Verificar clases críticas y registrar en log
    error_log("[GENERAR-XML] Verificando librerías...");
    
    $clasesRequeridas = [
        'SelloUtils' => 'Utilidades de sello digital',
        'CfdiUtils\\CfdiCreator40' => 'Creador CFDI 4.0',
        'CfdiUtils\\Certificado\\Certificado' => 'Certificado digital',
        'XmlResourceRetriever\\XsltRetriever' => 'XSLT Retriever',
        'Database' => 'Conexión a base de datos'
    ];
    
    foreach ($clasesRequeridas as $clase => $descripcion) {
        if (class_exists($clase)) {
            error_log("[GENERAR-XML] ✓ $descripcion ($clase) cargada correctamente");
        } else {
            error_log("[GENERAR-XML] ✗ FALTA: $descripcion ($clase)");
            throw new Exception("La clase requerida no está disponible: $clase ($descripcion)");
        }
    }
    
    error_log("[GENERAR-XML] Todas las librerías requeridas están cargadas");

    // ---------------------------------------------------------
    // 3. RECEPCIÓN DE DATOS
    // ---------------------------------------------------------
    $input = file_get_contents('php://input');
    
    // Registrar entrada para debugging
    error_log("generar-xml.php - Input recibido: " . substr($input, 0, 200));
    
    if (empty($input)) {
        throw new Exception("No se recibieron datos en la petición");
    }
    
    $datos = json_decode($input, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("generar-xml.php - JSON Error: " . json_last_error_msg() . " | Input: " . $input);
        throw new Exception("JSON inválido recibido: " . json_last_error_msg() . ". Verifique el formato de los datos enviados.");
    }

    if (empty($datos['id_factura'])) {
        error_log("generar-xml.php - Datos recibidos: " . print_r($datos, true));
        throw new Exception("No se recibió el ID de la factura");
    }

    $id_factura = $datos['id_factura'];
    $db = new Database();
    $conn = $db->getConnection();

    // ---------------------------------------------------------
    // 4. RECUPERACIÓN DE DATOS
    // ---------------------------------------------------------
    $sql = "SELECT f.*, 
                   e.rfc as emisor_rfc, 
                   e.razon_social as emisor_nombre, 
                   e.reg_fiscal as emisor_regimen, 
                   e.cp as emisor_cp,
                   e.file_cer, 
                   e.file_key, 
                   e.clave as pass_key 
            FROM facturas f
            JOIN empresas e ON f.id_empresa = e.id_empresa
            WHERE f.id_factura = ?";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_factura]);
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$factura) throw new Exception("Factura no encontrada en BD (ID: $id_factura)");

    $stmtDet = $conn->prepare("SELECT * FROM facturas_detalles WHERE id_factura = ?");
    $stmtDet->execute([$id_factura]);
    $conceptos = $stmtDet->fetchAll(PDO::FETCH_ASSOC);

    if (empty($conceptos)) throw new Exception("La factura no tiene conceptos.");

    // ---------------------------------------------------------
    // 3.5 VALIDACIÓN DE CÓDIGO POSTAL
    // ---------------------------------------------------------
    $codigoPostalReceptor = trim($factura['codigo_postal_receptor'] ?? $factura['domicilio_fiscal_receptor'] ?? '');
    
    // Extraer solo los 5 dígitos si están en un domicilio completo
    if (preg_match('/(\d{5})/', $codigoPostalReceptor, $matches)) {
        $cp = $matches[1];
    } else {
        throw new Exception("Código postal del receptor inválido: debe contener 5 dígitos");
    }

    // Validar que el CP existe en la BD
    $stmtCP = $conn->prepare("SELECT d_codigo FROM cat_codigo_postal WHERE d_codigo = ? LIMIT 1");
    $stmtCP->execute([$cp]);
    $cpValido = $stmtCP->fetch(PDO::FETCH_ASSOC);
    
    if (!$cpValido) {
        throw new Exception("Código postal {$cp} no encontrado en el catálogo del SAT. Verifique que sea válido.");
    }

    // ---------------------------------------------------------
    // 5. VALIDACIÓN DE CERTIFICADOS
    // ---------------------------------------------------------
    $rutaCertificados = __DIR__ . '/../uploads/sellos/';
    $archivoCer = $rutaCertificados . $factura['file_cer'];
    $archivoKey = $rutaCertificados . $factura['file_key'];
    
    if (!file_exists($archivoCer)) throw new Exception("Archivo .cer no encontrado: " . $factura['file_cer']);
    if (!file_exists($archivoKey)) throw new Exception("Archivo .key no encontrado: " . $factura['file_key']);

    // Descifrar contraseña
    $idEmpresa = (int)$factura['id_empresa'];
    $passwordKey = SelloUtils::descifrarClave($factura['pass_key'], $idEmpresa);
    
    // Si descifrar falla, puede devolver false o string vacio
    if ($passwordKey === false) {
        throw new Exception('Fallo al descifrar la contraseña del sello.');
    }

    // ---------------------------------------------------------
    // 6. ARMADO DEL XML
    // ---------------------------------------------------------
    
    // Configuración del Certificado
    try {
        $certificado = new Certificado($archivoCer);
    } catch (Exception $e) {
        throw new Exception("Error al leer el archivo .cer: " . $e->getMessage());
    }
    try {
        $cacheDir = __DIR__ . '/../vendor/phpcfdi/cfdiutils/build/schema';
        if (is_dir($cacheDir)) {
            $files = glob($cacheDir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) @unlink($file);
            }
        }
    } catch (Exception $ignorar) {
        // No detenemos el proceso si falla el borrado
    }

    $fechaEmision = date('Y-m-d\TH:i:s', strtotime($factura['fecha_emision']));

    // Validación CFDI40114: Si la moneda es MXN, TipoCambio debe ser "1"
    $tipoCambio = $factura['tipo_cambio'];
    if ($factura['moneda'] === 'MXN') {
        $tipoCambio = '1';
    }

    $comprobanteAtributos = [
        'Serie'             => $factura['serie_interno'],
        'Folio'             => $factura['folio_interno'],
        'Fecha'             => $fechaEmision,
        'SubTotal'          => number_format($factura['subtotal'], 2, '.', ''),
        'Total'             => number_format($factura['total'], 2, '.', ''),
        'Moneda'            => $factura['moneda'],
        'TipoCambio'        => $tipoCambio, 
        'TipoDeComprobante' => 'I', 
        'Exportacion'       => $factura['exportacion'], 
        'MetodoPago'        => $factura['metodo_pago'],
        'FormaPago'         => $factura['forma_pago'],
        'LugarExpedicion'   => $factura['lugar_expedicion']
    ];

    $creator = new CfdiCreator40($comprobanteAtributos, $certificado);
    $comprobante = $creator->comprobante();

    // Emisor
    $comprobante->addEmisor([
        'RegimenFiscal' => $factura['emisor_regimen'],
        'Nombre'        => $factura['emisor_nombre'],
        'Rfc'           => mb_strtoupper($factura['emisor_rfc'], 'UTF-8') // forzar mayúsculas
    ]);

    // Validación CFDI40138: Verificar que el nombre del emisor no esté vacío y sea válido
    if (empty(trim($factura['emisor_nombre']))) {
        throw new Exception("CFDI40138: El nombre del emisor no puede estar vacío. Asegúrese de que el emisor esté correctamente registrado en el SAT.");
    }
    
    if (strlen(trim($factura['emisor_nombre'])) < 3) {
        throw new Exception("CFDI40138: El nombre del emisor debe tener al menos 3 caracteres.");
    }

    // Validar RFC del emisor (13 caracteres, formato válido)
    $rfcEmisor = mb_strtoupper($factura['emisor_rfc'], 'UTF-8');
    if (!preg_match('/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/', $rfcEmisor)) {
        throw new Exception("CFDI40138: El RFC del emisor '{$rfcEmisor}' tiene un formato inválido. Debe ser una clave válida del SAT.");
    }

    // Receptor
    $comprobante->addReceptor([
        'Rfc'                     => mb_strtoupper($factura['rfc_receptor'], 'UTF-8'),
        'Nombre'                  => $factura['razon_social_receptor'],
        'UsoCFDI'                 => $factura['uso_cfdi'],
        'DomicilioFiscalReceptor' => $factura['domicilio_fiscal_receptor'], 
        'RegimenFiscalReceptor'   => $factura['regimen_fiscal_receptor']    
    ]);

    // Conceptos
    foreach ($conceptos as $item) {
        $nodoConcepto = $comprobante->addConcepto([
            'ClaveProdServ'    => $item['clave_prod_serv'],
            'NoIdentificacion' => $item['no_identificacion'] ?? $item['id_detalle'],
            'Cantidad'         => number_format($item['cantidad'], 6, '.', ''),
            'ClaveUnidad'      => $item['clave_unidad'],
            'Unidad'           => $item['unidad'],
            'Descripcion'      => $item['descripcion'],
            'ValorUnitario'    => number_format($item['valor_unitario'], 2, '.', ''),
            'Importe'          => number_format($item['importe'], 2, '.', ''),
            'ObjetoImp'        => $item['objeto_imp']
        ]);

        if ($item['objeto_imp'] === '02') {
            $nodoConcepto->addTraslado([
                'Base'       => number_format($item['impuesto_base'], 2, '.', ''),
                'Impuesto'   => $item['impuesto_tipo'], 
                'TipoFactor' => 'Tasa',
                'TasaOCuota' => number_format($item['impuesto_tasa'], 6, '.', ''), 
                'Importe'    => number_format($item['impuesto_importe'], 2, '.', '')
            ]);
        }
    }

    $creator->addSumasConceptos(null, 2);
    $creator->moveSatDefinitionsToComprobante();

    // ---------------------------------------------------------
    // 7. SELLADO (Lógica Robusta)
    // ---------------------------------------------------------
    
    // Intentamos obtener el PEM string.
    // SelloUtils::convertirKeyAPEM intenta arreglarlo si es binario.
    $keyPemContent = SelloUtils::convertirKeyAPEM($archivoKey, $passwordKey);

    // Si devuelve false (falló conversión o archivo vacío), intentamos leer directo 
    // asumiendo que quizá YA es un PEM válido.
    if (!$keyPemContent) {
        $keyPemContent = file_get_contents($archivoKey);
    }

    if (!$keyPemContent) {
        throw new Exception("No se pudo leer el contenido de la llave privada.");
    }

    try {
        // Pasamos el contenido STRING directamente
        $creator->addSello($keyPemContent, $passwordKey);
    } catch (Exception $e) {
        throw new Exception("Fallo al firmar el XML (addSello): " . $e->getMessage());
    }

    // ---------------------------------------------------------
    // 8. VALIDACIÓN Y GUARDADO
    // ---------------------------------------------------------
    // ---------------------------------------------------------
    // 8. VALIDACIÓN Y GUARDADO
    // ---------------------------------------------------------
    $asserts = $creator->validate();
    
    if ($asserts->hasErrors()) {
        $errores = [];
        foreach ($asserts->errors() as $error) {
            // --- CORRECCIÓN: Extraer título y explicación detallada ---
            $errores[] = sprintf(
                'Código: %s | Estado: %s | Error: %s | Detalle: %s', 
                $error->getCode(), 
                $error->getStatus(), 
                $error->getTitle(),
                $error->getExplanation()
            );
        }
        $respuesta['errores_validacion'] = $errores;
        throw new Exception("El XML no cumple con el estándar del SAT. Revisa la lista de errores.");
    }

    $nombreArchivo = $factura['emisor_rfc'] . '_' . $factura['serie_interno'] . $factura['folio_interno'] . '.xml';
    $rutaRelativa = '/../uploads/xml_timbrados/' . $nombreArchivo;
    $rutaAbsoluta = __DIR__ . $rutaRelativa;

    if (!file_exists(dirname($rutaAbsoluta))) {
        mkdir(dirname($rutaAbsoluta), 0777, true);
    }

    $creator->saveXml($rutaAbsoluta);

    // Actualizar BD
    $upd = $conn->prepare("UPDATE facturas SET xml_path = ? WHERE id_factura = ?");
    $upd->execute([$nombreArchivo, $id_factura]);

    $respuesta['success'] = true;
    $respuesta['message'] = 'XML Generado Correctamente';
    $respuesta['xml_url'] = 'uploads/xml_timbrados/' . $nombreArchivo; // Ajuste ruta relativa para descarga

} catch (Throwable $e) {
    // CAPTURA DE ERRORES FATALES Y EXCEPCIONES
    // 'Throwable' captura errores de sintaxis y excepciones en PHP 7+
    $respuesta['success'] = false;
    $respuesta['message'] = $e->getMessage();
    $respuesta['debug'] = [
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ];
}

// Limpiar cualquier salida de texto (warnings, notices) que haya ocurrido antes
$output = ob_get_clean(); 

// Si hubo salida inesperada, la agregamos al debug (opcional)
if (!empty($output)) {
    $respuesta['debug']['unexpected_output'] = $output;
}

echo json_encode($respuesta);
?>