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

function soundAnimation() {
    playSound('../music/epica.mp3');
}

function soundBicho() {
    playSound('../music/bicho.mp3');
}

function soundHelpQuestion() {
    playSound('../music/help_sound.mp3');
}

var respuestasCorrectas = 0; // Variable para rastrear las respuestas correctas
var comodinUsado;
var nivelDificultadActual = document.getElementById('nivel-dificultad').getAttribute('data-nivel');
var ultimaPreguntaMostrada = 1;
var preguntasAcertadas = localStorage.getItem('puntaje');




if (nivelDificultadActual === '1') {
    preguntasAcertadas = 0;
    localStorage.setItem('nivelDificultad', nivelDificultadActual);
    document.getElementById('btnEliminarRespuestas').disabled = false;
    localStorage.setItem('botonEliminacionPresionado', 'false');
    localStorage.setItem('botonEliminacionPresionado2', 'false');
    localStorage.setItem('botonEliminacionPresionado3', 'false');
}

var botonEliminacionPresionado = localStorage.getItem('botonEliminacionPresionado');
var botonEliminacionPresionado2 = localStorage.getItem('botonEliminacionPresionado2');
var botonEliminacionPresionado3 = localStorage.getItem('botonEliminacionPresionado3');

if (botonEliminacionPresionado === 'true') {
    // Si ya ha sido pulsado, deshabilita el botón
    document.getElementById('btnEliminarRespuestas').disabled = true;
}

if (botonEliminacionPresionado2 === 'true') {
    // Si ya ha sido pulsado, deshabilita el botón
    document.getElementById('comodin-publico').disabled = true;
}

if (botonEliminacionPresionado3 === 'true') {
    // Si ya ha sido pulsado, deshabilita el botón
    document.getElementById('comodin-llamada').disabled = true;
}

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

var cronometro1; // Declarar las variables fuera de la función
var cronometro2;
var cronometro0;


function verificarRespuesta(respuesta, respuestaCorrecta, boton,cronometroId) {

    if (preguntaActual ==1 && nivelDificultadActual != '1'){
        document.getElementById('cronoPregunta_1').style.display = 'block';
        detenerCronometro(cronometro0);
        cronometro1 = iniciarCronometro('cronoPregunta_1', 60);
    }
    if (preguntaActual ==2 && nivelDificultadActual != '1'){
        document.getElementById('cronoPregunta_2').style.display = 'block';
        detenerCronometro(cronometro1); // Detener el cronómetro 1 si es necesario
        cronometro2 = iniciarCronometro('cronoPregunta_2', 60);
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
            clearInterval(cronometro2);
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
            element.classList.remove("backgroundContenidoRespuesta");
            element.classList.add("btonBloqueado");
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

            // Incrementa el número de la pregunta actual
        }
        
        scrollHaciaSiguientePregunta();
    } else {
        soundBadQuestion();
        boton.classList.add("backgroundContenidoRespuestaIncorrecta");

        // Deshabilita todos los botones en el mismo grupo de respuestas
        botones.forEach(function(element) {
            element.classList.remove("backgroundContenidoRespuesta");
            element.classList.add("btonBloqueado");
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
            // Cambia la acción del formulario a 'lose.php'
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

function iniciarCronometro(cronoId, valorInicial) {
    var cronoPregunta = document.getElementById(cronoId);

    if (cronoPregunta) {
        var valorActual = valorInicial;
        cronoPregunta.innerHTML = valorActual;

        var intervalo = setInterval(function() {
            valorActual--;
            cronoPregunta.innerHTML = valorActual;

            if (valorActual === 0) {
                clearInterval(intervalo);
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

            }
        }, 1000); // Actualiza cada 1 segundo (1000 milisegundos)

        return intervalo; // Devuelve el identificador del intervalo
    }
}

function detenerCronometro(intervalo) {
    clearInterval(intervalo);
}

function sumarTiempo(segundos, cronometroId) {
    if (botonEliminacionPresionado3 === 'true') {
        // Si ya se ha utilizado el comodín público, no hagas nada
        return;
    }

    var cronoPregunta = document.getElementById(cronometroId);
    var tiempoRestante = parseInt(cronoPregunta.innerHTML);

    // Verifica si el tiempo restante es mayor que 0 y si el botón no ha sido deshabilitado
    if (tiempoRestante > 0) {
        // Suma los segundos al tiempo restante
        tiempoRestante += segundos;

        // Asegúrate de que el tiempo restante no sea mayor que 60
        tiempoRestante = Math.min(tiempoRestante, 60);

        // Actualiza el cronómetro con el nuevo tiempo
        cronoPregunta.innerHTML = tiempoRestante;

        // Deshabilita el botón para evitar más clics
        var botonComodin = document.getElementById('comodin-publico');
        botonComodin.disabled = true;

        // Evita que el evento del botón se propague y refresque la página
        return false;
    }
}



window.onload = function () {
    actualizarCronometro();
    if (nivelDificultadActual != '1'){
        document.getElementById('cronoPregunta_0').style.display = 'block';
        cronometro0 = iniciarCronometro('cronoPregunta_0', 60); 
    }else{
        
    }
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

function reanudarCronometro() {
    if (cronometroPausado) { // Solo reanudar si está pausado
        actualizarCronometro();
        cronometroPausado = false;
    }
}




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
        this.classList.remove("ovalBackground");
        this.classList.add("btonBloqueadoComodin");
        this.disabled = true;
    }
});

document.getElementById('comodin-llamada').addEventListener('click', function() {
    var nivelDificultadActual = parseInt(document.getElementById('nivel-dificultad').getAttribute('data-nivel'));
    var botonEliminacionPresionado3 = localStorage.getItem('botonEliminacionPresionado3');

    if (nivelDificultadActual > 1 && botonEliminacionPresionado3 !== 'true' && localStorage.getItem('preguntaActual') < 3) {
        
        var preguntaActual = localStorage.getItem('preguntaActual');
        if (preguntaActual==1){
            console.log(nivelDificultadActual)
            sumarTiempo(30, 'cronoPregunta_0');
        }else if(preguntaActual==2){
            sumarTiempo(30, 'cronoPregunta_1');
        }else if(preguntaActual==3){
            sumarTiempo(30, 'cronoPregunta_2');
        }
        // Marca el comodín como utilizado en el almacenamiento local
        localStorage.setItem('botonEliminacionPresionado3', 'true');
        // Deshabilita el botón
        this.classList.remove("ovalBackground");
        this.classList.add("btonBloqueadoComodin");
        this.disabled = true;
    }
});


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


document.getElementById('comodin-publico').addEventListener('click', function() {
    pausarCronometro();
    if (botonEliminacionPresionado2 !== 'true' && localStorage.getItem('preguntaActual') < 3 ) {
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

        botonPublico.classList.remove("ovalBackground");
        botonPublico.classList.add("btonBloqueadoComodin");
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
