<?php
// core/mail/PHPMailerStub.php
// Este archivo es solo para ayuda del IDE (Intelephense)
// No se usa en ejecución, solo para análisis estático

namespace PHPMailer\PHPMailer;

class PHPMailer
{
    const ENCRYPTION_SMTPS = 'ssl';
    const ENCRYPTION_STARTTLS = 'tls';
    
    public $Subject;
    public $Body;
    public $AltBody;
    public $CharSet;
    public $SMTPAuth;
    public $Host;
    public $Port;
    public $Username;
    public $Password;
    public $SMTPSecure;
    
    public function __construct($exceptions = false) {}
    public function isSMTP() {}
    public function setFrom($address, $name = '') {}
    public function addReplyTo($address, $name = '') {}
    public function addAddress($address, $name = '') {}
    public function isHTML($isHtml = true) {}
    public function send() {}
    public function addAttachment($path, $name = '') {}
}

class Exception extends \Exception {}