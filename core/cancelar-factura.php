<?php
/**
 * Cancelación de facturas ante el SAT vía Finkok
 * core/cancelar-factura.php
 */

// Limpieza agresiva del buffer para evitar que warnings de PHP rompan el JSON
while (ob_get_level() > 0) {
    ob_end_clean();
}
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 1); // No mostrar errores en pantalla, solo log
ini_set('log_errors', 1);

header('Content-Type: application/json; charset=utf-8');

// Dependencias
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/autoload-vendor.php';
require_once __DIR__ . '/../api/FinkokApi.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/sello-utils.php';

$respuesta = ['success' => false, 'message' => 'Error desconocido al iniciar'];

try {
    session_start();
    
    // 1. Validar sesión
    $id_usuario = $_SESSION['usuario_id'] ?? $_SESSION['USR_ID'] ?? null;
    if (!$id_usuario) {
        throw new Exception('Sesión no válida o expirada.');
    }

    // 2. Obtener datos de entrada
    $input = file_get_contents('php://input');
    $datos = json_decode($input, true);

    if (!isset($datos['id_factura'])) {
        throw new Exception('ID de factura no proporcionado.');
    }

    $id_factura = (int)$datos['id_factura'];
    $motivo = $datos['motivo'] ?? '02';
    $uuidSustitucion = $datos['uuid_sustitucion'] ?? null;

    if ($motivo === '01' && empty($uuidSustitucion)) {
        throw new Exception('Para el motivo "01" es obligatorio indicar el UUID de sustitución.');
    }

    // 3. Conexión a BD y obtención de datos
    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("
        SELECT f.*, 
               e.rfc as rfc_emisor, 
               e.file_cer,
               e.file_key,
               e.clave as pass_key,
               e.id_empresa
        FROM facturas f
        INNER JOIN empresas e ON f.id_empresa = e.id_empresa
        WHERE f.id_factura = ? 
    ");
    // Nota: Quitamos "AND f.id_usuario = ?" temporalmente si es admin, o ajustalo según tu lógica de permisos
    $stmt->execute([$id_factura]);
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$factura) {
        throw new Exception('Factura no encontrada.');
    }

    // 4. Validaciones de negocio
    if ($factura['estatus'] === 'cancelada') {
        throw new Exception('La factura ya se encuentra marcada como cancelada en el sistema.');
    }
    if (empty($factura['uuid'])) {
        throw new Exception('La factura no tiene UUID asignado.');
    }
    if (empty($factura['file_cer']) || empty($factura['file_key'])) {
        throw new Exception('La empresa emisora no tiene cargados los archivos CSD (cer/key).');
    }

    // 5. Preparar Credenciales
    // Rutas absolutas a los archivos
    $basePath = realpath(__DIR__ . '/../uploads/sellos/');
    $archivoCer = $basePath . '/' . $factura['file_cer'];
    $archivoKey = $basePath . '/' . $factura['file_key'];

    if (!file_exists($archivoCer)) throw new Exception("No se encuentra el archivo .cer en: $archivoCer");
    if (!file_exists($archivoKey)) throw new Exception("No se encuentra el archivo .key en: $archivoKey");

    // Descifrar contraseña de la llave privada
    error_log("[CANCELACION] Descifrando contraseña del CSD para empresa ID: {$factura['id_empresa']}");
    $passwordKey = SelloUtils::descifrarClave($factura['pass_key'], (int)$factura['id_empresa']);
    
    if (!$passwordKey) {
        error_log("[ERROR] No se pudo descifrar la contraseña del CSD");
        throw new Exception("No se pudo descifrar la contraseña del CSD.");
    }
    
    error_log("[CANCELACION] Contraseña descifrada correctamente (longitud: " . strlen($passwordKey) . ")");
    error_log("[CANCELACION] RFC Emisor: {$factura['rfc_emisor']}");
    error_log("[CANCELACION] UUID a cancelar: {$factura['uuid']}");
    error_log("[CANCELACION] Motivo: {$motivo}");
    error_log("[CANCELACION] Archivo CER: {$archivoCer}");
    error_log("[CANCELACION] Archivo KEY: {$archivoKey}");

    // 6. Instanciar API
    // AJUSTAR AQUÍ TUS CREDENCIALES REALES O DE PRUEBA
    $finkokUser = defined('FINKOK_USER') ? FINKOK_USER : 'michellflores822@gmail.com'; 
    $finkokPass = defined('FINKOK_PASSWORD') ? FINKOK_PASSWORD : 'PankyContra1997.'; 
    $enProduccion = defined('FINKOK_PRODUCCION') ? FINKOK_PRODUCCION : false;

    $finkok = new FinkokApi($finkokUser, $finkokPass, $enProduccion);

    // 7. Llamar a cancelar
    error_log("Iniciando cancelación en Finkok para UUID: " . $factura['uuid']);
    
    $resultado = $finkok->cancelarFactura(
        $factura['rfc_emisor'],
        $factura['uuid'],
        $motivo,
        $uuidSustitucion,
        $archivoCer,
        $archivoKey,
        $passwordKey // Pasamos la contraseña desencriptada
    );

    error_log("Respuesta Finkok Cancelación: " . json_encode($resultado));

    if ($resultado['success']) {
        // 8. Actualizar base de datos
        $stmtUpdate = $conn->prepare("
            UPDATE facturas 
            SET estatus = 'cancelada'
            WHERE id_factura = ?
        ");
        
        $stmtUpdate->execute([$id_factura]);

        $respuesta = [
            'success' => true,
            'message' => 'Factura cancelada exitosamente.',
            'status_sat' => $resultado['status_code'],
            'uuid' => $factura['uuid']
        ];
    } else {
        // Error en la API
        error_log("[ERROR] Finkok devolvió error: " . ($resultado['message'] ?? 'Sin mensaje'));
        error_log("[ERROR] Código de estatus: " . ($resultado['status_code'] ?? 'N/A'));
        $respuesta = [
            'success' => false,
            'message' => $resultado['message'],
            'status_code' => $resultado['status_code'] ?? null,
            'debug' => $resultado['raw_response'] ?? null
        ];
    }

} catch (Throwable $e) {
    error_log("Error critico cancelacion: " . $e->getMessage());
    $respuesta = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

$output = ob_get_clean();
if(!empty($output)) error_log("Salida inesperada en cancelar-factura: $output");

echo json_encode($respuesta);
exit;
?>