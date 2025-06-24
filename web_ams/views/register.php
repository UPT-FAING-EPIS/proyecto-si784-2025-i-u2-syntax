<?php
require_once BASE_PATH . '/views/components/head.php';
require_once BASE_PATH . '/views/components/header.php';
?>
<div class="register-container">
    <h2>Crear cuenta</h2>
    <form id="formRegistro" method="post">
        <input type="text" name="dni" id="dni" maxlength="8" placeholder="DNI" required oninput="validarDNI(this.value)">
        
        <input type="text" name="nombre" id="nombre" placeholder="Nombres" readonly required>
        <input type="text" name="apellido" id="apellido" placeholder="Apellidos" readonly required>

        <input type="email" name="email" id="email" placeholder="Correo electrónico" required oninput="validarCorreo(this)">
        <span id="correoError" class="error-text"></span>

        <input type="password" name="password" id="password" placeholder="Contraseña" required oninput="validarPassword(this.value)">
        <input type="password" name="confirmar" id="confirmar" placeholder="Confirmar contraseña" required oninput="compararPassword()">
        <span id="passError" class="error-text"></span>

        <button type="submit" id="btnRegistrar" disabled>Registrarse</button>
    </form>

    <div class="login-link">
        ¿Ya tienes cuenta? <a href="<?= BASE_URL ?>/index.php?accion=login">Inicia sesión aquí</a>
    </div>
</div>
<div id="modalCodigo" class="modal">
    <div class="modal-content">
        <h3>Verificación de correo</h3>
        <p>Revisa tu correo y escribe el código enviado:</p>
        <input type="text" id="codigoVerificacion" placeholder="Código de 6 dígitos" maxlength="6">
        <br>
        <button type="button" onclick="verificarCodigo()">Verificar</button>
        <button type="button" onclick="cerrarModal()" style="background-color: #6c757d; margin-left: 10px;">Cancelar</button>
    </div>
</div>
<?php require_once BASE_PATH . '/views/components/footer.php'; ?>
