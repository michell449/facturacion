<?php
// core/generar-xml.php

// 1. Carga de dependencias y configuración
require_once __DIR__ . '/autoload-vendor.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/sello-utils.php';

use CfdiUtils\CfdiCreator40;
use CfdiUtils\Certificado\Certificado;

header('Content-Type: application/json; charset=utf-8');

$respuesta = [
    'success' => false, 
    'message' => '', 
    'xml_url' => '',
    'errores_validacion' => []
];

try {
    // ---------------------------------------------------------
    // 2. RECEPCIÓN Y VALIDACIÓN DE DATOS
    // ---------------------------------------------------------
    $input = file_get_contents('php://input');
    $datos = json_decode($input, true);
    
    if (empty($datos['id_factura'])) {
        throw new Exception("No se recibió el ID de la factura");
    }

    $id_factura = $datos['id_factura'];
    $db = new Database();
    $conn = $db->getConnection();

    // ---------------------------------------------------------
    // 3. RECUPERACIÓN DE DATOS (JOIN FACTURA + EMPRESA)
    // ---------------------------------------------------------
    $sql = "SELECT f.*, 
                   e.rfc as emisor_rfc, 
                   e.razon_social as emisor_nombre, 
                   e.reg_fiscal as emisor_regimen, 
                   e.cp as emisor_cp,
                   e.file_cer, 
                   e.file_key, 
                   e.clave as pass_key  -- Tu tabla dice 'clave' para la contraseña
            FROM facturas f
            JOIN empresas e ON f.id_empresa = e.id_empresa
            WHERE f.id_factura = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_factura]);
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$factura) throw new Exception("Factura no encontrada en BD");

    // Recuperar Conceptos
    $stmtDet = $conn->prepare("SELECT * FROM facturas_detalles WHERE id_factura = ?");
    $stmtDet->execute([$id_factura]);
    $conceptos = $stmtDet->fetchAll(PDO::FETCH_ASSOC);

    if (empty($conceptos)) throw new Exception("La factura no tiene conceptos/detalles.");

    // ---------------------------------------------------------
    // 4. PREPARACIÓN DE CERTIFICADOS (Paso Vital)
    // ---------------------------------------------------------
    $rutaCertificados = __DIR__ . '/../uploads/sellos/'; 
    $archivoCer = $rutaCertificados . $factura['file_cer'];
    $archivoKey = $rutaCertificados . $factura['file_key'];
    $passwordKey = SelloUtils::descifrarClave($factura['pass_key'], $factura['id_empresa']);

    if (!file_exists($archivoCer)) throw new Exception("No se encuentra el archivo .cer: " . $factura['file_cer']);
    if (!file_exists($archivoKey)) throw new Exception("No se encuentra el archivo .key: " . $factura['file_key']);
    if ($passwordKey === false || $passwordKey === '') throw new Exception('No se pudo recuperar la contraseña del sello digital.');

    // Creamos el objeto Certificado como indica la documentación
    $certificado = new Certificado($archivoCer);

    // ---------------------------------------------------------
    // 5. CREACIÓN DEL CFDI 4.0
    // ---------------------------------------------------------
    
    // Formato de fecha estricto: 2023-01-01T12:00:00
    $fechaEmision = date('Y-m-d\TH:i:s', strtotime($factura['fecha_emision']));

    // Atributos del Comprobante (Cabecera)
    $comprobanteAtributos = [
        'Serie'             => $factura['serie_interno'],
        'Folio'             => $factura['folio_interno'],
        'Fecha'             => $fechaEmision,
        'SubTotal'          => number_format($factura['subtotal'], 2, '.', ''),
        'Total'             => number_format($factura['total'], 2, '.', ''),
        'Moneda'            => $factura['moneda'],
        'TipoCambio'        => $factura['tipo_cambio'], // Solo si moneda != MXN, pero si es 1 se pone 1
        'TipoDeComprobante' => 'I', // Ingreso
        'Exportacion'       => $factura['exportacion'], // '01'
        'MetodoPago'        => $factura['metodo_pago'],
        'FormaPago'         => $factura['forma_pago'],
        'LugarExpedicion'   => $factura['lugar_expedicion']
    ];

    // Instanciamos CfdiCreator40 pasando atributos y el certificado
    $creator = new CfdiCreator40($comprobanteAtributos, $certificado);

    // Obtener el nodo raíz para trabajar (como dice la documentación)
    $comprobante = $creator->comprobante();

    // ---------------------------------------------------------
    // 6. AGREGAR EMISOR Y RECEPTOR
    // ---------------------------------------------------------

    // Emisor (RFC y Nombre se toman del certificado automáticamente, pero añadimos el Regimen)
    $comprobante->addEmisor([
        'RegimenFiscal' => $factura['emisor_regimen']
    ]);

    // Receptor
    $comprobante->addReceptor([
        'Rfc'                     => $factura['rfc_receptor'],
        'Nombre'                  => $factura['razon_social_receptor'],
        'UsoCFDI'                 => $factura['uso_cfdi'],
        'DomicilioFiscalReceptor' => $factura['domicilio_fiscal_receptor'], // CP del Receptor (Obligatorio en 4.0)
        'RegimenFiscalReceptor'   => $factura['regimen_fiscal_receptor']    // Obligatorio en 4.0
    ]);

    // ---------------------------------------------------------
    // 7. AGREGAR CONCEPTOS
    // ---------------------------------------------------------

    foreach ($conceptos as $item) {
        // Creamos el nodo Concepto
        $nodoConcepto = $comprobante->addConcepto([
            'ClaveProdServ'    => $item['clave_prod_serv'],
            'NoIdentificacion' => $item['no_identificacion'] ?? $item['id_detalle'],
            'Cantidad'         => number_format($item['cantidad'], 6, '.', ''),
            'ClaveUnidad'      => $item['clave_unidad'],
            'Unidad'           => $item['unidad'],
            'Descripcion'      => $item['descripcion'],
            'ValorUnitario'    => number_format($item['valor_unitario'], 2, '.', ''),
            'Importe'          => number_format($item['importe'], 2, '.', ''),
            'ObjetoImp'        => $item['objeto_imp'] // '02'
        ]);

        // Si es objeto de impuesto ('02'), agregamos el Traslado
        if ($item['objeto_imp'] === '02') {
            $nodoConcepto->addTraslado([
                'Base'       => number_format($item['impuesto_base'], 2, '.', ''),
                'Impuesto'   => $item['impuesto_tipo'], // '002'
                'TipoFactor' => 'Tasa',
                'TasaOCuota' => number_format($item['impuesto_tasa'], 6, '.', ''), // '0.160000'
                'Importe'    => number_format($item['impuesto_importe'], 2, '.', '')
            ]);
        }
    }

    // ---------------------------------------------------------
    // 8. CÁLCULOS GLOBALES Y SELLO (Helpers de la Librería)
    // ---------------------------------------------------------

    // Helper: Calcula y escribe los totales de impuestos en el nodo raíz
    // Esto evita que sumes los impuestos manualmente
    $creator->addSumasConceptos(null, 2);

    // Helper: Mueve las definiciones de namespaces al inicio (Recomendado por SAT y doc)
    $creator->moveSatDefinitionsToComprobante();

    // Helper: Genera Cadena Original y Firma (Sello)
    // Lee el archivo .key y usa la contraseña
    try {
        $creator->addSello($archivoKey, $passwordKey);
    } catch (Exception $e) {
        throw new Exception("Error al sellar XML (Verifique la contraseña del CSD): " . $e->getMessage());
    }

    // ---------------------------------------------------------
    // 9. VALIDACIÓN DE ESTRUCTURA LOCAL
    // ---------------------------------------------------------
    
    $asserts = $creator->validate();
    
    if ($asserts->hasErrors()) {
        $erroresTxt = [];
        foreach ($asserts->errors() as $error) {
            // Usamos explain() o __toString() para ver el detalle
            $erroresTxt[] = $error->getCode() . ': ' . $error->getStatus();
        }
        $respuesta['errores_validacion'] = $erroresTxt;
        throw new Exception("El XML no cumple con el estándar SAT 4.0");
    }

    // ---------------------------------------------------------
    // 10. GUARDAR ARCHIVO
    // ---------------------------------------------------------
    
    // Generar nombre de archivo
    $nombreArchivo = $factura['emisor_rfc'] . '_' . $factura['serie_interno'] . $factura['folio_interno'] . '.xml';
    $rutaRelativa = '/../uploads/xml_timbrados/' . $nombreArchivo;
    $rutaAbsoluta = __DIR__ . $rutaRelativa;

    // Crear carpeta si no existe
    if (!file_exists(dirname($rutaAbsoluta))) {
        mkdir(dirname($rutaAbsoluta), 0777, true);
    }

    // Método helper para guardar
    $creator->saveXml($rutaAbsoluta);

    // Actualizar BD
    $upd = $conn->prepare("UPDATE facturas SET xml_path = ? WHERE id_factura = ?");
    $upd->execute([$nombreArchivo, $id_factura]);

    $respuesta['success'] = true;
    $respuesta['message'] = 'XML Generado y Sellado correctamente. Listo para enviar al PAC.';
    $respuesta['xml_url'] = 'xml_timbrados/' . $nombreArchivo;

} catch (Exception $e) {
    $respuesta['success'] = false;
    $respuesta['message'] = $e->getMessage();
}

echo json_encode($respuesta);
?>