<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nosotros</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/estilos2.css">
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
                <li class="menu__item"><a href="../index.php" class="menu__link ">Inicio</a></li>
                <li class="menu__item"><a href="../paginas/nosotros.php" class="menu__link menu__link--select">Nosotros</a></li>
                <li class="menu__item"><a href="../paginas/galeria.php" class="menu__link">Galeria</a></li>
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
            Lo mejor de nosotros a tu servicio 
        </div>
        </section>
        <main class="main">
        <section class="group group--color">
            <div class="container">
                <h2 class="main__title">Sobre Nosotros</h2>
                <p class="main__txt">Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur recusandae sed aperiam id iste nihil deleniti ut, expedita sequi ipsa.</p>
            </div>
        </section>
        
        <section class="group today-special">
            <h2 class="group__title">Nuestro Equipo</h2>
            <div class="container container--flex">
                <div class="column column--33">
                <div class="today-special__price">Hugo</div>
                <br>
                    <img src="../imagenes/food1.jpg" alt="" class="today-special__img">
                    <br><br>
               <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quae, temporibus?</p>
                </div>
                <div class="column column--33">
                <div class="today-special__price">Juan</div>
                <br>
                <img src="../imagenes/food2.jpg" alt="" class="today-special__img">
                <br><br>
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Reprehenderit, et?</p>
                </div>
                <div class="column column--33">
                <div class="today-special__price">Max</div>
                <br>
                <img src="../imagenes/food3.jpg" alt="" class="today-special__img">
                <br><br>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae, nostrum!</p>
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
</body>
</html>