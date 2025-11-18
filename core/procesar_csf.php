<?php

require_once __DIR__ . '/autoload-vendor.php';

use Smalot\PdfParser;

header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);

$respuesta = [
    'success' => false,
    'data'    => [],
    'message' => 'Error desconocido.'
];

$filePath = $_FILES['constanciaFile']['tmp_name'];

try {
    if (!isset($_FILES['constanciaFile']) && $_FILES['constanciaFile']['error'] == UPLOAD_ERR_OK) {
        throw new Exception('No se ha subido ningún archivo o hubo un error en la subida.');
    }

    $parser = new \Smalot\PdfParser\Parser();
    $pdf = $parser->parseFile($_FILES['constanciaFile']['tmp_name']);
    $textoCompleto = $pdf->getText();
    $datosEncontrados = [];

    if (preg_match('/RFC:\s*([A-Z&Ñ]{3,4}\d{6}[A-Z\d]{3})/', $textoCompleto, $matches)) {
        $datosEncontrados['rfcFiscal'] = trim($matches[1]);
    }

    if (preg_match('/Denominación\/RazónSocial:\s*(.+?)(?=RégimenCapital|Régimen|Fechainicio)/s', $textoCompleto, $matches)) {
        $datosEncontrados['nombreFiscal'] = trim($matches[1]);
    }

    if (preg_match('/C(ó|o)digo\s*Postal[\s:]*(\d{5})/', $textoCompleto, $matches)) {
        $datosEncontrados['cpFiscal'] = trim($matches[2]);
    }
    if (preg_match('/Régimenes\s*(.+?)(?=Fecha Inicio)/s', $textoCompleto, $matches)) {
        $regimenTexto = trim($matches[1]);
    }

    if (!empty($datosEncontrados)) {
        $respuesta['success'] = true;
        $respuesta['data'] = $datosEncontrados;
        $respuesta['message'] = 'Datos extraídos correctamente.';
    } else {
        $respuesta['message'] = 'Se leyó el PDF, pero no se pudieron extraer datos (posiblemente es una imagen).';
    }

    $respuesta['debug_raw_text'] = substr($textoCompleto, 0, 5000);
} catch (Exception $e) {
    $respuesta['message'] = 'Error al procesar el PDF: ' . $e->getMessage();
}

echo json_encode($respuesta);
