<?php
use PHPUnit\Framework\TestCase;

require_once BASE_PATH . '/config/constants.php';

require_once __DIR__ . '/../controllers/RangoController.php';
require_once __DIR__ . '/../models/Usuario.php';

class RangoControllerTest extends TestCase
{
    private $controller;

    protected function setUp(): void
    {
        // Instancia con mocks
        $this->controller = $this->getMockBuilder(RangoController::class)
            ->onlyMethods(['guardarEnMongoDB', 'enviarNotificacionCorreo'])
            ->getMock();

        $this->controller->method('guardarEnMongoDB')->willReturn(true);
        $this->controller->method('enviarNotificacionCorreo')->willReturn(true);
    }

    private function setUsuarioMock($data)
    {
        $mockUsuario = $this->createMock(Usuario::class);
        $mockUsuario->method('obtenerDatosCompletos')->willReturn($data);

        $ref = new ReflectionClass($this->controller);
        $prop = $ref->getProperty('usuarioModel');
        $prop->setAccessible(true);
        $prop->setValue($this->controller, $mockUsuario);
    }

    public function testGenerarClaveReclamoExitosoEstudiante()
    {
        $this->setUsuarioMock([
            'ID_ESTUDIANTE' => 1,
            'NOMBRE' => 'Carlos',
            'APELLIDO' => 'Perez',
            'DNI' => '12345678'
        ]);

        $res = $this->controller->generarClaveReclamo(1, 'carlos_dev', 'carlos@example.com');

        $this->assertTrue($res['success']);
        $this->assertArrayHasKey('codigo', $res);
    }

    public function testUsuarioNoEncontrado()
    {
        $this->setUsuarioMock(null);

        $res = $this->controller->generarClaveReclamo(1, 'devuser', 'dev@example.com');

        $this->assertFalse($res['success']);
        $this->assertEquals('Usuario no encontrado', $res['mensaje']);
    }

    public function testUsuarioSinPermiso()
    {
        $this->setUsuarioMock(['ID_ESTUDIANTE' => null, 'ID_DOCENTE' => null]);

        $res = $this->controller->generarClaveReclamo(1, 'devuser', 'dev@example.com');

        $this->assertFalse($res['success']);
        $this->assertEquals('El usuario no tiene permisos para reclamar un rango', $res['mensaje']);
    }

    public function testNombreApellidoVacio()
    {
        $this->setUsuarioMock(['ID_ESTUDIANTE' => 1, 'NOMBRE' => '', 'APELLIDO' => '']);

        $res = $this->controller->generarClaveReclamo(1, 'devuser', 'dev@example.com');

        $this->assertFalse($res['success']);
        $this->assertEquals('Faltan datos obligatorios del usuario (nombre o apellido)', $res['mensaje']);
    }

    public function testDiscordUsernameInvalido()
    {
        $this->setUsuarioMock(['ID_ESTUDIANTE' => 1, 'NOMBRE' => 'Cris', 'APELLIDO' => 'Zeta']);

        $res = $this->controller->generarClaveReclamo(1, 'invalido*', 'correo@demo.com');

        $this->assertFalse($res['success']);
        $this->assertEquals('Username de Discord inválido. Solo se permiten letras, números, puntos y guiones bajos (2-32 caracteres)', $res['mensaje']);
    }

    public function testEmailInvalido()
    {
        $this->setUsuarioMock(['ID_ESTUDIANTE' => 1, 'NOMBRE' => 'Ana', 'APELLIDO' => 'Lopez']);

        $res = $this->controller->generarClaveReclamo(1, 'anita_dev', 'no_es_email');

        $this->assertFalse($res['success']);
        $this->assertEquals('Email inválido', $res['mensaje']);
    }

    public function testEmailVacio()
    {
        $this->setUsuarioMock(['ID_DOCENTE' => 2, 'NOMBRE' => 'Luis', 'APELLIDO' => 'Rodriguez']);

        $res = $this->controller->generarClaveReclamo(2, 'luis_dev', '');

        $this->assertFalse($res['success']);
        $this->assertEquals('El email del usuario es requerido', $res['mensaje']);
    }

    public function testDiscordUsernameVacio()
    {
        $this->setUsuarioMock(['ID_DOCENTE' => 2, 'NOMBRE' => 'Luis', 'APELLIDO' => 'Rodriguez']);

        $res = $this->controller->generarClaveReclamo(2, '', 'luis@example.com');

        $this->assertFalse($res['success']);
        $this->assertEquals('El username de Discord es requerido', $res['mensaje']);
    }
}
