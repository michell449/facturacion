<?php

/**
 * Cancelación de facturas ante el SAT vía Finkok
 * core/cancelar-factura.php
 */

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

require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/autoload-vendor.php';
require_once __DIR__ . '/../api/FinkokApi.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/sello-utils.php';

use CfdiUtils\CfdiCreator40;
use CfdiUtils\Certificado\Certificado;

$respuesta = ['success' => false, 'message' => 'Error desconocido'];

try {

    // Validar sesión
    $id_usuario = $_SESSION['usuario_id'] ?? $_SESSION['USR_ID'] ?? null;
    if (!$id_usuario) {
        throw new Exception('Sesión no válida o expirada.');
    }

    error_log("Usuario ID: {$id_usuario}");

    $input = file_get_contents('php://input');
    error_log("Input recibido: " . substr($input, 0, 200));

    $datos = json_decode($input, true);

    if (!isset($datos['id_factura'])) {
        throw new Exception('ID de factura no proporcionado.');
    }

    $id_factura = (int)$datos['id_factura'];
    $motivo = $datos['motivo'] ?? '02';
    $uuidSustitucion = $datos['uuid_sustitucion'] ?? null;

    error_log("Cancelando factura ID: {$id_factura}, Motivo: {$motivo}");

    $db = new Database();
    $conn = $db->getConnection();

    // Obtener datos de la factura Y los certificados del emisor
    $stmt = $conn->prepare("
        SELECT f.*, 
               e.rfc as rfc_emisor, 
               e.nombre as nombre_emisor,
               e.file_cer,
               e.file_key,
               e.clave as pass_key
        FROM facturas f
        INNER JOIN empresas e ON f.id_empresa = e.id_empresa
        WHERE f.id_factura = ? AND f.id_usuario = ?
    ");
    $stmt->execute([$id_factura, $id_usuario]);
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$factura) {
        throw new Exception('Factura no encontrada o no tienes permiso para cancelarla.');
    }

    // Validaciones
    if ($factura['estatus'] === 'cancelada') {
        throw new Exception('La factura ya está cancelada.');
    }

    if ($factura['estatus'] !== 'timbrada') {
        throw new Exception('Solo se pueden cancelar facturas timbradas.');
    }

    if (empty($factura['uuid'])) {
        throw new Exception('La factura no tiene UUID, no se puede cancelar ante el SAT.');
    }

    // Validar que el emisor tenga certificados configurados
    if (empty($factura['file_cer']) || empty($factura['file_key'])) {
        throw new Exception('El emisor no tiene certificados digitales (CSD) configurados.');
    }

    error_log("Factura encontrada - UUID: {$factura['uuid']}, RFC Emisor: {$factura['rfc_emisor']}");

    // Validar que el motivo sea válido
    if (!in_array($motivo, ['01', '02', '03', '04'])) {
        throw new Exception('Motivo de cancelación inválido. Debe ser 01, 02, 03 o 04.');
    }

    if ($motivo === '01' && empty($uuidSustitucion)) {
        throw new Exception('El motivo 01 (Comprobante emitido con errores con relación) requiere un UUID de sustitución.');
    }

    $finkokUser = 'michellflores822@gmail.com';
    $finkokPass = 'Pankycontra2025.';
    $enProduccion = false;

    error_log("Configuración Finkok: Usuario={$finkokUser}, Producción=" . ($enProduccion ? 'SI' : 'NO'));

    // Crear instancia de la API
    $finkok = new FinkokApi($finkokUser, $finkokPass, $enProduccion);

    // Preparar rutas de certificados
    $rutaCertificados = __DIR__ . '/../uploads/sellos/';
    $archivoCer = $rutaCertificados . $factura['file_cer'];
    $archivoKey = $rutaCertificados . $factura['file_key'];
    
    if (!file_exists($archivoCer) || !file_exists($archivoKey)) {
        throw new Exception("Archivos CSD no encontrados.");
    }

    // Descifrar contraseña
    $passwordKey = SelloUtils::descifrarClave($factura['pass_key'], (int)$factura['id_empresa']);
    if (!$passwordKey) {
        throw new Exception("Error al descifrar contraseña del CSD.");
    }

    error_log("Archivos CSD validados: CER={$archivoCer}, KEY={$archivoKey}");
    error_log("Enviando solicitud de cancelación a Finkok...");

    // Cancelar con certificados (FinkokApi procesará los certificados internamente)
    $resultado = $finkok->cancelarFactura(
        $factura['rfc_emisor'],
        $factura['uuid'],
        $motivo,
        $uuidSustitucion,
        $archivoCer,
        $archivoKey,
        $passwordKey
    );

    error_log("Resultado Finkok: " . json_encode($resultado));

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

        error_log("Factura actualizada en BD como cancelada");

        $respuesta = [
            'success' => true,
            'message' => $resultado['message'],
            'uuid' => $factura['uuid'],
            'status_code' => $resultado['status_code'],
            'acuse' => $resultado['acuse'] ?? null,
            'detalle' => 'La factura ha sido cancelada exitosamente ante el SAT.'
        ];
    } else {
        $mensajeError = $resultado['message'];

        if (!empty($resultado['fault_string'])) {
            $mensajeError .= ' - ' . $resultado['fault_string'];
        }

        // Log del error
        error_log("ERROR al cancelar factura ID {$id_factura}: " . json_encode($resultado));

        $respuesta = [
            'success' => false,
            'message' => $mensajeError,
            'status_code' => $resultado['status_code'] ?? null,
            'fault_code' => $resultado['fault_code'] ?? null,
            'detalle' => 'No se pudo completar la cancelación. Verifica el mensaje de error.',
            'debug_response' => $resultado['raw_response'] ?? null,
            'debug_request' => $resultado['debug_request'] ?? null
        ];
    }
} catch (Throwable $e) {
    error_log("EXCEPCIÓN EN CANCELACIÓN: " . $e->getMessage());
    error_log("Trace: " . $e->getTraceAsString());

    http_response_code(500);
    $respuesta = [
        'success' => false,
        'message' => $e->getMessage(),
        'error_type' => 'exception',
        'debug' => [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ];
}

$outputBuffer = ob_get_clean();
if (!empty($outputBuffer)) {
    error_log("OUTPUT INESPERADO CAPTURADO EN CANCELACIÓN: " . substr($outputBuffer, 0, 200));
}
echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
exit;
