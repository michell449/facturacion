<?php
session_start();
header('Content-Type: application/json');

try {
    $idUsuario = $_SESSION['id_usuario'] ?? $_SESSION['usuario_id'] ?? $_SESSION['USR_ID'] ?? null;
    $tipoUsuario = $_SESSION['tipo_usuario'] ?? null;
    $loggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

    if (!$loggedIn || !$idUsuario || $tipoUsuario !== 'cliente') {
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        exit;
    }

    $pdo = new PDO('mysql:host=localhost;dbname=facturacion;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Stats
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM facturas WHERE id_usuario = :id');
    $stmt->execute([':id' => $idUsuario]);
    $totalFacturas = (int)$stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COALESCE(SUM(total),0) FROM facturas WHERE id_usuario = :id AND estatus = 'timbrada' AND YEAR(fecha_emision) = YEAR(CURDATE()) AND MONTH(fecha_emision) = MONTH(CURDATE())");
    $stmt->execute([':id' => $idUsuario]);
    $montoMes = (float)$stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM facturas WHERE id_usuario = :id AND estatus = 'pendiente'");
    $stmt->execute([':id' => $idUsuario]);
    $pendientes = (int)$stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM facturas WHERE id_usuario = :id AND estatus = 'cancelada'");
    $stmt->execute([':id' => $idUsuario]);
    $canceladas = (int)$stmt->fetchColumn();

    // Datos fiscales
    $stmt = $pdo->prepare('SELECT rfc, razon_social, reg_fiscal, cp, tipo_pers, calle, num_ext, num_int, col FROM datos_fiscales_usuario WHERE id_usuario = :id LIMIT 1');
    $stmt->execute([':id' => $idUsuario]);
    $df = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

    // Últimas facturas
    $stmt = $pdo->prepare("SELECT id_factura, serie_interno, folio_interno, fecha_emision, total, estatus FROM facturas WHERE id_usuario = :id ORDER BY fecha_emision DESC LIMIT 5");
    $stmt->execute([':id' => $idUsuario]);
    $recientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($recientes as &$r) {
        $r['folio'] = ($r['serie_interno'] ? $r['serie_interno'] . '-' : '') . $r['folio_interno'];
        $r['fecha'] = date('d/m/Y H:i', strtotime($r['fecha_emision']));
        $r['total_formatted'] = '$' . number_format((float)$r['total'], 2);
        unset($r['serie_interno'], $r['folio_interno']);
    }

    // Respuestas de cancelación recientes (si existen)
    $stmt = $pdo->prepare("SELECT s.estado, s.respuesta_admin, s.fecha_respuesta, f.serie_interno, f.folio_interno FROM solicitudes_cancelacion s INNER JOIN facturas f ON f.id_factura = s.id_factura WHERE s.id_usuario = :id AND s.fecha_respuesta IS NOT NULL ORDER BY s.fecha_respuesta DESC LIMIT 5");
    $stmt->execute([':id' => $idUsuario]);
    $respuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($respuestas as &$a) {
        $a['folio'] = ($a['serie_interno'] ? $a['serie_interno'] . '-' : '') . $a['folio_interno'];
        $a['fecha'] = date('d/m/Y H:i', strtotime($a['fecha_respuesta']));
        unset($a['serie_interno'], $a['folio_interno']);
    }

    echo json_encode([
        'success' => true,
        'user' => [
            'nombre' => $_SESSION['USR_NAME'] ?? $_SESSION['nombre'] ?? 'Usuario',
            'correo' => $_SESSION['correo'] ?? null,
        ],
        'fiscal' => $df,
        'stats' => [
            'total_facturas' => $totalFacturas,
            'monto_mes' => '$' . number_format($montoMes, 2),
            'pendientes' => $pendientes,
            'canceladas' => $canceladas
        ],
        'recientes' => $recientes,
        'respuestas' => $respuestas
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor', 'error' => $e->getMessage()]);
}
