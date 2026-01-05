-- ============================================
-- TABLAS INVOLUCRADAS EN FACTURACIÓN AUTOMÁTICA
-- ============================================

-- 1. DATOS FISCALES DEL USUARIO (EMISOR)
-- ============================================
-- Tabla que se consulta para obtener información del usuario que factura
-- Se obtiene con: obtener-datos-fiscales-usuario.php

DESCRIBE datos_fiscales_usuario;

-- Campos relevantes:
-- - id_usuario: ID del usuario logueado
-- - rfc: RFC del usuario (emisor de la factura)
-- - razon_social: Nombre de la empresa
-- - reg_fiscal: Régimen fiscal (ej: 601, 605, etc)
-- - cp: Código postal
-- - tipo_pers: Física o Moral
-- - calle, num_ext, num_int, col: Domicilio


-- 2. TICKETS (DATOS DEL CLIENTE - RECEPTOR)
-- ============================================
-- Tabla que contiene los tickets a facturar
-- Se obtiene desde sessionStorage en JavaScript

DESCRIBE tickets;

-- Campos utilizados:
-- - id_ticket: ID del ticket
-- - id_empresa: Sucursal/empresa
-- - folio: Número de folio del ticket
-- - fecha_venta: Fecha del ticket
-- - subtotal, impuesto, total: Montos

-- Relaciones:
-- - ticket -> detalles_tickets (detalles del ticket)
-- - ticket -> forma_pago (métodos de pago)


-- 3. DETALLES DEL TICKET (CONCEPTOS)
-- ============================================
-- Contiene los productos/servicios del ticket

DESCRIBE detalles_tickets;

-- Campos utilizados:
-- - id_ticket: Referencia al ticket
-- - descripcion: Nombre del producto
-- - cantidad: Cantidad
-- - precio_unitario: Precio por unidad
-- - importe: Subtotal (cantidad * precio)


-- 4. FORMA DE PAGO (RECEPTOR)
-- ============================================
-- Métodos de pago registrados en el ticket

-- Campos clave:
-- - forma_pago: Código CFDI (01=Efectivo, 03=Transferencia, etc)
-- - metodo_pago: Tipo (PUE=Una exhibición, PPD=Diferido)


-- 5. FACTURAS (CREADAS AUTOMÁTICAMENTE)
-- ============================================
-- Se crea automáticamente al generar factura

DESCRIBE facturas;

-- Campos que se populan:
-- - id_ticket: Referencia del ticket
-- - id_usuario: Usuario que factura
-- - id_empresa: Sucursal
-- - folio_interno: Folio generado
-- - rfc_receptor: RFC del usuario (de datos_fiscales_usuario)
-- - razon_social_receptor: Razón social del usuario
-- - regimen_fiscal_receptor: Régimen del usuario
-- - subtotal, impuestos_trasladados, total
-- - forma_pago, metodo_pago
-- - xml_path: Ruta del archivo XML generado
-- - uuid_timbrado: UUID de Finkok
-- - estatus: pendiente, timbrado, cancelado


-- 6. FACTURAS DETALLES
-- ============================================
-- Detalles de cada factura (extraídos del ticket)

DESCRIBE facturas_detalles;

-- Campos:
-- - id_factura: Referencia a facturas
-- - descripcion: Concepto
-- - cantidad: Cantidad vendida
-- - valor_unitario: Precio unitario
-- - importe: Total del concepto
-- - impuesto_importe: IVA calculado


-- 7. CONFIG FACTURAS
-- ============================================
-- Configuración de facturación por sucursal

DESCRIBE config_facturas;

-- Campos relevantes:
-- - id_usuario, id_sucursal: Identificadores únicos
-- - folio_actual: Siguiente folio a usar
-- - serie_factura: Serie CFDI (ej: A, B, C)
-- - Se actualiza automáticamente cada factura


-- ============================================
-- CONSULTAS ÚTILES PARA DEBUGGING
-- ============================================

-- Verificar datos fiscales de un usuario
SELECT * FROM datos_fiscales_usuario 
WHERE id_usuario = 123;

-- Verificar configuración de facturación
SELECT * FROM config_facturas 
WHERE id_usuario = 123 AND id_sucursal = 1;

-- Ver últimas facturas generadas
SELECT id_factura, folio_interno, rfc_receptor, estatus, uuid_timbrado
FROM facturas
WHERE id_usuario = 123
ORDER BY id_factura DESC
LIMIT 10;

-- Ver detalles de una factura específica
SELECT fd.*, f.folio_interno, f.rfc_receptor
FROM facturas_detalles fd
JOIN facturas f ON fd.id_factura = f.id_factura
WHERE f.id_factura = 999;

-- Verificar estado de timbrado
SELECT id_factura, folio_interno, estatus, uuid_timbrado, xml_path
FROM facturas
WHERE id_usuario = 123 AND estatus = 'timbrado';


-- ============================================
-- FLOW DE DATOS
-- ============================================

/*
1. Usuario hace clic en "Generar Factura" en detalle-ticket.inc.php
   ↓
2. JavaScript carga datos de:
   - sessionStorage (ticket actual)
   - obtener-datos-fiscales-usuario.php (datos del usuario)
   ↓
3. Se envía JSON a generar-factura.php con:
   {
     id_ticket: (del ticket)
     id_sucursal: (del ticket)
     receptor: {
       rfc: (de datos_fiscales_usuario)
       nombre: (de datos_fiscales_usuario)
       regimen: (de datos_fiscales_usuario)
       cp: (de datos_fiscales_usuario)
       uso_cfdi: G01 (default)
     }
     conceptos: (de detalles del ticket)
   }
   ↓
4. generar-factura.php:
   - Inserta en tabla FACTURAS
   - Inserta en tabla FACTURAS_DETALLES
   - Actualiza folio en CONFIG_FACTURAS
   ↓
5. generar-xml.php:
   - Lee datos de FACTURAS
   - Genera XML con estructura CFDI 4.0
   - Sella con CSD (certificados digitales)
   ↓
6. timbrar-xml.php:
   - Envía XML a Finkok (PAC)
   - Recibe XML timbrado con UUID
   - Actualiza FACTURAS con uuid_timbrado
   ↓
7. Factura completada y timbrada ✓
*/

-- ============================================
-- CAMPOS DE REFERENCIA CRUZADA
-- ============================================

-- De TICKET a DATOS_FISCALES_USUARIO:
-- ticket.id_usuario → datos_fiscales_usuario.id_usuario

-- De TICKET a FACTURAS:
-- ticket.id_ticket → facturas.id_ticket

-- De FACTURAS a FACTURAS_DETALLES:
-- facturas.id_factura ← facturas_detalles.id_factura

-- De FACTURAS a CONFIG_FACTURAS:
-- facturas.id_usuario = config_facturas.id_usuario
-- facturas.id_empresa = config_facturas.id_sucursal
