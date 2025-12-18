<?php
/**
 * Endpoint: obtener-codigos-postales.php
 *
 * Funciones:
 *  - Autocompletar CP: recibe termino (1-5 dígitos) y retorna coincidencias (LIKE)
 *  - Validar CP exacto: con { validar: true } y 5 dígitos, confirma si existe en catálogo SAT (tabla cat_codigo_postal)
 *
 * Entradas:
 *  - JSON { termino: string, validar?: boolean }
 *  - O GET ?termino=
 * Salida:
 *  - { success, data[], message, valid?: boolean, municipio?, estado? }
 */
header('Content-Type: application/json; charset=utf-8');

try {
    require_once __DIR__ . '/class/db.php';

    $respuesta = [
        'success' => false,
        'data' => [],
        'message' => 'Error desconocido.',
        'valid' => false
    ];

    // Obtener el término de búsqueda/validación
    $input = json_decode(file_get_contents('php://input'), true);
    $termino = isset($input['termino']) ? trim($input['termino']) : '';
    $validar = isset($input['validar']) ? (bool)$input['validar'] : false;

    if (empty($termino) && isset($_GET['termino'])) {
        $termino = trim($_GET['termino']);
    }

    if (empty($termino)) {
        throw new Exception('Término de búsqueda no proporcionado');
    }

    // Validar que sea numérico y tenga máximo 5 dígitos
    if (!preg_match('/^\d{1,5}$/', $termino)) {
        throw new Exception('El código postal debe ser numérico (1-5 dígitos)');
    }

    // Si es exactamente 5 dígitos, hacer búsqueda exacta
    $esExacto = strlen($termino) === 5;

    $db = new Database();
    $conn = $db->getConnection();

    if (!$conn) {
        throw new Exception('No se pudo conectar a la base de datos');
    }

    // Si viene en modo validación (verificar existencia exacta)
    if ($validar && $esExacto) {
        $stmt = $conn->prepare("
            SELECT d_codigo, d_mnpio, d_estado 
            FROM cat_codigo_postal 
            WHERE d_codigo = ? 
            LIMIT 1
        ");
        $stmt->execute([$termino]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $respuesta = [
                'success' => true,
                'data' => [$resultado],
                'message' => 'Código postal válido',
                'valid' => true,
                'codigo' => $termino,
                'municipio' => $resultado['d_mnpio'],
                'estado' => $resultado['d_estado']
            ];
        } else {
            $respuesta = [
                'success' => false,
                'data' => [],
                'message' => "Código postal {$termino} no encontrado en el catálogo del SAT",
                'valid' => false,
                'codigo' => $termino
            ];
        }
    } else {
        // Búsqueda por coincidencia (autocompletado)
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
                'total' => count($codigos),
                'valid' => $esExacto && count($codigos) > 0 ? true : null
            ];
        } else {
            $respuesta = [
                'success' => false,
                'data' => [],
                'message' => 'No se encontraron códigos postales que coincidan',
                'valid' => false
            ];
        }
    }
} catch (Exception $e) {
    $respuesta = [
        'success' => false,
        'data' => [],
        'message' => $e->getMessage(),
        'valid' => false
    ];
}

echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
?>