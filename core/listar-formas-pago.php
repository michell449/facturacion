<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json; charset=utf-8');

try {

    $db = new Database();
    $conn = $db->getConnection();

    if (!$conn) {
        throw new Exception('Error al conectar con la base de datos');
    }

    $stmt = $conn-> prepare("SELECT clave, concepto AS description FROM cat_forma_pago ORDER BY clave ASC"); 
    $stmt->execute();
    $formasPago = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode([
        'success' => true, 
        'data' => $formasPago,
        'count' => count($formasPago)
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

