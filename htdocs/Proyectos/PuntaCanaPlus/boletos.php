<?php include("header.php"); ?>
<style>
     @import url('https://fonts.googleapis.com/css2?family=Pacifico&display=swap');

    body {
        margin: 0;
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        background-color: #195370;
    }
    .logopunta {
        height: 50px;
        margin-left: 20px;
    }
    .container {
        padding: 30px;
        background-color: #247FAE; /* Azul medio */
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin: 20px auto;
        max-width: 800px;
        color: white;
        text-align: center;
        font-size: 50px;
    }
    .tickets {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 20px;
    }
    .ticket {
        background-color: #DF2C99; /* Rosa */
        border-radius: 10px;
        padding: 20px;
        margin: 10px;
        width: 80%;
        max-width: 700px;
        text-align: left;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .ticket h2 {
        background-color: #195370; /* Azul oscuro */
        color: white;
        margin: 0 -20px 20px -20px;
        padding: 10px;
        border-radius: 10px 10px 0 0;
        font-size: 35px;
    }
    .ticket ul {
        list-style: none;
        padding: 0;
        font-size: 25px;
    }
    .ticket ul li {
        margin: 20px 0;
    }
    .ticket .price {
        font-size: 60px;
        font-weight: bold;
        text-align: right;
        font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
    }
    .buy-button {
        background-color: #D0D842; /* Amarillo claro */
        color: #195370; /* Azul oscuro */
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
        display: block;
        margin-top: 10px;
        text-decoration: none;
        font-weight: bold;
    }
    .buy-button:hover {
        background-color: #FFFFFF; /* Blanco */
        color: #195370; /* Azul oscuro */
    }
</style>

<div class="container">
    <h1>Boletos</h1>
    <div class="tickets">
        <div class="ticket general">
            <h2>General</h2>
            <ul>
                <li>Acceso al festival y sus 3 escenarios</li>
                <li>Más de 10 horas de música</li>
                <li>Renta de lockers</li>
                <li>Zona de food trucks</li>
                <li>Zona de hidratación gratuita</li>
                <li>Asistencia médica</li>
            </ul>
            <div class="price">$1,000</div>
        </div>
        <div class="ticket bypass">
            <h2>ComfortPass</h2>
            <ul>
                <li>Acceso al festival y sus 3 escenarios</li>
                <li>Más de 10 horas de música</li>
                <li>Renta de lockers</li>
                <li>Zona de food trucks</li>
                <li>Carril de acceso rápido al festival</li>
                <li>Baños exclusivos</li>
                <li>Zona de hidratación gratuita</li>
                <li>Asistencia médica</li>
            </ul>
            <div class="price">$2,000</div>
        </div>
        <div class="ticket vip">
            <h2>VIP</h2>
            <ul>
                <li>Acceso al festival y sus 3 escenarios</li>
                <li>Más de 10 horas de música</li>
                <li>Renta de lockers</li>
                <li>Zona de food trucks</li>
                <li>Carril de acceso rápido al festival (VIP)</li>
                <li>Pit lateral en escenario principal</li>
                <li>Baños VIP</li>
                <li>Zona de hidratación gratuita</li>
                <li>Asistencia médica</li>
                <li>Experiencias de marcas</li>
                <li>Zona exclusiva con sombra</li>
                <li>Zona de mesas comunes</li>
                <li>Zona de food trucks exclusiva</li>
            </ul>
            <div class="price">$2,680</div>
        </div>
        <a href="comprar.php" class="buy-button">Comprar Boletos</a>
    </div>
</div>
</body>
</html>
