<?php
/**
 * Facturación para clientes invitados
 * Flujo completo: crear usuario → guardar datos fiscales → crear factura → XML → Timbrar → PDF → Email
 */

// PRIMERO: Limpiar buffers previos
while (ob_get_level() > 0) {
    ob_end_clean();
}

// SEGUNDO: Iniciar buffer LIMPIO
ob_start();

// TERCERO: Configurar PHP
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
mb_internal_encoding('UTF-8');
ini_set('default_charset', 'UTF-8');

// Headers JSON (ANTES de cualquier salida)
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/autoload-vendor.php';
require_once __DIR__ . '/FacturaPdfService.php';
require_once __DIR__ . '/mail/CorreoConfigService.php';
require_once __DIR__ . '/mail/FacturaMailer.php';

$respuesta = [
    'success' => false,
    'message' => 'Error desconocido.',
    'id_factura' => null,
    'folio' => null,
    'uuid' => null
];


try {
    // =========================================================================
    // 1. VALIDAR Y RECIBIR DATOS
    // =========================================================================
    error_log("═══ INICIO FACTURACIÓN INVITADO ═══");
    
    $input = file_get_contents('php://input');
    $datos = json_decode($input, true);

    if (!is_array($datos)) {
        throw new Exception('Formato de datos inválido.');
    }

    // Datos del ticket
    $id_ticket = isset($datos['id_ticket']) ? intval($datos['id_ticket']) : null;
    $nombre_empresa = $datos['nombre_empresa'] ?? null;
    $folio_ticket = $datos['folio_ticket'] ?? null;
    $monto_ticket = $datos['monto_ticket'] ? floatval($datos['monto_ticket']) : null;

    // Datos fiscales del cliente
    $correo = $datos['correo'] ?? null;
    $rfc = $datos['rfc'] ?? null;
    $razon_social = $datos['razon_social'] ?? null;
    $reg_fiscal = $datos['reg_fiscal'] ?? null;
    $cp = $datos['cp'] ? intval($datos['cp']) : null;
    $tipo_persona = $datos['tipo_persona'] ?? null;
    $calle = $datos['calle'] ?? null;
    $num_ext = $datos['num_ext'] ?? null;
    $num_int = $datos['num_int'] ?? null;
    $colonia = $datos['colonia'] ?? null;
    $uso_cfdi = $datos['uso_cfdi'] ?? 'G01';

    error_log("Datos recibidos - Ticket: {$id_ticket}, RFC: {$rfc}, Email: {$correo}");

    // Validar datos requeridos
    if (!$id_ticket || !$correo || !$rfc || !$razon_social || !$reg_fiscal || !$cp || !$tipo_persona) {
        throw new Exception('Faltan datos requeridos para el registro.');
    }

    // Validar email
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Correo electrónico no válido.');
    }

    // Validar RFC
    $rfc = strtoupper(trim($rfc));
    if (strlen($rfc) < 12 || strlen($rfc) > 13) {
        throw new Exception('RFC no válido. Debe tener 12 o 13 caracteres.');
    }

    // Validar código postal
    if ($cp < 1000 || $cp > 99999) {
        throw new Exception('Código postal debe tener 5 dígitos.');
    }

    if (!in_array($tipo_persona, ['Fisica', 'Moral'])) {
        throw new Exception('Tipo de persona debe ser "Fisica" o "Moral".');
    }

    $db = new Database();
    $conn = $db->getConnection();

    // =========================================================================
    // 2. VERIFICAR TICKET EXISTE Y ESTÁ PENDIENTE
    // =========================================================================
    error_log("[TICKET] Buscando ticket {$id_ticket}");
    
    $stmtTicket = $conn->prepare("
        SELECT 
            t.*,
            e.id_usuario as id_usuario_empresa,
            e.rfc as rfc_emisor,
            e.razon_social,
            e.nombre,
            e.reg_fiscal as reg_fiscal_emisor,
            e.cp as cp_emisor,
            e.direccion,
            e.colonia,
            e.file_cer,
            e.file_key
        FROM tickets t
        INNER JOIN empresas e ON t.id_empresa = e.id_empresa
        WHERE t.id_ticket = ?
            AND t.estatus = 'pendiente'
    ");
    $stmtTicket->execute([$id_ticket]);
    $ticket = $stmtTicket->fetch(PDO::FETCH_ASSOC);

    if (!$ticket) {
        throw new Exception('Ticket no encontrado o ya ha sido facturado.');
    }

    $id_empresa = $ticket['id_empresa'];
    error_log("[TICKET] ✓ Encontrado - Folio: {$ticket['folio_ticket']}, Total: {$ticket['importe_t']}");

    // =========================================================================
    // 3. OBTENER DETALLES DEL TICKET
    // =========================================================================
    error_log("[DETALLES] Obteniendo detalles del ticket");
    
    $stmtDetalles = $conn->prepare("
        SELECT * FROM ticket_detalle WHERE id_ticket = ?
    ");
    $stmtDetalles->execute([$id_ticket]);
    $detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);

    if (empty($detalles)) {
        throw new Exception('El ticket no tiene productos.');
    }

    error_log("[DETALLES] ✓ " . count($detalles) . " productos encontrados");

    // =========================================================================
    // 4. OBTENER MÉTODOS DE PAGO
    // =========================================================================
    $stmtPagos = $conn->prepare("
        SELECT * FROM ticket_metodo_pago WHERE id_ticket = ?
    ");
    $stmtPagos->execute([$id_ticket]);
    $pagos = $stmtPagos->fetchAll(PDO::FETCH_ASSOC);

    $forma_pago = 'PUE';
    $metodo_pago = '01';
    if (!empty($pagos)) {
        $forma_pago = $pagos[0]['forma_pago'] ?? 'PUE';
        $metodo_pago = $pagos[0]['metodo_pago'] ?? '01';
    }

    // =========================================================================
    // 5. REGISTRAR USUARIO INVITADO
    // =========================================================================
    error_log("[USUARIO] Registrando usuario invitado");
    
    $stmtUsuarioExistente = $conn->prepare("
        SELECT id_usuario FROM usuarios 
        WHERE correo = ? AND tipo_cliente = 'invitado'
        LIMIT 1
    ");
    $stmtUsuarioExistente->execute([$correo]);
    $id_usuario_invitado = null;

    if ($stmtUsuarioExistente->rowCount() > 0) {
        $usuarioExistente = $stmtUsuarioExistente->fetch(PDO::FETCH_ASSOC);
        $id_usuario_invitado = $usuarioExistente['id_usuario'];
        error_log("[USUARIO] ✓ Usuario invitado existente ID: {$id_usuario_invitado}");
    } else {
        $stmtInsertUsuario = $conn->prepare("
            INSERT INTO usuarios (correo, tipo_usuario, tipo_cliente, verificacion, fecha_reg)
            VALUES (?, 'cliente', 'invitado', 1, NOW())
        ");
        $stmtInsertUsuario->execute([$correo]);
        $id_usuario_invitado = $conn->lastInsertId();
        error_log("[USUARIO] ✓ Nuevo usuario invitado creado ID: {$id_usuario_invitado}");
    }

    if (!$id_usuario_invitado) {
        throw new Exception('No se pudo registrar el usuario.');
    }

    // =========================================================================
    // 6. GUARDAR DATOS FISCALES
    // =========================================================================
    error_log("[DATOS_FISCALES] Guardando datos fiscales");
    
    $stmtDFExistentes = $conn->prepare("
        SELECT id_df FROM datos_fiscales_usuario 
        WHERE id_usuario = ? AND rfc = ? LIMIT 1
    ");
    $stmtDFExistentes->execute([$id_usuario_invitado, $rfc]);

    if ($stmtDFExistentes->rowCount() > 0) {
        $stmtUpdateDF = $conn->prepare("
            UPDATE datos_fiscales_usuario 
            SET razon_social = ?, reg_fiscal = ?, cp = ?, tipo_pers = ?, 
                calle = ?, num_ext = ?, num_int = ?, col = ?
            WHERE id_usuario = ? AND rfc = ?
        ");
        $stmtUpdateDF->execute([
            $razon_social, $reg_fiscal, $cp, $tipo_persona,
            $calle, $num_ext, $num_int, $colonia,
            $id_usuario_invitado, $rfc
        ]);
        error_log("[DATOS_FISCALES] ✓ Actualizado");
    } else {
        $stmtInsertDF = $conn->prepare("
            INSERT INTO datos_fiscales_usuario 
            (id_usuario, rfc, razon_social, reg_fiscal, cp, tipo_pers, calle, num_ext, num_int, col)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmtInsertDF->execute([
            $id_usuario_invitado, $rfc, $razon_social, $reg_fiscal, $cp, $tipo_persona,
            $calle, $num_ext, $num_int, $colonia
        ]);
        error_log("[DATOS_FISCALES] ✓ Insertado");
    }

    // =========================================================================
    // 7. GENERAR FOLIO
    // =========================================================================
    error_log("[FOLIO] Generando folio");
    
    $stmtFolio = $conn->prepare("
        SELECT MAX(CAST(folio_interno AS UNSIGNED)) as max_folio 
        FROM facturas WHERE id_empresa = ?
    ");
    $stmtFolio->execute([$id_empresa]);
    $resultFolio = $stmtFolio->fetch(PDO::FETCH_ASSOC);
    $folio_nuevo = ($resultFolio['max_folio'] ?? 0) + 1;
    $serie = 'A';

    error_log("[FOLIO] ✓ {$serie}{$folio_nuevo}");

    // =========================================================================
    // 8. CREAR FACTURA EN BD
    // =========================================================================
    error_log("[BD] Creando factura en base de datos");
    
    $stmtFactura = $conn->prepare("
        INSERT INTO facturas (
            id_usuario, id_empresa, id_ticket,
            folio_interno, serie_interno,
            fecha_emision,
            rfc_receptor, razon_social_receptor,
            regimen_fiscal_receptor, codigo_postal_receptor, uso_cfdi,
            calle_receptor, num_ext_receptor, num_int_receptor, colonia_receptor,
            subtotal, total, impuesto_total,
            forma_pago, metodo_pago,
            correo_receptor,
            estatus
        ) VALUES (
            ?, ?, ?,
            ?, ?,
            NOW(),
            ?, ?,
            ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?,
            ?, ?,
            ?,
            'pendiente'
        )
    ");

    $stmtFactura->execute([
        $id_usuario_invitado, $id_empresa, $id_ticket,
        $folio_nuevo, $serie,
        $rfc, $razon_social,
        $reg_fiscal, $cp, $uso_cfdi,
        $calle, $num_ext, $num_int, $colonia,
        $ticket['subtotal'], $ticket['importe_t'], $ticket['impuesto_t'],
        $forma_pago, $metodo_pago,
        $correo
    ]);

    $id_factura = $conn->lastInsertId();
    error_log("[BD] ✓ Factura creada ID: {$id_factura}");

    // =========================================================================
    // 9. INSERTAR DETALLES DE FACTURA
    // =========================================================================
    error_log("[DETALLES_BD] Insertando detalles de factura");
    
    foreach ($detalles as $detalle) {
        $stmtInsertDetalle = $conn->prepare("
            INSERT INTO facturas_detalles (
                id_factura, descripcion, cantidad,
                precio_unitario, importe,
                impuesto, tasa_impuesto
            ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $impuesto_detalle = $detalle['importe'] * 0.16;

        $stmtInsertDetalle->execute([
            $id_factura,
            $detalle['descr'],
            $detalle['cant'],
            $detalle['precio_unit'],
            $detalle['importe'],
            $impuesto_detalle,
            '16'
        ]);
    }

    error_log("[DETALLES_BD] ✓ Detalles insertados");

    // =========================================================================
    // 10. MARCAR TICKET COMO FACTURADO
    // =========================================================================
    error_log("[TICKET] Actualizando estado del ticket");
    
    $stmtActualizarTicket = $conn->prepare("
        UPDATE tickets 
        SET estatus = 'facturado', id_factura = ?
        WHERE id_ticket = ?
    ");
    $stmtActualizarTicket->execute([$id_factura, $id_ticket]);

    error_log("[TICKET] ✓ Estado actualizado a 'facturado'");

    // =========================================================================
    // 11. GENERAR XML
    // =========================================================================
    error_log("[XML] ═══ Generando XML ═══");
    
    $resultadoXML = generarXMLFactura($id_factura);

    if (!isset($resultadoXML['success']) || !$resultadoXML['success']) {
        error_log("[XML] ✗ Error: " . ($resultadoXML['message'] ?? 'Error desconocido'));
        throw new Exception('Error al generar XML: ' . ($resultadoXML['message'] ?? 'Error desconocido'));
    }

    error_log("[XML] ✓ XML generado exitosamente");

    // =========================================================================
    // 12. TIMBRAR CON SAT
    // =========================================================================
    error_log("[TIMBRADO] ═══ Timbrado con SAT ═══");
    
    $resultadoTimbrado = timbrarFactura($id_factura);

    $timbradoExitoso = false;
    if (isset($resultadoTimbrado['success']) && $resultadoTimbrado['success']) {
        $timbradoExitoso = true;
    } elseif (isset($resultadoTimbrado['status']) && $resultadoTimbrado['status'] === 'success') {
        $timbradoExitoso = true;
    }

    if (!$timbradoExitoso) {
        $mensajeError = $resultadoTimbrado['message'] ?? 'Error desconocido al timbrar';
        error_log("[TIMBRADO] ✗ Error: {$mensajeError}");
        throw new Exception('No se pudo timbrar la factura: ' . $mensajeError);
    }

    $uuid = $resultadoTimbrado['uuid'] ?? ($resultadoTimbrado['data']['uuid'] ?? 'N/A');
    error_log("[TIMBRADO] ✓ Factura timbrada - UUID: {$uuid}");

    // =========================================================================
    // 13. GENERAR PDF
    // =========================================================================
    error_log("[PDF] Generando PDF");
    
    try {
        $pdfInfo = facturaGenerarPdfArchivo($conn, $id_factura);
        if (file_exists($pdfInfo['absolute'])) {
            error_log("[PDF] ✓ PDF generado exitosamente");
        } else {
            error_log("[PDF] ⚠ PDF no existe en: " . $pdfInfo['absolute']);
        }
    } catch (Exception $e) {
        error_log("[PDF] ⚠ Error al generar PDF: " . $e->getMessage());
    }

    // =========================================================================
    // 14. OBTENER RUTAS DE XML Y PDF
    // =========================================================================
    $stmtFacturaFinal = $conn->prepare("
        SELECT xml_path, pdf_path FROM facturas WHERE id_factura = ?
    ");
    $stmtFacturaFinal->execute([$id_factura]);
    $facturaFinal = $stmtFacturaFinal->fetch(PDO::FETCH_ASSOC);

    $rutaXML = $facturaFinal['xml_path'] ?? null;
    $rutaPDF = $facturaFinal['pdf_path'] ?? null;

    // =========================================================================
    // 15. ENVIAR CORREO
    // =========================================================================
    error_log("[EMAIL] ═══ Enviando correo ═══");
    
    $configCorreo = null;
    if (function_exists('correoConfigGet')) {
        try {
            $configCorreo = correoConfigGet($conn, $id_usuario_invitado, true);
        } catch (Exception $e) {
            error_log("[EMAIL] ⚠ Error obteniendo configuración: " . $e->getMessage());
        }
    }

    // Usar configuración por defecto si no está en BD
    if (!$configCorreo || empty($configCorreo['smtp_host'])) {
        if (defined('MAIL_HOST') && defined('MAIL_USER') && defined('MAIL_PSWD')) {
            $configCorreo = [
                'smtp_host' => MAIL_HOST,
                'smtp_port' => MAIL_PORT ?? 587,
                'smtp_user' => MAIL_USER,
                'smtp_password' => MAIL_PSWD,
                'smtp_secure' => MAIL_SEC ?? 'tls',
                'remitente_email' => MAIL_USER,
                'remitente_nombre' => 'Sistema de Facturación'
            ];
            error_log("[EMAIL] Usando configuración de config.php");
        }
    }

    if ($configCorreo && !empty($configCorreo['smtp_host'])) {
        try {
            // Preparar adjuntos
            $adjuntos = [];

            // Adjuntar PDF
            if (!empty($rutaPDF)) {
                $rutaPDFAbs = __DIR__ . '/../' . ltrim($rutaPDF, '/');
                if (file_exists($rutaPDFAbs)) {
                    $adjuntos[] = [
                        'path' => $rutaPDFAbs,
                        'name' => basename($rutaPDFAbs)
                    ];
                    error_log("[EMAIL] ✓ PDF adjuntado");
                }
            }

            // Adjuntar XML
            if (!empty($rutaXML)) {
                $rutaXMLAbs = __DIR__ . '/../' . ltrim($rutaXML, '/');
                if (file_exists($rutaXMLAbs)) {
                    $adjuntos[] = [
                        'path' => $rutaXMLAbs,
                        'name' => ($uuid ?: 'Factura') . '.xml'
                    ];
                    error_log("[EMAIL] ✓ XML adjuntado");
                }
            }

            // Construir mensaje
            $folio = $serie . str_pad($folio_nuevo, 6, '0', STR_PAD_LEFT);
            $asunto = "Factura Electrónica {$folio} - {$ticket['razon_social']}";
            
            $cuerpoMensaje = "
                <html>
                <head>
                    <meta charset='UTF-8'>
                    <style>
                        body { font-family: Arial, sans-serif; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .header { background-color: #0d6efd; color: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
                        .info-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                        .info-table th { background-color: #f8f9fa; padding: 10px; text-align: left; border: 1px solid #ddd; }
                        .info-table td { padding: 10px; border: 1px solid #ddd; }
                        .total { font-size: 18px; font-weight: bold; color: #0d6efd; }
                        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h1>Factura Electrónica</h1>
                            <p>Folio: <strong>{$folio}</strong></p>
                        </div>
                        
                        <p>Estimado/a <strong>{$razon_social}</strong>,</p>
                        
                        <p>Le notificamos que su factura electrónica ha sido generada exitosamente.</p>
                        
                        <table class='info-table'>
                            <tr>
                                <th>Concepto</th>
                                <th>Valor</th>
                            </tr>
                            <tr>
                                <td>Folio</td>
                                <td>{$folio}</td>
                            </tr>
                            <tr>
                                <td>RFC</td>
                                <td>{$rfc}</td>
                            </tr>
                            <tr>
                                <td>Fecha</td>
                                <td>" . date('d/m/Y H:i:s') . "</td>
                            </tr>
                            <tr>
                                <td>Subtotal</td>
                                <td>\$" . number_format($ticket['subtotal'], 2) . "</td>
                            </tr>
                            <tr>
                                <td>Impuesto (IVA 16%)</td>
                                <td>\$" . number_format($ticket['impuesto_t'], 2) . "</td>
                            </tr>
                            <tr>
                                <td class='total'>TOTAL</td>
                                <td class='total'>\$" . number_format($ticket['importe_t'], 2) . "</td>
                            </tr>
                        </table>
                        
                        <p>Los archivos XML y PDF de su factura se encuentran adjuntos a este correo.</p>
                        
                        <p>Si tiene alguna duda o pregunta, no dude en contactar al administrador.</p>
                        
                        <div class='footer'>
                            <p>Este es un correo automático del Sistema de Facturación.</p>
                            <p>Por favor, no responda a este correo. Si necesita asistencia, contacte al administrador.</p>
                        </div>
                    </div>
                </body>
                </html>
            ";

            // Enviar correo con función personalizada
            if (function_exists('facturaEnviarCorreo')) {
                $vars = [
                    'folio' => $folio,
                    'cliente' => $razon_social,
                    'total' => '$' . number_format($ticket['importe_t'], 2),
                    'rfc' => $rfc
                ];

                $resultadoEmail = facturaEnviarCorreo(
                    $configCorreo,
                    $correo,
                    $razon_social,
                    $vars,
                    $adjuntos
                );

                if ($resultadoEmail['success']) {
                    error_log("[EMAIL] ✓ Correo enviado a {$correo}");
                } else {
                    error_log("[EMAIL] ⚠ Error al enviar correo: " . $resultadoEmail['message']);
                }
            } else {
                error_log("[EMAIL] ⚠ Función facturaEnviarCorreo no disponible");
            }

        } catch (Exception $e) {
            error_log("[EMAIL] ⚠ Error: " . $e->getMessage());
        }
    } else {
        error_log("[EMAIL] ⚠ Configuración SMTP no disponible");
    }

    // =========================================================================
    // RESPUESTA EXITOSA
    // =========================================================================
    $respuesta = [
        'success' => true,
        'message' => 'Factura generada, timbrada y enviada por correo exitosamente',
        'id_factura' => $id_factura,
        'folio' => $folio_nuevo,
        'uuid' => $uuid,
        'correo' => $correo
    ];

    error_log("╔════════════════════════════════════════╗");
    error_log("║  ✓ FACTURACIÓN INVITADO COMPLETADA   ║");
    error_log("║  ID: {$id_factura}                    ║");
    error_log("║  Folio: {$serie}{$folio_nuevo}        ║");
    error_log("║  Email: {$correo}                     ║");
    error_log("╚════════════════════════════════════════╝");

} catch (Throwable $e) {
    error_log("╔════════════════════════════════════════╗");
    error_log("║  ✗ ERROR EN FACTURACIÓN ✗             ║");
    error_log("╚════════════════════════════════════════╝");
    error_log("[ERROR] " . $e->getMessage());
    error_log("[ERROR] Archivo: " . $e->getFile());
    error_log("[ERROR] Línea: " . $e->getLine());

    http_response_code(400);
    $respuesta['message'] = $e->getMessage();
}


// ============================================================================
// SALIDA FINAL
// ============================================================================
$outputBuffer = ob_get_clean();
if (!empty($outputBuffer)) {
    error_log("⚠ OUTPUT NO ESPERADO: " . substr($outputBuffer, 0, 100));
}

echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
exit;

// ============================================================================
// FUNCIONES AUXILIARES
// ============================================================================

/**
 * Genera XML de la factura
 */
function generarXMLFactura($id_factura) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost/facturacion/core/generar-xml.php');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['id_factura' => $id_factura]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return ['success' => false, 'message' => 'Error cURL: ' . $error];
    }

    if ($httpCode !== 200) {
        return ['success' => false, 'message' => "Error HTTP {$httpCode}"];
    }

    $resultado = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['success' => false, 'message' => 'JSON inválido: ' . json_last_error_msg()];
    }

    return $resultado;
}

/**
 * Timbra la factura
 */
function timbrarFactura($id_factura) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost/facturacion/core/timbrar-xml.php');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['id_factura' => $id_factura]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return ['success' => false, 'message' => 'Error cURL: ' . $error];
    }

    if ($httpCode !== 200) {
        return ['success' => false, 'message' => "Error HTTP {$httpCode}"];
    }

    $resultado = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['success' => false, 'message' => 'JSON inválido'];
    }

    return $resultado;
}
?>
