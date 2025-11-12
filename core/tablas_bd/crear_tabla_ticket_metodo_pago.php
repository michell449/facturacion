<?php
require_once __DIR__ . '/../class/db.php';
$db = new Database();
$conn = $db->getConnection();
$sql = "CREATE TABLE IF NOT EXISTS `ticket_metodo_pago` (
    `id_ticket` INT(10) NOT NULL,
    `metodo_pago` ENUM('PUE', 'PPD') NOT NULL,
    `forma_pago` VARCHAR(3) NOT NULL,
    `monto` DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (`id_ticket`) USING BTREE,
    INDEX `fk_ticket_metodo_pago_ticket_idx` (`id_ticket`),
    CONSTRAINT `fk_ticket_metodo_pago_ticket`
    FOREIGN KEY (`id_ticket`)
    REFERENCES `tickets_sin_facturar` (`id_ticket`) ON DELETE CASCADE
    ON UPDATE CASCADE,
    INDEX `fk_ticket_forma_pago_idx` (`forma_pago`), 
    CONSTRAINT `fk_ticket_forma_pago` FOREIGN KEY (`forma_pago`) REFERENCES `cat_forma_pago`(`clave`) ON DELETE RESTRICT ON UPDATE CASCADE
) COLLATE='utf8mb4_general_ci' ENGINE=InnoDB;";
try {
    $conn->exec($sql);
    echo "Tabla 'ticket_metodo_pago' creada correctamente.";
} catch (PDOException $e) {
    echo "Error al crear la tabla: " . $e->getMessage();
}
$conn = null;