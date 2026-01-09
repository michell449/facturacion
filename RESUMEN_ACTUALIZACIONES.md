# Resumen de Actualizaciones - Facturación para Invitados

## Fecha de Actualización
**2024** - Mejora del flujo completo de facturación

---

## 📋 Cambios Principales

### 1. **Reescritura Completa de `core/facturar-invitado.php`**

El archivo ha sido completamente rediseñado para seguir el patrón de `generar-factura-ticket.php` con el siguiente flujo:

#### Paso 1: Validación y Recepción de Datos
- Limpieza de buffers PHP para evitar output no deseado
- Configuración de headers JSON
- Validación exhaustiva de datos de entrada (email, RFC, código postal)

#### Paso 2: Verificación del Ticket
- Búsqueda del ticket en base de datos
- Validación de estado 'pendiente'
- Extracción de información de empresa emisora

#### Paso 3: Gestión de Usuario Invitado
- Creación o reutilización de usuario con tipo `'invitado'`
- Sin contraseña requerida
- Registro automático en tabla `usuarios`

#### Paso 4: Almacenamiento de Datos Fiscales
- Guardado o actualización de información fiscal en tabla `datos_fiscales_usuario`
- Campos: RFC, razón social, régimen fiscal, CP, tipo de persona, domicilio

#### Paso 5: Creación de Factura
- Generación automática de folio (serie + número correlativo)
- Cálculo de totales (subtotal, impuesto, total)
- Inserción en tabla `facturas` con estado 'pendiente'

#### Paso 6: Inserción de Detalles
- Copia de líneas de artículos desde ticket a factura
- Cálculo automático de impuesto por detalle (IVA 16%)

#### Paso 7: Actualización del Ticket
- Cambio de estado de 'pendiente' a 'facturado'
- Vinculación del ticket con la factura mediante `id_factura`

#### Paso 8: Generación de XML
- Llamada a `generar-xml.php` via cURL
- Manejo de errores con mensajes descriptivos
- Logging detallado de cada paso

#### Paso 9: Timbrado con SAT
- Llamada a `timbrar-xml.php` via cURL
- Integración con Finkok API
- Obtención de UUID de factura electrónica
- Manejo robusto de errores

#### Paso 10: Generación de PDF
- Llamada a `facturaGenerarPdfArchivo()` (si está disponible)
- Manejo de errores sin fallar el flujo

#### Paso 11: Envío por Correo
- Obtención de configuración SMTP (BD o config.php)
- Adjunción de PDF y XML
- Construcción de email con plantilla HTML
- Validación de disponibilidad de función `facturaEnviarCorreo()`

#### Paso 12: Respuesta al Cliente
- JSON con confirmación de éxito
- Inclusión de folio, ID de factura, UUID, correo destino
- Códigos HTTP apropiados (200 éxito, 400 error)

---

## 🔄 Mejoras Técnicas

### Buffer Management
```php
while (ob_get_level() > 0) {
    ob_end_clean();
}
ob_start();
```
Elimina cualquier salida previa y luego captura output para validación.

### Logging Mejorado
Cada paso incluye logs con formato visual:
```
═══ INICIO FACTURACIÓN INVITADO ═══
[TICKET] Buscando ticket {id}
[TICKET] ✓ Encontrado - Folio: {folio}
```

### Manejo de Errores
```php
try {
    // Proceso completo
} catch (Throwable $e) {
    error_log("✗ ERROR EN FACTURACIÓN");
    http_response_code(400);
    $respuesta['message'] = $e->getMessage();
}
```

### Funciones Auxiliares
```php
generarXMLFactura($id_factura)      // Genera XML via cURL
timbrarFactura($id_factura)         // Timbra con Finkok
```

---

## 🖼️ Actualización del Frontend

### Formulario Ampliado
Agregado los campos de domicilio:
- **Calle** (opcional)
- **Número Exterior** (opcional)
- **Número Interior** (opcional)
- **Colonia/Municipio** (opcional)

### Mensaje de Éxito Mejorado
Ahora muestra:
- ✅ Confirmación visual
- 📋 Folio y ID de factura
- 📧 Correo de destino
- ⏳ Estado del timbrado

```html
<div class="alert alert-success">
  <strong>A000001</strong> - Folio asignado
  <br>
  <code>ID_FACTURA_123</code>
  <br>
  Correo: usuario@ejemplo.com
</div>
```

---

## 📊 Flujo Completo Visualizado

```
┌─────────────────────────────────────────────────────┐
│  CLIENTE INVITADO - FACTURACIÓN SIN CUENTA          │
├─────────────────────────────────────────────────────┤
│ 1. Busca Ticket   → Nombre empresa, folio, monto    │
│ 2. Valida Ticket  → Verifica en BD, estado pendiente│
│ 3. Crea Usuario   → Registro automático invitado    │
│ 4. Guarda Datos   → RFC, razón social, domicilio   │
│ 5. Genera Factura → Crea registro en BD con folio  │
│ 6. Copia Detalles → Líneas de artículos             │
│ 7. Marca Ticket   → Cambio de estado a 'facturado' │
│ 8. Genera XML     → CFDI válido para SAT            │
│ 9. Timbra XML     → Obtiene UUID de Finkok         │
│ 10. PDF           → Factura en formato PDF          │
│ 11. Email         → Envío con XML y PDF adjuntos   │
│ 12. Respuesta     → JSON con confirmación            │
└─────────────────────────────────────────────────────┘
```

---

## 🗄️ Cambios en Base de Datos

### Tabla: `usuarios`
```sql
INSERT INTO usuarios (correo, tipo_usuario, tipo_cliente, verificacion, fecha_reg)
VALUES ('usuario@ejemplo.com', 'cliente', 'invitado', 1, NOW())
```

### Tabla: `datos_fiscales_usuario`
```sql
INSERT INTO datos_fiscales_usuario 
(id_usuario, rfc, razon_social, reg_fiscal, cp, tipo_pers, calle, num_ext, num_int, col)
VALUES (...)
```

### Tabla: `facturas`
```sql
INSERT INTO facturas 
(id_usuario, id_empresa, id_ticket, folio_interno, fecha_emision, ...)
VALUES (...)
UPDATE facturas SET xml_path='...', pdf_path='...', uuid='...'
```

### Tabla: `tickets`
```sql
UPDATE tickets SET estatus='facturado', id_factura=? WHERE id_ticket=?
```

---

## 🔧 Configuración Requerida

### En `config.php`
```php
// SMTP Configuration (si no está en BD)
define('MAIL_HOST', 'smtp.ejemplo.com');
define('MAIL_PORT', 587);
define('MAIL_USER', 'noreply@ejemplo.com');
define('MAIL_PSWD', 'contraseña');
define('MAIL_SEC', 'tls');
```

### Endpoints Internos Requeridos
- ✅ `core/buscar-ticket-cliente.php` - Búsqueda de tickets
- ✅ `core/generar-xml.php` - Generación de XML CFDI
- ✅ `core/timbrar-xml.php` - Timbrado con Finkok
- ✅ `core/FacturaPdfService.php` - Generación de PDF
- ✅ `core/mail/FacturaMailer.php` - Envío de emails

---

## 📧 Contenido del Email

El email enviado incluye:

**Asunto:**
```
Factura Electrónica A000001 - Razón Social Empresa
```

**Cuerpo HTML:**
- Logo y encabezado azul
- Número de folio prominente
- Tabla con datos fiscales:
  - Folio
  - RFC
  - Fecha
  - Subtotal
  - Impuesto (IVA 16%)
  - **TOTAL**
- Aviso de archivos adjuntos
- Pie de página

**Adjuntos:**
- `A000001.pdf` - Factura en PDF
- `{UUID}.xml` - Archivo CFDI timbrado

---

## 🧪 Pruebas Recomendadas

### Test 1: Búsqueda de Ticket
```bash
POST core/buscar-ticket-cliente.php
nombre_empresa: "Test Store"
folio: "00001"
monto: "1000.00"
```

### Test 2: Generación de Factura
```bash
POST core/facturar-invitado.php
Content-Type: application/json

{
  "id_ticket": 1,
  "nombre_empresa": "Test Store",
  "correo": "cliente@ejemplo.com",
  "rfc": "PEPJ8001019Q8",
  "razon_social": "Juan Pérez",
  "reg_fiscal": "612",
  "cp": "28001",
  "tipo_persona": "Fisica"
}
```

### Validación de Respuesta
```json
{
  "success": true,
  "message": "Factura generada, timbrada y enviada por correo exitosamente",
  "id_factura": 123,
  "folio": 1,
  "uuid": "12345678-1234-1234-1234-123456789012",
  "correo": "cliente@ejemplo.com"
}
```

---

## 📝 Logs a Monitorear

Verificar `php_errors.log`:
```
═══ INICIO FACTURACIÓN INVITADO ═══
[TICKET] Buscando ticket 1
[TICKET] ✓ Encontrado - Folio: 00001, Total: 1000.00
[USUARIO] ✓ Nuevo usuario invitado creado ID: 42
[DATOS_FISCALES] ✓ Insertado
[BD] ✓ Factura creada ID: 123
[XML] ✓ XML generado exitosamente
[TIMBRADO] ✓ Factura timbrada - UUID: 12345678-1234-1234-1234-123456789012
[PDF] ✓ PDF generado exitosamente
[EMAIL] ✓ Correo enviado a cliente@ejemplo.com
╔════════════════════════════════════════╗
║  ✓ FACTURACIÓN INVITADO COMPLETADA   ║
║  ID: 123                              ║
║  Folio: A000001                       ║
║  Email: cliente@ejemplo.com           ║
╚════════════════════════════════════════╝
```

---

## 🔐 Seguridad

### Validaciones Implementadas
✅ Email válido (filter_var)
✅ RFC 12-13 caracteres
✅ Código postal 5 dígitos
✅ Tipo de persona enum
✅ Datos requeridos presentes

### Headers JSON
```php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');
```

### Manejo de Excepciones
- Try-catch para todo el flujo
- Captura de errores de cURL
- Validación de JSON decodificado

---

## 📚 Archivos Modificados

| Archivo | Cambios |
|---------|---------|
| `core/facturar-invitado.php` | ✏️ Reescrito completamente (686 líneas) |
| `pages/facturar-invitado.inc.php` | ✏️ Ampliado con campos de domicilio |
| NUEVO: `RESUMEN_ACTUALIZACIONES.md` | 📄 Este archivo |

---

## ✅ Checklist de Implementación

- [x] Reescritura del backend siguiendo patrón generar-factura-ticket.php
- [x] Integración de XML generation
- [x] Integración de timbrado Finkok
- [x] Integración de PDF generation
- [x] Integración de envío por email
- [x] Logging detallado
- [x] Manejo robusto de errores
- [x] Validación de datos de entrada
- [x] Actualización del formulario frontend
- [x] Mensaje de éxito mejorado
- [x] Documentación completa

---

## 🚀 Próximos Pasos

1. **Verificar endpoints internos** - Asegurar que generar-xml.php y timbrar-xml.php funcionan
2. **Probar con datos reales** - Usar un ticket válido de la BD
3. **Validar emails** - Confirmar que llegan con adjuntos
4. **Revisar logs** - Monitorear el flujo en php_errors.log
5. **Usuarios finales** - Instruir a clientes sobre el proceso

---

## 📞 Soporte

Si encuentras errores:
1. Revisa `php_errors.log` para detalles exactos
2. Verifica que los endpoints internos funcionen
3. Confirma la configuración SMTP en `config.php`
4. Valida que exista el ticket en BD con estado 'pendiente'

---

**Última actualización:** 2024
**Versión:** 2.0
**Estado:** ✅ Completado
