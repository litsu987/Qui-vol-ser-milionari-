<?php
session_start();
if (isset($_POST["name"]) && (isset($_POST["currentDate"])) && (isset($_SESSION["score"])) && isset($_SESSION[session_id()])) {
    $name = $_POST["name"];
    $currentDate = $_POST["currentDate"];
    $score = $SESSION["score"];
    $sessionId = session_id();


    $data = "$sessionId-;-;-$name-;-;-$score-;-;-$currentDate" . PHP_EOL;

    // Abre el archivo para escritura al final del mismo
    $file = fopen("../../data/records.txt", "a");

    if ($file) {
        // Escribe la información en el archivo
        fwrite($file, $data);
        fclose($file);
    }
}
?>