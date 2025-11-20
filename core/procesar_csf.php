<?php

require_once __DIR__ . '/autoload-vendor.php';
require_once __DIR__ . '/class/db.php';

use Smalot\PdfParser;

header('Content-Type: application/json; charset=utf-8');
// Mantener display_errors en 0 o comentar en producción.
ini_set('display_errors', 1);

$respuesta = [
    'success' => false,
    'data'    => [],
    'message' => 'Error desconocido.'
];

try {

    if (!isset($_FILES['constanciaFile']) || $_FILES['constanciaFile']['error'] !== UPLOAD_ERR_OK) {
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

    if (preg_match('/Régimen(es)?:(.*?)Obligaciones:/s', $textoCompleto, $matches)) {
        $regimenesListaPDF = $matches[2];

        // Conexión y búsqueda en la base de datos
        try {
            $db = new Database();
            $conn = $db->getConnection();
            $stmt = $conn->prepare("SELECT codigo, descr FROM cat_regimen_fiscal ORDER BY codigo ASC");
            $stmt->execute();
            $regimenesDB = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $codigoEncontrado = null;
            $mejorCoincidencia = 0;
            $regimenLimpio = str_replace(array("\n", "\r", "\t", "  ", 'Fecha Inicio', 'Fecha Fin'), ' ', $regimenesListaPDF);
            $regimenLimpio = trim(preg_replace('/\s+/', ' ', $regimenLimpio));

            foreach ($regimenesDB as $regimen) {
                $descripcionDB = trim($regimen['descr']);

                if (stripos($regimenLimpio, $descripcionDB) !== false) {
                    $codigoEncontrado = $regimen['codigo'];
                    $mejorCoincidencia = 100;
                    break;
                }

                $similitud = 0;

                $porcentajeSimilitud = 0;
                similar_text(strtoupper($regimenLimpio), strtoupper($descripcionDB), $porcentajeSimilitud);

                if ($porcentajeSimilitud >= 80) {
                    $similitud = $porcentajeSimilitud;
                } else {
                    $palabrasClavePDF = explode(' ', strtoupper($regimenLimpio));
                    $palabrasClaveDB = explode(' ', strtoupper($descripcionDB));
                    $palabrasCoincidentes = array_intersect($palabrasClavePDF, $palabrasClaveDB);

                    if (count($palabrasCoincidentes) > 0) {
                        $similitud = (count($palabrasCoincidentes) / count($palabrasClaveDB)) * 100;
                    }
                }

                if ($similitud > $mejorCoincidencia && $similitud >= 75) {
                    $mejorCoincidencia = $similitud;
                    $codigoEncontrado = $regimen['codigo'];
                }
            }

            if ($codigoEncontrado) {
                $datosEncontrados['regimenFiscal'] = $codigoEncontrado;
                $respuesta['debug_regimen'] = [
                    'codigo_encontrado' => $codigoEncontrado,
                    'texto_original_pdf' => $regimenLimpio,
                    'confianza_similitud' => round($mejorCoincidencia, 2) . '%'
                ];
            } else {
                $respuesta['debug_regimen'] = [
                    'codigo_encontrado' => 'No se encontró un código con más del 75% de confianza.',
                    'texto_original_pdf' => $regimenLimpio,
                    'confianza_similitud' => round($mejorCoincidencia, 2) . '%'
                ];
            }
        } catch (Exception $e) {
            $respuesta['message'] = 'Error de Base de Datos: ' . $e->getMessage();
            throw $e;
        }
    }

    if (preg_match('/CódigoPostal:(\d{5})/', $textoCompleto, $matches)) {
        $datosEncontrados['cpFiscal'] = trim($matches[1]);
    }

    //en base al codigo postal, obtener la ciudad, el estado, municipio y colonias de la tabla cat_codigo_postal
    if (!empty($datosEncontrados['cpFiscal'])) {
        try {
            $db = new Database();
            $conn = $db->getConnection();
            
            // Obtener información general del código postal (primera coincidencia)
            $stmt = $conn->prepare("SELECT d_ciudad, d_estado, d_mnpio FROM cat_codigo_postal WHERE d_codigo = ? LIMIT 1");
            $stmt->execute([$datosEncontrados['cpFiscal']]);
            $ubicacion = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($ubicacion) {
                $datosEncontrados['ciudadFiscal'] = $ubicacion['d_ciudad'];
                $datosEncontrados['estadoFiscal'] = $ubicacion['d_estado'];
                $datosEncontrados['municipioFiscal'] = $ubicacion['d_mnpio'];
            }

            // Obtener todas las colonias/asentamientos para este código postal
            $stmtColonias = $conn->prepare("SELECT d_asenta, tipo_asenta FROM cat_codigo_postal WHERE d_codigo = ? ORDER BY d_asenta ASC");
            $stmtColonias->execute([$datosEncontrados['cpFiscal']]);
            $colonias = $stmtColonias->fetchAll(PDO::FETCH_ASSOC);

            if ($colonias) {
                $datosEncontrados['colonias'] = $colonias;
                // También agregar solo los nombres de las colonias para fácil acceso
                $datosEncontrados['listaColonias'] = array_map(function($colonia) {
                    return $colonia['d_asenta'];
                }, $colonias);
            }
            
            // Información adicional para debug
            $respuesta['debug_cp'] = [
                'codigo_postal' => $datosEncontrados['cpFiscal'],
                'colonias_encontradas' => count($colonias ?? []),
                'ubicacion_encontrada' => !empty($ubicacion)
            ];

        } catch (Exception $e) {
            $respuesta['debug_cp'] = [
                'error' => 'Error de BD al obtener ubicación: ' . $e->getMessage(),
                'codigo_postal' => $datosEncontrados['cpFiscal'] ?? 'No disponible'
            ];
            // No lanzar excepción, continuar con otros datos
        }
    }

    if (!empty($datosEncontrados)) {
        $respuesta['success'] = true;
        $respuesta['data'] = $datosEncontrados;
        $respuesta['message'] = 'Datos extraídos correctamente.';
    } else {
        $respuesta['message'] = 'Se leyó el PDF, pero no se pudieron extraer datos.';
    }

    $respuesta['debug_raw_text'] = substr($textoCompleto, 0, 5000);
} catch (Exception $e) {
    $respuesta['message'] = 'Error al procesar el PDF: ' . $e->getMessage();
    // No ponemos success=false aquí, ya que se inicia así.
}

echo json_encode($respuesta);
