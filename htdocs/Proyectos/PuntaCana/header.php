<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Punta Cana</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
        }
        .navbar {
            background-color: #D0D842;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .navbar img {
            height: 50px;
            margin-left: 20px;
        }
        .navbar-nav {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .nav-item {
            list-style: none;
            margin-right: 20px;
        }
        .nav-link {
            color: #0a2192;
            font-weight: bold;
            font-size: 18px;
            text-decoration: none;
            transition: color 0.3s;
        }
        .nav-link:hover {
            color: #FFD700;
        }
        .nav-link.active {
            color: #FFD700;
        }
        /* Responsive styles */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }
            .navbar-nav {
                width: 100%;
                flex-direction: column;
                align-items: flex-start;
            }
            .nav-item {
                margin-right: 0;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a class="nav-item nav-link" href="">
            <img src="imagenes/logo.png" alt="Punta Cana" class="logopunta">
        </a>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link active" href="../index.php" aria-current="page">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../boletos.php">Boletos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../Line-up.php">Line-up</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../info.php">Info</a>
            </li>
        </ul>
    </nav>
</body>
</html>
