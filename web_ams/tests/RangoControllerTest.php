<?php
use PHPUnit\Framework\TestCase;

if (!defined('BASE_PATH')) {
    define('BASE_PATH', realpath(__DIR__ . '/..'));
}

require_once BASE_PATH . '/models/Usuario.php';
require_once BASE_PATH . '/config/mongodb.php';
require_once BASE_PATH . '/controllers/RangoController.php';

/**
 * @coversDefaultClass RangoController
 */
class RangoControllerTest extends TestCase
{
    private $controller;

 protected function setUp(): void
{
    // Mock de Usuario
    $this->mockUsuario = $this->createMock(Usuario::class);

    // Fake de insertOne() que devuelve un objeto con getInsertedId()
    $fakeCollection = new class {
    public function insertOne($document) {
        return new class {
            public function getInsertedId() {
                return 'mock_id';
            }
        };
    }

    public function updateMany($filter, $update) {
    return new class {
        public function getModifiedCount() {
            return 1; // Simula que se modificó un documento
        }
    };
}

};

    // Fake de database que devuelve la colección falsa
    $fakeDatabase = new class($fakeCollection) {
        private $collection;
        public function __construct($collection) {
            $this->collection = $collection;
        }
        public function selectCollection($name) {
            return $this->collection;
        }
    };

    // Mock de MongoDB que devuelve la base de datos fake
    $this->mockMongo = $this->getMockBuilder(MongoDB::class)
        ->disableOriginalConstructor()
        ->onlyMethods(['verificarConexion'])
        ->getMock();
    $this->mockMongo->method('verificarConexion')->willReturn(true);
    $this->mockMongo->database = $fakeDatabase;

    // Mock de RangoController con método de envío de correo simulado
    $this->controller = $this->getMockBuilder(RangoController::class)
        ->setConstructorArgs([$this->mockUsuario, $this->mockMongo])
        ->onlyMethods(['enviarNotificacionCorreo'])
        ->getMock();
    $this->controller->method('enviarNotificacionCorreo')->willReturn(true);
}



  private function setUsuarioMock($data): void
    {
        $this->mockUsuario->method('obtenerDatosCompletos')->willReturn($data);
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

     /**
     * @covers ::generarClaveReclamo
     */
    public function testUsuarioNoEncontrado(): void
    {
        $this->setUsuarioMock(null);

        $res = $this->controller->generarClaveReclamo(1, 'devuser', 'correo@demo.com');

        $this->assertFalse($res['success']);
        $this->assertEquals('Usuario no encontrado', $res['mensaje']);
    }

     /**
     * @covers ::generarClaveReclamo
     */
    public function testUsuarioSinPermiso(): void
    {
        $this->setUsuarioMock(['ID_ESTUDIANTE' => null, 'ID_DOCENTE' => null]);

        $res = $this->controller->generarClaveReclamo(1, 'devuser', 'correo@demo.com');

        $this->assertFalse($res['success']);
        $this->assertEquals('El usuario no tiene permisos para reclamar un rango', $res['mensaje']);
    }

    /**
     * @covers ::generarClaveReclamo
     */
    public function testNombreApellidoVacio(): void
    {
        $this->setUsuarioMock(['ID_ESTUDIANTE' => 1, 'NOMBRE' => '', 'APELLIDO' => '']);

        $res = $this->controller->generarClaveReclamo(1, 'devuser', 'correo@demo.com');

        $this->assertFalse($res['success']);
        $this->assertEquals('Faltan datos obligatorios del usuario (nombre o apellido)', $res['mensaje']);
    }

      /**
     * @covers ::generarClaveReclamo
     */
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

      /**
     * @covers ::generarClaveReclamo
     */
    public function testEmailInvalido(): void
    {
        $this->setUsuarioMock(['ID_ESTUDIANTE' => 1, 'NOMBRE' => 'Ana', 'APELLIDO' => 'Lopez']);

        $res = $this->controller->generarClaveReclamo(1, 'anita_dev', 'no_es_email');

        $this->assertFalse($res['success']);
        $this->assertEquals('Email inválido', $res['mensaje']);
    }

     /**
     * @covers ::generarClaveReclamo
     */
    public function testEmailVacio(): void
    {
        $this->setUsuarioMock(['ID_DOCENTE' => 2, 'NOMBRE' => 'Luis', 'APELLIDO' => 'Rodriguez']);

        $res = $this->controller->generarClaveReclamo(2, 'luis_dev', '');

        $this->assertFalse($res['success']);
        $this->assertEquals('El email del usuario es requerido', $res['mensaje']);
    }

     /**
     * @covers ::generarClaveReclamo
     */
    public function testDiscordUsernameVacio(): void
    {
        $this->setUsuarioMock(['ID_DOCENTE' => 2, 'NOMBRE' => 'Luis', 'APELLIDO' => 'Rodriguez']);

        $res = $this->controller->generarClaveReclamo(2, '', 'luis@example.com');

        $this->assertFalse($res['success']);
        $this->assertEquals('El username de Discord es requerido', $res['mensaje']);
    }

     /**
     * @covers ::generarClaveReclamo
     * @covers ::guardarEnMongoDB
     */
    public function testGuardarEnMongoDBFalla(): void
    {
        // Crear mock con fallo de guardado
       $mockUsuario = $this->createMock(Usuario::class);
       // Fake collection que simula insertOne() fallido
$fakeCollection = new class {
    public function insertOne($document) {
        return new class {
            public function getInsertedId() {
                return null; // fuerza error
            }
        };
    }

    public function updateMany($filter, $update) {
    return new class {
        public function getModifiedCount() {
            return 1; // o 0, si deseas simular que no se modificó nada
        }
    };
}
};

// Fake database que devuelve la colección fake
$fakeDatabase = new class($fakeCollection) {
    private $collection;
    public function __construct($collection) {
        $this->collection = $collection;
    }
    public function selectCollection($name) {
        return $this->collection;
    }
};

// Mock Mongo que tiene base de datos fake
$mockMongo = $this->getMockBuilder(MongoDB::class)
    ->disableOriginalConstructor()
    ->onlyMethods(['verificarConexion'])
    ->getMock();
$mockMongo->method('verificarConexion')->willReturn(true);
$mockMongo->database = $fakeDatabase;


        $controller = $this->getMockBuilder(RangoController::class)
            ->setConstructorArgs([$mockUsuario, $mockMongo])
            ->onlyMethods(['enviarNotificacionCorreo'])
            ->getMock();

        $controller->method('enviarNotificacionCorreo')->willReturn(true);

        $mockUsuario->method('obtenerDatosCompletos')->willReturn([
            'ID_ESTUDIANTE' => 1,
            'NOMBRE' => 'Test',
            'APELLIDO' => 'User',
            'DNI' => '87654321'
        ]);

        $res = $controller->generarClaveReclamo(1, 'testuser', 'test@example.com');

        $this->assertFalse($res['success']);
        $this->assertEquals('Error al guardar el código en la base de datos', $res['mensaje']);
    }
}
