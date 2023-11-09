<?php
session_start();
error_reporting(0);

if (isset($_GET['lang']) && ($_GET['lang'] == 'es' || $_GET['lang'] == 'ca' || $_GET['lang'] == 'en')) {
    $_SESSION['lang'] = $_GET['lang'];
} else {
    if (!isset($_SESSION['lang']) && !($_SESSION['lang'] == 'es' || $_SESSION['lang'] == 'ca' || $_SESSION['lang'] == 'en')) {
        $_SESSION['lang'] = 'en';
    }
}
include '../assets/language/' . $_SESSION['lang'] . '.php';

// Lee el contenido del archivo records.txt y guárdalo en un arreglo
$records = file('../data/records.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Crea un array para almacenar los datos de cada registro
$recordsArray = [];

foreach ($records as $record) {
    $data = explode("-;-;-", $record);
    // Verifica si hay al menos 4 elementos en la línea
    if (count($data) >= 3) {
        $recordData = [
            'idSesion' => $data[0],
            'nombre' => $data[1],
            'puntuacion' => $data[2],
            'fechaHora' => $data[3],
        ];
        $recordsArray[] = $recordData;
    }
}

// Función de comparación para usort
function compareRecords($a, $b)
{
    // Compara por puntuación
    $scoreComparison = $b['puntuacion'] - $a['puntuacion'];

    // Si la puntuación es igual, compara por fechaHora
    if ($scoreComparison == 0) {
        return strtotime($a['fechaHora']) - strtotime($b['fechaHora']);
    }

    return $scoreComparison;
}

// Ordena los registros utilizando la función de comparación
usort($recordsArray, 'compareRecords');

$sessionId = session_id();



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall Of Fame</title>
    <link rel="stylesheet" type="text/css" href="../assets/styles/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>

<body class="ranking">
    <noscript>
        <div id="avisoJS" class="avisoJS">
            <h1 class="titleNoscript">
                <?php echo $lang['noscipt']['tittle']; ?>
            </h1>
            <div class="deshabilitado">
                <?php echo $lang['noscipt']['message']; ?>
                <a href="https://support.google.com/adsense/answer/12654?hl" target="_blank">
                    <?php echo $lang['noscipt']['link']; ?>
                </a>.
            </div>
        </div>
        <div id="fondoDesenfocado" class="fondoDesenfocado"></div>
    </noscript>

    <div id="banner" class="centrar salonTitle">
        <h1 class="hallOfFameTittle">HALL OF FAME</h1>
    </div>

    <div class="tableContainer">
        <table>
            <tr>
                <th>Posición</th>
                <th>Puntuación</th>
                <th>Nombre</th>
                <th class="idSesionColumna">ID sesion</th>
                <th>Tiempo</th>
            </tr>
            <?php
            foreach ($recordsArray as $index => $record) {
                if ($sessionId === $record['idSesion']) {
                    echo '<tr class="userRow">';
                } else {
                    echo '<tr>';
                }
                echo '<td>' . ($index + 1) . '</td>'; // Posición
                echo '<td>' . $record['puntuacion'] . '</td>'; // Puntuación
                echo '<td>' . $record['nombre'] . '</td>'; // Nombre
                echo '<td class="idSesionColumna">' . $record['idSesion'] . '</td>'; // Nombre
                echo '<td>' . $record['fechaHora'] . '</td>'; // Fecha y Hora
                echo '</tr>';
            }
            ?>
        </table>
    </div>
    <div class="centrar" id="toStartButton">
        <a href="../assets/scripts/logout.php" class="button">
            <?php echo $lang['buttons']['toStart']; ?>
        </a>
    </div>
</body>

</html>