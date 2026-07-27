
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <style>
        body{ color: red;
             font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif ;
             }
    </style>
    <h1>Formulario</h1>
    <form action="insert.php" method="POST">
        <p>
            <label for="titulo">Titulo:</label>
            <input type="text" name="titulo">
        </p>

        <p>
            <label for="autor">Autor:</label>
            <input type="text" name="autor">
        </p>

        <p>
            <label for="publicado">Publicado:</label>
            <input type="text" name="publicado">
     </p>

     <p>
            <label for="barcode">Bar Code:</label>
            <input type="text" name="barcode">
     </p>

        <p>
            <input type="submit" value="Login">
        </p>
    </form>
</body>
</html>
