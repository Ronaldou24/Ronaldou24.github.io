<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Clientes</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <!-- Estilos personalizados -->
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
            background-color: #007bff;
            color: white;
            padding: 10px 20px; 
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px; 
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-secondary:hover {
            background-color: #0056b3;
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

        .table td {
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
</head>
<body>

<?php
include "config/bd.php";

$sql = "SELECT * FROM taller_mecanico.clientes;";

$stmt = $conexion->prepare($sql);
$stmt->execute();
$listadoCliente = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = null;
?>

<div class="card">
    <div class="card-header">
        <h3>Listado de Clientes</h3>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="myTable">
                <thead>
                    <tr>
                        <th>Dni</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($listadoCliente)) {
                        foreach ($listadoCliente as $cliente) { ?>
                            <tr>
                                <td><?php echo $cliente['dni'] ?? 'Sin Dni'; ?></td>
                                <td><?php echo $cliente['nombre'] ?? 'Sin Nombre'; ?></td>
                                <td><?php echo $cliente['apellido'] ?? 'Sin Apellido'; ?></td>
                                <td><?php echo $cliente['direccion'] ?? 'Sin Dirección'; ?></td>
                                <td><?php echo $cliente['telefono'] ?? 'Sin Teléfono'; ?></td>
                            </tr>
                    <?php } } else { ?>
                        <tr>
                            <td colspan="5">No se encontraron clientes</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer">Fin de la lista</div>
</div>

<!-- Inicialización de DataTable -->
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

</body>
</html>
