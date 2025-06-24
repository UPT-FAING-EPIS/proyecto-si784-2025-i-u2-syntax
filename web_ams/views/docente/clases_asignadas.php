<?php
$pageTitle = "Clases Asignadas";
require_once BASE_PATH . '/views/components/head.php';
require_once BASE_PATH . '/views/components/header.php';

// Definir los estados disponibles
$estados_clase = [
    1 => ['texto' => 'Activa', 'clase' => 'activo'],
    2 => ['texto' => 'En Proceso', 'clase' => 'proceso'],
    3 => ['texto' => 'Finalizado', 'clase' => 'finalizado'],
    5 => ['texto' => 'Cerrado', 'clase' => 'cerrado']
];

// Funciones auxiliares para botones
function obtenerTextoBotonEmpezar($estado_clase) {
    switch($estado_clase) {
        case 1: return 'Empezar';
        case 2: return 'En Proceso';
        case 3: return 'Reanudar';
        case 5: return 'Clase Cerrada';
        default: return 'No Disponible';
    }
}

function obtenerClaseBotonEmpezar($estado_clase) {
    switch($estado_clase) {
        case 1: 
        case 3: return 'btn-empezar';
        case 2: return 'btn-proceso';
        case 5: return 'btn-cerrado';
        default: return 'btn-disabled';
    }
}

function obtenerIconoBoton($estado_clase) {
    switch($estado_clase) {
        case 1: return 'play';
        case 2: return 'spinner fa-spin';
        case 3: return 'redo';
        case 5: return 'lock';
        default: return 'ban';
    }
}
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

.btn-accion {
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
    border: none !important;
    cursor: pointer !important;
}

.btn-empezar {
    background: linear-gradient(135deg, var(--success-green) 0%, #20c997 100%) !important;
    color: white !important;
}

.btn-empezar:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
    color: white;
}

.btn-calificar {
    background: linear-gradient(135deg, var(--warning-orange) 0%, #ffca2c 100%) !important;
    color: white !important;
}

.btn-calificar:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.4);
    color: white;
}

.btn-cerrar {
    background: linear-gradient(135deg, var(--danger-red) 0%, #e55a5a 100%) !important;
    color: white !important;
}

.btn-cerrar:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
    color: white;
}

/* Nuevos estilos para estados adicionales */
.btn-proceso {
    background: linear-gradient(135deg, #6c757d 0%, #85939e 100%) !important;
    color: white !important;
    cursor: not-allowed !important;
    opacity: 0.8 !important;
}

.btn-proceso:disabled {
    background: linear-gradient(135deg, #6c757d 0%, #85939e 100%) !important;
    color: white !important;
    cursor: not-allowed !important;
    opacity: 0.6 !important;
}

.btn-cerrado:disabled {
    background: linear-gradient(135deg, #495057 0%, #6c757d 100%) !important;
    color: rgba(255, 255, 255, 0.7) !important;
    cursor: not-allowed !important;
    opacity: 0.6 !important;
}

.btn-disabled {
    background: #6c757d !important;
    color: rgba(255, 255, 255, 0.7) !important;
    cursor: not-allowed !important;
    opacity: 0.6 !important;
}

.btn-accion:not(:disabled):hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

/* Tooltip personalizado */
.btn-accion[disabled][title] {
    position: relative;
}

.btn-accion[disabled][title]:hover::after {
    content: attr(title);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    white-space: nowrap;
    z-index: 1000;
    pointer-events: none;
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

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.fa-spinner.fa-spin {
    animation: spin 1s linear infinite;
}

.clase-card {
    animation: fadeInUp 0.5s ease-out;
}

.clase-card:nth-child(1) { animation-delay: 0.1s; }
.clase-card:nth-child(2) { animation-delay: 0.2s; }
.clase-card:nth-child(3) { animation-delay: 0.3s; }
.clase-card:nth-child(4) { animation-delay: 0.4s; }

.btn-group {
    display: flex;
    flex-direction: row;
    gap: 0.5rem;
    width: 100%;
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
    
    .btn-group {
        flex-direction: column;
    }
}
</style>

<div class="clases-container">
    <div class="container">
        <div class="page-header">
            <div class="container">
                <h1 class="mb-0">
                    <i class="fas fa-chalkboard-teacher me-3"></i>
                    Mis Clases Asignadas
                </h1>
                <p class="mb-0 mt-2 opacity-75">Gestiona tus clases, estudiantes y calificaciones</p>
            </div>
        </div>

        <?php if (isset($mensaje)): ?>
            <div class="alert alert-<?= $tipo_mensaje ?> alert-dismissible fade show" role="alert">
                <i class="fas fa-<?= $tipo_mensaje === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
                <?= htmlspecialchars($mensaje) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($clases)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-chalkboard"></i>
                </div>
                <h3 class="text-muted">No tienes clases asignadas</h3>
                <p class="text-muted mb-4">
                    Aún no tienes clases asignadas. Contacta con el administrador para obtener clases.
                </p>
                <a href="<?= BASE_URL ?>/index.php?accion=dashboard" class="btn btn-primary btn-lg">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Ir al Dashboard
                </a>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($clases as $clase): ?>
                    <?php 
                    $estado_actual = $estados_clase[$clase['ESTADO_CLASE']] ?? $estados_clase[5];
                    $puede_empezar = in_array($clase['ESTADO_CLASE'], [1, 3]); // Solo Activo y Finalizado
                    ?>
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
                                                    Ir a clase
                                                </a>
                                            <?php else: ?>
                                                <span class="no-link">No disponible</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="acciones-container">
                                    <div class="btn-group">
                                        <?php if ($puede_empezar): ?>
                                            <!-- Estados que permiten empezar/reanudar clase -->
                                            <button type="button" 
                                                    class="btn-accion <?= obtenerClaseBotonEmpezar($clase['ESTADO_CLASE']) ?>" 
                                                    onclick="empezarClase(<?= $clase['ID_CLASE'] ?>, '<?= htmlspecialchars($clase['NOMBRE_CURSO']) ?>', '<?= htmlspecialchars($clase['NOMBRE_CICLO']) ?>', <?= $clase['ESTADO_CLASE'] ?>)">
                                                <i class="fas fa-<?= obtenerIconoBoton($clase['ESTADO_CLASE']) ?>"></i>
                                                <?= obtenerTextoBotonEmpezar($clase['ESTADO_CLASE']) ?>
                                            </button>
                                            
                                            <button type="button" 
                                                    class="btn-accion btn-calificar" 
                                                    onclick="calificarEstudiantes(<?= $clase['ID_CLASE'] ?>, '<?= htmlspecialchars($clase['NOMBRE_CURSO']) ?>')">
                                                <i class="fas fa-star"></i>
                                                Calificar
                                            </button>
                                            <a href="<?= BASE_URL ?>/index.php?accion=ver_calificaciones&clase=<?= $clase['ID_CLASE'] ?>" 
                                                class="btn-accion btn-calificar" 
                                                title="Ver calificaciones registradas">
                                                    <i class="fas fa-eye"></i>
                                                    Ver Calificaciones
                                                </a>
                                            
                                            <?php if ($clase['ESTADO_CLASE'] == 1): ?>
                                                <button type="button" 
                                                        class="btn-accion btn-cerrar" 
                                                        onclick="cerrarClase(<?= $clase['ID_CLASE'] ?>, '<?= htmlspecialchars($clase['NOMBRE_CURSO']) ?>')">
                                                    <i class="fas fa-times"></i>
                                                    Cerrar
                                                </button>
                                            <?php endif; ?>
                                            
                                        <?php elseif ($clase['ESTADO_CLASE'] == 2): ?>
                                            <!-- Estado En Proceso - botón deshabilitado -->
                                            <button type="button" 
                                                    class="btn-accion btn-proceso" 
                                                    disabled 
                                                    title="La clase está en proceso y no puede ser iniciada nuevamente">
                                                <i class="fas fa-spinner fa-spin"></i>
                                                En Proceso
                                            </button>
                                            
                                            <button type="button" 
                                                    class="btn-accion btn-calificar" 
                                                    onclick="calificarEstudiantes(<?= $clase['ID_CLASE'] ?>, '<?= htmlspecialchars($clase['NOMBRE_CURSO']) ?>')">
                                                <i class="fas fa-star"></i>
                                                Calificar
                                            </button>
                                            
                                        <?php else: ?>
                                            <!-- Estado Cerrado u otros - solo calificaciones -->
                                            <button type="button" 
                                                    class="btn-accion btn-cerrado" 
                                                    disabled 
                                                    title="Esta clase ha sido cerrada y no puede ser iniciada">
                                                <i class="fas fa-lock"></i>
                                                Clase Cerrada
                                            </button>
                                            
                                            <button type="button" 
                                                    class="btn-accion btn-calificar" 
                                                    onclick="calificarEstudiantes(<?= $clase['ID_CLASE'] ?>, '<?= htmlspecialchars($clase['NOMBRE_CURSO']) ?>')">
                                                <i class="fas fa-star"></i>
                                                Ver Calificaciones
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Cerrar Clase -->
<div class="modal fade" id="cerrarClaseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-times-circle me-2"></i>
                    Cerrar Clase
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h5>¿Estás seguro de cerrar esta clase?</h5>
                    <p class="text-muted mb-0">Esta acción no se puede deshacer</p>
                    <p><strong id="nombreCursoCerrar"></strong></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="confirmarCerrarClase()">
                    <i class="fas fa-times me-2"></i>
                    Cerrar Clase
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Empezar Clase -->
<div class="modal fade" id="empezarClaseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-play-circle me-2"></i>
                    Empezar Clase
                </h5>
            </div>
            <form id="formEmpezarClase">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">Curso:</div>
                                <div class="info-value" id="cursoEmpezar"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">Ciclo:</div>
                                <div class="info-value" id="cicloEmpezar"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="info-item">
                            <div class="info-label">ID de Clase:</div>
                            <div class="info-value" id="idClaseEmpezar"></div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <h6>Selecciona una opción:</h6>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="opcionClase" id="insertarLink" value="link">
                                <label class="btn btn-outline-primary" for="insertarLink">
                                    <i class="fas fa-link"></i> Insertar Link
                                </label>
                                
                                <input type="radio" class="btn-check" name="opcionClase" id="usarDiscord" value="discord">
                                <label class="btn btn-outline-secondary" for="usarDiscord">
                                    <i class="fab fa-discord"></i> Usar Discord
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="linkContainer" class="mb-3" style="display: none;">
                        <label for="enlaceClase" class="form-label fw-bold">Enlace de la clase:</label>
                        <input type="url" class="form-control" id="enlaceClase" name="enlace" 
                               placeholder="https://meet.google.com/...">
                    </div>

                    <input type="hidden" id="idClaseInput" name="id_clase">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-play me-2"></i>
                        Iniciar Clase
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Calificar Estudiantes -->
<div class="modal fade" id="calificarModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-star me-2"></i>
                    Calificar Estudiantes
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6 id="cursoCalificar" class="mb-3"></h6>
                <div id="estudiantesContainer">
                    <!-- Aquí se cargarán los estudiantes dinámicamente -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-warning" onclick="guardarCalificaciones()">
                    <i class="fas fa-save me-2"></i>
                    Guardar Calificaciones
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let claseActual = null;

// Función para mostrar notificaciones elegantes
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
    
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        if (notificacion.parentNode) {
            notificacion.remove();
        }
    }, 5000);
}

// Función auxiliar para obtener iconos según el tipo
function getIconoTipo(tipo) {
    const iconos = {
        'success': 'check-circle',
        'warning': 'exclamation-triangle',
        'danger': 'exclamation-circle',
        'info': 'info-circle'
    };
    return iconos[tipo] || 'info-circle';
}

// Función para cerrar clase
function cerrarClase(idClase, nombreCurso) {
    claseActual = idClase;
    document.getElementById('nombreCursoCerrar').textContent = nombreCurso;
    new bootstrap.Modal(document.getElementById('cerrarClaseModal')).show();
}

function confirmarCerrarClase() {
    if (!claseActual) return;
    
    fetch('<?= BASE_URL ?>/index.php?accion=cerrar_clase', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id_clase=${claseActual}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('cerrarClaseModal')).hide();
            mostrarNotificacion('success', 'Clase cerrada', 'La clase se ha cerrado correctamente');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            mostrarNotificacion('danger', 'Error al cerrar clase', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarNotificacion('danger', 'Error', 'Error al procesar la solicitud');
    });
}

function empezarClase(idClase, nombreCurso, ciclo, estadoClase = null) {
    // Validar estados permitidos (1: Activo, 3: Finalizado)
    const estadosPermitidos = [1, 3];
    
    // Si se proporciona el estado, validarlo
    if (estadoClase !== null && !estadosPermitidos.includes(parseInt(estadoClase))) {
        let mensaje;
        switch(parseInt(estadoClase)) {
            case 2:
                mensaje = 'No se puede empezar la clase porque está en proceso.';
                break;
            case 5:
                mensaje = 'No se puede empezar la clase porque está cerrada.';
                break;
            default:
                mensaje = 'Estado de clase no válido para iniciar.';
        }
        
        mostrarNotificacion('warning', 'Acción no permitida', mensaje);
        return false;
    }
    
    claseActual = idClase;
    document.getElementById('cursoEmpezar').textContent = nombreCurso;
    document.getElementById('cicloEmpezar').textContent = ciclo;
    document.getElementById('idClaseEmpezar').textContent = `#${idClase}`;
    document.getElementById('idClaseInput').value = idClase;
    
    // Reset form
    document.getElementById('formEmpezarClase').reset();
    document.getElementById('linkContainer').style.display = 'none';
    
    new bootstrap.Modal(document.getElementById('empezarClaseModal')).show();
    return true;
}

// Manejar cambio de opción en modal empezar clase
document.addEventListener('change', function(e) {
    if (e.target.name === 'opcionClase') {
        const linkContainer = document.getElementById('linkContainer');
        if (e.target.value === 'link') {
            linkContainer.style.display = 'block';
            document.getElementById('enlaceClase').required = true;
        } else {
            linkContainer.style.display = 'none';
            document.getElementById('enlaceClase').required = false;
        }
    }
});

// Mejorar la función de envío del formulario con mejor manejo de errores
document.getElementById('formEmpezarClase').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Deshabilitar botón y mostrar loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Iniciando...';
    
    const formData = new FormData(this);
    if (document.getElementById('usarDiscord').checked) {
        formData.append('usar_discord', '1');
    }
    
    fetch('<?= BASE_URL ?>/index.php?accion=empezar_clase', {
        method: 'POST',
        headers: {
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('La respuesta del servidor no es JSON válido');
        }
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        return response.json();
    })
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('empezarClaseModal')).hide();
            mostrarNotificacion('success', 'Clase iniciada', 'La clase se ha iniciado correctamente');
            
            // Recargar después de un breve delay para mostrar la notificación
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Error desconocido');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarNotificacion('danger', 'Error al iniciar clase', error.message);
    })
    .finally(() => {
        // Restaurar botón
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

function calificarEstudiantes(idClase, nombreCurso) {
    claseActual = idClase;
    document.getElementById('cursoCalificar').textContent = `Curso: ${nombreCurso}`;
    
    fetch(`<?= BASE_URL ?>/index.php?accion=obtener_estudiantes_clase&id_clase=${idClase}`)
    .then(response => response.json())
    .then(estudiantes => {
        let html = '';
        if (estudiantes.length === 0) {
            html = '<div class="alert alert-info"><i class="fas fa-info-circle me-2"></i>No hay estudiantes inscritos en esta clase</div>';
        } else {
            html = '<div class="table-responsive">';
            html += '<table class="table table-striped table-hover">';
            html += '<thead class="table-dark"><tr><th>Código</th><th>Nombre Completo</th><th>Email</th><th>Calificación</th></tr></thead>';
            html += '<tbody>';
            
            estudiantes.forEach(estudiante => {
                html += `
                    <tr>
                        <td><span class="badge bg-secondary">${estudiante.CODIGO}</span></td>
                        <td><strong>${estudiante.NOMBRE} ${estudiante.APELLIDO}</strong></td>
                        <td><small class="text-muted">${estudiante.EMAIL_CORPORATIVO}</small></td>
                        <td>
                            <input type="number" class="form-control form-control-sm" 
                                   min="0" max="20" step="0.1" 
                                   name="calificacion_${estudiante.ID_ESTUDIANTE}" 
                                   placeholder="0.0 - 20.0"
                                   style="max-width: 120px;">
                        </td>
                    </tr>
                `;
            });
            html += '</tbody></table></div>';
        }
        
        document.getElementById('estudiantesContainer').innerHTML = html;
        new bootstrap.Modal(document.getElementById('calificarModal')).show();
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarNotificacion('danger', 'Error', 'Error al cargar los estudiantes');
    });
}

function guardarCalificaciones() {
    const form = document.getElementById('calificarModal');
    const inputs = form.querySelectorAll('input[name^="calificacion_"]');
    let errores = 0;

    inputs.forEach(input => {
        const idEstudiante = input.name.match(/\d+/)[0];
        const calificacion = input.value;
        const observacion = prompt(`Observación para el estudiante ${idEstudiante} (opcional):`, "");

        const formData = new FormData();
        formData.append('id_clase', claseActual);
        formData.append('id_estudiante', idEstudiante);
        formData.append('calificacion', calificacion);
        formData.append('observacion', observacion || '');

        fetch('<?= BASE_URL ?>/index.php?accion=calificar_estudiante', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                errores++;
                alert(`Error al guardar nota para estudiante ${idEstudiante}: ${data.message}`);
            }
        })
        .catch(() => {
            errores++;
            alert(`Error de red con el estudiante ${idEstudiante}`);
        });
    });

    setTimeout(() => {
        if (errores === 0) {
            alert('✅ Todas las calificaciones se registraron correctamente.');
            bootstrap.Modal.getInstance(document.getElementById('calificarModal')).hide();
        } else {
            alert('⚠️ Algunas calificaciones fallaron. Revisa los mensajes.');
        }
    }, 1000);
}
</script>

<?php require_once BASE_PATH . '/views/components/footer.php'; ?>