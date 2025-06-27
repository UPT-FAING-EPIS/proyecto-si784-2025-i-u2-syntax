<?php
require_once __DIR__ . '/../core/BaseController.php';

require_once BASE_PATH . '/models/EstudianteModel.php';

class EstudianteController extends BaseController {
    private $estudianteModel;

    public function __construct() {
        $this->estudianteModel = new EstudianteModel();
    }

    public function vincularGet() {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] !== 1) {
            header('Location: ' . BASE_URL . '/index.php?accion=login');
            exit;
        }

        $mensaje = $_SESSION['mensaje'] ?? '';
        $tipo_mensaje = $_SESSION['tipo_mensaje'] ?? '';
        $error = $_SESSION['error'] ?? '';
        $datos_estudiante = $_SESSION['datos_estudiante'] ?? null;
        $codigo_enviado = $_SESSION['codigo_enviado'] ?? false;

        unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje'], $_SESSION['error']);

        require_once BASE_PATH . '/views/usuario/vincularme.php';
    }

    public function buscar_estudiantePost() {
        try {
            if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] !== 1) {
                header('Location: ' . BASE_URL . '/index.php?accion=login');
                exit;
            }

            $codigoEstudiante = trim($_POST['codigo_estudiante'] ?? '');

            if (empty($codigoEstudiante)) {
                throw new Exception('El código de estudiante es obligatorio');
            }

            if (!preg_match('/^[0-9]{8,12}$/', $codigoEstudiante)) {
                throw new Exception('El código debe tener entre 8 y 12 dígitos');
            }

            $usuarioExistente = $this->estudianteModel->verificarUsuarioVinculado($codigoEstudiante);
            if ($usuarioExistente) {
                throw new Exception('Este código de estudiante ya está vinculado a otra cuenta');
            }

            $estudiante = $this->estudianteModel->buscarPorCodigo($codigoEstudiante);

            if (!$estudiante) {
                throw new Exception('Código de estudiante no encontrado en el sistema UPT');
            }

            $emailInstitucional = $estudiante['email_institucional'] ?? $this->generarEmailInstitucional($estudiante);

            $_SESSION['datos_estudiante'] = [
                'codigo_estudiante' => $estudiante['codigo_estudiante'],
                'nombres' => $estudiante['nombres'],
                'apellidos' => $estudiante['apellidos'],
                'email_institucional' => $emailInstitucional
            ];

            $_SESSION['mensaje'] = 'Estudiante encontrado. Verifica tus datos antes de continuar.';
            $_SESSION['tipo_mensaje'] = 'success';

        } catch (Exception $e) {
            error_log("Error en buscar_estudiantePost: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: ' . BASE_URL . '/index.php?accion=vincularme');
        exit;
    }

    public function enviar_codigo_vinculacionPost() {
        try {
            if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] !== 1) {
                header('Location: ' . BASE_URL . '/index.php?accion=login');
                exit;
            }

            $codigoEstudiante = trim($_POST['codigo_estudiante'] ?? '');

            if (empty($codigoEstudiante)) {
                throw new Exception('Datos de sesión perdidos. Intenta nuevamente.');
            }
            $estudiante = $this->estudianteModel->buscarPorCodigo($codigoEstudiante);
            if (!$estudiante) {
                throw new Exception('Estudiante no encontrado. Intenta nuevamente.');
            }
            $codigoVerificacion = $this->generarCodigoVerificacion();
            $expiracion = date('Y-m-d H:i:s', strtotime('+15 minutes'));
            $guardado = $this->estudianteModel->guardarCodigoVerificacion(
                $codigoEstudiante, 
                $codigoVerificacion, 
                $expiracion
            );

            if (!$guardado) {
                throw new Exception('Error al generar código de verificación. Intenta nuevamente.');
            }
            $emailInstitucional = $estudiante['email_institucional'] ?? $this->generarEmailInstitucional($estudiante);
            $emailEnviado = $this->enviarEmailVerificacion($emailInstitucional, $codigoVerificacion, $estudiante);

            if ($emailEnviado) {
                $_SESSION['datos_estudiante'] = [
                    'codigo_estudiante' => $estudiante['codigo_estudiante'],
                    'nombres' => $estudiante['nombres'],
                    'apellidos' => $estudiante['apellidos'],
                    'email_institucional' => $emailInstitucional
                ];
                $_SESSION['codigo_enviado'] = true;
                $_SESSION['mensaje'] = 'Código de verificación enviado correctamente';
                $_SESSION['tipo_mensaje'] = 'success';
            } else {
                throw new Exception('Error al enviar el código de verificación. Intenta nuevamente.');
            }

        } catch (Exception $e) {
            error_log("Error en enviar_codigo_vinculacionPost: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: ' . BASE_URL . '/index.php?accion=vincular');
        exit;
    }

    public function verificar_codigo_vinculacionPost() {
        try {
            if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] !== 1) {
                header('Location: ' . BASE_URL . '/index.php?accion=login');
                exit;
            }

            $codigoEstudiante = trim($_POST['codigo_estudiante'] ?? '');
            $codigoIngresado = '';

            for ($i = 1; $i <= 6; $i++) {
                $codigoIngresado .= $_POST["digit_$i"] ?? '';
            }

            if (empty($codigoEstudiante) || strlen($codigoIngresado) !== 6) {
                throw new Exception('Código incompleto. Ingresa los 6 dígitos.');
            }

            $verificacion = $this->estudianteModel->verificarCodigo($codigoEstudiante, $codigoIngresado);

            if (!$verificacion['valido']) {
                $_SESSION['error_verificacion'] = $verificacion['mensaje'];
                header('Location: ' . BASE_URL . '/index.php?accion=vincular');
                exit;
            }

            $estudiante = $this->estudianteModel->buscarPorCodigo($codigoEstudiante);
            if (!$estudiante) {
                throw new Exception('Error al obtener datos del estudiante.');
            }

            $usuarioId = $_SESSION['usuario_id'];
            $datosVinculacion = [
                'codigo_estudiante' => $codigoEstudiante,
                'nombres_completos' => $estudiante['nombres'] . ' ' . $estudiante['apellidos'],
                'email_institucional' => $estudiante['email_institucional'] ?? $this->generarEmailInstitucional($estudiante),
                'carrera' => $estudiante['carrera'] ?? null,
                'semestre' => $estudiante['semestre'] ?? null,
                'estado' => 'activo'
            ];

            $vinculado = $this->estudianteModel->vincularUsuario($usuarioId, $datosVinculacion);

            if ($vinculado) {
                $this->estudianteModel->limpiarCodigoVerificacion($codigoEstudiante);

                $this->estudianteModel->actualizarRolUsuario($usuarioId, 2);

                $_SESSION['rol_id'] = 2; 
                $_SESSION['vinculacion_exitosa'] = true;
                $_SESSION['mensaje'] = '¡Vinculación exitosa! Ahora eres parte de la comunidad UPT.';
                $_SESSION['tipo_mensaje'] = 'success';
            } else {
                throw new Exception('Error al completar la vinculación. Intenta nuevamente.');
            }

        } catch (Exception $e) {
            error_log("Error en verificar_codigo_vinculacionPost: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: ' . BASE_URL . '/index.php?accion=inicio');
        exit;
    }

    public function reenviar_codigo_vinculacionPost() {
        try {
            if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] !== 1) {
                header('Location: ' . BASE_URL . '/index.php?accion=login');
                exit;
            }

            $codigoEstudiante = trim($_POST['codigo_estudiante'] ?? '');

            if (empty($codigoEstudiante)) {
                throw new Exception('Datos de sesión perdidos. Intenta nuevamente.');
            }
            $intentosRecientes = $this->estudianteModel->contarIntentosRecientes($codigoEstudiante);
            if ($intentosRecientes >= 3) {
                throw new Exception('Has alcanzado el límite de reenvíos. Intenta en una hora.');
            }

            $_POST['codigo_estudiante'] = $codigoEstudiante;
            $this->enviar_codigo_vinculacionPost();

        } catch (Exception $e) {
            error_log("Error en reenviar_codigo_vinculacionPost: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/index.php?accion=vincular');
            exit;
        }
    }

    private function generarCodigoVerificacion() {
        return str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function generarEmailInstitucional($estudiante) {
        $nombres = strtolower($estudiante['nombres']);
        $apellidos = strtolower($estudiante['apellidos']);
        
        $primeraLetraNombre = substr($nombres, 0, 1);
        $primerApellido = explode(' ', $apellidos)[0];
        
        return $primeraLetraNombre . $primerApellido . '@virtual.upt.pe';
    }
    private function enviarEmailVerificacion($email, $codigo, $estudiante) {
        require_once BASE_PATH . '/utils/CorreoVinculacionUPT.php';
        
        $correoUPT = new CorreoVinculacionUPT();
        $nombreCompleto = $estudiante['nombres'] . ' ' . $estudiante['apellidos'];
        $codigoGenerado = $codigo;
        $enviado = $correoUPT->mtdNotificar($email, $nombreCompleto, $codigoGenerado);
        
        if ($enviado) {
            error_log("Se envio correctamente el codigo de verificación para {$email}: {$codigoGenerado}");
            return $codigoGenerado;
        }
        
        return false;
    }
}