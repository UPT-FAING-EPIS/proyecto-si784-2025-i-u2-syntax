<footer class="footer mt-auto py-3 bg-light border-top fixed-bottom">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <span class="text-muted">
                    <i class="fas fa-graduation-cap me-2"></i>
                    &copy; <?php echo date("Y"); ?> Sistema de Mentoría Académica
                </span>
            </div>
            <div class="col-md-6 text-md-end">
                <span class="text-muted">
                    Todos los derechos reservados
                    <i class="fas fa-heart text-danger ms-1"></i>
                </span>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
window.AppConfig = {
    baseUrl: '<?php echo defined('BASE_URL') ? BASE_URL : ''; ?>',
    debug: <?php echo (defined('DEBUG') && DEBUG) ? 'true' : 'false'; ?>,
    currentPage: '<?php echo isset($_SERVER['PHP_SELF']) ? basename($_SERVER['PHP_SELF']) : ''; ?>',
    timestamp: <?php echo time(); ?>
};

if (!window.AppConfig.baseUrl) {
    window.AppConfig.baseUrl = window.location.origin;
}

function loadScript(src, callback) {
    if (!src || src.trim() === '') return;
    const script = document.createElement('script');
    script.src = src;
    script.async = true;
    script.onload = function() {
        if (typeof callback === 'function') {
            try { callback(); } catch (e) {}
        }
    };
    script.onerror = function() {
        if (src.includes(window.AppConfig.baseUrl)) {
            const relativePath = src.replace(window.AppConfig.baseUrl, '.');
            loadScript(relativePath, callback);
        }
    };
    document.head.appendChild(script);
}

function waitForElement(selector, callback, timeout = 5000) {
    const startTime = Date.now();
    function check() {
        const element = document.querySelector(selector);
        if (element) {
            callback(element);
        } else if (Date.now() - startTime < timeout) {
            setTimeout(check, 100);
        }
    }
    check();
}

document.addEventListener('DOMContentLoaded', function() {
    initializeBasicComponents();
    const scriptPath = window.AppConfig.baseUrl + '/public/js/script.js';
    loadScript(scriptPath, function() {
        if (typeof window.initializeApp === 'function') {
            window.initializeApp();
        }
        initializeBootstrapComponents();
    });
});

function initializeBasicComponents() {
    try {
        const tooltipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        if (tooltipElements.length > 0 && typeof bootstrap !== 'undefined') {
            [...tooltipElements].forEach(el => new bootstrap.Tooltip(el));
        }

        const popoverElements = document.querySelectorAll('[data-bs-toggle="popover"]');
        if (popoverElements.length > 0 && typeof bootstrap !== 'undefined') {
            [...popoverElements].forEach(el => new bootstrap.Popover(el));
        }

        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            setTimeout(() => {
                if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    if (bsAlert) bsAlert.close();
                }
            }, 5000);
        });

        adjustBodyPadding();
    } catch (error) {}
}

function initializeBootstrapComponents() {
    try {
        const forms = document.querySelectorAll('.needs-validation');
        forms.forEach(form => {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });

        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.addEventListener('show.bs.modal', function() {});
        });
    } catch (error) {}
}

function adjustBodyPadding() {
    const footer = document.querySelector('.footer');
    if (footer) {
        const footerHeight = footer.offsetHeight;
        document.body.style.paddingBottom = (footerHeight + 20) + 'px';
    }
}

window.addEventListener('resize', adjustBodyPadding);

function showNotification(message, type = 'info', duration = 4000) {
    try {
        const alertContainer = document.createElement('div');
        alertContainer.className = 'position-fixed top-0 end-0 p-3';
        alertContainer.style.zIndex = '9999';

        const alertId = 'alert-' + Date.now();
        const iconMap = {
            'success': 'check-circle',
            'danger': 'exclamation-triangle',
            'warning': 'exclamation-circle',
            'info': 'info-circle'
        };

        alertContainer.innerHTML = `
            <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="fas fa-${iconMap[type] || 'info-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        document.body.appendChild(alertContainer);

        setTimeout(() => {
            const alert = document.getElementById(alertId);
            if (alert && typeof bootstrap !== 'undefined') {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                if (bsAlert) bsAlert.close();
            }
            setTimeout(() => {
                if (alertContainer.parentNode) {
                    alertContainer.remove();
                }
            }, 500);
        }, duration);
    } catch (error) {
        alert(message);
    }
}

window.showNotification = showNotification;
window.loadScript = loadScript;
window.waitForElement = waitForElement;

window.addEventListener('error', function(e) {
    if (window.AppConfig.debug) {
        showNotification('Se produjo un error en la aplicación', 'danger');
    }
});

window.addEventListener('unhandledrejection', function(e) {});
</script>

<?php
$currentAction = isset($_GET['accion']) ? $_GET['accion'] : '';
$isClassesPage = ($currentAction === 'mis_clases' || strpos($_SERVER['REQUEST_URI'], 'mis_clases') !== false);
if ($isClassesPage):
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    waitForElement('#modalCalificar', function(modal) {
        initializeRatingModal();
    });
});

function initializeRatingModal() {
    const modal = document.querySelector('#modalCalificar');
    const stars = document.querySelectorAll('.star');
    const puntuacionInput = document.querySelector('#puntuacion_input');
    const btnEnviar = document.querySelector('#btn_enviar_calificacion');

    if (!modal) return;

    if (stars.length > 0 && puntuacionInput) {
        stars.forEach((star, index) => {
            star.addEventListener('mouseenter', () => highlightStars(index + 1));
            star.addEventListener('mouseleave', () => {
                const currentRating = parseInt(puntuacionInput.value) || 0;
                highlightStars(currentRating);
            });
            star.addEventListener('click', () => selectRating(parseInt(star.dataset.value)));
        });
    }

    function highlightStars(rating) {
        stars.forEach((star, index) => {
            star.classList.toggle('active', index < rating);
        });
    }

    function selectRating(rating) {
        if (puntuacionInput) {
            puntuacionInput.value = rating;
            highlightStars(rating);
            if (btnEnviar) btnEnviar.disabled = false;
        }
    }

    const botonesCalificar = document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target="#modalCalificar"]');
    botonesCalificar.forEach(boton => {
        boton.addEventListener('click', function() {
            const claseId = this.dataset.claseId;
            const claseNombre = this.dataset.claseNombre;

            const modalClaseId = document.querySelector('#modal_clase_id');
            const modalClaseNombre = document.querySelector('#modal_clase_nombre');

            if (modalClaseId && claseId) modalClaseId.value = claseId;
            if (modalClaseNombre && claseNombre) modalClaseNombre.textContent = claseNombre;

            resetModalForm();
        });
    });

    function resetModalForm() {
        if (puntuacionInput) puntuacionInput.value = '';
        stars.forEach(star => star.classList.remove('active'));

        const comentario = document.querySelector('#comentario');
        if (comentario) comentario.value = '';

        if (btnEnviar) btnEnviar.disabled = true;
    }

    modal.addEventListener('hidden.bs.modal', resetModalForm);

    const form = modal.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const rating = parseInt(puntuacionInput.value);
            if (!rating || rating < 1 || rating > 5) {
                e.preventDefault();
                showNotification('Por favor, selecciona una calificación del 1 al 5', 'warning');
                return false;
            }
        });
    }
}
</script>
<?php endif; ?>

<style>
.footer {
    box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
    backdrop-filter: blur(10px);
    background-color: rgba(248, 249, 250, 0.95) !important;
}
.footer .text-muted {
    font-size: 0.9rem;
    font-weight: 500;
}
@media (max-width: 768px) {
    .footer .col-md-6:last-child {
        text-align: center !important;
        margin-top: 0.5rem;
    }
    .footer {
        padding: 10px 0 !important;
    }
}
.text-danger {
    animation: heartbeat 1.5s ease-in-out infinite;
}
@keyframes heartbeat {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}
body {
    margin-bottom: 80px;
}
</style>

</body>
</html>
