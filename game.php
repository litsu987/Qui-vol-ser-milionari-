<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    

<?php
$nombre_archivo = 'questions/catalan_1.txt';

// Comprueba si el archivo existe
if (file_exists($nombre_archivo)) {
    // Abre el archivo en modo lectura
    $archivo = fopen($nombre_archivo, 'r');

    if ($archivo) {
        $preguntas_respuestas = [];
        $pregunta = '';
        $respuestas = [];
        $respuestaCorrecta = ''; // Variable para almacenar la respuesta correcta

        while (($linea = fgets($archivo)) !== false) {
            $linea = trim($linea); // Elimina espacios en blanco al principio y al final de la línea

            if (empty($linea)) {
                continue; // Salta las líneas en blanco
            }

            if (substr($linea, 0, 1) === '*') {
                // Nueva pregunta
                if (!empty($pregunta)) {
                    // Agrega la pregunta y respuestas al arreglo
                    $preguntas_respuestas[] = [
                        'pregunta' => $pregunta,
                        'respuestas' => $respuestas,
                        'respuestaCorrecta' => $respuestaCorrecta,
                    ];
                }
                // Reinicia para la nueva pregunta
                $pregunta = substr($linea, 1);
                $respuestas = [];
                $respuestaCorrecta = ''; // Reinicia la respuesta correcta
            } elseif (substr($linea, 0, 1) === '-') {
                // Respuesta incorrecta
                $respuestas[] = ['respuesta' => substr($linea, 1), 'correcta' => false];
            } elseif (substr($linea, 0, 1) === '+') {
                // Respuesta correcta
                $respuestas[] = ['respuesta' => substr($linea, 1), 'correcta' => true];
                $respuestaCorrecta = substr($linea, 1);
            } else {
                // Línea de texto anterior
                $pregunta .= ' ' . $linea;
            }
        }

        // Cerrar el archivo
        fclose($archivo);

        // Barajar aleatoriamente las preguntas
        shuffle($preguntas_respuestas);

        // Limitar a las 3 primeras preguntas
        $preguntas_respuestas = array_slice($preguntas_respuestas, 0, 3);

        if (isset($_POST['mostrarContenido'])) {
            echo '<div id="preguntas_contenedor">';
            foreach ($preguntas_respuestas as $i => $pregunta_respuestas) {
                $pregunta = $pregunta_respuestas['pregunta'];
                $respuestas = $pregunta_respuestas['respuestas'];
                $respuestaCorrecta = $pregunta_respuestas['respuestaCorrecta'];
                echo '<div id="pregunta_' . ($i + 1) . '" style="display: ' . ($i === 0 ? 'block' : 'none') . ';">';
                mostrarPreguntaRespuestas($pregunta, $respuestas, $respuestaCorrecta);
                if ($i < count($preguntas_respuestas) - 1) {
                    echo '<button id="mostrar_' . ($i + 1) . '" onclick="mostrarSiguientePregunta(' . ($i + 1) . ')">Mostrar Siguiente Pregunta</button>';
                }
                echo '</div>';
            }
            echo '</div>';
        } else {
            // Muestra solo el botón para mostrar el contenido
            echo '
            <form method="post">
                <input type="submit" name="mostrarContenido" value="Mostrar Preguntas y Respuestas">
            </form>';
        }
    } else {
        echo "No se pudo abrir el archivo.";
    }
} else {
    echo "El archivo no existe.";
}

function mostrarPreguntaRespuestas($pregunta, $respuestas, $respuestaCorrecta) {
    ?>
    <div id='contenidoPregunta' class="radial-gradient">
    <?php
    echo "<h1 >$pregunta</h1>\n";
    ?>
    </div>
    <?php
    echo '<div id="respuestas">';
    
    foreach ($respuestas as $i => $respuesta) {
        $respuestaTexto = $respuesta['respuesta'];
        echo '<button id="contenidoRespuesta" onclick="verificarRespuesta(\'' . $respuestaTexto . '\', \'' . $respuestaCorrecta . '\', this)"> ' . $respuestaTexto . '</button>';
    
    }
    
    echo '</div>';
    echo '<div id="mensaje_respuesta"></div>'; // Aquí se mostrará el mensaje
}
?>
<script>
var ultimaPreguntaMostrada = 1; // Variable para rastrear la última pregunta mostrada

function mostrarSiguientePregunta(id) {
    var preguntaActual = document.getElementById("pregunta_" + id);
    preguntaActual.style.display = "block";

    // Oculta el botón "Mostrar Pregunta Anterior" para la primera pregunta
    if (id === 1 || id === 2) {
        document.getElementById("mostrar_" + id).style.display = "none";
    }

    var siguienteId = id + 1;
    if (siguienteId <= <?php echo count($preguntas_respuestas); ?>) {
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
    } else {

        boton.style.backgroundColor = "red"; // Cambia el color del botón a rojo
    }

    // Deshabilita todos los botones en el mismo grupo de respuestas
    botones.forEach(function (element) {
        element.disabled = true;
    });
}



</script>

</body>
</html>