# Guía Visual: Facturación para Clientes

## 1. Pantalla de Búsqueda

```
┌─────────────────────────────────────────────────────────────────┐
│                                                                 │
│   📄 Facturar mis Compras                                      │
│   Busca tu ticket de compra y genera la factura electrónica   │
│                                                                 │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  🔍 Encuentra tu Compra                                         │
│                                                                 │
│  Ingresa los datos de tu ticket de compra                       │
│                                                                 │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │                                                          │  │
│  │  # Número de Folio                                      │  │
│  │  ┌────────────────────────────────────────────────────┐ │  │
│  │  │ Ej: 123456789                                     │ │  │
│  │  └────────────────────────────────────────────────────┘ │  │
│  │  Encontrarás este número en tu ticket de compra          │  │
│  │                                                          │  │
│  │  💰 Monto Total              📅 Fecha de Compra         │  │
│  │  ┌────────────────────────┐  ┌───────────────────────┐  │  │
│  │  │ $ │ 0.00              │  │ YYYY-MM-DD            │  │  │
│  │  └────────────────────────┘  └───────────────────────┘  │  │
│  │                                                          │  │
│  │  🏢 Sucursal de Compra                                  │  │
│  │  ┌────────────────────────────────────────────────────┐ │  │
│  │  │ -- Selecciona la sucursal --                   ▼   │ │  │
│  │  └────────────────────────────────────────────────────┘ │  │
│  │  Elige la sucursal donde realizaste tu compra            │  │
│  │                                                          │  │
│  │  ┌────────────────────────────────────────────────────┐ │  │
│  │  │           🔍 Buscar Ticket                        │ │  │
│  │  └────────────────────────────────────────────────────┘ │  │
│  │                                                          │  │
│  └──────────────────────────────────────────────────────────┘  │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

## 2. Pantalla de Carga

```
┌─────────────────────────────────────────────────────────────────┐
│                                                                 │
│                                                                 │
│                    ╱  ╲                                        │
│                   ╱    ╲      Buscando tu ticket...            │
│                   ╲    ╱                                        │
│                    ╲  ╱                                         │
│                                                                 │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

## 3. Pantalla de Resultados

```
┌─────────────────────────────────────────────────────────────────┐
│                                                                 │
│  ✅ Ticket Encontrado                                          │
│                                                                 │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌──────────────────┐  ┌──────────────────┐                   │
│  │ Folio            │  │ Fecha de Venta   │                   │
│  │ 123456789        │  │ 1 enero, 2025    │                   │
│  └──────────────────┘  └──────────────────┘                   │
│                                                                 │
│  ┌──────────────────┐  ┌──────────────────┐                   │
│  │ Sucursal         │  │ Código Sucursal  │                   │
│  │ Centro           │  │ 001              │                   │
│  └──────────────────┘  └──────────────────┘                   │
│                                                                 │
│  ─────────────────────────────────────────────────────────     │
│                                                                 │
│  📋 Detalles de la Compra                                      │
│                                                                 │
│  ┌────────────────────┬──────┬────────┬─────────────────────┐ │
│  │ Descripción        │ Qty  │ Precio │ Importe             │ │
│  ├────────────────────┼──────┼────────┼─────────────────────┤ │
│  │ Laptop Dell XPS 13 │  1   │$1,500  │ $1,500.00           │ │
│  │ Monitor LG 27"     │  1   │$1,000  │ $1,000.00           │ │
│  │ Teclado Mecánico   │  1   │  $500  │   $500.00           │ │
│  └────────────────────┴──────┴────────┴─────────────────────┘ │
│                                                                 │
│  ─────────────────────────────────────────────────────────     │
│                                                                 │
│                                    Subtotal:  $ 3,000.00       │
│                                    IVA (16%): $   480.00       │
│                                    ─────────────────────────   │
│                                    Total:     $ 3,480.00       │
│                                                                 │
│  ─────────────────────────────────────────────────────────     │
│                                                                 │
│  💳 Métodos de Pago                                            │
│                                                                 │
│  ┌────────────────────────────────────────────┬──────────────┐ │
│  │ Método                                     │ Importe      │ │
│  ├────────────────────────────────────────────┼──────────────┤ │
│  │ Tarjeta de Crédito                        │ $ 3,480.00   │ │
│  └────────────────────────────────────────────┴──────────────┘ │
│                                                                 │
│  ┌─────────────────────┐  ┌──────────────────────────────────┐ │
│  │ 🔄 Nueva Búsqueda   │  │ 📄 Generar Factura               │ │
│  └─────────────────────┘  └──────────────────────────────────┘ │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

## 4. Flujo de Interacción

```
                    INICIO
                      │
                      ↓
        ┌─────────────────────────┐
        │  Completar Formulario   │
        │  - Folio                │
        │  - Monto                │
        │  - Fecha                │
        │  - Sucursal             │
        └─────────────────────────┘
                      │
                      ↓
        ┌─────────────────────────┐
        │ ¿Datos Completos?       │─── No ──→ Mostrar Error
        └─────────────────────────┘
                      │ Sí
                      ↓
        ┌─────────────────────────┐
        │ Buscar en Base de Datos │
        └─────────────────────────┘
                      │
                      ↓
        ┌─────────────────────────┐
        │ ¿Ticket Encontrado?     │─── No ──→ Mostrar Error
        └─────────────────────────┘
                      │ Sí
                      ↓
        ┌─────────────────────────┐
        │ ¿Ya Facturado?          │─── Sí ──→ Mostrar Error
        └─────────────────────────┘
                      │ No
                      ↓
        ┌─────────────────────────┐
        │ Mostrar Detalles del    │
        │ Ticket Completo         │
        └─────────────────────────┘
                      │
                      ↓
        ┌─────────────────────────┐
        │ Usuario Verifica        │
        │ Información             │
        └─────────────────────────┘
                      │
           ┌──────────┴──────────┐
           │                     │
        Nueva Búsqueda      Generar Factura
           │                     │
           ↓                     ↓
        Limpiar            Confirmar Acción
        Formulario               │
           │                     ↓
           │          ┌─────────────────┐
           │          │ Generar Factura │
           │          │ - XML           │
           │          │ - Timbrado      │
           │          │ - PDF           │
           │          └─────────────────┘
           │                     │
           │                     ↓
           │          ┌─────────────────┐
           │          │ Mostrar Folio   │
           │          │ Factura         │
           │          └─────────────────┘
           │                     │
           └──────────┬──────────┘
                      │
                      ↓
                    FIN
```

## 5. Estructura de Carpetas (Cambios)

```
facturacion/
│
├── 📄 RESUMEN_IMPLEMENTACION.md (NUEVO)
├── 📄 FACTURACION_CLIENTES.md (NUEVO)
│
├── pages/
│   ├── 📄 facturar-cliente.inc.php (NUEVO)
│   ├── 📝 header.inc.php (MODIFICADO)
│   └── ...
│
├── core/
│   ├── 📄 buscar-ticket-cliente.php (NUEVO)
│   ├── 📄 obtener-sucursales-cliente.php (NUEVO)
│   ├── 📄 INSTRUCCIONES_DATOS_PRUEBA.php (NUEVO)
│   ├── 📄 generar-factura.php (EXISTENTE)
│   └── ...
│
└── ...
```

## 6. Validaciones en el Flujo

### Frontend (JavaScript):
```
┌──────────────────────────────┐
│ Campo Completado?            │ ──→ Sí ──→ ✅
│ Folio, Monto, Fecha, Sucursal│
└──────────────────────────────┘
         │
         No → ❌ Mostrar alerta


┌──────────────────────────────┐
│ Formato de Monto Válido?     │ ──→ Sí ──→ ✅
│ (número decimal)             │
└──────────────────────────────┘
         │
         No → ❌ Mostrar alerta


┌──────────────────────────────┐
│ Fecha Válida? (no futuro)    │ ──→ Sí ──→ ✅
└──────────────────────────────┘
         │
         No → ❌ Mostrar alerta
```

### Backend (PHP):
```
┌──────────────────────────────┐
│ ¿Sesión Válida?              │ ──→ Sí ──→ ✅
└──────────────────────────────┘
         │
         No → ❌ Retornar error


┌──────────────────────────────┐
│ ¿Parámetros Presentes?       │ ──→ Sí ──→ ✅
│ (folio, monto, fecha, suc)   │
└──────────────────────────────┘
         │
         No → ❌ Retornar error


┌──────────────────────────────┐
│ ¿Sucursal del Usuario?       │ ──→ Sí ──→ ✅
└──────────────────────────────┘
         │
         No → ❌ Retornar error


┌──────────────────────────────┐
│ ¿Ticket Existe?              │ ──→ Sí ──→ ✅
│ (folio + fecha + monto +     │
│  sucursal)                   │
└──────────────────────────────┘
         │
         No → ❌ Retornar error


┌──────────────────────────────┐
│ ¿No Facturado Aún?           │ ──→ Sí ──→ ✅
└──────────────────────────────┘
         │
         No → ❌ Retornar error
```

## 7. Formatos de Respuesta

### Búsqueda Exitosa:
```json
{
  "success": true,
  "message": "Ticket encontrado.",
  "ticket": {
    "id_ticket": 1,
    "folio": "123456789",
    "fecha_venta": "2025-01-01",
    "sucursal": "Sucursal Centro",
    "subtotal": 1000.00,
    "impuesto": 160.00,
    "total": 1160.00,
    "detalles": [
      {
        "descripcion": "Producto A",
        "cantidad": 2,
        "precio_unitario": 500.00,
        "importe": 1000.00
      }
    ],
    "pagos": [
      {
        "metodo_pago": "Efectivo",
        "importe": 1160.00
      }
    ]
  }
}
```

### Búsqueda Fallida:
```json
{
  "success": false,
  "message": "No se encontró un ticket con esos datos. 
              Verifica el folio, monto, fecha y sucursal."
}
```

### Ticket Ya Facturado:
```json
{
  "success": false,
  "message": "Este ticket ya ha sido facturado."
}
```

## 8. Estados de la Interfaz

```
ESTADO 1: Búsqueda
┌────────────────────┐
│ Formulario Activo  │
│ Resultados: OculTO │
│ Carga: Oculto      │
└────────────────────┘

ESTADO 2: Cargando
┌────────────────────┐
│ Formulario: OculTO │
│ Resultados: OculTO │
│ Carga: VISIBLE     │
└────────────────────┘

ESTADO 3: Resultados
┌────────────────────┐
│ Formulario: OculTO │
│ Resultados: VIS.   │
│ Carga: Oculto      │
└────────────────────┘

ESTADO 4: Error
┌────────────────────┐
│ Formulario: VIS.   │
│ Resultados: OculTO │
│ Carga: Oculto      │
│ Alerta: Visible    │
└────────────────────┘
```

## 9. Diagrama de Componentes

```
┌─────────────────────────────────────────────────────────────┐
│                    facturar-cliente.inc.php                 │
│                                                             │
│  ┌──────────────────────────────────────────────────────┐  │
│  │          FORMULARIO DE BÚSQUEDA (HTML)               │  │
│  │  - Input: Folio                                      │  │
│  │  - Input: Monto                                      │  │
│  │  - Input: Fecha                                      │  │
│  │  - Select: Sucursal (llenado dinámicamente)          │  │
│  └──────────────────────────────────────────────────────┘  │
│                          ↓                                   │
│  ┌──────────────────────────────────────────────────────┐  │
│  │          JAVASCRIPT - VALIDACIÓN FRONTEND            │  │
│  │  - Campos completados                                │  │
│  │  - Formatos válidos                                  │  │
│  │  - Llamadas AJAX a APIs                              │  │
│  └──────────────────────────────────────────────────────┘  │
│                          ↓                                   │
│  ┌──────────────────────────────────────────────────────┐  │
│  │       SECCIÓN DE RESULTADOS (Dinámicamente)          │  │
│  │  - Información del ticket                            │  │
│  │  - Tabla de detalles                                 │  │
│  │  - Resumen de montos                                 │  │
│  │  - Tabla de métodos de pago                          │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                             │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│              buscar-ticket-cliente.php (API)                │
│                                                             │
│  1. Validar sesión                                          │
│  2. Obtener parámetros POST                                 │
│  3. Validar datos                                           │
│  4. Buscar ticket en BD                                     │
│  5. Verificar no facturado                                  │
│  6. Obtener detalles                                        │
│  7. Obtener métodos pago                                    │
│  8. Retornar JSON                                           │
│                                                             │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│           obtener-sucursales-cliente.php (API)              │
│                                                             │
│  1. Validar sesión                                          │
│  2. Obtener sucursales del usuario                          │
│  3. Retornar JSON con lista                                 │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

## 10. Checklist de Pruebas

- [ ] Acceder a "Facturar mis Compras"
- [ ] Verificar que las sucursales carguen correctamente
- [ ] Buscar un ticket existente (debe encontrar)
- [ ] Buscar un ticket no existente (debe mostrar error)
- [ ] Buscar con folio correcto pero monto incorrecto (debe no encontrar)
- [ ] Buscar un ticket ya facturado (debe mostrar error)
- [ ] Verificar que se muestren correctamente:
  - Folio del ticket
  - Fecha de venta
  - Sucursal
  - Tabla de detalles
  - Resumen de montos
  - Métodos de pago
- [ ] Generar factura (debe crear CFDI)
- [ ] Verificar que aparezca el folio de factura
- [ ] Realizar nueva búsqueda (debe limpiar formulario)
- [ ] Probar en dispositivo móvil (responsiveness)

---

**Versión**: 1.0  
**Fecha**: Enero 2025  
**Estado**: ✅ Completado
