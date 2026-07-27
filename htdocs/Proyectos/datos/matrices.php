<?php 

 $numeros = array(1,2,3,4,5,6,7,8,9,0);
 $nombres = array("pollo", "rommel", "lolo");

 // para imprimir todo el arreglo
    print_r($numeros);
    echo "<br>";
    print_r($nombres);

    echo "<br>";
    // para imprimir un elemento del arreglo
    echo $numeros [1];
    echo "<br>";
    echo $nombres[0];

    echo "<br>";
    echo "<br>";
    foreach($numeros as $numero){
        echo $numero . "<br>";
    }

    foreach($nombres as $nombre){
        echo $nombre . "<br>";
    }



    
?>