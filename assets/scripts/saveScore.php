<?php
session_start();
if (isset($_POST["name"]) && (isset($_POST["currentDate"])) && (isset($_SESSION["score"]))&& (isset($_SESSION['tiempoTranscurrido']))){

    $name = $_POST["name"];
    $currentDate = $_POST["currentDate"];
    $score = $_SESSION["score"];
    $sessionId = session_id();
    $tiempo = $_SESSION['tiempoTranscurrido'];
    $minutos = $tiempo / 60; // Calcula los minutos

    if ($_SESSION["score"] == 18){
        if ($tiempo <= 300){
            $score=$score+5;
        }else if ($tiempo <= 600){
            $score=$score+3;
        }else if ($tiempo <= 900){
            $score=$score+1;
        }
    }

    $tiempo_formateado = sprintf("%d:%02d", floor($minutos), $tiempo % 60);

    $data = "$sessionId-;-;-$name-;-;-$score-;-;-$tiempo_formateado" . PHP_EOL;

    // Abre el archivo para escritura al final del mismo
    $file = fopen("../../data/records.txt", "a");

    if ($file) {
        // Escribe la información en el archivo
        fwrite($file, $data);
        fclose($file);
    }
}
header("Location: ../../pages/ranking.php");
exit;
?>