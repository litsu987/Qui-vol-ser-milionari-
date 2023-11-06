<?php
    session_start();
    include "usuarios.php";
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
            $error = "";

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $user = $_POST["user"];
                $pass = $_POST["pass"];

                if (isset($usuarios[$user]) && $usuarios[$user] == $pass) {
                    $_SESSION["user"] = $user; 
                    header("Location: edit.php");
                    exit();
                } else {
                    $error = "Usuario o contraseña incorrectos. Por favor, intenta nuevamente.";
                }
            }
        ?>
        <div id="divLogin" class="fondo">
            <h1><?php echo $lang['login']['title']; ?></h1>
            <form id="login" action="" method="post" class="field">
                <div class="form__group">
                    <input type="text" name="user" class="form__field" required>
                    <label for="user" class="form__label"><?php echo $lang['login']['user']; ?></label><br><br>
                </div>
                <div class="form__group">
                    <input type="password" name="pass" class="form__field" required>
                    <label for="pass" class="form__label"><?php echo $lang['login']['pass']; ?></label><br><br>
                </div>
                
                <button id="btnLogin" type="submit" class="buttonLogin css-button-shadow-border-sliding--sky"><?php echo $lang['login']['acces']; ?></button>
            </form>
            <?php if ($error != "") { ?>
                    <p style="color: red;"><?php echo $error; ?></p>
                <?php } ?>
        </div>




    </body>
</html>