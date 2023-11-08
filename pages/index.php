<?php
session_start();
session_destroy();
session_start();
if (isset($_GET['lang']) && ($_GET['lang'] == 'es' || $_GET['lang'] == 'ca' || $_GET['lang'] == 'en')) {
    $_SESSION['lang'] = $_GET['lang'];
} else {
    // if (!isset($_SESSION['lang']) && !($_SESSION['lang'] == 'es' || $_SESSION['lang'] == 'ca' || $_SESSION['lang'] == 'en')) {
    $_SESSION['lang'] = 'en';
    // }
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
    <script src="https://kit.fontawesome.com/74ec47558a.js" crossorigin="anonymous"></script>
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
    <div class="divLogin">
        <a href="login.php" class="login"><?php echo $lang['instructions']['login']; ?></a>
    </div>
    <div id="banner">
        <img src="../assets/images/LOGO_QQSM.png" alt="Banner">
    </div>
    <h1 class="h1Titulo">
        <?php echo $lang['tittle']; ?>
    </h1>
    <div id="ul-container">
        <ul>
            <li>
                <!-- class="fa fa-hand-o-right" aria-hidden="true" -->
                <span class="icon-container">
                    <i class="fa fa-hand-o-right"></i>
                </span>
                <span class="text-container">
                    <?php echo $lang['instructions']['0']; ?>
                </span>
            </li>
            <li>
                <span class="icon-container">
                    <i class="fa fa-hand-o-right"></i>
                </span>
                <span class="text-container">
                    <?php echo $lang['instructions']['1']; ?>
                </span>
            </li>
            <li>
                <span class="icon-container">
                    <i class="fa fa-hand-o-right"></i>
                </span>
                <span class="text-container">
                    <?php echo $lang['instructions']['2']; ?>
                </span>
            </li>
            <li>
                <span class="icon-container">
                    <i class="fa fa-hand-o-right"></i>
                </span>
                <span class="text-container">
                    <?php echo $lang['instructions']['3']; ?>
                </span>
            </li>
            <li>
                <span class="icon-container">
                    <i class="fa fa-hand-o-right"></i>
                </span>
                <span class="text-container">
                    <?php echo $lang['instructions']['4']; ?>
                </span>

            </li>
        </ul>
    </div>
    <div class="buttonsGroup">
        <a href="game.php" class="button">
            <?php echo $lang['buttons']['play']; ?>
        </a>
        <a href="ranking.php" class="button">
            <?php echo $lang['buttons']['hallOfFameButton']; ?>
        </a>
    </div>

    <div id="languages">
        <a href="index.php?lang=es">
            <img id="es" src="../assets/images/spainFlag.jpg" alt="Spanish flag">
        </a>
        <a href="index.php?lang=en">
            <img id="en" src="../assets/images/UKFlag.jpg" alt="England flag">
        </a>
        <a href="index.php?lang=ca">
            <img id="ca" src="../assets/images/cataloniaFlag.jpg" alt="Catalan flag">
        </a>
    </div>
</body>

</html>