<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Punta Cana</title>
    <style>@import url('https://fonts.googleapis.com/css2?family=Pacifico&display=swap');

        body {
            margin: 0;
            font-family: 'Pacifico', cursive;
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
            cursor: pointer;
            height: 50px;
            margin-left: 20px;
        }
        .container {
            padding: 20px;
            background-color: #247FAE;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 800px;
            color: white;
            text-align: center;
        }
        .flyer img {
            max-width: 80%;
            height: auto;
            border: 10px solid #195370;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div>
            <a class="nav-item nav-link" href="index.php">
                <img src="imagenes/logo.png" alt="Punta Cana" class="logopunta">
            </a>
        </div>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link active" href="index.php" aria-current="page">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="boletos.php">Boletos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Line-up.php">Line-up</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="info.php">Info</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin/login.php">Iniciar Sesion</a>
            </li>
        </ul>
    </nav>

   
</body>
</html>
