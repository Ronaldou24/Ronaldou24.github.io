<?php 
session_start();
ob_start();
$bienvenido = true;
if (isset($_SESSION["nombre"]) ) {
   
    $bienvenido = true;
    $username = $_SESSION["nombre"];
} else  {
    if(isset ($_POST["username"]) && isset ($_POST["password"]) ){
      if ($_POST["username"] == "profesor" && $_POST["password"] == "CodingAcademy!"){

        $bienvenido = true;
        $_SESSION["nombre"] = $_POST["username"];
        $username = $_POST["username"];
    
      }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mostrar sesion</title>
</head>
<body>
    <h1>Bienvenido a la página <?php echo $_SESSION["nombre"]; ?></h1>
    <h2>Haz entrado a la deep web</h2>
    
    <?php
    if ($bienvenido = true) { ?>
        <h2>Haz entrado a la deep web</h2>
        <h1><a href="cerrar_session.php">Click aquí para cerrar sesión</a></h1>
        
        <?php } else {?> 
            <h1>Accseso no permitido</h1>
        
    <?php }
    ?>
</body>
</html>