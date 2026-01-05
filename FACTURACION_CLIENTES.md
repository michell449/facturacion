# Guía de Facturación para Clientes

## Descripción General

Se ha implementado un nuevo módulo de facturación para que los clientes puedan facturar sus compras de forma sencilla y segura. El sistema permite buscar un ticket de compra y generar su correspondiente factura electrónica.

## Flujo de Facturación para Clientes

### 1. Búsqueda de Ticket

El cliente accede a la sección "Facturar mis Compras" desde el menú principal y debe proporcionar la siguiente información:

- **Folio del Ticket**: Número de venta/folio que aparece en su ticket de compra
- **Monto Total**: Cantidad exacta de la compra (con decimales)
- **Fecha de Compra**: Fecha en la que se realizó la compra
- **Sucursal**: Lugar donde se realizó la compra (seleccionada de una lista)

### 2. Validación del Ticket

El sistema busca en la base de datos `tickets_sin_facturar` un registro que coincida con:
- El folio ingresado
- La fecha exacta
- El monto exacto
- La sucursal seleccionada
- El usuario propietario de la sucursal

### 3. Verificación de Facturación

Si el ticket se encuentra, el sistema verifica que:
- El ticket aún **no ha sido facturado** (no existe en la tabla `facturas`)
- Tiene información completa (detalles, métodos de pago)

### 4. Visualización de Detalles

Una vez encontrado, se muestran:
- **Información del Ticket**:
  - Folio del ticket
  - Fecha de venta
  - Sucursal y código de sucursal
  
- **Detalles de la Compra**:
  - Tabla con descripción, cantidad, precio unitario e importe de cada artículo
  
- **Resumen Financiero**:
  - Subtotal
  - IVA (16%)
  - Total a facturar
  
- **Métodos de Pago**:
  - Tabla con los métodos de pago utilizados y sus importes

### 5. Generación de Factura

El cliente puede hacer clic en el botón "Generar Factura" para:
- Crear el CFDI (Comprobante Fiscal Digital por Internet)
- Generar el XML con toda la información
- Timbrar el documento con el SAT
- Generar el PDF para descarga
- Registrar la factura en el sistema

## Arquitectura Técnica

### Archivos Creados/Modificados

#### 1. **core/buscar-ticket-cliente.php**
API encargada de buscar el ticket en la base de datos.

**Parámetros POST:**
- `folio`: Folio del ticket
- `monto`: Monto total de la compra
- `fecha`: Fecha de la compra (formato YYYY-MM-DD)
- `id_sucursal`: ID de la empresa/sucursal

**Respuesta JSON:**
```json
{
  "success": true,
  "message": "Ticket encontrado.",
  "ticket": {
    "id_ticket": 123,
    "id_empresa": 5,
    "folio": "123456789",
    "fecha_venta": "2025-01-01",
    "sucursal": "Sucursal Centro",
    "codigo_sucursal": "001",
    "subtotal": 1000.00,
    "impuesto": 160.00,
    "total": 1160.00,
    "detalles": [
      {
        "id_detalle": 1,
        "descripcion": "Producto XYZ",
        "cantidad": 2,
        "precio_unitario": 500.00,
        "importe": 1000.00
      }
    ],
    "pagos": [
      {
        "id_pago": 1,
        "metodo_pago": "Efectivo",
        "importe": 1160.00
      }
    ]
  }
}
```

#### 2. **core/obtener-sucursales-cliente.php**
API que retorna la lista de sucursales del cliente autenticado.

**Respuesta JSON:**
```json
{
  "success": true,
  "sucursales": [
    {
      "id_empresa": 5,
      "nombre": "Sucursal Centro",
      "codigo_suc": "001"
    },
    {
      "id_empresa": 6,
      "nombre": "Sucursal Norte",
      "codigo_suc": "002"
    }
  ]
}
```

#### 3. **pages/facturar-cliente.inc.php**
Página principal de facturación para clientes. Incluye:
- Formulario de búsqueda interactivo
- Modal para seleccionar sucursal
- Visualización de detalles del ticket encontrado
- Botones de acción (Nueva Búsqueda, Generar Factura)
- JavaScript para manejar toda la lógica del frontend

#### 4. **pages/header.inc.php** (Actualizado)
Se actualizó el menú de navegación para cambiar:
- "Facturar" → "Facturar mis Compras"
- Link actualizado a `facturar-cliente`

### Tabla de Base de Datos

Se utiliza la tabla `tickets_sin_facturar`:

```sql
CREATE TABLE `tickets_sin_facturar` (
    `id_ticket` INT(10) NOT NULL AUTO_INCREMENT,
    `id_empresa` INT(5) NOT NULL,
    `folio_ticket` VARCHAR(10) NOT NULL,
    `fecha_venta` DATE NOT NULL,
    `importe_t` DECIMAL(15,2) NOT NULL,
    `subtotal` DECIMAL(15,2) NOT NULL,
    `impuesto_t` DECIMAL(15,2) NOT NULL,
    `estatus` TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id_ticket`),
    INDEX `fk_tickets_sin_facturar_empresas_idx` (`id_empresa`),
    CONSTRAINT `fk_tickets_sin_facturar_empresas` FOREIGN KEY (`id_empresa`) 
        REFERENCES `empresas`(`id_empresa`) ON DELETE CASCADE ON UPDATE CASCADE
) COLLATE='utf8mb4_general_ci' ENGINE=InnoDB;
```

Relaciones:
- `tickets_sin_facturar.id_empresa` ← `empresas.id_empresa`
- `ticket_detalle.id_ticket` ← `tickets_sin_facturar.id_ticket`
- `ticket_metodo_pago.id_ticket` ← `tickets_sin_facturar.id_ticket`
- `facturas.id_ticket` ← `tickets_sin_facturar.id_ticket` (para verificar si ya fue facturado)

## Validaciones Implementadas

### Frontend (JavaScript)
- ✅ Validación de campos requeridos
- ✅ Validación de formato numérico para monto
- ✅ Validación de formato de fecha
- ✅ Fecha máxima = hoy (no se pueden facturar compras futuras)
- ✅ Selección obligatoria de sucursal

### Backend (PHP)
- ✅ Validación de sesión
- ✅ Validación de datos requeridos
- ✅ Validación de monto positivo
- ✅ Validación de fecha con formato correcto
- ✅ Verificación de que la sucursal pertenece al usuario
- ✅ Búsqueda exacta del ticket (folio + fecha + monto + sucursal)
- ✅ Verificación de que no esté ya facturado
- ✅ Obtención segura de detalles y métodos de pago

## Flujo de Datos

```
Cliente │
        │ 1. Ingresa datos de búsqueda
        ↓
    Frontend (JavaScript)
        │
        ├─ Valida campos
        └─ Envía POST a buscar-ticket-cliente.php
                     │
                     ↓
            Backend (PHP)
                │
                ├─ Valida sesión
                ├─ Valida parámetros
                ├─ Verifica sucursal pertenece al usuario
                ├─ Busca ticket en BD
                ├─ Verifica no esté facturado
                ├─ Obtiene detalles
                ├─ Obtiene métodos de pago
                └─ Retorna JSON
                     │
                     ↓
                  Frontend (JavaScript)
                     │
                     ├─ Muestra información del ticket
                     ├─ Muestra tabla de detalles
                     ├─ Muestra resumen de montos
                     ├─ Muestra métodos de pago
                     └─ Habilita botón "Generar Factura"
                        │
                        ├─ Usuario hace clic en "Generar Factura"
                        └─ Envía POST a generar-factura.php
```

## Seguridad

- ✅ Todas las solicitudes validadas contra sesión del usuario
- ✅ Los tickets solo se pueden consultar si pertenecen a las sucursales del usuario
- ✅ Uso de prepared statements para prevenir SQL injection
- ✅ Validación de tipos de datos
- ✅ Headers JSON seguros
- ✅ Gestión de errores sin exponer información sensible

## Mejoras Futuras

1. **Búsqueda Avanzada**:
   - Filtro por rango de fechas
   - Búsqueda por nombre del cliente
   - Filtro por estado de facturación

2. **Historial de Búsquedas**:
   - Guardar búsquedas recientes
   - Acceso rápido a tickets frecuentes

3. **Notificaciones**:
   - Confirmar envío de factura por email
   - Notificar cuando se facture correctamente

4. **Descarga de Documentos**:
   - Descarga de PDF después de facturar
   - Descarga de XML
   - Descarga de comprobante de timbrado

5. **Reportes**:
   - Historial de tickets facturados
   - Resumen de montos facturados por período

## Instrucciones para el Cliente

1. **Acceder al Sistema**:
   - Inicia sesión en el panel de facturación

2. **Navegar a Facturación**:
   - En el menú superior, haz clic en "Facturar mis Compras"

3. **Buscar Ticket**:
   - Ingresa el folio del ticket (número de venta)
   - Ingresa el monto exacto de la compra
   - Selecciona la fecha de la compra
   - Selecciona la sucursal donde compraste
   - Haz clic en "Buscar Ticket"

4. **Verificar Información**:
   - Revisa que todos los detalles sean correctos
   - Verifica el listado de artículos comprados
   - Confirma el monto total a facturar

5. **Generar Factura**:
   - Haz clic en "Generar Factura"
   - Confirma en el diálogo de confirmación
   - Espera a que se procese (puede tomar algunos segundos)
   - Verás un mensaje de confirmación con el folio de la factura

6. **Descargar o Compartir**:
   - Usa la opción de descarga si está disponible
   - Comparte el PDF con quien corresponda

## Troubleshooting

### Ticket no encontrado
- Verifica que el folio esté correcto (sin espacios)
- Confirma que el monto coincida exactamente
- Asegúrate de haber seleccionado la sucursal correcta
- Verifica que la fecha sea correcta

### Error "Sesión no válida"
- Tu sesión ha expirado
- Inicia sesión nuevamente
- Recarga la página si es necesario

### Error "Este ticket ya ha sido facturado"
- Este ticket ya tiene una factura asociada
- Si necesitas una factura adicional, contacta a soporte

### Error al generar factura
- Verifica tu conexión a internet
- Intenta nuevamente después de unos segundos
- Si el problema persiste, contacta a soporte técnico
