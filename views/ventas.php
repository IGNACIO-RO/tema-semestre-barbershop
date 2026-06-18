<?php
// views/ventas.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
// ================= VÍNCULO CON EL CONTROL DE CAJA ACTIVADO =================
// Apuntamos al controlador general unificado
$ruta_controlador = __DIR__ . '/../controllers/CajaController.php';

if (file_exists($ruta_controlador)) {
    require_once $ruta_controlador;
} else {
    die("Error crítico: No se encuentra el archivo físico en: controllers/CajaController.php");
}

// Cambiamos la validación para usar CajaController
if (class_exists('CajaController')) {
    $cajaCont = new CajaController();
    $id_cajero_actual = $_SESSION['id_usuario'] ?? 2; 
    $cajaActiva = $cajaCont->obtenerCajaActiva($id_cajero_actual);
} else {
    die("Error interno en controllers/CajaController.php");
}
require_once __DIR__ . '/../controllers/InventarioController.php';
require_once __DIR__ . '/../controllers/ClienteController.php';
$clienteController = new ClienteController();
$clientes = $clienteController->listarClientes();
$controller = new InventarioController();
$productos = $controller->listarProductos(); 

include __DIR__ . '/layouts/header.php';
?>
<div class="PANEL-PRINCIPAL">
    
    <?php include __DIR__ . '/layouts/sidebar.php'; ?>
    
    <div class="CONTENIDO-VENTAS">
        
        <?php include __DIR__ . '/layouts/navbar.php'; ?>

        <?php if (!$cajaActiva): ?>
            <div class="ALERTA-BLOQUEO">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                Atención: Debes realizar la Apertura de Turno en tu panel de "Control de Caja" antes de poder procesar transacciones comerciales en el sistema.
            </div>
        <?php endif; ?>
        
        <div class="LAYOUT-VENTAS">
            
            <div class="BLOQUE-BLANCO">
                <div class="ENCABEZADO-BLOQUE">
                    <div class="GRUPO-FORM" style="padding:15px;">
    <label><strong>Cliente</strong></label>

    <select name="id_cliente"
            id="id_cliente"
            class="INPUT-CAJA"
            required>

        <option value="">Seleccione un cliente</option>

        <?php foreach($clientes as $cliente): ?>
            <option value="<?= $cliente['id_cliente']; ?>">
                <?= htmlspecialchars($cliente['nombre_completo']); ?>
            </option>
        <?php endforeach; ?>

    </select>
</div>
                    <h2><i class="bi bi-search me-2"></i> Buscar y Agregar Productos</h2>
                </div>
                <div class="CUERPO-BLOQUE">
                    
                    <div class="CAJA-BUSCADOR">
                        <input type="text" id="buscar_producto" class="INPUT-BUSCAR" onkeyup="filtrarTablaProductos()" placeholder="Escribe el nombre del producto para filtrar...">
                    </div>
                    
                    <table class="TABLA-VENTAS" id="tabla-catalogo">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Stock Disp.</th>
                                <th>Precio (Bs.)</th>
                                <th style="text-align: center;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($productos)): ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 20px; color: #888;">No hay productos en el inventario.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($productos as $p): ?>
                                    <tr class="fila-producto-busqueda">
                                        <td>#<?php echo $p['id_producto']; ?></td>
                                        <td><strong class="nombre-prod-buscar"><?php echo htmlspecialchars($p['nombre_producto']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($p['nombre_categoria'] ?? 'General'); ?></td>
                                        <td>
                                            <span style="font-weight: bold; color: <?php echo ($p['stock_actual'] <= $p['stock_minimo']) ? '#dc3545' : '#198754'; ?>;">
                                                <?php echo $p['stock_actual']; ?> u.
                                            </span>
                                        </td>
                                        <td><strong><?php echo number_format($p['precio_venta'] ?? 0, 2); ?> Bs.</strong></td>
                                        <td style="text-align: center;">
                                            <?php if (($p['stock_actual'] ?? 0) > 0): ?>
                                                <button class="BOTON-ACCION BTN-AGREGAR" 
                                                        onclick="agregarAlCarrito(<?php echo $p['id_producto']; ?>, '<?php echo htmlspecialchars(addslashes($p['nombre_producto'])); ?>', <?php echo $p['precio_venta'] ?? 0; ?>, <?php echo $p['stock_actual']; ?>)"
                                                        <?php echo !$cajaActiva ? 'disabled' : ''; ?>>
                                                    <i class="bi bi-plus-lg"></i> Añadir
                                                </button>
                                            <?php else: ?>
                                                <span style="color:#dc3545; font-size:0.8rem; font-weight:bold;">Sin Stock</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    
                </div>
            </div>
            
            <div class="BLOQUE-BLANCO">
                <div class="ENCABEZADO-BLOQUE" style="background-color: #198754;">
                    <h2><i class="bi bi-cart-fill me-2"></i> Detalle de la Venta</h2>
                </div>
                <div class="CUERPO-BLOQUE" style="flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                    
                    <div style="max-height: 350px; overflow-y: auto; margin-bottom: 20px;">
                        <table class="TABLA-VENTAS">
                            <thead>
                                <tr>
                                    <th>Cant.</th>
                                    <th>Descripción</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead> <tbody id="items-carrito">
                                <tr id="carrito-vacio">
                                    <td colspan="4" style="text-align: center; padding: 30px; color: #888;">Ningún producto añadido.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div>
                        <div class="FILA-TOTAL">
                            <span>Subtotal:</span>
                            <span><strong id="txt-subtotal">0.00</strong> Bs.</span>
                        </div>
                        <div class="FILA-TOTAL">
                            <span>Descuento (Bs.):</span>
                            <input type="number" id="input-descuento" value="0.00" min="0" step="0.01" class="INPUT-CANTIDAD" style="width: 90px; text-align: right;" oninput="calcularTotales()" <?php echo !$cajaActiva ? 'disabled' : ''; ?>>
                        </div>
                        <div class="FILA-TOTAL GRANDE">
                            <span>Total a Pagar:</span>
                            <span style="color: #198754;"><span id="txt-total">0.00</span> Bs.</span>
                        </div>
                        
                        <form method="POST" action="../controllers/VentasController.php" onsubmit="prepararEnvio(event)">
                            <input type="hidden" name="accion" value="guardar_venta">
                            <input type="hidden" name="carrito_datos" id="carrito_datos">
                            <input type="hidden" name="id_caja" value="<?php echo $cajaActiva ? $cajaActiva['id_caja'] : ''; ?>">
                            <input type="hidden" name="id_cliente_hidden" id="id_cliente_hidden">
                           <div class="GRUPO-FORM" style="margin-top:15px;">
    <label><strong>Método de Pago</strong></label>

    <select
    name="metodo_pago"
    id="metodo_pago"
    class="INPUT-CAJA"
    onchange="mostrarMetodoPago()"
    required>

    <option value="">Seleccione método de pago</option>

    <option value="EFECTIVO">Efectivo</option>

    <option value="QR">QR</option>

    <option value="TARJETA">Tarjeta</option>

</select>

    <div id="contenedor_pago" style="display:none; margin-top:15px;">

    <div id="panel_qr" style="display:none; text-align:center;">

        <h5>Escanee el QR para realizar el pago</h5>

        <img
            src="./../public/img/QR.jpeg"
            alt="QR Pago"
            style="width:250px; border:1px solid #ccc; padding:10px; border-radius:8px;">

        <p style="margin-top:10px;">
            Una vez recibido el pago presione Procesar Venta.
            Por favor en la referencia del pago indique el nombre del cliente o el número de teléfono para facilitar la identificación.
        </p>

    </div>

    <div id="panel_tarjeta" style="display:none;">

        <div class="alert alert-info">

            Pase la tarjeta por la terminal POS.

            <br><br>

            Espere la aprobación bancaria antes de registrar la venta.

        </div>

    </div>

</div>
</div>

                            <button type="submit" class="BOTON-CONFIRMAR" <?php echo !$cajaActiva ? 'disabled' : ''; ?>>
                                <i class="bi bi-check-circle-fill me-2"></i> Procesar Venta (Bs.)
                            </button>
                        </form>
                    </div>

                </div>
            </div>
            
        </div> 
    </div>
</div>
<?php include __DIR__ . '/layouts/footer.php'; ?>