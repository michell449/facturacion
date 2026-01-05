# ✅ AJUSTES REALIZADOS PARA TUS TABLAS EXISTENTES

## 📋 Resumen

He ajustado todo el código para trabajar con **tus tablas reales**, sin necesidad de cambiar nombres.

---

## 🔧 CAMBIOS EN BASE DE DATOS

### ✅ Solo necesitas ejecutar esto:

```sql
-- Agregar 1 columna a tickets
ALTER TABLE tickets 
ADD COLUMN id_factura INT(11) DEFAULT NULL;

-- Agregar índice
ALTER TABLE tickets 
ADD INDEX idx_id_factura (id_factura);
```

**O ejecuta el archivo completo:**
```bash
mysql -u root -p facturacion < SQL_AJUSTES_MINIMOS.sql
```

---

## 📝 MAPEO DE TABLAS

### Tus Tablas → Código Ajustado

| Mi código anterior | Tu tabla real | ✅ Estado |
|-------------------|---------------|-----------|
| `ticket_detalles` | `ticket_detalle` | ✅ CORREGIDO |
| `ticket_pagos` | `ticket_metodo_pago` | ✅ CORREGIDO |
| `tickets.facturado` (columna) | `tickets.estatus` | ✅ USA ESTATUS |
| `facturas_detalles.base` | `facturas_detalles.impuesto_base` | ✅ CORREGIDO |
| `facturas_detalles.impuesto` | `facturas_detalles.impuesto_tipo` | ✅ CORREGIDO |
| `facturas_detalles.tasa_o_cuota` | `facturas_detalles.impuesto_tasa` | ✅ CORREGIDO |
| `facturas_detalles.importe_impuesto` | `facturas_detalles.impuesto_importe` | ✅ CORREGIDO |

---

## 🗄️ ESTRUCTURA FINAL DE TABLAS

### tickets
```
- id_ticket
- id_empresa
- folio_ticket
- fecha_venta
- importe_t
- subtotal
- impuesto_t
- estatus ('facturado' o 'pendiente')
- id_factura  ← NUEVA (relación con facturas)
```

### ticket_detalle
```
- id_detalle
- id_ticket
- folio
- id_prod_serv
- descr
- cant
- precio_unit
- importe
- imp_1, imp_2, imp_3
```

### ticket_metodo_pago
```
- id_ticket
- metodo_pago (PUE o PPD)
- forma_pago (01, 03, 04, etc.)
- monto
```

### datos_fiscales_usuario
```
- id_df
- id_usuario
- rfc
- razon_social
- reg_fiscal
- cp
- tipo_pers (Fisica o Moral)
- calle, num_ext, num_int, col
```

### facturas
```
- id_factura
- id_ticket  ← YA LA TENÍAS
- id_usuario
- id_empresa
- folio_interno
- serie_interno
- fecha_emision
- estatus
- rfc_receptor
- razon_social_receptor
- regimen_fiscal_receptor
- domicilio_fiscal_receptor
- uso_cfdi
- moneda, tipo_cambio
- subtotal, total
- metodo_pago, forma_pago
- uuid, sello_sat, fecha_timbrado
- xml_path, pdf_path
```

### facturas_detalles
```
- id_detalle
- id_factura
- clave_prod_serv
- clave_unidad
- no_identificacion
- cantidad
- unidad
- descripcion
- valor_unitario
- importe
- objeto_imp
- impuesto_base     ← Correcto
- impuesto_tipo     ← Correcto
- impuesto_tasa     ← Correcto
- impuesto_importe  ← Correcto
```

### empresas
```
- id_empresa
- id_usuario
- razon_social
- codigo_suc
- rfc
- direccion, cp, colonia
- reg_fiscal
- estatus
- file_cer, file_key, clave
- nombre, correo, logo
```

---

## 🎯 CÓMO FUNCIONA AHORA

### 1. Usuario factura un ticket

```javascript
// Frontend: detalle-ticket.inc.php
facturarTicket() → POST a generar-factura-ticket.php
```

### 2. Backend procesa

```php
// generar-factura-ticket.php

// Obtiene ticket
SELECT * FROM tickets WHERE id_ticket = ?

// Valida que estatus != 'facturado'
if (estatus == 'facturado') → Error

// Obtiene productos
SELECT * FROM ticket_detalle WHERE id_ticket = ?

// Obtiene forma de pago
SELECT * FROM ticket_metodo_pago WHERE id_ticket = ?

// Obtiene datos del receptor
SELECT * FROM datos_fiscales_usuario WHERE id_usuario = ?

// Obtiene datos del emisor
SELECT * FROM empresas WHERE id_empresa = ?

// Crea factura
INSERT INTO facturas (...)

// Crea detalles
INSERT INTO facturas_detalles (
    impuesto_base,    ← Nombre correcto
    impuesto_tipo,    ← Nombre correcto
    impuesto_tasa,    ← Nombre correcto
    impuesto_importe  ← Nombre correcto
)

// Marca ticket como facturado
UPDATE tickets 
SET estatus = 'facturado', id_factura = ?
WHERE id_ticket = ?
```

### 3. Genera XML → Timbra → PDF

```php
generar-xml.php → timbrar-xml.php → generar-pdf-factura.php
```

---

## 🚀 ARCHIVOS MODIFICADOS

✅ **core/generar-factura-ticket.php**
- Usa `ticket_detalle` (no ticket_detalles)
- Usa `ticket_metodo_pago` (no ticket_pagos)
- Usa `estatus = 'facturado'` (no columna facturado)
- Usa nombres correctos de columnas en facturas_detalles

✅ **SQL_AJUSTES_MINIMOS.sql**
- Solo agrega columna `id_factura` a tickets
- Sin cambios innecesarios

---

## ✅ LISTO PARA USAR

### Paso 1: Ejecuta el SQL
```bash
mysql -u root -p facturacion < SQL_AJUSTES_MINIMOS.sql
```

### Paso 2: Verifica datos fiscales del usuario
```sql
SELECT * FROM datos_fiscales_usuario WHERE id_usuario = 22;
```

### Paso 3: Prueba
1. Buscar ticket
2. Click "Generar Factura"
3. Sistema genera XML → Timbra → PDF automáticamente

---

## 📊 CONSULTAS ÚTILES

```sql
-- Ver tickets pendientes de facturar
SELECT * FROM tickets WHERE estatus = 'pendiente';

-- Ver tickets facturados
SELECT t.*, f.uuid, f.folio_interno 
FROM tickets t
JOIN facturas f ON t.id_factura = f.id_factura
WHERE t.estatus = 'facturado';

-- Ver datos fiscales de usuarios
SELECT u.correo, df.rfc, df.razon_social
FROM usuarios u
JOIN datos_fiscales_usuario df ON u.id_usuario = df.id_usuario;
```

---

## ⚠️ IMPORTANTE

- ✅ No cambié nombres de tus tablas
- ✅ No eliminé columnas existentes
- ✅ Solo agregué 1 columna: `tickets.id_factura`
- ✅ Todo el código está ajustado a tus tablas

---

**Sistema listo. Solo ejecuta el SQL y prueba.**
