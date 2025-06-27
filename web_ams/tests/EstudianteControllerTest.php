<?php
use PHPUnit\Framework\TestCase;

require_once BASE_PATH . '/config/constants.php';

require_once BASE_PATH . '/controllers/EstudianteController.php';

class EstudianteControllerTest extends TestCase
{
    protected function setUp(): void
    {
        if (ob_get_level() === 0) {
            ob_start();
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
    }

    protected function tearDown(): void
    {
        ob_end_clean();
        $_SESSION = [];
    }

    public function testVerificarCodigoVinculacionPostConDatosValidos()
    {
        // Simular sesión válida
        $_SESSION['usuario_id'] = 10;
        $_SESSION['rol_id'] = 1;

        // Simular POST con 6 dígitos y código estudiante
        $_POST = [
            'codigo_estudiante' => '2020123456',
            'digit_1' => '1',
            'digit_2' => '2',
            'digit_3' => '3',
            'digit_4' => '4',
            'digit_5' => '5',
            'digit_6' => '6'
        ];

        // Crear mock del modelo
        $mockModel = $this->createMock(EstudianteModel::class);

        $mockModel->method('verificarCodigo')
            ->willReturn(['valido' => true, 'mensaje' => 'Código correcto']);

        $mockModel->method('buscarPorCodigo')
            ->willReturn([
                'codigo_estudiante' => '2020123456',
                'nombres' => 'Juan',
                'apellidos' => 'Pérez',
                'email_institucional' => 'jperez@virtual.upt.pe',
                'carrera' => 'Sistemas',
                'semestre' => 'VII'
            ]);

        $mockModel->method('vincularUsuario')->willReturn(true);
        $mockModel->method('limpiarCodigoVerificacion')->willReturn(true);
        $mockModel->method('actualizarRolUsuario')->willReturn(true);

        // Instanciar el controlador con el mock inyectado
        $controller = new EstudianteController();
        $refProp = new ReflectionProperty(EstudianteController::class, 'estudianteModel');
        $refProp->setAccessible(true);
        $refProp->setValue($controller, $mockModel);

        // Capturar headers para verificar redirección
        $this->expectOutputRegex('/.*/'); // evita fallo por falta de salida
        $controller->verificar_codigo_vinculacionPost();

        // Verificar que la sesión fue modificada correctamente
        $this->assertEquals(2, $_SESSION['rol_id']);
        $this->assertTrue($_SESSION['vinculacion_exitosa']);
        $this->assertEquals('¡Vinculación exitosa! Ahora eres parte de la comunidad UPT.', $_SESSION['mensaje']);
    }
    public function testVerificarCodigoVinculacionPostConCodigoIncompleto()
{
    $_SESSION['usuario_id'] = 10;
    $_SESSION['rol_id'] = 1;

    // Solo 4 dígitos
    $_POST = [
        'codigo_estudiante' => '2020123456',
        'digit_1' => '1',
        'digit_2' => '2',
        'digit_3' => '3',
        'digit_4' => '4'
        // faltan digit_5 y digit_6
    ];

    // Mock vacío porque no debe llegar a usar el modelo
    $mockModel = $this->createMock(EstudianteModel::class);

    $controller = new EstudianteController();
    $refProp = new ReflectionProperty(EstudianteController::class, 'estudianteModel');
    $refProp->setAccessible(true);
    $refProp->setValue($controller, $mockModel);

    // Esperamos redirección (simulada)
    $this->expectOutputRegex('/.*/');
    $controller->verificar_codigo_vinculacionPost();

    // Se espera mensaje de error en sesión
    $this->assertEquals('Código incompleto. Ingresa los 6 dígitos.', $_SESSION['error']);
}

public function testBuscarEstudiantePostConCodigoValido()
{
    $_SESSION['usuario_id'] = 10;
    $_SESSION['rol_id'] = 1;
    $_POST['codigo_estudiante'] = '2022123456';

    // Mock del modelo
    $mockModel = $this->createMock(EstudianteModel::class);
    $mockModel->method('verificarUsuarioVinculado')->willReturn(false);
    $mockModel->method('buscarPorCodigo')->willReturn([
        'codigo_estudiante' => '2022123456',
        'nombres' => 'Luis',
        'apellidos' => 'Sanchez',
        'email_institucional' => 'lsanchez@virtual.upt.pe'
    ]);

    $controller = new EstudianteController();
    $refProp = new ReflectionProperty(EstudianteController::class, 'estudianteModel');
    $refProp->setAccessible(true);
    $refProp->setValue($controller, $mockModel);

    ob_start();
    $controller->buscar_estudiantePost();
    ob_end_clean();

    // Verifica que los datos estén en sesión
    $this->assertEquals('Luis', $_SESSION['datos_estudiante']['nombres']);
    $this->assertEquals('Estudiante encontrado. Verifica tus datos antes de continuar.', $_SESSION['mensaje']);
    $this->assertEquals('success', $_SESSION['tipo_mensaje']);
}
public function testBuscarEstudiantePostCodigoYaVinculado()
{
    $_SESSION['usuario_id'] = 10;
    $_SESSION['rol_id'] = 1;
    $_POST['codigo_estudiante'] = '2022123456';

    // Mock del modelo con código ya vinculado
    $mockModel = $this->createMock(EstudianteModel::class);
    $mockModel->method('verificarUsuarioVinculado')->willReturn(true);

    $controller = new EstudianteController();
    $refProp = new ReflectionProperty(EstudianteController::class, 'estudianteModel');
    $refProp->setAccessible(true);
    $refProp->setValue($controller, $mockModel);

    ob_start();
    $controller->buscar_estudiantePost();
    ob_end_clean();

    $this->assertEquals('Este código de estudiante ya está vinculado a otra cuenta', $_SESSION['error']);
}
public function testBuscarEstudiantePostCodigoNoExiste()
{
    $_SESSION['usuario_id'] = 10;
    $_SESSION['rol_id'] = 1;
    $_POST['codigo_estudiante'] = '99999999';

    $mockModel = $this->createMock(EstudianteModel::class);
    $mockModel->method('verificarUsuarioVinculado')->willReturn(false);
    $mockModel->method('buscarPorCodigo')->willReturn(null); // Simula estudiante no encontrado

    $controller = new EstudianteController();
    $refProp = new ReflectionProperty(EstudianteController::class, 'estudianteModel');
    $refProp->setAccessible(true);
    $refProp->setValue($controller, $mockModel);

    ob_start();
    $controller->buscar_estudiantePost();
    ob_end_clean();

    $this->assertEquals('Código de estudiante no encontrado en el sistema UPT', $_SESSION['error']);
}
}
