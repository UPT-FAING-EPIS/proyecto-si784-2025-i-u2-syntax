<?php
require_once BASE_PATH . '/config/Database.php';

class EstudianteModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }
    public function buscarPorCodigo($codigoEstudiante) {
        try {
            $sql = "SELECT 
                        codigo_estudiante,
                        nombres,
                        apellidos,
                        email_institucional,
                        carrera,
                        semestre,
                        estado
                    FROM alumnos 
                    WHERE codigo_estudiante = ? AND estado = 'activo'";
            
            return $this->db->fetchOne($sql, [$codigoEstudiante]);
        } catch (PDOException $e) {
            error_log("Error en buscarPorCodigo: " . $e->getMessage());
            return false;
        }
    }
    public function verificarUsuarioVinculado($codigoEstudiante) {
        try {
            $sql = "SELECT u.id_usuario
                    FROM usuario u 
                    INNER JOIN estudiante e ON u.id_usuario = e.id_usuario 
                    WHERE e.codigo = ?";
            
            $resultado = $this->db->fetchOne($sql, [$codigoEstudiante]);
            return $resultado !== false;
        } catch (PDOException $e) {
            error_log("Error en verificarUsuarioVinculado: " . $e->getMessage());
            return false;
        }
    }

    public function guardarCodigoVerificacion($codigoEstudiante, $codigoVerificacion, $expiracion) {
        try {
            $sqlDelete = "DELETE FROM codigos_verificacion WHERE codigo_estudiante = ?";
            $this->db->execute($sqlDelete, [$codigoEstudiante]);
            $sql = "INSERT INTO codigos_verificacion 
                    (codigo_estudiante, codigo_verificacion, fecha_expiracion, intentos, creado_en) 
                    VALUES (?, ?, ?, 0, NOW())";
            
            $this->db->execute($sql, [$codigoEstudiante, $codigoVerificacion, $expiracion]);
            return true;
        } catch (PDOException $e) {
            error_log("Error en guardarCodigoVerificacion: " . $e->getMessage());
            return false;
        }
    }
    public function verificarCodigo($codigoEstudiante, $codigoIngresado) {
        try {
            $sql = "SELECT codigo_verificacion, fecha_expiracion, intentos 
                    FROM codigos_verificacion 
                    WHERE codigo_estudiante = ? 
                    ORDER BY creado_en DESC 
                    LIMIT 1";
            
            $registro = $this->db->fetchOne($sql, [$codigoEstudiante]);

            if (!$registro) {
                return [
                    'valido' => false,
                    'mensaje' => 'No se encontró código de verificación. Solicita uno nuevo.'
                ];
            }

            if (strtotime($registro['fecha_expiracion']) < time()) {
                $this->limpiarCodigoVerificacion($codigoEstudiante);
                return [
                    'valido' => false,
                    'mensaje' => 'El código ha expirado. Solicita uno nuevo.'
                ];
            }
            if ($registro['intentos'] >= 3) {
                $this->limpiarCodigoVerificacion($codigoEstudiante);
                return [
                    'valido' => false,
                    'mensaje' => 'Has excedido el límite de intentos. Solicita un código nuevo.'
                ];
            }

            $this->incrementarIntentos($codigoEstudiante);

            if ($registro['codigo_verificacion'] === $codigoIngresado) {
                return [
                    'valido' => true,
                    'mensaje' => 'Código verificado correctamente.'
                ];
            } else {
                $intentosRestantes = 3 - ($registro['intentos'] + 1);
                return [
                    'valido' => false,
                    'mensaje' => "Código incorrecto. Te quedan {$intentosRestantes} intentos."
                ];
            }
        } catch (PDOException $e) {
            error_log("Error en verificarCodigo: " . $e->getMessage());
            return [
                'valido' => false,
                'mensaje' => 'Error interno del sistema.'
            ];
        }
    }
    private function incrementarIntentos($codigoEstudiante) {
        try {
            $sql = "UPDATE codigos_verificacion 
                    SET intentos = intentos + 1 
                    WHERE codigo_estudiante = ?";
            
            return $this->db->execute($sql, [$codigoEstudiante]) > 0;
        } catch (PDOException $e) {
            error_log("Error en incrementarIntentos: " . $e->getMessage());
            return false;
        }
    }
    public function limpiarCodigoVerificacion($codigoEstudiante) {
        try {
            $sql = "DELETE FROM codigos_verificacion WHERE codigo_estudiante = ?";
            return $this->db->execute($sql, [$codigoEstudiante]) > 0;
        } catch (PDOException $e) {
            error_log("Error en limpiarCodigoVerificacion: " . $e->getMessage());
            return false;
        }
    }
    public function vincularUsuario($usuarioId, $datosVinculacion) {
        try {
            $this->db->beginTransaction();

            $sqlCheck = "SELECT id_estudiante FROM estudiante WHERE id_usuario = ?";
            $existeEstudiante = $this->db->fetchOne($sqlCheck, [$usuarioId]);

            if ($existeEstudiante) {
                $sql = "UPDATE estudiante SET 
                        codigo = ?,
                        email_corporativo = ?,
                        condicion = 'activo',
                        fecha_reg = NOW()
                        WHERE id_usuario = ?";
                
                $resultado = $this->db->execute($sql, [
                    $datosVinculacion['codigo_estudiante'],
                    $datosVinculacion['email_institucional'],
                    $usuarioId
                ]);
            } else {
                $sql = "INSERT INTO estudiante 
                        (id_usuario, codigo, email_corporativo, fecha_reg, condicion) 
                        VALUES (?, ?, ?, NOW(), 'activo')";
                
                $resultado = $this->db->execute($sql, [
                    $usuarioId,
                    $datosVinculacion['codigo_estudiante'],
                    $datosVinculacion['email_institucional']
                ]);
            }

            if ($resultado > 0) {
                $this->db->commit();
                return true;
            } else {
                $this->db->rollback();
                return false;
            }
        } catch (PDOException $e) {
            $this->db->rollback();
            error_log("Error en vincularUsuario: " . $e->getMessage());
            return false;
        }
    }
    public function actualizarRolUsuario($usuarioId, $nuevoRol) {
        try {
            $sql = "UPDATE roles_asignados SET id_rol = ? WHERE id_usuario = ?";
            return $this->db->execute($sql, [$nuevoRol, $usuarioId]) > 0;
        } catch (PDOException $e) {
            error_log("Error en actualizarRolUsuario: " . $e->getMessage());
            return false;
        }
    }
    public function contarIntentosRecientes($codigoEstudiante) {
        try {
            $sql = "SELECT COUNT(*) as total 
                    FROM codigos_verificacion 
                    WHERE codigo_estudiante = ? 
                    AND creado_en > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
            
            $resultado = $this->db->fetchOne($sql, [$codigoEstudiante]);
            return $resultado['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error en contarIntentosRecientes: " . $e->getMessage());
            return 0;
        }
    }
    public function obtenerClasesEstudiante($usuarioId) {
        try {
            $sql = "SELECT 
                        c.id,
                        c.titulo,
                        c.descripcion,
                        c.fecha_clase,
                        c.hora_inicio,
                        c.hora_fin,
                        c.modalidad,
                        c.estado,
                        u.nombre as mentor_nombre,
                        u.email as mentor_email,
                        s.estado as solicitud_estado,
                        s.fecha_solicitud
                    FROM clases c
                    INNER JOIN solicitudes_clase s ON c.id = s.clase_id
                    INNER JOIN usuarios u ON c.mentor_id = u.id
                    WHERE s.estudiante_id = ? 
                    ORDER BY c.fecha_clase DESC, c.hora_inicio ASC";
            
            return $this->db->fetchAll($sql, [$usuarioId]);
        } catch (PDOException $e) {
            error_log("Error en obtenerClasesEstudiante: " . $e->getMessage());
            return [];
        }
    }
    public function obtenerEstudiantePorUsuario($usuarioId) {
        try {
            $sql = "SELECT 
                        e.*,
                        u.nombre as usuario_nombre,
                        u.email as usuario_email
                    FROM estudiante e
                    INNER JOIN usuarios u ON e.usuario_id = u.id
                    WHERE e.usuario_id = ? AND e.estado = 'activo'";
            
            return $this->db->fetchOne($sql, [$usuarioId]);
        } catch (PDOException $e) {
            error_log("Error en obtenerEstudiantePorUsuario: " . $e->getMessage());
            return false;
        }
    }
}