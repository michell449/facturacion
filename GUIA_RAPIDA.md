# GUÍA RÁPIDA: Facturación para Invitados

## ¿Qué es?

Un sistema que permite a clientes **sin cuenta** facturar sus compras proporcionando:
1. Datos del ticket (folio, monto, nombre empresa)
2. Datos fiscales (RFC, razón social, domicilio)
3. ¡Listo! Se genera y envía factura automáticamente

## 🔗 Acceso del Cliente

```
http://tu-sitio.com/facturacion/?pg=facturar-invitado
```

## 📋 Flujo Visual

```
┌─────────────────────────────────────┐
│  PASO 1: BUSCAR TICKET              │
│  • Nombre empresa                   │
│  • Folio/número de ticket           │
│  • Monto total                      │
└─────────────────┬───────────────────┘
                  │
                  ▼
            ¿Encontrado?
                  │
        ┌─────────┴─────────┐
        │ SÍ                │ NO
        │                   │ (error)
        ▼                   ▼
┌─────────────────┐    [REINTENTAR]
│ PASO 2:         │
│ DATOS FISCALES  │
│ • RFC           │
│ • Razón Social  │
│ • Régimen       │
│ • Código Postal │
│ • Domicilio     │
│ • Email         │
└────────┬────────┘
         │
         ▼
  ¿Datos OK?
         │
    ┌────┴────┐
    │ SÍ      │ NO
    │         │ (validar)
    ▼         ▼
  ✓ GENERAR [CORREGIR]
  FACTURA
    │
    └─→ XML
        │
        └─→ TIMBRADO SAT
            │
            └─→ PDF
                │
                └─→ EMAIL
                    │
                    ✅ LISTO
```

## 🎯 Datos Requeridos

### Búsqueda del Ticket
| Campo | Tipo | Ejemplo |
|-------|------|---------|
| Nombre Empresa | Texto | "Tienda ABC" |
| Folio | Texto | "00001234" |
| Monto | Número | 116.00 |

### Datos Fiscales
| Campo | Tipo | Ejemplo | Validación |
|-------|------|---------|-----------|
| Email | Email | juan@email.com | Formato válido |
| RFC | Texto | PEPJ8001019Q8 | 12-13 caracteres |
| Nombre/Razón Social | Texto | Juan Pérez | Libre |
| Tipo Persona | Select | Física/Moral | Requerido |
| Régimen | Select | 612 | Requerido |
| Código Postal | Número | 28000 | 5 dígitos |
| Calle | Texto | Avenida Principal | Requerido |
| No. Exterior | Texto | 123 | Requerido |
| No. Interior | Texto | 4B | Opcional |
| Colonia | Texto | Centro | Requerido |

## ✅ Validaciones Automáticas

✓ Email con formato válido
✓ RFC 12-13 caracteres
✓ Código postal 5 dígitos (entre 10000-99999)
✓ Ticket existe y está pendiente
✓ Todos los campos requeridos completos

## 🚫 Errores Comunes

### "Ticket no encontrado"
- ❌ Nombre empresa mal escrito
- ❌ Folio incorrecto
- ❌ Monto no coincide
- ✓ Verificar con exactitud en el ticket físico

### "RFC no válido"
- ❌ Menos de 12 caracteres
- ❌ Más de 13 caracteres
- ✓ Usar RFC exacto de comprobante fiscal

### "Email inválido"
- ❌ usuario@email (falta extensión)
- ❌ usuario@.com (falta dominio)
- ✓ Usar formato: usuario@dominio.com

### "Código postal inválido"
- ❌ 12345 (6 dígitos)
- ❌ 123 (3 dígitos)
- ✓ Usar exactamente 5 dígitos

## 🔐 Privacidad y Seguridad

✓ **No requiere contraseña**
✓ **Datos no se guardan permanentemente** (solo lo necesario)
✓ **Encriptado en tránsito** (HTTPS)
✓ **RFC protegido** como dato sensible
✓ **Email verificado** automáticamente

## 📧 Después de Facturar

### Cliente recibe por email:
- ✓ Factura XML (CFDI)
- ✓ Factura PDF (imprimible)
- ✓ Folio de la factura
- ✓ Confirmación de timbrado

### Estado de la factura:
```
PENDIENTE → GENERADA → XML → TIMBRADA → COMPLETADA
  (1 seg)   (1 seg)   (5s)   (10s)    ✅
```

Generalmente **en menos de 1 minuto** toda la factura está lista.

## 📱 Respuesta del Sistema

### Exitosa (éxito):
```json
{
  "success": true,
  "message": "Factura generada exitosamente...",
  "id_factura": 456,
  "folio": 1,
  "correo": "juan@email.com"
}
```

### Con Error:
```json
{
  "success": false,
  "message": "RFC no válido. Debe tener 12 o 13 caracteres."
}
```

## 🛠️ Troubleshooting (Técnico)

### Si no funciona la búsqueda:
1. Verificar que existe ticket en BD con estado `pendiente`
```sql
SELECT * FROM tickets WHERE folio_ticket = '00001234' AND estatus = 'pendiente';
```

2. Verificar que la empresa existe
```sql
SELECT * FROM empresas WHERE razon_social LIKE '%ABC%';
```

### Si no se genera factura:
1. Verificar logs de PHP
```bash
tail -f /var/log/apache2/error.log
```

2. Verificar tabla `usuarios`
```sql
SELECT * FROM usuarios WHERE correo = 'juan@email.com';
```

3. Verificar tabla `facturas`
```sql
SELECT * FROM facturas WHERE id_ticket = 1;
```

### Si no llega email:
1. Verificar configuración SMTP en `config.php`
2. Verificar logs de correo
3. Verificar bandeja de spam

## 💡 Tips Útiles

### Para los clientes:
- 📱 Tener a mano el ticket físico
- ✏️ Escribir exactamente el nombre de la empresa
- 🔍 Verificar código postal antes de enviar
- 📧 Usar un email válido (recibirá factura aquí)

### Para el administrador:
- 📊 Monitorear facturas invitados en dashboard
- 🔄 Verificar timbrados completados
- ✉️ Verificar entregas de email
- 📈 Mantener estadísticas

## 📞 Soporte

### Panel de Control
```
http://tu-sitio.com/facturacion/
(acceso con cuenta registrada)
```

### Consultas útiles para soporte:
```sql
-- Facturas generadas hoy
SELECT * FROM facturas f
INNER JOIN usuarios u ON f.id_usuario = u.id_usuario
WHERE u.tipo_cliente = 'invitado'
AND DATE(f.fecha_emision) = CURDATE();

-- Facturas sin timbrar
SELECT * FROM facturas f
WHERE f.estatus != 'timbrada'
AND f.fecha_emision > DATE_SUB(NOW(), INTERVAL 1 HOUR);

-- Usuarios invitados más activos
SELECT u.correo, COUNT(*) as facturas_generadas
FROM usuarios u
INNER JOIN facturas f ON u.id_usuario = f.id_usuario
WHERE u.tipo_cliente = 'invitado'
GROUP BY u.id_usuario
ORDER BY facturas_generadas DESC;
```

## 🎓 Ejemplos

### Ejemplo 1: Cliente Persona Física

**Búsqueda:**
- Empresa: "Tienda Centro"
- Folio: "00001234"
- Monto: $500.00

**Datos Fiscales:**
- RFC: PEPJ8001019Q8
- Nombre: Juan Pérez García
- Tipo: **Persona Física**
- Régimen: **612** (Actividades Empresariales)
- CP: 28000
- Calle: Avenida Paseo
- No. Ext: 505
- No. Int: 4B
- Colonia: Cuauhtémoc
- Email: juan@email.com

### Ejemplo 2: Cliente Persona Moral

**Búsqueda:**
- Empresa: "Comercial XYZ"
- Folio: "00005678"
- Monto: $2,500.00

**Datos Fiscales:**
- RFC: ABC123456XY1 (12 caracteres)
- Razón Social: ABC Consultores S.A. de C.V.
- Tipo: **Persona Moral**
- Régimen: **601** (General de Ley)
- CP: 64000
- Calle: Boulevard principal
- No. Ext: 1000
- No. Int: Piso 5
- Colonia: Centro Empresarial
- Email: contacto@abc.com

## ⏰ Tiempos Aproximados

| Proceso | Tiempo |
|---------|--------|
| Búsqueda de ticket | 1-2 segundos |
| Validación de datos | <1 segundo |
| Creación de usuario/datos | 1-2 segundos |
| Generación de XML | 3-5 segundos |
| Timbrado SAT | 5-15 segundos |
| Generación de PDF | 2-3 segundos |
| Envío de email | 2-5 segundos |
| **Total** | **15-35 segundos** |

## 🔄 Información Guardada en BD

### Tabla `usuarios`:
```
- id_usuario (auto)
- correo (email invitado)
- tipo_usuario = 'cliente'
- tipo_cliente = 'invitado'
- verificacion = 1
- fecha_reg = ahora
- contrasena = NULL (sin contraseña)
- token = NULL
```

### Tabla `datos_fiscales_usuario`:
```
- id_df (auto)
- id_usuario (FK)
- rfc
- razon_social
- reg_fiscal
- cp
- tipo_pers (Física/Moral)
- calle
- num_ext
- num_int
- col
```

### Tabla `facturas`:
```
- id_factura (auto)
- id_usuario (FK al invitado)
- id_ticket (FK al ticket)
- folio_interno
- fecha_emision
- datos fiscales del receptor
- subtotal, total, impuesto
- estatus (pendiente → timbrada)
- correo_receptor
- xml_path
- pdf_path
- uuid (después de timbrar)
```

## 📊 Estadísticas

Ver reportes en: [Documentación Completa](FACTURAR_INVITADO.md)

---

**Última actualización:** Enero 2025
**Versión:** 1.0
**Estado:** Producción ✅
