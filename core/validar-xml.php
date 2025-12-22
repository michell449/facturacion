<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validador de XML CFDI 4.0</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        .section {
            margin: 20px 0;
            padding: 15px;
            border-left: 4px solid #3498db;
            background: #f8f9fa;
        }
        .section h2 {
            margin-top: 0;
            color: #34495e;
            font-size: 1.3em;
        }
        .success {
            color: #27ae60;
            font-weight: bold;
        }
        .error {
            color: #e74c3c;
            font-weight: bold;
        }
        .warning {
            color: #f39c12;
            font-weight: bold;
        }
        .info-row {
            display: flex;
            margin: 8px 0;
            padding: 8px;
            background: white;
            border-radius: 4px;
        }
        .info-label {
            font-weight: bold;
            min-width: 200px;
            color: #555;
        }
        .info-value {
            color: #2c3e50;
            word-break: break-all;
        }
        pre {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            font-size: 12px;
            line-height: 1.4;
        }
        .check-item {
            padding: 10px;
            margin: 5px 0;
            border-radius: 4px;
            background: white;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 5px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background: #2980b9;
        }
        .btn-danger {
            background: #e74c3c;
        }
        .btn-danger:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Validador de XML CFDI 4.0</h1>
        
        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        require_once __DIR__ . '/../config.php';
        require_once __DIR__ . '/class/db.php';
        
        if (!isset($_GET['id_factura'])) {
            echo '<p class="warning">⚠️ Uso: validar-xml.php?id_factura=X</p>';
            echo '<p>Ingresa el ID de la factura que quieres validar antes de timbrar.</p>';
            exit;
        }
        
        $id_factura = (int)$_GET['id_factura'];
        
        $db = new Database();
        $conn = $db->getConnection();
        
        // Obtener datos de factura
        $sql = "SELECT f.*, 
                e.rfc as emisor_rfc, 
                e.razon_social as emisor_nombre,
                e.reg_fiscal as emisor_regimen,
                e.cp as emisor_cp
                FROM facturas f 
                JOIN empresas e ON f.id_empresa = e.id_empresa
                WHERE f.id_factura = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_factura]);
        $factura = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$factura) {
            echo '<p class="error">❌ Factura no encontrada</p>';
            exit;
        }
        
        // Obtener conceptos
        $stmtConc = $conn->prepare("SELECT * FROM facturas_detalles WHERE id_factura = ?");
        $stmtConc->execute([$id_factura]);
        $conceptos = $stmtConc->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<div class='section'>";
        echo "<h2>📄 Factura #{$id_factura}</h2>";
        echo "<p><strong>Estado:</strong> {$factura['estado']}</p>";
        echo "<p><strong>Folio:</strong> {$factura['serie_interno']}{$factura['folio_interno']}</p>";
        echo "</div>";
        
        // VALIDACIONES CRÍTICAS
        $errores = [];
        $warnings = [];
        
        echo "<div class='section'>";
        echo "<h2>✅ Validaciones CFDI 4.0</h2>";
        
        // 1. RFC RECEPTOR
        echo "<div class='check-item'>";
        echo "<strong>1. RFC del Receptor</strong><br>";
        $rfcReceptor = strtoupper(trim($factura['rfc_receptor']));
        echo "RFC: <code>{$rfcReceptor}</code> ";
        
        if (empty($rfcReceptor)) {
            echo "<span class='error'>❌ RFC FALTANTE</span>";
            $errores[] = "RFC del receptor está vacío";
        } else if ($rfcReceptor === 'XAXX010101000' || $rfcReceptor === 'XEXX010101000') {
            echo "<span class='warning'>⚠️ RFC GENÉRICO (Público en General)</span>";
            $warnings[] = "Usando RFC genérico - debe tener Régimen 616 y Uso S01";
        } else {
            echo "<span class='success'>✓ RFC válido</span>";
        }
        echo "</div>";
        
        // 2. NOMBRE/RAZÓN SOCIAL
        echo "<div class='check-item'>";
        echo "<strong>2. Nombre/Razón Social del Receptor</strong><br>";
        $nombreOriginal = $factura['razon_social_receptor'];
        echo "Nombre en BD: <code>{$nombreOriginal}</code><br>";
        
        // Simular limpieza
        $nombreLimpio = mb_strtoupper($nombreOriginal, 'UTF-8');
        $nombreLimpio = str_replace(['Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ'], ['A', 'E', 'I', 'O', 'U', 'N'], $nombreLimpio);
        $patrones = [
            '/\s*,?\s*S\.?A\.?\s*DE\s*C\.?V\.?\s*$/i',
            '/\s*,?\s*S\.?A\.?\s*$/i',
            '/\s*,?\s*S\.?C\.?\s*$/i'
        ];
        foreach ($patrones as $patron) {
            $nombreLimpio = preg_replace($patron, '', $nombreLimpio);
        }
        $nombreLimpio = trim(preg_replace('/\s+/', ' ', $nombreLimpio));
        
        echo "Nombre en XML: <code>{$nombreLimpio}</code> ";
        
        if ($nombreOriginal !== $nombreLimpio) {
            echo "<span class='success'>✓ Se limpiará automáticamente</span>";
        } else {
            echo "<span class='success'>✓ Ya está limpio</span>";
        }
        
        if (preg_match('/S\.?A\.?|S\.?C\.?|DE\s*C\.?V\.?/i', $nombreOriginal)) {
            $warnings[] = "El nombre contiene régimen societario, será eliminado automáticamente";
        }
        echo "</div>";
        
        // 3. CÓDIGO POSTAL
        echo "<div class='check-item'>";
        echo "<strong>3. Código Postal del Receptor</strong><br>";
        $cp = $factura['domicilio_fiscal_receptor'];
        echo "CP: <code>{$cp}</code> ";
        
        if (empty($cp)) {
            echo "<span class='error'>❌ CP FALTANTE</span>";
            $errores[] = "Código Postal del receptor está vacío";
        } else if (!preg_match('/^\d{5}$/', $cp)) {
            echo "<span class='error'>❌ CP INVÁLIDO (debe ser 5 dígitos)</span>";
            $errores[] = "Código Postal inválido: {$cp}";
        } else {
            echo "<span class='success'>✓ Formato correcto</span>";
        }
        echo "</div>";
        
        // 4. RÉGIMEN FISCAL RECEPTOR
        echo "<div class='check-item'>";
        echo "<strong>4. Régimen Fiscal del Receptor</strong><br>";
        $regimenReceptor = $factura['regimen_fiscal_receptor'];
        echo "Régimen: <code>{$regimenReceptor}</code> ";
        
        if (empty($regimenReceptor)) {
            echo "<span class='error'>❌ RÉGIMEN FALTANTE</span>";
            $errores[] = "Régimen Fiscal del receptor está vacío";
        } else {
            echo "<span class='success'>✓ Régimen especificado</span>";
            
            // Validar compatibilidad con RFC genérico
            if (($rfcReceptor === 'XAXX010101000' || $rfcReceptor === 'XEXX010101000') && $regimenReceptor !== '616') {
                echo "<br><span class='error'>❌ RFC Genérico debe usar Régimen 616</span>";
                $errores[] = "RFC Genérico incompatible con Régimen {$regimenReceptor}";
            }
        }
        echo "</div>";
        
        // 5. USO CFDI
        echo "<div class='check-item'>";
        echo "<strong>5. Uso CFDI</strong><br>";
        $usoCfdi = $factura['uso_cfdi'];
        echo "Uso: <code>{$usoCfdi}</code> ";
        
        if (empty($usoCfdi)) {
            echo "<span class='error'>❌ USO CFDI FALTANTE</span>";
            $errores[] = "Uso CFDI está vacío";
        } else {
            echo "<span class='success'>✓ Uso especificado</span>";
            
            // Validar compatibilidad con RFC genérico
            if (($rfcReceptor === 'XAXX010101000' || $rfcReceptor === 'XEXX010101000') && $usoCfdi !== 'S01') {
                echo "<br><span class='warning'>⚠️ RFC Genérico debería usar S01</span>";
                $warnings[] = "RFC Genérico incompatible con Uso {$usoCfdi}";
            }
        }
        echo "</div>";
        
        // 6. MÉTODO Y FORMA DE PAGO
        echo "<div class='check-item'>";
        echo "<strong>6. Método y Forma de Pago</strong><br>";
        $metodoPago = $factura['metodo_pago'];
        $formaPago = $factura['forma_pago'];
        echo "Método: <code>{$metodoPago}</code>, Forma: <code>{$formaPago}</code> ";
        
        if ($metodoPago === 'PUE' && $formaPago === '99') {
            echo "<span class='error'>❌ PUE no compatible con Forma 99</span>";
            $errores[] = "Método PUE no es compatible con Forma de Pago 99";
        } else if ($metodoPago === 'PPD' && $formaPago !== '99') {
            echo "<span class='error'>❌ PPD solo compatible con Forma 99</span>";
            $errores[] = "Método PPD solo es compatible con Forma de Pago 99";
        } else {
            echo "<span class='success'>✓ Compatibles</span>";
        }
        echo "</div>";
        
        // 7. CONCEPTOS
        echo "<div class='check-item'>";
        echo "<strong>7. Conceptos</strong><br>";
        if (empty($conceptos)) {
            echo "<span class='error'>❌ NO HAY CONCEPTOS</span>";
            $errores[] = "La factura no tiene conceptos";
        } else {
            echo "<span class='success'>✓ {count($conceptos)} concepto(s)</span>";
        }
        echo "</div>";
        
        echo "</div>";
        
        // RESUMEN
        echo "<div class='section'>";
        if (empty($errores)) {
            echo "<h2 class='success'>✅ LISTO PARA TIMBRAR</h2>";
            echo "<p>No se encontraron errores críticos. El XML se puede generar y timbrar.</p>";
        } else {
            echo "<h2 class='error'>❌ NO SE PUEDE TIMBRAR</h2>";
            echo "<p><strong>Errores encontrados:</strong></p><ul>";
            foreach ($errores as $error) {
                echo "<li class='error'>{$error}</li>";
            }
            echo "</ul>";
        }
        
        if (!empty($warnings)) {
            echo "<p><strong>Advertencias:</strong></p><ul>";
            foreach ($warnings as $warning) {
                echo "<li class='warning'>{$warning}</li>";
            }
            echo "</ul>";
        }
        echo "</div>";
        
        // DATOS DEL EMISOR
        echo "<div class='section'>";
        echo "<h2>🏢 Datos del Emisor</h2>";
        echo "<div class='info-row'><span class='info-label'>RFC:</span><span class='info-value'>{$factura['emisor_rfc']}</span></div>";
        echo "<div class='info-row'><span class='info-label'>Nombre:</span><span class='info-value'>{$factura['emisor_nombre']}</span></div>";
        echo "<div class='info-row'><span class='info-label'>Régimen:</span><span class='info-value'>{$factura['emisor_regimen']}</span></div>";
        echo "<div class='info-row'><span class='info-label'>CP:</span><span class='info-value'>{$factura['emisor_cp']}</span></div>";
        echo "</div>";
        
        // CONCEPTOS DETALLE
        if (!empty($conceptos)) {
            echo "<div class='section'>";
            echo "<h2>📦 Conceptos</h2>";
            foreach ($conceptos as $i => $concepto) {
                $num = $i + 1;
                echo "<div class='check-item'>";
                echo "<strong>Concepto #{$num}</strong><br>";
                echo "<div class='info-row'><span class='info-label'>Descripción:</span><span class='info-value'>{$concepto['descripcion']}</span></div>";
                echo "<div class='info-row'><span class='info-label'>Clave SAT:</span><span class='info-value'>{$concepto['clave_prod_serv']}</span></div>";
                echo "<div class='info-row'><span class='info-label'>Cantidad:</span><span class='info-value'>{$concepto['cantidad']}</span></div>";
                echo "<div class='info-row'><span class='info-label'>Valor Unitario:</span><span class='info-value'>\${$concepto['valor_unitario']}</span></div>";
                echo "<div class='info-row'><span class='info-label'>Importe:</span><span class='info-value'>\${$concepto['importe']}</span></div>";
                echo "</div>";
            }
            echo "</div>";
        }
        
        // VER XML SI EXISTE
        if (!empty($factura['xml_path'])) {
            $rutaXml = __DIR__ . '/../uploads/xml_timbrados/' . $factura['xml_path'];
            if (file_exists($rutaXml)) {
                $xmlContent = file_get_contents($rutaXml);
                echo "<div class='section'>";
                echo "<h2>📜 XML Generado</h2>";
                echo "<details><summary>Click para ver el XML completo</summary>";
                echo "<pre>" . htmlspecialchars($xmlContent) . "</pre>";
                echo "</details>";
                echo "</div>";
            }
        }
        
        // ACCIONES
        echo "<div class='section'>";
        echo "<h2>🎯 Acciones</h2>";
        echo "<a href='?id_factura={$id_factura}' class='btn'>🔄 Recargar</a>";
        echo "<a href='debug-xml.php?id_factura={$id_factura}' class='btn'>🔍 Debug Técnico</a>";
        
        if (empty($errores)) {
            echo "<a href='#' onclick='generarYTimbrar({$id_factura})' class='btn'>🚀 Generar y Timbrar</a>";
        }
        echo "</div>";
        ?>
        
        <script>
        function generarYTimbrar(idFactura) {
            if (!confirm('¿Generar XML y timbrar factura #' + idFactura + '?')) return;
            
            alert('Esta función debe integrarse con tu flujo de timbrado actual.\nPor ahora, usa la interfaz principal.');
        }
        </script>
    </div>
</body>
</html>
