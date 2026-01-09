<?php
// plantilla-factura.php

// 1. Asegurar funciones auxiliares
if (!function_exists('numeroALetras')) {
    function numeroALetras($numero) {
        $formatter = new NumberFormatter("es", NumberFormatter::SPELLOUT);
        return strtoupper($formatter->format($numero)); 
    }
}

// 2. Preparar datos
$totalLetra = isset($totalLetras) ? $totalLetras : (numeroALetras($factura['total']) . ' PESOS ' . sprintf('%02d', round(($factura['total'] - floor($factura['total'])) * 100)) . '/100 M.N.');

// Rutas de imagen
$logoPath = '';
if (!empty($config['logo_url'])) {
    if (strpos($config['logo_url'], 'http') === 0) {
        $logoPath = $config['logo_url'];
    } else {
        $logoPath = __DIR__ . '/../' . $config['logo_url']; // Ajuste de ruta relativa
    }
}

// Colores por defecto si fallan
$cPrimario = $config['color_primario'] ?? '#0d6efd';
$cSecundario = $config['color_secundario'] ?? '#6c757d';
$fontFamily = $config['tipo_letra'] ?? 'Helvetica';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura <?php echo ($factura['serie_interno'] ?? '') . ($factura['folio_interno'] ?? ''); ?></title>
    <style>
        body {
            font-family: '<?php echo $fontFamily; ?>', sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.3;
        }
        
        /* Tablas de estructura (Layout) */
        table { width: 100%; border-collapse: collapse; border-spacing: 0; }
        td { vertical-align: top; padding: 5px; }
        
        /* Encabezado */
        .header-table td { vertical-align: middle; }
        .empresa-nombre { font-size: 14pt; font-weight: bold; color: <?php echo $cPrimario; ?>; }
        .empresa-datos { font-size: 9pt; color: #555; }
        
        .factura-box {
            border: 2px solid <?php echo $cPrimario; ?>;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            background-color: #fcfcfc;
        }
        .factura-titulo { font-size: 14pt; font-weight: bold; color: <?php echo $cPrimario; ?>; margin-bottom: 5px; display: block;}
        .factura-folio { font-size: 12pt; color: #333; font-weight: bold; }
        .factura-uuid { font-size: 7pt; color: #666; margin-top: 5px; font-family: monospace; }
        
        /* Cliente */
        .cliente-bar {
            background-color: <?php echo $cSecundario; ?>;
            color: #fff;
            padding: 5px 10px;
            font-weight: bold;
            font-size: 10pt;
            margin-top: 15px;
            border-radius: 4px;
        }
        .cliente-table { margin-top: 5px; font-size: 9pt; }
        .cliente-label { font-weight: bold; color: #555; width: 15%; }
        
        /* Conceptos */
        .conceptos-table { margin-top: 20px; border: 1px solid #ddd; }
        .conceptos-table th {
            background-color: <?php echo $cPrimario; ?>;
            color: #fff;
            padding: 8px;
            font-size: 9pt;
            text-align: center;
            font-weight: bold;
        }
        .conceptos-table td {
            border-bottom: 1px solid #eee;
            padding: 8px;
            font-size: 9pt;
        }
        .row-alt { background-color: #f9f9f9; }
        
        /* Totales y Pie */
        .footer-section { margin-top: 20px; }
        .totales-table td { padding: 4px; text-align: right; }
        .total-label { font-weight: bold; color: #555; }
        .total-amount { font-weight: bold; font-size: 11pt; }
        .total-final { background-color: <?php echo $cPrimario; ?>; color: #fff; padding: 8px !important; }
        
        /* Sellos */
        .sello-container {
            border: 1px solid #ddd;
            background-color: #f5f5f5;
            padding: 8px;
            font-size: 7pt;
            margin-top: 10px;
            page-break-inside: avoid; /* Evita que el sello se corte entre páginas */
        }
        .sello-label { font-weight: bold; color: <?php echo $cPrimario; ?>; display: block; margin-bottom: 2px; }
        .sello-text { word-wrap: break-word; text-align: justify; color: #444; font-family: monospace; }
        
        .qr-img { width: 32mm; height: 32mm; }
        
        /* Utilidades */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td width="20%">
                <?php if(file_exists($logoPath)): ?>
                    <img src="<?php echo $logoPath; ?>" style="width: 100%; max-width: 150px;">
                <?php else: ?>
                    <h2 style="color: #ccc;">LOGO</h2>
                <?php endif; ?>
            </td>
            
            <td width="45%">
                <div class="empresa-nombre"><?php echo htmlspecialchars($factura['razon_social_emisor'] ?? $factura['nombre_emisor']); ?></div>
                <div class="empresa-datos">
                    <strong>RFC:</strong> <?php echo htmlspecialchars($factura['rfc_emisor']); ?><br>
                    <strong>Régimen:</strong> <?php echo htmlspecialchars($factura['regimen_fiscal_emisor']); ?><br>
                    <?php echo htmlspecialchars($factura['direccion_emisor']); ?><br>
                    <?php echo htmlspecialchars($factura['colonia_emisor']); ?>, C.P. <?php echo htmlspecialchars($factura['cp_emisor']); ?>
                </div>
            </td>
            
            <td width="35%">
                <div class="factura-box">
                    <span class="factura-titulo">FACTURA</span>
                    <span class="factura-folio">
                        <?php echo htmlspecialchars(($factura['serie_interno'] ?? '') . '-' . ($factura['folio_interno'] ?? '')); ?>
                    </span>
                    <div style="margin-top: 8px; font-size: 9pt;">
                        <strong>Fecha Emisión:</strong><br>
                        <?php echo date('d/m/Y H:i:s', strtotime($factura['fecha_emision'])); ?><br>
                        <strong>Lugar Exp:</strong> <?php echo htmlspecialchars($factura['cp_emisor']); ?>
                    </div>
                    <?php if(!empty($factura['uuid'])): ?>
                        <div class="factura-uuid">UUID: <?php echo $factura['uuid']; ?></div>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    </table>

    <div class="cliente-bar">DATOS DEL CLIENTE</div>
    <table class="cliente-table">
        <tr>
            <td class="cliente-label">Razón Social:</td>
            <td><?php echo htmlspecialchars($factura['razon_social_receptor']); ?></td>
            <td class="cliente-label">RFC:</td>
            <td><?php echo htmlspecialchars($factura['rfc_receptor']); ?></td>
        </tr>
        <tr>
            <td class="cliente-label">Domicilio:</td>
            <td><?php echo htmlspecialchars($factura['domicilio_fiscal_receptor']); ?></td>
            <td class="cliente-label">Uso CFDI:</td>
            <td><?php echo htmlspecialchars($factura['uso_cfdi']); ?></td>
        </tr>
        <tr>
            <td class="cliente-label">Régimen:</td>
            <td colspan="3"><?php echo htmlspecialchars($factura['regimen_fiscal_receptor']); ?></td>
        </tr>
    </table>

    <table class="conceptos-table">
        <thead>
            <tr>
                <th width="10%">Cant.</th>
                <th width="10%">Unidad</th>
                <th width="10%">Clave SAT</th>
                <th width="40%">Descripción</th>
                <th width="15%">P. Unitario</th>
                <th width="15%">Importe</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 0;
            foreach ($conceptos as $c): 
                $bg = ($i++ % 2 == 0) ? '' : 'class="row-alt"';
            ?>
            <tr <?php echo $bg; ?>>
                <td class="text-center"><?php echo number_format($c['cantidad'], 2); ?></td>
                <td class="text-center"><?php echo htmlspecialchars($c['unidad']); ?></td>
                <td class="text-center"><?php echo htmlspecialchars($c['clave_producto']); ?></td>
                <td><?php echo htmlspecialchars($c['descripcion']); ?></td>
                <td class="text-right">$<?php echo number_format($c['precio_unitario'], 2); ?></td>
                <td class="text-right">$<?php echo number_format($c['importe'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
            
            <?php if(count($conceptos) < 3): ?>
                <tr><td colspan="6" style="height: 30px;"></td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <table class="footer-section">
        <tr>
            <td width="60%">
                <div style="font-size: 9pt; color: #555;">
                    <strong>Importe con letra:</strong><br>
                    <?php echo $totalLetra; ?>
                </div>
                <div style="margin-top: 15px; font-size: 9pt;">
                    <table style="width: auto;">
                        <tr><td style="padding: 2px;"><strong>Forma de Pago:</strong></td><td><?php echo htmlspecialchars($factura['forma_pago']); ?></td></tr>
                        <tr><td style="padding: 2px;"><strong>Método de Pago:</strong></td><td><?php echo htmlspecialchars($factura['metodo_pago']); ?></td></tr>
                        <tr><td style="padding: 2px;"><strong>Moneda:</strong></td><td><?php echo htmlspecialchars($factura['moneda']); ?></td></tr>
                        <tr><td style="padding: 2px;"><strong>Tipo de cambio:</strong></td><td>1.00</td></tr>
                    </table>
                </div>
            </td>

            <td width="40%">
                <table class="totales-table">
                    <tr>
                        <td class="total-label">Subtotal:</td>
                        <td>$<?php echo number_format($factura['subtotal'], 2); ?></td>
                    </tr>
                    <tr>
                        <td class="total-label">IVA Trasladado (16%):</td>
                        <td>$<?php echo number_format($factura['impuestos_trasladados'], 2); ?></td>
                    </tr>
                    <?php if($factura['impuestos_retenidos'] > 0): ?>
                    <tr>
                        <td class="total-label">(-) IVA Retenido:</td>
                        <td style="color: #dc3545;">-$<?php echo number_format($factura['impuestos_retenidos'], 2); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td colspan="2"><hr style="border: 0; border-top: 1px solid #ccc; margin: 5px 0;"></td>
                    </tr>
                    <tr>
                        <td class="total-final">TOTAL:</td>
                        <td class="total-final total-amount">$<?php echo number_format($factura['total'], 2); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="sello-container">
        <table>
            <tr>
                <td width="20%" style="vertical-align: middle; text-align: center;">
                    <?php if(!empty($qrImage)): ?>
                        <img src="<?php echo $qrImage; ?>" class="qr-img">
                    <?php else: ?>
                        <div style="border:1px dashed #ccc; padding:20px;">QR</div>
                    <?php endif; ?>
                </td>
                
                <td width="80%">
                    <?php if(!empty($factura['uuid']) && $factura['uuid'] !== 'PENDIENTE'): ?>
                        <span class="sello-label">Sello Digital del CFDI:</span>
                        <div class="sello-text"><?php echo htmlspecialchars($factura['sello_cfdi'] ?? ''); ?></div>
                        
                        <div style="height: 5px;"></div>
                        
                        <span class="sello-label">Sello del SAT:</span>
                        <div class="sello-text"><?php echo htmlspecialchars($factura['sello_sat'] ?? ''); ?></div>
                        
                        <div style="height: 5px;"></div>
                        
                        <span class="sello-label">Cadena Original del complemento de certificación digital del SAT:</span>
                        <div class="sello-text"><?php echo htmlspecialchars($factura['cadena_original'] ?? ''); ?></div>
                        
                        <div style="margin-top: 8px; font-weight: bold;">
                            RFC Prov. Certif: <?php echo htmlspecialchars($factura['rfc_prov_certif'] ?? ''); ?> | 
                            No. Certificado SAT: <?php echo htmlspecialchars($factura['no_certificado_sat'] ?? ''); ?> | 
                            Fecha Certificación: <?php echo htmlspecialchars($factura['fecha_timbrado'] ?? ''); ?>
                        </div>
                    <?php else: ?>
                        <div style="color: #999; text-align: center; padding: 20px;">
                            <h3>VISTA PREVIA - SIN TIMBRAR</h3>
                            <p>Este documento aún no tiene validez fiscal.</p>
                        </div>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>

    <div style="text-align: center; font-size: 8pt; color: #888; margin-top: 15px;">
        <?php echo htmlspecialchars($config['leyenda_factura'] ?? 'Este documento es una representación impresa de un CFDI'); ?>
    </div>

</body>
</html>