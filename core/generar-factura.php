<?php
// Limpiar cualquier salida previa
if (ob_get_level()) {
    ob_end_clean();
}

// Suprimir warnings que puedan romper el JSON
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '0');

// Iniciar sesión sin cargar config.php completo
session_start();

require_once __DIR__ . '/class/db.php';

// Enviar headers JSON
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');

$respuesta = [
    'success' => false,
    'message' => 'Error desconocido.',
    'folio' => null,
    'id_factura' => null
];

try {
    // ---------------------------------------------------------
    // 1. VALIDACIONES DE SEGURIDAD Y DATOS
    // ---------------------------------------------------------
    
    error_log("=== INICIO GENERAR FACTURA ===");
    
    // Validar Sesión
    $id_usuario = $_SESSION['usuario_id'] ?? $_SESSION['USR_ID'] ?? null;
    if (!$id_usuario) {
        error_log("ERROR: Sesión no válida");
        throw new Exception('Sesión no válida o expirada.');
    }
    
    error_log("Usuario ID: {$id_usuario}");

    // Obtener datos del JSON
    $input = file_get_contents('php://input');
    error_log("Input recibido: " . substr($input, 0, 200));
    
    $datos = json_decode($input, true);
    
    if (!$datos) {
        error_log("ERROR: JSON inválido - " . json_last_error_msg());
        throw new Exception('No se recibieron datos válidos.');
    }
    if (empty($datos['id_sucursal'])) {
        error_log("ERROR: Falta id_sucursal");
        throw new Exception('Falta la sucursal.');
    }
    if (empty($datos['receptor']['rfc'])) {
        error_log("ERROR: Falta RFC receptor");
        throw new Exception('Falta RFC del receptor.');
    }
    if (empty($datos['conceptos'])) {
        error_log("ERROR: Sin conceptos");
        throw new Exception('Debe haber al menos un concepto.');
    }
    
    error_log("Datos validados correctamente");

    $db = new Database();
    $conn = $db->getConnection();

    // ---------------------------------------------------------
    // 2. OBTENER DATOS DE LA EMPRESA (EMISOR) Y CONFIG
    // ---------------------------------------------------------
    
    // Configuración de folios
    $sqlConfig = "SELECT * FROM config_facturas WHERE id_usuario = ? AND id_sucursal = ?";
    $stmtConfig = $conn->prepare($sqlConfig);
    $stmtConfig->execute([$id_usuario, $datos['id_sucursal']]);
    $config = $stmtConfig->fetch(PDO::FETCH_ASSOC);

    if (!$config) throw new Exception('No hay configuración de facturas para esta sucursal.');

    // Datos de la Sucursal (Emisor)
    $sqlSuc = "SELECT * FROM empresas WHERE id_empresa = ? AND id_usuario = ?";
    $stmtSuc = $conn->prepare($sqlSuc);
    $stmtSuc->execute([$datos['id_sucursal'], $id_usuario]);
    $sucursal = $stmtSuc->fetch(PDO::FETCH_ASSOC);

    if (!$sucursal) throw new Exception('Sucursal no encontrada.');

    // ---------------------------------------------------------
    // 3. CÁLCULOS MATEMÁTICOS (CABECERA)
    // ---------------------------------------------------------
    
    $subtotalGeneral = 0;
    $totalImpuestos = 0;
    
    // Recalculamos el backend para seguridad (no confiar solo en el JS)
    foreach ($datos['conceptos'] as $c) {
        $cant = floatval($c['cantidad']);
        $precio = floatval($c['precio']);
        $importe = $cant * $precio;
        
        $subtotalGeneral += $importe;
        // Asumiendo IVA 16% general (luego esto debe ser dinámico por producto)
        $totalImpuestos += ($importe * 0.16); 
    }
    
    $totalGeneral = $subtotalGeneral + $totalImpuestos;

    // Generar Folio
    $serie = $config['serie_factura'] ?? 'A';
    $folioActual = intval($config['folio_actual'] ?? 0) + 1;
    $folioString = str_pad($folioActual, 6, '0', STR_PAD_LEFT);

    // ---------------------------------------------------------
    // 4. GUARDADO EN BASE DE DATOS (TRANSACCIÓN)
    // ---------------------------------------------------------
    
    $conn->beginTransaction();

    try {
        // A. INSERTAR EN TABLA 'FACTURAS' (SOLO CABECERA)
        // Nota: Quitamos columnas de productos de aquí
        $sqlCabecera = "INSERT INTO facturas (
            id_ticket, id_usuario, id_empresa,
            folio_interno, serie_interno, fecha_emision,
            rfc_receptor, razon_social_receptor, regimen_fiscal_receptor,
            domicilio_fiscal_receptor, uso_cfdi,
            moneda, tipo_cambio, 
            subtotal, impuestos_trasladados, total,
            tipo_comprobante, metodo_pago, forma_pago, exportacion, lugar_expedicion,
            estatus
        ) VALUES (
            ?, ?, ?, 
            ?, ?, NOW(), 
            ?, ?, ?, 
            ?, ?, 
            'MXN', 1, 
            ?, ?, ?, 
            'I', ?, ?, '01', ?, 
            'pendiente'
        )";

        $stmtCab = $conn->prepare($sqlCabecera);
        $stmtCab->execute([
            $datos['id_ticket'] ?? null,
            $id_usuario,
            $datos['id_sucursal'],
            $folioString,                   // folio_interno
            $serie,                         // serie_interno
            $datos['receptor']['rfc'],
            $datos['receptor']['nombre'],
            $datos['receptor']['regimen'],
            $datos['receptor']['cp'],       // domicilio_fiscal_receptor (CP)
            $datos['receptor']['uso_cfdi'],
            $subtotalGeneral,
            $totalImpuestos,
            $totalGeneral,
            $datos['metodo_pago'],
            $datos['forma_pago'],
            $sucursal['cp']                 // lugar_expedicion
        ]);

        $id_factura = $conn->lastInsertId();

        // B. INSERTAR EN TABLA 'FACTURAS_DETALLES' (LOS PRODUCTOS)
        $sqlDetalle = "INSERT INTO facturas_detalles (
            id_factura, 
            clave_prod_serv, clave_unidad, 
            cantidad, unidad, descripcion, 
            valor_unitario, importe,
            objeto_imp, impuesto_base, impuesto_tipo, impuesto_tasa, impuesto_importe
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, '02', ?, '002', 0.160000, ?)";

        $stmtDet = $conn->prepare($sqlDetalle);

        foreach ($datos['conceptos'] as $concepto) {
            $cant = floatval($concepto['cantidad']);
            $precio = floatval($concepto['precio']); // Valor Unitario
            $importe = $cant * $precio;
            $impuesto = $importe * 0.16;

            $stmtDet->execute([
                $id_factura,
                $concepto['clave'] ?? '01010101', // Clave SAT
                'H87',                            // Clave Unidad SAT (Deberías enviarla desde JS también)
                $cant,
                $concepto['unidad'] ?? 'Pieza',   // Nombre unidad
                $concepto['descripcion'],
                $precio,
                $importe,
                $importe,   // Base para impuesto
                $impuesto   // Importe del impuesto
            ]);
        }

        // C. ACTUALIZAR FOLIO EN CONFIGURACIÓN
        $sqlUpdateFolio = "UPDATE config_facturas SET folio_actual = ? WHERE id_config = ?";
        // Ojo: usa el ID correcto, aquí asumo id_config o where usuario/sucursal
        $stmtUpd = $conn->prepare("UPDATE config_facturas SET folio_actual = ? WHERE id_usuario = ? AND id_sucursal = ?");
        $stmtUpd->execute([$folioActual, $id_usuario, $datos['id_sucursal']]);

        // D. ACTUALIZAR TICKET (SI EXISTE)
        if (!empty($datos['id_ticket'])) {
            $stmtTicket = $conn->prepare("UPDATE tickets SET estatus = 'facturado' WHERE id_ticket = ?");
            $stmtTicket->execute([$datos['id_ticket']]);
        }

        $conn->commit();

        $respuesta['success'] = true;
        $respuesta['message'] = 'Factura guardada correctamente (Pre-factura).';
        $respuesta['folio'] = $serie . '-' . $folioString;
        $respuesta['id_factura'] = $id_factura;

    } catch (Exception $e) {
        $conn->rollBack();
        throw new Exception('Error en base de datos: ' . $e->getMessage());
    }

} catch (Exception $e) {
    error_log("EXCEPCIÓN EN GENERAR-FACTURA: " . $e->getMessage());
    error_log("Trace: " . $e->getTraceAsString());
    http_response_code(400);
    $respuesta['message'] = $e->getMessage();
}

echo json_encode($respuesta);
exit;