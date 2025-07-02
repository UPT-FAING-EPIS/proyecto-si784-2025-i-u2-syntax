<?php
use PHPUnit\Framework\TestCase;
use App\Controllers\RangoController;
use App\Models\Usuario;

const TEST_EMAIL = 'dev@example.com';

/**
 * @coversDefaultClass \App\Controllers\RangoController
 */
class RangoControllerTest extends TestCase
{
    private $controller;

    protected function setUp(): void
    {
        $this->controller = $this->getMockBuilder(RangoController::class)
            ->onlyMethods(['guardarEnMongoDB', 'enviarNotificacionCorreo'])
            ->getMock();

        $this->controller->method('guardarEnMongoDB')->willReturn(true);
        $this->controller->method('enviarNotificacionCorreo')->willReturn(true);
    }

    private function setUsuarioMock($data): void
    {
        $mockUsuario = $this->createMock(Usuario::class);
        $mockUsuario->method('obtenerDatosCompletos')->willReturn($data);

        $ref = new ReflectionClass($this->controller);
        $prop = $ref->getProperty('usuarioModel');
        $prop->setAccessible(true); // NOSONAR: necesario para prueba controlada
        $prop->setValue($this->controller, $mockUsuario);
    }

    /**
     * @covers ::generarClaveReclamo
     */
    public function testGenerarClaveReclamoExitosoEstudiante(): void
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

    public function testUsuarioNoEncontrado(): void
    {
        $this->setUsuarioMock(null);

        $res = $this->controller->generarClaveReclamo(1, 'devuser', TEST_EMAIL);

        $this->assertFalse($res['success']);
        $this->assertEquals('Usuario no encontrado', $res['mensaje']);
    }

    public function testUsuarioSinPermiso(): void
    {
        $this->setUsuarioMock(['ID_ESTUDIANTE' => null, 'ID_DOCENTE' => null]);

        $res = $this->controller->generarClaveReclamo(1, 'devuser', TEST_EMAIL);

        $this->assertFalse($res['success']);
        $this->assertEquals('El usuario no tiene permisos para reclamar un rango', $res['mensaje']);
    }

    public function testNombreApellidoVacio(): void
    {
        $this->setUsuarioMock(['ID_ESTUDIANTE' => 1, 'NOMBRE' => '', 'APELLIDO' => '']);

        $res = $this->controller->generarClaveReclamo(1, 'devuser', TEST_EMAIL);

        $this->assertFalse($res['success']);
        $this->assertEquals('Faltan datos obligatorios del usuario (nombre o apellido)', $res['mensaje']);
    }

    public function testDiscordUsernameInvalido(): void
    {
        $this->setUsuarioMock(['ID_ESTUDIANTE' => 1, 'NOMBRE' => 'Cris', 'APELLIDO' => 'Zeta']);

        $res = $this->controller->generarClaveReclamo(1, 'invalido*', 'correo@demo.com');

        $this->assertFalse($res['success']);
        $this->assertEquals(
            'Username de Discord inválido. Solo se permiten letras, números, puntos y guiones bajos (2-32 caracteres)',
            $res['mensaje']
        );
    }

    public function testEmailInvalido(): void
    {
        $this->setUsuarioMock(['ID_ESTUDIANTE' => 1, 'NOMBRE' => 'Ana', 'APELLIDO' => 'Lopez']);

        $res = $this->controller->generarClaveReclamo(1, 'anita_dev', 'no_es_email');

        $this->assertFalse($res['success']);
        $this->assertEquals('Email inválido', $res['mensaje']);
    }

    public function testEmailVacio(): void
    {
        $this->setUsuarioMock(['ID_DOCENTE' => 2, 'NOMBRE' => 'Luis', 'APELLIDO' => 'Rodriguez']);

        $res = $this->controller->generarClaveReclamo(2, 'luis_dev', '');

        $this->assertFalse($res['success']);
        $this->assertEquals('El email del usuario es requerido', $res['mensaje']);
    }

    public function testDiscordUsernameVacio(): void
    {
        $this->setUsuarioMock(['ID_DOCENTE' => 2, 'NOMBRE' => 'Luis', 'APELLIDO' => 'Rodriguez']);

        $res = $this->controller->generarClaveReclamo(2, '', 'luis@example.com');

        $this->assertFalse($res['success']);
        $this->assertEquals('El username de Discord es requerido', $res['mensaje']);
    }

    public function testGuardarEnMongoDBFalla(): void
    {
        $this->setUsuarioMock([
            'ID_ESTUDIANTE' => 1,
            'NOMBRE' => 'Test',
            'APELLIDO' => 'User',
            'DNI' => '87654321'
        ]);

        $controller = $this->getMockBuilder(RangoController::class)
            ->onlyMethods(['guardarEnMongoDB', 'enviarNotificacionCorreo'])
            ->getMock();

        $controller->method('guardarEnMongoDB')->willReturn(false);
        $controller->method('enviarNotificacionCorreo')->willReturn(true);

        $this->setUsuarioMockOnController($controller, $this->getMockUsuario());

        $res = $controller->generarClaveReclamo(1, 'testuser', 'test@example.com');

        $this->assertFalse($res['success']);
        $this->assertEquals('Error al guardar el código en la base de datos', $res['mensaje']);
    }

    private function setUsuarioMockOnController($controller, $mockUsuario): void
    {
        $ref = new ReflectionClass($controller);
        $prop = $ref->getProperty('usuarioModel');
        $prop->setAccessible(true); // NOSONAR: necesario para pruebas unitarias
        $prop->setValue($controller, $mockUsuario);
    }

    private function getMockUsuario(): Usuario
    {
        $mock = $this->createMock(Usuario::class);
        $mock->method('obtenerDatosCompletos')->willReturn([
            'ID_ESTUDIANTE' => 1,
            'NOMBRE' => 'Test',
            'APELLIDO' => 'User',
            'DNI' => '87654321'
        ]);
        return $mock;
    }
}
