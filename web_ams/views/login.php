<?php
require_once BASE_PATH . '/views/components/head.php';
require_once BASE_PATH . '/views/components/header.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<div class="login-container">
    <h2>Iniciar sesión</h2>

    <?php if (isset($_GET['error'])): ?>
    <?php if ($_GET['error'] == 1): ?>
        <p class="mensaje-error" style="color: red; font-weight: bold;">
            Credenciales incorrectas. Intenta nuevamente.
        </p>
    <?php elseif ($_GET['error'] == 2): ?>
        <p class="mensaje-error" style="color: red; font-weight: bold;">
            Por favor completa todos los campos antes de ingresar.
        </p>
    <?php endif; ?>
<?php endif; ?>
    <form method="post" action="<?= BASE_URL ?>/index.php?accion=procesar_login">
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Ingresar</button>
    </form>

    <div class="register-link">
        ¿No tienes cuenta? <a href="<?= BASE_URL ?>/index.php?accion=registro">Regístrate aquí</a>
    </div>

    <hr>
    <div class="social-buttons">
        <form action="<?= BASE_URL ?>/auth/google.php" method="get">
            <button class="google" type="submit">
                <i class="fab fa-google"></i> Continuar con Google
            </button>
        </form>
    </div>
</div>

<?php require_once BASE_PATH . '/views/components/footer.php'; ?>
