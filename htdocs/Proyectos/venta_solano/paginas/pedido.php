<?php

if (isset($_POST["OK"])) {
    $nombre = $_POST["nombre"];
    $estado = $_POST["estado"];
    $colonia = $_POST["colonia"];
    $num = $_POST["num"];
    $instrumento = $_POST["instrumento"];
    $cantidad = $_POST["cantidad"];
    $metodo = $_POST["metodo"];
    include("../paginas/config/bd.php");
    try {
        $sql = $conexion->prepare("INSERT INTO pedidos VALUES (:nombre,:estado,:colonia,:num,:instrumento,:cantidad,:metodo)");
        
        $sql->bindParam(':nombre', $nombre);
        $sql->bindParam(':estado', $estado);
        $sql->bindParam(':colonia', $colonia);
        $sql->bindParam(':num', $num);
        $sql->bindParam(':instrumento', $instrumento);
        $sql->bindParam(':cantidad', $cantidad);
        $sql->bindParam(':metodo', $metodo);

        $sql->execute();
        if ($sql) {
            $sql = null;
            $conexion = null;
            echo "<script>alert('Pedido con exito master');</script>";
        }
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/estilos1.css">
    <link rel="stylesheet" href="../css/pedido.css">

</head>
<body>
    <header class="main-header">
        <div class="container container--flex">
            <div class="logo-container column column--50">
                <h1 class="logo">Haz tu pedido</h1>
            </div>
            <div class="main-header__contactInfo column column--50">
                <p class="main-header__contactInfo__phone"><span class="icon-phone">322-1085189</span></p>
                <p class="main-header__contactInfo__address"><span class="icon-map">Avenida Rio</span></p>
            </div>
        </div>
    </header>
    <nav class="main-menu">
        <div class="container container--flex">
            <span class="icon-menu" id="btnMenu"></span>
            <ul class="menu" id="menu">
                <li class="menu__item"><a href="../index.html" class="menu__link">Inicio</a></li>
                <li class="menu__item"><a href="nosotros.html" class="menu__link">Nosotros</a></li>
                <li class="menu__item"><a href="../paginas/pedido.php" class="menu__link menu__link--select">Pedidos</a></li>
            </ul>
            <div class="social-icon">
                <a href="" class="social-icon__link"><span class="icon-facebook"></span></a>
                <a href="" class="social-icon__link"><span class="icon-email"></span></a>
            </div>
        </div>
    </nav>
    <section class="banner">
        <img src="../imagenes/master.jpeg" alt="" class="banner__img">
        <div class="banner__content">
            <h2>Formulario de Pedido</h2>
        </div>
    </section>
    <form id="pedidoForm" action="pedido.php" method="POST">
        <div class="container">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input name="nombre" type="text">
            </div>
            <div class="form-group">
                <label for="estado">Estado:</label>
                <input name="estado" type="text">
            </div>
            <div class="form-group">
                <label for="colonia">Colonia:</label>
                <input name="colonia" type="text">
            </div>
            <div class="form-group">
                <label for="num">Número Exterior:</label>
                <input name="num" type="number">
            </div>
            <div class="form-group">
                <label for="instrumento">Instrumento a Elegir:</label>
                <input name="instrumento" type="text">
                
            </div>
            <div class="form-group">
                <label for="cantidad">Cantidad:</label>
                <input name="cantidad" type="number">
            </div>
            <div class="form-group">
                <label for="metodo">Método de Pago:</label>
                <input name="metodo" type="text">
            </div>
            <div class="form-group">
                <input type="submit" value="OK" name="OK">
            </div>
        </div>
    </form>
    <footer class="main-footer">
        <div class="container container--flex">
            <div class="column">
                <h2 class="column__title">¿Por qué visitarnos?</h2>
                <p class="column__txt">nos comprometemos a brindar un servicio personalizado y experto a cada cliente. Nuestro equipo está formado por músicos apasionados que están aquí para ofrecerte orientación y apoyo en cada etapa de tu viaje musical.</p>
            </div>
            <div class="column">
                <h2 class="column__title">Contáctanos</h2>
                <p class="column__txt"><span class="icon-map">Domicilio: Avenida Rio </span></p>
                <p class="column__txt"><span class="icon-phone">322 108 5189</span></p>
                <p class="column__txt"><span class="icon-email">Correo: musical@gmail.com</span></p>
            </div>
            <div class="column">
                <h2 class="column__title">Síguenos en nuestras redes</h2>
                <p class="column__txt"><a href="" class="icon-facebook">Facebook: Musica vietnam</a></p>
                <p class="column__txt"><a href="" class="icon-youtube">Visita nuestro canal: Taiwan Ezpress</a></p>
            </div>
        </div>
        <p class="copy">&copy; tu musica | todos los derechos reservados</p>
    </footer>
///
    </script>
</body>
</html>
