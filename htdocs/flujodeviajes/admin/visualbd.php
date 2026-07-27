<?php
$servername = "localhost";
$username = "root";
$password = "programacion"; 
$dbname = "Flujotransportes";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$tables = $conn->query("SHOW TABLES");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizador de Base de Datos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-container { margin: 20px; }
        .table-title { background-color: #f8f9fa; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Contenido de la Base de Datos</h2>
        
        <?php while($table = $tables->fetch_array()) : ?>
            <?php 
            $tableName = $table[0];
            $result = $conn->query("SELECT * FROM $tableName");
            ?>
            
            <div class="table-container">
                <div class="table-title">
                    <h4>Tabla: <?php echo $tableName; ?></h4>
                    <small>Registros: <?php echo $result->num_rows; ?></small>
                </div>
                
                <?php if($result->num_rows > 0) : ?>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <?php while($field = $result->fetch_field()) : ?>
                                    <th><?php echo $field->name; ?></th>
                                <?php endwhile; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()) : ?>
                                <tr>
                                    <?php foreach($row as $value) : ?>
                                        <td><?php echo htmlspecialchars($value); ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <div class="alert alert-warning">No hay registros en esta tabla</div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>

    <?php
    // Cerrar conexión
    $conn->close();
    ?>
</body>
</html>