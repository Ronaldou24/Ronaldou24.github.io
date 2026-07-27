<?php 

$numeros = array(1=>10, 2=>20, 3=>30);
$nombres = array("noob" =>"juanpa", "medio" =>"lolo","pro" =>"ian");

print_r($numeros);
echo "<br>";
print_r($nombres);
echo "<br>";
echo "<br>";
foreach($numeros as $numero => $valor){
    echo $numero . " = " . $valor . "<br>";
}

foreach($nombres as $nombre => $valor1){
    echo $nombre . " = " . $valor1 . "<br>";
}

echo $nombres ["pro"];

?>