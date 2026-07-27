<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include("dbconexion.php");

$sql = "SELECT BookID, COUNT(*) AS Cuenta FROM loans GROUP BY BookID ORDER BY `Cuenta` DESC LIMIT 10";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        #chartContainer {
            width: 60%;
            height: 400px;
        }
    </style>
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
                text: "Top 10 Cantidad de Prestamos"
            },
            axisY: {
                includeZero: false,
                title: "Cantidad de libros"
            },
            axisX: {
                title: "ID",
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
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "{ label: '" . $row['BookID'] . "', y: " . $row['Cuenta'] . " },";
                    }
                    ?>
                ]
            }]
        });

        chart.render();
    </script>
</body>
</html>
