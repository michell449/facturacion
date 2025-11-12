<?php
require_once __DIR__ . '/../class/db.php';
$db = new Database();
$conn = $db->getConnection();


$sql = "CREATE TABLE IF NOT EXISTS `tickets_sin_facturar` (
    `id_ticket` INT(10) NOT NULL AUTO_INCREMENT,
    `id_empresa` INT(5) NOT NULL,
    `folio_ticket` VARCHAR(10) NOT NULL,
    `fecha_venta` DATE NOT NULL,
    `importe_t` DECIMAL(15,2) NOT NULL,
    `subtotal` DECIMAL(15,2) NOT NULL,
    `impuesto_t` DECIMAL(15,2) NOT NULL,
    `estatus` TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id_ticket`) USING BTREE,
    INDEX `fk_tickets_sin_facturar_empresas_idx` (`id_empresa`),
    CONSTRAINT `fk_tickets_sin_facturar_empresas` FOREIGN KEY (`id_empresa`) REFERENCES `empresas`(`id_empresa`) ON DELETE CASCADE ON UPDATE CASCADE
) COLLATE='utf8mb4_general_ci' ENGINE=InnoDB;";

try {
    $conn->exec($sql);
    echo "Tabla 'tickets_sin_facturar' creada correctamente.";
} catch (PDOException $e) {
    echo "Error al crear la tabla: " . $e->getMessage();
}