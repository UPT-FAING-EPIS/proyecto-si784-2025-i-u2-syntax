<?php
require_once '../config/Database.php';

class Comentario {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function registrarComentario($docenteId, $estudianteId, $puntuacion) {
        $stmt = $this->pdo->prepare("INSERT INTO comentario (PUNTUACION, ID_DOCENTE, ID_ESTUDIANTE) VALUES (?, ?, ?)");
        $stmt->execute([$puntuacion, $docenteId, $estudianteId]);
    }

    public function obtenerComentariosPorEstudiante($estudianteId) {
        $stmt = $this->pdo->prepare("SELECT * FROM comentario WHERE ID_ESTUDIANTE = ?");
        $stmt->execute([$estudianteId]);
        return $stmt->fetchAll();
    }
}
