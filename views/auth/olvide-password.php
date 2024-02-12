<h1 class="nombre-pagina">Restablecer <span>Contraseña</span> </h1>
<p class="descripcion-pagina">Escribe tu email para restablecer tu contraseña</p>
<?php 
    include_once __DIR__. '/../templates/alertas.php';

?>
<form action="/olvide" class="formulario" method="POST">
    <div class="campo">
        <label for="email">Correo electrónico</label>
        <input type="email" name="email" id="email">
    </div>

    <input type="submit" value="Enviar Intrucciones" class="boton">
    
    <div class="acciones">
        <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
        <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea una</a>
    </div>
</form>