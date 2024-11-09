<div class="barra">
    <p>Hola: <?php echo $nombreyapellido ?? '';?></p>
    <a href="logout" class="btn">Cerrar Sesión</a>
</div>

<?php if(isset($_SESSION['admin'])):?>
    <div class="barra-servicios">
        <a href="/admin" class="btn">Ver Citas</a>
        <a href="/servicios" class="btn">Servicios</a>
        <a href="/servicios/crear" class="btn">Nuevo Servicio</a>
    </div>
<?php  endif ?>