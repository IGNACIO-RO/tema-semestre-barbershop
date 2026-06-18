<?php
// views/dashboard.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../controllers/DashboardController.php';
$dashController = new DashboardController();

$ingresosHoy = $dashController->getIngresosHoy();
$alertasStock = $dashController->getProductosAlertaStock();
$citasHoy = $dashController->getCitasHoy();
?>

<?php include __DIR__ . '/layouts/header.php'; ?>
<div class="CONTENEDOR-PANEL">
    
    <?php include __DIR__ . '/layouts/sidebar.php'; ?>

    <div class="CONTENIDO-DASHBOARD">
        
        <?php include __DIR__ . '/layouts/navbar.php'; ?>

        <div class="BANNER-BIENVENIDA">
            <h1>¡Bienvenido, <?php echo explode(' ', $_SESSION['nombre'])[0]; ?>!</h1>
            <p>Monitoreo general del estado operativo de la barbería.</p>
        </div>

        <div class="GRID-METRICAS">
            
            <div class="TARJETA-CONTADOR VERDE">
                <div class="TEXTOS-TARJETA">
                    <label>Ingresos Totales (Hoy)</label>
                    <h2><?php echo number_format($ingresosHoy, 2); ?> Bs.</h2>
                </div>
                <div class="ICONO-CONTENEDOR">
                    <i class="bi bi-cash-coin"></i>
                </div>
            </div>

            <div class="TARJETA-CONTADOR AZUL">
                <div class="TEXTOS-TARJETA">
                    <label>Citas Programadas</label>
                    <h2><?php echo $citasHoy; ?> Clientes</h2>
                </div>
                <div class="ICONO-CONTENEDOR">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>
            </div>

            <div class="TARJETA-CONTADOR ROJO">
                <div class="TEXTOS-TARJETA">
                    <label>Alertas de Stock</label>
                    <h2><?php echo $alertasStock; ?> Insumos Bajos</h2>
                </div>
                <div class="ICONO-CONTENEDOR">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
            </div>

        </div> </div>
</div>

<?php include __DIR__ . '/layouts/footer.php'; ?>