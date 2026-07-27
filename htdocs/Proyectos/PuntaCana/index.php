<?php include("header.php"); ?>
<link rel="stylesheet" href="css/index.css">
<div class="container">
    <style>
        body {
    margin: 0;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    background-color: #f8f9fa; /* Fondo claro para todo el sitio */
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
.card-section {
    display: flex;
    justify-content: space-around;
    margin-top: 20px;
    flex-wrap: wrap;
}
.card {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 30%;
    padding: 20px;
    text-align: center;
    color: #333;
    position: relative;
    background-size: cover;
    background-position: center;
    margin: 10px;
}
.card h3 {
    margin-top: 0;
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    padding: 10px;
    border-radius: 5px;
}
.card button {
    background-color: #195370;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
}
.card button:hover {
    background-color: #143a5b;
}

/* Background images for cards */
.boletos {
    background-image: url('imagenes/boletos.jpg');
}
.lineup {
    background-image: url('imagenes/lineup.jpg');
}
.info {
    background-image: url('imagenes/info.jpg');
}

/* Responsive styles */
@media (max-width: 768px) {
    .card {
        width: 45%;
    }
}

@media (max-width: 480px) {
    .card {
        width: 100%;
    }
    .navbar {
        flex-direction: column;
    }
    .navbar .nav-item {
        display: block;
        margin-bottom: 10px;
    }
}

    </style>
    <main>
        <div class="flyer">
            <img src="imagenes/Flyer.jpeg" alt="Flyer">
        </div>
    </main>
    <div class="card-section">
        <div class="card boletos">
            <h3>Boletos</h3>
            <button onclick="location.href='imagenes/bp'">Comprar</button>
        </div>
        <div class="card lineup">
            <h3>Line-up</h3>
            <button onclick="location.href='#'">Ver Line-up</button>
        </div>
        <div class="card info">
            <h3>Info</h3>
            <button onclick="location.href='#'">Más Info</button>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka2YvPv9TIRhAq6z8SDGkKx0Tm4KbSKWcy4E2tbYklL4Sz31Tx3W6ahzn2R3VsUU" crossorigin="anonymous"></script>
