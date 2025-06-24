<?php
require_once BASE_PATH . '/config/Database.php';

class Nota {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function obtenerEstudiantesConNotas($id_clase, $id_unidad, $id_docente) {
        $stmt = $this->pdo->prepare("
            SELECT ra.ID_REGISTRO, u.NOMBRE, u.APELLIDO
            FROM registro_academico ra
            INNER JOIN estudiante e ON ra.ID_ESTUDIANTE = e.ID_ESTUDIANTE
            INNER JOIN usuario u ON e.ID_USUARIO = u.ID_USUARIO
            WHERE ra.ID_CLASE = ? AND ra.ID_UNIDAD = ? AND ra.ID_DOCENTE = ?
        ");
        $stmt->execute([$id_clase, $id_unidad, $id_docente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardar($id_registro, $id_unidad, $tipo_nota, $calificacion, $usuario_registrador, $ip, $obs = null) {
        $stmt = $this->pdo->prepare("INSERT INTO notas 
            (ID_REGISTRO, ID_UNIDAD, TIPO_NOTA, CALIFICACION, USUARIO_REGISTRADOR, IP_REGISTRADOR, OBSERVACION)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id_registro, $id_unidad, $tipo_nota, $calificacion, $usuario_registrador, $ip, $obs]);
    }
}
