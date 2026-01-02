<?php
//Ubicación: core/timbrar-xml.php
require_once __DIR__ . '/../core/autoload-vendor.php';
require_once __DIR__ . '/../api/FinkokApi.php';
require_once __DIR__ . '/class/db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    // =========================================================================
    // 1. OBTENCIÓN DEL XML (SOLUCIÓN A TU ERROR)
    // =========================================================================
    
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

    // =========================================================================
    // 2. CONFIGURACIÓN FINKOK
    // =========================================================================
    
    $finkokUser   = 'michellflores822@gmail.com';  // CAMBIA A TUS DATOS REALES SI YA TIENES
    $finkokPass   = 'Pankycontra2025.';        // CAMBIA A TUS DATOS REALES SI YA TIENES
    $enProduccion = false;                 // false = Demo / true = Producción

    $timbrador = new FinkokApi($finkokUser, $finkokPass, $enProduccion);

    // =========================================================================
    // 3. TIMBRAR
    // =========================================================================

    $resultado = $timbrador->timbrar($xml_string);

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

        // RESPUESTA EXITOSA
        echo json_encode([
            'status'  => 'success',
            'success' => true,
            'message' => 'Factura timbrada correctamente',
            'uuid'    => $uuid,
            'xml_url' => $rutaParaBD,
            'ruta_xml'=> $rutaParaBD,
            'fecha'   => $fechaTimbrado
        ]);

    } else {
        // ERROR DE FINKOK (Saldo agotado, RFC inválido, XML mal formado)
        echo json_encode([
            'status'  => 'error',
            'message' => 'Error de Finkok: ' . $resultado['message'],
            'detail'  => $resultado['detail'] ?? null,
            'fault_code' => $resultado['fault_code'] ?? null,
            'fault_string' => $resultado['fault_string'] ?? null,
            'status_code' => $resultado['status_code'] ?? null
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Excepción del sistema: ' . $e->getMessage()
    ]);
}
?>