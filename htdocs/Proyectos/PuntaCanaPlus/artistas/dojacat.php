
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doja cat - Detalle del Artista</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #143a5b;
    color: #ffffff;
    padding: 25px;
    margin: 0;
}

.container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
}

.artist-card {
    display: flex;
    background-color: #1f4068;
    color: #ffffff;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    max-width: 800px;
    overflow: hidden;
    width: 100%;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.artist-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
}

.artist-card img {
    width: 50%;
    object-fit: cover;
}

.artist-details {
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.artist-title {
    font-size: 3.0em;
    color: #FF3E99;
    text-align: center;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
}

.artist-description {
    font-size: 1.1em;
    text-align: justify;
    line-height: 1.6;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
}

.social-links {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    gap: 20px;
}

.social-links a {
    color: #ffffff;
    font-size: 2em;
    transition: color 0.3s ease;
}

.social-links a:hover {
    color: #FF3E99;
}

/* Icon Styles */
.fa-instagram, .fa-facebook, .fa-twitter, .fa-spotify, .fa-youtube {
    font-size: 2em;
}
a{
    font-size: 1.0em;

}

    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="artist-card">
            <img src="../img/doja.jpeg" alt="Natanael Cano">
            <div class="artist-details">
                <h2 class="artist-title">Doja Cat</h2>
                <p class="artist-description">
                Doja Cat, cuyo nombre real es Amala Ratna Zandile Dlamini, es una cantante, rapera y compositora estadounidense conocida por su versatilidad musical que abarca géneros como el pop, el R&B y el hip hop. Su ascenso a la fama se consolidó con el éxito viral de su canción "Say So" en 2020, que la catapultó a la escena mainstream. Doja Cat es elogiada por su estilo único y provocativo, así como por su habilidad para combinar letras ingeniosas con melodías contagiosas, estableciéndose como una figura.
                </p>
                <a href="../line-up.php">Line-up</a>
                <div class="social-links">
                    <a href="https://www.instagram.com/natanael_cano" class="fab fa-instagram"></a>
                    <a href="https://open.spotify.com/artist/5pDjmC5mRl7vDJhsjVwNfk" class="fab fa-spotify"></a>
                    <a href="https://www.youtube.com/channel/UCY3n4oKGzW34TPaXWybGqfw" class="fab fa-youtube"></a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
