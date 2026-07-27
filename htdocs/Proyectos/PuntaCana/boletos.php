<?php 
include("template/header.php");
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<link rel="stylesheet" href="css/estilos.css">
<style>
.galeria {
    display: grid;
    grid-template-columns: repeat(4, 1fr); /* Cuatro columnas iguales */
    gap: 10px; /* Espacio entre los elementos de la galería */
}

.galeria div {
    display: grid;
    gap: 10px; /* Espacio entre las imágenes dentro de cada sección */
}

.galeria div img {
    width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: transform 0.2s;
}

/* Ajustes para cada sección */
.img-1 {
    grid-column: span 2; /* Ocupa 2 columnas */
    grid-row: span 1; /* Ocupa 1 fila */
    display: grid;
    grid-template-columns: 1fr 1fr; /* Dos columnas dentro de esta sección */
}

.img-2 {
    grid-column: span 2; /* Ocupa 2 columnas */
    grid-row: span 1; /* Ocupa 1 fila */
    display: grid;
    grid-template-columns: 1fr 1fr; /* Dos columnas dentro de esta sección */
}

.img-3 {
    grid-column: span 2; /* Ocupa 2 columnas */
    grid-row: span 1; /* Ocupa 1 fila */
    display: grid;
    grid-template-columns: 1fr 1fr; /* Dos columnas dentro de esta sección */
}

.img-4 {
    grid-column: span 2; /* Ocupa 2 columnas */
    grid-row: span 1; /* Ocupa 1 fila */
    display: grid;
    grid-template-columns: 1fr 1fr; /* Dos columnas dentro de esta sección */
}

.galeria div img:hover {
    transform: scale(1.05); /* Efecto de zoom al pasar el cursor */
}


</style>
<main>
    <div class="p-5 mb-4 bg-light rounded-3">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold">Macotas Kawais</h1>
            <p class="col-md-8 fs-4">
               Una numerosa cantidad de mascotas han estado felices con nuestro servicio
            </p>
            <button class="btn btn-primary btn-lg" type="button">
                Example button
            </button>
        </div>
    </div>
    <div class="galeria">
    <div class="img-1">
        <img src="imagenes/perro1.jpeg" alt="">
        <img src="imagenes/perro4.jpeg" alt="">
    </div>
    <div class="img-2">
        <img src="imagenes/perro3.jpeg" alt="">
        <img src="imagenes/perro2.jpeg" alt="">
    </div>
    <div class="img-3">
        <img src="imagenes/perro5.jpeg" alt="">
        <img src="imagenes/perro6.jpeg" alt="">
    </div>
    <div class="img-4">
        <img src="imagenes/perro7.jpeg" alt="">
        <img src="imagenes/perro8.jpeg" alt="">
    </div>
</div>

</main>
</body>
<?php 
include("template/footer.php");
?>
</html>