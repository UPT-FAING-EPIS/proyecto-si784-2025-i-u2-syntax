<?php
$pageTitle = "Tomar Clases";
require_once BASE_PATH . '/views/components/head.php';
require_once BASE_PATH . '/views/components/header.php';
require_once BASE_PATH . '/models/DocenteModel.php';

$docenteModel = new DocenteModel();
$docente = $docenteModel->obtenerIdDocente($_SESSION['usuario_id']);

if (!$docente) {
    echo "<div class='alert alert-danger'>Error: No se encontró el perfil de docente.</div>";
    require_once BASE_PATH . '/views/components/footer.php';
    exit;
}

$clases_disponibles = $docenteModel->obtenerClasesDisponibles();

$estados_clase = [
    1 => ['texto' => 'Activa', 'clase' => 'activo'],
    2 => ['texto' => 'En Proceso', 'clase' => 'proceso'],
    3 => ['texto' => 'Finalizado', 'clase' => 'finalizado'],
    5 => ['texto' => 'Cerrado', 'clase' => 'cerrado']
];
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

.estado-disponible {
    background-color: var(--success-green);
    color: white;
    box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
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

.acciones-container {
    border-top: 1px solid var(--border-gray);
    padding-top: 1.5rem;
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    justify-content: center;
}

.btn-tomar {
    background: linear-gradient(135deg, var(--success-green) 0%, #20c997 100%) !important;
    color: white !important;
    padding: 0.7rem 2rem !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    font-size: 0.9rem !important;
    text-decoration: none !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.5rem !important;
    transition: all 0.3s ease !important;
    border: none !important;
    cursor: pointer !important;
    box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
}

.btn-tomar:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(40, 167, 69, 0.4);
    color: white;
}

.page-header {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
    border-radius: 0 0 1rem 1rem;
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
                    <i class="fas fa-hand-paper me-3"></i>
                    Tomar Clases Disponibles
                </h1>
                <p class="mb-0 mt-2 opacity-75">Clases sin mentor asignado - Conviértete en mentor</p>
            </div>
        </div>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-<?= $_SESSION['tipo_mensaje'] ?? 'info' ?> alert-dismissible fade show" role="alert">
                <i class="fas fa-<?= ($_SESSION['tipo_mensaje'] ?? 'info') === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
                <?= htmlspecialchars($_SESSION['mensaje']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php 
            unset($_SESSION['mensaje']);
            unset($_SESSION['tipo_mensaje']);
            ?>
        <?php endif; ?>

        <?php if (empty($clases_disponibles)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <h3 class="text-muted">No hay clases disponibles</h3>
                <p class="text-muted mb-4">
                    Actualmente no hay clases sin mentor asignado. Vuelve más tarde para ver nuevas oportunidades.
                </p>
                <a href="<?= BASE_URL ?>/index.php?accion=clases_asignadas" class="btn btn-primary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>
                    Volver a Mis Clases
                </a>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($clases_disponibles as $clase): ?>
                    <div class="col-lg-6 col-xl-4">
                        <div class="clase-card">
                            <div class="clase-header">
                                <div class="titulo-info">
                                    <h4 class="clase-titulo">
                                        <?= htmlspecialchars($clase['NOMBRE_CURSO']) ?>
                                    </h4>
                                    <p class="clase-codigo">
                                        Código: <?= htmlspecialchars($clase['CODIGO_CURSO']) ?>
                                    </p>
                                </div>
                                <div class="estado-badge estado-disponible">
                                    <i class="fas fa-hand-paper me-1"></i>
                                    Disponible
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
                                            <i class="fas fa-layer-group me-1"></i>
                                            Ciclo
                                        </div>
                                        <div class="info-value">
                                            <?= htmlspecialchars($clase['NOMBRE_CICLO']) ?>
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
                                            <i class="fas fa-users me-1"></i>
                                            Participantes
                                        </div>
                                        <div class="info-value">
                                            <?= $clase['PARTICIPANTES'] ?>/<?= $clase['CAPACIDAD'] ?>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-book me-1"></i>
                                            Semestre
                                        </div>
                                        <div class="info-value">
                                            <?= htmlspecialchars($clase['SEMESTRE']) ?>
                                        </div>
                                    </div>

                                    <?php if (!empty($clase['RAZON'])): ?>
                                    <div class="info-item" style="grid-column: 1 / -1;">
                                        <div class="info-label">
                                            <i class="fas fa-comment-alt me-1"></i>
                                            Descripción
                                        </div>
                                        <div class="info-value">
                                            <?= htmlspecialchars($clase['RAZON']) ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="acciones-container">
                                    <button type="button" 
                                            class="btn-tomar" 
                                            onclick="mostrarModalTomar(<?= $clase['ID_CLASE'] ?>, '<?= htmlspecialchars($clase['NOMBRE_CURSO'], ENT_QUOTES) ?>', '<?= htmlspecialchars($clase['CODIGO_CURSO'], ENT_QUOTES) ?>', '<?= htmlspecialchars($clase['NOMBRE_CICLO'], ENT_QUOTES) ?>', '<?= htmlspecialchars($clase['HORARIO'], ENT_QUOTES) ?>', <?= $clase['PARTICIPANTES'] ?>, <?= $clase['CAPACIDAD'] ?>)">
                                        <i class="fas fa-hand-paper"></i>
                                        Tomar Clase
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Confirmar Tomar Clase -->
<div class="modal fade" id="tomarClaseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-hand-paper me-2"></i>
                    Confirmar - Tomar Clase
                </h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>¿Estás seguro de que deseas tomar esta clase como mentor?</strong>
                    <br><small>Una vez confirmado, serás asignado como mentor de esta clase y podrás gestionarla.</small>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">Curso:</div>
                            <div class="info-value" id="cursoTomar"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">Código:</div>
                            <div class="info-value" id="codigoTomar"></div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">Ciclo:</div>
                            <div class="info-value" id="cicloTomar"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">Horario:</div>
                            <div class="info-value" id="horarioTomar"></div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">Participantes:</div>
                            <div class="info-value" id="participantesTomar"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">ID de Clase:</div>
                            <div class="info-value" id="idClaseTomar"></div>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="idClaseInput">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    Cancelar
                </button>
                <button type="button" class="btn btn-success" onclick="confirmarTomarClase()">
                    <i class="fas fa-check me-2"></i>
                    Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let claseSeleccionada = null;

function mostrarNotificacion(tipo, titulo, mensaje) {
    const notificacion = document.createElement('div');
    notificacion.className = `alert alert-${tipo} alert-dismissible fade show position-fixed`;
    notificacion.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    
    notificacion.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${getIconoTipo(tipo)} me-2"></i>
            <div>
                <strong>${titulo}</strong><br>
                <small>${mensaje}</small>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notificacion);
    
    setTimeout(() => {
        if (notificacion.parentNode) {
            notificacion.remove();
        }
    }, 5000);
}

function getIconoTipo(tipo) {
    const iconos = {
        'success': 'check-circle',
        'warning': 'exclamation-triangle',
        'danger': 'exclamation-circle',
        'info': 'info-circle'
    };
    return iconos[tipo] || 'info-circle';
}

function mostrarModalTomar(idClase, nombreCurso, codigoCurso, ciclo, horario, participantes, capacidad) {
    claseSeleccionada = idClase;
    
    document.getElementById('cursoTomar').textContent = nombreCurso;
    document.getElementById('codigoTomar').textContent = codigoCurso;
    document.getElementById('cicloTomar').textContent = ciclo;
    document.getElementById('horarioTomar').textContent = horario;
    document.getElementById('participantesTomar').textContent = `${participantes}/${capacidad}`;
    document.getElementById('idClaseTomar').textContent = `#${idClase}`;
    document.getElementById('idClaseInput').value = idClase;
    
    new bootstrap.Modal(document.getElementById('tomarClaseModal')).show();
}

function confirmarTomarClase() {
    if (!claseSeleccionada) return;
    
    const submitBtn = document.querySelector('#tomarClaseModal .btn-success');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Procesando...';
    
    fetch('<?= BASE_URL ?>/index.php?accion=procesar_tomar_clase', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id_clase=${claseSeleccionada}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('tomarClaseModal')).hide();
            mostrarNotificacion('success', 'Clase tomada exitosamente', data.message);
            
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            mostrarNotificacion('danger', 'Error al tomar clase', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarNotificacion('danger', 'Error', 'Error al procesar la solicitud');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}
</script>

<?php require_once BASE_PATH . '/views/components/footer.php'; ?>