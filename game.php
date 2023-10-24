<?php
session_start();

if (!isset($_SESSION['lang']) && !($_SESSION['lang'] == 'es' || $_SESSION['lang'] == 'ca' || $_SESSION['lang'] == 'en')) {
    $_SESSION['lang'] = 'en';
}

$_SESSION['lang'] = 'en';

include 'language/' . $_SESSION['lang'] . '.php';
$_SESSION['score'] = 13;
    

if (isset($_POST['aumentar_dificultad'])) {
    if (!isset($_SESSION['nivel_dificultad'])) {
        // Si la variable de sesión 'nivel_dificultad' no existe en esta sesión, inicialízala en 1
        $_SESSION['nivel_dificultad'] = 1;
    } else {
        // Aumenta la variable de sesión 'nivel_dificultad' en 1
        $_SESSION['nivel_dificultad']++;
    }
} else {
    // Restablece la variable de sesión 'nivel_dificultad' en 1 cuando no se presiona el botón
    $_SESSION['nivel_dificultad'] = 1;
}

$dificultad = $_SESSION['nivel_dificultad'];

?>


<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $lang['tittle']; ?>
    </title>
    <style> 
    .oculto {
        display: none;
    }
    </style>
</head>
<body>
<img src="./images/banner.jpg" alt="Banner">
<p><?php echo $lang['messages']['dificultLvl']; ?> : <?php echo $_SESSION['nivel_dificultad']; ?></p>

<?php

function obtenerArchivoSegunDificultad($nivelDificultad) {
    // Define una matriz que asocia cada nivel de dificultad con un archivo correspondiente
    if ($_SESSION['lang'] == 'ca'){
        $archivosPorDificultad = [
            1 => 'questions/catalan_1.txt', // Archivo para nivel de dificultad 1
            2 => 'questions/catalan_2.txt', // Archivo para nivel de dificultad 2
            3 => 'questions/catalan_3.txt', // Archivo para nivel de dificultad 3
            4 => 'questions/catalan_4.txt', // Archivo para nivel de dificultad 4
            5 => 'questions/catalan_5.txt', // Archivo para nivel de dificultad 5
            6 => 'questions/catalan_6.txt', // Archivo para nivel de dificultad 6
        ];
    }else  if ($_SESSION['lang'] == 'es'){
        $archivosPorDificultad = [
            1 => 'questions/spanish_1.txt', // Archivo para nivel de dificultad 1
            2 => 'questions/spanish_2.txt', // Archivo para nivel de dificultad 2
            3 => 'questions/spanish_3.txt', // Archivo para nivel de dificultad 3
            4 => 'questions/spanish_4.txt', // Archivo para nivel de dificultad 4
            5 => 'questions/spanish_5.txt', // Archivo para nivel de dificultad 5
            6 => 'questions/spanish_6.txt', // Archivo para nivel de dificultad 6
        ];
    }else  if ($_SESSION['lang'] == 'en'){
        $archivosPorDificultad = [
            1 => 'questions/english_1.txt', // Archivo para nivel de dificultad 1
            2 => 'questions/english_2.txt', // Archivo para nivel de dificultad 2
            3 => 'questions/english_3.txt', // Archivo para nivel de dificultad 3
            4 => 'questions/english_4.txt', // Archivo para nivel de dificultad 4
            5 => 'questions/english_5.txt', // Archivo para nivel de dificultad 5
            6 => 'questions/english_6.txt', // Archivo para nivel de dificultad 6
        ];
    }

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


if ($dificultad > 6) {
    echo "<script>window.location = 'win.php';</script>";
    exit;
}

$archivoSeleccionado = obtenerArchivoSegunDificultad($dificultad);
cargarPreguntas($archivoSeleccionado);


?>
<form method="post" action="game.php">
  <input type="hidden" name="aumentar_dificultad" value="1">
  <input type="submit" value=" <?php echo $lang['nextQuestions']; ?>" class="oculto">
  
</form>



<script src="juego.js"></script>
</body>
</html>