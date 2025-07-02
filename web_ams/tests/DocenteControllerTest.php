<?php
use PHPUnit\Framework\TestCase;

if (!defined('BASE_PATH')) {
    define('BASE_PATH', realpath(__DIR__ . '/..'));
}
require_once BASE_PATH . '/config/constants.php';
require_once BASE_PATH . '/controllers/DocenteController.php';

/**
 * @covers DocenteController
 */
class DocenteControllerTest extends TestCase
{
    protected function setUp(): void
{
    // ⚠️ Esto evita warnings de headers enviados
    if (session_status() !== PHP_SESSION_ACTIVE) {
        @session_start(); // el @ evita que PHPUnit se queje si ya se enviaron headers
    }

    $_SESSION = [];
    $_POST = [];
    $_GET = [];

    // También puede prevenir errores en views
    $_SERVER['REQUEST_URI'] = '/test';
}

    protected function tearDown(): void
{
    if (ob_get_level() > 0) {
        ob_end_clean();
    }
    $_SESSION = [];
    $_POST = [];
    $_GET = [];
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
    public function testClasesAsignadasJsonConSesionValida()
{
    $_SESSION['usuario_id'] = 10;
    $_SESSION['rol_id'] = 3;
    $_GET['format'] = 'json';
    $_SERVER['HTTP_ACCEPT'] = 'application/json';

    $mockModel = $this->createMock(DocenteModel::class);
    $mockModel->method('obtenerIdDocente')->willReturn(['ID_DOCENTE' => 1]);
    $mockModel->method('obtenerClasesAsignadas')->willReturn([
        ['ID_CLASE' => 1, 'NOMBRE_CURSO' => 'Programación']
    ]);

    $controller = new DocenteController();
    $this->injectModel($controller, $mockModel);

    ob_start();
    $controller->clases_asignadas();
    $output = ob_get_clean();

    $json = json_decode($output, true);
    $this->assertTrue($json['success']);
    $this->assertEquals(1, $json['total']);
}

public function testCerrarClaseConPermisos()
{
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_SESSION['usuario_id'] = 20;
    $_SESSION['rol_id'] = 3;
    $_POST['id_clase'] = 7;

    $mockModel = $this->createMock(DocenteModel::class);
    $mockModel->method('verificarPermisosClase')->willReturn(true);
    $mockModel->method('cerrarClase')->willReturn(true);

    $controller = new DocenteController();
    $this->injectModel($controller, $mockModel);

    ob_start();
    $controller->cerrar_clase();
    $output = ob_get_clean();

    $json = json_decode($output, true);
    $this->assertTrue($json['success']);
    $this->assertStringContainsString('Clase cerrada exitosamente', $json['message']);
}
private function injectModel($controller, $mock)
{
    $ref = new ReflectionClass($controller);
    $prop = $ref->getProperty('docenteModel');
    $prop->setAccessible(true);
    $prop->setValue($controller, $mock);
}
public function testHandleConAccionValida()
{
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_SESSION['usuario_id'] = 99;
    $_SESSION['rol_id'] = 3;
    $_POST['id_clase'] = 1;

    $mockModel = $this->createMock(DocenteModel::class);
    $mockModel->method('verificarPermisosClase')->willReturn(true);
    $mockModel->method('cerrarClase')->willReturn(true);

    $controller = new DocenteController();
    $this->injectModel($controller, $mockModel);

    ob_start();
    $controller->handle('cerrar_clase');
    $output = ob_get_clean();

    $json = json_decode($output, true);
    $this->assertTrue($json['success']);
}
public function testHandleConAccionInvalida()
{
    $controller = new DocenteController();

    ob_start();
    $controller->handle('accion_que_no_existe');
    $output = ob_get_clean();

    $this->assertStringContainsString('Acción no encontrada', $output);
}
public function testCalificarEstudianteConDatosValidos()
{
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_SESSION['usuario_id'] = 30;
    $_SESSION['rol_id'] = 3;
    $_POST = [
        'id_clase' => 10,
        'id_estudiante' => 5,
        'calificacion' => 18,
        'observacion' => 'Buen trabajo'
    ];

    $mockModel = $this->createMock(DocenteModel::class);
    $mockModel->method('verificarPermisosClase')->willReturn(['ID_DOCENTE' => 1]);
    $mockModel->method('calificarEstudiante')->willReturn(true);

    $controller = new DocenteController();
    $this->injectModel($controller, $mockModel);

    ob_start();
    $controller->calificar_estudiante();
    $output = ob_get_clean();

    $json = json_decode($output, true);
    $this->assertTrue($json['success']);
    $this->assertEquals(18, $json['calificacion']);
}
public function testProcesarTomarClaseConExito()
{
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_SESSION['usuario_id'] = 40;
    $_SESSION['rol_id'] = 3;
    $_POST['id_clase'] = 15;

    $mockModel = $this->createMock(DocenteModel::class);
    $mockModel->method('obtenerIdDocente')->willReturn(['ID_DOCENTE' => 2]);
    $mockModel->method('puedeTomarClase')->willReturn(['puede_tomar' => true]);
    $mockModel->method('tomarClase')->willReturn(['success' => true]);

    $controller = new DocenteController();
    $this->injectModel($controller, $mockModel);

    ob_start();
    $controller->procesar_tomar_clase();
    $output = ob_get_clean();

    $json = json_decode($output, true);
    $this->assertTrue($json['success']);
}

}
