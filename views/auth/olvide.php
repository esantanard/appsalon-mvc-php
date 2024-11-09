<h1 class="nombre-pagina">Olvide Pagina</h1>
<p class="descripcion-pagina">Reestablece tu password </p>

<?php include __DIR__ . '/../templates/alertas.php'; ?>

<form action="/olvide" method="POST" class="formulario">
        <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email"
            name="email"
            id="email"
            placeholder="Tu email"    
        />    
    </div>
    <input type="submit" value="Enviar Instrucciones" class="btn">
</form>

<div class="acciones">
    <a href="/crear-cuenta">Aun no tienes una cuenta?</a>
    <a href="/">Iniciar sesión</a>
</div>