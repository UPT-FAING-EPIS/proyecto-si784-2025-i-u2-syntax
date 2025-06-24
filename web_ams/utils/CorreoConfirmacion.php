<?php
require_once realpath(__DIR__ . '/abstract/NotificadorCorreo.php');

class CorreoConfirmacion extends NotificadorCorreo {
    private $codigo;

    public function mtdNotificar(...$inputs) {
        $emailDestino = $inputs[0];
        $this->codigo = strval(rand(100000, 999999));
        $this->subject = "¡Hola! Tu código de confirmación ha llegado";

        $html = "
        <div style='background:#f3f4f6;padding:20px;font-family:Arial'>
            <div style='max-width:600px;margin:auto;background:white;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,0.12)'>
                <div style='background:linear-gradient(135deg,#4f46e5,#3b82f6);color:white;padding:30px;text-align:center;border-radius:12px 12px 0 0'>
                    <h1 style='margin:0;font-size:24px'>Código de Confirmación</h1>
                </div>
                <div style='padding:30px;text-align:center'>
                    <p style='color:#374151;font-size:16px'>Tu código de confirmación es:</p>
                    <div style='font-size:28px;font-family:monospace;background:#f3f4f6;padding:15px 25px;margin:20px 0;border-radius:8px;color:#4f46e5;font-weight:bold;letter-spacing:2px'>{$this->codigo}</div>
                    <p style='color:#6b7280;font-size:14px'>Este código expirará en 10 minutos</p>
                </div>
                <div style='background:#f9fafb;padding:20px;text-align:center;border-radius:0 0 12px 12px;color:#6b7280;font-size:14px'>
                    <p style='margin:0'>Si no solicitaste este código, por favor ignora este mensaje.</p>
                </div>
            </div>
        </div>";

        try {
            $this->mail->addAddress($emailDestino);
            $this->mail->Subject = $this->subject;
            $this->mail->Body = $html;
            $this->mail->send();
            return $this->codigo;
        } catch (Exception $e) {
            error_log("Error al enviar correo: " . $this->mail->ErrorInfo);
            return null;
        }
    }
}
