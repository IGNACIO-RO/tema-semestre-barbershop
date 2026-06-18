function abrirModal(idModal) {
    document.getElementById(idModal).style.display = 'flex';
}

function cerrarModal(idModal) {
    document.getElementById(idModal).style.display = 'none';
}

function cargarYEditar(producto) {
    document.getElementById('edit_id').value = producto.id_producto;
    document.getElementById('edit_nombre').value = producto.nombre_producto;
    document.getElementById('edit_stock').value = producto.stock_actual;
    document.getElementById('edit_minimo').value = producto.stock_minimo;
    document.getElementById('edit_costo').value = producto.precio_costo_bob;
    document.getElementById('edit_venta').value = producto.precio_venta;
    
    document.getElementById('edit_cat').value = producto.id_categoria;
    document.getElementById('edit_marca').value = producto.id_marca;
    document.getElementById('edit_prov').value = producto.id_proveedor;

    abrirModal('modalEditar');
}

function confirmarEliminar(id, nombre) {
    document.getElementById('delete_id').value = id;
    document.getElementById('delete_texto').innerText = 'Vas a borrar definitivamente: ' + nombre;
    abrirModal('modalEliminar');
}