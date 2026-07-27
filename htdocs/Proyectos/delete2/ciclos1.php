<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include("dbconnection.php");

$sql = "SELECT * FROM books";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
<style>
  /* Estilo para la tabla */
  h1{ color: black;
}
  table {
    width: 100%;
    border-collapse: collapse;
    background-color: #ff5555; /* Fondo rojo */
    border: 2px solid #ff0000; /* Borde rojo más oscuro */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra suave */
  }
  th, td {
    border: 2px solid #ff0000;
    padding: 10px;
    text-align: left;
  }
    a {
       color: #000000; /* Cambia este valor al color que desees */
       text-decoration: none;
       font-weight: bold;
       margin: 10px;
       display: inline-block;
       padding: 10px 20px;
       border: 2px solid #000000;
       border-radius: 30px;
       font-size: 30px;
      transition: background-color 0.3s, color 0.3s;
                      }
    th {
    background-color: #ff0000; /* Encabezado rojo */
    color: white; /* Texto blanco en el encabezado */
  }
  tr:nth-child(even) {
    background-color: #ff9999; /* Fondo más claro para filas pares */
  }
</style>
</head>
<body>
<h1>Tabla de Libros</h1>
<a href="nuevo.php">Nuevo</a>
<a href="editar.php">Editar</a>
<?php
if ($result) {
    // Si $result es verdadero indica que la consulta fue exitosa. 
    // mysqli_fetch_assoc() se usa para obtener la primera fila del resultado.
    if (mysqli_num_rows($result) > 0) {
        
        echo "<table>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Titulo</th>";
        echo "<th>Autor</th>";
        echo "<th>Publicado</th>";
        echo "<th>Bar Code</th>";
        echo "</tr>";
        while ($row = mysqli_fetch_assoc($result)){
        echo "<tr>";
        echo "<td>" . $row["BookID"] . "</td>";
        echo "<td>" . $row["Title"] . "</td>";
        echo "<td>" . $row["Author"] . "</td>";
        echo "<td>" . $row["Published"] . "</td>";
        echo "<td>" . $row["Barcode"] . "</td>";
        echo "<td><a href='delete.php'" . $row["BookID"] . "'>Eliminar</a></td>";
        echo "</tr>";
    } 
    echo "</table>";
}
    else {
        // De lo contrario, se imprime "0 results".
        echo "0 results";
    }
} else {
    echo "Error en la consulta: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
</body>
</html>
