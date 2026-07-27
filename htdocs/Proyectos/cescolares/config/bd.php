<?php
    $host = "localhost";
    $bd = "cescolares";
    $user = "root";
    $pwd = "programacion";

    try{
        $conexion = new PDO("mysql:host=$host;dbname=$bd" , $user, $pwd);
        if(!$conexion){
            echo "No se estableció la conexion";
        }

    } catch(Exception $ex){
        echo $ex ->getMessage();
    
    }