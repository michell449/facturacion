# IMPLEMENTACIÓN: Facturación para Clientes Invitados

## 📋 Resumen de Cambios

Se ha implementado un sistema completo de facturación para clientes invitados que permite a usuarios sin cuenta realizar facturas de forma simple y rápida.

## 📁 Archivos Creados/Modificados

### Creados:
1. **`core/facturar-invitado.php`** (NUEVO)
   - Endpoint principal que gestiona todo el proceso
   - Registra usuario invitado
   - Crea factura
   - Llama a XML y timbrado
   - ~500 líneas de PHP

2. **`FACTURAR_INVITADO.md`** (NUEVO)
   - Documentación completa del sistema
   - Diagramas de flujo
   - Referencia de API
   - Ejemplos de uso

3. **`core/facturar-invitado-queries.sql`** (NUEVO)
   - Consultas SQL para testing y debugging
   - Auditoría y validación
   - Limpieza de datos de prueba

4. **`TESTING_FACTURAR_INVITADO.js`** (NUEVO)
   - Ejemplos de cURL
   - Casos de prueba
   - Colección Postman
   - Validaciones JavaScript

### Modificados:
1. **`pages/facturar-invitado.inc.php`**
   - Actualizado formulario de búsqueda
   - Expandido formulario de datos fiscales
   - Agregados campos de domicilio
   - Implementado JavaScript para el flujo completo
   - Agregadas validaciones en cliente

## 🔧 Requisitos Previos

- PHP 7.4+
- MariaDB/MySQL con tablas:
  - `usuarios`
  - `datos_fiscales_usuario`
  - `tickets`
  - `facturas`
  - `facturas_detalles`
  - `empresas`

## ⚙️ Configuración

### 1. Verificar tabla `usuarios`

```sql
ALTER TABLE usuarios MODIFY COLUMN tipo_cliente ENUM('registrado', 'invitado') NOT NULL DEFAULT 'registrado';
```

### 2. Verificar tabla `datos_fiscales_usuario`

```sql
-- Asegurar que existe la columna id_usuario
ALTER TABLE datos_fiscales_usuario ADD CONSTRAINT fk_datos_usuario 
FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE;
```

### 3. Configurar tabla `usuarios`

```sql
-- Los invitados NO deben tener contraseña ni token
-- Estos campos pueden ser NULL o tener valores por defecto
ALTER TABLE usuarios MODIFY COLUMN contrasena VARCHAR(100) NULL;
ALTER TABLE usuarios MODIFY COLUMN token VARCHAR(7) NULL;
```

## 🚀 Instalación

### Paso 1: Copiar archivos
```bash
# Ya están en el workspace:
# - core/facturar-invitado.php
# - pages/facturar-invitado.inc.php
```

### Paso 2: Verificar rutas en `generar-xml.php` y `timbrar-xml.php`
```php
// Estas funciones deben estar disponibles:
// - generarXMLFactura()
// - timbrarFactura()
// - facturaGenerarPdfArchivo()
```

### Paso 3: Verificar configuración de correo
```php
// En config.php o mail/ debe estar configurado el servidor SMTP
require_once __DIR__ . '/core/mail/CorreoConfigService.php';
require_once __DIR__ . '/core/mail/FacturaMailer.php';
```

## ✅ Testing Rápido

### 1. Verificar que existe un ticket pendiente
```sql
SELECT * FROM tickets WHERE estatus = 'pendiente' LIMIT 1;
```

### 2. Llamar al API de búsqueda
```bash
curl -X POST http://localhost/facturacion/core/buscar-ticket-cliente.php \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "nombre_empresa=TuEmpresa&folio=00001&monto=100.00"
```

### 3. Si fue exitosa, probar facturación
```bash
curl -X POST http://localhost/facturacion/core/facturar-invitado.php \
  -H "Content-Type: application/json" \
  -d '{
    "id_ticket": 1,
    "nombre_empresa": "TuEmpresa",
    "folio_ticket": "00001",
    "monto_ticket": 100.00,
    "correo": "test@email.com",
    "rfc": "PEPJ8001019Q8",
    "razon_social": "Juan Pérez",
    "tipo_persona": "Fisica",
    "reg_fiscal": "612",
    "cp": 28000,
    "calle": "Avenida Principal",
    "num_ext": "123",
    "colonia": "Centro"
  }'
```

## 🔐 Seguridad

### Implementadas:
✓ Validación de entrada (lado cliente y servidor)
✓ Prepared statements (PDO)
✓ Filtrado de datos
✓ Validación de email y RFC
✓ Verificación de tickets pendientes
✓ No se almacena información sensible innecesaria

### Recomendaciones:
- [ ] Implementar Rate Limiting
- [ ] Agregar CAPTCHA en el formulario
- [ ] Encriptar RFC en logs
- [ ] Auditar acceso a datos fiscales

## 📊 Flujo de Datos

```
FRONTEND (JavaScript)
    ↓
    ├─→ Validar campos localmente
    ├─→ POST /core/buscar-ticket-cliente.php
    │   ├─→ Buscar en BD
    │   └─→ Retornar ticket
    │
    ├─→ Rellenar datos fiscales
    └─→ POST /core/facturar-invitado.php
        ├─→ Validar datos
        ├─→ Crear usuario invitado
        ├─→ Guardar datos fiscales
        ├─→ Crear factura en BD
        ├─→ Generar XML
        ├─→ Timbrar con SAT
        ├─→ Generar PDF
        ├─→ Enviar email
        └─→ Retornar resultado
```

## 📝 Variables de Sesión

**NOTA:** Este sistema NO requiere sesión iniciada
- Sin `$_SESSION`
- Sin autenticación previa
- Sin validación de usuario existente

## 🗂️ Estructura de Directorios

```
facturacion/
├── core/
│   ├── facturar-invitado.php          (NUEVO)
│   ├── facturar-invitado-queries.sql  (NUEVO)
│   ├── buscar-ticket-cliente.php      (EXISTENTE)
│   ├── generar-xml.php                (EXISTENTE)
│   ├── timbrar-xml.php                (EXISTENTE)
│   ├── autoload-vendor.php            (EXISTENTE)
│   └── class/db.php                   (EXISTENTE)
├── pages/
│   └── facturar-invitado.inc.php      (MODIFICADO)
├── FACTURAR_INVITADO.md               (NUEVO)
└── TESTING_FACTURAR_INVITADO.js       (NUEVO)
```

## 🔗 Rutas Accesibles

```
http://localhost/facturacion/?pg=facturar-invitado
```

## 📞 Soporte

### Logs útiles para debugging:
```bash
# Ver errores PHP
tail -f /var/log/apache2/error.log

# Ver registro de errores del sistema
error_log('Mensaje aquí');

# Verificar en BD
SELECT * FROM usuarios WHERE tipo_cliente = 'invitado';
SELECT * FROM facturas WHERE id_usuario = X;
```

## 🧪 Casos de Prueba

Ver archivo: `TESTING_FACTURAR_INVITADO.js`

Incluye:
- ✓ Búsqueda exitosa de ticket
- ✓ Generación exitosa de factura
- ✓ Errores de validación
- ✓ Casos límite (RFC largo, CP inválido)
- ✓ Ejemplos cURL
- ✓ Colección Postman

## ⚠️ Posibles Errores

### Error: "Ticket no encontrado"
**Causa:** El nombre de empresa no coincide exactamente
**Solución:** Buscar primero con una consulta SQL

### Error: "RFC no válido"
**Causa:** RFC con longitud diferente a 12 o 13 caracteres
**Solución:** Validar formato: `[A-Z]{3,4}[0-9]{6}[A-Z0-9]{3}`

### Error: "Correo electrónico no válido"
**Causa:** Formato de email incorrecto
**Solución:** Usar formato estándar: `usuario@dominio.com`

### Error: "Código postal debe tener 5 dígitos"
**Causa:** CP fuera del rango 10000-99999
**Solución:** Ingresar CP válido (5 dígitos)

### Error: "No se pudo registrar el usuario"
**Causa:** Posible violación de constraints en BD
**Solución:** Verificar que email no exista

## 📈 Estadísticas y Monitoreo

### Consultas útiles:

```sql
-- Usuarios invitados registrados
SELECT COUNT(*) FROM usuarios WHERE tipo_cliente = 'invitado';

-- Facturas generadas hoy
SELECT COUNT(*) FROM facturas f
INNER JOIN usuarios u ON f.id_usuario = u.id_usuario
WHERE u.tipo_cliente = 'invitado'
AND DATE(f.fecha_emision) = CURDATE();

-- Monto total facturado
SELECT SUM(total) FROM facturas f
INNER JOIN usuarios u ON f.id_usuario = u.id_usuario
WHERE u.tipo_cliente = 'invitado';

-- Facturas pendientes de timbrar
SELECT COUNT(*) FROM facturas f
INNER JOIN usuarios u ON f.id_usuario = u.id_usuario
WHERE u.tipo_cliente = 'invitado'
AND f.estatus != 'timbrada';
```

## 🎯 Próximas Mejoras (Backlog)

- [ ] Portal de seguimiento para invitados
- [ ] Búsqueda avanzada de tickets
- [ ] Historial de facturas invitado
- [ ] Resend de factura por email
- [ ] Descargar facturas sin login
- [ ] Validación de colonias por CP
- [ ] Múltiples idiomas
- [ ] QR en factura
- [ ] Integración con sistemas contables

## ✨ Características Completadas

✓ Búsqueda de tickets
✓ Registro de usuario invitado
✓ Guardado de datos fiscales
✓ Generación de factura
✓ XML CFDI
✓ Timbrado SAT
✓ Generación de PDF
✓ Envío por email
✓ Validaciones completas
✓ Interfaz responsive
✓ Documentación

## 📞 Contacto/Support

Para reportar issues o sugerencias, consultar:
- Documentación: `FACTURAR_INVITADO.md`
- Testing: `TESTING_FACTURAR_INVITADO.js`
- Queries: `core/facturar-invitado-queries.sql`

---

**Versión:** 1.0
**Fecha:** Enero 2025
**Estado:** Listo para producción
