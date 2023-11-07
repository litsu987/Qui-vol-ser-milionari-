<?php
session_start();

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
    <link rel="stylesheet" type="text/css" href="../assets/styles/style.css">
    <title>Publish fail</title>
</head>

<body>
    <div class="centrar divExtras">
        <h1>
            <?php echo $lang['messages']['publishFailed']; ?>
        </h1>
        <a href="../assets/scripts/logout.php" class="button">
            <?php echo $lang['buttons']['toStart']; ?>
        </a>
    </div>


</body>

</html>