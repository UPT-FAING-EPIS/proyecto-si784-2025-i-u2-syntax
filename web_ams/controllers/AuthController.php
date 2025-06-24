<?php
require_once BASE_PATH . '/models/Usuario.php';

class AuthController extends BaseController {

    public function handle($accion) {
        switch ($accion) {
            case 'login':
                $this->loginGet();
                break;
            case 'procesar_login':
                $this->loginPost();
                break;
            case 'registro':
                $this->registroGet();
                break;
            case 'procesar_registro':
                $this->registroPost();
                break;
            case 'consulta_dni':
                $this->consultaDNI();
                break;
            default:
                echo "<h2>Acción de autenticación no válida: " . htmlspecialchars($accion) . "</h2>";
                break;
        }
    }

    public function loginGet() {
        require BASE_PATH . '/views/login.php';
    }

    public function loginPost() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $usuario = new Usuario();
        $datos = $usuario->verificarCredenciales($email, $password);

        if ($datos) {
            session_start();
            $_SESSION['usuario_id'] = $datos['ID_USUARIO'];
            $_SESSION['rol_id'] = (int)$datos['ID_ROL']; 
            $_SESSION['rol_nombre'] = $datos['ROL'];   

            header('Location: ' . BASE_URL . '/index.php');
        } else {
            echo "<script>alert('Credenciales incorrectas');window.location.href='" . BASE_URL . "/index.php?accion=login';</script>";
        }
    }

    public function registroGet() {
        require BASE_PATH . '/views/register.php';
    }
    public function registroPost() {
        header('Content-Type: application/json');

        $dni      = $_POST['dni'] ?? '';
        $nombre   = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        try {
            $usuarioModel = new Usuario();
            $user_id = $usuarioModel->registrarUsuario($dni, $nombre, $apellido, $email, $password);

            if ($user_id) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['usuario_id'] = $user_id;

                $datosUsuario = $usuarioModel->buscarPorCorreo($email);
                if ($datosUsuario) {
                    $_SESSION['rol_id'] = (int)$datosUsuario['ID_ROL'];
                    $_SESSION['rol_nombre'] = $datosUsuario['ROL'];
                }

                echo json_encode([
                    'success' => true,
                    'message' => 'Registro exitoso'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al registrar el usuario'
                ]);
            }

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error en el registro'
            ]);
        }

        exit;
    }
    private function consultaDNI() {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            if (!isset($_GET['dni']) || !preg_match('/^\d{8}$/', $_GET['dni'])) {
                $this->sendErrorResponse(400, 'DNI inválido. Debe contener exactamente 8 dígitos.');
                return;
            }

            $dni = $_GET['dni'];
            $token = 'apis-token-16209.4jn7mUQ93GRnE1lHfPq1eQ20s0Ywir8P';
            
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.apis.net.pe/v2/reniec/dni?numero=' . $dni,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 3,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; DNI Validator/1.0)',
                CURLOPT_HTTPHEADER => [
                    'Accept: application/json',
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $token,
                    'Referer: https://apis.net.pe/consulta-dni-api'
                ],
            ]);

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $curlError = curl_error($curl);
            curl_close($curl);

            if ($curlError) {
                $this->sendErrorResponse(500, 'Error de conexión: ' . $curlError);
                return;
            }

            if ($httpCode !== 200) {
                $this->sendErrorResponse($httpCode, "Error del servidor externo (HTTP $httpCode)");
                return;
            }

            $data = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log("Respuesta no es JSON válido: " . substr($response, 0, 200));
                $this->sendErrorResponse(502, 'Respuesta inválida del servicio externo');
                return;
            }

            if (isset($data['error'])) {
                $this->sendErrorResponse(400, $data['error']);
                return;
            }

            if (!isset($data['nombres']) || !isset($data['apellidoPaterno'])) {
                $this->sendErrorResponse(404, 'DNI no encontrado o datos incompletos');
                return;
            }

            echo json_encode([
                'success' => true,
                'nombres' => trim($data['nombres']),
                'apellidoPaterno' => trim($data['apellidoPaterno']),
                'apellidoMaterno' => trim($data['apellidoMaterno'] ?? ''),
                'numeroDocumento' => $dni
            ], JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            error_log("Error en consultaDNI: " . $e->getMessage());
            $this->sendErrorResponse(500, 'Error interno del servidor');
        }
    }

    private function sendErrorResponse($code, $message) {
        http_response_code($code);
        echo json_encode([
            'success' => false,
            'error' => $message,
            'code' => $code
        ], JSON_UNESCAPED_UNICODE);
    }
}
