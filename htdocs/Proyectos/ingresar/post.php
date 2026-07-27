
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
    <form action="mostarr_nuevo_user.php" method="POST">
        <p>
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre">
        </p>

        <p>
            <label for="apellido">Apelldo:</label>
            <input type="text" name="apellido">
        </p>

        <p>
            <label for="email">Email:</label>
            <input type="text" name="email">
     </p>

        <p>
            <input type="submit" value="Login">
        </p>
    </form>
</body>
</html>
