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
    
    // Contar solicitudes pendientes
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM solicitudes_cancelacion WHERE estado = 'pendiente'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'total' => (int)$result['total']
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al contar solicitudes: ' . $e->getMessage()
    ]);
}
?>
