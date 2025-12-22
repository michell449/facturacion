# 🔍 Guía de Validación XML CFDI 4.0

## ✅ Mejoras Implementadas

### 1. Limpieza Automática de Razón Social
El sistema ahora **automáticamente** limpia los nombres según las reglas del SAT:

**Antes:**
```
Soluciones Tecnológicas S.A. de C.V.
```

**Después (en el XML):**
```
SOLUCIONES TECNOLOGICAS
```

**Qué se elimina:**
- SA, S.A., SA DE CV, S.A. DE C.V.
- SC, S.C.
- S DE RL, S. DE R.L.
- AC, A.C.
- SCP, S.C.P.
- Acentos (Á → A, É → E, etc.)
- Todo se convierte a MAYÚSCULAS

### 2. Validaciones Previas al XML

Antes de generar el XML, el sistema valida:

#### ✓ Datos del Receptor
- RFC no vacío
- Nombre/Razón Social presente
- Código Postal válido (5 dígitos)
- Régimen Fiscal especificado
- Uso CFDI presente

#### ✓ Compatibilidad RFC Genérico
Si usas **RFC Genérico** (`XAXX010101000` o `XEXX010101000`):
- **DEBE** usar Régimen `616` (Sin obligaciones fiscales)
- **DEBE** usar Uso CFDI `S01` (Sin efectos fiscales)

#### ✓ Método y Forma de Pago
- **PUE** (Pago en una Exhibición): NO puede usar Forma `99`
- **PPD** (Pago en Parcialidades): SOLO puede usar Forma `99`

#### ✓ Conceptos
- Descripciones convertidas a MAYÚSCULAS
- Sin acentos

### 3. Código Postal
- Validación de formato (5 dígitos)
- Verificación contra catálogo del SAT (ya implementado antes)

## 🛠️ Herramientas de Validación

### Opción 1: Validador Visual (RECOMENDADO)

**URL:** `http://localhost/facturacion/core/validar-xml.php?id_factura=X`

**Qué muestra:**
- ✅ Checklist de validaciones CFDI 4.0
- 🔍 Comparación Nombre Original vs Nombre Limpio
- ⚠️ Errores y advertencias
- 📊 Vista previa de todos los datos
- 📜 XML generado (si existe)

**Cuándo usar:**
- Antes de timbrar por primera vez
- Cuando una factura falla al timbrar
- Para verificar que los datos sean correctos

### Opción 2: Debug Técnico

**URL:** `http://localhost/facturacion/core/debug-xml.php?id_factura=X`

**Qué muestra:**
- Información técnica del archivo XML
- Detección de BOM UTF-8
- Primeros caracteres en hexadecimal
- Validación de XML bien formado
- Preview del JSON que se envía al PAC

**Cuándo usar:**
- Cuando el validador dice que todo está bien pero aún falla
- Para problemas técnicos de codificación
- Para ver exactamente qué se envía al PAC

## 📋 Proceso de Validación Recomendado

### Paso 1: Crear Factura
Usa la interfaz normal para crear la factura

### Paso 2: Validar ANTES de Timbrar
```
http://localhost/facturacion/core/validar-xml.php?id_factura=123
```

### Paso 3: Revisar Checklist
- ✅ Todos los checks en verde = LISTO PARA TIMBRAR
- ❌ Algún error rojo = CORREGIR EN BASE DE DATOS
- ⚠️ Advertencias amarillas = REVISAR (puede funcionar pero no es óptimo)

### Paso 4: Si hay errores
1. Corrige los datos en la base de datos
2. Vuelve a validar (Paso 2)
3. Cuando todo esté verde, timbra

### Paso 5: Timbrar
Usa el botón "Generar Factura" de la interfaz normal

## ⚠️ Errores Comunes y Soluciones

### Error: "Nombre del receptor no coincide"
**Causa:** El nombre tiene régimen societario o está en minúsculas

**Solución:** El sistema ahora lo limpia automáticamente. Si persiste:
```sql
-- Ver cómo está en BD
SELECT razon_social_receptor FROM facturas WHERE id_factura = 123;

-- Si tiene "SA DE CV", se limpiará automáticamente al generar el XML
-- No necesitas modificar la BD
```

### Error: "Código Postal inválido"
**Causa:** CP no tiene 5 dígitos o no existe en catálogo SAT

**Solución:**
```sql
UPDATE facturas 
SET domicilio_fiscal_receptor = '01234' 
WHERE id_factura = 123;
```

### Error: "RFC Genérico con régimen incorrecto"
**Causa:** Usaste XAXX010101000 pero no pusiste Régimen 616

**Solución:**
```sql
UPDATE facturas 
SET regimen_fiscal_receptor = '616',
    uso_cfdi = 'S01'
WHERE id_factura = 123 AND rfc_receptor = 'XAXX010101000';
```

### Error: "PUE no compatible con Forma 99"
**Causa:** Método de pago incompatible con forma de pago

**Solución:**
```sql
-- Opción A: Cambiar a PPD
UPDATE facturas SET metodo_pago = 'PPD' WHERE id_factura = 123;

-- Opción B: Cambiar forma de pago
UPDATE facturas SET forma_pago = '01' WHERE id_factura = 123;
```

## 🔧 Validación Manual del XML (Externo)

Si quieres validar el XML en el sitio oficial del SAT:

1. Ve a: `http://localhost/facturacion/uploads/xml_timbrados/`
2. Descarga el archivo XML de tu factura
3. Entra a: https://www.sat.gob.mx/aplicacion/operacion/31274/consulta-de-validez-del-cfdi
4. Sube el archivo XML

## 📊 Cambios en el Flujo de Generación

### Antes
```
1. Guardar en BD
2. Generar XML (sin validar mucho)
3. Timbrar (y fallar con error críptico)
```

### Ahora
```
1. Guardar en BD
2. VALIDAR datos exhaustivamente
   - Si hay error: lanzar excepción con detalles
3. LIMPIAR nombres (quitar SA, DE CV, etc.)
4. NORMALIZAR todo a MAYÚSCULAS
5. Generar XML
6. VALIDAR estructura XML (CfdiUtils)
7. Timbrar
```

## 🎯 Tips para Evitar Errores

### Al registrar clientes
- Registra el nombre SIN régimen societario desde el inicio
- Usa MAYÚSCULAS
- Verifica el CP en el catálogo SAT

### Al facturar con RFC Genérico
- Siempre usar:
  - RFC: `XAXX010101000`
  - Régimen: `616`
  - Uso CFDI: `S01`
  - CP: Igual al del emisor

### Al facturar con RFC Real
- Pide la Constancia de Situación Fiscal del cliente
- Usa los datos EXACTOS de la constancia
- El CP debe ser el fiscal (no el de entrega)

## 📞 Soporte

Si después de validar todo sigue fallando:

1. Usa `debug-xml.php` para ver el XML exacto
2. Copia el contenido del XML
3. Valídalo en un validador online de CFDI 4.0
4. El validador te dirá EXACTAMENTE qué está mal

## 🚀 Mejoras Futuras Sugeridas

- [ ] Validar Claves de Producto/Servicio contra catálogo SAT
- [ ] Validar Unidades de Medida contra catálogo SAT
- [ ] Autocompletar Régimen Fiscal según RFC
- [ ] Integrar validación en tiempo real en el formulario
- [ ] Base de datos de clientes con datos validados

---

**Última actualización:** 22 de Diciembre de 2025
