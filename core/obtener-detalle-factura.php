<?php
session_start();
require_once 'autoload-vendor.php';

header('Content-Type: application/json');

// Verificar sesión
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

if (!isset($_GET['id_factura'])) {
    echo json_encode(['success' => false, 'message' => 'ID de factura no proporcionado']);
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=facturacion;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $id_factura = $_GET['id_factura'];
    $id_usuario = $_SESSION['id_usuario'];
    
    // Obtener datos de la factura - verificando que pertenezca al usuario
    $query = "SELECT 
                f.*
              FROM facturas f
              WHERE f.id_factura = :id_factura AND f.id_usuario = :id_usuario";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'id_factura' => $id_factura,
        'id_usuario' => $id_usuario
    ]);
    
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$factura) {
        echo json_encode(['success' => false, 'message' => 'Factura no encontrada']);
        exit;
    }
    
    // Obtener detalles (productos) de la factura
    $query_detalles = "SELECT 
                        fd.*
                      FROM facturas_detalles fd
                      WHERE fd.id_factura = :id_factura
                      ORDER BY fd.id_detalle";
    
    $stmt_detalles = $pdo->prepare($query_detalles);
    $stmt_detalles->execute(['id_factura' => $id_factura]);
    $detalles = $stmt_detalles->fetchAll(PDO::FETCH_ASSOC);
    
    // Formatear los datos
    $factura['fecha_emision_formatted'] = date('d/m/Y H:i', strtotime($factura['fecha_emision']));
    $factura['fecha_timbrado_formatted'] = $factura['fecha_timbrado'] ? date('d/m/Y H:i', strtotime($factura['fecha_timbrado'])) : 'N/A';
    $factura['subtotal_formatted'] = '$' . number_format($factura['subtotal'], 2);
    $factura['impuestos_trasladados_formatted'] = '$' . number_format($factura['impuestos_trasladados'], 2);
    $factura['impuestos_retenidos_formatted'] = '$' . number_format($factura['impuestos_retenidos'], 2);
    $factura['total_formatted'] = '$' . number_format($factura['total'], 2);
    $factura['folio_completo'] = ($factura['serie_interno'] ? $factura['serie_interno'] . '-' : '') . $factura['folio_interno'];
    
    // Determinar si tiene archivos disponibles
    $factura['tiene_pdf'] = !empty($factura['pdf_path']) && file_exists($factura['pdf_path']);
    $factura['tiene_xml'] = !empty($factura['xml_path']) && file_exists($factura['xml_path']);
    
    // Formatear detalles
    foreach ($detalles as &$detalle) {
        $detalle['cantidad_formatted'] = number_format($detalle['cantidad'], 2);
        $detalle['valor_unitario_formatted'] = '$' . number_format($detalle['valor_unitario'], 2);
        $detalle['importe_formatted'] = '$' . number_format($detalle['importe'], 2);
        $detalle['impuesto_importe_formatted'] = '$' . number_format($detalle['impuesto_importe'], 2);
        $detalle['impuesto_tasa_formatted'] = ($detalle['impuesto_tasa'] * 100) . '%';
    }
    
    echo json_encode([
        'success' => true,
        'factura' => $factura,
        'detalles' => $detalles
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener detalle de factura: ' . $e->getMessage()
    ]);
}
