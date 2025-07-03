<?php
if (!defined('PHPUNIT_RUNNING')) {
    define('PHPUNIT_RUNNING', true);
}

use PHPUnit\Framework\TestCase;

class RegistroUsuarioTest extends TestCase {

   public function testRegistroUsuarioExitoso() {
    $_POST = [
        'dni' => (string)rand(10000000, 99999999),
        'nombre' => 'Test',
        'apellido' => 'User',
        'email' => 'test.user' . rand(1000, 9999) . '@example.com',
        'password' => '123456'
    ];

    require_once __DIR__ . '/../../../web_ams/controllers/AuthController.php';
    $controller = new AuthController();
    $data = $controller->registroPost(); // ← esto retorna el array directamente

    $this->assertIsArray($data);
    $this->assertArrayHasKey('success', $data);
    $this->assertTrue($data['success']);
    $this->assertEquals('Registro exitoso', $data['message']);
}
}
