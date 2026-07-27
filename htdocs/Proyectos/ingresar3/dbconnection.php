<?php
$servername = "localhost";
$username = "bduser";
$password = "CodingAcademy!1";
$dbname = "library";

try {
    // Crear la conexión
    global $conn;
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Checar la conexión
    if (!$conn) {
        // Lanzar una excepción en caso de error
        throw new Exception();
    }
    // Si no hubo error entonces la conexión fue exitosa
} catch (Exception $e) {
    // Manejar la excepción (mostrar un mensaje de error)
    echo $e->getMessage();
} 
?>

