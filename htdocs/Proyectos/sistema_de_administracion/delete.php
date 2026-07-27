<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include("dbconnection.php");

// Verifica si el parámetro "PatronID" está presente en la solicitud POST
if (isset($_POST["PatronID"])) {
    // Escapa el valor del parámetro "PatronID" para evitar SQL injection
    $id_borrar = mysqli_real_escape_string($conn, $_POST["PatronID"]);

    // Construye la consulta SQL para obtener los datos del cliente antes de eliminarlo
    $query = "SELECT FirstName, LastName, Email, PatronID FROM patrons WHERE PatronID = '$id_borrar'";
    $result_select = mysqli_query($conn, $query);

    // Verifica si la consulta fue exitosa
    if ($result_select) {
        // Obtiene los datos del cliente
        $row = mysqli_fetch_assoc($result_select);
        $nombre = $row['FirstName'];
        $apellido = $row['LastName'];
        $correo = $row['Email'];
        $ID = $row['PatronID'];

        // Construye la consulta SQL para eliminar el registro
        $sql = "DELETE FROM patrons WHERE PatronID = '$id_borrar'";
    
        $result_delete = mysqli_query($conn, $sql);

        if ($result_delete) {
            // Si $result_delete es verdadero, indica que el query de eliminación fue exitoso.
?>
<!DOCTYPE html>
<html lang="en">
<head>
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

        h1 {
            color: #e74c3c;
            text-align: center;
            font-size: 50px;
        }

        a {
            color: #2ecc71;
            text-decoration: none;
            text-align: left;
            font-weight: bold;
            margin: 10px;
            display: inline-block;
            padding: 10px 20px;
            border: 2px solid #000000;
            border-radius: 30px;
            font-size: 30px;
            transition: background-color 0.3s, color 0.3s;
        }

        img {
            max-width: 100%;
            height: auto;
            margin-left: 20px;
        }

        a:hover {
            background-color: #000000;
            color: #ffffff;
        }

        table {
            margin-top: 20px;
            width: 60%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #000000;
            text-align: left;
            padding: 10px;
        }

        th {
            background-color: #e74c3c;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <h1>Registro eliminado exitosamente!</h1>
    <table>
        <tr>
            <th>Valores</th>
            <th>Datos del Cliente</th>
        </tr>
        <tr>
            <td>Nombre</td>
            <td><?php echo $nombre; ?></td>
        </tr>
        <tr>
            <td>Apellido</td>
            <td><?php echo $apellido ?></td>
        </tr>
        <tr>
            <td>Correo</td>
            <td><?php echo $correo; ?></td>
        </tr>
        <tr>
            <td>ID</td>
            <td><?php echo $ID; ?></td>
        </tr>
    </table>
    <a href="menu.php">Volver al Menú</a>
    <br>
    <a href="clientes.php">Ver tabla actualizada</a>
</body>
</html>
<?php
        } else {
            echo "Error al eliminar: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "Error al obtener los datos del cliente: " . mysqli_error($conn);
    }
} else {
    echo "ID no proporcionado.";
}

// Cierra la conexión a la base de datos
mysqli_close($conn);
?>
