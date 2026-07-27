<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<link rel="stylesheet" href="../contactos/css/estilos.css">
<body>
    <div class="contact_form">
        <div class="formulario">
            <h1>Formulario de contactos</h1>
            <h2>Ingresa los datos</h2>
            <form action="pages/datos.php" method="POST">

                <p>
                    <label for="nombre" class="colocar_nombre">Nombre <span class="obligatorio"></span>
                    </label>
                    <input type="text" name="nombre" id="nombre" required placeholder="Pon tu nombre">
                </p>

                <p>
                    <label for="email" class="colocar_email">Email <span class="obligatorio"></span>
                    </label>
                    <input type="email" name="email" id="email" required placeholder="Pon tu email">
                </p>

                <p>
                    <label for="website" class="colocar_website">Sitio Web <span class="obligatorio"></span>
                    </label>
                    <input type="url" name="website" id="website" required placeholder="Pon tu sitio web">
                </p>

                <p>
                    <label for="asunto" class="colocar_asunto">Asunto <span class="obligatorio"></span>
                    </label>
                    <input type="text" name="asunto" id="asunto" required placeholder="Asunto">
                </p>

                <p>
                    <label for="mensaje" class="colocar_mensaje">Mensaje <span class="obligatorio"></span>
                    </label>
                    <textarea name="mensaje" id="mensaje" placeholder="Deja tu comentario"></textarea>
                </p>
                <button type="submit" name="enviar" id="enviar">Enviar mensaje</button>
            </form>
        </div>
    </div>
</body>
</html>