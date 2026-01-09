<?php
// ver-vista-previa.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';

// 1. Obtener ID de sucursal
$id_sucursal = isset($_GET['id_sucursal']) ? (int)$_GET['id_sucursal'] : 0;
$id_usuario = $_SESSION['usuario_id'] ?? 0;

if ($id_sucursal <= 0) die("ID de sucursal no válido");

$db = new Database();
$conn = $db->getConnection();

// 2. Obtener la configuración REAL de la base de datos
$stmt = $conn->prepare("SELECT * FROM config_facturas WHERE id_sucursal = ? ORDER BY id_config DESC LIMIT 1");
$stmt->execute([$id_sucursal]);
$config = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$config) {
    die("No hay configuración guardada para esta sucursal. Por favor guarda la configuración primero.");
}

// 3. Simular datos de una factura (Dummy Data)
$factura = [
    'serie_interno' => $config['serie_factura'] ?? 'A',
    'folio_interno' => $config['folio_actual'] ?? '1',
    'uuid' => '00000000-0000-0000-0000-000000000000', // UUID Ficticio
    'fecha_emision' => date('Y-m-d H:i:s'),
    'lugar_expedicion' => $config['cp_emisor'],
    'forma_pago' => '01 - Efectivo',
    'metodo_pago' => 'PUE - Pago en una sola exhibición',
    'moneda' => 'MXN',
    'total' => 1160.00,
    'subtotal' => 1000.00,
    'impuestos_trasladados' => 160.00,
    'impuestos_retenidos' => 0.00,
    
    // Datos del Emisor (Desde la Configuración)
    'razon_social_emisor' => $config['nombre_empresa'],
    'rfc_emisor' => $config['rfc_empresa'],
    'regimen_fiscal_emisor' => $config['regimen_fiscal'] ?? '601',
    'direccion_emisor' => $config['direccion_empresa'],
    'colonia_emisor' => '',
    'cp_emisor' => $config['cp_emisor'],
    
    // Datos del Receptor (Ficticios)
    'razon_social_receptor' => 'CLIENTE DE PRUEBA S.A. DE C.V.',
    'rfc_receptor' => 'XAXX010101000',
    'domicilio_fiscal_receptor' => '06000',
    'uso_cfdi' => 'G03 - Gastos en general',
    'regimen_fiscal_receptor' => '601',
    
    // Sellos (Ficticios)
    'sello_cfdi' => 'SelloDePrueba...ABC123456...',
    'sello_sat' => 'SelloSATDePrueba...XYZ987654...',
    'cadena_original' => '||1.1|00000000-0000-0000-0000-000000000000|...',
    'rfc_prov_certif' => 'LSO1306189R5',
    'no_certificado_sat' => '00001000000500000000',
    'fecha_timbrado' => date('Y-m-d H:i:s')
];

// 4. Simular Conceptos
$conceptos = [
    [
        'cantidad' => 1,
        'unidad' => 'H87',
        'clave_unidad' => 'Pieza',
        'clave_prod_serv' => '01010101',
        'descripcion' => 'Producto de Prueba para Vista Previa',
        'precio_unitario' => 1000.00,
        'importe' => 1000.00,
        'impuesto' => 160.00
    ]
];

// 5. Incluir la plantilla (Ahora sí funcionará porque las variables existen)
// Asegúrate de que la ruta apunte al archivo que creamos en el paso anterior
require 'plantilla-factura.php'; 
?>