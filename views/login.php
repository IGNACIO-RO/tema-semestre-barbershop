<?php 
// views/login.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'layouts/header.php';
// Si ya inició sesión
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: dashboard.php");
    exit();
}
?>
<div class="PAGINA-LOGIN">
    <div class="TARJETA-LOGIN">
        <div class="LOGIN-HEADER">
            <h2>💈 BARBERSHOP</h2>
            <small>SISTEMA DE GESTIÓN ELITE</small>
        </div>
        <div class="LOGIN-CUERPO">
            <p class="SUBTITULO-LOGIN">
                Inicie sesión para acceder al sistema
            </p>
            <?php if (isset($_GET['error'])): ?>
                <div class="ALERTA-ERROR">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>
                        Correo o contraseña incorrectos.
                    </span>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['registro']) && $_GET['registro'] == 'success'): ?>
                <div class="ALERTA-SUCCESS">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>
                        Cuenta creada correctamente.
                    </span>
                </div>
            <?php endif; ?>
            <!-- LOGIN -->
            <form action="../controllers/AuthController.php" method="POST">
                <input
                    type="hidden"
                    name="action"
                    value="login">
                <!-- CORREO -->
                <div class="GRUPO-INPUT">
                    <label for="correo">
                        Correo Electrónico
                    </label>
                    <div class="CON-ICONO">
                        <span class="ICONO-CEN">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input
                            type="email"
                            class="INPUT-LOGIN"
                            id="correo"
                            name="correo"
                            required
                            placeholder="ejemplo@barbershop.bo">
                    </div>
                </div>
                <!-- PASSWORD -->
                <div class="GRUPO-INPUT">
                    <label for="password">
                        Contraseña
                    </label>
                    <div class="CON-ICONO">
                        <span class="ICONO-CEN">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input
                            type="password"
                            class="INPUT-LOGIN"
                            id="password"
                            name="password"
                            required
                            placeholder="********">
                    </div>
                </div>
                <!-- BOTON LOGIN -->
                <button
                    type="submit"
                    class="BOTON-INGRESAR">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Ingresar al Sistema
                </button>
            </form>
            <!-- SEPARADOR -->
            <div class="SEPARADOR">
                <span>o</span>
            </div>
            <!-- CREAR CUENTA -->
            <a
                href="registro.php"
                class="BOTON-REGISTRO">
                <i class="bi bi-person-plus-fill"></i>
                Crear Cuenta Cliente
            </a>
        </div>     
        <div class="LOGIN-FOOTER">

            Talleres de Sistemas I — 2026
        </div>
    </div>
</div>
<?php 
include 'layouts/footer.php'; 
?>