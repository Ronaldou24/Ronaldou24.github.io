<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado</title>
    <style>
        h1{color: black;}
        body{ color: red;
             font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif ;
        }
        table {
            border-collapse: collapse;
            width: 50%;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Nuevo usuario añadido</h1>
    
    <table>
        <tr>
            <th>Valores</th>
            <th>Datos de la cuenta</th>
        </tr>
        <tr>
            <td>Nombre</td>
            <td><?php echo $_POST["nombre"]; ?></td>
        </tr>
        <tr>
            <td>Apellido</td>
            <td><?php echo $_POST["apellido"]; ?></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><?php echo $_POST["email"]; ?></td>
        </tr>
    </table>
    <a href="post.php">Volver a el Lobby</a>
</body>
</html>
