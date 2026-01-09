<?php
/**
 * Envía una factura timbrada por correo electrónico
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/FacturaPdfService.php';
require_once __DIR__ . '/mail/CorreoConfigService.php';
require_once __DIR__ . '/mail/FacturaMailer.php';

header('Content-Type: application/json; charset=utf-8');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

try {
    $idUsuario = $_SESSION['usuario_id'] ?? $_SESSION['USR_ID'] ?? null;
    if (!$idUsuario) {
        throw new Exception('Sesión no válida o expirada.');
    }

    $input = file_get_contents('php://input');
    $payload = json_decode($input, true);
    if (!is_array($payload)) {
        throw new Exception('Datos inválidos.');
    }

    $idFactura = isset($payload['id_factura']) ? (int)$payload['id_factura'] : 0;
    $correoDestino = $payload['correo_destino'] ?? '';

    if ($idFactura <= 0) {
        throw new Exception('ID de factura inválido.');
    }

    if (!filter_var($correoDestino, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Correo electrónico inválido.');
    }

    $db = new Database();
    $conn = $db->getConnection();
    if (!($conn instanceof PDO)) {
        throw new Exception('No se pudo conectar a la base de datos.');
    }

    // Obtener datos de la factura
    $stmt = $conn->prepare("
        SELECT f.*, 
               e.nombre AS nombre_emisor, 
               e.razon_social AS razon_social_emisor, 
               e.rfc AS rfc_emisor,
               e.id_usuario as usuario_empresa
        FROM facturas f 
        INNER JOIN empresas e ON f.id_empresa = e.id_empresa 
        WHERE f.id_factura = ?
        LIMIT 1
    ");
    $stmt->execute([$idFactura]);
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$factura) {
        throw new Exception('Factura no encontrada.');
    }

    // Verificar permisos
    if ((int)$factura['id_usuario'] !== $idUsuario && (int)$factura['usuario_empresa'] !== $idUsuario) {
        throw new Exception('No tiene permisos para enviar esta factura.');
    }

    // Validar que esté timbrada
    if ($factura['estatus'] !== 'timbrada') {
        throw new Exception('Solo se pueden enviar facturas timbradas.');
    }

    // Generar PDF
    if (!function_exists('facturaGenerarPdfArchivo')) {
        throw new Exception('Servicio de PDF no disponible.');
    }
    
    $pdfInfo = facturaGenerarPdfArchivo($conn, $idFactura);
    if (!file_exists($pdfInfo['absolute'])) {
        throw new Exception('No se pudo generar el PDF de la factura.');
    }

    // Obtener configuración de correo
    if (!function_exists('correoConfigGet')) {
        throw new Exception('Servicio de correo no disponible. Asegúrese de que CorreoConfigService.php esté cargado.');
    }
    
    $configCorreo = correoConfigGet($conn, (int)$idUsuario, true);
    
    // Si no hay configuración en BD, usar constantes de config.php como fallback
    if (!$configCorreo || empty($configCorreo['smtp_host']) || empty($configCorreo['smtp_password'])) {
        if (defined('MAIL_HOST') && defined('MAIL_USER') && defined('MAIL_PSWD')) {
            $configCorreo = [
                'smtp_host' => MAIL_HOST,
                'smtp_port' => MAIL_PORT,
                'smtp_user' => MAIL_USER,
                'smtp_password' => MAIL_PSWD,
                'smtp_secure' => MAIL_SEC,
                'smtp_auth' => MAIL_AUT,
                'remitente_email' => MAIL_USER,
                'remitente_nombre' => 'Sistema de Facturación',
                'asunto_factura' => 'Factura Electrónica {folio} - {empresa}',
                'plantilla_correo' => 'Estimado/a {cliente}, Adjuntamos su factura electrónica {folio} por un total de {total}.\n\nFecha: {fecha}\nRFC: {rfc_cliente}\n\nGracias por su preferencia.\n\nSaludos,\n{empresa}'
            ];
        } else {
            throw new Exception('No se ha configurado el correo SMTP. Por favor, configure el servidor SMTP en: Panel → Configuración → Correo SMTP o en config.php');
        }
    }

    // Preparar variables
    $folio = ($factura['serie_interno'] ?? 'A') . str_pad((string)($factura['folio_interno'] ?? 0), 6, '0', STR_PAD_LEFT);
    
    $vars = [
        'folio' => $folio,
        'empresa' => $factura['razon_social_emisor'] ?? $factura['nombre_emisor'] ?? '',
        'cliente' => $factura['razon_social_receptor'] ?? '',
        'fecha' => isset($factura['fecha_emision']) ? date('d/m/Y H:i', strtotime($factura['fecha_emision'])) : '',
        'total' => '$' . number_format((float)($factura['total'] ?? 0), 2),
        'rfc_cliente' => $factura['rfc_receptor'] ?? '',
        'rfc_empresa' => $factura['rfc_emisor'] ?? ''
    ];

    // Preparar adjuntos
    $attachments = [
        [
            'path' => $pdfInfo['absolute'],
            'name' => basename($pdfInfo['absolute'])
        ]
    ];

    // Adjuntar XML
    if (!empty($factura['xml_path'])) {
        $rutaXmlAbs = __DIR__ . '/../' . ltrim($factura['xml_path'], '/');
        if (file_exists($rutaXmlAbs)) {
            $attachments[] = [
                'path' => $rutaXmlAbs,
                'name' => ($factura['uuid'] ?: 'Factura') . '.xml'
            ];
        }
    }

    // Enviar correo
    $resultado = facturaEnviarCorreo(
        $configCorreo,
        $correoDestino,
        $factura['razon_social_receptor'] ?? 'Cliente',
        $vars,
        $attachments
    );

    if (!$resultado['success']) {
        throw new Exception($resultado['message']);
    }

    // Actualizar correo en la factura
    $stmtUpdate = $conn->prepare("UPDATE facturas SET correo_receptor = ? WHERE id_factura = ?");
    $stmtUpdate->execute([$correoDestino, $idFactura]);

    echo json_encode([
        'success' => true,
        'message' => 'Factura enviada correctamente a ' . $correoDestino
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
