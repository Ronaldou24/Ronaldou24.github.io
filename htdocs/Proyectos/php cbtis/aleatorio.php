<?php
    $nombre = "";
    $num = 0;
    $suma = 0;
    $ahorro = 0;
    $zero = 0;
    $random = 0;
    if(isset($_GET['aceptar'])){
        $nombre = $_GET['nombre'];
        $num = $_GET['num'];
        $ahorro = $_GET['ahorro'];
        $suma = $_GET['suma'];
        $suma = $suma + $ahorro;

        $random = mt_rand(0, 100);

        $num = $num + 1;
        $mensaje = "{$nombre} Se presiono el boton";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Numero Aleatorio</title>
</head>
<body>
    <table align="center">
        <form action="" method="GET">
            <tr>
                <td> 
                    <label for="nombre">Nombre</label>
                
                </td>
                <td>
                    <input type="text" name="nombre" value="<?php echo $nombre;?>">
                </td>
            </tr>
            <tr>
                <td> 
                    <label for="num">Numero</label>
                
                </td>
                <td>
                    <input type="text" name="num" value="<?php echo $num;?>">
                </td>
            </tr>
            <tr>
                <td> 
                    <label for="Ahorro">Suma</label>
                
                </td>
                <td>
                    <input type="hidden" name="suma" value="<?php echo $suma;?>">>
                    <input type="text" name="ahorro" value="<?php echo $zero;?>">
                </td>
            </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" value="Aceptar" name="aceptar">
            </td>
        </tr>
        </form>

    </table>
    <h1> <?php if(isset($mensaje)) echo $mensaje; ?></h1>
    <h1> <?php if(isset($suma)) echo "llevas ahorrado ". $suma. " master"; ?></h1>
    <h1> <?php if(isset($random)) echo "Te toco el numero ". $random; ?></h1>
</body>
</html>