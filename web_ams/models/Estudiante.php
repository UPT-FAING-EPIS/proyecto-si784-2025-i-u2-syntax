<?php
require_once BASE_PATH . '/config/Database.php';

class Estudiante {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // Listar estudiantes inscritos en una clase específica
    public function listarPorClase($claseId) {
        $stmt = $this->pdo->prepare("
            SELECT E.ID_ESTUDIANTE, U.NOMBRE, U.APELLIDO
            FROM inscripcion I
            INNER JOIN estudiante E ON I.ID_ESTUDIANTE = E.ID_ESTUDIANTE
            INNER JOIN usuario U ON E.ID_USUARIO = U.ID_USUARIO
            WHERE I.ID_CLASE = ?
        ");
        $stmt->execute([$claseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
