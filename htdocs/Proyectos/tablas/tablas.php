<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla del 5</title>

</head>

<body>
    <h1>Tabla del 5</h1>
   
    <?php
    $columnas = 10;
    $filas = 10;
    $num = 0;
    echo "Columnas = " . $columnas;
    echo "Filas = " . $filas;
    ?>
   <table border=1>

    <?php
    for ($i = 1; $i <= $columnas; $i++){
        echo "<th> Tabla del " . $num + 1 . "<th>". "\n";
    }
    for ($i = 1; $i <= $filas; $i++){
        echo "<tr>";
        for ($j = 1; $j <= $columnas; $j++){
            $r = $i * 5;
        echo "<td>" . $num . " x ". $i . " = ".  $r . "</td>";
    }
    echo "<tr>";
}
    



    ?>
</body>
</html>
