<?php
include "config/bd.php";

$listadoMov = [];

// Procesar formulario
if (isset($_POST['movimiento'])) {
    $movimiento = $_POST['movimiento'];

    include "config/bd.php";
    
    if ($movimiento == 'I' || $movimiento == 'E') {
        // Consulta con filtro para el Movimiento (I para Ingresos, E para Egresos)
        $sql = $conexion->prepare("SELECT * FROM movimientos.ing_egr WHERE tipo_m = :movimiento");
        $sql->bindParam(':movimiento', $movimiento);
    } else {
        // Consulta todos los movimientos
        $sql = $conexion->prepare("SELECT * FROM movimientos.ing_egr");
    }
    
    $sql->execute();
    $listadoMov = $sql->fetchAll(PDO::FETCH_ASSOC);
    $sql = null;
    $conexion = null;
}
?>

<style>

/* General styling */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    color: #333;
    margin: 0;
    padding: 0;
}

/* Card styling */
.card {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
    padding: 20px;
    max-width: 800px;
}

/* Card header styling */
.card-header {
    background-color: #ff7043; /* More vibrant orange */
    color: white;
    padding: 10px;
    border-radius: 8px 8px 0 0;
    font-size: 1.5em;
    font-weight: bold;
    text-align: center;
}

/* Button styling */
.btn-secondary {
    background-color: #ffb73f; /* Lighter orange */
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    font-size: 1em;
    transition: background-color 0.3s ease;
}

.btn-secondary:hover {
    background-color: #ff9800; /* Darker orange on hover */
}

/* Radio button styling */
form input[type="radio"] {
    display: none;
}

form input[type="radio"] + label {
    display: inline-block;
    background-color: #ffb74d;
    color: white;
    padding: 10px 20px;
    border-radius: 20px;
    cursor: pointer;
    margin-right: 10px;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

form input[type="radio"]:checked + label {
    background-color: #ff7043;
    transform: scale(1.1);
}

form input[type="radio"] + label:hover {
    background-color: #ff9800;
}

/* Table styling */
.table-responsive {
    margin-top: 20px;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table thead {
    background-color: #ffcc80; /* Lighter orange */
    color: white;
}

.table th,
.table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.table tbody tr:nth-child(even) {
    background-color: #ffe0b2; /* Very light orange for alternating rows */
}

/* Footer styling */
.card-footer {
    background-color: #ffcc80;
    color: #333;
    padding: 10px;
    text-align: center;
    border-radius: 0 0 8px 8px;
    font-size: 0.9em;
}



</style>
<div class="card">
    <div class="card-header">
        <h3>Listado de Movimientos</h3>
        <form method="POST" action="">
            <input type="radio" id="ingreso" name="movimiento" value="I">
            <label for="ingreso">Ingreso</label>

            <input type="radio" id="egreso" name="movimiento" value="E">
            <label for="egreso">Egreso</label>

            <input type="radio" id="todo" name="movimiento" value="todo">
            <label for="todo">Todos</label>
            
            <button type="submit" class="btn-secondary">Buscar</button>
        </form>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-secondary">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Fecha</th>
                        <th>Tipo de Movimiento</th>
                        <th>Concepto</th>
                        <th>Importe</th>
                        <th>Forma de Pago</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($listadoMov)) {
                        foreach ($listadoMov as $mov) { ?>
                    <tr>
                        <td><?php echo $mov['folio']; ?></td>
                        <td><?php echo $mov['fecha']; ?></td>
                        <td><?php echo $mov['tipo_m']; ?></td>
                        <td><?php echo $mov['concepto']; ?></td>
                        <td><?php echo $mov['importe']; ?></td>
                        <td><?php echo $mov['forma_pago']; ?></td>
                    </tr>
                    <?php } 
                    } else { ?>
                    <tr>
                        <td colspan="6">No se encontraron movimientos</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer text-muted">Fin de la lista</div>
    <div class="card-footer text-muted "><a href="nuevo_mov.php">Nuevo Movimiento</a></div>

</div>
