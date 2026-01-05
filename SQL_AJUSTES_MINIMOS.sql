-- ============================================================================
-- AJUSTES MÍNIMOS PARA FACTURACIÓN DESDE TICKETS
-- Solo agrega lo que falta en tus tablas existentes
-- ============================================================================

USE facturacion;

-- ============================================================================
-- 1. AGREGAR COLUMNA id_factura A tickets
-- ============================================================================
-- Esta columna permite relacionar el ticket con la factura generada

ALTER TABLE tickets 
ADD COLUMN id_factura INT(11) DEFAULT NULL COMMENT 'ID de la factura generada desde este ticket';

-- Agregar índice para mejorar búsquedas
ALTER TABLE tickets 
ADD INDEX idx_id_factura (id_factura);

-- ============================================================================
-- VERIFICACIÓN
-- ============================================================================

-- Verificar que se agregó correctamente
DESCRIBE tickets;

SELECT 'Ajustes completados. Sistema listo para facturar desde tickets.' AS STATUS;

-- ============================================================================
-- NOTAS IMPORTANTES:
-- ============================================================================
-- 
-- Tu tabla 'tickets' ya tiene la columna 'estatus' con valores:
-- - 'facturado': Cuando el ticket ya fue facturado
-- - 'pendiente': Cuando aún no se factura
-- 
-- El sistema usará:
-- - estatus = 'facturado' para marcar tickets facturados
-- - id_factura para relacionar con la factura generada
-- 
-- Tablas que YA están correctas (NO necesitan cambios):
-- ✅ datos_fiscales_usuario
-- ✅ ticket_detalle
-- ✅ ticket_metodo_pago
-- ✅ facturas (ya tiene id_ticket)
-- ✅ facturas_detalles
-- ✅ empresas
-- ============================================================================
