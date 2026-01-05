# ✅ Corrección: Cancelación de Facturas con Finkok

## Problema Detectado

**Error anterior:**
```json
{
    "success": false,
    "message": "Error devuelto por Finkok: La lista enviada contiene UUIDS con formato invalido."
}
```

## Causa del Error

La estructura del array de UUIDs no coincidía con el formato exacto requerido por Finkok.

### ❌ Estructura Anterior (Incorrecta)
```php
$uuidObj = new \stdClass();
$uuidObj->UUID = $uuid;
$uuidObj->Motivo = $motivoCancelacion;
$uuidObj->FolioSustitucion = $uuidSustitucion;

$params = [
    'username' => $this->username,
    'password' => $this->password,
    'taxpayer_id' => $rfcEmisor,
    'uuids' => ['UUID' => $uuidObj],  // ❌ 'uuids' en minúsculas
    'store_pending' => true
];
```

**Problemas:**
1. Usaba `'uuids'` (minúsculas) en lugar de `'UUIDS'` (mayúsculas)
2. La estructura del objeto no coincidía exactamente con el ejemplo de Finkok

### ✅ Estructura Corregida (Según Ejemplo Finkok)
```php
// Estructura exacta del ejemplo de Finkok:
// $uuids = array("UUID" => "277C8C2C-4B76-50BD-851B-FB9EA3B8FCCB", "Motivo" => "02", "FolioSustitucion" => "");
// $uuid_ar = array('UUID' => $uuids);
// $uuids_ar = array('UUIDS' => $uuid_ar);

$uuids = [
    "UUID" => $uuid,
    "Motivo" => $motivoCancelacion,
    "FolioSustitucion" => $uuidSustitucion ? strtoupper(trim($uuidSustitucion)) : ""
];

$uuid_ar = ['UUID' => $uuids];

$params = [
    'UUIDS' => $uuid_ar,  // ✅ 'UUIDS' en MAYÚSCULAS
    'username' => $this->username,
    'password' => $this->password,
    'taxpayer_id' => strtoupper(trim($rfcEmisor)),
    'store_pending' => true
];
```

## Archivos Modificados

### 1. **FinkokApi.php** ✅
**Ubicación:** `api/FinkokApi.php`

**Cambios en método `cancelarFactura()`:**
- ✅ Cambió `'uuids'` → `'UUIDS'` (mayúsculas)
- ✅ Estructura de array corregida para coincidir exactamente con ejemplo de Finkok
- ✅ Agregado logging detallado de request/response
- ✅ Comentarios explicativos sobre la estructura requerida

### 2. **cancelar-factura.php** ✅
**Ubicación:** `core/cancelar-factura.php`

**Mejoras aplicadas:**
- ✅ Patrón robusto de limpieza de buffers
- ✅ Headers JSON antes de cualquier salida
- ✅ Configuración UTF-8 completa
- ✅ Logging detallado de todo el proceso
- ✅ Manejo de excepciones con Throwable
- ✅ Respuestas JSON siempre válidas

## Estructura del XML SOAP Generado

### Estructura Correcta (que genera el código actualizado):
```xml
<SOAP-ENV:Envelope>
  <SOAP-ENV:Body>
    <ns1:cancel>
      <ns1:UUIDS>                          <!-- MAYÚSCULAS -->
        <ns1:UUID>                         <!-- Contenedor UUID -->
          <ns1:UUID>277C8C2C-...</ns1:UUID>  <!-- El UUID -->
          <ns1:Motivo>02</ns1:Motivo>
          <ns1:FolioSustitucion></ns1:FolioSustitucion>
        </ns1:UUID>
      </ns1:UUIDS>
      <ns1:username>pruebas@finkok.com</ns1:username>
      <ns1:password>S0port3.22</ns1:password>
      <ns1:taxpayer_id>EKU9003173C9</ns1:taxpayer_id>
      <ns1:store_pending>true</ns1:store_pending>
    </ns1:cancel>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
```

## Flujo de Cancelación

```
Usuario → Solicitar Cancelación
    ↓
cancelar-factura.php
    ├── Validar sesión
    ├── Validar factura existe
    ├── Validar que esté timbrada (no ya cancelada)
    ├── Validar UUID presente
    ├── Validar motivo (01, 02, 03, 04)
    ├── Si motivo=01, validar UUID sustitución
    │
    └── FinkokApi.cancelarFactura()
        ├── Validar formato UUID
        ├── Construir estructura EXACTA según Finkok
        │   └── UUIDS (mayúsculas) → UUID → [UUID, Motivo, FolioSustitucion]
        ├── Enviar SOAP request
        ├── Recibir respuesta
        ├── Parsear resultado
        └── Retornar status code (201, 202, etc.)
    
    ↓ (Si success)
    ├── Actualizar BD: estatus='cancelada'
    ├── Guardar acuse de cancelación
    └── Respuesta JSON exitosa
```

## Códigos de Respuesta Finkok

### Códigos Exitosos
- **201**: Petición de cancelación realizada exitosamente
- **202**: UUID previamente cancelado (también se considera éxito)

### Códigos de Error Comunes
- **203**: No encontrado o no corresponde al emisor
- **205**: UUID no existe
- **207**: Motivo de cancelación inválido
- **300**: Usuario no válido
- **301**: XML mal formado
- **304**: Certificado revocado o caduco
- **305**: Certificado inválido
- **311**: Clave de motivo de cancelación no válida
- **708**: No se pudo conectar al SAT
- **798**: Ya existe una solicitud previa, esperar 72 horas

## Motivos de Cancelación

| Código | Descripción | Requiere UUID Sustitución |
|--------|-------------|---------------------------|
| 01 | Comprobante emitido con errores con relación | ✅ SÍ |
| 02 | Comprobante emitido con errores sin relación | ❌ NO |
| 03 | No se llevó a cabo la operación | ❌ NO |
| 04 | Operación nominativa relacionada en una factura global | ❌ NO |

## Ejemplo de Uso

### Request desde Frontend
```javascript
fetch('core/cancelar-factura.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        id_factura: 123,
        motivo: '02',
        uuid_sustitucion: null  // Solo si motivo es '01'
    })
})
.then(res => res.json())
.then(data => {
    if (data.success) {
        console.log('Cancelada:', data.uuid);
        console.log('Status:', data.status_code);
    } else {
        console.error('Error:', data.message);
    }
});
```

### Response Exitosa
```json
{
    "success": true,
    "message": "Petición de cancelación realizada exitosamente",
    "uuid": "277C8C2C-4B76-50BD-851B-FB9EA3B8FCCB",
    "status_code": "201",
    "acuse": "<?xml version=\"1.0\"...",
    "detalle": "La factura ha sido cancelada exitosamente ante el SAT."
}
```

### Response con Error
```json
{
    "success": false,
    "message": "Error devuelto por Finkok: UUID no existe",
    "status_code": "205",
    "fault_code": null,
    "detalle": "No se pudo completar la cancelación. Verifica el mensaje de error.",
    "debug_response": "{...}",
    "debug_request": "<SOAP-ENV:Envelope>..."
}
```

## Logging y Debugging

### Ver Logs en Tiempo Real
```powershell
Get-Content "C:\xampp\apache\logs\error.log" -Wait -Tail 50
```

### Logs Generados
```
=== INICIO CANCELACIÓN DE FACTURA ===
Usuario ID: 123
Input recibido: {"id_factura":456,"motivo":"02"}
Cancelando factura ID: 456, Motivo: 02
Factura encontrada - UUID: 277C8C2C-..., RFC Emisor: AAA010101AAA
Configuración Finkok: Usuario=michellflores822@gmail.com, Producción=NO
Enviando solicitud de cancelación a Finkok...
==== FINKOK CANCELACIÓN - REQUEST ====
Parámetros: {...}
==== FINKOK CANCELACIÓN - RESPONSE ====
XML Request enviado: <SOAP-ENV:Envelope>...
XML Response recibido: <?xml version="1.0"...
Resultado de Finkok: {"success":true,"status_code":"201"...}
Factura actualizada en BD como cancelada
```

## Configuración Finkok

### Credenciales Demo (actuales)
```php
$finkokUser = 'michellflores822@gmail.com';
$finkokPass = 'Pankycontra2025.';
$enProduccion = false;
```

### Credenciales Demo Oficiales
```php
$finkokUser = 'pruebas@finkok.com';
$finkokPass = 'S0port3.22';
$enProduccion = false;
```

### Para Producción
```php
$finkokUser = 'tu_usuario@empresa.com';
$finkokPass = 'tu_contraseña';
$enProduccion = true;
```

## Requisitos de Base de Datos

### Campos necesarios en tabla `facturas`:
- `uuid` VARCHAR(36) - UUID timbrado del SAT
- `estatus` ENUM/VARCHAR - Debe permitir 'cancelada'
- `fecha_cancelacion` DATETIME
- `motivo_cancelacion` VARCHAR(2)
- `acuse_cancelacion` TEXT - XML del acuse

### Campos en tabla `empresas`:
- `rfc` VARCHAR(13) - RFC del emisor
- `nombre` o `razon_social` - Nombre de la empresa

## Verificación de Corrección

✅ **Antes de probar, verificar:**
1. UUID tenga formato válido: `XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX`
2. UUID esté en mayúsculas
3. Factura tenga estatus 'timbrada'
4. RFC emisor sea válido
5. Credenciales Finkok sean correctas

✅ **Para confirmar que funciona:**
1. Intentar cancelar factura timbrada
2. Revisar logs en `error.log`
3. Ver XML Request/Response en logs
4. Verificar que respuesta sea 201 o 202
5. Confirmar actualización en BD

## Solución de Problemas

### Si sigue dando error de formato:
1. Verificar que UUID esté en mayúsculas
2. Verificar que no tenga espacios al inicio/fin
3. Revisar XML Request en logs
4. Confirmar que UUIDS esté en mayúsculas en el XML

### Si da error 300 (Usuario no válido):
- Verificar credenciales de Finkok
- Confirmar que cuenta esté activa
- Verificar modo (demo vs producción)

### Si da error 203 (No corresponde al emisor):
- Verificar que RFC emisor coincida con el del CFDI
- Verificar que UUID pertenezca a ese RFC

---

**Fecha de corrección:** 5 de enero de 2026  
**Archivos modificados:** 2 (FinkokApi.php, cancelar-factura.php)  
**Patrón aplicado:** Estructura exacta según ejemplo oficial de Finkok
