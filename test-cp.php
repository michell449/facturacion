<?php
require_once 'core/class/db.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Verificar si hay datos en la tabla
    $stmt = $conn->query("SELECT COUNT(*) as total FROM cat_codigo_postal");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Total de registros en cat_codigo_postal: " . $result['total'] . "\n";
    
    // Probar algunos códigos postales comunes
    $codigosTest = ['01000', '03100', '11000', '15000'];
    
    foreach ($codigosTest as $cp) {
        echo "\nProbando CP: $cp\n";
        $stmt = $conn->prepare("SELECT d_codigo, d_asenta, d_mnpio, d_estado FROM cat_codigo_postal WHERE d_codigo = ? LIMIT 5");
        $stmt->execute([$cp]);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($resultados) {
            echo "Encontrados " . count($resultados) . " registros:\n";
            foreach ($resultados as $row) {
                echo "- {$row['d_asenta']}, {$row['d_mnpio']}, {$row['d_estado']}\n";
            }
        } else {
            echo "No se encontraron datos para este CP\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}