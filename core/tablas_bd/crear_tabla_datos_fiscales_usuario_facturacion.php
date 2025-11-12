<?php
require_once __DIR__ . '/../class/db.php';
$db = new Database();
$conn = $db->getConnection();

$sql = "CREATE TABLE IF NOT EXISTS `datos_fiscales_usuario` (
    `id_df` INT(5) NOT NULL AUTO_INCREMENT,
    `id_usuario` INT(8) NOT NULL,
    `rfc` VARCHAR(15) NOT NULL,
    `razon_social` VARCHAR(254) NOT NULL,
    `reg_fiscal` VARCHAR(5) NOT NULL,
    `cp` VARCHAR(5) NOT NULL,
    `tipo_pers` ENUM('Fisica', 'Moral') NOT NULL,
    PRIMARY KEY (`id_df`),
    INDEX `fk_datos_fiscales_usuario_usuarios_idx` (`id_usuario`),
    CONSTRAINT `fk_datos_fiscales_usuario_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios`(`id_usuario`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    INDEX `fk_datos_fiscales_usuario_codigo_postal_idx` (`cp`),
    CONSTRAINT `fk_datos_fiscales_usuario_codigo_postal` FOREIGN KEY (`cp`) REFERENCES `cat_codigo_postal`(`d_codigo`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    INDEX `fk_datos_fiscales_usuario_regimen_fiscal_idx` (`reg_fiscal`),
    CONSTRAINT `fk_datos_fiscales_usuario_regimen_fiscal` FOREIGN KEY (`reg_fiscal`) REFERENCES `cat_regimen_fiscal`(`codigo`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);";

try {
    $conn->exec($sql);
    echo "Tabla datos_fiscales_usuario creada correctamente.";
} catch (PDOException $e) {
    echo "Error al crear la tabla datos_fiscales_usuario: " . $e->getMessage();
}
$conn = null;
