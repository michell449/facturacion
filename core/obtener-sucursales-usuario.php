<?php
// core/obtener-sucursales-usuario.php
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once __DIR__ . '/class/db.php';

try {
    // Validar sesión
    $id_usuario = $_SESSION['usuario_id'] ?? $_SESSION['USR_ID'] ?? null;
    if (!$id_usuario) {
        throw new Exception('Sesión no válida o expirada.');
    }

    $db = new Database();
    $conn = $db->getConnection();

    // Obtener sucursales del usuario
    $stmt = $conn->prepare("SELECT id_empresa, nombre FROM empresas WHERE id_usuario = ? ORDER BY nombre");
    $stmt->execute([$id_usuario]);
    $sucursales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'sucursales' => $sucursales
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
