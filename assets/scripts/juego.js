document.addEventListener('DOMContentLoaded', function() {
    var avisoJS = document.getElementById('noscript');
    var fondoDesenfocado = document.getElementById('fondoDesenfocado');
  
    avisoJS.style.display = 'none'; // Oculta el mensaje de aviso
    fondoDesenfocado.style.display = 'none'; // Oculta el fondo desenfocado
  });
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
                scrollHaciaAbajo();

                
            });
        }
        
        // Deshabilita todos los botones en el mismo grupo de respuestas
        botones.forEach(function (element) {
            element.disabled = true;
        });

        // Obtén el ID de la pregunta actual
        var id = parseInt(boton.parentElement.parentElement.id.split('_')[1]);
        scrollHaciaSiguientePregunta(); 

        // Muestra automáticamente la siguiente pregunta si no estás en la última pregunta
        if (id < 3) {
            var siguienteId = id + 1;
            var siguientePregunta = document.getElementById("pregunta_" + siguienteId);
            if (siguientePregunta) {
                siguientePregunta.style.display = "block";
                

            }
            
        }
        scrollHaciaSiguientePregunta(); 
    } else {
        soundBadQuestion();
        boton.classList.add("backgroundContenidoRespuestaIncorrecta");

    
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
function scrollHaciaSiguientePregunta() {
    var siguientePregunta = document.querySelector('.pregunta:not(.respondida)');
  
    if (siguientePregunta) {
      siguientePregunta.scrollIntoView({ behavior: "smooth" });
    }
    else {
        // Si no hay más preguntas, hacer scroll hacia el final de la página
        window.scrollTo({
          top: document.body.scrollHeight,
          behavior: 'smooth'
        });
    }
}
function scrollHaciaAbajo() {
    var botonCambiarNivel = document.getElementById('botonCambiarNivel');
  
    if (botonCambiarNivel) {
      botonCambiarNivel.scrollIntoView({ behavior: "smooth" });
    }
}

// function mostrarPopup() {
//     document.getElementById("popup").style.display = "block";
// }
  
// function cerrarPopup() {
//     document.getElementById("popup").style.display = "none";
// }

function mostrarEstadistica(pregunta, porcentaje) {
    const popup = document.getElementById("popup");
    popup.style.display = "block";
  
    const chartData = [
      { opcion: "Correcta", porcentaje: porcentaje },
      { opcion: "Incorrecta", porcentaje: 100 - porcentaje }
    ];
  
    dibujarDiagrama(chartData, pregunta);
}
  
function leerPreguntasDesdeArchivo() {
    // Código para leer el archivo de texto y devolver un array de preguntas y porcentajes
}
  
 function dibujarDiagrama(data, pregunta) {
    const chartDiv = document.getElementById("chart");
  
    const chart = document.createElement("div");
    chart.classList.add("chart");
  
    data.forEach(item => {
      const barra = document.createElement("div");
      barra.classList.add("barra");
      barra.style.width = `${item.porcentaje}%`;
      barra.innerText = `${item.opcion} (${item.porcentaje}%)`;
      chart.appendChild(barra);
    });
  
    const preguntaElement = document.createElement("h3");
    preguntaElement.innerText = pregunta;
  
    chartDiv.innerHTML = ''; // Limpiar contenido previo
    chartDiv.appendChild(preguntaElement);
    chartDiv.appendChild(chart);
}
  
function cargarPregunta(idPregunta) {
    const preguntas = leerPreguntasDesdeArchivo();
    const preguntaActual = preguntas[idPregunta];
  
    if (preguntaActual) {
      const [pregunta, porcentaje] = preguntaActual.split(";");
      mostrarEstadistica(pregunta, Number(porcentaje));
    } else {
      alert("No hay más preguntas disponibles.");
    }
}
  
function siguientePregunta() {
    // Lógica para cargar la siguiente pregunta
    cargarPregunta(1); // Por ejemplo, cargar la segunda pregunta (índice 1)
}
  
  
  
  
  
  
  
