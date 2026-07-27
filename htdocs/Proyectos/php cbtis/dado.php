<?php 
    $random = 0;
    $dinero = 0;
    $nombre = "";
    $numero = 0;
    if(isset($_GET['apostar'])){
        $nombre = $_GET['nombre'];
        $dinero = $_GET['dinero'];
        $numero = $_GET['numero'];
        $apuesta = $dinero * 2;
        $perdida = $dinero - $dinero;
        $random = mt_rand(1, 6);
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dado insano</title>
</head>
<body>
    <style>
        body{
            background-color: brown;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        }
    </style>
<form action="" method="Get">
    <table align="center" bgcolor="#E7310A">
        <tr>
            <td><h1>Dado insano doble o nada</h1></td>
        </tr>
        <tr>
            <td>
            <label for="nombre">Nombre</label>    
            <input type="text" name="nombre">
        </td>
            
        </tr>
        <tr>
            <td>
            <label for="dinero">Dinero</label>    
            <input type="text" name="dinero">
        </td>
        </tr>

        </tr>
        <tr>
            <td>
            <label for="numero">Numero a apostar</label>    
            <input type="number" name="numero">
        </td>
        </tr>

        <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="apostar" name="apostar">
                </td>
            </tr>

           
    </table>
    <h1><?php
        if(isset($_GET['apostar'])) {
        
        if ($numero == $random){
            echo "Felicidades, ganaste ". $apuesta. " pesos";
            ?>
            <style>
                body{
                    background-color: green;
                }
            </style>
            <?php
        }
        else {
            echo "Muy mal Master " . $nombre. ", perdiste ". $dinero. " pesos, ahora tienes ". $perdida. " pesos";
            ?>
            <style>
                body{
                    background-color: red;
                }
            </style>
            <?php
        }    
    }
    
    
    ?></h1>
    </form>
    
</body>
</html>