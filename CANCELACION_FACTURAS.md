# Cancelación de Facturas con Finkok

## 📋 Descripción

Este sistema implementa la cancelación de facturas (CFDI) ante el SAT utilizando el servicio web de Finkok. La implementación sigue las mejores prácticas y maneja correctamente los diferentes escenarios de cancelación.

## 🔧 Instalación

### 1. Agregar Campos a la Base de Datos

Ejecuta el siguiente script desde tu navegador para agregar los campos necesarios:

```
http://localhost/facturacion/core/tablas_bd/agregar_campos_cancelacion.php
```

Este script agregará los siguientes campos a la tabla `facturas`:
- `fecha_cancelacion` - Fecha y hora de la cancelación
- `motivo_cancelacion` - Código del motivo (01, 02, 03, 04)
- `acuse_cancelacion` - Voucher o acuse del SAT

### 2. Configurar Credenciales de Finkok

Edita el archivo `core/cancelar-factura.php` y configura tus credenciales:

```php
$finkokUser = 'tu_usuario@finkok.com';
$finkokPass = 'tu_contraseña';
$enProduccion = false; // false = Demo, true = Producción
```

## 📚 Motivos de Cancelación

Según el SAT, existen 4 motivos de cancelación válidos:

| Código | Descripción | Requiere UUID Sustitución |
|--------|-------------|---------------------------|
| 01 | Comprobante emitido con errores sin relación | ✅ SÍ |
| 02 | Comprobante emitido con errores con relación | ❌ NO |
| 03 | No se llevó a cabo la operación | ❌ NO |
| 04 | Operación nominativa relacionada en una factura global | ❌ NO |

## 🚀 Uso

### Desde la Interfaz Web

1. Ve a la sección de "Facturas Generadas"
2. Localiza la factura que deseas cancelar
3. Haz clic en el botón "Cancelar" (rojo)
4. Selecciona el motivo de cancelación
5. Si seleccionaste el motivo 01, ingresa el UUID de sustitución
6. Confirma la cancelación

### Códigos de Respuesta Comunes

#### Exitosos
- **201** - Petición de cancelación realizada exitosamente
- **202** - UUID previamente cancelado

#### Errores de Validación
- **203** - No encontrado o no corresponde al emisor
- **205** - UUID no existe
- **207** - Motivo de cancelación inválido
- **208** - La fecha de solicitud es mayor a la fecha de declaración

#### Errores de Certificados
- **300** - Usuario no válido
- **304** - Certificado revocado o caduco
- **305** - Certificado inválido
- **310** - Se está usando FIEL en lugar de CSD

#### Errores de Conexión
- **708** - No se pudo conectar al SAT
- **711** - Error con el certificado al cancelar

## ⚠️ Consideraciones Importantes

### Ambiente de Pruebas (Demo)

1. **Tiempo de espera**: Espera de 1 a 5 minutos después del timbrado antes de cancelar
2. **Cancelable sin aceptación**: Facturas menores a $1,000 MXN
3. **Cancelable con aceptación**: Facturas de $1,000 MXN o más (demora 30 minutos)
4. **Aceptación automática**: Si no hay respuesta en 5 minutos, se cancela automáticamente

### Ambiente de Producción

1. La cancelación es **irreversible**
2. Solo se pueden cancelar facturas del ejercicio fiscal actual (hasta el 31 de enero del año siguiente)
3. Las facturas con relaciones tienen estatus "No cancelable"
4. Máximo 5 intentos de cancelación por factura

## 🔍 Flujo de Cancelación Recomendado

```
1. Validar Sesión de Usuario
          ↓
2. Verificar Factura Timbrada
          ↓
3. Seleccionar Motivo
          ↓
4. UUID de Sustitución (solo motivo 01)
          ↓
5. Enviar Solicitud a Finkok
          ↓
6. Actualizar Base de Datos
          ↓
7. Mostrar Resultado al Usuario
```

## 📁 Archivos Modificados/Creados

### Backend
- `api/FinkokApi.php` - Métodos de cancelación
- `core/cancelar-factura.php` - Endpoint de cancelación
- `core/tablas_bd/agregar_campos_cancelacion.php` - Script de migración
- `core/tablas_bd/agregar_campos_cancelacion.sql` - Script SQL

### Frontend
- `pages/facturas-generadas-admin.inc.php` - Interfaz y lógica de cancelación

## 🐛 Solución de Problemas

### Error: "UUID No existe (205)"
**Causa**: La factura no está en el sistema del SAT aún
**Solución**: Espera 1-5 minutos después del timbrado

### Error: "No se pudo conectar al SAT (708)"
**Causa**: Intermitencias en el servicio del SAT
**Solución**: Intenta nuevamente en unos minutos

### Error: "Certificado inválido (305)"
**Causa**: Certificado con problemas conocidos
**Solución**: Genera un nuevo certificado (CSD)

### Error: "Ya existe una solicitud previa (798)"
**Causa**: Se envió una petición de cancelación recientemente
**Solución**: Espera 72 horas o verifica el estatus con `get_sat_status`

## 📞 Soporte

Para más información sobre los códigos de error y el proceso de cancelación, consulta:
- [Documentación oficial de Finkok](https://wiki.finkok.com/)
- Email de soporte: soporte@finkok.com

## ✅ Checklist de Implementación

- [x] Agregar métodos de cancelación a FinkokApi
- [x] Crear endpoint cancelar-factura.php
- [x] Implementar interfaz de usuario
- [x] Agregar campos a la base de datos
- [x] Manejar los 4 motivos de cancelación
- [x] Validar UUID de sustitución para motivo 01
- [x] Mostrar mensajes de error apropiados
- [x] Actualizar estatus en BD después de cancelar
- [x] Registrar acuse de cancelación

## 🔐 Seguridad

- ✅ Validación de sesión de usuario
- ✅ Verificación de permisos (solo el dueño puede cancelar)
- ✅ Validación de datos de entrada
- ✅ Manejo seguro de excepciones
- ✅ Uso de consultas preparadas (PDO)
- ✅ Validación de formato UUID

---

**Última actualización**: Enero 2026
**Versión**: 1.0.0
