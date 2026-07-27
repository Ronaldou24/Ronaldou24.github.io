<?php 

$host = "localhost";
$bd = "consultorio";
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

echo "Conexion exitosa";
?>