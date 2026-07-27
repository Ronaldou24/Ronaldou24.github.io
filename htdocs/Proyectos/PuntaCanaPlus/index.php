<?php include("header.php"); ?>
<div class="body_index">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Pacifico&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

    body {
        background-color: #195370;
        font-family: 'Roboto', sans-serif;
        color: #ffffff;
    }
    .titulo h1 {
        font-family: 'Pacifico', cursive;
        font-size: 4em;
        color: #D0D842;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        text-align: center;
        margin: 20px 0;
    }
    .card-section {
        display: flex;
        justify-content: space-around;
        margin-top: 30px;
        flex-wrap: wrap;
    }
    .card {
        background-color: #333;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        width: 300px;
        padding: 20px;
        text-align: center;
        color: #fff;
        margin: 20px;
        position: relative;
        transition: transform 0.3s;
    }
    .card:hover {
        transform: scale(1.05);
    }
    .card h3 {
        margin-top: 15px;
        font-size: 24px;
        font-weight: 700;
    }
    .card img {
        width: 100%;
        height: 200px; /* Altura fija para todas las imágenes */
        object-fit: cover; /* Asegura que la imagen cubra el área sin deformarse */
        border-radius: 10px;
        margin-bottom: 15px;
    }
    .card button {
        background-color: #D0D842;
        color: #0a2192;
        padding: 10px 20px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: background-color 0.3s, color 0.3s;
        font-size: 18px;
        font-weight: bold;
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
    }
    .card button:hover {
        background-color: #FFD700;
        color: #0a2192;
    }
    .container {
        display: flex;
        justify-content: center;
    }
    .flyer img {
        max-width: 100%;
        height: auto;
    }
    .flyer {
        border: 10px solid #195370;
        border-radius: 20px;
        padding: 20px;
    }
</style>

<div class="titulo">
    <h1>Punta Cana</h1>
    <h1>Festival Punta Cana</h1>
</div>
<div class="container">
    <main>
        <div class="flyer">
            <img src="imagenes/Flyer.jpeg" alt="Flyer">
        </div>
    </main>
</div>

<div class="card-section"> 
    <div class="card">
    <h3>Boletos</h3>
        <img src="imagenes/boletos.jpg" alt="Boletos">
        
    <br><br><br><br>
        <button onclick="location.href='boletos.php'">Más información</button>
    </div>
    <div class="card">
    <h3>Lineup</h3>
        <img src="imagenes/line-up.jpg" alt="Line-up">
        
        <button onclick="location.href='line-up.php'">Más información</button>
    </div>
    <div class="card">
    <h3>Información</h3>
        <img src="imagenes/info.jpeg" alt="Info">
       <br><br><br><br>
        <button onclick="location.href='info.php'">Más información</button>
    </div>
</div>
</div>
