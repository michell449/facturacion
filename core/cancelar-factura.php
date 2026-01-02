<?php
// core/cancelar-factura.php
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/autoload-vendor.php';
require_once __DIR__ . '/../api/FinkokApi.php';

try {
    // Validar sesión
    $id_usuario = $_SESSION['usuario_id'] ?? $_SESSION['USR_ID'] ?? null;
    if (!$id_usuario) {
        throw new Exception('Sesión no válida o expirada.');
    }

    // Obtener datos del POST
    $input = file_get_contents('php://input');
    $datos = json_decode($input, true);
    
    if (!isset($datos['id_factura'])) {
        throw new Exception('ID de factura no proporcionado.');
    }
    
    $id_factura = (int)$datos['id_factura'];
    $motivo = $datos['motivo'] ?? '02'; // 02 = Comprobante emitido con errores con relación
    $uuidSustitucion = $datos['uuid_sustitucion'] ?? null; // Para motivo 01
    
    $db = new Database();
    $conn = $db->getConnection();
    
    // Obtener información completa de la factura y el emisor
    $stmt = $conn->prepare("
        SELECT f.*, e.rfc as rfc_emisor, e.nombre as nombre_emisor
        FROM facturas f
        INNER JOIN empresas e ON f.id_empresa = e.id_empresa
        WHERE f.id_factura = ? AND f.id_usuario = ?
    ");
    $stmt->execute([$id_factura, $id_usuario]);
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$factura) {
        throw new Exception('Factura no encontrada o no tienes permiso para cancelarla.');
    }
    
    // Validaciones previas
    if ($factura['estatus'] === 'cancelada') {
        throw new Exception('La factura ya está cancelada.');
    }
    
    if ($factura['estatus'] !== 'timbrada') {
        throw new Exception('Solo se pueden cancelar facturas timbradas.');
    }
    
    if (empty($factura['uuid'])) {
        throw new Exception('La factura no tiene UUID, no se puede cancelar ante el SAT.');
    }

    // Validar que el motivo sea válido
    if (!in_array($motivo, ['01', '02', '03', '04'])) {
        throw new Exception('Motivo de cancelación inválido. Debe ser 01, 02, 03 o 04.');
    }

    // Si el motivo es 01, validar UUID de sustitución
    if ($motivo === '01' && empty($uuidSustitucion)) {
        throw new Exception('El motivo 01 (Comprobante emitido con errores sin relación) requiere un UUID de sustitución.');
    }
    
    // Configuración Finkok (idealmente debería estar en BD o archivo de configuración)
    $finkokUser = 'michellflores822@gmail.com';
    $finkokPass = 'Pankycontra2025.';
    $enProduccion = false; // false = Demo, true = Producción
    
    // Crear instancia de la API de Finkok
    $finkok = new FinkokApi($finkokUser, $finkokPass, $enProduccion);
    
    // Intentar cancelar en Finkok
    $resultado = $finkok->cancelarFactura(
        $factura['rfc_emisor'],
        $factura['uuid'],
        $motivo,
        $uuidSustitucion
    );
    
    if ($resultado['success']) {
        // Actualizar estatus en la base de datos
        $stmtUpdate = $conn->prepare("
            UPDATE facturas 
            SET estatus = 'cancelada',
                fecha_cancelacion = NOW(),
                motivo_cancelacion = ?,
                acuse_cancelacion = ?
            WHERE id_factura = ?
        ");
        
        $acuseCancelacion = $resultado['acuse'] ?? null;
        $stmtUpdate->execute([$motivo, $acuseCancelacion, $id_factura]);
        
        // Respuesta exitosa
        echo json_encode([
            'success' => true,
            'message' => $resultado['message'],
            'uuid' => $factura['uuid'],
            'status_code' => $resultado['status_code'],
            'acuse' => $resultado['acuse'] ?? null,
            'detalle' => 'La factura ha sido cancelada exitosamente ante el SAT.'
        ]);
        
    } else {
        // Error en la cancelación
        $mensajeError = $resultado['message'];
        
        // Agregar información adicional si existe
        if (!empty($resultado['fault_string'])) {
            $mensajeError .= ' - ' . $resultado['fault_string'];
        }
        
        // Log para debugging
        error_log("Error al cancelar factura ID {$id_factura}: " . json_encode($resultado));
        
        // Responder con el error incluyendo información de debugging
        echo json_encode([
            'success' => false,
            'message' => $mensajeError,
            'status_code' => $resultado['status_code'] ?? null,
            'fault_code' => $resultado['fault_code'] ?? null,
            'detalle' => 'No se pudo completar la cancelación. Verifica el mensaje de error.',
            'debug_response' => $resultado['raw_response'] ?? null,
            'debug_structure' => $resultado['response_structure'] ?? null
        ]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error_type' => 'exception'
    ]);
}
?>
