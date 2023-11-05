<?php
session_start();
session_regenerate_id(); // Regenera el identificador de sesión
// session_destroy(); // Destruye la sesión
header("Location: ../../pages/index.php");
?>