<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');
$respuesta = [
    'success' => false,
    'message' => 'Error desconocido.'
];


try {
    if (!isset($_SESSION['usuario_id']) || empty($_SESSION['usuario_id'])) {
        throw new Exception('Sesión de usuario no válida. ID de usuario no encontrado en la sesión.');
    }
    $id_usuario = (int)$_SESSION['usuario_id'];

    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("SELECT rfc, razon_social, reg_fiscal, cp, tipo_pers, calle, num_ext, num_int, col FROM datos_fiscales_usuario WHERE id_usuario = ? LIMIT 1");
    $stmt->execute([$id_usuario]);
    $datos_fiscales = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($datos_fiscales) {
        // obtener municipio y estado del catálogo con codigo postal
        if (!empty($datos_fiscales['cp'])) {
            $stmt_ubicacion = $conn->prepare("SELECT d_mnpio, d_estado FROM cat_codigo_postal WHERE d_codigo = ? LIMIT 1");
            $stmt_ubicacion->execute([$datos_fiscales['cp']]);
            $ubicacion = $stmt_ubicacion->fetch(PDO::FETCH_ASSOC);
            
            if ($ubicacion) {
                $datos_fiscales['municipio'] = $ubicacion['d_mnpio'];
                $datos_fiscales['estado'] = $ubicacion['d_estado'];
            }
        }
        
        $respuesta['success'] = true;
        $respuesta['data'] = $datos_fiscales;
        $respuesta['message'] = 'Información fiscal obtenida correctamente.';
    } else {
        $respuesta['message'] = 'No se encontró información fiscal para el usuario.';
    }

} catch (PDOException $e) {
    error_log('Error de base de datos al obtener información fiscal: ' . $e->getMessage());
    $respuesta['message'] = 'Error de conexión a la base de datos.';
} catch (Exception $e) {
    error_log('Error al obtener información fiscal: ' . $e->getMessage());
    $respuesta['message'] = 'Error interno del servidor.';
}

echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);