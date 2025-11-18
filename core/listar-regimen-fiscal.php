<?php
// listar regimenes fiscales desde la base de datos
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json; charset=utf-8');

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    if (!$conn) {
        throw new Exception('Error al conectar con la base de datos');
    }
    
    $stmt = $conn->prepare("SELECT codigo, descr as descripcion FROM cat_regimen_fiscal ORDER BY codigo ASC");
    $stmt->execute();
    $regimenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true, 
        'data' => $regimenes,
        'count' => count($regimenes)
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Error al obtener regímenes fiscales: ' . $e->getMessage(),
        'data' => []
    ]);
}

