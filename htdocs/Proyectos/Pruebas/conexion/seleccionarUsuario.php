<?php
$servername = "localhost";
$username = "bduser";
$password = "CodingAcademy!1";
$dbname = "library"; 

// Crear la conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Conexión exitosa <br>";

// Recoge los datos del formulario de inicio de sesión
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta preparada para verificar la autenticación del usuario
    $sql = "SELECT username, password FROM Users WHERE username = 'administrador'";
    $resultado = mysqli_query($conn, $sql);

    if ($resultado) {
        $row = mysqli_fetch_assoc($resultado);
        if ($row) {
            echo "Bienvenido " . $row['username'];
            ?>

            <style> body{ color: black;
             font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif ;
             }
            </style>

            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Bienvenido</title>
            </head>
            <body>
            <h1>Haz entrado a la pagina secreta bienvenido</h1>
            <h1><a href="cerrar.php">Si quieres cerrar la sesion, haz clic aquí</a></h1>
            </body>
            </html>
           
            <?php

        } else {
            echo "Error: Contraseña incorrecta.";
        }
    }
    }


mysqli_close($conn);
?>
