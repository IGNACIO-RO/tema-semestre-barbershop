<?php
// 1. Conexión a la base de datos (Usando tu arquitectura modular)
require_once __DIR__ . '/../config/database.php';
$database = new Database();
$conexion = $database->getConnection(); // <-- Devuelve el objeto PDO

// Verificar la conexión
if (!$conexion) {
    die("Error de conexión a la base de datos.");
}

// 2. Consulta SQL para traer solo a los barberos (rol 3)
$query = "SELECT id_usuario, nombre_completo, ci FROM usuarios WHERE id_rol = 3";
// Nota: Ajusta los nombres de las columnas (como apellido_usuario o especialidad) si en tu tabla se llaman distinto.

$stmt = $conexion->prepare($query);
$stmt->execute();                  
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marbella Salon - Nuestros Estilistas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./../public/css/styles.css">
    <link rel="stylesheet" href="./../public/css/css_opciones/ver_peluqueros_personal.css">
</head>
<body>
    <header>
        <div class="mini-logo">
            <img src="./../public/img/logo_arriba.jpg" alt="Marbella Salon">
        </div>
        
        <nav>
            <ul>
                <li><a href="./../views/index_principal.html">Inicio</a></li>
                <li><a href="./../views/ver_peluqueros_personal.php" class="active">Estilistas</a></li>
                <li><a href="./../views/servicios_que_se_pueden_hacer.php">Servicios</a></li>
                <li><a href="./../views/galeria.php">Galería</a></li>
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

    <div class="contenedor-barberos">
        <?php 
        // 3. Recorrer los barberos devueltos por la base de datos
        while ($barbero = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
            $idUsuario       = $barbero['id_usuario'];
            $nombreUsuario   = $barbero['nombre_completo']; // He asumido que tu tabla tiene una columna llamada nombre_completo, si no es así, ajusta este nombre.
            // Asegúrate de que estas columnas existan en tu BD o inicialízalas vacías si no las usas aún:
            $ci                = isset($barbero['ci']) ? $barbero['ci'] : '';

            $nombreCompleto  = $nombreUsuario . " " . $ci;

            // Definimos la foto por defecto para los barberos que no tengan foto subida
            $rutaFotoBarbero = "./../public/img/img_peluqueros/default_avatar.png"; 

            // Buscamos la foto del barbero por su id_usuario en tu carpeta seleccionada
            // He asumido que crearás una carpeta llamada "img_peluqueros", puedes cambiar la ruta si gustas.
            if (file_exists("./../public/img/img_peluqueros/$idUsuario.webp")) {
                $rutaFotoBarbero = "./../public/img/img_peluqueros/$idUsuario.webp";
            } elseif (file_exists("./../public/img/img_peluqueros/$idUsuario.jpg")) {
                $rutaFotoBarbero = "./../public/img/img_peluqueros/$idUsuario.jpg";
            } elseif (file_exists("./../public/img/img_peluqueros/$idUsuario.jpeg")) { 
                $rutaFotoBarbero = "./../public/img/img_peluqueros/$idUsuario.jpeg";
            } elseif (file_exists("./../public/img/img_peluqueros/$idUsuario.png")) {  
                $rutaFotoBarbero = "./../public/img/img_peluqueros/$idUsuario.png";
            }
            ?>

            <div class="tarjeta-barbero">
                <img src="<?php echo $rutaFotoBarbero; ?>" alt="Barbero <?php echo htmlspecialchars($nombreCompleto); ?>" class="avatar-barbero">
                <h3><?php echo htmlspecialchars($nombreCompleto); ?></h3>
                <p class="ci">CI: <?php echo htmlspecialchars($ci); ?></p>
                <a href="login.php" class="btn-perfil">Reservar con él</a>
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
            <a href="mis_citas.php" class="footer-btn-reserve">RESERVA AHORA</a>
        </div>
    </footer>
</body>
</html>
<?php
// 4. Limpieza de variables PDO
$stmt = null;
$conexion = null;
?>