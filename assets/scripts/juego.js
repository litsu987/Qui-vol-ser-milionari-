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

function soundAnimation() {
    playSound('../music/epica.mp3');
}

function soundHelpQuestion() {
    playSound('../music/help_sound.mp3');
}

let respuestasCorrectas = 0; // Variable para rastrear las respuestas correctas
let comodinUsado;
let nivelDificultadActual = document.getElementById('nivel-dificultad').getAttribute('data-nivel'); 
console.log(nivelDificultadActual);
let ultimaPreguntaMostrada = 1;
let preguntasAcertadas = localStorage.getItem('puntaje');

if (nivelDificultadActual === '1') {
    preguntasAcertadas = 0;
    localStorage.setItem('nivelDificultad', nivelDificultadActual);
    document.getElementById('btnEliminarRespuestas').disabled = false;
    localStorage.setItem('botonEliminacionPresionado', 'false');
    localStorage.setItem('botonEliminacionPresionado2', 'false');
    localStorage.setItem('botonEliminacionPresionado3', 'false');
    localStorage.setItem('botonEliminacionPresionado4', 'false');
}

var botonEliminacionPresionado = localStorage.getItem('botonEliminacionPresionado');
var botonEliminacionPresionado2 = localStorage.getItem('botonEliminacionPresionado2');
var botonEliminacionPresionado3 = localStorage.getItem('botonEliminacionPresionado3');
var botonEliminacionPresionado4 = localStorage.getItem('botonEliminacionPresionado4');

function deshabilitarBotonSiPresionado(botonId, botonPresionadoId) {
    var botonPresionado = localStorage.getItem(botonPresionadoId);
    
    if (botonPresionado === 'true') {
        // Si ya ha sido pulsado, deshabilita el botón
        var boton = document.getElementById(botonId);
        boton.classList.remove("ovalBackground");
        boton.classList.add("btonBloqueadoComodin");
        boton.disabled = true;
    }
}

// Llamar a la función para deshabilitar los botones según sea necesario
deshabilitarBotonSiPresionado('btnEliminarRespuestas', 'botonEliminacionPresionado');
deshabilitarBotonSiPresionado('comodin-publico', 'botonEliminacionPresionado2');
deshabilitarBotonSiPresionado('comodin-llamada', 'botonEliminacionPresionado3');
deshabilitarBotonSiPresionado('comodin-telefono', 'botonEliminacionPresionado4 ');



if (preguntasAcertadas === null) {
    // Si no hay un puntaje almacenado, establecerlo en 0
    preguntasAcertadas = 0;
} else {
    // Convertir el valor recuperado a un número
    preguntasAcertadas = parseInt(preguntasAcertadas);
}

var preguntaActual = localStorage.getItem('preguntaActual');
var preguntaActual = 1;
localStorage.setItem('preguntaActual', preguntaActual);

if (preguntaActual === null) {
    preguntaActual = 0;
} else {
    preguntaActual = parseInt(preguntaActual);
}



if (preguntaActual >3){
}
var preguntaActual = localStorage.getItem('preguntaActual');
var preguntaActual = 1;
localStorage.setItem('preguntaActual', preguntaActual);
if (preguntaActual === null) {
    preguntaActual = 0;
} else {
    preguntaActual = parseInt(preguntaActual);
}


var cronometro0;
var cronometro1; 
var cronometro2;

function deshabilitarBotones(botones) {
    botones.forEach(function(element) {
        element.classList.remove("backgroundContenidoRespuesta");
        element.classList.add("btonBloqueado");
        element.disabled = true;
    });
}

function verificarRespuesta(respuesta, respuestaCorrecta, boton) {
    
    if (preguntaActual == 1 && nivelDificultadActual != '1'){
        console.log(nivelDificultadActual);
        detenerCronometroPregunta(cronometro0);
        document.getElementById('cronoPregunta_1').style.display = 'block';
        localStorage.setItem("valorInicial",60) 
        cronometro1 = iniciarCronometroPregunta('cronoPregunta_1',60);
    }
    if (preguntaActual == 2 && nivelDificultadActual != '1'){
        detenerCronometroPregunta(cronometro1);
        document.getElementById('cronoPregunta_2').style.display = 'block';
        detenerCronometroPregunta(cronometro2); // Detener el cronómetro 1 si es necesario
        localStorage.setItem("valorInicial",60)
        cronometro2 = iniciarCronometroPregunta('cronoPregunta_2',60);
    }
    
    var botones = boton.parentElement.querySelectorAll('button');
    var esRespuestaCorrecta = respuesta === respuestaCorrecta;
    
    if (esRespuestaCorrecta) {
       
        soundSuccessQuuestion();
        boton.classList.remove("backgroundContenidoRespuesta");
        boton.classList.add("backgroundContenidoRespuestaCorrecta");
        respuestasCorrectas++;

        preguntaActual++;
        localStorage.setItem('preguntaActual', preguntaActual);

        preguntasAcertadas++;
        localStorage.setItem('puntaje', preguntasAcertadas);

        // Reproducir el sonido de éxito

        if (respuestasCorrectas === 3) {
            detenerCronometroPregunta(cronometro2);
            document.getElementById("nextQuestions").style.display = "block";

            // var botonesFormulario = document.querySelectorAll("form input[type=submit].oculto");
            // botonesFormulario.forEach(function(boton) {
            //     boton.classList.remove("oculto");
            //     boton.classList.add("nextQuestion");
            // });
        }

        // Deshabilita todos los botones en el mismo grupo de respuestas
        deshabilitarBotones(botones);

        // Obtén el ID de la pregunta actual
        var idPreguntaActual = parseInt(boton.parentElement.parentElement.id.split('_')[1]);
        scrollHaciaSiguientePregunta();

        // Muestra automáticamente la siguiente pregunta si no estás en la última pregunta
        if (idPreguntaActual < 3) {
            var siguienteId = idPreguntaActual + 1;
            var siguientePregunta = document.getElementById("pregunta_" + siguienteId);
            if (siguientePregunta) {
                siguientePregunta.style.display = "block";
            }

            // Incrementa el número de la pregunta actual
        }
        scrollHaciaSiguientePregunta();
    } else {
        soundBadQuestion();
        boton.classList.add("backgroundContenidoRespuestaIncorrecta");

        // Deshabilita todos los botones en el mismo grupo de respuestas
        deshabilitarBotones(botones);

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
            // Cambia la acción del formulario a 'win.php'
            var form = document.createElement('form');
            form.method = 'post';
            form.action = 'win.php';

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





valorActual = 0;



function iniciarCronometroPregunta(cronoId, segundos) {
    var cronoPregunta = document.getElementById(cronoId);
    var valorInicial = localStorage.getItem("valorInicial") || segundos || 60;

    if (cronoPregunta) {
        var valorActual = valorInicial;
        cronoPregunta.innerHTML = valorActual;

        var intervalo = setInterval(function() {
            valorActual--;
            cronoPregunta.innerHTML = valorActual;

            localStorage.setItem("valorInicial", valorActual);

            if (valorActual === 0) {
                clearInterval(intervalo);
                    // Cambia la acción del formulario a 'lose.php'
                var form = document.createElement('form');
                form.method = 'post';
                form.action = 'lose.php';

                // Crea un campo oculto para el tiempo transcurrido
                var inputTiempo = document.createElement('input');
                inputTiempo.type = 'hidden';
                inputTiempo.name = 'tiempoTranscurrido2';
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

            }
        }, 1000); // Actualiza cada 1 segundo (1000 milisegundos)

        return intervalo; // Devuelve el identificador del intervalo
    }
}




function sumarTiempoComodinTiempo(cronometroId) {
    // Obtiene el valor inicial almacenado en localStorage
    var valorInicial = parseInt(localStorage.getItem("valorInicial"), 10) || 0;


    // Suma 30 segundos al valor inicial
    valorInicial += 30;


    // Almacena el nuevo valor en localStorage
    localStorage.setItem("valorInicial", valorInicial);

    // Actualiza el cronómetro con el nuevo valor
    var cronoPregunta = document.getElementById(cronometroId);
    cronoPregunta.innerHTML = valorInicial;

    // Deshabilita el botón para evitar más clics
    var botonComodin = document.getElementById('comodin-publico');
    botonComodin.disabled = true;
    console.log(localStorage.getItem("valorInicial"))
    // Evita que el evento del botón se propague y refresque la página
    return false;
}

function detenerCronometroPregunta(intervalo) {
    clearInterval(intervalo);
}

function actualizarCronometroPreguntas(cronometroId, nuevoValor) {
    var cronoPregunta = document.getElementById(cronometroId);

    if (cronoPregunta) {
        cronoPregunta.innerHTML = nuevoValor;
        console.log(nuevoValor);
    }
}



function actualizarTiempoTotal() {
    var cronometro = document.getElementById('tiempoTotal');

    cronometroInterval = setInterval(function () {
        var minutos = Math.floor(tiempoInicio / 60);
        var segundos = tiempoInicio % 60;
        var tiempoFormateado = minutos + ':' + (segundos < 10 ? '0' : '') + segundos; // Formato 0:00

        cronometro.textContent = tiempoFormateado;
        localStorage.setItem('tiempoInicio', tiempoInicio);
        tiempoInicio++; // Incrementa el tiempo en segundos
    }, 1000);
}

function enviarTiempoTotalTranscurrido() {
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

function pausarTiempoTotal() {
    if (!cronometroPausado) { // Solo pausar si no está pausado ya
        clearInterval(cronometroInterval);
        cronometroPausado = true;
    }
}

function reanudarTiempoTotal() {
    if (cronometroPausado) { // Solo reanudar si está pausado
        actualizarTiempoTotal();
        console.log('reanudarTiempoTotal');
        cronometroPausado = false;
    }
}

window.onload = function () {
    actualizarTiempoTotal();
    console.log(nivelDificultadActual);
    if (nivelDificultadActual != 1){
        document.getElementById('cronoPregunta_0').style.display = 'block';
        localStorage.setItem("valorInicial",60) 
        cronometro0 = iniciarCronometroPregunta('cronoPregunta_0',60); 
    }
};

localStorage.onload = function () {
    tiempoInicio = parseInt(localStorage.getItem('tiempoInicio'));
    clearInterval(cronometroInterval); // Detén el intervalo actual
    actualizarTiempoTotal(); // Reinicia el cronómetro con el valor de localStorage
};






function eliminarRespuestasIncorrectas(preguntaActual) {
    // Obtiene una referencia al botón de eliminación
    var botonEliminar = document.getElementById('btnEliminarRespuestas');

    // Verifica si el botón ya se encuentra deshabilitado
    if (botonEliminar.disabled) {
        return;
    }

   

    var pregunta = document.getElementById('pregunta_' + preguntaActual);

    if (pregunta) {
        var respuestas = pregunta.querySelectorAll('.contenidoRespuesta');

        var respuestasIncorrectas = [];

        respuestas.forEach(function(boton) {
            var respuestaCorrecta = boton.getAttribute('data-respuesta-correcta');
            var respuestaActual = boton.innerText.trim();

            if (respuestaActual !== respuestaCorrecta.trim()) {
                respuestasIncorrectas.push(boton);
            }
        });

        // Verifica si hay al menos dos respuestas incorrectas para eliminar
        if (respuestasIncorrectas.length >= 2) {
            document.getElementById('btnEliminarRespuestas').classList.remove("ovalBackground");
            document.getElementById('btnEliminarRespuestas').classList.add("btonBloqueadoComodin");

            // Elimina todas las respuestas incorrectas excepto una elegida al azar
            var indiceVisible = Math.floor(Math.random() * respuestasIncorrectas.length);
            var respuestaGuardada = '';
            for (var i = 0; i < respuestasIncorrectas.length; i++) {
                if (i !== indiceVisible) {
                    var botonAEliminar = respuestasIncorrectas[i];
                    var textoRespuestaIncorrecta = botonAEliminar.innerText.trim();
                    if (i >1){
                        respuestaGuardada += textoRespuestaIncorrecta;
                    }else{
                        respuestaGuardada += textoRespuestaIncorrecta+',';
                    }
                    localStorage.setItem('respuestaIncorrecta_',respuestaGuardada)
                    
                    botonAEliminar.parentNode.removeChild(botonAEliminar); // Elimina el botón del DOM
                }
            }
           console.log(localStorage.getItem('respuestaIncorrecta_'))

            localStorage.setItem('funcionEjecutada', 'true');
            // Deshabilita el botón de eliminación
            botonEliminar.disabled = true;
            // Cambia el estilo del botón (opcional)
            botonEliminar.style.backgroundColor = 'gray';
        }
    }
}

// Agrega un evento click al botón
document.getElementById('btnEliminarRespuestas').addEventListener('click', function() {
    // Verifica si el botón ya ha sido pulsado
    console.log(localStorage.getItem('preguntaActual'));
    if (botonEliminacionPresionado !== 'true' && localStorage.getItem('preguntaActual') < 3 ) {
        var preguntaActual = localStorage.getItem('preguntaActual');
        eliminarRespuestasIncorrectas(preguntaActual);

        // Marca el botón como pulsado en el almacenamiento local
        localStorage.setItem('botonEliminacionPresionado', 'true');

        // Deshabilita el botón

        this.disabled = true;
    }
});

document.getElementById('comodin-llamada').addEventListener('click', function() {


    var nivelDificultadActual = parseInt(document.getElementById('nivel-dificultad').getAttribute('data-nivel'));
    var botonEliminacionPresionado3 = localStorage.getItem('botonEliminacionPresionado3');

    if (nivelDificultadActual > 1 && botonEliminacionPresionado3 !== 'true' && localStorage.getItem('preguntaActual') < 4) {
        document.getElementById('comodin-llamada').classList.remove("ovalBackground");
        document.getElementById('comodin-llamada').classList.add("btonBloqueadoComodin");

        document.getElementById('comodin-llamada').classList.remove("ovalBackground");
        document.getElementById('comodin-llamada').classList.add("btonBloqueadoComodin");

        var preguntaActual = localStorage.getItem('preguntaActual');
        if (preguntaActual==1){
            detenerCronometroPregunta(cronometro0)
            sumarTiempoComodinTiempo('cronoPregunta_0');
            cronometro0 = iniciarCronometroPregunta('cronoPregunta_0',localStorage.getItem("valorInicial")); 
        }else if(preguntaActual==2){
            detenerCronometroPregunta(cronometro1);
            sumarTiempoComodinTiempo('cronoPregunta_1');
            cronometro1 = iniciarCronometroPregunta('cronoPregunta_1',localStorage.getItem("valorInicial")); 
            
        }else if(preguntaActual==3){
            detenerCronometroPregunta(cronometro2);
            sumarTiempoComodinTiempo('cronoPregunta_2');
            cronometro2 = iniciarCronometroPregunta('cronoPregunta_2',localStorage.getItem("valorInicial")); 
        }

        // Marca el comodín como utilizado en el almacenamiento local
        localStorage.setItem('botonEliminacionPresionado3', 'true');
        // Deshabilita el botón
        this.disabled = true;
    }
});


document.getElementById('comodin-telefono').addEventListener('click', function() {
    var nivelDificultadActual = parseInt(document.getElementById('nivel-dificultad').getAttribute('data-nivel'));
    var botonEliminacionPresionado4 = localStorage.getItem('botonEliminacionPresionado3');
    if (botonEliminacionPresionado4 !== 'true' && localStorage.getItem('preguntaActual') < 4) {
        document.getElementById('comodin-telefono').classList.remove("ovalBackground");
        document.getElementById('comodin-telefono').classList.add("btonBloqueadoComodin");
        mostrarMinijuego()
        // Marca el comodín como utilizado en el almacenamiento local
        localStorage.setItem('botonEliminacionPresionado4', 'true');
        // Deshabilita el botón
        this.disabled = true;
    }
});


document.getElementById('comodin-publico').addEventListener('click', function() {
    if (botonEliminacionPresionado2 !== 'true' && localStorage.getItem('preguntaActual') < 4 ) {
        document.getElementById('comodin-publico').classList.remove("ovalBackground");
        document.getElementById('comodin-publico').classList.add("btonBloqueadoComodin");

        var preguntaActual = localStorage.getItem('preguntaActual');

        // Marca el botón como pulsado en el almacenamiento local
        localStorage.setItem('botonEliminacionPresionado2', 'true');

        // Deshabilita el botón
       
    }
    var botonPublico = document.getElementById('comodin-publico');
    var respuestaCorrecta = localStorage.getItem('bien');

    if (botonPublico.disabled) {
        return;
    }

    

    if ( botonEliminacionPresionado2 === 'true') {
        // Si ya se ha utilizado el comodín público, no hagas nada
        return;
    }

    var pregunta = document.getElementById('pregunta_' + preguntaActual);

    if (pregunta) {
        var respuestas = pregunta.querySelectorAll('.contenidoRespuesta');

        var respuestasIncorrectas = [];
        respuestas.forEach(function(boton) {
            localStorage.setItem('bien', respuestaCorrecta = boton.getAttribute('data-respuesta-correcta'));
            var respuestaActual = boton.innerText.trim();

            if (respuestaActual !== respuestaCorrecta.trim()) {
                respuestasIncorrectas.push(boton);
            }
        });

        var porcentajeCorrecta;
        var porcentajeIncorrectas;
        var porcentajesAleatorios = [];

        // Determina aleatoriamente si la respuesta correcta tendrá un 80% de probabilidad de ser correcta
        if (Math.random() <= 0.8) {
            // Genera un número aleatorio entre 51 y 99 para el porcentaje de la respuesta correcta
            porcentajeCorrecta = Math.floor(Math.random() * 49) + 51;
            porcentajeIncorrectas = 100 - porcentajeCorrecta;
        } else {
            porcentajeCorrecta = 20; // Respuesta correcta con 20% de probabilidad
            porcentajeIncorrectas = 100 - porcentajeCorrecta;
        }

        // Genera porcentajes aleatorios para las respuestas incorrectas
        for (var i = 0; i < respuestasIncorrectas.length - 1; i++) {
            var porcentajeAleatorio = Math.floor(Math.random() * porcentajeIncorrectas) + 1;
            porcentajesAleatorios.push(porcentajeAleatorio);
            porcentajeIncorrectas -= porcentajeAleatorio;
        }

        // El porcentaje restante se asigna a la última respuesta incorrecta
        porcentajesAleatorios.push(porcentajeIncorrectas);

        var estadistica = respuestaCorrecta + "@" + porcentajeCorrecta;
        var estadistica2 = [];

        respuestasIncorrectas.forEach(function(boton, index) {
            var respuesta = boton.innerText.trim();
            if (index != 2) {
                estadistica2 += respuesta + "@" + porcentajesAleatorios[index] + "#";
            } else {
                estadistica2 += respuesta + "@" + porcentajesAleatorios[index];
            }
        });

        localStorage.setItem('estadistica2', estadistica2);
        localStorage.setItem('estadistica', estadistica);

        // Deshabilita el botón de eliminación
        botonPublico.disabled = true;
        // Cambia el estilo del botón (opcional)
        botonPublico.style.backgroundColor = 'gray';

        
        // Llama a la función mostrarModal después de actualizar 'estadistica'
        mostrarModal();

                }
});

function mostrarModal() {
    const estadistica = localStorage.getItem('estadistica');
    const estadistica2 = localStorage.getItem('estadistica2');

    if (estadistica) {
        const [nombre, porcentaje] = estadistica.split('@');
        const porcentajeNumerico = parseFloat(porcentaje);

        console.log(`Nombre: ${nombre}, Porcentaje: ${porcentajeNumerico}`);

        const modal = document.getElementById('modal');
        modal.style.display = 'block';

        const modalCanvas = document.getElementById('modal-contenido');
        if (modalCanvas.chart) {
            modalCanvas.chart.destroy();
        }
        const modalCtx = modalCanvas.getContext('2d');

        const labels = [nombre];
        const data = [porcentajeNumerico];

        console.log('Labels:', labels);
        console.log('Data:', data);

        if (estadistica2) {
            const respuestas = estadistica2.split('#');
            respuestas.forEach(respuesta => {
                const [nombreRespuesta, porcentajeRespuesta] = respuesta.split('@');
                labels.push(nombreRespuesta);
                data.push(parseFloat(porcentajeRespuesta));
            });
        }

        const modalChart = new Chart(modalCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'VOTO DEL PUBLICO',
                    data: data,
                    backgroundColor: 'rgba(255, 192, 18, 0.4)',
                    borderColor: 'rgba(255, 209, 18, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: 100
                    }
                }
            }
        });

        modalCanvas.chart = modalChart;
    } else {
        console.log('No se encontró estadística en el almacenamiento local.');
    }
}

// Función para cerrar el modal
function cerrarModal() {
    const modal = document.getElementById('modal');
    modal.style.display = 'none';
    reanudarCronometro()
    
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


var cantidadLlamadas;
var i = 0;
var imagen = null;
var parpadeo; // Variable para el intervalo de parpadeo
var isVisible = true; // Variable para rastrear la visibilidad de la imagen
var contadorTono = 0;

function minijuego() {
    cantidadLlamadas = Math.floor(Math.random() * 10) + 1;
    i = 0;
    console.log(cantidadLlamadas);

    setTimeout(function () {
        reproducirSonido();
    }, 0);

    setTimeout(function () {
        var respuestaUsuario = document.getElementById('respuestaUsuario');
        var botonComprobar = document.getElementById('botonComprobar');
        var botonSalir = document.getElementById('botonSalir');

        botonSalir.style.display = 'block';
        respuestaUsuario.style.display = 'block';
        botonComprobar.style.display = 'block';
    }, cantidadLlamadas * 3000);
}

function reproducirSonido() {
    var audioElement = document.createElement('audio');
    audioElement.src = '/assets/music/telefono.mp3';

    audioElement.addEventListener('play', function () {
        mostrarImagenLlamada();
    });

    audioElement.addEventListener('ended', function () {
        i++;
        if (i < cantidadLlamadas) {
            setTimeout(function () {
                reproducirSonido();
            }, 100);
        } else {
            // Tu lógica para manejar las respuestas
        }
    });

    audioElement.play();
}

function mostrarImagenLlamada() {
    
    var minijuegoContainer = document.getElementById('minijuego');
    if (minijuegoContainer && imagen === null) {
        imagen = document.createElement('img');
        imagen.src = '/assets/images/telefono.png';
        imagen.style.position = 'fixed';
        imagen.style.top = '50%';
        imagen.style.left = '50%';
        imagen.style.transform = 'translate(-50%, -50%)';
        minijuegoContainer.appendChild(imagen);

        var audioDuration = 2000;
        var parpadeoInterval = 1100;

        // Inicializar el intervalo de parpadeo
        parpadeo = setInterval(function () {
            console.log(contadorTono);
            if (isVisible) {
                imagen.style.visibility = 'hidden';
                contadorTono += 1;
                isVisible = false;
            } else {
                imagen.style.visibility = 'visible';
                isVisible = true;
            }

            if (contadorTono >= cantidadLlamadas){
                setTimeout(function () {
                    clearInterval(parpadeo);
                    if (!isVisible) {
                        imagen.style.visibility = 'visible';
                    }
                }, audioDuration);
            }
        }, parpadeoInterval);
        console.log(cantidadLlamadas);
        

    } else {
        
    }
}


function realizarLlamada() {
  if (i < cantidadLlamadas) {
    setTimeout(function() {
      reproducirSonido();
    }, 100); // Espera 4 segundos entre llamadas
    i++;
  }
}

function mostrarMinijuego() {
    const minijuego1 = document.getElementById('minijuego');
    if (minijuego1) {
        minijuego1.style.display = 'block';
        minijuego(); // Llama a la función minijuego para comenzar el juego
    }
}

function cerrarMinijuego() {
    const minijuego = document.getElementById('minijuego');
    if (minijuego) {
        minijuego.style.display = 'none';
    }
}

function comprobarRespuesta() {
    document.getElementById('botonComprobar').disabled = true;
    var respuestaUsuario = document.getElementById('respuestaUsuario').value;
    var respuestaCorrecta = cantidadLlamadas; // Obtén la respuesta correcta desde tus datos

    var resultadoMinijuego = document.getElementById('resultadoMinijuego');
    
    if (parseInt(respuestaUsuario) === respuestaCorrecta) {
        resultadoMinijuego.innerHTML = '¡Correcto! La respuesta es: '+localStorage.getItem('bien');
        

    } else {
        resultadoMinijuego.innerHTML = 'Lo siento, has respondido incorrectamente.';
    }

    resultadoMinijuego.style.display = 'block';
    
}
