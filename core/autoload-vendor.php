<?php
// C:\xampp\htdocs\app\core\autoload-phpcfdi.php

spl_autoload_register(function ($class) {
    // Agrupación de namespaces por utilidad para facilitar mantenimiento
    $prefixes = [
        // --- CFDI (Eclipxe) núcleo y dependencias ---
        'CfdiUtils\\'                        => __DIR__ . '/../vendor/eclipxe/cfdiutils/src/CfdiUtils/',
        'Eclipxe\\Enum\\'                   => __DIR__ . '/../vendor/eclipxe/enum/src/',
        'Eclipxe\\MicroCatalog\\'           => __DIR__ . '/../vendor/eclipxe/micro-catalog/src/',
        'Eclipxe\\XmlSchemaValidator\\'     => __DIR__ . '/../vendor/eclipxe/xmlschemavalidator/src/',
        'XmlResourceRetriever\\'            => __DIR__ . '/../vendor/eclipxe/xmlresourceretriever/src/XmlResourceRetriever/',

        // --- Ecosistema PhpCfdi (herramientas auxiliares CFDI) ---
        'PhpCfdi\\Rfc\\'                    => __DIR__ . '/../vendor/phpcfdi/rfc/src/',
        'PhpCfdi\\XmlCancelacion\\'         => __DIR__ . '/../vendor/phpcfdi/xml-cancelacion/src/',
        'PhpCfdi\\SatWsDescargaMasiva\\'    => __DIR__ . '/../vendor/phpcfdi/sat-ws-descarga-masiva/src/',
        'PhpCfdi\\CfdiCleaner\\'            => __DIR__ . '/../vendor/phpcfdi/cfdi-cleaner/src/',
        'PhpCfdi\\CfdiToPdf\\'              => __DIR__ . '/../vendor/phpcfdi/cfditopdf/src/',
        'PhpCfdi\\CfdiExpresiones\\'        => __DIR__ . '/../vendor/phpcfdi/cfdi-expresiones/src/',

        // --- Credenciales y timbrado (PhpCfdi) ---
        'PhpCfdi\\Credentials\\'            => __DIR__ . '/../vendor/phpcfdi/credentials/src/',
        'PhpCfdi\\Finkok\\'                 => __DIR__ . '/../vendor/phpcfdi/finkok/src/',

        // --- HTTP / PSR / Guzzle ---
        'Http\\Message\\'              => __DIR__ . '/../vendor/psr/http-message/src/',
        'Http\\Client\\'               => __DIR__ . '/../vendor/psr/http-client/src/',
        'GuzzleHttp\\'                      => __DIR__ . '/../vendor/guzzlehttp/guzzle/src/',
        'GuzzleHttp\\Psr7\\'                => __DIR__ . '/../vendor/guzzlehttp/psr7/src/',
        'GuzzleHttp\\Promise\\'             => __DIR__ . '/../vendor/guzzlehttp/promises/src/',
        'Psr\\Http\\Message\\'              => __DIR__ . '/../vendor/psr/http-message/src/',
        'Psr\\Http\\Client\\'               => __DIR__ . '/../vendor/psr/http-client/src/',
        // --- Email ---
        'PHPMailer\\PHPMailer\\'            => __DIR__ . '/../vendor/phpmailer/phpmailer/src/',

        // --- PDF ---
        'Mpdf\\'                            => __DIR__ . '/../vendor/mpdf/mpdf/src/',
        'Dompdf\\'                          => __DIR__ . '/../vendor/dompdf/dompdf/src/',
        'Smalot\\PdfParser\\'               => __DIR__ . '/../vendor/smalot/pdfparser/src/',
        'Spatie\\PdfToText\\'               => __DIR__ . '/../vendor/spatie/pdf-to-text/src/',
    ];
    
    foreach ($prefixes as $prefix => $baseDir) {
        $len = strlen($prefix);

        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }

        $relativeClass = substr($class, $len);
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        // Log de intento de carga
        error_log("[AUTOLOAD] Intentando cargar: $class desde $file");

        if (file_exists($file)) {
            require_once $file;
            error_log("[AUTOLOAD] ✓ Cargado exitosamente: $class");
            return;
        } else {
            error_log("[AUTOLOAD] ✗ No se encontró el archivo: $file");
        }
    }
    
    // Si llegamos aquí, no se encontró ninguna coincidencia
    if (strpos($class, 'CfdiUtils') !== false || strpos($class, 'PhpCfdi') !== false || strpos($class, 'XmlResource') !== false) {
        error_log("[AUTOLOAD] ⚠ Clase CFDI no encontrada en ninguna ruta: $class");
    }
});
