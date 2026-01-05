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

class FinkokApi
{
    private $finkok;
    private $username;
    private $password;
    private $isProduction;
    private $soapCancelUrl;

    public function __construct($username, $password, $isProduction = false)
    {
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

    public function timbrar($xmlContent)
    {
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
    public function cancelarFactura(
        $rfcEmisor,
        $uuid,
        $motivoCancelacion = '02',
        $uuidSustitucion = null,
        $cerPath = null,
        $keyPath = null,
        $keyPassword = null
    ) {
        try {
            // 1. Validaciones básicas
            if (!in_array($motivoCancelacion, ['01', '02', '03', '04'])) {
                return ['success' => false, 'message' => 'Motivo inválido'];
            }

            // Validar formato del UUID
            $uuid = strtoupper(trim($uuid));
            if (!preg_match('/^[A-F0-9]{8}-[A-F0-9]{4}-[A-F0-9]{4}-[A-F0-9]{4}-[A-F0-9]{12}$/i', $uuid)) {
                return [
                    'success' => false, 
                    'message' => 'El UUID no tiene el formato válido (xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx)'
                ];
            }

            // 2. Preparación del cliente SOAP
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

            // --- CORRECCIÓN CRÍTICA: Estructura correcta para Finkok ---
            // Después de analizar el WSDL, Finkok espera una estructura específica
            // El nodo 'uuids' debe contener objetos con clave 'UUID' (no 'uuid')
            
            // Crear objeto UUID usando stdClass para asegurar la estructura correcta
            $uuidObj = new \stdClass();
            $uuidObj->UUID = $uuid;
            $uuidObj->Motivo = $motivoCancelacion;
            $uuidObj->FolioSustitucion = $uuidSustitucion ? strtoupper(trim($uuidSustitucion)) : '';
            
            // CRÍTICO: El parámetro 'uuids' debe ser un array asociativo con la clave 'UUID' (singular)
            // NO usar 'uuids' => [$uuidObj] porque genera <item>
            // Usar 'uuids' => ['UUID' => $uuidObj] para generar <UUID>
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'taxpayer_id' => strtoupper(trim($rfcEmisor)),
                'uuids' => ['UUID' => $uuidObj],  // CLAVE CRÍTICA: usar 'UUID' como clave
                'store_pending' => true
            ];

            // Ejecutar la petición
            try {
                $response = $soapClient->cancel($params);
            } catch (\SoapFault $soapFault) {
                // Log del error
                error_log("SOAP Fault en cancelación: " . $soapFault->getMessage());
                error_log("XML Request: " . $soapClient->__getLastRequest());
                error_log("XML Response: " . $soapClient->__getLastResponse());
                
                throw $soapFault;
            }

            // Log para debugging
            error_log("==== FINKOK CANCELACIÓN ====");
            error_log("XML Request enviado: " . $soapClient->__getLastRequest());
            error_log("XML Response recibido: " . $soapClient->__getLastResponse());

            // Procesar respuesta (Manejo de variaciones de Finkok)
            $result = $response->cancelResult ?? $response->CancelResult ?? null;

            if (!$result) {
                return [
                    'success' => false, 
                    'message' => 'Sin respuesta del servicio SOAP',
                    'raw_response' => $soapClient->__getLastResponse()
                ];
            }

            // Si Finkok devuelve el error directo en la raíz del objeto
            if (isset($result->CodEstatus) && !isset($result->Folios)) {
                return [
                    'success' => false,
                    'message' => "Error devuelto por Finkok: " . $result->CodEstatus,
                    'raw_response' => json_encode($result),
                    'debug_request' => $soapClient->__getLastRequest()
                ];
            }

            // Extracción de datos
            $folios = $result->Folios ?? null;
            $statusCode = null;
            $statusUuid = null;
            $estatusCancelacion = null;

            if ($folios && isset($folios->Folio)) {
                // Finkok puede devolver un array o un objeto único dependiendo si envías 1 o varios
                $folio = is_array($folios->Folio) ? $folios->Folio[0] : $folios->Folio;

                $statusCode = $folio->EstatusUUID ?? null;
                $statusUuid = $folio->UUID ?? null;
                $estatusCancelacion = $folio->EstatusCancelacion ?? null;
            }

            // 201 = Petición recibida, 202 = Ya cancelado previamente
            $esExitoso = in_array($statusCode, ['201', '202']);

            return [
                'success' => $esExitoso,
                'status_code' => $statusCode,
                'uuid' => $statusUuid,
                'estatus_cancelacion' => $estatusCancelacion,
                'acuse' => isset($result->Acuse) ? $result->Acuse : null,
                'message' => $this->obtenerMensajeCancelacion($statusCode),
            ];
        } catch (\SoapFault $e) {
            error_log("Finkok SOAP Fault: " . $e->getMessage());
            return [
                'success' => false,
                'message' => "Error de conexión SOAP: " . $e->getMessage(),
                'fault_code' => $e->faultcode ?? null
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Error interno: " . $e->getMessage()
            ];
        }
    }

    /**
     * Cancelar usando el método sign_cancel (con certificados en base64)
     */
    public function cancelarConCertificados(
        $rfcEmisor,
        $uuid,
        $motivoCancelacion,
        $uuidSustitucion,
        $cerBase64,
        $keyBase64
    ) {
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
     * Consultar el estatus de cancelación de un UUID ante el SAT
     * Método: get_sat_status
     * 
     * @param string $rfcEmisor RFC del emisor
     * @param string $uuid UUID del comprobante
     * @param string $rfcReceptor RFC del receptor (opcional)
     * @param string $total Total del comprobante con decimales (opcional)
     * @return array Resultado de la consulta
     */
    public function consultarEstatusCancelacion($rfcEmisor, $uuid, $rfcReceptor = null, $total = null)
    {
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

            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'taxpayer_id' => $rfcEmisor,
                'uuid' => strtoupper(trim($uuid))
            ];

            // Parámetros opcionales para validación adicional
            if ($rfcReceptor) {
                $params['rtaxpayer_id'] = $rfcReceptor;
            }
            if ($total) {
                $params['total'] = number_format($total, 6, '.', '');
            }

            $response = $soapClient->get_sat_status($params);

            if (isset($response->get_sat_statusResult)) {
                $result = $response->get_sat_statusResult;

                // Procesar código SAT
                $satCode = $result->sat ?? null;
                $codigoEstado = null;
                $estadoDescripcion = null;

                if ($satCode) {
                    $partes = explode(' - ', $satCode, 2);
                    $codigoEstado = $partes[0] ?? null;
                    $estadoDescripcion = $partes[1] ?? $satCode;
                }

                return [
                    'success' => true,
                    'sat_code' => $satCode,
                    'codigo_estado' => $codigoEstado,
                    'estado_descripcion' => $estadoDescripcion,
                    'estado' => $result->Estado ?? null,
                    'es_cancelable' => $result->EsCancelable ?? null,
                    'estatus_cancelacion' => $result->EstatusCancelacion ?? null,
                    'validacion_efos' => $result->ValidacionEFOS ?? null,
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
     * Obtener solicitudes de cancelación pendientes
     * Método: get_pending
     * 
     * @param string $rfcReceptor RFC del receptor que consulta
     * @return array Lista de solicitudes pendientes
     */
    public function obtenerCancelacionesPendientes($rfcReceptor)
    {
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

            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'taxpayer_id' => $rfcReceptor
            ];

            $response = $soapClient->get_pending($params);

            if (isset($response->get_pendingResult)) {
                $result = $response->get_pendingResult;

                // Procesar UUIDs pendientes
                $uuidsPendientes = [];
                if (isset($result->uuids)) {
                    $uuids = $result->uuids;
                    
                    // Puede ser un array o un objeto único
                    if (is_array($uuids)) {
                        $uuidsPendientes = $uuids;
                    } else if (is_object($uuids)) {
                        $uuidsPendientes = [$uuids];
                    }
                }

                return [
                    'success' => true,
                    'uuids_pendientes' => $uuidsPendientes,
                    'cantidad' => count($uuidsPendientes),
                    'message' => 'Consulta exitosa'
                ];
            }

            return [
                'success' => false,
                'message' => 'No se pudieron obtener las cancelaciones pendientes'
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
     * Aceptar o rechazar una solicitud de cancelación
     * Método: accept_reject
     * 
     * @param string $rfcReceptor RFC del receptor
     * @param array $uuidsAcciones Array de ['uuid' => 'UUID', 'action' => 'Aceptacion'|'Rechazo']
     * @return array Resultado de la operación
     */
    public function aceptarRechazarCancelacion($rfcReceptor, $uuidsAcciones)
    {
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

            // Construir estructura de UUIDs
            $uuidsArray = [];
            foreach ($uuidsAcciones as $item) {
                $uuidObj = new \stdClass();
                $uuidObj->UUID = strtoupper(trim($item['uuid']));
                $uuidObj->action = $item['action']; // 'Aceptacion' o 'Rechazo'
                $uuidsArray[] = $uuidObj;
            }

            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'taxpayer_id' => $rfcReceptor,
                'uuids' => ['uuid' => $uuidsArray]
            ];

            $response = $soapClient->accept_reject($params);

            if (isset($response->accept_rejectResult)) {
                $result = $response->accept_rejectResult;

                return [
                    'success' => true,
                    'folios' => $result->Folios ?? null,
                    'acuse' => $result->Acuse ?? null,
                    'message' => 'Operación realizada exitosamente'
                ];
            }

            return [
                'success' => false,
                'message' => 'No se pudo procesar la respuesta'
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
     * Obtener el acuse de cancelación
     * Método: get_receipt
     * 
     * @param string $rfcEmisor RFC del emisor
     * @param string $uuid UUID del comprobante
     * @param string $tipo Tipo de acuse: 'C' para cancelación, 'R' para recuperación
     * @return array Resultado con el acuse
     */
    public function obtenerAcuse($rfcEmisor, $uuid, $tipo = 'C')
    {
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

            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'taxpayer_id' => $rfcEmisor,
                'uuid' => strtoupper(trim($uuid)),
                'type' => $tipo
            ];

            $response = $soapClient->get_receipt($params);

            if (isset($response->get_receiptResult)) {
                $result = $response->get_receiptResult;

                return [
                    'success' => true,
                    'acuse' => $result->Acuse ?? null,
                    'fecha' => $result->Fecha ?? null,
                    'rfc_emisor' => $result->RfcEmisor ?? null,
                    'message' => 'Acuse obtenido exitosamente'
                ];
            }

            return [
                'success' => false,
                'message' => 'No se pudo obtener el acuse'
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
     * Consultar CFDIs relacionados
     * Método: get_related
     * 
     * @param string $rfcEmisor RFC del emisor
     * @param string $uuid UUID del comprobante
     * @return array Resultado con los CFDIs relacionados
     */
    public function consultarRelacionados($rfcEmisor, $uuid)
    {
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

            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'taxpayer_id' => $rfcEmisor,
                'uuid' => strtoupper(trim($uuid))
            ];

            $response = $soapClient->get_related($params);

            if (isset($response->get_relatedResult)) {
                $result = $response->get_relatedResult;

                return [
                    'success' => true,
                    'uuid_padres' => $result->UuidsPadres ?? [],
                    'uuid_hijos' => $result->UuidsHijos ?? [],
                    'message' => 'Consulta exitosa'
                ];
            }

            return [
                'success' => false,
                'message' => 'No se pudieron consultar los relacionados'
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
     * Encriptar llave privada usando OpenSSL DES3
     * Equivalente a: openssl rsa -in RFC.key.pem -des3 -out RFC.enc -passout pass:"contraseña"
     * 
     * @param string $keyPemContent Contenido de la llave en formato PEM
     * @param string $password Contraseña de Finkok para encriptar
     * @param string $keyPassword Contraseña original de la llave (si tiene)
     * @return array Resultado con la llave encriptada en base64
     */
    public function encriptarLlaveConDES3($keyPemContent, $password, $keyPassword = null)
    {
        try {
            // 1. Leer la llave privada
            if ($keyPassword) {
                $privateKey = openssl_pkey_get_private($keyPemContent, $keyPassword);
            } else {
                $privateKey = openssl_pkey_get_private($keyPemContent);
            }

            if ($privateKey === false) {
                return [
                    'success' => false,
                    'message' => 'No se pudo leer la llave privada. Verifica la contraseña.'
                ];
            }

            // 2. Exportar la llave en formato PEM sin encriptar
            $keyDetails = openssl_pkey_get_details($privateKey);
            openssl_pkey_export($privateKey, $pemUnencrypted);
            openssl_free_key($privateKey);

            // 3. Encriptar con DES3 usando la contraseña de Finkok
            $encrypted = '';
            $method = 'des-ede3-cbc'; // DES3
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
            
            $encrypted = openssl_encrypt(
                $pemUnencrypted,
                $method,
                $password,
                OPENSSL_RAW_DATA,
                $iv
            );

            if ($encrypted === false) {
                return [
                    'success' => false,
                    'message' => 'Error al encriptar la llave con DES3'
                ];
            }

            // 4. Combinar IV + datos encriptados y codificar en base64
            $keyEncryptedBase64 = base64_encode($iv . $encrypted);

            return [
                'success' => true,
                'key_encrypted' => $keyEncryptedBase64,
                'message' => 'Llave encriptada exitosamente'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al encriptar llave: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Preparar certificado y llave para envío a Finkok
     * Convierte archivos a base64 en el formato requerido
     * 
     * @param string $cerPath Ruta del archivo .cer
     * @param string $keyPath Ruta del archivo .key
     * @return array Certificado y llave en base64
     */
    public function prepararCertificadosParaCancelacion($cerPath, $keyPath)
    {
        try {
            // Leer certificado
            if (!file_exists($cerPath)) {
                return [
                    'success' => false,
                    'message' => 'Archivo de certificado no encontrado'
                ];
            }

            if (!file_exists($keyPath)) {
                return [
                    'success' => false,
                    'message' => 'Archivo de llave no encontrado'
                ];
            }

            $cerContent = file_get_contents($cerPath);
            $keyContent = file_get_contents($keyPath);

            // Convertir certificado a base64 (debe estar en formato PEM)
            $cerBase64 = base64_encode($cerContent);

            // Convertir llave a base64 (debe estar en formato PEM)
            $keyBase64 = base64_encode($keyContent);

            return [
                'success' => true,
                'cer_base64' => $cerBase64,
                'key_base64' => $keyBase64,
                'message' => 'Certificados preparados exitosamente'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al preparar certificados: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtener mensaje descriptivo según el código de cancelación
     */
    private function obtenerMensajeCancelacion($codigo)
    {
        $mensajes = [
            // Validación de la cancelación del CFDI
            '201' => 'Petición de cancelación realizada exitosamente',
            '202' => 'UUID previamente cancelado',
            '203' => 'No encontrado o no corresponde al emisor',
            '205' => 'UUID no existe',
            '207' => 'Motivo de cancelación inválido',
            '208' => 'La fecha de solicitud de cancelación es mayor a la fecha de declaración',
            
            // Validación en las peticiones
            '300' => 'Usuario no válido',
            '301' => 'XML mal formado',
            '302' => 'Sello mal formado',
            '304' => 'Certificado revocado o caduco',
            '305' => 'Certificado inválido',
            '309' => 'Patrón de folio inválido',
            '310' => 'Se encuentra usando certificados tipo FIEL y no de CSD',
            '311' => 'Clave de motivo de cancelación no válida',
            '312' => 'UUID no relacionado de acuerdo a la clave de motivo',
            '314' => 'Relación no válida',
            
            // Validación Get_Sat_Status
            'N - 601' => 'La expresión impresa proporcionada no es válida',
            'N - 602' => 'Comprobante no encontrado',
            'S' => 'Comprobante obtenido satisfactoriamente',
            
            // Validación Accept_Reject
            '1000' => 'Se recibió la respuesta de la petición de forma exitosa',
            '1001' => 'No existen peticiones de cancelación en espera de respuesta para el uuid',
            '1002' => 'Ya se recibió una respuesta para la petición de cancelación del uuid',
            '1003' => 'Sello no corresponde al RFC del Receptor',
            '1004' => 'Existen más de una petición de cancelación para el mismo uuid',
            '1005' => 'El uuid es nulo o no posee el formato correcto',
            '1006' => 'Se rebasó el número máximo de solicitudes permitidas',
            
            // Validación Finkok
            '708' => 'No se pudo conectar al SAT',
            '711' => 'Error con el certificado al cancelar',
            '798' => 'Ya existe una solicitud previa, esperar 72 horas',
            '799' => 'Excedieron el límite de las 5 peticiones',
            
            // Estados especiales
            'no_cancelable' => 'El UUID contiene CFDI relacionados',
            'Invalid Passphrase' => 'Contraseña de encriptación incorrecta',
            'Already en BufferCancellation' => 'Solicitud almacenada en buffer, se reenviará al SAT',
            'Already Cancelleded' => 'Se han detectado múltiples peticiones de cancelación',
            'Incorrect padding' => 'Error al descifrar la llave, contactar soporte'
        ];

        return $mensajes[$codigo] ?? "Código desconocido: {$codigo}";
    }

    /**
     * Validar formato de UUID
     * 
     * @param string $uuid UUID a validar
     * @return bool True si el formato es válido
     */
    public function validarFormatoUUID($uuid)
    {
        // Patrón UUID: 8-4-4-4-12 caracteres hexadecimales
        $patron = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';
        return preg_match($patron, $uuid) === 1;
    }

    /**
     * Cancelar factura usando __doRequest personalizado (método alternativo)
     * Este método construye manualmente el XML para tener control total sobre la estructura
     */
    public function cancelarFacturaXMLManual(
        $rfcEmisor,
        $uuid,
        $motivoCancelacion = '02',
        $uuidSustitucion = null
    ) {
        try {
            // Validaciones
            $uuid = strtoupper(trim($uuid));
            if (!$this->validarFormatoUUID($uuid)) {
                return [
                    'success' => false,
                    'message' => 'UUID con formato inválido'
                ];
            }

            // Construir XML manualmente según la estructura exacta que espera Finkok
            $xmlRequest = '<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://facturacion.finkok.com/cancel">
    <SOAP-ENV:Body>
        <ns1:cancel>
            <ns1:username>' . htmlspecialchars($this->username) . '</ns1:username>
            <ns1:password>' . htmlspecialchars($this->password) . '</ns1:password>
            <ns1:taxpayer_id>' . strtoupper(trim($rfcEmisor)) . '</ns1:taxpayer_id>
            <ns1:uuids>
                <ns1:UUID>
                    <ns1:UUID>' . $uuid . '</ns1:UUID>
                    <ns1:Motivo>' . $motivoCancelacion . '</ns1:Motivo>
                    <ns1:FolioSustitucion>' . ($uuidSustitucion ? strtoupper(trim($uuidSustitucion)) : '') . '</ns1:FolioSustitucion>
                </ns1:UUID>
            </ns1:uuids>
            <ns1:store_pending>true</ns1:store_pending>
        </ns1:cancel>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>';

            // Crear contexto HTTP
            $headers = [
                'Content-Type: text/xml; charset=utf-8',
                'SOAPAction: "cancel"',
                'Content-Length: ' . strlen($xmlRequest)
            ];

            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => implode("\r\n", $headers),
                    'content' => $xmlRequest,
                    'timeout' => 60
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ]
            ]);

            // Enviar petición
            $response = file_get_contents($this->soapCancelUrl, false, $context);
            
            if ($response === false) {
                return [
                    'success' => false,
                    'message' => 'Error al conectar con el servicio de Finkok'
                ];
            }

            // Parsear respuesta XML
            $xml = simplexml_load_string($response);
            if (!$xml) {
                return [
                    'success' => false,
                    'message' => 'Error al parsear respuesta XML',
                    'raw_response' => $response
                ];
            }
            
            // Registrar namespaces correctos según la respuesta de Finkok
            $xml->registerXPathNamespace('senv', 'http://schemas.xmlsoap.org/soap/envelope/');
            $xml->registerXPathNamespace('tns', 'http://facturacion.finkok.com/cancel');
            $xml->registerXPathNamespace('s0', 'apps.services.soap.core.views');

            // Extraer resultado usando los namespaces correctos
            $resultNodes = $xml->xpath('//tns:cancelResult');
            
            if (empty($resultNodes)) {
                return [
                    'success' => false,
                    'message' => 'Respuesta inesperada del servicio',
                    'raw_response' => $response
                ];
            }

            $result = $resultNodes[0];
            
            // Verificar si hay error directo (CodEstatus sin Folios)
            $codEstatus = $result->children('apps.services.soap.core.views')->CodEstatus;
            if ($codEstatus && !isset($result->children('apps.services.soap.core.views')->Folios)) {
                return [
                    'success' => false,
                    'message' => "Error: " . (string)$codEstatus,
                    'raw_response' => $response
                ];
            }

            // Procesar folios si existen
            $statusCode = null;
            $foliosNode = $result->children('apps.services.soap.core.views')->Folios;
            if ($foliosNode) {
                $folioNode = $foliosNode->children('apps.services.soap.core.views')->Folio;
                if ($folioNode) {
                    $statusCode = (string)$folioNode->EstatusUUID;
                }
            }

            // Check if we got an unexpected response
            if (!$statusCode) {
                return [
                    'success' => false,
                    'message' => 'Respuesta inesperada del servicio',
                    'raw_response' => $response
                ];
            }

            $result = $resultNodes[0];

            // Verificar si hay error directo
            if (isset($result->CodEstatus) && !isset($result->Folios)) {
                return [
                    'success' => false,
                    'message' => "Error: " . (string)$result->CodEstatus,
                    'raw_response' => $response
                ];
            }

            // Procesar folios
            $statusCode = null;
            if (isset($result->Folios->Folio)) {
                $statusCode = (string)$result->Folios->Folio->EstatusUUID;
            }

            $esExitoso = in_array($statusCode, ['201', '202']);

            return [
                'success' => $esExitoso,
                'status_code' => $statusCode,
                'acuse' => isset($result->Acuse) ? (string)$result->Acuse : null,
                'message' => $this->obtenerMensajeCancelacion($statusCode),
                'raw_response' => $response
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Validar motivo de cancelación
     * 
     * @param string $motivo Código del motivo (01, 02, 03, 04)
     * @return array Resultado de la validación
     */
    public function validarMotivoCancelacion($motivo)
    {
        $motivos = [
            '01' => 'Comprobante emitido con errores con relación (requiere UUID de sustitución)',
            '02' => 'Comprobante emitido con errores sin relación',
            '03' => 'No se llevó a cabo la operación',
            '04' => 'Operación nominativa relacionada en una factura global'
        ];

        if (!isset($motivos[$motivo])) {
            return [
                'valido' => false,
                'mensaje' => 'Motivo inválido. Debe ser 01, 02, 03 o 04'
            ];
        }

        return [
            'valido' => true,
            'motivo' => $motivo,
            'descripcion' => $motivos[$motivo],
            'requiere_sustitucion' => ($motivo === '01')
        ];
    }
}
