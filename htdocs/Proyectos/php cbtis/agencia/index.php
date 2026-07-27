<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agencia de Terrenos</title>
</head>
<link rel="stylesheet" href="../agencia/css/estilo.css">
<body>
    <div class="contact_form">
        <div class="formulario">
            <h1>Formulario de Datos</h1>
            <h2>Ingresa los datos</h2>
            <h2>Recuerda el m2 vale 1500$</h2>
            <form action="pages/agencia.php" method="Post">

                <p>
                    <label for="nombre">Nombre <span class="obligatorio"></span>
                    </label>
                    <input type="text" name="nombre" id="nombre" required placeholder="Pon tu nombre">
                </p>

                <p>
                    <label for="ancho" >Ancho <span class="obligatorio"></span>
                    </label>
                    <input type="number" name="ancho" id="ancho" required placeholder="Ingresa el ancho del terreno">
                </p>

                <p>
                    <label for="largo">Largo <span class="obligatorio"></span>
                    </label>
                    <input type="number" name="largo" id="largo" required placeholder="Ingresa el largo del terreno">
                </p>

                <p>
                    <label for="m2e" >Numero de m*2 <span class="obligatorio"></span>
                    </label>
                    <input type="number" name="m2e" id="m2e" required placeholder="Ingrese la cantidad de m2">
                </p>

                <p>
                    <label for="enganche">Enganche <span class="obligatorio"></span>
                    </label>
                    <input type="number" name="enganche" id="enganche" placeholder="Cuanto dinero de enganche vas a poner">
                </p>

                <h2>Numero de meses a pagar el terreno = 60</h2>
                <button type="submit" name="enviar" id="enviar">Enviar Propuesta</button>
            </form>
        </div>
    </div>
</body>
</html>