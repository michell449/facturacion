<?php
/**
 * Genera una factura desde un ticket
 * Toma datos del emisor de la sucursal del ticket
 * Toma datos del receptor de datos_fiscales_usuario
 */

// PRIMERO: Limpiar buffers previos
while (ob_get_level() > 0) {
    ob_end_clean();
}

// SEGUNDO: Iniciar buffer LIMPIO para capturar salida no deseada
ob_start();

// TERCERO: Configurar PHP para no mostrar errores en pantalla
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// CUARTO: Configurar UTF-8 interno de PHP
mb_internal_encoding('UTF-8');
ini_set('default_charset', 'UTF-8');

// Headers JSON (ANTES de cualquier salida)
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');

session_start();

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/../api/FinkokApi.php';

$respuesta = ['success' => false, 'message' => 'Error desconocido'];

try {
    error_log("=== INICIO FACTURAR DESDE TICKET ===");
    
    // =========================================================================
    // 1. VALIDAR SESIÓN
    // =========================================================================
    $id_usuario = $_SESSION['usuario_id'] ?? $_SESSION['USR_ID'] ?? null;
    if (!$id_usuario) {
        throw new Exception('Sesión no válida. Por favor, inicia sesión nuevamente.');
    }
    
    error_log("Usuario ID: {$id_usuario}");

    // =========================================================================
    // 2. OBTENER Y VALIDAR DATOS DEL POST
    // =========================================================================
    $input = file_get_contents('php://input');
    error_log("Input recibido: " . substr($input, 0, 200));
    
    $datos = json_decode($input, true);

    if (!$datos) {
        throw new Exception('No se recibieron datos válidos: ' . json_last_error_msg());
    }

    if (empty($datos['id_ticket'])) {
        throw new Exception('ID de ticket requerido');
    }

    if (empty($datos['id_empresa'])) {
        throw new Exception('ID de empresa/sucursal requerido');
    }

    $id_ticket = (int)$datos['id_ticket'];
    $id_empresa = (int)$datos['id_empresa'];
    
    error_log("Ticket ID: {$id_ticket}, Empresa ID: {$id_empresa}");

    $db = new Database();
    $conn = $db->getConnection();

    // =========================================================================
    // 3. OBTENER DATOS DEL TICKET
    // =========================================================================
    error_log("[TICKET] Consultando ticket {$id_ticket} de empresa {$id_empresa}");
    $sqlTicket = "SELECT * FROM tickets WHERE id_ticket = ? AND id_empresa = ?";
    $stmtTicket = $conn->prepare($sqlTicket);
    $stmtTicket->execute([$id_ticket, $id_empresa]);
    $ticket = $stmtTicket->fetch(PDO::FETCH_ASSOC);

    if (!$ticket) {
        error_log("[ERROR] Ticket {$id_ticket} no encontrado en empresa {$id_empresa}");
        throw new Exception('Ticket no encontrado');
    }
    
    error_log("[TICKET] Encontrado - Folio: {$ticket['folio_ticket']}, Estatus: {$ticket['estatus']}, ID_Factura: " . ($ticket['id_factura'] ?? 'NULL'));

    // Validar que el ticket no esté ya facturado
    if (!empty($ticket['estatus']) && $ticket['estatus'] == 'facturado') {
        error_log("[VALIDACION] Ticket marcado como 'facturado', verificando factura asociada");
        
        // Verificar si ya tiene una factura activa (no cancelada)
        if (!empty($ticket['id_factura'])) {
            error_log("[VALIDACION] Consultando factura ID: {$ticket['id_factura']}");
            $sqlVerificarFactura = "SELECT estatus FROM facturas WHERE id_factura = ?";
            $stmtVerificar = $conn->prepare($sqlVerificarFactura);
            $stmtVerificar->execute([$ticket['id_factura']]);
            $facturaExistente = $stmtVerificar->fetch(PDO::FETCH_ASSOC);
            
            if ($facturaExistente) {
                error_log("[VALIDACION] Factura encontrada con estatus: {$facturaExistente['estatus']}");
                if ($facturaExistente['estatus'] !== 'cancelada') {
                    error_log("[ERROR] Intento de re-facturar ticket con factura activa (ID: {$ticket['id_factura']})");
                    throw new Exception('Este ticket ya tiene una factura activa. Primero cancela la factura anterior (ID: ' . $ticket['id_factura'] . ')');
                }
                error_log("[OK] Factura anterior está cancelada, permitiendo re-facturación");
            } else {
                error_log("[ADVERTENCIA] Factura ID {$ticket['id_factura']} no existe en BD, permitiendo re-facturación");
            }
        } else {
            error_log("[ADVERTENCIA] Ticket marcado como facturado pero sin id_factura asociado");
        }
        
        // Si la factura anterior fue cancelada o no existe, permitir re-facturar
        error_log("[OK] Permitiendo re-facturación del ticket");
    } else {
        error_log("[OK] Ticket con estatus '" . ($ticket['estatus'] ?? 'NULL') . "', no facturado previamente");
    }
    
    error_log("Ticket encontrado: Folio {$ticket['folio_ticket']}");

    // =========================================================================
    // 4. OBTENER DETALLES DEL TICKET (PRODUCTOS)
    // =========================================================================
    $sqlDetalles = "SELECT * FROM ticket_detalle WHERE id_ticket = ?";
    $stmtDetalles = $conn->prepare($sqlDetalles);
    $stmtDetalles->execute([$id_ticket]);
    $detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);

    if (empty($detalles)) {
        throw new Exception('El ticket no tiene productos asociados');
    }
    
    error_log("Productos encontrados: " . count($detalles));

    // =========================================================================
    // 5. OBTENER DATOS FISCALES DEL EMISOR (SUCURSAL/EMPRESA)
    // =========================================================================
    error_log("[EMISOR] Consultando datos de empresa ID: {$id_empresa}");
    $sqlEmisor = "SELECT * FROM empresas WHERE id_empresa = ?";
    $stmtEmisor = $conn->prepare($sqlEmisor);
    $stmtEmisor->execute([$id_empresa]);
    $emisor = $stmtEmisor->fetch(PDO::FETCH_ASSOC);

    if (!$emisor) {
        error_log("[ERROR] Emisor no encontrado para empresa ID: {$id_empresa}");
        throw new Exception('Datos del emisor no encontrados');
    }
    
    error_log("[EMISOR] Encontrado - RFC: {$emisor['rfc']}, Razón Social: {$emisor['razon_social']}");

    // Validar datos fiscales del emisor
    if (empty($emisor['rfc'])) throw new Exception('El emisor no tiene RFC configurado');
    if (empty($emisor['reg_fiscal'])) throw new Exception('El emisor no tiene Régimen Fiscal configurado');
    if (empty($emisor['cp'])) throw new Exception('El emisor no tiene Código Postal configurado');
    if (empty($emisor['file_cer']) || empty($emisor['file_key'])) {
        throw new Exception('El emisor no tiene certificados digitales (CSD) configurados');
    }
    
    error_log("Emisor: {$emisor['rfc']} - {$emisor['razon_social']}");

    // =========================================================================
    // 6. OBTENER DATOS FISCALES DEL RECEPTOR (USUARIO)
    // =========================================================================
    error_log("[RECEPTOR] Consultando datos fiscales de usuario ID: {$id_usuario}");
    $sqlReceptor = "SELECT * FROM datos_fiscales_usuario WHERE id_usuario = ?";
    $stmtReceptor = $conn->prepare($sqlReceptor);
    $stmtReceptor->execute([$id_usuario]);
    $receptor = $stmtReceptor->fetch(PDO::FETCH_ASSOC);

    if (!$receptor) {
        error_log("[ERROR] Datos fiscales no encontrados para usuario ID: {$id_usuario}");
        throw new Exception('No tienes datos fiscales registrados. Por favor, registra tus datos fiscales primero.');
    }
    
    error_log("[RECEPTOR] Encontrado - RFC: {$receptor['rfc']}, Razón Social: {$receptor['razon_social']}");

    // Validar datos fiscales del receptor
    if (empty($receptor['rfc'])) throw new Exception('Tu RFC no está registrado');
    if (empty($receptor['razon_social'])) throw new Exception('Tu Razón Social no está registrada');
    if (empty($receptor['reg_fiscal'])) throw new Exception('Tu Régimen Fiscal no está registrado');
    if (empty($receptor['cp'])) throw new Exception('Tu Código Postal no está registrado');
    
    error_log("Receptor: {$receptor['rfc']} - {$receptor['razon_social']}");

    // =========================================================================
    // 7. OBTENER FORMA Y MÉTODO DE PAGO DEL TICKET
    // =========================================================================
    $sqlPagos = "SELECT * FROM ticket_metodo_pago WHERE id_ticket = ?";
    $stmtPagos = $conn->prepare($sqlPagos);
    $stmtPagos->execute([$id_ticket]);
    $pagos = $stmtPagos->fetchAll(PDO::FETCH_ASSOC);

    // Por defecto PUE (Pago en una exhibición) y efectivo
    $metodoPago = 'PUE';
    $formaPago = '01'; // Efectivo por defecto

    if (!empty($pagos)) {
        $formaPago = $pagos[0]['forma_pago'] ?? '01';
        $metodoPago = $pagos[0]['metodo_pago'] ?? 'PUE';
    }
    
    error_log("Método pago: {$metodoPago}, Forma pago: {$formaPago}");

    // =========================================================================
    // 8. DETERMINAR USO CFDI SEGÚN TIPO DE PERSONA
    // =========================================================================
    $usoCfdi = 'G03'; // Por defecto: Gastos en general

    if ($receptor['tipo_pers'] === 'Fisica') {
        $usoCfdi = 'G03';
    } else if ($receptor['tipo_pers'] === 'Moral') {
        $usoCfdi = 'G03';
    }

    // Si el RFC es genérico
    if ($receptor['rfc'] === 'XAXX010101000' || $receptor['rfc'] === 'XEXX010101000') {
        $usoCfdi = 'S01';
        $receptor['reg_fiscal'] = '616';
    }
    
    error_log("Uso CFDI: {$usoCfdi}");

    // =========================================================================
    // 9. GENERAR SERIE Y FOLIO
    // =========================================================================
    $sqlUltimoFolio = "SELECT MAX(CAST(folio_interno AS UNSIGNED)) as ultimo_folio 
                       FROM facturas 
                       WHERE id_empresa = ? AND serie_interno = 'A'";
    $stmtUltimoFolio = $conn->prepare($sqlUltimoFolio);
    $stmtUltimoFolio->execute([$id_empresa]);
    $resultadoFolio = $stmtUltimoFolio->fetch(PDO::FETCH_ASSOC);
    
    $nuevoFolio = ($resultadoFolio['ultimo_folio'] ?? 0) + 1;
    $serieInterna = 'A';
    
    error_log("Nuevo folio: {$serieInterna}{$nuevoFolio}");

    // =========================================================================
    // 10. CALCULAR TOTALES
    // =========================================================================
    $subtotal = floatval($ticket['subtotal'] ?? 0);
    $impuestos = floatval($ticket['impuesto_t'] ?? 0);
    $total = floatval($ticket['importe_t'] ?? 0);

    // Si no hay impuestos calculados, calcular (16% IVA)
    if ($impuestos == 0) {
        $impuestos = $subtotal * 0.16;
        $total = $subtotal + $impuestos;
    }

    // =========================================================================
    // 11. VALIDACIÓN PREVIA DE DATOS ANTES DE GUARDAR EN BD
    // =========================================================================
    $erroresPrevios = [];
    if (empty($receptor['rfc'])) $erroresPrevios[] = 'RFC receptor vacío';
    if (empty($receptor['razon_social'])) $erroresPrevios[] = 'Nombre receptor vacío';
    if (empty($receptor['reg_fiscal'])) $erroresPrevios[] = 'Régimen fiscal receptor vacío';
    if (empty($receptor['cp'])) $erroresPrevios[] = 'CP receptor vacío';
    if (empty($usoCfdi)) $erroresPrevios[] = 'Uso CFDI vacío';
    if (empty($formaPago)) $erroresPrevios[] = 'Forma de pago vacía';
    if (empty($metodoPago)) $erroresPrevios[] = 'Método de pago vacío';
    if (empty($detalles)) $erroresPrevios[] = 'Debe haber al menos un concepto';
    
    if (count($erroresPrevios) > 0) {
        throw new Exception('Errores de validación previos: ' . implode('; ', $erroresPrevios));
    }

    // =========================================================================
    // 12. INICIAR TRANSACCIÓN Y CREAR FACTURA
    // =========================================================================
    error_log("[BD] Iniciando transacción para crear factura");
    error_log("[BD] Datos a insertar - Serie: {$serieInterna}, Folio: {$nuevoFolio}, Subtotal: {$subtotal}, IVA: {$impuestos}, Total: {$total}");
    $conn->beginTransaction();

    try {
        // Insertar cabecera de factura
        error_log("[BD] Insertando cabecera de factura");
        $sqlInsertFactura = "INSERT INTO facturas (
            id_usuario,
            id_empresa,
            id_ticket,
            serie_interno,
            folio_interno,
            fecha_emision,
            rfc_receptor,
            razon_social_receptor,
            domicilio_fiscal_receptor,
            regimen_fiscal_receptor,
            uso_cfdi,
            forma_pago,
            metodo_pago,
            moneda,
            tipo_cambio,
            subtotal,
            impuestos_trasladados,
            total,
            tipo_comprobante,
            exportacion,
            lugar_expedicion,
            estatus
        ) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?, 'MXN', 1.00, ?, ?, ?, 'I', '01', ?, 'pendiente')";

        $stmtInsert = $conn->prepare($sqlInsertFactura);
        $stmtInsert->execute([
            $id_usuario,
            $id_empresa,
            $id_ticket,
            $serieInterna,
            $nuevoFolio,
            $receptor['rfc'],
            $receptor['razon_social'],
            $receptor['cp'],
            $receptor['reg_fiscal'],
            $usoCfdi,
            $formaPago,
            $metodoPago,
            $subtotal,
            $impuestos,
            $total,
            $emisor['cp']
        ]);

        $id_factura = $conn->lastInsertId();
        
        error_log("[BD] ✓ Factura creada exitosamente con ID: {$id_factura}");

        // Insertar conceptos/productos
        error_log("[BD] Insertando " . count($detalles) . " conceptos/productos");
        $sqlInsertConcepto = "INSERT INTO facturas_detalles (
            id_factura,
            clave_prod_serv,
            no_identificacion,
            cantidad,
            clave_unidad,
            unidad,
            descripcion,
            valor_unitario,
            importe,
            objeto_imp,
            impuesto_base,
            impuesto_tipo,
            impuesto_tasa,
            impuesto_importe
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmtInsertConcepto = $conn->prepare($sqlInsertConcepto);

        $conceptoNum = 1;
        foreach ($detalles as $detalle) {
            $cantidad = floatval($detalle['cant'] ?? 1);
            $precioUnitario = floatval($detalle['precio_unit'] ?? 0);
            $descripcion = $detalle['descr'] ?? 'Producto/Servicio';
            
            // Obtener clave producto/servicio y validar formato
            $claveProdServ = $detalle['id_prod_serv'] ?? '01010101';
            
            // Validar que sea de 8 dígitos, si no, usar genérico
            if (empty($claveProdServ) || strlen($claveProdServ) != 8 || !ctype_digit($claveProdServ)) {
                error_log("[BD] ⚠ Concepto {$conceptoNum}: Clave '{$claveProdServ}' inválida, usando genérica 01010101");
                $claveProdServ = '01010101'; // Clave genérica para productos/servicios
            } else {
                error_log("[BD] Concepto {$conceptoNum}: '{$descripcion}' - Clave: {$claveProdServ}, Cant: {$cantidad}, Precio: {$precioUnitario}");
            }
            
            $noIdentificacion = $detalle['folio'] ?? '';
            
            // Si no hay identificación, no enviarla vacía
            if (empty($noIdentificacion)) {
                $noIdentificacion = 'PROD-' . ($detalle['id_detalle'] ?? uniqid());
            }
            
            $importe = $cantidad * $precioUnitario;
            
            // Calcular impuestos (IVA 16%)
            $base = $importe;
            $tasaIVA = 0.160000;
            $importeIVA = $base * $tasaIVA;

            $stmtInsertConcepto->execute([
                $id_factura,
                $claveProdServ,
                $noIdentificacion,
                $cantidad,
                'H87',
                'Pieza',
                $descripcion,
                $precioUnitario,
                $importe,
                '02',
                $base,
                '002',
                '0.160000',
                $importeIVA
            ]);
            
            $conceptoNum++;
        }
        
        error_log("[BD] ✓ " . count($detalles) . " conceptos insertados exitosamente");

        // Marcar ticket como facturado
        error_log("[BD] Actualizando ticket {$id_ticket} a estatus 'facturado'");
        $sqlUpdateTicket = "UPDATE tickets SET estatus = 'facturado', id_factura = ? WHERE id_ticket = ?";
        $stmtUpdateTicket = $conn->prepare($sqlUpdateTicket);
        $stmtUpdateTicket->execute([$id_factura, $id_ticket]);

        $conn->commit();
        
        error_log("[BD] ✓✓✓ Transacción completada exitosamente ✓✓✓");

    } catch (Exception $e) {
        error_log("[BD] ✗✗✗ ERROR en transacción: {$e->getMessage()} ✗✗✗");
        error_log("[BD] Ejecutando ROLLBACK");
        $conn->rollBack();
        throw new Exception('Error en base de datos: ' . $e->getMessage());
    }

    // =========================================================================
    // 13. GENERAR XML
    // =========================================================================
    error_log("[XML] ═══ Iniciando generación de XML para factura {$id_factura} ═══");
    $resultadoXML = generarXMLFactura($id_factura);
    
    if (!isset($resultadoXML['success']) || !$resultadoXML['success']) {
        error_log("[XML] ✗ Error al generar XML: " . ($resultadoXML['message'] ?? 'Error desconocido'));
        throw new Exception('Error al generar XML: ' . ($resultadoXML['message'] ?? 'Error desconocido'));
    }
    
    error_log("[XML] ✓ XML generado exitosamente");

    // =========================================================================
    // 14. TIMBRAR XML CON FINKOK
    // =========================================================================
    error_log("[TIMBRADO] ═══ Iniciando timbrado con Finkok para factura {$id_factura} ═══");
    $resultadoTimbrado = timbrarFactura($id_factura);
    
    // Verificar respuesta del timbrado
    $timbradoExitoso = false;
    if (isset($resultadoTimbrado['success']) && $resultadoTimbrado['success']) {
        $timbradoExitoso = true;
    } elseif (isset($resultadoTimbrado['status']) && $resultadoTimbrado['status'] === 'success') {
        $timbradoExitoso = true;
    }
    
    if (!$timbradoExitoso) {
        $mensajeError = $resultadoTimbrado['message'] ?? 'Error desconocido al timbrar';
        $detalle = $resultadoTimbrado['detail'] ?? '';
        error_log("[TIMBRADO] ✗ Error: {$mensajeError}" . ($detalle ? " | {$detalle}" : ''));
        throw new Exception('Error al timbrar factura: ' . $mensajeError . ($detalle ? ' | ' . $detalle : ''));
    }
    
    $uuid = $resultadoTimbrado['uuid'] ?? ($resultadoTimbrado['data']['uuid'] ?? 'N/A');
    error_log("[TIMBRADO] ✓ Factura timbrada exitosamente - UUID: {$uuid}");

    // =========================================================================
    // 15. GENERAR PDF (OPCIONAL)
    // =========================================================================
    $resultadoPDF = generarPDFFactura($id_factura);
    
    if (!$resultadoPDF['success']) {
        error_log('Advertencia: No se pudo generar el PDF: ' . $resultadoPDF['message']);
    }

    // =========================================================================
    // RESPUESTA EXITOSA
    // =========================================================================
    $uuid = $resultadoTimbrado['uuid'] ?? ($resultadoTimbrado['data']['uuid'] ?? null);
    $xmlUrl = $resultadoTimbrado['xml_url'] ?? $resultadoTimbrado['ruta_xml'] ?? null;
    
    $respuesta = [
        'success' => true,
        'message' => 'Factura generada, timbrada y guardada correctamente',
        'id_factura' => $id_factura,
        'folio' => $serieInterna . $nuevoFolio,
        'uuid' => $uuid,
        'xml_url' => $xmlUrl,
        'pdf_url' => $resultadoPDF['pdf_url'] ?? null
    ];
    
    error_log("╔═══════════════════════════════════════════════════╗");
    error_log("║  ✓✓✓ FACTURACIÓN COMPLETADA EXITOSAMENTE ✓✓✓    ║");
    error_log("║  Factura ID: {$id_factura}                       ║");
    error_log("║  Folio: {$serieInterna}{$nuevoFolio}             ║");
    error_log("║  UUID: {$uuid}                                   ║");
    error_log("╚═══════════════════════════════════════════════════╝");

} catch (Throwable $e) {
    if (isset($conn) && $conn->inTransaction()) {
        error_log("[ERROR] Ejecutando ROLLBACK de transacción activa");
        $conn->rollBack();
    }
    
    error_log("╔═══════════════════════════════════════════════════╗");
    error_log("║  ✗✗✗ ERROR EN FACTURACIÓN ✗✗✗                    ║");
    error_log("╚═══════════════════════════════════════════════════╝");
    error_log("[ERROR] Mensaje: " . $e->getMessage());
    error_log("[ERROR] Archivo: " . $e->getFile());
    error_log("[ERROR] Línea: " . $e->getLine());
    error_log("[ERROR] Trace: " . $e->getTraceAsString());
    
    http_response_code(500);
    $respuesta = [
        'success' => false,
        'message' => $e->getMessage(),
        'debug' => [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ];
}

// ============================================================================
// SALIDA FINAL: Siempre limpio, siempre JSON válido
// ============================================================================
$outputBuffer = ob_get_clean();
if (!empty($outputBuffer)) {
    error_log("OUTPUT INESPERADO CAPTURADO: " . substr($outputBuffer, 0, 200));
}
echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
exit;

// ============================================================================
// FUNCIONES AUXILIARES
// ============================================================================

/**
 * Genera el XML de la factura
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
        error_log("Error cURL XML: $error");
        return ['success' => false, 'message' => 'Error de conexión al generar XML: ' . $error];
    }
    
    if ($httpCode !== 200) {
        error_log("HTTP $httpCode al generar XML factura $id_factura");
        return ['success' => false, 'message' => "Error HTTP $httpCode del servidor"];
    }
    
    $resultado = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("Error JSON XML: " . json_last_error_msg());
        error_log("Respuesta: " . substr($response, 0, 500));
        return ['success' => false, 'message' => 'Respuesta inválida del servidor al generar XML'];
    }
    
    return $resultado;
}

/**
 * Timbra la factura usando Finkok
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
        error_log("Error cURL timbrado: $error");
        return ['success' => false, 'message' => 'Error de conexión al timbrar: ' . $error];
    }
    
    if ($httpCode !== 200) {
        error_log("HTTP $httpCode al timbrar factura $id_factura");
        return ['success' => false, 'message' => "Error HTTP $httpCode del servidor de timbrado"];
    }
    
    $resultado = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("Error JSON timbrado: " . json_last_error_msg());
        error_log("Respuesta: " . substr($response, 0, 500));
        return ['success' => false, 'message' => 'Respuesta inválida del servidor de timbrado'];
    }
    
    return $resultado;
}

/**
 * Genera el PDF de la factura
 */
function generarPDFFactura($id_factura) {
    try {
        return [
            'success' => true,
            'message' => 'PDF disponible para descarga',
            'pdf_url' => 'core/generar-pdf-factura.php?id_factura=' . $id_factura
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}
