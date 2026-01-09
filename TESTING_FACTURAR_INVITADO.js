/**
 * PRUEBAS DE API - FACTURACIÓN PARA INVITADOS
 * Ejemplos de solicitudes cURL y JSON para testing
 */

// ========================================================================
// TEST 1: BUSCAR TICKET (buscar-ticket-cliente.php)
// ========================================================================

// Usando cURL
curl -X POST http://localhost/facturacion/core/buscar-ticket-cliente.php \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "nombre_empresa=Tienda%20ABC&folio=00001234&monto=116.00"

// Respuesta esperada (exitosa):
{
  "success": true,
  "message": "Ticket encontrado.",
  "ticket": {
    "id_ticket": 1,
    "id_empresa": 1,
    "folio": "00001234",
    "fecha_venta": "2025-01-15",
    "sucursal": "Tienda Centro",
    "razon_social": "Tienda ABC",
    "codigo_sucursal": "001",
    "subtotal": 100.00,
    "impuesto": 16.00,
    "total": 116.00,
    "detalles": [
      {
        "id_detalle": 1,
        "descr": "Producto 1",
        "cant": 2,
        "precio_unit": 50.00,
        "importe": 100.00
      }
    ],
    "pagos": [
      {
        "metodo_pago": "01",
        "forma_pago": "PUE",
        "monto": 116.00
      }
    ]
  }
}

// Respuesta esperada (error):
{
  "success": false,
  "message": "No se encontró la empresa \"Tienda ABC\". Verifica que el nombre sea correcto."
}

// ========================================================================
// TEST 2: GENERAR FACTURA PARA INVITADO (facturar-invitado.php)
// ========================================================================

// Usando cURL
curl -X POST http://localhost/facturacion/core/facturar-invitado.php \
  -H "Content-Type: application/json" \
  -d '{
    "id_ticket": 1,
    "nombre_empresa": "Tienda ABC",
    "folio_ticket": "00001234",
    "monto_ticket": 116.00,
    "correo": "juan.perez@email.com",
    "rfc": "PEPJ8001019Q8",
    "razon_social": "Juan Pérez García",
    "tipo_persona": "Fisica",
    "reg_fiscal": "612",
    "cp": 28000,
    "uso_cfdi": "G01",
    "calle": "Avenida Paseo de la Reforma",
    "num_ext": "505",
    "num_int": "Apt. 4B",
    "colonia": "Cuauhtémoc"
  }'

// Respuesta esperada (exitosa):
{
  "success": true,
  "message": "Factura generada exitosamente. Se ha enviado un correo a juan.perez@email.com",
  "id_factura": 456,
  "folio": 1,
  "correo": "juan.perez@email.com"
}

// ========================================================================
// TEST 3: CASOS DE ERROR
// ========================================================================

// Error 3.1: Ticket no encontrado
curl -X POST http://localhost/facturacion/core/facturar-invitado.php \
  -H "Content-Type: application/json" \
  -d '{
    "id_ticket": 99999,
    "nombre_empresa": "Tienda ABC",
    "folio_ticket": "00001234",
    "monto_ticket": 116.00,
    "correo": "juan@email.com",
    "rfc": "PEPJ8001019Q8",
    "razon_social": "Juan Pérez",
    "tipo_persona": "Fisica",
    "reg_fiscal": "612",
    "cp": 28000,
    "calle": "Avenida Principal",
    "num_ext": "123",
    "colonia": "Centro"
  }'

// Respuesta:
{
  "success": false,
  "message": "Ticket no encontrado o ya ha sido facturado."
}

// Error 3.2: RFC inválido (muy corto)
curl -X POST http://localhost/facturacion/core/facturar-invitado.php \
  -H "Content-Type: application/json" \
  -d '{
    "id_ticket": 1,
    "correo": "juan@email.com",
    "rfc": "PEPE",
    "razon_social": "Juan Pérez",
    "tipo_persona": "Fisica",
    "reg_fiscal": "612",
    "cp": 28000,
    "calle": "Avenida Principal",
    "num_ext": "123",
    "colonia": "Centro"
  }'

// Respuesta:
{
  "success": false,
  "message": "RFC no válido. Debe tener 12 o 13 caracteres."
}

// Error 3.3: Email inválido
curl -X POST http://localhost/facturacion/core/facturar-invitado.php \
  -H "Content-Type: application/json" \
  -d '{
    "id_ticket": 1,
    "correo": "no-es-email",
    "rfc": "PEPJ8001019Q8",
    "razon_social": "Juan Pérez",
    "tipo_persona": "Fisica",
    "reg_fiscal": "612",
    "cp": 28000,
    "calle": "Avenida Principal",
    "num_ext": "123",
    "colonia": "Centro"
  }'

// Respuesta:
{
  "success": false,
  "message": "Correo electrónico no válido."
}

// Error 3.4: Código postal inválido
curl -X POST http://localhost/facturacion/core/facturar-invitado.php \
  -H "Content-Type: application/json" \
  -d '{
    "id_ticket": 1,
    "correo": "juan@email.com",
    "rfc": "PEPJ8001019Q8",
    "razon_social": "Juan Pérez",
    "tipo_persona": "Fisica",
    "reg_fiscal": "612",
    "cp": 999999,
    "calle": "Avenida Principal",
    "num_ext": "123",
    "colonia": "Centro"
  }'

// Respuesta:
{
  "success": false,
  "message": "Código postal debe tener 5 dígitos."
}

// Error 3.5: Datos faltantes
curl -X POST http://localhost/facturacion/core/facturar-invitado.php \
  -H "Content-Type: application/json" \
  -d '{
    "id_ticket": 1,
    "correo": "juan@email.com",
    "rfc": "PEPJ8001019Q8"
  }'

// Respuesta:
{
  "success": false,
  "message": "Faltan datos requeridos para el registro."
}

// ========================================================================
// TEST 4: PERSONAS MORALES (RFC de 12 caracteres)
// ========================================================================

curl -X POST http://localhost/facturacion/core/facturar-invitado.php \
  -H "Content-Type: application/json" \
  -d '{
    "id_ticket": 1,
    "nombre_empresa": "Tienda ABC",
    "folio_ticket": "00001234",
    "monto_ticket": 116.00,
    "correo": "contacto@empresa.com",
    "rfc": "ABC123456XY1",
    "razon_social": "ABC Consultores S.A. de C.V.",
    "tipo_persona": "Moral",
    "reg_fiscal": "601",
    "cp": 28000,
    "uso_cfdi": "G03",
    "calle": "Avenida Paseo de la Reforma",
    "num_ext": "505",
    "num_int": "Piso 10",
    "colonia": "Cuauhtémoc"
  }'

// Respuesta:
{
  "success": true,
  "message": "Factura generada exitosamente...",
  "id_factura": 457,
  "folio": 2,
  "correo": "contacto@empresa.com"
}

// ========================================================================
// TEST 5: VALIDACIÓN DE REGÍMENES FISCALES
// ========================================================================

// Régimen válido para Persona Física (612)
{
  "tipo_persona": "Fisica",
  "reg_fiscal": "612"
}

// Régimen válido para Persona Moral (601)
{
  "tipo_persona": "Moral",
  "reg_fiscal": "601"
}

// ========================================================================
// TEST 6: VALIDACIÓN DE USOS CFDI
// ========================================================================

// Uso CFDI - Adquisición de mercancías (default)
{
  "uso_cfdi": "G01"
}

// Uso CFDI - Gastos en general
{
  "uso_cfdi": "G03"
}

// Uso CFDI - Servicios profesionales
{
  "uso_cfdi": "S01"
}

// ========================================================================
// TEST 7: DATOS ADICIONALES (OPCIONALES)
// ========================================================================

// Todos estos campos son OPCIONALES:
{
  "num_int": "",  // Puede ser vacío
  "uso_cfdi": "G01"  // Si no se envía, usa default G01
}

// ========================================================================
// TEST 8: FLUJO COMPLETO EN JAVASCRIPT (FRONT-END)
// ========================================================================

// Paso 1: Buscar ticket
async function buscarTicket() {
  const response = await fetch('core/buscar-ticket-cliente.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: new URLSearchParams({
      nombre_empresa: 'Tienda ABC',
      folio: '00001234',
      monto: '116.00'
    })
  });
  
  const result = await response.json();
  
  if (result.success) {
    console.log('Ticket encontrado:', result.ticket);
    // Mostrar formulario de datos fiscales
  } else {
    console.error('Error:', result.message);
    alert('Ticket no encontrado: ' + result.message);
  }
}

// Paso 2: Generar factura
async function generarFactura() {
  const datosFactura = {
    id_ticket: ticketEncontrado.id_ticket,
    nombre_empresa: document.getElementById('lugarCompraInput').value,
    folio_ticket: ticketEncontrado.folio,
    monto_ticket: ticketEncontrado.total,
    
    correo: document.getElementById('correoFiscal').value,
    rfc: document.getElementById('rfcFiscal').value.toUpperCase(),
    razon_social: document.getElementById('nombreFiscal').value,
    tipo_persona: document.getElementById('tipoPersona').value,
    reg_fiscal: document.getElementById('regimenFiscal').value,
    cp: parseInt(document.getElementById('cpFiscal').value),
    uso_cfdi: document.getElementById('usoCFDI').value,
    
    calle: document.getElementById('calleFiscal').value,
    num_ext: document.getElementById('numExtFiscal').value,
    num_int: document.getElementById('numIntFiscal').value,
    colonia: document.getElementById('coloniaFiscal').value
  };

  try {
    const response = await fetch('core/facturar-invitado.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(datosFactura)
    });

    const result = await response.json();

    if (result.success) {
      alert('¡Factura generada!\nID: ' + result.id_factura + '\nFolio: ' + result.folio);
      // Mostrar paso 3 (factura generada)
    } else {
      alert('Error: ' + result.message);
    }
  } catch (error) {
    alert('Error de conexión: ' + error.message);
  }
}

// ========================================================================
// TEST 9: POSTMAN COLLECTION
// ========================================================================

// Importar en Postman como JSON:
{
  "info": {
    "name": "Facturación Invitados",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "1. Buscar Ticket",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/x-www-form-urlencoded"
          }
        ],
        "body": {
          "mode": "urlencoded",
          "urlencoded": [
            {"key": "nombre_empresa", "value": "Tienda ABC"},
            {"key": "folio", "value": "00001234"},
            {"key": "monto", "value": "116.00"}
          ]
        },
        "url": {
          "raw": "http://localhost/facturacion/core/buscar-ticket-cliente.php",
          "protocol": "http",
          "host": ["localhost"],
          "path": ["facturacion", "core", "buscar-ticket-cliente.php"]
        }
      }
    },
    {
      "name": "2. Generar Factura Invitado",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/json"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\n  \"id_ticket\": 1,\n  \"nombre_empresa\": \"Tienda ABC\",\n  \"folio_ticket\": \"00001234\",\n  \"monto_ticket\": 116.00,\n  \"correo\": \"juan.perez@email.com\",\n  \"rfc\": \"PEPJ8001019Q8\",\n  \"razon_social\": \"Juan Pérez García\",\n  \"tipo_persona\": \"Fisica\",\n  \"reg_fiscal\": \"612\",\n  \"cp\": 28000,\n  \"uso_cfdi\": \"G01\",\n  \"calle\": \"Avenida Paseo de la Reforma\",\n  \"num_ext\": \"505\",\n  \"num_int\": \"Apt. 4B\",\n  \"colonia\": \"Cuauhtémoc\"\n}"
        },
        "url": {
          "raw": "http://localhost/facturacion/core/facturar-invitado.php",
          "protocol": "http",
          "host": ["localhost"],
          "path": ["facturacion", "core", "facturar-invitado.php"]
        }
      }
    }
  ]
}

// ========================================================================
// TEST 10: VALIDACIÓN DE CAMPOS (LADO CLIENTE)
// ========================================================================

// Validación de RFC (12-13 caracteres)
const rfc = document.getElementById('rfcFiscal').value.toUpperCase();
if (rfc.length < 12 || rfc.length > 13) {
  console.error('RFC inválido');
}

// Validación de Email
const email = document.getElementById('correoFiscal').value;
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
if (!emailRegex.test(email)) {
  console.error('Email inválido');
}

// Validación de Código Postal (5 dígitos)
const cp = parseInt(document.getElementById('cpFiscal').value);
if (cp < 1000 || cp > 99999) {
  console.error('Código Postal inválido');
}

// Validación de Tipo de Persona
const tipoPersona = document.getElementById('tipoPersona').value;
if (!['Fisica', 'Moral'].includes(tipoPersona)) {
  console.error('Tipo de Persona inválido');
}

// ========================================================================
// TEST 11: LOGS Y DEBUGGING
// ========================================================================

// En PHP, buscar en error_log:
tail -f /var/log/apache2/error.log
// o
tail -f /var/log/php-errors.log

// Ejemplo de log:
// [15-Jan-2025 14:30:45] Error en facturar-invitado.php: RFC no válido...

// ========================================================================
// TEST 12: MONITOREO DE BASE DE DATOS
// ========================================================================

// Ver usuarios invitados creados:
SELECT * FROM usuarios WHERE tipo_cliente = 'invitado' ORDER BY fecha_reg DESC LIMIT 10;

// Ver facturas de invitados:
SELECT f.id_factura, f.folio_interno, f.estatus, u.correo 
FROM facturas f 
INNER JOIN usuarios u ON f.id_usuario = u.id_usuario 
WHERE u.tipo_cliente = 'invitado' 
ORDER BY f.fecha_emision DESC;
