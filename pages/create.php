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
    <div class="">
        <form action="" method="get">
            <div class="">
                <label for="pregunta">NUEVA PREGUNTA<label></br>
                <input type="text" name="pregunta" id="" class="input" required>
            </div>
            <div>
                <label for="respuestaCorrecta">Respuesta correcta</label></br>
                <input type="text" name="respuestaCorrecta" id="">
            </div>
            <div>
                <label for="respuestaIncorrecta1">Primera respuesta incorrecta</label></br>
                <input type="text" name="respuestaIncorrecta2" id="">
            </div>
            <div>
                <label for="respuestaIncorrecta2">Segunda respuesta incorrecta</label></br>
                <input type="text" name="respuestaIncorrecta2" id="">
            </div>
            <div>
                <label for="respuestaIncorrecta3">Tercera respuesta incorrecta</label></br>
                <input type="text" name="respuestaIncorrecta3" id="">
            </div>
            <button type="submit">Crear pregunta</button>
        </form>
    </div>
</body>
</html>