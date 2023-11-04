<?php
session_start();
session_destroy();
//header("Location: ../../pages/index.php");
?>

<html>
<script>
    window.location.replace("../../pages/index.php");
</script>

</html>