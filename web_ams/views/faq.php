<?php
require_once BASE_PATH . '/views/components/head.php';
require_once BASE_PATH . '/views/components/header.php';
?>

<div class="faq-container">
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">
                <i class="fas fa-question-circle"></i>
                Preguntas Frecuentes
            </h1>
            <p class="page-subtitle">Encuentra respuestas rápidas a las consultas más comunes sobre nuestro sistema de mentoría</p>
        </div>
    </div>

    <div class="container">
        <!-- Buscador de FAQ -->
        <div class="search-section">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchFAQ" placeholder="Buscar en preguntas frecuentes...">
            </div>
        </div>

        <!-- Categorías -->
        <div class="categories-section">
            <h2 class="section-title">Categorías</h2>
            <div class="categories-grid">
                <button class="category-btn active" data-category="all">
                    <i class="fas fa-th-large"></i>
                    <span>Todas</span>
                </button>
                <button class="category-btn" data-category="cuenta">
                    <i class="fas fa-user"></i>
                    <span>Cuenta</span>
                </button>
                <button class="category-btn" data-category="mentoria">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Mentoría</span>
                </button>
                <button class="category-btn" data-category="tecnico">
                    <i class="fas fa-cogs"></i>
                    <span>Técnico</span>
                </button>
                <button class="category-btn" data-category="pagos">
                    <i class="fas fa-credit-card"></i>
                    <span>Pagos</span>
                </button>
                <button class="category-btn" data-category="general">
                    <i class="fas fa-info-circle"></i>
                    <span>General</span>
                </button>
            </div>
        </div>

        <!-- FAQs -->
        <div class="faq-content">
            <div class="faq-section" data-category="cuenta">
                <h3 class="faq-category-title">
                    <i class="fas fa-user"></i>
                    Gestión de Cuenta
                </h3>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h4>¿Cómo me registro en el sistema de mentoría UPT?</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Puedes registrarte de dos formas en nuestro sistema:</p>
                        <ul>
                            <li><strong>Con DNI y correo:</strong> Ingresa tu DNI, correo electrónico UPT y crea una contraseña</li>
                            <li><strong>Con Google Auth:</strong> Usa tu cuenta de Google para un registro más rápido</li>
                        </ul>
                        <p>Después del registro, deberás validar tu código de estudiante UPT para confirmar que eres estudiante activo de la universidad.</p>
                        <div class="answer-highlight">
                            <i class="fas fa-lightbulb"></i>
                            <span>Tip: Ten a la mano tu código de estudiante UPT, lo necesitarás para la validación.</span>
                        </div>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h4>¿Qué es el código de estudiante y por qué lo necesito?</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>El código de estudiante es tu identificador único en la Universidad Privada de Tacna. Lo necesitamos para:</p>
                        <ul>
                            <li>Verificar que eres estudiante activo de la UPT</li>
                            <li>Acceder a tu información académica</li>
                            <li>Asignarte mentores según tu carrera y ciclo</li>
                            <li>Garantizar la seguridad del sistema</li>
                        </ul>
                        <div class="answer-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>Sin validar tu código estudiantil, no podrás acceder al sistema de mentoría.</span>
                        </div>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h4>¿Qué hago si olvido mi contraseña?</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>En la página de inicio de sesión, haz clic en "¿Olvidaste tu contraseña?" e ingresa tu correo electrónico. Recibirás un enlace para restablecer tu contraseña.</p>
                    </div>
                </div>
            </div>

            <div class="faq-section" data-category="mentoria">
                <h3 class="faq-category-title">
                    <i class="fas fa-chalkboard-teacher"></i>
                    Proceso de Mentoría
                </h3>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h4>¿Cómo sugiero una clase donde necesito ayuda?</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Para sugerir una clase problemática donde necesitas mentoría:</p>
                        <ul>
                            <li>Ve a "Solicitar Mentoría" en tu panel principal</li>
                            <li>Selecciona la materia específica</li>
                            <li>Describe el tema o problema donde necesitas ayuda</li>
                            <li>Indica tu disponibilidad horaria</li>
                            <li>El sistema notificará a mentores calificados</li>
                        </ul>
                        <p>Una vez que un mentor tome tu solicitud, recibirás una notificación para coordinar la sesión.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h4>¿Cómo un mentor toma mi clase sugerida?</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Cuando un mentor ve tu solicitud y tiene conocimiento en esa área:</p>
                        <ul>
                            <li>Revisa los detalles de tu solicitud</li>
                            <li>Verifica que puede ayudarte con ese tema</li>
                            <li>Acepta la solicitud si su horario coincide</li>
                            <li>Te contacta para coordinar la primera sesión</li>
                        </ul>
                        <p>El sistema te enviará una notificación inmediata cuando un mentor tome tu clase.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h4>¿Qué carreras están disponibles en el sistema?</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>El sistema cubre todas las facultades de la Universidad Privada de Tacna:</p>
                        <ul>
                            <li><strong>Facultad de Ingeniería:</strong> Sistemas, Civil, Industrial, Ambiental, etc.</li>
                            <li><strong>Facultad de Arquitectura y Urbanismo</strong></li>
                            <li><strong>Facultad de Ciencias Empresariales:</strong> Administración, Contabilidad, etc.</li>
                            <li><strong>Facultad de Ciencias de la Salud:</strong> Medicina, Odontología, etc.</li>
                            <li><strong>Facultad de Educación, Ciencias de la Comunicación y Humanidades</strong></li>
                            <li><strong>Facultad de Derecho y Ciencias Políticas</strong></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="faq-section" data-category="tecnico">
                <h3 class="faq-category-title">
                    <i class="fas fa-cogs"></i>
                    Soporte Técnico
                </h3>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h4>¿Qué navegadores son compatibles con el sistema?</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>El sistema es compatible con las versiones más recientes de:</p>
                        <ul>
                            <li>Google Chrome (recomendado)</li>
                            <li>Mozilla Firefox</li>
                            <li>Microsoft Edge</li>
                            <li>Safari</li>
                        </ul>
                        <div class="answer-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>Internet Explorer no es compatible con nuestro sistema.</span>
                        </div>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h4>¿Qué hago si tengo problemas de conexión durante una sesión?</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Si experimentas problemas de conexión:</p>
                        <ul>
                            <li>Verifica tu conexión a internet</li>
                            <li>Cierra otras aplicaciones que consuman ancho de banda</li>
                            <li>Reinicia tu navegador</li>
                            <li>Contacta a soporte técnico si el problema persiste</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="faq-section" data-category="pagos">
                <h3 class="faq-category-title">
                    <i class="fas fa-credit-card"></i>
                    Pagos y Facturación
                </h3>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h4>¿Cuáles son los métodos de pago disponibles?</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Aceptamos los siguientes métodos de pago:</p>
                        <ul>
                            <li>Tarjetas de crédito y débito (Visa, Mastercard)</li>
                            <li>Transferencias bancarias</li>
                            <li>Billeteras digitales (Yape, Plin)</li>
                            <li>Pago en efectivo en agencias autorizadas</li>
                        </ul>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h4>¿Ofrecen descuentos para estudiantes?</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Sí, ofrecemos descuentos especiales para estudiantes de la UPT. También hay descuentos por volumen si contratas paquetes de sesiones múltiples.</p>
                    </div>
                </div>
            </div>

            <div class="faq-section" data-category="general">
                <h3 class="faq-category-title">
                    <i class="fas fa-info-circle"></i>
                    Información General
                </h3>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h4>¿El sistema está disponible para todas las sedes de la UPT?</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Sí, el sistema de mentoría está disponible para estudiantes de todas las sedes de la Universidad Privada de Tacna. Las sesiones de mentoría están disponibles:</p>
                        <ul>
                            <li><strong>Lunes a Viernes:</strong> 7:00 AM - 10:00 PM</li>
                            <li><strong>Sábados:</strong> 8:00 AM - 6:00 PM</li>
                            <li><strong>Domingos:</strong> 9:00 AM - 5:00 PM</li>
                        </ul>
                        <p>El soporte técnico está disponible de lunes a viernes de 8:00 AM a 6:00 PM.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h4>¿Cómo puedo contactar al soporte?</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Puedes contactarnos a través de:</p>
                        <ul>
                            <li>Chat en vivo (esquina inferior derecha)</li>
                            <li>Correo electrónico: soporte@ams-upt.edu.pe</li>
                            <li>Teléfono: (052) 583-000</li>
                            <li>WhatsApp: +51 952 123 456</li>
                            <li>Oficina: Campus UPT - Edificio Central</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de contacto -->
        <div class="contact-section">
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="contact-content">
                    <h3>¿No encontraste la respuesta a tu consulta?</h3>
                    <p>Nuestro equipo de soporte de la UPT está listo para ayudarte con cualquier pregunta sobre el sistema de mentoría académica.</p>
                    <a href="#" class="btn-contact">
                        <i class="fas fa-comments"></i>
                        Contactar Soporte
                    </a>
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

.faq-container {
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
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="faq-grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23faq-grid)"/></svg>');
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
    max-width: 600px;
    margin: 0 auto;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.search-section {
    margin: 3rem 0;
    display: flex;
    justify-content: center;
}

.search-box {
    position: relative;
    max-width: 500px;
    width: 100%;
}

.search-box i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
    font-size: 1.125rem;
}

.search-box input {
    width: 100%;
    padding: 1rem 1rem 1rem 3rem;
    border: 2px solid #e5e7eb;
    border-radius: 25px;
    font-size: 1rem;
    background: white;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-medium);
}

.search-box input:focus {
    outline: none;
    border-color: var(--secondary-blue);
    box-shadow: 0 0 0 3px rgba(46, 82, 130, 0.1);
}

.categories-section {
    margin-bottom: 3rem;
}

.section-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 2rem;
    text-align: center;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    max-width: 800px;
    margin: 0 auto;
}

.category-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1.5rem 1rem;
    border: 2px solid #e5e7eb;
    background: white;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    color: #6b7280;
}

.category-btn:hover {
    border-color: var(--secondary-blue);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(46, 82, 130, 0.15);
}

.category-btn.active {
    border-color: var(--secondary-blue);
    background: var(--secondary-blue);
    color: white;
}

.category-btn i {
    font-size: 1.5rem;
}

.category-btn span {
    font-weight: 600;
    font-size: 0.875rem;
}

.faq-content {
    margin-bottom: 4rem;
}

.faq-section {
    margin-bottom: 3rem;
}

.faq-category-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding-bottom: 0.75rem;
    border-bottom: 3px solid var(--secondary-blue);
}

.faq-category-title i {
    color: var(--secondary-blue);
}

.faq-item {
    background: white;
    border-radius: 12px;
    margin-bottom: 1rem;
    box-shadow: var(--shadow-medium);
    border: 1px solid rgba(46, 82, 130, 0.1);
    overflow: hidden;
    transition: all 0.3s ease;
}

.faq-item:hover {
    box-shadow: 0 4px 12px rgba(46, 82, 130, 0.1);
}

.faq-question {
    padding: 1.5rem;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #f8fafc 0%, white 100%);
    transition: background 0.3s ease;
}

.faq-question:hover {
    background: linear-gradient(135deg, #eff6ff 0%, #f8fafc 100%);
}

.faq-question h4 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
    line-height: 1.4;
}

.faq-question i {
    color: var(--secondary-blue);
    transition: transform 0.3s ease;
    font-size: 1rem;
}

.faq-item.active .faq-question i {
    transform: rotate(180deg);
}

.faq-answer {
    padding: 0 1.5rem 1.5rem;
    color: #4b5563;
    line-height: 1.6;
    display: none;
}

.faq-item.active .faq-answer {
    display: block;
    animation: slideDown 0.3s ease;
}

.faq-answer p {
    margin-bottom: 1rem;
}

.faq-answer ul {
    margin: 1rem 0;
    padding-left: 1.5rem;
}

.faq-answer li {
    margin-bottom: 0.5rem;
}

.answer-highlight {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(32, 201, 151, 0.1) 100%);
    border-left: 4px solid var(--accent-green);
    padding: 1rem;
    border-radius: 8px;
    margin: 1rem 0;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.answer-highlight i {
    color: var(--accent-green);
    margin-top: 0.125rem;
}

.answer-warning {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border-left: 4px solid #f59e0b;
    padding: 1rem;
    border-radius: 8px;
    margin: 1rem 0;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.answer-warning i {
    color: #f59e0b;
    margin-top: 0.125rem;
}

.contact-section {
    margin: 4rem 0;
    text-align: center;
}

.contact-card {
    background: linear-gradient(135deg, var(--secondary-blue) 0%, var(--primary-blue) 100%);
    color: white;
    padding: 3rem 2rem;
    border-radius: 20px;
    max-width: 600px;
    margin: 0 auto;
    position: relative;
    overflow: hidden;
}

.contact-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: float 6s ease-in-out infinite;
}

.contact-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    position: relative;
    z-index: 1;
}

.contact-content {
    position: relative;
    z-index: 1;
}

.contact-content h3 {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.contact-content p {
    font-size: 1.125rem;
    opacity: 0.9;
    margin-bottom: 2rem;
}

.btn-contact {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    background: white;
    color: var(--secondary-blue);
    padding: 1rem 2rem;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 700;
    font-size: 1.125rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-contact:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    text-decoration: none;
    color: var(--primary-blue);
}

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
    }
    to {
        opacity: 1;
        max-height: 500px;
    }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-20px) rotate(180deg);
    }
}

@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .categories-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .faq-question {
        padding: 1rem;
    }
    
    .faq-answer {
        padding: 0 1rem 1rem;
    }
    
    .contact-card {
        padding: 2rem 1rem;
    }
    
    .search-box {
        margin: 0 1rem;
    }
}

@media (max-width: 480px) {
    .categories-grid {
        grid-template-columns: 1fr;
    }
    
    .category-btn {
        padding: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const faqItem = this.parentElement;
            const isActive = faqItem.classList.contains('active');
            
            document.querySelectorAll('.faq-item.active').forEach(item => {
                if (item !== faqItem) {
                    item.classList.remove('active');
                }
            });
            
            faqItem.classList.toggle('active', !isActive);
        });
    });
    
    const categoryBtns = document.querySelectorAll('.category-btn');
    const faqSections = document.querySelectorAll('.faq-section');
    
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            categoryBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const category = this.dataset.category;
            
            faqSections.forEach(section => {
                if (category === 'all' || section.dataset.category === category) {
                    section.style.display = 'block';
                    section.style.animation = 'slideDown 0.3s ease';
                } else {
                    section.style.display = 'none';
                }
            });
        });
    });
    
    const searchInput = document.getElementById('searchFAQ');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const faqItems = document.querySelectorAll('.faq-item');
        
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question h4').textContent.toLowerCase();
            const answer = item.querySelector('.faq-answer').textContent.toLowerCase();
            
            if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                item.style.display = 'block';
                
                if (searchTerm.length > 2) {
                    highlightSearchTerm(item, searchTerm);
                }
            } else {
                item.style.display = 'none';
            }
        });
        
        if (searchTerm.length > 0) {
            faqSections.forEach(section => {
                section.style.display = 'block';
            });
        }
    });
    
    function highlightSearchTerm(element, term) {
        const walker = document.createTreeWalker(
            element,
            NodeFilter.SHOW_TEXT,
            null,
            false
        );
        
        const textNodes = [];
        let node;
        
        while (node = walker.nextNode()) {
            if (node.textContent.toLowerCase().includes(term)) {
                textNodes.push(node);
            }
        }
        
        textNodes.forEach(textNode => {
            const parent = textNode.parentNode;
            const text = textNode.textContent;
            const regex = new RegExp(`(${term})`, 'gi');
            const highlightedText = text.replace(regex, '<mark class="search-highlight">$1</mark>');
            
            if (highlightedText !== text) {
                const wrapper = document.createElement('span');
                wrapper.innerHTML = highlightedText;
                parent.replaceChild(wrapper, textNode);
            }
        });
    }
    
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'slideUp 0.6s ease forwards';
            }
        });
    }, { threshold: 0.1 });
    
    document.querySelectorAll('.faq-section, .category-btn').forEach(item => {
        observer.observe(item);
    });
});

const style = document.createElement('style');
style.textContent = `
    .search-highlight {
        background: var(--accent-green);
        padding: 0.125rem 0.25rem;
        border-radius: 3px;
        font-weight: 600;
        color: white;
    }
    
    @keyframes slideUp {
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