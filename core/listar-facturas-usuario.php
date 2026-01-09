<?php
session_start();
require_once 'autoload-vendor.php';

header('Content-Type: application/json');

// Verificar sesión (aceptar diferentes nombres de variable usados en el proyecto)
$id_usuario = $_SESSION['id_usuario'] ?? $_SESSION['usuario_id'] ?? $_SESSION['USR_ID'] ?? null;
$tipo_usuario = $_SESSION['tipo_usuario'] ?? null;
$logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

// Solo clientes autenticados pueden consultar sus facturas
if (!$logged_in || !$id_usuario || $tipo_usuario !== 'cliente') {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=facturacion;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // id_usuario ya obtenido arriba
    // Obtener parámetros de filtrado
    $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
    $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;
    $estado = isset($_GET['estado']) ? $_GET['estado'] : null;
    $buscar = isset($_GET['buscar']) ? $_GET['buscar'] : null;
    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $por_pagina = 10;
    $offset = ($pagina - 1) * $por_pagina;
    
    // Construir query base
    $query = "SELECT 
                f.id_factura,
                f.folio_interno,
                f.serie_interno,
                f.fecha_emision,
                f.estatus,
                f.rfc_receptor,
                f.razon_social_receptor,
                f.total,
                f.uuid,
                f.xml_path,
                f.pdf_path,
                f.fecha_timbrado,
                f.tipo_comprobante,
                f.moneda
              FROM facturas f
              WHERE f.id_usuario = :id_usuario";
    
    $params = ['id_usuario' => $id_usuario];
    
    // Agregar filtros
    if ($fecha_inicio) {
        $query .= " AND DATE(f.fecha_emision) >= :fecha_inicio";
        $params['fecha_inicio'] = $fecha_inicio;
    }
    
    if ($fecha_fin) {
        $query .= " AND DATE(f.fecha_emision) <= :fecha_fin";
        $params['fecha_fin'] = $fecha_fin;
    }
    
    if ($estado) {
        $query .= " AND f.estatus = :estado";
        $params['estado'] = $estado;
    }
    
    if ($buscar) {
        $query .= " AND (f.folio_interno LIKE :buscar 
                    OR f.serie_interno LIKE :buscar 
                    OR f.rfc_receptor LIKE :buscar
                    OR f.razon_social_receptor LIKE :buscar
                    OR f.uuid LIKE :buscar)";
        $params['buscar'] = "%$buscar%";
    }
    
    // Contar total de registros
    $count_query = "SELECT COUNT(*) as total FROM (" . $query . ") as temp";
    $stmt_count = $pdo->prepare($count_query);
    $stmt_count->execute($params);
    $total_registros = $stmt_count->fetch(PDO::FETCH_ASSOC)['total'];
    $total_paginas = ceil($total_registros / $por_pagina);
    
    // Agregar ordenamiento y paginación
    $query .= " ORDER BY f.fecha_emision DESC LIMIT :limit OFFSET :offset";
    $params['limit'] = $por_pagina;
    $params['offset'] = $offset;
    
    $stmt = $pdo->prepare($query);
    
    // Bind parameters con tipos específicos
    foreach ($params as $key => $value) {
        if ($key === 'limit' || $key === 'offset') {
            $stmt->bindValue(":$key", $value, PDO::PARAM_INT);
        } else {
            $stmt->bindValue(":$key", $value);
        }
    }
    
    $stmt->execute();
    $facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Formatear los datos
    foreach ($facturas as &$factura) {
        $factura['fecha_emision_formatted'] = date('d/m/Y H:i', strtotime($factura['fecha_emision']));
        $factura['total_formatted'] = '$' . number_format($factura['total'], 2);
        $factura['folio_completo'] = ($factura['serie_interno'] ? $factura['serie_interno'] . '-' : '') . $factura['folio_interno'];
        
        // Determinar si tiene archivos disponibles (verificar si la ruta no está vacía)
        $factura['tiene_pdf'] = !empty($factura['pdf_path']);
        $factura['tiene_xml'] = !empty($factura['xml_path']);
    }
    
    echo json_encode([
        'success' => true,
        'facturas' => $facturas,
        'paginacion' => [
            'pagina_actual' => $pagina,
            'total_paginas' => $total_paginas,
            'total_registros' => $total_registros,
            'por_pagina' => $por_pagina
        ]
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener facturas: ' . $e->getMessage()
    ]);
}
