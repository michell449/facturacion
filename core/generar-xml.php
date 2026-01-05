<?php
/**
 * Generación de XML CFDI 4.0
 * core/generar-xml.php
 */

// PRIMERO: Limpiar buffers previos
while (ob_get_level() > 0) {
    ob_end_clean();
}

// SEGUNDO: Iniciar buffer LIMPIO para capturar salida no deseada
ob_start();

// TERCERO: Configurar PHP para no mostrar errores en pantalla
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// CUARTO: Configurar UTF-8 interno de PHP
mb_internal_encoding('UTF-8');
ini_set('default_charset', 'UTF-8');

// Headers JSON (ANTES de cualquier salida)
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');

require_once __DIR__ . '/autoload-vendor.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/sello-utils.php';

use CfdiUtils\CfdiCreator40;
use CfdiUtils\Certificado\Certificado;

/**
 * Limpia el nombre/razón social según reglas CFDI 4.0
 * - Elimina régimen societario (SA, SA DE CV, SC, etc.)
 * - Convierte a mayúsculas
 * - Elimina acentos y caracteres especiales
 * - Elimina puntos, comas, guiones al final
 */
function limpiarRazonSocial($nombre) {
    // Convertir a mayúsculas preservando UTF-8
    $nombre = mb_strtoupper($nombre, 'UTF-8');
    
    // Eliminar acentos EXCEPTO Ñ (la Ñ es válida según el SAT)
    $acentos = [
        'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
        'À' => 'A', 'È' => 'E', 'Ì' => 'I', 'Ò' => 'O', 'Ù' => 'U',
        'Ä' => 'A', 'Ë' => 'E', 'Ï' => 'I', 'Ö' => 'O', 'Ü' => 'U',
        'Â' => 'A', 'Ê' => 'E', 'Î' => 'I', 'Ô' => 'O', 'Û' => 'U'
        // Eliminamos 'Ñ' => 'N' para preservar la Ñ
    ];
    $nombre = str_replace(array_keys($acentos), array_values($acentos), $nombre);
    
    // Eliminar caracteres especiales que no son permitidos (mantener letras, números, espacios, &, Ñ)
    $nombre = preg_replace('/[^A-Z0-9\s&Ñ]/', '', $nombre);
    
    // Patrones de régimen societario a eliminar (más completos)
    $patrones = [
        // SA de CV y variaciones
        '/\s*,?\s*S\.?\s?A\.?\s*DE\s*C\.?\s?V\.?\s*$/i',
        '/\s*,?\s*SA\s*DE\s*CV\s*$/i',
        '/\s*,?\s*SOCIEDAD\s*ANONIMA\s*DE\s*CAPITAL\s*VARIABLE\s*$/i',
        
        // SA simple
        '/\s*,?\s*S\.?\s?A\.?\s*$/i',
        '/\s*,?\s*SA\s*$/i',
        '/\s*,?\s*SOCIEDAD\s*ANONIMA\s*$/i',
        
        // SC
        '/\s*,?\s*S\.?\s?C\.?\s*$/i',
        '/\s*,?\s*SC\s*$/i',
        '/\s*,?\s*SOCIEDAD\s*CIVIL\s*$/i',
        
        // S de RL
        '/\s*,?\s*S\.?\s*DE\s*R\.?\s?L\.?\s*$/i',
        '/\s*,?\s*S\s*DE\s*RL\s*$/i',
        '/\s*,?\s*SOCIEDAD\s*DE\s*RESPONSABILIDAD\s*LIMITADA\s*$/i',
        
        // AC
        '/\s*,?\s*A\.?\s?C\.?\s*$/i',
        '/\s*,?\s*AC\s*$/i',
        '/\s*,?\s*ASOCIACION\s*CIVIL\s*$/i',
        
        // SCP
        '/\s*,?\s*S\.?\s?C\.?\s?P\.?\s*$/i',
        '/\s*,?\s*SCP\s*$/i',
        '/\s*,?\s*SOCIEDAD\s*CIVIL\s*PARTICULAR\s*$/i',
        
        // S en C
        '/\s*,?\s*S\.?\s*EN\s*C\.?\s*$/i',
        '/\s*,?\s*S\s*EN\s*C\s*$/i',
        
        // S en C por A
        '/\s*,?\s*S\.?\s*EN\s*C\.?\s*POR\s*A\.?\s*$/i',
        '/\s*,?\s*S\s*EN\s*C\s*POR\s*A\s*$/i',
        
        // SPR
        '/\s*,?\s*S\.?\s?P\.?\s?R\.?\s*$/i',
        '/\s*,?\s*SPR\s*$/i',
        
        // Eliminar puntos, comas y guiones al final
        '/[\.,\-]+\s*$/'
    ];
    
    foreach ($patrones as $patron) {
        $nombre = preg_replace($patron, '', $nombre);
    }
    
    // Limpiar espacios múltiples y trimear
    $nombre = preg_replace('/\s+/', ' ', $nombre);
    $nombre = trim($nombre);
    
    // Eliminar puntos al final si quedaron
    $nombre = rtrim($nombre, '.');
    
    return $nombre;
}

/**
 * Valida compatibilidad RFC-Régimen-Uso CFDI según reglas SAT
 */
function validarCompatibilidadCFDI($rfc, $regimen, $usoCfdi, &$errores) {
    $rfc = strtoupper(trim($rfc));
    
    // RFC Genérico (Público en General)
    if ($rfc === 'XAXX010101000' || $rfc === 'XEXX010101000') {
        if ($regimen !== '616') {
            $errores[] = "RFC Genérico debe usar Régimen 616 (Sin obligaciones fiscales)";
        }
        if ($usoCfdi !== 'S01') {
            $errores[] = "RFC Genérico debe usar Uso CFDI S01 (Sin efectos fiscales)";
        }
    }
    
    // Validar que régimen existe (básico)
    if (empty($regimen)) {
        $errores[] = "Régimen Fiscal del receptor es obligatorio";
    }
}

$respuesta = ['success' => false, 'message' => 'Error desconocido.'];

try {
    $input = file_get_contents('php://input');
    $datos = json_decode($input, true);

    if (empty($datos['id_factura'])) throw new Exception("Falta ID Factura");
    $id_factura = $datos['id_factura'];

    $db = new Database();
    $conn = $db->getConnection();

    // --- (Tu Query SQL original está bien, la mantengo resumida) ---
    $sql = "SELECT f.*, e.rfc as emisor_rfc, e.razon_social as emisor_nombre, 
            e.reg_fiscal as emisor_regimen, e.cp as emisor_cp,
            e.file_cer, e.file_key, e.clave as pass_key 
            FROM facturas f JOIN empresas e ON f.id_empresa = e.id_empresa
            WHERE f.id_factura = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_factura]);
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$factura) throw new Exception("Factura no encontrada");

    $stmtDet = $conn->prepare("SELECT * FROM facturas_detalles WHERE id_factura = ?");
    $stmtDet->execute([$id_factura]);
    $conceptos = $stmtDet->fetchAll(PDO::FETCH_ASSOC);

    // --- VALIDACIONES CFDI 4.0 ---
    $erroresValidacion = [];
    
    // 1. Validar Receptor
    if (empty($factura['rfc_receptor'])) {
        $erroresValidacion[] = "RFC Receptor faltante";
    }
    if (empty($factura['razon_social_receptor'])) {
        $erroresValidacion[] = "Nombre/Razón Social del Receptor faltante";
    }
    if (empty($factura['domicilio_fiscal_receptor'])) {
        $erroresValidacion[] = "Código Postal del Receptor faltante";
    } else if (!preg_match('/^\d{5}$/', $factura['domicilio_fiscal_receptor'])) {
        $erroresValidacion[] = "Código Postal inválido (debe ser 5 dígitos): " . $factura['domicilio_fiscal_receptor'];
    }
    if (empty($factura['regimen_fiscal_receptor'])) {
        $erroresValidacion[] = "Régimen Fiscal del Receptor faltante";
    }
    if (empty($factura['uso_cfdi'])) {
        $erroresValidacion[] = "Uso CFDI faltante";
    }
    
    // 2. Validar compatibilidad RFC-Régimen-Uso
    validarCompatibilidadCFDI(
        $factura['rfc_receptor'],
        $factura['regimen_fiscal_receptor'],
        $factura['uso_cfdi'],
        $erroresValidacion
    );
    
    // 3. Validar Emisor
    if (empty($factura['emisor_rfc'])) {
        $erroresValidacion[] = "RFC Emisor faltante";
    }
    if (empty($factura['emisor_nombre'])) {
        $erroresValidacion[] = "Nombre Emisor faltante";
    }
    if (empty($factura['emisor_regimen'])) {
        $erroresValidacion[] = "Régimen Fiscal Emisor faltante";
    }
    
    // 4. Validar Conceptos
    if (empty($conceptos)) {
        $erroresValidacion[] = "No hay conceptos en la factura";
    }
    
    // 5. Validar Forma/Método de Pago
    if ($factura['metodo_pago'] === 'PUE' && $factura['forma_pago'] === '99') {
        $erroresValidacion[] = "Método PUE no es compatible con Forma de Pago 99";
    }
    if ($factura['metodo_pago'] === 'PPD' && $factura['forma_pago'] !== '99') {
        $erroresValidacion[] = "Método PPD solo es compatible con Forma de Pago 99";
    }
    
    if (count($erroresValidacion) > 0) {
        throw new Exception("Errores de validación: " . implode(" | ", $erroresValidacion));
    }

    // --- VALIDACIÓN CERTIFICADOS ---
    $rutaCertificados = __DIR__ . '/../uploads/sellos/';
    $archivoCer = $rutaCertificados . $factura['file_cer'];
    $archivoKey = $rutaCertificados . $factura['file_key'];

    if (!file_exists($archivoCer) || !file_exists($archivoKey)) 
        throw new Exception("Archivos CSD no encontrados.");

    $passwordKey = SelloUtils::descifrarClave($factura['pass_key'], (int)$factura['id_empresa']);
    if (!$passwordKey) throw new Exception("Error al descifrar contraseña del CSD.");
    
    // Convertir KEY a PEM
    $keyPem = SelloUtils::convertirKeyAPEM($archivoKey, $passwordKey);
    if(!$keyPem) $keyPem = file_get_contents($archivoKey);

    $certificado = new Certificado($archivoCer);

    // --- CREACIÓN DEL CFDI ---
    $comprobanteAtributos = [
        'Version' => '4.0',
        'Serie' => $factura['serie_interno'],
        'Folio' => $factura['folio_interno'],
        'Fecha' => date('Y-m-d\TH:i:s', strtotime($factura['fecha_emision'])),
        'Sello' => '', 
        'FormaPago' => $factura['forma_pago'],
        'NoCertificado' => $certificado->getSerial(),
        'Certificado' => $certificado->getPemContentsOneLine(),
        'SubTotal' => number_format($factura['subtotal'], 2, '.', ''),
        'Moneda' => $factura['moneda'],
        'Total' => number_format($factura['total'], 2, '.', ''),
        'TipoDeComprobante' => 'I',
        'Exportacion' => '01',
        'MetodoPago' => $factura['metodo_pago'],
        'LugarExpedicion' => $factura['lugar_expedicion'] ?: $factura['emisor_cp']
    ];

    $creator = new CfdiCreator40($comprobanteAtributos);
    $rfcReceptor = mb_strtoupper($factura['rfc_receptor'], 'UTF-8');
    $nombreReceptor = mb_strtoupper($factura['razon_social_receptor'], 'UTF-8');

    // Verifica si es el RFC genérico y el nombre exacto
    if ($rfcReceptor === 'XAXX010101000' && $nombreReceptor === 'PUBLICO EN GENERAL') {
        
        // Obtenemos el mes actual formato '01', '02'... '12'
        $mesActual = date('m'); 
        $anioActual = date('Y');

        // Periodicidad: 01=Diaria, 02=Semanal, 03=Quincenal, 04=Mensual
        // Meses: 01=Enero ... 12=Diciembre
        $creator->comprobante()->addInformacionGlobal([
            'Periodicidad' => '01', // Asumimos '01' (Diaria) para ventas de mostrador al momento
            'Meses'        => $mesActual,
            'Año'          => $anioActual
        ]);
    }
    // ----------------------------------
    // Normalizar datos del emisor
    $nombreEmisor = limpiarRazonSocial($factura['emisor_nombre']);
    
    $creator->comprobante()->addEmisor([
        'Rfc' => strtoupper(trim($factura['emisor_rfc'])),
        'Nombre' => $nombreEmisor,
        'RegimenFiscal' => trim($factura['emisor_regimen'])
    ]);

    // Limpiar y normalizar nombre del receptor
    $nombreOriginalReceptor = $factura['razon_social_receptor'];
    $nombreReceptor = limpiarRazonSocial($nombreOriginalReceptor);
    
    // LOG para debug (solo si hay diferencia)
    if ($nombreOriginalReceptor !== $nombreReceptor) {
        error_log("[CFDI] Limpieza nombre receptor ID {$id_factura}:");
        error_log("  Original: {$nombreOriginalReceptor}");
        error_log("  Limpio:   {$nombreReceptor}");
    }
    
    $creator->comprobante()->addReceptor([
        'Rfc' => strtoupper(trim($factura['rfc_receptor'])),
        'Nombre' => $nombreReceptor,
        'DomicilioFiscalReceptor' => trim($factura['domicilio_fiscal_receptor']),
        'RegimenFiscalReceptor' => trim($factura['regimen_fiscal_receptor']),
        'UsoCFDI' => trim($factura['uso_cfdi'])
    ]);

    foreach ($conceptos as $item) {
        // Normalizar descripción del concepto preservando Ñ
        $descripcion = mb_strtoupper(trim($item['descripcion']), 'UTF-8');
        $descripcion = str_replace(
            ['Á', 'É', 'Í', 'Ó', 'Ú'],
            ['A', 'E', 'I', 'O', 'U'],
            $descripcion
        );
        
        // Preparar atributos del concepto
        $atributosConcepto = [
            'ClaveProdServ' => trim($item['clave_prod_serv']),
            'Cantidad' => number_format($item['cantidad'], 6, '.', ''),
            'ClaveUnidad' => trim($item['clave_unidad']),
            'Descripcion' => $descripcion,
            'ValorUnitario' => number_format($item['valor_unitario'], 2, '.', ''),
            'Importe' => number_format($item['importe'], 2, '.', ''),
            'ObjetoImp' => trim($item['objeto_imp'])
        ];
        
        // Solo agregar NoIdentificacion si no está vacío
        if (!empty($item['no_identificacion'])) {
            $atributosConcepto['NoIdentificacion'] = trim($item['no_identificacion']);
        }
        
        $concepto = $creator->comprobante()->addConcepto($atributosConcepto);

        if ($item['objeto_imp'] === '02') {
            $traslados = $concepto->addImpuestos()->addTraslados();
            $traslados->addTraslado([
                'Base' => number_format($item['impuesto_base'], 2, '.', ''),
                'Impuesto' => $item['impuesto_tipo'],
                'TipoFactor' => 'Tasa',
                'TasaOCuota' => number_format($item['impuesto_tasa'], 6, '.', ''),
                'Importe' => number_format($item['impuesto_importe'], 2, '.', '')
            ]);
        }
    }

    $creator->addSumasConceptos(null, 2);

    // --- SELLO ---
    $creator->addSello($keyPem, $passwordKey);

    // --- VALIDACIÓN TÉCNICA ---
    $asserts = $creator->validate();
    if ($asserts->hasErrors()) {
        $errs = [];
        foreach ($asserts->errors() as $e) $errs[] = $e->getExplanation();
        throw new Exception("Error validación Estructura XML: " . implode(', ', $errs));
    }

    // --- GUARDADO ---
    // Usamos el RFC y Serie/Folio para el nombre
    $nombreArchivo = $factura['emisor_rfc'] . '_' . $factura['serie_interno'] . $factura['folio_interno'] . '.xml';
    $rutaDir = __DIR__ . '/../uploads/xml_timbrados/';
    
    if (!is_dir($rutaDir)) mkdir($rutaDir, 0755, true);

    $creator->saveXml($rutaDir . $nombreArchivo);

    // Guardar nombre en BD
    $conn->prepare("UPDATE facturas SET xml_path = ? WHERE id_factura = ?")
         ->execute([$nombreArchivo, $id_factura]);

    $respuesta['success'] = true;
    $respuesta['message'] = 'XML Generado.';
    $respuesta['xml_url'] = 'uploads/xml_timbrados/' . $nombreArchivo;

} catch (Throwable $e) {
    error_log("EXCEPCIÓN EN GENERAR-XML: " . $e->getMessage());
    error_log("Trace: " . $e->getTraceAsString());
    
    http_response_code(500);
    $respuesta['success'] = false;
    $respuesta['message'] = $e->getMessage();
    $respuesta['debug'] = [
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ];
}

// ============================================================================
// SALIDA FINAL: Siempre limpio, siempre JSON válido
// ============================================================================
$outputBuffer = ob_get_clean();
if (!empty($outputBuffer)) {
    error_log("OUTPUT INESPERADO CAPTURADO EN XML: " . substr($outputBuffer, 0, 200));
}
echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
exit;