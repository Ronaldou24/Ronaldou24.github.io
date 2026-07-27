

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
    <form action="seleccionarUsuario.php" method="POST">
        <p>
            <label for="username">Username:</label>
            <input type="text" name="username">
        </p>

        <p>
            <label for="password">Password:</label>
            <input type="password" name="password">
        </p>

        <p>
            <input type="submit" value="Login">
        </p>
    </form>
</body>
</html>
