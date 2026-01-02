-- Script para agregar campos de cancelación a la tabla facturas
-- Ejecutar este archivo si los campos no existen

-- Agregar campo fecha_cancelacion
ALTER TABLE facturas 
ADD COLUMN IF NOT EXISTS fecha_cancelacion DATETIME NULL COMMENT 'Fecha y hora en que se canceló la factura';

-- Agregar campo motivo_cancelacion
ALTER TABLE facturas 
ADD COLUMN IF NOT EXISTS motivo_cancelacion VARCHAR(2) NULL COMMENT 'Motivo de cancelación: 01, 02, 03, 04';

-- Agregar campo acuse_cancelacion
ALTER TABLE facturas 
ADD COLUMN IF NOT EXISTS acuse_cancelacion TEXT NULL COMMENT 'Acuse de cancelación del SAT (voucher)';

-- Agregar índice para búsquedas por estatus de cancelación
ALTER TABLE facturas 
ADD INDEX IF NOT EXISTS idx_estatus_cancelacion (estatus, fecha_cancelacion);
