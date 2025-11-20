<?php
// Script de prueba para verificar la API de colonias
require_once 'config.php';

// Códigos postales de prueba
$codigosPostalPrueba = ['01000', '03100', '06700', '11560', '12345']; // El último no existe para probar error

?>
<!DOCTYPE html>
<html>
<head>
    <title>Prueba API Colonias</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ccc; }
        .success { background-color: #d4edda; border-color: #c3e6cb; }
        .error { background-color: #f8d7da; border-color: #f5c6cb; }
        .info { background-color: #d1ecf1; border-color: #bee5eb; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>🧪 Prueba de API de Colonias</h1>
    
    <div class="test-section info">
        <h3>Probando códigos postales:</h3>
        <p><?php echo implode(', ', $codigosPostalPrueba); ?></p>
    </div>

    <?php foreach($codigosPostalPrueba as $cp): ?>
    <div class="test-section">
        <h3>📍 Código Postal: <?php echo $cp; ?></h3>
        
        <div id="resultado-<?php echo $cp; ?>">
            <button onclick="probarCP('<?php echo $cp; ?>')">Probar CP <?php echo $cp; ?></button>
            <div id="respuesta-<?php echo $cp; ?>"></div>
        </div>
    </div>
    <?php endforeach; ?>

    <script>
    async function probarCP(codigoPostal) {
        const divRespuesta = document.getElementById('respuesta-' + codigoPostal);
        const divResultado = document.getElementById('resultado-' + codigoPostal);
        
        divRespuesta.innerHTML = '<p>🔄 Cargando...</p>';
        
        try {
            const response = await fetch('core/obtener-colonias-cp.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    codigo_postal: codigoPostal
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                throw new Error(`Respuesta no es JSON. Contenido: ${text.substring(0, 200)}...`);
            }

            const result = await response.json();
            
            if (result.success) {
                divResultado.className = 'test-section success';
                divRespuesta.innerHTML = `
                    <h4>✅ Éxito</h4>
                    <p><strong>Ciudad:</strong> ${result.data.ciudad || 'N/A'}</p>
                    <p><strong>Estado:</strong> ${result.data.estado || 'N/A'}</p>
                    <p><strong>Municipio:</strong> ${result.data.municipio || 'N/A'}</p>
                    <p><strong>Total colonias:</strong> ${result.data.total_colonias || 0}</p>
                    
                    ${result.data.colonias ? `
                        <h5>Colonias encontradas:</h5>
                        <ul>
                            ${result.data.colonias.slice(0, 5).map(col => 
                                `<li>${col.d_asenta} (${col.tipo_asenta})</li>`
                            ).join('')}
                            ${result.data.colonias.length > 5 ? '<li>... y más</li>' : ''}
                        </ul>
                    ` : ''}
                    
                    <details>
                        <summary>Ver JSON completo</summary>
                        <pre>${JSON.stringify(result, null, 2)}</pre>
                    </details>
                `;
            } else {
                divResultado.className = 'test-section error';
                divRespuesta.innerHTML = `
                    <h4>❌ Error en la respuesta</h4>
                    <p><strong>Mensaje:</strong> ${result.message || 'Sin mensaje'}</p>
                    <details>
                        <summary>Ver JSON completo</summary>
                        <pre>${JSON.stringify(result, null, 2)}</pre>
                    </details>
                `;
            }
            
        } catch (error) {
            divResultado.className = 'test-section error';
            divRespuesta.innerHTML = `
                <h4>💥 Error en la consulta</h4>
                <p><strong>Error:</strong> ${error.message}</p>
                <p><strong>Tipo:</strong> ${error.name}</p>
            `;
        }
    }
    
    // Probar automáticamente el primer código postal
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => probarCP('<?php echo $codigosPostalPrueba[0]; ?>'), 1000);
    });
    </script>
</body>
</html>