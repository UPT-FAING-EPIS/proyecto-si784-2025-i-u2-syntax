<?php
use PHPUnit\Framework\TestCase;
if (!defined('BASE_PATH')) {
    define('BASE_PATH', realpath(__DIR__ . '/..'));
}
if (!defined('PHPUNIT_RUNNING')) {
    define('PHPUNIT_RUNNING', true);
}
require_once BASE_PATH . '/models/Usuario.php';
require_once BASE_PATH . '/config/constants.php';
require_once BASE_PATH . '/controllers/AuthController.php';

/**
 * @covers AuthController
 */

class AuthControllerTest extends TestCase
{
protected function setUp(): void
    {
        // ⚠️ Usa el operador @ para evitar que PHPUnit muestre el warning
        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }

        $_SESSION = [];
        $_POST = [];
        $_GET = [];
        $_SERVER['REQUEST_URI'] = '/test'; // Previene warnings en footer.php
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
    $output = ob_get_clean();

    // Validar que la salida esté vacía (no hubo errores visibles)
    $this->assertEmpty(trim($output), "No se esperaba salida al hacer login con credenciales válidas.");

    // Validar sesión iniciada correctamente
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

    $response = $controller->loginPost();

    $this->assertIsArray($response);
    $this->assertFalse($response['success']);
    $this->assertEquals('Credenciales incorrectas', $response['message']);
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

    $response = $controller->registroPost();

    $this->assertIsArray($response);
    $this->assertTrue($response['success']);
    $this->assertEquals('Registro exitoso', $response['message']);

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

    $response = $controller->registroPost();

    $this->assertIsArray($response);
    $this->assertFalse($response['success']);
    $this->assertEquals('Error al registrar el usuario', $response['message']);
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
        /**
     * @covers AuthController::handle
     */
    public function testHandleProcesarLogin()
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
        $controller->handle('procesar_login');
        ob_end_clean();

        $this->assertEquals(1, $_SESSION['usuario_id']);
    }

    /**
     * @covers AuthController::handle
     */
    public function testHandleAccionInvalida()
    {
        $controller = new AuthController();
        ob_start();
        $controller->handle('accion_no_existente');
        $output = ob_get_clean();

        $this->assertStringContainsString('Acción de autenticación no válida', $output);
    }
/**
 * @covers AuthController::loginGet
 */
public function testLoginGet()
{
    $controller = new AuthController();
    ob_start();
    $controller->loginGet();
    $output = ob_get_clean();

    // Verifica que se esté mostrando el formulario de login
    $this->assertStringContainsString('<form method="post" action="' . BASE_URL . '/index.php?accion=procesar_login">', $output);

    // Verifica que los campos del formulario estén presentes
    $this->assertStringContainsString('name="email"', $output);
    $this->assertStringContainsString('name="password"', $output);
    $this->assertStringContainsString('type="submit"', $output);
}

/**
 * @covers AuthController::registroGet
 */
public function testRegistroGet()
{
    $controller = new AuthController();
    ob_start();
    $controller->registroGet();
    $output = ob_get_clean();

    // Verifica que se esté mostrando el formulario de registro real
    $this->assertStringContainsString('<form id="formRegistro" method="post">', $output);

    // Verifica campos esenciales del formulario
    $this->assertStringContainsString('name="dni"', $output);
    $this->assertStringContainsString('name="email"', $output);
    $this->assertStringContainsString('name="password"', $output);
    $this->assertStringContainsString('type="submit"', $output);
}

/**
 * @covers AuthController::consultaDNI
 */
public function testConsultaDNIConDNIInvalido()
{
    $_GET['dni'] = '123'; // inválido

    $controller = new AuthController();
    ob_start();
    $controllerReflection = new ReflectionClass($controller);
    $method = $controllerReflection->getMethod('consultaDNI');
    $method->setAccessible(true);
    $method->invoke($controller);
    $output = ob_get_clean();

    $json = json_decode($output, true);
    $this->assertFalse($json['success']);
    $this->assertEquals('DNI inválido. Debe contener exactamente 8 dígitos.', $json['error']);
}

}

