<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes</title>
    <style>
        body {
            color: #333;
            font-family: 'Roboto', sans-serif;
            background-color: #ecf0f1;
            text-align: center;
            margin: 20px;
        }

        h1 {
            color: #000;
            font-size: 36px;
            margin-bottom: 20px;
        }

        a {
            color: #000;
            text-decoration: none;
            font-weight: bold;
            margin: 10px;
            display: block;
            padding: 10px 20px;
            border: 2px solid #000;
            border-radius: 30px;
            font-size: 20px;
            transition: background-color 0.3s, color 0.3s;
        }

        a:hover {
            background-color: #000;
            color: #fff;
        }
    </style>
</head>
<body>
    <h1>Reportes</h1>
    <a href="libros-por-mes.php">Cantidad de libros prestados por mes</a>
    <a href="libros-pendientes.php">Libros pendientes de devolución</a>
    <a href="total-clientes.php">Cantidad total de clientes</a>
    <a href="grafica-prestados-por-año.php">Gráfica libros prestados por año</a>
    <a href="grafica.php">Gráfica cantidad de préstamos</a>
    <a href="grafica_tendencia.php">Gráfica tendencia libros por año</a>
    <a href="menu.php">Volver al menú</a>
</body>
</html>
