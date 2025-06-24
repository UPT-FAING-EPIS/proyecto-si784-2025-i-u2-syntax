<?php 
require_once __DIR__ . '/../../config/constants.php'; 
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>

<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container-fluid px-3 px-md-4">
        <a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL ?>/index.php">
            <div class="university-logo">
                <img src="<?= BASE_URL ?>/public/img/ams.png" alt="Logo UPT" class="logo-img">
            </div>
            <span class="brand-text d-none d-sm-inline">AMS-UPT</span>
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="hamburger-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mt-3 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'index' ? 'active' : '' ?>" href="<?= BASE_URL ?>/index.php">
                        <i class="fas fa-home"></i> 
                        <span>Inicio</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'novedades' ? 'active' : '' ?>" href="<?= BASE_URL ?>/index.php?accion=anuncios">
                        <i class="fas fa-bullhorn"></i> 
                        <span>Novedades</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'faq' ? 'active' : '' ?>" href="<?= BASE_URL ?>/index.php?accion=faq">
                        <i class="fas fa-question-circle"></i> 
                        <span>F.A.Q</span>
                    </a>
                </li>
            </ul>
            
            <div class="navbar-user-section mt-3 mt-lg-0">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <div class="dropdown">
                        <button class="btn btn-profile dropdown-toggle d-flex align-items-center" 
                                type="button" 
                                id="profileDropdown" 
                                data-bs-toggle="dropdown" 
                                aria-expanded="false">
                            <div class="user-avatar">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div class="user-info d-none d-lg-block">
                                <span class="user-greeting">Mi Perfil</span>
                            </div>
                        </button>
                        
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <?php if ($_SESSION['rol_id'] === 2): ?>
                                <li><h6 class="dropdown-header">Panel de Estudiante</h6></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/index.php?accion=clases">
                                    <i class="fas fa-chalkboard-teacher"></i>Mis Clases</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/index.php?accion=solicitar">
                                    <i class="fas fa-users"></i>Clases Disponibles</a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php elseif ($_SESSION['rol_id'] === 3): ?>
                                <li><h6 class="dropdown-header">Panel de Docente</h6></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/index.php?accion=clases_asignadas">
                                    <i class="fas fa-chalkboard"></i>Mis Clases</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/index.php?accion=tomar_clase">
                                    <i class="fas fa-user-graduate"></i>Tomar Clases</a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php elseif ($_SESSION['rol_id'] === 4): ?>
                                <li><h6 class="dropdown-header">Panel de Admin</h6></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/index.php?accion=gestion">
                                    <i class="fas fa-users-cog"></i>Gestión de Usuarios</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/index.php?accion=reportes">
                                    <i class="fas fa-chart-bar"></i>Reportes</a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>

                            <?php if ($_SESSION['rol_id'] === 1): ?>
                                <li><h6 class="dropdown-header">Opciones</h6></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/index.php?accion=vincularme">
                                    <i class="fas fa-link"></i>Vincularme a la UPT</a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>

                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/index.php?accion=perfil">
                                <i class="fas fa-user"></i>Mi Perfil</a></li>
                            <li><a class="dropdown-item text-danger" href="#" onclick="confirmarCerrarSesion(event, '<?= BASE_URL ?>/index.php?accion=cerrar')">
                                <i class="fas fa-sign-out-alt"></i>Cerrar sesión</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="/index.php?accion=login" class="btn btn-login">
                        <i class="fas fa-sign-in-alt"></i> 
                        <span class="d-none d-sm-inline">Iniciar sesión</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<div class="navbar-spacer"></div>

<style>
:root {
    --navbar-height: 70px;
    --primary-color: #1e3a5f;
    --secondary-color: #2c5282;
    --accent-color: #3182ce;
    --text-light: rgba(255, 255, 255, 0.9);
    --text-lighter: rgba(255, 255, 255, 0.7);
    --bg-overlay: rgba(255, 255, 255, 0.1);
    --bg-overlay-hover: rgba(255, 255, 255, 0.15);
    --shadow-light: 0 2px 10px rgba(0, 0, 0, 0.1);
    --shadow-medium: 0 4px 20px rgba(0, 0, 0, 0.15);
    --border-radius: 8px;
    --transition: all 0.3s ease;
}

/* Navbar Base */
.navbar-custom {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    padding: 0.75rem 0;
    box-shadow: var(--shadow-light);
    min-height: var(--navbar-height);
    transition: var(--transition);
}

.navbar-spacer {
    height: var(--navbar-height);
}

.navbar-brand {
    font-weight: 700;
    color: white !important;
    transition: var(--transition);
    padding: 0;
}

.university-logo {
    margin-right: 0.75rem;
}

.logo-img {
    height: 40px;
    width: auto;
    transition: var(--transition);
}

.brand-text {
    font-size: 1.1rem;
    font-weight: 600;
    margin-left: 0.5rem;
}

.navbar-toggler {
    padding: 0.5rem;
    border: none !important;
    border-radius: var(--border-radius);
    background: var(--bg-overlay);
    transition: var(--transition);
}

.navbar-toggler:hover {
    background: var(--bg-overlay-hover);
}

.hamburger-icon {
    display: flex;
    flex-direction: column;
    width: 24px;
    height: 18px;
    justify-content: space-between;
}

.hamburger-icon span {
    display: block;
    height: 2px;
    width: 100%;
    background: white;
    border-radius: 1px;
    transition: var(--transition);
}

.navbar-toggler[aria-expanded="true"] .hamburger-icon span:nth-child(1) {
    transform: rotate(45deg) translate(6px, 6px);
}

.navbar-toggler[aria-expanded="true"] .hamburger-icon span:nth-child(2) {
    opacity: 0;
}

.navbar-toggler[aria-expanded="true"] .hamburger-icon span:nth-child(3) {
    transform: rotate(-45deg) translate(6px, -6px);
}

.navbar-nav .nav-item {
    margin: 0 0.25rem;
}

.navbar-nav .nav-link {
    color: var(--text-light) !important;
    padding: 0.75rem 1rem !important;
    border-radius: var(--border-radius);
    transition: var(--transition);
    display: flex;
    align-items: center;
    font-weight: 500;
    position: relative;
    overflow: hidden;
}

.navbar-nav .nav-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transition: left 0.5s;
}

.navbar-nav .nav-link:hover::before {
    left: 100%;
}

.navbar-nav .nav-link:hover {
    color: white !important;
    background: var(--bg-overlay-hover);
    transform: translateY(-2px);
}

.navbar-nav .nav-link.active {
    color: white !important;
    background: var(--bg-overlay);
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
}

.navbar-nav .nav-link i {
    margin-right: 0.5rem;
    width: 18px;
    text-align: center;
    font-size: 0.9rem;
}

.navbar-user-section {
    display: flex;
    align-items: center;
}

.btn-profile {
    background: var(--bg-overlay) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    color: white !important;
    border-radius: 50px !important;
    padding: 0.5rem 1rem !important;
    transition: var(--transition) !important;
    min-width: 120px;
    justify-content: flex-start;
}

.btn-profile:hover {
    background: var(--bg-overlay-hover) !important;
    color: white !important;
    transform: translateY(-2px) !important;
    box-shadow: var(--shadow-light);
}

.user-avatar {
    margin-right: 0.75rem;
}

.user-avatar i {
    font-size: 1.5rem;
}

.user-info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    line-height: 1.2;
}

.user-greeting {
    font-size: 0.9rem;
    font-weight: 600;
}

.user-role {
    font-size: 0.75rem;
    opacity: 0.8;
}

.btn-login {
    background: var(--accent-color) !important;
    border: none !important;
    color: white !important;
    padding: 0.6rem 1.5rem !important;
    border-radius: 25px !important;
    font-weight: 600 !important;
    transition: var(--transition) !important;
    text-decoration: none;
}

.btn-login:hover {
    background: var(--secondary-color) !important;
    color: white !important;
    transform: translateY(-2px) !important;
    box-shadow: var(--shadow-light);
}

.btn-login i {
    margin-right: 0.5rem;
}

/* Dropdown */
.dropdown-menu {
    border: none !important;
    box-shadow: var(--shadow-medium) !important;
    border-radius: var(--border-radius) !important;
    margin-top: 0.75rem !important;
    min-width: 280px !important;
    padding: 0.5rem 0 !important;
    background: white;
}

.dropdown-header {
    color: var(--primary-color) !important;
    font-weight: 600 !important;
    font-size: 0.8rem !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    padding: 0.75rem 1rem 0.5rem !important;
}

.dropdown-item {
    padding: 0.75rem 1rem !important;
    transition: var(--transition) !important;
    color: #333 !important;
    display: flex !important;
    align-items: center !important;
}

.dropdown-item:hover {
    background: #f8f9fa !important;
    padding-left: 1.5rem !important;
    color: var(--primary-color) !important;
}

.dropdown-item.text-danger:hover {
    background: #fee !important;
    color: #dc3545 !important;
}

.dropdown-item i {
    width: 20px !important;
    text-align: center !important;
    margin-right: 0.75rem !important;
    font-size: 0.9rem !important;
}

.dropdown-divider {
    margin: 0.5rem 0 !important;
}

/* Mobile Responsive */
@media (max-width: 991.98px) {
    .navbar-custom {
        padding: 0.5rem 0;
    }
    
    .navbar-collapse {
        background: rgba(0, 0, 0, 0.1);
        border-radius: var(--border-radius);
        margin-top: 1rem;
        padding: 1rem;
        backdrop-filter: blur(10px);
    }
    
    .navbar-nav {
        flex-direction: column;
        width: 100%;
    }
    
    .navbar-nav .nav-item {
        margin: 0.25rem 0;
        width: 100%;
    }
    
    .navbar-nav .nav-link {
        padding: 1rem !important;
        justify-content: flex-start;
        width: 100%;
    }
    
    .btn-profile {
        width: 100% !important;
        justify-content: flex-start !important;
        margin-top: 1rem;
    }
    
    .btn-login {
        width: 100% !important;
        justify-content: center !important;
        margin-top: 1rem;
        padding: 1rem !important;
    }
    
    .dropdown-menu {
        position: static !important;
        float: none !important;
        width: 100% !important;
        margin-top: 0.5rem !important;
        box-shadow: none !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px);
    }
}

@media (max-width: 767.98px) {
    .logo-img {
        height: 35px;
    }
    
    .brand-text {
        font-size: 1rem;
    }
    
    .navbar-nav .nav-link i {
        margin-right: 0.75rem;
        width: 20px;
    }
    
    .user-avatar i {
        font-size: 1.25rem;
    }
}

@media (max-width: 575.98px) {
    .navbar-custom {
        padding: 0.5rem 0;
    }
    
    .logo-img {
        height: 30px;
    }
    
    .brand-text {
        display: none !important;
    }
    
    .container-fluid {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
}

/* Animaciones adicionales */
@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.dropdown-menu.show {
    animation: slideInDown 0.3s ease;
}

/* Accesibilidad */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* Estado de carga */
.navbar-custom.loading {
    opacity: 0.7;
}

/* Hover states mejorados */
.navbar-nav .nav-link:focus {
    outline: 2px solid rgba(255, 255, 255, 0.5);
    outline-offset: 2px;
}

.btn-profile:focus,
.btn-login:focus {
    outline: 2px solid rgba(255, 255, 255, 0.5);
    outline-offset: 2px;
}
</style>

<script>
function confirmarCerrarSesion(event, url) {
    event.preventDefault();
    if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
        window.location.href = url;
    }
}

// Mejorar la experiencia del navbar en móvil
document.addEventListener('DOMContentLoaded', function() {
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    // Cerrar el menú al hacer clic en un enlace (móvil)
    document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 992) {
                const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
                    toggle: false
                });
                bsCollapse.hide();
            }
        });
    });
    
    let lastScrollTop = 0;
    const navbar = document.querySelector('.navbar-custom');
    
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > lastScrollTop && scrollTop > 100) {
            navbar.style.transform = 'translateY(-50%)';
        } else {
            navbar.style.transform = 'translateY(0)';
        }
        
        lastScrollTop = scrollTop;
    });
});
</script>