let paso=1;
let pasoInicial=1;
let pasoFinal=3;

const cita={
    id:'',
    nombre:'',
    fecha:'',
    hora:'',
    servicios:[]
}

document.addEventListener('DOMContentLoaded', ()=>{
    iniciarApp();
});

function iniciarApp(){
    mostrarSeccion();
    tabs();//cambia la seccion cuando se presionen los tabs
    botonesPaginador();//agrega o quita los botones del paginador
    paginaSiguiente();
    paginaAnterior();

    consultarAPI();// Consulta la APi en el backend

    idCliente();
    nombreCliente();//añade el nombre del cliente al objeto de cita
    seleccionarFecha();
    seleccionarHora();
    mostrarResumen();
}
function mostrarSeccion() {

    // Ocultar la sección que tenga la clase de mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior) {
        seccionAnterior.classList.remove('mostrar');
    }

    // Seleccionar la sección con el paso...
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');

    // Quita la clase de actual al tab anterior
    const tabAnterior = document.querySelector('.actual');
    if(tabAnterior) {
        tabAnterior.classList.remove('actual');
    }

    // Resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');
}

function tabs(){
    const botones=document.querySelectorAll('.tabs button');
    
    botones.forEach( boton=>{
        boton.addEventListener('click',(e)=>{
            paso=parseInt(e.target.dataset.paso);
            mostrarSeccion();
            botonesPaginador();

        })
    })
}
function botonesPaginador() {
    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');

    if(paso === 1) {
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    } else if (paso === 3) {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');
        mostrarResumen();
        
    } else {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }

    mostrarSeccion();
}

function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function() {

        if(paso <= pasoInicial) return;
        paso--;
        
        botonesPaginador();
    })
}
function paginaSiguiente() {
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function() {

        if(paso >= pasoFinal) return;
        paso++;
        
        botonesPaginador();
    })
}

async function consultarAPI(){
    try {
        const url=`${location.origin}/api/servicios`;
        const resultado=await fetch(url);
        const servicios= await resultado.json();
        
        mostrarServicios(servicios);
    } 
    catch (error) {
        console.log(error)
    }
}
function mostrarServicios(servicios){

    servicios.forEach(servicio =>{
        const {id,nombre,precio}=servicio;

        const nombreServicio=document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent=nombre;

        const precioServicio=document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent="$" +precio;

        const servicioDiv=document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio=id;
        servicioDiv.onclick=function(){
            seleccionarServicio(servicio)
        }

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);
        
        document.querySelector('#servicios').appendChild(servicioDiv);


    });
}
function seleccionarServicio(servicio){
    const {id}=servicio;
    const {servicios}=cita;
    //identificar el elemento al que se le da click
    const divServicio=document.querySelector(`[data-id-servicio="${id}"]`);
    //confirmar si un servicio ya fue agregado
    if(servicios.some(agregado => agregado.id === id)){
        //si ya esta agregado, se elimina
        cita.servicios=servicios.filter(agregado=> agregado.id !== id);
        divServicio.classList.remove('seleccionado');
    }
    else{
        //agregarlo
        cita.servicios=[...servicios,servicio];
        divServicio.classList.add('seleccionado');
    }

   
    
    //console.log(cita)
}
function idCliente(){
    cita.id=document.querySelector('#id').value;
}
function nombreCliente(){
    cita.nombre=document.querySelector('#nombre').value;

    
}
function seleccionarFecha(){
    const inputFecha=document.querySelector('#fecha');
    inputFecha.addEventListener('input',function(e){

        const dia=new Date(e.target.value).getUTCDay();
        if([6,0].includes(dia)){
            e.target.value='';
            mostrarAlerta('Fines de semana no permitidos','error','.formulario');
        }
        else{
            cita.fecha=e.target.value;
        }
    })
}
function seleccionarHora(){
    const inputHora=document.querySelector('#hora');
    inputHora.addEventListener('input',function(e){
        const horaCita=e.target.value;
        const hora=horaCita.split(":")[0];
        if(hora<10 || hora >18){
            e.target.value='';
            mostrarAlerta('Estamos disponibles desde las 10 am hasta las 6 pm','error','.formulario');
        }
        else{
            cita.hora=e.target.value;
        }
    });
}
function mostrarAlerta(mensaje,tipo,elemento,desaparece=true){
    //previene que se genere mas de una alerta
    const alertaPrevia=document.querySelector('.alerta');
    if(alertaPrevia) {
        alertaPrevia.remove()
    }


    //scripting para crear la alerta
    const alerta=document.createElement('DIV');
    alerta.textContent=mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    const referencia=document.querySelector(elemento);
    referencia.appendChild(alerta);
    //eliminar la alerta
    if(desaparece){
        setTimeout(() => {
        
            alerta.remove();
        }, 2500);
    }
   
}
function mostrarResumen(){
    const resumen=document.querySelector('.contenido-resumen');
   
    //Limpiar el contenido de resumen
    while(resumen.firstChild){
        resumen.removeChild(resumen.firstChild);
    }
    if(Object.values(cita).includes('') || cita.servicios.length===0){
        mostrarAlerta('Hacen falta datos o que seleccione un servicio','error','.contenido-resumen',false);
        return;
    }
    
    //FORMATEAR EL DIV DE RESUMEN
    const {nombre,fecha,hora,servicios}=cita;

    //HEADING PARA SERVICIOS EN RESUMEN
    const headingServicios=document.createElement('H3');
    headingServicios.textContent='Resumen de Servicios';
    resumen.appendChild(headingServicios);

    //ITERANDO Y MOSTRANDO LOS SERVICIOS
    servicios.forEach(servicio =>{
        const {id,precio,nombre}=servicio;

        const contenedorServicio=document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio=document.createElement('P');
        textoServicio.textContent=nombre;

        const precioServicio=document.createElement('P');
        precioServicio.innerHTML=`<span>Precio:</span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    });

    //HEADING PARA CITA EN RESUMEN
    const headingCita=document.createElement('H3');
    headingCita.textContent='Resumen de Cita';
    resumen.appendChild(headingCita);

    const nombreCliente=document.createElement('P');
    nombreCliente.innerHTML=`<span>Nombre:</span> ${nombre}`;

    //FORMATEAR LA FECHA
    const fechaObj=new Date(fecha);
    const mes=fechaObj.getMonth();
    const dia=fechaObj.getDate()+2;
    const year=fechaObj.getFullYear();

    const fechaUTC=new Date( Date.UTC(year,mes,dia ) );
    const opciones={weekday:'long',year: 'numeric',month: 'long',day:'numeric'}
    const fechaFormateada=fechaUTC.toLocaleDateString('es-MX',opciones);

    const fechaCita=document.createElement('P');
    fechaCita.innerHTML=`<span>Fecha:</span> ${fechaFormateada}`;

    const horaCita=document.createElement('P');
    horaCita.innerHTML=`<span>Hora:</span> ${hora} Horas`;

    //boton para crear una cita
    const btnReservar=document.createElement('BUTTON');
    btnReservar.classList.add('boton');
    btnReservar.textContent='Reservar Cita';
    btnReservar.onclick=reservarCita;

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    resumen.appendChild(btnReservar);

}
async function reservarCita(){
    const {nombre,fecha,hora,servicios,id}=cita;
    const idServicios=servicios.map(servicio => servicio.id)

    const datos=new FormData();
    datos.append('fecha',fecha);
    datos.append('hora',hora);
    datos.append('usuarioId',id);
    datos.append('servicios',idServicios);

    try {
         //peticion hacia la api
        const url=`${location.origin}/api/citas`;

        const respuesta=await fetch(url,{
            method:'POST',
            body:datos
        });
        const resultado=await respuesta.json();
        console.log(resultado.resultado)
        if(resultado.resultado){
            Swal.fire({
                icon: "success",
                title: "Cita creada",
                text: "Tu cita fue creada correctamente",
                button:'OK'
            }).then(()=>{
                window.location.reload();
            });
        }
    }
    catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Algo salió mal al guardar la cita",
          });
    }
   
    // console.log([...datos]);
}