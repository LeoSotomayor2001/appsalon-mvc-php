<h1 class="nombre-pagina">Recuperar <span>Contraseña</span></h1>
<p class="descripcion-pagina">Coloca tu nuevo password a continuacion:</p>
<?php 
    include_once __DIR__. '/../templates/alertas.php';

?>
<?php if($error) return; ?>
<form class="formulario" method="POST">
    <div class="campo">
        <label for="password">Contraseña</label>
        <input 
            type="password" 
            name="password" 
            id="password"
        >
    </div>
    <input type="submit" value="Guardar" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes cuenta? Inicia sesión</a>
</div>