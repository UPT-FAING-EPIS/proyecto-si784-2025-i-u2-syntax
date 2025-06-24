<?php
require_once BASE_PATH . '/config/Database.php';

class Unidad {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function obtenerTodos() {
        $stmt = $this->pdo->prepare("SELECT * FROM unidad ORDER BY NOMBRE ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
