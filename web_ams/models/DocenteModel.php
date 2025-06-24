<?php
require_once BASE_PATH . '/config/Database.php';

class DocenteModel {
    
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function obtenerIdDocente($usuarioId) {
        try {
            $resultado = $this->db->fetchOne("
                SELECT d.ID_DOCENTE, u.NOMBRE, u.APELLIDO, u.EMAIL
                FROM docente d
                INNER JOIN usuario u ON d.ID_USUARIO = u.ID_USUARIO 
                WHERE d.ID_USUARIO = ? AND d.ESTADO = 1
            ", [$usuarioId]);
            
            return $resultado;
        } catch (Exception $e) {
            error_log("Error en obtenerIdDocente: " . $e->getMessage());
            return false;
        }
    }
    public function obtenerClasesAsignadas($idDocente) {
        try {
            $clases = $this->db->fetchAll("
                SELECT DISTINCT
                    c.ID_CLASE,
                    c.HORARIO,
                    c.ESTADO as ESTADO_CLASE,
                    c.FECHA_INICIO,
                    c.FECHA_FIN,
                    c.CAPACIDAD,
                    c.ENLACE,
                    c.RAZON,
                    cu.NOMBRE as NOMBRE_CURSO,
                    cu.CODIGO as CODIGO_CURSO,
                    ci.NOMBRE as NOMBRE_CICLO,
                    sa.CODIGO as SEMESTRE,
                    COALESCE(COUNT(DISTINCT i.ID_ESTUDIANTE), 0) as PARTICIPANTES,
                    cu.ID_CURSO,
                    ci.ID_CICLO,
                    sa.ID_SEMESTRE,
                    -- Indicadores adicionales
                    CASE 
                        WHEN c.FECHA_INICIO > NOW() THEN 'programada'
                        WHEN c.FECHA_INICIO <= NOW() AND (c.FECHA_FIN IS NULL OR c.FECHA_FIN > NOW()) AND c.ESTADO = 1 THEN 'en_curso'
                        WHEN c.FECHA_FIN <= NOW() OR c.ESTADO = 0 THEN 'finalizada'
                        ELSE 'indefinida'
                    END as ESTADO_TEMPORAL
                FROM registro_academico ra
                INNER JOIN docente d ON ra.ID_DOCENTE = d.ID_DOCENTE
                INNER JOIN clase c ON ra.ID_CLASE = c.ID_CLASE
                INNER JOIN curso cu ON c.ID_CURSO = cu.ID_CURSO
                INNER JOIN ciclo ci ON cu.ID_CICLO = ci.ID_CICLO
                INNER JOIN semestre_academico sa ON ci.ID_SEMESTRE = sa.ID_SEMESTRE
                LEFT JOIN inscripcion i ON c.ID_CLASE = i.ID_CLASE
                WHERE d.ID_DOCENTE = ? AND d.ESTADO = 1
                GROUP BY 
                    c.ID_CLASE, c.HORARIO, c.ESTADO, c.FECHA_INICIO, c.FECHA_FIN, 
                    c.CAPACIDAD, c.ENLACE, c.RAZON, cu.NOMBRE, cu.CODIGO, 
                    ci.NOMBRE, sa.CODIGO, cu.ID_CURSO, ci.ID_CICLO, sa.ID_SEMESTRE
                ORDER BY 
                    c.ESTADO DESC, -- Clases activas primero
                    c.FECHA_INICIO DESC, 
                    cu.NOMBRE ASC
            ", [$idDocente]);

            foreach ($clases as &$clase) {
                $clase['PORCENTAJE_OCUPACION'] = $clase['CAPACIDAD'] > 0 
                    ? round(($clase['PARTICIPANTES'] / $clase['CAPACIDAD']) * 100, 1) 
                    : 0;
                if ($clase['FECHA_INICIO']) {
                    $clase['FECHA_INICIO_FORMATEADA'] = date('d/m/Y H:i', strtotime($clase['FECHA_INICIO']));
                }
                if ($clase['FECHA_FIN']) {
                    $clase['FECHA_FIN_FORMATEADA'] = date('d/m/Y H:i', strtotime($clase['FECHA_FIN']));
                }
            }

            return $clases;
            
        } catch (Exception $e) {
            error_log("Error en obtenerClasesAsignadas: " . $e->getMessage());
            return [];
        }
    }

    public function verificarPermisosClase($idClase, $usuarioId) {
        $resultado = $this->db->fetchOne("
            SELECT COUNT(*) as count, d.ID_DOCENTE
            FROM registro_academico ra
            INNER JOIN docente d ON ra.ID_DOCENTE = d.ID_DOCENTE
            WHERE ra.ID_CLASE = ? AND d.ID_USUARIO = ?
        ", [$idClase, $usuarioId]);

        return $resultado['count'] > 0 ? $resultado : false;
    }

    public function cerrarClase($idClase) {
        try {
            $this->db->beginTransaction();
            
            $filasAfectadas = $this->db->execute("
                UPDATE clase 
                SET ESTADO = 0, FECHA_FIN = NOW() 
                WHERE ID_CLASE = ?
            ", [$idClase]);

            if ($filasAfectadas > 0) {
                $this->db->commit();
                return true;
            } else {
                $this->db->rollback();
                return false;
            }
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    }

    public function iniciarClase($idClase, $enlace) {
        try {
            $this->db->beginTransaction();
            
            $filasAfectadas = $this->db->execute("
                UPDATE clase 
                SET ENLACE = ?, FECHA_INICIO = NOW() 
                WHERE ID_CLASE = ?
            ", [$enlace, $idClase]);

            if ($filasAfectadas > 0) {
                $this->db->commit();
                return true;
            } else {
                $this->db->rollback();
                return false;
            }
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    }

    public function obtenerEstudiantesClase($idClase) {
        return $this->db->fetchAll("
            SELECT 
                e.ID_ESTUDIANTE,
                u.NOMBRE,
                u.APELLIDO,
                e.CODIGO,
                e.EMAIL_CORPORATIVO,
                COALESCE(n.CALIFICACION, 0) as CALIFICACION_ACTUAL
            FROM inscripcion i
            INNER JOIN estudiante e ON i.ID_ESTUDIANTE = e.ID_ESTUDIANTE
            INNER JOIN usuario u ON e.ID_USUARIO = u.ID_USUARIO
            LEFT JOIN (
                SELECT ra.ID_ESTUDIANTE, AVG(n.CALIFICACION) as CALIFICACION
                FROM registro_academico ra
                INNER JOIN notas n ON ra.ID_REGISTRO = n.ID_REGISTRO
                WHERE ra.ID_CLASE = ?
                GROUP BY ra.ID_ESTUDIANTE
            ) n ON e.ID_ESTUDIANTE = n.ID_ESTUDIANTE
            WHERE i.ID_CLASE = ?
            ORDER BY u.APELLIDO, u.NOMBRE
        ", [$idClase, $idClase]);
    }

    public function obtenerInfoClaseParaDiscord($idClase) {
        return $this->db->fetchOne("
            SELECT 
                c.ID_CLASE,
                cu.CODIGO as CODIGO_CURSO,
                cu.NOMBRE as NOMBRE_CURSO,
                sa.CODIGO as CICLO,
                c.CAPACIDAD
            FROM clase c
            INNER JOIN curso cu ON c.ID_CURSO = cu.ID_CURSO
            INNER JOIN ciclo ci ON cu.ID_CICLO = ci.ID_CICLO
            INNER JOIN semestre_academico sa ON ci.ID_SEMESTRE = sa.ID_SEMESTRE
            WHERE c.ID_CLASE = ?
        ", [$idClase]);
    }

    public function calificarEstudiante($idDocente, $idEstudiante, $idClase, $calificacion, $observacion, $usuarioRegistrador) {
        try {
            $this->db->beginTransaction();

            $registro = $this->db->fetchOne("
                SELECT ID_REGISTRO 
                FROM registro_academico 
                WHERE ID_DOCENTE = ? AND ID_ESTUDIANTE = ? AND ID_CLASE = ?
            ", [$idDocente, $idEstudiante, $idClase]);

            if (!$registro) {
                $idRegistro = $this->db->insert("
                    INSERT INTO registro_academico (ID_DOCENTE, ID_ESTUDIANTE, ID_CLASE, FECHA_REG)
                    VALUES (?, ?, ?, NOW())
                ", [$idDocente, $idEstudiante, $idClase]);
            } else {
                $idRegistro = $registro['ID_REGISTRO'];
            }

            $unidad = $this->db->fetchOne("SELECT ID_UNIDAD FROM unidad LIMIT 1");

            if (!$unidad) {
                $idUnidad = $this->db->insert("INSERT INTO unidad (NOMBRE) VALUES ('Unidad General')");
            } else {
                $idUnidad = $unidad['ID_UNIDAD'];
            }

            $notaExistente = $this->db->fetchOne("
                SELECT ID_NOTAS 
                FROM notas 
                WHERE ID_REGISTRO = ? AND ID_UNIDAD = ?
            ", [$idRegistro, $idUnidad]);

            if ($notaExistente) {
                $this->db->execute("
                    UPDATE notas 
                    SET CALIFICACION = ?, OBSERVACION = ?, FECHA_REG = NOW()
                    WHERE ID_NOTAS = ?
                ", [$calificacion, $observacion, $notaExistente['ID_NOTAS']]);
            } else {
                $this->db->insert("
                    INSERT INTO notas (ID_REGISTRO, ID_UNIDAD, TIPO_NOTA, CALIFICACION, OBSERVACION, FECHA_REG, USUARIO_REGISTRADOR, IP_REGISTRADOR)
                    VALUES (?, ?, 'Evaluación', ?, ?, NOW(), ?, ?)
                ", [
                    $idRegistro, 
                    $idUnidad, 
                    $calificacion, 
                    $observacion, 
                    $usuarioRegistrador, 
                    $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    }

    public function obtenerCalificacionesClase($idClase, $usuarioId) {
        $clase = $this->db->fetchOne("
            SELECT 
                c.ID_CLASE,
                cu.NOMBRE as NOMBRE_CURSO,
                cu.CODIGO as CODIGO_CURSO,
                ci.NOMBRE as NOMBRE_CICLO
            FROM registro_academico ra
            INNER JOIN clase c ON ra.ID_CLASE = c.ID_CLASE
            INNER JOIN curso cu ON c.ID_CURSO = cu.ID_CURSO
            INNER JOIN ciclo ci ON cu.ID_CICLO = ci.ID_CICLO
            INNER JOIN docente d ON ra.ID_DOCENTE = d.ID_DOCENTE
            WHERE c.ID_CLASE = ? AND d.ID_USUARIO = ?
        ", [$idClase, $usuarioId]);

        if (!$clase) {
            return false;
        }

        $calificaciones = $this->db->fetchAll("
            SELECT 
                e.ID_ESTUDIANTE,
                u.NOMBRE,
                u.APELLIDO,
                e.CODIGO,
                e.EMAIL_CORPORATIVO,
                AVG(n.CALIFICACION) as PROMEDIO,
                COUNT(n.ID_NOTAS) as TOTAL_NOTAS,
                MAX(n.FECHA_REG) as ULTIMA_CALIFICACION
            FROM inscripcion i
            INNER JOIN estudiante e ON i.ID_ESTUDIANTE = e.ID_ESTUDIANTE
            INNER JOIN usuario u ON e.ID_USUARIO = u.ID_USUARIO
            LEFT JOIN registro_academico ra ON (e.ID_ESTUDIANTE = ra.ID_ESTUDIANTE AND ra.ID_CLASE = ?)
            LEFT JOIN notas n ON ra.ID_REGISTRO = n.ID_REGISTRO
            WHERE i.ID_CLASE = ?
            GROUP BY e.ID_ESTUDIANTE, u.NOMBRE, u.APELLIDO, e.CODIGO, e.EMAIL_CORPORATIVO
            ORDER BY u.APELLIDO, u.NOMBRE
        ", [$idClase, $idClase]);

        return [
            'clase' => $clase,
            'calificaciones' => $calificaciones
        ];
    }

    public function obtenerInfoDocente($usuarioId) {
        return $this->db->fetchOne("
            SELECT 
                d.ID_DOCENTE,
                u.NOMBRE,
                u.APELLIDO,
                u.EMAIL,
                u.DNI
            FROM docente d
            INNER JOIN usuario u ON d.ID_USUARIO = u.ID_USUARIO
            WHERE u.ID_USUARIO = ?
        ", [$usuarioId]);
    }

    public function obtenerClasesDisponibles() {
        try {
            $clases = $this->db->fetchAll("
                SELECT DISTINCT
                    c.ID_CLASE,
                    c.HORARIO,
                    c.ESTADO as ESTADO_CLASE,
                    c.FECHA_INICIO,
                    c.FECHA_FIN,
                    c.CAPACIDAD,
                    c.ENLACE,
                    c.RAZON,
                    cu.NOMBRE as NOMBRE_CURSO,
                    cu.CODIGO as CODIGO_CURSO,
                    ci.NOMBRE as NOMBRE_CICLO,
                    sa.CODIGO as SEMESTRE,
                    COALESCE(COUNT(DISTINCT i.ID_ESTUDIANTE), 0) as PARTICIPANTES,
                    cu.ID_CURSO,
                    ci.ID_CICLO,
                    sa.ID_SEMESTRE
                FROM clase c
                INNER JOIN curso cu ON c.ID_CURSO = cu.ID_CURSO
                INNER JOIN ciclo ci ON cu.ID_CICLO = ci.ID_CICLO
                INNER JOIN semestre_academico sa ON ci.ID_SEMESTRE = sa.ID_SEMESTRE
                LEFT JOIN inscripcion i ON c.ID_CLASE = i.ID_CLASE
                LEFT JOIN registro_academico ra ON (c.ID_CLASE = ra.ID_CLASE AND ra.ID_DOCENTE IS NOT NULL)
                WHERE ra.ID_CLASE IS NULL  
                AND c.ESTADO = 1           
                AND c.FECHA_INICIO > NOW() 
                GROUP BY 
                    c.ID_CLASE, c.HORARIO, c.ESTADO, c.FECHA_INICIO, c.FECHA_FIN, 
                    c.CAPACIDAD, c.ENLACE, c.RAZON, cu.NOMBRE, cu.CODIGO, 
                    ci.NOMBRE, sa.CODIGO, cu.ID_CURSO, ci.ID_CICLO, sa.ID_SEMESTRE
                ORDER BY 
                    c.FECHA_INICIO ASC, 
                    cu.NOMBRE ASC
            ");

            foreach ($clases as &$clase) {
                $clase['PORCENTAJE_OCUPACION'] = $clase['CAPACIDAD'] > 0 
                    ? round(($clase['PARTICIPANTES'] / $clase['CAPACIDAD']) * 100, 1) 
                    : 0;
                
                if ($clase['FECHA_INICIO']) {
                    $clase['FECHA_INICIO_FORMATEADA'] = date('d/m/Y H:i', strtotime($clase['FECHA_INICIO']));
                }
                if ($clase['FECHA_FIN']) {
                    $clase['FECHA_FIN_FORMATEADA'] = date('d/m/Y H:i', strtotime($clase['FECHA_FIN']));
                }
            }

            return $clases;
            
        } catch (Exception $e) {
            error_log("Error en obtenerClasesDisponibles: " . $e->getMessage());
            return [];
        }
    }

    public function tomarClase($idClase, $idDocente) {
        try {
            $this->db->query("CALL sp_tomar_clase(?, ?, @resultado, @success)", [$idClase, $idDocente]);
            
            $result = $this->db->fetchOne("SELECT @resultado as resultado, @success as success");
            
            return [
                'success' => (bool)$result['success'],
                'message' => $result['resultado']
            ];
            
        } catch (Exception $e) {
            error_log("Error en tomarClase: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error interno del servidor'
            ];
        }
    }

    public function puedeTomarClase($idClase, $idDocente) {
        try {
            $resultado = $this->db->fetchOne("
                SELECT 
                    c.ID_CLASE,
                    c.ESTADO,
                    c.FECHA_INICIO,
                    cu.NOMBRE as NOMBRE_CURSO,
                    CASE 
                        WHEN ra.ID_CLASE IS NULL THEN 'YA_ASIGNADA'
                        WHEN c.ESTADO != 1 THEN 'INACTIVA'
                        WHEN c.FECHA_INICIO <= NOW() THEN 'YA_INICIADA'
                        ELSE 'DISPONIBLE'
                    END as ESTADO_DISPONIBILIDAD
                FROM clase c
                INNER JOIN curso cu ON c.ID_CURSO = cu.ID_CURSO
                LEFT JOIN registro_academico ra ON c.ID_CLASE = ra.ID_CLASE
                WHERE c.ID_CLASE = ?
            ", [$idClase]);

            if (!$resultado) {
                return [
                    'puede_tomar' => false,
                    'razon' => 'La clase no existe'
                ];
            }

            if ($resultado['ESTADO_DISPONIBILIDAD'] !== 'DISPONIBLE') {
                $razones = [
                    'YA_ASIGNADA' => 'La clase ya tiene un mentor asignado',
                    'INACTIVA' => 'La clase no está activa',
                    'YA_INICIADA' => 'La clase ya ha iniciado'
                ];
                
                return [
                    'puede_tomar' => false,
                    'razon' => $razones[$resultado['ESTADO_DISPONIBILIDAD']]
                ];
            }

            $yaAsignado = $this->db->fetchOne("
                SELECT ID_REGISTRO 
                FROM registro_academico 
                WHERE ID_CLASE = ? AND ID_DOCENTE = ?
            ", [$idClase, $idDocente]);

            if ($yaAsignado) {
                return [
                    'puede_tomar' => false,
                    'razon' => 'Ya estás asignado como mentor de esta clase'
                ];
            }

            return [
                'puede_tomar' => true,
                'clase' => $resultado
            ];
            
        } catch (Exception $e) {
            error_log("Error en puedeTomarClase: " . $e->getMessage());
            return [
                'puede_tomar' => false,
                'razon' => 'Error al verificar disponibilidad'
            ];
        }
    }
}
?>