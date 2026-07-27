

<?php
session_start();

$msg = '';

if (isset($_POST['submit'])) {
    $tipo = $_POST['tipo'];
    $nombre = $_POST['nombre'];

    try {
        include("../PuntaCanaPlus/admin/config/bd.php");
        $sql = $conexion->prepare("INSERT INTO boletos(tipo, nombre) VALUES(:tipo, :nombre)");
        $sql->bindParam(':tipo', $tipo);
        $sql->bindParam(':nombre', $nombre);

        $sql->execute();
        $sql = null;
        $conexion = null;

        $_SESSION['msg'] = "Boleto registrado exitosamente.";
        header("Location: comprar.php");
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
    <title>Compra de Boletos</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@400;700&display=swap">
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
            font-size: 1em;
        }

        .container {
            display: flex;
            justify-content: center;
        }

        .form-card {
            background-color: #FFD700;
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            width: 400px;
            padding: 30px;
            text-align: center;
            color: #333;
            transition: transform 0.3s;
        }

        .form-card:hover {
            transform: scale(1.05);
        }

        .form-card h2 {
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

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            background-color: #f9f9f9;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s;
        }

        .input-group input:focus,
        .input-group select:focus {
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
    </style>
</head>
<body class="body_index">
    <div class="container">
        <div class="form-card">
            <h2>Registro de Boletos</h2>
            <?php if (!empty($msg)) { ?>
                <div class="alert"><?php echo $msg; ?></div>
            <?php } ?>
            <form action="comprar.php" method="POST">
                <div class="input-group">
                    <label for="tipo">Tipo de Boleto</label>
                    <select id="tipo" name="tipo" required>
                        <option value="normal">Normal</option>
                        <option value="comfortpass">ComfortPass</option>
                        <option value="vip">VIP</option>
                    </select>
                </div>
                <div class="input-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <button type="submit" name="submit">Registrar</button>
                <a href="index.php">Inicio</a>
            </form>
        </div>
    </div>
</body>
</html>
