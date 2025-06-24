<?php
require_once BASE_PATH . '/views/components/head.php';
require_once BASE_PATH . '/views/components/header.php';
?>
<!DOCTYPE html>
<html lang="es">
<body>
    <div class="contenido-dashboard">
        <div class="container-fluid">
            <div class="panel-central">
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-lg border-0">
                            <div class="card-header bg-gradient text-white text-center py-4">
                                <h2 class="mb-0">
                                    <i class="fas fa-quote-left me-2"></i>
                                    Testimonios
                                </h2>
                                <p class="mb-0 mt-2">Lo que dicen nuestros usuarios sobre nosotros</p>
                            </div>

                            <div class="card-body p-4">
                                <!-- Estadísticas ficticias -->
                                <div class="row mb-4">
                                    <div class="col-md-4 text-center">
                                        <h4 class="text-primary">127</h4>
                                        <small class="text-muted">Total Testimonios</small>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <h4 class="text-warning">4.5</h4>
                                        <small class="text-muted">Calificación Promedio</small>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <h4 class="text-success">85</h4>
                                        <small class="text-muted">Usuarios Satisfechos</small>
                                    </div>
                                </div>

                                <!-- Filtros -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                                            <input type="text" class="form-control" id="searchTestimonio" placeholder="Buscar en testimonios...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select" id="filterCalificacion">
                                            <option value="">Todas las calificaciones</option>
                                            <option value="5">5 estrellas</option>
                                            <option value="4">4 estrellas</option>
                                            <option value="3">3 estrellas</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-primary w-100" onclick="agregarTestimonio()">
                                            <i class="fas fa-plus me-1"></i> Añadir Testimonio
                                        </button>
                                    </div>
                                </div>

                                <!-- Testimonios ficticios -->
                                <div class="row" id="testimoniosGrid">
                                    <!-- Testimonio 1 -->
                                    <div class="col-md-6 mb-4 testimonio-card" data-calificacion="5" data-content="excelente plataforma y mentores">
                                        <div class="card h-100 shadow-sm hover-card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    <img src="https://via.placeholder.com/50" class="rounded-circle me-3" alt="Usuario 1">
                                                    <div>
                                                        <h6 class="mb-1 text-primary">María González</h6>
                                                        <div class="rating">
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-warning"></i>
                                                        </div>
                                                        <small class="text-muted">05/06/2025</small>
                                                    </div>
                                                </div>
                                                <p>Excelente plataforma y mentores muy profesionales. Aprendí mucho en poco tiempo.</p>
                                                <div class="badge bg-primary"><i class="fas fa-graduation-cap me-1"></i> Desarrollo Web</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Testimonio 2 -->
                                    <div class="col-md-6 mb-4 testimonio-card" data-calificacion="4" data-content="buena experiencia y buenos recursos">
                                        <div class="card h-100 shadow-sm hover-card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    <img src="https://via.placeholder.com/50" class="rounded-circle me-3" alt="Usuario 2">
                                                    <div>
                                                        <h6 class="mb-1 text-primary">Luis Ramírez</h6>
                                                        <div class="rating">
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                        </div>
                                                        <small class="text-muted">03/06/2025</small>
                                                    </div>
                                                </div>
                                                <p>Buena experiencia general. Los recursos proporcionados fueron muy útiles.</p>
                                                <div class="badge bg-primary"><i class="fas fa-graduation-cap me-1"></i> Marketing Digital</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Testimonio 3 -->
                                    <div class="col-md-6 mb-4 testimonio-card" data-calificacion="3" data-content="cumple su función pero puede mejorar">
                                        <div class="card h-100 shadow-sm hover-card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    <img src="https://via.placeholder.com/50" class="rounded-circle me-3" alt="Usuario 3">
                                                    <div>
                                                        <h6 class="mb-1 text-primary">Carmen Torres</h6>
                                                        <div class="rating">
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-warning"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                            <i class="fas fa-star text-muted"></i>
                                                        </div>
                                                        <small class="text-muted">01/06/2025</small>
                                                    </div>
                                                </div>
                                                <p>La plataforma cumple su función, pero podría mejorar en tiempos de respuesta.</p>
                                                <div class="badge bg-primary"><i class="fas fa-graduation-cap me-1"></i> Habilidades Blandas</div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- /testimoniosGrid -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('searchTestimonio').addEventListener('input', filterTestimonios);
        document.getElementById('filterCalificacion').addEventListener('change', filterTestimonios);

        function filterTestimonios() {
            const searchTerm = document.getElementById('searchTestimonio').value.toLowerCase();
            const calificacionFilter = document.getElementById('filterCalificacion').value;
            const testimonioCards = document.querySelectorAll('.testimonio-card');

            testimonioCards.forEach(card => {
                const content = card.dataset.content;
                const calificacion = card.dataset.calificacion;
                const matchesSearch = content.includes(searchTerm);
                const matchesCalificacion = !calificacionFilter || calificacion === calificacionFilter;
                card.style.display = matchesSearch && matchesCalificacion ? 'block' : 'none';
            });
        }

        function agregarTestimonio() {
            alert('Funcionalidad ficticia: abrir modal para agregar testimonio');
        }
    </script>
</body>
</html>
