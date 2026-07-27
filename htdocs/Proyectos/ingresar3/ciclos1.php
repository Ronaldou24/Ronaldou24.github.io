<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include("dbconnection.php");

$sql = "SELECT * FROM Patrons";
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
<h1>Tabla de Patrons</h1>
<?php
if ($result) {
    // Si $result es verdadero indica que la consulta fue exitosa. 
    // mysqli_fetch_assoc() se usa para obtener la primera fila del resultado.
    if (mysqli_num_rows($result) > 0) {
        
        echo "<table>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Nombre</th>";
        echo "<th>Email</th>";
        echo "</tr>";
        while ($row = mysqli_fetch_assoc($result)){
        echo "<tr>";
        echo "<td>" . $row["PatronID"] . "</td>";
        echo "<td>" . $row["FirstName"] . " " . $row["LastName"] . "</td>";
        echo "<td>" . $row["Email"] . "</td>";
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
