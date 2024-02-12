<h1 class="nombre-pagina">Crear <span>Cuenta</span></h1>
<p class="descripcion-pagina">Llena el siguiente formulario para crear una cuenta</p>

<?php 
    include_once __DIR__. '/../templates/alertas.php';

?>

<form action="/crear-cuenta" class="formulario" method="POST">
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo s($usuario->nombre)?>" placeholder="Tu nombre">
    </div>

    <div class="campo">
        <label for="apellido">Apellido</label>
        <input type="text" id="apellido" name="apellido" value="<?php echo s($usuario->apellido)?>"  placeholder="Tu apellido">
    </div>

    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input type="tel" id="telefono" name="telefono" value="<?php echo s($usuario->telefono)?>"  placeholder="Tu teléfono">
    </div>

    <div class="campo">
        <label for="email">Correo electrónico</label>
        <input type="tel" id="email" name="email" value="<?php echo s($usuario->email)?>"  placeholder="ej: laura@gmail.com">
    </div>

    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password"  placeholder="Tu contraseña">
    </div>

    <input type="submit" value="Crear Cuenta" class="boton">

    <div class="acciones">
        <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
        <a href="/olvide">¿Olvidaste tu contraseña?</a>
    </div>
</form>