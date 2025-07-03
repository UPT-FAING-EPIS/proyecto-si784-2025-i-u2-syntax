<?php
if (!defined('PHPUNIT_RUNNING')) {
    define('PHPUNIT_RUNNING', true);
}

use PHPUnit\Framework\TestCase;

class LoginUsuarioTest extends TestCase {

    public function testLoginUsuarioExitoso() {
        $dni = (string)rand(10000000, 99999999);
        $email = 'test.login' . rand(1000, 9999) . '@example.com';
        $password = '123456';

        require_once __DIR__ . '/../../../web_ams/controllers/AuthController.php';
        require_once BASE_PATH . '/models/Usuario.php';

        $controller = new AuthController();
        $model = new Usuario(); // Modelo real con acceso a la BD remota
        $controller->setUsuarioModel($model);

        // Simular registro
        $_POST = [
            'dni' => $dni,
            'nombre' => 'Login',
            'apellido' => 'Test',
            'email' => $email,
            'password' => $password
        ];
        $resRegistro = $controller->registroPost();

        // Validaciones post-registro
        $this->assertIsArray($resRegistro);
        $this->assertTrue($resRegistro['success'], '❌ El registro falló: ' . json_encode($resRegistro));
        $this->assertArrayHasKey('usuario_id', $resRegistro);

        // Verificar que tenga rol asignado
        $idUsuario = $resRegistro['usuario_id'];
        $roles = $model->obtenerRolesUsuario($idUsuario);
        $this->assertIsArray($roles);
        $this->assertNotEmpty($roles, '❌ El usuario registrado no tiene roles asignados.');

        // Simular login
        $_POST = [
            'email' => $email,
            'password' => $password
        ];
        $controller->setUsuarioModel($model); // seguir usando el mismo modelo
        $resLogin = $controller->loginPost();

        // Validaciones post-login
        $this->assertIsArray($resLogin);
        if (!$resLogin['success']) {
            $this->fail('❌ Login falló: ' . json_encode($resLogin));
        }

        $this->assertTrue($resLogin['success']);
        $this->assertEquals('Inicio de sesión exitoso', $resLogin['message']);
        $this->assertArrayHasKey('usuario_id', $resLogin);
    }
}
