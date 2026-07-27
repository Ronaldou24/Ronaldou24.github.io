<?php include("header.php"); ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>@import url('https://fonts.googleapis.com/css2?family=Pacifico&display=swap');
        body{
            background-color: #143a5b;
            font-size: 20px;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }
       .card {
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    color: #333;
    margin: 15px 0;
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-10px);
}

.card img {
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    height: 200px;
    width: 100%;
    object-fit: cover;
}

.card h3 {
    font-size: 1.7em;
    margin: 15px 0;
}

.card button {
    background-color: #195370;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.card button:hover {
    background-color: #143a5b;
}
/* Modifica la paleta de colores */
.card {
  background-color: #DF2C99; /* Fondo oscuro para las tarjetas */
  color: #fff; /* Texto blanco para legibilidad */
}

.card button {
  background-color: #FFD700; /* Botón amarillo */
  color: #0a2192; /* Texto del botón en azul */
}

    </style>
<div class="header">
    
</div>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <!-- Repetir esta estructura para cada artista -->
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <img src="img_artistas/natanael.avif" class="card-img-top" alt="Natanael Cano">
                    <div class="card-body">
                        <h3 class="card-title">Natanael Cano</h3>
                        <button class="btn btn-primary btn-block" onclick="location.href='artistas/natanael.php'">Más Info</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <img src="img_artistas/metro_boomin.jpg" class="card-img-top" alt="Metro Boomin">
                    <div class="card-body">
                        <h3 class="card-title">Metro Boomin</h3>
                        <button class="btn btn-primary btn-block" onclick="location.href='artistas/metroboomin.php'">Más Info</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <img src="img_artistas/kevin_amf.jpg" class="card-img-top" alt="Kevin AMF">
                    <div class="card-body">
                        <h3 class="card-title">Kevin AMF</h3>
                        <button class="btn btn-primary btn-block" onclick="location.href='artistas/kevinamf.php'">Más Info</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <img src="img_artistas/kendrick_lamar.jpg" class="card-img-top" alt="Kendrick Lamar">
                    <div class="card-body">
                        <h3 class="card-title">Kendrick Lamar</h3>
                        <button class="btn btn-primary btn-block" onclick="location.href='artistas/kendrick.php'">Más Info</button>
                    </div>
                </div>
            </div>
            <!-- Agregar más tarjetas de artistas aquí -->
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <img src="img_artistas/rojuu.jpg" class="card-img-top" alt="Kendrick Lamar">
                    <div class="card-body">
                        <h3 class="card-title">Rojuu</h3>
                        <button class="btn btn-primary btn-block" onclick="location.href='artistas/rojuu.php'">Más Info</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <img src="img_artistas/blnko.jpg" class="card-img-top" alt="Kendrick Lamar">
                    <div class="card-body">
                        <h3 class="card-title">Blnko</h3>
                        <button class="btn btn-primary btn-block" onclick="location.href='artistas/blnko.php'">Más Info</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <img src="img_artistas/21_savage.jpg" class="card-img-top" alt="Kendrick Lamar">
                    <div class="card-body">
                        <h3 class="card-title">21 Savage</h3>
                        <button class="btn btn-primary btn-block" onclick="location.href='artistas/21_savage.php'">Más Info</button>
                    </div>
                </div>
            </div>
           
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <img src="img_artistas/drake.jpg" class="card-img-top" alt="Kendrick Lamar">
                    <div class="card-body">
                        <h3 class="card-title">Drake</h3>
                        <button class="btn btn-primary btn-block" onclick="location.href='artistas/drake.php'">Más Info</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <img src="img_artistas/sza.jpg" class="card-img-top" alt="Kendrick Lamar">
                    <div class="card-body">
                        <h3 class="card-title">Sza</h3>
                        <button class="btn btn-primary btn-block" onclick="location.href='artistas/sza.php'">Más Info</button>
                    </div>
                </div>
            </div>


            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <img src="img_artistas/future.jpg" class="card-img-top" alt="Kendrick Lamar">
                    <div class="card-body">
                        <h3 class="card-title">Future</h3>
                        <button class="btn btn-primary btn-block" onclick="location.href='artistas/future.php'">Más Info</button>
                    </div>
                </div>
            </div>
            <!-- Agregar más tarjetas de artistas aquí -->
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <img src="img_artistas/doja.jpg" class="card-img-top" alt="Kendrick Lamar">
                    <div class="card-body">
                        <h3 class="card-title">Doja Cat</h3>
                        <button class="btn btn-primary btn-block" onclick="location.href='artistas/dojacat.php'">Más Info</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <img src="img_artistas/playboy.jpg" class="card-img-top" alt="Kendrick Lamar">
                    <div class="card-body">
                        <h3 class="card-title">Playboy Carti</h3>
                        <button class="btn btn-primary btn-block" onclick="location.href='artistas/playboy.php'">Más Info</button>
                    </div>
                </div>
            </div>
            

            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <img src="img_artistas/trueno.jpg" class="card-img-top" alt="Kendrick Lamar">
                    <div class="card-body">
                        <h3 class="card-title">Trueno</h3>
                        <button class="btn btn-primary btn-block" onclick="location.href='artistas/trueno.php'">Más Info</button>
                    </div>
                </div>
            </div>
            <!-- Agregar más tarjetas de artistas aquí -->
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <img src="img_artistas/travis.jpg" class="card-img-top" alt="Kendrick Lamar">
                    <div class="card-body">
                        <h3 class="card-title">Travis Scott</h3>
                        <button class="btn btn-primary btn-block" onclick="location.href='artistas/travis.php'">Más Info</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <img src="img_artistas/the_wekeend.jpg" class="card-img-top" alt="Kendrick Lamar">
                    <div class="card-body">
                        <h3 class="card-title">The Wekeend</h3>
                        <button class="btn btn-primary btn-block" onclick="location.href='artistas/thewekeend.php'">Más Info</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
