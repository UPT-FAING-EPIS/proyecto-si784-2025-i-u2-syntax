<?php
use PHPUnit\Framework\TestCase;

if (!defined('BASE_PATH')) {
    define('BASE_PATH', realpath(__DIR__ . '/../'));
}

require_once BASE_PATH . '/config/constants.php';
require_once BASE_PATH . '/controllers/HomeController.php';

/**
 * @coversDefaultClass HomeController
 */
class HomeControllerTest extends TestCase
{
    private $controller;

    protected function setUp(): void
    {
        $this->controller = new HomeController();

        // Simula archivos necesarios
        foreach (['home', 'mentoria'] as $vista) {
            $path = BASE_PATH . "/views/{$vista}.php";
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0777, true);
            }
            if (!file_exists($path)) {
                file_put_contents($path, "<h1>{$vista}</h1>");
            }
        }
        $_SERVER['REQUEST_URI'] = '/test';

    }

    protected function tearDown(): void
    {
        // Limpia archivos simulados
        foreach (['home', 'mentoria'] as $vista) {
            $path = BASE_PATH . "/views/{$vista}.php";
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }

    /**
     * @covers HomeController::inicioGet
     */
    public function testInicioGetMuestraVistaHomeSiExiste()
    {
        ob_start();
        $this->controller->inicioGet();
        $salida = ob_get_clean();

        $this->assertStringContainsString('<h1>home</h1>', $salida);
    }

    /**
     * @covers HomeController::inicioGet
     */
    public function testInicioGetMuestraErrorSiArchivoNoExiste()
    {
        unlink(BASE_PATH . '/views/home.php');

        ob_start();
        $this->controller->inicioGet();
        $salida = ob_get_clean();

        $this->assertStringContainsString('La página de inicio no existe', $salida);
    }

    /**
     * @covers HomeController::mostrarSeccionGet
     */
    public function testMostrarSeccionValidaCargaArchivo()
    {
        $_GET['accion'] = 'mentoria';

        ob_start();
        $this->controller->mostrarSeccionGet();
        $salida = ob_get_clean();

        $this->assertStringContainsString('<h1>mentoria</h1>', $salida);
    }

    /**
     * @covers HomeController::mostrarSeccionGet
     */
    public function testMostrarSeccionNoPermitida()
    {
        $_GET['accion'] = 'hack';

        ob_start();
        $this->controller->mostrarSeccionGet();
        $salida = ob_get_clean();

        $this->assertStringContainsString('Sección no permitida', $salida);
    }

    /**
     * @covers HomeController::handle
     */
    public function testHandleConInicioLlamaInicioGet()
    {
        ob_start();
        $this->controller->handle('inicio');
        $salida = ob_get_clean();

        $this->assertStringContainsString('<h1>home</h1>', $salida);
    }

    /**
     * @covers HomeController::handle
     */
    public function testHandleConSeccionLlamaMostrarSeccionGet()
    {
        $_GET['accion'] = 'mentoria';

        ob_start();
        $this->controller->handle('mentoria');
        $salida = ob_get_clean();

        $this->assertStringContainsString('<h1>mentoria</h1>', $salida);
    }
}
