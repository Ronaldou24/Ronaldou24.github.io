<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include("dbconnection.php");

$nombre = $_POST["nombre"];
$apellido = $_POST["apellido"];
$email = $_POST["email"];

$sql = "INSERT INTO Patrons (FirstName, LastName, email) VALUES ('{$nombre}', '{$apellido}', '{$email}')";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Cliente</title>
    <style>
        body {
            background-color: #f4f4f4;
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            height: 100vh;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        a {
            text-decoration: none;
            color: #d9534f;
            font-weight: bold;
            margin-top: 20px;
            display: inline-block;
        }

        a:hover {
            color: #c9302c;
        }
    </style>
</head>
<body>
    <?php
    if ($result) {
    ?>
        <h1>Nuevo Cliente Añadido Exitosamente</h1>

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

        <a href="ciclos1.php">Ver tabla de Patrons</a>
        <br>
        <a href="post1.php">Regresar</a>
    <?php
    } else {
        echo "<h1>Error al añadir el nuevo cliente</h1>";
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        echo "<br><a href='post1.php'>Regresar</a>";
    }

    mysqli_close($conn);
    ?>
</body>
</html>
