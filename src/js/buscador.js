document.addEventListener('DOMContentLoaded',()=>{
    iniciarApp();
});

function iniciarApp(){
    buscarPorFecha();
}

function buscarPorFecha() {
    const fechaInput = document.querySelector('#fecha');
    const botonBuscar = document.querySelector('#buscar');

    botonBuscar.addEventListener('click', () => {
        const fechaSeleccionada = fechaInput.value;
        if (fechaSeleccionada) {
            window.location = `?fecha=${fechaSeleccionada}`;
        } else {
            alert('Por favor selecciona una fecha');
        }
    });
}