<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../controllers/SumaController.php';

class SumaControllerTest extends TestCase {
    public function testSumarDosNumeros() {
        $controller = new SumaController();
        $this->assertEquals(5, $controller->sumar(2, 3));
    }
}