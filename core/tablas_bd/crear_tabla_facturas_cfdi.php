<?php
require_once __DIR__ . '/../class/db.php';
header('Content-Type: text/html; charset=utf-8');

try {
    $db = (new Database())->getConnection();

    $sql = "CREATE TABLE IF NOT EXISTS `facturas` (
        `id_factura` INT(10) NOT NULL AUTO_INCREMENT,
        `id_ticket` INT(10) NOT NULL,
        `version` VARCHAR(5) NOT NULL,
        `uuid` VARCHAR(50) NOT NULL,
        `serie` VARCHAR(10) NOT NULL,
        `folio` VARCHAR(20) NOT NULL,
        `fecha_e` DATETIME NOT NULL,
        `form_pago` INT(3) NOT NULL,
        `no_cert` VARCHAR(30) NOT NULL,
        `subtotal` DECIMAL(15,2) NOT NULL,
        `moneda` VARCHAR(3) NOT NULL,
        `exportacion` VARCHAR(5) NOT NULL,
        `total` DECIMAL(15,2) NOT NULL,
        `tipo_de_compro` VARCHAR(5) NOT NULL,
        `met_pago` ENUM('PUE', 'PPD') NOT NULL,
        `lugar_exp` VARCHAR(5) NOT NULL,
        `sello` VARCHAR(150) NOT NULL,
        `tipo_cambio` DECIMAL(10,4) DEFAULT NULL,
        `rfc_emisor` VARCHAR(15) NOT NULL,
        `rfc_receptor` VARCHAR(15) NOT NULL,
        `uso_cfdi` VARCHAR(3) NOT NULL,
        `objeto_imp` VARCHAR(2) NOT NULL,
        `clave_p_s` INT(5) NOT NULL,
        `cantidad` DECIMAL(10,2) NOT NULL,
        `unidad` VARCHAR(5) NOT NULL,
        `valor_unit` DECIMAL(15,2) NOT NULL,
        `importe` DECIMAL(15,2) NOT NULL,
        `t_imp_trasladados` DECIMAL(15,2) NOT NULL,
        `timbre_fiscal` VARCHAR(100) NOT NULL,
        `fecha_timbrado` DATETIME NOT NULL,
        `xml_file` VARCHAR(255) NOT NULL,
        `pdf_file` VARCHAR(255) NOT NULL,
        `estatus` TINYINT(1) NOT NULL DEFAULT 1,
        PRIMARY KEY (`id_factura`),

        -- Foreign keys
        CONSTRAINT `fk_facturas_ticket`
            FOREIGN KEY (`id_ticket`)
            REFERENCES `tickets_sin_facturar` (`id_ticket`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,

        CONSTRAINT `fk_facturas_form_pago`
            FOREIGN KEY (`form_pago`)
            REFERENCES `cat_forma_pago` (`id_forma_pago`)
            ON DELETE RESTRICT
            ON UPDATE CASCADE,

        CONSTRAINT `fk_facturas_prod_serv`
            FOREIGN KEY (`clave_p_s`)
            REFERENCES `cat_prod_serv` (`id_prod_serv`)
            ON DELETE RESTRICT
            ON UPDATE CASCADE,

        CONSTRAINT `fk_facturas_emisor`
            FOREIGN KEY (`rfc_emisor`)
            REFERENCES `empresas` (`rfc`)
            ON DELETE RESTRICT
            ON UPDATE CASCADE,

        CONSTRAINT `fk_facturas_receptor`
            FOREIGN KEY (`rfc_receptor`)
            REFERENCES `datos_fiscales_usuario` (`rfc`)
            ON DELETE RESTRICT
            ON UPDATE CASCADE,

        CONSTRAINT `fk_facturas_uso_cfdi`
            FOREIGN KEY (`uso_cfdi`)
            REFERENCES `cat_uso_cfdi` (`codigo`)
            ON DELETE RESTRICT
            ON UPDATE CASCADE,

        CONSTRAINT `fk_facturas_lugar_exp`
            FOREIGN KEY (`lugar_exp`)
            REFERENCES `cat_codigo_postal` (`d_codigo`)
            ON DELETE RESTRICT
            ON UPDATE CASCADE
    ) ENGINE=InnoDB 
    DEFAULT CHARSET=utf8mb4 
    COLLATE=utf8mb4_unicode_ci;";

    $db->exec($sql);
    echo " Tabla 'facturas' creada correctamente.";
} catch (PDOException $e) {
    echo " Error al crear la tabla 'facturas': " . $e->getMessage();
}
?>
