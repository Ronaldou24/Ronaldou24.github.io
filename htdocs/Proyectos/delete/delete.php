<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include("dbconnection.php");

$id_borrar = $_POST["YYYYY"];


$sql = "DELETE FROM Books WHERE BookID={$id_borrar}";
$result = mysqli_query($conn, $sql);

if ($result) {
    // Si $result es verdadero indica que el query fue exitoso. 
    echo "Registro Borrado Exitosamente";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

//Por default las conexiones se cierran automáticamente al
//terminar el script pero si quieres lo puedes forzar usando esta instrucción
mysqli_close($conn);
?>
<style>
    body {
            background-color: #f4f4f4;
            color: #333;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
</style>
<a href="ciclos1.php">Ver Tabla de Libros</a>








