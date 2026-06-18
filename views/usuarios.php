<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) { header("Location: login.php"); exit(); }
require_once __DIR__ . '/../controllers/UsuariosController.php';
$controller = new UsuariosController();
$usuarios = $controller->listarUsuarios();
$roles = $controller->listarRoles();
$generos = $controller->listarGeneros();
include __DIR__ . '/layouts/header.php';
?>
<div class="PANEL-PRINCIPAL">
<?php include __DIR__ . '/layouts/sidebar.php'; ?>
<div class="CONTENIDO-INVENTARIO">
<?php include __DIR__ . '/layouts/navbar.php'; ?>
<div class="CONTENEDOR-TABLA" style="margin-top:20px;">
<div class="ENCABEZADO-PANEL">
    <h2>Gestión de Usuarios</h2>
    <button class="BOTON BOTON-NUEVO" onclick="abrirModal('modalNuevo')">+ Nuevo Usuario</button>
</div>
<table class="TABLA-DATOS">
<thead>
<tr>
    <th>ID</th><th>Nombre</th><th>CI</th><th>Correo</th><th>Rol</th><th>Estado</th><th>Acciones</th>
</tr>
</thead>
<tbody>
<?php foreach($usuarios as $u): ?>
<tr>
<td>#<?= $u['id_usuario']; ?></td>
<td><?= htmlspecialchars($u['nombre_completo']); ?></td>
<td><?= htmlspecialchars($u['ci']); ?></td>
<td><?= htmlspecialchars($u['correo']); ?></td>
<td><?= htmlspecialchars($u['nombre_rol']); ?></td>
<td>
<?php if($u['estado_active']): ?>
<span style="color:green;font-weight:bold;">Activo</span>
<?php else: ?>
<span style="color:red;font-weight:bold;">Inactivo</span>
<?php endif; ?>
</td>
<td>
<button class="BOTON BOTON-EDITAR" onclick='cargarEditar(<?= json_encode($u); ?>)'>Editar</button>
<form method="POST" action="../controllers/UsuariosController.php" style="display:inline;">
<input type="hidden" name="action" value="cambiar_estado">
<input type="hidden" name="id_usuario" value="<?= $u['id_usuario']; ?>">
<input type="hidden" name="estado" value="<?= $u['estado_active'] ? 0 : 1; ?>">
<button type="submit" class="BOTON BOTON-ELIMINAR"><?= $u['estado_active'] ? 'Desactivar' : 'Activar'; ?></button>
</form>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</div>
</div>

<div id="modalNuevo" class="CAPA-MODAL">
<div class="VENTANA-MODAL">
<div class="MODAL-HEADER">
    <h3>Nuevo Usuario</h3>
    <button class="MODAL-CERRAR" onclick="cerrarModal('modalNuevo')">&times;</button>
</div>
<form method="POST" action="../controllers/UsuariosController.php">
<input type="hidden" name="action" value="registrar_usuario">
<div class="MODAL-BODY">
    <div class="GRUPO-FORM"><label>Nombre Completo</label><input type="text" name="nombre_completo" class="INPUT-CONTROL" required></div>
    <div class="FILA-DOBLE">
        <div class="GRUPO-FORM"><label>CI</label><input type="text" name="ci" class="INPUT-CONTROL" required></div>
        <div class="GRUPO-FORM"><label>Celular</label><input type="text" name="celular" class="INPUT-CONTROL"></div>
    </div>
    <div class="GRUPO-FORM"><label>Correo</label><input type="email" name="correo" class="INPUT-CONTROL" required></div>
    <div class="GRUPO-FORM"><label>Contraseña</label><input type="password" name="password" class="INPUT-CONTROL" required></div>
    <div class="FILA-DOBLE">
        <div class="GRUPO-FORM"><label>Rol</label>
        <select name="id_rol" class="INPUT-CONTROL" required>
            <?php foreach($roles as $r): ?><option value="<?= $r['id_rol']; ?>"><?= htmlspecialchars($r['nombre_rol']); ?></option><?php endforeach; ?>
        </select>
        </div>
        <div class="GRUPO-FORM"><label>Género</label>
        <select name="id_genero" class="INPUT-CONTROL" required>
            <?php foreach($generos as $g): ?><option value="<?= $g['id_genero']; ?>"><?= htmlspecialchars($g['nombre_genero']); ?></option><?php endforeach; ?>
        </select>
        </div>
    </div>
</div>
<div class="MODAL-FOOTER">
    <button type="button" class="BOTON BOTON-CANCELAR" onclick="cerrarModal('modalNuevo')">Cancelar</button>
    <button type="submit" class="BOTON" style="background:#212529;color:white;">Guardar</button>
</div>
</form>
</div>
</div>

<div id="modalEditar" class="CAPA-MODAL">
<div class="VENTANA-MODAL">
<div class="MODAL-HEADER">
    <h3>Editar Usuario</h3>
    <button class="MODAL-CERRAR" onclick="cerrarModal('modalEditar')">&times;</button>
</div>
<form method="POST" action="../controllers/UsuariosController.php">
<input type="hidden" name="action" value="editar_usuario"><input type="hidden" name="id_usuario" id="edit_id">
<div class="MODAL-BODY">
    <div class="GRUPO-FORM"><label>Nombre</label><input type="text" id="edit_nombre" name="nombre_completo" class="INPUT-CONTROL"></div>
    <div class="GRUPO-FORM"><label>CI</label><input type="text" id="edit_ci" name="ci" class="INPUT-CONTROL"></div>
    <div class="GRUPO-FORM"><label>Celular</label><input type="text" id="edit_celular" name="celular" class="INPUT-CONTROL"></div>
    <div class="GRUPO-FORM"><label>Correo</label><input type="email" id="edit_correo" name="correo" class="INPUT-CONTROL"></div>
</div>
<div class="MODAL-FOOTER">
    <button type="button" class="BOTON BOTON-CANCELAR" onclick="cerrarModal('modalEditar')">Cancelar</button>
    <button type="submit" class="BOTON" style="background:#212529;color:white;">Actualizar</button>
</div>
</form>
</div>
</div>

<script>
function cargarEditar(usuario){
    document.getElementById('edit_id').value = usuario.id_usuario;
    document.getElementById('edit_nombre').value = usuario.nombre_completo;
    document.getElementById('edit_ci').value = usuario.ci;
    document.getElementById('edit_celular').value = usuario.celular;
    document.getElementById('edit_correo').value = usuario.correo;
    abrirModal('modalEditar');
}
function abrirModal(id) { document.getElementById(id).style.display = "flex"; }
function cerrarModal(id) { document.getElementById(id).style.display = "none"; }
</script>
<?php include __DIR__ . '/layouts/footer.php'; ?>