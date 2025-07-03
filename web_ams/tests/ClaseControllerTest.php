<?php
use PHPUnit\Framework\TestCase;
require_once BASE_PATH . '/config/constants.php';

require_once BASE_PATH . '/controllers/ClaseController.php';
require_once BASE_PATH . '/models/ClaseModel.php';
require_once BASE_PATH . '/models/Usuario.php';

/**
 * @covers ClaseController
 */
class ClaseControllerTest extends TestCase
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
}

    // 🔧 Método auxiliar para inyectar mocks en propiedades privadas
    private function setPrivateProperty($object, $property, $value)
    {
        $refProp = new ReflectionProperty($object, $property);
        $refProp->setAccessible(true);
        $refProp->setValue($object, $value);
    }

    // ✅ Test 1: inscripción exitosa
    public function testInscribirClasePostConDatosValidos()
    {
        $_SESSION['usuario_id'] = 5;
        $_POST['id_clase'] = 10;

        $mockClaseModel = $this->createMock(ClaseModel::class);
        $mockUsuarioModel = $this->createMock(Usuario::class);

        $mockUsuarioModel->method('obtenerIdEstudiante')->with(5)->willReturn(123);
        $mockClaseModel->method('inscribirEstudiante')->with(123, 10)->willReturn(true);

        $controller = new ClaseController();
        $this->setPrivateProperty($controller, 'claseModel', $mockClaseModel);
        $this->setPrivateProperty($controller, 'usuarioModel', $mockUsuarioModel);

        $ob = ob_get_level();
        ob_start();
        $controller->inscribir_clasePost();
        while (ob_get_level() > $ob) ob_end_clean();

        $this->assertEquals("¡Te has inscrito exitosamente a la clase!", $_SESSION['mensaje']);
        $this->assertEquals("success", $_SESSION['tipo_mensaje']);
    }

    // ❌ Test 2: clase inválida
    public function testInscribirClasePostConClaseInvalida()
    {
        $_SESSION['usuario_id'] = 5;
        $_POST['id_clase'] = null;

        $mockClaseModel = $this->createMock(ClaseModel::class);
        $mockUsuarioModel = $this->createMock(Usuario::class);

        $mockUsuarioModel->method('obtenerIdEstudiante')->willReturn(123);

        $controller = new ClaseController();
        $this->setPrivateProperty($controller, 'claseModel', $mockClaseModel);
        $this->setPrivateProperty($controller, 'usuarioModel', $mockUsuarioModel);

        $ob = ob_get_level();
        ob_start();
        $controller->inscribir_clasePost();
        while (ob_get_level() > $ob) ob_end_clean();

        $this->assertStringContainsString("ID de clase no válido", $_SESSION['mensaje']);
        $this->assertEquals("danger", $_SESSION['tipo_mensaje']);
    }

    // ✅ Test 3: creación exitosa de clase
    public function testCrearClasePostConDatosValidos()
    {
        $_SESSION['usuario_id'] = 8;
        $_POST = [
            'id_ciclo' => 1,
            'id_curso' => 2,
            'horario_preferido' => '08:00 - 10:00',
            'razon' => 'Necesito reforzar temas de estructuras.'
        ];

        $mockClaseModel = $this->createMock(ClaseModel::class);
        $mockUsuarioModel = $this->createMock(Usuario::class);

        $mockUsuarioModel->method('obtenerIdEstudiante')->willReturn(22);
        $mockClaseModel->method('contarClasesEstudiante')->willReturn(2);
        $mockClaseModel->method('solicitarNuevaClase')->willReturn(true);

        $controller = new ClaseController();
        $this->setPrivateProperty($controller, 'claseModel', $mockClaseModel);
        $this->setPrivateProperty($controller, 'usuarioModel', $mockUsuarioModel);

        $ob = ob_get_level();
        ob_start();
        $controller->crear_clasePost();
        while (ob_get_level() > $ob) ob_end_clean();

        $this->assertEquals("¡Nueva clase creada exitosamente! Ya estás inscrito.", $_SESSION['mensaje']);
        $this->assertEquals("success", $_SESSION['tipo_mensaje']);
    }
    public function testCrearClasePostConRazonCorta()
{
    $_SESSION['usuario_id'] = 9;
    $_POST = [
        'id_ciclo' => 1,
        'id_curso' => 2,
        'horario_preferido' => '10:00 - 12:00',
        'razon' => 'corto'
    ];

    $mockClaseModel = $this->createMock(ClaseModel::class);
    $mockUsuarioModel = $this->createMock(Usuario::class);

    $mockUsuarioModel->method('obtenerIdEstudiante')->willReturn(22);
    $mockClaseModel->method('contarClasesEstudiante')->willReturn(0);

    $controller = new ClaseController();
    $this->setPrivateProperty($controller, 'claseModel', $mockClaseModel);
    $this->setPrivateProperty($controller, 'usuarioModel', $mockUsuarioModel);

     $ob = ob_get_level();
    ob_start();
    $controller->crear_clasePost();
    while (ob_get_level() > $ob) ob_end_clean();

    $this->assertStringContainsString("La razón debe tener al menos 10 caracteres", $_SESSION['mensaje']);
    $this->assertEquals("danger", $_SESSION['tipo_mensaje']);
}

public function testCrearClasePostMaximoAlcanzado()
{
    $_SESSION['usuario_id'] = 10;
    $_POST = [
        'id_ciclo' => 1,
        'id_curso' => 2,
        'horario_preferido' => '10:00 - 12:00',
        'razon' => 'Solicito clase adicional'
    ];

    $mockClaseModel = $this->createMock(ClaseModel::class);
    $mockUsuarioModel = $this->createMock(Usuario::class);

    $mockUsuarioModel->method('obtenerIdEstudiante')->willReturn(50);
    $mockClaseModel->method('contarClasesEstudiante')->willReturn(3);

    $controller = new ClaseController();
    $this->setPrivateProperty($controller, 'claseModel', $mockClaseModel);
    $this->setPrivateProperty($controller, 'usuarioModel', $mockUsuarioModel);

     $ob = ob_get_level();
    ob_start();
    $controller->crear_clasePost();
    while (ob_get_level() > $ob) ob_end_clean();
    
    $this->assertStringContainsString("No puedes crear más clases", $_SESSION['mensaje']);
    $this->assertEquals("danger", $_SESSION['tipo_mensaje']);
}

}
