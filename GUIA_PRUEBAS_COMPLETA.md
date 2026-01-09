# Guía de Pruebas - Facturación para Invitados v2.0

## 🧪 Pruebas del Sistema Completo

---

## Test 1: Búsqueda de Ticket (Paso Previo)

### Descripción
Verifica que el ticket exista en BD y esté disponible para facturar.

### Request
```bash
curl -X POST http://localhost/facturacion/core/buscar-ticket-cliente.php \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "nombre_empresa=Mi%20Tienda&folio=00001&monto=1000.00"
```

### Response Esperada (Éxito)
```json
{
  "success": true,
  "ticket": {
    "id_ticket": 1,
    "folio_ticket": "00001",
    "fecha_venta": "2024-01-15",
    "subtotal": "862.07",
    "impuesto": "137.93",
    "total": "1000.00",
    "estatus": "pendiente"
  }
}
```

### Response Esperada (Error)
```json
{
  "success": false,
  "message": "Ticket no encontrado"
}
```

### Validaciones
- ✅ El ticket debe existir en BD
- ✅ El folio debe coincidir exactamente
- ✅ El monto debe ser similar (dentro de rango)
- ✅ El estado debe ser 'pendiente'

---

## Test 2: Generación Completa de Factura (FLUJO PRINCIPAL)

### Descripción
Prueba todo el flujo: registro de usuario, datos fiscales, creación de factura, XML, timbrado, PDF y email.

### Request Detallado
```bash
curl -X POST http://localhost/facturacion/core/facturar-invitado.php \
  -H "Content-Type: application/json" \
  -d '{
    "id_ticket": 1,
    "nombre_empresa": "Mi Tienda",
    "folio_ticket": "00001",
    "monto_ticket": 1000.00,
    
    "correo": "cliente.invitado@ejemplo.com",
    "rfc": "PEPJ8001019Q8",
    "razon_social": "Juan Perez",
    "tipo_persona": "Fisica",
    "reg_fiscal": "612",
    "cp": "28001",
    "uso_cfdi": "G01",
    
    "calle": "Avenida Paseo de la Reforma",
    "num_ext": "505",
    "num_int": "1001",
    "colonia": "Cuauhtemoc"
  }'
```

### Response Esperada (Éxito)
```json
{
  "success": true,
  "message": "Factura generada, timbrada y enviada por correo exitosamente",
  "id_factura": 123,
  "folio": 1,
  "uuid": "12345678-1234-1234-1234-123456789012",
  "correo": "cliente.invitado@ejemplo.com"
}
```

### Response Esperada (Error - Validación)
```json
{
  "success": false,
  "message": "RFC no válido. Debe tener 12 o 13 caracteres."
}
```

### Response Esperada (Error - Ticket No Encontrado)
```json
{
  "success": false,
  "message": "Ticket no encontrado o ya ha sido facturado."
}
```

---

## Test 3: Validaciones de Entrada

### Test 3.1: Email Inválido
```bash
curl -X POST http://localhost/facturacion/core/facturar-invitado.php \
  -H "Content-Type: application/json" \
  -d '{"correo": "invalido@", "rfc": "PEPJ8001019Q8", ...}'
```
**Esperado:** `"Correo electrónico no válido."`

### Test 3.2: RFC Muy Corto
```bash
curl -X POST http://localhost/facturacion/core/facturar-invitado.php \
  -H "Content-Type: application/json" \
  -d '{"rfc": "PEPJ80", ...}'
```
**Esperado:** `"RFC no válido. Debe tener 12 o 13 caracteres."`

### Test 3.3: Código Postal Inválido
```bash
curl -X POST http://localhost/facturacion/core/facturar-invitado.php \
  -H "Content-Type: application/json" \
  -d '{"cp": "123", ...}'
```
**Esperado:** `"Código postal debe tener 5 dígitos."`

### Test 3.4: Tipo de Persona Inválido
```bash
curl -X POST http://localhost/facturacion/core/facturar-invitado.php \
  -H "Content-Type: application/json" \
  -d '{"tipo_persona": "Extranjero", ...}'
```
**Esperado:** `"Tipo de persona debe ser 'Fisica' o 'Moral'."`

---

## Test 4: Verificación en Base de Datos

### Después de una facturación exitosa, validar:

### Usuario Creado
```sql
SELECT * FROM usuarios 
WHERE correo = 'cliente.invitado@ejemplo.com' 
AND tipo_cliente = 'invitado';
```

**Resultado esperado:**
| id_usuario | correo | tipo_usuario | tipo_cliente | verificacion | fecha_reg |
|-----------|--------|-------------|-------------|------------|-----------|
| 42 | cliente.invitado@ejemplo.com | cliente | invitado | 1 | 2024-01-20 10:30:00 |

### Datos Fiscales Guardados
```sql
SELECT * FROM datos_fiscales_usuario 
WHERE id_usuario = 42 AND rfc = 'PEPJ8001019Q8';
```

**Resultado esperado:**
| id_df | id_usuario | rfc | razon_social | reg_fiscal | cp | tipo_pers | calle | num_ext | num_int | col |
|-------|-----------|-----|--------------|-----------|-----|----------|-------|---------|---------|-----|
| 99 | 42 | PEPJ8001019Q8 | Juan Perez | 612 | 28001 | Fisica | Avenida... | 505 | 1001 | Cuauhtemoc |

### Factura Creada
```sql
SELECT id_factura, folio_interno, rfc_receptor, total, estatus 
FROM facturas 
WHERE id_usuario = 42;
```

**Resultado esperado:**
| id_factura | folio_interno | rfc_receptor | total | estatus |
|-----------|--------------|-------------|-------|---------|
| 123 | 1 | PEPJ8001019Q8 | 1000.00 | timbrado |

### Detalles de Factura
```sql
SELECT descripcion, cantidad, precio_unitario, importe 
FROM facturas_detalles 
WHERE id_factura = 123;
```

**Resultado esperado:** (Copiados del ticket)
| descripcion | cantidad | precio_unitario | importe |
|------------|----------|-----------------|---------|
| Producto 1 | 2 | 100.00 | 200.00 |
| Producto 2 | 1 | 662.07 | 662.07 |

### Ticket Actualizado
```sql
SELECT estatus, id_factura 
FROM tickets 
WHERE id_ticket = 1;
```

**Resultado esperado:**
| estatus | id_factura |
|---------|-----------|
| facturado | 123 |

---

## Test 5: Validación de Archivos Generados

### Verificar existencia de XML
```bash
ls -la uploads/facturas/xml/
# Debe existir: A000001.xml o similar
```

### Verificar existencia de PDF
```bash
ls -la uploads/facturas/pdf/
# Debe existir: A000001.pdf o similar
```

### Validar contenido de XML
```bash
# Debe incluir:
# - Comprobante con UUID válido
# - Folio correlativo
# - RFC receptor
# - Totales correctos
head -20 uploads/facturas/xml/A000001.xml
```

---

## Test 6: Verificación de Email

### Validar que el email llegó
1. Revisar bandeja de entrada de `cliente.invitado@ejemplo.com`
2. Verificar asunto: `Factura Electrónica A000001 - Juan Perez`
3. Validar adjuntos:
   - `A000001.pdf` ✅
   - `{UUID}.xml` ✅

### Estructura del Email
```
FROM: noreply@ejemplo.com
TO: cliente.invitado@ejemplo.com
SUBJECT: Factura Electrónica A000001 - Juan Perez

BODY:
  ┌──────────────────────────────┐
  │  Factura Electrónica         │
  │  Folio: A000001              │
  └──────────────────────────────┘
  
  Estimado/a Juan Perez,
  
  Concepto          │  Valor
  ──────────────────┼─────────────
  Folio             │ A000001
  RFC               │ PEPJ8001019Q8
  Fecha             │ 20/01/2024 10:30:00
  Subtotal          │ $862.07
  Impuesto (16%)    │ $137.93
  ──────────────────┼─────────────
  TOTAL             │ $1000.00
  
  Adjuntos:
  • A000001.pdf
  • UUID.xml
```

---

## Test 7: Logs y Auditoría

### Verificar el archivo de log
```bash
tail -100 /xampp/php/logs/php_errors.log
```

### Patrones esperados (éxito)
```
═══ INICIO FACTURACIÓN INVITADO ═══
[TICKET] Buscando ticket 1
[TICKET] ✓ Encontrado - Folio: 00001, Total: 1000.00
[USUARIO] ✓ Nuevo usuario invitado creado ID: 42
[DATOS_FISCALES] ✓ Insertado
[BD] ✓ Factura creada ID: 123
[DETALLES_BD] ✓ Detalles insertados
[TICKET] ✓ Estado actualizado a 'facturado'
[XML] ═══ Generando XML ═══
[XML] ✓ XML generado exitosamente
[TIMBRADO] ═══ Timbrado con SAT ═══
[TIMBRADO] ✓ Factura timbrada - UUID: 12345678-1234-1234-1234-123456789012
[PDF] ✓ PDF generado exitosamente
[EMAIL] ═══ Enviando correo ═══
[EMAIL] ✓ PDF adjuntado
[EMAIL] ✓ XML adjuntado
[EMAIL] ✓ Correo enviado a cliente.invitado@ejemplo.com
╔════════════════════════════════════════╗
║  ✓ FACTURACIÓN INVITADO COMPLETADA   ║
║  ID: 123                              ║
║  Folio: A000001                       ║
║  Email: cliente.invitado@ejemplo.com  ║
╚════════════════════════════════════════╝
```

### Patrones esperados (error)
```
╔════════════════════════════════════════╗
║  ✗ ERROR EN FACTURACIÓN ✗             ║
╚════════════════════════════════════════╝
[ERROR] Ticket no encontrado o ya ha sido facturado.
[ERROR] Archivo: /xampp/htdocs/facturacion/core/facturar-invitado.php
[ERROR] Línea: 123
```

---

## Test 8: Stress Testing (Múltiples Facturas)

### Generar 5 facturas en secuencia
```bash
for i in {1..5}; do
  echo "Factura $i..."
  curl -X POST http://localhost/facturacion/core/facturar-invitado.php \
    -H "Content-Type: application/json" \
    -d "{...}"
  sleep 2
done
```

### Validar en BD
```sql
SELECT COUNT(*) as total_facturas, 
       MAX(folio_interno) as ultimo_folio
FROM facturas 
WHERE fecha_emision >= DATE_SUB(NOW(), INTERVAL 1 HOUR);
```

**Esperado:** 5 facturas con folios 1-5

---

## Test 9: Reutilización de Usuario Invitado

### Generar factura con mismo email (diferente ticket)
```bash
# Primera factura
correo: "cliente.invitado@ejemplo.com"
id_ticket: 1

# Segunda factura (mismo email, ticket diferente)
correo: "cliente.invitado@ejemplo.com"
id_ticket: 2
```

### Validar en BD
```sql
SELECT id_usuario, COUNT(*) as num_facturas
FROM facturas 
WHERE rfc_receptor = 'PEPJ8001019Q8'
GROUP BY id_usuario;
```

**Esperado:** Un usuario (ID 42) con 2 facturas

---

## Test 10: Campos Opcionales

### Factura sin domicilio
```bash
curl -X POST http://localhost/facturacion/core/facturar-invitado.php \
  -H "Content-Type: application/json" \
  -d '{
    ...
    "calle": "",
    "num_ext": "",
    "num_int": "",
    "colonia": ""
  }'
```

**Esperado:** ✅ Factura se genera correctamente sin domicilio

---

## 📊 Matriz de Pruebas

| Test ID | Descripción | Status | Notas |
|---------|------------|--------|-------|
| T1 | Búsqueda de ticket | ⬜ | Precondición |
| T2 | Flujo completo exitoso | ⬜ | Crítico |
| T3.1 | Email inválido | ⬜ | Validación |
| T3.2 | RFC corto | ⬜ | Validación |
| T3.3 | CP inválido | ⬜ | Validación |
| T3.4 | Persona inválida | ⬜ | Validación |
| T4 | BD consistencia | ⬜ | Integridad |
| T5 | Archivos generados | ⬜ | Output |
| T6 | Email enviado | ⬜ | Comunicación |
| T7 | Logs auditoría | ⬜ | Monitoreo |
| T8 | Stress testing | ⬜ | Rendimiento |
| T9 | Reutilizar usuario | ⬜ | Flujo alterno |
| T10 | Campos opcionales | ⬜ | Flexibilidad |

---

## 🔍 Checklist de Validación Post-Test

- [ ] Respuesta JSON válida y estructurada
- [ ] Códigos HTTP correctos (200 éxito, 400 error)
- [ ] Usuario creado en BD con tipo_cliente='invitado'
- [ ] Datos fiscales almacenados correctamente
- [ ] Factura creada con folio único y correlativo
- [ ] Detalles copiados del ticket correctamente
- [ ] Ticket actualizado a estado 'facturado'
- [ ] XML válido generado
- [ ] Timbrado completado con UUID asignado
- [ ] PDF generado y accesible
- [ ] Email recibido con adjuntos
- [ ] Logs muestran flujo completo
- [ ] No hay errores en error_log

---

## 🚨 Troubleshooting Común

### Error: "Ticket no encontrado"
- ✅ Verificar que ticket existe en BD
- ✅ Validar folio exacto (case-sensitive)
- ✅ Confirmar estado es 'pendiente'

### Error: "No se pudo registrar el usuario"
- ✅ Revisar permisos de BD
- ✅ Confirmar tabla usuarios existe
- ✅ Validar último_id() funciona

### Error: "Error al generar XML"
- ✅ Verificar que generar-xml.php existe
- ✅ Probar endpoint directamente
- ✅ Revisar logs de generación XML

### Error: "No se pudo timbrar"
- ✅ Validar credenciales Finkok
- ✅ Confirmar conexión a internet
- ✅ Revisar que XML es válido

### Email no llega
- ✅ Validar configuración SMTP
- ✅ Revisar logs de email
- ✅ Confirmar email no va a spam
- ✅ Probar con cuenta de prueba

---

**Último actualizado:** 2024
**Versión:** 2.0
**Crítico para:** QA, Implementación, Mantenimiento
