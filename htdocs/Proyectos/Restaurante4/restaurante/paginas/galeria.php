<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeria Mi restaurante</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/estilos1.css">
    <link rel="stylesheet" href="../css/gallery.css">
</head>
<body>
    <div class="modal" id="modal">
        <div class="modal__content">
            <img src="" alt="" class="modal__img" id="modal__img">
        </div>
        <div class="modal__boton" id="modal__boton">X</div>
    </div>
    <header class="main-header">
        <div class="container container--flex">
            <div class="logo-container column column--50">
                <h1 class="logo">Mi Restaurante</h1>
            </div>
            <div class="main-header__contactInfo column column--50">
                <p class="main-header__contactInfo__phone"><span class="icon-phone">322-2058758</span></p>
                <p class="main-header__contactInfo__address"><span class="icon-map">Lorem ipsum dolor sit amet.</span></p>
            </div>
        </div>
    </header>
    <nav class="main-menu">
        <div class="container container--flex">
            <span class="icon-menu" id="btnMenu"></span>
            <ul class="menu" id="menu">
                <li class="menu__item"><a href="../index.php" class="menu__link ">Inicio</a></li>
                <li class="menu__item"><a href="../paginas/nosotros.php" class="menu__link ">Nosotros</a></li>
                <li class="menu__item"><a href="../paginas/galeria.php" class="menu__link menu__link--select">Galeria</a></li>
                <li class="menu__item"><a href="../paginas/contacto.php" class="menu__link">Contacto</a></li>
            </ul>
            <div class="social-icon">
                <a href="" class="social-icon__link"><span class="icon-facebook"></span></a>
                <a href="" class="social-icon__link"><span class="icon-email"></span></a>
            </div>
        </div>
    </nav>
    <section class="banner">
        <img src="../imagenes/banner.jpg" alt="" class="banner__img">
        <div class="banner__content">
            Lo mejor de nosotros para tu paladar
        </div>
        </section>
   
        <main class= "main">
    <section class="group gallery">
        <h2 class="group__title">Nuestra Galeria</h2>
        <div class="container container--flex">
            <div class="column column--50-25">
                <img src="../imagenes/gallery/gallery1.jpg" alt="" class="gallery__img gallery__img-big">
                <img src="../imagenes/gallery/gallery2.jpg" alt="" class="gallery__img gallery__img-small">
            </div>
            <div class="column column--50-25">
                <img src="../imagenes/gallery/gallery3.jpg" alt="" class="gallery__img gallery__img-small">
                <img src="../imagenes/gallery/gallery4.jpg" alt="" class="gallery__img gallery__img-big">
            </div>
            <div class="column column--50-25">
                <img src="../imagenes/gallery/gallery5.jpg" alt="" class="gallery__img gallery__img-big">
                <img src="../imagenes/gallery/gallery6.jpg" alt="" class="gallery__img gallery__img-small">
            </div>
            <div class="column column--50-25">
                <img src="../imagenes/gallery/gallery7.jpg" alt="" class="gallery__img gallery__img-small">
                <img src="../imagenes/gallery/gallery8.jpg" alt="" class="gallery__img gallery__img-big">
            </div>
        </div>
    </section>
    </main>
        
        
        
        
        
        
        
        <footer class="main-footer">
        <div class="container container--flex">
            <div class="column column--33">
                <h2 class="column__title">¿Porque visitarnos?</h2>
                <p class="column__txt">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Asperiores eaque non laboriosam sapiente consequatur sunt est recusandae adipisci possimus. Nemo!</p>
            </div>
            <div class="column column--33">
                <h2 class="column__title">Contactanos</h2>
                <p class="column__txt"><span class="icon-map">Domicilio</span></p>
                <p class="column__txt"><span class="icon-phone">322</span></p>
                <p class="column__txt"><span class="icon-email">Correo</span></p>
            </div>
            <div class="column column--33">
                <h2 class="column__title">Siguenos en nuestras redes</h2>
                <p class="column__txt"><a href="" class="icon-facebook">facebook</a></p>
                <p class="column__txt"><a href="" class="icon-youtube">visita nuestro canal</a></p>
            </div>
            <p class="copy">&copy your restaurant | todos los derechos reservados</p>
        </div>
    </footer>
        <script src="../js/menu.js"></script>
        <script src="../js/modal.js"></script>
</body>
</html>