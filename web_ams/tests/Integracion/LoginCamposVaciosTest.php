<?php
if (!defined('PHPUNIT_RUNNING')) define('PHPUNIT_RUNNING', true);
use PHPUnit\Framework\TestCase;

class LoginCamposVaciosTest extends TestCase {
    public function testLoginCamposVacios() {
        require_once __DIR__ . '/../../../web_ams/controllers/AuthController.php';
        $controller = new AuthController();

        $_POST = [
            'email' => '',
            'password' => ''
        ];

        $resultado = $controller->loginPost();
        $this->assertIsArray($resultado);
        $this->assertEquals(false, $resultado['success']);
        $this->assertEquals('Campos vacíos', $resultado['message']);
    }
}
