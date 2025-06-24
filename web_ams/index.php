<?php
session_start();
require_once __DIR__ . '/config/constants.php';
require_once BASE_PATH . '/core/BaseController.php';

$tiempoExpiracion = 900; // 15 minutos

if (isset($_SESSION['ultimo_acceso'])) {
    $inactividad = time() - $_SESSION['ultimo_acceso'];
    if ($inactividad > $tiempoExpiracion) {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '/index.php?accion=login&reason=timeout');
        exit;
    }
}

$_SESSION['ultimo_acceso'] = time();
$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'inicio';
$usuarioId = $_SESSION['usuario_id'] ?? null;
$rolId = $_SESSION['rol_id'] ?? null;

$authActions = ['login', 'procesar_login', 'consulta_dni', 'registro', 'procesar_registro', 'cerrar'];
if (in_array($accion, $authActions)) {
    require_once BASE_PATH . '/controllers/AuthController.php';
    $auth = new AuthController();
    if ($accion === 'cerrar') {
        session_destroy();
        header('Location: ' . BASE_URL);
        exit;
    }
    $auth->handle($accion);
    exit;
}

$accionesPublicas = ['mentoria', 'mentores', 'alumnos', 'anuncios', 'faq', 'testimonios', 'inicio'];
if (in_array($accion, $accionesPublicas)) {
    require_once BASE_PATH . '/controllers/HomeController.php';
    $home = new HomeController();
    $home->handle($accion);
    exit;
}

if (!$usuarioId || !$rolId) {
    header('Location: ' . BASE_URL . '/index.php?accion=login');
    exit;
}
$accionesVinculacion = [
    'vincular',
    'buscar_estudiante', 
    'enviar_codigo_vinculacion', 
    'verificar_codigo_vinculacion', 
    'reenviar_codigo_vinculacion'
];

if (in_array($accion, $accionesVinculacion)) {
    if (!$usuarioId) {
        header('Location: ' . BASE_URL . '/index.php?accion=login');
        exit;
    }

    require_once BASE_PATH . '/controllers/EstudianteController.php';
    $estudiante = new EstudianteController();
    $estudiante->handle($accion);
    exit;
}

$mapaAcciones = [
    'empezar_clase' => ['DocenteController', [3, 4]],
    'solicitar_clase' => ['ClaseController', [2]],
    'mis_clases' => ['EstudianteController', [2]],
    'clases_asignadas' => ['DocenteController', [3]],
    'tomar_clases' => ['DocenteController', [3, 4]],
    'procesar_tomar_clase' => ['DocenteController', [3, 4]],
    'info_clase_disponible' => ['DocenteController', [3, 4]],
    'estadisticas_docente' => ['DocenteController', [3, 4]],
    'alumnos' => ['DocenteController', [3]] ,
    'reportes' => ['DocenteController', [3]],
];

if (isset($mapaAcciones[$accion])) {
    [$controladorNombre, $rolesPermitidos] = $mapaAcciones[$accion];
    if (in_array($rolId, $rolesPermitidos)) {
        require_once BASE_PATH . "/controllers/{$controladorNombre}.php";
        $ctrl = new $controladorNombre();
        $ctrl->handle($accion);
        exit;
    } else {
        http_response_code(403);
        echo "<h2>Acción no permitida para tu rol.</h2>";
        exit;
    }
}

$carpetasPorRol = [1 => 'usuario', 2 => 'estudiante', 3 => 'docente', 4 => 'administrador'];
$carpeta = $carpetasPorRol[$rolId] ?? null;

if ($carpeta) {
    $ruta = BASE_PATH . "/views/$carpeta/$accion.php";
    $rutaComun = BASE_PATH . "/views/usuario/$accion.php";
    if (file_exists($ruta)) {
        include $ruta;
        exit;
    } elseif (file_exists($rutaComun)) {
        include $rutaComun;
        exit;
    }
}

http_response_code(404);
echo "<h2>Página no encontrada</h2>";
exit;