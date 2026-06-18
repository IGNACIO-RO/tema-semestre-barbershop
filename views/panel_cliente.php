<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// VALIDAR LOGIN
if (
    !isset($_SESSION['logged_in'])
    ||
    $_SESSION['logged_in'] !== true
) {
    header("Location: login.php");
    exit();
}
// VALIDAR ROL CLIENTE
if ($_SESSION['id_rol'] != 4) {
    header("Location: dashboard.php");
    exit();
}
include 'layouts/header.php';
?>
<div class="PANEL-CLIENTE">
    <div class="TARJETA-BIENVENIDA">
        <h1>
             Bienvenido
            <?php echo htmlspecialchars($_SESSION['nombre']); ?>
        </h1>
        <p>
            Panel de Cliente BarberShop Elite
        </p>
    </div>
    <div class="GRID-CLIENTE">
        <!-- AGENDAR -->
        <a
            href="mis_citas.php"
            class="CARD-CLIENTE">
            <i class="bi bi-calendar-check-fill"></i>
            <h3>Reservar Cita</h3>
            <p>
                Agenda tu próxima visita
            </p>
        </a>
        <!-- HISTORIAL -->
        <a
            href="mis_citas.php"
            class="CARD-CLIENTE">
            <i class="bi bi-clock-history"></i>
            <h3>Mis Citas</h3>
            <p>
                Historial de reservas
            </p>
        </a>
        <!-- COMPRAS -->
        <!-- <a
            href="#"
            class="CARD-CLIENTE">
            <i class="bi bi-bag-fill"></i>
            <h3>Mis Compras</h3>
            <p>
                Productos y servicios
            </p>
        </a>-->
        <!-- PERFIL -->
       <!--  <a
            href="#"
            class="CARD-CLIENTE">
            <i class="bi bi-person-fill"></i>
            <h3>Mi Perfil</h3>
            <p>
                Editar información personal
            </p>
        </a>-->
    </div>
    <!-- LOGOUT -->
    <div style="margin-top:30px; text-align:center;">
        <a
            href="../controllers/AuthController.php?action=logout"
            class="BTN-LOGOUT">
            <i class="bi bi-box-arrow-left"></i>
            Cerrar Sesión
        </a>
    </div>
</div>
<?php
include 'layouts/footer.php';
?>