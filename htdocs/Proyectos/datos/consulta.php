<?php 

$maestros = array(
    "CC" =>"Director General del cbtis",
    "SB" =>"Padre de las Matematicas",
    "MT" =>"Un Pendejo",
    "PP" =>"Hacker de la Deep Web",
    "GS" =>"Licenciado con 3 doctorados en gastronomia",
    "KB" =>"No sabe hacer nada",
); 
    $id = $_GET['clave'];
    echo "<h1>Descripcion : $maestros[$id]</h1>";
