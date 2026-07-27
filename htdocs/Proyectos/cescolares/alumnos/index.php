<?php
    include "../config/bd.php";
    $sql = $conexion->prepare("SELECT * FROM alumnos ORDER BY nombre;");
    $sql ->execute();
    $alumnos = $sql->fetchAll(PDO::FETCH_ASSOC);
    $sql  = null;
    $conexion = null;
?>
<?php include "../template/header_sec.php";
?>

<div class="card">
    <div class="card-header">
        <h3>Listado de alumnos</h3>
        <a class  = "btn btn-secondary" href="form_nuevo.php" role = "button">Nuevo</a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-secondary">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Grupo</th>
                    <th scope="col">Sem</th>
                    <th scope="col">Tno</th>
                    <th scope = "col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($alumnos as $alumno) {
                ?>
                <tr class="">
                    <td><?php echo $alumno['id'];?></td>
                    <td><?php echo $alumno['nombre'];?></td>
                    <td><?php echo $alumno['grupo'];?></td>
                    <td><?php echo $alumno['semestre'];?></td>
                    <td><?php echo $alumno['turno'];?></td>
                    <td>
                        <form action="detalle_alumno.php" method="GET">
                            <input type="text" name="id" hidden value = "<?php echo $alumno['id']; ?>">
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
    <h1>pagina de alumnos</h1>
    </div>
<?php include "../template/footer.php"; ?>