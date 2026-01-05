<?php
/**
 * Consultar Estatus SAT
 * Ubicación: core/consultar-estatus-sat.php
 */

require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/autoload-vendor.php';
require_once __DIR__ . '/../api/FinkokApi.php';
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');

try {
    // 1. Obtener ID de la factura (vía POST o GET)
    // Se asume que recibes un JSON o $_POST
    $input = json_decode(file_get_contents('php://input'), true);
    $id_factura = $input['id_factura'] ?? $_GET['id_factura'] ?? null;

    if (!$id_factura) {
        throw new Exception("ID de factura no proporcionado");
    }

    // 2. Conectar a BD
    $db = new Database();
    $conn = $db->getConnection();

    // 3. Obtener datos necesarios: UUID, RFC Emisor, RFC Receptor, Total
    // Ajusta la consulta a los nombres reales de tus tablas
    $sql = "SELECT 
                f.uuid, 
                f.total, 
                e.rfc as rfc_emisor, 
                c.rfc as rfc_receptor
            FROM facturas f
            INNER JOIN empresas e ON f.id_empresa = e.id_empresa
            INNER JOIN clientes c ON f.id_cliente = c.id_cliente
            WHERE f.id_factura = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_factura]);
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$factura) {
        throw new Exception("Factura no encontrada");
    }

    if (empty($factura['uuid'])) {
        throw new Exception("La factura no tiene UUID (no está timbrada)");
    }

    // 4. Configurar Finkok
    $finkokUser = 'michellflores822@gmail.com'; 
    $finkokPass = 'Pankycontra2025.'; 
    $enProduccion = false; // Cambiar según tu entorno

    $api = new FinkokApi($finkokUser, $finkokPass, $enProduccion);

    // 5. Formatear Total (Finkok es estricto con esto)
    // Debe ser string con decimales exactos. Ej: 116.00
    $totalFormateado = number_format((float)$factura['total'], 2, '.', '');

    // 6. Consultar
    $resultado = $api->consultarEstatusSat(
        $factura['rfc_emisor'],
        $factura['rfc_receptor'],
        $factura['uuid'],
        $totalFormateado
    );

    // 7. Actualizar BD si es necesario
    if ($resultado['success']) {
        // Opcional: Actualizar el estado local si cambió en el SAT
        $nuevoEstatus = $resultado['sat_estado']; // Vigente o Cancelado
        
        if ($nuevoEstatus === 'Cancelado') {
            $stmtUpd = $conn->prepare("UPDATE facturas SET estatus = 'cancelada' WHERE id_factura = ?");
            $stmtUpd->execute([$id_factura]);
        }
    }

    echo json_encode($resultado);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>