<?php
session_start();
if (!isset($_SESSION['lang']) && !($_SESSION['lang'] == 'es' || $_SESSION['lang'] == 'ca' || $_SESSION['lang'] == 'en')) {
    $_SESSION['lang'] = 'en';
}
include 'language/' . $_SESSION['lang'] . '.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $lang['tittle']; ?>
    </title>
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
    <h5>
        <?php echo $lang['messages']['publishScore']; ?>
    </h5>
    <button id="yesPublish" class="button">
        <?php echo $lang['buttons']['yes']; ?>
    </button>
    <button id="noPublish" class="button">
        <?php echo $lang['buttons']['no']; ?>
    </button>

    <div id="nameAndPublishDiv">
        <input type="text" id="name" placeholder="<?php echo $lang['namePlaceholder']; ?>">
        <button id="publishButton" class="button">
            <?php echo $lang['buttons']['publishButton']; ?>
        </button>
    </div>


</body>

</html>