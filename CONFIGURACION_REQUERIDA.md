# Configuración Requerida - Facturación para Invitados v2.0

## 📋 Checklist de Requisitos Previos

Antes de poner en producción el sistema de facturación para invitados, asegurate de tener:

- [ ] PHP 7.4+
- [ ] MariaDB/MySQL con tablas requeridas
- [ ] PHPMailer instalado via Composer
- [ ] mPDF para generación de PDF
- [ ] cURL habilitado en PHP
- [ ] Acceso a API Finkok para timbrado
- [ ] SMTP configurado

---

## 🔧 Configuración en config.php

### SMTP Configuration

```php
// ============================================================================
// MAIL CONFIGURATION
// ============================================================================

// SMTP Host
define('MAIL_HOST', 'smtp.gmail.com');  // Gmail, Office365, etc.

// SMTP Port
define('MAIL_PORT', 587);  // 587 para TLS, 465 para SSL, 25 para sin encriptación

// Sender Email (dirección de envío)
define('MAIL_USER', 'noreply@tusistema.com');

// SMTP Password (contraseña de aplicación, NO contraseña de usuario)
define('MAIL_PSWD', 'tu_contraseña_de_aplicacion');

// Security (tls o ssl)
define('MAIL_SEC', 'tls');

// Name of Sender
define('MAIL_FROM_NAME', 'Sistema de Facturación');

// Reply-To Address (opcional)
define('MAIL_REPLY_TO', 'soporte@tusistema.com');

// Debug mode (0 = no debug, 1 = client, 2 = server)
define('MAIL_DEBUG', 0);
```

### Example Providers

#### Gmail
```php
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_SEC', 'tls');
define('MAIL_USER', 'tu_email@gmail.com');
define('MAIL_PSWD', 'tu_contraseña_de_aplicacion');
// Nota: Habilitar acceso de aplicaciones menos seguras
// https://myaccount.google.com/lesssecureapps
```

#### Outlook/Microsoft 365
```php
define('MAIL_HOST', 'smtp.office365.com');
define('MAIL_PORT', 587);
define('MAIL_SEC', 'tls');
define('MAIL_USER', 'tu_email@outlook.com');
define('MAIL_PSWD', 'tu_contraseña');
```

#### Mailgun
```php
define('MAIL_HOST', 'smtp.mailgun.org');
define('MAIL_PORT', 587);
define('MAIL_SEC', 'tls');
define('MAIL_USER', 'postmaster@tu_dominio.mailgun.org');
define('MAIL_PSWD', 'mailgun_api_key');
```

#### SendGrid
```php
define('MAIL_HOST', 'smtp.sendgrid.net');
define('MAIL_PORT', 587);
define('MAIL_SEC', 'tls');
define('MAIL_USER', 'apikey');
define('MAIL_PSWD', 'SG.tu_sendgrid_api_key');
```

#### Servidor Local (Postfix/Sendmail)
```php
define('MAIL_HOST', 'localhost');
define('MAIL_PORT', 25);
define('MAIL_SEC', '');
define('MAIL_USER', '');
define('MAIL_PSWD', '');
```

---

## 🗄️ Tablas de Base de Datos Requeridas

### 1. Tabla: usuarios

```sql
CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    correo VARCHAR(255) UNIQUE NOT NULL,
    tipo_usuario VARCHAR(50),  -- 'admin', 'cliente'
    tipo_cliente VARCHAR(50),  -- 'regular', 'invitado'
    verificacion TINYINT DEFAULT 0,
    fecha_reg DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Índices
CREATE INDEX idx_correo ON usuarios(correo);
CREATE INDEX idx_tipo_cliente ON usuarios(tipo_cliente);
```

### 2. Tabla: datos_fiscales_usuario

```sql
CREATE TABLE IF NOT EXISTS datos_fiscales_usuario (
    id_df INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    rfc VARCHAR(13) NOT NULL,
    razon_social VARCHAR(255) NOT NULL,
    reg_fiscal VARCHAR(10),  -- Régimen fiscal
    cp INT,  -- Código postal
    tipo_pers VARCHAR(50),  -- 'Fisica' o 'Moral'
    calle VARCHAR(255),
    num_ext VARCHAR(50),
    num_int VARCHAR(50),
    col VARCHAR(255),  -- Colonia
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    UNIQUE KEY unique_user_rfc (id_usuario, rfc)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Índices
CREATE INDEX idx_rfc ON datos_fiscales_usuario(rfc);
CREATE INDEX idx_usuario ON datos_fiscales_usuario(id_usuario);
```

### 3. Tabla: facturas

```sql
CREATE TABLE IF NOT EXISTS facturas (
    id_factura INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    id_empresa INT NOT NULL,
    id_ticket INT,
    folio_interno INT,
    serie_interno VARCHAR(10),
    fecha_emision DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    rfc_receptor VARCHAR(13) NOT NULL,
    razon_social_receptor VARCHAR(255),
    regimen_fiscal_receptor VARCHAR(10),
    codigo_postal_receptor INT,
    uso_cfdi VARCHAR(10) DEFAULT 'G01',
    
    calle_receptor VARCHAR(255),
    num_ext_receptor VARCHAR(50),
    num_int_receptor VARCHAR(50),
    colonia_receptor VARCHAR(255),
    
    subtotal DECIMAL(10,2),
    total DECIMAL(10,2),
    impuesto_total DECIMAL(10,2),
    
    forma_pago VARCHAR(50),
    metodo_pago VARCHAR(50),
    
    correo_receptor VARCHAR(255),
    
    xml_path VARCHAR(500),
    pdf_path VARCHAR(500),
    uuid VARCHAR(50),
    
    estatus VARCHAR(50) DEFAULT 'pendiente',  -- pendiente, timbrado, cancelado
    
    fecha_timbrado DATETIME,
    
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_empresa) REFERENCES empresas(id_empresa),
    UNIQUE KEY unique_folio (id_empresa, folio_interno)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Índices
CREATE INDEX idx_usuario ON facturas(id_usuario);
CREATE INDEX idx_empresa ON facturas(id_empresa);
CREATE INDEX idx_ticket ON facturas(id_ticket);
CREATE INDEX idx_estatus ON facturas(estatus);
CREATE INDEX idx_uuid ON facturas(uuid);
```

### 4. Tabla: facturas_detalles

```sql
CREATE TABLE IF NOT EXISTS facturas_detalles (
    id_detalle INT PRIMARY KEY AUTO_INCREMENT,
    id_factura INT NOT NULL,
    descripcion VARCHAR(255),
    cantidad INT,
    precio_unitario DECIMAL(10,2),
    importe DECIMAL(10,2),
    impuesto DECIMAL(10,2),
    tasa_impuesto VARCHAR(10) DEFAULT '16',
    
    FOREIGN KEY (id_factura) REFERENCES facturas(id_factura) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Índices
CREATE INDEX idx_factura ON facturas_detalles(id_factura);
```

### 5. Tabla: tickets (Debe Existir)

```sql
-- Esta tabla debe existir en tu BD
-- Estructura esperada:
CREATE TABLE IF NOT EXISTS tickets (
    id_ticket INT PRIMARY KEY AUTO_INCREMENT,
    id_empresa INT,
    folio_ticket VARCHAR(50),
    fecha_venta DATETIME,
    subtotal DECIMAL(10,2),
    impuesto_t DECIMAL(10,2),
    importe_t DECIMAL(10,2),
    estatus VARCHAR(50) DEFAULT 'pendiente',  -- pendiente, facturado
    id_factura INT,
    FOREIGN KEY (id_empresa) REFERENCES empresas(id_empresa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 6. Tabla: ticket_detalle (Debe Existir)

```sql
CREATE TABLE IF NOT EXISTS ticket_detalle (
    id_detalle INT PRIMARY KEY AUTO_INCREMENT,
    id_ticket INT,
    descr VARCHAR(255),  -- Descripción
    cant INT,  -- Cantidad
    precio_unit DECIMAL(10,2),  -- Precio unitario
    importe DECIMAL(10,2),
    FOREIGN KEY (id_ticket) REFERENCES tickets(id_ticket)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 7. Tabla: ticket_metodo_pago (Debe Existir)

```sql
CREATE TABLE IF NOT EXISTS ticket_metodo_pago (
    id_pago INT PRIMARY KEY AUTO_INCREMENT,
    id_ticket INT,
    metodo_pago VARCHAR(50) DEFAULT '01',  -- 01=Efectivo, etc
    forma_pago VARCHAR(50) DEFAULT 'PUE',  -- PUE=Pago en una exhibición
    monto DECIMAL(10,2),
    FOREIGN KEY (id_ticket) REFERENCES tickets(id_ticket)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## 📂 Directorios Requeridos

Asegurate de que existan estos directorios con permisos de escritura:

```bash
# Crear directorios
mkdir -p uploads/facturas/xml
mkdir -p uploads/facturas/pdf
mkdir -p logs

# Dar permisos (Linux/Mac)
chmod 755 uploads/facturas
chmod 755 uploads/facturas/xml
chmod 755 uploads/facturas/pdf
chmod 755 logs
```

### Windows (XAMPP)

```
C:\xampp\htdocs\facturacion\
├── uploads/
│   └── facturas/
│       ├── xml/     (archivos XML CFDI)
│       └── pdf/     (archivos PDF)
└── logs/            (error logs)
```

---

## 🔌 Endpoints Internos Requeridos

El sistema llama a estos endpoints internos. Asegurate de que existan y funcionen:

### 1. core/buscar-ticket-cliente.php
**Propósito:** Buscar tickets existentes por empresa, folio y monto

**Input (POST x-www-form-urlencoded):**
```
nombre_empresa=Mi%20Tienda&folio=00001&monto=1000.00
```

**Output (JSON):**
```json
{
  "success": true,
  "ticket": {
    "id_ticket": 1,
    "folio_ticket": "00001",
    "fecha_venta": "2024-01-15",
    "subtotal": "862.07",
    "impuesto": "137.93",
    "total": "1000.00"
  }
}
```

### 2. core/generar-xml.php
**Propósito:** Generar XML CFDI válido para SAT

**Input (POST JSON):**
```json
{
  "id_factura": 123
}
```

**Output (JSON):**
```json
{
  "success": true,
  "xml_path": "uploads/facturas/xml/A000001.xml"
}
```

### 3. core/timbrar-xml.php
**Propósito:** Enviar XML a Finkok para timbrado y obtener UUID

**Input (POST JSON):**
```json
{
  "id_factura": 123
}
```

**Output (JSON):**
```json
{
  "success": true,
  "uuid": "12345678-1234-1234-1234-123456789012",
  "data": {
    "uuid": "12345678-1234-1234-1234-123456789012"
  }
}
```

### 4. core/FacturaPdfService.php
**Función:** `facturaGenerarPdfArchivo($conn, $id_factura)`

**Propósito:** Generar PDF de la factura

**Return:**
```php
[
    'absolute' => '/absolute/path/to/A000001.pdf',
    'relative' => 'uploads/facturas/pdf/A000001.pdf'
]
```

### 5. core/mail/FacturaMailer.php
**Función:** `facturaEnviarCorreo($config, $email, $nombre, $vars, $adjuntos)`

**Propósito:** Enviar email con adjuntos

**Parameters:**
```php
$config = [
    'smtp_host' => 'smtp.ejemplo.com',
    'smtp_port' => 587,
    'smtp_user' => 'noreply@ejemplo.com',
    'smtp_password' => 'password',
    'smtp_secure' => 'tls'
];

$email = 'cliente@ejemplo.com';
$nombre = 'Juan Perez';

$vars = [
    'folio' => 'A000001',
    'total' => '$1000.00',
    'cliente' => 'Juan Perez',
    'rfc' => 'PEPJ8001019Q8'
];

$adjuntos = [
    [
        'path' => '/absolute/path/to/A000001.pdf',
        'name' => 'A000001.pdf'
    ],
    [
        'path' => '/absolute/path/to/UUID.xml',
        'name' => 'UUID.xml'
    ]
];
```

**Return:**
```json
{
  "success": true,
  "message": "Correo enviado exitosamente"
}
```

---

## 🔐 Configuración de Finkok (SAT Timbrado)

Si usas Finkok para timbrado, añade a config.php:

```php
// ============================================================================
// FINKOK CONFIGURATION
// ============================================================================

// Credenciales de Finkok
define('FINKOK_USER', 'tu_usuario_finkok');
define('FINKOK_PASS', 'tu_contraseña_finkok');

// URLs de Finkok
define('FINKOK_URL_PRODUCTION', 'https://facturación.finkok.com/servicios/soap/');
define('FINKOK_URL_SANDBOX', 'https://staging.finkok.com/servicios/soap/');

// Usar sandbox o producción
define('FINKOK_SANDBOX', false);  // true para testing, false para producción

// Certificado digital (archivos)
define('FINKOK_CERT_PATH', __DIR__ . '/certs/certificado.cer');
define('FINKOK_KEY_PATH', __DIR__ . '/certs/clave.key');
define('FINKOK_KEY_PASS', 'contraseña_del_certificado');
```

---

## 🧪 Prueba de Conexión SMTP

### Crear archivo: test_smtp.php

```php
<?php
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = defined('MAIL_HOST') ? MAIL_HOST : 'smtp.example.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = defined('MAIL_USER') ? MAIL_USER : 'tu_email@example.com';
    $mail->Password   = defined('MAIL_PSWD') ? MAIL_PSWD : 'tu_password';
    $mail->SMTPSecure = defined('MAIL_SEC') ? MAIL_SEC : PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = defined('MAIL_PORT') ? MAIL_PORT : 587;

    // Content
    $mail->setFrom(defined('MAIL_USER') ? MAIL_USER : 'noreply@example.com', 'Test');
    $mail->addAddress('tu_email@example.com', 'Test User');
    $mail->isHTML(true);
    $mail->Subject = 'Prueba de Conexión SMTP';
    $mail->Body    = 'Este es un email de prueba. Si lo recibes, SMTP está configurado correctamente.';

    $mail->send();
    echo "✓ Email enviado correctamente";
    
} catch (Exception $e) {
    echo "✗ Error al enviar email: {$mail->ErrorInfo}";
}
?>
```

**Ejecutar:**
```bash
php test_smtp.php
```

---

## 🔍 Validación de Instalación

Ejecutar este checklist antes de producción:

```bash
# 1. Verificar que PHP está actualizado
php -v
# Esperado: PHP 7.4+

# 2. Verificar que cURL está habilitado
php -m | grep curl
# Esperado: curl

# 3. Verificar que mPDF está instalado
php -r "require 'vendor/autoload.php'; echo 'mPDF OK';"
# Esperado: mPDF OK

# 4. Verificar que PHPMailer está instalado
php -r "require 'vendor/autoload.php'; use PHPMailer\PHPMailer\PHPMailer; echo 'PHPMailer OK';"
# Esperado: PHPMailer OK

# 5. Verificar directorios con permisos
ls -la uploads/facturas/
# Esperado: drwxr-xr-x

# 6. Probar conexión a BD
mysql -u user -p -e "SELECT 1;"
# Esperado: 1

# 7. Probar SMTP
php test_smtp.php
# Esperado: ✓ Email enviado correctamente
```

---

## 📝 Notas Importantes

1. **SMTP Credenciales**: Nunca uses tu contraseña real. Usa contraseña de aplicación (especialmente con Gmail)
2. **Certificados**: Guarda certificados digitales en directorio seguro, no en web root
3. **Logs**: Revisa `php_errors.log` regularmente para detectar problemas
4. **Pruebas**: Usa sandbox de Finkok antes de pasar a producción
5. **Backups**: Haz backup de la BD regularmente (facturas es información crítica)

---

**Última actualización:** 2024
**Versión:** 2.0
**Criticidad:** ALTA - Requiere configuración antes de usar
