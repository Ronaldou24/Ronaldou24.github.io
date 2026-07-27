<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include("dbconnection.php");

$sql = "SELECT * FROM Patrons";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Patrons</title>
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
            height: 100vh;
            flex-direction: column;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin: 0 auto;
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
            cursor: pointer; /* Cambio del cursor al pasar sobre las filas */
        }
    </style>
</head>
<body>
    <h1>Tabla de Patrons</h1>
    <?php
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Nombre</th>";
            echo "<th>Email</th>";
            echo "</tr>";
            while ($row = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["PatronID"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["FirstName"] . " " . $row["LastName"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["Email"]) . "</td>";
                echo "</tr>";
            } 
            echo "</table>";
        } else {
            echo "<p style='text-align: center; color: #555;'>No hay resultados</p>";
        }
    } else {
        echo "<p style='text-align: center; color: #d9534f;'>Error en la consulta: " . mysqli_error($conn) . "</p>";
    }

    mysqli_close($conn);
    ?>
</body>
</html>
