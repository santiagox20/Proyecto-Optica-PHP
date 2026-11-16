<?php
session_start();      // Recupera la sesion actual
session_unset();      // Borra todas las variables de sesion
session_destroy();    // Destruye la sesion en el servidor
header("Location: index.php"); // Devuelve al login
exit();
?>


