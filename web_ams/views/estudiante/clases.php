<?php
require_once BASE_PATH . '/models/Usuario.php';
require_once BASE_PATH . '/models/ClaseModel.php';

if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['rol_id'], [2])) {
    header('Location: ' . BASE_URL . '/index.php?accion=login');
    exit;
}

$usuarioModel = new Usuario();
$claseModel = new ClaseModel();

$id_estudiante = $usuarioModel->obtenerIdEstudiante($_SESSION['usuario_id']);

if (!$id_estudiante) {
    $error = "No se encontró información de estudiante para este usuario.";
    $clases = [];
} else {
    $clases = $claseModel->obtenerClasesEstudiante($id_estudiante);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'calificar') {
    $id_clase = $_POST['id_clase'] ?? 0;
    $puntuacion = $_POST['puntuacion'] ?? 0;
    $comentario = $_POST['comentario'] ?? '';
    
    try {
        if ($puntuacion < 1 || $puntuacion > 5) {
            throw new Exception("La puntuación debe estar entre 1 y 5");
        }
        
        $resultado = $claseModel->calificarMentor($id_estudiante, $id_clase, $puntuacion, $comentario);
        
        if ($resultado) {
            $mensaje = "Calificación enviada correctamente";
            $tipo_mensaje = "success";
            $clases = $claseModel->obtenerClasesEstudiante($id_estudiante);
        } else {
            throw new Exception("No se pudo enviar la calificación");
        }
        
    } catch (Exception $e) {
        $mensaje = "Error: " . $e->getMessage();
        $tipo_mensaje = "danger";
    }
}

$estados_clase = [
    1 => ['texto' => 'Activa', 'clase' => 'activo'],
    2 => ['texto' => 'En Proceso', 'clase' => 'proceso'],
    3 => ['texto' => 'Finalizado', 'clase' => 'finalizado'],
    5 => ['texto' => 'Cerrado', 'clase' => 'cerrado']
];

require_once BASE_PATH . '/views/components/head.php';
require_once BASE_PATH . '/views/components/header.php';
?>

<style>
:root {
    --primary-blue: #1e3a5f;
    --secondary-blue: #2c5282;
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

.clases-container {
    background-color: #f8f9fa;
    min-height: calc(100vh - 120px);
    padding: 2rem 0;
}

.clase-card {
    border: none !important;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
    border-radius: 12px !important;
    overflow: hidden !important;
    margin-bottom: 1.5rem !important;
    background: white !important;
    transition: all 0.3s ease !important;
}

.clase-card:hover {
    box-shadow: 0 0.5rem 1.5rem rgba(60, 90, 166, 0.15);
    transform: translateY(-3px);
}

.clase-header {
    background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%) !important;
    color: white !important;
    padding: 1rem 1.5rem !important;
    position: relative !important;
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    min-height: 80px !important;
}

.clase-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    transform: rotate(45deg);
}

.titulo-info {
    display: flex !important;
    flex-direction: column !important;
    gap: 0.25rem !important;
    z-index: 1;
    position: relative;
}

.clase-titulo {
    color: white !important;
    font-size: 1.2rem !important;
    font-weight: 600 !important;
    margin: 0 !important;
    text-shadow: 0 1px 3px rgba(0,0,0,0.3) !important;
    line-height: 1.2 !important;
}

.clase-codigo {
    color: rgba(255,255,255,0.9) !important;
    font-size: 0.85rem !important;
    margin: 0 !important;
    line-height: 1 !important;
}

.estado-badge {
    position: static !important;
    padding: 0.3rem 0.7rem !important;
    border-radius: 15px !important;
    font-size: 0.7rem !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    white-space: nowrap !important;
    z-index: 1;
    position: relative;
}

/* Estados del badge */
.estado-activo {
    background-color: var(--success-green);
    color: white;
    box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
}

.estado-proceso {
    background-color: #f59e0b;
    color: white;
    box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3);
}

.estado-finalizado {
    background-color: #3b82f6;
    color: white;
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
}

.estado-cerrado {
    background-color: var(--danger-red);
    color: white;
    box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
}

.clase-body {
    padding: 1rem 1.5rem !important; 
}

.info-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)) !important; 
    gap: 0.75rem !important; 
    margin-bottom: 1rem !important; 
}

.info-item {
    background: #f8f9fa !important;
    padding: 0.75rem !important; 
    border-radius: 6px !important;
    border-left: 3px solid #1e3a5f !important; 
}

.info-label {
    font-size: 0.7rem !important;
    font-weight: 600 !important;
    color: #6c757d !important;
    text-transform: uppercase !important;
    margin-bottom: 0.2rem !important;
    line-height: 1 !important;
}

.info-value {
    font-size: 0.9rem !important; 
    font-weight: 500 !important;
    color: #1e3a5f !important;
    line-height: 1.2 !important;
}

.link-clase {
    color: #1e3a5f !important;
    text-decoration: none !important;
    font-weight: 600 !important;
    transition: all 0.3s ease !important;
}

.link-clase:hover {
    color: var(--accent-blue) !important;
    text-decoration: underline !important;
}

.no-link {
    color: #6c757d !important;
    font-style: italic !important;
}

.acciones-container {
    border-top: 1px solid var(--border-gray);
    padding-top: 1.5rem;
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.btn-calificar {
    background: linear-gradient(135deg, #9f9696 0%, rgb(182, 180, 176) 100%) !important;
    border: none !important;
    color: white !important;
    padding: 0.5rem 1rem !important; 
    border-radius: 6px !important;
    font-weight: 600 !important;
    font-size: 0.85rem !important; 
    text-decoration: none !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.4rem !important;
    transition: all 0.3s ease !important;
    white-space: nowrap !important;
    cursor: pointer !important;
}

.btn-calificar:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(159, 150, 150, 0.4);
    color: white;
}

.btn-calificar:disabled {
    background: #6c757d !important;
    cursor: not-allowed !important;
    opacity: 0.6 !important;
}

.ya-calificado {
    background: #e8f0fe !important;
    color: #2d4482 !important;
    padding: 0.5rem 1rem !important; 
    border-radius: 6px !important;
    text-align: center !important;
    font-weight: 500 !important;
    font-size: 0.85rem !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.4rem !important;
}

.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    background: white;
    border-radius: 1rem;
    box-shadow: 0 0.125rem 0.25rem rgba(60, 90, 166, 0.1);
}

.empty-icon {
    font-size: 4rem;
    color: var(--accent-blue);
    margin-bottom: 1rem;
}

.page-header {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
    border-radius: 0 0 1rem 1rem;
}

.rating-stars {
    display: inline-flex;
    gap: 0.25rem;
}

.star {
    font-size: 1.2rem;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s ease;
}

.star.active,
.star:hover {
    color: var(--warning-orange);
}

.modal-header {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    color: white;
    border-bottom: none;
}

.modal-content {
    border: none;
    border-radius: 0.75rem;
    overflow: hidden;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.clase-card {
    animation: fadeInUp 0.5s ease-out;
}

.clase-card:nth-child(1) { animation-delay: 0.1s; }
.clase-card:nth-child(2) { animation-delay: 0.2s; }
.clase-card:nth-child(3) { animation-delay: 0.3s; }
.clase-card:nth-child(4) { animation-delay: 0.4s; }

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .acciones-container {
        flex-direction: column;
    }
    
    .clase-body {
        padding: 1rem;
    }
    
    .estado-badge {
        position: static;
        margin-top: 0.5rem;
        display: inline-block;
    }
    
    .clase-header {
        padding: 1rem;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}
</style>

<div class="clases-container">
    <div class="container">
        <div class="page-header">
            <div class="container">
                <h1 class="mb-0">
                    <i class="fas fa-chalkboard-teacher me-3"></i>
                    Mis Clases
                </h1>
                <p class="mb-0 mt-2 opacity-75">Gestiona tus clases inscritas y califica a tus mentores</p>
            </div>
        </div>

        <?php if (isset($mensaje)): ?>
            <div class="alert alert-<?= $tipo_mensaje ?> alert-dismissible fade show" role="alert">
                <i class="fas fa-<?= $tipo_mensaje === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
                <?= htmlspecialchars($mensaje) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (empty($clases)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h3 class="text-muted">No tienes clases inscritas</h3>
                <p class="text-muted mb-4">
                    Aún no te has inscrito en ninguna clase. ¡Explora las clases disponibles y comienza tu aprendizaje!
                </p>
                <a href="<?= BASE_URL ?>/index.php?accion=solicitar" class="btn btn-primary btn-lg">
                    <i class="fas fa-search me-2"></i>
                    Buscar Clases
                </a>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($clases as $clase): ?>
                    <?php 
                    $estado_actual = $estados_clase[$clase['ESTADO_CLASE']] ?? ['texto' => 'Desconocido', 'clase' => 'cerrado'];
                    ?>
                    <div class="col-lg-6 col-xl-4">
                        <div class="clase-card">
                            <div class="clase-header">
                                <div class="titulo-info">
                                    <h4 class="clase-titulo">
                                        <?= htmlspecialchars($clase['CURSO_NOMBRE']) ?>
                                    </h4>
                                    <p class="clase-codigo">
                                        Código: <?= htmlspecialchars($clase['CURSO_CODIGO']) ?>
                                    </p>
                                </div>
                                <div class="estado-badge estado-<?= $estado_actual['clase'] ?>">
                                    <?= $estado_actual['texto'] ?>
                                </div>
                            </div>
                            
                            <div class="clase-body">
                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-clock me-1"></i>
                                            Horario
                                        </div>
                                        <div class="info-value">
                                            <?= htmlspecialchars($clase['HORARIO']) ?>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            Inicio
                                        </div>
                                        <div class="info-value">
                                            <?= date('d/m/Y', strtotime($clase['FECHA_INICIO'])) ?>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-calendar-check me-1"></i>
                                            Fin
                                        </div>
                                        <div class="info-value">
                                            <?= date('d/m/Y', strtotime($clase['FECHA_FIN'])) ?>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-door-open me-1"></i>
                                            Aula
                                        </div>
                                        <div class="info-value">
                                            <?= htmlspecialchars($clase['AULA_NOMBRE'] ?? 'Virtual') ?>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-layer-group me-1"></i>
                                            Ciclo
                                        </div>
                                        <div class="info-value">
                                            <?= htmlspecialchars($clase['CICLO_NOMBRE']) ?>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-users me-1"></i>
                                            Capacidad
                                        </div>
                                        <div class="info-value">
                                            <?= $clase['INSCRITOS'] ?>/<?= $clase['CAPACIDAD'] ?>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-link me-1"></i>
                                            Link de Clase
                                        </div>
                                        <div class="info-value">
                                            <?php if (!empty($clase['ENLACE'])): ?>
                                                <a href="<?= htmlspecialchars($clase['ENLACE']) ?>" 
                                                   class="link-clase" 
                                                   target="_blank" 
                                                   rel="noopener noreferrer"
                                                   title="Ir a la clase">
                                                    <i class="fas fa-external-link-alt me-1"></i>
                                                    Unirse a clase
                                                </a>
                                            <?php else: ?>
                                                <span class="no-link">No disponible</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="acciones-container">
                                    <?php if ($clase['PUEDE_CALIFICAR']): ?>
                                        <?php if ($clase['YA_CALIFICO']): ?>
                                            <div class="ya-calificado">
                                                <i class="fas fa-check-circle me-2"></i>
                                                Ya calificaste esta clase
                                            </div>
                                        <?php else: ?>
                                            <button type="button" class="btn-calificar" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalCalificar"
                                                    data-clase-id="<?= $clase['ID_CLASE'] ?>"
                                                    data-clase-nombre="<?= htmlspecialchars($clase['CURSO_NOMBRE']) ?>">
                                                <i class="fas fa-star"></i>
                                                Calificar Mentor
                                            </button>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <button type="button" class="btn-calificar" disabled>
                                            <i class="fas fa-lock"></i>
                                            Clase no finalizada
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="modalCalificar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-star me-2"></i>
                    Calificar Mentor
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="accion" value="calificar">
                    <input type="hidden" name="id_clase" id="modal_clase_id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Clase:</label>
                        <p class="text-muted" id="modal_clase_nombre"></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Puntuación:</label>
                        <div class="rating-stars">
                            <span class="star" data-value="1">★</span>
                            <span class="star" data-value="2">★</span>
                            <span class="star" data-value="3">★</span>
                            <span class="star" data-value="4">★</span>
                            <span class="star" data-value="5">★</span>
                        </div>
                        <input type="hidden" name="puntuacion" id="puntuacion_input" required>
                        <small class="form-text text-muted">Haz clic en las estrellas para calificar</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="comentario" class="form-label fw-bold">Comentario (opcional):</label>
                        <textarea class="form-control" id="comentario" name="comentario" rows="3"
                                  placeholder="Comparte tu experiencia con el mentor..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btn_enviar_calificacion" disabled>
                        <i class="fas fa-paper-plane me-2"></i>
                        Enviar Calificación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalCalificar = document.getElementById('modalCalificar');
    const stars = document.querySelectorAll('.star');
    const puntuacionInput = document.getElementById('puntuacion_input');
    const btnEnviar = document.getElementById('btn_enviar_calificacion');

    modalCalificar.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const claseId = button.getAttribute('data-clase-id');
        const claseNombre = button.getAttribute('data-clase-nombre');
        
        document.getElementById('modal_clase_id').value = claseId;
        document.getElementById('modal_clase_nombre').textContent = claseNombre;
        
        stars.forEach(star => star.classList.remove('active'));
        puntuacionInput.value = '';
        btnEnviar.disabled = true;
        document.getElementById('comentario').value = '';
    });

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const value = parseInt(this.getAttribute('data-value'));
            puntuacionInput.value = value;
            btnEnviar.disabled = false;
            
            stars.forEach((s, index) => {
                if (index < value) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
            });
        });
        
        star.addEventListener('mouseenter', function() {
            const value = parseInt(this.getAttribute('data-value'));
            stars.forEach((s, index) => {
                if (index < value) {
                    s.style.color = '#ffc107';
                } else {
                    s.style.color = '#ddd';
                }
            });
        });
    });
    
    document.querySelector('.rating-stars').addEventListener('mouseleave', function() {
        const currentValue = parseInt(puntuacionInput.value) || 0;
        stars.forEach((s, index) => {
            if (index < currentValue) {
                s.style.color = '#ffc107';
            } else {
                s.style.color = '#ddd';
            }
        });
    });
});
</script>

<?php require_once BASE_PATH . '/views/components/footer.php'; ?>