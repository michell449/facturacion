<?php
require_once 'config.php';
require_once 'core/autoload-vendor.php';
require_once 'core/class/db.php';

use Smalot\PdfParser;

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Debug Extracción Régimen Fiscal</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .debug-section { margin: 20px 0; padding: 15px; border: 2px solid #007bff; border-radius: 8px; }
        .success { background-color: #d4edda; border-color: #28a745; }
        .error { background-color: #f8d7da; border-color: #dc3545; }
        .warning { background-color: #fff3cd; border-color: #ffc107; }
        .info { background-color: #d1ecf1; border-color: #17a2b8; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; max-height: 300px; }
        .regex-test { margin: 10px 0; padding: 8px; border-radius: 4px; }
        .found { background: #d4edda; }
        .not-found { background: #f8d7da; }
    </style>
</head>
<body>
    <h1>🔍 Debug Extracción de Régimen Fiscal</h1>
    
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdfFile'])): ?>
        
        <?php
        try {
            if ($_FILES['pdfFile']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Error al subir el archivo: ' . $_FILES['pdfFile']['error']);
            }

            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($_FILES['pdfFile']['tmp_name']);
            $textoCompleto = $pdf->getText();
            
            echo "<div class='debug-section info'>";
            echo "<h3>📄 Información del PDF</h3>";
            echo "<p><strong>Archivo:</strong> " . htmlspecialchars($_FILES['pdfFile']['name']) . "</p>";
            echo "<p><strong>Tamaño:</strong> " . number_format($_FILES['pdfFile']['size']) . " bytes</p>";
            echo "<p><strong>Longitud del texto:</strong> " . number_format(strlen($textoCompleto)) . " caracteres</p>";
            echo "</div>";
            
            echo "<div class='debug-section'>";
            echo "<h3>📝 Texto Completo del PDF</h3>";
            echo "<pre>" . htmlspecialchars(substr($textoCompleto, 0, 2000)) . (strlen($textoCompleto) > 2000 ? "..." : "") . "</pre>";
            echo "</div>";
            
            // Buscar líneas que contengan "régimen" o "regimen"
            echo "<div class='debug-section'>";
            echo "<h3>🔍 Líneas que contienen 'Régimen'</h3>";
            $lineas = explode("\n", $textoCompleto);
            $lineasRegimen = [];
            foreach ($lineas as $i => $linea) {
                if (stripos($linea, 'régimen') !== false || stripos($linea, 'regimen') !== false) {
                    $lineasRegimen[] = [
                        'numero' => $i + 1,
                        'contenido' => trim($linea)
                    ];
                }
            }
            
            if (!empty($lineasRegimen)) {
                echo "<ul>";
                foreach ($lineasRegimen as $linea) {
                    echo "<li><strong>Línea {$linea['numero']}:</strong> " . htmlspecialchars($linea['contenido']) . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p class='warning'>❌ No se encontraron líneas con 'régimen'</p>";
            }
            echo "</div>";
            
            // Probar todos los patrones de regex
            echo "<div class='debug-section'>";
            echo "<h3>🧪 Prueba de Patrones de Regex</h3>";
            
            $patrones = [
                'Tabla específica' => '/Regímenes:\s*.*?(?:Régimen\s+Fecha\s*Inicio\s*Fecha\s*Fin|Régimen\s+Fecha\s*InicioFecha\s*Fin)\s*([^0-9\n\r]+?)\s*\d{2}\/\d{2}\/\d{4}/s',
                'Directo con fecha' => '/(Régimen General de Ley Personas Morales|Régimen General de Ley Personas Físicas|Régimen Simplificado de Confianza|Régimen de Incorporación Fiscal|Régimen de Actividades Empresariales|Sueldos y Salarios e Ingresos Asimilados a Salarios)[^\d]*\d{2}\/\d{2}\/\d{4}/i',
                'Sección regímenes' => '/Regímenes:.*?(?=Fecha|$)/s',
                'Patrón global' => '/(Régimen General de Ley Personas Morales|Régimen General de Ley Personas Físicas|Régimen Simplificado de Confianza|Régimen de Incorporación Fiscal|Régimen de Actividades Empresariales|Sueldos y Salarios e Ingresos Asimilados a Salarios)/i',
                'Tradicional' => '/Régimen(?:Fiscal)?:\s*(.+?)(?=Fechainicio|Fecha|$)/s',
                'Libre después de Regímenes' => '/Regímenes:\s*[^\n\r]*[\n\r]\s*[^\n\r]*[\n\r]\s*([^0-9\n\r\t]+?)\s*\d{2}\/\d{2}\/\d{4}/s',
                'Muy flexible' => '/(?:Regímenes|Régimen)[\s\S]*?(Régimen [\w\s]+?)\s*\d{2}\/\d{2}\/\d{4}/i'
            ];
            
            $regimenEncontrado = null;
            
            foreach ($patrones as $nombre => $patron) {
                echo "<div class='regex-test ";
                
                if (preg_match($patron, $textoCompleto, $matches)) {
                    echo "found'>";
                    echo "<strong>✅ $nombre:</strong> ";
                    
                    if ($nombre === 'Directo con fecha' || $nombre === 'Patrón global') {
                        $resultado = trim($matches[1]);
                    } elseif ($nombre === 'Sección regímenes') {
                        if (preg_match('/(Régimen General de Ley Personas Morales|Régimen General de Ley Personas Físicas|Régimen Simplificado de Confianza|Régimen de Incorporación Fiscal|Régimen de Actividades Empresariales)/i', $matches[0], $regMatch)) {
                            $resultado = trim($regMatch[1]);
                        } else {
                            echo "Sección encontrada pero sin régimen específico";
                            echo "<pre>" . htmlspecialchars(substr($matches[0], 0, 200)) . "</pre>";
                            continue;
                        }
                    } else {
                        $resultado = isset($matches[1]) ? trim($matches[1]) : 'Sin captura';
                    }
                    
                    echo htmlspecialchars($resultado);
                    if (!$regimenEncontrado && !empty($resultado) && $resultado !== 'Sin captura') {
                        $regimenEncontrado = $resultado;
                    }
                    
                    // Mostrar contexto del match
                    echo "<br><small><strong>Contexto:</strong> " . htmlspecialchars(substr($matches[0], 0, 150)) . "...</small>";
                    
                } else {
                    echo "not-found'>";
                    echo "<strong>❌ $nombre:</strong> No encontrado";
                }
                echo "</div>";
            }
            echo "</div>";
            
            // Si encontramos un régimen, buscar en la base de datos
            if (!empty($regimenEncontrado)) {
                echo "<div class='debug-section success'>";
                echo "<h3>🎯 Búsqueda en Base de Datos</h3>";
                echo "<p><strong>Régimen extraído:</strong> " . htmlspecialchars($regimenEncontrado) . "</p>";
                
                try {
                    $db = new Database();
                    $conn = $db->getConnection();
                    
                    $stmt = $conn->prepare("SELECT clave, descripcion FROM cat_regimen_fiscal ORDER BY descripcion");
                    $stmt->execute();
                    $regimenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    echo "<h4>Comparación con regímenes en BD:</h4>";
                    echo "<table style='border-collapse:collapse; width:100%;'>";
                    echo "<tr style='background:#f8f9fa;'><th style='border:1px solid #ddd; padding:8px;'>Clave</th><th style='border:1px solid #ddd; padding:8px;'>Descripción</th><th style='border:1px solid #ddd; padding:8px;'>Similitud</th></tr>";
                    
                    $mejorCoincidencia = null;
                    $mayorSimilitud = 0;
                    
                    foreach ($regimenes as $regimen) {
                        similar_text(
                            strtolower($regimenEncontrado), 
                            strtolower($regimen['descripcion']), 
                            $porcentaje
                        );
                        
                        $distancia = levenshtein(
                            strtolower($regimenEncontrado), 
                            strtolower($regimen['descripcion'])
                        );
                        $similitudLevenshtein = max(0, 100 - ($distancia * 2));
                        $similitudPromedio = ($porcentaje + $similitudLevenshtein) / 2;
                        
                        $colorFondo = $similitudPromedio >= 60 ? '#d4edda' : ($similitudPromedio >= 30 ? '#fff3cd' : '#fff');
                        
                        echo "<tr style='background:$colorFondo;'>";
                        echo "<td style='border:1px solid #ddd; padding:8px;'>{$regimen['clave']}</td>";
                        echo "<td style='border:1px solid #ddd; padding:8px;'>{$regimen['descripcion']}</td>";
                        echo "<td style='border:1px solid #ddd; padding:8px;'>" . round($similitudPromedio, 2) . "%</td>";
                        echo "</tr>";
                        
                        if ($similitudPromedio > $mayorSimilitud) {
                            $mayorSimilitud = $similitudPromedio;
                            $mejorCoincidencia = $regimen;
                        }
                    }
                    echo "</table>";
                    
                    if ($mejorCoincidencia && $mayorSimilitud >= 60) {
                        echo "<div class='debug-section success'>";
                        echo "<h4>🎉 ¡Régimen Identificado!</h4>";
                        echo "<p><strong>Clave:</strong> {$mejorCoincidencia['clave']}</p>";
                        echo "<p><strong>Descripción:</strong> {$mejorCoincidencia['descripcion']}</p>";
                        echo "<p><strong>Similitud:</strong> " . round($mayorSimilitud, 2) . "%</p>";
                        echo "</div>";
                    } else {
                        echo "<div class='debug-section warning'>";
                        echo "<h4>⚠️ Régimen no identificado con suficiente confianza</h4>";
                        echo "<p>Mejor coincidencia: " . ($mejorCoincidencia ? $mejorCoincidencia['descripcion'] : 'N/A') . "</p>";
                        echo "<p>Similitud máxima: " . round($mayorSimilitud, 2) . "% (se requiere mínimo 60%)</p>";
                        echo "</div>";
                    }
                    
                } catch (Exception $e) {
                    echo "<div class='debug-section error'>";
                    echo "<h4>❌ Error de Base de Datos</h4>";
                    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
                    echo "</div>";
                }
                
            } else {
                echo "<div class='debug-section error'>";
                echo "<h3>❌ No se pudo extraer el régimen fiscal</h3>";
                echo "<p>Ninguno de los patrones de regex funcionó con este PDF.</p>";
                echo "</div>";
            }
            
        } catch (Exception $e) {
            echo "<div class='debug-section error'>";
            echo "<h3>❌ Error al procesar PDF</h3>";
            echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
            echo "</div>";
        }
        ?>
        
    <?php else: ?>
        
        <div class="debug-section info">
            <h3>📤 Sube tu PDF para analizar</h3>
            <form method="POST" enctype="multipart/form-data">
                <p>
                    <label for="pdfFile">Selecciona tu constancia de situación fiscal (PDF):</label><br>
                    <input type="file" id="pdfFile" name="pdfFile" accept=".pdf" required>
                </p>
                <p>
                    <button type="submit" style="background:#007bff; color:white; padding:10px 20px; border:none; border-radius:4px; cursor:pointer;">
                        🔍 Analizar PDF
                    </button>
                </p>
            </form>
        </div>
        
    <?php endif; ?>
    
</body>
</html>