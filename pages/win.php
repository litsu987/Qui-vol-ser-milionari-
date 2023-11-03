<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    http_response_code(403);
    echo "<div id='contForbidden'><h1>Error 403 - Forbidden</h1></div>";
    exit;
}

if (isset($_GET['lang']) && ($_GET['lang'] == 'es' || $_GET['lang'] == 'ca' || $_GET['lang'] == 'en')) {
    $_SESSION['lang'] = $_GET['lang'];
} else {
    if (!isset($_SESSION['lang']) && !($_SESSION['lang'] == 'es' || $_SESSION['lang'] == 'ca' || $_SESSION['lang'] == 'en')) {
        $_SESSION['lang'] = 'en';
    }
}
include '../assets/language/' . $_SESSION['lang'] . '.php';

if (isset($_POST['puntaje'])) {
    $puntaje = intval($_POST['puntaje']);
    $_SESSION['score'] = $puntaje;
}

if (isset($_POST['tiempoTranscurrido'])) {
    
    $tiempoTranscurrido = $_POST['tiempoTranscurrido'];
    $_SESSION['tiempoTranscurrido'] = $tiempoTranscurrido;
    
} 

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
</head>

<body onload="soundWinQuestion()" class="bodyLoseWin">
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
    
    <div id="banner" onclick="eggQuuestion()">
        <img src="../assets/images/LOGO_QQSM.png" alt="Banner">
    </div>
    
    <div class="fondo">
        <h1 class="tituloLost centrar h1Titulo">
            <?php echo $lang['messages']['win']; ?>
        </h1>
        <h3 class="centrar score">
            <?php echo $lang['messages']['score'] . $_SESSION['score']; ?>
        </h3>
        <h5 class="centrar ">
            <?php echo $lang['messages']['publishScore']; ?>
        </h5>
        <div id="publishQuestion" class="preguntaPublicar centrar">

            <button id="yesPublish" class="button">
                <?php echo $lang['buttons']['yes']; ?>
            </button>
            <button id="noPublish" class="button">
                <?php echo $lang['buttons']['no']; ?>
            </button>
        </div>

        <div id="nameAndPublishDiv">
            <form action="../assets/scripts/saveScore.php" method="post">
                <input type="text" id="name" name="name" placeholder="<?php echo $lang['namePlaceholder']; ?>" required>
                <input type="hidden" id="currentDate" name="currentDate" value="tiempoTranscurrido">
                <button type="submit" class="button" id="publishButton">
                    <?php echo $lang['buttons']['publishButton']; ?>
                    <i class="fas fa-upload"></i>
                </button>
            </form>
        </div>

        <div class="centrar divExtras">
            <a href="ranking.php" class="button">
                <?php echo $lang['buttons']['hallOfFameButton']; ?>
            </a>
            <a href="index.php" class="button">
                <?php echo $lang['buttons']['toStart']; ?>
            </a>
        </div>

    </div>


    <script src="../assets/scripts/script.js"></script>
    <script src="../assets/scripts/juego.js"></script>
</body>

</html>