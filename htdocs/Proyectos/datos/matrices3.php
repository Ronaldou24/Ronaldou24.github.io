<?php 

$maestros = array(
    "CC" =>"Carlos Lias",
    "SB" =>"Sandobal Bobadilla",
    "MT" =>"Mota",
    "PP" =>"Paco Peraza",
    "GS" =>"Guiso",
    "KB" =>"Karina Barajas",
);
if(isset($_POST['buscar'])){
    $clave = $_POST['clave'];
    echo "<h2> Bienvenido profe  $maestros[$clave] </h2>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busqueda</title>
</head>
<body>
    <style>
        table{
            width: 400px;
        }
    </style>
   <table align="center" border="3">
    <form action="" method="GET">
       <tr>
        <td>ID</td>
        <td>Nombre del profe</td>
       </tr>
       <?php 
       foreach($maestros as $id => $nombre){
        echo "<tr>";
        echo "<td> ($id) </td>";
        echo "<td> ($nombre) </td>";
        echo "<td> <a href='consulta.php?clave{$id}'> Consulta </a>";
        echo "</tr>";
       }
       ?>
</table> 

</body>
</html>