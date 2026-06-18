<?php
// views/registro.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'layouts/header.php';
// Si ya inició sesión
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) { header("Location: dashboard.php"); exit(); }
?>
<div class="PAGINA-REGISTRO">
    <div class="TARJETA-REGISTRO">
        <div class="REGISTRO-HEADER"><h2>💈 BARBERSHOP</h2><small>CREAR CUENTA CLIENTE</small></div>
        <div class="REGISTRO-CUERPO">
            <p class="SUBTITULO-REGISTRO">Regístrate para reservar citas y acceder al sistema</p>
            <?php if (isset($_GET['error']) && $_GET['error'] == 'correo'): ?><div class="ALERTA-ERROR"><i class="bi bi-exclamation-triangle-fill"></i> Ese correo ya está registrado.</div><?php endif; ?>
            <?php if (isset($_GET['error']) && $_GET['error'] == 'password'): ?><div class="ALERTA-ERROR"><i class="bi bi-exclamation-triangle-fill"></i> Las contraseñas no coinciden.</div><?php endif; ?>
            <?php if (isset($_GET['error']) && $_GET['error'] == 'general'): ?><div class="ALERTA-ERROR"><i class="bi bi-exclamation-triangle-fill"></i> Ocurrió un error al registrar.</div><?php endif; ?>
            <form action="../controllers/AuthController.php" method="POST" onsubmit="return validarPasswords()">
                <input type="hidden" name="action" value="registro_cliente">
                <div class="GRUPO-INPUT">
                    <label>Nombre Completo</label>
                    <div class="CON-ICONO"><span class="ICONO-CEN"><i class="bi bi-person-fill"></i></span><input type="text" name="nombre" class="INPUT-LOGIN" required placeholder="Juan Pérez"></div>
                </div>
                <div class="GRUPO-INPUT">
                    <label>Celular</label>
                    <div class="CON-ICONO"><span class="ICONO-CEN"><i class="bi bi-telephone-fill"></i></span><input type="text" name="celular" class="INPUT-LOGIN" required placeholder="70000000"></div>
                </div>
                <div class="GRUPO-INPUT">
                    <label>Género</label>
                    <div class="CON-ICONO">
                        <span class="ICONO-CEN"><i class="bi bi-gender-ambiguous"></i></span>
                        <select name="id_genero" class="INPUT-LOGIN" required>
                            <option value="">Seleccione género</option>
                            <option value="1">Masculino</option>
                            <option value="2">Femenino</option>
                        </select>
                    </div>
                </div>
                <div class="GRUPO-INPUT">
                    <label>Correo Electrónico</label>
                    <div class="CON-ICONO"><span class="ICONO-CEN"><i class="bi bi-envelope-fill"></i></span><input type="email" name="correo" class="INPUT-LOGIN" required placeholder="correo@gmail.com"></div>
                </div>
                <div class="GRUPO-INPUT">
                    <label>Contraseña</label>
                    <div class="CON-ICONO"><span class="ICONO-CEN"><i class="bi bi-lock-fill"></i></span><input type="password" name="password" id="password" class="INPUT-LOGIN" required placeholder="********"></div>
                </div>
                <div class="GRUPO-INPUT">
                    <label>Confirmar Contraseña</label>
                    <div class="CON-ICONO"><span class="ICONO-CEN"><i class="bi bi-shield-lock-fill"></i></span><input type="password" id="confirm_password" class="INPUT-LOGIN" required placeholder="********"></div>
                </div>
                <button type="submit" class="BOTON-REGISTRAR"><i class="bi bi-person-plus-fill"></i> Crear Cuenta</button>
            </form>
            <div class="VOLVER-LOGIN"><a href="login.php"><i class="bi bi-arrow-left-circle"></i> Ya tengo cuenta</a></div>
        </div>
        <div class="REGISTRO-FOOTER">BarberShop Elite — 2026</div>
    </div>
</div>
<script>
function validarPasswords(){
    let pass = document.getElementById('password').value;
    let confirm = document.getElementById('confirm_password').value;
    if(pass !== confirm){ alert("Las contraseñas no coinciden"); return false; }
    return true;
}
</script>
<?php include 'layouts/footer.php'; ?>