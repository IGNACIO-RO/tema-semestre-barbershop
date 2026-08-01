function cambiarSesionCaja(idCaja)
{
    if(idCaja)
    {
        window.location.href = "caja.php?id_caja=" + idCaja;
    }
}

function abrirModalArqueo()
{
    let monto = prompt("Ingrese el monto real contado en caja:");

    if(monto !== null && monto !== "")
    {
        const idCaja = document.getElementById("filtro_caja").value;

        window.location.href =
        "../controllers/CajaController.php?action=arqueo&id_caja=" +
        idCaja +
        "&monto_real=" + monto;
    }
}

function confirmarCierreDefinitivo()
{
    if(confirm("¿Está seguro de cerrar la caja definitivamente?"))
    {
        const idCaja = document.getElementById("filtro_caja").value;

        window.location.href =
        "../controllers/CajaController.php?action=cerrar_caja&id_caja=" +
        idCaja;
    }
}