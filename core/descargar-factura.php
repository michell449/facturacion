<?php
session_start();
require_once 'autoload-vendor.php';

// Verificar sesión (aceptar diferentes nombres de variable usados en el proyecto)
$id_usuario = $_SESSION['id_usuario'] ?? $_SESSION['usuario_id'] ?? $_SESSION['USR_ID'] ?? null;
$tipo_usuario = $_SESSION['tipo_usuario'] ?? null;
$logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

// Solo clientes autenticados pueden descargar sus facturas
if (!$logged_in || !$id_usuario || $tipo_usuario !== 'cliente') {
    header('HTTP/1.0 403 Forbidden');
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

if (!isset($_GET['id_factura']) || !isset($_GET['tipo'])) {
    header('HTTP/1.0 400 Bad Request');
    echo json_encode(['success' => false, 'message' => 'Parámetros incompletos']);
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=facturacion;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $id_factura = $_GET['id_factura'];
    // Usar el id_usuario ya obtenido de la sesión (línea 6)
    $tipo = $_GET['tipo']; // 'pdf' o 'xml'
    
    // Obtener ruta del archivo - verificando que pertenezca al usuario
    $query = "SELECT 
                f.id_factura,
                f.folio_interno,
                f.serie_interno,
                f.xml_path,
                f.pdf_path
              FROM facturas f
              WHERE f.id_factura = :id_factura AND f.id_usuario = :id_usuario";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'id_factura' => $id_factura,
        'id_usuario' => $id_usuario
    ]);
    
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$factura) {
        header('HTTP/1.0 404 Not Found');
        echo json_encode(['success' => false, 'message' => 'Factura no encontrada']);
        exit;
    }
    
    $folio_completo = ($factura['serie_interno'] ? $factura['serie_interno'] . '-' : '') . $factura['folio_interno'];
    
    // Directorio base del proyecto
    $base_dir = dirname(__DIR__);
    
    // Determinar archivo a descargar
    if ($tipo === 'pdf') {
        $file_path = $factura['pdf_path'];
        $content_type = 'application/pdf';
        $file_extension = 'pdf';
    } elseif ($tipo === 'xml') {
        $file_path = $factura['xml_path'];
        $content_type = 'application/xml';
        $file_extension = 'xml';
    } else {
        header('HTTP/1.0 400 Bad Request');
        echo json_encode(['success' => false, 'message' => 'Tipo de archivo no válido']);
        exit;
    }
    
    // Verificar que el archivo exista
    if (empty($file_path)) {
        header('HTTP/1.0 404 Not Found');
        echo json_encode(['success' => false, 'message' => 'Archivo no disponible']);
        exit;
    }
    
    // Si la ruta es relativa, construir la ruta absoluta
    if (!file_exists($file_path)) {
        // Intentar con ruta relativa desde el directorio base
        $file_path = $base_dir . '/' . ltrim($file_path, '/');
    }
    
    if (!file_exists($file_path)) {
        header('HTTP/1.0 404 Not Found');
        echo json_encode(['success' => false, 'message' => 'Archivo no encontrado en el servidor']);
        exit;
    }
    
    // Nombre del archivo para descarga
    $download_name = "Factura_{$folio_completo}.{$file_extension}";
    
    // Enviar headers para descarga
    header('Content-Type: ' . $content_type);
    header('Content-Disposition: attachment; filename="' . $download_name . '"');
    header('Content-Length: ' . filesize($file_path));
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    
    // Limpiar buffer de salida
    ob_clean();
    flush();
    
    // Leer y enviar el archivo
    readfile($file_path);
    exit;
    
} catch (PDOException $e) {
    header('HTTP/1.0 500 Internal Server Error');
    echo json_encode([
        'success' => false,
        'message' => 'Error al descargar archivo: ' . $e->getMessage()
    ]);
    exit;
} catch (Exception $e) {
    header('HTTP/1.0 500 Internal Server Error');
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
    exit;
}
