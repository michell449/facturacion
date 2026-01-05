<?php
// Script de prueba para ver el XML exacto que se envía a Finkok

require_once __DIR__ . '/core/class/db.php';

// Configuración
$finkokUser = 'michellflores822@gmail.com';
$finkokPass = 'Pankycontra2025.';
$wsdlUrl = 'https://demo-facturacion.finkok.com/servicios/soap/cancel.wsdl';

// Obtener factura de prueba
$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("
    SELECT f.*, e.rfc as rfc_emisor
    FROM facturas f
    INNER JOIN empresas e ON f.id_empresa = e.id_empresa
    WHERE f.id_factura = 163
");
$stmt->execute();
$factura = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<h2>Datos de la Factura</h2>";
echo "<pre>";
print_r($factura);
echo "</pre>";

$uuid = trim($factura['uuid']);
$rfcEmisor = trim($factura['rfc_emisor']);

echo "<h2>Datos a enviar</h2>";
echo "<pre>";
echo "UUID: [{$uuid}]\n";
echo "Longitud: " . strlen($uuid) . "\n";
echo "RFC Emisor: [{$rfcEmisor}]\n";
echo "Motivo: 02\n";
echo "</pre>";

// PROBAR DIFERENTES ESTRUCTURAS
echo "<h2>Prueba 1: Array simple</h2>";
echo "<pre>";
try {
    $soapClient = new SoapClient($wsdlUrl, [
        'trace' => true,
        'exceptions' => true,
        'cache_wsdl' => WSDL_CACHE_NONE,
        'stream_context' => stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ])
    ]);
    
    $params = [
        'username' => $finkokUser,
        'password' => $finkokPass,
        'taxpayer_id' => $rfcEmisor,
        'uuids' => [
            [
                'UUID' => $uuid,
                'Motivo' => '02',
                'FolioSustitucion' => ''
            ]
        ],
        'store_pending' => true
    ];
    
    echo "PARÁMETROS:\n";
    print_r($params);
    
    $response = $soapClient->cancel($params);
    
    echo "\n\n=== XML REQUEST ENVIADO ===\n";
    echo htmlspecialchars($soapClient->__getLastRequest());
    
    echo "\n\n=== XML RESPONSE RECIBIDO ===\n";
    echo htmlspecialchars($soapClient->__getLastResponse());
    
    echo "\n\n=== RESPUESTA PROCESADA ===\n";
    print_r($response);
    
} catch (SoapFault $e) {
    echo "ERROR SOAP: " . $e->getMessage() . "\n";
    echo "\n=== XML REQUEST ===\n";
    echo htmlspecialchars($soapClient->__getLastRequest());
    echo "\n\n=== XML RESPONSE ===\n";
    echo htmlspecialchars($soapClient->__getLastResponse());
}
echo "</pre>";

// PRUEBA 2: Con SoapVar
echo "<h2>Prueba 2: Con SoapVar</h2>";
echo "<pre>";
try {
    $soapClient2 = new SoapClient($wsdlUrl, [
        'trace' => true,
        'exceptions' => true,
        'cache_wsdl' => WSDL_CACHE_NONE,
        'stream_context' => stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ])
    ]);
    
    // Crear estructura con SoapVar
    $uuidData = new stdClass();
    $uuidData->UUID = $uuid;
    $uuidData->Motivo = '02';
    $uuidData->FolioSustitucion = '';
    
    $params2 = [
        'username' => $finkokUser,
        'password' => $finkokPass,
        'taxpayer_id' => $rfcEmisor,
        'uuids' => [$uuidData],
        'store_pending' => true
    ];
    
    echo "PARÁMETROS:\n";
    print_r($params2);
    
    $response2 = $soapClient2->cancel($params2);
    
    echo "\n\n=== XML REQUEST ENVIADO ===\n";
    echo htmlspecialchars($soapClient2->__getLastRequest());
    
    echo "\n\n=== XML RESPONSE RECIBIDO ===\n";
    echo htmlspecialchars($soapClient2->__getLastResponse());
    
    echo "\n\n=== RESPUESTA PROCESADA ===\n";
    print_r($response2);
    
} catch (SoapFault $e) {
    echo "ERROR SOAP: " . $e->getMessage() . "\n";
    echo "\n=== XML REQUEST ===\n";
    echo htmlspecialchars($soapClient2->__getLastRequest());
    echo "\n\n=== XML RESPONSE ===\n";
    echo htmlspecialchars($soapClient2->__getLastResponse());
}
echo "</pre>";

// PRUEBA 3: Consultando el WSDL para ver qué espera
echo "<h2>Información del WSDL</h2>";
echo "<pre>";
try {
    $soapClient3 = new SoapClient($wsdlUrl, [
        'cache_wsdl' => WSDL_CACHE_NONE,
        'stream_context' => stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ])
    ]);
    
    echo "FUNCIONES DISPONIBLES:\n";
    print_r($soapClient3->__getFunctions());
    
    echo "\n\nTIPOS DE DATOS:\n";
    print_r($soapClient3->__getTypes());
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
echo "</pre>";
?>
