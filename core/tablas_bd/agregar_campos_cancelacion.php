<?php
/**
 * Script para agregar campos de cancelación a la tabla facturas
 * Ejecutar este archivo desde el navegador
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../class/db.php';

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Agregar Campos de Cancelación</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: #28a745; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; margin: 10px 0; }
        .error { color: #dc3545; padding: 10px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px; margin: 10px 0; }
        .info { color: #0c5460; padding: 10px; background: #d1ecf1; border: 1px solid #bee5eb; border-radius: 4px; margin: 10px 0; }
        h1 { color: #333; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 4px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔧 Agregar Campos de Cancelación</h1>
";

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    echo "<div class='info'>Agregando campos necesarios para la funcionalidad de cancelación...</div>";
    
    // Array de campos a agregar
    $campos = [
        [
            'nombre' => 'fecha_cancelacion',
            'sql' => "ALTER TABLE facturas ADD COLUMN fecha_cancelacion DATETIME NULL COMMENT 'Fecha y hora en que se canceló la factura'"
        ],
        [
            'nombre' => 'motivo_cancelacion',
            'sql' => "ALTER TABLE facturas ADD COLUMN motivo_cancelacion VARCHAR(2) NULL COMMENT 'Motivo de cancelación: 01, 02, 03, 04'"
        ],
        [
            'nombre' => 'acuse_cancelacion',
            'sql' => "ALTER TABLE facturas ADD COLUMN acuse_cancelacion TEXT NULL COMMENT 'Acuse de cancelación del SAT (voucher)'"
        ]
    ];
    
    $agregados = 0;
    $yaExisten = 0;
    
    foreach ($campos as $campo) {
        try {
            // Verificar si el campo ya existe
            $stmt = $conn->query("SHOW COLUMNS FROM facturas LIKE '{$campo['nombre']}'");
            
            if ($stmt->rowCount() > 0) {
                echo "<div class='info'>ℹ️ El campo <strong>{$campo['nombre']}</strong> ya existe</div>";
                $yaExisten++;
            } else {
                // Agregar el campo
                $conn->exec($campo['sql']);
                echo "<div class='success'>✅ Campo <strong>{$campo['nombre']}</strong> agregado exitosamente</div>";
                $agregados++;
            }
        } catch (PDOException $e) {
            echo "<div class='error'>❌ Error al agregar campo <strong>{$campo['nombre']}</strong>: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
    
    // Agregar índice
    try {
        echo "<div class='info'>Agregando índice para optimizar búsquedas...</div>";
        $conn->exec("ALTER TABLE facturas ADD INDEX idx_estatus_cancelacion (estatus, fecha_cancelacion)");
        echo "<div class='success'>✅ Índice agregado exitosamente</div>";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "<div class='info'>ℹ️ El índice ya existe</div>";
        } else {
            echo "<div class='error'>❌ Error al agregar índice: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
    
    echo "<hr>";
    echo "<div class='info'>";
    echo "<h3>📊 Resumen</h3>";
    echo "<p><strong>Campos agregados:</strong> $agregados</p>";
    echo "<p><strong>Campos que ya existían:</strong> $yaExisten</p>";
    echo "</div>";
    
    if ($agregados > 0 || $yaExisten === count($campos)) {
        echo "<div class='success'>";
        echo "<h3>🎉 ¡Proceso completado!</h3>";
        echo "<p>La tabla <code>facturas</code> está lista para la funcionalidad de cancelación.</p>";
        echo "<p><strong>Campos disponibles:</strong></p>";
        echo "<ul>";
        echo "<li><code>fecha_cancelacion</code> - Almacena cuándo se canceló la factura</li>";
        echo "<li><code>motivo_cancelacion</code> - Código del motivo (01, 02, 03, 04)</li>";
        echo "<li><code>acuse_cancelacion</code> - Voucher o acuse del SAT</li>";
        echo "</ul>";
        echo "</div>";
    }
    
    // Mostrar estructura actual de la tabla
    echo "<hr>";
    echo "<h3>📋 Estructura actual de la tabla facturas:</h3>";
    
    $columns = $conn->query("DESCRIBE facturas")->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    foreach ($columns as $col) {
        $highlight = in_array($col['Field'], ['fecha_cancelacion', 'motivo_cancelacion', 'acuse_cancelacion']) ? '👉 ' : '';
        echo $highlight . "{$col['Field']} - {$col['Type']} " . ($col['Null'] === 'YES' ? 'NULL' : 'NOT NULL') . "\n";
    }
    echo "</pre>";
    
} catch (Exception $e) {
    echo "<div class='error'>";
    echo "<h3>❌ Error crítico</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}

echo "
        <hr>
        <p><strong>Siguiente paso:</strong> Ya puedes usar la funcionalidad de cancelación de facturas.</p>
        <p><a href='../../panel?pg=facturas-generadas-admin'>← Ir a Facturas Generadas</a></p>
    </div>
</body>
</html>";
?>
