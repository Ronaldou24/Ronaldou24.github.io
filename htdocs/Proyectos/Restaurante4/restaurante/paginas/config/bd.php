<?php 

$host = "localhost";
$bd = "buzon";
$user = "root";
$pasword = "programacion";

try{
$conexion = new PDO("mysql:host=$host;dbname=$bd", $user, $pasword);
if(!$conexion){
    echo "Fallo la conexion master";
}
}
catch(Exception $ex){
echo $ex->getMessage();
}

echo "Hola xd";
?>