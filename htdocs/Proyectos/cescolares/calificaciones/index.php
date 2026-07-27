<?php
include "../config/bd.php";

// Consulta inicial para obtener todas las calificaciones
$sql = $conexion->prepare("
    SELECT calificaciones.id as id, alumnos.nombre as nombre, calificaciones.idmat, parcial1, parcial2, parcial3
    FROM alumnos, calificaciones, materias
    WHERE alumnos.id = calificaciones.id
");
$sql->execute();
$listadoCal = $sql->fetchAll(PDO::FETCH_ASSOC);
$sql = null;
$conexion = null;

// Si se presiona el botón de buscar
if (isset($_POST["buscar"])) {
    $materia = $_POST['materia']; // Materia seleccionada

    include "../config/bd.php";
    
    // Consulta con filtro por materia
    $sql = $conexion->prepare("SELECT calificaciones.id as id, alumnos.nombre as nombre, idmat, parcial1, parcial2, parcial3, materia.nombre as materias
        FROM alumnos, calificaciones, materias
        WHERE alumnos.id = calificaciones.id AND
        materias.idmat = calificaciones.idmat 
        AND calificaciones.idmat = :idmat
    ");
    

    $sql->bindParam(':idmat', $materias);
    
    $sql->execute();
    $listadoCal = $sql->fetchAll(PDO::FETCH_ASSOC);
    $sql = null;
    $conexion = null;
}
?>
<?php include "../template/header_sec.php"; ?>

<div class="card">
    <div class="card-header">
        <h3>Listado de Calificaciones de la materia de <?php echo $listadoCal[0]['materia'];?></h3>
        <a class="btn btn-secondary" href="form_nuevo.php" role="button">Nuevo</a>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <div class="mb-3">
            <form action="" method="POST">
                <label for="materias" class="form-label">Materia</label>
                <select class="form-select form-select-lg" name="materias" id="materias">
                    <option value="1">Cálculo</option>
                    <option value="2">Física</option>
                    <option value="3">Inglés</option>
                    <option value="4">CTSV</option>
                    <option value="5">Base de Datos</option>
                    <option value="6">PHP</option>
                </select>
                <br>
                <button type="submit" name="buscar" class="btn btn-secondary">Buscar</button>
            </form>
        </div>

        <table class="table table-secondary">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">ID MAT</th>
                    <th scope="col">Parcial 1</th>
                    <th scope="col">Parcial 2</th>
                    <th scope="col">Parcial 3</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($listadoCal as $cal) { ?>
                <tr>
                    <td><?php echo $cal['id']; ?></td>
                    <td><?php echo $cal['nombre']; ?></td>
                    <td><?php echo $cal['idmat']; ?></td>
                    <td><?php echo isset($cal['parcial1']) ? $cal['parcial1'] : 'N/A'; ?></td>
                    <td><?php echo isset($cal['parcial2']) ? $cal['parcial2'] : 'N/A'; ?></td>
                    <td><?php echo isset($cal['parcial3']) ? $cal['parcial3'] : 'N/A'; ?></td>

                    <td>
                        <form action="detalle_materias.php" method="GET">
                            <input type="text" name="idmat" hidden value="<?php echo $cal['idmat']; ?>">
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
    <h1>Página de Calificaciones</h1>
</div>

<?php include "../template/footer.php"; ?>
