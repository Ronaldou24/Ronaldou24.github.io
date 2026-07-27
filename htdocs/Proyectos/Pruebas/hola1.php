
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php 
$titulo = "Compra una GameCube y se encuentra un juego llamado Pickles nunca lanzado";
$resumen = "Los fans más nostálgicos siguen recibiendo noticias interesantes sobre GameCube. Se trata de un juego que seguramente desconocierais hasta ahora.";
$nota = "Después de conocer hace poco un secreto de la tapa de la consola que había pasado desapercibido, 
ahora parece que el usuario de Twitter con el nombre @cake_hoarder ha descubierto un disco de demostración 
intrigante dentro de un kit de desarrollo de GameCube que había adquirido. El disco contenía un juego de 
plataformas nunca antes visto ni publicado, titulado “Pickles”. En este curioso juego,
el protagonista es un mono que monta un monociclo y navega a través de una serie de obstáculos.
Lo que hace este descubrimiento aún más interesante es que el juego parece haber sido desarrollado por Santa Cruz Games, una compañía conocida por
 trabajar en títulos con licencia como Spider-Man, Superman y Tomb Raider: Underworld para Nintendo DS. A juzgar por la información disponible, Pickles podría haber sido el primer y único título original creado por Santa Cruz Games.";
$hora = date("Y-m-d");
$fecha = date("H:i:s");
$nombre = "Ronaldo ";
$apellido = "Solano";

?>
<style>
    .rojo{
        color: red;
    }
    body {
            margin-top: 60px;
            margin-left: 60px;
            margin-right: 60px;
            text-align: justify;
            font-family:monospace;
            background-color:darkorchid;
        }
</style>

<h1> 
    <?php echo $titulo ?>
</h1>
<br>
<p>Nota escrita por: <?php echo $nombre, $apellido  ?> </p>
<p span class="rojo"> Fecha: <?php echo $fecha  ?></p>
<p span class="rojo"> Hora:  <?php echo $hora  ?></p>
<h2>
    <?php echo $resumen ?>
</h2>
<br>
<p> <?php echo $nota ?></p>


    
</body>
</html>