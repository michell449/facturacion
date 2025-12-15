<?php
/**
 * Utilidades para manejo seguro del sello digital
 */

class SelloUtils {
    
    /**
     * Cifra una clave privada usando AES-256-CBC
     * 
     * @param string $clavePrivada La clave a cifrar
     * @param int $idEmpresa ID de la empresa (usado como salt)
     * @return string Clave cifrada en base64
     */
    public static function cifrarClave($clavePrivada, $idEmpresa) {
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
    public static function descifrarClave($claveCifrada, $idEmpresa) {
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
    public static function convertirKeyAPEM($rutaKey, $password) {
        try {
            if (!file_exists($rutaKey)) {
                error_log("convertirKeyAPEM: Archivo no existe: $rutaKey");
                return false;
            }

            // Leer el contenido binario del archivo
            $keyContent = file_get_contents($rutaKey);
            if (!$keyContent) {
                error_log("convertirKeyAPEM: No se pudo leer el archivo: $rutaKey");
                return false;
            }

            $fileSize = strlen($keyContent);
            error_log("convertirKeyAPEM: Archivo leído, tamaño: $fileSize bytes");
            error_log("convertirKeyAPEM: Primeros 20 bytes (hex): " . bin2hex(substr($keyContent, 0, 20)));

            // Identificar formato basado en los primeros bytes
            $magic = bin2hex(substr($keyContent, 0, 10));
            
            // PKCS#12: Inicia con "3082" o "3081" (ASN.1 SEQUENCE)
            // y contiene "06092a864886f70d01050d" (Object ID para PKCS#12)
            if ((substr($magic, 0, 4) === '3082' || substr($magic, 0, 4) === '3081') &&
                strpos(bin2hex($keyContent), '06092a864886f70d01050d') !== false) {
                error_log("convertirKeyAPEM: Formato detectado: PKCS#12");
                return self::convertirPKCS12($keyContent, $password);
            }
            
            // Intento normal: PKCS#8 o PKCS#1 encriptado
            error_log("convertirKeyAPEM: Formato detectado: Probablemente PKCS#8 o DER");
            $privateKey = openssl_pkey_get_private($keyContent, $password);
            
            if (!$privateKey) {
                error_log("convertirKeyAPEM: openssl_pkey_get_private falló");
                error_log("OpenSSL Error: " . (openssl_error_string() ?: "Sin detalles"));
                
                // Intento sin contraseña
                $privateKey = openssl_pkey_get_private($keyContent);
                
                if (!$privateKey) {
                    error_log("convertirKeyAPEM: También falló sin contraseña");
                    return false;
                } else {
                    error_log("convertirKeyAPEM: Funciona sin contraseña (archivo no encriptado)");
                }
            } else {
                error_log("convertirKeyAPEM: openssl_pkey_get_private OK con contraseña");
            }

            // Exportar la clave a formato PEM
            $pem = '';
            $exported = openssl_pkey_export($privateKey, $pem);
            
            if (!$exported || empty($pem)) {
                error_log("convertirKeyAPEM: openssl_pkey_export falló");
                // Nota: openssl_free_key está deprecado en PHP modernos; liberación es automática.
                return false;
            }
            
            error_log("convertirKeyAPEM: Clave exportada a PEM, longitud: " . strlen($pem));
            // Nota: openssl_free_key está deprecado en PHP modernos; liberación es automática.

            return $pem;

        } catch (\Exception $e) {
            error_log("convertirKeyAPEM Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Extrae la clave privada de un archivo PKCS#12 encriptado
     * 
     * @param string $p12Content Contenido binario del archivo PKCS#12
     * @param string $password Contraseña
     * @return string|false PEM de la clave privada o false
     */
    private static function convertirPKCS12($p12Content, $password) {
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
    public static function crearKeyPEMTemporal($rutaKey, $password) {
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
    public static function verificarArchivos($rutaCer, $rutaKey) {
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
    public static function obtenerInfoSello($idEmpresa, $conn) {
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
    public static function validarCertificado($rutaCer, $rfcEsperado) {
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
}
?>