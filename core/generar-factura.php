<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';

header('Content-Type: application/json; charset=utf-8');

$respuesta = [
    'success' => false,
    'message' => 'Error desconocido.',
    'folio' => null
];

try {
    // 1. VALIDAR SESIÓN
    $id_usuario = null;
    if (isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id'])) {
        $id_usuario = (int)$_SESSION['usuario_id'];
    } elseif (isset($_SESSION['USR_ID']) && !empty($_SESSION['USR_ID'])) {
        $id_usuario = (int)$_SESSION['USR_ID'];
    }
    
    if (!$id_usuario) {
        throw new Exception('Sesión no válida o expirada.');
    }

    // 2. OBTENER DATOS DEL POST
    $input = file_get_contents('php://input');
    $datos = json_decode($input, true);
    
    if (!$datos) {
        throw new Exception('No se recibieron datos válidos.');
    }
    
    // 3. VALIDAR DATOS REQUERIDOS
    if (empty($datos['id_sucursal'])) {
        throw new Exception('Debe seleccionar una sucursal.');
    }
    
    if (empty($datos['receptor']['rfc']) || empty($datos['receptor']['nombre'])) {
        throw new Exception('Los datos del receptor son incompletos.');
    }
    
    if (empty($datos['conceptos']) || !is_array($datos['conceptos'])) {
        throw new Exception('Debe agregar al menos un concepto.');
    }
    
    $db = new Database();
    $conn = $db->getConnection();
    
    // 4. OBTENER CONFIGURACIÓN DE LA SUCURSAL
    $sqlConfig = "SELECT * FROM config_facturas WHERE id_usuario = ? AND id_sucursal = ?";
    $stmtConfig = $conn->prepare($sqlConfig);
    $stmtConfig->execute([$id_usuario, $datos['id_sucursal']]);
    $config = $stmtConfig->fetch(PDO::FETCH_ASSOC);
    
    if (!$config) {
        throw new Exception('La sucursal no tiene configuración de facturación.');
    }
    
    // 5. OBTENER DATOS DE LA SUCURSAL
    $sqlSuc = "SELECT * FROM empresas WHERE id_empresa = ? AND id_usuario = ?";
    $stmtSuc = $conn->prepare($sqlSuc);
    $stmtSuc->execute([$datos['id_sucursal'], $id_usuario]);
    $sucursal = $stmtSuc->fetch(PDO::FETCH_ASSOC);
    
    if (!$sucursal) {
        throw new Exception('Sucursal no encontrada.');
    }
    
    // 6. CALCULAR TOTALES
    $subtotal = 0;
    foreach ($datos['conceptos'] as $concepto) {
        $cantidad = floatval($concepto['cantidad']);
        $precio = floatval($concepto['precio']);
        $subtotal += $cantidad * $precio;
    }
    
    $iva = $subtotal * 0.16;
    $total = $subtotal + $iva;
    
    // 7. GENERAR FOLIO
    $serie = $config['serieFactura'] ?? 'A';
    $folioActual = intval($config['folioActual'] ?? 0) + 1;
    $folio = $serie . str_pad($folioActual, 4, '0', STR_PAD_LEFT);
    
    // 8. INICIAR TRANSACCIÓN
    $conn->beginTransaction();
    
    try {
        // 9. INSERTAR FACTURA (asumiendo que existe una tabla facturas)
        $sqlInsert = "INSERT INTO facturas (
            id_usuario, id_sucursal, id_ticket,
            folio, serie, fecha_emision,
            receptor_rfc, receptor_nombre, receptor_cp, receptor_domicilio, receptor_correo,
            receptor_regimen, uso_cfdi,
            forma_pago, metodo_pago,
            subtotal, iva, total,
            observaciones, estatus,
            created_at
        ) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pendiente', NOW())";
        
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->execute([
            $id_usuario,
            $datos['id_sucursal'],
            $datos['id_ticket'] ?? null,
            $folio,
            $serie,
            $datos['receptor']['rfc'],
            $datos['receptor']['nombre'],
            $datos['receptor']['cp'],
            $datos['receptor']['domicilio'] ?? '',
            $datos['receptor']['correo'] ?? '',
            $datos['receptor']['regimen'] ?? '605',
            $datos['receptor']['uso_cfdi'],
            $datos['forma_pago'],
            $datos['metodo_pago'],
            $subtotal,
            $iva,
            $total,
            $datos['observaciones'] ?? ''
        ]);
        
        $id_factura = $conn->lastInsertId();
        
        // 10. INSERTAR CONCEPTOS
        $sqlConcepto = "INSERT INTO factura_conceptos (
            id_factura, descripcion, cantidad, precio_unitario, importe,
            clave_producto, clave_unidad
        ) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmtConcepto = $conn->prepare($sqlConcepto);
        
        foreach ($datos['conceptos'] as $concepto) {
            $cantidad = floatval($concepto['cantidad']);
            $precio = floatval($concepto['precio']);
            $importe = $cantidad * $precio;
            
            $stmtConcepto->execute([
                $id_factura,
                $concepto['descripcion'],
                $cantidad,
                $precio,
                $importe,
                $concepto['clave'] ?? '01010101',
                $concepto['unidad'] ?? 'H87'
            ]);
        }
        
        // 11. ACTUALIZAR FOLIO EN CONFIGURACIÓN
        $sqlUpdateFolio = "UPDATE config_facturas SET folioActual = ? WHERE id_usuario = ? AND id_sucursal = ?";
        $stmtUpdateFolio = $conn->prepare($sqlUpdateFolio);
        $stmtUpdateFolio->execute([$folioActual, $id_usuario, $datos['id_sucursal']]);
        
        // 12. SI VIENE DE UN TICKET, ACTUALIZARLO
        if (!empty($datos['id_ticket'])) {
            $sqlUpdateTicket = "UPDATE tickets SET estatus = 'facturado', id_factura = ? WHERE id_ticket = ?";
            $stmtUpdateTicket = $conn->prepare($sqlUpdateTicket);
            $stmtUpdateTicket->execute([$id_factura, $datos['id_ticket']]);
        }
        
        // 13. COMMIT
        $conn->commit();
        
        $respuesta['success'] = true;
        $respuesta['message'] = 'Factura generada correctamente.';
        $respuesta['folio'] = $folio;
        $respuesta['id_factura'] = $id_factura;
        
    } catch (Exception $e) {
        $conn->rollBack();
        throw new Exception('Error al guardar la factura: ' . $e->getMessage());
    }
    
} catch (Exception $e) {
    http_response_code(400);
    $respuesta['message'] = $e->getMessage();
}

echo json_encode($respuesta);
?>
