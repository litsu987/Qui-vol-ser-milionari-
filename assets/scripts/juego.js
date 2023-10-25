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


function soundHelp(){
	var sonido = document.createElement("iframe");
	sonido.setAttribute("src","./music/help_sound.mp3");
	document.body.appendChild(sonido);
}   
function soundWin(){
	var sonido = document.createElement("iframe");
	sonido.setAttribute("src","./music/win_sound.mp3");
	document.body.appendChild(sonido);
}   
function soundLose(){
	var sonido = document.createElement("iframe");
	sonido.setAttribute("src","./music/lose_sound.mp3");
	document.body.appendChild(sonido);
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
        boton.style.backgroundColor = "green"; // Cambia el color del botón a verde
        respuestasCorrectas++;

         preguntasAcertadas++;
         localStorage.setItem('puntaje', preguntasAcertadas);

        // Reproducir el sonido de éxito
        

        if (respuestasCorrectas === 3) {
            var botonesFormulario = document.querySelectorAll("form input[type=submit].oculto");
            botonesFormulario.forEach(function(boton) {
                boton.classList.remove("oculto");
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
        boton.style.backgroundColor = "red"; // Cambia el color del botón a rojo

    
        // Deshabilita todos los botones en el mismo grupo de respuestas
        botones.forEach(function (element) {
            element.disabled = true;
        });

        // Mostrar ventana emergente al fallar la respuesta
        var alertTimeout = setTimeout(function() {
            window.location.href = 'lose.php?puntaje=' + preguntasAcertadas; // Redirige automáticamente al usuario a "lose.php"
        }, 1000); // Espera 1 segundo antes de mostrar la ventana emergente

        // Agregar un evento para cancelar el temporizador si el usuario cierra la ventana emergente
        window.onbeforeunload = function() {
            clearTimeout(alertTimeout);
        };
    }
    if (preguntasAcertadas === 18) {
        window.location.href = 'win.php?puntaje=' + preguntasAcertadas;
    } 
    
}

