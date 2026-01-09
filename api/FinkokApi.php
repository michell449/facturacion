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
     * Estructura según documentación oficial de Finkok
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

            $uuids = [
                "UUID" => $uuid,
                "Motivo" => $motivoCancelacion,
                "FolioSustitucion" => $uuidSustitucion ? strtoupper(trim($uuidSustitucion)) : ""
            ];

            $uuid_ar = ['UUID' => $uuids];

            // 3. Validar rutas de certificados
            if (!$cerPath || !file_exists($cerPath)) {
                throw new \Exception("Ruta de certificado inválida o archivo no existe");
            }

            if (!$keyPath || !file_exists($keyPath)) {
                throw new \Exception("Ruta de llave privada inválida o archivo no existe");
            }

            if (!$keyPassword) {
                throw new \Exception("Contraseña de llave privada no proporcionada");
            }

            // 4. Procesar certificados EXACTAMENTE como generar-xml.php
            require_once __DIR__ . '/../core/sello-utils.php';

            // 4.1 Convertir KEY a PEM (mismo método que generar-xml.php)
            $keyPem = SelloUtils::convertirKeyAPEM($keyPath, $keyPassword);
            if (!$keyPem) {
                $keyPem = file_get_contents($keyPath);
            }

            if (!$keyPem) {
                throw new \Exception("No se pudo procesar la llave privada");
            }

            // 4.2 Cargar certificado usando Certificado (mismo que generar-xml.php)
            $certificado = new \CfdiUtils\Certificado\Certificado($cerPath);
            $cerPem = $certificado->getPemContents();

            if (!$cerPem) {
                throw new \Exception("No se pudo procesar el certificado");
            }

            error_log("Certificados procesados - CER: " . strlen($cerPem) . " bytes, KEY: " . strlen($keyPem) . " bytes");

            // 5. Construir parámetros según documentación Finkok
            $params = [
                'UUIDS' => $uuid_ar,
                'username' => $this->username,
                'password' => $this->password,
                'taxpayer_id' => strtoupper(trim($rfcEmisor)),
                'cer' => $cerPem,
                'key' => $keyPem,
                'store_pending' => 0
            ];

            error_log("Parámetros preparados para Finkok");

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

            // Log de respuesta
            error_log("==== FINKOK CANCELACIÓN - RESPONSE ====");
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
     * Obtener mensaje descriptivo según el código de cancelación
     */
    private function obtenerMensajeCancelacion($codigo)
    {
        $mensajes = [
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
            '708' => 'No se pudo conectar al SAT',
            '711' => 'Error con el certificado al cancelar'
        ];

        return $mensajes[$codigo] ?? "Código desconocido: {$codigo}";
    }
}
