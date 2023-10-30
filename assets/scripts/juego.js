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
var comodinUsado
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
    var esRespuestaCorrecta = respuesta === respuestaCorrecta;

    if (esRespuestaCorrecta) {
        soundSuccessQuuestion();
        boton.classList.remove("backgroundContenidoRespuesta");
        boton.classList.add("backgroundContenidoRespuestaCorrecta");
        respuestasCorrectas++;

        preguntasAcertadas++;
        localStorage.setItem('puntaje', preguntasAcertadas);

        // Reproducir el sonido de éxito

        if (respuestasCorrectas === 3) {
            pausarCronometro();
            var botonesFormulario = document.querySelectorAll("form input[type=submit].oculto");
            botonesFormulario.forEach(function(boton) {
                boton.classList.remove("oculto");
                boton.classList.add("nextQuestion");
            });
        }

        // Deshabilita todos los botones en el mismo grupo de respuestas
        botones.forEach(function(element) {
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
        botones.forEach(function(element) {
            element.disabled = true;
        });

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
        }, 1000);
    }
}


var tiempoInicio = localStorage.getItem('tiempoInicio');
var cronometroInterval; // Variable para almacenar el intervalo del cronómetro
var cronometroPausado = false; 

if (tiempoInicio === null || preguntasAcertadas <= 0) {
    tiempoInicio = 0;
} else {
    tiempoInicio = parseInt(tiempoInicio);
}

function actualizarCronometro() {
    var cronometro = document.getElementById('cronometro');
    
    cronometroInterval = setInterval(function () {
        var minutos = Math.floor(tiempoInicio / 60);
        var segundos = tiempoInicio % 60;
        var tiempoFormateado = minutos + ':' + (segundos < 10 ? '0' : '') + segundos; // Formato 0:00

        cronometro.textContent = tiempoFormateado;
        localStorage.setItem('tiempoInicio', tiempoInicio);
        tiempoInicio++; // Incrementa el tiempo en segundos
    }, 1000);
}

window.onload = function () {
    actualizarCronometro();
};

localStorage.onload = function () {
    tiempoInicio = parseInt(localStorage.getItem('tiempoInicio'));
    clearInterval(cronometroInterval); // Detén el intervalo actual
    actualizarCronometro(); // Reinicia el cronómetro con el valor de localStorage
};

function enviarTiempoTranscurrido() {
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
    inputTiempo.value = tiempoInicio; // Usar tiempoInicio en lugar de tiempoTranscurrido

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

function pausarCronometro() {
    if (!cronometroPausado) { // Solo pausar si no está pausado ya
        clearInterval(cronometroInterval);
        cronometroPausado = true;
    }
}


function eliminarRespuestas(boton) {
    var botones = boton.parentElement.querySelectorAll('button');
    var respuestasIncorrectas = [];

    // Filtrar las respuestas incorrectas
    botones.forEach(function(element) {
        if (!element.classList.contains("backgroundContenidoRespuestaCorrecta")) {
            respuestasIncorrectas.push(element);
        }
    });

    // Eliminar todas las respuestas incorrectas
    respuestasIncorrectas.forEach(function(respuestaIncorrecta) {
        respuestaIncorrecta.style.display = "none";
    });
}
