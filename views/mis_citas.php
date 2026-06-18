<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['logged_in'])) { header("Location: login.php"); exit(); }
if ($_SESSION['id_rol'] != 4) { header("Location: dashboard.php"); exit(); }
require_once __DIR__ . '/../controllers/AgendaController.php';
$controller = new AgendaController();
/*
|--------------------------------------------------------------------------
| OBTENER CLIENTE POR CORREO
|--------------------------------------------------------------------------
*/
$correo = $_SESSION['correo'];
$db = (new Database())->getConnection();
$sqlCliente = "SELECT id_cliente FROM clientes WHERE correo = :correo LIMIT 1";
$stmt = $db->prepare($sqlCliente);
$stmt->execute([':correo' => $correo]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$cliente) { die("No existe cliente asociado."); }
$idCliente = $cliente['id_cliente'];
/*
|--------------------------------------------------------------------------
| DATOS
|--------------------------------------------------------------------------
*/
$barberos = $controller->listarBarberos();
$servicios = $controller->listarServicios();
$citas = $controller->listarCitasCliente($idCliente);
include __DIR__ . '/layouts/header.php';
?>
<div class="PANEL-PRINCIPAL">
    <?php include __DIR__ . '/layouts/sidebar.php'; ?>
    <div class="CONTENIDO">
        <?php include __DIR__ . '/layouts/navbar.php'; ?>
        <?php if(isset($_GET['status']) && $_GET['status']=='success'): ?><div class="ALERTA SUCCESS">Cita registrada correctamente.</div><?php endif; ?>
        <div class="LAYOUT">
            <div class="BLOQUE">
                <div class="HEADER"><h2>Solicitar Nueva Cita</h2></div>
                <div class="CUERPO">
                    <form method="POST" action="../controllers/AgendaController.php">
                        <input type="hidden" name="action" value="registrar_cita">
                        <input type="hidden" name="id_cliente" value="<?= $idCliente ?>">
                        <div class="GRUPO">
                            <label>Barbero</label>
                            <select name="id_barbero" class="INPUT" required>
                                <option value="">Seleccione barbero</option>
                                <?php foreach($barberos as $b): ?><option value="<?= $b['id_usuario']; ?>"><?= htmlspecialchars($b['nombre_completo']); ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="GRUPO">
                            <label>Servicio</label>
                            <select name="id_servicio" class="INPUT" required>
                                <option value="">Seleccione servicio</option>
                                <?php foreach($servicios as $s): ?><option value="<?= $s['id_servicio']; ?>"><?= htmlspecialchars($s['nombre_servicio']); ?> - <?= number_format($s['precio_bob'],2); ?> Bs</option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="GRUPO"><label>Fecha</label><input type="date" name="fecha_cita" class="INPUT" min="<?= date('Y-m-d'); ?>" required></div>
                        <div class="GRUPO"><label>Hora</label><input type="time" name="hora_cita" class="INPUT" required></div>
                        <button type="submit" class="BTN">Solicitar Cita</button>
                    </form>
                </div>
            </div>
            <div class="BLOQUE">
                <div class="HEADER"><h2>Mis Citas</h2></div>
                <div class="CUERPO">
                    <table class="TABLA">
                        <thead><tr><th>Barbero</th><th>Servicio</th><th>Fecha</th><th>Hora</th><th>Estado</th></tr></thead>
                        <tbody>
                        <?php if(empty($citas)): ?>
                            <tr><td colspan="5" style="text-align:center;padding:20px;">No tienes citas registradas.</td></tr>
                        <?php endif; ?>
                        <?php foreach($citas as $c): ?>
                            <tr>
                                <td><?= htmlspecialchars($c['barbero']); ?></td>
                                <td><?= htmlspecialchars($c['nombre_servicio']); ?><br><small><?= number_format($c['precio_bob'],2); ?> Bs</small></td>
                                <td><?= $c['fecha_cita']; ?></td>
                                <td><?= $c['hora_cita']; ?></td>
                                <td>
                                    <?php
                                    $clase='';
                                    switch($c['estado_cita']){
                                        case 'Programada': $clase='PROGRAMADA'; break;
                                        case 'Confirmada': $clase='CONFIRMADA'; break;
                                        case 'En Proceso': $clase='PROCESO'; break;
                                        case 'Finalizada': $clase='FINALIZADA'; break;
                                        case 'Cancelada': $clase='CANCELADA'; break;
                                    }
                                    ?>
                                    <span class="BADGE <?= $clase; ?>"><?= $c['estado_cita']; ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/layouts/footer.php'; ?>