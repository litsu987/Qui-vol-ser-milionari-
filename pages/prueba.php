<?php
session_start();
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

        // foreach ($userArrayQuestions as $arrayQueston) {
        //     echo "<br>arrayQueston -> " . $arrayQueston;
        //     echo "<br>question -> " . $question . '<br>';
        //     if ($arrayQueston === $question) {
        //         echo 'true';
        //     }
        // }
        if ($index !== false) {
            echo '<br>index -> ' . $index;
            $question = $englishArrayQuestions[$index]; // Pregunta en inglés correspondiente al índice.
        } else {
            echo '<br>no tengo index';
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

        // echo "<br>imgNameWithoutExtension -> " . $imgNameWithoutExtension;
        // echo "<br>englishQuestion -> " . $englishQuestion . '<br>';

        // Compara la pregunta (sin espacios y en minúsculas) con el nombre de archivo (sin la extension, sin espacios y en minúsculas)
        if ($imgNameWithoutExtension === $englishQuestion) {
            echo 'hay concidencias';
            return $img; // Retorna el nombre del archivo de imagen
        }
    }
    echo 'no hay concidencias';

    return false;
}

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
    $_SESSION['lang'] = 'en';
    $_SESSION['nivel_dificultad'] = 1;
    $question = ' How Many Years Did The Aborigines Live?';
    $englishQuestion = getEnglishQuestion($question);
    if ($englishQuestion) {
        echo 'fsdfs';
        $img = getImgInfo($englishQuestion);
        if ($img) {
            echo "<div id='divImgQuestion'><img class='questionImg' src='../assets/images/questionPictures/{$_SESSION['nivel_dificultad']}/$img' alt='Imagen relacionada a la pregunta'></div>";
        }
    }
    ?>
</body>

</html>