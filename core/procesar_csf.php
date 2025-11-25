<?php
// Contenido de core/procesar_csf.php

require_once __DIR__ . '/autoload-vendor.php';
require_once __DIR__ . '/class/db.php';

use Smalot\PdfParser;

header('Content-Type: application/json; charset=utf-8');
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
    $patternRegimen = '/(Régimen|Regímenes)\s*[:\s].*?Régimen\s+Fecha\s*InicioFecha\s*Fin\s*(.+?)(?:\s+\d{2}\/\d{2}\/\d{4}|\s+Obligaciones:|$)/is';
    if (preg_match($patternRegimen, $textoCompleto, $matches)) {
        $extractedRegimen = trim($matches[2]);
        $datosEncontrados['regimenFiscal'] = preg_replace('/\s+/', ' ', $extractedRegimen);
    } else {
        if (preg_match('/Régimen(?:Fiscal)?:\s*(.+?)(?=Fechainicio|Fecha|$)/s', $textoCompleto, $matches)) {
            $datosEncontrados['regimenFiscal'] = trim($matches[1]);
        }
    }
    if (preg_match('/CódigoPostal:(\d{5})/', $textoCompleto, $matches)) {
        $datosEncontrados['cpFiscal'] = trim($matches[1]);
    }
    if (preg_match('/NombredeVialidad:\s*(.+?)(?=NúmeroExterior|NúmeroInterior|Colonia|CódigoPostal)/s', $textoCompleto, $matches)) {
        $datosEncontrados['calleFiscal'] = trim($matches[1]);
    }
    if (preg_match('/NúmeroExterior:\s*(.+?)(?=NúmeroInterior|NombredelaColonia|CódigoPostal)/s', $textoCompleto, $matches)) {
        $datosEncontrados['numeroExteriorFiscal'] = trim($matches[1]);
    }
    if (preg_match('/NúmeroInterior:\s*(.+?)(?=NombredelaColonia|CódigoPostal)/s', $textoCompleto, $matches)) {
        $rawInterior = trim($matches[1]);
        if (!empty($rawInterior)) {
            $datosEncontrados['numeroInteriorFiscal'] = $rawInterior;
        }
    }
    if (preg_match('/NombredelaColonia:\s*(.+?)(?=NombredelaLocalidad|NombredelMunicipiooDemarcaciónTerritorial|NombredelaEntidadFederativa)/s', $textoCompleto, $matches)) {
        $datosEncontrados['coloniaFiscalTexto'] = preg_replace('/\s+/', ' ', trim($matches[1])); 
    }

    $dbNeeded = !empty($datosEncontrados['cpFiscal']) || !empty($datosEncontrados['regimenFiscal']);

    if ($dbNeeded) {
        try {
            $db = new Database();
            $conn = $db->getConnection();

            if (!empty($datosEncontrados['regimenFiscal'])) {
                $cleanedRegimen = $datosEncontrados['regimenFiscal'];
                $normalizedRegimen = mb_strtoupper(str_replace(['É', 'é', 'Á', 'á'], ['E', 'e', 'A', 'a'], $cleanedRegimen), 'UTF-8');
                $searchRegimen = '%' . trim($normalizedRegimen) . '%';
                $stmtRegimen = $conn->prepare("SELECT codigo, descr FROM cat_regimen_fiscal WHERE descr LIKE ? LIMIT 1");
                $stmtRegimen->execute([$searchRegimen]);
                $regimenData = $stmtRegimen->fetch(PDO::FETCH_ASSOC);

                if ($regimenData) {
                    $datosEncontrados['regimenFiscal'] = $regimenData['descr'];
                    $datosEncontrados['regimenFiscalCodigo'] = $regimenData['codigo'];
                    $respuesta['debug_regimen'] = ['codigo_encontrado' => $regimenData['codigo'], 'descripcion_bd' => $regimenData['descr']];
                } else {
                    $datosEncontrados['regimenFiscal_message'] = 'Régimen fiscal extraído no validado: ' . $cleanedRegimen;
                    $respuesta['debug_regimen'] = ['regimen_extraido' => $cleanedRegimen, 'validacion_fallida' => true];
                }
            }
            
            if (!empty($datosEncontrados['cpFiscal'])) {
                $stmt = $conn->prepare("SELECT d_ciudad, d_estado, d_mnpio FROM cat_codigo_postal WHERE d_codigo = ? LIMIT 1");
                $stmt->execute([$datosEncontrados['cpFiscal']]);
                $ubicacion = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($ubicacion) {
                    $datosEncontrados['ciudadFiscal'] = $ubicacion['d_ciudad'];
                    $datosEncontrados['estadoFiscal'] = $ubicacion['d_estado'];
                    $datosEncontrados['municipioFiscal'] = $ubicacion['d_mnpio'];
                }

                $stmtColonias = $conn->prepare("SELECT d_asenta, tipo_asenta FROM cat_codigo_postal WHERE d_codigo = ? ORDER BY d_asenta ASC");
                $stmtColonias->execute([$datosEncontrados['cpFiscal']]);
                $colonias = $stmtColonias->fetchAll(PDO::FETCH_ASSOC);

                if ($colonias) {
                    $datosEncontrados['colonias'] = $colonias;
                    $datosEncontrados['listaColonias'] = array_map(function ($colonia) {
                        return $colonia['d_asenta'];
                    }, $colonias);
                    
                    if (!empty($datosEncontrados['coloniaFiscalTexto'])) {
                        $coloniaBuscada = ($datosEncontrados['coloniaFiscalTexto']);
                        
                        foreach ($colonias as $colonia) {
                            $coloniaDB = ($colonia['d_asenta']);
                            
                            if ($coloniaDB === $coloniaBuscada) {
                                $datosEncontrados['coloniaFiscalSeleccionada'] = $colonia['d_asenta'];
                                $respuesta['debug_colonia'] = ['seleccionada' => $colonia['d_asenta'], 'normalizada' => $coloniaDB];
                                break;
                            }
                        }
                    }
                }
                $respuesta['debug_cp'] = [
                    'codigo_postal' => $datosEncontrados['cpFiscal'],
                    'colonias_encontradas' => count($colonias ?? []),
                    'ubicacion_encontrada' => !empty($ubicacion)
                ];
            }
        } catch (Exception $e) {
            $respuesta['message'] = 'Error de base de datos: ' . $e->getMessage();
            $respuesta['debug_db_error'] = $e->getMessage();
        }
    }

    if (!empty($datosEncontrados)) {
        $respuesta['success'] = true;
        $respuesta['data'] = $datosEncontrados;
        $respuesta['message'] = 'Datos extraídos y validados correctamente.';
    } else {
        $respuesta['message'] = 'Se leyó el PDF, pero no se pudieron extraer datos.';
    }

    $respuesta['debug_raw_text'] = substr($textoCompleto, 0, 5000);
} catch (Exception $e) {
    $respuesta['message'] = 'Error al procesar el PDF: ' . $e->getMessage();
}

echo json_encode($respuesta);