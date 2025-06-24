<?php
require_once BASE_PATH . '/config/Database.php';

class ClaseModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    
    public function obtenerClasesEstudiante($id_estudiante) {
        $sql = "SELECT 
            c.ID_CLASE,
            c.HORARIO,
            c.ESTADO AS ESTADO_CLASE,
            c.FECHA_INICIO,
            c.FECHA_FIN,
            c.RAZON,
            c.CAPACIDAD,
            c.FECHA_REG,
            c.ENLACE,
            cu.CODIGO as CURSO_CODIGO,
            cu.NOMBRE as CURSO_NOMBRE,
            ci.NOMBRE as CICLO_NOMBRE,
            i.FECHA_REG as FECHA_INSCRIPCION,
            (SELECT COUNT(*) FROM inscripcion i2 WHERE i2.ID_CLASE = c.ID_CLASE) as INSCRITOS,
            CASE 
                WHEN c.ESTADO = 1 THEN 'Activa'
                WHEN c.ESTADO = 2 THEN 'En Proceso'
                WHEN c.ESTADO = 3 THEN 'Finalizado'
                WHEN c.ESTADO = 5 THEN 'Cerrado'
                ELSE 'Desconocido'
            END AS ESTADO_TEXTO,
            CASE 
                WHEN c.ESTADO = 3 OR c.ESTADO = 5 THEN 1
                ELSE 0
            END as PUEDE_CALIFICAR,
            0 as YA_CALIFICO
        FROM inscripcion i
        INNER JOIN clase c ON i.ID_CLASE = c.ID_CLASE
        INNER JOIN curso cu ON c.ID_CURSO = cu.ID_CURSO
        INNER JOIN ciclo ci ON cu.ID_CICLO = ci.ID_CICLO
        WHERE i.ID_ESTUDIANTE = ?
        ORDER BY 
            CASE 
                WHEN c.ESTADO = 2 THEN 1 
                WHEN c.ESTADO = 1 THEN 2
                WHEN c.ESTADO = 3 THEN 3 
                WHEN c.ESTADO = 5 THEN 4
                ELSE 5 
            END,
            c.FECHA_INICIO DESC";
        
        try {
            $clases = $this->db->fetchAll($sql, [$id_estudiante]);
            
            foreach ($clases as &$clase) {
                $clase['YA_CALIFICO'] = $this->verificarCalificacionExistente($id_estudiante, $clase['ID_CLASE']) ? 1 : 0;
            }
            
            return $clases;
        } catch (Exception $e) {
            error_log("ERROR en obtenerClasesEstudiante: " . $e->getMessage());
            error_log("SQL: " . $sql);
            return [];
        }
    }

    public function verificarCalificacionExistente($id_estudiante, $id_clase) {
        $sql = "SELECT COUNT(*) as total
                FROM comentario co 
                INNER JOIN registro_academico ra ON co.ID_DOCENTE = ra.ID_DOCENTE
                WHERE co.ID_ESTUDIANTE = ? AND ra.ID_CLASE = ?";
        
        try {
            $resultado = $this->db->fetchOne($sql, [$id_estudiante, $id_clase]);
            return $resultado['total'] > 0;
        } catch (Exception $e) {
            error_log("Error al verificar calificación: " . $e->getMessage());
            return false;
        }
    }

    public function calificarMentor($id_estudiante, $id_clase, $puntuacion, $comentario = '') {
        try {
            $this->db->beginTransaction();

            $sql_verificar = "SELECT 
                                c.ID_CLASE,
                                c.ESTADO,
                                c.FECHA_FIN,
                                cu.NOMBRE as CURSO_NOMBRE
                            FROM clase c
                            INNER JOIN curso cu ON c.ID_CURSO = cu.ID_CURSO
                            WHERE c.ID_CLASE = ? AND c.ESTADO = 1 AND NOW() > c.FECHA_FIN";
            
            $clase = $this->db->fetchOne($sql_verificar, [$id_clase]);
            
            if (!$clase) {
                throw new Exception("La clase no está disponible para calificación");
            }

            $sql_inscripcion = "SELECT 1 FROM inscripcion WHERE ID_ESTUDIANTE = ? AND ID_CLASE = ?";
            $inscrito = $this->db->fetchOne($sql_inscripcion, [$id_estudiante, $id_clase]);
            
            if (!$inscrito) {
                throw new Exception("No estás inscrito en esta clase");
            }

            $sql_docente = "SELECT ID_DOCENTE FROM docente WHERE ESTADO = 1 LIMIT 1";
            $docente = $this->db->fetchOne($sql_docente);
            
            if (!$docente) {
                $id_docente = 1;
            } else {
                $id_docente = $docente['ID_DOCENTE'];
            }

            if ($this->verificarCalificacionExistente($id_estudiante, $id_clase)) {
                throw new Exception("Ya has calificado esta clase");
            }

            $sql_comentario = "INSERT INTO comentario (ID_DOCENTE, ID_ESTUDIANTE, PUNTUACION, FECHA_REG) 
                              VALUES (?, ?, ?, NOW())";
            
            $this->db->execute($sql_comentario, [$id_docente, $id_estudiante, $puntuacion]);

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Error al calificar mentor: " . $e->getMessage());
            throw $e;
        }
    }

    public function obtenerClasesDisponiblesParaInscripcion($id_estudiante) {
        $sql = "SELECT 
                c.ID_CLASE,
                c.HORARIO,
                c.ESTADO,
                c.FECHA_INICIO,
                c.FECHA_FIN,
                c.CAPACIDAD,
                cu.CODIGO as CURSO_CODIGO,
                cu.NOMBRE as CURSO_NOMBRE,
                ci.NOMBRE as CICLO_NOMBRE,
                CONCAT(u.NOMBRE, ' ', u.APELLIDO) as MENTOR_NOMBRE,
                (SELECT COUNT(*) FROM inscripcion i WHERE i.ID_CLASE = c.ID_CLASE) as INSCRITOS,
                CASE 
                    WHEN (SELECT COUNT(*) FROM inscripcion i WHERE i.ID_CLASE = c.ID_CLASE) < c.CAPACIDAD 
                    THEN 1
                    ELSE 0
                END as DISPONIBLE
            FROM clase c
            INNER JOIN curso cu ON c.ID_CURSO = cu.ID_CURSO
            INNER JOIN ciclo ci ON cu.ID_CICLO = ci.ID_CICLO
            LEFT JOIN registro_academico ra ON c.ID_CLASE = ra.ID_CLASE
            LEFT JOIN docente d ON ra.ID_DOCENTE = d.ID_DOCENTE
            LEFT JOIN usuario u ON d.ID_USUARIO = u.ID_USUARIO
            WHERE c.ESTADO = 1 
              AND c.FECHA_INICIO > NOW()
              AND c.ID_CLASE NOT IN (
                  SELECT i.ID_CLASE 
                  FROM inscripcion i 
                  WHERE i.ID_ESTUDIANTE = ?
              )
            GROUP BY c.ID_CLASE
            ORDER BY c.FECHA_INICIO ASC";
        
        return $this->db->fetchAll($sql, [$id_estudiante]);
    }

    public function obtenerClasesDisponibles($filtros = []) {
        $sql = "SELECT 
            c.ID_CLASE,
            c.HORARIO,
            c.ESTADO,
            c.FECHA_INICIO,
            c.FECHA_FIN,
            c.CAPACIDAD,
            cu.CODIGO as CURSO_CODIGO,
            cu.NOMBRE as CURSO_NOMBRE,
            ci.NOMBRE as CICLO_NOMBRE,
            (SELECT COUNT(*) FROM inscripcion i WHERE i.ID_CLASE = c.ID_CLASE) as INSCRITOS,
            CASE 
                WHEN (SELECT COUNT(*) FROM inscripcion i WHERE i.ID_CLASE = c.ID_CLASE) < c.CAPACIDAD 
                     AND c.ESTADO = 1 
                     AND c.FECHA_INICIO > NOW() 
                THEN 1
                ELSE 0
            END as DISPONIBLE
        FROM clase c
        INNER JOIN curso cu ON c.ID_CURSO = cu.ID_CURSO
        INNER JOIN ciclo ci ON cu.ID_CICLO = ci.ID_CICLO
        WHERE c.ESTADO = 1 AND c.FECHA_INICIO > NOW()";

        $params = [];
        
        if (!empty($filtros['ciclo'])) {
            $sql .= " AND ci.ID_CICLO = ?";
            $params[] = $filtros['ciclo'];
        }
        
        if (!empty($filtros['curso'])) {
            $sql .= " AND cu.NOMBRE LIKE ?";
            $params[] = '%' . $filtros['curso'] . '%';
        }

        $sql .= " ORDER BY c.FECHA_INICIO ASC";
        
        try {
            return $this->db->fetchAll($sql, $params);
        } catch (Exception $e) {
            error_log("Error al obtener clases disponibles: " . $e->getMessage());
            return [];
        }
    }

    public function inscribirEstudiante($id_estudiante, $id_clase) {
        try {
            $this->db->beginTransaction();

            $sql_verificar = "SELECT 
                                c.ID_CLASE,
                                c.CAPACIDAD,
                                c.ESTADO,
                                c.FECHA_INICIO,
                                (SELECT COUNT(*) FROM inscripcion i WHERE i.ID_CLASE = c.ID_CLASE) as INSCRITOS
                            FROM clase c
                            WHERE c.ID_CLASE = ? AND c.ESTADO = 1 AND c.FECHA_INICIO > NOW()";
            
            $clase = $this->db->fetchOne($sql_verificar, [$id_clase]);
            
            if (!$clase) {
                throw new Exception("La clase no está disponible para inscripción");
            }

            if ($clase['INSCRITOS'] >= $clase['CAPACIDAD']) {
                throw new Exception("La clase ya alcanzó su capacidad máxima");
            }

            $sql_verificar_inscripcion = "SELECT 1 FROM inscripcion WHERE ID_ESTUDIANTE = ? AND ID_CLASE = ?";
            $ya_inscrito = $this->db->fetchOne($sql_verificar_inscripcion, [$id_estudiante, $id_clase]);
            
            if ($ya_inscrito) {
                throw new Exception("Ya estás inscrito en esta clase");
            }

            $sql_inscribir = "INSERT INTO inscripcion (ID_CLASE, ID_ESTUDIANTE, FECHA_REG) VALUES (?, ?, NOW())";
            $this->db->execute($sql_inscribir, [$id_clase, $id_estudiante]);

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Error al inscribir estudiante: " . $e->getMessage());
            throw $e;
        }
    }

    public function obtenerCursosDisponibles() {
        $sql = "SELECT 
                    cu.ID_CURSO,
                    cu.CODIGO,
                    cu.NOMBRE,
                    cu.ID_CICLO,
                    ci.NOMBRE as CICLO_NOMBRE
                FROM curso cu
                INNER JOIN ciclo ci ON cu.ID_CICLO = ci.ID_CICLO
                ORDER BY ci.NOMBRE, cu.NOMBRE";
        
        return $this->db->fetchAll($sql);
    }

    public function contarClasesEstudiante($id_estudiante) {
        $sql = "SELECT COUNT(*) as total 
                FROM inscripcion i
                INNER JOIN clase c ON i.ID_CLASE = c.ID_CLASE
                WHERE i.ID_ESTUDIANTE = ? AND c.ESTADO = 1";
        
        $resultado = $this->db->fetchOne($sql, [$id_estudiante]);
        return $resultado['total'] ?? 0;
    }

    public function solicitarNuevaClase($id_estudiante, $id_ciclo, $id_curso, $horario_preferido, $razon) {
        try {

            $horarios_map = [
                'Mañana' => 'Lunes 08:00-10:00',
                'Tarde' => 'Lunes 14:00-16:00', 
                'Noche' => 'Lunes 18:00-20:00'
            ];
            $horario_completo = $horarios_map[$horario_preferido] ?? 'Lunes 14:00-16:00';

            $sql = "CALL sp_crear_clase_con_inscripcion(?, ?, ?, ?)";
            $this->db->execute($sql, [$id_estudiante, $id_curso, $horario_completo, $razon]);

            return true;

        } catch (Exception $e) {
            error_log("Error al ejecutar el procedimiento: " . $e->getMessage());
            throw $e;
        }
    }

    public function obtenerCiclosDisponibles() {
        $sql = "SELECT ci.ID_CICLO, ci.NOMBRE
                FROM ciclo ci
                WHERE ci.ID_SEMESTRE = 1";
        
        return $this->db->fetchAll($sql);
    }

    public function obtenerNotasEstudiante($id_estudiante, $id_clase = null) {
        $sql = "SELECT 
                    n.ID_NOTAS,
                    n.TIPO_NOTA,
                    n.CALIFICACION,
                    n.FECHA_REG,
                    n.OBSERVACION,
                    u.NOMBRE as UNIDAD_NOMBRE,
                    cu.NOMBRE as CURSO_NOMBRE,
                    cu.CODIGO as CURSO_CODIGO,
                    c.ID_CLASE
                FROM notas n
                INNER JOIN registro_academico ra ON n.ID_REGISTRO = ra.ID_REGISTRO
                INNER JOIN unidad u ON n.ID_UNIDAD = u.ID_UNIDAD
                INNER JOIN clase c ON ra.ID_CLASE = c.ID_CLASE
                INNER JOIN curso cu ON c.ID_CURSO = cu.ID_CURSO
                WHERE ra.ID_ESTUDIANTE = ?";

        $params = [$id_estudiante];
        
        if ($id_clase) {
            $sql .= " AND ra.ID_CLASE = ?";
            $params[] = $id_clase;
        }

        $sql .= " ORDER BY n.FECHA_REG DESC";
        
        try {
            return $this->db->fetchAll($sql, $params);
        } catch (Exception $e) {
            error_log("Error al obtener notas: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerClasePorId($id_clase) {
        $sql = "SELECT 
                    c.*,
                    cu.CODIGO as CURSO_CODIGO,
                    cu.NOMBRE as CURSO_NOMBRE,
                    ci.NOMBRE as CICLO_NOMBRE,
                    COALESCE(a.NOMBRE, 'Virtual') as AULA_NOMBRE
                FROM clase c
                INNER JOIN curso cu ON c.ID_CURSO = cu.ID_CURSO
                INNER JOIN ciclo ci ON cu.ID_CICLO = ci.ID_CICLO
                LEFT JOIN aula a ON c.ID_AULA = a.ID_AULA
                WHERE c.ID_CLASE = ?";
        
        try {
            return $this->db->fetchOne($sql, [$id_clase]);
        } catch (Exception $e) {
            error_log("Error al obtener clase por ID: " . $e->getMessage());
            return null;
        }
    }

    public function obtenerAsistenciaEstudiante($id_estudiante, $id_clase = null) {
        $sql = "SELECT 
                    a.FECHA,
                    a.ESTADO,
                    c.ID_CLASE,
                    cu.NOMBRE as CURSO_NOMBRE,
                    cu.CODIGO as CURSO_CODIGO,
                    c.HORARIO
                FROM asistencia a
                INNER JOIN clase c ON a.ID_CLASE = c.ID_CLASE
                INNER JOIN curso cu ON c.ID_CURSO = cu.ID_CURSO
                WHERE a.ID_ESTUDIANTE = ?";

        $params = [$id_estudiante];
        
        if ($id_clase) {
            $sql .= " AND a.ID_CLASE = ?";
            $params[] = $id_clase;
        }

        $sql .= " ORDER BY a.FECHA DESC";
        
        return $this->db->fetchAll($sql, $params);
    }

    public function obtenerEstadisticasEstudiante($id_estudiante) {
        $sql_stats = "SELECT 
                        COUNT(DISTINCT i.ID_CLASE) as TOTAL_CLASES,
                        COUNT(DISTINCT CASE 
                            WHEN c.ESTADO = 1 AND NOW() > c.FECHA_FIN 
                            THEN i.ID_CLASE 
                        END) as CLASES_FINALIZADAS,
                        COUNT(DISTINCT CASE 
                            WHEN c.ESTADO = 1 AND NOW() BETWEEN c.FECHA_INICIO AND c.FECHA_FIN 
                            THEN i.ID_CLASE 
                        END) as CLASES_ACTIVAS,
                        COUNT(DISTINCT co.ID_COMENTARIO) as MENTORES_CALIFICADOS
                    FROM inscripcion i
                    INNER JOIN clase c ON i.ID_CLASE = c.ID_CLASE
                    LEFT JOIN registro_academico ra ON i.ID_CLASE = ra.ID_CLASE AND i.ID_ESTUDIANTE = ra.ID_ESTUDIANTE
                    LEFT JOIN comentario co ON ra.ID_DOCENTE = co.ID_DOCENTE AND i.ID_ESTUDIANTE = co.ID_ESTUDIANTE
                    WHERE i.ID_ESTUDIANTE = ?";

        $estadisticas = $this->db->fetchOne($sql_stats, [$id_estudiante]);

        $sql_promedio = "SELECT AVG(n.CALIFICACION) as PROMEDIO_NOTAS
                        FROM notas n
                        INNER JOIN registro_academico ra ON n.ID_REGISTRO = ra.ID_REGISTRO
                        WHERE ra.ID_ESTUDIANTE = ?";

        $promedio = $this->db->fetchOne($sql_promedio, [$id_estudiante]);
        $estadisticas['PROMEDIO_NOTAS'] = $promedio['PROMEDIO_NOTAS'] ?? 0;

        return $estadisticas;
    }

    public function desinscribirEstudiante($id_estudiante, $id_clase) {
        try {
            $sql_verificar = "SELECT 
                                i.ID_ESTUDIANTE,
                                c.FECHA_INICIO,
                                c.ESTADO
                            FROM inscripcion i
                            INNER JOIN clase c ON i.ID_CLASE = c.ID_CLASE
                            WHERE i.ID_ESTUDIANTE = ? AND i.ID_CLASE = ? 
                            AND c.FECHA_INICIO > NOW() AND c.ESTADO = 1";
            
            $inscripcion = $this->db->fetchOne($sql_verificar, [$id_estudiante, $id_clase]);
            
            if (!$inscripcion) {
                throw new Exception("No puedes desinscribirte de esta clase");
            }

            $sql_desinscribir = "DELETE FROM inscripcion WHERE ID_ESTUDIANTE = ? AND ID_CLASE = ?";
            $resultado = $this->db->execute($sql_desinscribir, [$id_estudiante, $id_clase]);
            
            return $resultado > 0;

        } catch (Exception $e) {
            error_log("Error al desinscribir estudiante: " . $e->getMessage());
            throw $e;
        }
    }
}