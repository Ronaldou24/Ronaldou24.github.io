<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include("dbconnection.php");

$sql = "SELECT * FROM patrons ";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #f8f9fa;
            color: #8B0000;
            font-family: 'Verdana';
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column; /* Cambiado a columna para apilar elementos verticalmente */
            align-items: center;
            height: 100vh;
        }

        .button-container {
            display: flex;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            border: 2px solid #d9534f;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.1);
            margin-top: 20px;
        }

        th, td {
            color: #d9534f;
            border: 2px solid ;
            padding: 12px;
            text-align: left;
        }   

        th {
            background-color: #d9534f;
            color: white;
        }

        a {
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
            margin: 10px;
            padding: 10px 20px;
            border: 2px solid #d9534f;
            border-radius: 30px;
            font-size: 16px;
            transition: background-color 0.3s, color 0.3s;
            background-color: #d9534f;
        }

        a:hover {
            background-color: #8B0000;
            color: black;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        h1 {
            color: black;
        }
    </style>
    <title>Tabla de Clientes</title>
</head>
<body>
    <h1>Tabla de Clientes</h1>
    <div class="button-container">
        <a href="nuevo.php">Nuevo</a>
        <a href="editar.php">Editar</a>
    </div>
    <?php
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Nombre</th>";
            echo "<th>Apellido</th>";
            echo "<th>Correo</th>";
            echo "</tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row["PatronID"] . "</td>";
                echo "<td>" . $row["FirstName"] . "</td>";
                echo "<td>" . $row["LastName"] . "</td>";
                echo "<td>" . $row["Email"] . "</td>";
                echo "<td><form method='post' action='delete.php'><input type='hidden' name='PatronID' value='" . $row["PatronID"] . "'><button type='submit'>Eliminar</button></form></td>";

                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "0 resultados";
        }
    } else {
        echo "Error en la consulta: " . mysqli_error($conn);
    }

    mysqli_close($conn);
    ?>
</body>
</html>
