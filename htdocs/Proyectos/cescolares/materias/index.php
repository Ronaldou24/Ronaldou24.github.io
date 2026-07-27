<?php include "../template/header.php"; ?>
<?php
    include "../config/bd.php";
    $sql = $conexion->prepare("SELECT * FROM materias ORDER BY nombre;");
    $sql ->execute();
    $materias = $sql->fetchAll(PDO::FETCH_ASSOC);
    $sql  = null;
    $conexion = null;
?>
<?php include "../template/header_sec.php";
?>

<div class="card">
    <div class="card-header">
        <h3>Listado de Materias</h3>
        <a class  = "btn btn-secondary" href="form_nuevo.php" role = "button">Nuevo</a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-secondary">
            <thead>
                <tr>
                    <th scope="col">ID Mat</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">HRST</th>
                    <th scope="col">HRSP</th>
                    <th scope="col">Creditos</th>
                    <th scope="col">Detalles</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($materias as $materia) {
                ?>
                <tr class="">
                    <td><?php echo $materia['idmat'];?></td>
                    <td><?php echo $materia['nombre'];?></td>
                    <td><?php echo $materia['hrst'];?></td>
                    <td><?php echo $materia['hrsp'];?></td>
                    <td><?php echo $materia['creditos'];?></td>
                    <td>
                        <form action="detalle_materias.php" method="GET">
                            <input type="text" name="idmat" hidden value = "<?php echo $materia['idmat']; ?>">
                            <button type="submit" class="btn btn-warning" name="detalle">Detalles</button>
                            
                        </form>
                    </td>
                   
                </tr>
                <?php } ?>
            </tbody>
        </table>
      </div>
      
    </div>
    <div class="card-footer text-muted">Fin de la lista</div>
</div>


    <div class="container">
    <h1>pagina de Materias</h1>
    </div>
<?php include "../template/footer.php"; ?>