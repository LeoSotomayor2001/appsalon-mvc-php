<h1 class="nombre-pagina">Actualizar <span>Servicios</span></h1>
<p class="descripcion-pagina">Modifica los valores del formulario</p>

<?php

    include_once __DIR__ . '/../templates/alertas.php';
    include_once __DIR__ . '/../templates/barra.php';

?>

<form method="POST" class="formulario">
    <?php include __DIR__ . '/formulario.php' ?>
    <input type="submit" value="Actualizar" class="boton">
</form>