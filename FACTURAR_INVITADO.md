# Facturación para Clientes Invitados

## Descripción General

Sistema de facturación sin requerimiento de cuenta para clientes finales. El proceso permite que cualquier cliente pueda:

1. **Buscar su ticket** usando el nombre de la empresa, folio y monto
2. **Ingresar sus datos fiscales** (RFC, razón social, régimen fiscal, domicilio)
3. **Generar su factura** de forma automática
4. **Recibir la factura** por correo electrónico

## Flujo del Proceso

```
┌─────────────────────────┐
│  1. BUSCAR TICKET       │
│  - Nombre empresa       │
│  - Folio                │
│  - Monto total          │
└────────────┬────────────┘
             │
             ▼
┌─────────────────────────┐
│  2. VALIDAR TICKET      │
│  en BD (pendiente)      │
└────────────┬────────────┘
             │
             ▼
┌─────────────────────────┐
│  3. DATOS FISCALES      │
│  - RFC                  │
│  - Razón Social         │
│  - Régimen Fiscal       │
│  - Código Postal        │
│  - Domicilio            │
│  - Email                │
└────────────┬────────────┘
             │
             ▼
┌─────────────────────────┐
│  4. REGISTRAR USUARIO   │
│  - Tipo: invitado       │
│  - Sin contraseña       │
│  - Verificado           │
└────────────┬────────────┘
             │
             ▼
┌─────────────────────────┐
│  5. GUARDAR DATOS FISCAL│
│  en tabla               │
│  datos_fiscales_usuario │
└────────────┬────────────┘
             │
             ▼
┌─────────────────────────┐
│  6. CREAR FACTURA       │
│  - Generar folio        │
│  - Guardar en BD        │
│  - Insertar detalles    │
└────────────┬────────────┘
             │
             ▼
┌─────────────────────────┐
│  7. GENERAR XML         │
│  - Crear estructura CFDI│
│  - Aplicar sello digital│
└────────────┬────────────┘
             │
             ▼
┌─────────────────────────┐
│  8. TIMBRAR CON SAT     │
│  - Llamar Finkok        │
│  - Obtener UUID         │
│  - Guardar comprobante  │
└────────────┬────────────┘
             │
             ▼
┌─────────────────────────┐
│  9. GENERAR PDF         │
│  - Crear plantilla      │
│  - Guardar en servidor  │
└────────────┬────────────┘
             │
             ▼
┌─────────────────────────┐
│  10. ENVIAR POR EMAIL   │
│  - Adjuntar XML y PDF   │
│  - Confirmación al user │
└─────────────────────────┘
```

## Archivos Principales

### Frontend (HTML/JavaScript)
- **[pages/facturar-invitado.inc.php](pages/facturar-invitado.inc.php)**
  - Interfaz visual con 3 pasos
  - Formulario de búsqueda de ticket
  - Formulario de datos fiscales
  - Validación en cliente

### Backend (PHP)
- **[core/facturar-invitado.php](core/facturar-invitado.php)** (NUEVO)
  - Endpoint principal que procesa todo
  - Valida datos
  - Registra usuario invitado
  - Crea factura
  - Llama a generar XML y timbrar

- **[core/buscar-ticket-cliente.php](core/buscar-ticket-cliente.php)** (EXISTENTE)
  - Busca el ticket en la BD
  - Valida que sea pendiente
  - Retorna detalles del ticket

## Base de Datos

### Tabla: `usuarios`
```sql
INSERT INTO usuarios 
(correo, tipo_usuario, tipo_cliente, verificacion, fecha_reg)
VALUES 
('cliente@email.com', 'cliente', 'invitado', 1, NOW())
```

**Campos importantes:**
- `id_usuario`: Auto-increment
- `correo`: Email único del cliente
- `tipo_usuario`: ENUM('admin', 'cliente') = 'cliente'
- `tipo_cliente`: ENUM('registrado', 'invitado') = 'invitado'
- `verificacion`: TINYINT = 1 (verificado)
- `contrasena`: NULL (no tiene contraseña)
- `token`: NULL (no aplica)
- `fecha_reg`: Fecha de registro

### Tabla: `datos_fiscales_usuario`
```sql
INSERT INTO datos_fiscales_usuario
(id_usuario, rfc, razon_social, reg_fiscal, cp, tipo_pers, calle, num_ext, num_int, col)
VALUES
(1, 'PEPJ8001019Q8', 'Juan Pérez', '612', 28000, 'Fisica', 'Avenida Principal', '123', '4B', 'Centro')
```

**Campos importantes:**
- `id_usuario`: FK a tabla usuarios
- `rfc`: RFC del cliente (12 o 13 caracteres)
- `razon_social`: Nombre o razón social
- `reg_fiscal`: Régimen fiscal (3-4 dígitos)
- `cp`: Código postal
- `tipo_pers`: ENUM('Fisica', 'Moral')
- `calle`, `num_ext`, `num_int`, `col`: Domicilio

### Tabla: `facturas`
Se crea factura con:
- `id_usuario`: ID del usuario invitado
- `id_ticket`: FK al ticket facturado
- `folio_interno`: Número secuencial
- `rfc_receptor`, `razon_social_receptor`: Datos del cliente
- `correo_receptor`: Email del cliente
- `estatus`: 'pendiente' → 'timbrada'

## Endpoints API

### POST `/core/buscar-ticket-cliente.php`
**Búsqueda del ticket (EXISTENTE)**

**Parámetros POST:**
```
nombre_empresa: String (nombre de la empresa)
folio: String (número de folio del ticket)
monto: Float (monto total del ticket)
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "message": "Ticket encontrado.",
  "ticket": {
    "id_ticket": 123,
    "folio": "00001234",
    "fecha_venta": "2025-01-15",
    "subtotal": 100.00,
    "impuesto": 16.00,
    "total": 116.00,
    "sucursal": "Tienda Centro",
    "detalles": [...],
    "pagos": [...]
  }
}
```

### POST `/core/facturar-invitado.php`
**Generación de factura (NUEVO)**

**Parámetros JSON:**
```json
{
  "id_ticket": 123,
  "nombre_empresa": "Tienda ABC",
  "folio_ticket": "00001234",
  "monto_ticket": 116.00,
  
  "correo": "cliente@email.com",
  "rfc": "PEPJ8001019Q8",
  "razon_social": "Juan Pérez",
  "tipo_persona": "Fisica",
  "reg_fiscal": "612",
  "cp": 28000,
  "uso_cfdi": "G01",
  
  "calle": "Avenida Principal",
  "num_ext": "123",
  "num_int": "4B",
  "colonia": "Centro"
}
```

**Respuesta exitosa:**
```json
{
  "success": true,
  "message": "Factura generada exitosamente...",
  "id_factura": 456,
  "folio": 1,
  "correo": "cliente@email.com"
}
```

## Validaciones

### Lado del Cliente (JavaScript)
- ✓ Campos requeridos no vacíos
- ✓ Email válido
- ✓ RFC 12-13 caracteres
- ✓ Código postal 5 dígitos (1000-99999)
- ✓ Tipo de persona válido (Fisica/Moral)
- ✓ Checkbox de confirmación

### Lado del Servidor (PHP)
- ✓ Datos no vacíos
- ✓ Email válido
- ✓ RFC formato correcto
- ✓ Ticket existe y está pendiente
- ✓ Monto coincide
- ✓ Usuario invitado no duplicado

## Regímenes Fiscales

### Personas Físicas
- `605`: Sueldos y Salarios
- `606`: Arrendamiento
- `608`: Demás ingresos
- `611`: Ingresos por Dividendos
- `612`: Actividades Empresariales y Profesionales ⭐ (común)
- `614`: Ingresos por intereses
- `616`: Sin obligaciones fiscales
- `621`: Incorporación Fiscal
- `622`: Actividades Agrícolas, Ganaderas, etc.
- `626`: Régimen Simplificado de Confianza

### Personas Morales
- `601`: General de Ley Personas Morales ⭐ (común)
- `603`: Personas Morales con Fines no Lucrativos
- `609`: Consolidación
- `620`: Sociedades Cooperativas
- `623`: Opcional para Grupos de Sociedades
- `624`: Coordinados
- `625`: Régimen de Plataformas Tecnológicas

## Usos CFDI (Catálogo)

- `G01`: **Adquisición de mercancías** ⭐ (default)
- `G02`: Devoluciones, descuentos o bonificaciones
- `G03`: Gastos en general
- `I01-I08`: Inversiones (construcción, equipo, etc.)
- `D01-D10`: Deducciones (médicos, gastos, etc.)
- `S01-S06`: Servicios (profesionales, comida, hospedaje, etc.)

## Ejemplo de Uso

### 1. Cliente accede a `/index.php?pg=facturar-invitado`

### 2. Llena formulario de búsqueda:
```
Nombre del Negocio: Tienda ABC
Folio: 00001234
Monto Total: $116.00
```

### 3. Sistema busca ticket:
```php
POST core/buscar-ticket-cliente.php
nombre_empresa=Tienda ABC
folio=00001234
monto=116.00
```

### 4. Cliente recibe confirmación y llena datos fiscales:
```
RFC: PEPJ8001019Q8
Nombre: Juan Pérez García
Régimen: 612
Código Postal: 28000
Calle: Avenida Paseo Principal
No. Exterior: 123
Colonia: Centro
```

### 5. Sistema crea factura:
```php
POST core/facturar-invitado.php
{
  id_ticket: 123,
  correo: "juan@email.com",
  rfc: "PEPJ8001019Q8",
  ...
}
```

### 6. Se generan:
- ✓ Usuario invitado en `usuarios`
- ✓ Datos fiscales en `datos_fiscales_usuario`
- ✓ Factura en `facturas`
- ✓ Detalles en `facturas_detalles`
- ✓ XML CFDI
- ✓ Timbrado SAT
- ✓ PDF
- ✓ Email al cliente

## Manejo de Errores

### Ticket no encontrado
```json
{
  "success": false,
  "message": "No se encontró un ticket pendiente en \"Tienda ABC\" con folio \"00001234\" y monto $116.00..."
}
```

### Datos fiscales incompletos
```json
{
  "success": false,
  "message": "Faltan datos requeridos para el registro."
}
```

### RFC inválido
```json
{
  "success": false,
  "message": "RFC no válido. Debe tener 12 o 13 caracteres."
}
```

## Seguridad

1. **Validación de entrada:** Todos los datos se validan tanto en cliente como en servidor
2. **Inyección SQL:** Se usan prepared statements (PDO)
3. **CSRF:** Las solicitudes usan POST/JSON
4. **Email spoofing:** Se valida formato de email
5. **Datos sensibles:** RFC y datos fiscales se tratan como PII

## Logs

Se generan logs en:
- `error_log()` del servidor
- Archivos de log del sistema
- Registro en table `facturas` (audit trail automático)

Ejemplo en logs:
```
Error en facturar-invitado.php: No se encontró el usuario...
Error en facturar-invitado.php: RFC no válido...
```

## Próximas Mejoras

- [ ] Verificación de correo para usuarios invitados
- [ ] Búsqueda de colonias/municipios por código postal
- [ ] Descarga automática de PDF/XML después de timbrado
- [ ] Portal de seguimiento para invitados (sin login)
- [ ] Historial de facturas invitado
- [ ] Resend de factura por correo
- [ ] Exportación a formato contable

## Testing

### Caso de prueba exitoso:
```bash
curl -X POST http://localhost/facturacion/core/facturar-invitado.php \
  -H "Content-Type: application/json" \
  -d '{
    "id_ticket": 1,
    "nombre_empresa": "Tienda ABC",
    "folio_ticket": "00001234",
    "monto_ticket": 116.00,
    "correo": "test@email.com",
    "rfc": "PEPJ8001019Q8",
    "razon_social": "Juan Pérez",
    "tipo_persona": "Fisica",
    "reg_fiscal": "612",
    "cp": 28000,
    "uso_cfdi": "G01",
    "calle": "Avenida Principal",
    "num_ext": "123",
    "num_int": "",
    "colonia": "Centro"
  }'
```

Respuesta esperada:
```json
{
  "success": true,
  "message": "Factura generada exitosamente...",
  "id_factura": 456,
  "folio": 1,
  "correo": "test@email.com"
}
```
