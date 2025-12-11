<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/autoload-vendor.php';
header('Content-Type: application/json; charset=utf-8');

use CfdiUtils\CfdiCreator40;
use CfdiUtils\Certificado\Certificado;
use CfdiUtils\Nodes\Node;
use CfdiUtils\Nodes\Nodes;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtils\XmlResolver\XmlResolver;



function generarXmlFactura($id_factura) {
    $db = new Database();
    $conn = $db->getConnection();
    
    // ---------------------------------------------------
    // 1. RECUPERAR DATOS DE LA BD
    // ---------------------------------------------------

    // A. Datos de Cabecera y Emisor
    $sql = "SELECT f.*, 
                   e.rfc as emisor_rfc, e.razon_social as emisor_nombre, 
                   e.reg_fiscal as emisor_regimen, e.cp as emisor_cp,
                   e.file_cer, e.file_key, e.pass_key
            FROM facturas f
            JOIN empresas e ON f.id_empresa = e.id_empresa
            WHERE f.id_factura = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_factura]);
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$factura) throw new Exception("Factura no encontrada");

    // B. Datos de Conceptos (Detalles)
    $stmtDet = $conn->prepare("SELECT * FROM facturas_detalles WHERE id_factura = ?");
    $stmtDet->execute([$id_factura]);
    $conceptos = $stmtDet->fetchAll(PDO::FETCH_ASSOC);

    // ---------------------------------------------------
    // 2. CONFIGURACIÓN DEL CREATOR (CABECERA)
    // ---------------------------------------------------
    
    // Formatear fecha al estándar ISO 8601 (requerido por SAT)
    // Ejemplo BD: 2023-10-25 15:30:00 -> XML: 2023-10-25T15:30:00
    $fecha = date('Y-m-d\TH:i:s', strtotime($factura['fecha_emision']));

    $creator = new CfdiCreator40([
        'Serie' => $factura['serie_interno'],
        'Folio' => $factura['folio_interno'],
        'Fecha' => $fecha,
        'Sello' => '', // Se calculará automáticamente
        'NoCertificado' => '', // Se llenará al cargar el certificado
        'Certificado' => '', // Se llenará al cargar el certificado
        'SubTotal' => number_format($factura['subtotal'], 2, '.', ''),
        'Moneda' => $factura['moneda'],
        'Total' => number_format($factura['total'], 2, '.', ''),
        'TipoDeComprobante' => 'I', // Ingreso
        'Exportacion' => '01', // 01 = No aplica
        'MetodoPago' => $factura['metodo_pago'],
        'LugarExpedicion' => $factura['lugar_expedicion'], // CP del Emisor
        'FormaPago' => $factura['forma_pago']
    ]);

    // ---------------------------------------------------
    // 3. AGREGAR EMISOR Y RECEPTOR
    // ---------------------------------------------------

    $creator->comprobante()->addEmisor([
        'Rfc' => $factura['emisor_rfc'],
        'Nombre' => $factura['emisor_nombre'],
        'RegimenFiscal' => $factura['emisor_regimen']
    ]);

    $creator->comprobante()->addReceptor([
        'Rfc' => $factura['rfc_receptor'],
        'Nombre' => $factura['razon_social_receptor'],
        'UsoCFDI' => $factura['uso_cfdi'],
        'DomicilioFiscalReceptor' => $factura['domicilio_fiscal_receptor'], // CP Cliente (Critico en 4.0)
        'RegimenFiscalReceptor' => $factura['regimen_fiscal_receptor']     // Critico en 4.0
    ]);

    // ---------------------------------------------------
    // 4. AGREGAR CONCEPTOS E IMPUESTOS
    // ---------------------------------------------------

    foreach ($conceptos as $item) {
        // Crear el Concepto
        $concepto = $creator->comprobante()->addConcepto([
            'ClaveProdServ' => $item['clave_prod_serv'],
            'NoIdentificacion' => $item['id_detalle'], // O tu SKU interno
            'Cantidad' => number_format($item['cantidad'], 6, '.', ''), // SAT permite hasta 6 decimales
            'ClaveUnidad' => $item['clave_unidad'],
            'Unidad' => $item['unidad'],
            'Descripcion' => $item['descripcion'],
            'ValorUnitario' => number_format($item['valor_unitario'], 2, '.', ''),
            'Importe' => number_format($item['importe'], 2, '.', ''),
            'ObjetoImp' => $item['objeto_imp'] // '02' = Sí objeto de impuesto
        ]);

        // Si el objeto de impuesto es '02', agregamos los traslados (IVA)
        if ($item['objeto_imp'] === '02') {
            $concepto->addTraslado([
                'Base' => number_format($item['impuesto_base'], 2, '.', ''),
                'Impuesto' => $item['impuesto_tipo'], // '002' (IVA)
                'TipoFactor' => 'Tasa',
                'TasaOCuota' => number_format($item['impuesto_tasa'], 6, '.', ''), // 0.160000
                'Importe' => number_format($item['impuesto_importe'], 2, '.', '')
            ]);
        }
    }

    // Calcular la suma de impuestos globales (obligatorio poner el nodo de totales al final)
    // La librería tiene un helper para sumar automáticamente lo que pusiste en los conceptos
    $creator->addSumasConceptos(null, 2); 

    // ---------------------------------------------------
    // 5. CARGAR CERTIFICADOS (CSD) Y SELLAR
    // ---------------------------------------------------

    // Rutas a los archivos (Definidas en tu BD o hardcodeadas para pruebas)
    $rutaCer = __DIR__ . '/../certificados/' . $factura['file_cer'];
    $rutaKey = __DIR__ . '/../certificados/' . $factura['file_key'];
    $password = $factura['pass_key']; // Ojalá esté desencriptada aquí

    if (!file_exists($rutaCer) || !file_exists($rutaKey)) {
        throw new Exception("Archivos CSD no encontrados en el servidor.");
    }

    // Cargar certificado al XML (pone el NoCertificado y el Certificado en Base64)
    $creator->putCertificado(new Certificado($rutaCer));

    // Generar la Cadena Original y el Sello con la Llave Privada
    $creator->addSello($rutaKey, $password);

    // ---------------------------------------------------
    // 6. VALIDAR ESTRUCTURA (LOCAL)
    // ---------------------------------------------------
    
    $asserts = $creator->validate();
    if ($asserts->hasErrors()) {
        $errores = [];
        foreach ($asserts->errors() as $error) {
            $errores[] = $error->getCode();
        }
        throw new Exception("Error al crear XML: " . implode(", ", $errores));
    }

    // ---------------------------------------------------      
    // 7. GUARDAR O RETORNAR EL XML SELLADO
    // ---------------------------------------------------
    
    // Obtener el string XML completo
    $xmlString = $creator->asXml();

    // Guardar temporalmente en una carpeta 'xml_sellados'
    $nombreArchivo = "pre_factura_" . $factura['folio_interno'] . ".xml";
    $rutaGuardado = __DIR__ . '/../xml_pendientes/' . $nombreArchivo;
    
    file_put_contents($rutaGuardado, $xmlString);

    return [
        'success' => true,
        'xml_string' => $xmlString,
        'ruta' => $rutaGuardado
    ];
}