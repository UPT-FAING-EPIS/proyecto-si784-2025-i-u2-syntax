<?php
require_once BASE_PATH . '/models/Usuario.php';
require_once BASE_PATH . '/config/mongodb.php';

class RangoController {
    
    private $usuarioModel;
    private $mongodb;
    
    public function __construct() {
        $this->usuarioModel = new Usuario();
        $this->mongodb = new MongoDB();
    }
    
    public function generarClaveReclamo($usuarioId, $discordUsername = '', $emailUsuario = '') {
        try {
            $usuario = $this->usuarioModel->obtenerDatosCompletos($usuarioId);
            
            if (!$usuario) {
                throw new Exception("Usuario no encontrado");
            }
            
            $puedeReclamar = !empty($usuario['ID_ESTUDIANTE']) || !empty($usuario['ID_DOCENTE']);
            
            if (!$puedeReclamar) {
                throw new Exception("El usuario no tiene permisos para reclamar un rango");
            }
            
            $tipoUsuario = !empty($usuario['ID_ESTUDIANTE']) ? 'estudiante' : 'docente';
            $rolId = !empty($usuario['ID_ESTUDIANTE']) ? $usuario['ID_ESTUDIANTE'] : $usuario['ID_DOCENTE'];
            
            if (empty($usuario['NOMBRE']) || empty($usuario['APELLIDO'])) {
                throw new Exception("Faltan datos obligatorios del usuario (nombre o apellido)");
            }
            
            // Validación de DNI (permitir vacío pero registrarlo)
            $dni = $usuario['DNI'] ?? 'No disponible';
            
            $discordUsername = trim($discordUsername);
            if (empty($discordUsername)) {
                throw new Exception("El username de Discord es requerido");
            }
            
            if (!preg_match('/^[a-zA-Z0-9_.]{2,32}$/', $discordUsername)) {
                throw new Exception("Username de Discord inválido. Solo se permiten letras, números, puntos y guiones bajos (2-32 caracteres)");
            }
            
            $emailUsuario = trim($emailUsuario);
            if (empty($emailUsuario)) {
                throw new Exception("El email del usuario es requerido");
            }
            
            if (!filter_var($emailUsuario, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Email inválido");
            }
            
            $this->invalidarCodigosAnteriores($usuarioId);
            
            $codigoReclamo = $this->generarCodigoUnico();
            
            $fechaExpiracion = new DateTime();
            $fechaExpiracion->add(new DateInterval('PT5M'));
            
            $datosReclamo = [
                'usuario_id' => (int)$usuarioId,
                'dni' => $dni,
                'nombre' => $usuario['NOMBRE'],
                'apellido' => $usuario['APELLIDO'],
                'email' => $emailUsuario,
                'discord_username' => $discordUsername,
                'tipo_usuario' => $tipoUsuario,
                'rol_id' => (int)$rolId,
                'codigo_reclamo' => $codigoReclamo,
                'fecha_generacion' => new MongoDB\BSON\UTCDateTime(),
                'fecha_expiracion' => new MongoDB\BSON\UTCDateTime($fechaExpiracion->getTimestamp() * 1000),
                'usado' => false,
                'activo' => true,
                'ip_generacion' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
            ];
            
            $resultado = $this->guardarEnMongoDB($datosReclamo);
            
            if (!$resultado) {
                throw new Exception("Error al guardar el código en la base de datos");
            }
            
            $emailEnviado = $this->enviarNotificacionCorreo(
                $emailUsuario,
                $usuario['NOMBRE'] . ' ' . $usuario['APELLIDO'],
                $codigoReclamo,
                $discordUsername,
                $tipoUsuario,
                $dni
            );
            
            error_log("✅ Código de reclamo generado - Usuario: {$usuarioId}, Discord: @{$discordUsername}, Tipo: {$tipoUsuario}, Código: {$codigoReclamo}, Email: " . ($emailEnviado ? 'Enviado' : 'Error'));
            
            $mensajeRespuesta = 'Código de reclamo generado exitosamente';
            if ($emailEnviado) {
                $mensajeRespuesta .= '. Se ha enviado una copia a tu correo electrónico.';
            } else {
                $mensajeRespuesta .= '. Nota: No se pudo enviar el correo de confirmación.';
            }
            
            return [
                'success' => true,
                'mensaje' => $mensajeRespuesta,
                'codigo' => $codigoReclamo,
                'tipo_usuario' => $tipoUsuario,
                'expira_en' => '5 minutos',
                'email_enviado' => $emailEnviado
            ];
            
        } catch (Exception $e) {
            error_log("❌ Error generando código de reclamo: " . $e->getMessage());
            return [
                'success' => false,
                'mensaje' => $e->getMessage()
            ];
        }
    }
    
    private function enviarNotificacionCorreo($email, $nombreCompleto, $codigo, $discordUsername, $tipoUsuario, $dni) {
        try {
            // ✅ Ruta correcta para tu estructura
            require_once BASE_PATH . '/utils/CorreoCodigoDiscord.php';
            
            $notificador = new CorreoCodigoDiscord();
            $resultado = $notificador->mtdNotificar(
                $email,
                $nombreCompleto,
                $codigo,
                $discordUsername,
                $tipoUsuario,
                $dni
            );
            
            return $resultado;
            
        } catch (Exception $e) {
            error_log("⚠️ Error enviando notificación por correo: " . $e->getMessage());
            return false;
        }
    }
    
    private function invalidarCodigosAnteriores($usuarioId) {
        try {
            $collection = $this->mongodb->database->selectCollection('keys_usuarios');
            
            $filtro = [
                'usuario_id' => (int)$usuarioId,
                'activo' => true,
                'usado' => false
            ];
            
            $actualizacion = [
                '$set' => [
                    'activo' => false,
                    'razon_invalidacion' => 'nuevo_codigo_generado',
                    'fecha_invalidacion' => new MongoDB\BSON\UTCDateTime()
                ]
            ];
            
            $resultado = $collection->updateMany($filtro, $actualizacion);
            
            if ($resultado->getModifiedCount() > 0) {
                error_log("📝 Invalidados {$resultado->getModifiedCount()} códigos anteriores del usuario {$usuarioId}");
            }
            
        } catch (Exception $e) {
            error_log("⚠️ Error invalidando códigos anteriores: " . $e->getMessage());
        }
    }
    
    private function generarCodigoUnico() {
        $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $longitud = 12;
        $codigo = '';
        
        $timestamp = base_convert(time(), 10, 36);
        
        for ($i = 0; $i < $longitud - strlen($timestamp); $i++) {
            $codigo .= $caracteres[random_int(0, strlen($caracteres) - 1)];
        }
        
        $codigoFinal = $codigo . strtoupper($timestamp);
        $codigoArray = str_split($codigoFinal);
        shuffle($codigoArray);
        
        return implode('', $codigoArray);
    }
    
    private function guardarEnMongoDB($datos) {
        try {
            if (!$this->mongodb->verificarConexion()) {
                throw new Exception("Sin conexión a MongoDB");
            }
            
            $collection = $this->mongodb->database->selectCollection('keys_usuarios');
            
            $resultado = $collection->insertOne($datos);
            
            return $resultado->getInsertedId() !== null;
            
        } catch (Exception $e) {
            error_log("❌ Error guardando en MongoDB: " . $e->getMessage());
            throw $e;
        }
    }
}