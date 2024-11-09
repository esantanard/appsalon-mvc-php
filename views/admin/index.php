<h1 class="nombre-pagina">Panel de Administración</h1>

<?php include_once __DIR__ . '/../templates/barra.php';?>

<h2>Buscar Citas</h2>
<div class="busqueda">
    <form action="" class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" id="fecha" value=<?php echo $fecha; ?> />
        </div>
    </form>
</div>

<?php 
//count Cuenta las posiciones de un arreglo
    if(count($citas) === 0 ){ 
    echo '<h2>No hay citas para esta fecha';
    }?>

<div id="citas-admin">
    <ul class="citas">
        <?php 
            $idCita = 0;
            //Registrando el indica $key para saber cuando llegue al ultimo registro de esa cita
            foreach($citas as $key => $cita ){
                if($idCita !== $cita->id){
                    //Inicio el total a pagar en 0, dentro del if
                    $total = 0; ?>
                    <li>
                        <p>Nombre: <span><?php echo $cita->cliente;?></span></p>
                        <p>Hora: <span><?php echo $cita->hora;?></span></p>
                        <p>Email: <span><?php echo $cita->email;?></span></p>
                        <p>Telefono: <span><?php echo $cita->telefono;?></span></p>
                        <h3>Servicios: </h3> 
                    <?php 
                        $idCita = $cita->id; 
                }
                $total += $cita->precio;
                ?>
                <p class="servicios"><?php echo $cita->servicio . ' ' . '$' . $cita->precio;?></p>
                <?php 
                    //Recorremos los IDs de la base de datos
                    $actual = $cita->id;
                    //Para ir un registro delante cuando se este iterando en la base de datos
                    $proximo = $citas[$key + 1]->id ?? 0;

                    if(esUltimo($actual, $proximo)){ ?>
                        <p class="total">Total a pagar : <span>$<?php echo number_format($total, 2) ;?> </span> </p> 
                
                    <form action="/api/eliminar" method="POST">
                        <input type="hidden" name="id" value="<?php echo $cita->id;?>">
                        <input type="submit" value="Eliminar" class="btn-eliminar">
                    </form>
                <?php }
            } 
        ?>
    </ul>
    
</div>

<?php $script = "<script src='build/js/buscador.js'></script>";?>