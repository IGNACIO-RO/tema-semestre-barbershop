<?php
// Definimos la ruta de la carpeta donde están las imágenes de la galería
// Ajusta esta ruta si tu carpeta se llama diferente (ej: ./../public/img/galeria/)
$carpetaGaleria = "./../public/img/img_galeria/";

// Buscamos formatos comunes: jpg, jpeg, png y webp
// Usamos GLOB_BRACE para buscar múltiples extensiones a la vez
$imagenes = glob($carpetaGaleria . "*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}", GLOB_BRACE);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marbella Salon - Galería</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./../public/css/styles.css"> 
    <style>
        /* Estilos rápidos para que la galería se vea impecable */
        .contenedor-galeria {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 40px 5%;
            background-color: #fff;
        }
        .item-galeria {
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            aspect-ratio: 1 / 1; /* Hace que todas las celdas sean cuadradas */
        }
        .item-galeria:hover {
            transform: scale(1.03);
        }
        .item-galeria img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Recorta la imagen para que llene el espacio sin deformarse */
            display: block;
        }
        .sin-imagenes {
            grid-column: 1 / -1;
            text-align: center;
            padding: 50px;
            color: #777;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="mini-logo">
            <img src="./../public/img/logo_arriba.jpg" alt="Marbella Salon">
        </div>
        
        <nav>
            <ul>
                <li><a href="./../views/index_principal.html">Inicio</a></li>
                <li><a href="./../views/ver_peluqueros_personal.php">Estilistas</a></li>
                <li><a href="./../views/servicios_que_se_pueden_hacer.php">Servicios</a></li>
                <li><a href="./../views/galeria.php" class="active">Galería</a></li>
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

    <div class="contenedor-galeria">
        <?php 
        // Validamos si la carpeta contiene imágenes
        if ($imagenes && count($imagenes) > 0) {
            foreach ($imagenes as $imagen) {
                // Obtenemos solo el nombre del archivo para usarlo en el alt del HTML
                $nombreArchivo = basename($imagen);
                ?>
                <div class="item-galeria">
                    <img src="<?php echo $imagen; ?>" alt="<?php echo htmlspecialchars($nombreArchivo); ?>">
                </div>
                <?php
            }
        } else {
            // Mensaje en caso de que la carpeta esté vacía o la ruta esté mal
            echo "<div class='sin-imagenes'><p><i class='fas fa-images'></i> No se encontraron imágenes en la galería aún.</p></div>";
        }
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