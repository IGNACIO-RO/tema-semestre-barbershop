<?php
// views/caja.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// LÓGICA DE AUDITORÍA CON RUTA ABSOLUTA PARA WINDOWS/XAMPP
$base_path = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require_once $base_path . 'controllers' . DIRECTORY_SEPARATOR . 'CajaController.php';

$cajaController = new CajaController();
$listaCajas = $cajaController->listarTodasLasCajas();

// Capturamos qué caja quiere auditar el administrador por la URL, por defecto la última
$id_caja_seleccionada = isset($_GET['id_caja']) ? intval($_GET['id_caja']) : (isset($listaCajas[0]) ? $listaCajas[0]['id_caja'] : null);

// Extraemos los números en tiempo real para las tarjetas informativas
$totales = $id_caja_seleccionada ? $cajaController->obtenerTotalesCaja($id_caja_seleccionada) : null;
$movimientos = $id_caja_seleccionada ? $cajaController->obtenerHistorialMovimientos($id_caja_seleccionada) : [];

include __DIR__ . '/layouts/header.php';
?>
<div class="PANEL-PRINCIPAL">
    
    <?php include __DIR__ . '/layouts/sidebar.php'; ?>
    
    <div class="CONTENIDO-CAJA">
        
        <?php include __DIR__ . '/layouts/navbar.php'; ?>
        <?php

$cajaActiva = $cajaController->obtenerCajaActiva(
    $_SESSION['id_usuario']
);

if(!$cajaActiva):
?>

<div class="BLOQUE-BLANCO" style="margin-bottom:20px;">

    <div class="ENCABEZADO-BLOQUE">
        <h2>
            <i class="bi bi-cash-stack me-2"></i>
            Abrir Nueva Caja
        </h2>
    </div>

    <div class="CUERPO-BLOQUE">

        <form
            method="POST"
            action="../controllers/CajaController.php">

            <input
                type="hidden"
                name="action"
                value="abrir_caja">

            <div class="GRUPO-FORM">

                <label>Monto Inicial (Bs.)</label>

                <input
                    type="number"
                    step="0.01"
                    min="0"
                    name="monto_apertura"
                    class="INPUT-CAJA"
                    required>

            </div>

            <button
                type="submit"
                class="BOTON-REGISTRAR"
                style="background:#198754;">

                <i class="bi bi-unlock-fill me-1"></i>
                Abrir Caja

            </button>

        </form>

    </div>

</div>

<?php endif; ?>
        <div class="BARRA-FILTROS">
            <div>
                <span style="font-weight: bold; color: #495057;"><i class="bi bi-sliders me-2"></i> Vista de Auditoría:</span>
            </div>
            <div>
                <label class="me-2" style="font-size: 0.9rem;">Seleccionar Caja / Sesión:</label>
                <select id="filtro_caja" class="SELECT-FILTRO" onchange="cambiarSesionCaja(this.value)">
                    <?php if (empty($listaCajas)): ?>
                        <option value="">No hay registros de cajas</option>
                    <?php else: ?>
                        <?php foreach ($listaCajas as $c): ?>
                            <option value="<?php echo $c['id_caja']; ?>" <?php echo ($id_caja_seleccionada == $c['id_caja']) ? 'selected' : ''; ?>>
                                Arqueo #<?php echo $c['id_caja']; ?> - <?php echo date('d/m/Y', strtotime($c['fecha_apertura'])); ?> (<?php echo htmlspecialchars($c['nombre_usuario']); ?>) - [<?php echo $c['estado_caja']; ?>]
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
        
        <div class="RESUMEN-CAJA">
            <div class="TARJETA-MONTO">
                <label>Monto Apertura</label>
                <h3><?php echo number_format($totales['monto_apertura'] ?? 0, 2); ?> Bs.</h3>
            </div>
            <div class="TARJETA-MONTO INGRESOS">
                <label>Ingresos Globales</label>
                <h3 style="color: #198754;">+ <?php echo number_format($totales['ingresos'] ?? 0, 2); ?> Bs.</h3>
            </div>
            <div class="TARJETA-MONTO EGRESOS">
                <label>Egresos (Gastos)</label>
                <h3 style="color: #dc3545;">- <?php echo number_format($totales['egresos'] ?? 0, 2); ?> Bs.</h3>
            </div>
            <div class="TARJETA-MONTO SALDO">
                <label>Efectivo Neto Esperado</label>
                <h3 style="color: #0d6efd;"><?php echo number_format($totales['saldo_neto'] ?? 0, 2); ?> Bs.</h3>
            </div>
        </div>

        <div class="LAYOUT-CAJA">
            
            <div class="BLOQUE-BLANCO">
                <div class="ENCABEZADO-BLOQUE">
                    <h2><i class="bi bi-cash-coin me-2"></i> Inyectar / Retirar Efectivo</h2>
                </div>
                <div class="CUERPO-BLOQUE">
                    <form method="POST" action="../controllers/procesar_movimiento.php">
                        <input type="hidden" name="action" value="guardar_movimiento_admin">
                        <input type="hidden" name="id_caja" value="<?php echo $id_caja_seleccionada; ?>">
                        
                        <div class="GRUPO-FORM">
                            <label for="tipo_movimiento">Operación</label>
                            <select name="tipo_movimiento" id="tipo_movimiento" class="SELECT-CAJA" required>
                                <option value="ingreso">Ingreso Manual (Ajuste/Cambio)</option>
                                <option value="egreso">Egreso Manual (Pago/Gasto)</option>
                            </select>
                        </div>
                        
                        <div class="GRUPO-FORM">
                            <label for="monto">Monto (Bs.)</label>
                            <input type="number" name="monto" id="monto" class="INPUT-CAJA" step="0.01" min="0.10" required placeholder="0.00">
                        </div>
                        
                        <div class="GRUPO-FORM">
                            <label for="descripcion">Justificación / Motivo</label>
                            <textarea name="descripcion" id="descripcion" class="INPUT-CAJA" rows="3" required placeholder="Detalla el motivo del movimiento..."></textarea>
                        </div>
                        
                        <button type="submit" class="BOTON-REGISTRAR" style="margin-bottom: 12px;" <?php echo ($totales && $totales['estado'] === 'Cerrada') ? 'disabled' : ''; ?>>
                            <i class="bi bi-check-lg me-1"></i> Aplicar Ajuste
                        </button>
                    </form>
                    
                    <hr style="border:0; border-top: 1px solid #dee2e6; margin: 15px 0;">
                    
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <button type="button" class="BOTON-REGISTRAR" style="background-color: #198754;" onclick="abrirModalArqueo()">
                            <i class="bi bi-calculator-fill me-1"></i> Realizar Arqueo de Caja
                        </button>
                        
                        <button type="button" class="BOTON-REGISTRAR" style="background-color: #dc3545;" onclick="confirmarCierreDefinitivo()" <?php echo ($totales && $totales['estado'] === 'Cerrada') ? 'disabled' : ''; ?>>
                            <i class="bi bi-lock-fill me-1"></i> Cerrar Caja Definitivamente
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="BLOQUE-BLANCO">
                <div class="ENCABEZADO-BLOQUE" style="background-color: #495057;">
                    <h2><i class="bi bi-journal-text me-2"></i> Flujo Diario Completo (Insumos y Servicios)</h2>
                </div>
                <div class="CUERPO-BLOQUE">
                    <table class="TABLA-MOVIMIENTOS">
                        <thead>
                            <tr>
                                <th>Hora</th>
                                <th>Tipo</th>
                                <th>Descripción / Transacción</th>
                                <th>Monto</th>
                                <th>Operador</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($movimientos)): ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; color: #888; padding: 20px;">No hay movimientos registrados en este arqueo.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($movimientos as $m): ?>
                                    <tr>
                                        <td><?php echo $m['hora']; ?></td>
                                        <td>
                                            <span class="BADGE-TIPO <?php 
                                                echo ($m['tipo'] == 'ingreso') ? 'BADGE-INGRESO' : (($m['tipo'] == 'egreso') ? 'BADGE-EGRESO' : 'BADGE-APERTURA'); 
                                            ?>">
                                                <?php echo $m['tipo']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($m['descripcion']); ?></td>
                                        <td style="font-weight: bold; color: <?php echo ($m['tipo'] == 'ingreso') ? '#198754' : '#dc3545'; ?>;">
                                            <?php echo ($m['tipo'] == 'ingreso') ? '+' : '-'; ?> <?php echo number_format($m['monto'], 2); ?> Bs.
                                        </td>
                                        <td><?php echo htmlspecialchars($m['usuario']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div> 
    </div>
</div>
<?php include __DIR__ . '/layouts/footer.php'; ?>