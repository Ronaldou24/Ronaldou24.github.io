<?php 

    $host = "localhost";
    $bd =   "dogfather";
    $user = getenv('DB_USER') ?: "root";
    $pwd =  getenv('DB_PASSWORD') ?: ""; // no hardcodees credenciales: define DB_PASSWORD como variable de entorno local

try{
    $conexion = new PDO("mysql:host=$host;dbname=$bd",$user,$pwd);
    
    if(!$conexion){
        echo "No se realizó la conexión...";
    }

}catch(Exception $ex){
 echo $ex->getMessage();
}



?>