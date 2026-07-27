<?php 
if (isset($_POST["OK"])) {
    $fecha = $_POST["fecha"];
    $hora = $_POST["hora"];
    $paciente = $_POST["paciente"];
    $edad = $_POST["edad"];
    $peso = $_POST["peso"];
    $estatura = $_POST["estatura"];
    $estado = $_POST["estado"];
    include("../paginas/config/bd.php");
    try {
        $sql = $conexion->prepare("INSERT INTO consultas VALUES (:fecha,:hora,:paciente,:edad,:peso,:estatura,:estado)");
        
        $sql->bindParam(':fecha', $fecha);
        $sql->bindParam(':hora', $hora);
        $sql->bindParam(':paciente', $paciente);
        $sql->bindParam(':edad', $edad);
        $sql->bindParam(':peso', $peso);
        $sql->bindParam(':estatura', $estatura);
        $sql->bindParam(':estado', $estado);

        $sql->execute();
        if ($sql) {
            $sql = null;
            $conexion = null;
            echo "<script>alert('Cita agendada master');</script>";
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
    <title>Consultorio Solano</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/estilos1.css">

</head>
<body>
    <header class="main-header">
        <div class="container container--flex">
            <div class="logo-container column column--50">
                <h1 class="logo">Consultorio</h1>
            </div>
            <div class="main-header__contactInfo column column--50">
                <p class="main-header__contactInfo__phone"><span class="icon-phone">322-1085189</span></p>
                <p class="main-header__contactInfo__address"><span class="icon-map">Dr. Ronaldo Solano</span></p>
            </div>
        </div>
    </header>
    <nav class="main-menu">
        <div class="container container--flex">
            <span class="icon-menu" id="btnMenu"></span>
            <ul class="menu" id="menu">
                <li class="menu__item"><a href="../index.php" class="menu__link ">Home</a></li>
                <li class="menu__item"><a href="contactar.php" class="menu__link menu__link--select">Citar</a></li>
                <li class="menu__item"><a href="../paginas/agenda.php" class="menu__link">Agenda</a></li>
            </ul>
            <div class="social-icon">
                <a href="" class="social-icon__link"><span class="icon-facebook"></span></a>
                <a href="" class="social-icon__link"><span class="icon-email"></span></a>
            </div>
        </div>
    </nav>
    <section class="banner">
        <img src="../imagenes/banner.jpeg" alt="" class="banner__img">
        <div class="banner__content">
            Formulario
        
        </div>
    </section>
    <form action="contactar.php" method="POST">
        <p>
    <label for="paciente">Nombre<input name="paciente" type="text"></label>
    </p>
    <p><label for="fecha">Fecha<input name="fecha" type="date"></label></p>
    <p> <label for="hora">Hora<input name="hora" type="time"></label></p>
    <p> <label for="edad">edad<input name="edad" type="int"></label></p>
    <p><label for="peso">Peso<input name="peso" type="int"></label></p>
    <p> <label for="estatura">Estatura<input name="estatura" type="int"></label></p>
    <p> <label for="estado">Estado<input  name="estado"type="text"></label></p>
    <p><input type="submit" value="OK" name="OK" > </p>
    </form>
  


        <div class="contacto "><p class="group__title column column--55">Ficha de contacto</p></div>
    </main>
    <footer class="main-footer">
        <div class="container container--flex">
            <div class="column column--33">
                <h2 class="column__title">¿Porque visitarnos?</h2>
                <p class="column__txt">Brindamos la mejor de  las atenciones a nuestros paciantes con ayuda de el gran dr Ronaldo</p>
            </div>
            <div class="column column--33">
                <h2 class="column__title">Contactanos</h2>
                <p class="column__txt"><span class="icon-map">Domicilio: Bùi Thị Xuân Middle School </span></p>
                <p class="column__txt"><span class="icon-phone">322 108 5189</span></p>
                <p class="column__txt"><span class="icon-email">Correo: drronlado@gmail.com</span></p>
            </div>
            <div class="column column--33">
                <h2 class="column__title">Siguenos en nuestras redes</h2>
                <p class="column__txt"><a href="" class="icon-facebook">facebook: Ronaldou</a></p>
                <p class="column__txt"><a href="" class="icon-youtube">visita nuestro canal: Dr Rony</a></p>
            </div>
            <p class="copy">&copy your consultories | todos los derechos reservados</p>
        </div>
    </footer>
    <script src="js/menu.js"></script>
</body>
</html>