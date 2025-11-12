<?php
require_once __DIR__ . '/../class/db.php';
$db = new Database();
$conn = $db->getConnection();

$sql = "CREATE TABLE IF NOT EXISTS `empresas` (
    `id_empresa` INT(5) NOT NULL AUTO_INCREMENT,
    `id_usuario` INT(8) NOT NULL,
    `razon_social` VARCHAR(254) NOT NULL,
    `clave_suc` VARCHAR(5) NOT NULL,
    `rfc` VARCHAR(15) NOT NULL,
    `calle` VARCHAR(254) NOT NULL,
    `cp` VARCHAR(5) NOT NULL,
    `num_ext` VARCHAR(4) NOT NULL,
    `num_int` VARCHAR(4) DEFAULT NULL,
    `reg_fiscal` VARCHAR(5) NOT NULL,
    `fecha_vig` DATE NOT NULL,
    `estatus` TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`id_empresa`),
    INDEX `fk_empresas_usuarios_idx` (`id_usuario`),
    CONSTRAINT `fk_empresas_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios`(`id_usuario`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    INDEX `fk_codigo_postal_idx` (`cp`),
    CONSTRAINT `fk_codigo_postal` FOREIGN KEY (`cp`) REFERENCES `cat_codigo_postal`(`d_codigo`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    INDEX `fk_regimen_fiscal_idx` (`reg_fiscal`),
    CONSTRAINT `fk_regimen_fiscal` FOREIGN KEY (`reg_fiscal`) REFERENCES `cat_regimen_fiscal`(`codigo`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

try {
    $conn->exec($sql);
    echo "Tabla 'empresas' creada correctamente.";
} catch (PDOException $e) {
    echo "Error al crear la tabla empresas: " . $e->getMessage();
}

$conn = null;
