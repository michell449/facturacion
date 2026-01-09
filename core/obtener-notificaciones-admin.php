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
    
    // Obtener solicitudes de las últimas 24 horas que estén pendientes
    $query = "SELECT 
                s.id_solicitud,
                s.id_factura,
                s.fecha_solicitud,
                f.folio_interno,
                f.serie_interno,
                f.razon_social_receptor,
                f.total,
                u.correo as correo_usuario
              FROM solicitudes_cancelacion s
              INNER JOIN facturas f ON s.id_factura = f.id_factura
              INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
              WHERE s.estado = 'pendiente' 
                AND s.fecha_solicitud >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
              ORDER BY s.fecha_solicitud DESC
              LIMIT 5";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Contar total de solicitudes pendientes
    $stmt_count = $pdo->prepare("SELECT COUNT(*) as total FROM solicitudes_cancelacion WHERE estado = 'pendiente'");
    $stmt_count->execute();
    $total_pendientes = $stmt_count->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Formatear datos
    foreach ($notificaciones as &$notif) {
        $notif['folio_completo'] = ($notif['serie_interno'] ? $notif['serie_interno'] . '-' : '') . $notif['folio_interno'];
        $notif['total_formatted'] = '$' . number_format($notif['total'], 2);
        $notif['fecha_solicitud_formatted'] = date('H:i', strtotime($notif['fecha_solicitud']));
        $notif['fecha_solicitud_fecha'] = date('d/m/Y', strtotime($notif['fecha_solicitud']));
    }
    
    echo json_encode([
        'success' => true,
        'notificaciones' => $notificaciones,
        'total_pendientes' => (int)$total_pendientes
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener notificaciones: ' . $e->getMessage()
    ]);
}
?>
