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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->controller = new HomeController();
        $_GET = [];
        $_SERVER['REQUEST_URI'] = '/test';
    }

    /**
     * @covers HomeController::inicioGet
     */
    public function testInicioGetMuestraVistaHomeSiExiste()
    {
        ob_start();
        $this->controller->inicioGet();
        $salida = ob_get_clean();

        // Busca contenido real del home.php
        $this->assertStringContainsString('Sistema de Mentoría Académica UPT', $salida);
        $this->assertStringContainsString('Explorar Servicios', $salida);
    }

    /**
     * @covers HomeController::inicioGet
     */
    public function testInicioGetMuestraErrorSiArchivoNoExiste()
    {
        $ruta = BASE_PATH . '/views/home.php';
        $copia = $ruta . '.bk';

        if (file_exists($ruta)) {
            rename($ruta, $copia);
        }

        ob_start();
        $this->controller->inicioGet();
        $salida = ob_get_clean();

        $this->assertStringContainsString('La página de inicio no existe', $salida);

        // Restaurar vista original
        if (file_exists($copia)) {
            rename($copia, $ruta);
        }
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

        $this->assertStringContainsString('Sistema de Mentoría Académica UPT', $salida);
    }
}
