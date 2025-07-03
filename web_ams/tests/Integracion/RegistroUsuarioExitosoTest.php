<?php
if (!defined('PHPUNIT_RUNNING')) define('PHPUNIT_RUNNING', true);
use PHPUnit\Framework\TestCase;

class RegistroUsuarioExitosoTest extends TestCase {
 public function testRegistroUsuarioExitoso() {
    require_once __DIR__ . '/../../../web_ams/controllers/AuthController.php';
    $controller = new AuthController();

    $dni = (string)rand(10000000, 99999999);
    $email = 'registro' . rand(1000, 9999) . '@example.com';

    $_POST = [
        'dni' => $dni,
        'nombre' => 'Test',
        'apellido' => 'Nuevo',
        'email' => $email,
        'password' => '123456'
    ];

    $data = $controller->registroPost();

    $this->assertIsArray($data);
    $this->assertArrayHasKey('success', $data);
    $this->assertTrue($data['success']);
    $this->assertEquals('Registro exitoso', $data['message']);
}
}