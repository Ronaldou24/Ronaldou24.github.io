
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
<style>
        body {
            background-color: #f4f4f4;
            color: #333;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #d9534f;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #d9534f;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #c9302c;
        }
    </style>
    <h1>Formulario</h1>
    <form action="insert.php" method="POST">
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
