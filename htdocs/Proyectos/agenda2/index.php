<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            "pageLength": 10,
            "language": {
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Actividades",
                "infoEmpty": "Mostrando 0 a 0 de 0 Actividades",
                "infoFiltered": "(Filtrado de _MAX_ total Actividades)",
                "lengthMenu": "Mostrar _MENU_ Actividades",
                "search": "Buscador:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            }
        });
    });
</script>



<?php
include "config/bd.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['folio'])) {
    $folio = $_POST['folio'];
    $estado = isset($_POST['estado']) ? 1 : 0;  

    $sql = "UPDATE agenda.registro SET estado = :estado WHERE Folio = :folio";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
    $stmt->bindParam(':folio', $folio, PDO::PARAM_STR);

    if ($stmt->execute()) {
    } else {
        echo "Error al actualizar el estado";
    }
}


$sql = "SELECT * FROM agenda.registro";
$stmt = $conexion->prepare($sql);
$stmt->execute();
$listadoMov = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = null;
?>

<div class="card">
    <div class="card-header">
        <h3>Listado de Movimientos</h3>
        <form method="POST" action="">
            <input type="radio" id="todo" name="movimiento" value="todo" checked>
            <label for="todo">Todos</label>
            <button type="submit" class="btn-secondary">Buscar</button>
        </form>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table" id="myTable">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Actividad</th>
                        <th>Estado</th>
                        <th>Si o No</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($listadoMov)) {
                        foreach ($listadoMov as $mov) { ?>
                            <tr>
                                <td><?php echo $mov['folio'] ?? 'Sin Folio'; ?></td>
                                <td><?php echo $mov['fecha'] ?? 'Sin Fecha'; ?></td>
                                <td><?php echo $mov['hora'] ?? 'Sin Hora'; ?></td>
                                <td><?php echo $mov['actividad'] ?? 'Sin Actividad'; ?></td>
                                <td><?php echo $mov['estado'] == 0 ? '0' : '1'; ?></td>
                                <td>
                                    <form method="POST" action="">
                                        <input type="hidden" name="folio" value="<?php echo $mov['folio']; ?>" />
                                        <input type="checkbox" name="estado" 
                                               onchange="this.form.submit()" 
                                               <?php echo $mov['estado'] == 1 ? 'checked' : ''; ?> />
                                    </form>
                                </td>
                            </tr>
                    <?php } } else { ?>
                        <tr>
                            <td colspan="6">No se encontraron movimientos</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer">Fin de la lista</div>
    <script>
	
    let table = new DataTable('#myTable');</script>
</div>

<style>

body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    color: #333;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 120vh;
}


.card {
    background-color: #ffffff;
    border: 2px solid #ccc;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    max-width: 100%; 
    width: 100%;
    padding: 20px; 
    margin: 10px; 
    box-sizing: border-box;
    justify-content: center;
}


.card-header {
    background-color: #f0f0f0;
    text-align: center;
    padding: 10px;
    font-size: 2.5em; 
    font-weight: bold;
    color: #555;
}
.btn-secondary {
    background-color: #757575;
    color: white;
    padding: 10px 20px; 
    border: none;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 10px; 
    transition: background-color 0.3s, transform 0.2s;
}

.btn-secondary:hover {
    background-color: #5a5a5a;
    transform: scale(1.05);
}


.table-responsive {
    margin-top: 10px; 
    overflow-x: auto; 
}


.table {
    width: 100%;
    border-collapse: collapse;
    justify-content: center;
}

.table th, .table td {
    padding: 29px;
    text-align: center;
    font-size: 1.5em;
}
.table td{
    font-size: 1.3em;
}

.table thead {
    background-color: #757575;
    color: white;
}


.table tbody tr:nth-child(even) {
    background-color: #f0f0f0;
}


.table tbody tr:hover {
    background-color: #e0e0e0;
    cursor: pointer;
}


.card-footer {
    background-color: #f0f0f0;
    color: #555;
    text-align: center;
    padding: 10px;
    font-weight: bold;
    margin-top: 10px;
}


@media screen and (max-width: 768px) {
    .card {
        padding: 10px; 
    }

    .card-header {
        font-size: 1.2em;
    }

    .table th, .table td {
        padding: 8px;
        font-size: 0.8em; 
    }

    .btn-secondary {
        padding: 8px 15px; 
    }
}
</style>
