<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Libros por Mes</title>
    <style>
        body {
            background-color: #f4f4f4;
            color: #333;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            height: 100vh;
        }

        h1 {
            color: #e74c3c;
            text-align: center;
            font-size: 50px;
        }

        a {
            color: #000000;
            text-decoration: none;
            text-align: left;
            font-weight: bold;
            margin: 10px;
            display: inline-block;
            padding: 10px 20px;
            border: 2px solid #000000;
            border-radius: 30px;
            font-size: 30px;
            transition: background-color 0.3s, color 0.3s;
        }

        img {
            max-width: 100%;
            height: auto;
            margin-left: 20px;
        }

        a:hover {
            background-color: #000000;
            color: #ffffff;
        }

        /* Contenedor para el formulario y la tabla */
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 30px;
        }

        table {
            margin-top: 40px;
            width: 80%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 3px solid #000000;
            text-align: left;
            padding: 15px;
        }

        th {
            background-color: #e74c3c;
            color: #ffffff;
        }

        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px; /* Margen inferior para separar del contenedor de la tabla */
        }

        h1 {
            color: #d9534f;
            text-align: left;
            font-size: 24px;
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 15px;
        }

        select, input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border:2px solid #ccc;
            border-radius: 6px;
            font-size: 18px;
        }

        input[type="submit"] {
            background-color: #d9534f;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <h1>Ingresa el mes y año por favor</h1>
    <form action="libros-por-mes.php" method="POST">
        <label for="mes">Mes</label>
        <select name="mes" id="mes">
            <option value="01">Enero</option>
            <option value="02">Febrero</option>
            <option value="03">Marzo</option>
            <option value="04">Abril</option>
            <option value="05">Mayo</option>
            <option value="06">Junio</option>
            <option value="07">Julio</option>
            <option value="08">Agosto</option>
            <option value="09">Septiembre</option>
            <option value="10">Octubre</option>
            <option value="11">Noviembre</option>
            <option value="12">Diciembre</option>
        </select>
        <label for="ano">Año</label>
        <select name="ano" id="ano">
            <option value="2016">2016</option>
            <option value="2017">2017</option>
            <option value="2018">2018</option>
            <option value="2019">2019</option>
            <option value="2020">2020</option>
        </select>
        <input type="submit" value="Consultar" />
    </form>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
    include("dbconnection.php");

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Verificar si el formulario ha sido enviado

        $ano = $_POST['ano'];
        $mes = $_POST['mes'];

        $sql = "SELECT COUNT(BookID)
                FROM loans
                WHERE LoanDate LIKE '{$ano}-{$mes}%'";

        $result = mysqli_query($conn, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo "<table>";
                echo "<tr>";
                echo "<th>Total libros prestados en $mes de $ano</th>";
                echo "</tr>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row["COUNT(BookID)"] . "</td>";
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
    }
    ?>

    <a href="reportes.php">Volver a Reportes</a>
    <a href="menu.php">Volver al Menú</a>

</body>
</html>
