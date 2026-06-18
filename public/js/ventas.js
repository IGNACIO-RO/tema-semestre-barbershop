let carrito = [];

function agregarAlCarrito(id, nombre, precio, stockMax) {
    let existe = carrito.find(item => item.id === id);
    
    if (existe) {
        if (existe.cantidad < stockMax) {
            existe.cantidad++;
        } else {
            alert("No puedes agregar más unidades que las disponibles en el stock.");
        }
    } else {
        carrito.push({
            id: id,
            nombre: nombre,
            precio: parseFloat(precio),
            cantidad: 1,
            stockMax: stockMax
        });
    }
    renderizarCarrito();
}

function cambiarCantidad(id, nuevaCantidad) {
    let item = carrito.find(item => item.id === id);
    if (item) {
        let cant = parseInt(nuevaCantidad);
        if (cant > item.stockMax) {
            alert("El stock actual de este producto es de " + item.stockMax + " u.");
            item.cantidad = item.stockMax;
        } else if (cant < 1 || isNaN(cant)) {
            item.cantidad = 1;
        } else {
            item.cantidad = cant;
        }
    }
    renderizarCarrito();
}

function eliminarDelCarrito(id) {
    carrito = carrito.filter(item => item.id !== id);
    renderizarCarrito();
}

function renderizarCarrito() {
    const tbody = document.getElementById('items-carrito');
    tbody.innerHTML = '';
    
    if (carrito.length === 0) {
        tbody.innerHTML = `<tr id="carrito-vacio"><td colspan="4" style="text-align: center; padding: 30px; color: #888;">Ningún producto añadido.</td></tr>`;
        calcularTotales();
        return;
    }
    
    carrito.forEach(item => {
        let subtotalItem = item.precio * item.cantidad;
        let fila = document.createElement('tr');
        fila.innerHTML = `
            <td>
                <input type="number" class="INPUT-CANTIDAD" value="${item.cantidad}" min="1" max="${item.stockMax}" onchange="cambiarCantidad(${item.id}, this.value)">
            </td>
            <td>${item.nombre}</td>
            <td><strong>${subtotalItem.toFixed(2)} Bs.</strong></td>
            <td>
                <button type="button" class="BOTON-ACCION BTN-QUITAR" onclick="eliminarDelCarrito(${item.id})">&times;</button>
            </td>
        `;
        tbody.appendChild(fila);
    });
    
    calcularTotales();
}

function calcularTotales() {
    let subtotal = 0;
    carrito.forEach(item => {
        subtotal += item.precio * item.cantidad;
    });
    
    let descInput = document.getElementById('input-descuento').value;
    let descuento = parseFloat(descInput) || 0;
    if (descuento < 0) descuento = 0;
    
    let total = subtotal - descuento;
    if (total < 0) total = 0;
    
    document.getElementById('txt-subtotal').innerText = subtotal.toFixed(2);
    document.getElementById('txt-total').innerText = total.toFixed(2);
}

function filtrarTablaProductos() {
    let input = document.getElementById('buscar_producto').value.toLowerCase();
    let filas = document.getElementsByClassName('fila-producto-busqueda');
    
    for (let i = 0; i < filas.length; i++) {
        let nombreProd = filas[i].getElementsByClassName('nombre-prod-buscar')[0].innerText.toLowerCase();
        if (nombreProd.includes(input)) {
            filas[i].style.display = "";
        } else {
            filas[i].style.display = "none";
        }
    }
}

function prepararEnvio(event) {

    if (carrito.length === 0) {

        event.preventDefault();

        alert("Debes añadir al menos un producto.");

        return;
    }

    let cliente =
        document.getElementById('id_cliente').value;

    if(cliente === ""){

        event.preventDefault();

        alert("Seleccione un cliente.");

        return;
    }

    let metodo =
        document.getElementById('metodo_pago').value;

    if(metodo === ""){

        event.preventDefault();

        alert("Seleccione un método de pago.");

        return;
    }

    document.getElementById('carrito_datos').value =
        JSON.stringify(carrito);

    document.getElementById('id_cliente_hidden').value =
        cliente;
}
function mostrarMetodoPago(){

    let metodo =
        document.getElementById('metodo_pago').value;

    let contenedor =
        document.getElementById('contenedor_pago');

    let qr =
        document.getElementById('panel_qr');

    let tarjeta =
        document.getElementById('panel_tarjeta');

    qr.style.display = "none";
    tarjeta.style.display = "none";

    if(metodo === "QR"){

        contenedor.style.display = "block";

        qr.style.display = "block";

    }
    else if(metodo === "TARJETA"){

        contenedor.style.display = "block";

        tarjeta.style.display = "block";

    }
    else{

        contenedor.style.display = "none";
    }
}