<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Libros no Devueltos</title>
    <a href="reportes.php">Volver a Reportes</a>
    <a href="menu.php">Volver al Menú</a>
    <style>
        body {
            background-color: #f8f9fa;
            color: #8B0000;
            font-family: 'Verdana';
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
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
            border: 2px solid #d9534f;
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
</head>
<body>
<h1>Libros Pendientes</h1>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
    include("dbconnection.php");

    $sql = "SELECT *
            FROM loans
            WHERE ReturnedDate IS NULL";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr>";
            echo "<th>ID Del prestamo</th>";
            echo "<th>ID Del Libro</th>";
            echo "<th>ID Del Cliente</th>";
            echo "<th>Fecha Limite</th>";
            echo "</tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row["LoanID"] . "</td>";
                echo "<td>" . $row["BookID"] . "</td>";
                echo "<td>" . $row["PatronID"] . "</td>";
                echo "<td>" . $row["DueDate"] . "</td>";
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
