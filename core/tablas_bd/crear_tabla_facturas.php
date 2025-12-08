<?php
/**
 * Script para crear las tablas necesarias para el sistema de facturación
 * Ejecutar este archivo una sola vez desde el navegador
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../class/db.php';

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Crear Tablas de Facturas</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: #28a745; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; margin: 10px 0; }
        .error { color: #dc3545; padding: 10px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px; margin: 10px 0; }
        .info { color: #0c5460; padding: 10px; background: #d1ecf1; border: 1px solid #bee5eb; border-radius: 4px; margin: 10px 0; }
        h1 { color: #333; }
        .sql-query { background: #f8f9fa; padding: 15px; border-radius: 4px; font-family: monospace; font-size: 12px; margin: 10px 0; white-space: pre-wrap; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🗄️ Crear Tablas de Facturas</h1>
";

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Leer el archivo SQL
    $sqlFile = __DIR__ . '/crear_tablas_facturas.sql';
    
    if (!file_exists($sqlFile)) {
        throw new Exception("No se encontró el archivo SQL: $sqlFile");
    }
    
    $sqlContent = file_get_contents($sqlFile);
    
    // Dividir las consultas por punto y coma
    $queries = array_filter(
        array_map('trim', explode(';', $sqlContent)),
        function($query) {
            return !empty($query) && !preg_match('/^--/', $query);
        }
    );
    
    echo "<div class='info'>Se encontraron " . count($queries) . " consultas SQL para ejecutar.</div>";
    
    $ejecutadas = 0;
    $errores = 0;
    
    foreach ($queries as $index => $query) {
        try {
            // Limpiar comentarios de la consulta
            $query = preg_replace('/^--.*$/m', '', $query);
            $query = trim($query);
            
            if (empty($query)) continue;
            
            // Ejecutar la consulta
            $conn->exec($query);
            $ejecutadas++;
            
            // Obtener el nombre de la tabla/operación
            preg_match('/CREATE TABLE IF NOT EXISTS (\w+)|ALTER TABLE (\w+)/i', $query, $matches);
            $tableName = $matches[1] ?? $matches[2] ?? "Consulta " . ($index + 1);
            
            echo "<div class='success'>✅ <strong>$tableName</strong> - Ejecutado correctamente</div>";
            
        } catch (PDOException $e) {
            $errores++;
            $errorMsg = $e->getMessage();
            echo "<div class='error'>❌ Error en consulta " . ($index + 1) . ": " . htmlspecialchars($errorMsg) . "</div>";
            echo "<div class='sql-query'>" . htmlspecialchars($query) . "</div>";
        }
    }
    
    echo "<hr>";
    echo "<div class='info'>";
    echo "<h3>📊 Resumen de Ejecución</h3>";
    echo "<p><strong>Consultas ejecutadas exitosamente:</strong> $ejecutadas</p>";
    echo "<p><strong>Errores encontrados:</strong> $errores</p>";
    echo "</div>";
    
    if ($errores === 0) {
        echo "<div class='success'>";
        echo "<h3>🎉 ¡Proceso completado exitosamente!</h3>";
        echo "<p>Las tablas de facturas han sido creadas correctamente.</p>";
        echo "<p><strong>Tablas creadas:</strong></p>";
        echo "<ul>";
        echo "<li><code>facturas</code> - Almacena las facturas generadas</li>";
        echo "<li><code>factura_conceptos</code> - Almacena los conceptos/productos de cada factura</li>";
        echo "</ul>";
        echo "<p><strong>Campos agregados:</strong></p>";
        echo "<ul>";
        echo "<li><code>tickets.id_factura</code> - Vincula el ticket con la factura generada</li>";
        echo "<li><code>config_facturas.folioActual</code> - Contador de folios por sucursal</li>";
        echo "</ul>";
        echo "</div>";
    }
    
    // Verificar las tablas creadas
    echo "<hr>";
    echo "<h3>📋 Tablas verificadas en la base de datos:</h3>";
    
    $tables = ['facturas', 'factura_conceptos', 'tickets', 'config_facturas'];
    
    foreach ($tables as $table) {
        $stmt = $conn->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "<div class='success'>✅ Tabla <strong>$table</strong> existe</div>";
            
            // Mostrar columnas
            $columns = $conn->query("DESCRIBE $table")->fetchAll(PDO::FETCH_ASSOC);
            echo "<div class='sql-query'>";
            echo "Columnas de $table:\n";
            foreach ($columns as $col) {
                echo "- {$col['Field']} ({$col['Type']})\n";
            }
            echo "</div>";
        } else {
            echo "<div class='error'>❌ Tabla <strong>$table</strong> NO existe</div>";
        }
    }
    
} catch (Exception $e) {
    echo "<div class='error'>";
    echo "<h3>❌ Error crítico</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}

echo "
        <hr>
        <p><a href='../../index.php'>← Volver al inicio</a></p>
    </div>
</body>
</html>";
?>
