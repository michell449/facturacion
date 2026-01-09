<?php
// plantilla-factura.php
// NO incluyas session_start ni conexiones a BD aquí, 
// esta plantilla solo debe recibir datos y mostrar HTML.

// Definir función numeroALetras si no existe (para evitar redeclaraciones)
if (!function_exists('numeroALetras')) {
    function numeroALetras($numero) {
        // Fallback si la extensión intl no está disponible
        if (class_exists('NumberFormatter')) {
            $formatter = new NumberFormatter("es", NumberFormatter::SPELLOUT);
            return strtoupper($formatter->format($numero)) . ' PESOS ' . sprintf('%02d', round(($numero - floor($numero)) * 100)) . '/100 M.N.';
        }
        
        // Fallback manual sin intl extension
        $entero = floor($numero);
        $decimales = round(($numero - $entero) * 100);
        
        if ($entero == 0) return 'CERO PESOS ' . sprintf('%02d', $decimales) . '/100 M.N.';
        
        $texto = '';
        
        // Millones
        if ($entero >= 1000000) {
            $millones = floor($entero / 1000000);
            $texto .= ($millones == 1 ? 'UN MILLON ' : numeroALetrasHelper($millones) . ' MILLONES ');
            $entero %= 1000000;
        }
        
        // Miles
        if ($entero >= 1000) {
            $miles = floor($entero / 1000);
            $texto .= ($miles == 1 ? 'MIL ' : numeroALetrasHelper($miles) . ' MIL ');
            $entero %= 1000;
        }
        
        // Resto
        if ($entero > 0) {
            $texto .= numeroALetrasHelper($entero);
        }
        
        return trim($texto) . ' PESOS ' . sprintf('%02d', $decimales) . '/100 M.N.';
    }
}

if (!function_exists('numeroALetrasHelper')) {
    function numeroALetrasHelper($num) {
        $unidades = ['', 'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];
        $decenas = ['', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
        $especiales = ['DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE'];
        $centenas = ['', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];
        
        if ($num < 10) return $unidades[$num];
        if ($num < 20) return $especiales[$num - 10];
        if ($num < 100) {
            $dec = floor($num / 10);
            $uni = $num % 10;
            if ($dec == 2 && $uni > 0) return 'VEINTI' . $unidades[$uni];
            return $decenas[$dec] . ($uni > 0 ? ' Y ' . $unidades[$uni] : '');
        }
        if ($num < 1000) {
            $cen = floor($num / 100);
            $resto = $num % 100;
            $texto = ($num == 100 ? 'CIEN' : $centenas[$cen]);
            if ($resto > 0) $texto .= ' ' . numeroALetrasHelper($resto);
            return $texto;
        }
        return '';
    }
}

// Convertir el total a letras
$totalLetra = numeroALetras($factura['total']);

// Rutas de imagen absoluta para mPDF
$logoPath = '';
if (!empty($config['logo_url'])) {
    // Si la ruta en BD es relativa (uploads/...), agregar __DIR__
    if (strpos($config['logo_url'], 'http') === 0) {
        $logoPath = $config['logo_url'];
    } else {
        $logoPath = __DIR__ . '/' . $config['logo_url'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura</title>
    <style>
        body { font-family: '<?php echo $config['tipo_letra']; ?>', sans-serif; font-size: <?php echo $config['tamano_letra']; ?>pt; color: #333; }
        .header { width: 100%; border-bottom: 3px solid <?php echo $config['color_primario']; ?>; padding-bottom: 10px; margin-bottom: 15px; }
        .col-left { float: left; width: 60%; }
        .col-right { float: right; width: 38%; text-align: right; }
        
        .factura-box { 
            background: <?php echo $config['color_primario']; ?>; 
            color: #fff; 
            padding: 10px; 
            border-radius: 5px; 
            text-align: center;
        }
        .factura-box h2 { margin: 0; font-size: 16pt; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: <?php echo $config['color_primario']; ?>; color: white; padding: 5px; font-size: 9pt; }
        td { border-bottom: 1px solid #ccc; padding: 5px; font-size: 9pt; }
        
        .totales { margin-top: 20px; page-break-inside: avoid; }
        .totales-table { float: right; width: 40%; }
        .totales-table td { border: none; border-bottom: 1px solid #eee; }
        .total-final { background: <?php echo $config['color_secundario']; ?>; color: white; font-weight: bold; }
        
        .sello-box { 
            margin-top: 30px; 
            border: 1px solid #ccc; 
            padding: 5px; 
            font-size: 7pt; 
            page-break-inside: avoid;
            background-color: #f9f9f9;
        }
        .qr-img { width: 120px; height: 120px; float: left; margin-right: 10px; }
        .cadena { word-wrap: break-word; }
        
        .clearfix { clear: both; }
    </style>
</head>
<body>

    <div class="header">
        <div class="col-left">
            <?php if(file_exists($logoPath) || filter_var($logoPath, FILTER_VALIDATE_URL)): ?>
                <img src="<?php echo $logoPath; ?>" style="max-height: 60px; margin-bottom: 10px;">
                <br>
            <?php endif; ?>
            <strong style="color: <?php echo $config['color_primario']; ?>; font-size: 14pt;"><?php echo $factura['razon_social_emisor']; ?></strong><br>
            RFC: <?php echo $factura['rfc_emisor']; ?><br>
            Régimen: <?php echo $factura['regimen_fiscal_emisor']; ?><br>
            <?php echo $factura['direccion_emisor']; ?> CP: <?php echo $factura['cp_emisor']; ?>
        </div>
        <div class="col-right">
            <div class="factura-box">
                <h2>FACTURA</h2>
                <span><?php echo $factura['serie_interno'] . '-' . $factura['folio_interno']; ?></span>
            </div>
            <br>
            <strong>Folio Fiscal (UUID):</strong><br>
            <span style="font-size: 8pt;"><?php echo $factura['uuid']; ?></span><br>
            <strong>Fecha de Emisión:</strong> <?php echo $factura['fecha_emision']; ?><br>
            <strong>Lugar Exp.:</strong> <?php echo $factura['lugar_expedicion']; ?>
        </div>
        <div class="clearfix"></div>
    </div>

    <div style="background: #eee; padding: 10px; border-left: 5px solid <?php echo $config['color_secundario']; ?>;">
        <strong>CLIENTE:</strong> <?php echo $factura['razon_social_receptor']; ?><br>
        <strong>RFC:</strong> <?php echo $factura['rfc_receptor']; ?> | 
        <strong>Uso CFDI:</strong> <?php echo $factura['uso_cfdi']; ?> |
        <strong>CP:</strong> <?php echo $factura['domicilio_fiscal_receptor']; ?> |
        <strong>Régimen:</strong> <?php echo $factura['regimen_fiscal_receptor']; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th width="10%">Cant.</th>
                <th width="10%">Unidad</th>
                <th width="10%">Clave</th>
                <th width="40%">Descripción</th>
                <th width="15%" align="right">P. Unit</th>
                <th width="15%" align="right">Importe</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($conceptos as $c): ?>
            <tr>
                <td align="center"><?php echo $c['cantidad']; ?></td>
                <td align="center"><?php echo $c['clave_unidad']; ?></td>
                <td align="center"><?php echo $c['clave_prod_serv']; ?></td>
                <td><?php echo $c['descripcion']; ?></td>
                <td align="right">$<?php echo number_format($c['valor_unitario'], 2); ?></td>
                <td align="right">$<?php echo number_format($c['importe'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="totales">
        <div style="float: left; width: 55%; font-size: 9pt;">
            <strong>Importe con letra:</strong><br>
            <?php echo $totalLetra; ?><br><br>
            <strong>Forma de Pago:</strong> <?php echo $factura['forma_pago']; ?><br>
            <strong>Método de Pago:</strong> <?php echo $factura['metodo_pago']; ?><br>
            <strong>Moneda:</strong> <?php echo $factura['moneda']; ?>
        </div>
        
        <table class="totales-table">
            <tr>
                <td align="right">Subtotal:</td>
                <td align="right">$<?php echo number_format($factura['subtotal'], 2); ?></td>
            </tr>
            <tr>
                <td align="right">IVA Trasladado:</td>
                <td align="right">$<?php echo number_format($factura['impuestos_trasladados'], 2); ?></td>
            </tr>
            <?php if($factura['impuestos_retenidos'] > 0): ?>
            <tr>
                <td align="right">Retenciones:</td>
                <td align="right">-$<?php echo number_format($factura['impuestos_retenidos'], 2); ?></td>
            </tr>
            <?php endif; ?>
            <tr class="total-final">
                <td align="right">TOTAL:</td>
                <td align="right">$<?php echo number_format($factura['total'], 2); ?></td>
            </tr>
        </table>
        <div class="clearfix"></div>
    </div>

    <div class="sello-box">
        <?php if(!empty($qrImage)): ?>
            <img src="<?php echo $qrImage; ?>" class="qr-img">
        <?php endif; ?>
        
        <div class="cadena">
            <strong>Sello Digital del CFDI:</strong><br>
            <?php echo $factura['sello_cfdi']; ?><br><br>
            <strong>Sello del SAT:</strong><br>
            <?php echo $factura['sello_sat']; ?><br><br>
            <strong>Cadena Original del complemento de certificación digital del SAT:</strong><br>
            <?php echo $factura['cadena_original']; ?><br><br>
            <strong>No. Certificado SAT:</strong> <?php echo $factura['no_certificado_sat']; ?> | 
            <strong>Fecha Certificación:</strong> <?php echo $factura['fecha_timbrado']; ?>
        </div>
        <div class="clearfix"></div>
    </div>
    
    <div style="text-align: center; font-size: 8pt; margin-top: 10px; color: #666;">
        <?php echo $config['leyenda_factura']; ?>
    </div>

</body>
</html>