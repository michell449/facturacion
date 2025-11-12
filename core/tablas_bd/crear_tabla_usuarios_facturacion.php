<?php
require_once __DIR__ . '/../class/db.php';

$db = new Database();
$conn = $db->getConnection();

$sql = "CREATE TABLE IF NOT EXISTS `usuarios` (
    `id_usuario` INT(8) NOT NULL AUTO_INCREMENT,
    `correo` VARCHAR(254) NOT NULL,
    `contrasena` VARCHAR(45) NOT NULL,
    `tipo_usuario` ENUM('admin', 'cliente') NOT NULL,
    `verificacion` TINYINT(1) NULL DEFAULT 0,
    `token` VARCHAR(7) NULL,
    `fecha_reg` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `tipo_cliente` ENUM('registrado', 'invitado') NOT NULL DEFAULT 'registrado',
    PRIMARY KEY (`id_usuario`) USING BTREE
) COLLATE='utf8mb4_general_ci' ENGINE=InnoDB;";

try {
    $conn->exec($sql);
    echo "Tabla 'usuarios_facturacion' creada correctamente.";
} catch (PDOException $e) {
    echo "Error al crear la tabla: " . $e->getMessage();
}
$conn = null;