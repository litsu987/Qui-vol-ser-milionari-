<?php
session_start();
if (!isset($_SESSION['lang']) && !($_SESSION['lang'] == 'es' || $_SESSION['lang'] == 'ca' || $_SESSION['lang'] == 'en')) {
    $_SESSION['lang'] = 'en';
}
include '../assets/language/' . $_SESSION['lang'] . '.php';
$_SESSION['score'] = 13;
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
        <?php echo $lang['messages']['win']; ?>
    </h1>
    <h3>
        <?php echo $lang['messages']['score']; ?>
    </h3>
    <div id="publishQuestion">
        <h5>
            <?php echo $lang['messages']['publishScore']; ?>
        </h5>
        <button id="yesPublish" class="button">
            <?php echo $lang['buttons']['yes']; ?>
        </button>
        <button id="noPublish" class="button">
            <?php echo $lang['buttons']['no']; ?>
        </button>
    </div>

    <div id="nameAndPublishDiv">
        <form action="saveScore.php.php" method="post">
            <input type="text" id="name" name="name" placeholder="<?php echo $lang['namePlaceholder']; ?>" required>
            <input type="hidden" id="currentDate" name="currentDate" value="">
            <button type="submit" class="button" id="publishButton">
                <?php echo $lang['buttons']['publishButton']; ?>
                <i class="fas fa-upload"></i>
            </button>
        </form>
    </div>

    <img src="" alt="victoryImage">
    <a href="ranking.php" class="button">
        <?php echo $lang['buttons']['hallOfFameButton']; ?>
    </a>
    <a href="index.php" class="button">
        <?php echo $lang['buttons']['toStart']; ?>
    </a>

    <script src="../assets/scripts/script.js"></script>

</body>

</html>