<?php
if (!defined('PHPUNIT_RUNNING')) {
    define('PHPUNIT_RUNNING', true);
}

use PHPUnit\Framework\TestCase;

class LoginIncorrectoTest extends TestCase {
    public function testLoginIncorrecto() {
        require_once __DIR__ . '/../../../web_ams/controllers/AuthController.php';
        $controller = new AuthController();

        $_POST = [
            'email' => 'inexistente' . rand(1000, 9999) . '@mail.com',
            'password' => 'password_incorrecto'
        ];

        $resultado = $controller->loginPost();
        $this->assertIsArray($resultado);
        $this->assertEquals(false, $resultado['success']);
        $this->assertEquals('Credenciales incorrectas', $resultado['message']);
    }
}
