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

        console.log('Nivel de dificultad actual:', nivelDificultadActual);
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
            }, 1000);
    }
    
}

