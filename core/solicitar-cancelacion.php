<?php
session_start();
require_once 'autoload-vendor.php';

header('Content-Type: application/json');

// Verificar sesión
$id_usuario = $_SESSION['id_usuario'] ?? $_SESSION['usuario_id'] ?? $_SESSION['USR_ID'] ?? null;
$tipo_usuario = $_SESSION['tipo_usuario'] ?? null;
$logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

// Solo clientes autenticados pueden solicitar cancelación
if (!$logged_in || !$id_usuario || $tipo_usuario !== 'cliente') {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

try {
    // Obtener datos del POST
    $data = json_decode(file_get_contents('php://input'), true);
    $id_factura = $data['id_factura'] ?? null;
    $motivo = $data['motivo'] ?? 'Solicitud de cancelación desde el portal del cliente';
    
    if (!$id_factura) {
        echo json_encode(['success' => false, 'message' => 'ID de factura no proporcionado']);
        exit;
    }
    
    // Validar motivo
    if (empty($motivo) || strlen($motivo) < 10) {
        echo json_encode(['success' => false, 'message' => 'El motivo debe tener al menos 10 caracteres']);
        exit;
    }
    
    $pdo = new PDO("mysql:host=localhost;dbname=facturacion;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Verificar que la factura pertenece al usuario
    $stmt = $pdo->prepare("SELECT id_factura, folio_interno, serie_interno, estatus FROM facturas WHERE id_factura = ? AND id_usuario = ?");
    $stmt->execute([$id_factura, $id_usuario]);
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$factura) {
        echo json_encode(['success' => false, 'message' => 'Factura no encontrada o no tienes permisos']);
        exit;
    }
    
    // Verificar que la factura no esté ya cancelada
    if ($factura['estatus'] === 'cancelada') {
        echo json_encode(['success' => false, 'message' => 'Esta factura ya está cancelada']);
        exit;
    }
    
    // Crear tabla de solicitudes si no existe
    $pdo->exec("CREATE TABLE IF NOT EXISTS solicitudes_cancelacion (
        id_solicitud INT AUTO_INCREMENT PRIMARY KEY,
        id_factura INT NOT NULL,
        id_usuario INT NOT NULL,
        fecha_solicitud DATETIME DEFAULT CURRENT_TIMESTAMP,
        estado ENUM('pendiente', 'aprobada', 'rechazada') DEFAULT 'pendiente',
        motivo TEXT,
        fecha_respuesta DATETIME,
        respuesta_admin TEXT,
        FOREIGN KEY (id_factura) REFERENCES facturas(id_factura),
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
        INDEX (id_usuario),
        INDEX (id_factura)
    )");
    
    // Verificar si ya existe una solicitud pendiente
    $stmt = $pdo->prepare("SELECT id_solicitud FROM solicitudes_cancelacion WHERE id_factura = ? AND estado = 'pendiente'");
    $stmt->execute([$id_factura]);
    $solicitud_existente = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($solicitud_existente) {
        echo json_encode(['success' => false, 'message' => 'Ya existe una solicitud de cancelación pendiente para esta factura']);
        exit;
    }
    
    // Guardar solicitud de cancelación
    $stmt = $pdo->prepare("INSERT INTO solicitudes_cancelacion (id_factura, id_usuario, motivo) VALUES (?, ?, ?)");
    $stmt->execute([$id_factura, $id_usuario, $motivo]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Solicitud de cancelación enviada correctamente'
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
    ]);
}
?>
