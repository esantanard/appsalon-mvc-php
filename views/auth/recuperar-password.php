<h1 class="nombre-pagina">Reestablecer Password</h1>
<p class="descripcion-pagina">Coloca tu nuevo password a contuniación</p>

<?php include __DIR__ . '/../templates/alertas.php'; ?>

<?php if(!$error) /*return;*/ { ?>


    <form method="POST" class="formulario">
            <div class="campo">
            <label for="password">Password</label>
            <input 
                type="password"
                name="password"
                id="password"
                placeholder="Tu Password"    
            />    
        </div>
        <input type="submit" value="Guardar" class="btn">
    </form>
<?php } ?>
<div class="acciones">
    <a href="/">Iniciar sesión</a>
    <a href="/crear-cuenta">Aun no tienes una cuenta?</a>
</div>