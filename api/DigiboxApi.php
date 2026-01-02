<?php
// core/api/DigiboxApi.php

// class DigiboxApi {
//     private $user;
//     private $pass;
//     private $token;

//     // Asegúrate de que esta URL sea la de V4 que indicaste
//     const URL_TIMBRADO = 'https://testtimbrado.digibox.com.mx/apisellado/timbradoxml/v4';
//     // Ajusta tu URL de autenticación según corresponda (normalmente no cambia mucho, pero revisa si tienes una V4 de auth)
//     const URL_AUTH = 'https://testtimbrado.digibox.com.mx/api/autenticacion/autenticarbasico'; 

//     public function __construct() {
//         // Definidos en config.php
//         $this->user = 'demo2'; 
//         $this->pass = '123456789';
//         $this->token = 'T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUTQyWFhnTUxGYjdKdG8xQTZWVjFrUDNiOTVrRkhiOGk3RHladHdMaEM0cS8rcklzaUhJOGozWjN0K2h6R3gwQzF0c0g5aGNBYUt6N2srR3VoMUw3amtvPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGRTK3dNKzdIdmFVaE1qMkVKSUV2ZnpFaG4ydkgvUTZaSHRnMk5oU21JWUtjUWhrSG5XeGxCdlFLd2xiN0NkY1B5WFFHSWdGUWEzVkxGSTMyTHlNVGdIYVhKcTZKNUFsVjBheDJoVGFqUEpDL1hubk9qRDhaUGhyT3JZa2JTOVQ4eDFyWjRMRDdGYmE5bjJkL2Vxam9GUGMydVVRQmVONk15T3dTQStuc0ZXQzJvU2pQNGFORVlSejBiYzRwOWttVkdRdlNtY29zQjhPNWQwOTNkNW41VGwrM3ZBR1B1dnlHZFA5SklBSC9HTVJ6c1UxMWZyYURCcThWWUxhRFlaMjZpVEs4Zk9zcUhhZnpPd0loeG5sbVBwc1RaVkpqM0YraTA3aG84dVNwc1VLOWNoQU9WbXJiSnBOVHoyN1paUGVYN3U0aWRPRDdkWnVKdGFRSFJ3Q0N1Qk1NQzZkWHYyTUxNQllVaDRNbTh4TVU9._4rUA_oASN2rIzSiD6FNet-ZgbFJWlqpLNhX__QwkOc';
//     }

//     public function autenticar() {
//         $ch = curl_init(self::URL_AUTH);
        
//         $credenciales = [
//             'usuario' => $this->user,
//             'password' => $this->pass 
//         ];
//         $payload = json_encode($credenciales);

//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, [
//             'Content-Type: application/json',
//             'Accept: application/json'
//         ]);

//         $response = curl_exec($ch);
//         $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//         curl_close($ch);

//         if ($httpCode == 200) {
//             // Digibox suele devolver el token como string o json. 
//             // Asumimos que lo devuelve limpio o en JSON. Ajustar según respuesta real de Auth.
//             // Si devuelve "el_token_xyz", usar trim.
//             $json = json_decode($response, true);
//             $this->token = $json['token'] ?? trim($response, '"'); 
//             return true;
//         }
        
//         throw new Exception("Error Auth ($httpCode): " . $response);
//     }

//     /**
//      * TIMBRADO V4 (XML RAW)
//      */
//     public function timbrar($xmlString) {
//         if (!$this->token) {
//             $this->autenticar();
//         }

//         // 1. Limpieza del XML (Evitar BOM y espacios)
//         $xmlBody = trim($xmlString);
//         $xmlBody = ltrim($xmlBody, "\xEF\xBB\xBF"); 

//         $ch = curl_init(self::URL_TIMBRADO);
        
//         // 2. Configuración para RAW XML
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlBody); // ENVIAMOS EL XML DIRECTO
//         curl_setopt($ch, CURLOPT_TIMEOUT, 60);

//         // 3. Headers según tu documentación
//         $headers = [
//             'Token: ' . $this->token,          // Documentación: "Key: Token"
//             'Content-Type: application/xml',   // Importante para Body Raw
//             'Accept: application/xml'          // Esperamos XML de respuesta
//         ];
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

//         $response = curl_exec($ch);
//         $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//         $curlErr = curl_error($ch);
//         curl_close($ch);

//         if ($curlErr) {
//             throw new Exception('Error conexión PAC: ' . $curlErr);
//         }

//         // 4. Manejo de Respuesta según documentación
//         // Response 200 (ok): XML (string)
//         if ($httpCode == 200) {
//             // Validamos que parezca un XML
//             if (strpos($response, '<?xml') !== false || strpos($response, 'tfd:TimbreFiscalDigital') !== false) {
//                 return $response; // Devolvemos el XML timbrado
//             }
//             throw new Exception("El PAC respondió 200 OK pero no devolvió un XML válido.");
//         }

//         // Response 500 (Error): JSON
//         // { “Message”:”string”, “ExceptionMessage”: “string” }
//         if ($httpCode == 500 || $httpCode == 400) {
//             $jsonError = json_decode($response, true);
//             $msg = $jsonError['Message'] ?? 'Error desconocido';
//             $exMsg = $jsonError['ExceptionMessage'] ?? '';
            
//             throw new Exception("Digibox Error ($httpCode): $msg - $exMsg");
//         }

//         throw new Exception("Error inesperado ($httpCode): " . substr($response, 0, 200));
//     }
// }
?>