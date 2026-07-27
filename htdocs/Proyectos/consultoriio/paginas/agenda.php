<?php 
if (isset($_POST["buscar"])) {
    $fecha = $_POST["fecha"];
    include("../paginas/config/bd.php");
    try {
        $sql = "SELECT * FROM consultas WHERE fecha = :fecha";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
        
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>   
     <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/estilos1.css">

    <link rel="stylesheet" href="../css/agendar.css">
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
                <li class="menu__item"><a href="contactar.php" class="menu__link">Citar</a></li>
                <li class="menu__item"><a href="agenda.php" class="menu__link menu__link--select">Agenda</a></li>
            </ul>
            <div class="social-icon">
                <a href="" class="social-icon__link"><span class="icon-facebook"></span></a>
                <a href="" class="social-icon__link"><span class="icon-email"></span></a>
            </div>
        </div>
    </nav>
    <section class="banner">
        <img src="imagenes/banner.jpeg" alt="" class="banner__img">
        <div class="banner__content">
            Pedriatra en Puerto Vallarta
        
        </div>
    </section>
    <form action="" method="post">
<p>
<label for="fecha">Fecha a buscar</label>    
<input type="date" name ="fecha" id="">
</p>
<p><input type="submit" value="buscar fecha" name="buscar"></p>
    </form>
    <hr width="70%">
    <table border="1">
<tr>
    <th>Fecha</th>
    <th>Hora</th>
    <th>Paciente</th>
    <th>Edad</th>
    <th>Peso</th>
    <th>Estatura</th>
    <th>Estado</th>
</tr>
<?php 
if (isset($_POST["buscar"])) {
    foreach($datos as $fila){
        echo "<tr>";

        echo "<td>". $fila['fecha']."</td>";
        echo "<td>". $fila['hora']."</td>";
        echo "<td>". $fila['paciente']."</td>";
        echo "<td>". $fila['edad']."</td>";
        echo "<td>". $fila['peso']."</td>";
        echo "<td>". $fila['estatura']."</td>";
        echo "<td>". $fila['estado']."</td>";

        echo "<tr/>";
    }
}
?>
    </table>
    <br><br><br><br><br>
</body>
</html>