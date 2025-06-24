<?php include BASE_PATH . '/views/components/head.php'; ?>
<?php include BASE_PATH . '/views/components/header.php'; ?>

<main>
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center hero-content">
                <div class="col-lg-6">
                    <h1 class="hero-title fade-in-up">Sistema de Mentoría Académica UPT</h1>
                    <p class="hero-subtitle fade-in-up">
                        Conectamos estudiantes con mentores especializados para potenciar tu rendimiento académico
                        y acompañarte en tu camino hacia el éxito universitario.
                    </p>
                    <div class="fade-in-up">
                        <a href="#servicios" class="btn btn-primary-custom me-3">Explorar Servicios</a>
                        <a href="#como-funciona" class="btn btn-outline-primary-custom">¿Cómo Funciona?</a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <img src="<?= BASE_URL ?>/public/img/estudiantes.png" alt="Estudiantes UPT" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Servicios -->
    <section id="servicios" class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="display-4 fw-bold text-primary mb-3">Nuestros Servicios</h2>
                    <p class="lead text-muted">Descubre todas las formas en que podemos apoyar tu desarrollo académico</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card service-card">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-chalkboard-teacher service-icon"></i>
                            <h4 class="service-title">Mentoría Personalizada</h4>
                            <p class="text-muted">Sesiones uno a uno con mentores especializados en tu área de estudio para reforzar conceptos y resolver dudas específicas.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card service-card">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-users service-icon"></i>
                            <h4 class="service-title">Grupos de Estudio</h4>
                            <p class="text-muted">Únete a grupos de estudiantes con intereses similares y aprende colaborativamente bajo la guía de un mentor.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card service-card">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-chart-line service-icon"></i>
                            <h4 class="service-title">Seguimiento Académico</h4>
                            <p class="text-muted">Monitoreo continuo de tu progreso académico con reportes personalizados y planes de mejora.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card service-card">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-laptop-code service-icon"></i>
                            <h4 class="service-title">Recursos Digitales</h4>
                            <p class="text-muted">Acceso a materiales de estudio, simuladores, ejercicios interactivos y biblioteca digital especializada.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card service-card">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-calendar-alt service-icon"></i>
                            <h4 class="service-title">Clases Programadas</h4>
                            <p class="text-muted">Horarios flexibles adaptados a tu disponibilidad con recordatorios automáticos y reprogramación fácil.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card service-card">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-graduation-cap service-icon"></i>
                            <h4 class="service-title">Preparación de Exámenes</h4>
                            <p class="text-muted">Estrategias específicas y práctica intensiva para prepararte para exámenes parciales, finales y de certificación.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Estadísticas -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">1,500+</span>
                        <span class="stat-label">Estudiantes Atendidos</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">85%</span>
                        <span class="stat-label">Mejora Promedio</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">50+</span>
                        <span class="stat-label">Mentores Certificados</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <span class="stat-label">Soporte Disponible</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Explicativo -->
    <section id="video-explicativo" class="video-section">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="display-4 fw-bold text-primary mb-3">Conoce Nuestro Sistema</h2>
                    <p class="lead text-muted">Mira cómo funciona la plataforma de mentoría académica</p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="video-container position-relative">
                        <img src="<?= BASE_URL ?>/public/img/epis.jpg"
                             alt="Video explicativo" 
                             class="img-fluid rounded w-100">
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <a href="https://www.youtube.com/watch?v=8t0eXBT5xp0" 
                               target="_blank" 
                               class="btn btn-danger btn-lg rounded-circle p-3">
                                <i class="fas fa-play"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cómo Solicitar una Clase -->
    <section id="como-funciona" class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="display-4 fw-bold text-primary mb-3">¿Cómo Solicitar una Mentoría?</h2>
                    <p class="lead text-muted">Sigue estos simples pasos para comenzar tu experiencia de aprendizaje</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="process-step">
                        <div class="step-number">1</div>
                        <h5 class="step-title">Registro</h5>
                        <p class="text-muted">Enlaza tú cuenta de usuario con una de estudiante para poder aprovechar de este beneficio.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="process-step">
                        <div class="step-number">2</div>
                        <h5 class="step-title">Selecciona Materia</h5>
                        <p class="text-muted">Elige la asignatura en la que necesitas apoyo académico</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="process-step">
                        <div class="step-number">3</div>
                        <h5 class="step-title">Agenda tu Sesión</h5>
                        <p class="text-muted">Selecciona horario disponible según tu preferencia (Necesita aprobación)</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="process-step">
                        <div class="step-number">4</div>
                        <h5 class="step-title">¡Aprende!</h5>
                        <p class="text-muted">Conecta con tu mentor y comienza tu sesión de aprendizaje</p>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <img src="<?= BASE_URL ?>/public/img/proceso.png" alt="Proceso de solicitud" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Información UPT -->
    <section class="upt-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4 text-center mb-4 mb-lg-0">
                    <img src="<?= BASE_URL ?>/public/img/upt.png" alt="Logo UPT" class="upt-logo">
                </div>
                <div class="col-lg-8">
                    <h2 class="display-5 fw-bold text-primary mb-4">Universidad Privada de Tacna</h2>
                    <p class="lead mb-4">
                        Fundada en 1985, la Universidad Privada de Tacna se ha consolidado como una institución de excelencia académica 
                        en el sur del Perú. Nuestro compromiso con la calidad educativa nos impulsa a implementar programas innovadores 
                        como este sistema de mentoría académica.
                    </p>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>15+ Carreras Profesionales</li>
                                <li><i class="fas fa-check text-success me-2"></i>Acreditación Internacional</li>
                                <li><i class="fas fa-check text-success me-2"></i>Docentes Calificados</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Campus Moderno</li>
                                <li><i class="fas fa-check text-success me-2"></i>Laboratorios Equipados</li>
                                <li><i class="fas fa-check text-success me-2"></i>Biblioteca Digital</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonios -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="display-4 fw-bold text-primary mb-3">Lo Que Dicen Nuestros Estudiantes</h2>
                    <p class="lead text-muted">Experiencias reales de estudiantes que han mejorado con nuestro sistema</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <i class="fas fa-quote-right quote-icon"></i>
                        <p class="testimonial-text">
                            "Gracias al sistema de mentoría pude mejorar mis notas en Cálculo de un 12 a un 17. 
                            Mi mentor fue muy paciente y me ayudó a entender conceptos que parecían imposibles."
                        </p>
                        <div class="testimonial-author">María González - Ingeniería Civil</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <i class="fas fa-quote-right quote-icon"></i>
                        <p class="testimonial-text">
                            "Los grupos de estudio me ayudaron mucho a prepararme para los exámenes finales. 
                            Poder estudiar con otros compañeros bajo la guía de un mentor fue increíble."
                        </p>
                        <div class="testimonial-author">Carlos Mendoza - Administración</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <i class="fas fa-quote-right quote-icon"></i>
                        <p class="testimonial-text">
                            "El seguimiento personalizado me motivó a mantener un ritmo constante de estudio. 
                            Ahora tengo mejores hábitos académicos y mis padres están orgullosos."
                        </p>
                        <div class="testimonial-author">Ana Quispe - Psicología</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-12">
                    <h2 class="display-4 fw-bold mb-4">¿Listo para Mejorar tu Rendimiento Académico?</h2>
                    <p class="lead mb-4">Únete a miles de estudiantes que ya están transformando su experiencia universitaria</p>
                    <a href="<?= BASE_URL ?>/index.php?accion=registro" class="btn btn-primary-custom btn-lg me-3">Registrarse Ahora</a>
                    <a href="<?php echo BASE_URL; ?>/index.php?accion=faq" class="btn btn-outline-primary-custom btn-lg">Más Información</a>
                </div>
            </div>
        </div>
    </section>
</main>


<?php include BASE_PATH . '/views/components/footer.php'; ?>