<?php 

    $host = "localhost";
    $bd =   "taller_mecanico";
    $user = "root";
    $pwd =  "programacion";

try{
    $conexion = new PDO("mysql:host=$host;dbname=$bd",$user,$pwd);
    
    if(!$conexion){
        echo "No se realizó la conexión...";
    }


}catch(Exception $ex){
 echo $ex->getMessage();
}



?>