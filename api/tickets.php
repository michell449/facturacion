<?php
// api/tickets.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");

require_once '../core/class/db.php';
require_once '../core/class/ApiClient.php';
require_once '../core/class/IntegrarTickets.php';   

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true) ?: [];

$response = ['success' => false, 'message' => 'Acción no válida'];

if (isset($_GET['accion'])) {
    $accion = $_GET['accion'];
    
    // Instancias
    $db = new Database();
    $conn = $db->getConnection();
    $localApi = new ApiClient($conn);        
    $externalSync = new IntegrarTickets(); 

    switch ($accion) {
        
        // ACCIÓN NUEVA: IMPORTAR DESDE OTRO SISTEMA
        // Uso: POST api/tickets.php?accion=importar body: { "folio": "A100", "id_empresa": 14 }
        case 'importar':
            if (!isset($input['folio'], $input['id_empresa'])) {
                $response = ['success' => false, 'message' => 'Falta folio o id_empresa'];
                break;
            }
            
            // 1. Ir a buscarlo al otro sistema
            $externo = $externalSync->fetchExternalTicket($input['folio']);
            
            if (!$externo['success']) {
                $response = $externo; // Devolver error si no se conecta
                break;
            }

            // 2. Guardarlo en nuestra BD
            // $externo['data'] contiene el JSON del otro sistema
            $importacion = $externalSync->importToLocalDB($externo['data'], $input['id_empresa']);
            
            $response = $importacion;
            break;

        // TUS ACCIONES EXISTENTES (buscar, consultar, etc...)
        // (Déjalas tal como te las corregí en la respuesta anterior)
        case 'buscar':
            // ... código existente ...
            $response = $localApi->searchTickets($_GET);
            break;

        case 'consultar':
             // ... código existente ...
             $response = $localApi->getTicket($_GET['ticket_id']);
             break;

        case 'facturar_ok': 
             // ESTA ACCIÓN LA LLAMAS CUANDO TU SISTEMA TERMINE DE TIMBRAR
             // Para avisar al otro lado
             if (!isset($input['folio_externo'], $input['uuid'])) {
                 $response = ['success' => false, 'message' => 'Faltan datos'];
                 break;
             }
             $externalSync->notifyExternalInvoiced($input['folio_externo'], $input['uuid']);
             $response = ['success' => true, 'message' => 'Sincronizado'];
             break;
    }
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>