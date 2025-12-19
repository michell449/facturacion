<?php
class DigiboxApi {
    private $user;
    private $pass;
    private $token;

    // Usar las URLs definidas en config.php para mantener un solo punto de verdad
    const URL_AUTH = DIGIBOX_URL_AUTH;
    const URL_TIMBRADO = DIGIBOX_URL_STAMP;

    public function __construct() {
        // Asegúrate de tener estas constantes en tu config.php
        $this->user = DIGIBOX_USER;
        $this->pass = DIGIBOX_PASS;
        $this->token = null;
    }

    /**
     * 1. Autenticación
     * Header: usuario, password
     * Response: Token (string)
     */
    public function autenticar() {
        $ch = curl_init(self::URL_AUTH);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        
        // El body puede ir vacío, los datos van en Headers según tu especificación
        curl_setopt($ch, CURLOPT_POSTFIELDS, ''); 

        $headers = [
            'usuario: ' . $this->user,
            'password: ' . $this->pass,
            'Content-Type: application/json'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200) {
            // Limpiamos comillas dobles que a veces envían los servicios .NET/WCF
            $this->token = trim($response, '"');
            return true;
        }
        
        // Si falla, intentamos leer el JSON de error
        throw new Exception("Error Auth ($httpCode): " . $response);
    }

    /**
     * 2. Timbrado
     * Header: Token
     * Body (raw): XML
     * Response 200: XML String (Raw)
     */
    public function timbrar($xmlString) {
        if (!$this->token) {
            $this->autenticar();
        }

        // El endpoint de json espera un body JSON con la propiedad "xml"
        $payload = json_encode(['xml' => $xmlString]);
        if ($payload === false) {
            throw new Exception('No se pudo codificar el XML a JSON (json_encode falló).');
        }

        $ch = curl_init(self::URL_TIMBRADO);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $headers = [
            'Token: ' . $this->token,
            'Content-Type: application/json',
            'Accept: application/xml, application/json',
            'Content-Length: ' . strlen($payload)
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            throw new Exception('Error Timbrado (curl): ' . $curlErr);
        }

        if ($httpCode == 200) {
            // Puede venir como XML directo o como string JSON escapada
            if (substr($response, 0, 1) === '"') {
                return json_decode($response);
            }
            return $response; 
        }

        // Error 4xx/5xx: intentar leer JSON de error del PAC
        $errorObj = json_decode($response);
        $msg = $response;
        if (json_last_error() === JSON_ERROR_NONE) {
            $msg = isset($errorObj->ExceptionMessage) ? $errorObj->ExceptionMessage : 
                   (isset($errorObj->Message) ? $errorObj->Message : $response);
        }

        throw new Exception("Error Timbrado ($httpCode): " . $msg);
    }
}
?>