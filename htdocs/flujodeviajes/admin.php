<?php
session_start();


if (!isset($_SESSION['usuario'])) {
    header("Location: login.php"); 
    exit();
}
 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Plataforma de Transporte</title>
    <link rel="stylesheet" href="css/admin.css">

</head>
<body>


    <h1>Pagina de Administrador</h1>
    <h1>Bienvenido <?php echo htmlspecialchars($_SESSION['usuario']); ?></h1>
    <p>Consulta los próximos horarios y rutas de transporte.</p>
    <a href="vuelos.php" class="btn">Ver Vuelos Próximos</a>

</body>
</html>
