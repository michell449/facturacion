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

    // Filtra por tipo de persona: fisica | moral | ambos (opcional)
    $tipoPersona = isset($_GET['tipo_persona']) ? strtolower(trim($_GET['tipo_persona'])) : 'ambos';
    $condiciones = [
        'vigencia_desde <= CURRENT_DATE',
        '(vigencia_hasta IS NULL OR vigencia_hasta >= CURRENT_DATE)'
    ];

    if ($tipoPersona === 'fisica') {
        $condiciones[] = 'aplica_fisica = 1';
    } elseif ($tipoPersona === 'moral') {
        $condiciones[] = 'aplica_moral = 1';
    } elseif ($tipoPersona !== 'ambos') {
        throw new Exception('Parámetro tipo_persona inválido. Use fisica, moral o ambos.');
    }

    $where = implode(' AND ', $condiciones);

    $sql = "SELECT codigo, descr AS descripcion FROM cat_regimen_fiscal WHERE $where ORDER BY codigo ASC";
    $stmt = $conn->prepare($sql);
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

