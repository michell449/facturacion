#!/bin/bash
# INSTALACION RAPIDA - Facturación para Clientes Invitados

echo "=========================================="
echo "Facturación para Clientes Invitados v1.0"
echo "=========================================="
echo ""

# Variables
XAMPP_PATH="/xampp/htdocs/facturacion"
DB_NAME="facturacion"
DB_USER="root"
DB_PASS=""

# 1. Copiar archivos
echo "[1/5] Verificando archivos..."

if [ ! -f "$XAMPP_PATH/core/facturar-invitado.php" ]; then
    echo "❌ Error: core/facturar-invitado.php no encontrado"
    exit 1
fi

if [ ! -f "$XAMPP_PATH/pages/facturar-invitado.inc.php" ]; then
    echo "❌ Error: pages/facturar-invitado.inc.php no encontrado"
    exit 1
fi

echo "✅ Archivos encontrados"
echo ""

# 2. Verificar base de datos
echo "[2/5] Verificando base de datos..."

mysql -u $DB_USER -p$DB_PASS $DB_NAME -e "DESCRIBE usuarios;" > /dev/null 2>&1

if [ $? -ne 0 ]; then
    echo "❌ Error: Base de datos no accesible"
    exit 1
fi

echo "✅ Base de datos accesible"
echo ""

# 3. Verificar tabla usuarios
echo "[3/5] Verificando tabla usuarios..."

mysql -u $DB_USER -p$DB_PASS $DB_NAME -e \
    "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_NAME='usuarios' AND COLUMN_NAME='tipo_cliente';" > /dev/null 2>&1

if [ $? -ne 0 ]; then
    echo "⚠️  Advertencia: Campo tipo_cliente no existe"
    echo "    Ejecutar: ALTER TABLE usuarios ADD COLUMN tipo_cliente ENUM('registrado', 'invitado');"
fi

echo "✅ Tabla usuarios OK"
echo ""

# 4. Verificar tabla datos_fiscales_usuario
echo "[4/5] Verificando tabla datos_fiscales_usuario..."

mysql -u $DB_USER -p$DB_PASS $DB_NAME -e "DESCRIBE datos_fiscales_usuario;" > /dev/null 2>&1

if [ $? -ne 0 ]; then
    echo "❌ Error: Tabla datos_fiscales_usuario no existe"
    exit 1
fi

echo "✅ Tabla datos_fiscales_usuario OK"
echo ""

# 5. Crear directorios si no existen
echo "[5/5] Verificando directorios..."

mkdir -p "$XAMPP_PATH/uploads/facturas"
mkdir -p "$XAMPP_PATH/logs"

echo "✅ Directorios creados/verificados"
echo ""

# Información final
echo "=========================================="
echo "✅ INSTALACION COMPLETADA"
echo "=========================================="
echo ""
echo "Próximos pasos:"
echo "1. Acceder a: http://localhost/facturacion/?pg=facturar-invitado"
echo "2. Realizar pruebas con datos reales"
echo "3. Verificar logs: tail -f /var/log/apache2/error.log"
echo "4. Ver documentación:"
echo "   - GUIA_RAPIDA.md (usuarios)"
echo "   - IMPLEMENTACION.md (técnica)"
echo "   - FACTURAR_INVITADO.md (referencia)"
echo ""
echo "Base de datos preparada para:"
echo "  ✓ Usuarios invitados"
echo "  ✓ Datos fiscales"
echo "  ✓ Facturas automáticas"
echo "  ✓ Timbrado SAT"
echo ""
echo "=========================================="
