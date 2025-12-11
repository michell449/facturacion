<?php

/**
 * Plantilla de Factura - Backend PHP
 * Genera HTML dinámico usando echo
 * Se incluye desde pages/plantilla-factura.inc.php
 */

require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/../config.php';

// Función helper para escapar HTML
function pf_html_escape($v)
{
    return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
}

// Función para convertir número a letras
function pf_numero_a_letras($numero)
{
    $unidades = ['', 'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];
    $decenas = ['', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
    $especiales = ['DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE'];
    $centenas = ['', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];

    $entero = floor($numero);
    $decimales = round(($numero - $entero) * 100);

    if ($entero == 0) return 'CERO PESOS ' . str_pad($decimales, 2, '0', STR_PAD_LEFT);
    if ($entero == 1) return 'UN PESO ' . str_pad($decimales, 2, '0', STR_PAD_LEFT);

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

    return trim($letra) . ' PESOS ' . str_pad($decimales, 2, '0', STR_PAD_LEFT);
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
    
    // Datos temporales de preview (desde sesión)
    $previewData = null;
    if ($isPreview && isset($_SESSION['preview_factura_data'])) {
        $previewData = $_SESSION['preview_factura_data'];
        // Limpiar datos de sesión después de usarlos
        unset($_SESSION['preview_factura_data']);
    }
    
    // Datos temporales de preview (desde sesión)
    $previewData = null;
    if ($isPreview && isset($_SESSION['preview_factura_data'])) {
        $previewData = $_SESSION['preview_factura_data'];
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
        
        if (!$config) {
            echo '<div class="alert alert-warning m-3">No se encontró configuración para esta sucursal. Por favor configure primero los datos de facturación.</div>';
            return;
        }

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
        } else if ($config) {
            // Si no hay sucursal pero hay config, usar datos de config
            $emisor = [
                'nombre' => $config['nombre_empresa'] ?? 'EMPRESA EJEMPLO S.A. DE C.V.',
                'rfc' => $config['rfc_empresa'] ?? 'EEE010101AAA',
                'direccion' => $config['direccion_empresa'] ?? 'Calle Principal #123',
                'cp' => $config['cp_emisor'] ?? '00000',
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
        // DATOS DE PREVIEW (desde sesión) o EJEMPLO
        if ($previewData) {
            // Usar datos ingresados por el usuario
            $receptor = [
                'rfc' => $previewData['receptor']['rfc'] ?? 'XAXX010101000',
                'nombre' => $previewData['receptor']['nombre'] ?? 'PÚBLICO EN GENERAL',
                'domicilio' => $previewData['receptor']['domicilio'] ?? '',
                'cp' => $previewData['receptor']['cp'] ?? '00000'
            ];
            
            $conceptos = $previewData['conceptos'] ?? [];
            
            $subtotal = $previewData['subtotal'] ?? 0;
            $iva = $previewData['iva'] ?? 0;
            $total = $previewData['total'] ?? 0;
            
            $factura = [
                'folio' => ($config['serie_factura'] ?? 'A') . str_pad(($config['folio_actual'] ?? 1), 6, '0', STR_PAD_LEFT),
                'serie' => $config['serie_factura'] ?? 'A',
                'fecha_e' => date('Y-m-d H:i:s'),
                'uuid' => 'PENDIENTE DE TIMBRADO',
                'subtotal' => $subtotal,
                'total' => $total,
                'total_imp_tras' => $iva,
                'form_pago' => $previewData['forma_pago'] ?? '01',
                'met_pago' => $previewData['metodo_pago'] ?? 'PUE',
                'uso_cfdi' => $previewData['uso_cfdi'] ?? 'G03',
                'no_certificado' => '00000000000000000000'
            ];
        } else {
            // DATOS DE EJEMPLO si no hay datos de preview
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
    }
    
    // Generar total en letras
    $totalLetra = pf_numero_a_letras($total);

    // ===== GENERAR HTML =====

    // CSS Variables dinámicas
    $colorPrimario = $config['color_primario'] ?? '#0d6efd';
    $colorSecundario = $config['color_secundario'] ?? '#0a58ca';
    $tipoLetra = $config['tipo_letra'] ?? 'Segoe UI';
    $tamanoLetra = ($config['tamano_letra'] ?? '10') . 'pt';
    
    // Procesar ruta del logo
    $logoUrl = '';
    $logoDebugInfo = '';
    if (isset($config['logo_url']) && !empty($config['logo_url'])) {
        $logoOriginal = $config['logo_url'];
        
        // Si es una URL absoluta (http/https), usarla tal cual
        if (preg_match('/^https?:\\/\\//', $logoOriginal)) {
            $logoUrl = $logoOriginal;
            $logoDebugInfo = 'URL externa';
        } 
        // Si comienza con '/', es ruta absoluta del servidor
        else if (strpos($logoOriginal, '/') === 0) {
            $logoUrl = $logoOriginal;
            $logoDebugInfo = 'Ruta absoluta';
        }
        // Si comienza con 'uploads/', es relativa desde la raíz del proyecto
        else if (strpos($logoOriginal, 'uploads/') === 0) {
            $logoUrl = $logoOriginal;
            $logoDebugInfo = 'Ruta relativa desde raíz';
        }
        // Cualquier otra ruta relativa
        else {
            $logoUrl = $logoOriginal;
            $logoDebugInfo = 'Ruta como está guardada';
        }
    }
    
    // DEBUG: Comentar después de verificar
    if ($isPreview) {
        error_log("PREVIEW DEBUG - Logo URL original: " . ($config['logo_url'] ?? 'NULL'));
        error_log("PREVIEW DEBUG - Logo URL procesado: " . $logoUrl);
        error_log("PREVIEW DEBUG - Logo Debug Info: " . $logoDebugInfo);
        error_log("PREVIEW DEBUG - Total en letras: " . $totalLetra);
        error_log("PREVIEW DEBUG - Config datos: " . json_encode([
            'nombre' => $config['nombre_empresa'] ?? 'NULL',
            'rfc' => $config['rfc_empresa'] ?? 'NULL',
            'color_primario' => $config['color_primario'] ?? 'NULL',
            'serie' => $config['serie_factura'] ?? 'NULL'
        ]));
    }

    // Estilos CSS
    echo '<style>';
    echo ':root {';
    echo '    --color-primario: ' . pf_html_escape($colorPrimario) . ';';
    echo '    --color-secundario: ' . pf_html_escape($colorSecundario) . ';';
    echo '    --font-family: ' . pf_html_escape($tipoLetra) . ', Arial, sans-serif;';
    echo '    --font-size-base: ' . pf_html_escape($tamanoLetra) . ';';
    echo '    --color-texto: #2c3e50;';
    echo '    --color-gris-claro: #f8f9fa;';
    echo '    --color-gris-medio: #e9ecef;';
    echo '    --color-borde: #dee2e6;';
    echo '}';
    echo '@page { size: A4; margin: 10mm; }';
    echo '@media print { html, body { width: 210mm; height: 297mm; margin: 0; padding: 0; } .factura-container { box-shadow: none !important; margin: 0 !important; } .no-print { display: none !important; } }';
    echo '* { margin: 0; padding: 0; box-sizing: border-box; }';
    echo 'body { font-family: var(--font-family); font-size: var(--font-size-base); color: var(--color-texto); background: #fcfcfc; line-height: 1.4; }';
    echo '.factura-container { max-width: 210mm; margin: 20px auto; background: #fff; box-shadow: 0 5px 30px rgba(0,0,0,0.15); border-radius: 8px; overflow: hidden; }';
    echo '.factura-header { background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%); color: #fff; padding: 20px 25px; display: flex; justify-content: space-between; align-items: center; }';
    echo '.header-left { display: flex; align-items: center; gap: 20px; }';
    echo '.logo-container { width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }';
    echo '.logo-container img { max-width: 75px; max-height: 75px; object-fit: contain; }';
    echo '.logo-placeholder { color: var(--color-primario); font-weight: bold; font-size: 10pt; }';
    echo '.emisor-header-info h1 { font-size: 16pt; font-weight: 700; margin-bottom: 3px; text-shadow: 0 2px 4px rgba(0,0,0,0.2); }';
    echo '.emisor-header-info p { font-size: 9pt; opacity: 0.9; margin: 0; }';
    echo '.header-right { text-align: right; }';
    echo '.factura-tipo { background: rgba(255,255,255,0.2); padding: 12px 25px; border-radius: 8px; border: 2px solid rgba(255,255,255,0.3); }';
    echo '.factura-tipo h2 { font-size: 18pt; font-weight: 700; margin: 0 0 2px 0; }';
    echo '.factura-tipo .serie-folio { font-size: 12pt; font-weight: 600; }';
    echo '.uuid-bar { background: var(--color-gris-claro); border-bottom: 3px solid var(--color-primario); padding: 12px 25px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }';
    echo '.uuid-info { display: flex; align-items: center; gap: 8px; }';
    echo '.uuid-label { font-size: 8pt; color: #666; font-weight: 600; text-transform: uppercase; }';
    echo '.uuid-value { font-family: Consolas, monospace; font-size: 10pt; color: var(--color-primario); font-weight: 600; background: #fff; padding: 4px 10px; border-radius: 4px; border: 1px solid var(--color-borde); }';
    echo '.badge-cfdi { display: inline-block; background: #28a745; color: #fff; font-size: 7pt; padding: 2px 8px; border-radius: 10px; font-weight: 600; text-transform: uppercase; }';
    echo '.fecha-info { text-align: right; font-size: 9pt; }';
    echo '.fecha-info strong { color: var(--color-primario); }';
    echo '.datos-section { display: grid; grid-template-columns: 1fr 1fr; gap: 0; border-bottom: 1px solid var(--color-borde); }';
    echo '.datos-box { padding: 18px 25px; }';
    echo '.datos-box:first-child { border-right: 1px solid var(--color-borde); }';
    echo '.datos-titulo { font-size: 9pt; font-weight: 700; color: #fff; background: var(--color-primario); padding: 6px 12px; border-radius: 4px; margin-bottom: 12px; display: inline-block; text-transform: uppercase; letter-spacing: 0.5px; }';
    echo '.datos-grid { display: grid; grid-template-columns: auto 1fr; gap: 6px 12px; font-size: 9pt; }';
    echo '.datos-grid dt { font-weight: 600; color: #666; margin: 0; }';
    echo '.datos-grid dd { color: var(--color-texto); margin: 0; }';
    echo '.datos-grid .valor-destacado { font-weight: 600; color: var(--color-primario); }';
    echo '.info-fiscal { background: var(--color-gris-claro); padding: 12px 25px; border-bottom: 1px solid var(--color-borde); display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; }';
    echo '.fiscal-item { text-align: center; padding: 8px; background: #fff; border-radius: 6px; border: 1px solid var(--color-borde); }';
    echo '.fiscal-item .label { font-size: 7pt; color: #888; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px; }';
    echo '.fiscal-item .value { font-size: 9pt; font-weight: 600; color: var(--color-texto); }';
    echo '.conceptos-section { padding: 20px 25px; }';
    echo '.seccion-titulo { font-size: 11pt; font-weight: 700; color: var(--color-primario); margin-bottom: 15px; padding-bottom: 8px; border-bottom: 2px solid var(--color-primario); display: flex; align-items: center; gap: 8px; }';
    echo '.seccion-titulo::before { content: ""; width: 4px; height: 18px; background: var(--color-primario); border-radius: 2px; }';
    echo '.tabla-conceptos { width: 100%; border-collapse: collapse; font-size: 8pt; }';
    echo '.tabla-conceptos thead { background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%); color: #fff; }';
    echo '.tabla-conceptos th { padding: 10px 8px; text-align: left; font-weight: 600; font-size: 8pt; text-transform: uppercase; letter-spacing: 0.3px; }';
    echo '.tabla-conceptos th:nth-child(n+5) { text-align: right; }';
    echo '.tabla-conceptos td { padding: 10px 8px; border-bottom: 1px solid var(--color-gris-medio); vertical-align: top; }';
    echo '.tabla-conceptos td:nth-child(n+5) { text-align: right; font-family: Consolas, monospace; }';
    echo '.tabla-conceptos tbody tr:hover { background: var(--color-gris-claro); }';
    echo '.tabla-conceptos tbody tr:last-child td { border-bottom: 2px solid var(--color-primario); }';
    echo '.concepto-codigo { font-size: 7pt; color: #888; font-family: Consolas, monospace; }';
    echo '.concepto-descripcion { font-weight: 500; color: var(--color-texto); }';
    echo '.totales-section { display: grid; grid-template-columns: 1fr 350px; gap: 25px; padding: 20px 25px; background: var(--color-gris-claro); border-top: 1px solid var(--color-borde); }';
    echo '.importe-letra { padding: 15px; background: #fff; border-radius: 8px; border-left: 4px solid var(--color-primario); }';
    echo '.importe-letra-titulo { font-size: 8pt; color: #666; text-transform: uppercase; margin-bottom: 5px; }';
    echo '.importe-letra-texto { font-size: 10pt; font-weight: 500; color: var(--color-texto); font-style: italic; }';
    echo '.totales-box { background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }';
    echo '.total-row { display: flex; justify-content: space-between; padding: 10px 15px; border-bottom: 1px solid var(--color-gris-medio); font-size: 9pt; }';
    echo '.total-row:last-child { border-bottom: none; }';
    echo '.total-row.subtotal { background: var(--color-gris-claro); }';
    echo '.total-row.impuesto { color: #666; }';
    echo '.total-row.total-final { background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%); color: #fff; font-size: 12pt; font-weight: 700; padding: 15px; }';
    echo '.total-row .label { font-weight: 500; }';
    echo '.total-row .value { font-family: Consolas, monospace; font-weight: 600; }';
    echo '.sellos-section { padding: 20px 25px; border-top: 1px solid var(--color-borde); }';
    echo '.sellos-grid { display: grid; grid-template-columns: 120px 1fr; gap: 20px; align-items: start; }';
    echo '.qr-container { text-align: center; }';
    echo '.qr-code { width: 110px; height: 110px; background: #fff; border: 2px solid var(--color-borde); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 5px; }';
    echo '.qr-code img { max-width: 100px; max-height: 100px; }';
    echo '.qr-label { font-size: 7pt; color: #888; text-transform: uppercase; }';
    echo '.sellos-content { display: flex; flex-direction: column; gap: 12px; }';
    echo '.sello-item { background: var(--color-gris-claro); padding: 10px 12px; border-radius: 6px; border-left: 3px solid var(--color-primario); }';
    echo '.sello-titulo { font-size: 7pt; color: #666; text-transform: uppercase; font-weight: 600; margin-bottom: 4px; }';
    echo '.sello-valor { font-family: Consolas, monospace; font-size: 6.5pt; color: var(--color-texto); word-break: break-all; line-height: 1.3; }';
    echo '.factura-footer { background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-secundario) 100%); color: #fff; padding: 15px 25px; }';
    echo '.leyenda-fiscal { font-size: 8pt; text-align: center; opacity: 0.9; margin-bottom: 10px; font-style: italic; }';
    echo '.moneda { font-size: 8pt; color: #666; }';
    echo '.watermark { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); font-size: 120px; color: rgba(255, 0, 0, 0.08); font-weight: bold; pointer-events: none; z-index: 0; }';
    if ($isPreview) {
        echo '.debug-info { position: fixed; bottom: 10px; right: 10px; background: #fff3cd; border: 2px solid #ffc107; padding: 8px 12px; border-radius: 6px; font-size: 8pt; z-index: 1000; max-width: 300px; box-shadow: 0 2px 10px rgba(0,0,0,0.2); }';
        echo '.debug-info h6 { margin: 0 0 5px 0; font-size: 9pt; color: #856404; }';
        echo '.debug-info p { margin: 2px 0; font-size: 7.5pt; color: #856404; }';
        echo '.debug-info code { background: #fff; padding: 1px 4px; border-radius: 3px; font-size: 7pt; }';
    }
    echo '</style>';


    // Contenedor principal
    echo '<div class="factura-container">';

    // HEADER PRINCIPAL
    echo '<div class="factura-header">';
    echo '<div class="header-left">';
    echo '<div class="logo-container">';
    if ($logoUrl) {
        // Construir la ruta completa para verificación
        $rutaVerificacion = $logoUrl;
        if (strpos($logoUrl, 'uploads/') === 0) {
            $rutaVerificacion = __DIR__ . '/../' . $logoUrl;
        }
        
        // Intentar verificar si el archivo existe (solo para archivos locales)
        $existe = false;
        if (!preg_match('/^https?:\\/\\//', $logoUrl)) {
            $existe = file_exists($rutaVerificacion);
        }
        
        if ($existe || preg_match('/^https?:\\/\\//', $logoUrl)) {
            // Mostrar imagen, usar onerror para fallback
            echo '<img src="' . pf_html_escape($logoUrl) . '" alt="Logo Empresa" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'block\';">';
            echo '<div class="logo-placeholder" style="display:none;">LOGO</div>';
        } else {
            echo '<div class="logo-placeholder">LOGO</div>';
        }
    } else {
        echo '<div class="logo-placeholder">LOGO</div>';
    }
    echo '</div>';
    echo '<div class="emisor-header-info">';
    echo '<h1>' . pf_html_escape($emisor['nombre']) . '</h1>';
    echo '<p>RFC: ' . pf_html_escape($emisor['rfc']) . '</p>';
    echo '</div>';
    echo '</div>';
    echo '<div class="header-right">';
    echo '<div class="factura-tipo">';
    echo '<h2>FACTURA</h2>';
    echo '<div class="serie-folio">' . pf_html_escape($factura['serie'] ?? 'A') . '-' . pf_html_escape($factura['folio'] ?? '000001') . '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    // BARRA UUID
    echo '<div class="uuid-bar">';
    echo '<div class="uuid-info">';
    echo '<span class="uuid-label">Folio Fiscal (UUID):</span>';
    echo '<span class="uuid-value">' . pf_html_escape($factura['uuid'] ?? 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX') . '</span>';
    echo '<span class="badge-cfdi">CFDI 4.0</span>';
    echo '</div>';
    echo '<div class="fecha-info">';
    echo '<strong>Fecha y Hora:</strong> ' . date('d/m/Y H:i:s', strtotime($factura['fecha_e'] ?? 'now')) . '<br>';
    echo '<strong>Lugar de Expedición:</strong> ' . pf_html_escape($emisor['cp']) . '';
    echo '</div>';
    echo '</div>';

    // DATOS EMISOR / RECEPTOR
    echo '<div class="datos-section">';
    // Emisor
    echo '<div class="datos-box">';
    echo '<span class="datos-titulo">Emisor</span>';
    echo '<dl class="datos-grid">';
    echo '<dt>Razón Social:</dt><dd class="valor-destacado">' . pf_html_escape($emisor['nombre']) . '</dd>';
    echo '<dt>RFC:</dt><dd>' . pf_html_escape($emisor['rfc']) . '</dd>';
    echo '<dt>Régimen Fiscal:</dt><dd>' . pf_html_escape($emisor['regimen']) . '</dd>';
    echo '<dt>Domicilio Fiscal:</dt><dd>' . pf_html_escape($emisor['direccion']) . '</dd>';
    echo '<dt>C.P.:</dt><dd>' . pf_html_escape($emisor['cp']) . '</dd>';
    echo '</dl>';
    echo '</div>';
    // Receptor
    echo '<div class="datos-box">';
    echo '<span class="datos-titulo">Receptor</span>';
    echo '<dl class="datos-grid">';
    echo '<dt>Razón Social:</dt><dd class="valor-destacado">' . pf_html_escape($receptor['nombre']) . '</dd>';
    echo '<dt>RFC:</dt><dd>' . pf_html_escape($receptor['rfc']) . '</dd>';
    echo '<dt>Régimen Fiscal:</dt><dd>-</dd>';
    echo '<dt>Domicilio Fiscal:</dt><dd>' . pf_html_escape($receptor['domicilio']) . '</dd>';
    echo '<dt>C.P.:</dt><dd>' . pf_html_escape($receptor['cp']) . '</dd>';
    echo '<dt>Uso CFDI:</dt><dd>' . pf_html_escape($factura['uso_cfdi'] ?? 'G03') . '</dd>';
    echo '</dl>';
    echo '</div>';
    echo '</div>';

    // INFORMACIÓN FISCAL
    echo '<div class="info-fiscal">';
    echo '<div class="fiscal-item"><div class="label">Forma de Pago</div><div class="value">' . pf_html_escape($factura['form_pago'] ?? '01') . ' - Efectivo</div></div>';
    echo '<div class="fiscal-item"><div class="label">Método de Pago</div><div class="value">' . pf_html_escape($factura['met_pago'] ?? 'PUE') . '</div></div>';
    echo '<div class="fiscal-item"><div class="label">Moneda</div><div class="value">' . pf_html_escape($config['moneda'] ?? 'MXN') . '</div></div>';
    echo '<div class="fiscal-item"><div class="label">Exportación</div><div class="value">01</div></div>';
    if ($config && !empty($config['condiciones_pago'])) {
        echo '<div class="fiscal-item"><div class="label">Condiciones de Pago</div><div class="value">' . pf_html_escape($config['condiciones_pago']) . '</div></div>';
    }
    echo '</div>';

    // CONCEPTOS
    echo '<div class="conceptos-section">';
    echo '<h3 class="seccion-titulo">Conceptos</h3>';
    echo '<table class="tabla-conceptos">';
    echo '<thead><tr>';
    echo '<th style="width: 80px;">Clave SAT</th>';
    echo '<th style="width: 50px;">Cant.</th>';
    echo '<th style="width: 60px;">Unidad</th>';
    echo '<th>Descripción</th>';
    echo '<th style="width: 90px;">P. Unitario</th>';
    echo '<th style="width: 70px;">Descuento</th>';
    echo '<th style="width: 90px;">Importe</th>';
    echo '</tr></thead>';
    echo '<tbody>';
    foreach ($conceptos as $concepto) {
        echo '<tr>';
        echo '<td><span class="concepto-codigo">' . pf_html_escape($concepto['clave_producto']) . '</span></td>';
        echo '<td>' . number_format($concepto['cantidad'], 2) . '</td>';
        echo '<td><span class="concepto-codigo">' . pf_html_escape($concepto['unidad']) . '</span></td>';
        echo '<td><span class="concepto-descripcion">' . pf_html_escape($concepto['descripcion']) . '</span></td>';
        echo '<td>$' . number_format($concepto['precio_unitario'], 2) . '</td>';
        echo '<td>$0.00</td>';
        echo '<td><strong>$' . number_format($concepto['importe'], 2) . '</strong></td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>';

    // TOTALES
    echo '<div class="totales-section">';
    echo '<div class="importe-letra">';
    echo '<div class="importe-letra-titulo">Importe con Letra</div>';
    echo '<div class="importe-letra-texto">' . pf_html_escape($totalLetra) . '</div>';
    echo '</div>';
    echo '<div class="totales-box">';
    echo '<div class="total-row subtotal"><span class="label">Subtotal</span><span class="value">$' . number_format($subtotal, 2) . ' <span class="moneda">MXN</span></span></div>';
    echo '<div class="total-row impuesto"><span class="label">IVA Trasladado (16%)</span><span class="value">$' . number_format($iva, 2) . '</span></div>';
    echo '<div class="total-row impuesto"><span class="label">IVA Retenido</span><span class="value">$0.00</span></div>';
    echo '<div class="total-row impuesto"><span class="label">ISR Retenido</span><span class="value">$0.00</span></div>';
    echo '<div class="total-row impuesto"><span class="label">IEPS</span><span class="value">$0.00</span></div>';
    echo '<div class="total-row total-final"><span class="label">TOTAL</span><span class="value">$' . number_format($total, 2) . ' MXN</span></div>';
    echo '</div>';
    echo '</div>';

    // SELLOS DIGITALES
    echo '<div class="sellos-section">';
    echo '<h3 class="seccion-titulo">Sellos Digitales</h3>';
    echo '<div class="sellos-grid">';
    echo '<div class="qr-container">';
    echo '<div class="qr-code">';
    if (!$isPreview && isset($factura['uuid']) && $factura['uuid'] != 'PENDIENTE') {
        echo '<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><rect width="100" height="100" fill="#f0f0f0"/><text x="50%" y="50%" text-anchor="middle" dy=".3em" font-size="10" fill="#999">QR</text></svg>';
    } else {
        echo '<i class="bi bi-qr-code" style="font-size: 40px; color: #ccc;"></i>';
    }
    echo '</div>';
    echo '<span class="qr-label">Verificación SAT</span>';
    echo '</div>';
    echo '<div class="sellos-content">';
    if (isset($factura['sello_cfdi']) && $factura['sello_cfdi'] != 'PENDIENTE') {
        echo '<div class="sello-item"><div class="sello-titulo">Sello Digital del CFDI</div><div class="sello-valor">' . pf_html_escape(substr($factura['sello_cfdi'] ?? '', 0, 250)) . '...</div></div>';
    }
    if (isset($factura['sello_sat']) && $factura['sello_sat'] != 'PENDIENTE') {
        echo '<div class="sello-item"><div class="sello-titulo">Sello Digital del SAT</div><div class="sello-valor">' . pf_html_escape(substr($factura['sello_sat'] ?? '', 0, 250)) . '...</div></div>';
    }
    if (isset($factura['cadena_original']) && $factura['cadena_original'] != 'PENDIENTE') {
        echo '<div class="sello-item"><div class="sello-titulo">Cadena Original del Complemento de Certificación</div><div class="sello-valor">' . pf_html_escape(substr($factura['cadena_original'] ?? '', 0, 250)) . '...</div></div>';
    }
    echo '<div class="sello-item"><div class="sello-titulo">No. Certificado SAT: ' . pf_html_escape($factura['no_certificado'] ?? '00000000000000000000') . ' | No. Certificado Emisor: ' . pf_html_escape($factura['no_cert_emisor'] ?? '00000000000000000000') . '</div></div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    // OBSERVACIONES
    if ($config && !empty($config['leyenda_factura'])) {
        echo '<div class="conceptos-section" style="padding-top: 0;">';
        echo '<h3 class="seccion-titulo">Observaciones</h3>';
        echo '<p style="font-size: 9pt; color: #666; padding: 10px; background: var(--color-gris-claro); border-radius: 6px;">';
        echo nl2br(pf_html_escape($config['leyenda_factura']));
        echo '</p>';
        echo '</div>';
    }

    // FOOTER
    echo '<div class="factura-footer">';
    if ($config && !empty($config['leyenda_factura'])) {
        echo '<p class="leyenda-fiscal">' . pf_html_escape($config['leyenda_factura']) . '</p>';
    } else {
        echo '<p class="leyenda-fiscal">Este documento es una representación impresa de un CFDI versión 4.0</p>';
    }
    echo '</div>';

    echo '</div>'; // Cierre factura-container

} catch (Throwable $e) {
    echo '<div class="alert alert-danger">Error: ' . pf_html_escape($e->getMessage()) . '</div>';
    if ($isPreview ?? false) {
        echo '<pre style="background:#fff3cd;padding:15px;border-radius:5px;margin:20px;">';
        echo pf_html_escape($e->getTraceAsString());
        echo '</pre>';
    }
}
