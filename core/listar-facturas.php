<?php
// core/listar-facturas.php
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once __DIR__ . '/class/db.php';

try {
    // Validar sesión
    $id_usuario = $_SESSION['usuario_id'] ?? $_SESSION['USR_ID'] ?? null;
    if (!$id_usuario) {
        throw new Exception('Sesión no válida o expirada.');
    }

    $db = new Database();
    $conn = $db->getConnection();

    // Obtener parámetros de filtro
    $busqueda = $_GET['busqueda'] ?? '';
    $sucursal = $_GET['sucursal'] ?? '';
    $estado = $_GET['estado'] ?? '';
    $fechaDesde = $_GET['fecha_desde'] ?? '';
    $fechaHasta = $_GET['fecha_hasta'] ?? '';
    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $porPagina = 10;
    $offset = ($pagina - 1) * $porPagina;

    // Construir consulta con filtros
    $sql = "SELECT 
                f.id_factura,
                f.folio_interno,
                f.serie_interno,
                f.fecha_emision,
                f.fecha_timbrado,
                f.estatus,
                f.rfc_receptor,
                f.razon_social_receptor,
                f.total,
                f.uuid,
                f.xml_path,
                f.pdf_path,
                e.nombre as sucursal,
                e.id_empresa
            FROM facturas f
            INNER JOIN empresas e ON f.id_empresa = e.id_empresa
            WHERE f.id_usuario = ?";
    
    $params = [$id_usuario];
    
    // Aplicar filtros
    if (!empty($busqueda)) {
        $sql .= " AND (f.folio_interno LIKE ? OR f.serie_interno LIKE ? OR f.rfc_receptor LIKE ? OR f.razon_social_receptor LIKE ?)";
        $likeBusqueda = "%{$busqueda}%";
        $params[] = $likeBusqueda;
        $params[] = $likeBusqueda;
        $params[] = $likeBusqueda;
        $params[] = $likeBusqueda;
    }
    
    if (!empty($sucursal)) {
        $sql .= " AND f.id_empresa = ?";
        $params[] = $sucursal;
    }
    
    if (!empty($estado)) {
        $sql .= " AND f.estatus = ?";
        $params[] = $estado;
    }
    
    if (!empty($fechaDesde)) {
        $sql .= " AND DATE(f.fecha_emision) >= ?";
        $params[] = $fechaDesde;
    }
    
    if (!empty($fechaHasta)) {
        $sql .= " AND DATE(f.fecha_emision) <= ?";
        $params[] = $fechaHasta;
    }
    
    // Ordenar por fecha más reciente
    $sql .= " ORDER BY f.fecha_emision DESC LIMIT ? OFFSET ?";
    $params[] = $porPagina;
    $params[] = $offset;
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Obtener total de registros para paginación
    $sqlCount = "SELECT COUNT(*) as total FROM facturas f WHERE f.id_usuario = ?";
    $paramsCount = [$id_usuario];
    
    if (!empty($busqueda)) {
        $sqlCount .= " AND (f.folio_interno LIKE ? OR f.serie_interno LIKE ? OR f.rfc_receptor LIKE ? OR f.razon_social_receptor LIKE ?)";
        $paramsCount[] = $likeBusqueda;
        $paramsCount[] = $likeBusqueda;
        $paramsCount[] = $likeBusqueda;
        $paramsCount[] = $likeBusqueda;
    }
    if (!empty($sucursal)) {
        $sqlCount .= " AND f.id_empresa = ?";
        $paramsCount[] = $sucursal;
    }
    if (!empty($estado)) {
        $sqlCount .= " AND f.estatus = ?";
        $paramsCount[] = $estado;
    }
    if (!empty($fechaDesde)) {
        $sqlCount .= " AND DATE(f.fecha_emision) >= ?";
        $paramsCount[] = $fechaDesde;
    }
    if (!empty($fechaHasta)) {
        $sqlCount .= " AND DATE(f.fecha_emision) <= ?";
        $paramsCount[] = $fechaHasta;
    }
    
    $stmtCount = $conn->prepare($sqlCount);
    $stmtCount->execute($paramsCount);
    $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
    
    echo json_encode([
        'success' => true,
        'facturas' => $facturas,
        'total' => (int)$total,
        'pagina' => $pagina,
        'porPagina' => $porPagina,
        'totalPaginas' => ceil($total / $porPagina)
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
