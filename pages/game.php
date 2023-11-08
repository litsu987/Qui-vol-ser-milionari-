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

if (isset($_POST['pregunta_actual'])) {
    $_SESSION['pregunta_actual'] = $_POST['pregunta_actual'];
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

<body class="game">
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

    <div id="divTiempoTotal">
        <h4>
            <?php echo $lang['messages']['generalTimer']; ?>
        </h4>
        <h1 id="tiempoTotal">--:--</h1>
    </div>

    <div id="modal" class="swirl-in-fwd modal">
        <div class="modal-contenido">
            <span class="cerrar" onclick="cerrarModal()">&times;</span>
            <canvas id="modal-contenido"></canvas>

            <div id="diagrama" class="diagrama"></div>
        </div>
    </div>

    <div class="comodines">
        <button id="btnEliminarRespuestas" class="comTiempo oval ovalBackground">50 : 50</button>
        <button id="comodin-publico" onclick="soundAnimation()" class="comPublico oval ovalBackground"><i
                class="fa-solid fa-users" style="color: #ffffff;"></i></button>
        <button id="comodin-llamada" class="comPublico oval ovalBackground">+30'</button>
        <button id="comodin-telefono" class="comPublico oval ovalBackground">telefono</button>
    </div>

    <div id="minijuego" class="minijuego" style="display: none;">
        <div class="minijuego-contenido">
            <input type="number" id="respuestaUsuario" class="textUser" style="display: none;" placeholder="Ingresa tu respuesta"
                pattern="[0-9]*" />
            <button id="botonComprobar" class="button" style="display: none;" onclick="comprobarRespuesta()">Comprobar</button>
            <button id="botonSalir" class="button" style="display: none;" onclick="cerrarMinijuego()">Salir</button>
            <div id="contenedorMini">
                <h1 id="resultadoMinijuego" style="display: none;"></h1>
            </div>
        </div>
        
    </div>

    <div class="bannerMillonario">
        <img class="bannerImagen" src="../assets/images/presentador.png" alt="Banner">
    </div>

    <h3 class="lvlDifivultad" id="nivel-dificultad" data-nivel="<?php echo $_SESSION['nivel_dificultad'] ?>">
        <?php echo $lang['messages']['dificultLvl'] . ": " . $_SESSION['nivel_dificultad'] ?>
    </h3>

    <div id="puntuacionDiv">
        <h4>
            <?php echo $lang['messages']['generalScore']; ?>
        </h4>
        <h1 id="puntuacion">0</h1>
    </div>

    <?php
    function getQuestionsFilepath()
    {
        if ($_SESSION['lang'] == 'ca') {
            return '../assets/questions/catalan_' . $_SESSION['nivel_dificultad'] . '.txt';
        } else if ($_SESSION['lang'] == 'es') {
            return '../assets/questions/spanish_' . $_SESSION['nivel_dificultad'] . '.txt';
        } else if ($_SESSION['lang'] == 'en') {
            return '../assets/questions/english_' . $_SESSION['nivel_dificultad'] . '.txt';
        }
    }

    // funcion para imprimir las preguntas con sus respuestas
    function mostrarPreguntaRespuestas($pregunta, $respuestas, $respuestaCorrecta)
    {
        // echo "<div class='preguntasRespuestasDiv'>";
        echo "<h1 class='contenidoPregunta'>$pregunta</h1>\n";
        echo '<div class="respuestas">';

        foreach ($respuestas as $i => $respuesta) {
            $respuestaTexto = $respuesta['respuesta']; // Extrae el texto de la respuesta
            echo '<button class="contenidoRespuesta backgroundContenidoRespuesta" data-respuesta-correcta="' . $respuestaCorrecta . '" onclick="verificarRespuesta(\'' . $respuestaTexto . '\', \'' . $respuestaCorrecta . '\', this)"> ' . $respuestaTexto . '</button>';
            // Imprime un botón que muestra la respuesta y llama a la función verificarRespuesta al hacer clic
        }
        echo '</div>';
    }

    function getQuestions($fileName)
    {
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
                        $questionsArray[] = trim(strtolower(str_replace(['"', ' ', '?', '¿'], '', substr($linea, 2))));
                    }
                }
                fclose($archivo);
                return $questionsArray;
            }
        }
        return null;
    }

    // Función para obtener la versión en inglés de una pregunta
    function getEnglishQuestion($question)
    {
        $question = trim(strtolower(str_replace(['"', ' ', '?', '¿'], '', $question)));
        // devuelve la pregunta sin espacios al principio y al final y sin el interrogante
    
        $englishQuestionsFileName = '../assets/questions/english_' . $_SESSION['nivel_dificultad'] . '.txt';
        $englishArrayQuestions = getQuestions($englishQuestionsFileName);

        // Si el idioma seleccionado por el usuario no es inglés, se crea un array de preguntas en el idioma elegido.
        // Dado que los arrays de preguntas mantienen el mismo orden independientemente del idioma, se obtiene el índice de posición
        // en el array que contiene la pregunta actual ($question). Luego, se extrae la pregunta en inglés del array de preguntas en inglés
        // utilizando el índice de posición.
        if ($_SESSION['lang'] != 'en') {
            $userQuestionsFilepath = getQuestionsFilepath(); // Ruta del archivo de preguntas en el idioma seleccionado.
            $userArrayQuestions = getQuestions($userQuestionsFilepath); // Array con las preguntas en el idioma seleccionado.
            $index = array_search($question, $userArrayQuestions); // Índice de la pregunta actual en el array seleccionado.
    
            if ($index !== false) {
                $question = $englishArrayQuestions[$index]; // Pregunta en inglés correspondiente al índice.
            } else {
                return false;
            }
        }

        $question = trim(strtolower(str_replace(['"', ' '], '', $question)));
        return $question;
    }

    // Función para buscar una imagen relacionada con una pregunta en inglés
    function getImgInfo($englishQuestion)
    {
        $imgDir = "../assets/images/questionPictures/" . $_SESSION['nivel_dificultad'];
        $imgFilesArray = scandir($imgDir);

        foreach ($imgFilesArray as $img) {
            $imgNameWithoutExtension = pathinfo($img, PATHINFO_FILENAME);
            $imgNameWithoutExtension = trim(strtolower(str_replace(['"', ' '], '', $imgNameWithoutExtension)));

            // Compara la pregunta (sin espacios y en minúsculas) con el nombre de archivo (sin la extension, sin espacios y en minúsculas)
            if ($imgNameWithoutExtension === $englishQuestion) {
                return $img; // Retorna el nombre del archivo de imagen
            }
        }
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
                    echo '<div class="preguntas" id="pregunta_' . ($i + 1) . '" style="display: ' . ($i === 0 ? 'block' : 'none') . ';">'; // Abre un div para una pregunta
                    echo "<div id='cronoPregunta_$i'class='CronoPregunta' style='display: none'></div>";

                    mostrarPreguntaRespuestas($pregunta, $respuestas, $respuestaCorrecta); // Llama a la función para mostrar la pregunta y respuestas
    
                    $englishQuestion = getEnglishQuestion($pregunta);
                    if ($englishQuestion) {
                        $img = getImgInfo($englishQuestion);
                        if ($img) {
                            echo "<div id='divImgQuestion'><img class='questionImg' src='../assets/images/questionPictures/{$_SESSION['nivel_dificultad']}/$img' alt='Imagen relacionada a la pregunta'></div>";
                        }
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
    // Verifica si la dificultad actual es mayor que 6 (condición de victoria)
    if ($_SESSION['nivel_dificultad'] > 6) {
        echo "<script>window.location = 'win.php';</script>"; // Redirige al jugador a la página de victoria
        exit; // Detiene la ejecución del script para evitar que el juego continúe
    }

    $archivoSeleccionado = getQuestionsFilepath(); // Selecciona el archivo de preguntas según la dificultad actual
    
    cargarPreguntas($archivoSeleccionado);
    // Carga y muestra las preguntas desde el archivo seleccionado
    
    // A continuación, se crea un formulario para permitir al jugador avanzar a la siguiente pregunta:
    
    ?>
    <form method="post" action="game.php">
        <input type="hidden" name="aumentar_dificultad" value="1">
        <div class="centrar">
            <input id="nextQuestions" type="submit" value=" <?php echo $lang['nextQuestions']; ?>"
                class="oculto button">
        </div>
    </form>

    <script src="../assets/scripts/juego.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>

</html>
