<?php
/**
 * API para obtener estadísticas del dashboard de tickets
 * Proporciona datos agrupados por turno, día, mes y rangos personalizados
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';

ob_clean();
header('Content-Type: application/json; charset=utf-8');

$respuesta = [
    'success' => false,
    'message' => 'Error desconocido.',
    'data' => []
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
    
    $db = new Database();
    $conn = $db->getConnection();
    
    // Obtener acción solicitada
    $accion = $_GET['accion'] ?? 'resumen_general';
    
    // Parámetros de filtro
    $id_empresa = !empty($_GET['id_empresa']) ? (int)$_GET['id_empresa'] : null;
    $fecha_desde = $_GET['fecha_desde'] ?? null;
    $fecha_hasta = $_GET['fecha_hasta'] ?? null;
    
    // Filtro base por usuario
    $filtro_empresa = "";
    $params = [$id_usuario];
    
    if ($id_empresa) {
        $filtro_empresa = " AND t.id_empresa = ?";
        $params[] = $id_empresa;
    }
    
    // Filtro de fechas
    $filtro_fechas = "";
    if ($fecha_desde && $fecha_hasta) {
        $filtro_fechas = " AND DATE(t.fecha_venta) BETWEEN ? AND ?";
        $params[] = $fecha_desde;
        $params[] = $fecha_hasta;
    }

    switch ($accion) {
        
        case 'resumen_general':
            // Estadísticas generales
            $sql = "
                SELECT 
                    COUNT(*) as total_tickets,
                    SUM(CASE WHEN t.estatus = 'facturado' THEN 1 ELSE 0 END) as facturados,
                    SUM(CASE WHEN t.estatus = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
                    COALESCE(SUM(t.importe_t), 0) as importe_total,
                    COALESCE(SUM(t.subtotal), 0) as subtotal_total,
                    COALESCE(SUM(t.impuesto_t), 0) as impuestos_total,
                    COALESCE(SUM(CASE WHEN t.estatus = 'facturado' THEN t.importe_t ELSE 0 END), 0) as importe_facturado,
                    COALESCE(SUM(CASE WHEN t.estatus = 'pendiente' THEN t.importe_t ELSE 0 END), 0) as importe_pendiente
                FROM tickets t
                INNER JOIN empresas e ON t.id_empresa = e.id_empresa
                WHERE e.id_usuario = ?
                {$filtro_empresa}
                {$filtro_fechas}
            ";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $resumen = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Calcular porcentajes
            $total = (int)$resumen['total_tickets'];
            $resumen['porcentaje_facturado'] = $total > 0 ? round(($resumen['facturados'] / $total) * 100, 1) : 0;
            $resumen['porcentaje_pendiente'] = $total > 0 ? round(($resumen['pendientes'] / $total) * 100, 1) : 0;
            
            $respuesta['success'] = true;
            $respuesta['data'] = $resumen;
            break;
            
        case 'por_turno':
            // Estadísticas por turno (Matutino: 6-14, Vespertino: 14-22, Nocturno: 22-6)
            // Nota: Necesitamos la hora del ticket, asumimos que se usa fecha_venta como datetime o hay otro campo
            $sql = "
                SELECT 
                    CASE 
                        WHEN HOUR(t.fecha_venta) >= 6 AND HOUR(t.fecha_venta) < 14 THEN 'Matutino (6:00 - 14:00)'
                        WHEN HOUR(t.fecha_venta) >= 14 AND HOUR(t.fecha_venta) < 22 THEN 'Vespertino (14:00 - 22:00)'
                        ELSE 'Nocturno (22:00 - 6:00)'
                    END as turno,
                    COUNT(*) as total_tickets,
                    SUM(CASE WHEN t.estatus = 'facturado' THEN 1 ELSE 0 END) as facturados,
                    SUM(CASE WHEN t.estatus = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
                    COALESCE(SUM(t.importe_t), 0) as importe_total
                FROM tickets t
                INNER JOIN empresas e ON t.id_empresa = e.id_empresa
                WHERE e.id_usuario = ?
                {$filtro_empresa}
                {$filtro_fechas}
                GROUP BY turno
                ORDER BY 
                    CASE turno 
                        WHEN 'Matutino (6:00 - 14:00)' THEN 1 
                        WHEN 'Vespertino (14:00 - 22:00)' THEN 2 
                        ELSE 3 
                    END
            ";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $turnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $respuesta['success'] = true;
            $respuesta['data'] = $turnos;
            break;
            
        case 'por_dia':
            // Estadísticas por día de la semana
            $sql = "
                SELECT 
                    DAYNAME(t.fecha_venta) as dia_nombre,
                    DAYOFWEEK(t.fecha_venta) as dia_num,
                    COUNT(*) as total_tickets,
                    SUM(CASE WHEN t.estatus = 'facturado' THEN 1 ELSE 0 END) as facturados,
                    SUM(CASE WHEN t.estatus = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
                    COALESCE(SUM(t.importe_t), 0) as importe_total
                FROM tickets t
                INNER JOIN empresas e ON t.id_empresa = e.id_empresa
                WHERE e.id_usuario = ?
                {$filtro_empresa}
                {$filtro_fechas}
                GROUP BY dia_nombre, dia_num
                ORDER BY dia_num
            ";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $dias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Traducir días al español
            $traduccion = [
                'Sunday' => 'Domingo',
                'Monday' => 'Lunes',
                'Tuesday' => 'Martes',
                'Wednesday' => 'Miércoles',
                'Thursday' => 'Jueves',
                'Friday' => 'Viernes',
                'Saturday' => 'Sábado'
            ];
            
            foreach ($dias as &$dia) {
                $dia['dia_nombre'] = $traduccion[$dia['dia_nombre']] ?? $dia['dia_nombre'];
            }
            
            $respuesta['success'] = true;
            $respuesta['data'] = $dias;
            break;
            
        case 'por_mes':
            // Estadísticas por mes
            $sql = "
                SELECT 
                    YEAR(t.fecha_venta) as anio,
                    MONTH(t.fecha_venta) as mes_num,
                    DATE_FORMAT(t.fecha_venta, '%Y-%m') as periodo,
                    COUNT(*) as total_tickets,
                    SUM(CASE WHEN t.estatus = 'facturado' THEN 1 ELSE 0 END) as facturados,
                    SUM(CASE WHEN t.estatus = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
                    COALESCE(SUM(t.importe_t), 0) as importe_total,
                    COALESCE(SUM(CASE WHEN t.estatus = 'facturado' THEN t.importe_t ELSE 0 END), 0) as importe_facturado
                FROM tickets t
                INNER JOIN empresas e ON t.id_empresa = e.id_empresa
                WHERE e.id_usuario = ?
                {$filtro_empresa}
                {$filtro_fechas}
                GROUP BY anio, mes_num, periodo
                ORDER BY anio DESC, mes_num DESC
                LIMIT 12
            ";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $meses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Traducir meses al español
            $meses_es = [
                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
            ];
            
            foreach ($meses as &$mes) {
                $mes['mes_nombre'] = $meses_es[(int)$mes['mes_num']] ?? 'Desconocido';
            }
            
            $respuesta['success'] = true;
            $respuesta['data'] = array_reverse($meses);
            break;
            
        case 'por_sucursal':
            // Estadísticas por sucursal
            $sql = "
                SELECT 
                    e.id_empresa,
                    e.nombre as sucursal,
                    e.codigo_suc,
                    COUNT(*) as total_tickets,
                    SUM(CASE WHEN t.estatus = 'facturado' THEN 1 ELSE 0 END) as facturados,
                    SUM(CASE WHEN t.estatus = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
                    COALESCE(SUM(t.importe_t), 0) as importe_total,
                    COALESCE(SUM(CASE WHEN t.estatus = 'facturado' THEN t.importe_t ELSE 0 END), 0) as importe_facturado
                FROM tickets t
                INNER JOIN empresas e ON t.id_empresa = e.id_empresa
                WHERE e.id_usuario = ?
                {$filtro_empresa}
                {$filtro_fechas}
                GROUP BY e.id_empresa, e.nombre, e.codigo_suc
                ORDER BY total_tickets DESC
            ";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $sucursales = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $respuesta['success'] = true;
            $respuesta['data'] = $sucursales;
            break;
            
        case 'tendencia_diaria':
            // Tendencia de los últimos 30 días
            $sql = "
                SELECT 
                    DATE(t.fecha_venta) as fecha,
                    COUNT(*) as total_tickets,
                    SUM(CASE WHEN t.estatus = 'facturado' THEN 1 ELSE 0 END) as facturados,
                    SUM(CASE WHEN t.estatus = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
                    COALESCE(SUM(t.importe_t), 0) as importe_total
                FROM tickets t
                INNER JOIN empresas e ON t.id_empresa = e.id_empresa
                WHERE e.id_usuario = ?
                {$filtro_empresa}
                AND DATE(t.fecha_venta) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                GROUP BY DATE(t.fecha_venta)
                ORDER BY fecha ASC
            ";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $tendencia = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $respuesta['success'] = true;
            $respuesta['data'] = $tendencia;
            break;
            
        case 'metodos_pago':
            // Estadísticas por método de pago
            $sql = "
                SELECT 
                    tmp.forma_pago,
                    cfp.descripcion as forma_pago_desc,
                    COUNT(DISTINCT t.id_ticket) as total_tickets,
                    COALESCE(SUM(tmp.monto), 0) as monto_total
                FROM tickets t
                INNER JOIN empresas e ON t.id_empresa = e.id_empresa
                LEFT JOIN ticket_metodo_pago tmp ON t.id_ticket = tmp.id_ticket
                LEFT JOIN cat_forma_pago cfp ON tmp.forma_pago = cfp.clave
                WHERE e.id_usuario = ?
                {$filtro_empresa}
                {$filtro_fechas}
                AND tmp.forma_pago IS NOT NULL
                GROUP BY tmp.forma_pago, cfp.descripcion
                ORDER BY total_tickets DESC
            ";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $metodos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $respuesta['success'] = true;
            $respuesta['data'] = $metodos;
            break;
            
        case 'top_productos':
            // Top productos más vendidos
            $sql = "
                SELECT 
                    td.descr as producto,
                    td.id_prod_serv,
                    COUNT(*) as veces_vendido,
                    SUM(td.cant) as cantidad_total,
                    COALESCE(SUM(td.importe), 0) as importe_total
                FROM ticket_detalle td
                INNER JOIN tickets t ON td.id_ticket = t.id_ticket
                INNER JOIN empresas e ON t.id_empresa = e.id_empresa
                WHERE e.id_usuario = ?
                {$filtro_empresa}
                {$filtro_fechas}
                GROUP BY td.descr, td.id_prod_serv
                ORDER BY cantidad_total DESC
                LIMIT 10
            ";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $respuesta['success'] = true;
            $respuesta['data'] = $productos;
            break;
            
        case 'comparativa':
            // Comparativa periodo actual vs anterior
            $hoy = date('Y-m-d');
            $inicio_mes_actual = date('Y-m-01');
            $fin_mes_anterior = date('Y-m-d', strtotime('last day of last month'));
            $inicio_mes_anterior = date('Y-m-01', strtotime('last month'));
            
            // Mes actual
            $sql_actual = "
                SELECT 
                    COUNT(*) as total_tickets,
                    SUM(CASE WHEN t.estatus = 'facturado' THEN 1 ELSE 0 END) as facturados,
                    COALESCE(SUM(t.importe_t), 0) as importe_total
                FROM tickets t
                INNER JOIN empresas e ON t.id_empresa = e.id_empresa
                WHERE e.id_usuario = ?
                {$filtro_empresa}
                AND DATE(t.fecha_venta) BETWEEN ? AND ?
            ";
            
            $params_actual = $params;
            $params_actual[] = $inicio_mes_actual;
            $params_actual[] = $hoy;
            
            $stmt = $conn->prepare($sql_actual);
            $stmt->execute($params_actual);
            $actual = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Mes anterior
            $params_anterior = $params;
            $params_anterior[] = $inicio_mes_anterior;
            $params_anterior[] = $fin_mes_anterior;
            
            $stmt = $conn->prepare($sql_actual);
            $stmt->execute($params_anterior);
            $anterior = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Calcular variaciones
            $variacion_tickets = $anterior['total_tickets'] > 0 
                ? round((($actual['total_tickets'] - $anterior['total_tickets']) / $anterior['total_tickets']) * 100, 1) 
                : 0;
            $variacion_importe = $anterior['importe_total'] > 0 
                ? round((($actual['importe_total'] - $anterior['importe_total']) / $anterior['importe_total']) * 100, 1) 
                : 0;
            
            $respuesta['success'] = true;
            $respuesta['data'] = [
                'actual' => $actual,
                'anterior' => $anterior,
                'variacion_tickets' => $variacion_tickets,
                'variacion_importe' => $variacion_importe,
                'periodo_actual' => date('F Y'),
                'periodo_anterior' => date('F Y', strtotime('last month'))
            ];
            break;

        case 'hoy':
            // Estadísticas de hoy
            $hoy = date('Y-m-d');
            $params_hoy = $params;
            $params_hoy[] = $hoy;
            
            $sql = "
                SELECT 
                    COUNT(*) as total_tickets,
                    SUM(CASE WHEN t.estatus = 'facturado' THEN 1 ELSE 0 END) as facturados,
                    SUM(CASE WHEN t.estatus = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
                    COALESCE(SUM(t.importe_t), 0) as importe_total,
                    COALESCE(AVG(t.importe_t), 0) as ticket_promedio
                FROM tickets t
                INNER JOIN empresas e ON t.id_empresa = e.id_empresa
                WHERE e.id_usuario = ?
                {$filtro_empresa}
                AND DATE(t.fecha_venta) = ?
            ";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($params_hoy);
            $hoy_stats = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $respuesta['success'] = true;
            $respuesta['data'] = $hoy_stats;
            break;
            
        default:
            throw new Exception('Acción no válida.');
    }
    
} catch (Exception $e) {
    $respuesta['success'] = false;
    $respuesta['message'] = $e->getMessage();
}

echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
