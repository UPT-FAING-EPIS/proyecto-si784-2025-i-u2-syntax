<?php

use Google\Service\Dfareporting\Ad;
use Google\Service\MyBusinessAccountManagement\Admin;

$tituloPagina = "Panel de Gestión - Sistema de Mentoría Académica";
require_once BASE_PATH . '/views/components/head.php';
require_once BASE_PATH . '/views/components/header.php';

require_once BASE_PATH . '/models/AdminModel.php';

$adminModel = new AdminModel();

$metricas = $adminModel->obtenerMetricasGenerales();
$estadisticasGestion = $adminModel->obtenerEstadisticasGestion();
$actividadReciente = $adminModel->obtenerActividadReciente();
$estadoSistema = $adminModel->obtenerEstadoSistema();
?>

<div class="admin-dashboard">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="container-fluid">
            <div class="header-content">
                <div class="header-text">
                    <h1 class="dashboard-title">
                        <i class="fas fa-chart-line"></i>
                        Panel de Gestión Administrativo
                    </h1>
                    <p class="dashboard-subtitle">
                        Control total del sistema de mentoría académica UPT
                    </p>
                </div>
                <div class="header-actions">
                    <button class="btn-header" onclick="refreshDashboard()">
                        <i class="fas fa-sync-alt"></i>
                        Actualizar
                    </button>
                    <button class="btn-header btn-primary" onclick="openQuickActions()">
                        <i class="fas fa-plus"></i>
                        Acción Rápida
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-content">
        <div class="container-fluid">
            
            <section class="metrics-section">
                <div class="section-header">
                    <h2 class="section-title">Métricas del Sistema</h2>
                    <div class="section-meta">
                        <span class="last-updated">Última actualización: <strong>hace 2 min</strong></span>
                    </div>
                </div>
                
                <div class="metrics-grid">
                    <div class="metric-card primary">
                        <div class="metric-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-value" data-target="<?php echo $metricas['total_usuarios']; ?>">0</div>
                            <div class="metric-label">Total Usuarios</div>
                            <div class="metric-trend <?php echo $metricas['cambio_usuarios'] >= 0 ? 'positive' : 'negative'; ?>">
                                <i class="fas fa-arrow-<?php echo $metricas['cambio_usuarios'] >= 0 ? 'up' : 'down'; ?>"></i>
                                <span><?php echo ($metricas['cambio_usuarios'] >= 0 ? '+' : '') . $metricas['cambio_usuarios']; ?>% vs mes anterior</span>
                            </div>
                        </div>
                        <div class="metric-chart">
                            <div class="mini-chart" data-chart="users"></div>
                        </div>
                    </div>

                    <div class="metric-card success">
                        <div class="metric-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-value" data-target="<?php echo $metricas['estudiantes_activos']; ?>">0</div>
                            <div class="metric-label">Estudiantes Activos</div>
                            <div class="metric-trend <?php echo $metricas['cambio_estudiantes'] >= 0 ? 'positive' : 'negative'; ?>">
                                <i class="fas fa-arrow-<?php echo $metricas['cambio_estudiantes'] >= 0 ? 'up' : 'down'; ?>"></i>
                                <span><?php echo ($metricas['cambio_estudiantes'] >= 0 ? '+' : '') . $metricas['cambio_estudiantes']; ?>% vs mes anterior</span>
                            </div>
                        </div>
                        <div class="metric-chart">
                            <div class="mini-chart" data-chart="students"></div>
                        </div>
                    </div>

                    <div class="metric-card info">
                        <div class="metric-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-value" data-target="<?php echo $metricas['docentes_mentores']; ?>">0</div>
                            <div class="metric-label">Docentes Mentores</div>
                            <div class="metric-trend <?php echo $metricas['cambio_docentes'] >= 0 ? 'positive' : 'negative'; ?>">
                                <i class="fas fa-arrow-<?php echo $metricas['cambio_docentes'] >= 0 ? 'up' : 'down'; ?>"></i>
                                <span><?php echo ($metricas['cambio_docentes'] >= 0 ? '+' : '') . $metricas['cambio_docentes']; ?>% vs mes anterior</span>
                            </div>
                        </div>
                        <div class="metric-chart">
                            <div class="mini-chart" data-chart="teachers"></div>
                        </div>
                    </div>

                    <div class="metric-card warning">
                        <div class="metric-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-value" data-target="<?php echo $metricas['sesiones_programadas']; ?>">0</div>
                            <div class="metric-label">Sesiones Programadas</div>
                            <div class="metric-trend <?php echo $metricas['cambio_sesiones'] > 0 ? 'positive' : ($metricas['cambio_sesiones'] < 0 ? 'negative' : 'neutral'); ?>">
                                <i class="fas fa-<?php echo $metricas['cambio_sesiones'] > 0 ? 'arrow-up' : ($metricas['cambio_sesiones'] < 0 ? 'arrow-down' : 'minus'); ?>"></i>
                                <span>
                                    <?php 
                                    if ($metricas['cambio_sesiones'] == 0) {
                                        echo 'Sin cambios';
                                    } else {
                                        echo ($metricas['cambio_sesiones'] > 0 ? '+' : '') . $metricas['cambio_sesiones'] . '% vs mes anterior';
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>
                        <div class="metric-chart">
                            <div class="mini-chart" data-chart="sessions"></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Acciones de Gestión -->
            <section class="actions-section">
                <div class="section-header">
                    <h2 class="section-title">Gestión del Sistema</h2>
                    <div class="section-meta">
                        <span>Herramientas de administración</span>
                    </div>
                </div>

                <div class="actions-grid">
                    <div class="action-item" onclick="navegarA('anadir_alumnos')">
                        <div class="action-header">
                            <div class="action-icon primary">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="action-badge">Nuevo</div>
                        </div>
                        <div class="action-content">
                            <h3 class="action-title">Añadir Estudiantes</h3>
                            <p class="action-description">
                                Registra nuevos estudiantes y gestiona su información académica
                            </p>
                            <div class="action-stats">
                                <div class="stat-item">
                                    <span class="stat-number"><?php echo $estadisticasGestion['estudiantes_hoy']; ?></span>
                                    <span class="stat-label">Hoy</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-number"><?php echo $estadisticasGestion['estudiantes_mes']; ?></span>
                                    <span class="stat-label">Este mes</span>
                                </div>
                            </div>
                        </div>
                        <div class="action-footer">
                            <span class="action-link">Gestionar estudiantes <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>

                    <div class="action-item" onclick="navegarA('modificar_usuarios')">
                        <div class="action-header">
                            <div class="action-icon success">
                                <i class="fas fa-users-cog"></i>
                            </div>
                            <div class="action-badge">Activo</div>
                        </div>
                        <div class="action-content">
                            <h3 class="action-title">Modificar Usuarios</h3>
                            <p class="action-description">
                                Administra perfiles, roles y permisos de usuarios del sistema
                            </p>
                            <div class="action-stats">
                                <div class="stat-item">
                                    <span class="stat-number"><?php echo $estadisticasGestion['total_usuarios']; ?></span>
                                    <span class="stat-label">Total</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-number"><?php echo $estadisticasGestion['usuarios_pendientes']; ?></span>
                                    <span class="stat-label">Pendientes</span>
                                </div>
                            </div>
                        </div>
                        <div class="action-footer">
                            <span class="action-link">Gestionar usuarios <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>

                    <div class="action-item" onclick="navegarA('modificar_clases')">
                        <div class="action-header">
                            <div class="action-icon info">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="action-badge">Programado</div>
                        </div>
                        <div class="action-content">
                            <h3 class="action-title">Gestionar Clases</h3>
                            <p class="action-description">
                                Administra horarios, materias y asignaciones de mentoría
                            </p>
                            <div class="action-stats">
                                <div class="stat-item">
                                    <span class="stat-number"><?php echo $estadisticasGestion['clases_activas']; ?></span>
                                    <span class="stat-label">Activas</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-number"><?php echo $estadisticasGestion['clases_hoy']; ?></span>
                                    <span class="stat-label">Hoy</span>
                                </div>
                            </div>
                        </div>
                        <div class="action-footer">
                            <span class="action-link">Gestionar clases <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>

                    <div class="action-item" onclick="navegarA('reportes')">
                        <div class="action-header">
                            <div class="action-icon warning">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="action-badge">Análisis</div>
                        </div>
                        <div class="action-content">
                            <h3 class="action-title">Reportes y Análisis</h3>
                            <p class="action-description">
                                Genera informes detallados y analiza el rendimiento del sistema
                            </p>
                            <div class="action-stats">
                                <div class="stat-item">
                                    <span class="stat-number"><?php echo $estadisticasGestion['total_reportes']; ?></span>
                                    <span class="stat-label">Reportes</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-number"><?php echo $estadisticasGestion['satisfaccion']; ?>%</span>
                                    <span class="stat-label">Satisfacción</span>
                                </div>
                            </div>
                        </div>
                        <div class="action-footer">
                            <span class="action-link">Ver reportes <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Panel de Actividad Reciente -->
            <section class="activity-section">
                <div class="activity-container">
                    <div class="activity-main">
                        <div class="section-header">
                            <h2 class="section-title">Actividad Reciente</h2>
                            <button class="btn-text" onclick="verTodasActividades()">
                                Ver todas <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>

                        <div class="activity-timeline">
                            <?php foreach ($actividadReciente as $actividad): ?>
                            <div class="timeline-item">
                                <div class="timeline-dot <?php echo $actividad['tipo']; ?>"></div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <h4><?php echo htmlspecialchars($actividad['titulo']); ?></h4>
                                        <span class="timeline-time"><?php echo $adminModel->formatearTiempoTranscurrido($actividad['fecha']); ?></span>
                                    </div>
                                    <p><?php echo htmlspecialchars($actividad['descripcion']); ?></p>
                                    <div class="timeline-meta">
                                        <span class="meta-badge"><?php echo htmlspecialchars($actividad['badge']); ?></span>
                                        <span class="meta-user"><?php echo htmlspecialchars($actividad['usuario']); ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Panel lateral de acceso rápido -->
                    <div class="quick-access">
                        <div class="quick-header">
                            <h3>Acceso Rápido</h3>
                        </div>
                        
                        <div class="quick-actions">
                            <button class="quick-btn" onclick="crearSesion()">
                                <i class="fas fa-plus-circle"></i>
                                <span>Nueva Sesión</span>
                            </button>    
                            <button class="quick-btn" onclick="generarReporte()">
                                <i class="fas fa-file-export"></i>
                                <span>Generar Reporte</span>
                            </button>
                        </div>

                        <div class="quick-stats">
                            <h4>Estado del Sistema</h4>
                            <div class="status-grid">
                                <div class="status-item">
                                    <div class="status-indicator <?php echo $estadoSistema['servicios_online'] ? 'online' : 'offline'; ?>"></div>
                                    <span>Servicios <?php echo $estadoSistema['servicios_online'] ? 'Online' : 'Offline'; ?></span>
                                </div>
                                <div class="status-item">
                                    <div class="status-indicator <?php echo $estadoSistema['mantenimiento_programado'] ? 'warning' : 'success'; ?>"></div>
                                    <span><?php echo $estadoSistema['mantenimiento_programado'] ? 'Mantenimiento programado' : 'Sistema operativo'; ?></span>
                                </div>
                                <div class="status-item">
                                    <div class="status-indicator <?php echo $estadoSistema['bd_optimizada'] ? 'success' : 'warning'; ?>"></div>
                                    <span>Base de datos <?php echo $estadoSistema['bd_optimizada'] ? 'optimizada' : 'requiere optimización'; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>

<script>
window.dashboardData = {
    metricas: <?php echo json_encode($metricas ?? []); ?>,
    estadisticas: <?php echo json_encode($estadisticasGestion ?? []); ?>,
    actividades: <?php echo json_encode($actividadReciente ?? []); ?>,
    estadoSistema: <?php echo json_encode($estadoSistema ?? []); ?>
};

console.log('Dashboard data:', window.dashboardData);
</script>

<?php require_once BASE_PATH . '/views/components/footer.php'; ?>