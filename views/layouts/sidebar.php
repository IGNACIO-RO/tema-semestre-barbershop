<?php
// views/layouts/sidebar.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Capturamos el id_rol de la sesión (si no existe, por defecto asumimos 0 o redirigimos)
// 1 = Administrador, 2 = Cajero, 3 = Barbero / Peluquero "HASTA AHORA TENEMOS ESOS 3 PERO FALTA EL CLIENTE"
//FALTAN UNAS 4 O 5 OPCIONES MAS PARA COMPLETAR PERO EL SISTEMA ESTA CASI TERMINADO 
$id_rol_actual = $_SESSION['id_rol'] ?? 1; 
?>

<div class="SIDEBAR">
    <div class="BRAND-LOGO">
        <i class="bi bi-scissors me-2"></i> ignacio y daniel
    </div>
    
    <ul class="MENU-ITEMS">
        <li class="ITEM-MENU">
            <a href="dashboard.php"><i class="bi bi-house-door-fill me-2"></i> Inicio</a>
        </li>

<?php if($_SESSION['id_rol'] == 1): ?>
<li class="ITEM-MENU">
    <a href="usuarios.php">
        <i class="bi bi-people-fill me-2"></i>
        Usuarios
    </a>
</li>
<?php endif; ?>

        <?php if ($id_rol_actual == 1 || $id_rol_actual == 3): ?>
            <li class="ITEM-MENU">
                <a href="agenda.php"><i class="bi bi-calendar3 me-2"></i> Agenda / Citas</a>
            </li>
        <?php endif; ?>

        <?php if ($id_rol_actual == 1 || $id_rol_actual == 2): ?>
            <li class="ITEM-MENU">
                <a href="caja.php"><i class="bi bi-cash-stack me-2"></i> Control de Caja</a>
            </li>
        <?php endif; ?>

        <?php if ($id_rol_actual == 1 || $id_rol_actual == 2): ?>
            <li class="ITEM-MENU">
                <a href="ventas.php"><i class="bi bi-cart-fill me-2"></i> Registrar Venta</a>
            </li>
        <?php endif; ?>

        <?php if ($id_rol_actual == 1): ?>
            <li class="ITEM-MENU">
                <a href="inventario.php"><i class="bi bi-box-seam-fill me-2"></i> Gestión Inventario</a>
            </li>
        <?php endif; ?>

        <?php if ($id_rol_actual == 1): ?>
<li class="ITEM-MENU">
    <a href="reporte_servicios.php">
        <i class="bi bi-graph-up-arrow me-2"></i>
        Reporte Servicios
    </a>
</li>
        <?php endif; ?>

<?php if ($id_rol_actual == 4): ?>
        <li class="ITEM-MENU">
    <a href="mis_citas.php">
        <i class="bi bi-calendar-check me-2"></i>
        Mis Citas
    </a>
</li>
<?php endif; ?>

<?php if ($id_rol_actual == 1 || $id_rol_actual == 2): ?>
    <li class="ITEM-MENU">
        <a href="reporte_ventas.php">
            <i class="bi bi-receipt-cutoff me-2"></i> Historial Ventas
        </a>
    </li>
<?php endif; ?>

<?php if ($id_rol_actual == 1 || $id_rol_actual == 2 || $id_rol_actual == 3 || $id_rol_actual == 4): ?>
    <li class="ITEM-MENU">
        <a href="calendario.php">
            <i class="bi bi-calendar-week me-2"></i>
        Calendario
    </a>
    </li>
<?php endif; ?>

    </ul>
    <div class="SIDEBAR-FOOTER">
        <a href="../controllers/AuthController.php?action=logout" class="BOTON-LOGOUT">
            <i class="bi bi-box-arrow-left me-2"></i> Cerrar Sesión
        </a>
    </div>
</div>