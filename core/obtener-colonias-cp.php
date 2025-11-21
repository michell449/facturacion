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
    $codigoPostal = isset($input['codigo_postal']) ? trim($input['codigo_postal']) : '';

    if (empty($codigoPostal) && isset($_GET['cp'])) {
        $codigoPostal = trim($_GET['cp']);
    }

    if (empty($codigoPostal)) {
        throw new Exception('Código postal no proporcionado');
    }

    if (!preg_match('/^\d{5}$/', $codigoPostal)) {
        throw new Exception('Formato de código postal inválido. Debe ser de 5 dígitos.');
    }

    $db = new Database();
    $conn = $db->getConnection();

    if (!$conn) {
        throw new Exception('No se pudo conectar a la base de datos');
    }

    $stmt = $conn->prepare("SELECT d_ciudad, d_estado, d_mnpio FROM cat_codigo_postal WHERE d_codigo = ? LIMIT 1");
    $stmt->execute([$codigoPostal]);
    $ubicacion = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ubicacion) {
        throw new Exception('Código postal no encontrado');
    }

    // Obtener todas las colonias 
    $stmtColonias = $conn->prepare("SELECT d_asenta, tipo_asenta FROM cat_codigo_postal WHERE d_codigo = ? ORDER BY d_asenta ASC");
    $stmtColonias->execute([$codigoPostal]);
    $colonias = $stmtColonias->fetchAll(PDO::FETCH_ASSOC);

    $respuesta = [
        'success' => true,
        'data' => [
            'codigo_postal' => $codigoPostal,
            'ciudad' => $ubicacion['d_ciudad'],
            'estado' => $ubicacion['d_estado'],
            'municipio' => $ubicacion['d_mnpio'],
            'colonias' => $colonias,
            'total_colonias' => count($colonias)
        ],
        'message' => 'Información del código postal obtenida correctamente'
    ];
} catch (Exception $e) {
    $respuesta = [
        'success' => false,
        'data' => [],
        'message' => $e->getMessage()
    ];
}

echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
