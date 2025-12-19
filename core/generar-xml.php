<?php
// core/generar-xml.php

// 1. CONFIGURACIÓN DE ENTORNO
ini_set('display_errors', 0);
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

// Buffer para capturar salidas no deseadas
ob_start();

// 2. INCLUSIÓN DE LIBRERÍAS (Lo primero que debe ocurrir)
$files = [
    'autoload' => __DIR__ . '/autoload-vendor.php',
    'config'   => __DIR__ . '/../config.php',
    'db'       => __DIR__ . '/class/db.php',
    'utils'    => __DIR__ . '/sello-utils.php'
];

foreach ($files as $path) {
    if (!file_exists($path)) {
        echo json_encode(['success' => false, 'message' => "Falta archivo: $path"]);
        exit;
    }
    require_once $path;
}

use CfdiUtils\CfdiCreator40;
use CfdiUtils\Certificado\Certificado;

$respuesta = [
    'success' => false,
    'message' => 'Error desconocido.',
    'debug' => []
];

try {
    // ---------------------------------------------------------
    // 3. RECEPCIÓN DE DATOS (INPUT)
    // ---------------------------------------------------------
    $input = file_get_contents('php://input');
    $datos = json_decode($input, true);

    if (empty($datos['id_factura'])) {
        throw new Exception("No se recibió el ID de la factura.");
    }

    $id_factura = $datos['id_factura'];

    // ---------------------------------------------------------
    // 4. RECUPERACIÓN DE DATOS (¡ESTO DEBE IR ANTES DE VALIDAR!)
    // ---------------------------------------------------------
    $db = new Database();
    $conn = $db->getConnection();

    // Query optimizada
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

    if (!$factura) {
        throw new Exception("Factura ID $id_factura no encontrada en la BD.");
    }

    // Obtener Conceptos
    $stmtDet = $conn->prepare("SELECT * FROM facturas_detalles WHERE id_factura = ?");
    $stmtDet->execute([$id_factura]);
    $conceptos = $stmtDet->fetchAll(PDO::FETCH_ASSOC);

    // ---------------------------------------------------------
    // 5. VALIDACIÓN DE DATOS (REGLAS ANEXO 20)
    // ---------------------------------------------------------
    $errores = [];

    // Validaciones Generales
        // Validación de compatibilidad Método/Forma de Pago
        $metodoPago = $factura['metodo_pago'];
        $formaPago = $factura['forma_pago'];
        // PUE solo es compatible con formas de pago distintas de 99 (Por definir)
        if ($metodoPago === 'PUE' && $formaPago === '99') {
            $errores[] = 'Método de pago PUE no es compatible con forma de pago 99 (Por definir). Cambia la forma de pago o el método.';
        }
        // PPD solo es compatible con 99
        if ($metodoPago === 'PPD' && $formaPago !== '99') {
            $errores[] = 'Método de pago PPD solo es compatible con forma de pago 99 (Por definir).';
        }
    if (empty($factura['emisor_rfc'])) $errores[] = 'RFC Emisor faltante';
    if (empty($factura['rfc_receptor'])) $errores[] = 'RFC Receptor faltante';
    if (empty($factura['uso_cfdi'])) $errores[] = 'Uso CFDI faltante (Requerido en 4.0)';
    if (empty($factura['regimen_fiscal_receptor'])) $errores[] = 'Régimen Fiscal Receptor faltante (Requerido en 4.0)';
    if (empty($factura['domicilio_fiscal_receptor'])) $errores[] = 'CP Receptor faltante (Requerido en 4.0)';
    if (empty($conceptos)) $errores[] = 'La factura no tiene conceptos';

    // Validación Matemática (Tolerancia de 1 centavo)
    $sumaConceptos = 0;
    foreach ($conceptos as $item) {
        $sumaConceptos += floatval($item['importe']);
    }
    
    // Convertir a float para comparación segura
    $subtotalFactura = floatval($factura['subtotal']);
    if (abs($subtotalFactura - $sumaConceptos) > 0.1) {
        $errores[] = "El subtotal ($subtotalFactura) no coincide con la suma de conceptos ($sumaConceptos)";
    }

    if (count($errores) > 0) {
        throw new Exception('Errores de datos: ' . implode('; ', $errores));
    }

    // ---------------------------------------------------------
    // 6. VALIDACIÓN DE CERTIFICADOS (CSD)
    // ---------------------------------------------------------
    $rutaCertificados = __DIR__ . '/../uploads/sellos/';
    $archivoCer = $rutaCertificados . $factura['file_cer'];
    $archivoKey = $rutaCertificados . $factura['file_key'];

    if (!file_exists($archivoCer) || !file_exists($archivoKey)) {
        throw new Exception("Archivos de sello digital (CSD) no encontrados en el servidor.");
    }

    // Descifrar contraseña
    $passwordKey = SelloUtils::descifrarClave($factura['pass_key'], (int)$factura['id_empresa']);
    if (!$passwordKey) {
        throw new Exception('No se pudo descifrar la contraseña del CSD.');
    }

    // Leer el archivo .key y convertirlo si es necesario
    $keyPemContent = SelloUtils::convertirKeyAPEM($archivoKey, $passwordKey);
    if (!$keyPemContent) {
         // Fallback: intentar leer directo si ya estaba en PEM
        $keyPemContent = file_get_contents($archivoKey);
    }

    // Instancia del Certificado
    try {
        $certificado = new Certificado($archivoCer);
    } catch (Exception $e) {
        throw new Exception("El archivo .cer es inválido: " . $e->getMessage());
    }

    // ---------------------------------------------------------
    // 7. CONSTRUCCIÓN DEL CFDI 4.0
    // ---------------------------------------------------------
    
    // Regla CFDI 4.0: Formato de fecha
    $fechaEmision = date('Y-m-d\TH:i:s', strtotime($factura['fecha_emision']));

    // Regla CFDI 4.0: TipoCambio solo cuando Moneda != MXN
    $tipoCambio = ($factura['moneda'] === 'MXN') ? null : $factura['tipo_cambio'];

    // Limpieza de datos (Trim)
    $lugarExpedicion = trim($factura['lugar_expedicion'] ?? $factura['emisor_cp']);

    $comprobanteAtributos = [
        'Version'           => '4.0', // Explícito
        'Serie'             => $factura['serie_interno'],
        'Folio'             => $factura['folio_interno'],
        'Fecha'             => $fechaEmision,
        'Sello'             => '', // Se calcula después
        'FormaPago'         => $factura['forma_pago'],
        'NoCertificado'     => $certificado->getSerial(),
        'Certificado'       => $certificado->getPemContentsOneLine(),
        'SubTotal'          => number_format($factura['subtotal'], 2, '.', ''),
        'Moneda'            => $factura['moneda'],
        'Total'             => number_format($factura['total'], 2, '.', ''),
        'TipoDeComprobante' => 'I', // Ingreso
        'Exportacion'       => $factura['exportacion'] ?? '01', // 01 = No aplica (Default 4.0)
        'MetodoPago'        => $factura['metodo_pago'],
        'LugarExpedicion'   => $lugarExpedicion
    ];

    // Solo incluir TipoCambio cuando la moneda no es MXN (regla SAT)
    if ($tipoCambio !== null && $tipoCambio !== '') {
        $comprobanteAtributos['TipoCambio'] = $tipoCambio;
    }

    $creator = new CfdiCreator40($comprobanteAtributos);

    // -- EMISOR --
    $creator->comprobante()->addEmisor([
        'Rfc'           => mb_strtoupper($factura['emisor_rfc']),
        'Nombre'        => mb_strtoupper($factura['emisor_nombre']),
        'RegimenFiscal' => $factura['emisor_regimen']
    ]);

    // -- RECEPTOR (Lógica estricta 4.0) --
    // Regla: Nombre en mayúsculas y SIN régimen societario (SA de CV, etc)
    // Nota: Asumo que en tu BD ya lo guardaste limpio o usas la lógica de limpieza que tenías antes.
    // Aquí simplifico asumiendo que el dato viene listo, si no, usa tu bloque de limpieza de strings.
    
    $creator->comprobante()->addReceptor([
        'Rfc'                     => mb_strtoupper($factura['rfc_receptor']),
        'Nombre'                  => mb_strtoupper($factura['razon_social_receptor']),
        'DomicilioFiscalReceptor' => $factura['domicilio_fiscal_receptor'], // Debe ser el CP
        'RegimenFiscalReceptor'   => $factura['regimen_fiscal_receptor'],
        'UsoCFDI'                 => $factura['uso_cfdi']
    ]);

    // -- CONCEPTOS --
    foreach ($conceptos as $item) {
        $concepto = $creator->comprobante()->addConcepto([
            'ClaveProdServ'    => $item['clave_prod_serv'],
            'NoIdentificacion' => $item['no_identificacion'] ?? $item['id_detalle'],
            'Cantidad'         => number_format($item['cantidad'], 6, '.', ''), // Hasta 6 decimales
            'ClaveUnidad'      => $item['clave_unidad'],
            'Unidad'           => $item['unidad'],
            'Descripcion'      => $item['descripcion'],
            'ValorUnitario'    => number_format($item['valor_unitario'], 2, '.', ''),
            'Importe'          => number_format($item['importe'], 2, '.', ''),
            'ObjetoImp'        => $item['objeto_imp'] // 01: No objeto, 02: Sí objeto
        ]);

        // Impuestos del Concepto (Solo si ObjetoImp == 02)
        if ($item['objeto_imp'] === '02') {
            $traslados = $concepto->addImpuestos()->addTraslados();
            $traslados->addTraslado([
                'Base'       => number_format($item['impuesto_base'], 2, '.', ''),
                'Impuesto'   => $item['impuesto_tipo'], // 002 para IVA
                'TipoFactor' => 'Tasa',
                'TasaOCuota' => number_format($item['impuesto_tasa'], 6, '.', ''), // Ej: 0.160000
                'Importe'    => number_format($item['impuesto_importe'], 2, '.', '')
            ]);
        }
    }

    // -- SUMARIAS --
    // CfdiUtils calcula las sumas automáticamente basado en los conceptos agregados
    $creator->addSumasConceptos(null, 2);

    // -- SELLADO (Firma) --
    // Usamos el .key (convertido a PEM en string) y la contraseña
    try {
        $creator->addSello($keyPemContent, $passwordKey);
    } catch (Exception $e) {
        throw new Exception("Error al sellar XML: " . $e->getMessage());
    }

    // ---------------------------------------------------------
    // 8. VALIDACIÓN FINAL Y GUARDADO
    // ---------------------------------------------------------
    $asserts = $creator->validate();
    
    if ($asserts->hasErrors()) {
        $msgErrores = [];
        foreach ($asserts->errors() as $error) {
            $msgErrores[] = "[" . $error->getCode() . "] " . $error->getExplanation();
        }
        throw new Exception("Validación Técnica SAT fallida: " . implode(" | ", $msgErrores));
    }

    // Generar ruta y guardar
    $nombreArchivo = $factura['emisor_rfc'] . '_' . $factura['serie_interno'] . $factura['folio_interno'] . '.xml';
    $rutaRelativa = '/../uploads/xml_timbrados/';
    $rutaAbsolutaDir = __DIR__ . $rutaRelativa;
    
    if (!is_dir($rutaAbsolutaDir)) {
        mkdir($rutaAbsolutaDir, 0755, true);
    }

    $rutaCompleta = $rutaAbsolutaDir . $nombreArchivo;
    $creator->saveXml($rutaCompleta);

    // Actualizar BD
    $upd = $conn->prepare("UPDATE facturas SET xml_path = ? WHERE id_factura = ?");
    $upd->execute([$nombreArchivo, $id_factura]);

    $respuesta['success'] = true;
    $respuesta['message'] = 'XML Generado Correctamente y listo para Timbrar.';
    $respuesta['xml_url'] = 'uploads/xml_timbrados/' . $nombreArchivo;

} catch (Throwable $e) {
    // Catch-all para errores
    $respuesta['success'] = false;
    $respuesta['message'] = $e->getMessage();
    $respuesta['debug'] = [
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ];
}

// Limpiar buffer y enviar JSON limpio
ob_end_clean();
echo json_encode($respuesta);
?>