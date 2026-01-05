<?php
/**
 * Genera un PDF de la factura usando la plantilla configurada
 * Requiere: mPDF para generar PDF
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/autoload-vendor.php';

use Mpdf\Mpdf;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;

// Validar sesión
$id_usuario = $_SESSION['usuario_id'] ?? $_SESSION['USR_ID'] ?? null;

if (!$id_usuario) {
    die('Sesión no válida. <a href="../index.php">Volver al inicio</a>');
}

$id_factura = isset($_GET['id_factura']) ? (int)$_GET['id_factura'] : 0;

if ($id_factura <= 0) {
    die('ID de factura no válido.');
}

// Obtener datos de la factura
$db = new Database();
$conn = $db->getConnection();

// Consulta completa de la factura con todos sus datos
$stmt = $conn->prepare("
    SELECT 
        f.*,
        e.rfc as rfc_emisor,
        e.nombre as nombre_emisor,
        e.razon_social as razon_social_emisor,
        e.direccion as direccion_emisor,
        e.colonia as colonia_emisor,
        e.cp as cp_emisor,
        e.reg_fiscal as regimen_fiscal_emisor,
        e.id_empresa
    FROM facturas f
    INNER JOIN empresas e ON f.id_empresa = e.id_empresa
    WHERE f.id_factura = ? AND f.id_usuario = ?
");
$stmt->execute([$id_factura, $id_usuario]);
$factura = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$factura) {
    die('Factura no encontrada o no tienes permiso para acceder a ella.');
}

// Validar que la factura esté timbrada
if ($factura['estatus'] !== 'timbrada') {
    die('La factura debe estar timbrada para generar el PDF.');
}

$id_sucursal = $factura['id_empresa'];

// Obtener configuración de la factura
$stmtConfig = $conn->prepare("
    SELECT * FROM config_facturas 
    WHERE id_usuario = ? AND id_sucursal = ?
    ORDER BY fecha_actualizacion DESC 
    LIMIT 1
");
$stmtConfig->execute([$id_usuario, $id_sucursal]);
$config = $stmtConfig->fetch(PDO::FETCH_ASSOC);

// Si no hay configuración, usar valores por defecto
if (!$config) {
    $config = [
        'color_primario' => '#0d6efd',
        'color_secundario' => '#6c757d',
        'tipo_letra' => 'Arial',
        'tamano_letra' => 12,
        'leyenda_factura' => 'Este documento es una representación impresa de un CFDI',
        'logo_url' => null
    ];
}

// Obtener conceptos/productos de la factura (debes tener una tabla para esto)
// Por ahora simulamos un concepto básico
$conceptos = [
    [
        'cantidad' => 1,
        'clave_producto' => '01010101',
        'descripcion' => 'Producto/Servicio',
        'precio_unitario' => $factura['subtotal'],
        'importe' => $factura['subtotal']
    ]
];

// Generar HTML de la factura
$html = generarHTMLFacturaProfesional($factura, $config, $conceptos);

try {
    // Crear instancia de mPDF con configuración personalizada
    // Desactivamos FPDI ya que no lo necesitamos (solo generamos PDFs, no importamos)
    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'Letter',
        'margin_left' => 5,
        'margin_right' => 5,
        'margin_top' => 5,
        'margin_bottom' => 5,
        'margin_header' => 0,
        'margin_footer' => 0,
        'default_font' => $config['tipo_letra'] ?? 'Arial',
        'autoScriptToLang' => false,
        'autoLangToFont' => false,
        'useSubstitutions' => false
    ]);
    
    // Escribir el HTML
    $mpdf->WriteHTML($html);
    
    // Definir nombre del archivo
    $serie = $factura['serie_interno'] ?? 'A';
    $folio = str_pad($factura['folio_interno'], 6, '0', STR_PAD_LEFT);
    $nombreArchivo = "Factura_{$serie}{$folio}.pdf";
    
    // Guardar el PDF
    $guardar = isset($_GET['guardar']) ? (int)$_GET['guardar'] : 1;
    
    if ($guardar) {
        // Crear directorio si no existe
        $dirPDF = __DIR__ . '/../uploads/facturas_pdf/';
        if (!is_dir($dirPDF)) {
            mkdir($dirPDF, 0755, true);
        }
        
        $rutaPDF = $dirPDF . $nombreArchivo;
        $mpdf->Output($rutaPDF, 'F');
        
        // Actualizar ruta en la base de datos
        $stmtUpdate = $conn->prepare("UPDATE facturas SET pdf_path = ? WHERE id_factura = ?");
        $stmtUpdate->execute(['uploads/facturas_pdf/' . $nombreArchivo, $id_factura]);
        
        // Descargar automáticamente
        $mpdf->Output($nombreArchivo, 'D'); // D = download (descarga automática)
    } else {
        // Solo mostrar sin guardar
        $mpdf->Output($nombreArchivo, 'I');
    }
} catch (Exception $e) {
    die('Error al generar PDF: ' . $e->getMessage());
}

/**
 * Genera el HTML profesional de la factura con todos los elementos del SAT
 */
function generarHTMLFacturaProfesional($factura, $config, $conceptos) {
    // Extraer colores y configuración
    $colorPrimario = $config['color_primario'] ?? '#0d6efd';
    $colorSecundario = $config['color_secundario'] ?? '#6c757d';
    $tipoLetra = $config['tipo_letra'] ?? 'Arial';
    $tamanoLetra = ($config['tamano_letra'] ?? 12);
    $leyenda = $config['leyenda_factura'] ?? 'Este documento es una representación impresa de un CFDI';
    
    // Ruta del logo
    $logoPath = '';
    if (!empty($config['logo_url'])) {
        $logoPathFull = __DIR__ . '/../' . $config['logo_url'];
        if (file_exists($logoPathFull)) {
            $logoPath = $logoPathFull;
        }
    }
    
    // Formatear fechas
    $fechaEmision = date('d/m/Y H:i:s', strtotime($factura['fecha_emision']));
    $fechaTimbrado = $factura['fecha_timbrado'] ? date('d/m/Y H:i:s', strtotime($factura['fecha_timbrado'])) : 'N/A';
    
    // Convertir total a letras (función auxiliar)
    $totalLetras = numeroALetras($factura['total']);
    
    // Generar URL para el QR Code del SAT
    $qrData = "https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?" .
              "id=" . $factura['uuid'] .
              "&re=" . $factura['rfc_emisor'] .
              "&rr=" . $factura['rfc_receptor'] .
              "&tt=" . number_format($factura['total'], 6, '.', '') .
              "&fe=" . substr($factura['sello_cfdi'], -8);
    
    ob_start();
    ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura <?php echo htmlspecialchars($factura['serie_interno'] . $factura['folio_interno']); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: '<?php echo $tipoLetra; ?>', Arial, sans-serif; 
            font-size: 10px; 
            color: #333;
            line-height: 1.2;
        }
        .container { width: 100%; padding: 0; }
        
        /* Encabezado con logo */
        .header {
            border-bottom: 2px solid <?php echo $colorPrimario; ?>;
            padding-bottom: 4px;
            margin-bottom: 4px;
            overflow: hidden;
        }
        .header-logo {
            float: left;
            width: 20%;
        }
        .header-logo img {
            max-width: 80px;
            max-height: 60px;
        }
        .header-emisor {
            float: left;
            width: 55%;
            padding-left: 5px;
        }
        .header-emisor h2 {
            color: <?php echo $colorPrimario; ?>;
            font-size: 12px;
            margin-bottom: 1px;
        }
        .header-emisor p {
            margin: 1px 0;
            font-size: 9px;
        }
        .header-factura {
            float: right;
            width: 25%;
            text-align: center;
            background: <?php echo $colorPrimario; ?>;
            color: white;
            padding: 4px;
            border-radius: 3px;
        }
        .header-factura h1 {
            font-size: 14px;
            margin-bottom: 1px;
        }
        .header-factura p {
            margin: 0px;
            font-size: 8px;
        }
        
        /* Cajas de información */
        .info-row {
            overflow: hidden;
            margin-bottom: 4px;
        }
        .info-box {
            float: left;
            width: 48%;
            border: 1px solid <?php echo $colorPrimario; ?>;
            padding: 4px;
            border-radius: 3px;
            background: #f8f9fa;
            font-size: 8px;
        }
        .info-box:last-child {
            float: right;
        }
        .info-box h3 {
            color: <?php echo $colorPrimario; ?>;
            font-size: 10px;
            margin-bottom: 2px;
            border-bottom: 1px solid <?php echo $colorPrimario; ?>;
            padding-bottom: 1px;
        }
        .info-box p {
            margin: 1px 0;
            font-size: 8px;
        }
        
        /* Tabla de conceptos */
        .conceptos-title {
            background: <?php echo $colorPrimario; ?>;
            color: white;
            padding: 3px;
            font-size: 10px;
            font-weight: bold;
            margin-top: 4px;
            border-radius: 3px 3px 0 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        thead th {
            background: <?php echo $colorPrimario; ?>;
            color: white;
            padding: 3px 2px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
        }
        tbody td {
            border: 1px solid #ddd;
            padding: 2px 2px;
            font-size: 8px;
        }
        tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        /* Totales */
        .totales-row {
            overflow: hidden;
            margin-top: 4px;
        }
        .totales-texto {
            float: left;
            width: 55%;
            padding: 4px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 8px;
        }
        .totales-texto p {
            margin: 1px 0;
            font-size: 8px;
        }
        .totales-numeros {
            float: right;
            width: 43%;
            border: 1px solid <?php echo $colorPrimario; ?>;
            border-radius: 3px;
            overflow: hidden;
        }
        .totales-numeros table {
            margin: 0;
        }
        .totales-numeros td {
            padding: 3px 2px;
            border: none;
            border-bottom: 1px solid #ddd;
            font-size: 8px;
        }
        .totales-numeros .total-final {
            background: <?php echo $colorPrimario; ?>;
            color: white;
            font-weight: bold;
            font-size: 9px;
        }
        
        /* Sección de timbre fiscal */
        .timbre-fiscal {
            margin-top: 4px;
            border: 1px solid <?php echo $colorSecundario; ?>;
            padding: 4px;
            border-radius: 3px;
            background: #fff8e1;
            overflow: hidden;
        }
        .timbre-fiscal h3 {
            color: <?php echo $colorSecundario; ?>;
            margin-bottom: 2px;
            font-size: 10px;
        }
        .timbre-qr {
            float: left;
            width: 15%;
            text-align: center;
        }
        .timbre-qr img {
            width: 60px;
            height: 60px;
        }
        .timbre-qr p {
            font-size: 7px;
            margin: 2px 0 0 0;
        }
        .timbre-datos {
            float: left;
            width: 85%;
            padding-left: 4px;
        }
        .timbre-datos p {
            margin: 1px 0;
            font-size: 7px;
            word-wrap: break-word;
        }
        .timbre-datos strong {
            color: <?php echo $colorSecundario; ?>;
        }
        
        /* Sellos digitales */
        .sellos-section {
            margin-top: 2px;
            padding: 2px;
            background: #f0f0f0;
            border-radius: 3px;
            display: none;
        }
        .sellos-section h4 {
            color: <?php echo $colorSecundario; ?>;
            font-size: 8px;
            margin-bottom: 1px;
        }
        .sello-text {
            font-size: 6px;
            word-wrap: break-word;
            line-height: 1.1;
            color: #666;
        }
        
        /* Leyenda final */
        .leyenda-final {
            margin-top: 4px;
            text-align: center;
            font-size: 8px;
            color: #666;
            padding: 2px;
            border-top: 1px solid #ddd;
        }
        
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="container">
        <!-- ENCABEZADO -->
        <div class="header">
            <?php if ($logoPath): ?>
            <div class="header-logo">
                <img src="<?php echo $logoPath; ?>" alt="Logo">
            </div>
            <?php endif; ?>
            
            <div class="header-emisor">
                <h2><?php echo htmlspecialchars($factura['razon_social_emisor'] ?? $factura['nombre_emisor']); ?></h2>
                <p><strong>RFC:</strong> <?php echo htmlspecialchars($factura['rfc_emisor']); ?></p>
                <p><strong>Régimen Fiscal:</strong> <?php echo htmlspecialchars($factura['regimen_fiscal_emisor']); ?></p>
                <p><?php echo htmlspecialchars($factura['direccion_emisor']); ?></p>
                <p><?php echo htmlspecialchars($factura['colonia_emisor']); ?>, CP <?php echo htmlspecialchars($factura['cp_emisor']); ?></p>
            </div>
            
            <div class="header-factura">
                <h1>FACTURA</h1>
                <p><strong>Serie:</strong> <?php echo htmlspecialchars($factura['serie_interno']); ?></p>
                <p><strong>Folio:</strong> <?php echo htmlspecialchars($factura['folio_interno']); ?></p>
                <p><strong>Fecha:</strong> <?php echo $fechaEmision; ?></p>
            </div>
            <div class="clear"></div>
        </div>
        
        <!-- INFORMACIÓN EMISOR Y RECEPTOR -->
        <div class="info-row">
            <div class="info-box">
                <h3>DATOS DEL EMISOR</h3>
                <p><strong>Nombre/Razón Social:</strong><br><?php echo htmlspecialchars($factura['razon_social_emisor'] ?? $factura['nombre_emisor']); ?></p>
                <p><strong>RFC:</strong> <?php echo htmlspecialchars($factura['rfc_emisor']); ?></p>
                <p><strong>Código Postal:</strong> <?php echo htmlspecialchars($factura['lugar_expedicion'] ?? $factura['cp_emisor']); ?></p>
                <p><strong>Régimen Fiscal:</strong> <?php echo htmlspecialchars($factura['regimen_fiscal_emisor']); ?></p>
            </div>
            
            <div class="info-box">
                <h3>DATOS DEL RECEPTOR</h3>
                <p><strong>Nombre/Razón Social:</strong><br><?php echo htmlspecialchars($factura['razon_social_receptor']); ?></p>
                <p><strong>RFC:</strong> <?php echo htmlspecialchars($factura['rfc_receptor']); ?></p>
                <p><strong>Código Postal:</strong> <?php echo htmlspecialchars($factura['domicilio_fiscal_receptor']); ?></p>
                <p><strong>Régimen Fiscal:</strong> <?php echo htmlspecialchars($factura['regimen_fiscal_receptor']); ?></p>
                <p><strong>Uso CFDI:</strong> <?php echo htmlspecialchars($factura['uso_cfdi']); ?></p>
            </div>
            <div class="clear"></div>
        </div>
        
        <!-- CONCEPTOS -->
        <div class="conceptos-title">CONCEPTOS FACTURADOS</div>
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 8%;">Cant.</th>
                    <th class="text-center" style="width: 12%;">Clave</th>
                    <th style="width: 40%;">Descripción</th>
                    <th class="text-right" style="width: 15%;">Precio Unit.</th>
                    <th class="text-right" style="width: 15%;">Importe</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($conceptos as $concepto): ?>
                <tr>
                    <td class="text-center"><?php echo number_format($concepto['cantidad'], 2); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($concepto['clave_producto']); ?></td>
                    <td><?php echo htmlspecialchars($concepto['descripcion']); ?></td>
                    <td class="text-right">$<?php echo number_format($concepto['precio_unitario'], 2); ?></td>
                    <td class="text-right">$<?php echo number_format($concepto['importe'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- TOTALES -->
        <div class="totales-row">
            <div class="totales-texto">
                <p><strong>Importe con letra:</strong></p>
                <p style="margin-top: 5px;"><?php echo htmlspecialchars($totalLetras); ?></p>
                <p style="margin-top: 10px;"><strong>Forma de Pago:</strong> <?php echo htmlspecialchars($factura['forma_pago']); ?></p>
                <p><strong>Método de Pago:</strong> <?php echo htmlspecialchars($factura['metodo_pago']); ?></p>
                <p><strong>Moneda:</strong> <?php echo htmlspecialchars($factura['moneda']); ?></p>
            </div>
            
            <div class="totales-numeros">
                <table>
                    <tr>
                        <td><strong>Subtotal:</strong></td>
                        <td class="text-right">$<?php echo number_format($factura['subtotal'], 2); ?></td>
                    </tr>
                    <?php if ($factura['impuestos_trasladados'] > 0): ?>
                    <tr>
                        <td><strong>IVA Trasladado:</strong></td>
                        <td class="text-right">$<?php echo number_format($factura['impuestos_trasladados'], 2); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($factura['impuestos_retenidos'] > 0): ?>
                    <tr>
                        <td><strong>IVA Retenido:</strong></td>
                        <td class="text-right">-$<?php echo number_format($factura['impuestos_retenidos'], 2); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr class="total-final">
                        <td><strong>TOTAL:</strong></td>
                        <td class="text-right"><strong>$<?php echo number_format($factura['total'], 2); ?></strong></td>
                    </tr>
                </table>
            </div>
            <div class="clear"></div>
        </div>
        
        <!-- TIMBRE FISCAL DIGITAL -->
        <div class="timbre-fiscal">
            <h3>COMPLEMENTO DE CERTIFICACIÓN DIGITAL DEL SAT</h3>
            <div class="timbre-qr">
                <img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=<?php echo urlencode($qrData); ?>" alt="QR Code">
                <p style="font-size: 9px; margin-top: 5px;">Código QR</p>
            </div>
            <div class="timbre-datos">
                <p><strong>UUID (Folio Fiscal):</strong> <?php echo htmlspecialchars($factura['uuid']); ?></p>
                <p><strong>Fecha y Hora de Certificación:</strong> <?php echo $fechaTimbrado; ?></p>
                <p><strong>No. Certificado SAT:</strong> <?php echo htmlspecialchars($factura['no_certificado_sat'] ?? 'N/A'); ?></p>
                <p><strong>No. Certificado del Emisor:</strong> <?php echo htmlspecialchars($factura['no_certificado_emisor'] ?? 'N/A'); ?></p>
                <p><strong>RFC Proveedor de Certificación:</strong> <?php echo htmlspecialchars($factura['rfc_prov_certif'] ?? 'N/A'); ?></p>
            </div>
            <div class="clear"></div>
        </div>
        
        <!-- SELLOS DIGITALES (Ocultos para optimizar espacio) -->
        <!-- Estos sellos se guardan en la BD pero no se imprimen en el PDF -->
        
        <!-- LEYENDA FINAL -->
        <div class="leyenda-final">
            <p><?php echo htmlspecialchars($leyenda); ?></p>
        </div>
    </div>
</body>
</html>
    <?php
    return ob_get_clean();
}

/**
 * Convierte un número a su representación en letras (pesos mexicanos)
 */
function numeroALetras($numero) {
    $formatter = new NumberFormatter('es_MX', NumberFormatter::SPELLOUT);
    $entero = floor($numero);
    $decimales = round(($numero - $entero) * 100);
    
    $letras = strtoupper($formatter->format($entero));
    return $letras . ' PESOS ' . str_pad($decimales, 2, '0', STR_PAD_LEFT) . '/100 M.N.';
}
?>
