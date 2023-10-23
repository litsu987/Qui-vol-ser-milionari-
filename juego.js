function soundBadQuestion(){
	var sonido = document.createElement("iframe");
	sonido.setAttribute("src","./music/error_sound.mp3");
	document.body.appendChild(sonido);
}
function soundSuccessQuuestion(){
	var sonido = document.createElement("iframe");
	sonido.setAttribute("src","./music/success_sound.mp3");
	document.body.appendChild(sonido);
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


var ultimaPreguntaMostrada = 1;
var nivel = 1;
var respuestasCorrectas = 0; // Variable para rastrear las respuestas correctas

function mostrarSiguientePregunta(id) {
    var preguntaActual = document.getElementById("pregunta_" + id);
    preguntaActual.style.display = "block";

	if (id === 1 || id === 2) {
		document.getElementById("mostrar_" + id).style.display = "none";
	}

    var siguienteId = id + 1;
    var totalPreguntas = 10; // Reemplaza con el número real de preguntas

    if (siguienteId <= totalPreguntas) {
        var siguientePregunta = document.getElementById("pregunta_" + siguienteId);
        siguientePregunta.style.display = "block";
    } else {
        // Aquí puedes agregar el código para mostrar más preguntas si las hay
        // Por ejemplo, para mostrar 3 preguntas adicionales:
        for (var i = 1; i <= 3; i++) {
            var pregunta = document.getElementById("pregunta_" + (siguienteId + i));
            if (pregunta) {
                pregunta.style.display = "block";
            }
        }
    }
    ultimaPreguntaMostrada = siguienteId;
}

function verificarRespuesta(respuesta, respuestaCorrecta, boton) {
    var botones = boton.parentElement.querySelectorAll('button');

    if (respuesta === respuestaCorrecta) {
        boton.style.backgroundColor = "green"; // Cambia el color del botón a verde
        respuestasCorrectas++;

        if (respuestasCorrectas === 3) {
            // Llama a cargarPreguntas cuando se han dado 3 respuestas correctas
            cargarPreguntas(archivoSeleccionado);
        }

        // Muestra el botón "Mostrar Siguiente Pregunta"
        if (ultimaPreguntaMostrada < 3) {
            // Muestra el botón "Mostrar Siguiente Pregunta" solo si no estás en la última pregunta
            document.getElementById("mostrar_" + ultimaPreguntaMostrada).style.display = "block";
        }
    } else {
        boton.style.backgroundColor = "red"; // Cambia el color del botón a rojo
    }

    // Deshabilita todos los botones en el mismo grupo de respuestas
    botones.forEach(function (element) {
        element.disabled = true;
    });
}


