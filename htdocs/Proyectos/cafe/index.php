<?php 
    $prod1 = "Americano";
    $precio1 = 20;
    $prod2 = "Moka";
    $precio2 = 35;
    $prod3 = "Pastel de Zanahoria";
    $precio3 = 30;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El buen Cafe</title>
    <link rel="stylesheet" href="css/estilos.css">
    <script src="js/subtotal.js"></script>
</head>
<body>
    <table border="4" align="center"> 

    <tr>
        <th colspan="3"  magic_quotes_runtime style="font-size: 30px;" >Cafeteria</th>
    </tr>

        <tr>
            <th align="center">Producto</th>
            <th>Precio</th>
            <th>Cantidad</th>
        </tr>

        <tr>
           <td align="center"> <?php echo $prod1 ?></td>
           <td align="center"> <input type="text" size="4" name="" id="precio1"   maxlength="4" value="<?php echo $precio1?>" readonly></td>
           <td align="center"> <input type="number" size="6" name="cant1" id="cant1"></td>
        </tr>
        <tr>
           <td align="center"> <?php echo $prod2 ?></td>
           <td align="center"> <input type="text" size="4" name="" id="precio2" maxlength="4" value="<?php echo $precio2?>" readonly></td>
           <td align="center"> <input type="number" size="6" name="cant2" id="cant2"></td>
        </tr>
        <tr>
           <td align="center"> <?php echo $prod3 ?></td>
           <td align="center"> <input type="text" size="4" name="" id="precio3" maxlength="4" value="<?php echo $precio3?>" readonly></td>
           <td align="center"> <input type="number" size="6" name="cant3" id="cant3"></td>
        </tr>
        <tr>
            <td align="center"><button align="center" onclick="obtenerSubtotal()">Calcular subtotal</button></td>
            <th><i>Subtotal</i></th>
            <td><input type="text" name="subtotal" id="subtotal" readonly></td>
        </tr>

        <tr><td align="center"><button onclick="pagar()">Pagar</button></td>
            <td align="center">Ingrese el money</td>
            <td align="center"><input type="text" name="saldo" id="saldo" ></td>
        </tr>

        <tr> <td>Cambio:</td>
            <td colspan="3" align="center"> <input type="text" name="total" id="total" value="total" readonly></td></tr>
    </table>
</body>
</html>