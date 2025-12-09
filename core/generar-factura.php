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
    
    // Validar que el ticket exista y pertenezca al usuario (si se proporciona)
    if (!empty($datos['id_ticket'])) {
        $sqlTicket = "SELECT t.* FROM tickets t 
                      INNER JOIN empresas e ON t.id_empresa = e.id_empresa 
                      WHERE t.id_ticket = ? AND e.id_usuario = ?";
        $stmtTicket = $conn->prepare($sqlTicket);
        $stmtTicket->execute([$datos['id_ticket'], $id_usuario]);
        $ticket = $stmtTicket->fetch(PDO::FETCH_ASSOC);
        
        if (!$ticket) {
            throw new Exception('El ticket no existe o no tiene permisos para facturarlo.');
        }
        
        if ($ticket['estatus'] === 'facturado') {
            throw new Exception('Este ticket ya ha sido facturado.');
        }
    }
    
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
    $primerConcepto = $datos['conceptos'][0]; // Para datos que van en la tabla principal
    
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
    $folio = str_pad($folioActual, 6, '0', STR_PAD_LEFT);
    
    // 8. GENERAR UUID TEMPORAL (se reemplazará al timbrar)
    $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
    
    // 9. INICIAR TRANSACCIÓN
    $conn->beginTransaction();
    
    try {
        // 10. INSERTAR FACTURA (adaptado a tu estructura)
        $sqlInsert = "INSERT INTO facturas (
            id_ticket, folio, version, serie, uuid, fecha_e,
            form_pago, no_certificado, subtotal, moneda, exportacion,
            total, tipo_compr, met_pago, lugar_exp, tipo_cambio,
            rfc_emisor, rfc_receptor, uso_cfdi, objeto_imp,
            clave_prod_serv, cantidad, unidad, valor_unit, importe,
            total_imp_tras, timbre, fecha_timbre, sello_sat, estatus
        ) VALUES (?, ?, ?, ?, ?, CURDATE(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), ?, ?)";
        
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->execute([
            $datos['id_ticket'] ?? 0,                           // id_ticket
            $folio,                                              // folio
            '4.0',                                               // version (CFDI 4.0)
            $serie,                                              // serie
            $uuid,                                               // uuid (temporal)
            // fecha_e se establece con CURDATE()
            $datos['forma_pago'],                                // form_pago
            '00000000000000000000',                              // no_certificado (temporal)
            $subtotal,                                           // subtotal
            'MXN',                                               // moneda
            '01',                                                // exportacion
            $total,                                              // total
            'I',                                                 // tipo_compr (I=Ingreso)
            $datos['metodo_pago'],                               // met_pago
            intval($sucursal['codigo_postal'] ?? 0),            // lugar_exp
            1.00,                                                // tipo_cambio
            $sucursal['rfc'],                                    // rfc_emisor
            $datos['receptor']['rfc'],                           // rfc_receptor
            intval($datos['receptor']['uso_cfdi']),              // uso_cfdi
            '02',                                                // objeto_imp
            intval($primerConcepto['clave'] ?? 1010101),        // clave_prod_serv (primer concepto)
            floatval($primerConcepto['cantidad']),               // cantidad (primer concepto)
            $primerConcepto['unidad'] ?? 'H87',                  // unidad (primer concepto)
            floatval($primerConcepto['precio']),                 // valor_unit (primer concepto)
            floatval($primerConcepto['cantidad']) * floatval($primerConcepto['precio']), // importe
            $iva,                                                // total_imp_tras
            'PENDIENTE',                                         // timbre
            // fecha_timbre se establece con CURDATE()
            'PENDIENTE',                                         // sello_sat
            'pendiente'                                          // estatus
        ]);
        
        $id_factura = $conn->lastInsertId();
        
        // 11. SI HAY MÁS DE UN CONCEPTO, GUARDARLOS EN UNA TABLA AUXILIAR (si existe)
        // Verificar si existe tabla factura_detalle o similar
        try {
            $checkTable = $conn->query("SHOW TABLES LIKE 'factura_detalle'");
            if ($checkTable->rowCount() > 0) {
                $sqlDetalle = "INSERT INTO factura_detalle (
                    id_factura, descripcion, cantidad, precio_unitario, importe,
                    clave_producto, unidad
                ) VALUES (?, ?, ?, ?, ?, ?, ?)";
                
                $stmtDetalle = $conn->prepare($sqlDetalle);
                
                foreach ($datos['conceptos'] as $concepto) {
                    $cantidad = floatval($concepto['cantidad']);
                    $precio = floatval($concepto['precio']);
                    $importe = $cantidad * $precio;
                    
                    $stmtDetalle->execute([
                        $id_factura,
                        $concepto['descripcion'],
                        $cantidad,
                        $precio,
                        $importe,
                        $concepto['clave'] ?? '01010101',
                        $concepto['unidad'] ?? 'H87'
                    ]);
                }
            }
        } catch (Exception $e) {
            // Si no existe la tabla, continuamos sin error
        }
        
        // 12. ACTUALIZAR FOLIO EN CONFIGURACIÓN
        $sqlUpdateFolio = "UPDATE config_facturas SET folioActual = ? WHERE id_usuario = ? AND id_sucursal = ?";
        $stmtUpdateFolio = $conn->prepare($sqlUpdateFolio);
        $stmtUpdateFolio->execute([$folioActual, $id_usuario, $datos['id_sucursal']]);
        
        // 13. SI VIENE DE UN TICKET, ACTUALIZARLO
        if (!empty($datos['id_ticket'])) {
            $sqlUpdateTicket = "UPDATE tickets SET estatus = 'facturado' WHERE id_ticket = ?";
            $stmtUpdateTicket = $conn->prepare($sqlUpdateTicket);
            $stmtUpdateTicket->execute([$datos['id_ticket']]);
        }
        
        // 14. COMMIT
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
