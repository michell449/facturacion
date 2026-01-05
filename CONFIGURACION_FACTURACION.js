/**
 * CONFIGURACIÓN REQUERIDA PARA FACTURACIÓN AUTOMÁTICA
 * 
 * Este archivo documenta la configuración necesaria en config.php
 * para que funcione correctamente la facturación automática
 */

// ============================================
// 1. FINKOK (PAC)
// ============================================

// Credenciales de Finkok
const FINKOK_USER = 'usuario@finkok.com';          // Tu usuario de Finkok
const FINKOK_PASSWORD = 'tucontraseña';             // Tu contraseña de Finkok
const FINKOK_URL = 'http://facturacion.finkok.com/servicios/soap/'; // URL SOAP

// ============================================
// 2. CERTIFICADOS DIGITALES (CSD)
// ============================================

// Rutas de los certificados
const RUTA_CSD_CERTIFICADO = '/path/to/certificado.cer';    // Archivo .CER
const RUTA_CSD_LLAVE = '/path/to/llave.key';                // Archivo .KEY
const RUTA_CSD_LLAVE_PROTEGIDA = '/path/to/csd.p12';        // Archivo .P12 (opcional)

// Contraseña de la llave privada
const PASS_CSD = 'tucontraseña';

// ============================================
// 3. BASE DE DATOS
// ============================================

// Conexión a MariaDB
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'facturacion';
const DB_CHARSET = 'utf8mb4';

// ============================================
// 4. DIRECTORIOS
// ============================================

// Directorios para almacenar archivos
const DIR_XML = __DIR__ . '/uploads/xml/';
const DIR_XML_TIMBRADOS = __DIR__ . '/uploads/xml_timbrados/';
const DIR_PDF = __DIR__ . '/uploads/pdf/';
const DIR_LOGS = __DIR__ . '/logs/';

// ============================================
// 5. CONFIGURACIÓN RFCI/SERIE
// ============================================

// RFC de la empresa (EMISOR)
const RFC_EMPRESA = 'XXX000000000';

// Serie de facturas por defecto
const SERIE_FACTURA = 'A';

// Correlativo inicial
const FOLIO_INICIAL = 1;

// ============================================
// 6. CFDI 4.0
// ============================================

// Versión CFDI
const VERSION_CFDI = '4.0';

// Tipo de comprobante
const TIPO_COMPROBANTE = 'I';  // I=Ingreso, E=Egreso, T=Traslado

// Régimen fiscal (debe coincidir con el del usuario)
// Opciones: 601, 603, 605, 606, 608, 610, 611, 612, 614, 616, 620, 621, 622, 623, 624, 625, 626, 627, 628
const REGIMEN_FISCAL_DEFAULT = '601'; // General de Ley

// Moneda
const MONEDA_DEFAULT = 'MXN';

// IVA por defecto
const TASA_IVA = 0.16; // 16%

// ============================================
// 7. VALIDACIONES
// ============================================

// Validar código postal contra catálogo SAT
const VALIDAR_CP = true;

// Validar RFC contra SAT
const VALIDAR_RFC = true;

// Validar certificados CSD
const VALIDAR_CSD = true;

// ============================================
// 8. ALERTAS Y NOTIFICACIONES
// ============================================

// Email para notificaciones
const EMAIL_NOTIFICACIONES = 'admin@empresa.com';

// Notificar al generar factura
const NOTIFICAR_GENERACION = true;

// Notificar al timbrar
const NOTIFICAR_TIMBRADO = true;

// ============================================
// 9. LOGS Y DEBUG
// ============================================

// Nivel de log (DEBUG, INFO, WARNING, ERROR)
const LOG_LEVEL = 'INFO';

// Guardar XML en log
const LOG_XML = true;

// Guardar respuestas de Finkok
const LOG_FINKOK_RESPONSE = true;

// ============================================
// 10. TIMEOUTS
// ============================================

// Timeout para conexión a Finkok (segundos)
const FINKOK_TIMEOUT = 30;

// Timeout para validación de CP (segundos)
const VALIDAR_CP_TIMEOUT = 10;

// ============================================
// EJEMPLO DE IMPLEMENTACIÓN EN config.php
// ============================================

/*

<?php
// config.php

// 1. Definir constantes
define('FINKOK_USER', 'usuario@finkok.com');
define('FINKOK_PASSWORD', 'contraseña');
define('FINKOK_URL', 'http://facturacion.finkok.com/servicios/soap/');

// 2. Rutas de CSD
define('RUTA_CSD_CERTIFICADO', __DIR__ . '/csd/certificado.cer');
define('RUTA_CSD_LLAVE', __DIR__ . '/csd/llave.key');
define('PASS_CSD', 'contraseña_csd');

// 3. Base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'facturacion');

// 4. Directorios
define('DIR_XML', __DIR__ . '/uploads/xml/');
define('DIR_XML_TIMBRADOS', __DIR__ . '/uploads/xml_timbrados/');
define('DIR_PDF', __DIR__ . '/uploads/pdf/');
define('DIR_LOGS', __DIR__ . '/logs/');

// 5. CFDI
define('VERSION_CFDI', '4.0');
define('RFC_EMPRESA', 'XXX000000000');
define('SERIE_FACTURA', 'A');
define('TASA_IVA', 0.16);

// 6. Debug
define('LOG_LEVEL', 'INFO');
define('LOG_XML', true);

?>

*/

// ============================================
// VERIFICACIÓN DE CONFIGURACIÓN
// ============================================

/*

Para verificar que todo está configurado correctamente, ejecuta:

1. Verificar directorios existen y son escribibles:
   ls -la /xampp/htdocs/facturacion/uploads/
   chmod 755 /xampp/htdocs/facturacion/uploads/*

2. Verificar CSD existe:
   ls -la /xampp/htdocs/facturacion/csd/
   # Debe contener: certificado.cer, llave.key, csd.p12

3. Verificar conexión a Finkok:
   curl -u usuario:contraseña http://facturacion.finkok.com/servicios/soap/

4. Verificar BD:
   mysql -u root facturacion -e "SELECT 1;"

5. Verificar PHP SOAP:
   php -m | grep soap
   # Debe mostrar: [Zend Modules] ... soap

*/

// ============================================
// TROUBLESHOOTING
// ============================================

/*

PROBLEMA: "No se puede conectar a Finkok"
SOLUCIÓN:
- Verificar usuario y contraseña
- Verificar URL (http vs https)
- Verificar que SOAP está habilitado en PHP
- Revisar logs en /var/log/apache2/error.log

PROBLEMA: "CSD no encontrado"
SOLUCIÓN:
- Verificar rutas en config.php
- Usar rutas absolutas (no relativas)
- Verificar permisos: chmod 644 *.cer *.key

PROBLEMA: "Error en validación de RFC"
SOLUCIÓN:
- Verificar formato RFC (12-13 caracteres)
- Verificar que RFC esté en mayúsculas
- Verificar en BD: SELECT rfc FROM datos_fiscales_usuario;

PROBLEMA: "XML no genera correctamente"
SOLUCIÓN:
- Verificar XMLSEC en PHP: php -m | grep xmlsec
- Verificar directorios de salida
- Revisar logs: tail -f /xampp/htdocs/facturacion/logs/

*/
