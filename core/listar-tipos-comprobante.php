<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json; charset=utf-8');
//listar el catalogo de comprobantes desde la base de datos

try {
    $db = new Database();
    $conn = $db->getConnection();

    if (!$conn) {
        throw new Exception('Error al conectar con la base de datos');
    }

    $stmt = $conn-> prepare("SELECT clave, comprobante AS description FROM cat_comprobantes ORDER BY clave ASC"); 
    $stmt->execute();
    $tiposComprobante = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode([
        'success' => true, 
        'data' => $tiposComprobante,
        'count' => count($tiposComprobante)
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}