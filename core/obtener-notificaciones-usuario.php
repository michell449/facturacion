<?php
session_start();
require_once __DIR__ . '/autoload-vendor.php';
require_once __DIR__ . '/auth.php';

header('Content-Type: application/json');

try {
    // Verificar sesión del usuario (cliente)
    if (empty($_SESSION['loggedin'])) {
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        exit;
    }

    // Obtener id de usuario desde múltiples claves posibles
    $idUsuario = $_SESSION['id_usuario']
        ?? $_SESSION['usuario_id']
        ?? $_SESSION['USR_ID']
        ?? null;

    if (!$idUsuario) {
        echo json_encode(['success' => false, 'message' => 'Usuario no identificado']);
        exit;
    }

    $pdo = new PDO("mysql:host=localhost;dbname=facturacion;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Últimos 30 días de respuestas
        $sqlItems = "
                SELECT s.id_solicitud, s.id_factura, s.estado, s.motivo, s.respuesta_admin,
                             s.fecha_respuesta,
                             f.serie_interno, f.folio_interno, f.total,
                             DATE_FORMAT(s.fecha_respuesta, '%d/%m/%Y %H:%i') AS fecha_respuesta_formatted
                FROM solicitudes_cancelacion s
                INNER JOIN facturas f ON f.id_factura = s.id_factura
                WHERE s.id_usuario = :idUsuario
                    AND s.estado IN ('aprobada','rechazada')
                    AND s.fecha_respuesta IS NOT NULL
                    AND s.fecha_respuesta >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                ORDER BY s.fecha_respuesta DESC
                LIMIT 5
        ";

    $stmt = $pdo->prepare($sqlItems);
    $stmt->execute([':idUsuario' => $idUsuario]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Contador total (últimos 30 días)
    $sqlCount = "
        SELECT COUNT(*) AS total
        FROM solicitudes_cancelacion s
        WHERE s.id_usuario = :idUsuario
          AND s.estado IN ('aprobada','rechazada')
          AND s.fecha_respuesta IS NOT NULL
          AND s.fecha_respuesta >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    ";

    $stmtCount = $pdo->prepare($sqlCount);
    $stmtCount->execute([':idUsuario' => $idUsuario]);
    $total = (int)$stmtCount->fetchColumn();

    echo json_encode([
        'success' => true,
        'total' => $total,
        'items' => array_map(function ($row) {
            return [
                'id_solicitud' => (int)$row['id_solicitud'],
                'id_factura' => (int)$row['id_factura'],
                'estado' => $row['estado'],
                'motivo' => $row['motivo'],
                'respuesta_admin' => $row['respuesta_admin'],
                'fecha_respuesta' => $row['fecha_respuesta_formatted'],
                'folio' => (($row['serie_interno'] ?? '') ? ($row['serie_interno'] . '-') : '') . ($row['folio_interno'] ?? ''),
                'total' => number_format((float)$row['total'], 2),
                'moneda' => 'MXN',
            ];
        }, $items)
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor', 'error' => $e->getMessage()]);
}
