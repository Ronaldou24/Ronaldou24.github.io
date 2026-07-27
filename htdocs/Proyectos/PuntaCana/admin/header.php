<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<!doctype html>
<html lang="es">
<head>
    <title>dogfather</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <!-- Custom CSS -->
    <link href="../css/header.css" rel="stylesheet" />
</head>
<body>
    <header>
        <nav class="navbar navbar-expand navbar-light">
            <div class="nav navbar-nav">
                <a class="nav-item nav-link big" href="index.php">Inicio</a>
                <a class="nav-item nav-link" href="agenda/index.php">Agenda</a>
                <a class="nav-item nav-link" href="buzon/index.php">Buzón</a>
                <a class="nav-item nav-link" href="mascotas/index.php">Mascotas</a>
                <a class="nav-item nav-link" href="usuario/index.php">Usuarios</a>
                <a class="nav-item nav-link" href="../index.php">Ir al sitio</a>
                <a class="nav-item nav-link" href="../admin/cerrar.php">Cerrar Sesion</a>
            </div>
        </nav>
    </header>
    <div class="container">
        <br>
