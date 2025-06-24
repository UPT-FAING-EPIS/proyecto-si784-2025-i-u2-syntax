<?php
require_once BASE_PATH . '/views/components/head.php';
require_once BASE_PATH . '/views/components/header.php';
?>

<div class="novedades-container">
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">
                <i class="fas fa-newspaper"></i>
                Novedades Académicas
            </h1>
            <p class="page-subtitle">Mantente informado sobre las últimas actualizaciones del sistema de mentoría</p>
        </div>
    </div>

    <div class="container">
        <div class="novedades-grid">
            <!-- Noticia destacada -->
            <div class="noticia-destacada">
                <div class="noticia-card featured">
                    <div class="noticia-image">
                        <img src="<?= BASE_URL ?>/assets/images/mentoria-destacada.jpg" alt="Mentoría Destacada">
                        <div class="badge-destacado">Destacado</div>
                    </div>
                    <div class="noticia-content">
                        <div class="noticia-meta">
                            <span class="fecha"><i class="far fa-calendar"></i> 20 de Junio, 2025</span>
                            <span class="categoria categoria-importante">Importante</span>
                        </div>
                        <h2 class="noticia-titulo">Nuevo Sistema de Validación Estudiantil UPT</h2>
                        <p class="noticia-resumen">
                            Ahora todos los estudiantes deben validar su código estudiantil UPT para acceder al sistema de mentoría. 
                            Esto garantiza que solo estudiantes verificados de la Universidad Privada de Tacna puedan participar.
                        </p>
                        <a href="#" class="btn-leer-mas">
                            Leer más <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Noticias secundarias -->
            <div class="noticias-secundarias">
                <div class="noticia-card">
                    <div class="noticia-content">
                        <div class="noticia-meta">
                            <span class="fecha"><i class="far fa-calendar"></i> 18 de Junio, 2025</span>
                            <span class="categoria categoria-evento">Evento</span>
                        </div>
                        <h3 class="noticia-titulo">Registro con Google Auth Disponible</h3>
                        <p class="noticia-resumen">
                            Ahora puedes registrarte más fácilmente usando tu cuenta de Google o tu DNI y correo UPT.
                        </p>
                        <a href="#" class="enlace-simple">Más información</a>
                    </div>
                </div>

                <div class="noticia-card">
                    <div class="noticia-content">
                        <div class="noticia-meta">
                            <span class="fecha"><i class="far fa-calendar"></i> 15 de Junio, 2025</span>
                            <span class="categoria categoria-actualizacion">Actualización</span>
                        </div>
                        <h3 class="noticia-titulo">Nueva Función: Sugerir Clases Problemáticas</h3>
                        <p class="noticia-resumen">
                            Los estudiantes ahora pueden sugerir clases específicas donde necesitan ayuda y los mentores pueden tomarlas directamente.
                        </p>
                        <a href="#" class="enlace-simple">Ver cambios</a>
                    </div>
                </div>

                <div class="noticia-card">
                    <div class="noticia-content">
                        <div class="noticia-meta">
                            <span class="fecha"><i class="far fa-calendar"></i> 12 de Junio, 2025</span>
                            <span class="categoria categoria-reconocimiento">Reconocimiento</span>
                        </div>
                        <h3 class="noticia-titulo">Mentores del Mes</h3>
                        <p class="noticia-resumen">
                            Conoce a los mentores destacados por su excelente desempeño este mes.
                        </p>
                        <a href="#" class="enlace-simple">Ver mentores</a>
                    </div>
                </div>

                <div class="noticia-card">
                    <div class="noticia-content">
                        <div class="noticia-meta">
                            <span class="fecha"><i class="far fa-calendar"></i> 10 de Junio, 2025</span>
                            <span class="categoria categoria-academico">Académico</span>
                        </div>
                        <h3 class="noticia-titulo">Nuevas Carreras Agregadas al Sistema</h3>
                        <p class="noticia-resumen">
                            Ampliamos la cobertura para todas las facultades de la UPT: Ingeniería, Arquitectura, Ciencias Empresariales y más.
                        </p>
                        <a href="#" class="enlace-simple">Explorar materias</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de archivo -->
        <div class="archivo-section">
            <h2 class="section-title">
                <i class="fas fa-archive"></i>
                Archivo de Noticias
            </h2>
            <div class="filtros-archivo">
                <button class="filtro-btn active" data-categoria="todas">Todas</button>
                <button class="filtro-btn" data-categoria="importante">Importantes</button>
                <button class="filtro-btn" data-categoria="evento">Eventos</button>
                <button class="filtro-btn" data-categoria="actualizacion">Actualizaciones</button>
            </div>
            <div class="archivo-grid">
                <!-- Las noticias del archivo se cargarían dinámicamente -->
                <div class="archivo-item" data-categoria="actualizacion">
                    <div class="archivo-fecha">08 JUN</div>
                    <div class="archivo-content">
                        <h4>Mantenimiento programado del sistema</h4>
                        <p>El sistema estará en mantenimiento el próximo domingo.</p>
                    </div>
                </div>
                <div class="archivo-item" data-categoria="evento">
                    <div class="archivo-fecha">05 JUN</div>
                    <div class="archivo-content">
                        <h4>Conferencia sobre metodologías de aprendizaje</h4>
                        <p>Evento virtual gratuito para todos los estudiantes.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary-blue: #1e3a5f;
    --secondary-blue: #2c5282;
    --accent-green: #28a745;
    --light-green: #20c997;
    --white: #ffffff;
    --shadow-medium: 0 4px 20px rgba(0,0,0,0.1);
}

.novedades-container {
    min-height: calc(100vh - 120px);
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.page-header {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    color: white;
    padding: 4rem 0 2rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
}

.page-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    position: relative;
    z-index: 1;
}

.page-title i {
    margin-right: 1rem;
    color: var(--accent-green);
}

.page-subtitle {
    font-size: 1.25rem;
    opacity: 0.9;
    position: relative;
    z-index: 1;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.novedades-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin: 3rem 0;
}

.noticia-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid rgba(59, 130, 246, 0.1);
}

.noticia-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(46, 82, 130, 0.15);
}

.noticia-card.featured {
    background: linear-gradient(135deg, white 0%, #f8fafc 100%);
}

.noticia-image {
    position: relative;
    height: 250px;
    background: linear-gradient(135deg, var(--secondary-blue) 0%, var(--primary-blue) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 4rem;
}

.noticia-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.badge-destacado {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: var(--accent-green);
    color: var(--white);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.875rem;
}

.noticia-content {
    padding: 1.5rem;
}

.noticia-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.fecha {
    color: #6b7280;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.categoria {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.categoria-importante { background: #fee2e2; color: #dc2626; }
.categoria-evento { background: #dbeafe; color: #2563eb; }
.categoria-actualizacion { background: #d1fae5; color: #059669; }
.categoria-reconocimiento { background: #fef3c7; color: #d97706; }
.categoria-academico { background: #e0e7ff; color: #5b21b6; }

.noticia-titulo {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1rem;
    line-height: 1.3;
}

.noticias-secundarias .noticia-titulo {
    font-size: 1.25rem;
}

.noticia-resumen {
    color: #6b7280;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.btn-leer-mas {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, var(--secondary-blue) 0%, var(--primary-blue) 100%);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-leer-mas:hover {
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(46, 82, 130, 0.3);
    text-decoration: none;
    color: white;
}

.enlace-simple {
    color: var(--secondary-blue);
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.enlace-simple:hover {
    color: var(--primary-blue);
    text-decoration: underline;
}

.noticias-secundarias {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.archivo-section {
    margin-top: 4rem;
    padding-top: 3rem;
    border-top: 2px solid #e5e7eb;
}

.section-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.section-title i {
    color: var(--secondary-blue);
}

.filtros-archivo {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.filtro-btn {
    padding: 0.5rem 1rem;
    border: 2px solid #e5e7eb;
    background: white;
    color: #6b7280;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
}

.filtro-btn:hover,
.filtro-btn.active {
    border-color: var(--secondary-blue);
    background: var(--secondary-blue);
    color: white;
}

.archivo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.archivo-item {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    border-left: 4px solid var(--secondary-blue);
    box-shadow: var(--shadow-medium);
    display: flex;
    gap: 1rem;
}

.archivo-fecha {
    background: #f3f4f6;
    padding: 0.5rem;
    border-radius: 8px;
    text-align: center;
    font-weight: 700;
    color: var(--secondary-blue);
    min-width: 60px;
    font-size: 0.875rem;
}

.archivo-content h4 {
    color: #1f2937;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.archivo-content p {
    color: #6b7280;
    font-size: 0.875rem;
}

@media (max-width: 768px) {
    .novedades-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .filtros-archivo {
        justify-content: center;
    }
    
    .archivo-item {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filtros = document.querySelectorAll('.filtro-btn');
    const items = document.querySelectorAll('.archivo-item');
    
    filtros.forEach(filtro => {
        filtro.addEventListener('click', function() {
            filtros.forEach(f => f.classList.remove('active'));
            this.classList.add('active');
            
            const categoria = this.dataset.categoria;
            
            items.forEach(item => {
                if (categoria === 'todas' || item.dataset.categoria === categoria) {
                    item.style.display = 'flex';
                    item.style.animation = 'fadeIn 0.3s ease';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'slideInUp 0.6s ease forwards';
            }
        });
    }, { threshold: 0.1 });
    
    document.querySelectorAll('.noticia-card, .archivo-item').forEach(item => {
        observer.observe(item);
    });
});

const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);
</script>

<?php require_once BASE_PATH . '/views/components/footer.php'; ?>