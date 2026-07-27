<?php
if(isset($_POST['aceptar'])){
$nombre = $_POST['nombre'];
$email = $_POST['email'];
echo "{$nombre} tu correo es {$email}";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario De la Deep Web</title>
</head>
<body>
    <form action="" method="post">
        <label for="nombre">Ingresa tu nombre viejin</label>
        <input type="text" name="nombre" id="">
        <br>
        <label for="email">Ingresa tu correo</label>
        <input type="text" name="email">
        <br>
        <input type="submit" value="Ok" name="aceptar">
    </form>
</body>
</html>