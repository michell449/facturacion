<?php
//crear tabla de uso de cfdi con regimen fiscal

require_once __DIR__ . '/../class/db.php';
$db = new Database();
$conn = $db->getConnection();

$sql = "CREATE TABLE IF NOT EXISTS `uso_regimen` (
`id_uso_regimen` INT(5) NOT NULL AUTO_INCREMENT,
    `id_uso_cfdi` INT(5) NOT NULL,
    `id_regimen` INT(5) NOT NULL,
    `tipo_p` ENUM('fisica', 'moral') NOT NULL,
    PRIMARY KEY (`id_uso_regimen`) USING BTREE,
    INDEX `fk_uso_regimen_uso_cfdi_idx` (`id_uso_cfdi` ASC) VISIBLE,
    CONSTRAINT `fk_uso_regimen_uso_cfdi` FOREIGN KEY (`id_uso_cfdi`)
    REFERENCES `cat_uso_cfdi` (`id_uso_cfdi`) ON DELETE CASCADE
    ON UPDATE CASCADE,
    INDEX `fk_uso_regimen_regimen_fiscal_idx` (`id_regimen` ASC) VISIBLE,
    CONSTRAINT `fk_uso_regimen_regimen_fiscal` FOREIGN KEY (`id_regimen`)
    REFERENCES `regimen_fiscal` (`id_regimen`) ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

try {
    $conn->exec($sql);
    echo "Tabla uso_regimen creada correctamente.";
} catch (PDOException $e) {
    echo "Error al crear la tabla uso_regimen: " . $e->getMessage();
}
$conn = null;
