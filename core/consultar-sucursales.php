<?php
// core/consultar-sucursales.php

session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';

header('Content-Type: application/json; charset=utf-8');

$respuesta = [
    'success' => false,
    'data'    => [],
    'message' => 'Error desconocido.'
];

try {
    $id_usuario = null;
    if (isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id'])) {
        $id_usuario = (int)$_SESSION['usuario_id'];
    } elseif (isset($_SESSION['USR_ID']) && !empty($_SESSION['USR_ID'])) {
        $id_usuario = (int)$_SESSION['USR_ID'];
    }
    
    if (!$id_usuario) {
        throw new Exception('Sesión de usuario no válida. ID de usuario no encontrado en la sesión.');
    }

    $db = new Database();
    $conn = $db->getConnection();
    
    $stmt = $conn->prepare("SELECT id_empresa, razon_social, codigo_suc, rfc, reg_fiscal, cp, calle, num_ext, num_int, colonia, estatus, csf, sello FROM empresas WHERE id_usuario = ? ORDER BY razon_social ASC");
    $stmt->execute([$id_usuario]);
    $sucursales = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt_regimenes = $conn->query("SELECT codigo, descr FROM cat_regimen_fiscal");
    $regimenes_map = $stmt_regimenes->fetchAll(PDO::FETCH_KEY_PAIR); 

    $respuesta['success'] = true;
    $respuesta['data'] = $sucursales;
    $respuesta['regimenes'] = $regimenes_map;
    
} catch (Exception $e) {
    $respuesta['message'] = $e->getMessage();
}

echo json_encode($respuesta);
?>