let paso = 1;
let pasoInicial = 1;
let pasoFinal = 3;

const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener("DOMContentLoaded", function(){
    iniciarApp();
});

function iniciarApp(){
    mostrarSeccion(); //
    tabs(); //
    botonesPaginador(); //Agrga o quita botones paginador
    paginaAnterior();
    paginaSiguiente();
    consultarAPI(); //Consulta la API en el backend de PHP
    idCliente();
    nombreCliente(); //Añade nombre cliente al objeto cita
    seleccionarFecha(); //Añade la fecha al objeto cita
    seleccionarHora(); //Añade la hora al objeto cita
    mostrarResumen(); //Muestra el resumen de la cita
}

function mostrarSeccion(){
    //OCultar la seccion que se esta mostrando
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior){
        seccionAnterior.classList.remove('mostrar');
    }

    const seccion = document.querySelector(`#paso-${paso}`);
    seccion.classList.add('mostrar');

    //Quitar la clase actual al tab anterior
    const tabAnterior = document.querySelector('.actual');
    if(tabAnterior){
        tabAnterior.classList.remove('actual');

    }

    //Resalta tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual')
}

function tabs(){
    const botones = document.querySelectorAll('.tabs button')

    botones.forEach((boton) => {
        boton.addEventListener('click', function(e){
            paso = parseInt(e.target.dataset.paso);
            mostrarSeccion();
            botonesPaginador();
        });
    })
}

function botonesPaginador(){
    const paginaAnterior = document.querySelector("#anterior");
    const paginaSiguiente = document.querySelector("#siguiente");

    if(paso === 1){
        paginaAnterior.classList.add('hidden');
        paginaSiguiente.classList.remove('hidden');

    }else if(paso === 3){
        paginaAnterior.classList.remove('hidden');
        paginaSiguiente.classList.add('hidden');
        mostrarResumen();
    }else{
        paginaAnterior.classList.remove('hidden');
        paginaSiguiente.classList.remove('hidden');


    }

    mostrarSeccion();
}

function paginaAnterior(){
    const paginaAnterior = document.querySelector("#anterior");
    paginaAnterior.addEventListener('click', function(){
        if(paso <= pasoInicial) return;
        paso--;
        botonesPaginador();
    })
}

function paginaSiguiente(){
    const paginasiguiente = document.querySelector("#siguiente");
    paginasiguiente.addEventListener('click', function(){
        if(paso >= pasoFinal) return;
        paso++;
        botonesPaginador();
    })
}

async function consultarAPI(){
    try {
        const url = '/api/servicios';
        //const url = `${location.origin}/api/servicios';
        const resultado = await fetch(url);
        const servicios = await resultado.json()
        mostrarServicios(servicios);
    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios){
    const divServicios = document.querySelector("#servicios");
    servicios.forEach( servicio => {
        const { id, nombre, precio } = servicio;

        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = function (){
            selectServicio(servicio);
        };

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        divServicios.appendChild(servicioDiv);
    });

}

function selectServicio(servicio){
    const { id } = servicio;
    //agregar objeto al objeto cita
    const {servicios } = cita;

    //Identificar elemento al que se le da click
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    //comprobar si un servicio ya fue agregado
    //.some verifica si en un arreglo ya esta un elemento
    if(servicios.some( agregado => agregado.id === id)){
        //Si ya esta agregado hay que eliminarlo
        cita.servicios = servicios.filter(agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado');
    }else{
        //Aqui se crea una copia del arreglo de cita, y se selecciona la llave servicios y se le agrega el servicio
        //... spread operator
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');
    }
}

function idCliente(){
    cita.id = document.querySelector('#id').value;
}

function nombreCliente(){
    cita.nombre = document.querySelector('#nombre').value;
}

function seleccionarFecha(){
    const inputFecha = document.querySelector('#fecha');

    inputFecha.addEventListener('input', function(e){
        //prevenir que se seleccione sabado y domingo
        const dia = new Date(inputFecha.value).getUTCDay();

        // busca dentro de un arreglo - Primero el arreglo y luego el valor contra el cual va a comparar
        if([6,0].includes(dia)){
            e.target.value = '';
            mostrarAlerta('Fines de semana no permitidos', 'error', '#paso-2 p');
        }else{
            cita.fecha = e.target.value;
        }
    })
}

function seleccionarHora(){
    let inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function(e){
        const horaCita = e.target.value;
        const hora = horaCita.split(':')[0];
        if(hora < 10 || hora > 18){
            e.target.value = '';
            mostrarAlerta("Horas no validas", "error", '#paso-2 p');
        }else{
            cita.hora = e.target.value;

        }
    })
}

function mostrarAlerta(mensaje, tipo, elemento, duracion = true){

    //Previene agregar mas de una alerta
    const alertaPrevia = document.querySelector('.alerta');
    if(alertaPrevia) {
        alertaPrevia.remove();
    };

    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    if(duracion){
        setTimeout(() => {
            alerta.remove();
         }, 3000);
    }
}

function mostrarResumen(){
    const resumen = document.querySelector('.contenido-resumen');

    //Limpiar contenido resumen
    while(resumen.firstChild){
        resumen.removeChild(resumen.firstChild);
    }

    //Object.values() Itera sobre un objeto
    if(Object.values(cita).includes('') || cita.servicios.length === 0){
        mostrarAlerta('Faltan datos de servicios, o infomracion cita', 'error', '.contenido-resumen', false);

        return;
    }

    //Formatear DIV resumen
    const { nombre, fecha, hora, servicios } = cita;

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre Cliente: </span> ${nombre}`;

    //Formatear Fecha
    //cada que uses Date() se atrasa un dia y un mes a la fecha
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 2;
    const year = fechaObj.getFullYear();

    const fechaUTC = new Date(Date.UTC(year, mes, dia));

    //Formatea la fecha al idioma deseada
    const opciones = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'}
    const fechaFormateada= fechaUTC.toLocaleDateString('es-Es', opciones);
    //console.log(fechaFormateada);

    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha: </span> ${fechaFormateada}`;

    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Fecha: </span> ${hora}`;

    const textService = document.createElement('P');
    textService.textContent = 'Servicios: ';

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    resumen.appendChild(textService);

    servicios.forEach(servicio => {

        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = servicio.nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio:</span> ${servicio.precio}` ;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);
        resumen.appendChild(contenedorServicio);

    })

    const botonReservar = document.createElement('BUTTON')
    botonReservar.classList.add('btn');
    botonReservar.textContent = "Reservar Cita";
    //Llamar una funcion, para pasar un parametro hay que usar function(){reservarCita(id)}
    botonReservar.onclick = reservarCita;

    resumen.appendChild(botonReservar);
}

async function reservarCita(){

    const { id, nombre, fecha, hora, servicios } = cita;

    //.forEach solo itera
    //.map las coindicencias las va agregando a la variable
    const idServicios = servicios.map( servicio => servicio.id );

    // console.log(idServicios);
    // return

    //aqui almacenamos la informacion que se va a enviar por fetch
    //El metodo encargado es FormData()
    const datos = new FormData();
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('usuarioId', id);
    datos.append('servicios', idServicios);

    //console.log([...datos]);return
    try {
        //peticion hacia la api
        const url = '/api/citas';
        //el segundo parametro es obligatorio cuando la peticion es de tipo POST
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });

        const resultado = await respuesta.json();

        //.resultado viene de ActiveRecord en el metodo crear
        //console.log(resultado.resultado)

        if(resultado.resultado){
            Swal.fire({
                icon: "success",
                title: "Cita Creada",
                text: "Tu cita fue creada!",
                //button: 'OK'
            }).then(()=>{
                setTimeout(() => {
                    window.location.reload();   
                }, 3000);
            })
        }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: " Hubo un error al guardar la cita!"
          });
    }
    

    //postman
    //console.log([...datos]);
}