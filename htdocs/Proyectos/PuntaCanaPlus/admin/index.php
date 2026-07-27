<?php
include("header.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
$host = "localhost";
$bd = "punta_cana";
$user = "root";
$pwd = "programacion";

try {
    $conexion = new PDO("mysql:host=$host;dbname=$bd", $user, $pwd);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $ex) {
    echo "Error de conexión: " . $ex->getMessage();
    exit();
}

// Recuperar información del usuario
$sql = $conexion->prepare("SELECT * FROM admin WHERE usuario = :usuario");
$sql->bindParam(':usuario', $_SESSION['usuario']);
$sql->execute();
$usuario_data = $sql->fetch(PDO::FETCH_ASSOC);

if (!$usuario_data) {
    echo "Error: No se pudo recuperar la información del usuario.";
    exit();
}

// Recuperar boletos del usuario
$sql = $conexion->prepare("
    SELECT b.idboletos, b.tipo 
    FROM boletos b 
    INNER JOIN admin a ON b.nombre = a.usuario 
    WHERE a.usuario = :usuario
");
$sql->bindParam(':usuario', $_SESSION['usuario']);
$sql->execute();
$boletos = $sql->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido!</title>
    <style>
body {
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #195370;
    font-size: 20px;
}

.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
}

main {
    background-color: #FFD700;
    border-radius: 20px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    width: 80%;
    max-width: 650px;
    padding: 30px;
    text-align: center;
    color: #333;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

main:hover {
    transform: scale(1.05);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.4);
}

h1 {
    margin-top: 0;
    font-size: 3.0em;
    font-family: 'Pacifico', cursive;
    color: #0a2192;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

p {
    font-size: 1.8em;
    margin: 10px 0;
}

.boleto {
    background-color: #FFF;
    border: 2px dashed #ccc;
    border-radius: 10px;
    margin: 20px 0;
    padding: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.boleto:hover {
    background-color: #f0f0f0;
    transform: translateY(-5px);
}

.boleto p {
    margin: 5px 0;
}

button {
    background-color: #0a2192;
    border: none;
    border-radius: 10px;
    color: white;
    padding: 10px 20px;
    margin: 10px 0;
    font-size: 1em;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
}

button:hover {
    background-color: #08317a;
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}

a {
    color: #0a2192;
    text-decoration: none;
    transition: color 0.3s ease;
    font-size: 30px;
}

a:hover {
    color: #08317a;
}


    </style>
</head>
<br><br><br>
<body>
    <div class="container">
        <main>
            <h1>Bienvenido <?php echo ($usuario_data['usuario']); ?>!</h1>
            <p><strong>Correo:</strong> <?php echo ($usuario_data['correo']); ?></p>
            <h2>Tus boletos:</h2>

            <?php if (!empty($boletos)) { ?>
                <ul>
                    <?php foreach ($boletos as $boleto) { ?>
                        <li class="boleto">
                            <p><strong>ID Boleto:</strong> <?php echo ($boleto['idboletos']); ?></p>
                            <p><strong>Tipo:</strong> <?php echo ($boleto['tipo']); ?></p>
                        </li>
                    <?php } ?>
                </ul>
            <?php } else { ?>
                <p>No tienes boletos registrados.</p>
            <?php } ?>

            <a href="index.php"><button>Volver</button></a>
        </main>
    </div>
</body>
</html>
