<?php
session_start();
if (isset($_GET['lang']) && ($_GET['lang'] == 'es' || $_GET['lang'] == 'ca' || $_GET['lang'] == 'en')) {
    $_SESSION['lang'] = $_GET['lang'];
} else {
    $_SESSION['lang'] = 'en';
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
</head>

<body>
    <div>
        <img src="" alt="Header">
    </div>
    <h1>
        <?php echo $lang['tittle']; ?>
    </h1>
    <ul>
        <li>
            <?php echo $lang['instructions']['0']; ?>
        </li>
        <li>
            <?php echo $lang['instructions']['1']; ?>
        </li>
        <li>
            <?php echo $lang['instructions']['2']; ?>
        </li>
        <li>
            <?php echo $lang['instructions']['3']; ?>
        </li>
        <li>
            <?php echo $lang['instructions']['4']; ?>
        </li>
    </ul>

    <a href="win.php" class="button">
        <?php echo $lang['buttons']['play']; ?>
    </a>
    <a href="ranking.php" class="button">
        <?php echo $lang['buttons']['hallOfFameButton']; ?>
    </a>

    <div id="languages">
        <a href="index.php?lang=ca">
            <img id="ca" src="../assets/images/cataloniaFlag.jpg" alt="Catalan flag">
        </a>
        <a href="index.php?lang=es">
            <img id="es" src="../assets/images/spainFlag.jpg" alt="Spanish flag">
        </a>
        <a href="index.php?lang=en">
            <img id="en" src="../assets/images/UKFlag.jpg" alt="England flag">
        </a>

    </div>
</body>

</html>