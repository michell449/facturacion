# Implementación: Facturación para Clientes

## Resumen Ejecutivo

Se ha implementado un módulo completo que permite a los clientes facturar sus compras de forma autónoma. El cliente busca su ticket ingresando el folio, monto, fecha y sucursal, y si el ticket existe en la base de datos y no ha sido facturado, puede generar la factura electrónica.

## Archivos Creados

### 1. **core/buscar-ticket-cliente.php**
- **Propósito**: API que busca tickets en la base de datos según los parámetros del cliente
- **Método**: POST
- **Parámetros**:
  - `folio`: Folio del ticket
  - `monto`: Monto exacto de la compra
  - `fecha`: Fecha de la compra (YYYY-MM-DD)
  - `id_sucursal`: ID de la sucursal donde se compró
- **Validaciones**:
  - Sesión válida del usuario
  - Todos los parámetros requeridos
  - Monto > 0
  - Fecha válida
  - Sucursal pertenece al usuario
  - Ticket existe en BD
  - Ticket no está facturado
- **Retorno**: JSON con información completa del ticket (detalles, métodos de pago, resumen)

### 2. **core/obtener-sucursales-cliente.php**
- **Propósito**: API que retorna las sucursales del cliente autenticado
- **Método**: POST
- **Retorno**: JSON con lista de sucursales (id_empresa, nombre, código_suc)

### 3. **pages/facturar-cliente.inc.php**
- **Propósito**: Página principal de facturación para clientes
- **Características**:
  - Formulario de búsqueda interactivo con validación frontend
  - Modal para seleccionar sucursal
  - Visualización completa del ticket encontrado
  - Tabla de detalles de compra
  - Resumen de montos (subtotal, IVA, total)
  - Tabla de métodos de pago
  - Botones de acción (Nueva Búsqueda, Generar Factura)
  - Spinner de carga
  - Alertas visuales para el usuario
  - Integración con generar-factura.php

### 4. **pages/header.inc.php** (Actualizado)
- **Cambios**: Actualización del menú de navegación
  - Antes: "Facturar" → panel?pg=facturar
  - Ahora: "Facturar mis Compras" → panel?pg=facturar-cliente

### 5. **FACTURACION_CLIENTES.md**
- Documentación completa del módulo
- Descripción del flujo
- Arquitectura técnica
- Ejemplos de APIs
- Validaciones implementadas
- Instrucciones para clientes
- Troubleshooting

### 6. **core/INSTRUCCIONES_DATOS_PRUEBA.php**
- Ejemplos SQL para insertar tickets de prueba
- Instrucciones detalladas de cómo probar el módulo
- Datos de ejemplo para búsquedas de prueba

## Flujo de Funcionamiento

```
1. CLIENTE INGRESA A "FACTURAR MIS COMPRAS"
   ↓
2. COMPLETA FORMULARIO CON:
   - Folio del ticket
   - Monto exacto
   - Fecha de compra
   - Selecciona sucursal
   ↓
3. HACE CLIC EN "BUSCAR TICKET"
   ↓
4. FRONTEND VALIDA DATOS (JS)
   ↓
5. ENVÍA SOLICITUD A buscar-ticket-cliente.php
   ↓
6. BACKEND VALIDA:
   - Sesión válida
   - Parámetros correctos
   - Sucursal pertenece al usuario
   - Ticket existe
   - No está facturado
   ↓
7. SI TODO OK:
   - Obtiene detalles del ticket
   - Obtiene métodos de pago
   - Retorna JSON
   ↓
8. FRONTEND MUESTRA:
   - Información del ticket
   - Tabla de artículos
   - Resumen de montos
   - Métodos de pago
   ↓
9. CLIENTE VERIFICA INFORMACIÓN
   ↓
10. HACE CLIC EN "GENERAR FACTURA"
    ↓
11. SOLICITUD A generar-factura.php
    ↓
12. BACKEND:
    - Valida datos nuevamente
    - Crea XML CFDI
    - Timbra con SAT
    - Genera PDF
    - Registra factura
    ↓
13. RETORNA FOLIO DE FACTURA
    ↓
14. FRONTEND MUESTRA CONFIRMACIÓN
    ↓
15. CLIENTE PUEDE DESCARGAR O COMPARTIR
```

## Seguridad Implementada

✅ **Autenticación**:
- Validación obligatoria de sesión en todos los APIs
- Solo se pueden consultar tickets propios (verificación por usuario)

✅ **Autorización**:
- Los tickets solo se pueden buscar si pertenecen a las sucursales del usuario
- Validación cruzada de id_empresa con id_usuario

✅ **Validación de Datos**:
- Prepared statements para evitar SQL injection
- Validación de tipos (string, number, date)
- Validación de rangos (monto > 0)
- Validación de formatos

✅ **Manejo de Errores**:
- Sin exposición de información sensible
- Mensajes de error genéricos al usuario
- Logging en el servidor para debugging

✅ **Headers de Seguridad**:
- Content-Type: application/json
- Cache-Control: no-cache

## Base de Datos

Usa las tablas existentes:
- `tickets_sin_facturar` (búsqueda principal)
- `ticket_detalle` (artículos comprados)
- `ticket_metodo_pago` (métodos de pago)
- `facturas` (verificación de facturación)
- `empresas` (sucursales del usuario)

No requiere creación de nuevas tablas.

## Características del Frontend

✨ **Interfaz Moderna**:
- Diseño responsive (mobile-first)
- Cards con sombras y bordes redondeados
- Iconos Bootstrap Icons
- Colores consistentes (primario: #0d6efd)

✨ **Validaciones**:
- Validación de campos requeridos
- Validación de formato de monto (número decimal)
- Validación de fecha (no futuras)
- Validación de sucursal seleccionada

✨ **Interactividad**:
- Búsqueda en tiempo real
- Spinner de carga mientras busca
- Alertas flotantes (toast style)
- Modal para seleccionar sucursal
- Botones con estados (habilitado/deshabilitado)

✨ **Información Completa**:
- Vista completa del ticket
- Tabla de detalles con formatos de moneda
- Resumen financiero
- Métodos de pago

## Testing/Pruebas

### Datos de Prueba Proporcionados

El archivo `INSTRUCCIONES_DATOS_PRUEBA.php` incluye:
- Scripts SQL para insertar tickets de prueba
- 3 ejemplos completos con detalles y métodos de pago
- Instrucciones paso a paso
- Datos para búsqueda de prueba

### Casos de Prueba Recomendados

1. **Búsqueda Exitosa**:
   - Insertar ticket de prueba
   - Buscar con datos correctos
   - Verificar que muestra detalles

2. **Validaciones**:
   - Buscar sin llenar campos (debe rechazar)
   - Buscar con monto incorrecto (debe no encontrar)
   - Buscar con fecha incorrecta (debe no encontrar)
   - Buscar con sucursal incorrecta (debe no encontrar)

3. **Ticket Facturado**:
   - Marcar un ticket como facturado
   - Intentar buscarlo (debe mostrar error)

4. **Generación de Factura**:
   - Buscar ticket válido
   - Generar factura
   - Verificar que se registra correctamente

## URLs de Acceso

- **Página Principal**: `panel?pg=facturar-cliente`
- **API Búsqueda**: `core/buscar-ticket-cliente.php` (POST)
- **API Sucursales**: `core/obtener-sucursales-cliente.php` (POST)

## Dependencias

- PHP 7.4+ (uso de características modernas)
- PDO (conexión a base de datos)
- Bootstrap 5 (CSS)
- Bootstrap Icons (iconos)
- generar-factura.php (para la generación de factura)

## Configuración Necesaria

Debe existir:
1. Tabla `tickets_sin_facturar` con estructura correcta
2. Tabla `ticket_detalle` para los artículos
3. Tabla `ticket_metodo_pago` para los métodos
4. Tabla `empresas` con relación a usuarios
5. Clase `Database` en `class/db.php`
6. Script `config.php` en raíz
7. API `generar-factura.php` funcional

## Próximas Mejoras Sugeridas

1. **Búsqueda Avanzada**:
   - Filtro por rango de fechas
   - Búsqueda por nombre del cliente
   - Listado de tickets sin facturar

2. **Notificaciones**:
   - Email con factura PDF
   - Confirmación de facturación
   - Alertas de facturas pendientes

3. **Reportes**:
   - Historial de facturas generadas
   - Resumen por período
   - Exportación a Excel

4. **Documentos**:
   - Descarga de PDF
   - Descarga de XML
   - Envío de correo

5. **Integración**:
   - Búsqueda por número de cliente
   - Integración con carrito de compras
   - Facturación masiva

## Notas Importantes

⚠️ **Importante**:
- El folio debe ser **único** por sucursal
- El monto debe ser **exacto** (incluyendo decimales)
- La búsqueda es **case-sensitive** para el folio
- Un ticket solo puede facturarse **una vez**
- La sesión debe estar **activa** para acceder

## Cambios en Archivos Existentes

### header.inc.php
```diff
- <a class="nav-link ... " href="panel?pg=facturar">
-     <i class="bi bi-receipt-cutoff me-2"></i> Facturar
+ <a class="nav-link ... " href="panel?pg=facturar-cliente">
+     <i class="bi bi-receipt-cutoff me-2"></i> Facturar mis Compras
```

Solo se cambió:
- El texto del link ("Facturar" → "Facturar mis Compras")
- El parámetro `pg` ("facturar" → "facturar-cliente")
- La condición de activo también se actualiza automáticamente

## Conclusión

Se ha implementado un módulo completo y funcional de facturación para clientes que:
- ✅ Permite a los clientes buscar sus tickets
- ✅ Valida que el ticket exista y no esté facturado
- ✅ Muestra información detallada del ticket
- ✅ Permite generar la factura electrónica
- ✅ Es seguro y está protegido contra ataques comunes
- ✅ Tiene interfaz moderna y responsive
- ✅ Incluye validaciones completas
- ✅ Está documentado y listo para pruebas

El cliente ahora puede facturar autónomamente sin necesidad de intermediarios.
