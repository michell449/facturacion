<?php
/**
 * Generación de PDF de facturas para envío automático.
 */

use Mpdf\Mpdf;
use Mpdf\QrCode\Output;
use Mpdf\QrCode\Output\Svg;
use Mpdf\QrCode\QrCode;

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../autoload-vendor.php';

/**
 * Genera (o reutiliza) el PDF de una factura y devuelve rutas relativa y absoluta.
 */
function facturaGenerarPdfArchivo(PDO $conn, int $idFactura): array
{
    $stmt = $conn->prepare("SELECT 
            f.*,
            e.id_usuario,
            e.nombre AS nombre_emisor,
            e.razon_social AS razon_social_emisor,
            e.rfc AS rfc_emisor,
            e.direccion AS direccion_emisor,
            e.colonia AS colonia_emisor,
            e.cp AS cp_emisor,
            e.reg_fiscal AS regimen_fiscal_emisor
        FROM facturas f
        INNER JOIN empresas e ON f.id_empresa = e.id_empresa
        WHERE f.id_factura = ?");
    $stmt->execute([$idFactura]);
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$factura) {
        throw new Exception('Factura no encontrada.');
    }

    $basePath = dirname(__DIR__, 2);
    $rutaExistente = $factura['pdf_path'] ?? '';
    if ($rutaExistente) {
        $rutaAbsoluta = $basePath . '/' . $rutaExistente;
        if (file_exists($rutaAbsoluta)) {
            return [
                'relative' => $rutaExistente,
                'absolute' => $rutaAbsoluta
            ];
        }
    }

    // Configuración visual
    $stmtConfig = $conn->prepare("SELECT * FROM config_facturas WHERE id_usuario = ? AND id_sucursal = ? ORDER BY fecha_actualizacion DESC LIMIT 1");
    $stmtConfig->execute([(int)$factura['id_usuario'], (int)$factura['id_empresa']]);
    $config = $stmtConfig->fetch(PDO::FETCH_ASSOC) ?: [
        'color_primario' => '#0d6efd',
        'color_secundario' => '#6c757d',
        'tipo_letra' => 'Arial',
        'tamano_letra' => 12,
        'leyenda_factura' => 'Este documento es una representación impresa de un CFDI',
        'logo_url' => null
    ];

    $conceptos = facturaObtenerConceptos($conn, $idFactura);
    if (empty($conceptos)) {
        $conceptos = [[
            'cantidad' => 1,
            'clave_producto' => '01010101',
            'unidad' => 'ACT',
            'descripcion' => 'Concepto no especificado',
            'precio_unitario' => (float)($factura['subtotal'] ?? 0),
            'importe' => (float)($factura['subtotal'] ?? 0),
            'impuesto' => (float)($factura['impuestos_trasladados'] ?? 0)
        ]];
    }

    $html = facturaGenerarHtmlBasico($factura, $config, $conceptos);

    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'Letter',
        'margin_left' => 10,
        'margin_right' => 10,
        'margin_top' => 10,
        'margin_bottom' => 15,
        'default_font' => $config['tipo_letra'] ?? 'Arial'
    ]);

    $mpdf->WriteHTML($html);

    $serie = $factura['serie_interno'] ?? 'A';
    $folioNumero = $factura['folio_interno'] ?? 0;
    $folio = str_pad((string)$folioNumero, 6, '0', STR_PAD_LEFT);
    $nombreArchivo = "Factura_{$serie}{$folio}.pdf";

    $directorio = $basePath . '/uploads/facturas_pdf/';
    if (!is_dir($directorio)) {
        mkdir($directorio, 0755, true);
    }

    $rutaAbsoluta = $directorio . $nombreArchivo;
    $mpdf->Output($rutaAbsoluta, 'F');

    $rutaRelativa = 'uploads/facturas_pdf/' . $nombreArchivo;

    $stmtUpdate = $conn->prepare('UPDATE facturas SET pdf_path = ? WHERE id_factura = ?');
    $stmtUpdate->execute([$rutaRelativa, $idFactura]);

    return [
        'relative' => $rutaRelativa,
        'absolute' => $rutaAbsoluta
    ];
}

/**
 * Obtiene conceptos de la factura.
 */
function facturaObtenerConceptos(PDO $conn, int $idFactura): array
{
    $stmt = $conn->prepare('SELECT clave_prod_serv, descripcion, unidad, cantidad, valor_unitario, importe, impuesto_importe FROM facturas_detalles WHERE id_factura = ?');
    $stmt->execute([$idFactura]);
    $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $conceptos = [];
    foreach ($detalles as $detalle) {
        $conceptos[] = [
            'cantidad' => (float)$detalle['cantidad'],
            'clave_producto' => $detalle['clave_prod_serv'],
            'unidad' => $detalle['unidad'],
            'descripcion' => $detalle['descripcion'],
            'precio_unitario' => (float)$detalle['valor_unitario'],
            'importe' => (float)$detalle['importe'],
            'impuesto' => (float)($detalle['impuesto_importe'] ?? 0)
        ];
    }

    return $conceptos;
}

/**
 * Genera HTML simplificado para el PDF.
 */
function facturaGenerarHtmlBasico(array $factura, array $config, array $conceptos): string
{
    $colorPrimario = $config['color_primario'] ?? '#0d6efd';
    $colorSecundario = $config['color_secundario'] ?? '#6c757d';
    $tamanoLetra = (int)($config['tamano_letra'] ?? 12);

    $logoPath = '';
    if (!empty($config['logo_url'])) {
        $rutaCompletaLogo = dirname(__DIR__, 2) . '/' . ltrim($config['logo_url'], '/');
        if (file_exists($rutaCompletaLogo)) {
            $logoPath = $rutaCompletaLogo;
        }
    }

    $folio = ($factura['serie_interno'] ?? 'A') . str_pad((string)($factura['folio_interno'] ?? 0), 6, '0', STR_PAD_LEFT);
    $fechaEmision = isset($factura['fecha_emision']) ? date('d/m/Y H:i', strtotime($factura['fecha_emision'])) : '';
    $fechaTimbrado = !empty($factura['fecha_timbrado']) ? date('d/m/Y H:i', strtotime($factura['fecha_timbrado'])) : '';
    $totalLetras = facturaTotalEnLetras((float)($factura['total'] ?? 0));
    $uuid = $factura['uuid'] ?? '';

    $qrBase64 = '';
    if ($uuid && !empty($factura['rfc_emisor']) && !empty($factura['rfc_receptor']) && isset($factura['total'])) {
        $qrUrl = 'https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?' . http_build_query([
            'id' => $uuid,
            're' => $factura['rfc_emisor'],
            'rr' => $factura['rfc_receptor'],
            'tt' => number_format((float)$factura['total'], 6, '.', ''),
            'fe' => substr($factura['sello_cfdi'] ?? $factura['sello_sat'] ?? '', -8)
        ]);
        
        // Generar QR usando API de Google Chart (o quickchart.io)
        $qrImageUrl = 'https://quickchart.io/qr?text=' . urlencode($qrUrl) . '&size=150';
        $qrBase64 = base64_encode(file_get_contents($qrImageUrl));
    }

    ob_start();
    ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: '<?php echo htmlspecialchars($config['tipo_letra'] ?? 'Arial'); ?>', Arial, sans-serif; font-size: <?php echo max(9, min($tamanoLetra, 14)); ?>px; color: #333; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid <?php echo $colorPrimario; ?>; padding-bottom: 12px; }
        .logo img { max-height: 80px; }
        .emisor { flex: 1; padding: 0 15px; }
        .folio { text-align: right; color: <?php echo $colorPrimario; ?>; font-weight: bold; }
        .section { margin-top: 18px; }
        .section-title { background: <?php echo $colorPrimario; ?>; color: #fff; padding: 6px 10px; border-radius: 4px; font-weight: bold; }
        .grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-top: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th { background: <?php echo $colorPrimario; ?>; color: #fff; padding: 6px; font-size: 0.9em; }
        td { border: 1px solid #ccc; padding: 6px; }
        .totales { margin-top: 15px; width: 100%; }
        .totales td { border: none; padding: 3px 6px; }
        .totales tr.total { background: <?php echo $colorPrimario; ?>; color: #fff; font-weight: bold; }
        .leyenda { margin-top: 20px; font-size: 0.9em; color: <?php echo $colorSecundario; ?>; }
        .qr { margin-top: 10px; text-align: right; }
        .qr img { width: 120px; height: 120px; }
        .evento { font-size: 0.85em; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <?php if ($logoPath): ?>
                <img src="<?php echo $logoPath; ?>" alt="Logo">
            <?php endif; ?>
        </div>
        <div class="emisor">
            <strong><?php echo htmlspecialchars($factura['razon_social_emisor'] ?? $factura['nombre_emisor'] ?? ''); ?></strong><br>
            RFC: <?php echo htmlspecialchars($factura['rfc_emisor'] ?? ''); ?><br>
            <?php echo htmlspecialchars($factura['direccion_emisor'] ?? ''); ?><br>
            CP <?php echo htmlspecialchars($factura['cp_emisor'] ?? ''); ?>
        </div>
        <div class="folio">
            <div>CFDI 4.0</div>
            <div>Folio: <?php echo htmlspecialchars($folio); ?></div>
            <?php if ($uuid): ?>
            <div>UUID:<br><?php echo htmlspecialchars($uuid); ?></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Datos del Receptor</div>
        <div class="grid">
            <div>
                <strong><?php echo htmlspecialchars($factura['razon_social_receptor'] ?? ''); ?></strong><br>
                RFC: <?php echo htmlspecialchars($factura['rfc_receptor'] ?? ''); ?><br>
                CP: <?php echo htmlspecialchars($factura['domicilio_fiscal_receptor'] ?? ''); ?>
            </div>
            <div>
                Forma de Pago: <?php echo htmlspecialchars($factura['forma_pago'] ?? ''); ?><br>
                Método de Pago: <?php echo htmlspecialchars($factura['metodo_pago'] ?? ''); ?><br>
                Uso CFDI: <?php echo htmlspecialchars($factura['uso_cfdi'] ?? ''); ?>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Conceptos</div>
        <table>
            <thead>
                <tr>
                    <th>Cantidad</th>
                    <th>Clave</th>
                    <th>Descripción</th>
                    <th>Unidad</th>
                    <th>Precio Unitario</th>
                    <th>Importe</th>
                    <th>IVA</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($conceptos as $concepto): ?>
                    <tr>
                        <td><?php echo number_format($concepto['cantidad'], 2); ?></td>
                        <td><?php echo htmlspecialchars($concepto['clave_producto']); ?></td>
                        <td><?php echo htmlspecialchars($concepto['descripcion']); ?></td>
                        <td><?php echo htmlspecialchars($concepto['unidad']); ?></td>
                        <td><?php echo '$' . number_format($concepto['precio_unitario'], 2); ?></td>
                        <td><?php echo '$' . number_format($concepto['importe'], 2); ?></td>
                        <td><?php echo '$' . number_format($concepto['impuesto'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <table class="totales" align="right">
        <tr>
            <td>Subtotal:</td>
            <td><?php echo '$' . number_format((float)($factura['subtotal'] ?? 0), 2); ?></td>
        </tr>
        <tr>
            <td>IVA:</td>
            <td><?php echo '$' . number_format((float)($factura['impuestos_trasladados'] ?? 0), 2); ?></td>
        </tr>
        <tr class="total">
            <td>Total:</td>
            <td><?php echo '$' . number_format((float)($factura['total'] ?? 0), 2); ?></td>
        </tr>
    </table>

    <div style="clear: both;"></div>

    <div class="section evento">
        <strong>Fecha de emisión:</strong> <?php echo htmlspecialchars($fechaEmision); ?><br>
        <?php if ($fechaTimbrado): ?>
            <strong>Fecha de timbrado:</strong> <?php echo htmlspecialchars($fechaTimbrado); ?><br>
        <?php endif; ?>
        <strong>Total con letra:</strong> <?php echo htmlspecialchars($totalLetras); ?>
    </div>

    <?php if ($qrBase64): ?>
    <div class="qr">
        <img src="data:image/svg+xml;base64,<?php echo $qrBase64; ?>" alt="QR SAT">
    </div>
    <?php endif; ?>

    <div class="leyenda">
        <?php echo htmlspecialchars($config['leyenda_factura'] ?? ''); ?>
    </div>
</body>
</html>
    <?php
    return ob_get_clean();
}

/**
 * Convierte total a letras.
 */
function facturaTotalEnLetras(float $monto): string
{
    $formatter = new NumberFormatter('es_MX', NumberFormatter::SPELLOUT);
    $entero = (int)floor($monto);
    $decimales = (int)round(($monto - $entero) * 100);
    $letras = strtoupper($formatter->format($entero));
    return $letras . ' PESOS ' . str_pad((string)$decimales, 2, '0', STR_PAD_LEFT) . '/100 M.N.';
}
