<?php
require_once BASE_PATH . '/core/BaseController.php';
require_once BASE_PATH . '/models/DocenteModel.php';
require_once BASE_PATH . '/config/mongodb.php';

class DocenteController extends BaseController {
    
    private $docenteModel;
    private $mongoDiscord;

    public function __construct() {
        $this->docenteModel = new DocenteModel();
        
        try {
            $this->mongoDiscord = new Mongodb();
        } catch (Exception $e) {
            $this->mongoDiscord = null;
        }
    }

    public function handle($accion) {
        switch($accion) {
            case 'clases_asignadas':
                $this->clases_asignadas();
                break;
            case 'cerrar_clase':
                $this->cerrar_clase();
                break;
            case 'empezar_clase':
                $this->empezar_clase();
                break;
            case 'obtener_estudiantes_clase':
                $this->obtener_estudiantes_clase();
                break;
            case 'calificar_estudiante':
                $this->calificar_estudiante();
                break;
            case 'ver_calificaciones':
                $this->ver_calificaciones();
                break;
            case 'estado_discord':
                $this->estado_discord();
                break;
            case 'procesar_tomar_clase':
                $this->procesar_tomar_clase();
                break;
            case 'info_clase_disponible':
                $this->info_clase_disponible();
                break;
                case 'reportes':
                $this->ver_reportes();
                break;
            default:
                http_response_code(404);
                echo "<h2>Acción no encontrada</h2>";
                break;
        }
    }

    public function clases_asignadas() {
        error_log("=== DEBUG: Inicio clases_asignadas ===");
        
        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 3) {
            header('Location: ' . BASE_URL . '/index.php?accion=login');
            exit;
        }

        try {
            error_log("Usuario ID: " . $_SESSION['usuario_id']);
            
            $docente = $this->docenteModel->obtenerIdDocente($_SESSION['usuario_id']);
            error_log("Docente encontrado: " . print_r($docente, true));

            if (!$docente) {
                throw new Exception("Docente no encontrado");
            }

            $clases = $this->docenteModel->obtenerClasesAsignadas($docente['ID_DOCENTE']);
            error_log("Clases encontradas: " . count($clases));

            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            
            $acceptsJson = isset($_SERVER['HTTP_ACCEPT']) && 
                        strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
            
            $formatJson = isset($_GET['format']) && $_GET['format'] === 'json';
            
            if ($isAjax || $acceptsJson || $formatJson) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'success' => true,
                    'data' => $clases,
                    'total' => count($clases)
                ]);
                exit;
            }

            $pageTitle = "Clases Asignadas";
            $breadcrumb = "Clases Asignadas";
            
            include BASE_PATH . '/views/docente/clases_asignadas.php';

        } catch (Exception $e) {
            error_log("ERROR: " . $e->getMessage());
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
                exit;
            }
            
            echo "<div class='alert alert-danger'>Error al cargar las clases: " . $e->getMessage() . "</div>";
        }
        
        error_log("=== DEBUG: Fin clases_asignadas ===");
    }

    public function cerrar_clase() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 3) {
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            return;
        }

        if (!isset($_POST['id_clase'])) {
            echo json_encode(['success' => false, 'message' => 'ID de clase requerido']);
            return;
        }

        try {
            $permisos = $this->docenteModel->verificarPermisosClase($_POST['id_clase'], $_SESSION['usuario_id']);

            if (!$permisos) {
                echo json_encode(['success' => false, 'message' => 'No tienes permisos para cerrar esta clase']);
                return;
            }

            $resultado = $this->docenteModel->cerrarClase($_POST['id_clase']);

            if ($resultado) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Clase cerrada exitosamente',
                    'timestamp' => date('Y-m-d H:i:s')
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo cerrar la clase']);
            }

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
        }
    }

    public function empezar_clase() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 3) {
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            return;
        }

        try {
            $idClase = $_POST['id_clase'] ?? null;
            $enlace = $_POST['enlace'] ?? null;
            $usarDiscord = isset($_POST['usar_discord']);

            if (!$idClase) {
                echo json_encode(['success' => false, 'message' => 'ID de clase requerido']);
                return;
            }

            $permisos = $this->docenteModel->verificarPermisosClase($idClase, $_SESSION['usuario_id']);

            if (!$permisos) {
                echo json_encode(['success' => false, 'message' => 'No tienes permisos para esta clase']);
                return;
            }
            
            if ($usarDiscord) {
                $enlace = $this->procesarDiscord($idClase);
                
                if (!$enlace) {
                    echo json_encode(['success' => false, 'message' => 'Error procesando Discord']);
                    return;
                }
            }

            if ($enlace && !filter_var($enlace, FILTER_VALIDATE_URL)) {
                echo json_encode(['success' => false, 'message' => 'Enlace inválido']);
                return;
            }
            
            $resultado = $this->docenteModel->iniciarClase($idClase, $enlace);

            if ($resultado) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Clase iniciada exitosamente',
                    'enlace' => $enlace,
                    'discord' => $usarDiscord,
                    'timestamp' => date('Y-m-d H:i:s')
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo iniciar la clase']);
            }

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
        }
    }

    private function procesarDiscord($idClase) {
        try {
            $infoClase = $this->docenteModel->obtenerInfoClaseParaDiscord($idClase);
            
            if (!$infoClase) {
                return false;
            }

            if (!$this->mongoDiscord) {
                return false;
            }

            $documento = [
                'tipo' => 'crear_clase_discord',
                'usuario' => (string) $_SESSION['usuario_id'],
                'parametros' => [
                    'id_clase' => (int) $infoClase['ID_CLASE'],
                    'codigo_curso' => $infoClase['CODIGO_CURSO'],
                    'nombre_curso' => $infoClase['NOMBRE_CURSO'],
                    'ciclo' => $infoClase['CICLO'],
                    'duracion_min' => 120,
                    'privado' => false
                ],
                'estado' => 'pendiente',
                'resultado' => null,
                'participantes' => 0,
                'creado_en' => new \MongoDB\BSON\UTCDateTime(),
                'procesado_en' => null
            ];

            $resultado = $this->mongoDiscord->collection->insertOne($documento);
            
            if ($resultado->getInsertedId()) {
                return "https://discord.gg/pendiente-" . substr((string)$resultado->getInsertedId(), -8);
            }
            
            return false;

        } catch (Exception $e) {
            return false;
        }
    }

    public function obtener_estudiantes_clase() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 3) {
            echo json_encode(['error' => 'No autorizado']);
            return;
        }

        if (!isset($_GET['id_clase'])) {
            echo json_encode(['error' => 'ID de clase requerido']);
            return;
        }

        try {
            $permisos = $this->docenteModel->verificarPermisosClase($_GET['id_clase'], $_SESSION['usuario_id']);

            if (!$permisos) {
                echo json_encode(['error' => 'No tienes permisos para esta clase']);
                return;
            }

            $estudiantes = $this->docenteModel->obtenerEstudiantesClase($_GET['id_clase']);
            
            echo json_encode($estudiantes);

        } catch (Exception $e) {
            echo json_encode(['error' => 'Error interno del servidor']);
        }
    }

    public function calificar_estudiante() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 3) {
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            return;
        }

        try {
            $idClase = $_POST['id_clase'] ?? null;
            $idEstudiante = $_POST['id_estudiante'] ?? null;
            $calificacion = $_POST['calificacion'] ?? null;
            $observacion = $_POST['observacion'] ?? '';

            if (!$idClase || !$idEstudiante || $calificacion === null) {
                echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
                return;
            }

            if ($calificacion < 0 || $calificacion > 20) {
                echo json_encode(['success' => false, 'message' => 'La calificación debe estar entre 0 y 20']);
                return;
            }

            $permisos = $this->docenteModel->verificarPermisosClase($idClase, $_SESSION['usuario_id']);

            if (!$permisos) {
                echo json_encode(['success' => false, 'message' => 'No tienes permisos para calificar en esta clase']);
                return;
            }

            $resultado = $this->docenteModel->calificarEstudiante(
                $permisos['ID_DOCENTE'],
                $idEstudiante,
                $idClase,
                $calificacion,
                $observacion,
                $_SESSION['usuario_id']
            );

            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Calificación guardada exitosamente',
                    'calificacion' => $calificacion
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo guardar la calificación']);
            }

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
        }
    }

    public function ver_calificaciones() {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 3) {
            header('Location: ' . BASE_URL . '/public?accion=login');
            exit;
        }

        try {
            $idClase = $_GET['clase'] ?? null;
            
            if (!$idClase) {
                throw new Exception("ID de clase requerido");
            }

            $datos = $this->docenteModel->obtenerCalificacionesClase($idClase, $_SESSION['usuario_id']);

            if (!$datos) {
                throw new Exception("No tienes permisos para ver las calificaciones de esta clase");
            }

            $clase = $datos['clase'];
            $calificaciones = $datos['calificaciones'];

            $pageTitle = "Calificaciones - " . $clase['NOMBRE_CURSO'];
            include BASE_PATH . '/views/docente/ver_calificaciones.php';

        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
        }
    }
    public function procesar_tomar_clase() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 3) {
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            return;
        }

        try {
            $docente = $this->docenteModel->obtenerIdDocente($_SESSION['usuario_id']);
            
            if (!$docente) {
                echo json_encode(['success' => false, 'message' => 'Perfil de docente no encontrado']);
                return;
            }

            $idClase = filter_input(INPUT_POST, 'id_clase', FILTER_VALIDATE_INT);
            
            if (!$idClase) {
                echo json_encode(['success' => false, 'message' => 'ID de clase no válido']);
                return;
            }

            $verificacion = $this->docenteModel->puedeTomarClase($idClase, $docente['ID_DOCENTE']);
            
            if (!$verificacion['puede_tomar']) {
                echo json_encode(['success' => false, 'message' => $verificacion['razon']]);
                return;
            }

            $resultado = $this->docenteModel->tomarClase($idClase, $docente['ID_DOCENTE']);
            
            echo json_encode($resultado);
            
        } catch (Exception $e) {
            error_log("Error en procesar_tomar_clase: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
        }
    }

    public function info_clase_disponible() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 3) {
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            return;
        }

        try {
            $idClase = filter_input(INPUT_GET, 'id_clase', FILTER_VALIDATE_INT);
            
            if (!$idClase) {
                echo json_encode(['success' => false, 'message' => 'ID de clase no válido']);
                return;
            }

            $docente = $this->docenteModel->obtenerIdDocente($_SESSION['usuario_id']);
            
            if (!$docente) {
                echo json_encode(['success' => false, 'message' => 'Perfil de docente no encontrado']);
                return;
            }

            $verificacion = $this->docenteModel->puedeTomarClase($idClase, $docente['ID_DOCENTE']);
            
            echo json_encode([
                'success' => true,
                'puede_tomar' => $verificacion['puede_tomar'],
                'razon' => $verificacion['razon'] ?? 'Disponible',
                'clase' => $verificacion['clase'] ?? null
            ]);
            
        } catch (Exception $e) {
            error_log("Error en info_clase_disponible: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error interno']);
        }
    }
    public function estado_discord() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 3) {
            echo json_encode(['error' => 'No autorizado']);
            return;
        }

        try {
            if (!$this->mongoDiscord) {
                echo json_encode(['error' => 'MongoDB no disponible']);
                return;
            }

            echo json_encode(['success' => true]);

        } catch (Exception $e) {
            echo json_encode(['error' => 'Error interno del servidor']);
        }
    }

    public function ver_reportes() {
    if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 3) {
        header('Location: ' . BASE_URL . '/index.php?accion=login');
        exit;
    }

    try {
        $docente = $this->docenteModel->obtenerIdDocente($_SESSION['usuario_id']);
        if (!$docente) {
            throw new Exception("Docente no encontrado");
        }

        $clases = $this->docenteModel->obtenerClasesAsignadas($docente['ID_DOCENTE']);
        $resumen = [];

        foreach ($clases as $clase) {
            $calificaciones = $this->docenteModel->obtenerCalificacionesClase($clase['ID_CLASE'], $_SESSION['usuario_id']);
            $notas = array_column($calificaciones['calificaciones'], 'CALIFICACION');

            $promedio = count($notas) > 0 ? round(array_sum($notas) / count($notas), 2) : '-';

            $resumen[] = [
                'curso' => $clase['NOMBRE_CURSO'],
                'ciclo' => $clase['NOMBRE_CICLO'],
                'estudiantes' => count($notas),
                'promedio' => $promedio,
            ];
        }

        $pageTitle = "Reportes de Clases";
        include BASE_PATH . '/views/docente/reportes.php';

    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Error al cargar reportes: " . $e->getMessage() . "</div>";
    }
}


    public function __destruct() {
        if ($this->mongoDiscord) {
            $this->mongoDiscord->cerrarConexion();
        }
    }
}
?>