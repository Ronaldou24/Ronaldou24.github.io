<?php 
session_start();
if (isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$msg = '';

if (isset($_POST['ingresar'])) {
    //-- Conexión a la base de datos
    $host = "localhost";
    $bd = "punta_cana";
    $user = "root";
    $pwd = "programacion";

    try {
        $conexion = new PDO("mysql:host=$host;dbname=$bd", $user, $pwd);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $ex) {
        $msg = "Error de conexión: " . $ex->getMessage();
    }
    //-- Fin de la conexión

    if (empty($msg)) {
        $usuario = $_POST['usuario'];
        $contras = $_POST['contras'];

        $sql = $conexion->prepare("SELECT * FROM admin WHERE usuario = :usuario AND contras = :contras");
        $sql->bindParam(':usuario', $usuario);
        $sql->bindParam(':contras', $contras);
        $sql->execute();

        $resultado = $sql->rowCount();

        if ($resultado > 0) {
            $usuario_data = $sql->fetch(PDO::FETCH_ASSOC);
            $_SESSION['usuario'] = $usuario_data['usuario'];
            header('Location: index.php');
            exit();
        } else {
            $msg = "Error: Usuario o contraseña incorrectos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@400;700&display=swap">
    <style>
        /* Aquí va tu CSS */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #195370;
        }

        .container {
            display: flex;
            justify-content: center;
        }

        .login-card {
            background-color: #FFD700;
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            width: 400px;
            padding: 30px;
            text-align: center;
            color: #333;
            transition: transform 0.3s;
        }

        .login-card:hover {
            transform: scale(1.05);
        }

        .login-card h2 {
            margin-top: 0;
            font-size: 2.5em;
            font-family: 'Pacifico', cursive;
            color: #0a2192;
        }

        .input-group {
            margin: 15px 0;
            text-align: left;
        }

        .input-group label {
            display: block;
            font-size: 1.2em;
            margin-bottom: 5px;
            color: #0a2192;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            background-color: #f9f9f9;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s;
        }

        .input-group input:focus {
            border-color: #0a2192;
            outline: none;
        }

        button {
            background-color: #0a2192;
            color: white;
            padding: 15px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 1.2em;
        }

        button:hover {
            background-color: #06287a;
        }

        .alert {
            background-color: #ffcccc;
            color: #d60000;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .alert strong {
            font-weight: bold;
        }

        a {
            color: #0a2192;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="body_index">
    <div class="container">
        <div class="login-card">
            <h2>Login</h2>
            <?php if (!empty($msg)) { ?>
                <div class="alert">
                    <strong><?php echo $msg; ?></strong>
                </div>
            <?php } ?>
            <form action="login.php" method="POST">
                <div class="input-group">
                    <label for="usuario">Usuario</label>
                    <input type="text" id="usuario" name="usuario" required>
                </div>
                <div class="input-group">
                    <label for="contras">Contraseña</label>
                    <input type="password" id="contras" name="contras" required>
                </div>
                <button type="submit" name="ingresar">Ingresar</button>
                <p>No tienes cuenta? <a href="registrar.php">Regístrate</a></p>
            </form>
        </div>
    </div>
</body>
</html>
