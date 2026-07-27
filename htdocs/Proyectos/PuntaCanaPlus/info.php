<?php include("header.php"); ?>
<div class="body_index">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@300;400;700&display=swap');

        body {
            background-color: #195370;
            font-family: 'Roboto', sans-serif;
            color: white;
            margin: 0;
            padding: 20px;
        }

        .titulo h1 {
            font-family: 'Pacifico', cursive;
            font-size: 4em;
            color: #FFD700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            text-align: center;
            margin: 20px 0;
        }

        .info-section {
            text-align: center;
            margin-top: 20px;
        }

        .info-section h1 {
            font-family: 'Pacifico', cursive;
            font-size: 3em;
            margin-bottom: 20px;
            color: #FFD700;
        }

        .info-section p {
            font-size: 1.5em;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto 20px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }

        .card-section {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }

        .card {
            background: linear-gradient(145deg, #2d557a, #3d6f9e);
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            width: 30%;
            padding: 20px;
            text-align: center;
            color: #fff;
            position: relative;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card h3 {
            font-family: 'Pacifico', cursive;
            margin-top: 15px;
            font-size: 2em;
            color: #FFD700;
        }

        .card img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .card button {
            background-color: #FFD700;
            color: #0a2192;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
            font-size: 1.2em;
            font-weight: bold;
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
        }

        .card button:hover {
            background-color: #D0D842;
            color: #0a2192;
        }

        .container {
            display: flex;
            justify-content: center;
        }

        .flyer {
            border: 10px solid #195370;
            border-radius: 20px;
            padding: 20px;
        }

        .flyer img {
            max-width: 100%;
            height: auto;
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
    </style>

    <div class="titulo">
        <h1>Punta Cana</h1>
    </div>

    <main>
        <div class="info-section">
            <h1>Festival Punta Cana</h1>
            <p>
                ¡Bienvenidos al Festival Punta Cana! Este festival se celebra en las hermosas playas de Punta Cana durante el verano. Es una experiencia única que combina música, sol y mar en un solo lugar. Durante tres días, podrás disfrutar de más de 30 artistas en vivo, repartidos en tres escenarios increíbles.
            </p>
            <p>
                Además de la música, el festival ofrece una variedad de actividades para todos los gustos: desde deportes acuáticos, áreas de relajación en la playa, hasta puestos de comida gourmet y bares con los mejores cócteles tropicales. También contamos con zonas exclusivas para aquellos que buscan una experiencia VIP, con acceso rápido, áreas de sombra y servicios premium.
            </p>
            <p>
                No te pierdas esta oportunidad de vivir un verano inolvidable en el Festival Punta Cana. ¡Compra tus boletos ahora y prepárate para la mejor fiesta en la playa!
            </p>
        </div>

        <div class="container">
            <div class="flyer">
                <img src="imagenes/Flyer.jpeg" alt="Flyer">
            </div>
        </div>

        </div>
    </main>
</div>
