function cambiarSesionCaja(idCaja) {
    if(idCaja) {
        // Recarga la página inyectando el ID por método GET para refrescar la auditoría
        window.location.href = "caja.php?id_caja=" + idCaja;
    }
}

function abrirModalArqueo() {
    alert("Calculando: Efectivo Real en Mano vs Efectivo del Sistema");
}

function confirmarCierreDefinitivo() {
    if(confirm("¿Estás seguro de cerrar la caja de manera definitiva? No se podrán registrar más ventas hasta mañana.")) {
        alert("Caja guardada con estado: CERRADA.");
    }
}

function abrirModalArqueo()
{
    let monto = prompt(
        "Ingrese el monto real contado en caja:"
    );

    if(monto !== null && monto !== "")
    {
        window.location.href =
        "../controllers/CajaController.php?action=arqueo&id_caja=<?php echo $id_caja_seleccionada; ?>&monto_real=" + monto;
    }
}

function cambiarSesionCaja(idCaja)
{
    window.location.href =
    "caja.php?id_caja=" + idCaja;
}

