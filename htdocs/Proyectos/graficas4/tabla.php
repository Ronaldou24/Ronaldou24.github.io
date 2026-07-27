<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include("dbconexion.php");

$sql = "SELECT YEAR(LoanDate) AS Anio, COUNT(*) AS Prestamos FROM loans WHERE YEAR(LoanDate) IN (2016, 2017, 2018, 2019, 2020)And PatronID = 13 GROUP BY YEAR(LoanDate);";
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
                text: "Historial de Prestamos PatronID 13"
            },
            axisY: {
                includeZero: false,
                title: "Prestamos"
            },
            axisX: {
                title: "Anio",
                labelAngle: 0, // Add a comma here
            },

            data: [{
                type: "line",
                indexLabelFontColor: "gray",
                indexLabelFontSize: 20,
                indexLabelPlacement: "outside",
                dataPoints: [
                    <?php
                    while($row = mysqli_fetch_assoc($result)) {
                        ?>
                        { label: "<?php echo $row['Anio'];?>", y: <?php echo $row['Prestamos'];?> },
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
