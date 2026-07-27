<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promoción del Taller - Agencia de Autos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        header {
            background-color: #004aad;
            color: white;
            padding: 15px 0;
            text-align: center;
        }
        h1 {
            margin: 0;
        }
        .promo {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 20px auto;
            text-align: center;
        }
        .promo h2 {
            color: #004aad;
        }
        .promo p {
            font-size: 1.2em;
        }
        nav {
            margin-top: 20px;
            text-align: center;
        }
        nav a {
            text-decoration: none;
            display: inline-block;
            background-color: #004aad;
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            font-size: 1.2em;
            margin: 10px;
            transition: background-color 0.3s ease;
        }
        nav a:hover {
            background-color: #e65c00;
        }
        .important {
            background-color: #28a745;
            font-size: 1.4em;
            padding: 20px 35px;
            margin-top: 20px;
        }
        .important:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <header>
        <h1>¡Bienvenido al Taller de la Agencia de Autos!</h1>
    </header>

    <div class="promo">
        <h2>Promoción Especial de Mantenimiento</h2>
        <p>¡Trae tu auto para un servicio de mantenimiento completo y recibe un 20% de descuento en reparaciones mayores!</p>
        <p>Esta oferta es válida hasta el final del mes, así que no pierdas esta oportunidad de mantener tu auto en perfectas condiciones.</p>
        <p>¡Nuestros expertos mecánicos están listos para atenderte!</p>
    </div>

    <nav>
        <h3>Accede rápidamente a nuestros servicios más importantes:</h3>
        
        <!-- Enlaces destacados para Ventas y Reparaciones -->
        <a href="ventas.php" class="important">Ventas</a>
        <a href="reparaciones.php" class="important">Reparaciones</a>


        <h3>Explora más opciones:</h3>
        <a href="clientes.php">Clientes</a>
        <a href="autos.php">Autos Disponibles</a>
        <a href="mecanicos.php">Mecánicos</a>
        <a href="ventas_dia.php">Ventas del Día</a>
        <a href="reparaciones_dia.php">Reparaciones del Día</a>
    </nav>
</body>
</html>
