<?php
session_start();
if (isset($_POST["name"]) && (isset($_POST["currentDate"])) && (isset($_SESSION["score"]))&& (isset($_SESSION['tiempoInicio']))){

    $name = $_POST["name"];
    $currentDate = $_POST["currentDate"];
    $score = $_SESSION["score"];
    $sessionId = session_id();
    $tiempo = $_SESSION['tiempoInicio'];
    $minutos = $tiempo / 60; 

    $puntosPorRespuestaCorrecta = 100; // Puntos por respuesta correcta
    $puntuacionRespuestasCorrectas = $score * $puntosPorRespuestaCorrecta;
    $puntosPorMinuto = 5; // Puntos iniciales por minuto
    $puntosRestadosPorTiempo = $minutos * $puntosPorMinuto;

    $adjustedScore = max(0, $puntuacionRespuestasCorrectas - $puntosRestadosPorTiempo);

    $adjustedScore = intval($adjustedScore);

    $tiempo_formateado = sprintf("%d:%02d", floor($minutos), $tiempo % 60);

    $data = "$sessionId-;-;-$name-;-;-$adjustedScore-;-;-$tiempo_formateado" . PHP_EOL;


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