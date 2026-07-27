<?php
$servername = "localhost";
$username = "bduser";
$password = "programacion";
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

<style>
                    body {
                        color: #3498db;
                        font-family: 'Roboto';
                        background-color: #ecf0f1;
                    }

                    h1 {
                        color: #e74c3c;
                        text-align: center;
                        font-size: 50px;
                    }

                    a {
                        color: #2ecc71;
                        text-decoration: none;
                        text-align: left;
                        font-weight: bold;
                        margin: 10px;
                    }

                    a {
                        color: #000000; /* Cambia este valor al color que desees */
                        text-decoration: none;
                        font-weight: bold;
                        margin: 10px;
                        display: inline-block;
                        padding: 10px 20px;
                        border: 2px solid #000000;
                        border-radius: 30px;
                        font-size: 30px;
                        transition: background-color 0.3s, color 0.3s;
                
                    }
                    img {
                    max-width: 100%;
                    height: auto;
                    margin-left: 20px;
                }
                    a:hover {
                        background-color: #000000;
                        color: #ffffff;
                    }
                </style>

            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Menu</title>
            </head>
            <body>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
            <h1>Sistema de Administracion</h1>
            <img src="ldt.png" alt="LDT">
            <br>
            <a href="clientes.php">Clientes</a>
            <br>
            <a href="inventario.php">Inventario de Libros</a>
            <br>
            <a href="reportes.php">Reportes - Estadisticas</a>
            <br>
            <a href="usuarios.php">Usuarios</a>
            <br>
            <a href="salir.php">Salir</a>
            <br>
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
