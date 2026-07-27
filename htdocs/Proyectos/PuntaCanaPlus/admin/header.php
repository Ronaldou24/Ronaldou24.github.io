<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Header</title>
    <style>
        body {
            margin: 0;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }
        .navbar {
            background-color: #D0D842;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar .nav-item {
            list-style: none;
            display: inline;
        }
        .navbar .nav-item .nav-link {
            color: #0a2192;
            font-weight: bold;
            margin-right: 20px;
            font-size: 18px;
            text-decoration: none;
            transition: color 0.3s;
        }
        .navbar .nav-item .nav-link:hover {
            color: #FFD700;
        }
        .navbar .nav-item .nav-link.active {
            color: #FFD700;
        }
        .logopunta {
            height: 50px;
            margin-left: 20px;
        }
        .header {
            background-color: #247FAE;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: white;
            margin-bottom: 20px;
        }
        .header img {
            max-height: 50px;
            vertical-align: middle;
        }
        .header-title {
            display: inline-block;
            margin-left: 20px;
            vertical-align: middle;
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <img src="../imagenes/logo.png" alt="Logo" class="logopunta">
        <ul>
            <li class="nav-item"><a href="../index.php" class="nav-link">Inicio</a></li>
            <li class="nav-item"><a href="cerrar.php" class="nav-link">Cerrar</a></li>
        </ul>
    </div>


    <div class="container">
        <!-- Your content here -->
    </div>

</body>
</html>
