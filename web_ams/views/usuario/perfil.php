<?php
require_once BASE_PATH . '/models/Usuario.php';

$usuarioModel = new Usuario();

$usuario = $usuarioModel->obtenerDatosCompletos($_SESSION['usuario_id']);
$roles = $usuarioModel->obtenerRolesUsuario($_SESSION['usuario_id']);

function validarPasswordSegura($password) {
    if (strlen($password) < 8) {
        return "La contraseña debe tener al menos 8 caracteres";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return "La contraseña debe contener al menos una letra mayúscula";
    }
    if (!preg_match('/[a-z]/', $password)) {
        return "La contraseña debe contener al menos una letra minúscula";
    }
    if (!preg_match('/[0-9]/', $password)) {
        return "La contraseña debe contener al menos un número";
    }
    if (!preg_match('/[^A-Za-z0-9]/', $password)) {
        return "La contraseña debe contener al menos un carácter especial";
    }
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'reclamar_rango') {
        // Manejar reclamación de rango
        try {
            $discordUsername = trim($_POST['discord_username'] ?? '');
            $emailUsuario = trim($_POST['email_usuario'] ?? '');
            
            if (empty($discordUsername)) {
                throw new Exception("El username de Discord es requerido");
            }
            
            if (empty($emailUsuario)) {
                throw new Exception("El email del usuario es requerido");
            }
            
            // Validar formato del username de Discord
            if (!preg_match('/^[a-zA-Z0-9_.]{2,32}$/', $discordUsername)) {
                throw new Exception("Username de Discord inválido. Solo se permiten letras, números, puntos y guiones bajos (2-32 caracteres)");
            }
            
            // Validar formato del email
            if (!filter_var($emailUsuario, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Email inválido");
            }
            
            require_once BASE_PATH . '/controllers/RangoController.php';
            $rangoController = new RangoController();
            $resultado = $rangoController->generarClaveReclamo($_SESSION['usuario_id'], $discordUsername, $emailUsuario);
            
            if ($resultado['success']) {
                $mensaje = $resultado['mensaje'];
                $tipo_mensaje = "success";
                $codigo_reclamo = $resultado['codigo'];
            } else {
                throw new Exception($resultado['mensaje']);
            }
        } catch (Exception $e) {
            $mensaje = "Error al generar código de reclamo: " . $e->getMessage();
            $tipo_mensaje = "danger";
        }
    } else {
        // Manejar cambio de contraseña (código existente)
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        try {
            if (empty($password)) {
                throw new Exception("La contraseña es requerida");
            }
            
            if ($password !== $confirm_password) {
                throw new Exception("Las contraseñas no coinciden");
            }

            $validacion = validarPasswordSegura($password);
            if ($validacion !== true) {
                throw new Exception($validacion);
            }

            $usuarioModel->actualizarPassword($_SESSION['usuario_id'], $password);
            
            $mensaje = "Contraseña actualizada correctamente";
            $tipo_mensaje = "success";
        } catch (Exception $e) {
            $mensaje = "Error al actualizar contraseña: " . $e->getMessage();
            $tipo_mensaje = "danger";
        }
    }
}

$login_time = $_SESSION['login_time'] ?? time();
$tiempo_conectado = time() - $login_time;

function formatearTiempo($segundos) {
    $horas = floor($segundos / 3600);
    $minutos = floor(($segundos % 3600) / 60);
    $segundos = $segundos % 60;
    
    if ($horas > 0) {
        return sprintf("%d hora(s), %d minuto(s)", $horas, $minutos);
    } elseif ($minutos > 0) {
        return sprintf("%d minuto(s), %d segundo(s)", $minutos, $segundos);
    } else {
        return sprintf("%d segundo(s)", $segundos);
    }
}

$tiempo_conectado_texto = formatearTiempo($tiempo_conectado);

$roles_nombres = array_column($roles, 'NOMBRE');
$rol_principal = !empty($roles_nombres) ? $roles_nombres[0] : 'Sin rol asignado';

// Verificar si el usuario puede reclamar rango (estudiante o docente)
$puede_reclamar = !empty($usuario['ID_ESTUDIANTE']) || !empty($usuario['ID_DOCENTE']);
$tipo_usuario = !empty($usuario['ID_ESTUDIANTE']) ? 'estudiante' : (!empty($usuario['ID_DOCENTE']) ? 'docente' : 'otro');

require_once BASE_PATH . '/views/components/head.php';
require_once BASE_PATH . '/views/components/header.php';
?>

<style>
:root {
    --accent-blue: #5a73c4;
    --light-blue: #e8f0fe;
    --dark-blue: #2d4482;
    --success-green: #28a745;
    --warning-orange: #ffc107;
    --danger-red: #dc3545;
    --light-gray: #f8f9fa;
    --border-gray: #e9ecef;
    --text-gray: #495057;
}

.profile-card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(60, 90, 166, 0.1);
    transition: all 0.15s ease-in-out;
    border-radius: 0.5rem;
}

.profile-card:hover {
    box-shadow: 0 0.5rem 1rem rgba(60, 90, 166, 0.15);
    transform: translateY(-2px);
}

.card-header {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    color: white;
    border-bottom: none;
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

.card-header h3, .card-header h5 {
    margin: 0;
    font-weight: 600;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
}

.form-label.fw-bold {
    color: var(--text-gray);
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
    font-weight: 600;
}

.form-control[readonly] {
    background-color: var(--light-gray);
    border-color: var(--border-gray);
    color: var(--text-gray);
}

.form-control:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 0.2rem rgba(60, 90, 166, 0.25);
}

.badge-status {
    font-size: 0.85rem;
    padding: 0.5rem 0.75rem;
    border-radius: 0.375rem;
}

.bg-primary {
    background-color: var(--primary-blue) !important;
}

.bg-secondary {
    background-color: var(--secondary-blue) !important;
}

.bg-info {
    background-color: var(--accent-blue) !important;
}

.btn-primary {
    background-color: var(--primary-blue);
    border-color: var(--primary-blue);
    transition: all 0.15s ease-in-out;
}

.btn-primary:hover {
    background-color: var(--dark-blue);
    border-color: var(--dark-blue);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(60, 90, 166, 0.3);
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    transition: all 0.15s ease-in-out;
}

.btn-secondary:hover {
    transform: translateY(-1px);
}

.btn-outline-secondary {
    color: var(--primary-blue);
    border-color: var(--primary-blue);
}

.btn-outline-secondary:hover {
    background-color: var(--primary-blue);
    border-color: var(--primary-blue);
}

.info-row {
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-gray);
    transition: background-color 0.15s ease;
}

.info-row:last-child {
    border-bottom: none;
}

.info-row:hover {
    background-color: rgba(60, 90, 166, 0.02);
    border-radius: 0.25rem;
    margin: 0 -0.5rem;
    padding-left: 0.5rem;
    padding-right: 0.5rem;
}

.password-strength {
    margin-top: 0.5rem;
}

.strength-indicator {
    height: 6px;
    border-radius: 3px;
    background-color: var(--border-gray);
    overflow: hidden;
    margin-bottom: 0.5rem;
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
}

.strength-bar {
    height: 100%;
    transition: all 0.3s ease;
    border-radius: 3px;
}

.strength-weak { 
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); 
    width: 25%; 
}

.strength-fair { 
    background: linear-gradient(135deg, #fd7e14 0%, #e55100 100%); 
    width: 50%; 
}

.strength-good { 
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); 
    width: 75%; 
}

.strength-strong { 
    background: linear-gradient(135deg, var(--success-green) 0%, #1e7e34 100%); 
    width: 100%; 
}

.alert-info {
    background-color: var(--light-blue);
    border-color: var(--primary-blue);
    color: var(--dark-blue);
}

.alert-warning {
    background-color: #fff3cd;
    border-color: var(--warning-orange);
    color: #856404;
}

.text-success {
    color: var(--success-green) !important;
}

.is-valid {
    border-color: var(--success-green);
}

.is-invalid {
    border-color: var(--danger-red);
}

.input-group .btn {
    border-color: var(--border-gray);
}

.card-body {
    padding: 1.5rem;
}

/* Estilos adicionales para el modal y botón de reclamo */
.btn-reclamar-rango {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    color: white;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
}

.btn-reclamar-rango:hover {
    background: linear-gradient(135deg, #218838 0%, #1bb394 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
    color: white;
}

.modal-header {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    color: white;
    border-bottom: none;
}

.modal-header .btn-close {
    filter: invert(1);
}

.codigo-reclamo {
    font-family: 'Courier New', monospace;
    font-size: 1.2rem;
    font-weight: bold;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px dashed var(--primary-blue);
    border-radius: 0.5rem;
    padding: 1rem;
    text-align: center;
    letter-spacing: 2px;
    margin: 1rem 0;
    color: var(--primary-blue);
    cursor: pointer;
    transition: all 0.3s ease;
}

.codigo-reclamo:hover {
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
    transform: scale(1.02);
}

/* Animaciones suaves */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.profile-card {
    animation: fadeIn 0.3s ease-out;
}

.profile-card:nth-child(2) { animation-delay: 0.1s; }
.profile-card:nth-child(3) { animation-delay: 0.2s; }
.profile-card:nth-child(4) { animation-delay: 0.3s; }
.profile-card:nth-child(5) { animation-delay: 0.4s; }

.btn-reclamar-rango:hover {
    animation: pulse 0.6s ease-in-out;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    
    .profile-card {
        margin-bottom: 1rem;
    }
    
    .btn {
        margin-bottom: 0.5rem;
    }
    
    .codigo-reclamo {
        font-size: 1rem;
        letter-spacing: 1px;
        padding: 0.75rem;
    }
}
</style>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <?php if (isset($mensaje)): ?>
                <div class="alert alert-<?= $tipo_mensaje ?> alert-dismissible fade show" role="alert">
                    <i class="fas fa-<?= $tipo_mensaje === 'success' ? 'check-circle' : 'exclamation-triangle' ?>"></i>
                    <?= htmlspecialchars($mensaje) ?>
                    <?php if (isset($codigo_reclamo)): ?>
                        <br><br>
                        <strong>Tu código de reclamo es:</strong>
                        <div class="codigo-reclamo mt-2" onclick="copiarCodigo(this)" title="Haz clic para copiar">
                            <?= htmlspecialchars($codigo_reclamo) ?>
                        </div>
                        <small class="text-muted">Este código expira en 5 minutos. Haz clic para copiar.</small>
                    <?php endif; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card profile-card mb-4">
                <div class="card-header">
                    <h3 class="mb-0">
                        <i class="fas fa-user me-2"></i> Información Personal
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nombre:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($usuario['NOMBRE'] ?? 'No especificado') ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Apellido:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($usuario['APELLIDO'] ?? 'No especificado') ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">DNI:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($usuario['DNI'] ?? 'No asignado') ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Celular:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($usuario['CELULAR'] ?? 'No registrado') ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Correo Electrónico:</label>
                        <input type="email" class="form-control" value="<?= htmlspecialchars($usuario['EMAIL'] ?? '') ?>" readonly>
                    </div>

                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i> 
                        Los datos personales no pueden ser modificados. Contacta al administrador si necesitas realizar cambios.
                    </div>
                </div>
            </div>

            <div class="card profile-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-lock me-2"></i> Cambiar Contraseña
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>/index.php?accion=perfil" id="passwordForm">
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Nueva Contraseña:</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Ingresa tu nueva contraseña" required>
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-strength">
                                <div class="strength-indicator">
                                    <div class="strength-bar" id="strengthBar"></div>
                                </div>
                                <small class="text-muted" id="strengthText">Ingresa una contraseña para ver su fortaleza</small>
                            </div>
                            <div class="form-text mt-2">
                                <strong>Requisitos de seguridad:</strong>
                                <ul class="mb-0 mt-1" id="requirements">
                                    <li id="req-length" class="text-muted">
                                        <i class="fas fa-times me-1"></i> Mínimo 8 caracteres
                                    </li>
                                    <li id="req-upper" class="text-muted">
                                        <i class="fas fa-times me-1"></i> Al menos una letra mayúscula
                                    </li>
                                    <li id="req-lower" class="text-muted">
                                        <i class="fas fa-times me-1"></i> Al menos una letra minúscula
                                    </li>
                                    <li id="req-number" class="text-muted">
                                        <i class="fas fa-times me-1"></i> Al menos un número
                                    </li>
                                    <li id="req-special" class="text-muted">
                                        <i class="fas fa-times me-1"></i> Al menos un carácter especial (!@#$%^&*)
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label fw-bold">Confirmar Nueva Contraseña:</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                       placeholder="Confirma tu nueva contraseña" required>
                                <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback" id="confirmFeedback"></div>
                        </div>

                        <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                            <i class="fas fa-save me-2"></i> Cambiar Contraseña
                        </button>
                    </form>
                </div>
            </div>

            <div class="card profile-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i> Información de la Cuenta
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <strong>ID de Usuario:</strong> 
                                <span class="badge bg-secondary ms-2"><?= $_SESSION['usuario_id'] ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <strong>Roles Asignados:</strong> 
                                <div class="mt-1">
                                    <?php foreach ($roles as $rol): ?>
                                        <span class="badge bg-primary me-1"><?= htmlspecialchars($rol['NOMBRE']) ?></span>
                                    <?php endforeach; ?>
                                    <?php if (empty($roles)): ?>
                                        <span class="badge bg-warning">Sin roles asignados</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <strong>Fecha de Registro:</strong> 
                                <?= $usuario['FECHA_REG'] ? date('d/m/Y H:i:s', strtotime($usuario['FECHA_REG'])) : 'No disponible' ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <strong>Tiempo Conectado:</strong> 
                                <span class="badge bg-success" id="tiempoConectado"><?= $tiempo_conectado_texto ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($usuario['ID_ESTUDIANTE'])): ?>
                <div class="card profile-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-graduation-cap me-2"></i> Información Académica
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email Corporativo:</label>
                                    <input type="email" class="form-control" 
                                           value="<?= htmlspecialchars($usuario['EMAIL_CORPORATIVO'] ?? 'No asignado') ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Código de Estudiante:</label>
                                    <input type="text" class="form-control" 
                                           value="<?= htmlspecialchars($usuario['CODIGO'] ?? 'No asignado') ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Condición Académica:</label>
                                    <div class="mt-2">
                                        <?php
                                        $condicion = $usuario['CONDICION'] ?? 'No definida';
                                        $clase_badge = match(strtolower($condicion)) {
                                            'activo' => 'bg-success',
                                            'inactivo' => 'bg-danger',
                                            'suspendido' => 'bg-warning',
                                            'egresado' => 'bg-info',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $clase_badge ?> badge-status">
                                            <?= htmlspecialchars($condicion) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">ID Estudiante:</label>
                                    <input type="text" class="form-control" 
                                           value="<?= htmlspecialchars($usuario['ID_ESTUDIANTE']) ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i> 
                            La información académica es administrada por el sistema y no puede ser modificada por el estudiante.
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($usuario['ID_DOCENTE'])): ?>
                <div class="card profile-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-chalkboard-teacher me-2"></i> Información de Docente
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-row">
                                    <strong>ID Docente:</strong> 
                                    <span class="badge bg-info"><?= $usuario['ID_DOCENTE'] ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-row">
                                    <strong>Estado:</strong> 
                                    <span class="badge <?= $usuario['ESTADO'] ? 'bg-success' : 'bg-danger' ?>">
                                        <?= $usuario['ESTADO'] ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($usuario['ID_ADMIN'])): ?>
                <div class="card profile-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user-shield me-2"></i> Información de Administrador
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-row">
                                    <strong>ID Administrador:</strong> 
                                    <span class="badge bg-danger"><?= $usuario['ID_ADMIN'] ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-row">
                                    <strong>Nivel de Acceso:</strong> 
                                    <span class="badge bg-warning">Nivel <?= $usuario['NIVEL_ACCESO'] ?? 'No definido' ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="d-flex gap-2">
                <a href="<?= BASE_URL ?>/index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Volver al Inicio
                </a>
                <?php if ($puede_reclamar): ?>
                <button type="button" class="btn btn-reclamar-rango" data-bs-toggle="modal" data-bs-target="#modalReclamarRango">
                    <i class="fas fa-<?= $tipo_usuario === 'estudiante' ? 'graduation-cap' : 'chalkboard-teacher' ?> me-2"></i> 
                    Reclamar Rango <?= ucfirst($tipo_usuario) ?>
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Reclamar Rango -->
<?php if ($puede_reclamar): ?>
<div class="modal fade" id="modalReclamarRango" tabindex="-1" aria-labelledby="modalReclamarRangoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalReclamarRangoLabel">
                    <i class="fas fa-<?= $tipo_usuario === 'estudiante' ? 'graduation-cap' : 'chalkboard-teacher' ?> me-2"></i>
                    Reclamar Rango de <?= ucfirst($tipo_usuario) ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                    <h4>Generar Código de Reclamo</h4>
                    <p class="text-muted">
                        Se generará un código único que podrás usar para reclamar tu rango de 
                        <strong><?= $tipo_usuario ?></strong> en Discord.
                    </p>
                </div>

                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-2"></i>Información importante:</h6>
                    <ul class="mb-0">
                        <li>El código será válido por <strong>5 minutos</strong></li>
                        <li>Úsalo en el servidor de Discord para reclamar tu rango</li>
                        <li>Solo puedes generar un código a la vez</li>
                        <li>Guarda el código en un lugar seguro</li>
                    </ul>
                </div>

                <div class="mb-4">
                    <label for="discord_username" class="form-label fw-bold">
                        <i class="fab fa-discord me-2"></i>Username de Discord:
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">@</span>
                        <input type="text" class="form-control" id="discord_username" name="discord_username" 
                               placeholder="tu_username" required 
                               pattern="^[a-zA-Z0-9_.]{2,32}$"
                               title="Username válido de Discord (2-32 caracteres, solo letras, números, puntos y guiones bajos)">
                    </div>
                    <div class="form-text">
                        <small>Ingresa tu username exacto de Discord (sin el #discriminador). Ejemplo: usuario123</small>
                    </div>
                    <div class="invalid-feedback" id="discordUsernameFeedback"></div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h6>Datos que se incluirán:</h6>
                        <ul class="list-unstyled">
                            <li><strong>Nombre:</strong> <?= htmlspecialchars($usuario['NOMBRE'] . ' ' . $usuario['APELLIDO']) ?></li>
                            <li><strong>DNI:</strong> <?= htmlspecialchars($usuario['DNI'] ?? 'No disponible') ?></li>
                            <li><strong>Tipo:</strong> <?= ucfirst($tipo_usuario) ?></li>
                            <li><strong>ID:</strong> <?= $tipo_usuario === 'estudiante' ? $usuario['ID_ESTUDIANTE'] : $usuario['ID_DOCENTE'] ?></li>
                            <li><strong>Discord:</strong> <span id="discord_preview">@tu_username</span></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Proceso de reclamo:</h6>
                        <ol class="small">
                            <li>Ingresa tu username de Discord</li>
                            <li>Confirma la generación del código</li>
                            <li>Copia el código generado</li>
                            <li>Ve al servidor de Discord</li>
                            <li>Usa el comando de reclamo con tu código</li>
                            <li>¡Disfruta de tu nuevo rango!</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <form method="POST" action="<?= BASE_URL ?>/index.php?accion=perfil" id="reclamoForm">
                    <input type="hidden" name="action" value="reclamar_rango">
                    <input type="hidden" name="discord_username" id="discord_username_hidden">
                    <input type="hidden" name="email_usuario" value="<?= htmlspecialchars($usuario['EMAIL'] ?? '') ?>">
                    <button type="submit" class="btn btn-success" id="btnConfirmarReclamo" disabled>
                        <i class="fas fa-key me-2"></i>Generar Código de Reclamo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    const submitBtn = document.getElementById('submitBtn');
    const confirmFeedback = document.getElementById('confirmFeedback');

    const loginTime = <?= $login_time ?>;
    const tiempoConectadoElement = document.getElementById('tiempoConectado');
    
    // Función para copiar código de reclamo
    window.copiarCodigo = function(element) {
        const texto = element.textContent.trim();
        navigator.clipboard.writeText(texto).then(function() {
            // Mostrar feedback visual
            const originalBg = element.style.backgroundColor;
            const originalColor = element.style.color;
            
            element.style.backgroundColor = '#28a745';
            element.style.color = 'white';
            element.textContent = '¡COPIADO!';
            
            setTimeout(function() {
                element.style.backgroundColor = originalBg;
                element.style.color = originalColor;
                element.textContent = texto;
            }, 1500);
            
            showToast('Código copiado al portapapeles', 'success');
        }).catch(function() {
            showToast('Error al copiar el código', 'error');
        });
    };
    
    function actualizarTiempo() {
        const tiempoActual = Math.floor(Date.now() / 1000);
        const segundosConectado = tiempoActual - loginTime;
        
        const horas = Math.floor(segundosConectado / 3600);
        const minutos = Math.floor((segundosConectado % 3600) / 60);
        const segundos = segundosConectado % 60;
        
        let texto = '';
        if (horas > 0) {
            texto = `${horas} hora(s), ${minutos} minuto(s)`;
        } else if (minutos > 0) {
            texto = `${minutos} minuto(s), ${segundos} segundo(s)`;
        } else {
            texto = `${segundos} segundo(s)`;
        }
        
        tiempoConectadoElement.textContent = texto;
    }
    
    setInterval(actualizarTiempo, 1000);

    document.getElementById('togglePassword').addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    passwordInput.addEventListener('input', function() {
        const password = this.value;
        checkPasswordStrength(password);
        validateForm();
    });

    confirmPasswordInput.addEventListener('input', function() {
        validatePasswordMatch();
        validateForm();
    });

    function checkPasswordStrength(password) {
        const requirements = [
            { id: 'req-length', regex: /.{8,}/, text: 'Mínimo 8 caracteres' },
            { id: 'req-upper', regex: /[A-Z]/, text: 'Al menos una letra mayúscula' },
            { id: 'req-lower', regex: /[a-z]/, text: 'Al menos una letra minúscula' },
            { id: 'req-number', regex: /[0-9]/, text: 'Al menos un número' },
            { id: 'req-special', regex: /[^A-Za-z0-9]/, text: 'Al menos un carácter especial' }
        ];

        let score = 0;
        requirements.forEach(req => {
            const element = document.getElementById(req.id);
            const icon = element.querySelector('i');
            
            if (req.regex.test(password)) {
                score++;
                element.classList.remove('text-muted');
                element.classList.add('text-success');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-check');
            } else {
                element.classList.remove('text-success');
                element.classList.add('text-muted');
                icon.classList.remove('fa-check');
                icon.classList.add('fa-times');
            }
        });

        strengthBar.className = 'strength-bar';
        if (password.length === 0) {
            strengthText.textContent = 'Ingresa una contraseña para ver su fortaleza';
            strengthBar.style.width = '0%';
        } else if (score < 2) {
            strengthBar.classList.add('strength-weak');
            strengthText.textContent = 'Contraseña muy débil';
        } else if (score < 3) {
            strengthBar.classList.add('strength-fair');
            strengthText.textContent = 'Contraseña débil';
        } else if (score < 5) {
            strengthBar.classList.add('strength-good');
            strengthText.textContent = 'Contraseña buena';
        } else {
            strengthBar.classList.add('strength-strong');
            strengthText.textContent = 'Contraseña fuerte';
        }

        return score === 5;
    }

    function validatePasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        if (confirmPassword === '') {
            confirmPasswordInput.classList.remove('is-valid', 'is-invalid');
            confirmFeedback.textContent = '';
            return false;
        }

        if (password === confirmPassword) {
            confirmPasswordInput.classList.remove('is-invalid');
            confirmPasswordInput.classList.add('is-valid');
            confirmFeedback.textContent = '';
            return true;
        } else {
            confirmPasswordInput.classList.remove('is-valid');
            confirmPasswordInput.classList.add('is-invalid');
            confirmFeedback.textContent = 'Las contraseñas no coinciden';
            return false;
        }
    }

    function validateForm() {
        const isPasswordStrong = checkPasswordStrength(passwordInput.value);
        const isPasswordMatch = validatePasswordMatch();
        const hasPassword = passwordInput.value.length > 0;
        const hasConfirmPassword = confirmPasswordInput.value.length > 0;

        submitBtn.disabled = !(isPasswordStrong && isPasswordMatch && hasPassword && hasConfirmPassword);
    }

    // Función para mostrar toast messages
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
        toast.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            opacity: 0;
            transition: opacity 0.3s ease;
        `;
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            ${message}
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => toast.style.opacity = '1', 100);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Validación del username de Discord en tiempo real
    const discordUsernameInput = document.getElementById('discord_username');
    const discordPreview = document.getElementById('discord_preview');
    const btnConfirmarReclamo = document.getElementById('btnConfirmarReclamo');
    const discordUsernameFeedback = document.getElementById('discordUsernameFeedback');
    const discordUsernameHidden = document.getElementById('discord_username_hidden');

    if (discordUsernameInput) {
        discordUsernameInput.addEventListener('input', function() {
            const username = this.value.trim();
            validateDiscordUsername(username);
        });

        discordUsernameInput.addEventListener('blur', function() {
            const username = this.value.trim();
            validateDiscordUsername(username);
        });
    }

    function validateDiscordUsername(username) {
        const discordRegex = /^[a-zA-Z0-9_.]{2,32}$/;
        
        // Actualizar preview
        discordPreview.textContent = username ? `@${username}` : '@tu_username';
        
        if (username === '') {
            discordUsernameInput.classList.remove('is-valid', 'is-invalid');
            discordUsernameFeedback.textContent = '';
            btnConfirmarReclamo.disabled = true;
            return false;
        }

        if (discordRegex.test(username)) {
            discordUsernameInput.classList.remove('is-invalid');
            discordUsernameInput.classList.add('is-valid');
            discordUsernameFeedback.textContent = '';
            discordUsernameHidden.value = username;
            btnConfirmarReclamo.disabled = false;
            return true;
        } else {
            discordUsernameInput.classList.remove('is-valid');
            discordUsernameInput.classList.add('is-invalid');
            
            let errorMsg = '';
            if (username.length < 2) {
                errorMsg = 'Debe tener al menos 2 caracteres';
            } else if (username.length > 32) {
                errorMsg = 'No puede tener más de 32 caracteres';
            } else {
                errorMsg = 'Solo se permiten letras, números, puntos y guiones bajos';
            }
            
            discordUsernameFeedback.textContent = errorMsg;
            btnConfirmarReclamo.disabled = true;
            return false;
        }
    }

    // Confirmación para el modal de reclamo
    if (btnConfirmarReclamo) {
        btnConfirmarReclamo.addEventListener('click', function(e) {
            const username = discordUsernameInput.value.trim();
            
            if (!validateDiscordUsername(username)) {
                e.preventDefault();
                showToast('Por favor, ingresa un username de Discord válido', 'error');
                return;
            }
            
            if (!confirm(`¿Estás seguro de que deseas generar un código de reclamo para @${username}? El código anterior (si existe) será invalidado.`)) {
                e.preventDefault();
            }
        });
    }
});
</script>

<?php require_once BASE_PATH . '/views/components/footer.php'; ?>