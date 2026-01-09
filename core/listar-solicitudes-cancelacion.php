<?php
session_start();
require_once 'autoload-vendor.php';

header('Content-Type: application/json');

// Verificar que sea administrador
$tipo_usuario = $_SESSION['tipo_usuario'] ?? null;
$logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

if (!$logged_in || $tipo_usuario !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=facturacion;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Obtener parámetros
    $estado = isset($_GET['estado']) ? $_GET['estado'] : 'pendiente';
    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $por_pagina = 10;
    $offset = ($pagina - 1) * $por_pagina;
    
    // Consulta para obtener solicitudes con información de factura y usuario
    $query = "SELECT 
                s.id_solicitud,
                s.id_factura,
                s.id_usuario,
                s.fecha_solicitud,
                s.estado,
                s.motivo,
                s.fecha_respuesta,
                s.respuesta_admin,
                f.folio_interno,
                f.serie_interno,
                f.fecha_emision,
                f.rfc_receptor,
                f.razon_social_receptor,
                f.correo_receptor,
                f.total,
                f.uuid,
                u.correo as correo_usuario
              FROM solicitudes_cancelacion s
              INNER JOIN facturas f ON s.id_factura = f.id_factura
              INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
              WHERE s.estado = :estado
              ORDER BY s.fecha_solicitud DESC
              LIMIT :limit OFFSET :offset";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':estado', $estado, PDO::PARAM_STR);
    $stmt->bindValue(':limit', $por_pagina, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Contar total de registros
    $count_query = "SELECT COUNT(*) as total 
                    FROM solicitudes_cancelacion s
                    WHERE s.estado = :estado";
    $stmt_count = $pdo->prepare($count_query);
    $stmt_count->execute(['estado' => $estado]);
    $total_registros = $stmt_count->fetch(PDO::FETCH_ASSOC)['total'];
    $total_paginas = ceil($total_registros / $por_pagina);
    
    // Formatear datos
    foreach ($solicitudes as &$solicitud) {
        $solicitud['folio_completo'] = ($solicitud['serie_interno'] ? $solicitud['serie_interno'] . '-' : '') . $solicitud['folio_interno'];
        $solicitud['fecha_solicitud_formatted'] = date('d/m/Y H:i', strtotime($solicitud['fecha_solicitud']));
        $solicitud['fecha_emision_formatted'] = date('d/m/Y', strtotime($solicitud['fecha_emision']));
        $solicitud['total_formatted'] = '$' . number_format($solicitud['total'], 2);
        
        if ($solicitud['fecha_respuesta']) {
            $solicitud['fecha_respuesta_formatted'] = date('d/m/Y H:i', strtotime($solicitud['fecha_respuesta']));
        }
    }
    
    echo json_encode([
        'success' => true,
        'solicitudes' => $solicitudes,
        'paginacion' => [
            'pagina_actual' => $pagina,
            'total_paginas' => $total_paginas,
            'total_registros' => $total_registros,
            'por_pagina' => $por_pagina
        ]
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener solicitudes: ' . $e->getMessage()
    ]);
}
?>
