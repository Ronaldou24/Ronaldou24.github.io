<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Tendencia Cliente
    </title>
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
    <h1>Ingresa el Cliente por favor</h1>
    <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
        include("dbconnection.php");
// Tu conexión a la base de datos y consulta SQL aquí

// Realizar la consulta para obtener los nombres de los clientes
$query = "SELECT FirstName FROM patrons ORDER BY FirstName";
$result = mysqli_query($conn, $query);

// Verificar si hay resultados
if ($result) {
    // Crear el formulario y el menú desplegable
    echo '<form action="grafica_tendencia.php" method="POST">';
    echo '<label for="cliente">Cliente</label>';
    echo '<select name="cliente" id="id">';

    // Iterar sobre los resultados y agregar opciones al menú desplegable
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<option value="' . $row['FirstName'] . '">' . $row['FirstName'] . '</option>';
    }

    // Cerrar el menú desplegable y el formulario
    echo '</select>';
    echo '<input type="submit" value="Enviar">';
    echo '</form>';
} else {
    // Manejar el caso en el que la consulta no tenga resultados
    echo 'Error al obtener datos de la base de datos.';
}

// Cerrar la conexión a la base de datos si es necesario


?>
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar si el formulario ha sido enviado
    $cliente = $_POST['cliente'];

    $sql = "SELECT '$cliente' AS PatronFirstName, YEAR(loans.LoanDate) AS LoanYear, COUNT(*) AS TotalLoans FROM loans 
            INNER JOIN patrons ON loans.PatronID = patrons.PatronID 
            WHERE patrons.FirstName = '$cliente' 
            GROUP BY PatronFirstName, LoanYear ORDER BY PatronFirstName, LoanYear";

    $resulta = mysqli_query($conn, $sql);
?>
<div id="chartContainer"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        exportEnabled: true,
        theme: "light3",
        title: {
            text: "Cantidad de Libros Prestados por Año de <?php echo $cliente?> "
        },
        axisY: {
            includeZero: true,
            title: "Año"
        },
        axisX: {
            title: "Total",
            labelAngle: 120,
            reversed: true
        },
        data: [{
            type: "column",
            indexLabelFontColor: "gray",
            indexLabelFontSize: 30,
            indexLabelPlacement: "outside",
            dataPoints: [
                <?php
                    while($row = mysqli_fetch_assoc($resulta)) {
                ?>
                { label: "<?php echo $row['LoanYear'];?>", y: <?php echo $row['TotalLoans'];?> },
                <?php
                    }
                    mysqli_close($conn);
                ?>
            ]
        }]
    });

    chart.render();
</script>
<?php
}
?>
<a href="reportes.php">Volver a Reportes</a>
<a href="menu.php">Volver al Menú</a>