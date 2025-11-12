<?php

require_once __DIR__ . '/../class/db.php';
$db = new Database();
$conn = $db->getConnection();

$sql = "CREATE TABLE IF NOT EXISTS `cat_prod_serv` (
    `id_prod_serv` INT(5) PRIMARY KEY AUTO_INCREMENT,
    `clave` VARCHAR(10) NOT NULL,
    `descr` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

try {
    $conn->exec($sql);
    echo "Tabla cat_prod_serv creada correctamente.";
} catch (PDOException $e) {
    echo "Error al crear la tabla cat_prod_serv: " . $e->getMessage();
}
$conn = null;