<?php
/**
 * Script de prueba para verificar XML antes de timbrar
 * Ubicación: core/verificar-xml-factura.php
 */

require_once __DIR__ . '/class/db.php';

header('Content-Type: text/html; charset=utf-8');

$id_factura = $_GET['id_factura'] ?? null;

if (!$id_factura) {
    die('Especifica ?id_factura=X en la URL');
}

$db = new Database();
$conn = $db->getConnection();

// Obtener info de la factura
$stmt = $conn->prepare("SELECT * FROM facturas WHERE id_factura = ?");
$stmt->execute([$id_factura]);
$factura = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$factura) {
    die("Factura $id_factura no encontrada");
}

echo "<h1>Verificación de Factura #$id_factura</h1>";

echo "<h2>Datos de la Factura</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Campo</th><th>Valor</th></tr>";
foreach ($factura as $campo => $valor) {
    echo "<tr><td><strong>$campo</strong></td><td>" . htmlspecialchars($valor) . "</td></tr>";
}
echo "</table>";

// Obtener detalles
$stmtDet = $conn->prepare("SELECT * FROM facturas_detalles WHERE id_factura = ?");
$stmtDet->execute([$id_factura]);
$detalles = $stmtDet->fetchAll(PDO::FETCH_ASSOC);

echo "<h2>Conceptos/Productos ($" . count($detalles) . " items)</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Descripción</th><th>Cantidad</th><th>Precio</th><th>Importe</th><th>IVA</th></tr>";
foreach ($detalles as $det) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($det['descripcion']) . "</td>";
    echo "<td>" . $det['cantidad'] . "</td>";
    echo "<td>$" . number_format($det['valor_unitario'], 2) . "</td>";
    echo "<td>$" . number_format($det['importe'], 2) . "</td>";
    echo "<td>$" . number_format($det['impuesto_importe'], 2) . "</td>";
    echo "</tr>";
}
echo "</table>";

// Verificar si existe el XML
if (!empty($factura['xml_path'])) {
    $rutaXML = __DIR__ . '/../uploads/xml_timbrados/' . $factura['xml_path'];
    
    echo "<h2>XML Generado</h2>";
    
    if (file_exists($rutaXML)) {
        $xmlContent = file_get_contents($rutaXML);
        
        echo "<p><strong>Ruta:</strong> " . htmlspecialchars($factura['xml_path']) . "</p>";
        echo "<p><strong>Tamaño:</strong> " . number_format(strlen($xmlContent)) . " bytes</p>";
        
        // Validar XML
        libxml_use_internal_errors(true);
        $xmlDoc = simplexml_load_string($xmlContent);
        
        if ($xmlDoc === false) {
            echo "<div style='background: #ffcccc; padding: 10px; border: 1px solid red;'>";
            echo "<h3>⚠️ XML INVÁLIDO</h3>";
            $errors = libxml_get_errors();
            foreach ($errors as $error) {
                echo "<p>Línea $error->line: " . htmlspecialchars($error->message) . "</p>";
            }
            libxml_clear_errors();
            echo "</div>";
        } else {
            echo "<div style='background: #ccffcc; padding: 10px; border: 1px solid green;'>";
            echo "<h3>✅ XML VÁLIDO</h3>";
            
            // Extraer datos clave
            $namespaces = $xmlDoc->getNamespaces(true);
            $cfdi = $xmlDoc->children($namespaces['cfdi']);
            
            echo "<p><strong>Versión:</strong> " . $cfdi['Version'] . "</p>";
            echo "<p><strong>Serie:</strong> " . $cfdi['Serie'] . "</p>";
            echo "<p><strong>Folio:</strong> " . $cfdi['Folio'] . "</p>";
            echo "<p><strong>Total:</strong> $" . $cfdi['Total'] . "</p>";
            echo "</div>";
        }
        
        // Mostrar XML formateado
        echo "<h3>Contenido XML (formateado)</h3>";
        echo "<textarea style='width: 100%; height: 400px; font-family: monospace;'>";
        
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xmlContent);
        echo htmlspecialchars($dom->saveXML());
        
        echo "</textarea>";
        
        // Botón para intentar timbrar
        echo "<hr>";
        echo "<h3>Acciones</h3>";
        echo "<button onclick='timbrarAhora()' style='padding: 10px 20px; font-size: 16px; cursor: pointer;'>🔖 Intentar Timbrar Ahora</button>";
        echo "<div id='resultado'></div>";
        
        echo "
        <script>
        function timbrarAhora() {
            document.getElementById('resultado').innerHTML = '<p>Timbrando...</p>';
            
            fetch('../core/timbrar-xml.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_factura: $id_factura })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('resultado').innerHTML = 
                        '<div style=\"background: #ccffcc; padding: 10px; border: 1px solid green;\">' +
                        '<h3>✅ Timbrado Exitoso</h3>' +
                        '<p><strong>UUID:</strong> ' + data.uuid + '</p>' +
                        '<p><strong>Fecha:</strong> ' + data.fecha + '</p>' +
                        '</div>';
                } else {
                    document.getElementById('resultado').innerHTML = 
                        '<div style=\"background: #ffcccc; padding: 10px; border: 1px solid red;\">' +
                        '<h3>❌ Error al Timbrar</h3>' +
                        '<p>' + data.message + '</p>' +
                        (data.detail ? '<p><small>' + data.detail + '</small></p>' : '') +
                        '</div>';
                }
            })
            .catch(err => {
                document.getElementById('resultado').innerHTML = 
                    '<div style=\"background: #ffcccc; padding: 10px;\"><p>Error: ' + err + '</p></div>';
            });
        }
        </script>
        ";
        
    } else {
        echo "<p style='color: red;'><strong>❌ Archivo XML no encontrado:</strong> $rutaXML</p>";
    }
} else {
    echo "<p style='color: orange;'><strong>⚠️ XML no generado aún</strong></p>";
    echo "<p><a href='generar-xml.php?id_factura=$id_factura'>Generar XML ahora</a></p>";
}

echo "<hr>";
echo "<p><a href='?id_factura=$id_factura'>🔄 Recargar</a></p>";
?>
