<?php
// generar-pdf-factura.php

// 1. LIMPIEZA TOTAL DEL BUFFER (CRÍTICO PARA MPDF)
// Esto borra cualquier "Warning" o espacio en blanco previo que corrompa el PDF
while (ob_get_level() > 0) {
    ob_end_clean();
}

// 2. Cargar dependencias
require_once __DIR__ . '/class/generador-pdf.php';
require_once __DIR__ . '/../config.php';

// 3. Validar Sesión
$id_usuario = $_SESSION['usuario_id'] ?? $_SESSION['USR_ID'] ?? null;

if (!$id_usuario) {
    http_response_code(403);
    die("Acceso denegado. <a href='../index.php'>Iniciar sesión</a>");
}

// 4. Validar Entrada
$id_factura = isset($_GET['id_factura']) ? (int)$_GET['id_factura'] : 0;
// Si ?download=1 forzamos descarga ('D'), si no, se ve en navegador ('I')
$modo = isset($_GET['download']) && $_GET['download'] == 1 ? 'D' : 'I';

if ($id_factura <= 0) {
    die("ID de factura inválido.");
}

try {
    // Llamada a la clase estática
    // Parametros: ID Factura, ID Usuario, Guardar en Disco (Sí), Modo de Salida ('I' o 'D')
    PdfGenerator::generar($id_factura, $id_usuario, true, $modo);

} catch (Exception $e) {
    // Manejo de errores amigable
    http_response_code(500);
    echo '<div style="font-family:Arial; padding:20px; border:1px solid red; background:#fff0f0; border-radius:5px; margin:20px;">';
    echo '<h3 style="color:#d9534f;">Error al generar el PDF</h3>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<a href="#" onclick="window.history.back();">Volver</a>';
    echo '</div>';
}
?>