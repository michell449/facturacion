<?php
/**
 * Timbrado de XML con Finkok
 * Ubicación: core/timbrar-xml.php
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

require_once __DIR__ . '/../core/autoload-vendor.php';
require_once __DIR__ . '/../api/FinkokApi.php';
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/FacturaPdfService.php';
require_once __DIR__ . '/mail/CorreoConfigService.php';
require_once __DIR__ . '/mail/FacturaMailer.php';

$respuesta = ['success' => false, 'message' => 'Error desconocido'];

try {
    
    $xml_string = null;
    $id_factura = null;
    
    // Leer datos del POST (JSON)
    $input = file_get_contents('php://input');
    $datos = json_decode($input, true);
    
    // A. Intentar obtener id_factura del POST
    if (isset($datos['id_factura'])) {
        $id_factura = intval($datos['id_factura']);
    } elseif (isset($_POST['id_factura'])) {
        $id_factura = intval($_POST['id_factura']);
    }
    
    // B. Si tenemos id_factura, buscar el XML en la base de datos
    if ($id_factura) {
        $db = new Database();
        $conn = $db->getConnection();
        
        $stmt = $conn->prepare("SELECT xml_path FROM facturas WHERE id_factura = ?");
        $stmt->execute([$id_factura]);
        $factura = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$factura || empty($factura['xml_path'])) {
            throw new Exception("No se encontró el XML para la factura con ID {$id_factura}. Asegúrate de generar el XML primero.");
        }
        
        // Construir la ruta completa del archivo
        $ruta_xml = __DIR__ . '/../uploads/xml_timbrados/' . $factura['xml_path'];
        
        if (!file_exists($ruta_xml)) {
            throw new Exception("El archivo XML no existe en: {$factura['xml_path']}");
        }
        
        // Leer el contenido del XML
        $xml_string = file_get_contents($ruta_xml);
        
        if (empty($xml_string)) {
            throw new Exception("El archivo XML está vacío.");
        }
    }
    // C. Intentar por POST directo (para compatibilidad con otros flujos)
    elseif (isset($datos['xml_string'])) {
        $xml_string = $datos['xml_string'];
    } 
    elseif (isset($_POST['xml_string'])) {
        $xml_string = $_POST['xml_string'];
    }
    
    // Validación final
    if (empty($xml_string)) {
        throw new Exception("No se recibió el contenido XML ni se encontró una ruta de archivo válida.");
    }
    
    // Validar que el XML sea válido antes de enviar a Finkok
    libxml_use_internal_errors(true);
    $xmlDoc = simplexml_load_string($xml_string);
    if ($xmlDoc === false) {
        $errors = libxml_get_errors();
        $errorMsg = "XML inválido: ";
        foreach ($errors as $error) {
            $errorMsg .= $error->message . " ";
        }
        libxml_clear_errors();
        throw new Exception($errorMsg);
    }
    
    // Log para debug
    error_log("=== TIMBRADO FACTURA ID: $id_factura ===");
    error_log("Tamaño XML: " . strlen($xml_string) . " bytes");
    error_log("Primeros 200 caracteres: " . substr($xml_string, 0, 200));

    // =========================================================================
    // 2. CONFIGURACIÓN FINKOK
    // =========================================================================
    
    $finkokUser   = 'michellflores822@gmail.com'; 
    $finkokPass   = 'PankyContra1997.';        
    $enProduccion = false;                 

    $timbrador = new FinkokApi($finkokUser, $finkokPass, $enProduccion);

    // =========================================================================
    // 3. TIMBRAR
    // =========================================================================

    error_log("Enviando XML a Finkok...");
    $resultado = $timbrador->timbrar($xml_string);
    
    error_log("Respuesta Finkok: " . json_encode($resultado));

    if ($resultado['success']) {
        // DATOS OBTENIDOS DE FINKOK
        $xmlTimbrado    = $resultado['xml_timbrado']; // XML completo con el nodo TFD
        $uuid           = $resultado['uuid'];
        $fechaTimbrado  = $resultado['fecha'];

        // =====================================================================
        // 4. GUARDAR ARCHIVO Y ACTUALIZAR BASE DE DATOS
        // =====================================================================

        // Definir ruta final (usamos la carpeta uploads/xml_timbrados)
        $directorioDestino = __DIR__ . '/../uploads/xml_timbrados/';
        if (!is_dir($directorioDestino)) {
            mkdir($directorioDestino, 0777, true);
        }

        // Nombre del archivo: RFC_UUID.xml (o solo UUID.xml)
        $nombreArchivo = $uuid . '.xml';
        $rutaFinal = $directorioDestino . $nombreArchivo;
        
        // Guardamos el XML YA TIMBRADO en el disco (sobrescribiendo el previo si es necesario)
        file_put_contents($rutaFinal, $xmlTimbrado);

        // Ruta relativa para guardar en BD (según tu estructura de carpetas)
        $rutaParaBD = 'uploads/xml_timbrados/' . $nombreArchivo;

        // Extraer Sello SAT y Certificado SAT para la BD (Opcional, pero recomendado)
        // Usamos una expresión regular simple para sacarlos rápido del XML string
        $selloSAT = '';
        $noCertificadoSAT = '';
        if (preg_match('/SelloSAT="([^"]+)"/', $xmlTimbrado, $matches)) {
            $selloSAT = $matches[1];
        }
        if (preg_match('/NoCertificadoSAT="([^"]+)"/', $xmlTimbrado, $matches)) {
            $noCertificadoSAT = $matches[1];
        }

        // ACTUALIZAR BASE DE DATOS
        if (isset($id_factura)) {
            // Usamos sentencias preparadas para seguridad
            $sql = "UPDATE facturas SET 
                    uuid = ?, 
                    estatus = 'timbrada', 
                    fecha_timbrado = ?, 
                    sello_sat = ?, 
                    no_certificado_sat = ?,
                    xml_path = ? 
                    WHERE id_factura = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $uuid, 
                $fechaTimbrado, 
                $selloSAT, 
                $noCertificadoSAT, 
                $rutaParaBD, 
                $id_factura
            ]);
        }
        
        // =====================================================================
        // 5. GENERAR PDF Y ENVIAR CORREO (SI APLICA)
        // =====================================================================
        error_log("=== INICIO GENERACIÓN PDF Y ENVÍO CORREO ===");
        
        $pdfGenerado = false;
        $correoEnviado = false;
        $mensajeCorreo = '';
        
        // 5.1 Generar PDF automáticamente
        if (isset($id_factura) && function_exists('facturaGenerarPdfArchivo')) {
            try {
                error_log("Generando PDF para factura ID: $id_factura");
                $pdfInfo = facturaGenerarPdfArchivo($conn, $id_factura);
                
                if ($pdfInfo && isset($pdfInfo['absolute']) && file_exists($pdfInfo['absolute'])) {
                    $pdfGenerado = true;
                    error_log("PDF generado exitosamente: " . $pdfInfo['absolute']);
                    $respuesta['pdf_path'] = $pdfInfo['relative'];
                } else {
                    error_log("ERROR: PDF no se pudo generar o no existe");
                }
            } catch (Exception $ePdf) {
                error_log("ERROR generando PDF: " . $ePdf->getMessage());
                $respuesta['pdf_error'] = $ePdf->getMessage();
            }
        } else {
            error_log("ADVERTENCIA: función facturaGenerarPdfArchivo no disponible o id_factura no definido");
        }
        
        // 5.2 Obtener correo del receptor
        $correo_receptor = '';
        if (isset($id_factura)) {
            try {
                $stmtCorreo = $conn->prepare("SELECT correo_receptor FROM facturas WHERE id_factura = ?");
                $stmtCorreo->execute([$id_factura]);
                $facturaData = $stmtCorreo->fetch(PDO::FETCH_ASSOC);
                $correo_receptor = $facturaData['correo_receptor'] ?? '';
                error_log("Correo receptor obtenido: " . ($correo_receptor ?: 'NO ESPECIFICADO'));
            } catch (Exception $eCorreo) {
                error_log("ERROR obteniendo correo receptor: " . $eCorreo->getMessage());
            }
        }
        
        // 5.3 Enviar correo automáticamente si hay correo válido y PDF generado
        if (!empty($correo_receptor) && filter_var($correo_receptor, FILTER_VALIDATE_EMAIL) && $pdfGenerado) {
            try {
                error_log("Intentando enviar correo a: $correo_receptor");
                
                // Obtener configuración SMTP
                if (!function_exists('correoConfigGet')) {
                    throw new Exception('Función correoConfigGet no disponible');
                }
                
                // Obtener ID de usuario de la factura
                $stmtUsuario = $conn->prepare("
                    SELECT f.id_usuario, e.id_usuario as usuario_empresa 
                    FROM facturas f 
                    INNER JOIN empresas e ON f.id_empresa = e.id_empresa 
                    WHERE f.id_factura = ?
                ");
                $stmtUsuario->execute([$id_factura]);
                $datosUsuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);
                $idUsuarioFactura = $datosUsuario['id_usuario'] ?? $datosUsuario['usuario_empresa'] ?? null;
                
                if (!$idUsuarioFactura) {
                    throw new Exception('No se pudo determinar el usuario de la factura');
                }
                
                $configCorreo = correoConfigGet($conn, (int)$idUsuarioFactura, true);
                error_log("Config correo obtenida. SMTP Host: " . ($configCorreo['smtp_host'] ?? 'NO DEFINIDO'));
                
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
                            'plantilla_correo' => 'Estimado/a {cliente},\n\nAdjuntamos su factura electrónica {folio} por un total de {total}.\n\nFecha: {fecha}\nRFC: {rfc_cliente}\n\nGracias por su preferencia.\n\nSaludos,\n{empresa}'
                        ];
                        error_log("Usando configuración SMTP de config.php: " . MAIL_HOST);
                    } else {
                        throw new Exception('Configuración SMTP no disponible ni en BD ni en config.php');
                    }
                }
                
                // Obtener datos completos de la factura para el email
                $stmtFact = $conn->prepare("
                    SELECT f.*, 
                           e.nombre AS nombre_emisor, 
                           e.razon_social AS razon_social_emisor,
                           e.rfc AS rfc_emisor
                    FROM facturas f 
                    INNER JOIN empresas e ON f.id_empresa = e.id_empresa 
                    WHERE f.id_factura = ?
                ");
                $stmtFact->execute([$id_factura]);
                $facturaCompleta = $stmtFact->fetch(PDO::FETCH_ASSOC);
                
                $folio = ($facturaCompleta['serie_interno'] ?? 'A') . str_pad((string)($facturaCompleta['folio_interno'] ?? 0), 6, '0', STR_PAD_LEFT);
                
                $vars = [
                    'folio' => $folio,
                    'empresa' => $facturaCompleta['razon_social_emisor'] ?? $facturaCompleta['nombre_emisor'] ?? '',
                    'cliente' => $facturaCompleta['razon_social_receptor'] ?? '',
                    'fecha' => isset($facturaCompleta['fecha_emision']) ? date('d/m/Y H:i', strtotime($facturaCompleta['fecha_emision'])) : '',
                    'total' => '$' . number_format((float)($facturaCompleta['total'] ?? 0), 2),
                    'rfc_cliente' => $facturaCompleta['rfc_receptor'] ?? '',
                    'rfc_empresa' => $facturaCompleta['rfc_emisor'] ?? ''
                ];
                
                // Preparar adjuntos
                $attachments = [];
                if (isset($pdfInfo['absolute']) && file_exists($pdfInfo['absolute'])) {
                    $attachments[] = [
                        'path' => $pdfInfo['absolute'],
                        'name' => basename($pdfInfo['absolute'])
                    ];
                    error_log("Adjunto PDF agregado: " . $pdfInfo['absolute']);
                }
                
                // Adjuntar XML timbrado
                if (file_exists($rutaFinal)) {
                    $attachments[] = [
                        'path' => $rutaFinal,
                        'name' => $nombreArchivo
                    ];
                    error_log("Adjunto XML agregado: $rutaFinal");
                }
                
                error_log("Total de adjuntos: " . count($attachments));
                
                // Enviar correo
                if (!function_exists('facturaEnviarCorreo')) {
                    throw new Exception('Función facturaEnviarCorreo no disponible');
                }
                
                $resultado = facturaEnviarCorreo(
                    $configCorreo,
                    $correo_receptor,
                    $facturaCompleta['razon_social_receptor'] ?? 'Cliente',
                    $vars,
                    $attachments
                );
                
                if ($resultado['success']) {
                    $correoEnviado = true;
                    $mensajeCorreo = 'Correo enviado exitosamente';
                    error_log("Correo enviado exitosamente a $correo_receptor");
                } else {
                    $mensajeCorreo = $resultado['message'];
                    error_log("Error enviando correo: " . $mensajeCorreo);
                }
                
            } catch (Exception $eMail) {
                $mensajeCorreo = 'Error: ' . $eMail->getMessage();
                error_log("EXCEPCIÓN al enviar correo: " . $mensajeCorreo);
            }
        } else {
            if (empty($correo_receptor)) {
                $mensajeCorreo = 'No se especificó correo del receptor';
                error_log("No se envió correo: correo receptor vacío");
            } elseif (!filter_var($correo_receptor, FILTER_VALIDATE_EMAIL)) {
                $mensajeCorreo = 'Correo del receptor inválido';
                error_log("No se envió correo: correo receptor inválido - $correo_receptor");
            } elseif (!$pdfGenerado) {
                $mensajeCorreo = 'PDF no disponible';
                error_log("No se envió correo: PDF no generado");
            }
        }
        
        // Agregar información del correo a la respuesta
        $respuesta['correo_enviado'] = $correoEnviado;
        $respuesta['correo_mensaje'] = $mensajeCorreo;
        error_log("=== FIN GENERACIÓN PDF Y ENVÍO CORREO ===");

        // RESPUESTA EXITOSA
        $respuesta = [
            'status'  => 'success',
            'success' => true,
            'message' => 'Factura timbrada correctamente',
            'uuid'    => $uuid,
            'xml_url' => $rutaParaBD,
            'ruta_xml'=> $rutaParaBD,
            'fecha'   => $fechaTimbrado
        ];

    } else {
        // ERROR DE FINKOK (Saldo agotado, RFC inválido, XML mal formado)
        $mensajeError = $resultado['message'] ?? 'Error desconocido';
        $detalle = $resultado['detail'] ?? '';
        
        error_log("ERROR FINKOK: $mensajeError");
        if ($detalle) {
            error_log("Detalle: $detalle");
        }
        
        $respuesta = [
            'status'  => 'error',
            'success' => false,
            'message' => 'Error de Finkok: ' . $mensajeError,
            'detail'  => $detalle,
            'fault_code' => $resultado['fault_code'] ?? null,
            'fault_string' => $resultado['fault_string'] ?? null,
            'status_code' => $resultado['status_code'] ?? null
        ];
    }

} catch (Exception $e) {
    error_log("EXCEPCIÓN TIMBRADO: " . $e->getMessage());
    error_log("Stack: " . $e->getTraceAsString());
    
    http_response_code(500);
    $respuesta = [
        'status'  => 'error',
        'success' => false,
        'message' => 'Excepción del sistema: ' . $e->getMessage(),
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
    error_log("OUTPUT INESPERADO CAPTURADO EN TIMBRADO: " . substr($outputBuffer, 0, 200));
}

// Si no se definió respuesta, usar la que capturó datos anteriores
if (isset($respuesta) && !empty($respuesta)) {
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
}
exit;
?>