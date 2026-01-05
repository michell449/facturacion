<?php
// API para que los clientes busquen sus tickets
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';

// Limpiar buffer de salida
ob_clean();

header('Content-Type: application/json; charset=utf-8');

$respuesta = [
    'success' => false,
    'message' => 'Error desconocido.',
    'ticket' => null
];

try {
    // 1. VALIDAR DATOS RECIBIDOS
    $nombre_empresa = $_POST['nombre_empresa'] ?? null;
    $folio = $_POST['folio'] ?? null;
    $monto = $_POST['monto'] ?? null;

    if (!$nombre_empresa || !$folio || !$monto) {
        throw new Exception('Faltan datos requeridos (nombre_empresa, folio, monto).');
    }

    // Validar que el monto sea numérico
    $monto = floatval($monto);
    if ($monto <= 0) {
        throw new Exception('El monto debe ser mayor a cero.');
    }

    $db = new Database();
    $conn = $db->getConnection();

    // 2. BUSCAR EMPRESA POR NOMBRE
    $sqlEmpresa = "SELECT id_empresa FROM empresas 
                   WHERE (razon_social = ? OR nombre = ?) AND estatus = 1
                   LIMIT 1";
    $stmtEmpresa = $conn->prepare($sqlEmpresa);
    $stmtEmpresa->execute([$nombre_empresa, $nombre_empresa]);
    
    if ($stmtEmpresa->rowCount() == 0) {
        throw new Exception('No se encontró la empresa "' . htmlspecialchars($nombre_empresa) . '". Verifica que el nombre sea correcto.');
    }

    $empresa = $stmtEmpresa->fetch(PDO::FETCH_ASSOC);
    $id_empresa = $empresa['id_empresa'];

    // 3. BUSCAR TICKET EN LA BD
    // Buscar por folio, monto e id_empresa
    // Se buscan tickets con estatus 'pendiente' (sin facturar)
    $sqlBuscar = "SELECT 
                    t.id_ticket,
                    t.id_empresa,
                    t.folio_ticket,
                    t.fecha_venta,
                    t.importe_t,
                    t.subtotal,
                    t.impuesto_t,
                    t.estatus,
                    e.nombre as sucursal,
                    e.codigo_suc,
                    e.razon_social
                  FROM tickets t
                  INNER JOIN empresas e ON t.id_empresa = e.id_empresa
                  WHERE t.folio_ticket = ?
                    AND t.importe_t = ?
                    AND t.id_empresa = ?
                    AND t.estatus = 'pendiente'";

    $stmtBuscar = $conn->prepare($sqlBuscar);
    $stmtBuscar->execute([$folio, $monto, $id_empresa]);

    if ($stmtBuscar->rowCount() == 0) {
        throw new Exception('No se encontró un ticket pendiente en "' . htmlspecialchars($nombre_empresa) . '" con folio "' . htmlspecialchars($folio) . '" y monto $' . number_format($monto, 2) . '. Verifica los datos o que el ticket no esté ya facturado.');
    }

    $ticket = $stmtBuscar->fetch(PDO::FETCH_ASSOC);

    // 4. OBTENER DETALLES DEL TICKET (artículos)
    $sqlDetalles = "SELECT 
                        id_detalle,
                        descr,
                        cant,
                        precio_unit,
                        importe
                    FROM ticket_detalle
                    WHERE id_ticket = ?
                    ORDER BY id_detalle ASC";

    $stmtDetalles = $conn->prepare($sqlDetalles);
    $stmtDetalles->execute([$ticket['id_ticket']]);
    $detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);

    // 5. OBTENER MÉTODOS DE PAGO
    $sqlPagos = "SELECT 
                    metodo_pago,
                    forma_pago,
                    monto
                FROM ticket_metodo_pago
                WHERE id_ticket = ?";

    $stmtPagos = $conn->prepare($sqlPagos);
    $stmtPagos->execute([$ticket['id_ticket']]);
    $pagos = $stmtPagos->fetchAll(PDO::FETCH_ASSOC);

    // 6. PREPARAR RESPUESTA
    $respuesta['success'] = true;
    $respuesta['message'] = 'Ticket encontrado.';
    $respuesta['ticket'] = [
        'id_ticket' => $ticket['id_ticket'],
        'id_empresa' => $ticket['id_empresa'],
        'folio' => $ticket['folio_ticket'],
        'fecha_venta' => $ticket['fecha_venta'],
        'sucursal' => $ticket['sucursal'],
        'razon_social' => $ticket['razon_social'],
        'codigo_sucursal' => $ticket['codigo_suc'],
        'subtotal' => floatval($ticket['subtotal']),
        'impuesto' => floatval($ticket['impuesto_t']),
        'total' => floatval($ticket['importe_t']),
        'detalles' => $detalles,
        'pagos' => $pagos
    ];

} catch (Exception $e) {
    $respuesta['success'] = false;
    $respuesta['message'] = $e->getMessage();
    error_log("Error en buscar-ticket-cliente.php: " . $e->getMessage());
}

echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
exit;
?>
