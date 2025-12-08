-- Tabla para almacenar las facturas generadas
CREATE TABLE IF NOT EXISTS facturas (
    id_factura INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_sucursal INT NOT NULL,
    id_ticket INT DEFAULT NULL,
    
    -- Datos del comprobante
    folio VARCHAR(20) NOT NULL,
    serie VARCHAR(10) NOT NULL,
    uuid VARCHAR(100) DEFAULT NULL,
    fecha_emision DATETIME NOT NULL,
    fecha_timbrado DATETIME DEFAULT NULL,
    
    -- Datos del receptor
    receptor_rfc VARCHAR(13) NOT NULL,
    receptor_nombre VARCHAR(255) NOT NULL,
    receptor_cp VARCHAR(5) NOT NULL,
    receptor_domicilio TEXT,
    receptor_correo VARCHAR(255),
    receptor_regimen VARCHAR(10),
    uso_cfdi VARCHAR(10) NOT NULL,
    
    -- Condiciones de pago
    forma_pago VARCHAR(10) NOT NULL,
    metodo_pago VARCHAR(10) NOT NULL,
    moneda VARCHAR(3) DEFAULT 'MXN',
    
    -- Totales
    subtotal DECIMAL(15,2) NOT NULL,
    iva DECIMAL(15,2) NOT NULL,
    total DECIMAL(15,2) NOT NULL,
    
    -- Datos adicionales
    observaciones TEXT,
    estatus VARCHAR(20) DEFAULT 'pendiente', -- pendiente, timbrada, cancelada
    
    -- Archivos
    xml_path VARCHAR(500),
    pdf_path VARCHAR(500),
    
    -- Sellos digitales
    sello_cfdi TEXT,
    sello_sat TEXT,
    cadena_original TEXT,
    no_certificado_sat VARCHAR(50),
    no_certificado_emisor VARCHAR(50),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_usuario (id_usuario),
    INDEX idx_sucursal (id_sucursal),
    INDEX idx_ticket (id_ticket),
    INDEX idx_folio (folio),
    INDEX idx_receptor_rfc (receptor_rfc),
    INDEX idx_estatus (estatus),
    INDEX idx_fecha (fecha_emision)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla para almacenar los conceptos de cada factura
CREATE TABLE IF NOT EXISTS factura_conceptos (
    id_concepto INT AUTO_INCREMENT PRIMARY KEY,
    id_factura INT NOT NULL,
    
    -- Datos del concepto
    descripcion TEXT NOT NULL,
    cantidad DECIMAL(15,4) NOT NULL,
    precio_unitario DECIMAL(15,2) NOT NULL,
    importe DECIMAL(15,2) NOT NULL,
    descuento DECIMAL(15,2) DEFAULT 0,
    
    -- Claves SAT
    clave_producto VARCHAR(20) DEFAULT '01010101',
    clave_unidad VARCHAR(10) DEFAULT 'H87',
    unidad_descripcion VARCHAR(50),
    
    -- Impuestos
    iva_tasa DECIMAL(5,4) DEFAULT 0.16,
    iva_importe DECIMAL(15,2) DEFAULT 0,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_factura) REFERENCES facturas(id_factura) ON DELETE CASCADE,
    INDEX idx_factura (id_factura)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Agregar campo id_factura a la tabla tickets si no existe
ALTER TABLE tickets 
ADD COLUMN IF NOT EXISTS id_factura INT DEFAULT NULL AFTER estatus,
ADD INDEX IF NOT EXISTS idx_factura (id_factura);

-- Agregar campo folioActual a config_facturas si no existe
ALTER TABLE config_facturas 
ADD COLUMN IF NOT EXISTS folioActual INT DEFAULT 0 AFTER serieFactura;

-- Insertar datos de prueba (opcional)
-- INSERT INTO facturas (id_usuario, id_sucursal, folio, serie, fecha_emision, receptor_rfc, receptor_nombre, receptor_cp, uso_cfdi, forma_pago, metodo_pago, subtotal, iva, total) 
-- VALUES (1, 1, 'A0001', 'A', NOW(), 'XAXX010101000', 'Público General', '12345', 'G03', '01', 'PUE', 1000.00, 160.00, 1160.00);
