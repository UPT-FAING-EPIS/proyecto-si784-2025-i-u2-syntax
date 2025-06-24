<?php
require_once '../config/Database.php';

class Asistencia {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function guardarAsistencia($claseId, $asistencias) {
        foreach ($asistencias as $idEstudiante => $estado) {
            $stmt = $this->pdo->prepare("INSERT INTO asistencia (ID_ESTUDIANTE, ID_CLASE, FECHA, ESTADO)
                                         VALUES (?, ?, NOW(), ?)");
            $stmt->execute([$idEstudiante, $claseId, $estado]);
        }
    }

    public function obtenerAsistenciaPorClase($claseId) {
    $stmt = $this->pdo->prepare("SELECT E.NOMBRE, E.APELLIDO, A.FECHA, A.ESTADO
                                 FROM asistencia A
                                 JOIN estudiante E ON A.ID_ESTUDIANTE = E.ID_ESTUDIANTE
                                 WHERE A.ID_CLASE = ?
                                 ORDER BY A.FECHA DESC");
    $stmt->execute([$claseId]);
    return $stmt->fetchAll();
}

}
