<?php
// core/timbrar-xml.php
ini_set('display_errors', 0);
error_reporting(E_ALL);
ob_start(); // Buffer para evitar salidas sucias
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../api/DigiboxApi.php';
require_once __DIR__ . '/class/db.php';

$respuesta = ['success' => false, 'message' => ''];

try {
    // 1. Obtener ID
    $input = file_get_contents('php://input');
    $datos = json_decode($input, true);
    $id_factura = $datos['id_factura'] ?? null;

    if (!$id_factura) throw new Exception("ID de factura no proporcionado");

    // 2. Buscar Ruta del XML generado (pre-sellado)
    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("SELECT xml_path FROM facturas WHERE id_factura = ? LIMIT 1");
    $stmt->execute([$id_factura]);
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$factura || empty($factura['xml_path'])) {
        throw new Exception("No se encontró registro del XML en la BD.");
    }

    $ruta_xml = __DIR__ . '/../uploads/xml_timbrados/' . $factura['xml_path'];

    if (!file_exists($ruta_xml)) {
        throw new Exception("El archivo XML físico no existe.");
    }

    // 3. Leer contenido
    $xml_content = file_get_contents($ruta_xml);
    if (empty($xml_content)) throw new Exception("El archivo XML está vacío.");

    // 4. Conectar y Timbrar
    $digibox = new DigiboxApi();

    // Digibox V4 devuelve el XML STRING timbrado si es exitoso
    $xml_timbrado_str = $digibox->timbrar($xml_content);

    // 5. Sobreescribir el archivo con el XML TIMBRADO
    file_put_contents($ruta_xml, $xml_timbrado_str);

    // 6. Extraer UUID del XML (Usando DOMDocument)
    $dom = new DOMDocument();
    $dom->loadXML($xml_timbrado_str);

    $xpath = new DOMXPath($dom);
    $xpath->registerNamespace('tfd', 'http://www.sat.gob.mx/TimbreFiscalDigital');

    $uuid = '';
    $nodos = $xpath->query('//tfd:TimbreFiscalDigital');

    if ($nodos->length > 0) {
        $nodoTimbre = $nodos->item(0);

        if ($nodoTimbre instanceof DOMElement) {
            $uuid = $nodoTimbre->getAttribute('UUID');
        }
    }

    if (empty($uuid)) {
        throw new Exception("Se recibió respuesta del PAC pero no se pudo leer el UUID.");
    }

    // 7. Actualizar BD
    $sqlUpd = "UPDATE facturas SET 
                uuid = ?, 
                estado = 'timbrado', 
                xml_timbrado = 1,
                fecha_timbrado = NOW() 
               WHERE id_factura = ?";
    $stmtUpd = $conn->prepare($sqlUpd);
    $stmtUpd->execute([$uuid, $id_factura]);

    $respuesta['success'] = true;
    $respuesta['message'] = 'Factura timbrada exitosamente';
    $respuesta['uuid'] = $uuid;
    $respuesta['xml_url'] = 'uploads/xml_timbrados/' . $factura['xml_path'];
} catch (Exception $e) {
    $respuesta['success'] = false;
    $respuesta['message'] = $e->getMessage();
}

ob_end_clean();
echo json_encode($respuesta);
