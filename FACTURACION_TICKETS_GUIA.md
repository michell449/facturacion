# FACTURACIÓN DESDE TICKETS - GUÍA DE IMPLEMENTACIÓN

## 📋 Descripción General

Este sistema permite generar facturas CFDI 4.0 directamente desde tickets de venta, realizando todo el proceso automático:
1. Creación del registro de factura
2. Generación del XML según CFDI 4.0
3. Timbrado con Finkok
4. Generación del PDF

## 🏗️ Arquitectura del Sistema

### Flujo de Facturación

```
Usuario hace clic en "Generar Factura"
    ↓
detalle-ticket.inc.php (Frontend)
    ↓
generar-factura-ticket.php (Backend)
    ↓
1. Valida datos del ticket
2. Obtiene datos fiscales del emisor (sucursal)
3. Obtiene datos fiscales del receptor (usuario)
4. Crea registro en BD (tabla facturas)
5. Inserta conceptos (tabla facturas_detalles)
    ↓
generar-xml.php
    ↓
timbrar-xml.php (Finkok)
    ↓
generar-pdf-factura.php
    ↓
Factura completa lista para descargar
```

## 🗄️ Estructura de Base de Datos

### Tablas Principales

#### 1. **datos_fiscales_usuario**
Almacena la información fiscal de cada usuario que facturará.

```sql
+-------------------+---------------+
| Campo             | Tipo          |
+-------------------+---------------+
| id_df             | INT PK        |
| id_usuario        | INT FK        |
| rfc               | VARCHAR(13)   |
| razon_social      | VARCHAR(255)  |
| reg_fiscal        | VARCHAR(10)   |
| cp                | VARCHAR(5)    |
| tipo_pers         | ENUM          |
| calle             | VARCHAR(255)  |
| num_ext           | VARCHAR(20)   |
| num_int           | VARCHAR(20)   |
| col               | VARCHAR(255)  |
+-------------------+---------------+
```

**Ejemplo:**
```sql
INSERT INTO datos_fiscales_usuario VALUES
(1, 22, 'ADX220314QI2', 'ACCESO DIRECTO XUBE', '601', '72400', 'Moral', 
 'BOULEVARD ATLIXCO', '2910', '3', 'Santa Cruz los Ángeles');
```

#### 2. **tickets**
```sql
ALTER TABLE tickets 
ADD COLUMN facturado TINYINT(1) DEFAULT 0,
ADD COLUMN id_factura INT(11) DEFAULT NULL;
```

#### 3. **ticket_pagos**
Almacena formas y métodos de pago del ticket.

```sql
CREATE TABLE ticket_pagos (
    id_pago INT(11) PRIMARY KEY AUTO_INCREMENT,
    id_ticket INT(11),
    forma_pago VARCHAR(10) DEFAULT '01',
    metodo_pago VARCHAR(10) DEFAULT 'PUE',
    monto DECIMAL(15,2),
    referencia VARCHAR(100)
);
```

#### 4. **facturas**
```sql
ALTER TABLE facturas 
ADD COLUMN id_ticket INT(11) DEFAULT NULL;
```

## 📝 Configuración Inicial

### Paso 1: Actualizar Base de Datos

```bash
mysql -u root -p facturacion < ACTUALIZACION_BD_FACTURACION_TICKETS.sql
```

### Paso 2: Verificar Datos Fiscales del Usuario

El usuario debe tener sus datos fiscales registrados:

```sql
SELECT * FROM datos_fiscales_usuario WHERE id_usuario = [ID_DEL_USUARIO];
```

Si no existen, el usuario debe registrarlos en:
- **Página:** `registro-info-fiscal.php` (o el formulario que tengas)

### Paso 3: Verificar Certificados del Emisor

Cada sucursal/empresa debe tener sus certificados CSD configurados:

```sql
SELECT id_empresa, rfc, razon_social, file_cer, file_key, clave 
FROM empresas 
WHERE id_empresa = [ID_EMPRESA];
```

Los archivos `.cer` y `.key` deben estar en:
```
uploads/sellos/
```

## 🚀 Uso del Sistema

### Desde la Interfaz de Usuario

1. **Buscar Ticket**: El usuario busca un ticket por folio o sucursal
2. **Ver Detalles**: Se muestra la página `detalle-ticket.inc.php`
3. **Generar Factura**: Click en botón "Generar Factura"
4. **Confirmación**: El sistema valida y genera la factura
5. **Descarga**: Modal con opciones para descargar XML y PDF

### Validaciones Automáticas

El sistema valida:
- ✅ Usuario tiene datos fiscales registrados
- ✅ Emisor tiene RFC, régimen fiscal y CP
- ✅ Emisor tiene certificados CSD válidos
- ✅ Ticket no ha sido facturado previamente
- ✅ Compatibilidad RFC-Régimen-Uso CFDI
- ✅ Ticket tiene productos/conceptos

## 🔧 Archivos Clave

### Backend (PHP)

| Archivo | Función |
|---------|---------|
| `core/generar-factura-ticket.php` | Orquestador principal del proceso |
| `core/generar-xml.php` | Genera XML CFDI 4.0 |
| `core/timbrar-xml.php` | Timbra con Finkok |
| `core/generar-pdf-factura.php` | Genera PDF de la factura |
| `api/FinkokApi.php` | Cliente SOAP para Finkok |

### Frontend (JavaScript)

| Archivo | Función |
|---------|---------|
| `pages/detalle-ticket.inc.php` | Página de detalle del ticket |
| `pages/facturar.inc.php` | Búsqueda de tickets |

## 📊 Datos que se Toman

### Del Ticket
- `id_ticket`: Identificador del ticket
- `id_empresa`: Sucursal/Empresa emisora
- `subtotal`: Monto antes de impuestos
- `total`: Monto total con impuestos
- `detalles`: Productos del ticket

### Del Emisor (Tabla: empresas)
- `rfc`: RFC del emisor
- `razon_social`: Nombre/Razón social
- `reg_fiscal`: Régimen fiscal (ej. 601)
- `cp`: Código postal
- `file_cer`, `file_key`: Certificados digitales

### Del Receptor (Tabla: datos_fiscales_usuario)
- `rfc`: RFC del cliente
- `razon_social`: Nombre/Razón social del cliente
- `reg_fiscal`: Régimen fiscal del cliente
- `cp`: Código postal del cliente
- `tipo_pers`: Persona Física o Moral

### Forma de Pago (Tabla: ticket_pagos)
- `forma_pago`: Código SAT (01=Efectivo, 04=Tarjeta)
- `metodo_pago`: PUE o PPD

## 🎯 Casos de Uso Especiales

### RFC Genérico (Público en General)

Si el usuario tiene RFC genérico (`XAXX010101000` o `XEXX010101000`):
- Se asigna automáticamente Régimen `616`
- Se asigna automáticamente Uso CFDI `S01`
- Se agrega el nodo `InformacionGlobal` al XML

### Validación de Compatibilidad

El sistema valida automáticamente:
- Uso CFDI compatible con Régimen Fiscal
- Método de pago compatible con Forma de pago
- Tipo de persona compatible con Uso CFDI

## 🔍 Troubleshooting

### Error: "No tienes datos fiscales registrados"

**Solución:** El usuario debe registrar sus datos fiscales primero.

```sql
-- Verificar si existen datos
SELECT * FROM datos_fiscales_usuario WHERE id_usuario = [ID];

-- Si no existen, el usuario debe registrarlos en el sistema
```

### Error: "El emisor no tiene certificados digitales"

**Solución:** La sucursal debe tener configurados sus archivos CSD.

```sql
-- Verificar certificados
SELECT file_cer, file_key, clave FROM empresas WHERE id_empresa = [ID];

-- Verificar que existan físicamente
ls uploads/sellos/[nombre_archivo].cer
ls uploads/sellos/[nombre_archivo].key
```

### Error: "Este ticket ya ha sido facturado"

**Solución:** Los tickets solo se pueden facturar una vez.

```sql
-- Verificar estado del ticket
SELECT facturado, id_factura FROM tickets WHERE id_ticket = [ID];

-- Si es un error, resetear manualmente (CON CUIDADO)
UPDATE tickets SET facturado = 0, id_factura = NULL WHERE id_ticket = [ID];
```

### Error al Timbrar: "Saldo agotado"

**Solución:** Recargar timbres en Finkok o contactar soporte.

### Error: "RFC inválido" al timbrar

**Solución:** Verificar que el RFC del emisor esté dado de alta en el SAT.

## 📦 Salidas del Sistema

Después de facturar exitosamente, se generan:

1. **Registro en BD**: `facturas` + `facturas_detalles`
2. **XML sin timbrar**: `uploads/xml_timbrados/[RFC_SERIE_FOLIO].xml`
3. **XML timbrado**: `uploads/xml_timbrados/[UUID].xml`
4. **PDF**: `uploads/facturas_pdf/Factura_[SERIE][FOLIO].pdf`

## 🔐 Seguridad

- ✅ Validación de sesión activa (`$_SESSION['usuario_id']`)
- ✅ Validación de pertenencia del ticket al usuario
- ✅ Uso de sentencias preparadas (PDO)
- ✅ Transacciones BD para integridad
- ✅ Validación de datos fiscales antes de facturar
- ✅ Rollback automático en caso de error

## 📈 Mejoras Futuras

- [ ] Soporte para facturas con múltiples tickets
- [ ] Facturación global mensual
- [ ] Envío automático por correo electrónico
- [ ] Cancelación de facturas desde el sistema
- [ ] Historial de intentos de facturación
- [ ] Notificaciones push cuando esté lista la factura

## 📞 Soporte

Para dudas o problemas:
1. Revisar logs del servidor: `error_log`
2. Revisar tabla `facturas` para ver estado
3. Verificar credenciales de Finkok en `timbrar-xml.php`

---

**Última actualización:** Enero 2026  
**Versión:** 1.0
