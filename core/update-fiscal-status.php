<?php
// Función para limpiar caché de estado fiscal
function limpiarCacheFiscal() {
    if (isset($_SESSION['FISCAL_COMPLETE'])) {
        unset($_SESSION['FISCAL_COMPLETE']);
    }
}

// Función para forzar actualización del estado fiscal
function actualizarEstadoFiscal($userId = null) {
    if (!$userId && isset($_SESSION['usuario_id'])) {
        $userId = $_SESSION['usuario_id'];
    }
    
    if ($userId) {
        require_once __DIR__ . '/core/class/db.php';
        $db = new Database();
        $conn = $db->getConnection();
        
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM datos_fiscales_usuario WHERE id_usuario = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $isFiscalComplete = ($result['count'] > 0);
        $_SESSION['FISCAL_COMPLETE'] = $isFiscalComplete ? 1 : 0;
        
        return $isFiscalComplete;
    }
    
    return false;
}

// Si se llama directamente, actualizar el estado
if (basename($_SERVER['PHP_SELF']) === 'update-fiscal-status.php') {
    session_start();
    header('Content-Type: application/json; charset=utf-8');
    
    try {
        $updated = actualizarEstadoFiscal();
        echo json_encode([
            'success' => true,
            'fiscal_complete' => $updated,
            'message' => 'Estado fiscal actualizado'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}
?>