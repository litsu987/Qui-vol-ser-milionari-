var tiempoInicio = Date.now();
function playSound(soundFile) {
    var audio = new Audio(soundFile);
    audio.play();
}

function soundSuccessQuuestion() {
    playSound('../music/success_sound.mp3');
}

function soundBadQuestion() {
    playSound('../music/error_sound.mp3');
}

function soundLoseQuestion() {
    playSound('../music/lose_sound.mp3');
}

function soundWinQuestion() {
    playSound('../music/win_sound.mp3');
}

function soundHelpQuestion() {
    playSound('../music/help_sound.mp3');
}

var respuestasCorrectas = 0; // Variable para rastrear las respuestas correctas


var nivelDificultadActual = document.getElementById('nivel-dificultad').getAttribute('data-nivel');
var ultimaPreguntaMostrada = 1;
var preguntasAcertadas = localStorage.getItem('puntaje');

if (nivelDificultadActual === '1') {
    preguntasAcertadas = 0;
    localStorage.setItem('nivelDificultad', nivelDificultadActual);
}
   

if (preguntasAcertadas === null) {
    // Si no hay un puntaje almacenado, establecerlo en 0
    preguntasAcertadas = 0;
} else {
    // Convertir el valor recuperado a un número
    preguntasAcertadas = parseInt(preguntasAcertadas);
}

function verificarRespuesta(respuesta, respuestaCorrecta, boton) {
    var botones = boton.parentElement.querySelectorAll('button');

    if (respuesta === respuestaCorrecta) {
        soundSuccessQuuestion();
        boton.classList.remove("backgroundContenidoRespuesta");
        boton.classList.add("backgroundContenidoRespuestaCorrecta");
        respuestasCorrectas++;

         preguntasAcertadas++;
         localStorage.setItem('puntaje', preguntasAcertadas);

        // Reproducir el sonido de éxito
        

        if (respuestasCorrectas === 3) {
            var botonesFormulario = document.querySelectorAll("form input[type=submit].oculto");
            botonesFormulario.forEach(function(boton) {
                boton.classList.remove("oculto");
                boton.classList.add("nextQuestion");
            });
        }

        // Deshabilita todos los botones en el mismo grupo de respuestas
        botones.forEach(function (element) {
            element.disabled = true;
        });

        // Obtén el ID de la pregunta actual
        var id = parseInt(boton.parentElement.parentElement.id.split('_')[1]);

        // Muestra automáticamente la siguiente pregunta si no estás en la última pregunta
        if (id < 3) {
            var siguienteId = id + 1;
            var siguientePregunta = document.getElementById("pregunta_" + siguienteId);
            if (siguientePregunta) {
                siguientePregunta.style.display = "block";
            }
        }

    } else {
        soundBadQuestion();
        boton.classList.add("backgroundContenidoRespuestaIncorrecta");

    
        // Deshabilita todos los botones en el mismo grupo de respuestas
        botones.forEach(function (element) {
            element.disabled = true;
        });

        // Mostrar ventana emergente al fallar la respuesta
        var alertTimeout = setTimeout(function() {


            var form = document.createElement('form');
            form.method = 'post';
            form.action = 'lose.php';

            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'puntaje';
            input.value = preguntasAcertadas;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();

            var tiempoTranscurrido = Math.floor((Date.now() - tiempoInicio) / 1000); // en segundos
            enviarTiempoTranscurrido(tiempoTranscurrido);
            

           
        }, 1000); // Espera 1 segundo antes de mostrar la ventana emergente

        // Agregar un evento para cancelar el temporizador si el usuario cierra la ventana emergente
        window.onbeforeunload = function() {
            clearTimeout(alertTimeout);
        };
    }
    if (preguntasAcertadas === 18) {
        var alertTimeout = setTimeout(function() {
        var formWin = document.createElement('form');
        formWin.method = 'post';
        formWin.action = 'win.php';

        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'puntaje';
        input.value = preguntasAcertadas;

        formWin.appendChild(input);
        document.body.appendChild(formWin);
        formWin.submit();
        var tiempoTranscurrido = Math.floor((Date.now() / 1000) - tiempoInicio);
        enviarTiempoTranscurrido(tiempoTranscurrido);
        
            }, 1000);
    }
    
    
}


function actualizarCronometro() {
    var cronometro = document.getElementById('cronometro');
    var tiempoInicio = Math.floor(Date.now() / 1000); // Establece el tiempo de inicio cada vez que se carga la página

    // Actualiza el cronómetro cada segundo
    var intervalo = setInterval(function () {
        var tiempoActual = Math.floor(Date.now() / 1000);
        var tiempoTranscurrido = tiempoActual - tiempoInicio;
        cronometro.textContent = 'Tiempo: ' + tiempoTranscurrido + ' segundos';
    }, 1000);

    // Detiene el intervalo cuando el usuario deja la página
    window.onbeforeunload = function () {
        clearInterval(intervalo);
    };
}

// Llama a la función para actualizar el cronómetro cuando la página se carga
window.onload = function () {
    actualizarCronometro();
};

function enviarTiempoTranscurrido(tiempoTranscurrido) {
    
    // Crea un formulario
    var form = document.createElement('form');
    form.method = 'post';
    
    // Establece la acción del formulario en 'win.php' o 'lose.php' según el caso
    var destino = (preguntasAcertadas === 18) ? 'win.php' : 'lose.php';
    form.action = destino;

    // Crea un campo oculto para el tiempo transcurrido
    var inputTiempo = document.createElement('input');
    inputTiempo.type = 'hidden';
    inputTiempo.name = 'tiempoTranscurrido';
    inputTiempo.value = tiempoTranscurrido;

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
}
