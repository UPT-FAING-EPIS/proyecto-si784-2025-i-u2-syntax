<?php
require_once BASE_PATH . '/config/Database.php';

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function verificarCredenciales($email, $password) {
        $sql = "SELECT U.ID_USUARIO, U.PASSWORD, R.ID_ROL, R.NOMBRE AS ROL
                FROM usuario U
                INNER JOIN roles_asignados RA ON U.ID_USUARIO = RA.ID_USUARIO
                INNER JOIN rol R ON RA.ID_ROL = R.ID_ROL
                WHERE U.EMAIL = ? LIMIT 1";
        
        $user = $this->db->fetchOne($sql, [$email]);

        if ($user && password_verify($password, $user['PASSWORD'])) {
            return $user;
        }
        return false;
    }

    public function registrarUsuario($dni, $nombre, $apellido, $email, $password) {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->db->getConnection()->prepare("CALL sp_registrar_usuario(?, ?, ?, ?, ?)");
            $stmt->execute([$dni, $nombre, $apellido, $email, $hash]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $user_id = $result && isset($result['id_usuario']) ? (int)$result['id_usuario'] : false;
            return $user_id;

        } catch (PDOException $e) {
            error_log("❌ Error al registrar con SP: " . $e->getMessage());
            return false;
        }
    }
    public function buscarPorCorreo($email) {
        $sql = "SELECT U.ID_USUARIO, U.EMAIL, R.ID_ROL, R.NOMBRE AS ROL
                FROM usuario U
                JOIN roles_asignados RA ON RA.ID_USUARIO = U.ID_USUARIO
                JOIN rol R ON RA.ID_ROL = R.ID_ROL
                WHERE U.EMAIL = ?";
        
        return $this->db->fetchOne($sql, [$email]);
    }

    public function registrarOAuth($nombre, $apellido, $email) {
        try {
            error_log("📌 Ejecutando procedimiento almacenado para registrar usuario OAuth");

            $stmt = $this->db->getConnection()->prepare("CALL sp_registrar_usuario_oauth(?, ?, ?, @p_id_usuario)");
            $stmt->execute([$nombre, $apellido, $email]);

            $result = $this->db->query("SELECT @p_id_usuario AS id")->fetch(PDO::FETCH_ASSOC);
            $user_id = $result['id'] ?? 0;

            error_log("✅ Usuario registrado con ID: $user_id");
            return $user_id;

        } catch (Exception $e) {
            error_log("❌ Error al registrar OAuth: " . $e->getMessage());
            return false;
        }
    }
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM usuario WHERE ID_USUARIO = ?";
        return $this->db->fetchOne($sql, [$id]);
    }

    public function actualizarPassword($id, $password) {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE usuario SET PASSWORD = ? WHERE ID_USUARIO = ?";
            $affectedRows = $this->db->execute($sql, [$hash, $id]);
            
            if ($affectedRows === 0) {
                throw new Exception("No se pudo actualizar la contraseña o el usuario no existe");
            }
            
            return true;
            
        } catch (Exception $e) {
            throw new Exception("Error en la base de datos: " . $e->getMessage());
        }
    }

    public function obtenerIdEstudiante($id_usuario) {
        $sql = "SELECT ID_ESTUDIANTE FROM estudiante WHERE ID_USUARIO = ?";
        $result = $this->db->fetchOne($sql, [$id_usuario]);
        return $result ? $result['ID_ESTUDIANTE'] : null;
    }

    public function obtenerDatosCompletos($id_usuario) {
        $sql = "SELECT 
                    U.*,
                    E.ID_ESTUDIANTE,
                    E.CODIGO,
                    E.EMAIL_CORPORATIVO,
                    E.CONDICION,
                    D.ID_DOCENTE,
                    D.ESTADO as DOCENTE_ESTADO,
                    A.ID_ADMIN,
                    A.NIVEL_ACCESO
                FROM usuario U
                LEFT JOIN estudiante E ON U.ID_USUARIO = E.ID_USUARIO
                LEFT JOIN docente D ON U.ID_USUARIO = D.ID_USUARIO
                LEFT JOIN administrador A ON U.ID_USUARIO = A.ID_USUARIO
                WHERE U.ID_USUARIO = ?";
        
        return $this->db->fetchOne($sql, [$id_usuario]);
    }

    public function obtenerRolesUsuario($id_usuario) {
        $sql = "SELECT R.ID_ROL, R.NOMBRE, RA.ESTADO
                FROM rol R
                INNER JOIN roles_asignados RA ON R.ID_ROL = RA.ID_ROL
                WHERE RA.ID_USUARIO = ? AND RA.ESTADO = 1";
        
        return $this->db->fetchAll($sql, [$id_usuario]);
    }

    public function asignarRol($id_usuario, $id_rol) {
        try {
            $sql = "INSERT INTO roles_asignados (ID_USUARIO, ID_ROL, FECHA_REG, ESTADO) VALUES (?, ?, NOW(), 1)";
            return $this->db->execute($sql, [$id_usuario, $id_rol]) > 0;
            
        } catch (Exception $e) {
            error_log("Error al asignar rol: " . $e->getMessage());
            return false;
        }
    }

    public function revocarRol($id_usuario, $id_rol) {
        try {
            $sql = "UPDATE roles_asignados SET ESTADO = 0 WHERE ID_USUARIO = ? AND ID_ROL = ?";
            return $this->db->execute($sql, [$id_usuario, $id_rol]) > 0;
            
        } catch (Exception $e) {
            error_log("Error al revocar rol: " . $e->getMessage());
            return false;
        }
    }

    public function existeEmail($email) {
        $sql = "SELECT COUNT(*) as total FROM usuario WHERE EMAIL = ?";
        $result = $this->db->fetchOne($sql, [$email]);
        return $result['total'] > 0;
    }

    public function existeDNI($dni) {
        $sql = "SELECT COUNT(*) as total FROM usuario WHERE DNI = ?";
        $result = $this->db->fetchOne($sql, [$dni]);
        return $result['total'] > 0;
    }

    public function actualizarDatos($id_usuario, $datos) {
        try {
            $campos = [];
            $valores = [];
            
            foreach ($datos as $campo => $valor) {
                if (in_array($campo, ['DNI', 'NOMBRE', 'APELLIDO', 'EMAIL', 'CELULAR'])) {
                    $campos[] = "$campo = ?";
                    $valores[] = $valor;
                }
            }
            
            if (empty($campos)) {
                throw new Exception("No hay campos válidos para actualizar");
            }
            
            $valores[] = $id_usuario;
            $sql = "UPDATE usuario SET " . implode(', ', $campos) . " WHERE ID_USUARIO = ?";
            
            return $this->db->execute($sql, $valores) > 0;
            
        } catch (Exception $e) {
            error_log("Error al actualizar datos: " . $e->getMessage());
            return false;
        }
    }

    public function listarUsuarios($filtros = [], $limite = 50, $offset = 0) {
        $sql = "SELECT U.ID_USUARIO, U.DNI, U.NOMBRE, U.APELLIDO, U.EMAIL, U.CELULAR, U.FECHA_REG,
                       GROUP_CONCAT(R.NOMBRE) as ROLES
                FROM usuario U
                LEFT JOIN roles_asignados RA ON U.ID_USUARIO = RA.ID_USUARIO AND RA.ESTADO = 1
                LEFT JOIN rol R ON RA.ID_ROL = R.ID_ROL";
        
        $params = [];
        $condiciones = [];
        
        if (!empty($filtros['email'])) {
            $condiciones[] = "U.EMAIL LIKE ?";
            $params[] = '%' . $filtros['email'] . '%';
        }
        
        if (!empty($filtros['nombre'])) {
            $condiciones[] = "(U.NOMBRE LIKE ? OR U.APELLIDO LIKE ?)";
            $params[] = '%' . $filtros['nombre'] . '%';
            $params[] = '%' . $filtros['nombre'] . '%';
        }
        
        if (!empty($filtros['rol'])) {
            $condiciones[] = "R.ID_ROL = ?";
            $params[] = $filtros['rol'];
        }
        
        if (!empty($condiciones)) {
            $sql .= " WHERE " . implode(' AND ', $condiciones);
        }
        
        $sql .= " GROUP BY U.ID_USUARIO ORDER BY U.FECHA_REG DESC LIMIT ? OFFSET ?";
        $params[] = $limite;
        $params[] = $offset;
        
        return $this->db->fetchAll($sql, $params);
    }

}