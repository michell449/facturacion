# Implementación de Facturación Automática desde Detalle de Ticket

## Resumen de Cambios

Se ha implementado la funcionalidad para generar facturas automáticamente desde la página de detalle de ticket, con auto-llenado de datos fiscales del usuario y procesamiento completo mediante Finkok.

---

## Archivos Creados

### 1. `core/obtener-datos-fiscales-usuario.php` ✓
**Propósito:** Endpoint PHP que obtiene los datos fiscales registrados del usuario logueado.

**Características:**
- Valida la sesión del usuario
- Obtiene datos de la tabla `datos_fiscales_usuario`
- Retorna JSON con información:
  - RFC
  - Razón Social
  - Régimen Fiscal
  - Código Postal
  - Tipo de Persona (Física/Moral)
  - Domicilio (calle, número exterior, interior, colonia)

**Respuesta:**
```json
{
  "success": true,
  "message": "Datos fiscales obtenidos correctamente",
  "data": {
    "rfc": "XXX000000000",
    "razon_social": "EMPRESA S.A. DE C.V.",
    "regimen_fiscal": "601",
    "cp": "28000",
    "tipo_persona": "Moral",
    "calle": "Calle Principal",
    "num_ext": "123",
    "num_int": "A",
    "colonia": "Centro"
  }
}
```

---

## Archivos Modificados

### 1. `pages/detalle-ticket.inc.php` ✓

#### Función: `facturarTicket()` (COMPLETAMENTE REESCRITA)

**Cambios principales:**
1. Convertida a función `async` para manejo de promesas
2. Agregadas validaciones robustas:
   - Verifica que el ticket tenga detalles
   - Verifica que el ticket tenga información de pago
   - Valida completitud de datos fiscales

3. **Proceso de 8 pasos:**
   - **Paso 1:** Carga datos fiscales del usuario
   - **Paso 2:** Prepara conceptos desde detalles del ticket
   - **Paso 3:** Obtiene forma y método de pago
   - **Paso 4:** Define uso CFDI (por defecto: G01 - Adquisición de mercancías)
   - **Paso 5:** Construye objeto `datosFactura` completo
   - **Paso 6:** Envía a `core/generar-factura.php`
   - **Paso 7:** Genera XML mediante `core/generar-xml.php`
   - **Paso 8:** Timbra con Finkok mediante `core/timbrar-xml.php`

4. **Manejo de errores mejorado:**
   - Try-catch completo
   - Mensajes de error específicos por paso
   - Validación de respuestas HTTP
   - Detección de errores de conexión

5. **Experiencia de usuario:**
   - Alertas informativas en cada paso
   - Confirmación con detalles de ticket (folio e importe)
   - Deshabilitación de botón durante procesamiento
   - Redirect automático después de éxito

#### Función: `mostrarTicket()`
- Mejorada presentación de métodos de pago con estilos nuevos
- Iconos adicionales para mejor visualización

---

## Flujo de Facturación Automática

```
┌─────────────────────────────────────────────────────────┐
│ Usuario hace clic en "Generar Factura"                  │
└────────────────┬────────────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────────────┐
│ Validaciones Preliminares                               │
│ ✓ Ticket existe                                         │
│ ✓ Hay detalles                                          │
│ ✓ Hay información de pago                               │
└────────────────┬────────────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────────────┐
│ 1. obtener-datos-fiscales-usuario.php                  │
│    ↓ Obtiene RFC, razón social, régimen, CP             │
└────────────────┬────────────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────────────┐
│ 2. Prepara Conceptos                                    │
│    ↓ Construye array con detalles del ticket            │
└────────────────┬────────────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────────────┐
│ 3. Obtiene Datos de Pago                                │
│    ↓ Usa forma/método del ticket o defaults             │
└────────────────┬────────────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────────────┐
│ 4. core/generar-factura.php                             │
│    ↓ Guarda factura en BD                               │
│    ↓ Actualiza folio                                    │
│    ↓ Retorna id_factura                                 │
└────────────────┬────────────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────────────┐
│ 5. core/generar-xml.php                                 │
│    ↓ Genera XML con datos de la factura                 │
│    ↓ Sella con CSD (certificados digitales)             │
│    ↓ Guarda ruta en BD                                  │
└────────────────┬────────────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────────────┐
│ 6. core/timbrar-xml.php (Finkok)                        │
│    ↓ Envía XML al PAC Finkok                            │
│    ↓ Recibe CFDI timbrado                               │
│    ↓ Guarda XML timbrado                                │
│    ↓ Actualiza BD con UUID                              │
└────────────────┬────────────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────────────┐
│ ✓ Éxito - Factura Generada y Timbrada                   │
│   • Muestra folio y UUID                                │
│   • Redirige a búsqueda                                 │
└─────────────────────────────────────────────────────────┘
```

---

## Datos que se Envían a `generar-factura.php`

```json
{
  "id_ticket": 12345,
  "id_sucursal": 1,
  "receptor": {
    "rfc": "XXX000000000",
    "nombre": "EMPRESA S.A. DE C.V.",
    "regimen": "601",
    "cp": "28000",
    "uso_cfdi": "G01"
  },
  "forma_pago": "01",
  "metodo_pago": "PUE",
  "conceptos": [
    {
      "clave": "01010101",
      "descripcion": "Producto/Servicio",
      "cantidad": 2,
      "precio": 150.00,
      "unidad": "H87"
    }
  ],
  "observaciones": "Facturado desde ticket #12345"
}
```

---

## Validaciones Implementadas

### Backend (PHP)
- ✓ Sesión válida del usuario
- ✓ Datos fiscales completos
- ✓ RFC, razón social, régimen fiscal válidos
- ✓ Conceptos válidos (cantidad > 0, precio > 0)
- ✓ Forma y método de pago válidos

### Frontend (JavaScript)
- ✓ Ticket existe
- ✓ Hay detalles en el ticket
- ✓ Hay métodos de pago
- ✓ Validación de respuestas JSON
- ✓ Manejo de errores HTTP

---

## Configuración Predeterminada

| Campo | Valor Default | Notas |
|-------|--------------|-------|
| Clave SAT (Concepto) | `01010101` | Puede personalizarse por concepto |
| Unidad | `H87` | Pieza (según SAT) |
| Uso CFDI | `G01` | Adquisición de mercancías |
| Forma Pago | Del ticket o `01` | Efectivo como fallback |
| Método Pago | Del ticket o `PUE` | Pago en una Exhibición |
| IVA | 16% | Calculado automáticamente |

---

## Mejoras en UX

1. **Alertas Progresivas:** El usuario ve cada paso del proceso
2. **Confirmación Detallada:** Muestra folio e importe antes de facturar
3. **Botón con Spinner:** Feedback visual mientras se procesa
4. **Mensajes de Error Claros:** Indica exactamente dónde falló
5. **Redirect Automático:** Vuelve a búsqueda después de éxito
6. **Diseño Azul y Blanco:** Tablas mejoradas con el esquema de colores

---

## Pruebas Recomendadas

1. **Verificar datos fiscales:**
   - Confirmar que el usuario tiene datos en `datos_fiscales_usuario`
   - Validar que todos los campos están completos

2. **Generar factura desde ticket:**
   - Buscar un ticket con detalles y pagos
   - Hacer clic en "Generar Factura"
   - Verificar que se crea en BD correctamente

3. **Validar XML:**
   - Confirmar que el XML se genera
   - Verificar estructura con validador SAT

4. **Timbrado Finkok:**
   - Asegurar credenciales de Finkok correctas
   - Verificar que se recibe UUID
   - Guardar XML timbrado correctamente

---

## Archivos Relacionados Existentes

- ✓ `core/generar-factura.php` - Guarda factura en BD
- ✓ `core/generar-xml.php` - Genera XML con CSD
- ✓ `core/timbrar-xml.php` - Timbra con Finkok
- ✓ `api/FinkokApi.php` - Interfaz con PAC
- ✓ `core/sello-utils.php` - Utilidades de sello digital

---

**Fecha de Implementación:** 4 de Enero de 2026
**Estado:** ✓ Completado
