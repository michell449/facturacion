# 🚀 Guía Rápida: Facturación desde Detalle de Ticket

## ¿Cómo Funciona?

Cuando el usuario hace clic en **"Generar Factura"** desde la página de detalle del ticket:

1. Se cargan automáticamente los datos fiscales del usuario desde la BD
2. Se extraen los detalles del ticket (productos/servicios, cantidades, precios)
3. Se genera la factura en la base de datos
4. Se crea el archivo XML (sellado con CSD)
5. Se timbra el XML con el PAC Finkok
6. ¡Listo! Factura generada y timbrada

---

## Archivo Principal: `pages/detalle-ticket.inc.php`

### Función Clave: `async function facturarTicket()`

**Ubicación:** Líneas 326-503

**Lo que hace:**
```javascript
// Paso 1: Validar ticket
// Paso 2: Cargar datos fiscales
// Paso 3: Preparar conceptos
// Paso 4: Generar factura (BD)
// Paso 5: Generar XML
// Paso 6: Timbrar con Finkok
```

---

## Nuevo Archivo: `core/obtener-datos-fiscales-usuario.php`

**Endpoint:** `./core/obtener-datos-fiscales-usuario.php`

**Método:** POST/GET

**Retorna:**
```json
{
  "success": true,
  "data": {
    "rfc": "...",
    "razon_social": "...",
    "regimen_fiscal": "...",
    "cp": "...",
    "tipo_persona": "..."
  }
}
```

---

## Tablas de Base de Datos Utilizadas

### Lectura:
- `datos_fiscales_usuario` - Datos del usuario que factura
- `tickets` - Información del ticket
- `empresas` - Datos de la sucursal

### Escritura:
- `facturas` - Nueva factura creada
- `facturas_detalles` - Detalles de la factura
- `config_facturas` - Actualiza folio

---

## Validaciones Automáticas

✅ Ticket tiene detalles  
✅ Ticket tiene métodos de pago  
✅ Usuario tiene datos fiscales completos  
✅ RFC válido (12 o 13 caracteres)  
✅ Datos de pago válidos  

---

## Valores Predeterminados

| Campo | Valor |
|-------|-------|
| Clave SAT | `01010101` |
| Unidad SAT | `H87` (Pieza) |
| Uso CFDI | `G01` (Adquisición) |
| IVA | 16% |

---

## Personalización

### Cambiar Uso CFDI
Edita la línea en `facturarTicket()`:
```javascript
const usoCFDI = 'G01'; // Cambiar a otro código
```

**Códigos disponibles:**
- `G01` - Adquisición de mercancías
- `D01` - Honorarios médicos
- `S01` - Sin efectos fiscales
- Ver tabla completa en `pages/crear-factura.inc.php`

### Cambiar Forma/Método de Pago
```javascript
// Línea ~398
const formaPago = '01'; // 01=Efectivo, 03=Transferencia, etc.
const metodoPago = 'PUE'; // PUE=Una exhibición, PPD=Diferido
```

---

## Estructura de Datos Enviados

```javascript
{
  id_ticket: 12345,
  id_sucursal: 1,
  receptor: {
    rfc: "XXX000000000",
    nombre: "EMPRESA SA DE CV",
    regimen: "601",
    cp: "28000",
    uso_cfdi: "G01"
  },
  forma_pago: "01",
  metodo_pago: "PUE",
  conceptos: [
    {
      clave: "01010101",
      descripcion: "Producto",
      cantidad: 2,
      precio: 100,
      unidad: "H87"
    }
  ]
}
```

---

## Depuración

### Ver detalles en consola del navegador:
```javascript
// Abre DevTools (F12) → Consola
// Busca logs de:
// - "Cargando datos fiscales"
// - "Error en facturarTicket"
```

### Ver errores del servidor:
```bash
# En el servidor, revisar:
tail -f /var/log/php-errors.log

# O en Windows/XAMPP:
# c:\xampp\php\logs\php_errors.log
```

---

## Integración con Otros Módulos

**Usa automáticamente:**
- ✓ `core/generar-factura.php` - Guarda en BD
- ✓ `core/generar-xml.php` - Crea XML
- ✓ `core/timbrar-xml.php` - Timbra con Finkok
- ✓ `api/FinkokApi.php` - API del PAC

**No requiere instalación adicional** - Reutiliza código existente

---

## ⚠️ Requisitos Previos

1. **Usuario debe tener datos fiscales registrados:**
   ```sql
   SELECT * FROM datos_fiscales_usuario WHERE id_usuario = 123;
   ```

2. **Sucursal debe tener configuración de facturación:**
   ```sql
   SELECT * FROM config_facturas 
   WHERE id_usuario = 123 AND id_sucursal = 1;
   ```

3. **Finkok debe estar configurado** en `config.php`

4. **CSD (Certificados digitales) deben estar instalados** en la sucursal

---

## 🐛 Posibles Errores y Soluciones

| Error | Causa | Solución |
|-------|-------|----------|
| "No hay datos fiscales" | Usuario sin registro | Crear datos en admin |
| "RFC inválido" | RFC con formato incorrecto | Verificar RFC en BD |
| "Error al generar XML" | CSD no encontrado | Verificar ruta de certificados |
| "Error de timbrado" | Finkok rechaza | Verificar credenciales Finkok |

---

## 📞 Soporte

Para problemas:
1. Revisar logs en consola (F12)
2. Verificar datos en BD
3. Validar credenciales de Finkok
4. Contactar con SAT para validaciones XML

---

**Última actualización:** 4 de Enero de 2026
