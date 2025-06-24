<?php
require_once realpath(__DIR__ . '/abstract/NotificadorCorreo.php');

class CorreoCodigoDiscord extends NotificadorCorreo {
    
    private $codigo;
    private $nombreCompleto;
    private $discordUsername;
    private $tipoUsuario;
    private $dni;

    public function mtdNotificar(...$inputs) {
        $emailDestino = $inputs[0];
        $this->nombreCompleto = $inputs[1] ?? 'Usuario';
        $this->codigo = $inputs[2] ?? null;
        $this->discordUsername = $inputs[3] ?? '';
        $this->tipoUsuario = $inputs[4] ?? 'usuario';
        $this->dni = $inputs[5] ?? '';

        $this->subject = "Código de Reclamo Discord - Sistema UPT";

        $html = $this->generarHtmlCorreo();

        try {
            $this->mail->addAddress($emailDestino);
            $this->mail->Subject = $this->subject;
            $this->mail->Body = $html;
            $this->mail->send();
            
            error_log("✅ [EMAIL] Código de reclamo Discord enviado a {$emailDestino} para @{$this->discordUsername}");
            
            return true; 
        } catch (Exception $e) {
            error_log("❌ [EMAIL] Error enviando código de reclamo Discord: " . $this->mail->ErrorInfo);
            return false;
        }
    }

    private function generarHtmlCorreo() {
        $tipoIcono = $this->tipoUsuario === 'estudiante' ? '🎓' : '👨‍🏫';
        $tipoTexto = ucfirst($this->tipoUsuario);
        $colorTipo = $this->tipoUsuario === 'estudiante' ? '#10b981' : '#3b82f6';

        return "
        <div style='background:#f3f4f6;padding:20px;font-family:Arial,sans-serif'>
            <div style='max-width:600px;margin:auto;background:white;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,0.12)'>
                
                <div style='background:linear-gradient(135deg,#1e3a5f,#3182ce);color:white;padding:30px;text-align:center;border-radius:12px 12px 0 0'>
                    <div style='margin-bottom:15px'>
                        <span style='font-size:48px'>🏛️</span>
                    </div>
                    <h1 style='margin:0;font-size:24px;font-weight:600'>Universidad Privada de Tacna</h1>
                    <p style='margin:10px 0 0 0;opacity:0.9;font-size:16px'>Sistema de Mentoría Académica</p>
                </div>

                <div style='padding:30px;text-align:center'>
                    <div style='margin-bottom:20px'>
                        <span style='font-size:64px'>{$tipoIcono}</span>
                    </div>
                    
                    <h2 style='color:#1e3a5f;margin:0 0 15px 0;font-size:22px'>¡Hola, {$this->nombreCompleto}!</h2>
                    
                    <p style='color:#374151;font-size:16px;line-height:1.6;margin-bottom:25px'>
                        Tu código para reclamar el rango de <strong style='color:{$colorTipo}'>{$tipoTexto}</strong> 
                        en Discord ha sido generado exitosamente.
                    </p>

                    <div style='background:#f8fafc;border:2px dashed #3182ce;border-radius:12px;padding:25px;margin:25px 0'>
                        <p style='color:#1e3a5f;font-size:14px;margin:0 0 10px 0;font-weight:600'>🔑 CÓDIGO DE RECLAMO</p>
                        <div style='font-size:28px;font-family:\"Courier New\",monospace;color:#1e3a5f;font-weight:bold;letter-spacing:3px;margin:10px 0'>
                            {$this->codigo}
                        </div>
                        <p style='color:#6b7280;font-size:12px;margin:10px 0 0 0'>Válido por 5 minutos</p>
                    </div>

                    <div style='background:#f0f9ff;border-radius:8px;padding:20px;margin:20px 0;text-align:left'>
                        <h3 style='color:#1e3a5f;margin:0 0 15px 0;font-size:16px;text-align:center'>📋 Datos de Verificación</h3>
                        <table style='width:100%;font-size:14px;color:#374151'>
                            <tr style='border-bottom:1px solid #e5e7eb'>
                                <td style='padding:8px 0;font-weight:600;color:#1e3a5f'>Nombre:</td>
                                <td style='padding:8px 0'>{$this->nombreCompleto}</td>
                            </tr>
                            <tr style='border-bottom:1px solid #e5e7eb'>
                                <td style='padding:8px 0;font-weight:600;color:#1e3a5f'>DNI:</td>
                                <td style='padding:8px 0'>{$this->dni}</td>
                            </tr>
                            <tr style='border-bottom:1px solid #e5e7eb'>
                                <td style='padding:8px 0;font-weight:600;color:#1e3a5f'>Discord:</td>
                                <td style='padding:8px 0;font-family:monospace'>@{$this->discordUsername}</td>
                            </tr>
                            <tr>
                                <td style='padding:8px 0;font-weight:600;color:#1e3a5f'>Tipo:</td>
                                <td style='padding:8px 0'><span style='background:{$colorTipo};color:white;padding:2px 8px;border-radius:4px;font-size:12px'>{$tipoTexto}</span></td>
                            </tr>
                        </table>
                    </div>

                    <div style='background:#fef3c7;border-radius:8px;padding:20px;margin:20px 0;text-align:left'>
                        <h3 style='color:#92400e;margin:0 0 15px 0;font-size:16px;text-align:center'>⚡ Instrucciones de Uso</h3>
                        <ol style='color:#92400e;font-size:14px;margin:0;padding-left:20px;line-height:1.6'>
                            <li>Ve al servidor Discord de la UPT</li>
                            <li>Busca el canal de verificación</li>
                            <li>Usa el comando <strong>/claim codigo:{$this->codigo}</strong></li>
                            <li>¡Tu rango será asignado automáticamente!</li>
                        </ol>
                    </div>

                    <div style='background:#fef2f2;border-radius:8px;padding:15px;margin:20px 0'>
                        <p style='color:#dc2626;font-size:14px;margin:0;font-weight:600;text-align:center'>🔒 Importante - Seguridad</p>
                        <ul style='color:#7f1d1d;font-size:13px;text-align:left;margin:10px 0 0 0;padding-left:20px;line-height:1.5'>
                            <li><strong>Este código expira en 5 minutos</strong></li>
                            <li>No compartas este código con nadie más</li>
                            <li>Úsalo solo en el servidor oficial de Discord</li>
                            <li>Solo tú puedes usar este código con tu cuenta</li>
                        </ul>
                    </div>

                    <div style='margin:25px 0'>
                        <div style='background:linear-gradient(135deg,#10b981,#059669);color:white;padding:12px 30px;border-radius:8px;display:inline-block;font-weight:600;text-decoration:none'>
                            🚀 Ir a Discord UPT
                        </div>
                        <p style='color:#6b7280;font-size:12px;margin:10px 0 0 0'>
                            O copia manualmente el código en Discord
                        </p>
                    </div>
                </div>

                <div style='background:#f9fafb;padding:25px;text-align:center;border-radius:0 0 12px 12px;border-top:1px solid #e5e7eb'>
                    <p style='color:#6b7280;font-size:13px;margin:0 0 10px 0;line-height:1.5'>
                        Si no solicitaste este código, puedes ignorar este mensaje de forma segura.<br>
                        El código expirará automáticamente en 5 minutos.
                    </p>
                    <div style='border-top:1px solid #e5e7eb;padding-top:15px;margin-top:15px'>
                        <p style='color:#9ca3af;font-size:12px;margin:0'>
                            <strong>Equipo de Soporte Técnico</strong><br>
                            Sistema de Gestión Académica - Universidad Privada de Tacna<br>
                            Este es un mensaje automático, no responder a este email.
                        </p>
                    </div>
                </div>
            </div>
        </div>";
    }
}