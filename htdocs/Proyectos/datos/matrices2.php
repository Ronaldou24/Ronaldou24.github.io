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
    <form action="" method="Post">
        <tr>
            <td>
                <label for="class"> Ingresa la clave </label>
                <input type="text" name="clave">
            </td>
        </tr>

        <tr>
            <td align="center">
                <input type="submit" value="buscar" name="buscar">
            </td>
        </tr>
        <tr>
            <td>
                Docentes
            </td>
            <td> <select name="maestros">
                <?php  
                foreach($mestros as $clave => $nombre){
                    echo "<option value='{$clave}'>{$nombre}</option>";

                    
                }
                
                ?>
            </select>
        </td>
        </tr>

    </form>
</table> 

</body>
</html>