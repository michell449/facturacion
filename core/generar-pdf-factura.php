<?php
/**
 * Genera un PDF de la factura usando la plantilla configurada
 * Requiere: composer require mpdf/mpdf o dompdf/dompdf
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Mpdf\Mpdf;

// Validar sesión
$id_usuario = $_SESSION['usuario_id'] ?? $_SESSION['USR_ID'] ?? null;

if (!$id_usuario) {
    die('Sesión no válida. <a href="../index.php">Volver al inicio</a>');
}

$id_factura = isset($_GET['id_factura']) ? (int)$_GET['id_factura'] : 0;

if ($id_factura <= 0) {
    die('ID de factura no válido.');
}

// Obtener ID de sucursal de la factura
require_once __DIR__ . '/class/db.php';
$db = new Database();
$conn = $db->getConnection();

// Obtener datos básicos de la factura para encontrar la sucursal
$stmt = $conn->prepare("
    SELECT f.*, t.id_empresa as id_sucursal
    FROM facturas f
    LEFT JOIN tickets t ON f.id_ticket = t.id_ticket
    WHERE f.id_factura = ?
");
$stmt->execute([$id_factura]);
$factura = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$factura) {
    die('Factura no encontrada.');
}

$id_sucursal = $factura['id_sucursal'] ?? 0;

// Obtener datos para generar el HTML
$urlDatos = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . "/obtener-datos-plantilla.php?preview=0&id_factura={$id_factura}&id_sucursal={$id_sucursal}";
$datosJson = file_get_contents($urlDatos);
$datos = json_decode($datosJson, true);

if (!$datos || isset($datos['error'])) {
    die('Error al obtener datos de la factura: ' . ($datos['error'] ?? 'Desconocido'));
}

// Generar HTML desde los datos
$html = generarHTMLFactura($datos, $factura);

try {
    // Crear instancia de mPDF
    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'Letter',
        'margin_left' => 10,
        'margin_right' => 10,
        'margin_top' => 10,
        'margin_bottom' => 10,
        'margin_header' => 5,
        'margin_footer' => 5
    ]);
    
    // Escribir el HTML
    $mpdf->WriteHTML($html);
    
    // Definir nombre del archivo
    $folio = $factura['folio'] ?? 'FACTURA';
    $serie = $factura['serie'] ?? '';
    $nombreArchivo = "Factura_{$serie}{$folio}.pdf";
    
    // Guardar el PDF si se requiere
    $guardar = isset($_GET['guardar']) && $_GET['guardar'] == 1;
    
    if ($guardar) {
        // Crear directorio si no existe
        $dirPDF = __DIR__ . '/../uploads/facturas/pdf/';
        if (!is_dir($dirPDF)) {
            mkdir($dirPDF, 0755, true);
        }
        
        $rutaPDF = $dirPDF . $nombreArchivo;
        $mpdf->Output($rutaPDF, 'F');
        
        // Actualizar ruta en la base de datos
        $stmtUpdate = $conn->prepare("UPDATE facturas SET pdf_file = ? WHERE id_factura = ?");
        $stmtUpdate->execute(['uploads/facturas/pdf/' . $nombreArchivo, $id_factura]);
        
        // Mostrar o descargar
        $mpdf->Output($nombreArchivo, 'I'); // I = inline, D = download
    } else {
        // Solo mostrar
        $mpdf->Output($nombreArchivo, 'I');
    }
    
} catch (Exception $e) {
    die('Error al generar PDF: ' . $e->getMessage());
}

function generarHTMLFactura($data, $factura) {
    $config = $data['config'] ?? [];
    $emisor = $data['emisor'] ?? [];
    $receptor = $data['receptor'] ?? [];
    $facturaData = $data['factura'] ?? [];
    $conceptos = $data['conceptos'] ?? [];
    $totales = $data['totales'] ?? [];
    
    $colorPrimario = $config['color_primario'] ?? '#0d6efd';
    $colorSecundario = $config['color_secundario'] ?? '#6c757d';
    $tipoLetra = $config['tipo_letra'] ?? 'Arial';
    $tamanoLetra = ($config['tamano_letra'] ?? '12') . 'pt';
    
    ob_start();
    ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: <?php echo $tipoLetra; ?>, sans-serif; font-size: <?php echo $tamanoLetra; ?>; margin: 20px; }
        .header { border-bottom: 3px solid <?php echo $colorPrimario; ?>; padding-bottom: 20px; margin-bottom: 20px; }
        .info-box { border: 2px solid <?php echo $colorPrimario; ?>; padding: 15px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th { background: <?php echo $colorPrimario; ?>; color: white; padding: 10px; text-align: left; }
        td { border: 1px solid #ddd; padding: 8px; }
        .totales { background: <?php echo $colorSecundario; ?>; color: white; padding: 20px; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2><?php echo htmlspecialchars($emisor['nombre']); ?></h2>
        <p>RFC: <strong><?php echo htmlspecialchars($emisor['rfc']); ?></strong></p>
        <p><?php echo htmlspecialchars($emisor['direccion']); ?></p>
        <div style="float: right; margin-top: -80px;">
            <h1>FACTURA</h1>
            <p>Folio: <strong><?php echo htmlspecialchars($facturaData['folio']); ?></strong></p>
            <p>Fecha: <?php echo date('d/m/Y', strtotime($facturaData['fecha_e'])); ?></p>
        </div>
    </div>
    
    <div style="width: 48%; display: inline-block; vertical-align: top;">
        <div class="info-box">
            <h4>EMISOR</h4>
            <p><strong><?php echo htmlspecialchars($emisor['nombre']); ?></strong></p>
            <p>RFC: <?php echo htmlspecialchars($emisor['rfc']); ?></p>
            <p>Régimen: <?php echo htmlspecialchars($emisor['regimen']); ?></p>
        </div>
    </div>
    
    <div style="width: 48%; display: inline-block; vertical-align: top; margin-left: 2%;">
        <div class="info-box">
            <h4>RECEPTOR</h4>
            <p><strong><?php echo htmlspecialchars($receptor['nombre']); ?></strong></p>
            <p>RFC: <?php echo htmlspecialchars($receptor['rfc']); ?></p>
            <p>Uso CFDI: <?php echo htmlspecialchars($facturaData['uso_cfdi']); ?></p>
        </div>
    </div>
    
    <h4>CONCEPTOS</h4>
    <table>
        <thead>
            <tr>
                <th>Cant.</th>
                <th>Clave</th>
                <th>Descripción</th>
                <th class="text-right">P. Unitario</th>
                <th class="text-right">Importe</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($conceptos as $concepto): ?>
            <tr>
                <td><?php echo number_format($concepto['cantidad'], 2); ?></td>
                <td><?php echo htmlspecialchars($concepto['clave_producto']); ?></td>
                <td><?php echo htmlspecialchars($concepto['descripcion']); ?></td>
                <td class="text-right">$<?php echo number_format($concepto['precio_unitario'], 2); ?></td>
                <td class="text-right">$<?php echo number_format($concepto['importe'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div style="width: 60%; display: inline-block; vertical-align: top;">
        <p><strong>Importe con letra:</strong></p>
        <p><?php echo htmlspecialchars($totales['totalLetra']); ?></p>
        <p><strong>Forma de Pago:</strong> <?php echo htmlspecialchars($facturaData['form_pago']); ?></p>
        <p><strong>Método de Pago:</strong> <?php echo htmlspecialchars($facturaData['met_pago']); ?></p>
    </div>
    
    <div style="width: 38%; display: inline-block; vertical-align: top;">
        <div class="totales">
            <p>Subtotal: <span style="float: right;">$<?php echo number_format($totales['subtotal'], 2); ?></span></p>
            <p>IVA: <span style="float: right;">$<?php echo number_format($totales['iva'], 2); ?></span></p>
            <h3>TOTAL: <span style="float: right;">$<?php echo number_format($totales['total'], 2); ?></span></h3>
        </div>
    </div>
    
    <div style="margin-top: 20px; border-top: 1px solid #ddd; padding-top: 20px;">
        <h4>TIMBRE FISCAL DIGITAL</h4>
        <p><strong>UUID:</strong> <?php echo htmlspecialchars($facturaData['uuid']); ?></p>
        <p><strong>No. Certificado SAT:</strong> <?php echo htmlspecialchars($factura['no_certificado'] ?? '00000000000000000000'); ?></p>
    </div>
    
    <div style="text-align: center; margin-top: 30px; font-size: 10px; color: #666;">
        <p>Este documento es una representación impresa de un CFDI versión 4.0</p>
    </div>
</body>
</html>
    <?php
    return ob_get_clean();
}
?>
