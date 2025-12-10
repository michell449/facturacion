<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura CFDI 4.0</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
</head>
<body>

<!-- Botones de acción (no se imprimen) -->
<div class="no-print text-center mb-3 p-3 bg-light">
    <button class="btn btn-primary" onclick="window.print()">
        <i class="bi bi-printer me-2"></i>Imprimir
    </button>
    <button class="btn btn-info" onclick="generarPDF()">
        <i class="bi bi-file-pdf me-2"></i>Generar PDF
    </button>
    <button class="btn btn-secondary" onclick="window.history.back()">
        <i class="bi bi-arrow-left me-2"></i>Regresar
    </button>
</div>

<?php
// Capturar la salida del backend mediante output buffering
ob_start();
require __DIR__ . '/../core/plantilla-factura.php';
$contenido_factura = ob_get_clean();

// Mostrar el contenido generado
echo $contenido_factura;
?>

<script>
function generarPDF() {
    const urlParams = new URLSearchParams(window.location.search);
    const idFactura = urlParams.get('id_factura') || 0;
    
    if (idFactura > 0) {
        window.open('core/generar-pdf-factura.php?id_factura=' + idFactura + '&guardar=1', '_blank');
    } else {
        alert('No se puede generar PDF en modo vista previa. Primero genere la factura.');
    }
}
</script>

</body>
</html>