<?php
//listar el catalo de uso de CFDI desde la base de datos
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json; charset=utf-8');

try{
    $db = new Database();
    $conn = $db->getConnection();

    if (!$conn) {
        throw new Exception('Error al conectar con la base de datos');
    }

    $stmt = $conn ->prepare("SELECT codigo, descr as descripcion FROM cat_uso_cfdi ORDER BY codigo ASC");
    $stmt->execute();
    $usos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true, 
        'data' => $usos,
        'count' => count($usos)
    ]);



} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Error al obtener usos de CFDI: ' . $e->getMessage(),
        'data' => []
    ]);
}