<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
    include("dbconnection.php");

    $sql = "SELECT YEAR(LoanDate) AS LoanYear, COUNT(*) AS TotalLoans
            FROM loans
            GROUP BY LoanYear;";
    $result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfica de Préstamos por Año</title>

</head>
<body>
    <style>
                body {
            color: #333;
            font-family: 'Roboto', sans-serif;
            background-color: #ecf0f1;
            text-align: center;
                }
a {
            color: #000;
            text-decoration: none;
            font-weight: bold;
            margin: 10px;
            display: block;
            padding: 10px 20px;
            border: 2px solid #000;
            border-radius: 30px;
            font-size: 20px;
            transition: background-color 0.3s, color 0.3s;
        }

        a:hover {
            background-color: #000;
            color: #fff;
        }
    </style>
    
    <a href="reportes.php">Volver a Reportes</a>
    <a href="menu.php">Volver a Menu</a>

    <div id="chartContainer"></div>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            exportEnabled: true,
            theme: "light3",
            title: {
                text: "Cantidad de Libros Prestados por Año"
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
                        while($row = mysqli_fetch_assoc($result)) {
                    ?>
                    { label: "<?php echo $row['LoanYear'];?>", y: <?php echo $row['TotalLoans'];?> },
                    <?php
                        }
                    ?>
                ]
            }]
        });

        chart.render();
    </script>

</body>
</html>