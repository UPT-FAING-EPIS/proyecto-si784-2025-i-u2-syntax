window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        window.location.reload();
    }
});

window.addEventListener('load', function() {
    document.querySelectorAll('.loading-overlay').forEach(overlay => {
        overlay.remove();
    });
});
function confirmarCerrarSesion(e, logoutUrl) {
    e.preventDefault();

    if (confirm("¿Estás seguro de que deseas cerrar sesión?")) {
        const overlay = document.createElement("div");
        overlay.style.position = "fixed";
        overlay.style.top = 0;
        overlay.style.left = 0;
        overlay.style.width = "100vw";
        overlay.style.height = "100vh";
        overlay.style.backgroundColor = "rgba(0,0,0,0.6)";
        overlay.style.display = "flex";
        overlay.style.justifyContent = "center";
        overlay.style.alignItems = "center";
        overlay.style.zIndex = 9999;

        const spinner = document.createElement("div");
        spinner.innerHTML = `
            <div style="color: white; text-align: center;">
                <div class="loader"></div>
                <p style="margin-top: 10px;">Cerrando sesión...</p>
            </div>
        `;

        overlay.appendChild(spinner);
        document.body.appendChild(overlay);

        setTimeout(() => {
            window.location.href = logoutUrl;
        }, 2000);
    }
}
let TOKEN_API_DNI = "";
let codigoEnviado = "";
const BASE_URL = window.location.origin;

fetch("config.json")
    .then(response => {
        if (!response.ok) throw new Error("No se pudo cargar config.json");
        return response.json();
    })
    .then(config => {
        TOKEN_API_DNI = config.apitoken;
    })
    .catch(err => {
        console.error("Error cargando config.json:", err);
    });

const form = document.getElementById("formRegistro");
const btnRegistrar = document.getElementById("btnRegistrar");

if (form) {
    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const correo = document.getElementById("email").value;
        if (!correo) {
            alert("El correo es obligatorio.");
            return;
        }

        mostrarCarga(true);

        const formData = new FormData();
        formData.append("email", correo);
        console.log("📤 Enviando solicitud de código...");

        fetch(`${BASE_URL}/auth/notify.php`, {
            method: "POST",
            body: formData
        })
        .then(res => {
            if (!res.ok) throw new Error(`Error ${res.status}: ${res.statusText}`);
            return res.json();
        })
        .then(data => {
            if (data.success && data.codigo) {
                codigoEnviado = data.codigo;
                document.getElementById("modalCodigo").style.display = "block";
            } else if (!data.success) {
                alert("❌ " + (data.message || "No se pudo enviar el código."));
            } else {
                alert("❌ El servidor no devolvió el código.");
            }
        })
        .catch(err => {
            console.error("❌ Error al contactar al servidor:", err);
            alert("❌ No se pudo contactar con el servidor. Revisa la consola.");
        })
        .finally(() => mostrarCarga(false));
    });
}

function verificarDNI(input) {
    input.value = input.value.replace(/\D/g, '');

    if (input.value.length === 8) {
        validarDNI(input.value);
    } else {
        document.getElementById("nombre").value = "";
        document.getElementById("apellido").value = "";
    }
}

function validarDNI(dni) {
    if (!/^\d{8}$/.test(dni)) {
        mostrarError("DNI debe contener exactamente 8 dígitos");
        return;
    }

    mostrarCarga(true);
    
    if (window.dniController) {
        window.dniController.abort();
    }
    window.dniController = new AbortController();

    fetch(`index.php?accion=consulta_dni&dni=${dni}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        signal: window.dniController.signal,
        timeout: 10000
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('La respuesta no es JSON válido');
        }
        
        return response.json();
    })
    .then(data => {
        if (data.success) {
            rellenarCamposDNI(data);
        } else {
            limpiarDNI();
        }
    })
    .catch(error => {
        if (error.name === 'AbortError') {
            console.log('Petición cancelada');
            return;
        }
        
        console.error('Error consultando DNI:', error);
    })
    .finally(() => {
        mostrarCarga(false);
        window.dniController = null;
    });
}
function mostrarError(mensaje) {
    let errorElement = document.getElementById('dni-error');
    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.id = 'dni-error';
        errorElement.className = 'error-message';
        
        const dniField = document.getElementById('dni');
        if (dniField && dniField.parentNode) {
            dniField.parentNode.insertBefore(errorElement, dniField.nextSibling);
        }
    }
    
    errorElement.textContent = mensaje;
    errorElement.style.display = 'block';
    setTimeout(() => ocultarError(), 5000);
}

function ocultarError() {
    const errorElement = document.getElementById('dni-error');
    if (errorElement) {
        errorElement.style.display = 'none';
    }
}
function rellenarCamposDNI(data) {
    const nombreField = document.getElementById("nombre");
    const apellidoField = document.getElementById("apellido");
    const emailField = document.getElementById("email");
    
    if (nombreField) {
        nombreField.value = data.nombres || '';
    }
    
    if (apellidoField) {
        const apellidos = [
            data.apellidoPaterno || '',
            data.apellidoMaterno || ''
        ].filter(Boolean).join(' ');
        apellidoField.value = apellidos;
    }
    
    if (emailField) {
        emailField.focus();
    }
}
function limpiarDNI(msg) {
    alert(msg);
    document.getElementById("dni").value = "";
    document.getElementById("nombre").value = "";
    document.getElementById("apellido").value = "";
    document.getElementById("dni").focus();
}

function validarCorreo(input) {
    const correo = input.value;
    const regex = /^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
    document.getElementById("correoError").textContent = regex.test(correo) ? "" : "Correo no válido";
}

function validarPassword(pass) {
    const fuerte = /(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#\$%\^&\*])/;
    document.getElementById("passError").textContent = fuerte.test(pass)
        ? ""
        : "Contraseña insegura. Usa mayúsculas, números y símbolos.";
    compararPassword();
}

function compararPassword() {
    const pass = document.getElementById("password").value;
    const confirm = document.getElementById("confirmar").value;
    const valido = pass && confirm && pass === confirm;
    document.getElementById("passError").textContent = !valido ? "Las contraseñas no coinciden." : "";
    document.getElementById("btnRegistrar").disabled = !valido;
}

function mostrarCarga(estado) {
    if (estado) {
        const carga = document.createElement("div");
        carga.id = "cargandoOverlay";
        carga.style = "position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.6);display:flex;align-items:center;justify-content:center;z-index:9999;";
        carga.innerHTML = '<div style="color:white;">Validando...<div class="loader"></div></div>';
        document.body.appendChild(carga);
    } else {
        const carga = document.getElementById("cargandoOverlay");
        if (carga) carga.remove();
    }
}

function verificarCodigo() {
    const input = document.getElementById("codigoVerificacion").value;

    if (input !== codigoEnviado) {
        alert("⚠️ Código incorrecto.");
        return;
    }

    const form = document.getElementById("formRegistro");
    const formData = new FormData(form);

    console.log("📤 Enviando datos del formulario:");
    for (let pair of formData.entries()) {
        console.log(`🔹 ${pair[0]}: ${pair[1]}`);
    }

    mostrarCarga(true);
    fetch(`${BASE_URL}/index.php?accion=procesar_registro`, {
        method: "POST",
        body: formData
    })
    .then(async res => {
        const text = await res.text();
        try {
            return JSON.parse(text);
        } catch (err) {
            throw new Error("Respuesta inválida del servidor: " + text);
        }
    })
    .then(data => {
        if (data.success) {
            alert("✅ Registro completado.");
            window.location.href = `${BASE_URL}/index.php`;
        } else {
            alert("❌ " + data.message);
        }
    })
    .catch((err) => {
        console.error("❌ Error al registrar:", err);
        alert("❌ Error al registrar: " + err.message);
    })
    .finally(() => mostrarCarga(false));
}
function cerrarModal() {
    const modal = document.getElementById('modalCodigo');
    modal.classList.remove('show');
}
document.addEventListener('DOMContentLoaded', function() {
    const header = document.querySelector('.header');
    const navLinks = document.querySelectorAll('.nav-link');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
    
    const currentUrl = window.location.href;
    navLinks.forEach(link => {
        if (currentUrl.includes(link.href) && link.href !== window.location.origin + '/') {
            link.setAttribute('data-active', 'true');
        }
    });
    
    // Cerrar menú móvil al hacer clic en un enlace
    const mobileLinks = document.querySelectorAll('.mobile-nav-link');
    const mobileMenu = document.getElementById('mobileMenu');
    const toggleButton = document.querySelector('.navbar-toggler');
    
    mobileLinks.forEach(link => {
        link.addEventListener('click', function() {
            mobileMenu.classList.remove('show');
            toggleButton.setAttribute('aria-expanded', 'false');
        });
    });
    
    // Efecto parallax sutil en el header
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const rate = scrolled * -0.5;
        header.style.transform = `translateY(${rate}px)`;
    });
});


//ADMIN - Script Completo Unificado
const dashboardConfig = {
    baseUrl: window.BASE_URL || window.location.origin,
    refreshInterval: 30000,
    animationDelay: 100
};

// ===== NAVEGACIÓN PRINCIPAL =====
function navegarA(accion) {
    switch(accion) {
        case 'anadir_alumnos':
            openAddStudentModal();
            break;
        case 'modificar_usuarios':
            openManageUsersModal();
            break;
        case 'modificar_clases':
        case 'reportes':
            const urls = {
                'modificar_clases': `${dashboardConfig.baseUrl}/index.php?accion=modificar_clases`,
                'reportes': `${dashboardConfig.baseUrl}/index.php?accion=reportes`
            };
            showLoadingState();
            window.location.href = urls[accion];
            break;
        default:
            console.log('Acción no definida:', accion);
    }
}

// ===== MODAL PARA AÑADIR ESTUDIANTES =====
function openAddStudentModal() {
    const modal = document.createElement('div');
    modal.className = 'modal-overlay';
    modal.innerHTML = `
        <div class="modal-content modal-large">
            <div class="modal-header">
                <h3><i class="fas fa-user-plus"></i> Añadir Nuevo Estudiante</h3>
                <button class="modal-close" onclick="this.closest('.modal-overlay').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addStudentForm" class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="codigo_estudiante">Código de Estudiante *</label>
                        <input type="text" id="codigo_estudiante" name="codigo_estudiante" 
                               placeholder="Ej: 2022073898" maxlength="12" required>
                        <small class="form-help">Código único del estudiante</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="nombres">Nombres *</label>
                        <input type="text" id="nombres" name="nombres" 
                               placeholder="Nombres completos" maxlength="100" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="apellidos">Apellidos *</label>
                        <input type="text" id="apellidos" name="apellidos" 
                               placeholder="Apellidos completos" maxlength="100" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email_institucional">Email Institucional</label>
                        <input type="email" id="email_institucional" name="email_institucional" 
                               placeholder="ejemplo@virtual.upt.pe" maxlength="150">
                    </div>
                    
                    <div class="form-group">
                        <label for="carrera">Carrera</label>
                        <select id="carrera" name="carrera">
                            <option value="">Seleccionar carrera</option>
                            <option value="Ingeniería de Sistemas">Ingeniería de Sistemas</option>
                            <option value="Ingeniería Civil">Ingeniería Civil</option>
                            <option value="Ingeniería Industrial">Ingeniería Industrial</option>
                            <option value="Ingeniería Electrónica">Ingeniería Electrónica</option>
                            <option value="Ingeniería Mecánica">Ingeniería Mecánica</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="semestre">Semestre</label>
                        <select id="semestre" name="semestre">
                            <option value="">Seleccionar semestre</option>
                            <option value="1">1° Semestre</option>
                            <option value="2">2° Semestre</option>
                            <option value="3">3° Semestre</option>
                            <option value="4">4° Semestre</option>
                            <option value="5">5° Semestre</option>
                            <option value="6">6° Semestre</option>
                            <option value="7">7° Semestre</option>
                            <option value="8">8° Semestre</option>
                            <option value="9">9° Semestre</option>
                            <option value="10">10° Semestre</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <select id="estado" name="estado">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                            <option value="graduado">Graduado</option>
                            <option value="retirado">Retirado</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="fecha_ingreso">Fecha de Ingreso</label>
                        <input type="date" id="fecha_ingreso" name="fecha_ingreso">
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="this.closest('.modal-overlay').remove()">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary" onclick="saveStudent()">
                    <i class="fas fa-save"></i> Guardar Estudiante
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    setTimeout(() => modal.classList.add('show'), 10);
    
    // Auto-generar email institucional basado en código
    document.getElementById('codigo_estudiante').addEventListener('input', function() {
        const codigo = this.value;
        const emailField = document.getElementById('email_institucional');
        if (codigo && codigo.length >= 8) {
            emailField.value = `${codigo}@virtual.upt.pe`;
        }
    });
}

function saveStudent() {
    const form = document.getElementById('addStudentForm');
    const formData = new FormData(form);
    
    // Validar campos requeridos
    const requiredFields = ['codigo_estudiante', 'nombres', 'apellidos'];
    let isValid = true;
    
    requiredFields.forEach(field => {
        const input = document.getElementById(field);
        if (!input.value.trim()) {
            input.classList.add('error');
            isValid = false;
        } else {
            input.classList.remove('error');
        }
    });
    
    if (!isValid) {
        showNotification('Por favor completa todos los campos requeridos', 'warning');
        return;
    }
    
    // Mostrar loading
    const saveBtn = document.querySelector('.btn-primary');
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    saveBtn.disabled = true;
    
    // Enviar datos
    fetch(`${dashboardConfig.baseUrl}/index.php?accion=guardar_estudiante`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Estudiante guardado exitosamente', 'success');
            document.querySelector('.modal-overlay').remove();
            refreshDashboard();
        } else {
            showNotification(data.message || 'Error al guardar estudiante', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error de conexión al guardar estudiante', 'error');
    })
    .finally(() => {
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
    });
}

// ===== MODAL PARA GESTIONAR USUARIOS =====
function openManageUsersModal() {
    const modal = document.createElement('div');
    modal.className = 'modal-overlay';
    modal.innerHTML = `
        <div class="modal-content modal-xlarge">
            <div class="modal-header">
                <h3><i class="fas fa-users-cog"></i> Gestionar Usuarios</h3>
                <button class="modal-close" onclick="this.closest('.modal-overlay').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="users-toolbar">
                    <div class="search-filters">
                        <div class="filter-group">
                            <label>Buscar por:</label>
                            <select id="searchType">
                                <option value="all">Todos los usuarios</option>
                                <option value="dni">DNI</option>
                                <option value="codigo">Código de Estudiante</option>
                                <option value="email">Email</option>
                                <option value="nombre">Nombre</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <input type="text" id="searchValue" placeholder="Ingresa el término de búsqueda..." />
                        </div>
                        <div class="filter-group">
                            <button class="btn btn-primary" onclick="searchUsers()">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                            <button class="btn btn-secondary" onclick="loadAllUsers()">
                                <i class="fas fa-refresh"></i> Todos
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="users-loading" id="usersLoading" style="display: none;">
                    <div class="spinner-small"></div>
                    <span>Cargando usuarios...</span>
                </div>
                
                <div class="users-table-container">
                    <table class="users-table" id="usersTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>DNI</th>
                                <th>Nombre Completo</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Código Est.</th>
                                <th>Estado</th>
                                <th>Fecha Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            <tr>
                                <td colspan="9" style="text-align: center; padding: 2rem;">
                                    <i class="fas fa-users fa-3x" style="color: #ddd; margin-bottom: 1rem;"></i>
                                    <p>Haz clic en "Todos" para cargar los usuarios</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    setTimeout(() => modal.classList.add('show'), 10);
    
    // Auto-buscar al escribir
    document.getElementById('searchValue').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            searchUsers();
        }
    });
    
    // Cargar usuarios automáticamente
    setTimeout(() => {
        loadAllUsers();
    }, 500);
}

function loadAllUsers() {
    showUsersLoading(true);
    
    fetch(`${dashboardConfig.baseUrl}/index.php?accion=obtener_usuarios`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayUsers(data.usuarios);
        } else {
            showNotification('Error al cargar usuarios', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error de conexión al cargar usuarios', 'error');
    })
    .finally(() => {
        showUsersLoading(false);
    });
}

function searchUsers() {
    const searchType = document.getElementById('searchType').value;
    const searchValue = document.getElementById('searchValue').value.trim();
    
    if (searchType === 'all') {
        loadAllUsers();
        return;
    }
    
    if (!searchValue) {
        showNotification('Ingresa un término de búsqueda', 'warning');
        return;
    }
    
    showUsersLoading(true);
    
    const params = new URLSearchParams({
        accion: 'buscar_usuarios',
        tipo: searchType,
        valor: searchValue
    });
    
    fetch(`${dashboardConfig.baseUrl}/index.php?${params}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayUsers(data.usuarios);
        } else {
            showNotification(data.message || 'No se encontraron usuarios', 'warning');
            displayUsers([]);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error de conexión en la búsqueda', 'error');
    })
    .finally(() => {
        showUsersLoading(false);
    });
}

function displayUsers(usuarios) {
    const tbody = document.getElementById('usersTableBody');
    
    if (!usuarios || usuarios.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" style="text-align: center; padding: 2rem;">
                    <i class="fas fa-search fa-2x" style="color: #ddd; margin-bottom: 1rem;"></i>
                    <p>No se encontraron usuarios</p>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = usuarios.map(usuario => `
        <tr>
            <td>${usuario.ID_USUARIO}</td>
            <td>${usuario.DNI || '-'}</td>
            <td>${usuario.NOMBRE} ${usuario.APELLIDO}</td>
            <td>${usuario.EMAIL}</td>
            <td>
                <span class="role-badge role-${usuario.ROL?.toLowerCase() || 'visitante'}">
                    ${usuario.ROL || 'Visitante'}
                </span>
            </td>
            <td>${usuario.CODIGO_ESTUDIANTE || '-'}</td>
            <td>
                <span class="status-badge status-${usuario.ESTADO_ESTUDIANTE || 'activo'}">
                    ${usuario.ESTADO_ESTUDIANTE || 'N/A'}
                </span>
            </td>
            <td>${formatDate(usuario.FECHA_REG)}</td>
            <td>
                <div class="action-buttons">
                    <button class="btn-action btn-edit" onclick="editUser(${usuario.ID_USUARIO})" 
                            data-tooltip="Editar usuario">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-action btn-view" onclick="viewUser(${usuario.ID_USUARIO})" 
                            data-tooltip="Ver detalles">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function showUsersLoading(show) {
    const loading = document.getElementById('usersLoading');
    if (loading) {
        loading.style.display = show ? 'flex' : 'none';
    }
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('es-PE', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    });
}

function editUser(userId) {
    showNotification(`Editando usuario ID: ${userId}`, 'info');
    // Implementar modal de edición
}

function viewUser(userId) {
    showNotification(`Viendo detalles del usuario ID: ${userId}`, 'info');
    // Implementar modal de vista detallada
}

// ===== FUNCIONES DE ANIMACIÓN Y CONTADORES =====
function animateCounter(element) {
    const target = parseInt(element.dataset.target);
    const duration = 2000;
    const step = target / (duration / 16);
    let current = 0;
    
    element.classList.add('loading');
    
    const timer = setInterval(() => {
        current += step;
        if (current >= target) {
            current = target;
            clearInterval(timer);
            element.classList.remove('loading');
        }
        element.textContent = Math.floor(current).toLocaleString();
    }, 16);
}

// ===== FUNCIONES DE NAVEGACIÓN RÁPIDA =====
function buscarUsuario() {
    const modal = createSearchModal();
    document.body.appendChild(modal);
    setTimeout(() => modal.classList.add('show'), 10);
}

function crearSesion() {
    showNotification('Redirigiendo a crear nueva sesión...', 'info');
    setTimeout(() => {
        window.location.href = `${dashboardConfig.baseUrl}/index.php?accion=nueva_sesion`;
    }, 1000);
}

function verPendientes() {
    showNotification('Cargando solicitudes pendientes...', 'info');
    setTimeout(() => {
        window.location.href = `${dashboardConfig.baseUrl}/index.php?accion=pendientes`;
    }, 1000);
}

function generarReporte() {
    showNotification('Preparando generador de reportes...', 'info');
    setTimeout(() => {
        window.location.href = `${dashboardConfig.baseUrl}/index.php?accion=generar_reporte`;
    }, 1000);
}

function verTodasActividades() {
    window.location.href = `${dashboardConfig.baseUrl}/index.php?accion=actividades`;
}

function refreshDashboard() {
    const button = document.querySelector('.btn-header .fa-sync-alt');
    if (button) {
        button.style.animation = 'spin 1s linear infinite';
    }
    
    showNotification('Actualizando datos del dashboard...', 'info');
    setTimeout(() => {
        document.querySelectorAll('.metric-value[data-target]').forEach(counter => {
            counter.textContent = '0';
            animateCounter(counter);
        });
        
        showNotification('Dashboard actualizado correctamente', 'success');
        
        if (button) {
            button.style.animation = '';
        }
    }, 1500);
}

function openQuickActions() {
    const menu = createQuickActionsMenu();
    document.body.appendChild(menu);
    setTimeout(() => menu.classList.add('show'), 10);
}

// ===== CREACIÓN DE MODALES Y MENÚS =====
function createSearchModal() {
    const modal = document.createElement('div');
    modal.className = 'modal-overlay';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h3>Buscar Usuario</h3>
                <button class="modal-close" onclick="this.closest('.modal-overlay').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="search-input">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Buscar por nombre, DNI o código..." id="searchInput">
                </div>
                <div class="search-results">
                    <p>Escribe para buscar usuarios...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="performSearch()">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </div>
        </div>
    `;
    
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.remove();
        }
    });
    
    modal.querySelector('#searchInput').addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            performSearch();
        }
    });
    
    return modal;
}

function performSearch() {
    const input = document.querySelector('#searchInput');
    const term = input?.value.trim();
    
    if (term) {
        showNotification(`Buscando: ${term}...`, 'info');
        window.location.href = `${dashboardConfig.baseUrl}/index.php?accion=buscar_usuario&q=${encodeURIComponent(term)}`;
    } else {
        showNotification('Por favor ingresa un término de búsqueda', 'warning');
    }
}

function createQuickActionsMenu() {
    const menu = document.createElement('div');
    menu.className = 'quick-actions-menu';
    menu.innerHTML = `
        <div class="menu-content">
            <div class="menu-header">
                <h3>Acciones Rápidas</h3>
                <button class="menu-close" onclick="this.closest('.quick-actions-menu').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="menu-grid">
                <button class="menu-item" onclick="navegarA('anadir_alumnos'); this.closest('.quick-actions-menu').remove();">
                    <i class="fas fa-user-plus"></i>
                    <span>Añadir Estudiante</span>
                </button>
                <button class="menu-item" onclick="navegarA('modificar_usuarios'); this.closest('.quick-actions-menu').remove();">
                    <i class="fas fa-users-cog"></i>
                    <span>Gestionar Usuarios</span>
                </button>
                <button class="menu-item" onclick="navegarA('modificar_clases'); this.closest('.quick-actions-menu').remove();">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Programar Clase</span>
                </button>
                <button class="menu-item" onclick="navegarA('reportes'); this.closest('.quick-actions-menu').remove();">
                    <i class="fas fa-chart-bar"></i>
                    <span>Ver Reportes</span>
                </button>
                <button class="menu-item" onclick="buscarUsuario(); this.closest('.quick-actions-menu').remove();">
                    <i class="fas fa-search"></i>
                    <span>Buscar Usuario</span>
                </button>
                <button class="menu-item" onclick="refreshDashboard(); this.closest('.quick-actions-menu').remove();">
                    <i class="fas fa-sync-alt"></i>
                    <span>Actualizar Dashboard</span>
                </button>
            </div>
        </div>
    `;
    
    menu.addEventListener('click', (e) => {
        if (e.target === menu) {
            menu.remove();
        }
    });
    
    return menu;
}

// ===== FUNCIONES DE ESTADO Y NOTIFICACIONES =====
function showLoadingState() {
    const existingOverlay = document.querySelector('.loading-overlay');
    if (existingOverlay) {
        existingOverlay.remove();
    }
    
    const loader = document.createElement('div');
    loader.className = 'loading-overlay';
    loader.innerHTML = `
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p>Cargando...</p>
        </div>
    `;
    document.body.appendChild(loader);
    
    setTimeout(() => {
        if (loader && loader.parentNode) {
            loader.remove();
        }
    }, 5000);
}

function showNotification(message, type = 'info', duration = 4000) {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    const icons = {
        'success': 'check-circle',
        'error': 'exclamation-triangle',
        'warning': 'exclamation-circle',
        'info': 'info-circle'
    };
    
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${icons[type] || 'info-circle'}"></i>
            <span>${message}</span>
            <button class="notification-close" onclick="this.closest('.notification').remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    setTimeout(() => notification.classList.add('show'), 100);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, duration);
}

function handleConnectionError() {
    showNotification('Error de conexión. Verificando estado del servidor...', 'error', 6000);
    
    setTimeout(() => {
        fetch(dashboardConfig.baseUrl + '/ping.php')
            .then(response => {
                if (response.ok) {
                    showNotification('Conexión restaurada', 'success');
                } else {
                    showNotification('Servidor no disponible. Intenta más tarde.', 'error');
                }
            })
            .catch(() => {
                showNotification('Sin conexión a internet', 'error');
            });
    }, 5000);
}

// ===== FUNCIONES PARA MINI GRÁFICOS =====
function createRealMiniCharts() {
    const charts = document.querySelectorAll('.mini-chart');
    
    charts.forEach(chart => {
        const chartType = chart.getAttribute('data-chart');
        
        const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        svg.setAttribute('width', '100');
        svg.setAttribute('height', '40');
        svg.setAttribute('viewBox', '0 0 100 40');
        svg.style.opacity = '0.7';
        
        const points = generateChartData(chartType);
        
        const polyline = document.createElementNS('http://www.w3.org/2000/svg', 'polyline');
        polyline.setAttribute('points', points.join(' '));
        polyline.setAttribute('fill', 'none');
        polyline.setAttribute('stroke', getChartColor(chartType));
        polyline.setAttribute('stroke-width', '2');
        polyline.setAttribute('stroke-linecap', 'round');
        
        const area = document.createElementNS('http://www.w3.org/2000/svg', 'polygon');
        const areaPoints = [...points, '100,40', '0,40'];
        area.setAttribute('points', areaPoints.join(' '));
        area.setAttribute('fill', getChartColor(chartType));
        area.setAttribute('opacity', '0.1');
        
        svg.appendChild(area);
        svg.appendChild(polyline);
        chart.appendChild(svg);
    });
}

function generateChartData(type) {
    const baseValue = window.dashboardData?.metricas?.[getMetricKey(type)] || 100;
    const points = [];
    
    for (let i = 0; i < 8; i++) {
        const x = (i / 7) * 100;
        const variation = (Math.random() - 0.5) * 0.3;
        const y = Math.max(5, Math.min(35, 20 + (variation * 15)));
        points.push(`${x},${y}`);
    }
    
    return points;
}

function getMetricKey(chartType) {
    const mapping = {
        'users': 'total_usuarios',
        'students': 'estudiantes_activos', 
        'teachers': 'docentes_mentores',
        'sessions': 'sesiones_programadas'
    };
    return mapping[chartType];
}

function getChartColor(type) {
    const colors = {
        'users': '#8b5cf6',
        'students': '#10b981',
        'teachers': '#3b82f6',
        'sessions': '#f59e0b'
    };
    return colors[type] || '#6b7280';
}

// ===== FUNCIONES DE TIEMPO =====
function updateTimeAgo() {
    const timeElements = document.querySelectorAll('.timeline-time');
    timeElements.forEach(element => {
        const originalTime = element.dataset.time;
        if (originalTime) {
            element.textContent = formatTimeAgo(originalTime);
        }
    });
}

function formatTimeAgo(dateString) {
    const now = new Date();
    const date = new Date(dateString);
    const diff = Math.floor((now - date) / 1000);
    
    if (diff < 60) return `hace ${diff} seg`;
    if (diff < 3600) return `hace ${Math.floor(diff / 60)} min`;
    if (diff < 86400) return `hace ${Math.floor(diff / 3600)} hora${Math.floor(diff / 3600) > 1 ? 's' : ''}`;
    return `hace ${Math.floor(diff / 86400)} día${Math.floor(diff / 86400) > 1 ? 's' : ''}`;
}

// ===== FUNCIONES ADICIONALES =====
function initializeTooltips() {
    document.querySelectorAll('[data-tooltip]').forEach(element => {
        element.addEventListener('mouseenter', showTooltip);
        element.addEventListener('mouseleave', hideTooltip);
    });
}

function showTooltip(event) {
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip';
    tooltip.textContent = event.target.dataset.tooltip;
    document.body.appendChild(tooltip);
    
    const rect = event.target.getBoundingClientRect();
    tooltip.style.position = 'absolute';
    tooltip.style.top = `${rect.top - tooltip.offsetHeight - 8}px`;
    tooltip.style.left = `${rect.left + (rect.width - tooltip.offsetWidth) / 2}px`;
    tooltip.style.zIndex = '1003';
    tooltip.style.background = '#1f2937';
    tooltip.style.color = 'white';
    tooltip.style.padding = '4px 8px';
    tooltip.style.borderRadius = '4px';
    tooltip.style.fontSize = '0.875rem';
    tooltip.style.whiteSpace = 'nowrap';
}

function hideTooltip() {
    const tooltip = document.querySelector('.tooltip');
    if (tooltip) {
        tooltip.remove();
    }
}

function checkConnectivity() {
    return fetch(dashboardConfig.baseUrl + '/ping.php', {
        method: 'HEAD',
        cache: 'no-cache'
    }).then(response => response.ok).catch(() => false);
}

function saveDashboardState() {
    const state = {
        lastRefresh: Date.now(),
        metricsData: window.dashboardData?.metricas || null,
        userPreferences: {
            autoRefresh: true,
            notifications: true
        }
    };
    
    try {
        localStorage.setItem('dashboardState', JSON.stringify(state));
    } catch (e) {
        console.warn('No se pudo guardar el estado del dashboard:', e);
    }
}

function loadDashboardState() {
    try {
        const state = localStorage.getItem('dashboardState');
        return state ? JSON.parse(state) : null;
    } catch (e) {
        console.warn('No se pudo cargar el estado del dashboard:', e);
        return null;
    }
}

function setupPerformanceObservers() {
    const lazyElements = document.querySelectorAll('.metric-card, .action-item');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
            }
        });
    }, { threshold: 0.1 });
    
    lazyElements.forEach(el => observer.observe(el));
}

// ===== CONFIGURACIÓN PARA DATOS PHP =====
function setDashboardData(data) {
    window.dashboardData = data;
    
    if (document.readyState === 'complete') {
        setTimeout(() => {
            createRealMiniCharts();
        }, 100);
    }
}

// ===== EVENTOS PRINCIPALES =====
document.addEventListener('DOMContentLoaded', function() {
    // Observer para animaciones de contadores
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counters = entry.target.querySelectorAll('.metric-value[data-target]');
                counters.forEach(counter => {
                    if (!counter.classList.contains('animated')) {
                        counter.classList.add('animated');
                        animateCounter(counter);
                    }
                });
            }
        });
    }, { threshold: 0.3 });
    
    document.querySelectorAll('.metrics-grid').forEach(section => {
        observer.observe(section);
    });

    // Carga inicial forzada de contadores
    setTimeout(() => {
        document.querySelectorAll('.metric-value[data-target]').forEach(counter => {
            animateCounter(counter);
        });
    }, 500);

    // Crear mini gráficos
    setTimeout(() => {
        if (window.dashboardData && window.dashboardData.metricas) {
            createRealMiniCharts();
        } else {
            console.log('Datos del dashboard no disponibles para gráficos');
        }
    }, 800);
    
    // Actualizar tiempo transcurrido cada minuto
    setInterval(updateTimeAgo, 60000);
    
    // Configurar timestamps para timeline
    document.querySelectorAll('.timeline-time').forEach(element => {
        if (!element.dataset.time) {
            element.dataset.time = new Date().toISOString();
        }
    });

    // Atajos de teclado
    document.addEventListener('keydown', (e) => {
        if (e.ctrlKey || e.metaKey) {
            switch(e.key) {
                case '1':
                    e.preventDefault();
                    navegarA('anadir_alumnos');
                    break;
                case '2':
                    e.preventDefault();
                    navegarA('modificar_usuarios');
                    break;
                case '3':
                    e.preventDefault();
                    navegarA('modificar_clases');
                    break;
                case '4':
                    e.preventDefault();
                    navegarA('reportes');
                    break;
                case 'k':
                    e.preventDefault();
                    buscarUsuario();
                    break;
                case 'r':
                    e.preventDefault();
                    refreshDashboard();
                    break;
            }
        }
        
        if (e.key === 'Escape') {
            const modal = document.querySelector('.modal-overlay, .quick-actions-menu');
            if (modal) {
                modal.remove();
            }
        }
    });
    
    // Estado de conexión
    window.addEventListener('offline', () => {
        showNotification('Sin conexión a internet', 'error');
    });
    
    window.addEventListener('online', () => {
        showNotification('Conexión restaurada', 'success');
    });
    
    // Notificación de bienvenida
    setTimeout(() => {
        if (window.dashboardData) {
            showNotification('Dashboard cargado con datos reales. ¡Bienvenido!', 'success');
        } else {
            showNotification('Dashboard cargado correctamente. ¡Bienvenido!', 'success');
        }
    }, 1000);

    // Inicializar funciones adicionales
    initializeTooltips();
    
    if ('IntersectionObserver' in window) {
        setupPerformanceObservers();
    }
    
    window.addEventListener('beforeunload', saveDashboardState);
});

// ===== ESTILOS CSS CONSOLIDADOS =====
const consolidatedStyles = `
    /* Animaciones */
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Estados de métricas */
    .metric-trend.positive { color: #10b981; }
    .metric-trend.negative { color: #ef4444; }
    .metric-trend.neutral { color: #6b7280; }
    
    .metric-value.loading { opacity: 0.7; }
    
    /* Indicadores de estado */
    .status-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }
    .status-indicator.online { background-color: #10b981; }
    .status-indicator.offline { background-color: #ef4444; }
    .status-indicator.warning { background-color: #f59e0b; }
    .status-indicator.success { background-color: #10b981; }
    
    /* Timeline dots */
    .timeline-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .timeline-dot.success { background-color: #10b981; }
    .timeline-dot.info { background-color: #3b82f6; }
    .timeline-dot.warning { background-color: #f59e0b; }
    .timeline-dot.primary { background-color: #8b5cf6; }
    
    /* Quick badge */
    .quick-badge {
        background-color: #ef4444;
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 0.75rem;
        margin-left: auto;
        min-width: 18px;
        text-align: center;
    }
    
    /* Modales grandes */
    .modal-large {
        max-width: 600px;
    }
    
    .modal-xlarge {
        max-width: 95%;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    /* Formularios */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
    }
    
    .form-group label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #374151;
    }
    
    .form-group input,
    .form-group select {
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 1rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    
    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .form-group input.error {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }
    
    .form-help {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }
    
    .btn-secondary {
        background-color: #6b7280;
        color: white;
    }
    
    .btn-secondary:hover {
        background-color: #4b5563;
    }
    
    /* Tabla de usuarios */
    .users-toolbar {
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: #f9fafb;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }
    
    .search-filters {
        display: flex;
        gap: 1rem;
        align-items: end;
        flex-wrap: wrap;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        min-width: 150px;
    }
    
    .filter-group label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.25rem;
    }
    
    .filter-group input,
    .filter-group select {
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        font-size: 0.875rem;
    }
    
    .users-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 2rem;
        color: #6b7280;
    }
    
    .spinner-small {
        width: 20px;
        height: 20px;
        border: 2px solid #e5e7eb;
        border-top: 2px solid #3b82f6;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    .users-table-container {
        max-height: 60vh;
        overflow-y: auto;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
    }
    
    .users-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }
    
    .users-table th,
    .users-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .users-table th {
        background: #f9fafb;
        font-weight: 600;
        color: #374151;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .users-table tbody tr:hover {
        background: #f9fafb;
    }
    
    .role-badge,
    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .role-estudiante { background: #dbeafe; color: #1e40af; }
    .role-docente { background: #d1fae5; color: #065f46; }
    .role-administrador { background: #fef3c7; color: #92400e; }
    .role-visitante { background: #f3f4f6; color: #4b5563; }
    
    .status-activo { background: #d1fae5; color: #065f46; }
    .status-inactivo { background: #fecaca; color: #991b1b; }
    .status-graduado { background: #ddd6fe; color: #5b21b6; }
    .status-retirado { background: #fed7aa; color: #9a3412; }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-action {
        padding: 0.25rem 0.5rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.875rem;
        transition: all 0.2s;
    }
    
    .btn-edit {
        background: #3b82f6;
        color: white;
    }
    
    .btn-edit:hover {
        background: #2563eb;
    }
    
    .btn-view {
        background: #6b7280;
        color: white;
    }
    
    .btn-view:hover {
        background: #4b5563;
    }
    
    .btn-delete {
        background: #ef4444;
        color: white;
    }
    
    .btn-delete:hover {
        background: #dc2626;
    }
    
    /* Modales y menús */
    .modal-overlay, .quick-actions-menu {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
        backdrop-filter: blur(4px);
    }
    
    .modal-overlay.show, .quick-actions-menu.show {
        opacity: 1;
    }
    
    .modal-content, .menu-content {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        max-width: 500px;
        width: 90%;
        transform: scale(0.9);
        transition: transform 0.3s ease;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    .modal-overlay.show .modal-content,
    .quick-actions-menu.show .menu-content {
        transform: scale(1);
    }
    
    .modal-header, .menu-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .modal-close, .menu-close {
        background: none;
        border: none;
        font-size: 1.25rem;
        color: #6b7280;
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
        transition: background-color 0.2s;
    }
    
    .modal-close:hover, .menu-close:hover {
        background-color: #f3f4f6;
    }
    
    .modal-footer {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }
    
    .btn {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-primary {
        background-color: #3b82f6;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #2563eb;
    }
    
    /* Search input */
    .search-input {
        position: relative;
        margin-bottom: 1rem;
    }
    
    .search-input i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }
    
    .search-input input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 1rem;
        transition: border-color 0.2s;
    }
    
    .search-input input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Menu grid */
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1rem;
    }
    
    .menu-item {
        padding: 1rem;
        border: 1px solid #e5e7eb;
        background: white;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        text-align: center;
    }
    
    .menu-item:hover {
        background: #f9fafb;
        border-color: #3b82f6;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .menu-item i {
        font-size: 1.5rem;
        color: #3b82f6;
    }
    
    .menu-item span {
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
    }
    
    /* Notificaciones */
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1001;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        max-width: 400px;
    }
    
    .notification.show {
        transform: translateX(0);
    }
    
    .notification-content {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 1rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        min-width: 300px;
    }
    
    .notification-success .notification-content { 
        border-left: 4px solid #10b981; 
        background: #f0fdf4;
    }
    .notification-error .notification-content { 
        border-left: 4px solid #ef4444; 
        background: #fef2f2;
    }
    .notification-warning .notification-content { 
        border-left: 4px solid #f59e0b; 
        background: #fffbeb;
    }
    .notification-info .notification-content { 
        border-left: 4px solid #3b82f6; 
        background: #eff6ff;
    }
    
    .notification-success i { color: #10b981; }
    .notification-error i { color: #ef4444; }
    .notification-warning i { color: #f59e0b; }
    .notification-info i { color: #3b82f6; }
    
    .notification-close {
        background: none;
        border: none;
        color: #6b7280;
        cursor: pointer;
        margin-left: auto;
        padding: 4px;
        border-radius: 4px;
        transition: background-color 0.2s;
    }
    
    .notification-close:hover {
        background-color: rgba(0, 0, 0, 0.1);
    }
    
    /* Loading overlay */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1002;
        backdrop-filter: blur(4px);
    }
    
    .loading-spinner {
        text-align: center;
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    
    .spinner {
        width: 50px;
        height: 50px;
        border: 4px solid #e5e7eb;
        border-top: 4px solid #3b82f6;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 1rem;
    }
    
    .loading-spinner p {
        color: #6b7280;
        font-weight: 500;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .modal-content, .menu-content {
            width: 95%;
            padding: 1rem;
        }
        
        .menu-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .notification {
            top: 10px;
            right: 10px;
            left: 10px;
        }
        
        .notification-content {
            min-width: auto;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .search-filters {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filter-group {
            min-width: auto;
        }
    }
    
    /* Mejoras de accesibilidad */
    .menu-item:focus,
    .btn:focus,
    .modal-close:focus,
    .menu-close:focus {
        outline: 2px solid #3b82f6;
        outline-offset: 2px;
    }
    
    /* Animaciones adicionales */
    .timeline-item {
        animation: fadeIn 0.5s ease-out;
    }
    
    .metric-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    
    .action-item {
        transition: all 0.2s ease;
    }
    
    .action-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    /* Estados adicionales */
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        pointer-events: none;
    }
    
    .form-group input:disabled,
    .form-group select:disabled {
        background-color: #f9fafb;
        color: #6b7280;
        cursor: not-allowed;
    }
    
    /* Efectos de hover mejorados */
    .btn:not(:disabled):hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .modal-content {
        max-height: 90vh;
        overflow-y: auto;
    }
    
    /* Scroll personalizado */
    .users-table-container::-webkit-scrollbar,
    .modal-content::-webkit-scrollbar {
        width: 6px;
    }
    
    .users-table-container::-webkit-scrollbar-track,
    .modal-content::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .users-table-container::-webkit-scrollbar-thumb,
    .modal-content::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    .users-table-container::-webkit-scrollbar-thumb:hover,
    .modal-content::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
`;

// Aplicar estilos
const styleSheet = document.createElement('style');
styleSheet.textContent = consolidatedStyles;
document.head.appendChild(styleSheet);