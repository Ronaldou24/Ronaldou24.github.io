
    <style>
        /* Estilos generales del footer */
.footer {
    background-color: #481117;
    color: #fff;
    padding: 40px 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.footer .container {
    max-width: 1200px;
    margin: 0 auto;
}

.footer .row {
    display: flex;
    flex-wrap: wrap;
}

.footer .col-md-4 {
    flex: 1;
    margin: 10px 0;
}

.f_titulo {
    font-size: 1.5rem;
    margin-bottom: 20px;
    border-bottom: 2px solid #ffffff;
    display: inline-block;
}

.social-links {
    list-style: none;
    padding: 0;
}

.social-links li {
    margin: 10px 0;
}

.social-links a {
    color: #fff;
    text-decoration: none;
    transition: color 0.3s;
}

.social-links a:hover {
    color: #e5ac15;
    transform: scale(1.1);
}

address {
    font-style: normal;
}

address p {
    margin: 5px 0;
}

    </style>
    
    <title>Tu página</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Vincula tu archivo de estilos CSS -->
    <link rel="stylesheet" href="../css/footer.css">



    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h2 class="f_titulo">Enlaces Relacionados</h2>
                </div>
                <div class="col-md-4">
                    <h2 class="f_titulo">Redes sociales</h2>
                    <ul class="social-links">
                        <li><a href="#">Facebook</a></li>
                        <li><a href="#">YouTube</a></li>

                    </ul>
                </div>
                <div class="col-md-4">
                    <h2 class="f_titulo">Dónde Ubicarnos</h2>
                    <address>
                        <p>Av Quimixto 628</p>
                        <p>Col Villas del Mar Puerto Vallarta, Jalisco</p>
                    </address>
                </div>
            </div>
        </div>
    </footer>

