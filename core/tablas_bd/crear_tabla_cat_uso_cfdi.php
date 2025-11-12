<?php
//tabla catalogo uso cfdi 
require_once __DIR__ . '/../class/db.php';

$db = new Database();
$conn = $db->getConnection();
$sql = "CREATE TABLE IF NOT EXISTS `cat_uso_cfdi` (
    `id_uso_cfdi` INT(5) PRIMARY KEY,
    `codigo` VARCHAR(3) NOT NULL,
    `descr` VARCHAR(75) NOT NULL,
    `p_fisica` TINYINT(1) NOT NULL,
    `p_moral` TINYINT(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

try {
    $conn->exec($sql);
    echo "Tabla cat_uso_cfdi creada correctamente.";
} catch (PDOException $e) {
    echo "Error al crear la tabla cat_uso_cfdi: " . $e->getMessage();
}
$conn = null;