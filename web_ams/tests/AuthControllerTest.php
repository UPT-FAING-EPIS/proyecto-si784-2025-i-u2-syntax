<?php
use PHPUnit\Framework\TestCase;


define('BASE_PATH', realpath(__DIR__ . '/..'));
require_once BASE_PATH . '/config/constants.php';
require_once BASE_PATH . '/controllers/AuthController.php';

/**
 * @covers AuthController
 */

class AuthControllerTest extends TestCase
{
    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION = [];
        $_POST = [];
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
        $_POST = [];
    }

      /**
     * @covers AuthController::loginPost
     */

    public function testLoginPostConCredencialesValidas()
    {
        $_POST['email'] = 'test@example.com';
        $_POST['password'] = '123456';

        $mockUsuario = $this->createMock(Usuario::class);
        $mockUsuario->method('verificarCredenciales')->willReturn([
            'ID_USUARIO' => 1,
            'ID_ROL' => 2,
            'ROL' => 'Estudiante'
        ]);

        $controller = new AuthController();
        $this->injectModel($controller, $mockUsuario, 'Usuario');

        ob_start();
        $controller->loginPost();
        ob_end_clean();

        $this->assertEquals(1, $_SESSION['usuario_id']);
        $this->assertEquals(2, $_SESSION['rol_id']);
        $this->assertEquals('Estudiante', $_SESSION['rol_nombre']);
    }

      /**
     * @covers AuthController::loginPost
     */

    public function testLoginPostConCredencialesInvalidas()
    {
        $_POST['email'] = 'wrong@example.com';
        $_POST['password'] = 'incorrecto';

        $mockUsuario = $this->createMock(Usuario::class);
        $mockUsuario->method('verificarCredenciales')->willReturn(false);

        $controller = new AuthController();
        $this->injectModel($controller, $mockUsuario, 'Usuario');

        ob_start();
        $controller->loginPost();
        $output = ob_get_clean();

        $this->assertStringContainsString('Credenciales incorrectas', $output);
    }

      /**
     * @covers AuthController::registroPost
     */
    public function testRegistroPostConDatosValidos()
    {
        $_POST = [
            'dni' => '12345678',
            'nombre' => 'Juan',
            'apellido' => 'Perez',
            'email' => 'juan@test.com',
            'password' => 'clave123'
        ];

        $mockUsuario = $this->createMock(Usuario::class);
        $mockUsuario->method('registrarUsuario')->willReturn(42);
        $mockUsuario->method('buscarPorCorreo')->willReturn([
            'ID_ROL' => 2,
            'ROL' => 'Estudiante'
        ]);

        $controller = new AuthController();
        $this->injectModel($controller, $mockUsuario, 'Usuario');

        ob_start();
        $controller->registroPost();
        $output = ob_get_clean();

        $this->assertStringContainsString('"success":true', $output);
        $this->assertEquals(42, $_SESSION['usuario_id']);
        $this->assertEquals(2, $_SESSION['rol_id']);
        $this->assertEquals('Estudiante', $_SESSION['rol_nombre']);
    }
    /**
     * @covers AuthController::registroPost
     */
    public function testRegistroPostConFallo()
    {
        $_POST = [
            'dni' => '00000000',
            'nombre' => 'Error',
            'apellido' => 'Test',
            'email' => 'error@test.com',
            'password' => 'fail'
        ];

        $mockUsuario = $this->createMock(Usuario::class);
        $mockUsuario->method('registrarUsuario')->willReturn(false);

        $controller = new AuthController();
        $this->injectModel($controller, $mockUsuario, 'Usuario');

        ob_start();
        $controller->registroPost();
        $output = ob_get_clean();

        $this->assertStringContainsString('"success":false', $output);
        $this->assertStringContainsString('Error al registrar el usuario', $output);
    }

    private function injectModel($controller, $mock, $modelClassName)
    {
        $refClass = new ReflectionClass($controller);
        $refProp = null;

        // Buscar propiedad del tipo Usuario (sólo si se usara como propiedad privada en versiones futuras)
        foreach ($refClass->getProperties() as $prop) {
            if ($prop->getName() === 'usuarioModel' || $prop->getName() === 'usuario') {
                $refProp = $prop;
                break;
            }
        }

        if ($refProp) {
            $refProp->setAccessible(true);
            $refProp->setValue($controller, $mock);
        } else {
            // fallback: redefinir método internamente si el modelo se instancia directamente
            $controllerReflection = new ReflectionClass($controller);
            $usuarioMethod = $controllerReflection->getMethod('loginPost');
            $usuarioMethod->setAccessible(true);
        }
    }
}
