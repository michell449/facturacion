<?php
/**
 * Generación de PDF de facturas
 */

use Mpdf\Mpdf;

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/autoload-vendor.php';

/**
 * Genera el PDF de una factura y lo guarda en disco
 */
function facturaGenerarPdfArchivo(PDO $conn, int $idFactura): array
{
    // Consultar datos de la factura
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

    // Ruta base del proyecto (un nivel arriba de core/)
    $basePath = dirname(__DIR__);
    
    // Verificar si el PDF ya existe
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

    // Obtener configuración visual
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

    // Obtener conceptos
    $stmtConceptos = $conn->prepare('SELECT clave_prod_serv, descripcion, unidad, cantidad, valor_unitario, importe, impuesto_importe FROM facturas_detalles WHERE id_factura = ?');
    $stmtConceptos->execute([$idFactura]);
    $conceptos = $stmtConceptos->fetchAll(PDO::FETCH_ASSOC);

    if (empty($conceptos)) {
        $conceptos = [[
            'clave_prod_serv' => '01010101',
            'descripcion' => 'Concepto no especificado',
            'unidad' => 'ACT',
            'cantidad' => 1,
            'valor_unitario' => (float)($factura['subtotal'] ?? 0),
            'importe' => (float)($factura['subtotal'] ?? 0),
            'impuesto_importe' => (float)($factura['impuestos_trasladados'] ?? 0)
        ]];
    }

    // Usar plantilla-factura.php para generar HTML
    ob_start();
    require __DIR__ . '/plantilla-factura.php';
    $html = ob_get_clean();

    // Crear PDF con mPDF
    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'Letter',
        'margin_left' => 5,
        'margin_right' => 5,
        'margin_top' => 5,
        'margin_bottom' => 10,
        'default_font' => $config['tipo_letra'] ?? 'Arial',
        'tempDir' => sys_get_temp_dir()
    ]);

    $mpdf->WriteHTML($html);

    // Definir nombre del archivo
    $serie = $factura['serie_interno'] ?? 'A';
    $folioNumero = $factura['folio_interno'] ?? 0;
    $folio = str_pad((string)$folioNumero, 6, '0', STR_PAD_LEFT);
    $nombreArchivo = "Factura_{$serie}{$folio}.pdf";

    // Crear directorio si no existe
    $directorio = $basePath . '/uploads/facturas_pdf/';
    if (!is_dir($directorio)) {
        mkdir($directorio, 0755, true);
    }

    // Guardar PDF
    $rutaAbsoluta = $directorio . $nombreArchivo;
    $mpdf->Output($rutaAbsoluta, 'F');

    // Actualizar BD con la ruta
    $rutaRelativa = 'uploads/facturas_pdf/' . $nombreArchivo;
    $stmtUpdate = $conn->prepare('UPDATE facturas SET pdf_path = ? WHERE id_factura = ?');
    $stmtUpdate->execute([$rutaRelativa, $idFactura]);

    return [
        'relative' => $rutaRelativa,
        'absolute' => $rutaAbsoluta
    ];
}

/**
 * Convierte un número a letras (español)
 */
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
?>