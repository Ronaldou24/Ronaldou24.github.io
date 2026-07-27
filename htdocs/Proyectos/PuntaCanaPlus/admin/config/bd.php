<?php 

    $host = "localhost";
    $bd =   "punta_cana";
    $user = "root";
    $pwd =  "programacion";

try{
    $conexion = new PDO("mysql:host=$host;dbname=$bd",$user,$pwd);
    
    if(!$conexion){
        echo "No se realizó la conexión...";
    }
    else{
        echo "Conexion exitosa";
    }

}catch(Exception $ex){
 echo $ex->getMessage();
}



?>