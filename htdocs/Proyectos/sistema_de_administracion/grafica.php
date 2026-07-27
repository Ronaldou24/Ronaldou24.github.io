<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
    include("dbconnection.php");

$sql = "SELECT Title, COUNT(*) AS Total FROM loans INNER JOIN Books ON Loans.BookID = Books.BookID GROUP BY Title ORDER BY Total DESC LIMIT 10;";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantidad de Prestamos</title>
    <style>
        #chartContainer {
            width:100%;
            height: 400px;
        }

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
</head>
<body>
    <div id="chartContainer"></div>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            exportEnabled: true,
            theme: "light3",
            title: {
                text: "Cantidad de Prestamos"
            },
            axisY: {
                includeZero: false,
                title: "Total"
            },
            axisX: {
                title: "Titulo",
                labelAngle: 0, // Add a comma here
                reversed: true
            },

            data: [{
                type: "bar",
                indexLabelFontColor: "gray",
                indexLabelFontSize: 20,
                indexLabelPlacement: "outside",
                dataPoints: [
                    <?php
                    while($row = mysqli_fetch_assoc($result)) {
                        ?>
                        { label: "<?php echo $row['Title'];?>", y: <?php echo $row['Total'];?> },
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
