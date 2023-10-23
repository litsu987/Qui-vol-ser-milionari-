var ultimaPreguntaMostrada = 1; // Variable para rastrear la última pregunta mostrada

function mostrarSiguientePregunta(id) {
    var preguntaActual = document.getElementById("pregunta_" + id);
    preguntaActual.style.display = "block";

    // Oculta el botón "Mostrar Pregunta Anterior" para la primera pregunta
    if (id === 1 || id === 2) {
        document.getElementById("mostrar_" + id).style.display = "none";
    }

    var siguienteId = id + 1;
    if (siguienteId <= 3) {
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