<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mi Restaurante</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/estilos1.css">
</head>
<body>
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
                <li class="menu__item"><a href="/" class="menu__link menu__link--select">Inicio</a></li>
                <li class="menu__item"><a href="paginas/nosotros.php" class="menu__link">Nosotros</a></li>
                <li class="menu__item"><a href="paginas/galeria.php" class="menu__link">Galeria</a></li>
                <li class="menu__item"><a href="paginas/contacto.php" class="menu__link">Contacto</a></li>
            </ul>
            <div class="social-icon">
                <a href="" class="social-icon__link"><span class="icon-facebook"></span></a>
                <a href="" class="social-icon__link"><span class="icon-email"></span></a>
            </div>
        </div>
    </nav>
    <section class="banner">
        <img src="imagenes/banner.jpg" alt="" class="banner__img">
        <div class="banner__content">
            La mejor y más rica comida está aquí
        </div>
    </section>
    <main class="main">
        <section class="group group--color">
            <div class="container">
                <h2 class="main__title">Bienvenido a su restaurante</h2>
                <p class="main__txt">Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur recusandae sed aperiam id iste nihil deleniti ut, expedita sequi ipsa.</p>
            </div>
        </section>
        <section class="group main__about__description">
            <div class="container container--flex">
                <div class="column column--50">
                    <img src="imagenes/imgmain.jpg" alt="">
                </div>
                <div class="column column--50">
                    <h3 class="column__title">Gran catalogo de instrumentos</h3>
                    <p class="column__txt">Ofrecemos una amplia gama de instrumentos musicales de alta calidad, cuidadosamente seleccionados para satisfacer las necesidades de músicos de todos los géneros. Desde guitarras acústicas hasta sintetizadores, nuestra colección está diseñada para inspirar y elevar tu creatividad.</p>
                    <a href="" class="btn btn--contact">Contacto</a>
                </div>
            </div>
        </section>
        <section class="group today-special">
            <h2 class="group__title">Nuestra Selección de instrumentos</h2>
            <div class="container container--flex">
                <div class="column column--50-25">
                    <img src="imagenes/food1.jpg" alt="" class="today-special__img">
                    <div class="today-special__title">Especial del día 1</div>
                    <div class="today-special__price">$ 2.99</div>
                </div>
                <div class="column column--50-25">
                <img src="imagenes/food2.jpg" alt="" class="today-special__img">
                    <div class="today-special__title">Especial del día 2</div>
                    <div class="today-special__price">$ 2.99</div>
                </div>
                <div class="column column--50-25">
                <img src="imagenes/food3.jpg" alt="" class="today-special__img">
                    <div class="today-special__title">Especial del día 3</div>
                    <div class="today-special__price">$ 2.99</div>
                </div>
                <div class="column column--50-25">
                <img src="imagenes/food4.jpg" alt="" class="today-special__img">
                    <div class="today-special__title">Especial del día 4</div>
                    <div class="today-special__price">$ 2.99</div>
                </div>
            </div>
        </section>
    </main>
    <footer class="main-footer">
        <div class="container container--flex">
            <div class="column column--33">
                <h2 class="column__title">¿Porque visitarnos?</h2>
                <p class="column__txt"> nos comprometemos a brindar un servicio personalizado y experto a cada cliente. Nuestro equipo está formado por músicos apasionados que están aquí para ofrecerte orientación y apoyo en cada etapa de tu viaje musical.</p>
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
    <script src="js/menu.js"></script>
</body>
</html>