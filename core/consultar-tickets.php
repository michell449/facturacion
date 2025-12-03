<?php
// config.php inicia la sesión, aseguramos no tener salidas previas
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';

// Limpiar buffer de salida por si config.php dejó espacios en blanco
ob_clean(); 

header('Content-Type: application/json; charset=utf-8');

$respuesta = [
    'success' => false,
    'message' => 'Error desconocido.',
    'tickets' => [],
    'resumen' => ['pendientes' => 0, 'facturados' => 0, 'total_importe' => 0]
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
        // Importante: No uses header('Location: ...'). Lanza excepción para que JS lo maneje.
        throw new Exception('Sesión no válida o expirada.');
    }
    
    $db = new Database();
    $conn = $db->getConnection();

    // Si el JS pide sucursales, respondemos y terminamos el script aquí
    if (isset($_GET['obtener_sucursales']) && $_GET['obtener_sucursales'] == 1) {
        $sqlSuc = "SELECT id_empresa as id, nombre, codigo_suc FROM empresas WHERE id_usuario = ?";
        $stmtSuc = $conn->prepare($sqlSuc);
        $stmtSuc->execute([$id_usuario]);
        $sucursales = $stmtSuc->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'sucursales' => $sucursales]);
        exit; // IMPORTANTE: Detener ejecución aquí
    }
    // ----------------------------------------
    
    // 2. FILTROS (Lógica normal de tickets)
    $params = [$id_usuario];
    $where = "";
    
    if (!empty($_GET['id_empresa'])) {
        $where .= " AND t.id_empresa = ?";
        $params[] = (int)$_GET['id_empresa'];
    }
    if (!empty($_GET['folio'])) {
        $where .= " AND t.folio_ticket LIKE ?";
        $params[] = "%" . trim($_GET['folio']) . "%";
    }
    if (!empty($_GET['estatus'])) {
        $where .= " AND t.estatus = ?";
        $params[] = $_GET['estatus'];
    }
    
    // Filtros de Fecha (Agregados para que funcione tu JS)
    if (!empty($_GET['fecha_desde']) && !empty($_GET['fecha_hasta'])) {
        $where .= " AND DATE(t.fecha_venta) BETWEEN ? AND ?";
        $params[] = $_GET['fecha_desde'];
        $params[] = $_GET['fecha_hasta'];
    }

    // Paginación
    $limite = isset($_GET['limite']) ? (int)$_GET['limite'] : 7;
    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $offset = ($pagina - 1) * $limite;

    // 3. OBTENER TICKETS
    $sql = "
        SELECT 
            t.id_ticket, t.folio_ticket, t.fecha_venta, t.importe_t, t.estatus,
            e.nombre as nombre_sucursal, e.codigo_suc,
            tmp.metodo_pago, tmp.forma_pago
        FROM tickets t
        INNER JOIN empresas e ON t.id_empresa = e.id_empresa
        LEFT JOIN ticket_metodo_pago tmp ON t.id_ticket = tmp.id_ticket
        WHERE e.id_usuario = ?
        {$where}
        ORDER BY t.fecha_venta DESC, t.id_ticket DESC
        LIMIT {$limite} OFFSET {$offset}
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4. OBTENER DETALLES
    $productos_map = [];
    if (!empty($tickets)) {
        $ids = array_column($tickets, 'id_ticket');
        $in_query = implode(',', array_fill(0, count($ids), '?'));
        
        $sql_det = "SELECT id_ticket, id_prod_serv, descr, cant, precio_unit, importe 
                    FROM ticket_detalle WHERE id_ticket IN ($in_query)";
        
        $stmt_det = $conn->prepare($sql_det);
        $stmt_det->execute($ids);
        $raw_productos = $stmt_det->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($raw_productos as $prod) {
            $productos_map[$prod['id_ticket']][] = $prod;
        }
    }

    // 5. RESUMEN
    $sql_count = "SELECT 
        COUNT(*) as total,
        COUNT(CASE WHEN t.estatus='pendiente' THEN 1 END) as pendientes,
        COUNT(CASE WHEN t.estatus='facturado' THEN 1 END) as facturados,
        COALESCE(SUM(t.importe_t),0) as total_importe
        FROM tickets t INNER JOIN empresas e ON t.id_empresa = e.id_empresa
        WHERE e.id_usuario = ? {$where}";
    $stmt_c = $conn->prepare($sql_count);
    $stmt_c->execute($params);
    $resumen = $stmt_c->fetch(PDO::FETCH_ASSOC);

    // 6. ARMAR RESPUESTA
    foreach ($tickets as &$t) {
        $t['productos'] = isset($productos_map[$t['id_ticket']]) ? $productos_map[$t['id_ticket']] : [];
        $t['importe_fmt'] = number_format($t['importe_t'], 2);
        $t['fecha_fmt'] = date('d/m/Y', strtotime($t['fecha_venta']));
        $t['sucursal_fmt'] = $t['nombre_sucursal'] . ' (' . $t['codigo_suc'] . ')';
        $t['items_count'] = count($t['productos']);
    }

    $respuesta['success'] = true;
    $respuesta['tickets'] = $tickets;
    $respuesta['total_registros'] = $resumen['total']; 
    $respuesta['total_paginas'] = ceil($resumen['total'] / $limite);
    $respuesta['resumen'] = [
        'pendientes' => $resumen['pendientes'],
        'facturados' => $resumen['facturados'],
        'importe_fmt' => number_format($resumen['total_importe'], 2)
    ];

} catch (Exception $e) {
    http_response_code(400); 
    $respuesta['message'] = $e->getMessage();
}

echo json_encode($respuesta);
?>