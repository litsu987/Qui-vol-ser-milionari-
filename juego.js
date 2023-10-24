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
            console.log(nuevaDificultad); // Mensaje de depuración
            // Define la nueva dificultad
            var nuevaDificultad = nivel + 1;
        
            // Realiza una solicitud POST al servidor para actualizar la dificultad
            fetch('game.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'newDificultad=' + nuevaDificultad,
            })
            .then(function (response) {
                if (response.status === 200) {
                    // La solicitud se completó con éxito
                    console.log('Dificultad actualizada en el servidor'); // Mensaje de depuración
                    console.log(nuevaDificultad); // Mensaje de depuración
                } else {
                    // Maneja errores o problemas en la solicitud
                    console.error('Error al actualizar la dificultad');
                }
            })
            .catch(function (error) {
                console.error('Error en la solicitud: ' + error);
            });
        
            // Resto del código para manejar las respuestas correctas
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


