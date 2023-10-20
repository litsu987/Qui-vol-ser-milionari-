<?php
$nombre_archivo = 'questions/catalan_1.txt';

// Comprueba si el archivo existe
if (file_exists($nombre_archivo)) {
    // Abre el archivo en modo lectura
    $archivo = fopen($nombre_archivo, 'r');

    if ($archivo) {
        $preguntas_respuestas = [];
        $pregunta = '';
        $respuestas = [];

        while (($linea = fgets($archivo)) !== false) {
            $linea = trim($linea); // Elimina espacios en blanco al principio y al final de la línea

            if (empty($linea)) {
                continue; // Salta las líneas en blanco
            }

            if (substr($linea, 0, 1) === '*') {
                // Nueva pregunta
                if (!empty($pregunta)) {
                    // Agrega la pregunta y respuestas al arreglo
                    $preguntas_respuestas[] = [
                        'pregunta' => $pregunta,
                        'respuestas' => $respuestas,
                    ];
                }
                // Reinicia para la nueva pregunta
                $pregunta = substr($linea, 1);
                $respuestas = [];
            } elseif (substr($linea, 0, 1) === '-') {
                // Respuesta incorrecta
                $respuestas[] = substr($linea, 1);
            } elseif (substr($linea, 0, 1) === '+') {
                // Respuesta correcta
                $respuestas[] = substr($linea, 1);
            } else {
                // Línea de texto anterior
                $pregunta .= ' ' . $linea;
            }
        }

        if (isset($_POST['mostrarContenido'])) {
            // Si se presiona el botón, selecciona 3 preguntas aleatorias
            $preguntas_seleccionadas = array_rand($preguntas_respuestas, 3);
            
            echo '<script>';
            echo 'var ultimaPreguntaMostrada = 0;'; // Variable para rastrear la última pregunta mostrada
            echo 'function mostrarPregunta(id) {';
            echo '  document.getElementById("pregunta_" + id).style.display = "block";';
            echo '  document.getElementById("mostrar_" + id).style.display = "none";';
            echo '  if (id < 3) {';
            echo '    document.getElementById("mostrar_" + (id + 1)).style.display = "block";';
            echo '  }';
            echo '  ultimaPreguntaMostrada = id;';
            echo '}';
            echo '</script>';

            for ($i = 0; $i < 3; $i++) {
                $id = $i + 1;
                echo '<div id="pregunta_' . $id . '" style="display: ' . ($i === 0 ? 'block' : 'none') . ';">';
                mostrarPreguntaRespuestas($preguntas_respuestas[$preguntas_seleccionadas[$i]]['pregunta'], $preguntas_respuestas[$preguntas_seleccionadas[$i]]['respuestas']);
                echo '</div>';
                if ($id == 1) {
                    echo '<button id="mostrar_' . $id . '" onclick="mostrarPregunta(' . $id . ')">Mostrar Pregunta ' . $id . '</button>';
                } else {
                    echo '<button id="mostrar_' . $id . '" onclick="mostrarPregunta(' . $id . ')" style="display: none;">Mostrar Pregunta ' . $id . '</button>';
                }
            }
        } else {
            // Muestra solo el botón para mostrar el contenido
            echo '
            <form method="post">
                <input type="submit" name="mostrarContenido" value="Mostrar Preguntas y Respuestas">
            </form>';
        }

        // Cierra el archivo
        fclose($archivo);
    } else {
        echo "No se pudo abrir el archivo.";
    }
} else {
    echo "El archivo no existe.";
}

function mostrarPreguntaRespuestas($pregunta, $respuestas) {
    echo "<strong>$pregunta</strong>\n";
    echo "<ul>\n";
    foreach ($respuestas as $respuesta) {
        echo "<li>$respuesta</li>\n";
    }
    echo "</ul>\n";
}
?>
