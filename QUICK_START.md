# ⚡ QUICK START - Facturación para Invitados v2.0

## 🚀 5 Minutos para Empezar

---

## 1️⃣ VERIFICAR REQUISITOS (2 min)

```bash
# PHP 7.4+
php -v

# cURL habilitado
php -m | grep curl

# MySQL/MariaDB
mysql -u root -p -e "SELECT 1;"
```

✅ Si todo OK, continúa al paso 2

---

## 2️⃣ CONFIGURAR SMTP (2 min)

Edita `config.php`:

```php
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USER', 'tu_email@gmail.com');
define('MAIL_PSWD', 'tu_app_password');
define('MAIL_SEC', 'tls');
```

✅ Guarda el archivo

---

## 3️⃣ CREAR DIRECTORIOS (1 min)

```bash
mkdir -p uploads/facturas/xml
mkdir -p uploads/facturas/pdf
mkdir -p logs
```

---

## 4️⃣ COPIAR ARCHIVOS

Los archivos ya están en:
- ✅ `core/facturar-invitado.php`
- ✅ `pages/facturar-invitado.inc.php`

No hay que copiar nada, ya están en su lugar.

---

## 5️⃣ PROBAR RÁPIDAMENTE

Abre en navegador:
```
http://localhost/facturacion/index.php?p=facturar-invitado
```

Verás un formulario con 3 pasos.

---

## 🧪 Test Rápido (Opcional)

```bash
# Test SMTP
php test_smtp.php

# Test búsqueda ticket
curl -X POST http://localhost/facturacion/core/buscar-ticket-cliente.php \
  -d "nombre_empresa=TestStore&folio=00001&monto=1000"
```

---

## ⚠️ SI ALGO FALLA

### Email no llega
- [ ] Revisar `config.php` - MAIL_* configurado?
- [ ] Revisar `php_errors.log` - ¿Qué error?
- [ ] Revisar spam - ¿Llegó ahí?

### Factura no se genera
- [ ] ¿El ticket existe en BD?
- [ ] ¿El ticket está en status 'pendiente'?
- [ ] Revisar `php_errors.log`

### XML no genera
- [ ] ¿`generar-xml.php` existe?
- [ ] ¿Tiene permisos de lectura?
- [ ] Probar directamente

### Ticket no encontrado
- [ ] ¿Nombre empresa exacto?
- [ ] ¿Folio exacto?
- [ ] ¿Monto similar?

---

## 📚 Necesito Más Información

Abre según el tema:

| Necesito... | Abre... |
|------------|---------|
| Entender qué es esto | RESUMEN_EJECUTIVO.md |
| Configurar todo | CONFIGURACION_REQUERIDA.md |
| Ver flujo visual | DIAGRAMA_FLUJO_V2.txt |
| Hacer pruebas | GUIA_PRUEBAS_COMPLETA.md |
| Entender cambios | RESUMEN_ACTUALIZACIONES.md |
| Buscar algo específico | INDICE_DOCUMENTACION.md |

---

## 🎯 Caso de Uso Real

1. Cliente entra a `http://localhost/facturacion/`
2. Ve opción "Facturar como invitado"
3. **Paso 1:** Busca su ticket (empresa, folio, monto)
4. **Paso 2:** Ingresa datos fiscales (email, RFC, etc)
5. **Paso 3:** Clic en "Generar Factura"
6. Sistema procesa: Usuario → Factura → XML → Timbrado → PDF → Email
7. **¡Éxito!** Recibe email con PDF y XML

---

## 📋 Checklist Mínimo

- [x] PHP 7.4+
- [x] MySQL 5.7+
- [x] cURL habilitado
- [ ] SMTP configurado en config.php
- [ ] Directorios creados (uploads/facturas)
- [ ] Ticket de prueba en BD
- [ ] ¿Funciona?

---

## 💡 Tips Útiles

### Ver logs en vivo
```bash
tail -f /xampp/php/logs/php_errors.log
```

### Limpiar facturas de prueba
```sql
DELETE FROM facturas WHERE id_factura > 100;
DELETE FROM facturas_detalles WHERE id_factura > 100;
```

### Ver última factura generada
```sql
SELECT id_factura, folio_interno, rfc_receptor, total 
FROM facturas 
ORDER BY id_factura DESC 
LIMIT 1;
```

### Validar que email llegó
- Revisar bandeja de entrada
- Revisar spam/basura
- Revisar logs SMTP en `php_errors.log`

---

## 🔐 Seguridad Mínima

Asegurate de:
- [ ] SMTP credenciales NO en código (usar config.php)
- [ ] Certificados digitales en directorio privado
- [ ] Permisos 755 en directorios, 644 en archivos
- [ ] No compartir `config.php`

---

## 📞 Soporte Rápido

### Pregunta
→ **Respuesta rápida** → **Acción**

Q: ¿Cuál es el folio?
A: Serie + 6 dígitos (A000001) → Ver RESUMEN_ACTUALIZACIONES.md

Q: ¿Cuánto tarda?
A: 4-8 segundos → Ver RESUMEN_EJECUTIVO.md

Q: ¿Qué puede fallar?
A: Email, PDF (opcionales) → Ver GUIA_PRUEBAS_COMPLETA.md Troubleshooting

Q: ¿Cómo hago rollback?
A: DELETE FROM facturas WHERE... → NO REC (marcar como cancelado)

---

## ✅ Listo

Si llegaste aquí:
1. ✅ Verificaste requisitos
2. ✅ Configuraste SMTP
3. ✅ Creaste directorios
4. ✅ Copiaste archivos
5. ✅ Testeaste

**¡El sistema está listo para usar!**

---

## 🎓 Próximos Pasos

Cuando estés cómodo:
1. Leer RESUMEN_ACTUALIZACIONES.md (entiende los cambios)
2. Leer DIAGRAMA_FLUJO_V2.txt (ve el flujo)
3. Ejecutar GUIA_PRUEBAS_COMPLETA.md (valida todo)
4. Deployar a producción

---

**¿Stuck?** → Lee INDICE_DOCUMENTACION.md
**¿Error?** → Revisar php_errors.log
**¿Idea?** → Leer RESUMEN_ACTUALIZACIONES.md

---

**Versión:** 2.0
**Estado:** ✅ Listo
**Tiempo setup:** ~10 minutos
**Tiempo test:** ~5 minutos
