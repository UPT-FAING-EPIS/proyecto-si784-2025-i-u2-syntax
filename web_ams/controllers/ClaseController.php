<?php
require_once BASE_PATH . '/models/ClaseModel.php';
require_once BASE_PATH . '/models/Usuario.php';
require_once __DIR__ . '/../core/BaseController.php';


class ClaseController extends BaseController {
    private $claseModel;
    private $usuarioModel;

    public function __construct() {
        $this->claseModel = new ClaseModel();
        $this->usuarioModel = new Usuario();
    }

    public function listar_claseGet() {
        try {
           if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['rol_id'], [2])) {
                if (!defined('PHPUNIT_RUNNING')) {
                    header('Location: ' . BASE_URL . 'index.php?accion=login');
                    exit;
                }
            }

            $usuarioId = $_SESSION['usuario_id'];
            $idEstudiante = $this->usuarioModel->obtenerIdEstudiante($usuarioId);

            if (!$idEstudiante) {
                $error = "No se encontró información de estudiante para este usuario.";
                $clases_disponibles = [];
                $ciclos = [];
                $cursos = [];
                $puede_solicitar = false;
                $id_estudiante = null;
            } else {
                $clases_disponibles = $this->claseModel->obtenerClasesDisponiblesParaInscripcion($idEstudiante);
                $ciclos = $this->claseModel->obtenerCiclosDisponibles();
                $cursos = $this->claseModel->obtenerCursosDisponibles();
                $total_clases = $this->claseModel->contarClasesEstudiante($idEstudiante);
                $puede_solicitar = $total_clases < 3;
                $id_estudiante = $idEstudiante;
                if (isset($_SESSION['mensaje'])) {
                    $mensaje = $_SESSION['mensaje'];
                    $tipo_mensaje = $_SESSION['tipo_mensaje'] ?? 'info';
                    unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);
                }
                
                if (isset($_SESSION['error'])) {
                    $error = $_SESSION['error'];
                    unset($_SESSION['error']);
                }
            }

            require_once BASE_PATH . '/views/estudiante/solicitar.php';
            
        } catch (Exception $e) {
            error_log("Error en listar_claseGet: " . $e->getMessage());
            $error = "Error al cargar las clases disponibles: " . $e->getMessage();
            $clases_disponibles = [];
            $ciclos = [];
            $cursos = [];
            $puede_solicitar = false;
            $id_estudiante = null;
            
            require_once BASE_PATH . '/views/estudiante/solicitar.php';
        }
    }

    public function inscribir_clasePost() {
        try {
            if (!isset($_SESSION['usuario_id'])) {
                throw new Exception("Debes iniciar sesión");
            }

            $usuarioId = $_SESSION['usuario_id'];
            $idEstudiante = $this->usuarioModel->obtenerIdEstudiante($usuarioId);
            $idClase = intval($_POST['id_clase'] ?? 0);

            if (!$idEstudiante) {
                throw new Exception("No se encontró información del estudiante");
            }

            if (!$idClase) {
                throw new Exception("ID de clase no válido");
            }

            $resultado = $this->claseModel->inscribirEstudiante($idEstudiante, $idClase);

            if ($resultado) {
                $_SESSION['mensaje'] = "¡Te has inscrito exitosamente a la clase!";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                throw new Exception("No se pudo completar la inscripción");
            }

        } catch (Exception $e) {
            if (!defined('PHPUNIT_RUNNING')) {
                error_log("Error en inscribir_clasePost: " . $e->getMessage());
            }
            $_SESSION['mensaje'] = "Error: " . $e->getMessage();
            $_SESSION['tipo_mensaje'] = "danger";
        }

        if (!defined('PHPUNIT_RUNNING')) {
    header('Location: ' . BASE_URL . '/index.php?accion=listar_clase');
    exit;
}
    }

    public function crear_clasePost() {
        try {
            if (!isset($_SESSION['usuario_id'])) {
                throw new Exception("Debes iniciar sesión");
            }

            $usuarioId = $_SESSION['usuario_id'];
            $idEstudiante = $this->usuarioModel->obtenerIdEstudiante($usuarioId);

            if (!$idEstudiante) {
                throw new Exception("No se encontró información del estudiante");
            }

            $totalClases = $this->claseModel->contarClasesEstudiante($idEstudiante);
            if ($totalClases >= 3) {
                throw new Exception("No puedes crear más clases. Ya tienes el máximo permitido (3).");
            }

            $idCiclo = intval($_POST['id_ciclo'] ?? 0);
            $idCurso = intval($_POST['id_curso'] ?? 0);
            $horarioPreferido = trim($_POST['horario_preferido'] ?? '');
            $razon = trim($_POST['razon'] ?? '');

            if (!$idCiclo) {
                throw new Exception("Debes seleccionar un ciclo");
            }

            if (!$idCurso) {
                throw new Exception("Debes seleccionar un curso");
            }

            if (!$horarioPreferido) {
                throw new Exception("Debes seleccionar un horario preferido");
            }

            if (strlen($razon) < 10) {
                throw new Exception("La razón debe tener al menos 10 caracteres");
            }

           if (!defined('PHPUNIT_RUNNING')) {
            error_log("Creando clase - Estudiante: $idEstudiante, Ciclo: $idCiclo, Curso: $idCurso, Horario: $horarioPreferido");
        }

            $resultado = $this->claseModel->solicitarNuevaClase(
                $idEstudiante, 
                $idCiclo, 
                $idCurso, 
                $horarioPreferido, 
                $razon
            );

            if ($resultado) {
                $_SESSION['mensaje'] = "¡Nueva clase creada exitosamente! Ya estás inscrito.";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                throw new Exception("No se pudo crear la clase. Inténtalo más tarde.");
            }

        } catch (Exception $e) {
            if (!defined('PHPUNIT_RUNNING')) {
                error_log("Error en crear_clasePost: " . $e->getMessage());
                error_log("POST data: " . print_r($_POST, true));
            }
            $_SESSION['mensaje'] = "Error: " . $e->getMessage();
            $_SESSION['tipo_mensaje'] = "danger";
        }

       if (!defined('PHPUNIT_RUNNING')) {
            header('Location: ' . BASE_URL . '/index.php?accion=listar_clase');
            exit;
        }
    }

    public function solicitar_clasePost() {
        return $this->crear_clasePost();
    }
    private function validarSesionEstudiante() {
       if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['rol_id'], [2])) {
            if (!defined('PHPUNIT_RUNNING')) {
                header('Location: ' . BASE_URL . '/index.php?accion=login');
                exit;
            }
        }

        $idEstudiante = $this->usuarioModel->obtenerIdEstudiante($_SESSION['usuario_id']);
        
        if (!$idEstudiante) {
            throw new Exception("No se encontró información del estudiante");
        }

        return $idEstudiante;
    }

    private function validarLimiteClases($idEstudiante) {
        $totalClases = $this->claseModel->contarClasesEstudiante($idEstudiante);
        
        if ($totalClases >= 3) {
            throw new Exception("Ya tienes el máximo de clases permitidas (3)");
        }

        return true;
    }
}