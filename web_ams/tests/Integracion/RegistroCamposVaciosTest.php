<?php
if (!defined('PHPUNIT_RUNNING')) {
    define('PHPUNIT_RUNNING', true);
}

use PHPUnit\Framework\TestCase;

class RegistroCamposVaciosTest extends TestCase {
    public function testRegistroCamposVacios() {
        require_once __DIR__ . '/../../../web_ams/controllers/AuthController.php';
        $controller = new AuthController();

        $_POST = [
            'dni' => '',
            'nombre' => '',
            'apellido' => '',
            'email' => '',
            'password' => ''
        ];

        $resultado = $controller->registroPost();

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('success', $resultado);
        $this->assertFalse($resultado['success']);
        $this->assertEquals('Todos los campos son obligatorios', $resultado['message']);
    }
}
