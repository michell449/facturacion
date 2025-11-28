<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';

header('Content-Type: application/json; charset=utf-8');

$respuesta = [
    'success' => false,
    'message' => 'Error desconocido.',
    'tickets' => [],
    'total_tickets' => 0,
    'resumen' => [
        'pendientes' => 0,
        'facturados' => 0,
        'total_importe' => 0
    ]
];

try {
    // Verificar sesión de usuario
    $id_usuario = null;
    if (isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id'])) {
        $id_usuario = (int)$_SESSION['usuario_id'];
    } elseif (isset($_SESSION['USR_ID']) && !empty($_SESSION['USR_ID'])) {
        $id_usuario = (int)$_SESSION['USR_ID'];
    }
    
    if (!$id_usuario) {
        throw new Exception('Sesión de usuario no válida. ID de usuario no encontrado en la sesión.');
    }
    
    $db = new Database();
    $conn = $db->getConnection();
    
    // Parámetros opcionales para filtrado
    $filtros = [];
    $params = [$id_usuario];
    $where_adicional = "";
    
    // Filtro por empresa específica (opcional)
    if (isset($_GET['id_empresa']) && !empty($_GET['id_empresa'])) {
        $id_empresa = (int)$_GET['id_empresa'];
        
        // Verificar que la empresa pertenece al usuario
        $stmt_verify = $conn->prepare("SELECT id_empresa FROM empresas WHERE id_empresa = ? AND id_usuario = ?");
        $stmt_verify->execute([$id_empresa, $id_usuario]);
        if (!$stmt_verify->fetch()) {
            throw new Exception('No tienes permisos para acceder a esta empresa.');
        }
        
        $where_adicional .= " AND t.id_empresa = ?";
        $params[] = $id_empresa;
    }
    
    // Filtro por folio (opcional)
    if (isset($_GET['folio']) && !empty($_GET['folio'])) {
        $folio = trim($_GET['folio']);
        $where_adicional .= " AND t.folio_ticket LIKE ?";
        $params[] = "%{$folio}%";
    }
    
    // Filtro por fecha (opcional)
    if (isset($_GET['fecha_inicio']) && !empty($_GET['fecha_inicio'])) {
        $fecha_inicio = $_GET['fecha_inicio'];
        $where_adicional .= " AND t.fecha_venta >= ?";
        $params[] = $fecha_inicio;
    }
    
    if (isset($_GET['fecha_fin']) && !empty($_GET['fecha_fin'])) {
        $fecha_fin = $_GET['fecha_fin'];
        $where_adicional .= " AND t.fecha_venta <= ?";
        $params[] = $fecha_fin;
    }
    
    // Filtro por estatus (opcional)
    if (isset($_GET['estatus']) && !empty($_GET['estatus']) && 
        in_array($_GET['estatus'], ['pendiente', 'facturado'])) {
        $estatus = $_GET['estatus'];
        $where_adicional .= " AND t.estatus = ?";
        $params[] = $estatus;
    }
    
    // Filtro por rango de importe (opcional)
    if (isset($_GET['importe_min']) && !empty($_GET['importe_min'])) {
        $importe_min = (float)$_GET['importe_min'];
        $where_adicional .= " AND t.importe_t >= ?";
        $params[] = $importe_min;
    }
    
    if (isset($_GET['importe_max']) && !empty($_GET['importe_max'])) {
        $importe_max = (float)$_GET['importe_max'];
        $where_adicional .= " AND t.importe_t <= ?";
        $params[] = $importe_max;
    }
    
    // Paginación
    $limite = isset($_GET['limite']) ? (int)$_GET['limite'] : 20;
    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    
    // Validar y sanitizar valores
    $limite = max(1, min($limite, 100)); // Entre 1 y 100 registros por página
    $pagina = max(1, $pagina); // Mínimo página 1
    $offset = ($pagina - 1) * $limite;
    
    // Consulta principal para obtener todos los tickets del usuario
    $sql = "
        SELECT 
            t.id_ticket,
            t.folio_ticket,
            t.fecha_venta,
            t.importe_t,
            t.subtotal,
            t.impuesto_t,
            t.estatus,
            e.id_empresa,
            e.razon_social,
            e.nombre as nombre_sucursal,
            e.codigo_suc,
            e.rfc,
            e.direccion,
            e.cp,
            e.colonia,
            COUNT(td.id_ticket) as total_productos,
            GROUP_CONCAT(
                CONCAT(td.descr, ' (', td.cant, ' x $', td.precio_unit, ')')
                ORDER BY td.id_prod_serv 
                SEPARATOR '; '
            ) as productos_detalle,
            tmp.metodo_pago,
            tmp.forma_pago,
            tmp.monto as monto_pago,
            DATEDIFF(CURDATE(), t.fecha_venta) as dias_transcurridos
        FROM tickets t
        INNER JOIN empresas e ON t.id_empresa = e.id_empresa
        LEFT JOIN ticket_detalle td ON t.id_ticket = td.id_ticket  
        LEFT JOIN ticket_metodo_pago tmp ON t.id_ticket = tmp.id_ticket
        WHERE e.id_usuario = ?
        {$where_adicional}
        GROUP BY t.id_ticket
        ORDER BY t.fecha_venta DESC, t.id_ticket DESC
        LIMIT {$limite} OFFSET {$offset}
    ";
    
    // No agregar LIMIT y OFFSET a los parámetros ya que están directamente en la query
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Error preparando la consulta SQL: ' . implode(', ', $conn->errorInfo()));
    }
    
    if (!$stmt->execute($params)) {
        throw new Exception('Error ejecutando la consulta: ' . implode(', ', $stmt->errorInfo()));
    }
    
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Consulta para obtener el total de registros (sin límite)
    $sql_count = "
        SELECT 
            COUNT(DISTINCT t.id_ticket) as total,
            COUNT(CASE WHEN t.estatus = 'pendiente' THEN 1 END) as pendientes,
            COUNT(CASE WHEN t.estatus = 'facturado' THEN 1 END) as facturados,
            COALESCE(SUM(t.importe_t), 0) as total_importe
        FROM tickets t
        INNER JOIN empresas e ON t.id_empresa = e.id_empresa
        WHERE e.id_usuario = ?
        {$where_adicional}
    ";
    
    // Los params ya no incluyen LIMIT y OFFSET, se pueden usar directamente
    $stmt_count = $conn->prepare($sql_count);
    
    if (!$stmt_count) {
        throw new Exception('Error preparando consulta de conteo: ' . implode(', ', $conn->errorInfo()));
    }
    
    if (!$stmt_count->execute($params)) {
        throw new Exception('Error ejecutando consulta de conteo: ' . implode(', ', $stmt_count->errorInfo()));
    }
    
    $resumen_data = $stmt_count->fetch(PDO::FETCH_ASSOC);
    
    // Procesar datos adicionales para cada ticket
    foreach ($tickets as &$ticket) {
        // Calcular urgencia para tickets pendientes
        if ($ticket['estatus'] === 'pendiente') {
            $dias = (int)$ticket['dias_transcurridos'];
            if ($dias >= 25) {
                $ticket['urgencia'] = 'alta';
                $ticket['dias_restantes'] = max(0, 30 - $dias);
                $ticket['mensaje_urgencia'] = 'Urgente - ' . $ticket['dias_restantes'] . ' días restantes';
            } elseif ($dias >= 20) {
                $ticket['urgencia'] = 'media';
                $ticket['dias_restantes'] = 30 - $dias;
                $ticket['mensaje_urgencia'] = $ticket['dias_restantes'] . ' días restantes';
            } else {
                $ticket['urgencia'] = 'baja';
                $ticket['dias_restantes'] = 30 - $dias;
                $ticket['mensaje_urgencia'] = $ticket['dias_restantes'] . ' días restantes';
            }
        } else {
            $ticket['urgencia'] = 'ninguna';
            $ticket['dias_restantes'] = null;
            $ticket['mensaje_urgencia'] = 'Facturado';
        }
        
        // Formatear datos
        $ticket['importe_formateado'] = number_format($ticket['importe_t'], 2);
        $ticket['subtotal_formateado'] = number_format($ticket['subtotal'], 2);
        $ticket['impuesto_formateado'] = number_format($ticket['impuesto_t'], 2);
        $ticket['fecha_formateada'] = date('d/m/Y', strtotime($ticket['fecha_venta']));
        
        // Información de la sucursal
        $ticket['sucursal_completa'] = "{$ticket['nombre_sucursal']} ({$ticket['codigo_suc']})";
    }
    
    $respuesta = [
        'success' => true,
        'message' => 'Tickets consultados exitosamente',
        'tickets' => $tickets,
        'total_tickets' => (int)$resumen_data['total'],
        'pagina_actual' => $pagina,
        'limite' => $limite,
        'total_paginas' => ceil($resumen_data['total'] / $limite),
        'resumen' => [
            'pendientes' => (int)$resumen_data['pendientes'],
            'facturados' => (int)$resumen_data['facturados'],
            'total_importe' => (float)$resumen_data['total_importe'],
            'total_importe_formateado' => number_format($resumen_data['total_importe'], 2)
        ]
    ];

} catch (Exception $e) {
    $respuesta['message'] = $e->getMessage();
    error_log("Error en consultar-tickets.php: " . $e->getMessage());
}

echo json_encode($respuesta, JSON_PRETTY_PRINT);
exit;