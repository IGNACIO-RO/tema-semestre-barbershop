<?php
// views/inventario.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}


require_once __DIR__ . '/../controllers/InventarioController.php';
// Cargamos también tu archivo de configuración de base de datos directamente
require_once __DIR__ . '/../config/database.php'; 


$controller = new InventarioController();


// Procesador de Acciones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    if ($_POST['accion'] === 'crear') {
        $controller->crearProducto(
            $_POST['id_categoria'], $_POST['id_marca'], $_POST['id_provider'], 
            $_POST['nombre_producto'], $_POST['stock_actual'], $_POST['stock_minimo'], 
            $_POST['precio_costo_bob'], $_POST['precio_venta_publico_bob']
        );
    } elseif ($_POST['accion'] === 'editar') {
        $controller->editarProducto(
            $_POST['id_producto'], $_POST['id_categoria'], $_POST['id_marca'], $_POST['id_provider'], 
            $_POST['nombre_producto'], $_POST['stock_actual'], $_POST['stock_minimo'], 
            $_POST['precio_costo_bob'], $_POST['precio_venta_publico_bob']
        );
    } elseif ($_POST['accion'] === 'eliminar') {
        $controller->eliminarProducto($_POST['id_producto']);
    }
    header("Location: inventario.php");
    exit();
}


$productos = $controller->listarProductos();


// SOLUCIÓN AL ERROR: Conectamos directamente desde la vista para traer las listas de los formularios
$database = new Database();
$db_conn = $database->getConnection();


$categorias = $db_conn->query("SELECT * FROM categorias_productos ORDER BY nombre_categoria ASC")->fetchAll(PDO::FETCH_ASSOC);
$marcas = $db_conn->query("SELECT * FROM marcas_productos ORDER BY nombre_marca ASC")->fetchAll(PDO::FETCH_ASSOC);
$proveedores = $db_conn->query("SELECT id_proveedor, razon_social FROM proveedores ORDER BY razon_social ASC")->fetchAll(PDO::FETCH_ASSOC);


include __DIR__ . '/layouts/header.php';
?>
<div class="PANEL-PRINCIPAL">
    
    <?php include __DIR__ . '/layouts/sidebar.php'; ?>
    
    <div class="CONTENIDO-INVENTARIO">
        
        <?php include __DIR__ . '/layouts/navbar.php'; ?>
        
        <div class="CONTENEDOR-TABLA" style="margin-top: 20px;">
            <div class="ENCABEZADO-PANEL">
                <h2>Control de Inventario e Insumos</h2>
                <button class="BOTON BOTON-NUEVO" onclick="abrirModal('modalNuevo')">
                    + Nuevo Producto
                </button>
            </div>
            
            <table class="TABLA-DATOS">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Marca</th>
                        <th>Proveedor</th>
                        <th>Stock</th>
                        <th>Precio Venta</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($productos)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 30px; color: #888;">No hay productos registrados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($productos as $p): ?>
                            <?php 
                            $esBajo = $p['stock_actual'] <= $p['stock_minimo']; 
                            ?>
                            <tr>
                                <td>#<?php echo $p['id_producto']; ?></td>
                                <td><strong><?php echo htmlspecialchars($p['nombre_producto']); ?></strong></td>
                                <td><?php echo htmlspecialchars($p['nombre_categoria'] ?? 'General'); ?></td>
                                <td><?php echo htmlspecialchars($p['nombre_marca'] ?? 'Sin Marca'); ?></td>
                                <td><?php echo htmlspecialchars($p['nombre_proveedor'] ?? 'Particular'); ?></td>
                                <td>
                                    <span class="ALERTA-STOCK <?php echo $esBajo ? 'STOCK-BAJO' : 'STOCK-OK'; ?>">
                                        <?php echo $p['stock_actual']; ?> u.
                                    </span>
                                </td>
                                <td><strong><?php echo number_format($p['precio_venta'], 2); ?> Bs.</strong></td>
                                <td>
                                    <button class="BOTON BOTON-EDITAR" onclick="cargarYEditar(<?php echo htmlspecialchars(json_encode($p)); ?>)">Editar</button>
                                    <button class="BOTON BOTON-ELIMINAR" onclick="confirmarEliminar(<?php echo $p['id_producto']; ?>, '<?php echo htmlspecialchars($p['nombre_producto']); ?>')">Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- ================= MODAL NUEVO PRODUCTO ================= -->
<div id="modalNuevo" class="CAPA-MODAL">
    <div class="VENTANA-MODAL">
        <div class="MODAL-HEADER">
            <h3>Registrar Insumo</h3>
            <button class="MODAL-CERRAR" onclick="cerrarModal('modalNuevo')">&times;</button>
        </div>
        <form method="POST">
            <input type="hidden" name="accion" value="crear">
            <div class="MODAL-BODY">
                <div class="GRUPO-FORM">
                    <label>Nombre del Producto</label>
                    <input type="text" name="nombre_producto" class="INPUT-CONTROL" required>
                </div>
                <div class="FILA-DOBLE">
                    <div class="GRUPO-FORM">
                        <label>Categoría</label>
                        <select name="id_categoria" class="INPUT-CONTROL" required>
                            <option value="">-- Seleccionar --</option>
                            <?php foreach($categorias as $cat): ?>
                                <option value="<?php echo $cat['id_categoria']; ?>"><?php echo htmlspecialchars($cat['nombre_categoria']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="GRUPO-FORM">
                        <label>Marca</label>
                        <select name="id_marca" class="INPUT-CONTROL" required>
                            <option value="">-- Seleccionar --</option>
                            <?php foreach($marcas as $m): ?>
                                <option value="<?php echo $m['id_marca']; ?>"><?php echo htmlspecialchars($m['nombre_marca']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="GRUPO-FORM">
                    <label>Proveedor</label>
                    <select name="id_provider" class="INPUT-CONTROL" required>
                        <option value="">-- Seleccionar --</option>
                        <?php foreach($proveedores as $prov): ?>
                            <option value="<?php echo $prov['id_proveedor']; ?>"><?php echo htmlspecialchars($prov['razon_social']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="FILA-DOBLE">
                    <div class="GRUPO-FORM">
                        <label>Stock Inicial</label>
                        <input type="number" name="stock_actual" class="INPUT-CONTROL" value="0" required>
                    </div>
                    <div class="GRUPO-FORM">
                        <label>Stock Mínimo</label>
                        <input type="number" name="stock_minimo" class="INPUT-CONTROL" value="5" required>
                    </div>
                </div>
                <div class="FILA-DOBLE">
                    <div class="GRUPO-FORM">
                        <label>Costo (Bs.)</label>
                        <input type="number" step="0.01" name="precio_costo_bob" class="INPUT-CONTROL" required>
                    </div>
                    <div class="GRUPO-FORM">
                        <label>Precio Venta (Bs.)</label>
                        <input type="number" step="0.01" name="precio_venta_publico_bob" class="INPUT-CONTROL" required>
                    </div>
                </div>
            </div>
            <div class="MODAL-FOOTER">
                <button type="button" class="BOTON BOTON-CANCELAR" onclick="cerrarModal('modalNuevo')">Cancelar</button>
                <button type="submit" class="BOTON" style="background-color:#212529; color:white;">Guardar</button>
            </div>
        </form>
    </div>
</div>


<!-- ================= MODAL EDITAR PRODUCTO ================= -->
<div id="modalEditar" class="CAPA-MODAL">
    <div class="VENTANA-MODAL">
        <div class="MODAL-HEADER">
            <h3>Modificar Insumo</h3>
            <button class="MODAL-CERRAR" onclick="cerrarModal('modalEditar')">&times;</button>
        </div>
        <form method="POST">
            <input type="hidden" name="accion" value="editar">
            <input type="hidden" name="id_producto" id="edit_id">
            <div class="MODAL-BODY">
                <div class="GRUPO-FORM">
                    <label>Nombre del Producto</label>
                    <input type="text" name="nombre_producto" id="edit_nombre" class="INPUT-CONTROL" required>
                </div>
                <div class="FILA-DOBLE">
                    <div class="GRUPO-FORM">
                        <label>Categoría</label>
                        <select name="id_categoria" id="edit_cat" class="INPUT-CONTROL" required>
                            <?php foreach($categorias as $cat): ?>
                                <option value="<?php echo $cat['id_categoria']; ?>"><?php echo htmlspecialchars($cat['nombre_categoria']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="GRUPO-FORM">
                        <label>Marca</label>
                        <select name="id_marca" id="edit_marca" class="INPUT-CONTROL" required>
                            <?php foreach($marcas as $m): ?>
                                <option value="<?php echo $m['id_marca']; ?>"><?php echo htmlspecialchars($m['nombre_marca']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="GRUPO-FORM">
                    <label>Proveedor</label>
                    <select name="id_provider" id="edit_prov" class="INPUT-CONTROL" required>
                        <?php foreach($proveedores as $prov): ?>
                            <option value="<?php echo $prov['id_proveedor']; ?>"><?php echo htmlspecialchars($prov['razon_social']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="FILA-DOBLE">
                    <div class="GRUPO-FORM">
                        <label>Stock Actual</label>
                        <input type="number" name="stock_actual" id="edit_stock" class="INPUT-CONTROL" required>
                    </div>
                    <div class="GRUPO-FORM">
                        <label>Stock Mínimo</label>
                        <input type="number" name="stock_minimo" id="edit_minimo" class="INPUT-CONTROL" required>
                    </div>
                </div>
                <div class="FILA-DOBLE">
                    <div class="GRUPO-FORM">
                        <label>Costo (Bs.)</label>
                        <input type="number" step="0.01" name="precio_costo_bob" id="edit_costo" class="INPUT-CONTROL" required>
                    </div>
                    <div class="GRUPO-FORM">
                        <label>Precio Venta (Bs.)</label>
                        <input type="number" step="0.01" name="precio_venta_publico_bob" id="edit_venta" class="INPUT-CONTROL" required>
                    </div>
                </div>
            </div>
            <div class="MODAL-FOOTER">
                <button type="button" class="BOTON BOTON-CANCELAR" onclick="cerrarModal('modalEditar')">Cancelar</button>
                <button type="submit" class="BOTON" style="background-color:#212529; color:white;">Actualizar Cambios</button>
            </div>
        </form>
    </div>
</div>
<!--  MODAL ELIMINAR -->
<div id="modalEliminar" class="CAPA-MODAL">
    <div class="VENTANA-MODAL" style="max-width: 350px; text-align: center; padding: 20px;">
        <h3 style="color: #dc3545; margin-top: 0;">¿Eliminar Insumo?</h3>
        <p id="delete_texto" style="font-size: 0.95rem; color: #555;"></p>
        <form method="POST" style="margin-top: 20px; display: flex; justify-content: center; gap: 10px;">
            <input type="hidden" name="accion" value="eliminar">
            <input type="hidden" name="id_producto" id="delete_id">
            <button type="button" class="BOTON BOTON-CANCELAR" onclick="cerrarModal('modalEliminar')">Volver</button>
            <button type="submit" class="BOTON BOTON-ELIMINAR">Sí, Eliminar</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/layouts/footer.php'; ?>