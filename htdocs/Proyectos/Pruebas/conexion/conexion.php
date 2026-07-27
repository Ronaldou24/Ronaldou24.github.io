<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
$servername = "localhost";
$username = "bduser";
$password = "CodingAcademy!1";
$dbname = "library";

// Crear la conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Checar la conexión
if (!$conn) {
    //Si la conexión falla imprimir mensaje y finalizar script
    die("Connection failed: " . mysqli_connect_error());
}
//Si no hubo error entonces la conexión fue exitosa
echo "Connected successfully<br>";

//Por default las conexiones se cierran automáticamente al
//terminar el script pero si quieres lo puedes forzar
//usando esta instrucción
mysqli_close($conn);
?>



