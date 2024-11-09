<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesión con tus datos</p>

<?php include __DIR__ . '/../templates/alertas.php'; ?>

<form action="/" method="POST" class="formulario">
    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email"
            name="email"
            id="email"
            placeholder="Tu email"    
        />
        </div>
        <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password"
            name="password"
            id="password"
            placeholder="Tu password"    
        />    
    </div>
    <input type="submit" value="Iniciar Sesion" class="btn">
</form>

<div class="acciones">
    <a href="/crear-cuenta">Aun no tienes una cuenta?</a>
    <a href="/olvide">Olvidé mi password</a>
</div>