<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Cuates</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        /* Estilos CSS adicionales */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #121212;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
        }

        .main-header {
            background-color: #3c714d;
            padding: 15px 0;
            text-align: center;
        }

        .logo {
            color: #ffffff;
            margin: 0;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 2px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="date"]:focus,
        input[type="email"]:focus {
            border-color: #2e6565;
        }

        input[type="submit"] {
            background-color: #2e6565;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 3px;
            transition: background-color 0.4s ease;
        }

        input[type="submit"]:hover {
            background-color: #1a3e63;
        }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="container">
            <h1 class="logo">Agrega un cuate Nuevo</h1>
        </div>
    </header>
   
    <form id="pedidoForm" action="form.php" method="POST" >
        <div class="container">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input id="nombre" name="nombre" type="text" required>
                <span id="error-nombre" class="error"></span>
            </div>
            <div class="form-group">
                <label for="genero">Genero:</label>
                <input id="genero" name="genero" type="text" required>
                <span id="error-genero" class="error"></span>
            </div>
            <div class="form-group">
                <label for="fecha">Fecha:</label>
                <input id="fecha" name="fecha" type="date" required>
                <span id="error-fecha" class="error"></span>
            </div>
            <div class="form-group">
                <label for="celular">Celular:</label>
                <input id="celular" name="celular" type="number" required>
                <span id="error-celular" class="error"></span>
            </div>
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input id="correo" name="correo" type="email" required>
                <span id="error-correo" class="error"></span>
            </div>
           
            <div class="form-group">
                <input type="submit" value="OK" name="OK">
            </div>
        </div>
    </form>


</body>
</html>
