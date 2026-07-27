<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerrar Sesión</title>
</head>
<body>
    <h1>Sesión cerrada exitosamente</h1>
    <h1><a href="Crear_sesion.php">Si quieres volver a entrar, haz clic aquí</a></h1>
</body>
</html>
