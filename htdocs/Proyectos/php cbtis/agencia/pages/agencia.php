<?php 
if(isset($_POST['enviar'])){
    $nombre = $_POST['nombre'];
    $ancho = $_POST['ancho'];
    $largo = $_POST['largo'];
    $m2e = $_POST['m2e'];
    $enganche = $_POST['enganche'];


    /* aritmetica */


    $prem2 = 1500;
    $area = $ancho * $largo;
    $subtotal = $prem2 * $area;
    $total = $subtotal - $enganche;

    $pagoxmes = $total / 60;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Propuesta</title>
</head>
<body>
    <h1>Propouesta Costos</h1>
    <br>
    <h2>Fecha = 21/02/2024</h2>
   <h2>Estimado <?php  echo $nombre;?></h2>
   <br>
   <h2>Area del terreno: <?php echo $area;?> m2</h2>
   <h2>Subcosto del Terreno: <?php  echo $subtotal;?> pesos</h2>
   <h2>Enganche <?php  echo $enganche;?></h2>
   <h2>Total - enganche <?php  echo $total;?></h2>
   <h2>Pago Por cada mes <?php  echo $pagoxmes;?></h2>
   <h2>Un total de 60 meses</h2>




</body>
</html>