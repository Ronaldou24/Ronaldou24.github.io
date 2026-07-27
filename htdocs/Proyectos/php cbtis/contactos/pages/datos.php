<?php 
if(isset($_POST['enviar'])){
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $website = $_POST['website'];
    $asunto = $_POST['asunto'];
    $mensaje = $_POST['mensaje'];


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form for="">
        <label for="nombre"> Nombre </label>
        <input type="text"  value=" <?php echo $nombre; ?>">
        <br>
        <label for="asunto"> Asunto </label>
        <input type="text"  value=" <?php echo $asunto; ?>">
        <br>
        <label for="mensaje"> Mensaje </label>
        <h3 type="text"  value=" <?php echo $mensaje; ?>"> </h3>
        <br>
    </form>


</body>
</html>