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
    $data = json_decode(file_get_contents('php://input'), true);
    $id_solicitud = $data['id_solicitud'] ?? null;
    $estado = $data['estado'] ?? null;
    $respuesta_admin = $data['respuesta_admin'] ?? '';
    
    if (!$id_solicitud || !$estado) {
        echo json_encode(['success' => false, 'message' => 'Parámetros incompletos']);
        exit;
    }
    
    if (!in_array($estado, ['aprobada', 'rechazada'])) {
        echo json_encode(['success' => false, 'message' => 'Estado no válido']);
        exit;
    }
    
    $pdo = new PDO("mysql:host=localhost;dbname=facturacion;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Actualizar solicitud
    $stmt = $pdo->prepare("UPDATE solicitudes_cancelacion 
                           SET estado = ?, respuesta_admin = ?, fecha_respuesta = NOW() 
                           WHERE id_solicitud = ?");
    $stmt->execute([$estado, $respuesta_admin, $id_solicitud]);
    
    // Si se aprobó, actualizar el estado de la factura
    if ($estado === 'aprobada') {
        // Obtener el id_factura
        $stmt = $pdo->prepare("SELECT id_factura FROM solicitudes_cancelacion WHERE id_solicitud = ?");
        $stmt->execute([$id_solicitud]);
        $solicitud = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($solicitud) {
            // Actualizar estado de la factura a cancelada
            $stmt = $pdo->prepare("UPDATE facturas SET estatus = 'cancelada' WHERE id_factura = ?");
            $stmt->execute([$solicitud['id_factura']]);
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Solicitud actualizada correctamente'
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al procesar solicitud: ' . $e->getMessage()
    ]);
}
?>
