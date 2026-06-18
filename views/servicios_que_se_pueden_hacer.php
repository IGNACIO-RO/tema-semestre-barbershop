 <?php
// 1. Conexión a la base de datos (Usando tu arquitectura modular)
require_once __DIR__ . '/../config/database.php';
$database = new Database();
$conexion = $database->getConnection(); // <-- Esto te devuelve un objeto PDO

// Verificar la conexión (En PDO si hay un error, salta una excepción, pero validamos que no esté vacío)
if (!$conexion) {
    die("Error de conexión a la base de datos.");
}

// 2. Hacer la consulta usando la sintaxis de PDO
$query = "SELECT id_servicio, nombre_servicio, precio_bob, duracion_estimada_min FROM servicios";
$stmt = $conexion->prepare($query); // Preparamos la consulta
$stmt->execute();                  // La ejecutamos
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marbella Salon - Inicio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./../public/css/styles.css">
    <link rel="stylesheet" href="./../public/css/ver_productos.css">
</head>
<body>
    <header>
        <div class="mini-logo">
            <img src="./../public/img/logo_arriba.jpg" alt="Marbella Salon">
        </div>
        
        <nav>
            <ul>
                <li><a href="./../views/index_principal.html" >Inicio</a></li>
                <li><a href="./../views/ver_peluqueros_personal.php">Estilistas</a></li>
                <li><a href="./../views/servicios_que_se_pueden_hacer.php" class="active">Servicios</a></li>
                <li><a href="./../views/galeria.php">Galería</a></li> <!-- este esta en duda si aponer o no poner -->
                <li><a href="./../views/ver_productos.php">Productos</a></li>
               <!-- <li><a href="./../views/reseñas_de_los_clientes.php">Reseñas</a></li> -->
            </ul>
        </nav>

        <div class="social-icons">
            <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
            <a href="#" target="_blank"><i class="fab fa-x-twitter"></i></a>
            <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
            <a href="#" target="_blank"><i class="fab fa-whatsapp"></i></a>
        </div>
    </header>
<div class="contenedor-productos">
    <?php 
    // 3. Recorrer los productos uno por uno con el fetch de PDO
    while ($servicio = $stmt->fetch(PDO::FETCH_ASSOC)) {
        
        $idServicio      = $servicio['id_servicio'];
        $nombreServicio  = $servicio['nombre_servicio'];
        $precioServicio = $servicio['precio_bob'];
        $duracionServicio = $servicio['duracion_estimada_min'];

        // Definimos la ruta base por defecto por si acaso no hay ninguna foto
        $rutaFinal = "./../public/img/img_servicios/default.png"; 

        // PHP revisa en orden cuál de los archivos existe físicamente en tu carpeta img/
        if (file_exists("./../public/img/img_servicios/$idServicio.webp")) {
            $rutaFinal = "./../public/img/img_servicios/$idServicio.webp";
        } elseif (file_exists("./../public/img/img_servicios/$idServicio.jpg")) {
            $rutaFinal = "./../public/img/img_servicios/$idServicio.jpg";
        } elseif (file_exists("./../public/img/img_servicios/$idServicio.jpeg")) { 
            $rutaFinal = "./../public/img/img_servicios/$idServicio.jpeg";
        } elseif (file_exists("./../public/img/img_servicios/$idServicio.png")) {  
            $rutaFinal = "./../public/img/img_servicios/$idServicio.png";
        }
        ?>

        <div class="tarjeta-producto">
            <div class="contenedor-img">
                <img src="<?php echo $rutaFinal; ?>" alt="<?php echo $nombreServicio; ?>" class="imagen-producto">
            </div>
            <h3><?php echo $nombreServicio; ?></h3>
            <p class="precio"><?php echo $precioServicio; ?> BOB</p>
            <p class="duracion"><?php echo $duracionServicio; ?> minutos</p>
        </div>
    <?php 
    } // Fin del ciclo while 
    ?>

</div>
    

    <footer class="main-footer">
        
        <div class="footer-top-notices">
            <a href="#" class="policy-link">VER POLÍTICA DE CANCELACIÓN Y NO REEMBOLSO</a>
            <p class="parking-notice">*Aparcamiento adicional disponible al lado, en AvantGarden, de 10:00 a 18:00.</p>
        </div>

        <div class="footer-columns">
            
            <div class="footer-col">
                <h3>LLAMA O MENSAJE DE TEXTO</h3>
                <a href="https://wa.link/0czncx" class="phone-link">(713) 523-6905</a>
                <a href="#" class="privacy-link">política de privacidad</a>
            </div>

            <div class="footer-col">
                <h3>HORARIO DE APERTURA</h3>
                <table class="schedule-table">
                    <tr>
                        <td>Lunes</td>
                        <td class="text-right">Cerrado</td>
                    </tr>
                    <tr>
                        <td>Martes - Viernes</td>
                        <td class="text-right">10:00 a. m. - 20:00</td>
                    </tr>
                    <tr>
                        <td>Sábado</td>
                        <td class="text-right">10:00 a. m. - 18:00</td>
                    </tr>
                    <tr>
                        <td>Domingo</td>
                        <td class="text-right">12:00 p. m. - 18:00</td>
                    </tr>
                </table>
            </div>

            <div class="footer-col">
                <h3>NUESTRA DIRECCIÓN</h3>
                <p class="address-text">
                    415 Westheimer Rd #210<br>
                    Houston, TX 77006
                </p>
            </div>
            
        </div>

        <div class="footer-bottom">
            <div class="footer-social">
                <a href="#" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" target="_blank" title="X (Twitter)"><i class="fab fa-x-twitter"></i></a>
            </div>
            <a href="#reservas" class="footer-btn-reserve">RESERVA AHORA</a>
        </div>
    </footer>
</body>
</html>
<?php
// 4. En PDO no se usa mysqli_close. Para cerrar la conexión simplemente limpiamos las variables
$stmt = null;
$conexion = null;
?>