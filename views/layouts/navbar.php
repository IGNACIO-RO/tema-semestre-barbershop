<nav class="BARRA-NAVEGACION">
    <h1 class="BARRA-TITULO">💈 Sistema de Gestión e Insumos</h1>
    
    <div class="BARRA-USUARIO-CONTENEDOR">
        <span>Usuario: <strong><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Sin Sesión'); ?></strong></span>
        <span class="ETIQUETA-ROL"><?php echo htmlspecialchars($_SESSION['rol'] ?? 'Invitado'); ?></span>
    </div>
</nav>