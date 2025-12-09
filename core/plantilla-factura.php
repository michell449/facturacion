<?php
/**
 * Plantilla de Factura - Backend PHP
 * Genera HTML dinámico usando echo
 * Se incluye desde pages/plantilla-factura.inc.php
 */

require_once __DIR__ . '/class/db.php';

// Función helper para escapar HTML
function pf_html_escape($v) {
    return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
}

// Función para convertir número a letras
function pf_numero_a_letras($numero) {
    $unidades = ['', 'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];
    $decenas = ['', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
    $especiales = ['DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE'];
    $centenas = ['', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];
    
    $entero = floor($numero);
    $decimales = round(($numero - $entero) * 100);
    
    if ($entero == 0) return 'CERO PESOS ' . str_pad($decimales, 2, '0', STR_PAD_LEFT) . '/100 M.N.';
    if ($entero == 1) return 'UN PESO ' . str_pad($decimales, 2, '0', STR_PAD_LEFT) . '/100 M.N.';
    
    $letra = '';
    
    if ($entero >= 1000) {
        $miles = floor($entero / 1000);
        if ($miles == 1) {
            $letra .= 'MIL ';
        } else if ($miles < 10) {
            $letra .= $unidades[$miles] . ' MIL ';
        } else {
            $letra .= 'MILES ';
        }
        $entero = $entero % 1000;
    }
    
    if ($entero >= 100) {
        $letra .= $centenas[floor($entero / 100)] . ' ';
        $entero = $entero % 100;
    }
    
    if ($entero >= 10 && $entero < 20) {
        $letra .= $especiales[$entero - 10];
    } else if ($entero >= 20) {
        $letra .= $decenas[floor($entero / 10)];
        if ($entero % 10 > 0) {
            $letra .= ' Y ' . $unidades[$entero % 10];
        }
    } else if ($entero > 0) {
        $letra .= $unidades[$entero];
    }
    
    return trim($letra) . ' PESOS ' . str_pad($decimales, 2, '0', STR_PAD_LEFT) . '/100 M.N.';
}

try {
    // Obtener parámetros
    $isPreview = isset($_GET['preview']) && $_GET['preview'] == 1;
    $facturaId = isset($_GET['id_factura']) ? (int)$_GET['id_factura'] : 0;
    $sucursalId = isset($_GET['id_sucursal']) ? (int)$_GET['id_sucursal'] : 0;
    
    // Validar sesión
    $id_usuario = $_SESSION['usuario_id'] ?? $_SESSION['USR_ID'] ?? null;
    if (!$id_usuario) {
        echo '<div class="alert alert-danger">Sesión no válida</div>';
        return;
    }
    
    $db = new Database();
    $conn = $db->getConnection();
    
    // Variables iniciales
    $config = null;
    $emisor = [];
    $receptor = [];
    $factura = [];
    $conceptos = [];
    $subtotal = 0;
    $iva = 0;
    $total = 0;
    
    // ===== CONFIGURACIÓN DE LA SUCURSAL =====
    if ($sucursalId > 0) {
        $stmtConfig = $conn->prepare("
            SELECT * FROM config_facturas 
            WHERE id_usuario = ? AND id_sucursal = ?
        ");
        $stmtConfig->execute([$id_usuario, $sucursalId]);
        $config = $stmtConfig->fetch(PDO::FETCH_ASSOC);
        
        $stmtSuc = $conn->prepare("
            SELECT * FROM empresas 
            WHERE id_empresa = ? AND id_usuario = ?
        ");
        $stmtSuc->execute([$sucursalId, $id_usuario]);
        $sucursal = $stmtSuc->fetch(PDO::FETCH_ASSOC);
        
        if ($sucursal) {
            $emisor = [
                'nombre' => $config['nombre_empresa'] ?? $sucursal['razon_social'] ?? '',
                'rfc' => $config['rfc_empresa'] ?? $sucursal['rfc'] ?? '',
                'direccion' => $config['direccion_empresa'] ?? $sucursal['direccion'] ?? '',
                'cp' => $config['cp_emisor'] ?? $sucursal['codigo_postal'] ?? '',
                'regimen' => $config['regimen_fiscal'] ?? '601'
            ];
        }
    }
    
    // ===== DATOS DE FACTURA =====
    if ($facturaId > 0 && !$isPreview) {
        $stmtFactura = $conn->prepare("
            SELECT f.*, 
                   e.nombre as nombre_sucursal,
                   e.rfc as rfc_sucursal,
                   e.direccion as direccion_sucursal,
                   e.codigo_postal as cp_sucursal
            FROM facturas f
            LEFT JOIN tickets t ON f.id_ticket = t.id_ticket
            LEFT JOIN empresas e ON t.id_empresa = e.id_empresa
            WHERE f.id_factura = ?
        ");
        $stmtFactura->execute([$facturaId]);
        $factura = $stmtFactura->fetch(PDO::FETCH_ASSOC);
        
        if ($factura) {
            $conceptos[] = [
                'descripcion' => $factura['descripcion'] ?? 'Producto/Servicio',
                'cantidad' => $factura['cantidad'] ?? 1,
                'precio_unitario' => $factura['valor_unit'] ?? 0,
                'importe' => $factura['importe'] ?? 0,
                'clave_producto' => $factura['clave_prod_serv'] ?? '01010101',
                'unidad' => $factura['unidad'] ?? 'H87'
            ];
            
            $receptor = [
                'rfc' => $factura['rfc_receptor'] ?? '',
                'nombre' => 'Cliente',
                'domicilio' => '',
                'cp' => ''
            ];
            
            if (empty($emisor)) {
                $emisor = [
                    'nombre' => $factura['nombre_sucursal'] ?? '',
                    'rfc' => $factura['rfc_emisor'] ?? '',
                    'direccion' => $factura['direccion_sucursal'] ?? '',
                    'cp' => $factura['cp_sucursal'] ?? '',
                    'regimen' => '601'
                ];
            }
            
            $subtotal = $factura['subtotal'] ?? 0;
            $iva = $factura['total_imp_tras'] ?? 0;
            $total = $factura['total'] ?? 0;
        }
    } else {
        // DATOS DE EJEMPLO PARA VISTA PREVIA
        if (empty($emisor)) {
            $emisor = [
                'nombre' => 'EMPRESA EJEMPLO S.A. DE C.V.',
                'rfc' => 'EEE010101AAA',
                'direccion' => 'Calle Principal #123, Colonia Centro',
                'cp' => '00000',
                'regimen' => '601'
            ];
        }
        
        $receptor = [
            'rfc' => 'XAXX010101000',
            'nombre' => 'PÚBLICO EN GENERAL',
            'domicilio' => 'Av. Ejemplo #456',
            'cp' => '12345'
        ];
        
        $conceptos = [
            [
                'descripcion' => 'Producto o Servicio de Ejemplo',
                'cantidad' => 1.00,
                'precio_unitario' => 1000.00,
                'importe' => 1000.00,
                'clave_producto' => '01010101',
                'unidad' => 'H87'
            ]
        ];
        
        $factura = [
            'folio' => ($config['serie_factura'] ?? 'A') . '000001',
            'serie' => $config['serie_factura'] ?? 'A',
            'fecha_e' => date('Y-m-d'),
            'uuid' => 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX',
            'subtotal' => 1000.00,
            'total' => 1160.00,
            'total_imp_tras' => 160.00,
            'form_pago' => '01',
            'met_pago' => 'PUE',
            'uso_cfdi' => 'G03',
            'no_certificado' => '00000000000000000000'
        ];
        
        $subtotal = 1000.00;
        $iva = 160.00;
        $total = 1160.00;
    }
    
    $totalLetra = pf_numero_a_letras($total);
    
    // CSS Variables
    $colorPrimario = $config['color_primario'] ?? '#0d6efd';
    $colorSecundario = $config['color_secundario'] ?? '#6c757d';
    $tipoLetra = $config['tipo_letra'] ?? 'Arial';
    $tamanoLetra = ($config['tamano_letra'] ?? '12') . 'pt';
    $logoUrl = $config['logo_url'] ?? '';
    
    // ===== GENERAR HTML =====
    
    // Estilos CSS
    echo '<style>';
    echo ':root {';
    echo '    --color-primario: ' . pf_html_escape($colorPrimario) . ';';
    echo '    --color-secundario: ' . pf_html_escape($colorSecundario) . ';';
    echo '    --tipo-letra: ' . pf_html_escape($tipoLetra) . ', sans-serif;';
    echo '    --tamano-letra: ' . pf_html_escape($tamanoLetra) . ';';
    echo '}';
    echo 'body { background: #f8f9fa; padding: 20px; font-family: var(--tipo-letra); font-size: var(--tamano-letra); }';
    echo '.factura-container { max-width: 21cm; min-height: 29.7cm; margin: 0 auto; background: white; padding: 30px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }';
    echo '.header-factura { border-bottom: 3px solid var(--color-primario); padding-bottom: 20px; margin-bottom: 20px; }';
    echo '.logo-empresa { max-width: 200px; max-height: 100px; object-fit: contain; }';
    echo '.info-box { border: 2px solid var(--color-primario); padding: 15px; border-radius: 8px; background: rgba(13, 110, 253, 0.05); }';
    echo '.table-conceptos { margin: 20px 0; }';
    echo '.table-conceptos th { background: var(--color-primario); color: white; font-weight: 600; padding: 12px 8px; }';
    echo '.table-conceptos td { padding: 10px 8px; vertical-align: middle; }';
    echo '.totales-box { background: var(--color-secundario); color: white; padding: 20px; border-radius: 8px; }';
    echo '.totales-box .total-final { font-size: 1.5em; font-weight: bold; margin-top: 10px; padding-top: 10px; border-top: 2px solid rgba(255,255,255,0.3); }';
    echo '.sello-digital { font-family: "Courier New", monospace; font-size: 7pt; word-break: break-all; background: #f8f9fa; padding: 10px; border-radius: 4px; margin: 10px 0; }';
    echo '.qr-code { max-width: 150px; height: auto; }';
    echo '.watermark { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); font-size: 120px; color: rgba(255, 0, 0, 0.08); font-weight: bold; pointer-events: none; z-index: 0; }';
    echo '@media print { body { background: white; padding: 0; } .no-print { display: none !important; } .factura-container { box-shadow: none; padding: 0; } }';
    echo '@page { size: letter; margin: 1cm; }';
    echo '</style>';
    
    // Watermark si es vista previa
    if ($isPreview) {
        echo '<div class="watermark">VISTA PREVIA</div>';
    }
    
    // Contenedor principal
    echo '<div class="factura-container">';
    
    // ENCABEZADO
    echo '<div class="header-factura">';
    echo '<div class="row align-items-center">';
    echo '<div class="col-7">';
    if ($logoUrl && file_exists(__DIR__ . '/../' . $logoUrl)) {
        echo '<img src="' . pf_html_escape($logoUrl) . '" class="logo-empresa mb-3" alt="Logo Empresa">';
    }
    echo '<h4 class="mb-1 fw-bold">' . pf_html_escape($emisor['nombre']) . '</h4>';
    echo '<p class="mb-0"><small>RFC: <strong>' . pf_html_escape($emisor['rfc']) . '</strong></small></p>';
    echo '<p class="mb-0"><small>' . pf_html_escape($emisor['direccion']) . '</small></p>';
    echo '<p class="mb-0"><small>C.P. ' . pf_html_escape($emisor['cp']) . '</small></p>';
    echo '</div>';
    echo '<div class="col-5 text-end">';
    echo '<div class="info-box">';
    echo '<h5 class="mb-2">FACTURA ELECTRÓNICA</h5>';
    echo '<h2 class="mb-0">' . pf_html_escape($factura['folio'] ?? '') . '</h2>';
    echo '<p class="mb-0"><small>Serie: ' . pf_html_escape($factura['serie'] ?? '') . '</small></p>';
    echo '<p class="mb-0"><small>Fecha: ' . date('d/m/Y', strtotime($factura['fecha_e'] ?? 'now')) . '</small></p>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    
    // DATOS EMISOR Y RECEPTOR
    echo '<div class="row mb-4">';
    echo '<div class="col-6">';
    echo '<div class="info-box">';
    echo '<h6 class="fw-bold mb-2 text-primary"><i class="bi bi-building me-2"></i>EMISOR</h6>';
    echo '<p class="mb-1"><strong>' . pf_html_escape($emisor['nombre']) . '</strong></p>';
    echo '<p class="mb-1"><small>RFC: ' . pf_html_escape($emisor['rfc']) . '</small></p>';
    echo '<p class="mb-1"><small>Régimen Fiscal: ' . pf_html_escape($emisor['regimen']) . '</small></p>';
    echo '<p class="mb-0"><small>' . pf_html_escape($emisor['direccion']) . '</small></p>';
    echo '</div>';
    echo '</div>';
    echo '<div class="col-6">';
    echo '<div class="info-box">';
    echo '<h6 class="fw-bold mb-2 text-primary"><i class="bi bi-person me-2"></i>RECEPTOR</h6>';
    echo '<p class="mb-1"><strong>' . pf_html_escape($receptor['nombre']) . '</strong></p>';
    echo '<p class="mb-1"><small>RFC: ' . pf_html_escape($receptor['rfc']) . '</small></p>';
    echo '<p class="mb-1"><small>Uso CFDI: ' . pf_html_escape($factura['uso_cfdi'] ?? 'G03') . '</small></p>';
    echo '<p class="mb-0"><small>' . pf_html_escape($receptor['domicilio']) . '</small></p>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    
    // CONCEPTOS
    echo '<h6 class="fw-bold mb-3"><i class="bi bi-card-list me-2"></i>CONCEPTOS</h6>';
    echo '<table class="table table-bordered table-conceptos">';
    echo '<thead><tr>';
    echo '<th width="8%">Cant.</th>';
    echo '<th width="10%">Clave</th>';
    echo '<th width="8%">Unidad</th>';
    echo '<th width="44%">Descripción</th>';
    echo '<th width="15%" class="text-end">P. Unitario</th>';
    echo '<th width="15%" class="text-end">Importe</th>';
    echo '</tr></thead>';
    echo '<tbody>';
    foreach ($conceptos as $concepto) {
        echo '<tr>';
        echo '<td class="text-center">' . number_format($concepto['cantidad'], 2) . '</td>';
        echo '<td class="text-center"><small>' . pf_html_escape($concepto['clave_producto']) . '</small></td>';
        echo '<td class="text-center"><small>' . pf_html_escape($concepto['unidad']) . '</small></td>';
        echo '<td>' . pf_html_escape($concepto['descripcion']) . '</td>';
        echo '<td class="text-end">$' . number_format($concepto['precio_unitario'], 2) . '</td>';
        echo '<td class="text-end fw-bold">$' . number_format($concepto['importe'], 2) . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    
    // TOTALES Y OBSERVACIONES
    echo '<div class="row mt-4">';
    echo '<div class="col-7">';
    echo '<p class="mb-1"><strong>Importe con letra:</strong></p>';
    echo '<p class="mb-3">' . pf_html_escape($totalLetra) . '</p>';
    echo '<p class="mb-1"><strong>Forma de Pago:</strong> ' . pf_html_escape($factura['form_pago'] ?? '01') . ' - Efectivo</p>';
    echo '<p class="mb-1"><strong>Método de Pago:</strong> ' . pf_html_escape($factura['met_pago'] ?? 'PUE') . '</p>';
    echo '<p class="mb-0"><strong>Moneda:</strong> MXN - Peso Mexicano</p>';
    if ($config && !empty($config['leyenda_factura'])) {
        echo '<div class="mt-3 p-2 bg-light rounded">';
        echo '<small>' . nl2br(pf_html_escape($config['leyenda_factura'])) . '</small>';
        echo '</div>';
    }
    echo '</div>';
    echo '<div class="col-5">';
    echo '<div class="totales-box">';
    echo '<div class="d-flex justify-content-between mb-2"><span>Subtotal:</span><span>$' . number_format($subtotal, 2) . '</span></div>';
    echo '<div class="d-flex justify-content-between mb-2"><span>IVA (16%):</span><span>$' . number_format($iva, 2) . '</span></div>';
    echo '<div class="total-final d-flex justify-content-between"><span>TOTAL:</span><span>$' . number_format($total, 2) . '</span></div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    
    // SELLO DIGITAL
    echo '<div class="mt-4">';
    echo '<h6 class="fw-bold mb-2"><i class="bi bi-shield-check me-2"></i>TIMBRE FISCAL DIGITAL</h6>';
    echo '<div class="row">';
    echo '<div class="col-9">';
    echo '<p class="mb-1"><small><strong>UUID:</strong> ' . pf_html_escape($factura['uuid'] ?? 'PENDIENTE DE TIMBRADO') . '</small></p>';
    echo '<p class="mb-1"><small><strong>Fecha Timbrado:</strong> ' . (isset($factura['fecha_timbre']) ? date('d/m/Y H:i:s', strtotime($factura['fecha_timbre'])) : 'Pendiente') . '</small></p>';
    echo '<p class="mb-1"><small><strong>No. Certificado SAT:</strong> ' . pf_html_escape($factura['no_certificado'] ?? '00000000000000000000') . '</small></p>';
    if (isset($factura['sello_sat']) && $factura['sello_sat'] != 'PENDIENTE') {
        echo '<p class="mb-1"><small><strong>Sello Digital del CFDI:</strong></small></p>';
        echo '<div class="sello-digital">' . pf_html_escape(substr($factura['sello_sat'] ?? '', 0, 200)) . '...</div>';
    }
    echo '</div>';
    echo '<div class="col-3 text-center">';
    if (!$isPreview && isset($factura['uuid']) && $factura['uuid'] != 'PENDIENTE') {
        echo '<svg class="qr-code" width="150" height="150" xmlns="http://www.w3.org/2000/svg">';
        echo '<rect width="150" height="150" fill="#f8f9fa"/>';
        echo '<text x="50%" y="50%" text-anchor="middle" dy=".3em" font-size="12" fill="#6c757d">QR CODE</text>';
        echo '</svg>';
    } else {
        echo '<div class="border p-3 rounded">';
        echo '<i class="bi bi-qr-code display-4 text-muted"></i>';
        echo '<p class="mb-0"><small>QR pendiente</small></p>';
        echo '</div>';
    }
    echo '</div>';
    echo '</div>';
    echo '</div>';
    
    // FOOTER
    echo '<div class="mt-4 text-center border-top pt-3">';
    echo '<p class="mb-0"><small class="text-muted">Este documento es una representación impresa de un CFDI versión 4.0</small></p>';
    if ($config && !empty($config['condiciones_pago'])) {
        echo '<p class="mb-0"><small class="text-muted">' . pf_html_escape($config['condiciones_pago']) . '</small></p>';
    }
    echo '</div>';
    
    echo '</div>'; // Cierre factura-container

} catch (Throwable $e) {
    echo '<div class="alert alert-danger">Error: ' . pf_html_escape($e->getMessage()) . '</div>';
}
?>
