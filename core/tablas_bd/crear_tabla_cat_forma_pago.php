<?php

require_once __DIR__ . '/../class/db.php';
$db = new Database();
$conn = $db->getConnection();

$sql = "CREATE TABLE IF NOT EXISTS `cat_forma_pago` (
    `id_forma_pago` INT(3) PRIMARY KEY AUTO_INCREMENT,
    `clave` VARCHAR(3) NOT NULL,
    `concepto` VARCHAR(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

try {
    $conn->exec($sql);
    echo "Tabla cat_forma_pago creada correctamente.";
} catch (PDOException $e) {
    echo "Error al crear la tabla cat_forma_pago: " . $e->getMessage();
}
$conn = null;