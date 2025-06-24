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
                                    <i class="fas fa-users me-2"></i>
                                    Nuestros Mentores
                                </h2>
                                <p class="mb-0 mt-2">Conoce a nuestro equipo de mentores expertos</p>
                            </div>
                            <div class="card-body p-4">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-search"></i>
                                            </span>
                                            <input type="text" class="form-control" id="searchMentor" placeholder="Buscar mentor por nombre o especialidad...">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-select" id="filterArea">
                                            <option value="">Todas las áreas</option>
                                            <option value="Tecnología">Tecnología</option>
                                            <option value="Negocios">Negocios</option>
                                            <option value="Marketing">Marketing</option>
                                            <option value="Diseño">Diseño</option>
                                            <option value="Desarrollo Personal">Desarrollo Personal</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-primary w-100" onclick="clearFilters()">
                                            <i class="fas fa-refresh"></i> Limpiar
                                        </button>
                                    </div>
                                </div>

                                <!-- Mentores ficticios -->
                                <div class="row" id="mentoresGrid">
                                    <div class="col-lg-4 col-md-6 mb-4 mentor-card" data-nombre="juan pérez" data-area="Tecnología">
                                        <div class="card h-100 shadow-sm hover-card">
                                            <div class="card-body text-center">
                                                <div class="mentor-avatar mb-3">
                                                    <img src="https://images.pexels.com/photos/8197527/pexels-photo-8197527.jpeg" class="rounded-circle img-fluid mentor-img" alt="Juan Pérez">
                                                </div>
                                                <h5 class="card-title text-primary mb-2">Juan Pérez</h5>
                                                <div class="badge bg-primary mb-2">Tecnología</div>
                                                <p class="card-text text-muted small mb-3">Ingeniero de software con más de 10 años de experiencia en desarrollo web y móvil.</p>
                                                <div class="row text-center mb-3">
                                                    <div class="col-6">
                                                        <div class="stat-box">
                                                            <h6 class="text-primary mb-0">120</h6>
                                                            <small class="text-muted">Estudiantes</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="stat-box">
                                                            <h6 class="text-primary mb-0">6</h6>
                                                            <small class="text-muted">Años exp.</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="rating mb-3">
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-muted"></i>
                                                    <span class="ms-2 text-muted">(4)</span>
                                                </div>
                                                <div class="d-grid gap-2">
                                                    <button class="btn btn-primary" onclick="verPerfilMentor(1)">
                                                        <i class="fas fa-eye me-1"></i> Ver Perfil
                                                    </button>
                                                    <button class="btn btn-outline-primary" onclick="contactarMentor(1)">
                                                        <i class="fas fa-envelope me-1"></i> Contactar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6 mb-4 mentor-card" data-nombre="ana lópez" data-area="Marketing">
                                        <div class="card h-100 shadow-sm hover-card">
                                            <div class="card-body text-center">
                                                <div class="mentor-avatar mb-3">
                                                    <img src="https://images.pexels.com/photos/7516347/pexels-photo-7516347.jpeg" class="rounded-circle img-fluid mentor-img" alt="Ana López">
                                                </div>
                                                <h5 class="card-title text-primary mb-2">Ana López</h5>
                                                <div class="badge bg-primary mb-2">Marketing</div>
                                                <p class="card-text text-muted small mb-3">Especialista en marketing digital y branding con enfoque en redes sociales.</p>
                                                <div class="row text-center mb-3">
                                                    <div class="col-6">
                                                        <div class="stat-box">
                                                            <h6 class="text-primary mb-0">80</h6>
                                                            <small class="text-muted">Estudiantes</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="stat-box">
                                                            <h6 class="text-primary mb-0">7</h6>
                                                            <small class="text-muted">Años exp.</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="rating mb-3">
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-muted"></i>
                                                    <i class="fas fa-star text-muted"></i>
                                                    <span class="ms-2 text-muted">(3)</span>
                                                </div>
                                                <div class="d-grid gap-2">
                                                    <button class="btn btn-primary" onclick="verPerfilMentor(2)">
                                                        <i class="fas fa-eye me-1"></i> Ver Perfil
                                                    </button>
                                                    <button class="btn btn-outline-primary" onclick="contactarMentor(2)">
                                                        <i class="fas fa-envelope me-1"></i> Contactar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6 mb-4 mentor-card" data-nombre="carlos ramírez" data-area="Diseño">
                                        <div class="card h-100 shadow-sm hover-card">
                                            <div class="card-body text-center">
                                                <div class="mentor-avatar mb-3">
                                                    <img src="https://images.pexels.com/photos/3184328/pexels-photo-3184328.jpeg" class="rounded-circle img-fluid mentor-img" alt="Carlos Ramírez">
                                                </div>
                                                <h5 class="card-title text-primary mb-2">Carlos Ramírez</h5>
                                                <div class="badge bg-primary mb-2">Diseño</div>
                                                <p class="card-text text-muted small mb-3">Diseñador gráfico con experiencia en UI/UX para plataformas web y móviles.</p>
                                                <div class="row text-center mb-3">
                                                    <div class="col-6">
                                                        <div class="stat-box">
                                                            <h6 class="text-primary mb-0">95</h6>
                                                            <small class="text-muted">Estudiantes</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="stat-box">
                                                            <h6 class="text-primary mb-0">13</h6>
                                                            <small class="text-muted">Años exp.</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="rating mb-3">
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <i class="fas fa-star text-warning"></i>
                                                    <span class="ms-2 text-muted">(5)</span>
                                                </div>
                                                <div class="d-grid gap-2">
                                                    <button class="btn btn-primary" onclick="verPerfilMentor(3)">
                                                        <i class="fas fa-eye me-1"></i> Ver Perfil
                                                    </button>
                                                    <button class="btn btn-outline-primary" onclick="contactarMentor(3)">
                                                        <i class="fas fa-envelope me-1"></i> Contactar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- /mentoresGrid -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal perfil -->
    <div class="modal fade" id="mentorModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Perfil del Mentor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="mentorModalBody"></div>
            </div>
        </div>
    </div>

    <script>
        // funciones JS siguen igual (filtrado, modal, etc.)
        document.getElementById('searchMentor').addEventListener('input', filterMentores);
        document.getElementById('filterArea').addEventListener('change', filterMentores);

        function filterMentores() {
            const searchTerm = document.getElementById('searchMentor').value.toLowerCase();
            const areaFilter = document.getElementById('filterArea').value;
            const mentorCards = document.querySelectorAll('.mentor-card');

            mentorCards.forEach(card => {
                const nombre = card.dataset.nombre;
                const area = card.dataset.area;
                const matchesSearch = nombre.includes(searchTerm);
                const matchesArea = !areaFilter || area === areaFilter;
                card.style.display = matchesSearch && matchesArea ? 'block' : 'none';
            });
        }

        function clearFilters() {
            document.getElementById('searchMentor').value = '';
            document.getElementById('filterArea').value = '';
            filterMentores();
        }

        function verPerfilMentor(id) {
            alert('Mostrando perfil ficticio del mentor ID: ' + id);
        }

        function contactarMentor(id) {
            alert('Contactar con mentor ficticio ID: ' + id);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.mentor-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>

</html>