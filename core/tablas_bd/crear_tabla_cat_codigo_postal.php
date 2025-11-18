<?php
require_once __DIR__ . '/../class/db.php';
$db = new Database();
$pdo = $db->getConnection();

$sql = "CREATE TABLE IF NOT EXISTS `cat_codigo_postal` (
    `id_cp` INT(5) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `d_codigo` VARCHAR(5) NOT NULL,
    `d_asenta` VARCHAR(100) NOT NULL,
    `tipo_asenta` VARCHAR(25) NOT NULL,
    `d_mnpio` VARCHAR(50) NOT NULL,
    `d_estado` VARCHAR(31) NOT NULL,
    `d_ciudad` VARCHAR(50) NOT NULL,
    `c_oficina` VARCHAR(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

try {
    $pdo->exec($sql);
} catch (PDOException $e) {
    echo "Error al crear la tabla cat_codigo_postal: " . $e->getMessage();
}   
$alterSql = "ALTER TABLE cat_codigo_postal MODIFY COLUMN d_ciudad VARCHAR(50) null;";