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
}
?>