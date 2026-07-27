<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado</title>
    <style>
        h1 {color: black;}
        body {
            color: red;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 50%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
<?php
$nombre = $_POST["nombre"];
$apellido = $_POST["apellido"];
$email = $_POST["email"];
$contador = 0;

if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $contador++;
} else {
    echo "La dirección de correo electrónico no es válida.";
    echo '<br>';
}

if ($apellido == "") {
    echo "El Apellido es inválido. Asegúrate de ingresar algo válido.";
    echo '<br>';
} else {
    $contador++;
}

if ($nombre == "") {
    echo "El Nombre es inválido. Asegúrate de ingresar algo válido.";
} else {
    $contador++;
}

if ($contador == 3) {
    echo '<h1>Nuevo usuario añadido</h1>';
?>
    <table>
        <tr>
            <th>Valores</th>
            <th>Datos de la cuenta</th>
        </tr>
        <tr>
            <td>Nombre</td>
            <td><?php echo $nombre; ?></td>
        </tr>
        <tr>
            <td>Apellido</td>
            <td><?php echo $apellido ?></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><?php echo $email; ?></td>
        </tr>
    </table>
    <a href="post1.php">Volver al Lobby</a>
</body>
</html>
<?php
}
else {
    ?>
    
<a href="post1.php">Regresar</a>

<?php
}
?>
