<?php
/**
 * API para obtener la configuración de facturas del usuario actual
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';

header('Content-Type: application/json; charset=utf-8');

$respuesta = [
    'success' => false,
    'message' => 'Error desconocido.',
    'data' => null
];

try {
    // Validar sesión
    $id_usuario = $_SESSION['usuario_id'] ?? $_SESSION['USR_ID'] ?? null;
    
    if (!$id_usuario) {
        throw new Exception('Sesión no válida o expirada.');
    }

    // Obtener ID de sucursal del parámetro GET
    $id_sucursal = (int)($_GET['sucursalId'] ?? 0);

    $db = new Database();
    $conn = $db->getConnection();

    // Si se especificó una sucursal, buscar su configuración
    if ($id_sucursal > 0) {
        $stmt = $conn->prepare("
            SELECT 
                id_config,
                id_usuario,
                id_sucursal,
                nombre_empresa,
                rfc_empresa,
                regimen_fiscal,
                cp_emisor,
                direccion_empresa,
                logo_url,
                color_primario,
                color_secundario,
                tipo_letra,
                tamano_letra,
                serie_factura,
                folio_inicial,
                folio_actual,
                leyenda_factura,
                condiciones_pago,
                observaciones_default
            FROM config_facturas 
            WHERE id_usuario = ? AND id_sucursal = ?
        ");
        $stmt->execute([$id_usuario, $id_sucursal]);
    } else {
        // Si no se especificó sucursal, no devolver configuración
        $stmt = null;
    }
    
    $config = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : false;

    if ($config) {
        // Mapear nombres de BD a nombres de campos del formulario
        $respuesta['data'] = [
            'sucursalId' => (int)($config['id_sucursal'] ?? 0),
            'nombreEmpresa' => $config['nombre_empresa'] ?? '',
            'rfcEmpresa' => $config['rfc_empresa'] ?? '',
            'regimenFiscal' => $config['regimen_fiscal'] ?? '',
            'cpEmisor' => $config['cp_emisor'] ?? '',
            'direccionEmpresa' => $config['direccion_empresa'] ?? '',
            
            'logoEmpresa' => $config['logo_url'] ?? '',
            'colorPrimario' => $config['color_primario'] ?? '#0d6efd',
            'colorSecundario' => $config['color_secundario'] ?? '#6c757d',
            'tipoLetra' => $config['tipo_letra'] ?? 'Arial',
            'tamanoLetra' => (int)($config['tamano_letra'] ?? 12),
            
            'serieFactura' => $config['serie_factura'] ?? 'A',
            'folioInicial' => (int)($config['folio_inicial'] ?? 1),
            'folioActual' => (int)($config['folio_actual'] ?? 0),
            
            'leyendaFactura' => $config['leyenda_factura'] ?? '',
            'condicionesPagoTexto' => $config['condiciones_pago'] ?? '',
            'observacionesDefault' => $config['observaciones_default'] ?? ''
        ];
        
        $respuesta['success'] = true;
        $respuesta['message'] = 'Configuración cargada correctamente.';
    } else {
        // No hay configuración, devolver valores por defecto
        $respuesta['data'] = [
            'nombreEmpresa' => '',
            'rfcEmpresa' => '',
            'regimenFiscal' => '',
            'cpEmisor' => '',
            'direccionEmpresa' => '',
            
            'usoCfdi' => 'G03',
            'formaPago' => '04',
            'metodoPagoDefault' => 'PUE',
            'monedaDefault' => 'MXN',
            'tipoComprobante' => 'I',
            'exportacionDefault' => '01',
            
            'logoEmpresa' => '',
            'colorPrimario' => '#0d6efd',
            'colorSecundario' => '#6c757d',
            'tipoLetra' => 'Arial',
            'tamanoLetra' => 12,
            
            'serieFactura' => 'A',
            'folioInicial' => 1,
            'folioActual' => 0,
            
            'mostrarLogo' => 1,
            'mostrarSelloDigital' => 1,
            'mostrarObservaciones' => 1,
            
            'leyendaFactura' => '',
            'condicionesPagoTexto' => '',
            'observacionesDefault' => ''
        ];
        
        $respuesta['success'] = true;
        $respuesta['message'] = 'No hay configuración guardada. Mostrando valores por defecto.';
    }

} catch (Exception $e) {
    $respuesta['success'] = false;
    $respuesta['message'] = $e->getMessage();
}

echo json_encode($respuesta);
