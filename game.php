<?php
session_start();

$respuesta = ''; // Inicializa la variable respuesta
$respuestaCorrecta = ''; // Inicializa la variable respuestaCorrecta
$dificultad=0;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica si es una solicitud POST para actualizar la dificultad
    echo "Dificultad actualizada a: " . $dificultad; // Mensaje de depuración
    $newDificultad = isset($_POST['newDificultad']) ? intval($_POST['newDificultad']) : null;
   
    if ($newDificultad !== null) {
        if ($newDificultad >= 1 && $newDificultad <= 6) {
            // Actualiza la variable $dificultad
            $dificultad = $newDificultad;
    
            // Mensaje de depuración para verificar la nueva dificultad
            echo "Dificultad actualizada a: " . $dificultad;
        }
        
    }
}
echo $dificultad;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php
$dificultad=1;

function obtenerArchivoSegunDificultad($nivelDificultad) {
    // Define una matriz que asocia cada nivel de dificultad con un archivo correspondiente
    $archivosPorDificultad = [
        1 => 'questions/catalan_1.txt', // Archivo para nivel de dificultad 1
        2 => 'questions/catalan_2.txt', // Archivo para nivel de dificultad 2
        3 => 'questions/catalan_3.txt', // Archivo para nivel de dificultad 3
        4 => 'questions/catalan_4.txt', // Archivo para nivel de dificultad 4
        5 => 'questions/catalan_5.txt', // Archivo para nivel de dificultad 5
        6 => 'questions/catalan_6.txt', // Archivo para nivel de dificultad 6
    ];

    // Verifica si el nivel de dificultad proporcionado existe en la matriz
    if (array_key_exists($nivelDificultad, $archivosPorDificultad)) {
        return $archivosPorDificultad[$nivelDificultad];
    } else {
        // Si el nivel de dificultad no se encuentra, utiliza un archivo predeterminado (por ejemplo, el nivel 1)
        return 'questions/nivel1.txt';
    }
}
// Define la función mostrarPreguntaRespuestas
function mostrarPreguntaRespuestas($pregunta, $respuestas, $respuestaCorrecta) {
    echo "<strong>$pregunta</strong>\n";
    echo '<div class="respuestas">';
    
    foreach ($respuestas as $i => $respuesta) {
        $respuestaTexto = $respuesta['respuesta'];
        echo '<button onclick="verificarRespuesta(\'' . $respuestaTexto . '\', \'' . $respuestaCorrecta . '\', this)"> ' . $respuestaTexto . '</button>';
    }
    
    echo '</div>';
    echo '<div id="mensaje_respuesta"></div>'; // Aquí se mostrará el mensaje
}



function cargarPreguntasYMostrar($nombre_archivo, $dificultad) {
    // Aquí va el código actual de cargarPreguntas
    // ...

    echo '<div id="preguntas_contenedor">';
    foreach ($preguntas_respuestas as $i => $pregunta_respuestas) {
        $pregunta = $pregunta_respuestas['pregunta'];
        $respuestas = $pregunta_respuestas['respuestas'];
        $respuestaCorrecta = $pregunta_respuestas['respuestaCorrecta'];
        echo '<div id="pregunta_' . ($i + 1) . '" style="display: ' . ($i === 0 ? 'block' : 'none') . ';">';
        mostrarPreguntaRespuestas($pregunta, $respuestas, $respuestaCorrecta);
        if ($i < count($preguntas_respuestas) - 1) {
            echo '<button id="mostrar_' . ($i + 1) . '" style="display: none;" onclick="mostrarSiguientePregunta(' . ($i + 1) . ')">Mostrar Siguiente Pregunta</button>';
        }
        echo '</div>';
    }
    echo '</div>';
}

function cargarPreguntas($nombre_archivo) {
    
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

            echo '<div id="preguntas_contenedor">';
            foreach ($preguntas_respuestas as $i => $pregunta_respuestas) {
                $pregunta = $pregunta_respuestas['pregunta'];
                $respuestas = $pregunta_respuestas['respuestas'];
                $respuestaCorrecta = $pregunta_respuestas['respuestaCorrecta'];
                echo '<div id="pregunta_' . ($i + 1) . '" style="display: ' . ($i === 0 ? 'block' : 'none') . ';">';
                mostrarPreguntaRespuestas($pregunta, $respuestas, $respuestaCorrecta);
                if ($i < count($preguntas_respuestas) - 1) {
                    echo '<button id="mostrar_' . ($i + 1) . '" style="display: none;" onclick="mostrarSiguientePregunta(' . ($i + 1) . ')">Mostrar Siguiente Pregunta</button>';
                }
                echo '</div>';
                
            }
            echo '</div>';
           
        } else {
            echo "No se pudo abrir el archivo.";
        }
    } else {
        echo "El archivo no existe.";
    }
}


$archivoSeleccionado = obtenerArchivoSegunDificultad($dificultad);
cargarPreguntas($archivoSeleccionado);



?>
<script src="juego.js"></script>
</body>
</html>