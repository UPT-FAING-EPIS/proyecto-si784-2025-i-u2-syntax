<?php
use PHPUnit\Framework\TestCase;
require_once BASE_PATH . '/config/constants.php';

require_once BASE_PATH . '/controllers/ClaseController.php';
require_once BASE_PATH . '/models/ClaseModel.php';
require_once BASE_PATH . '/models/Usuario.php';

class ClaseControllerTest extends TestCase
{
    protected function setUp(): void
    {
        if (ob_get_level() === 0) ob_start();
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION = [];
        $_POST = [];
    }

    protected function tearDown(): void
    {
        ob_end_clean();
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

        ob_start();
        $controller->inscribir_clasePost();
        ob_end_clean();

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

        ob_start();
        $controller->inscribir_clasePost();
        ob_end_clean();

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

        ob_start();
        $controller->crear_clasePost();
        ob_end_clean();

        $this->assertEquals("¡Nueva clase creada exitosamente! Ya estás inscrito.", $_SESSION['mensaje']);
        $this->assertEquals("success", $_SESSION['tipo_mensaje']);
    }
}
