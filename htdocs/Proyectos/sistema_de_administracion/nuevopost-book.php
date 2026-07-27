<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include("dbconnection.php");

$ID = $row['BookID'];
$titulo = $row['Title'];
$autor = $row['Author'];
$publicado = $row['Published'];
$barcode = $row['Barcode'];

$sql = "INSERT INTO books (BookID, Title, Author, Published, Barcode) VALUES ('{$ID}', '{$titulo}', '{$autor}', '{$publicado}', '{$barcode}')";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Libro</title>
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

        table, th, td {
            border: 1px solid #000000;
            text-align: left;
            padding: 10px;
        }

        th {
            background-color: #e74c3c;
            color: #ffffff;
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
        <h1>Nuevo Libro Añadido Exitosamente</h1>

        <table>
            <tr>
                <th>Valores</th>
                <th>Datos del Libro</th>
            </tr>
            <tr>
                <td>ID</td>
                <td><?php echo $ID; ?></td>
            </tr>
            <tr>
                <td>Titulo</td>
                <td><?php echo $titulo ?></td>
            </tr>
            <tr>
                <td>Autor</td>
                <td><?php echo $autor; ?></td>
            </tr>
            <tr>
                <td>Publicado</td>
                <td><?php echo $publicado; ?></td>
            </tr>
            <tr>
                <td>Barcode</td>
                <td><?php echo $barcode; ?></td>
            </tr>
        </table>

        <a href="inventario.php">Ver tabla de libros</a>
        <br>
        <a href="menu.php">Regresar al menu</a>
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
