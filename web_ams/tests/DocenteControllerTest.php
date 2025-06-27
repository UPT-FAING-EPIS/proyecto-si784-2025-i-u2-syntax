<?php
use PHPUnit\Framework\TestCase;

if (!defined('BASE_PATH')) {
    define('BASE_PATH', realpath(__DIR__ . '/..'));
}
require_once BASE_PATH . '/config/constants.php';
require_once BASE_PATH . '/controllers/DocenteController.php';

class DocenteControllerTest extends TestCase
{
    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];

        if (ob_get_level() === 0) ob_start();
    }

    protected function tearDown(): void
    {
        if (ob_get_length()) {
            ob_end_clean();
        }
        $_SESSION = [];
    }

    public function testInstanciarControlador()
    {
        $controller = new DocenteController();
        $this->assertInstanceOf(DocenteController::class, $controller);
    }

    public function testRedireccionSinSesionClasesAsignadas()
    {
        unset($_SESSION['usuario_id']);
        unset($_SESSION['rol_id']);

        $controller = new DocenteController();

        ob_start();
        $controller->clases_asignadas();
        ob_end_clean();

        $headers = xdebug_get_headers(); // si usas Xdebug
        $locationHeader = array_filter($headers, fn($h) => str_starts_with($h, 'Location:'));
        $this->assertNotEmpty($locationHeader, 'Debe redirigir sin sesión activa');
    }

    public function testCerrarClaseMetodoIncorrecto()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SESSION['usuario_id'] = 5;
        $_SESSION['rol_id'] = 3;

        $controller = new DocenteController();

        ob_start();
        $controller->cerrar_clase();
        $output = ob_get_clean();

        $json = json_decode($output, true);
        $this->assertFalse($json['success']);
        $this->assertEquals('Método no permitido', $json['message']);
    }

    public function testEmpezarClaseSinSesion()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        unset($_SESSION['usuario_id']);
        unset($_SESSION['rol_id']);

        $controller = new DocenteController();

        ob_start();
        $controller->empezar_clase();
        $output = ob_get_clean();

        $json = json_decode($output, true);
        $this->assertFalse($json['success']);
        $this->assertEquals('No autorizado', $json['message']);
    }

    public function testObtenerEstudiantesClaseSinId()
    {
        $_SESSION['usuario_id'] = 10;
        $_SESSION['rol_id'] = 3;

        unset($_GET['id_clase']);
        $controller = new DocenteController();

        ob_start();
        $controller->obtener_estudiantes_clase();
        $output = ob_get_clean();

        $json = json_decode($output, true);
        $this->assertArrayHasKey('error', $json);
        $this->assertEquals('ID de clase requerido', $json['error']);
    }
}
