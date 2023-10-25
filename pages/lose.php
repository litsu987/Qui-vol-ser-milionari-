<?php
session_start();
if (!isset($_SESSION['lang']) && !($_SESSION['lang'] == 'es' || $_SESSION['lang'] == 'ca' || $_SESSION['lang'] == 'en')) {
    $_SESSION['lang'] = 'en';
}
include '../assets/language/' . $_SESSION['lang'] . '.php';

if (isset($_GET['puntaje'])) {
    $puntaje = intval($_GET['puntaje']);
    $_SESSION['score'] = $puntaje;
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

<body onload="soundLoseQuestion()" class="bodyLoseWin">
    <div class="divImagenPrincipal centrar">
        <img class="imagenPrincipal" src="../assets/images/LOGO_QQSM.jpg" alt="Banner">
    </div>
    <div class="fondo">
        <h1 class="tituloLost centrar">
            <?php echo $lang['messages']['lose']; ?>
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

        <div id="nameAndPublishDiv" >
            <form action="../assets/scripts/saveScore.php" method="post">
                <input type="text" id="name" name="name" placeholder="<?php echo $lang['namePlaceholder']; ?>" required>
                <input type="hidden" id="currentDate" name="currentDate" value="">
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