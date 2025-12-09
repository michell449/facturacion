<?php
/**
 * core/funciones-configuracion.php
 * Lógica pura para obtener y guardar configuraciones
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';

function obtenerConfiguracionSucursal($id_usuario, $id_sucursal) {
    $db = new Database();
    $conn = $db->getConnection();

    // Valores por defecto (Default Configuration)
    $defaultConfig = [
        'sucursalId' => 0,
        'nombreEmpresa' => '',
        'rfcEmpresa' => '',
        'regimenFiscal' => '',
        'cpEmisor' => '',
        'direccionEmpresa' => '',
        'logoEmpresa' => '',
        'colorPrimario' => '#0d6efd',
        'colorSecundario' => '#6c757d',
        'tipoLetra' => 'Arial',
        'tamanoLetra' => 12,
        'serieFactura' => 'A',
        'folioInicial' => 1,
        'folioActual' => 0,
        'leyendaFactura' => '',
        'condicionesPagoTexto' => '',
        'observacionesDefault' => '',
        // Extras
        'usoCfdi' => 'G03',
        'formaPago' => '04',
        'metodoPagoDefault' => 'PUE',
        'monedaDefault' => 'MXN',
        'exportacionDefault' => '01',
        'mostrarLogo' => 1,
        'mostrarSelloDigital' => 1,
        'mostrarObservaciones' => 1
    ];

    // Si no hay sucursal seleccionada, retornamos defaults
    if ($id_sucursal <= 0) {
        return $defaultConfig;
    }

    // Consulta BD
    $sql = "SELECT * FROM config_facturas WHERE id_usuario = ? AND id_sucursal = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_usuario, $id_sucursal]);
    $config = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$config) {
        // Se retorna default pero con el ID de sucursal que intentaron buscar
        $defaultConfig['sucursalId'] = $id_sucursal;
        return $defaultConfig; 
    }

    // Mapeo de BD a estructura deseada
    return [
        'sucursalId' => (int)$config['id_sucursal'],
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
        'observacionesDefault' => $config['observaciones_default'] ?? '',
        
        // Mantenemos estos campos si los usas en el front aunque no vengan de BD por ahora
        'usoCfdi' => 'G03',
        'formaPago' => '04',
        'metodoPagoDefault' => 'PUE',
        'monedaDefault' => 'MXN',
        'exportacionDefault' => '01'
    ];
}
?>