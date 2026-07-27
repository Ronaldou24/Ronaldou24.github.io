

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Natanael Cano - Detalle del Artista</title>
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
            <img src="../img/playboy.jpeg" alt="Natanael Cano">
            <div class="artist-details">
                <h2 class="artist-title">Playboy Carti</h2>
                <p class="artist-description">
                Playboi Carti, cuyo nombre real es Jordan Terrell Carter, es un rapero y cantante estadounidense conocido por su estilo único que mezcla el rap experimental con influencias de trap y punk. Originario de Atlanta, Georgia, Carti ganó prominencia con canciones como "Magnolia" y "Shoota", caracterizadas por sus ritmos contagiosos y letras que reflejan su estilo de vida y experiencias personales. Ha colaborado con varios artistas prominentes y continúa siendo una figura influyente en la escena musical contemporánea.
                </p>
                <a href="../line-up.php">Line-up</a>
                <div class="social-links">
                    <a href="https://www.instagram.com/playboicarti/" class="fab fa-instagram"></a>
                    <a href="https://open.spotify.com/intl-es/artist/699OTQXzgjhIYAHMy9RyPD" class="fab fa-spotify"></a>
                    <a href="https://www.youtube.com/@playboicarti" class="fab fa-youtube"></a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
