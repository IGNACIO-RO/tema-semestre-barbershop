<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) { header("Location: login.php"); exit(); }
require_once __DIR__ . '/../config/database.php';
$database = new Database();
$db = $database->getConnection();
/* ==========================================
   TARJETAS
========================================== */
$sqlClientes = "SELECT COUNT(*) total FROM citas WHERE estado_cita='Finalizada'";
$totalClientes = $db->query($sqlClientes)->fetch(PDO::FETCH_ASSOC)['total'];
$sqlIngresos = "SELECT COALESCE(SUM(s.precio_bob),0) total FROM citas c INNER JOIN servicios s ON c.id_servicio=s.id_servicio WHERE c.estado_cita='Finalizada'";
$totalIngresos = $db->query($sqlIngresos)->fetch(PDO::FETCH_ASSOC)['total'];
$sqlServicios = "SELECT COUNT(*) total FROM citas WHERE estado_cita='Finalizada'";
$totalServicios = $db->query($sqlServicios)->fetch(PDO::FETCH_ASSOC)['total'];
$sqlTopBarbero = "SELECT u.nombre_completo, COUNT(*) servicios FROM citas c INNER JOIN barberos b ON c.id_barbero=b.id_barbero INNER JOIN usuarios u ON b.id_barbero=u.id_usuario WHERE c.estado_cita='Finalizada' GROUP BY u.nombre_completo ORDER BY servicios DESC LIMIT 1";
$topBarbero = $db->query($sqlTopBarbero)->fetch(PDO::FETCH_ASSOC);
/* ==========================================
   DETALLE
========================================== */
$sqlDetalle = "SELECT c.id_cita, c.fecha_cita, c.hora_cita, cli.nombre_completo cliente, s.nombre_servicio, s.precio_bob, u.nombre_completo barbero, c.estado_cita FROM citas c INNER JOIN clientes cli ON c.id_cliente=cli.id_cliente INNER JOIN servicios s ON c.id_servicio=s.id_servicio INNER JOIN barberos b ON c.id_barbero=b.id_barbero INNER JOIN usuarios u ON b.id_barbero=u.id_usuario WHERE c.estado_cita='Finalizada' ORDER BY c.fecha_cita DESC, c.hora_cita DESC";
$stmt = $db->prepare($sqlDetalle);
$stmt->execute();
$atenciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
include __DIR__ . '/layouts/header.php';
?>
<div class="PANEL-PRINCIPAL">
    <?php include __DIR__ . '/layouts/sidebar.php'; ?>
    <div class="CONTENIDO-VENTAS">
        <?php include __DIR__ . '/layouts/navbar.php'; ?>
        <h2 style="margin-bottom:20px;">📊 Reporte de Servicios Realizados</h2>
        <div class="RESUMEN">
            <div class="TARJETA"><span>Clientes Atendidos</span><h3><?= $totalClientes ?></h3></div>
            <div class="TARJETA"><span>Ingresos Generados</span><h3><?= number_format($totalIngresos,2) ?> Bs.</h3></div>
            <div class="TARJETA"><span>Servicios Realizados</span><h3><?= $totalServicios ?></h3></div>
            <div class="TARJETA"><span>Barbero Top</span><h3><?= $topBarbero['nombre_completo'] ?? 'N/D'; ?></h3></div>
        </div>
        <div class="BLOQUE">
            <h4>Detalle de Atenciones</h4>
            <table class="TABLA">
                <thead>
                    <tr><th>#</th><th>Fecha</th><th>Hora</th><th>Cliente</th><th>Servicio</th><th>Barbero</th><th>Precio</th></tr>
                </thead>
                <tbody>
                <?php if(empty($atenciones)): ?>
                    <tr><td colspan="7" style="text-align:center;padding:20px;">No existen servicios atendidos.</td></tr>
                <?php else: ?>
                    <?php foreach($atenciones as $a): ?>
                    <tr>
                        <td><?= $a['id_cita']; ?></td>
                        <td><?= date('d/m/Y',strtotime($a['fecha_cita'])); ?></td>
                        <td><?= $a['hora_cita']; ?></td>
                        <td><?= htmlspecialchars($a['cliente']); ?></td>
                        <td><?= htmlspecialchars($a['nombre_servicio']); ?></td>
                        <td><?= htmlspecialchars($a['barbero']); ?></td>
                        <td><strong style="color:#198754;"><?= number_format($a['precio_bob'],2); ?> Bs.</strong></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include __DIR__ . '/layouts/footer.php'; ?>