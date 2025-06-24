<?php require_once BASE_PATH . '/views/components/head.php'; ?>
<?php require_once BASE_PATH . '/views/components/header.php'; ?>

<div class="admin-reportes-container">
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="admin-title">
                        <i class="fas fa-chart-bar me-2"></i>
                        Dashboard de Reportes
                    </h2>
                    <div class="btn-group">
                        <button class="btn btn-outline-primary" onclick="exportarReporte('pdf')">
                            <i class="fas fa-file-pdf me-2"></i>PDF
                        </button>
                        <button class="btn btn-outline-success" onclick="exportarReporte('excel')">
                            <i class="fas fa-file-excel me-2"></i>Excel
                        </button>
                        <button class="btn btn-primary" onclick="actualizarReportes()">
                            <i class="fas fa-sync-alt me-2"></i>Actualizar
                        </button>
                    </div>
                </div>
                
                <!-- Estadísticas principales -->
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="metric-card gradient-blue">
                            <div class="metric-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="metric-content">
                                <h3 id="totalEstudiantes">0</h3>
                                <p>Total Estudiantes</p>
                                <span class="metric-change positive">+12% este mes</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="metric-card gradient-green">
                            <div class="metric-icon">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div class="metric-content">
                                <h3 id="totalMentores">0</h3>
                                <p>Mentores Activos</p>
                                <span class="metric-change positive">+8% este mes</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="metric-card gradient-orange">
                            <div class="metric-icon">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div class="metric-content">
                                <h3 id="totalClases">0</h3>
                                <p>Clases Impartidas</p>
                                <span class="metric-change positive">+25% este mes</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="metric-card gradient-purple">
                            <div class="metric-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="metric-content">
                                <h3 id="promedioSatisfaccion">0.0</h3>
                                <p>Satisfacción Promedio</p>
                                <span class="metric-change positive">+5% este mes</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos principales -->
        <div class="row mb-4">
            <!-- Clases más populares -->
            <div class="col-lg-8 mb-4">
                <div class="chart-card">
                    <div class="chart-header">
                        <h5>
                            <i class="fas fa-fire me-2"></i>
                            Cursos Más Populares
                        </h5>
                        <div class="chart-filters">
                            <select class="form-select form-select-sm" id="filtroTiempo">
                                <option value="7">Últimos 7 días</option>
                                <option value="30" selected>Últimos 30 días</option>
                                <option value="90">Últimos 3 meses</option>
                            </select>
                        </div>
                    </div>
                    <div class="chart-body">
                        <canvas id="chartClasesPopulares" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Distribución por carreras -->
            <div class="col-lg-4 mb-4">
                <div class="chart-card">
                    <div class="chart-header">
                        <h5>
                            <i class="fas fa-graduation-cap me-2"></i>
                            Distribución por Carreras
                        </h5>
                    </div>
                    <div class="chart-body">
                        <canvas id="chartDistribucionCarreras" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <!-- Mentores mejor calificados -->
            <div class="col-lg-6 mb-4">
                <div class="chart-card">
                    <div class="chart-header">
                        <h5>
                            <i class="fas fa-trophy me-2"></i>
                            Top Mentores
                        </h5>
                    </div>
                    <div class="chart-body">
                        <div class="mentor-rankings">
                            <div id="listaMentores" class="mentor-list">
                                <!-- Se llena dinámicamente -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progreso de estudiantes -->
            <div class="col-lg-6 mb-4">
                <div class="chart-card">
                    <div class="chart-header">
                        <h5>
                            <i class="fas fa-chart-line me-2"></i>
                            Progreso de Estudiantes
                        </h5>
                    </div>
                    <div class="chart-body">
                        <canvas id="chartProgresoEstudiantes" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos adicionales -->
        <div class="row mb-4">
            <!-- Inscripciones por mes -->
            <div class="col-lg-8 mb-4">
                <div class="chart-card">
                    <div class="chart-header">
                        <h5>
                            <i class="fas fa-calendar-alt me-2"></i>
                            Tendencia de Inscripciones
                        </h5>
                    </div>
                    <div class="chart-body">
                        <canvas id="chartInscripcionesMes" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Estados de clases -->
            <div class="col-lg-4 mb-4">
                <div class="chart-card">
                    <div class="chart-header">
                        <h5>
                            <i class="fas fa-tasks me-2"></i>
                            Estados de Clases
                        </h5>
                    </div>
                    <div class="chart-body">
                        <canvas id="chartEstadosClases" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de rendimiento detallado -->
        <div class="row">
            <div class="col-12">
                <div class="chart-card">
                    <div class="chart-header">
                        <h5>
                            <i class="fas fa-table me-2"></i>
                            Rendimiento Detallado por Curso
                        </h5>
                        <button class="btn btn-sm btn-outline-primary" onclick="toggleDetalleTabla()">
                            <i class="fas fa-expand-alt"></i>
                        </button>
                    </div>
                    <div class="chart-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="tablaRendimiento">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Curso</th>
                                        <th>Código</th>
                                        <th>Inscritos</th>
                                        <th>Completados</th>
                                        <th>% Éxito</th>
                                        <th>Promedio</th>
                                        <th>Mentor</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody id="bodyTablaRendimiento">
                                    <!-- Se llena dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de carga -->
<div class="modal fade" id="modalCargando" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mb-0">Generando reportes...</p>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
<script>
// Variables globales para los gráficos
let charts = {};
let reportData = {};

// Inicializar cuando se carga el DOM
document.addEventListener('DOMContentLoaded', function() {
    cargarReportes();
    setupEventListeners();
});

// Cargar todos los reportes
async function cargarReportes() {
    showModal('modalCargando');
    
    try {
        // Cargar datos de diferentes endpoints
        const [estadisticas, clasesPopulares, mentores, progreso, distribucion, inscripciones, estados] = await Promise.all([
            fetchData('estadisticas_generales'),
            fetchData('clases_populares'),
            fetchData('mentores_calificados'),
            fetchData('progreso_estudiantes'),
            fetchData('distribucion_carreras'),
            fetchData('inscripciones_mes'),
            fetchData('estados_clases')
        ]);

        // Almacenar datos
        reportData = {
            estadisticas,
            clasesPopulares,
            mentores,
            progreso,
            distribucion,
            inscripciones,
            estados
        };

        // Actualizar métricas principales
        actualizarMetricas(estadisticas);
        
        // Crear gráficos
        crearGraficos();
        
        // Llenar tablas
        llenarTablaMentores(mentores);
        llenarTablaRendimiento();
        
    } catch (error) {
        console.error('Error cargando reportes:', error);
        showAlert('danger', 'Error al cargar los reportes');
    } finally {
        hideModal('modalCargando');
    }
}

// Función para obtener datos
async function fetchData(tipo) {
    const response = await fetch(`${BASE_URL}/index.php?accion=obtenerDatosGrafico&tipo=${tipo}`);
    const data = await response.json();
    if (!data.success) throw new Error(data.message);
    return data.data;
}

// Actualizar métricas principales
function actualizarMetricas(stats) {
    const elementos = {
        totalEstudiantes: stats.total_estudiantes || 0,
        totalMentores: stats.total_mentores || 0,
        totalClases: stats.total_clases || 0,
        promedioSatisfaccion: (stats.promedio_satisfaccion || 0).toFixed(1)
    };
    
    Object.entries(elementos).forEach(([id, value]) => {
        animateCounter(id, value);
    });
}

// Animar contadores
function animateCounter(elementId, finalValue) {
    const element = document.getElementById(elementId);
    const increment = finalValue / 50;
    let currentValue = 0;
    
    const timer = setInterval(() => {
        currentValue += increment;
        if (currentValue >= finalValue) {
            currentValue = finalValue;
            clearInterval(timer);
        }
        
        if (elementId === 'promedioSatisfaccion') {
            element.textContent = currentValue.toFixed(1);
        } else {
            element.textContent = Math.floor(currentValue);
        }
    }, 30);
}

// Crear todos los gráficos
function crearGraficos() {
    crearGraficoClasesPopulares();
    crearGraficoDistribucionCarreras();
    crearGraficoProgresoEstudiantes();
    crearGraficoInscripcionesMes();
    crearGraficoEstadosClases();
}
</script>

<style>
/* Estilos específicos para reportes */
.admin-reportes-container {
    background: linear-gradient(135deg, #f8f9fc 0%, #e3f2fd 100%);
    min-height: 100vh;
    padding-top: 80px;
}

/* Tarjetas de métricas */
.metric-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    height: 140px;
    display: flex;
    align-items: center;
}

.metric-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.metric-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-blue), var(--light-green));
}

.gradient-blue::before { background: linear-gradient(90deg, #1e3a5f, #2c5282); }
.gradient-green::before { background: linear-gradient(90deg, #28a745, #20c997); }
.gradient-orange::before { background: linear-gradient(90deg, #fd7e14, #ffc107); }
.gradient-purple::before { background: linear-gradient(90deg, #6f42c1, #8e44ad); }

.metric-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    margin-right: 1rem;
    flex-shrink: 0;
}

.gradient-blue .metric-icon { background: linear-gradient(135deg, #1e3a5f, #2c5282); }
.gradient-green .metric-icon { background: linear-gradient(135deg, #28a745, #20c997); }
.gradient-orange .metric-icon { background: linear-gradient(135deg, #fd7e14, #ffc107); }
.gradient-purple .metric-icon { background: linear-gradient(135deg, #6f42c1, #8e44ad); }

.metric-content h3 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    color: var(--primary-blue);
}

.metric-content p {
    margin: 0;
    color: #6c757d;
    font-weight: 500;
}

.metric-change {
    font-size: 0.8rem;
    font-weight: 600;
    padding: 0.2rem 0.5rem;
    border-radius: 12px;
    display: inline-block;
    margin-top: 0.5rem;
}

.metric-change.positive {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

/* Tarjetas de gráficos */
.chart-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    height: 100%;
}

.chart-card:hover {
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.chart-header {
    padding: 1.5rem 1.5rem 0;
    display: flex;
    justify-content: between;
    align-items: center;
    border-bottom: 1px solid #f0f0f0;
    margin-bottom: 1rem;
}

.chart-header h5 {
    margin: 0;
    color: var(--primary-blue);
    font-weight: 600;
    flex-grow: 1;
}

.chart-filters .form-select {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 0.85rem;
}

.chart-body {
    padding: 0 1.5rem 1.5rem;
}

/* Lista de mentores */
.mentor-list {
    max-height: 250px;
    overflow-y: auto;
}

.mentor-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 0.5rem;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.mentor-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.mentor-rank {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    margin-right: 1rem;
    flex-shrink: 0;
}

.mentor-info h6 {
    margin: 0;
    color: var(--primary-blue);
    font-weight: 600;
}

.mentor-stats {
    font-size: 0.8rem;
    color: #6c757d;
}

.mentor-rating {
    margin-left: auto;
    text-align: right;
}

.rating-stars {
    color: #ffc107;
    font-size: 0.9rem;
}

/* Tabla responsive */
.table {
    font-size: 0.9rem;
}

.table thead th {
    background: var(--primary-blue);
    color: white;
    border: none;
    font-weight: 600;
    white-space: nowrap;
}

.table tbody td {
    vertical-align: middle;
    border-color: #e9ecef;
}

/* Responsive */
@media (max-width: 768px) {
    .metric-card {
        height: auto;
        flex-direction: column;
        text-align: center;
        padding: 1rem;
    }
    
    .metric-icon {
        margin-right: 0;
        margin-bottom: 0.5rem;
    }
    
    .chart-header {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>
<script src="<?php echo BASE_URL; ?>/assets/js/reportes.js"></script>
<?php require_once BASE_PATH . '/views/components/footer.php'; ?>