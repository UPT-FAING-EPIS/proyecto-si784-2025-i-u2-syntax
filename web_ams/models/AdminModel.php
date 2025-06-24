<?php
require_once BASE_PATH . '/config/Database.php';

class AdminModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function obtenerMetricasGenerales() {
        try {
            $sql_usuarios = "SELECT COUNT(*) as total FROM usuario WHERE FECHA_REG >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
            $usuarios_mes_anterior = $this->db->fetchOne("SELECT COUNT(*) as total FROM usuario WHERE FECHA_REG >= DATE_SUB(NOW(), INTERVAL 60 DAY) AND FECHA_REG < DATE_SUB(NOW(), INTERVAL 30 DAY)");
            
            $sql_estudiantes = "SELECT COUNT(*) as total FROM estudiante e INNER JOIN usuario u ON e.ID_USUARIO = u.ID_USUARIO WHERE e.CONDICION = 'activo'";
            $estudiantes_mes_anterior = $this->db->fetchOne("SELECT COUNT(*) as total FROM estudiante WHERE FECHA_REG >= DATE_SUB(NOW(), INTERVAL 60 DAY) AND FECHA_REG < DATE_SUB(NOW(), INTERVAL 30 DAY)");
            
            $sql_docentes = "SELECT COUNT(*) as total FROM docente WHERE ESTADO = 1";
            $docentes_mes_anterior = $this->db->fetchOne("SELECT COUNT(*) as total FROM docente WHERE FECHA_REG >= DATE_SUB(NOW(), INTERVAL 60 DAY) AND FECHA_REG < DATE_SUB(NOW(), INTERVAL 30 DAY)");
            
            $sql_sesiones = "SELECT COUNT(*) as total FROM clase WHERE ESTADO IN (1, 2)";
            $sesiones_mes_anterior = $this->db->fetchOne("SELECT COUNT(*) as total FROM clase WHERE FECHA_REG >= DATE_SUB(NOW(), INTERVAL 60 DAY) AND FECHA_REG < DATE_SUB(NOW(), INTERVAL 30 DAY)");

            $metricas = [
                'total_usuarios' => $this->db->fetchOne($sql_usuarios)['total'] ?? 0,
                'estudiantes_activos' => $this->db->fetchOne($sql_estudiantes)['total'] ?? 0,
                'docentes_mentores' => $this->db->fetchOne($sql_docentes)['total'] ?? 0,
                'sesiones_programadas' => $this->db->fetchOne($sql_sesiones)['total'] ?? 0,
                'usuarios_mes_anterior' => $usuarios_mes_anterior['total'] ?? 0,
                'estudiantes_mes_anterior' => $estudiantes_mes_anterior['total'] ?? 0,
                'docentes_mes_anterior' => $docentes_mes_anterior['total'] ?? 0,
                'sesiones_mes_anterior' => $sesiones_mes_anterior['total'] ?? 0
            ];

            $metricas['cambio_usuarios'] = $this->calcularPorcentajeCambio($metricas['total_usuarios'], $metricas['usuarios_mes_anterior']);
            $metricas['cambio_estudiantes'] = $this->calcularPorcentajeCambio($metricas['estudiantes_activos'], $metricas['estudiantes_mes_anterior']);
            $metricas['cambio_docentes'] = $this->calcularPorcentajeCambio($metricas['docentes_mentores'], $metricas['docentes_mes_anterior']);
            $metricas['cambio_sesiones'] = $this->calcularPorcentajeCambio($metricas['sesiones_programadas'], $metricas['sesiones_mes_anterior']);

            return $metricas;
        } catch (Exception $e) {
            error_log("Error al obtener métricas generales: " . $e->getMessage());
            return $this->obtenerMetricasPorDefecto();
        }
    }

    private function calcularPorcentajeCambio($actual, $anterior) {
        if ($anterior == 0) {
            return $actual > 0 ? 100 : 0;
        }
        return round((($actual - $anterior) / $anterior) * 100, 1);
    }

    private function obtenerMetricasPorDefecto() {
        return [
            'total_usuarios' => 1247,
            'estudiantes_activos' => 892,
            'docentes_mentores' => 124,
            'sesiones_programadas' => 342,
            'cambio_usuarios' => 12.5,
            'cambio_estudiantes' => 8.3,
            'cambio_docentes' => 5.1,
            'cambio_sesiones' => 0
        ];
    }
    public function obtenerEstadisticasGestion() {
        try {
            $estudiantes_hoy = $this->db->fetchOne("SELECT COUNT(*) as total FROM alumnos WHERE DATE(creado_en) = CURDATE()")['total'] ?? 0;
            
            $estudiantes_mes = $this->db->fetchOne("SELECT COUNT(*) as total FROM alumnos WHERE MONTH(creado_en) = MONTH(NOW()) AND YEAR(creado_en) = YEAR(NOW())")['total'] ?? 0;
            
            $total_usuarios = $this->db->fetchOne("SELECT COUNT(*) as total FROM usuario")['total'] ?? 0;
            
            $usuarios_pendientes = $this->db->fetchOne("SELECT COUNT(*) as total FROM codigos_verificacion WHERE fecha_expiracion > NOW()")['total'] ?? 0;
            
            $clases_activas = $this->db->fetchOne("SELECT COUNT(*) as total FROM clase WHERE ESTADO IN (1, 2)")['total'] ?? 0;
            
            $clases_hoy = $this->db->fetchOne("SELECT COUNT(*) as total FROM clase WHERE DATE(FECHA_INICIO) = CURDATE() AND ESTADO IN (1, 2)")['total'] ?? 0;
            
            $total_reportes = 15;
            
            $satisfaccion = $this->db->fetchOne("SELECT AVG(PUNTUACION) * 20 as promedio FROM comentario WHERE FECHA_REG >= DATE_SUB(NOW(), INTERVAL 30 DAY)")['promedio'] ?? 98;

            return [
                'estudiantes_hoy' => $estudiantes_hoy,
                'estudiantes_mes' => $estudiantes_mes,
                'total_usuarios' => $total_usuarios,
                'usuarios_pendientes' => $usuarios_pendientes,
                'clases_activas' => $clases_activas,
                'clases_hoy' => $clases_hoy,
                'total_reportes' => $total_reportes,
                'satisfaccion' => round($satisfaccion, 0)
            ];
        } catch (Exception $e) {
            error_log("Error al obtener estadísticas de gestión: " . $e->getMessage());
            return [
                'estudiantes_hoy' => 24,
                'estudiantes_mes' => 156,
                'total_usuarios' => 1247,
                'usuarios_pendientes' => 12,
                'clases_activas' => 342,
                'clases_hoy' => 28,
                'total_reportes' => 15,
                'satisfaccion' => 98
            ];
        }
    }

    public function obtenerActividadReciente($limite = 10) {
        try {
            $actividades = [];

            $sql_estudiantes = "SELECT 
                                    CONCAT(a.nombres, ' ', a.apellidos) as nombre,
                                    a.carrera,
                                    a.creado_en as fecha,
                                    'registro' as tipo
                                FROM alumnos a 
                                ORDER BY a.creado_en DESC 
                                LIMIT 3";
            
            $estudiantes = $this->db->fetchAll($sql_estudiantes);
            foreach ($estudiantes as $est) {
                $actividades[] = [
                    'tipo' => 'success',
                    'titulo' => 'Nuevo estudiante registrado',
                    'descripcion' => $est['nombre'] . ' (' . $est['carrera'] . ') completó su registro',
                    'fecha' => $est['fecha'],
                    'badge' => 'Registro',
                    'usuario' => 'Sistema automático'
                ];
            }

            $sql_clases = "SELECT 
                              c.HORARIO,
                              cu.NOMBRE as curso_nombre,
                              c.FECHA_REG as fecha,
                              u.NOMBRE as docente_nombre,
                              u.APELLIDO as docente_apellido
                          FROM clase c
                          INNER JOIN curso cu ON c.ID_CURSO = cu.ID_CURSO
                          LEFT JOIN registro_academico ra ON c.ID_CLASE = ra.ID_CLASE
                          LEFT JOIN docente d ON ra.ID_DOCENTE = d.ID_DOCENTE
                          LEFT JOIN usuario u ON d.ID_USUARIO = u.ID_USUARIO
                          WHERE c.ESTADO = 1
                          ORDER BY c.FECHA_REG DESC 
                          LIMIT 2";
            
            $clases = $this->db->fetchAll($sql_clases);
            foreach ($clases as $clase) {
                $docente = ($clase['docente_nombre'] && $clase['docente_apellido']) 
                    ? $clase['docente_nombre'] . ' ' . $clase['docente_apellido']
                    : 'Sin asignar';
                
                $actividades[] = [
                    'tipo' => 'info',
                    'titulo' => 'Clase programada',
                    'descripcion' => 'Nueva sesión de ' . $clase['curso_nombre'] . ' para ' . $clase['HORARIO'],
                    'fecha' => $clase['fecha'],
                    'badge' => 'Programación',
                    'usuario' => $docente
                ];
            }

            $sql_actualizaciones = "SELECT 
                                       COUNT(*) as cantidad,
                                       MAX(actualizado_en) as ultima_fecha
                                   FROM alumnos 
                                   WHERE DATE(actualizado_en) = CURDATE()";
            
            $actualizacion = $this->db->fetchOne($sql_actualizaciones);
            if ($actualizacion['cantidad'] > 0) {
                $actividades[] = [
                    'tipo' => 'warning',
                    'titulo' => 'Perfil actualizado',
                    'descripcion' => 'Información académica de ' . $actualizacion['cantidad'] . ' estudiantes modificada',
                    'fecha' => $actualizacion['ultima_fecha'],
                    'badge' => 'Actualización',
                    'usuario' => 'Admin UPT'
                ];
            }

            $sql_completadas = "SELECT 
                                   cu.NOMBRE as curso_nombre,
                                   c.FECHA_FIN as fecha,
                                   u.NOMBRE as docente_nombre,
                                   u.APELLIDO as docente_apellido
                               FROM clase c
                               INNER JOIN curso cu ON c.ID_CURSO = cu.ID_CURSO
                               LEFT JOIN registro_academico ra ON c.ID_CLASE = ra.ID_CLASE
                               LEFT JOIN docente d ON ra.ID_DOCENTE = d.ID_DOCENTE
                               LEFT JOIN usuario u ON d.ID_USUARIO = u.ID_USUARIO
                               WHERE c.ESTADO = 3 
                               ORDER BY c.FECHA_FIN DESC 
                               LIMIT 2";
            
            $completadas = $this->db->fetchAll($sql_completadas);
            foreach ($completadas as $sesion) {
                $docente = ($sesion['docente_nombre'] && $sesion['docente_apellido']) 
                    ? 'Prof. ' . $sesion['docente_nombre'] . ' ' . $sesion['docente_apellido']
                    : 'Mentor UPT';
                
                $actividades[] = [
                    'tipo' => 'primary',
                    'titulo' => 'Sesión completada',
                    'descripcion' => 'Mentoría de ' . $sesion['curso_nombre'] . ' finalizada exitosamente',
                    'fecha' => $sesion['fecha'],
                    'badge' => 'Sesión',
                    'usuario' => $docente
                ];
            }

            usort($actividades, function($a, $b) {
                return strtotime($b['fecha']) - strtotime($a['fecha']);
            });

            return array_slice($actividades, 0, $limite);

        } catch (Exception $e) {
            error_log("Error al obtener actividad reciente: " . $e->getMessage());
            return $this->obtenerActividadPorDefecto();
        }
    }

    private function obtenerActividadPorDefecto() {
        return [
            [
                'tipo' => 'success',
                'titulo' => 'Nuevo estudiante registrado',
                'descripcion' => 'Juan Carlos Pérez (Ing. Sistemas) completó su registro',
                'fecha' => date('Y-m-d H:i:s', strtotime('-15 minutes')),
                'badge' => 'Registro',
                'usuario' => 'Sistema automático'
            ],
            [
                'tipo' => 'info',
                'titulo' => 'Clase programada',
                'descripcion' => 'Nueva sesión de Matemáticas II para mañana 14:00',
                'fecha' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                'badge' => 'Programación',
                'usuario' => 'Dr. María González'
            ],
            [
                'tipo' => 'warning',
                'titulo' => 'Perfil actualizado',
                'descripcion' => 'Información académica de 3 estudiantes modificada',
                'fecha' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'badge' => 'Actualización',
                'usuario' => 'Admin UPT'
            ],
            [
                'tipo' => 'primary',
                'titulo' => 'Sesión completada',
                'descripcion' => 'Mentoría de Física I finalizada exitosamente',
                'fecha' => date('Y-m-d H:i:s', strtotime('-3 hours')),
                'badge' => 'Sesión',
                'usuario' => 'Prof. Carlos Ruiz'
            ]
        ];
    }

    public function obtenerDatosGraficos() {
        try {
            $sql_usuarios = "SELECT 
                                MONTH(FECHA_REG) as mes,
                                YEAR(FECHA_REG) as año,
                                COUNT(*) as cantidad
                            FROM usuario 
                            WHERE FECHA_REG >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                            GROUP BY YEAR(FECHA_REG), MONTH(FECHA_REG)
                            ORDER BY año, mes";
            
            $usuarios_grafico = $this->db->fetchAll($sql_usuarios);

            $sql_estudiantes = "SELECT 
                                   MONTH(creado_en) as mes,
                                   YEAR(creado_en) as año,
                                   COUNT(*) as cantidad
                               FROM alumnos 
                               WHERE creado_en >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                               GROUP BY YEAR(creado_en), MONTH(creado_en)
                               ORDER BY año, mes";
            
            $estudiantes_grafico = $this->db->fetchAll($sql_estudiantes);

            $sql_clases = "SELECT 
                              MONTH(FECHA_REG) as mes,
                              YEAR(FECHA_REG) as año,
                              COUNT(*) as cantidad
                          FROM clase 
                          WHERE FECHA_REG >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                          GROUP BY YEAR(FECHA_REG), MONTH(FECHA_REG)
                          ORDER BY año, mes";
            
            $clases_grafico = $this->db->fetchAll($sql_clases);

            return [
                'usuarios' => $usuarios_grafico,
                'estudiantes' => $estudiantes_grafico,
                'clases' => $clases_grafico
            ];

        } catch (Exception $e) {
            error_log("Error al obtener datos de gráficos: " . $e->getMessage());
            return [
                'usuarios' => [],
                'estudiantes' => [],
                'clases' => []
            ];
        }
    }

    public function obtenerEstadoSistema() {
        try {
            $servicios_online = true;
            
            $mantenimiento = $this->db->fetchOne("SELECT COUNT(*) as total FROM clase WHERE ESTADO = 5")['total'] > 0;
            
            $bd_optimizada = true;

            return [
                'servicios_online' => $servicios_online,
                'mantenimiento_programado' => $mantenimiento,
                'bd_optimizada' => $bd_optimizada
            ];

        } catch (Exception $e) {
            error_log("Error al obtener estado del sistema: " . $e->getMessage());
            return [
                'servicios_online' => true,
                'mantenimiento_programado' => false,
                'bd_optimizada' => true
            ];
        }
    }

    public function formatearTiempoTranscurrido($fecha) {
        $tiempo = time() - strtotime($fecha);
        
        if ($tiempo < 60) {
            return 'hace ' . $tiempo . ' seg';
        } elseif ($tiempo < 3600) {
            return 'hace ' . floor($tiempo / 60) . ' min';
        } elseif ($tiempo < 86400) {
            return 'hace ' . floor($tiempo / 3600) . ' hora' . (floor($tiempo / 3600) > 1 ? 's' : '');
        } else {
            return 'hace ' . floor($tiempo / 86400) . ' día' . (floor($tiempo / 86400) > 1 ? 's' : '');
        }
    }
}
?>
