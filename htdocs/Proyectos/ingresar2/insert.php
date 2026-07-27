<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include("dbconnection.php");

$nombre = $_POST["nombre"];
$apellido = $_POST["apellido"];
$email = $_POST["email"];

$sql = "INSERT INTO Patrons (FirstName, LastName, email) VALUES ('{$nombre}', '{$apellido}', '{$email}')";
$result = mysqli_query($conn, $sql);

if ($result) {
    // Si $result es verdadero indica que el query fue exitoso. 
    echo "Nuevo Registro Creado Exitosamente";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}


//Por default las conexiones se cierran automáticamente al
//terminar el script pero si quieres lo puedes forzar usando esta instrucción
mysqli_close($conn);
?>
<br>
<h1>Cliente añadido</h1>

<table>
        <tr>
            <th>Valores</th>
            <th>Datos de la cuenta</th>
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
            <td>Email</td>
            <td><?php echo $email; ?></td>
        </tr>
    </table>

<a href="post1.php">Regresar</a>








