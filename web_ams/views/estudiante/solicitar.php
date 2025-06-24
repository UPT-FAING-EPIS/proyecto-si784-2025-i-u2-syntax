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
    $clases_disponibles = [];
    $ciclos = [];
    $cursos = [];
    $puede_solicitar = false;
} else {
    try {
        $clases_disponibles = $claseModel->obtenerClasesDisponiblesParaInscripcion($id_estudiante);
        $ciclos = $claseModel->obtenerCiclosDisponibles();
        $cursos = $claseModel->obtenerCursosDisponibles();
        $total_clases = $claseModel->contarClasesEstudiante($id_estudiante);
        $puede_solicitar = $total_clases < 3;
    } catch (Exception $e) {
        $error = "Error al cargar las clases: " . $e->getMessage();
        $clases_disponibles = [];
        $ciclos = [];
        $cursos = [];
        $puede_solicitar = false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion']) && $_POST['accion'] === 'inscribir') {
        $id_clase = $_POST['id_clase'] ?? 0;
        
        try {
            $resultado = $claseModel->inscribirEstudiante($id_estudiante, $id_clase);
            
            if ($resultado) {
                $mensaje = "¡Te has inscrito exitosamente a la clase!";
                $tipo_mensaje = "success";
                $clases_disponibles = $claseModel->obtenerClasesDisponiblesParaInscripcion($id_estudiante);
            } else {
                throw new Exception("No se pudo completar la inscripción");
            }
            
        } catch (Exception $e) {
            $mensaje = "Error: " . $e->getMessage();
            $tipo_mensaje = "danger";
        }
    } elseif (isset($_POST['accion']) && $_POST['accion'] === 'crear_clase') {
        $id_ciclo = $_POST['id_ciclo'] ?? 0;
        $id_curso = $_POST['id_curso'] ?? 0;
        $horario_preferido = $_POST['horario_preferido'] ?? '';
        $razon = $_POST['razon'] ?? '';
        
        try {
            if (!$puede_solicitar) {
                throw new Exception("No puedes crear más clases. Ya tienes 3 o más clases registradas.");
            }
            
            $resultado = $claseModel->solicitarNuevaClase($id_estudiante, $id_ciclo, $id_curso, $horario_preferido, $razon);
            
            if ($resultado) {
                $mensaje = "¡Nueva clase creada exitosamente! Ya estás inscrito.";
                $tipo_mensaje = "success";
                $clases_disponibles = $claseModel->obtenerClasesDisponiblesParaInscripcion($id_estudiante);
                $total_clases = $claseModel->contarClasesEstudiante($id_estudiante);
                $puede_solicitar = $total_clases < 3;
            } else {
                throw new Exception("No se pudo crear la clase");
            }
            
        } catch (Exception $e) {
            $mensaje = "Error: " . $e->getMessage();
            $tipo_mensaje = "danger";
        }
    }
}

require_once BASE_PATH . '/views/components/head.php';
require_once BASE_PATH . '/views/components/header.php';
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
}

.solicitar-container {
    background-color: var(--light-gray);
    min-height: calc(100vh - 120px);
    padding: 2rem 0;
}

.page-header {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
    border-radius: 0 0 1rem 1rem;
}

.clase-card {
    border: none;
    box-shadow: 0 2px 8px rgba(30, 58, 95, 0.1);
    transition: all 0.3s ease;
    border-radius: 0.75rem;
    overflow: hidden;
    margin-bottom: 1.5rem;
    background: white;
}

.clase-card:hover {
    box-shadow: 0 8px 25px rgba(30, 58, 95, 0.15);
    transform: translateY(-4px);
}

.card-header-custom {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    color: white;
    padding: 1.25rem;
    border: none;
}

.estado-badge {
    font-size: 0.75rem;
    padding: 0.4rem 0.8rem;
    border-radius: 50px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.estado-disponible {
    background-color: var(--success-green);
    color: white;
}

.estado-lleno {
    background-color: var(--warning-orange);
    color: white;
}

.info-row {
    display: flex;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-gray);
}

.info-row:last-child {
    border-bottom: none;
}

.info-icon {
    width: 40px;
    color: var(--primary-blue);
    font-size: 1.1rem;
}

.info-label {
    font-weight: 600;
    color: var(--text-gray);
    min-width: 100px;
}

.info-value {
    color: var(--dark-blue);
    font-weight: 500;
}

.btn-unirse {
    background: linear-gradient(135deg, var(--success-green) 0%, #2f855a 100%);
    border: none;
    color: white;
    padding: 0.6rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-unirse:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(56, 161, 105, 0.4);
    color: white;
}

.btn-unirse:disabled {
    background: #a0aec0;
    cursor: not-allowed;
}

.btn-solicitar-clase {
    background: linear-gradient(135deg, var(--accent-blue) 0%, var(--primary-blue) 100%);
    border: none;
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 0.5rem;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(49, 130, 206, 0.3);
}

.btn-solicitar-clase:hover:not(:disabled) {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(49, 130, 206, 0.4);
    color: white;
}

.btn-solicitar-clase:disabled {
    background: #a0aec0;
    cursor: not-allowed;
    box-shadow: none;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 1rem;
    box-shadow: 0 2px 8px rgba(30, 58, 95, 0.1);
}

.empty-icon {
    font-size: 5rem;
    color: var(--accent-blue);
    margin-bottom: 1.5rem;
}

.modal-header-custom {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    color: white;
    border-bottom: none;
}

.modal-content {
    border: none;
    border-radius: 0.75rem;
    overflow: hidden;
}

.modal-body-detail {
    padding: 2rem;
}

.detail-section {
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-gray);
}

.detail-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.detail-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--primary-blue);
    margin-bottom: 0.75rem;
}

.cupos-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.cupo-disponible {
    background-color: var(--light-blue);
    color: var(--primary-blue);
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 600;
}

.horario-options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.horario-option {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 0.2rem rgba(30, 58, 95, 0.25);
}

.form-select {
    background-color: white;
    border: 1px solid var(--border-gray);
    border-radius: 0.375rem;
    padding: 0.375rem 2.25rem 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: var(--text-gray);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 7 7 7-7'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}

.form-select:disabled {
    background-color: var(--light-gray);
    opacity: 0.65;
}

.modal-body .form-select {
    width: 100%;
    margin-bottom: 1rem;
}

.horario-options .form-check-input {
    margin-right: 0.5rem;
}

.horario-option {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem;
    border: 1px solid var(--border-gray);
    border-radius: 0.375rem;
    margin-bottom: 0.5rem;
    transition: all 0.2s ease;
}

.horario-option:hover {
    background-color: var(--light-blue);
    border-color: var(--primary-blue);
}

.horario-option input[type="radio"]:checked + label {
    color: var(--primary-blue);
    font-weight: 600;
}
</style>

<div class="solicitar-container">
    <div class="container">
        <div class="page-header">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h1 class="mb-2">
                            <i class="fas fa-plus-circle me-3"></i>
                            Clases Disponibles
                        </h1>
                        <p class="mb-0 opacity-75">Explora y únete a las clases que están abiertas para inscripción</p>
                    </div>
                    <button type="button" 
                            class="btn btn-solicitar-clase" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modalSolicitarClase"
                            <?= !$puede_solicitar ? 'disabled' : '' ?>>
                        <i class="fas fa-plus me-2"></i>
                        Crear Nueva Clase
                    </button>
                </div>
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

        <?php if (!$puede_solicitar): ?>
            <div class="alert alert-info-custom">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Límite alcanzado:</strong> Ya tienes 3 o más clases registradas. No puedes solicitar nuevas clases en este momento.
            </div>
        <?php endif; ?>

        <?php if (empty($clases_disponibles)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h3 class="text-muted mb-3">No hay clases disponibles</h3>
                <p class="text-muted mb-4">
                    Actualmente no hay clases abiertas para inscripción. Las clases disponibles aparecerán aquí cuando se abran nuevos períodos de registro.
                </p>
                <p class="text-muted">
                    Mientras tanto, puedes solicitar que se abra una nueva clase usando el botón "Solicitar Clase".
                </p>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($clases_disponibles as $clase): ?>
                    <div class="col-lg-6 col-xl-4">
                        <div class="clase-card">
                            <div class="card-header-custom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="mb-1"><?= htmlspecialchars($clase['CURSO_NOMBRE']) ?></h5>
                                        <small class="opacity-75">Código: <?= htmlspecialchars($clase['CURSO_CODIGO']) ?></small>
                                    </div>
                                    <span class="estado-badge estado-<?= $clase['DISPONIBLE'] ? 'disponible' : 'lleno' ?>">
                                        <?= $clase['DISPONIBLE'] ? 'Disponible' : 'Lleno' ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <div class="info-row">
                                    <i class="fas fa-layer-group info-icon"></i>
                                    <span class="info-label">Ciclo:</span>
                                    <span class="info-value"><?= htmlspecialchars($clase['CICLO_NOMBRE']) ?></span>
                                </div>
                                
                                <div class="info-row">
                                    <i class="fas fa-clock info-icon"></i>
                                    <span class="info-label">Horario:</span>
                                    <span class="info-value"><?= htmlspecialchars($clase['HORARIO']) ?></span>
                                </div>
                                
                                <div class="info-row">
                                    <i class="fas fa-chalkboard-teacher info-icon"></i>
                                    <span class="info-label">Mentor:</span>
                                    <span class="info-value"><?= htmlspecialchars($clase['MENTOR_NOMBRE'] ?? 'Por asignar') ?></span>
                                </div>
                                
                                <div class="info-row">
                                    <i class="fas fa-users info-icon"></i>
                                    <span class="info-label">Cupos:</span>
                                    <div class="cupos-info">
                                        <span class="info-value"><?= $clase['INSCRITOS'] ?>/<?= $clase['CAPACIDAD'] ?></span>
                                        <span class="cupo-disponible">
                                            <?= $clase['CAPACIDAD'] - $clase['INSCRITOS'] ?> disponibles
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="info-row">
                                    <i class="fas fa-calendar-alt info-icon"></i>
                                    <span class="info-label">Inicio:</span>
                                    <span class="info-value"><?= date('d/m/Y', strtotime($clase['FECHA_INICIO'])) ?></span>
                                </div>
                                
                                <div class="mt-3 d-flex gap-2">
                                    <button type="button" 
                                            class="btn btn-unirse flex-grow-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalDetalle"
                                            data-clase='<?= json_encode($clase) ?>'
                                            <?= !$clase['DISPONIBLE'] ? 'disabled' : '' ?>>
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        <?= $clase['DISPONIBLE'] ? 'Unirse a Clase' : 'Clase Llena' ?>
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

<!-- Modal Detalle de Clase -->
<div class="modal fade" id="modalDetalle" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header-custom">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>
                    Detalle de la Clase
                </h5>
            </div>
            <div class="modal-body-detail">
                <div id="detalleContenido"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="accion" value="inscribir">
                    <input type="hidden" name="id_clase" id="modal_id_clase">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Confirmar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Solicitar Nueva Clase -->
<div class="modal fade" id="modalSolicitarClase" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header-custom">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>
                    Crear Nueva Clase
                </h5>
            </div>
            <form method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                <div class="modal-body">
                    <input type="hidden" name="accion" value="crear_clase">
                    
                    <div class="alert alert-info-custom mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Nota:</strong> Al crear una nueva clase, automáticamente quedarás inscrito.
                    </div>
                    
                    <div class="mb-3">
                        <label for="id_ciclo" class="form-label fw-bold">Ciclo:</label>
                        <select class="form-select" id="id_ciclo" name="id_ciclo" required>
                            <option value="">Selecciona un ciclo</option>
                            <?php foreach ($ciclos as $ciclo): ?>
                                <option value="<?= $ciclo['ID_CICLO'] ?>"><?= htmlspecialchars($ciclo['NOMBRE']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="id_curso" class="form-label fw-bold">Curso:</label>
                        <select class="form-select" id="id_curso" name="id_curso" required disabled>
                            <option value="">Primero selecciona un ciclo</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Horario Preferido:</label>
                        <div class="horario-options">
                            <div class="horario-option">
                                <input type="radio" class="form-check-input" id="manana" name="horario_preferido" value="Mañana" required>
                                <label class="form-check-label" for="manana">
                                    <i class="fas fa-sun me-2"></i>Mañana (8:00 - 10:00)
                                </label>
                            </div>
                            <div class="horario-option">
                                <input type="radio" class="form-check-input" id="tarde" name="horario_preferido" value="Tarde" required>
                                <label class="form-check-label" for="tarde">
                                    <i class="fas fa-sun me-2"></i>Tarde (14:00 - 16:00)
                                </label>
                            </div>
                            <div class="horario-option">
                                <input type="radio" class="form-check-input" id="noche" name="horario_preferido" value="Noche" required>
                                <label class="form-check-label" for="noche">
                                    <i class="fas fa-moon me-2"></i>Noche (18:00 - 20:00)
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="razon" class="form-label fw-bold">Razón para crear esta clase:</label>
                        <textarea class="form-control" id="razon" name="razon" rows="4" 
                                  placeholder="Explica por qué necesitas que se abra esta clase..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Crear Clase
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalDetalle = document.getElementById('modalDetalle');
    const cicloSelect = document.getElementById('id_ciclo');
    const cursoSelect = document.getElementById('id_curso');
    
    const cursos = <?= json_encode($cursos) ?>;
    console.log('Cursos cargados:', cursos);
    
    if (modalDetalle) {
        modalDetalle.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const clase = JSON.parse(button.getAttribute('data-clase'));
            
            document.getElementById('modal_id_clase').value = clase.ID_CLASE;
            
            const contenido = `
                <div class="detail-section">
                    <div class="detail-title">Información del Curso</div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Nombre:</strong> ${clase.CURSO_NOMBRE}<br>
                            <strong>Código:</strong> ${clase.CURSO_CODIGO}<br>
                            <strong>Ciclo:</strong> ${clase.CICLO_NOMBRE}
                        </div>
                        <div class="col-md-6">
                            <strong>Horario:</strong> ${clase.HORARIO}<br>
                            <strong>Aula:</strong> ${clase.AULA_NOMBRE || 'Virtual'}<br>
                            <strong>Mentor:</strong> ${clase.MENTOR_NOMBRE || 'Por asignar'}
                        </div>
                    </div>
                </div>
                
                <div class="detail-section">
                    <div class="detail-title">Fechas</div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Fecha de inicio:</strong><br>
                            ${new Date(clase.FECHA_INICIO).toLocaleDateString('es-ES', { 
                                weekday: 'long', 
                                year: 'numeric', 
                                month: 'long', 
                                day: 'numeric' 
                            })}
                        </div>
                        <div class="col-md-6">
                            <strong>Fecha de fin:</strong><br>
                            ${new Date(clase.FECHA_FIN).toLocaleDateString('es-ES', { 
                                weekday: 'long', 
                                year: 'numeric', 
                                month: 'long', 
                                day: 'numeric' 
                            })}
                        </div>
                    </div>
                </div>
                
                <div class="detail-section">
                    <div class="detail-title">Disponibilidad</div>
                    <div class="cupos-info">
                        <span><strong>Total de cupos:</strong> ${clase.CAPACIDAD}</span>
                        <span><strong>Inscritos:</strong> ${clase.INSCRITOS}</span>
                        <span class="cupo-disponible">
                            <i class="fas fa-users me-2"></i>
                            ${clase.CAPACIDAD - clase.INSCRITOS} cupos disponibles
                        </span>
                    </div>
                </div>
                
                <div class="alert alert-info-custom">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Importante:</strong> Una vez inscrito, podrás acceder a todos los materiales de la clase y participar en las actividades programadas.
                </div>
            `;
            
            document.getElementById('detalleContenido').innerHTML = contenido;
        });
    }
    
    if (cicloSelect && cursoSelect) {
        cicloSelect.addEventListener('change', function() {
            const cicloId = this.value;
            console.log('Ciclo seleccionado:', cicloId);
            
            cursoSelect.innerHTML = '<option value="">Selecciona un curso</option>';
            cursoSelect.disabled = true;
            
            if (cicloId && cicloId !== '') {
                const cursosDelCiclo = cursos.filter(curso => {
                    console.log('Comparando:', curso.ID_CICLO, 'con', cicloId);
                    return String(curso.ID_CICLO) === String(cicloId);
                });
                
                console.log('Cursos filtrados:', cursosDelCiclo);
                
                if (cursosDelCiclo.length > 0) {
                    cursosDelCiclo.forEach(curso => {
                        const option = document.createElement('option');
                        option.value = curso.ID_CURSO;
                        option.textContent = curso.CODIGO + ' - ' + curso.NOMBRE;
                        cursoSelect.appendChild(option);
                    });
                    cursoSelect.disabled = false;
                } else {
                    cursoSelect.innerHTML = '<option value="">No hay cursos para este ciclo</option>';
                }
            }
        });
    } else {
        console.error('No se encontraron los elementos select');
        console.log('cicloSelect:', cicloSelect);
        console.log('cursoSelect:', cursoSelect);
    }
});

</script>

<?php require_once BASE_PATH . '/views/components/footer.php'; ?>