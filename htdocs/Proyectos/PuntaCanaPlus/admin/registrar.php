<?php
session_start();

$msg = ''; 


if (isset($_POST['guardar'])) {
    $usuario = $_POST['usuario'];
    $contras = $_POST['contras'];
    $correo = $_POST['correo'];

    try {
        include("config/bd.php");
        $sql = $conexion->prepare("INSERT INTO admin(usuario,contras,correo) VALUES(:usuario,:contras,:correo)");
        $sql->bindParam(':usuario', $usuario);
        $sql->bindParam(':contras', $contras);
        $sql->bindParam(':correo', $correo);

        $sql->execute();
        $sql = null;
        $conexion = null;

        $_SESSION['msg'] = "Cuenta registrada exitosamente.";
        header("Location: registrar.php");
        exit();
    } catch (Exception $e) {
        $msg = "Error: " . $e->getMessage();
    }
}

// Mostrar mensaje de éxito si existe en la sesión
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar</title>
    <link rel="stylesheet" href="styles.css">
    <style>
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
            margin: 15px;
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
            margin-top: 15px;
        }

        button:hover {
            background-color: #06287a;
        }

        .body_index {
            background-color: #195370;
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

        .login-link {
            margin-top: 20px;
        }

        .login-link a {
            color: #DF2CFF;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="body_index">
    <div class="container">
        <div class="login-card">
            <h2>Registrar</h2>
            <?php if (!empty($msg)) { ?>
                <div class="success-message"><?php echo $msg; ?></div>
                <div class="login-link"><a href="login.php">Volver al login</a></div>
            <?php } else { ?>
                <form action="registrar.php" method="POST">
                    <div class="input-group">
                        <label for="usuario">Usuario</label>
                        <input type="text" id="usuario" name="usuario" required>
                    </div>
                    <div class="input-group">
                        <label for="contraseña">Contraseña</label>
                        <input type="password" id="contraseña" name="contras" required>
                    </div>
                    <div class="input-group">
                        <label for="correo">Correo</label>
                        <input type="email" id="correo" name="correo" required>
                    </div>
                    <input type="submit" name="guardar" value="Registrar">
                    <p><a href="login.php">Login</a></p>
                </form>
            <?php } ?>
        </div>
    </div>
</body>
</html>
