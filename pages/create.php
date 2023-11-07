<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["user"])) {
    // Redirigir a la página de inicio de sesión
    header("Location: login.php");
    exit();
}
if (isset($_GET['lang']) && ($_GET['lang'] == 'es' || $_GET['lang'] == 'ca' || $_GET['lang'] == 'en')) {
    $_SESSION['lang'] = $_GET['lang'];
} else {
    if (!isset($_SESSION['lang']) && !($_SESSION['lang'] == 'es' || $_SESSION['lang'] == 'ca' || $_SESSION['lang'] == 'en')) {
        $_SESSION['lang'] = 'en';
    }
}
include '../assets/language/' . $_SESSION['lang'] . '.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pregunta = $_POST["pregunta"];
    $respuestas = array(
        "+ " . $_POST["respuestaCorrecta"],
        "- " . $_POST["respuestaIncorrecta1"],
        "- " . $_POST["respuestaIncorrecta2"],
        "- " . $_POST["respuestaIncorrecta3"]
    );

    // Mezcla las respuestas para aleatorizar su orden
    shuffle($respuestas);

    $archivo_seleccionado = $_POST["archivo"];

    if (!empty($pregunta) && !empty($archivo_seleccionado) && !empty($respuestas)) {
        // Abre el archivo seleccionado en modo append (añadir contenido al final)
        $archivo = fopen($archivo_seleccionado, "a");

        fwrite($archivo, PHP_EOL);
        // Escribe la pregunta en el archivo
        fwrite($archivo, "* " . $pregunta . PHP_EOL);

        // Escribe las respuestas en el archivo
        foreach ($respuestas as $respuesta) {
            fwrite($archivo, $respuesta . PHP_EOL );
        }

        // Cierra el archivo
        fclose($archivo);

        $_SESSION["mensaje"]  = "<div id='alertQuestion' class='demo-preview'>
                        <div class='alert alert-success alert-dismissable fade in'>
                        <button aria-label='Close' onclick=\"document.getElementById('alertQuestion').style.display='none';\" class='close spanX'><i class='fa-regular fa-circle-xmark' style='color: #ffffff;'></i></button>
                        <strong>La pregunta se ha guardado correctamente!</strong>
                        </div>
                    </div>";
         

    }

    // Redirecciona a la página del formulario
    header("Location: create.php");
    exit();
}
$mensaje = isset($_SESSION["mensaje"]) ? $_SESSION["mensaje"] : "";
unset($_SESSION["mensaje"]); // Limpiar el mensaje después de mostrarlo
?>
<!DOCTYPE html>
<html lang="en">
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
    <div><?php echo $mensaje; ?></div>  
    <div class="login-box">
        <h1><?php echo $lang['create']['title']; ?></h1>
        <form action="create.php" method="post" class="">
            <div class="divSelect">
                
            </div>
            <div class="user-box">
                <input type="text" name="pregunta" id="" class="" required>
                <label for="pregunta"><?php echo $lang['create']['newQuestion']; ?><label>
            </div>
            <div class="user-box">
                <input type="text" name="respuestaCorrecta" id="" required>
                <label for="respuestaCorrecta"><?php echo $lang['create']['corectQuestion']; ?></label>
            </div>
            <div class="user-box">
                <input type="text" name="respuestaIncorrecta1" id="" required>
                <label for="respuestaIncorrecta1"><?php echo $lang['create']['incorectQuestion1']; ?></label>
            </div>
            <div class="user-box">
                <input type="text" name="respuestaIncorrecta2" id="" required>
                <label for="respuestaIncorrecta2"><?php echo $lang['create']['incorectQuestion2']; ?></label>
            </div>
            <div class="user-box">
                <input type="text" name="respuestaIncorrecta3" id="" required>
                <label for="respuestaIncorrecta3"><?php echo $lang['create']['incorectQuestion3']; ?></label>          
            </div>
            <input type="submit" value="<?php echo $lang['create']['submit']; ?>" class="enviarPregunta">
        </form>
    </div>
</body>
</html>