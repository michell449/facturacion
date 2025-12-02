<?php
header('Content-Type: application/json; charset=utf-8');

try {
    require_once __DIR__ . '/class/db.php';

    $respuesta = [
        'success' => false,
        'data' => [],
        'message' => 'Error desconocido.'
    ];

    $input = json_decode(file_get_contents('php://input'), true);
    $termino = isset($input['termino']) ? trim($input['termino']) : '';

    if (empty($termino) && isset($_GET['termino'])) {
        $termino = trim($_GET['termino']);
    }

    if (empty($termino)) {
        throw new Exception('Término de búsqueda no proporcionado');
    }

    if (!preg_match('/^\d{1,5}$/', $termino)) {
        throw new Exception('El término debe ser numérico (1-5 dígitos)');
    }

    $db = new Database();
    $conn = $db->getConnection();

    if (!$conn) {
        throw new Exception('No se pudo conectar a la base de datos');
    }

    $stmt = $conn->prepare("
        SELECT DISTINCT d_codigo, d_mnpio, d_estado 
        FROM cat_codigo_postal 
        WHERE d_codigo LIKE ? 
        ORDER BY d_codigo ASC 
        LIMIT 20
    ");
    $stmt->execute([$termino . '%']);
    $codigos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($codigos) > 0) {
        $respuesta = [
            'success' => true,
            'data' => $codigos,
            'message' => 'Códigos postales encontrados correctamente',
            'total' => count($codigos)
        ];
    } else {
        $respuesta = [
            'success' => false,
            'data' => [],
            'message' => 'No se encontraron códigos postales que coincidan'
        ];
    }
} catch (Exception $e) {
    $respuesta = [
        'success' => false,
        'data' => [],
        'message' => $e->getMessage()
    ];
}

echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);