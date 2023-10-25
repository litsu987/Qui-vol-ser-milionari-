<?php
session_start();

if (!isset($_SESSION['lang']) && !($_SESSION['lang'] == 'es' || $_SESSION['lang'] == 'ca' || $_SESSION['lang'] == 'en')) {
    $_SESSION['lang'] = 'en';
}

include '../assets/language/' . $_SESSION['lang'] . '.php';
$_SESSION['score'] = 0;
    

if (isset($_POST['aumentar_dificultad'])) {
    if (!isset($_SESSION['nivel_dificultad'])) {
        // Si la variable de sesión 'nivel_dificultad' no existe en esta sesión, inicialízala en 1
        $_SESSION['nivel_dificultad'] = 1;
    } else {
        // Aumenta la variable de sesión 'nivel_dificultad' en 1
        $_SESSION['nivel_dificultad']++;
        if ($_SESSION['nivel_dificultad'] <=6){
        }
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
    <link rel="stylesheet" type="text/css" href="../assets/styles/style.css">
</head>
<body>
<div class="bannerMillonario">
<img class="bannerImagen" src="../assets/images/presentador.png" alt="Banner">
</div>

<h3 class="lvlDifivultad trapecioDificultad"><?php echo $lang['messages']['dificultLvl']; ?> : <?php echo $_SESSION['nivel_dificultad']; ?></h3>

<div id="nivel-dificultad " data-nivel="<?php echo $_SESSION['nivel_dificultad']; ?>"></div>
<?php

function obtenerArchivoSegunDificultad($nivelDificultad) {
    // Define una matriz que asocia cada nivel de dificultad con un archivo correspondiente
    if ($_SESSION['lang'] == 'ca'){
        $archivosPorDificultad = [
            1 => '../assets/questions/catalan_1.txt', // Archivo para nivel de dificultad 1
            2 => '../assets/questions/catalan_2.txt', // Archivo para nivel de dificultad 2
            3 => '../assets/questions/catalan_3.txt', // Archivo para nivel de dificultad 3
            4 => '../assets/questions/catalan_4.txt', // Archivo para nivel de dificultad 4
            5 => '../assets/questions/catalan_5.txt', // Archivo para nivel de dificultad 5
            6 => '../assets/questions/catalan_6.txt', // Archivo para nivel de dificultad 6
        ];
    }else  if ($_SESSION['lang'] == 'es'){
        $archivosPorDificultad = [
            1 => '../assets/questions/spanish_1.txt', // Archivo para nivel de dificultad 1
            2 => '../assets/questions/spanish_2.txt', // Archivo para nivel de dificultad 2
            3 => '../assets/questions/spanish_3.txt', // Archivo para nivel de dificultad 3
            4 => '../assets/questions/spanish_4.txt', // Archivo para nivel de dificultad 4
            5 => '../assets/questions/spanish_5.txt', // Archivo para nivel de dificultad 5
            6 => '../assets/questions/spanish_6.txt', // Archivo para nivel de dificultad 6
        ];
    }else  if ($_SESSION['lang'] == 'en'){
        $archivosPorDificultad = [
            1 => '../assets/questions/english_1.txt', // Archivo para nivel de dificultad 1
            2 => '../assets/questions/english_2.txt', // Archivo para nivel de dificultad 2
            3 => '../assets/questions/english_3.txt', // Archivo para nivel de dificultad 3
            4 => '../assets/questions/english_4.txt', // Archivo para nivel de dificultad 4
            5 => '../assets/questions/english_5.txt', // Archivo para nivel de dificultad 5
            6 => '../assets/questions/english_6.txt', // Archivo para nivel de dificultad 6
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

function mostrarPreguntaRespuestas($pregunta, $respuestas, $respuestaCorrecta) {    
    echo "<h1 class='contenidoPregunta'>$pregunta</h1>\n"; // Imprime la pregunta como texto en negrita

    echo '<div class="respuestas">'; // Abre un contenedor para las respuestas

    // Recorre las respuestas
    foreach ($respuestas as $i => $respuesta) {
        $respuestaTexto = $respuesta['respuesta']; // Extrae el texto de la respuesta

        echo '<button class="contenidoRespuesta" onclick="verificarRespuesta(\'' . $respuestaTexto . '\', \'' . $respuestaCorrecta . '\', this)"> ' . $respuestaTexto . '</button>';
        // Imprime un botón que muestra la respuesta y llama a la función verificarRespuesta al hacer clic
    }

    echo '</div>'; // Cierra el contenedor de respuestas
    
    echo '<div id="mensaje_respuesta"></div>'; // Aquí se mostrará el mensaje de respuesta
}



function cargarPreguntas($nombre_archivo) {
    // Comprueba si el archivo existe
    if (file_exists($nombre_archivo)) {
        // Abre el archivo en modo lectura
        $archivo = fopen($nombre_archivo, 'r');

        if ($archivo) {
            $preguntas_respuestas = [];  // Array para almacenar preguntas y respuestas
            $pregunta = '';  // Variable para almacenar la pregunta actual
            $respuestas = [];  // Array para almacenar las respuestas
            $respuestaCorrecta = '';  // Variable para almacenar la respuesta correcta

            // Recorre el archivo línea por línea
            while (($linea = fgets($archivo)) !== false) {
                $linea = trim($linea); // Elimina espacios en blanco al principio y al final de la línea

                if (empty($linea)) {
                    continue; // Salta las líneas en blanco
                }

                if (substr($linea, 0, 1) === '*') {
                    // Nueva pregunta
                    if (!empty($pregunta)) {
                        // Agrega la pregunta y respuestas al array
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

            echo '<div id="preguntas_contenedor">'; // Abre el contenedor principal para todas las preguntas
            foreach ($preguntas_respuestas as $i => $pregunta_respuestas) {
                $pregunta = $pregunta_respuestas['pregunta']; // Extrae la pregunta actual
                $respuestas = $pregunta_respuestas['respuestas']; // Extrae las respuestas de la pregunta
                $respuestaCorrecta = $pregunta_respuestas['respuestaCorrecta']; // Extrae la respuesta correcta
            
                echo '<div class="cosas" id="pregunta_' . ($i + 1) . '" style="display: ' . ($i === 0 ? 'block' : 'none') . ';">'; // Abre un div para una pregunta
                mostrarPreguntaRespuestas($pregunta, $respuestas, $respuestaCorrecta); // Llama a la función para mostrar la pregunta y respuestas
                echo '</div>'; // Cierra el div de la pregunta
                echo '<div class=""></div>';

                
            }
            echo '</div>'; // Cierra el contenedor principal de todas las preguntas
            
        } else {
            echo "No se pudo abrir el archivo.";
        }
    } else {
        echo "El archivo no existe.";
    }
}


if ($dificultad > 6) {
    // Verifica si la dificultad actual es mayor que 6 (condición de victoria)
    echo "<script>window.location = 'win.php';</script>";
    // Redirige al jugador a la página de victoria
    exit;
    // Detiene la ejecución del script para evitar que el juego continúe
}

// Si la dificultad no es mayor que 6, el juego continúa:

$archivoSeleccionado = obtenerArchivoSegunDificultad($dificultad);
// Selecciona el archivo de preguntas según la dificultad actual
cargarPreguntas($archivoSeleccionado);
// Carga y muestra las preguntas desde el archivo seleccionado

// A continuación, se crea un formulario para permitir al jugador avanzar a la siguiente pregunta:

?>
<form class="formNextQuestions" method="post" action="game.php">
  <input type="hidden" name="aumentar_dificultad" value="1">
  <!-- Campo oculto que indica la intención de aumentar la dificultad -->
  <input type="submit" value=" <?php echo $lang['nextQuestions']; ?>" class="oculto">
  <!-- Botón de envío con texto "Siguiente Pregunta" (depende del idioma) -->
</form>

<script src="../assets/scripts/juego.js"></script>
</body>
</html>