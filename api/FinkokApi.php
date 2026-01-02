<?php
// Ubicación: C:\xampp\htdocs\facturacion\api\FinkokApi.php

// Cargamos las librerías de Composer
require_once __DIR__ . '/../core/autoload-vendor.php';

use PhpCfdi\Finkok\QuickFinkok;
use PhpCfdi\Finkok\FinkokEnvironment;
use PhpCfdi\Finkok\FinkokSettings;
use PhpCfdi\XmlCancelacion\XmlCancelacionHelper;
use PhpCfdi\XmlCancelacion\Credentials;
use PhpCfdi\XmlCancelacion\Models\CancelDocument;
use PhpCfdi\XmlCancelacion\Models\CancelReason;
use PhpCfdi\XmlCancelacion\Models\Uuid;

class FinkokApi {
    private $finkok;
    private $username;
    private $password;
    private $isProduction;
    private $soapCancelUrl;

    public function __construct($username, $password, $isProduction = false) {
        $this->username = $username;
        $this->password = $password;
        $this->isProduction = $isProduction;
        
        // URLs del servicio SOAP de cancelación
        $this->soapCancelUrl = $isProduction 
            ? 'https://facturacion.finkok.com/servicios/soap/cancel.wsdl'
            : 'https://demo-facturacion.finkok.com/servicios/soap/cancel.wsdl';
        
        // Definimos el entorno para timbrado
        $env = $isProduction ? FinkokEnvironment::makeProduction() : FinkokEnvironment::makeDevelopment();
        $settings = new FinkokSettings($username, $password);
        $this->finkok = new QuickFinkok($settings, $env);
    }

    public function timbrar($xmlContent) {
        try {
            // Intentamos timbrar el XML (Método Stamp)
            $stampResult = $this->finkok->stamp($xmlContent);
            
            // Obtenemos el XML ya timbrado y el UUID
            $xmlTimbrado = $stampResult->xml();
            $uuid = $stampResult->uuid();
            $faultString = $stampResult->faultString();
            $faultCode = $stampResult->faultCode();
            $statusCode = $stampResult->statusCode();
            $alerts = $stampResult->alerts();

            // Validación de seguridad básica
            if (empty($xmlTimbrado) || empty($uuid)) {
                // Construimos mensaje de diagnóstico detallado
                $alertasTxt = [];
                if ($alerts && $alerts->count() > 0) {
                    foreach ($alerts as $alerta) {
                        $alertasTxt[] = trim(($alerta->errorCode() ?? '') . ' ' . ($alerta->message() ?? ''));
                    }
                }

                $detalle = [];
                if ($faultCode || $faultString) {
                    $detalle[] = "faultcode: {$faultCode}";
                    $detalle[] = "faultstring: {$faultString}";
                }
                if ($statusCode) {
                    $detalle[] = "CodEstatus: {$statusCode}";
                }
                if (!empty($alertasTxt)) {
                    $detalle[] = 'Incidencias: ' . implode(' | ', $alertasTxt);
                }

                return [
                    'success' => false,
                    'message' => 'Finkok respondió, pero no devolvió el XML timbrado. Revisa créditos o XML.',
                    'detail'  => implode(' | ', $detalle)
                ];
            }

            return [
                'success' => true,
                'xml_timbrado' => $xmlTimbrado,
                'uuid' => $uuid,
                'fecha' => $stampResult->date(),
                'status_code' => $statusCode,
                'fault_code' => $faultCode,
                'fault_string' => $faultString
            ];

        } catch (\SoapFault $e) {
            // Aquí capturamos los errores SOAP (Conexión, 301, 705, etc.)
            return [
                'success' => false,
                'message' => "Error de Finkok (SOAP): " . $e->getMessage() . " (Código: " . $e->faultcode . ")"
            ];
        } catch (\Exception $e) {
            // Errores generales de la librería o PHP
            return [
                'success' => false,
                'message' => "Error interno al timbrar: " . $e->getMessage()
            ];
        }
    }

    /**
     * Cancelar un CFDI usando el método cancel de Finkok
     * 
     * @param string $rfcEmisor RFC del emisor
     * @param string $uuid UUID del comprobante a cancelar
     * @param string $motivoCancelacion Motivo: 01, 02, 03, 04
     * @param string|null $uuidSustitucion UUID de sustitución (requerido para motivo 01)
     * @param string $cerPath Ruta del certificado .cer
     * @param string $keyPath Ruta de la llave privada .key
     * @param string $keyPassword Contraseña de la llave privada
     * @return array Resultado de la cancelación
     */
    public function cancelarFactura($rfcEmisor, $uuid, $motivoCancelacion = '02', $uuidSustitucion = null, 
                                   $cerPath = null, $keyPath = null, $keyPassword = null) {
        try {
            // Validar motivo de cancelación
            if (!in_array($motivoCancelacion, ['01', '02', '03', '04'])) {
                return [
                    'success' => false,
                    'message' => 'Motivo de cancelación inválido. Debe ser 01, 02, 03 o 04.'
                ];
            }

            // Si el motivo es 01, requiere UUID de sustitución
            if ($motivoCancelacion === '01' && empty($uuidSustitucion)) {
                return [
                    'success' => false,
                    'message' => 'El motivo 01 requiere un UUID de sustitución.'
                ];
            }

            // Crear cliente SOAP
            $soapClient = new \SoapClient($this->soapCancelUrl, [
                'trace' => true,
                'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'stream_context' => stream_context_create([
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ]
                ])
            ]);

            // Preparar array de UUIDs para cancelar
            $uuidsArray = [[
                'UUID' => $uuid,
                'Motivo' => $motivoCancelacion,
                'FolioSustitucion' => $uuidSustitucion ?? ''
            ]];

            // Llamar al método cancel del webservice
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'taxpayer_id' => $rfcEmisor,
                'uuids' => $uuidsArray,
                'store_pending' => true
            ];

            $response = $soapClient->cancel($params);

            // Debug: Log de la respuesta completa para diagnóstico
            $responseDebug = json_encode($response);
            error_log("Respuesta SOAP Finkok: " . $responseDebug);

            // Procesar respuesta
            if (isset($response->CancelResult)) {
                $result = $response->CancelResult;
                
                // Obtener información de la respuesta
                $folios = $result->Folios ?? null;
                $statusCode = null;
                $statusUuid = null;
                $estatusCancelacion = null;

                if ($folios && isset($folios->Folio)) {
                    $folio = is_array($folios->Folio) ? $folios->Folio[0] : $folios->Folio;
                    $statusCode = $folio->EstatusUUID ?? null;
                    $statusUuid = $folio->UUID ?? null;
                    $estatusCancelacion = $folio->EstatusCancelacion ?? null;
                }

                // Códigos exitosos: 201 (exitosa), 202 (previamente cancelado)
                $esExitoso = in_array($statusCode, ['201', '202']);

                return [
                    'success' => $esExitoso,
                    'status_code' => $statusCode,
                    'status_uuid' => $statusUuid,
                    'estatus_cancelacion' => $estatusCancelacion,
                    'acuse' => isset($result->Acuse) ? $result->Acuse : null,
                    'fecha' => isset($result->Fecha) ? $result->Fecha : null,
                    'message' => $this->obtenerMensajeCancelacion($statusCode),
                    'raw_response' => $responseDebug  // Para debugging
                ];
            }

            // Si no hay CancelResult, retornar la respuesta completa para debugging
            return [
                'success' => false,
                'message' => 'Respuesta inesperada del servicio de Finkok',
                'raw_response' => $responseDebug,
                'response_structure' => print_r($response, true)
            ];

        } catch (\SoapFault $e) {
            return [
                'success' => false,
                'message' => "Error SOAP al cancelar: " . $e->getMessage(),
                'fault_code' => $e->faultcode ?? null,
                'fault_string' => $e->faultstring ?? null
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Error al cancelar factura: " . $e->getMessage()
            ];
        }
    }

    /**
     * Cancelar usando el método sign_cancel (con certificados en base64)
     */
    public function cancelarConCertificados($rfcEmisor, $uuid, $motivoCancelacion, $uuidSustitucion,
                                           $cerBase64, $keyBase64) {
        try {
            $soapClient = new \SoapClient($this->soapCancelUrl, [
                'trace' => true,
                'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'stream_context' => stream_context_create([
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ]
                ])
            ]);

            $uuidsArray = [[
                'UUID' => $uuid,
                'Motivo' => $motivoCancelacion,
                'FolioSustitucion' => $uuidSustitucion ?? ''
            ]];

            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'taxpayer_id' => $rfcEmisor,
                'uuids' => $uuidsArray,
                'cer' => $cerBase64,
                'key' => $keyBase64,
                'store_pending' => true
            ];

            $response = $soapClient->sign_cancel($params);

            if (isset($response->sign_cancelResult)) {
                $result = $response->sign_cancelResult;
                $folios = $result->Folios ?? null;
                $statusCode = null;

                if ($folios && isset($folios->Folio)) {
                    $folio = is_array($folios->Folio) ? $folios->Folio[0] : $folios->Folio;
                    $statusCode = $folio->EstatusUUID ?? null;
                }

                $esExitoso = in_array($statusCode, ['201', '202']);

                return [
                    'success' => $esExitoso,
                    'status_code' => $statusCode,
                    'acuse' => $result->Acuse ?? null,
                    'message' => $this->obtenerMensajeCancelacion($statusCode)
                ];
            }

            return [
                'success' => false,
                'message' => 'Respuesta inesperada del servicio'
            ];

        } catch (\SoapFault $e) {
            return [
                'success' => false,
                'message' => "Error SOAP: " . $e->getMessage(),
                'fault_code' => $e->faultcode ?? null
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Error: " . $e->getMessage()
            ];
        }
    }

    /**
     * Consultar el estatus de cancelación de un UUID
     */
    public function consultarEstatusCancelacion($rfcEmisor, $uuid) {
        try {
            $soapClient = new \SoapClient($this->soapCancelUrl, [
                'trace' => true,
                'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE
            ]);

            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'taxpayer_id' => $rfcEmisor,
                'uuid' => $uuid
            ];

            $response = $soapClient->get_sat_status($params);

            if (isset($response->get_sat_statusResult)) {
                $result = $response->get_sat_statusResult;

                return [
                    'success' => true,
                    'sat_code' => $result->sat ?? null,
                    'estado' => $result->Estado ?? null,
                    'es_cancelable' => $result->EsCancelable ?? null,
                    'estatus_cancelacion' => $result->EstatusCancelacion ?? null,
                    'message' => 'Consulta exitosa'
                ];
            }

            return [
                'success' => false,
                'message' => 'No se pudo consultar el estatus'
            ];

        } catch (\SoapFault $e) {
            return [
                'success' => false,
                'message' => "Error SOAP: " . $e->getMessage()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Error: " . $e->getMessage()
            ];
        }
    }

    /**
     * Obtener mensaje descriptivo según el código de cancelación
     */
    private function obtenerMensajeCancelacion($codigo) {
        $mensajes = [
            '201' => 'Petición de cancelación realizada exitosamente',
            '202' => 'UUID previamente cancelado',
            '203' => 'No encontrado o no corresponde al emisor',
            '205' => 'UUID no existe',
            '207' => 'Motivo de cancelación inválido',
            '208' => 'La fecha de solicitud de cancelación es mayor a la fecha de declaración',
            '300' => 'Usuario no válido',
            '301' => 'XML mal formado',
            '302' => 'Sello mal formado',
            '304' => 'Certificado revocado o caduco',
            '305' => 'Certificado inválido',
            '309' => 'Patrón de folio inválido',
            '310' => 'Se encuentra usando certificados tipo FIEL y no de CSD',
            '311' => 'Clave de motivo de cancelación no válida',
            '312' => 'UUID no relacionado de acuerdo a la clave de motivo',
            '708' => 'No se pudo conectar al SAT',
            '711' => 'Error con el certificado al cancelar',
            '798' => 'Ya existe una solicitud previa, esperar 72 horas',
            '799' => 'Excedieron el límite de las 5 peticiones',
            'no_cancelable' => 'El UUID contiene CFDI relacionados'
        ];

        return $mensajes[$codigo] ?? "Código desconocido: {$codigo}";
    }
}
?>