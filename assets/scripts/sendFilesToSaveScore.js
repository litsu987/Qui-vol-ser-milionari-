const name = document.getElementById("name");

function setDate() {
    let date = document.getElementById("currentDate");
    const currentDate = new Date().toDateString();
    date.value = currentDate;
}















// Mostrar ventana emergente al fallar la respuesta
var alertTimeout = setTimeout(function() {
    // Cambia la acción del formulario a 'lose.php'
    var form = document.createElement('form');
    form.method = 'post';
    form.action = 'lose.php';

    // Crea un campo oculto para el tiempo transcurrido
    var inputTiempo = document.createElement('input');
    inputTiempo.type = 'hidden';
    inputTiempo.name = 'tiempoTranscurrido';
    inputTiempo.value = tiempoInicio;

    // Crea un campo oculto para el puntaje
    var inputPuntaje = document.createElement('input');
    inputPuntaje.type = 'hidden';
    inputPuntaje.name = 'puntaje';
    inputPuntaje.value = preguntasAcertadas;

    // Agrega los campos ocultos al formulario
    form.appendChild(inputTiempo);
    form.appendChild(inputPuntaje);

    // Agrega el formulario al cuerpo del documento y envíalo
    document.body.appendChild(form);
    form.submit();
}, 1000);

// Agregar un evento para cancelar el temporizador si el usuario cierra la ventana emergente
window.onbeforeunload = function() {
    clearTimeout(alertTimeout);
};