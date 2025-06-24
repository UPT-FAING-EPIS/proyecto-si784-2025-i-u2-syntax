<?php
require_once realpath(__DIR__ . '/abstract/NotificadorCorreo.php');

class CorreoVinculacionUPT extends NotificadorCorreo {
    private $codigo;
    private $nombreEstudiante;

    public function mtdNotificar(...$inputs) {
        $emailDestino = $inputs[0];
        $nombreCompleto = $inputs[1] ?? 'Estudiante';
        $codigoExterno = $inputs[2] ?? null;

        $this->codigo = $codigoExterno;
        $this->nombreEstudiante = $nombreCompleto;
        $this->subject = "Código de verificación - Vinculación UPT";

        $html = "
        <div style='background:#f3f4f6;padding:20px;font-family:Arial'>
            <div style='max-width:600px;margin:auto;background:white;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,0.12)'>
                <div style='background:linear-gradient(135deg,#1e3a5f,#3182ce);color:white;padding:30px;text-align:center;border-radius:12px 12px 0 0'>
                    <div style='margin-bottom:15px'>
                        <i style='font-size:48px'>🏛️</i>
                    </div>
                    <h1 style='margin:0;font-size:24px'>Universidad Privada de Tacna</h1>
                    <p style='margin:10px 0 0 0;opacity:0.9'>Sistema de Mentoría Académica</p>
                </div>
                <div style='padding:30px;text-align:center'>
                    <h2 style='color:#1e3a5f;margin:0 0 20px 0'>¡Hola, {$this->nombreEstudiante}!</h2>
                    <p style='color:#374151;font-size:16px;line-height:1.6'>
                        Has solicitado vincular tu cuenta al sistema UPT.<br>
                        Tu código de verificación es:
                    </p>
                    <div style='font-size:32px;font-family:monospace;background:#f3f4f6;padding:20px 30px;margin:25px 0;border-radius:8px;color:#1e3a5f;font-weight:bold;letter-spacing:3px;border:2px dashed #3182ce'>{$this->codigo}</div>
                    <div style='background:#ebf8ff;padding:15px;border-radius:8px;margin:20px 0'>
                        <p style='color:#1e3a5f;font-size:14px;margin:0;font-weight:bold'>⏰ Importante:</p>
                        <ul style='color:#374151;font-size:14px;text-align:left;margin:10px 0 0 0;padding-left:20px'>
                            <li>Este código expira en <strong>15 minutos</strong></li>
                            <li>No compartas este código con nadie</li>
                            <li>Úsalo solo en el sitio oficial de AMS-UPT</li>
                        </ul>
                    </div>
                </div>
                <div style='background:#f9fafb;padding:20px;text-align:center;border-radius:0 0 12px 12px;color:#6b7280;font-size:14px'>
                    <p style='margin:0'>
                        Si no solicitaste esta verificación, por favor ignora este mensaje.<br>
                        <strong>Equipo de Soporte Técnico - AMS UPT</strong>
                    </p>
                </div>
            </div>
        </div>";

        try {
            $this->mail->addAddress($emailDestino);
            $this->mail->Subject = $this->subject;
            $this->mail->Body = $html;
            $this->mail->send();
            
            error_log("Código de vinculación UPT enviado a {$emailDestino}: {$this->codigo}");
            
            return true; 
        } catch (Exception $e) {
            error_log("Error al enviar correo de vinculación UPT: " . $this->mail->ErrorInfo);
            return false;
        }
    }
}