<?php
// Script de prueba para verificar formato de UUID y estructura SOAP

require_once __DIR__ . '/core/class/db.php';

// Conectar a la base de datos
$db = new Database();
$conn = $db->getConnection();

// Obtener una factura timbrada de ejemplo
$stmt = $conn->prepare("
    SELECT id_factura, uuid, rfc_emisor, estatus
    FROM facturas 
    WHERE estatus = 'timbrada' 
    AND uuid IS NOT NULL 
    LIMIT 1
");
$stmt->execute();
$factura = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$factura) {
    die("No hay facturas timbradas para probar");
}

echo "<h2>Factura de Prueba</h2>";
echo "<pre>";
echo "ID: " . $factura['id_factura'] . "\n";
echo "UUID Original: [" . $factura['uuid'] . "]\n";
echo "Longitud: " . strlen($factura['uuid']) . "\n";
echo "RFC Emisor: " . $factura['rfc_emisor'] . "\n";
echo "Estatus: " . $factura['estatus'] . "\n";
echo "</pre>";

// Probar el formato
$uuid = $factura['uuid'];
echo "<h3>Análisis del UUID</h3>";
echo "<pre>";
echo "UUID sin procesar: " . var_export($uuid, true) . "\n";
echo "Tiene espacios al inicio/final: " . (trim($uuid) !== $uuid ? "SÍ" : "NO") . "\n";
echo "Tiene saltos de línea: " . (strpos($uuid, "\n") !== false ? "SÍ" : "NO") . "\n";
echo "UUID limpio: " . trim($uuid) . "\n";
echo "UUID uppercase: " . strtoupper(trim($uuid)) . "\n";

// Validar formato con regex
$pattern = '/^[A-F0-9]{8}-[A-F0-9]{4}-[A-F0-9]{4}-[A-F0-9]{4}-[A-F0-9]{12}$/i';
$esValido = preg_match($pattern, trim($uuid));
echo "\nFormato válido: " . ($esValido ? "SÍ" : "NO") . "\n";

if (!$esValido) {
    echo "PROBLEMA: El UUID no tiene el formato esperado\n";
    echo "Formato esperado: XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX\n";
}
echo "</pre>";

// Probar estructura del array para SOAP
echo "<h3>Estructura del Array para Finkok</h3>";
echo "<pre>";

$motivoCancelacion = '02';
$uuidSustitucion = '';

$uuidArray = [
    [
        'UUID' => strtoupper(trim($uuid)),
        'Motivo' => $motivoCancelacion,
        'FolioSustitucion' => $uuidSustitucion
    ]
];

echo "Array PHP:\n";
print_r($uuidArray);

echo "\nJSON (para ver estructura):\n";
echo json_encode($uuidArray, JSON_PRETTY_PRINT);

echo "\n\nCómo se convertiría a XML SOAP:\n";
echo htmlspecialchars('
<uuids>
    <item>
        <UUID>' . strtoupper(trim($uuid)) . '</UUID>
        <Motivo>' . $motivoCancelacion . '</Motivo>
        <FolioSustitucion>' . $uuidSustitucion . '</FolioSustitucion>
    </item>
</uuids>
');

echo "</pre>";

// Mostrar lo que espera Finkok
echo "<h3>Lo que Finkok espera recibir</h3>";
echo "<pre>";
echo htmlspecialchars('
<cancel>
    <username>usuario@correo.com</username>
    <password>contraseña</password>
    <taxpayer_id>RFC_EMISOR</taxpayer_id>
    <uuids>
        <UUID>
            <UUID>' . strtoupper(trim($uuid)) . '</UUID>
            <Motivo>02</Motivo>
            <FolioSustitucion></FolioSustitucion>
        </UUID>
    </uuids>
    <store_pending>true</store_pending>
</cancel>
');
echo "</pre>";

?>
