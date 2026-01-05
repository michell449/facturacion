# 📖 REFERENCIA RÁPIDA: Facturación para Clientes

## Acceso Rápido

| Elemento | Ubicación | Descripción |
|----------|-----------|-------------|
| **Página Usuario** | `panel?pg=facturar-cliente` | Interfaz para que cliente busque y facture |
| **API Búsqueda** | `core/buscar-ticket-cliente.php` | Busca ticket en BD |
| **API Sucursales** | `core/obtener-sucursales-cliente.php` | Lista sucursales del usuario |
| **Menú** | `pages/header.inc.php` | Enlace actualizado |
| **Documentación** | `FACTURACION_CLIENTES.md` | Docs completas |
| **Datos Prueba** | `core/INSTRUCCIONES_DATOS_PRUEBA.php` | SQL para testing |

---

## 🚀 Quick Start (5 minutos)

### 1. Verificar Instalación
```bash
# Verificar que existen los archivos
✓ pages/facturar-cliente.inc.php
✓ core/buscar-ticket-cliente.php
✓ core/obtener-sucursales-cliente.php
✓ pages/header.inc.php (actualizado)
```

### 2. Insertar Datos Prueba
```sql
-- Copiar y ejecutar en phpMyAdmin
-- Archivo: core/INSTRUCCIONES_DATOS_PRUEBA.php
-- Seleccionar uno de los ejemplos SQL
```

### 3. Acceder a la Página
```
URL: http://localhost/facturacion/panel?pg=facturar-cliente
```

### 4. Probar Búsqueda
```
Folio:    100001
Monto:    1740.00
Fecha:    2025-01-10
Sucursal: [Seleccionar de lista]
```

### 5. Generar Factura
```
Click en "Generar Factura" → Confirmar → Listo!
```

---

## 📨 Parámetros de APIs

### buscar-ticket-cliente.php (POST)

**Parámetros requeridos:**
```php
$_POST['folio']      // String: Folio del ticket
$_POST['monto']      // Float: Monto total
$_POST['fecha']      // String: YYYY-MM-DD
$_POST['id_sucursal'] // Int: ID de empresa
```

**Respuesta (success):**
```json
{
  "success": true,
  "ticket": {
    "id_ticket": 1,
    "folio": "100001",
    "fecha_venta": "2025-01-10",
    "sucursal": "Centro",
    "subtotal": 1500.00,
    "impuesto": 240.00,
    "total": 1740.00,
    "detalles": [...],
    "pagos": [...]
  }
}
```

**Respuesta (error):**
```json
{
  "success": false,
  "message": "Descripción del error"
}
```

### obtener-sucursales-cliente.php (POST)

**Parámetros**: Ninguno (usa sesión)

**Respuesta:**
```json
{
  "success": true,
  "sucursales": [
    {
      "id_empresa": 5,
      "nombre": "Sucursal Centro",
      "codigo_suc": "001"
    }
  ]
}
```

---

## 🔑 Variables JavaScript Globales

```javascript
ticketActual  // Objeto con el ticket encontrado
modalSucursales // Instancia del modal de Bootstrap
```

## 🔧 Funciones JavaScript Principales

```javascript
cargarSucursales()      // Carga sucursales al iniciar
buscarTicket()          // Busca ticket en BD
mostrarTicket(ticket)   // Muestra detalles del ticket
facturarTicket()        // Genera factura
nuevaBusqueda()         // Limpia formulario
mostrarAlerta(msg, tipo) // Muestra alerta flotante
```

---

## 💾 Estructura de Base de Datos (Usada)

### tickets_sin_facturar
```sql
id_ticket         INT PRIMARY KEY
id_empresa        INT FK → empresas
folio_ticket      VARCHAR(10) -- Único por empresa
fecha_venta       DATE
importe_t         DECIMAL
subtotal          DECIMAL
impuesto_t        DECIMAL
estatus           TINYINT
```

### ticket_detalle
```sql
id_detalle        INT PRIMARY KEY
id_ticket         INT FK → tickets_sin_facturar
descripcion       VARCHAR
cantidad          INT
precio_unitario   DECIMAL
importe           DECIMAL
```

### ticket_metodo_pago
```sql
id_pago           INT PRIMARY KEY
id_ticket         INT FK → tickets_sin_facturar
metodo_pago       VARCHAR
importe           DECIMAL
```

---

## 🎨 CSS Classes Usadas

```css
.card              /* Tarjetas */
.btn-primary       /* Botones primarios */
.btn-success       /* Botones de éxito */
.table             /* Tablas */
.alert             /* Alertas */
.spinner-border    /* Spinner de carga */
.form-control      /* Inputs */
.form-select       /* Selects */
```

---

## ⚠️ Errores Comunes

| Error | Causa | Solución |
|-------|-------|----------|
| "Sesión no válida" | Usuario no logueado | Loguearse de nuevo |
| "Sucursal no válida" | No pertenece al usuario | Verificar datos en BD |
| "Ticket no encontrado" | Datos incorrectos | Verificar folio, monto, fecha |
| "Ya facturado" | Ticket tiene factura | Contactar soporte |
| "CORS error" | Ruta incorrecta | Verificar URLs en JavaScript |
| "Undefined variable" | Sesión no inicializada | Verificar config.php |

---

## 🔍 Validaciones Críticas

### Frontend
```javascript
if (!folio || !monto || !fecha || !idSucursal) {
    // Campos obligatorios
}

if (isNaN(monto) || monto <= 0) {
    // Monto debe ser número positivo
}

if (new Date(fecha) > new Date()) {
    // Fecha no puede ser futura
}
```

### Backend
```php
if (!$id_usuario) {
    throw new Exception('Sesión no válida');
}

if (!$folio || !$monto || !$fecha || !$id_sucursal) {
    throw new Exception('Faltan datos');
}

if ($monto <= 0) {
    throw new Exception('Monto inválido');
}

// Verificar que sucursal pertenece al usuario
$sqlVerify = "SELECT id_empresa FROM empresas 
              WHERE id_empresa = ? AND id_usuario = ?";

// Buscar ticket exacto
$sqlBuscar = "SELECT ... 
              WHERE folio_ticket = ? 
              AND DATE(fecha_venta) = ?
              AND importe_t = ?
              AND id_empresa = ?";

// Verificar no facturado
if ($ticket['facturado'] > 0) {
    throw new Exception('Ya facturado');
}
```

---

## 📱 Responsive Design

```css
/* Mobile First */
@media (max-width: 576px) {
    .col-md-6    /* Stack vertically */
    .btn-lg      /* Botones grandes en móvil */
}

@media (min-width: 768px) {
    .col-md-6    /* Side by side */
    .d-md-flex   /* Flex en desktop */
}
```

---

## 🌍 URLs Relativas en JavaScript

```javascript
// Correcto (relativo a raíz)
fetch('/facturacion/core/buscar-ticket-cliente.php', {...})

// También funciona (si panel.php está en raíz)
fetch('./core/buscar-ticket-cliente.php', {...})

// En servidor productivo (sin /facturacion)
fetch('/core/buscar-ticket-cliente.php', {...})
```

---

## 🛠️ Debug Mode (Para Desarrolladores)

### Ver respuesta de API en consola
```javascript
// En facturar-cliente.inc.php
.then(data => {
    console.log('Respuesta API:', data); // Debug
    if (data.success) { ... }
})
```

### Ver errores de BD en servidor
```php
// En buscar-ticket-cliente.php
error_log("Debug: " . json_encode($datos));
// Ver en /logs o php_errors.log
```

### Inspeccionar HTML generado
```javascript
// En navegador (F12)
// Ver Elements → HTML generado dinámicamente
```

---

## 📊 Flujo de Datos (Resumido)

```
Usuario completa form
    ↓
JavaScript valida
    ↓
AJAX POST a buscar-ticket-cliente.php
    ↓
PHP valida sesión
    ↓
PHP busca en BD
    ↓
PHP retorna JSON
    ↓
JavaScript actualiza DOM
    ↓
Usuario ve resultados
```

---

## 🔐 Headers de Seguridad

```php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');

// Prepared statements
$stmt = $conn->prepare("SELECT ... WHERE id = ?");
$stmt->execute([$id]);

// Sin exposición de datos
try {
    // código
} catch (Exception $e) {
    // Log en servidor, no mostrar detalles al cliente
    error_log($e->getMessage());
    $respuesta['message'] = 'Error desconocido';
}
```

---

## 📈 Monitoreo

### Logs a revisar
```
/logs/php_errors.log    /* Errores PHP */
/logs/apache_error.log  /* Errores Apache */
Browser DevTools        /* Errores JavaScript */
```

### Queries SQL para debug
```sql
-- Ver tickets sin facturar
SELECT * FROM tickets_sin_facturar 
WHERE id_empresa = 1;

-- Ver si está facturado
SELECT COUNT(*) FROM facturas 
WHERE id_ticket = 1;

-- Ver detalles
SELECT * FROM ticket_detalle 
WHERE id_ticket = 1;
```

---

## 🚨 Checklist Final

Antes de poner en producción:
- [ ] Todos los archivos creados
- [ ] Permisos correctos (644/755)
- [ ] Base de datos con datos de prueba
- [ ] Búsqueda funciona
- [ ] Muestra detalles correctamente
- [ ] Generación de factura funciona
- [ ] Responsive en móvil
- [ ] Sin errores en consola
- [ ] Sin errores en logs
- [ ] Documentación leída
- [ ] Testing completado

---

## 🆘 Ayuda Rápida

**Documentación completa**: `FACTURACION_CLIENTES.md`
**Guía visual**: `GUIA_VISUAL.md`
**Resumen técnico**: `RESUMEN_IMPLEMENTACION.md`
**Implementación**: `IMPLEMENTACION_COMPLETADA.md`
**Datos prueba**: `INSTRUCCIONES_DATOS_PRUEBA.php`

---

## 📞 Contacto

Para preguntas o problemas:
1. Revisa la documentación (arriba)
2. Revisa los comentarios en el código
3. Ejecuta el checklist
4. Revisa los logs

**¡Listo para usar!** ✅
