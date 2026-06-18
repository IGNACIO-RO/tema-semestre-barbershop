<?php
// views/agenda.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['logged_in'])) { header("Location: login.php"); exit(); }
require_once __DIR__ . '/../controllers/AgendaController.php';
$controller = new AgendaController();
$clientes = $controller->listarClientes();
$barberos = $controller->listarBarberos();
$servicios = $controller->listarServicios();
$citas = $controller->listarCitas();
include __DIR__ . '/layouts/header.php';
?>
<div class="PANEL-PRINCIPAL">
    <?php include __DIR__ . '/layouts/sidebar.php'; ?>
    <div class="CONTENIDO">
        <?php include __DIR__ . '/layouts/navbar.php'; ?>
        <?php if(isset($_GET['status']) && $_GET['status']=='success'): ?><div class="ALERTA SUCCESS">✅ Cita registrada correctamente.</div><?php endif; ?>
        <?php if(isset($_GET['status']) && $_GET['status']=='error'): ?><div class="ALERTA ERROR"><?= htmlspecialchars($_GET['msg'] ?? 'Error'); ?></div><?php endif; ?>

        <div class="LAYOUT">
            <div class="BLOQUE">
                <div class="HEADER"><h2>Registrar Cita</h2></div>
                <div class="CUERPO">
                    <form method="POST" action="../controllers/AgendaController.php">
                        <input type="hidden" name="action" value="registrar_cita">
                        <div class="GRUPO">
                            <label>Cliente</label>
                            <select name="id_cliente" class="INPUT" required>
                                <option value="">Seleccione cliente</option>
                                <?php foreach($clientes as $c): ?><option value="<?= $c['id_cliente']; ?>"><?= htmlspecialchars($c['nombre_completo']); ?></option><?php endforeach; ?>
                            </select>
                        </div>
                         <!-- BARBERO -->
                        <div class="GRUPO">
                            <label>Barbero</label>
                            <select name="id_barbero" class="INPUT" required>
                                <option value="">Seleccione barbero</option>
                                <?php foreach($barberos as $b): ?><option value="<?= $b['id_usuario']; ?>"><?= htmlspecialchars($b['nombre_completo']); ?></option><?php endforeach; ?>
                            </select>
                        </div>
                         <!-- SERVICIO -->
                        <div class="GRUPO">
                            <label>Servicio</label>
                            <select name="id_servicio" class="INPUT" required>
                                <option value="">Seleccione servicio</option>
                                <?php foreach($servicios as $s): ?><option value="<?= $s['id_servicio']; ?>"><?= htmlspecialchars($s['nombre_servicio']); ?> - <?= number_format($s['precio_bob'],2); ?> Bs</option><?php endforeach; ?>
                            </select>
                        </div>
                           <!-- FECHA -->
                        <div class="GRUPO"><label>Fecha</label><input type="date" name="fecha_cita" class="INPUT" required></div>
                        <!-- HORA -->
                        <div class="GRUPO"><label>Hora</label><input type="time" name="hora_cita" class="INPUT" required></div>
                        <button type="submit" class="BTN">Registrar Cita</button>
                    </form>
                </div>
            </div>
<!-- TABLA CITAS -->
            <div class="BLOQUE">
                <div class="HEADER"><h2>Agenda de Citas</h2></div>
                <div class="CUERPO">
                    <table class="TABLA">
                        <thead>
                            <tr><th>Cliente</th><th>Barbero</th><th>Servicio</th><th>Fecha</th><th>Hora</th><th>Estado</th><th>Acción</th></tr>
                        </thead>
                        <tbody>
                            <?php if(empty($citas)): ?>
                                <tr><td colspan="7" style="text-align:center;padding:20px;color:#888;">No hay citas registradas.</td></tr>
                            <?php endif; ?>
                            <?php foreach($citas as $c): ?>
                                <tr>
                                    <td><?= htmlspecialchars($c['cliente']); ?></td>
                                    <td><?= htmlspecialchars($c['barbero']); ?></td>
                                    <td><?= htmlspecialchars($c['nombre_servicio']); ?><br><small><?= number_format($c['precio_bob'],2); ?> Bs</small></td>
                                    <td><?= $c['fecha_cita']; ?></td>
                                    <td><?= $c['hora_cita']; ?></td>
                                    <td>
                                        <?php
                                        $clase = '';
                                        switch($c['estado_cita']){
                                            case 'Programada': $clase = 'PROGRAMADA'; break;
                                            case 'Confirmada': $clase = 'CONFIRMADA'; break;
                                            case 'En Proceso': $clase = 'PROCESO'; break;
                                            case 'Finalizada': $clase = 'FINALIZADA'; break;
                                            case 'Cancelada': $clase = 'CANCELADA'; break;
                                        }
                                        ?>
                                        <span class="BADGE <?= $clase; ?>"><?= $c['estado_cita']; ?></span>
                                    </td>
                                    <td>
                                        <form method="POST" action="../controllers/AgendaController.php">
                                            <input type="hidden" name="action" value="cambiar_estado">
                                            <input type="hidden" name="id_cita" value="<?= $c['id_cita']; ?>">
                                            <select name="estado" class="INPUT" onchange="this.form.submit()">
                                                <option value="Programada" <?= ($c['estado_cita']=='Programada') ? 'selected' : ''; ?>>Programada</option>
                                                <option value="Confirmada" <?= ($c['estado_cita']=='Confirmada') ? 'selected' : ''; ?>>Confirmada</option>
                                                <option value="En Proceso" <?= ($c['estado_cita']=='En Proceso') ? 'selected' : ''; ?>>En Proceso</option>
                                                <option value="Finalizada" <?= ($c['estado_cita']=='Finalizada') ? 'selected' : ''; ?>>Finalizada</option>
                                                <option value="Cancelada" <?= ($c['estado_cita']=='Cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                                            </select>
                                        </form>
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