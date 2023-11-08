<?php
session_start();
error_reporting(0);
if (isset($_GET['lang']) && ($_GET['lang'] == 'es' || $_GET['lang'] == 'ca' || $_GET['lang'] == 'en')) {
    $_SESSION['lang'] = $_GET['lang'];
} else {
    if (!isset($_SESSION['lang']) && !($_SESSION['lang'] == 'es' || $_SESSION['lang'] == 'ca' || $_SESSION['lang'] == 'en')) {
        $_SESSION['lang'] = 'en';
    }
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
    }
} else {
    $_SESSION['nivel_dificultad'] = 1;
    $_SESSION['pregunta_actual'] = 0;
}

$dificultad = $_SESSION['nivel_dificultad'];

if (isset($_POST['pregunta_actual'])) {
    $_SESSION['pregunta_actual'] = $_POST['pregunta_actual'];
}



function comodinPublico()
{
    $random = rand(0, 100);

    if ($random <= 80) {
        // La respuesta simulada del público es la respuesta correcta
        return "La mayoría del público cree que la respuesta correcta es A.";
    } else {
        // La respuesta simulada del público es una respuesta incorrecta aleatoria
        $respuestasIncorrectas = array("B", "C", "D");
        $respuestaSimulada = $respuestasIncorrectas[array_rand($respuestasIncorrectas)];
        return "La mayoría del público cree que la respuesta correcta es " . $respuestaSimulada . ".";
    }
}

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <noscript>
        <div id="avisoJS" class="avisoJS">
            <h1 class="titleNoscript">
                <?php echo $lang['noscipt']['tittle']; ?>
            </h1>
            <div class="deshabilitado">
                <?php echo $lang['noscipt']['message']; ?>
                <a href="https://support.google.com/adsense/answer/12654?hl" target="_blank">
                    <?php echo $lang['noscipt']['link']; ?>
                </a>.
            </div>
        </div>
        <div id="fondoDesenfocado" class="fondoDesenfocado"></div>
    </noscript>
    <div id="cronometro2">
        <h1 id="cronometro">-:--</h1>

    </div>
    <?php
    ?>
    <div id="modal" class="swirl-in-fwd modal">
        <div class="modal-contenido">
            <span class="cerrar" onclick="cerrarModal()">&times;</span>
            <canvas id="modal-contenido"></canvas>

            <div id="diagrama" class="diagrama"></div>
        </div>
    </div>

    <div id="barras"></div>



    <div class="comodines">
        <button id="btnEliminarRespuestas" class="comTiempo oval ovalBackground">50 : 50</button>
        <button id="comodin-publico" onclick="soundAnimation()" class="comPublico oval ovalBackground"><i
                class="fa-solid fa-users" style="color: #ffffff;"></i></button>
        <button id="comodin-llamada" class="comPublico oval ovalBackground">+30'</button>
    </div>




    <div class="bannerMillonario">
        <img class="bannerImagen" src="../assets/images/presentador.png" alt="Banner">
    </div>

    <h3 class="lvlDifivultad">
        <?php echo $lang['messages']['dificultLvl']; ?> :
        <?php echo $_SESSION['nivel_dificultad']; ?>
    </h3>

    <div id="nivel-dificultad" data-nivel="<?php echo $_SESSION['nivel_dificultad']; ?>"></div>

    <?php




    function obtenerArchivoSegunDificultad($nivelDificultad)
    {
        if ($_SESSION['lang'] == 'ca') {
            return '../assets/questions/catalan_' . $_SESSION['nivel_dificultad'] . '.txt';
        } else if ($_SESSION['lang'] == 'es') {
            return '../assets/questions/spanish_' . $_SESSION['nivel_dificultad'] . '.txt';
        } else if ($_SESSION['lang'] == 'en') {
            return '../assets/questions/english_' . $_SESSION['nivel_dificultad'] . '.txt';
        }
    }

    function mostrarPreguntaRespuestas($pregunta, $respuestas, $respuestaCorrecta)
    {
        // echo "<div class='preguntasRespuestasDiv'>";
        echo "<h1 class='contenidoPregunta'>$pregunta</h1>\n"; // Imprime la pregunta como texto en negrita
        echo '<div class="respuestas">'; // Abre un contenedor para las respuestas
        // Recorre las respuestas
    
        foreach ($respuestas as $i => $respuesta) {
            $respuestaTexto = $respuesta['respuesta']; // Extrae el texto de la respuesta
            echo '<button class="contenidoRespuesta backgroundContenidoRespuesta" data-respuesta-correcta="' . $respuestaCorrecta . '" onclick="verificarRespuesta(\'' . $respuestaTexto . '\', \'' . $respuestaCorrecta . '\', this)"> ' . $respuestaTexto . '</button>';
            // Imprime un botón que muestra la respuesta y llama a la función verificarRespuesta al hacer clic
        }

        echo '</div>'; // Cierra el contenedor de respuestas
        // echo "</div>";
    
        echo '<div id="mensaje_respuesta"></div>'; // Aquí se mostrará el mensaje de respuesta
    
    }





    function getQuestions($fileName)
    {
        // echo "<br>=====================================================================================<br>";
    
        if (file_exists($fileName)) {
            $archivo = fopen($fileName, 'r');
            if ($archivo) {
                $questionsArray = [];
                while (($linea = fgets($archivo)) !== false) {
                    $linea = trim($linea); // Elimina espacios en blanco al principio y al final de la línea
                    if (empty($linea)) {
                        continue; // Salta las líneas en blanco
                    }
                    if (substr($linea, 0, 1) === '*') {
                        $question = rtrim(substr($linea, 2), "?");
                        // echo "<br>//////////question ---> " . $question;
                        $questionsArray[] = $question;

                    }
                }
                // echo "<br>=====================================================================================<br>";
    
                fclose($archivo);
                return $questionsArray;
            }
        }
        // echo "<br>=====================================================================================<br>";
    
        return null;
    }
    function getFileNamesWithoutExtension($dir)
    {
        $filesArray = scandir($dir);
        foreach ($filesArray as $file) {
        }
        $namesWithoutExtension = array_map(function ($file) {
            return pathinfo($file, PATHINFO_FILENAME);
        }, $filesArray);
        return array_filter($namesWithoutExtension);
    }

    function getEnglishQuestion($dificultLvl, $question)
    {
        $question = trim(rtrim($question, "?"));
        $QuestionsFileName = '../assets/questions/english_' . $_SESSION['nivel_dificultad'] . '.txt';
        $arrayQuestions = getQuestions($QuestionsFileName);


        if ($_SESSION['lang'] != 'en') {
            $userQuestionsFileName = obtenerArchivoSegunDificultad($dificultLvl);
            $UserArrayQuestions = getQuestions($userQuestionsFileName);
            for ($i = 0; $i < count($arrayQuestions); $i++) {
                // echo "question ---> " . $UserArrayQuestions[$i];
                // echo " index ---> $i<br>";
            }
            $index = array_search($question, $UserArrayQuestions);
            $question = $arrayQuestions[$index];
            // echo "<br>question ---> " . $question;
            // echo " index ---> $index<br>";
        }
        $question = str_replace('"', '', $question);
        return $question;
    }
    function imgExists($dificultLvl, $englishQuestion)
    {
        $imgArray = getFileNamesWithoutExtension("../assets/images/questionPictures/$dificultLvl");

        // Convertir $englishQuestion a minúsculas y eliminar espacios en blanco
        $englishQuestion = strtolower(trim((rtrim($englishQuestion, " "))));

        for ($i = 2; $i < count($imgArray) + 1; $i++) {
            // Convertir $imgArray[$i] a minúsculas y eliminar espacios en blanco
            $imgArray[$i] = strtolower(trim((rtrim($imgArray[$i], " "))));
            if ($imgArray[$i] == $englishQuestion) {
            }
        }

        return in_array($englishQuestion, $imgArray);
    }




    function getfileImgName($dificultLvl, $englishQuestion)
    {
        $imgDir = "../assets/images/questionPictures/$dificultLvl";
        $imgFilesArray = scandir($imgDir);

        foreach ($imgFilesArray as $img) {
            // echo " img -->" . pathinfo($img, PATHINFO_FILENAME); // Muestra el nombre del archivo sin la extensión
    
            if (pathinfo($img, PATHINFO_FILENAME) === $englishQuestion) {
                return $img;
            }
        }

        return false;
    }

    function cargarPreguntas($nombre_archivo)
    {
        // Comprueba si el archivo existe
        if (file_exists($nombre_archivo)) {
            // Abre el archivo en modo lectura
            $archivo = fopen($nombre_archivo, 'r');

            if ($archivo) {
                $preguntas_respuestas = []; // Array para almacenar preguntas y respuestas
                $pregunta = ''; // Variable para almacenar la pregunta actual
                $respuestas = []; // Array para almacenar las respuestas
                $respuestaCorrecta = ''; // Variable para almacenar la respuesta correcta
    
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

                if (!empty($pregunta)) {
                    $preguntas_respuestas[] = [
                        'pregunta' => $pregunta,
                        'respuestas' => $respuestas,
                        'respuestaCorrecta' => $respuestaCorrecta,
                    ];
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
                    $respuestaCorrecta = $pregunta_respuestas['respuestaCorrecta'];
                    echo '<div id="pregunta_' . ($i + 1) . '" style="display: ' . ($i === 0 ? 'block' : 'none') . ';">'; // Abre un div para una pregunta
                    echo "<div id='cronoPregunta_$i'class='CronoPregunta' style='display: none;'></div>";
                    mostrarPreguntaRespuestas($pregunta, $respuestas, $respuestaCorrecta); // Llama a la función para mostrar la pregunta y respuestas
    

                    $englishQuestion = getEnglishQuestion($_SESSION['nivel_dificultad'], $pregunta);
                    if (imgExists($_SESSION['nivel_dificultad'], $englishQuestion)) {
                        $imgName = getfileImgName($_SESSION['nivel_dificultad'], $englishQuestion);
                        echo "<div id='divImgQuestion'><img class='questionImg' src='../assets/images/questionPictures/{$_SESSION['nivel_dificultad']}/$imgName' alt='Imagen relacionada a la pregunta'></div>";
                    }

                    echo '</div>'; // Cierra el div de la pregunta
    
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
    <form method="post" action="game.php">
        <input type="hidden" name="aumentar_dificultad" value="1">
        <!-- Campo oculto que indica la intención de aumentar la dificultad -->
        <div class="centrar">
            <input id="nextQuestions" type="submit" value=" <?php echo $lang['nextQuestions']; ?>" class="oculto">
        </div>

        <!-- Botón de envío con texto "Siguiente Pregunta" (depende del idioma) -->
    </form>

    <script src="../assets/scripts/juego.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>

</html>