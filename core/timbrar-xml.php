<?php
// core/timbrar.php
ini_set('display_errors', 0);
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../api/DigiboxApi.php';
require_once __DIR__ . '/class/db.php';

$respuesta = ['success' => false, 'message' => ''];

try {
    // 1. Obtener ID Factura
    $input = file_get_contents('php://input');
    $datos = json_decode($input, true);
    $id_factura = $datos['id_factura'] ?? null;

    if (!$id_factura) throw new Exception("ID de factura no proporcionado");

    // 2. Buscar Ruta del XML sin timbrar
    $db = new Database();
    $conn = $db->getConnection();
    
    $stmt = $conn->prepare("SELECT xml_path FROM facturas WHERE id_factura = ? LIMIT 1");
    $stmt->execute([$id_factura]);
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$factura || empty($factura['xml_path'])) {
        throw new Exception("No se encontró el archivo XML generado en la BD");
    }

    $ruta_xml = __DIR__ . '/../uploads/xml_timbrados/' . $factura['xml_path'];

    if (!file_exists($ruta_xml)) {
        throw new Exception("El archivo físico no existe: " . $factura['xml_path']);
    }

    $xml_content = file_get_contents($ruta_xml);

    // 3. Conectar a API Digibox y Timbrar
    $digibox = new DigiboxApi();
    
    // Aquí ocurre la magia. Si falla, saltará al catch
    $xml_timbrado_str = $digibox->timbrar($xml_content);

    if (empty($xml_timbrado_str) || strpos($xml_timbrado_str, 'TFD') === false) {
        throw new Exception("La respuesta del PAC no parece un XML válido timbrado.");
    }

    // 4. Sobreescribir el archivo con el XML YA TIMBRADO (Con el nodo TimbreFiscalDigital)
    file_put_contents($ruta_xml, $xml_timbrado_str);

    // 5. Extraer UUID del XML Timbrado para guardar en BD
    $dom = new DOMDocument();
    // Suprimimos errores de formato al cargar para evitar warnings en logs
    $oldVal = libxml_use_internal_errors(true);
    $dom->loadXML($xml_timbrado_str);
    libxml_use_internal_errors($oldVal);

    $xpath = new DOMXPath($dom);
    $xpath->registerNamespace('tfd', 'http://www.sat.gob.mx/TimbreFiscalDigital');
    
    $uuid = '';
    $nodos = $xpath->query('//tfd:TimbreFiscalDigital');

    if ($nodos->length > 0) {
        $nodoTimbre = $nodos->item(0);
        // CORRECCIÓN: Verificamos que sea un Elemento antes de pedir atributos
        if ($nodoTimbre instanceof DOMElement) {
            $uuid = $nodoTimbre->getAttribute('UUID');
        }
    }

    if (empty($uuid)) {
        // Opcional: Si no hay UUID, algo salió mal aunque el PAC dijera 200 OK
        throw new Exception("No se pudo leer el UUID del XML timbrado.");
    }

    // 6. Actualizar Base de Datos
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

echo json_encode($respuesta);
?>