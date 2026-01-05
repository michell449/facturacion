<?php
// core/consultar-estatus-cancelacion.php
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
    
    if (!isset($datos['id_factura']) && !isset($datos['uuid'])) {
        throw new Exception('Se requiere el ID de la factura o el UUID.');
    }
    
    $db = new Database();
    $conn = $db->getConnection();
    
    // Obtener información de la factura
    if (isset($datos['id_factura'])) {
        $id_factura = (int)$datos['id_factura'];
        
        $stmt = $conn->prepare("
            SELECT f.*, e.rfc as rfc_emisor, e.nombre as nombre_emisor
            FROM facturas f
            INNER JOIN empresas e ON f.id_empresa = e.id_empresa
            WHERE f.id_factura = ? AND f.id_usuario = ?
        ");
        $stmt->execute([$id_factura, $id_usuario]);
        $factura = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$factura) {
            throw new Exception('Factura no encontrada o no tienes permiso para consultarla.');
        }
        
        $uuid = $factura['uuid'];
        $rfcEmisor = $factura['rfc_emisor'];
        
    } else {
        // Buscar por UUID
        $uuid = $datos['uuid'];
        $rfcEmisor = $datos['rfc_emisor'] ?? null;
        
        if (empty($rfcEmisor)) {
            // Intentar obtener el RFC de la base de datos
            $stmt = $conn->prepare("
                SELECT e.rfc as rfc_emisor
                FROM facturas f
                INNER JOIN empresas e ON f.id_empresa = e.id_empresa
                WHERE f.uuid = ? AND f.id_usuario = ?
                LIMIT 1
            ");
            $stmt->execute([$uuid, $id_usuario]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado) {
                $rfcEmisor = $resultado['rfc_emisor'];
            } else {
                throw new Exception('RFC del emisor no proporcionado y no se pudo determinar automáticamente.');
            }
        }
    }
    
    if (empty($uuid)) {
        throw new Exception('La factura no tiene UUID.');
    }
    
    // Configuración Finkok
    $finkokUser = 'michellflores822@gmail.com';
    $finkokPass = 'Pankycontra2025.';
    $enProduccion = false;
    
    // Crear instancia de la API
    $finkok = new FinkokApi($finkokUser, $finkokPass, $enProduccion);
    
    // Consultar estatus en el SAT
    $resultado = $finkok->consultarEstatusCancelacion($rfcEmisor, $uuid);
    
    if ($resultado['success']) {
        // Interpretar el resultado
        $satCode = $resultado['sat_code'] ?? '';
        $estado = $resultado['estado'] ?? '';
        $esCancelable = $resultado['es_cancelable'] ?? '';
        $estatusCancelacion = $resultado['estatus_cancelacion'] ?? '';
        
        // Actualizar información en la base de datos si tenemos id_factura
        if (isset($id_factura)) {
            $stmtUpdate = $conn->prepare("
                UPDATE facturas 
                SET sat_estatus = ?,
                    es_cancelable = ?,
                    estatus_cancelacion = ?,
                    ultima_consulta_sat = NOW()
                WHERE id_factura = ?
            ");
            $stmtUpdate->execute([$estado, $esCancelable, $estatusCancelacion, $id_factura]);
        }
        
        // Interpretar códigos del SAT
        $interpretacion = self::interpretarEstatusSAT($satCode, $estado, $esCancelable, $estatusCancelacion);
        
        echo json_encode([
            'success' => true,
            'uuid' => $uuid,
            'rfc_emisor' => $rfcEmisor,
            'sat_code' => $satCode,
            'estado' => $estado,
            'es_cancelable' => $esCancelable,
            'estatus_cancelacion' => $estatusCancelacion,
            'interpretacion' => $interpretacion,
            'message' => 'Consulta realizada exitosamente'
        ]);
        
    } else {
        echo json_encode([
            'success' => false,
            'message' => $resultado['message'],
            'uuid' => $uuid
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

/**
 * Interpretar los códigos de estatus del SAT
 */
function interpretarEstatusSAT($codigo, $estado, $esCancelable, $estatusCancelacion) {
    $interpretaciones = [
        'S' => 'Comprobante obtenido satisfactoriamente',
        'N - 601' => 'La expresión impresa proporcionada no es válida',
        'N - 602' => 'Comprobante no encontrado'
    ];
    
    $mensaje = $interpretaciones[$codigo] ?? "Estado: {$estado}";
    
    $detalles = [];
    
    if ($esCancelable === 'Cancelable sin aceptación') {
        $detalles[] = 'Puedes cancelar esta factura sin necesidad de aceptación del receptor.';
    } elseif ($esCancelable === 'Cancelable con aceptación') {
        $detalles[] = 'Requiere aceptación del receptor para cancelar (monto mayor a $1000 MXN).';
    } elseif ($esCancelable === 'No cancelable') {
        $detalles[] = 'La factura no puede ser cancelada (puede tener relaciones con otras facturas).';
    }
    
    if (!empty($estatusCancelacion)) {
        if ($estatusCancelacion === 'En proceso') {
            $detalles[] = 'La solicitud de cancelación está en proceso.';
        } elseif ($estatusCancelacion === 'Cancelado') {
            $detalles[] = 'La factura ha sido cancelada exitosamente.';
        } elseif ($estatusCancelacion === 'Plazo vencido') {
            $detalles[] = 'La solicitud de cancelación fue cancelada automáticamente por vencimiento del plazo (5 minutos).';
        } elseif ($estatusCancelacion === 'Rechazado') {
            $detalles[] = 'El receptor rechazó la solicitud de cancelación.';
        }
    }
    
    return [
        'mensaje' => $mensaje,
        'detalles' => $detalles
    ];
}
?>