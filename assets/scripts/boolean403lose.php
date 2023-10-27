<?php
    session_start();
    $_SESSION['booleanError'] = false;
    $_SESSION['puntuacio'] += $_POST['puntuacio'];
    $_SESSION['victories'] += $_POST['victories'];
    $_SESSION['derrotes'] += $_POST['derrotes'];
    $_SESSION['intents'] = $_POST['intents'];
    echo "<script> document.location.href='../../pages/lose.php'; </script>";
?>