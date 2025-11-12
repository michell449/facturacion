<?php
require_once __DIR__ . '/../class/db.php';
$db = new Database();
$conn = $db->getConnection();

$sql = "CREATE TABLE IF NOT EXISTS `ticket_detalle` (
    `id_ticket` INT(10) NOT NULL,
    `folio` VARCHAR(10) NOT NULL,
    `id_prod_serv` INT(5) NOT NULL,
    `descr` VARCHAR(80) NOT NULL,
    `cant` FLOAT(4,2) NOT NULL,
    `precio_unit` DECIMAL(10,2) NOT NULL,
    `importe` DECIMAL(15,2) NOT NULL,
    `imp_1` DECIMAL(15,2) NOT NULL,
    `imp_2` DECIMAL(15,2) NOT NULL, 
    `imp_3` DECIMAL(15,2) NOT NULL,
    PRIMARY KEY (`id_ticket`) USING BTREE,
    INDEX `fk_ticket_detalle_ticket_idx` (`id_ticket`),
    CONSTRAINT `fk_ticket_detalle_ticket`
    FOREIGN KEY (`id_ticket`)
    REFERENCES `tickets_sin_facturar` (`id_ticket`) ON DELETE CASCADE
    ON UPDATE CASCADE
) COLLATE='utf8mb4_general_ci' ENGINE=InnoDB;";
try {
    $conn->exec($sql);
    echo "Tabla 'ticket_detalle' creada correctamente.";
} catch (PDOException $e) {
    echo "Error al crear la tabla: " . $e->getMessage();
}