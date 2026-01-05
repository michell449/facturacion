# ✅ Solución: Visualización de Sucursales

## Cambios Realizados

Se han realizado las siguientes mejoras en `pages/facturar-cliente.inc.php`:

### 1. **Mejora en la Interfaz de Sucursal**
- Agregado botón "Ver lista" al lado del select
- Permite abrir modal con vista alternativa de sucursales
- El select se llena automáticamente

### 2. **Rutas Relativas Corregidas**
- Cambio: `/facturacion/core/...` → `./core/...`
- Evita problemas de configuración de servidor
- Funciona en cualquier instalación

### 3. **Mejor Manejo de Errores**
- Consola de navegador ahora muestra debug info
- Mensajes de error más detallados
- Mejor validación de respuestas

### 4. **Mejoras en JavaScript**
- Función `cargarSucursales()` mejorada
- Mejor manejo de fetch con validación
- Console.log para debugging
- Mensajes de error más claros

---

## Cómo Ver si las Sucursales Cargan

### Opción 1: Ver en la Consola del Navegador

1. **Abre la página**: `panel?pg=facturar-cliente`
2. **Abre la consola**: Presiona `F12`
3. **Ve a la pestaña**: "Console"
4. **Deberías ver**:
   - Si carga: `Sucursales cargadas: {success: true, sucursales: [...]}`
   - Si hay error: El mensaje de error específico

### Opción 2: Ver en el Select

1. **Abre la página**: `panel?pg=facturar-cliente`
2. **Haz clic en el dropdown**: "-- Selecciona la sucursal --"
3. **Deberían aparecer**: Todas tus sucursales

### Opción 3: Ver en el Modal

1. **Abre la página**: `panel?pg=facturar-cliente`
2. **Haz clic en el botón**: 📋 (al lado del select)
3. **Deberían aparecer**: Sucursales en tarjetas clickeables

---

## Solución de Problemas

### Las sucursales no aparecen en el select

**Posible causa 1**: El usuario no tiene sucursales registradas
→ Solución: Crea una sucursal en la configuración

**Posible causa 2**: Error al cargar los datos
→ Solución: Abre consola (F12) y revisa el error

**Posible causa 3**: Problema de sesión
→ Solución: Inicia sesión nuevamente

### Ver detalles del error

Ejecuta en consola (F12):
```javascript
// Hacer clic en el botón de búsqueda
buscarTicket();

// Luego revisar la consola para ver el error exacto
```

---

## Verificar que Todo Funciona

### Checklist:

1. ☑ Abre la página `panel?pg=facturar-cliente`
2. ☑ Abre consola (F12)
3. ☑ Ve a pestaña "Console"
4. ☑ Deberías ver: `Sucursales cargadas:`
5. ☑ Haz clic en el select
6. ☑ Deberías ver las sucursales en el dropdown
7. ☑ Haz clic en el botón 📋
8. ☑ Deberías ver modal con sucursales
9. ☑ Haz clic en una sucursal del modal
10. ☑ El select debería actualizar su valor

---

## Cambios Técnicos

### Antes:
```php
<!-- Sin botón de modal -->
<select id="selectSucursal">
    <option>-- Selecciona --</option>
</select>
```

### Ahora:
```php
<!-- Con botón de modal -->
<div class="input-group">
    <select id="selectSucursal">
        <option>-- Selecciona --</option>
    </select>
    <button data-bs-toggle="modal" data-bs-target="#modalSucursalesCliente">
        📋
    </button>
</div>
```

### URLs actualizadas:
```javascript
// Antes
fetch('/facturacion/core/obtener-sucursales-cliente.php')

// Ahora
fetch('./core/obtener-sucursales-cliente.php')
```

---

## Próximos Pasos

1. **Prueba la página**
2. **Abre consola (F12)**
3. **Verifica que aparezcan las sucursales**
4. **Si hay error, comparte el mensaje de la consola**

---

## Contacto de Soporte

Si aún no ves las sucursales:
1. Abre consola (F12)
2. Ve a pestaña "Console"
3. Comparte el mensaje de error que ves

---

**Versión**: 1.1  
**Actualización**: Enero 2025
