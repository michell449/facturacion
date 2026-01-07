<?php
/**
 * Utilidades para envío de facturas vía correo electrónico.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../autoload-vendor.php';
require_once __DIR__ . '/CorreoConfigService.php';
// PHPMailer v5 (sin namespaces) incluido manualmente
require_once __DIR__ . '/class.phpmailer.php';
require_once __DIR__ . '/class.smtp.php';

// Nota: Usamos PHPMailer v5 sin namespaces; evitar "use" de v6.



/**
 * Renderiza plantillas sustituyendo marcadores.
 */
function facturaRenderTemplate(string $template, array $vars): string
{
    $replacements = [
        '{FOLIO}' => $vars['folio'] ?? '',
        '{EMPRESA}' => $vars['empresa'] ?? '',
        '{CLIENTE}' => $vars['cliente'] ?? '',
        '{FECHA}' => $vars['fecha'] ?? '',
        '{TOTAL}' => $vars['total'] ?? '',
        '{RFC_CLIENTE}' => $vars['rfc_cliente'] ?? '',
        '{RFC_EMPRESA}' => $vars['rfc_empresa'] ?? ''
    ];

    return str_replace(array_keys($replacements), array_values($replacements), $template);
}

/**
 * Configura PHPMailer con los parámetros proporcionados.
 */
/** @return PHPMailer */
function facturaBuildMailer(array $config)
{
    $mailer = new PHPMailer(true);
    $mailer->isSMTP();
    $mailer->CharSet = 'UTF-8';

    $host = $config['smtp_host'] ?? (defined('MAIL_HOST') ? MAIL_HOST : '');
    $port = (int)($config['smtp_port'] ?? (defined('MAIL_PORT') ? MAIL_PORT : 587));
    $user = $config['smtp_usuario'] ?? (defined('MAIL_USER') ? MAIL_USER : '');
    $pass = $config['smtp_password'] ?? (defined('MAIL_PSWD') ? MAIL_PSWD : '');
    $auth = isset($config['smtp_auth']) ? (bool)$config['smtp_auth'] : (defined('MAIL_AUT') ? (bool)MAIL_AUT : true);
    $seguridad = strtolower($config['seguridad'] ?? (defined('MAIL_SEC') ? MAIL_SEC : 'tls'));
    $fromEmail = $config['remitente_email'] ?? (defined('MAIL_USER') ? MAIL_USER : $user);
    $fromName = $config['remitente_nombre'] ?? (defined('SYSNAME') ? SYSNAME : 'Sistema');

    $mailer->SMTPAuth = $auth;
    $mailer->Host = $host;
    $mailer->Port = $port;
    $mailer->Username = $user;
    $mailer->Password = $pass;

    // PHPMailer v5 espera 'ssl' o 'tls' como string
    if ($seguridad === 'ssl' || $seguridad === 'tls') {
        $mailer->SMTPSecure = $seguridad;
    }

    $mailer->setFrom($fromEmail, $fromName);
    $mailer->addReplyTo($fromEmail, $fromName);
    $mailer->isHTML(true);

    return $mailer;
}

/**
 * Envía un correo con los datos proporcionados.
 */
function facturaEnviarCorreo(
    array $config,
    string $destinatarioEmail,
    string $destinatarioNombre,
    array $vars,
    array $attachments = []
): array {
    try {
        $effectivePassword = $config['smtp_password'] ?? (defined('MAIL_PSWD') ? MAIL_PSWD : '');
        if (empty($effectivePassword)) {
            throw new Exception('La configuración SMTP no está completa.');
        }

        $mailer = facturaBuildMailer($config);
        $mailer->addAddress($destinatarioEmail, $destinatarioNombre);

        $mailer->Subject = facturaRenderTemplate($config['asunto_factura'], $vars);
        $mensaje = facturaRenderTemplate($config['plantilla_correo'], $vars);
        $mailer->Body = nl2br($mensaje);
        $mailer->AltBody = $mensaje;

        foreach ($attachments as $attachment) {
            if (is_array($attachment)) {
                $ruta = $attachment['path'] ?? '';
                $nombre = $attachment['name'] ?? '';
            } else {
                $ruta = $attachment;
                $nombre = '';
            }

            if ($ruta && file_exists($ruta)) {
                if ($nombre) {
                    $mailer->addAttachment($ruta, $nombre);
                } else {
                    $mailer->addAttachment($ruta);
                }
            }
        }

        $mailer->send();

        return [
            'success' => true,
            'message' => 'Correo enviado correctamente.'
        ];
    } catch (phpmailerException $mailEx) {
        return [
            'success' => false,
            'message' => 'Error al enviar correo: ' . $mailEx->getMessage()
        ];
    } catch (Exception $ex) {
        return [
            'success' => false,
            'message' => $ex->getMessage()
        ];
    }
}
