<?php

/**
 * Utilidades para manejo seguro del sello digital
 */

class SelloUtils
{

    /**
     * Devuelve la ruta explícita de OpenSSL en XAMPP/Windows
     */
    private static function opensslBin(): string
    {
        return 'C:\\xampp\\apache\\bin\\openssl.exe';
    }

    /**
     * Cifra una clave privada usando AES-256-CBC
     * 
     * @param string $clavePrivada La clave a cifrar
     * @param int $idEmpresa ID de la empresa (usado como salt)
     * @return string Clave cifrada en base64
     */
    public static function cifrarClave($clavePrivada, $idEmpresa)
    {
        $cipher = 'AES-256-CBC';
        $key = hash('sha256', 'clave_secreta_sello_digital_' . $idEmpresa, true);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
        $claveEncriptada = openssl_encrypt($clavePrivada, $cipher, $key, 0, $iv);
        return base64_encode($iv . $claveEncriptada);
    }

    /**
     * Descifra una clave privada cifrada
     * 
     * @param string $claveCifrada Clave cifrada en base64
     * @param int $idEmpresa ID de la empresa (usado como salt)
     * @return string|false Clave descifrada o false en caso de error
     */
    public static function descifrarClave($claveCifrada, $idEmpresa)
    {
        try {
            $cipher = 'AES-256-CBC';
            $key = hash('sha256', 'clave_secreta_sello_digital_' . $idEmpresa, true);

            $data = base64_decode($claveCifrada);
            $ivLength = openssl_cipher_iv_length($cipher);
            $iv = substr($data, 0, $ivLength);
            $encrypted = substr($data, $ivLength);

            return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
        } catch (Exception $e) {
            error_log("Error al descifrar clave: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Convierte un archivo .key DER/PKCS#12 encriptado a PEM para usar con addSello()
     * Los archivos del SAT pueden ser PKCS#8, PKCS#12 o simple DER encriptados
     * 
     * @param string $rutaKey Ruta completa del archivo .key
     * @param string $password Contraseña descifrada
     * @return string|false Contenido PEM o false en error
     */
    public static function convertirKeyAPEM(string $rutaKey, string $password): string
    {
        $password = trim($password);
        error_log('convertirKeyAPEM: PASSWORD LEN: ' . strlen($password));

        $raw = file_get_contents($rutaKey);
        if ($raw === false) {
            throw new Exception('No se pudo leer la KEY');
        }

        $isPem = (strpos($raw, 'BEGIN') !== false);

        if ($isPem && stripos($raw, 'ENCRYPTED') === false) {
            return $raw;
        }

        $openssl = self::opensslBin();
        $tmpPem = tempnam(sys_get_temp_dir(), 'key_') . '.pem';
        $passFile = tempnam(sys_get_temp_dir(), 'pass_');

        if ($passFile === false || file_put_contents($passFile, $password) === false) {
            if ($passFile !== false) {
                @unlink($passFile);
            }
            @unlink($tmpPem);
            throw new Exception('No se pudo preparar la contraseña temporal');
        }

        $conversionExitosa = false;
        $errores = [];
        $inform = $isPem ? 'PEM' : 'DER';
        $passArg = escapeshellarg('file:' . $passFile);
        $inputArg = escapeshellarg($rutaKey);
        $tmpPemArg = escapeshellarg($tmpPem);

        $comandos = [
            sprintf(
                '"%s" pkcs8 -inform %s -in %s -passin %s -out %s 2>&1',
                $openssl,
                $inform,
                $inputArg,
                $passArg,
                $tmpPemArg
            ),
            sprintf(
                '"%s" rsa -inform %s -in %s -passin %s -out %s 2>&1',
                $openssl,
                $inform,
                $inputArg,
                $passArg,
                $tmpPemArg
            )
        ];

        try {
            foreach ($comandos as $cmd) {
                $out = [];
                $status = 0;
                exec($cmd, $out, $status);
                if ($status === 0 && file_exists($tmpPem)) {
                    $conversionExitosa = true;
                    break;
                }
                if (!empty($out)) {
                    $errores[] = implode("\n", $out);
                }
            }

            if (!$conversionExitosa || !file_exists($tmpPem)) {
                if (!empty($errores)) {
                    error_log('OPENSSL CONVERSION ERRORS:' . PHP_EOL . implode(PHP_EOL . '---' . PHP_EOL, $errores));
                }
                throw new Exception('OpenSSL no pudo convertir la KEY (PKCS8 ni RSA)');
            }

            $pem = file_get_contents($tmpPem);
            if ($pem === false) {
                throw new Exception('No se pudo leer el PEM generado');
            }

            if (strpos($pem, 'ENCRYPTED') !== false) {
                throw new Exception('La llave privada sigue estando cifrada, revise la contraseña');
            }

            if (
                stripos($pem, 'BEGIN PRIVATE KEY') === false &&
                stripos($pem, 'BEGIN RSA PRIVATE KEY') === false
            ) {
                throw new Exception('El PEM generado no es una llave privada válida');
            }

            return $pem;
        } finally {
            @unlink($passFile);
            @unlink($tmpPem);
        }
    }


    /**
     * Extrae la clave privada de un archivo PKCS#12 encriptado
     * 
     * @param string $p12Content Contenido binario del archivo PKCS#12
     * @param string $password Contraseña
     * @return string|false PEM de la clave privada o false
     */
    private static function convertirPKCS12($p12Content, $password)
    {
        error_log("convertirPKCS12: Iniciando extracción de PKCS#12");

        // Crear archivo temporal para el PKCS#12
        $tempP12 = tempnam(sys_get_temp_dir(), 'sat_p12_');
        if (file_put_contents($tempP12, $p12Content) === false) {
            error_log("convertirPKCS12: No se pudo crear archivo temporal");
            return false;
        }

        // Arreglo para almacenar datos del PKCS#12
        $certs = [];
        $privateKey = null;

        // Cargar el PKCS#12
        $loaded = openssl_pkcs12_read($p12Content, $certs, $password);

        unlink($tempP12);

        if (!$loaded) {
            error_log("convertirPKCS12: openssl_pkcs12_read falló");
            error_log("OpenSSL Error: " . (openssl_error_string() ?: "Sin detalles"));
            return false;
        }

        error_log("convertirPKCS12: PKCS#12 cargado correctamente");
        error_log("convertirPKCS12: Claves en array: " . implode(", ", array_keys($certs)));

        // Obtener la clave privada del array
        if (!isset($certs['pkey']) || empty($certs['pkey'])) {
            error_log("convertirPKCS12: No hay clave privada en el PKCS#12");
            return false;
        }

        $pem = $certs['pkey'];
        error_log("convertirPKCS12: Clave privada extraída, longitud: " . strlen($pem));

        return $pem;
    }

    /**
     * Crea un archivo PEM temporal a partir del archivo .key DER o PKCS#12
     * Útil para métodos que requieren ruta a archivo en lugar de contenido
     * 
     * @param string $rutaKey Ruta del .key
     * @param string $password Contraseña descifrada
     * @return string|false Ruta al archivo PEM temporal o false
     */
    public static function crearKeyPEMTemporal($rutaKey, $password)
    {
        error_log("crearKeyPEMTemporal: Iniciando conversión de $rutaKey");

        $pem = self::convertirKeyAPEM($rutaKey, $password);

        if (!$pem) {
            error_log("crearKeyPEMTemporal: convertirKeyAPEM retornó false");
            return false;
        }

        // Crear archivo temporal
        $tempFile = tempnam(sys_get_temp_dir(), 'sat_key_');
        if (!$tempFile) {
            error_log("crearKeyPEMTemporal: tempnam falló");
            return false;
        }

        error_log("crearKeyPEMTemporal: Archivo temporal creado: $tempFile");

        if (file_put_contents($tempFile, $pem) === false) {
            error_log("crearKeyPEMTemporal: file_put_contents falló");
            unlink($tempFile);
            return false;
        }

        // Restringir permisos del archivo
        chmod($tempFile, 0600);

        error_log("crearKeyPEMTemporal: Éxito, archivo temporal listo: $tempFile");

        return $tempFile;
    }

    /**
     * Verifica que los archivos del sello digital existan
     * 
     * @param string $rutaCer Ruta del archivo .cer
     * @param string $rutaKey Ruta del archivo .key
     * @return bool True si ambos archivos existen
     */
    public static function verificarArchivos($rutaCer, $rutaKey)
    {
        $baseDir = __DIR__ . '/../uploads/sellos/';
        return file_exists($baseDir . $rutaCer) && file_exists($baseDir . $rutaKey);
    }

    /**
     * Obtiene información del sello digital de una empresa
     * 
     * @param int $idEmpresa ID de la empresa
     * @param PDO $conn Conexión a la base de datos
     * @return array|false Información del sello o false si no existe
     */
    public static function obtenerInfoSello($idEmpresa, $conn)
    {
        try {
            $stmt = $conn->prepare("SELECT file_cer, file_key, clave FROM empresas WHERE id_empresa = ?");
            $stmt->execute([$idEmpresa]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && $result['file_cer'] && $result['file_key'] && $result['clave']) {
                return [
                    'certificado' => $result['file_cer'],
                    'llave' => $result['file_key'],
                    'clave_cifrada' => $result['clave'],
                    'clave_descifrada' => self::descifrarClave($result['clave'], $idEmpresa),
                    'archivos_existen' => self::verificarArchivos($result['file_cer'], $result['file_key'])
                ];
            }

            return false;
        } catch (Exception $e) {
            error_log("Error al obtener info del sello: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Valida que el RFC del certificado .cer coincida con el RFC esperado
     * 
     * @param string $rutaCer Ruta completa al archivo .cer
     * @param string $rfcEsperado RFC esperado (en mayúsculas)
     * @return array|false Array con detalles si válido, false si no coincide o error
     */
    public static function validarCertificado($rutaCer, $rfcEsperado)
    {
        try {
            if (!file_exists($rutaCer)) {
                error_log("validarCertificado: Archivo .cer no existe: $rutaCer");
                return false;
            }

            $certContent = file_get_contents($rutaCer);
            if (!$certContent) {
                error_log("validarCertificado: No se pudo leer el archivo .cer");
                return false;
            }

            $x509 = openssl_x509_read($certContent);
            if (!$x509) {
                error_log("validarCertificado: El archivo no es un certificado X.509 válido");
                return false;
            }

            $parsed = openssl_x509_parse($x509);
            if (!$parsed) {
                error_log("validarCertificado: No se pudo parsear el certificado");
                return false;
            }

            // Extraer RFC del Subject - puede estar en CN o en el campo x500UniqueIdentifier
            $subject = $parsed['subject'] ?? [];

            // El SAT usa diferentes formatos, intentamos varias ubicaciones
            $rfcEnCert = null;

            // Opción 1: En el Common Name (CN)
            if (isset($subject['CN'])) {
                $rfcEnCert = $subject['CN'];
            }

            // Opción 2: En x500UniqueIdentifier (usado frecuentemente por el SAT)
            if (empty($rfcEnCert) && isset($subject['x500UniqueIdentifier'])) {
                $rfcEnCert = $subject['x500UniqueIdentifier'];
            }

            // Opción 3: En serialNumber
            if (empty($rfcEnCert) && isset($subject['serialNumber'])) {
                $rfcEnCert = $subject['serialNumber'];
            }

            if (!$rfcEnCert) {
                error_log("validarCertificado: RFC no encontrado en el certificado");
                error_log("validarCertificado: Subject completo: " . print_r($subject, true));
                return false;
            }

            // Normalizar para comparación - extraer solo el RFC si viene con formato "RFC / Nombre"
            $rfcEnCert = trim($rfcEnCert);
            if (strpos($rfcEnCert, '/') !== false) {
                $partes = explode('/', $rfcEnCert);
                $rfcEnCert = trim($partes[0]);
            }

            $rfcEnCert = mb_strtoupper($rfcEnCert);
            $rfcEsperado = mb_strtoupper(trim($rfcEsperado));

            error_log("validarCertificado: RFC en cert: '$rfcEnCert', RFC esperado: '$rfcEsperado'");

            // Validar coincidencia
            if ($rfcEnCert !== $rfcEsperado) {
                error_log("validarCertificado: Los RFC no coinciden");
                return false;
            }

            return [
                'valido' => true,
                'rfc_cert' => $rfcEnCert,
                'vigencia_desde' => $parsed['validFrom_time_t'] ?? null,
                'vigencia_hasta' => $parsed['validTo_time_t'] ?? null
            ];
        } catch (Exception $e) {
            error_log("validarCertificado Exception: " . $e->getMessage());
            return false;
        }
    }
    public static function convertirKeyAPEMSeguro(string $rutaKey, string $password): string
    {
        $password = trim($password);
        $tmpPem = tempnam(sys_get_temp_dir(), 'key_') . '.pem';

        $cmd = sprintf(
            '"%s" pkcs8 -inform DER -in %s -passin pass:%s -out %s 2>&1',
            self::opensslBin(),
            escapeshellarg($rutaKey),
            escapeshellarg($password),
            escapeshellarg($tmpPem)
        );

        exec($cmd, $output, $status);

        if ($status !== 0 || !file_exists($tmpPem)) {
            error_log('OPENSSL ERROR: ' . implode("\n", $output));
            throw new Exception('OpenSSL no pudo convertir la KEY a PEM');
        }

        $pem = file_get_contents($tmpPem);
        unlink($tmpPem);

        if (
            stripos($pem, 'BEGIN PRIVATE KEY') === false &&
            stripos($pem, 'BEGIN RSA PRIVATE KEY') === false &&
            stripos($pem, 'BEGIN ENCRYPTED PRIVATE KEY') === false
        ) {
            throw new Exception('El PEM generado no es una llave privada válida');
        }

        return $pem;
    }
}
