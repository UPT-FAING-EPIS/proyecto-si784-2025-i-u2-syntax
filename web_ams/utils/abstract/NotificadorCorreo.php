<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

abstract class NotificadorCorreo {
    protected $emailFrom = "ams2024.epis@gmail.com";
    protected $passwordFrom = "nqkhnjumzsoypkmv";

    protected $subject;
    protected $content;

    protected $mail;

    public function __construct() {
        $this->configurarCorreo();
    }

    protected function configurarCorreo() {
        $this->mail = new PHPMailer(true);

        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $this->emailFrom;
        $this->mail->Password = $this->passwordFrom;
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Port = 587;

        $this->mail->setFrom($this->emailFrom, 'Sistema Mentoría UPT');
        $this->mail->isHTML(true);
        $this->mail->CharSet = 'UTF-8';
    }

    abstract public function mtdNotificar(...$inputs);
}
