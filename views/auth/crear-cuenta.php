<h1 class="nombre-pagina">Crear cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario</p>

<?php include __DIR__ . '/../templates/alertas.php'; ?>

<form action="/crear-cuenta" class="formulario" method="POST">
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input type="text"
            name="nombre"
            id="nombre"
            placeholder="Tu nombre"
            value="<?php echo s($usuario->nombre); ?>"
        >
    </div>

    <div class="campo">
        <label for="apellido">Apellido</label>
        <input type="text"
            name="apellido"
            id="apellido"
            placeholder="Tu apellido"
             value="<?php echo s($usuario->apellido) ; ?>"
        >
    </div>
    <div class="campo">
        <label for="telefono">Telefono</label>
        <input type="tel"
            name="telefono"
            id="telefono"
            placeholder="Tu telefono"
             value="<?php echo s($usuario->telefono);?>"
        >
    </div>
    <div class="campo">
        <label for="email">Email</label>
        <input type="email"
            name="email"
            id="email"
            placeholder="Tu email"
             value="<?php echo s($usuario->email);?>"
        >
    </div>
    <div class="campo">
        <label for="password">Password</label>
        <input type="password"
            name="password"
            id="password"
            placeholder="Tu password"
        >
    </div>
    <input type="submit" value="Crear Cuenta" class="btn">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes cuenta?</a>
    <a href="/olvide">Olvidé mi password</a>
</div>