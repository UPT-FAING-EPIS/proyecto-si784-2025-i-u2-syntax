<?php
require_once BASE_PATH . '/views/components/head.php';
require_once BASE_PATH . '/views/components/header.php';
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] !== 1) {
    header('Location: ' . BASE_URL . '/public?accion=login');
    exit;
}

$mensaje = $_SESSION['mensaje'] ?? '';
$tipo_mensaje = $_SESSION['tipo_mensaje'] ?? '';
$error = $_SESSION['error'] ?? '';
$datos_estudiante = $_SESSION['datos_estudiante'] ?? null;
$codigo_enviado = $_SESSION['codigo_enviado'] ?? false;

unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje'], $_SESSION['error']);
?>

<style>
:root {
    --primary-blue: #1e3a5f;
    --secondary-blue: #2c5282;
    --accent-blue: #3182ce;
    --light-blue: #ebf8ff;
    --dark-blue: #1a202c;
    --success-green: #38a169;
    --warning-orange: #ed8936;
    --danger-red: #e53e3e;
    --light-gray: #f7fafc;
    --border-gray: #e2e8f0;
    --text-gray: #4a5568;
    --gradient-primary: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    --gradient-accent: linear-gradient(135deg, var(--accent-blue) 0%, var(--primary-blue) 100%);
    --shadow-sm: 0 2px 8px rgba(30, 58, 95, 0.1);
    --shadow-md: 0 4px 20px rgba(30, 58, 95, 0.15);
    --shadow-lg: 0 8px 30px rgba(30, 58, 95, 0.2);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --border-radius: 10px;
}

body {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    min-height: 100vh;
}

.vincular-container {
    min-height: calc(100vh - 120px);
    display: flex;
    align-items: center;
    padding: 2rem 0;
}

.vincular-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    max-width: 500px;
    width: 100%;
    margin: 0 auto;
    position: relative;
}

.vincular-header {
    background: var(--gradient-primary);
    color: white;
    padding: 2.5rem 2rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.vincular-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
}

.vincular-header .icon-wrapper {
    position: relative;
    z-index: 2;
    margin-bottom: 1rem;
}

.vincular-header .main-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    display: inline-block;
    animation: pulse 2s infinite;
}

.vincular-header h1 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    position: relative;
    z-index: 2;
}

.vincular-header p {
    opacity: 0.9;
    font-size: 1rem;
    margin: 0;
    position: relative;
    z-index: 2;
}

.vincular-body {
    padding: 2.5rem 2rem;
}

.form-floating {
    margin-bottom: 1.5rem;
    position: relative;
}

.form-control {
    border: 2px solid var(--border-gray);
    border-radius: var(--border-radius);
    padding: 1rem 1rem 1rem 3rem;
    font-size: 1rem;
    transition: var(--transition);
    background: var(--light-gray);
}

.form-control:focus {
    border-color: var(--accent-blue);
    box-shadow: 0 0 0 0.2rem rgba(49, 130, 206, 0.25);
    background: white;
}

.form-control:read-only {
    background-color: #f8f9fa;
    opacity: 1;
}

.form-floating .form-control {
    height: 60px;
    padding-left: 3rem; 
    padding-top: 1.5rem; 
    padding-bottom: 0.5rem; 
    font-size: 1rem;
    line-height: 1.2;
}

.form-floating > label {
    padding-left: 5rem;
    color: var(--text-gray);
    font-weight: 500;
}

.input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    pointer-events: none;
    transform: translateY(-50%);
    color: var(--text-gray);
    font-size: 1.1rem;
    z-index: 5;
    transition: var(--transition);
}

.form-floating .form-control:focus ~ .input-icon,
.form-floating .form-control:not(:placeholder-shown) ~ .input-icon {
    color: var(--accent-blue);
}

.btn-vincular {
    background: var(--gradient-accent);
    border: none;
    color: white;
    padding: 1rem 2rem;
    border-radius: var(--border-radius);
    font-weight: 600;
    font-size: 1.1rem;
    width: 100%;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.btn-vincular:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    color: white;
}

.btn-vincular:disabled {
    background: var(--border-gray);
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.info-section {
    background: var(--light-blue);
    border: 1px solid rgba(49, 130, 206, 0.2);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.info-section h6 {
    color: var(--primary-blue);
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}

.info-section h6 i {
    margin-right: 0.5rem;
    font-size: 1.1rem;
}

.info-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.info-list li {
    padding: 0.5rem 0;
    display: flex;
    align-items: flex-start;
    color: var(--text-gray);
}

.info-list li i {
    color: var(--success-green);
    margin-right: 0.75rem;
    margin-top: 0.2rem;
    flex-shrink: 0;
}

/* Modal Styles */
.modal-content {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-lg);
    text-align: center;
    max-width: 800px;
}

.modal-header-custom {
    background: var(--gradient-primary);
    color: white;
    border-bottom: none;
    padding: 2rem;
}

.modal-content input[type="text"] {
    width: 100%;
    padding: 50px;
    margin: 5px 0;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

.modal-header-custom h5 {
    font-weight: 700;
    margin: 0;
}

.btn-close-white {
    filter: brightness(0) invert(1);
    opacity: 0.8;
}

.btn-close-white:hover {
    opacity: 1;
}

.modal-body-custom {
    padding: 2rem;
}

.validation-step {
    text-align: center;
    margin-bottom: 2rem;
}

.step-indicator {
    display: flex;
    justify-content: center;
    margin-bottom: 2rem;
    position: relative;
}

.step {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin: 0 1rem;
    position: relative;
    transition: var(--transition);
}

.step.active {
    background: var(--accent-blue);
    color: white;
    box-shadow: 0 0 0 4px rgba(49, 130, 206, 0.2);
}

.step.completed {
    background: var(--success-green);
    color: white;
}

.step.inactive {
    background: var(--border-gray);
    color: var(--text-gray);
}

.step-connector {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translateY(-50%);
    width: 60px;
    height: 2px;
    background: var(--border-gray);
    z-index: -1;
}

.step-connector.completed {
    background: var(--success-green);
}

.code-input-section {
    opacity: 0.5;
    pointer-events: none;
    transition: var(--transition);
}

.code-input-section.active {
    opacity: 1;
    pointer-events: auto;
}

/* CÓDIGO DE VERIFICACIÓN MEJORADO */
.code-inputs {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin: 2rem 0;
}

.code-digit {
    width: 50px !important;
    height: 60px !important;
    text-align: center !important;
    font-size: 1.8rem !important; /* Aumentado para mejor visibilidad */
    font-weight: 800 !important; /* Más grueso */
    border: 3px solid var(--border-gray) !important; /* Borde más grueso */
    border-radius: var(--border-radius);
    background: white !important; /* Fondo completamente blanco */
    transition: var(--transition);
    color: #1a202c !important; /* Color muy oscuro para máximo contraste */
    padding: 0 !important; /* Eliminar padding que puede interferir */
    line-height: 1.2 !important; /* Centrado vertical mejorado */
    outline: none !important; /* Eliminar outline por defecto */
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1) !important; /* Sombra interna para profundidad */
}

.code-digit:focus {
    border-color: var(--accent-blue) !important;
    box-shadow: 0 0 0 0.2rem rgba(49, 130, 206, 0.25), inset 0 1px 3px rgba(0, 0, 0, 0.1) !important;
    background: white !important;
    color: #1a202c !important; /* Color muy oscuro en focus */
}

.code-digit:valid,
.code-digit.is-valid {
    border-color: var(--success-green) !important;
    background: #f0fff4 !important; /* Fondo ligeramente verde */
    color: #22543d !important; /* Verde muy oscuro para contraste */
}

.code-digit.is-invalid {
    border-color: var(--danger-red) !important;
    background: #fff5f5 !important; /* Fondo ligeramente rojo */
    color: #742a2a !important; /* Rojo muy oscuro para contraste */
    animation: shake 0.3s ease-in-out;
}

/* Eliminar spinners de número */
.code-digit::-webkit-outer-spin-button,
.code-digit::-webkit-inner-spin-button {
    -webkit-appearance: none !important;
    margin: 0 !important;
}

.code-digit[type=number] {
    -moz-appearance: textfield !important;
}

.code-digit::placeholder {
    color: transparent !important;
}
/* Eliminar spinners de input number */
.code-digit::-webkit-outer-spin-button,
.code-digit::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.code-digit[type=number] {
    -moz-appearance: textfield;
}

.code-digit::placeholder {
    color: transparent;
}

.success-message {
    text-align: center;
    padding: 2rem;
    color: var(--success-green);
}

.success-message i {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.alert-custom {
    border: none;
    border-radius: var(--border-radius);
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
}

.alert-info-custom {
    background: rgba(49, 130, 206, 0.1);
    color: var(--primary-blue);
    border-left: 4px solid var(--accent-blue);
}

.alert-success-custom {
    background: rgba(56, 161, 105, 0.1);
    color: var(--success-green);
    border-left: 4px solid var(--success-green);
}

.alert-danger-custom {
    background: rgba(229, 62, 62, 0.1);
    color: var(--danger-red);
    border-left: 4px solid var(--danger-red);
}

/* Estilos adicionales para mejor UX */
.btn-pulse {
    animation: pulse-success 1.5s infinite;
}

@keyframes pulse-success {
    0% {
        box-shadow: 0 0 0 0 rgba(56, 161, 105, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(56, 161, 105, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(56, 161, 105, 0);
    }
}

.clearing {
    background: rgba(229, 62, 62, 0.1) !important;
    transform: scale(0.95);
}

#emailDestino {
    color: var(--primary-blue);
    font-weight: 600;
    word-break: break-all;
}

.code-inputs.error {
    animation: shake 0.5s ease-in-out;
}

.btn-link {
    color: var(--accent-blue);
    text-decoration: none;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    transition: var(--transition);
}

.btn-link:hover {
    color: var(--primary-blue);
    background: rgba(49, 130, 206, 0.1);
    text-decoration: none;
}

.alert-info-custom strong {
    color: var(--primary-blue);
}

/* Animaciones */
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.slide-in-up {
    animation: slideInUp 0.6s ease-out;
}

.fade-in {
    animation: fadeIn 0.4s ease-out;
}

/* Responsive */
@media (max-width: 768px) {
    .vincular-container {
        padding: 1rem;
    }

    .vincular-header {
        padding: 2rem 1.5rem;
    }

    .vincular-body {
        padding: 2rem 1.5rem;
    }

    .modal-body-custom {
        padding: 1.5rem;
    }

    .code-inputs {
        gap: 0.25rem;
    }

    .code-digit {
        width: 40px;
        height: 50px;
        font-size: 1.25rem;
    }

    .step {
        width: 35px;
        height: 35px;
        margin: 0 0.5rem;
    }
}

@media (max-width: 576px) {
    .modal-body-custom {
        padding: 1.5rem 1rem;
    }
    
    .code-inputs {
        margin: 1.5rem 0;
        gap: 0.3rem;
    }
    
    .step-indicator {
        margin-bottom: 1.5rem;
    }
    
    .step {
        width: 30px;
        height: 30px;
        font-size: 0.8rem;
        margin: 0 0.3rem;
    }
}

@media (max-width: 480px) {
    .vincular-header h1 {
        font-size: 1.5rem;
    }

    .form-control {
        padding: 0.875rem 0.875rem 0.875rem 2.5rem;
    }

    .form-floating > label {
        padding-left: 2.5rem;
    }

    .input-icon {
        left: 0.75rem;
    }

    .code-inputs {
        gap: 0.2rem;
    }

    .code-digit {
        width: 35px;
        height: 45px;
        font-size: 1.1rem;
    }
}
</style>

<div class="vincular-container">
    <div class="container">
        <div class="vincular-card slide-in-up">
            <div class="vincular-header">
                <div class="icon-wrapper">
                    <i class="fas fa-university main-icon"></i>
                </div>
                <h1>Vincularme a la UPT</h1>
                <p>Conecta tu cuenta con la Universidad Privada de Tacna</p>
            </div>

            <div class="vincular-body">
                <?php if ($mensaje): ?>
                    <div class="alert alert-<?= $tipo_mensaje ?> alert-dismissible fade show" role="alert">
                        <i class="fas fa-<?= $tipo_mensaje === 'success' ? 'check-circle' : ($tipo_mensaje === 'danger' ? 'exclamation-triangle' : 'info-circle') ?> me-2"></i>
                        <?= htmlspecialchars($mensaje) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="info-section">
                    <h6>
                        <i class="fas fa-info-circle"></i>
                        Información importante
                    </h6>
                    <ul class="info-list">
                        <li>
                            <i class="fas fa-check"></i>
                            Necesitas tu código de estudiante oficial de la UPT
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            Tu correo institucional debe estar activo
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            El proceso de validación toma unos minutos
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            Una vez validado, tendrás acceso completo al sistema
                        </li>
                    </ul>
                </div>

                <!-- FORMULARIO MODIFICADO PARA BUSCAR ESTUDIANTE -->
                <form method="POST" action="<?= BASE_URL ?>/index.php?accion=buscar_estudiante" id="codigoForm">
                    <div class="form-floating">
                        <input type="text" 
                               class="form-control" 
                               id="codigoEstudiante" 
                               name="codigo_estudiante"
                               placeholder="Código de estudiante"
                               pattern="[0-9]{8,12}"
                               maxlength="12"
                               value="<?= htmlspecialchars($_POST['codigo_estudiante'] ?? '') ?>"
                               required>
                        <label for="codigoEstudiante">Código de Estudiante UPT</label>
                        <i class="fas fa-id-card input-icon"></i>
                    </div>

                    <button type="submit" class="btn btn-vincular" id="btnBuscar">
                        <i class="fas fa-search me-2"></i>
                        Buscar Estudiante
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ACTUALIZADO CON CAMPOS DE SOLO LECTURA -->
<div class="modal fade" id="validacionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header-custom">
                <h5 class="modal-title">
                    <i class="fas fa-shield-alt me-2"></i>
                    Validación de Estudiante UPT
                </h5>
            </div>
            <div class="modal-body-custom">
                <div class="step-indicator">
                    <div class="step active" id="step1">1</div>
                    <div class="step-connector" id="connector1"></div>
                    <div class="step inactive" id="step2">2</div>
                    <div class="step-connector" id="connector2"></div>
                    <div class="step inactive" id="step3">
                        <i class="fas fa-check"></i>
                    </div>
                </div>

                <!-- SECCIÓN 1: CONFIRMAR DATOS (CAMPOS DE SOLO LECTURA) -->
                <div id="datosSection" class="validation-step">
                    <h4 class="mb-4">Confirma tus datos</h4>
                    
                    <form method="POST" action="<?= BASE_URL ?>/index.php?accion=enviar_codigo_vinculacion" id="datosForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" 
                                           class="form-control" 
                                           id="codigoConfirm" 
                                           name="codigo_estudiante"
                                           readonly>
                                    <label for="codigoConfirm">Código de Estudiante</label>
                                    <i class="fas fa-id-card input-icon"></i>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="email" 
                                           class="form-control" 
                                           id="emailInstitucional" 
                                           name="email_institucional"
                                           readonly>
                                    <label for="emailInstitucional">Correo Institucional</label>
                                    <i class="fas fa-envelope input-icon"></i>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" 
                                           class="form-control" 
                                           id="nombres" 
                                           name="nombres"
                                           readonly>
                                    <label for="nombres">Nombres</label>
                                    <i class="fas fa-user input-icon"></i>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" 
                                           class="form-control" 
                                           id="apellidos" 
                                           name="apellidos"
                                           readonly>
                                    <label for="apellidos">Apellidos</label>
                                    <i class="fas fa-user input-icon"></i>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info-custom">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>¿Son correctos tus datos?</strong> Se enviará un código de verificación al correo institucional mostrado.
                        </div>

                        <button type="submit" class="btn btn-vincular" id="btnEnviarCodigo">
                            <i class="fas fa-paper-plane me-2"></i>
                            Enviar Código de Verificación
                        </button>
                    </form>
                </div>

                <!-- SECCIÓN 2: INGRESAR CÓDIGO DE VERIFICACIÓN -->
                <div id="codigoSection" class="validation-step code-input-section">
                    <h4 class="mb-4">Ingresa el código de verificación</h4>
                    
                    <div class="alert alert-info-custom">
                        <i class="fas fa-envelope me-2"></i>
                        Hemos enviado un código de 6 dígitos a <strong id="emailDestino"></strong>
                    </div>

                    <form method="POST" action="<?= BASE_URL ?>/index.php?accion=verificar_codigo_vinculacion" id="verificacionForm">
                        <input type="hidden" name="codigo_estudiante" id="codigoHidden">
                        
                        <div class="code-inputs">
                            <input type="text" class="form-control code-digit" name="digit_1" maxlength="1" data-index="0" inputmode="numeric" pattern="[0-9]" required>
                            <input type="text" class="form-control code-digit" name="digit_2" maxlength="1" data-index="1" inputmode="numeric" pattern="[0-9]" required>
                            <input type="text" class="form-control code-digit" name="digit_3" maxlength="1" data-index="2" inputmode="numeric" pattern="[0-9]" required>
                            <input type="text" class="form-control code-digit" name="digit_4" maxlength="1" data-index="3" inputmode="numeric" pattern="[0-9]" required>
                            <input type="text" class="form-control code-digit" name="digit_5" maxlength="1" data-index="4" inputmode="numeric" pattern="[0-9]" required>
                            <input type="text" class="form-control code-digit" name="digit_6" maxlength="1" data-index="5" inputmode="numeric" pattern="[0-9]" required>
                        </div>

                        <div class="text-center mb-3">
                            <p class="text-muted">¿No recibiste el código?</p>
                            <button type="button" class="btn btn-link" id="btnReenviar">
                                <i class="fas fa-redo me-2"></i>Reenviar código
                            </button>
                        </div>

                        <button type="submit" class="btn btn-vincular" id="btnVerificar" disabled>
                            <i class="fas fa-check me-2"></i>
                            Verificar Código
                        </button>
                    </form>
                </div>

                <!-- SECCIÓN 3: VINCULACIÓN EXITOSA -->
                <div id="exitoSection" class="validation-step" style="display: none;">
                    <div class="success-message">
                        <i class="fas fa-check-circle"></i>
                        <h4 class="mb-3">¡Vinculación Exitosa!</h4>
                        <p class="lead mb-4">Tu cuenta ha sido vinculada correctamente con la Universidad Privada de Tacna.</p>
                        <div class="alert alert-success-custom">
                            <i class="fas fa-graduation-cap me-2"></i>
                            Ahora tienes acceso completo al sistema de mentoría académica.
                        </div>
                        <a href="<?= BASE_URL ?>/index.php?accion=inicio" class="btn btn-vincular">
                            <i class="fas fa-home me-2"></i>
                            Ir al Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const codigoForm = document.getElementById('codigoForm');
    const validacionModal = new bootstrap.Modal(document.getElementById('validacionModal'));
    const datosForm = document.getElementById('datosForm');
    const verificacionForm = document.getElementById('verificacionForm');
    const codeInputs = document.querySelectorAll('.code-digit');

    // Si hay datos del estudiante en la sesión, mostrar el modal con datos prellenados
    <?php if ($datos_estudiante): ?>
        // Rellenar datos del estudiante encontrado
        document.getElementById('codigoConfirm').value = '<?= htmlspecialchars($datos_estudiante['codigo_estudiante']) ?>';
        document.getElementById('emailInstitucional').value = '<?= htmlspecialchars($datos_estudiante['email_institucional']) ?>';
        document.getElementById('nombres').value = '<?= htmlspecialchars($datos_estudiante['nombres']) ?>';
        document.getElementById('apellidos').value = '<?= htmlspecialchars($datos_estudiante['apellidos']) ?>';
        
        // Mostrar modal automáticamente
        validacionModal.show();
        
        <?php 
        // Limpiar datos de la sesión después de usarlos
        unset($_SESSION['datos_estudiante']); 
        ?>
    <?php endif; ?>

    // Si se envió el código, activar la segunda sección
    <?php if ($codigo_enviado): ?>
        activateStep(2);
        document.getElementById('emailDestino').textContent = document.getElementById('emailInstitucional').value;
        document.getElementById('codigoHidden').value = document.getElementById('codigoConfirm').value;
        document.getElementById('codigoSection').classList.add('active');
        document.getElementById('datosSection').style.display = 'none';
        codeInputs[0].focus();
        validacionModal.show();
        
        <?php 
        // Limpiar flag de la sesión
        unset($_SESSION['codigo_enviado']); 
        ?>
    <?php endif; ?>

    // Validación del formulario principal (buscar estudiante)
    codigoForm.addEventListener('submit', function(e) {
        const codigo = document.getElementById('codigoEstudiante').value.trim();
        
        if (!codigo) {
            e.preventDefault();
            showAlert('Por favor, ingresa tu código de estudiante');
            return;
        }

        if (!/^[0-9]{8,12}$/.test(codigo)) {
            e.preventDefault();
            showAlert('El código debe tener entre 8 y 12 dígitos');
            return;
        }

        const btnBuscar = document.getElementById('btnBuscar');
        btnBuscar.disabled = true;
        btnBuscar.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Buscando...';
    });

    // Validación del formulario de envío de código
    datosForm.addEventListener('submit', function(e) {
        const btnEnviar = document.getElementById('btnEnviarCodigo');
        btnEnviar.disabled = true;
        btnEnviar.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';
    });

    // Validación del formulario de verificación de código
    verificacionForm.addEventListener('submit', function(e) {
        const codigo = Array.from(codeInputs).map(input => input.value).join('');
        
        if (codigo.length !== 6) {
            e.preventDefault();
            showAlert('Por favor, ingresa el código completo de 6 dígitos');
            return;
        }

        const btnVerificar = document.getElementById('btnVerificar');
        btnVerificar.disabled = true;
        btnVerificar.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Verificando...';
    });

    // Funcionalidad mejorada de los inputs de código
    codeInputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            let value = e.target.value;
            
            // Limpiar cualquier carácter no numérico
            value = value.replace(/[^0-9]/g, '');
            
            // Solo permitir un dígito
            if (value.length > 1) {
                value = value.slice(-1);
            }
            
            e.target.value = value;
            
            // Remover clases de error
            e.target.classList.remove('is-invalid');
            
            // Agregar clase de válido si tiene contenido
            if (value) {
                e.target.classList.add('is-valid');
                // Auto-focus al siguiente input
                if (index < codeInputs.length - 1) {
                    codeInputs[index + 1].focus();
                }
            } else {
                e.target.classList.remove('is-valid');
            }
            
            // Verificar si todos los campos están llenos
            checkAllDigitsFilled();
        });

        input.addEventListener('keydown', function(e) {
            // Permitir teclas especiales
            const allowedKeys = ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'ArrowLeft', 'ArrowRight'];
            
            if (allowedKeys.includes(e.key)) {
                // Navegación con backspace
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    e.preventDefault();
                    codeInputs[index - 1].focus();
                    codeInputs[index - 1].value = '';
                    codeInputs[index - 1].classList.remove('is-valid');
                }
                
                // Navegación con flechas
                if (e.key === 'ArrowLeft' && index > 0) {
                    e.preventDefault();
                    codeInputs[index - 1].focus();
                }
                if (e.key === 'ArrowRight' && index < codeInputs.length - 1) {
                    e.preventDefault();
                    codeInputs[index + 1].focus();
                }
                return;
            }
            
            // Solo permitir números
            if (!/^[0-9]$/.test(e.key)) {
                e.preventDefault();
                return;
            }
            
            // Si ya hay un valor, reemplazarlo
            if (e.target.value) {
                e.target.value = '';
                e.target.classList.remove('is-valid');
            }
        });

        // Funcionalidad de pegar código completo mejorada
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            const digits = paste.replace(/\D/g, '').slice(0, 6);
            
            // Limpiar todos los inputs primero
            codeInputs.forEach(inp => {
                inp.value = '';
                inp.classList.remove('is-valid', 'is-invalid');
            });
            
            // Llenar con los dígitos pegados
            digits.split('').forEach((digit, i) => {
                if (codeInputs[i]) {
                    codeInputs[i].value = digit;
                    codeInputs[i].classList.add('is-valid');
                }
            });

            // Focus en el siguiente campo vacío o el último si están todos llenos
            const nextEmpty = digits.length < 6 ? digits.length : 5;
            if (codeInputs[nextEmpty]) {
                codeInputs[nextEmpty].focus();
            }
            
            checkAllDigitsFilled();
        });

        // Seleccionar todo el texto al hacer focus
        input.addEventListener('focus', function() {
            this.select();
        });
    });

    // Función para verificar si todos los dígitos están llenos
    function checkAllDigitsFilled() {
        const allFilled = Array.from(codeInputs).every(input => input.value.length === 1);
        const btnVerificar = document.getElementById('btnVerificar');
        
        if (btnVerificar) {
            btnVerificar.disabled = !allFilled;
            if (allFilled) {
                btnVerificar.classList.add('btn-pulse');
            } else {
                btnVerificar.classList.remove('btn-pulse');
            }
        }
    }

    // Botón de reenviar código
    document.getElementById('btnReenviar').addEventListener('click', function() {
        const codigo = document.getElementById('codigoHidden').value;
        
        if (!codigo) {
            showAlert('Error: Datos de sesión perdidos. Recarga la página.');
            return;
        }

        // Crear formulario dinámico para reenviar
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASE_URL ?>/index.php?accion=reenviar_codigo_vinculacion';
        form.style.display = 'none';

        const codigoInput = document.createElement('input');
        codigoInput.name = 'codigo_estudiante';
        codigoInput.value = codigo;
        form.appendChild(codigoInput);

        document.body.appendChild(form);
        form.submit();
    });

    // Función para activar pasos del proceso
    function activateStep(stepNumber) {
        // Resetear todos los pasos
        document.querySelectorAll('.step').forEach(step => {
            step.classList.remove('active', 'completed');
            step.classList.add('inactive');
        });

        document.querySelectorAll('.step-connector').forEach(connector => {
            connector.classList.remove('completed');
        });

        // Activar pasos hasta el número especificado
        for (let i = 1; i <= stepNumber; i++) {
            const step = document.getElementById(`step${i}`);
            if (i < stepNumber) {
                step.classList.remove('inactive');
                step.classList.add('completed');
            } else if (i === stepNumber) {
                step.classList.remove('inactive');
                step.classList.add('active');
            }

            // Activar conectores completados
            if (i < stepNumber) {
                const connector = document.getElementById(`connector${i}`);
                if (connector) {
                    connector.classList.add('completed');
                }
            }
        }
    }

    // Función para mostrar alertas personalizadas
    function showAlert(message, type = 'danger') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        const targetContainer = document.querySelector('.modal.show .modal-body-custom') || 
                               document.querySelector('.vincular-body');
        
        if (targetContainer) {
            targetContainer.insertBefore(alertDiv, targetContainer.firstChild);
            
            // Auto-remover alerta después de 5 segundos
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
    }

    // Función para limpiar código con animación
    function clearCodeInputs() {
        codeInputs.forEach((input, index) => {
            setTimeout(() => {
                input.value = '';
                input.classList.remove('is-valid', 'is-invalid');
                input.classList.add('clearing');
                setTimeout(() => input.classList.remove('clearing'), 200);
            }, index * 50);
        });
        
        setTimeout(() => codeInputs[0].focus(), 300);
    }

    // Función para mostrar error en código
    function showCodeError(message) {
        codeInputs.forEach(input => {
            input.classList.add('is-invalid');
            input.value = '';
        });
        
        // Shake animation
        const codeContainer = document.querySelector('.code-inputs');
        codeContainer.style.animation = 'shake 0.5s ease-in-out';
        setTimeout(() => {
            codeContainer.style.animation = '';
            codeInputs[0].focus();
        }, 500);
        
        showAlert(message, 'danger');
    }

    // Validación en tiempo real del código de estudiante
    document.getElementById('codigoEstudiante').addEventListener('input', function() {
        const codigo = this.value.replace(/\D/g, ''); // Solo números
        this.value = codigo;
        
        if (codigo.length > 0 && (codigo.length < 8 || codigo.length > 12)) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    // Auto-remover alertas de éxito después de un tiempo
    const existingAlerts = document.querySelectorAll('.alert:not(.alert-info-custom):not(.alert-success-custom)');
    existingAlerts.forEach(alert => {
        if (alert.classList.contains('alert-success')) {
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }
            }, 4000);
        }
    });

    // Prevenir múltiples envíos de formularios
    [codigoForm, datosForm, verificacionForm].forEach(form => {
        if (form) {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn && !submitBtn.disabled) {
                    setTimeout(() => submitBtn.disabled = true, 100);
                }
            });
        }
    });

    // Manejo de estados especiales desde PHP
    <?php if (isset($_SESSION['vinculacion_exitosa']) && $_SESSION['vinculacion_exitosa']): ?>
        activateStep(3);
        document.getElementById('datosSection').style.display = 'none';
        document.getElementById('codigoSection').style.display = 'none';
        document.getElementById('exitoSection').style.display = 'block';
        document.getElementById('exitoSection').classList.add('fade-in');
        validacionModal.show();
        <?php unset($_SESSION['vinculacion_exitosa']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_verificacion'])): ?>
        activateStep(2);
        document.getElementById('codigoSection').classList.add('active');
        document.getElementById('datosSection').style.display = 'none';
        codeInputs.forEach(input => {
            input.value = '';
            input.classList.add('is-invalid');
        });
        codeInputs[0].focus();
        validacionModal.show();
        showAlert('<?= addslashes($_SESSION['error_verificacion']) ?>', 'danger');
        <?php unset($_SESSION['error_verificacion']); ?>
    <?php endif; ?>
});

// Funciones globales para manejo del modal
function limpiarFormularios() {
    document.getElementById('codigoEstudiante').value = '';
    document.getElementById('emailInstitucional').value = '';
    document.getElementById('nombres').value = '';
    document.getElementById('apellidos').value = '';
    
    document.querySelectorAll('.code-digit').forEach(input => {
        input.value = '';
        input.classList.remove('is-invalid', 'is-valid');
    });
    
    document.querySelectorAll('.form-control').forEach(input => {
        input.classList.remove('is-invalid');
    });
}

function cerrarModalVinculacion() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('validacionModal'));
    if (modal) {
        modal.hide();
    }
    limpiarFormularios();
}

// Event listener para resetear modal al cerrarse
document.getElementById('validacionModal').addEventListener('hidden.bs.modal', function() {
    // Resetear indicadores de pasos
    document.querySelectorAll('.step').forEach(step => {
        step.classList.remove('active', 'completed');
        step.classList.add('inactive');
    });
    document.getElementById('step1').classList.remove('inactive');
    document.getElementById('step1').classList.add('active');
    
    // Resetear conectores
    document.querySelectorAll('.step-connector').forEach(connector => {
        connector.classList.remove('completed');
    });
    
    // Mostrar primera sección y ocultar las demás
    document.getElementById('datosSection').style.display = 'block';
    document.getElementById('codigoSection').style.display = 'block';
    document.getElementById('codigoSection').classList.remove('active');
    document.getElementById('exitoSection').style.display = 'none';
    
    // Limpiar formularios
    limpiarFormularios();
});
</script>

<?php require_once BASE_PATH . '/views/components/footer.php'; ?>