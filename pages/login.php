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
    <noscript>
        <div id="avisoJS" class="avisoJS" >
            <h1 class="titleNoscript"><?php echo $lang['noscipt']['tittle']; ?></h1>
            <div class="deshabilitado">
                <?php echo $lang['noscipt']['message']; ?>
                <a href="https://support.google.com/adsense/answer/12654?hl" 
                target="_blank"><?php echo $lang['noscipt']['link']; ?></a>.
            </div>
        </div>
        <div id="fondoDesenfocado" class="fondoDesenfocado"></div>
    </noscript>





<?php
    include "usuarios.php";

    $error = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user = $_POST["user"];
        $pass = $_POST["pass"];

        if (isset($usuarios[$user]) && $usuarios[$user] == $pass) {
            session_start();
            $_SESSION["user"] = $user; 
            header("Location: edit.php");
            exit();
        } else {
            $error = "Usuario o contraseña incorrectos. Por favor, intenta nuevamente.";
        }
    }
?>

<div id="divLogin" class="fondo">
    <h1>Iniciar Sesión</h1>
    <form id="login" action="" method="post">
        <label for="user"> Usuario</label><br>
        <input type="text" name="user" required><br><br>
        <label for="pass"> Contraseña</label><br>
        <input type="password" name="pass" required><br><br>
        <button id="btnLogin" type="submit" class="button">Iniciar Sesión</button>
    </form>
    <?php if ($error != "") { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>
</div>




</body>
</html>