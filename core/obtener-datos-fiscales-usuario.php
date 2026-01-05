<?php
/**
 * obtener-datos-fiscales-usuario.php
 * 
 * Obtiene los datos fiscales registrados del usuario actual desde la tabla datos_fiscales_usuario
 * Responde con JSON
 */

// Limpiar buffers
while (ob_get_level() > 0) {
    ob_end_clean();
}

ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

mb_internal_encoding('UTF-8');
ini_set('default_charset', 'UTF-8');

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');

session_start();

require_once __DIR__ . '/class/db.php';

$respuesta = [
    'success' => false,
    'data' => null,
    'message' => 'Error desconocido'
];

try {
    // Validar sesión
    $id_usuario = $_SESSION['usuario_id'] ?? $_SESSION['USR_ID'] ?? null;
    
    if (!$id_usuario) {
        throw new Exception('Sesión no válida o expirada');
    }

    $db = new Database();
    $conn = $db->getConnection();

    // Obtener datos fiscales del usuario
    $sql = "SELECT * FROM datos_fiscales_usuario WHERE id_usuario = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_usuario]);
    $datosFiscales = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$datosFiscales) {
        throw new Exception('No hay datos fiscales registrados para este usuario');
    }

    $respuesta['success'] = true;
    $respuesta['message'] = 'Datos fiscales obtenidos correctamente';
    $respuesta['data'] = [
        'rfc' => $datosFiscales['rfc'],
        'razon_social' => $datosFiscales['razon_social'],
        'regimen_fiscal' => $datosFiscales['reg_fiscal'],
        'cp' => $datosFiscales['cp'],
        'tipo_persona' => $datosFiscales['tipo_pers'],
        'calle' => $datosFiscales['calle'],
        'num_ext' => $datosFiscales['num_ext'],
        'num_int' => $datosFiscales['num_int'],
        'colonia' => $datosFiscales['col']
    ];

} catch (Exception $e) {
    error_log("ERROR obtener-datos-fiscales-usuario: " . $e->getMessage());
    http_response_code(400);
    $respuesta['success'] = false;
    $respuesta['message'] = $e->getMessage();
}

$outputBuffer = ob_get_clean();
if (!empty($outputBuffer)) {
    error_log("OUTPUT INESPERADO: " . substr($outputBuffer, 0, 200));
}

echo json_encode($respuesta);
exit;
?>
