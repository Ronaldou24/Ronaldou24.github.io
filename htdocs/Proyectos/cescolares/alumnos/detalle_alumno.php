<?php 

// Verificar si se ha enviado un ID a través de GET para ver los detalles del alumno
if(isset($_GET['detalle'])){
    $id = $_GET['id'];
    include("../config/bd.php");

    // Obtener los datos del alumno desde la base de datos
    $sql = $conexion->prepare("SELECT * FROM alumnos WHERE id = :id");
    $sql->bindParam(':id', $id);
    $sql->execute();
    $alumno = $sql->fetch(PDO::FETCH_ASSOC);

    // Cerrar la conexión
    $sql = null;
    $conexion = null;
}

// Verificar si se ha enviado una acción a través de POST
if(isset($_POST['accion'])){
    $accion = $_POST['accion']; // Definir la variable $accion

    switch($accion){
        case "editar":
            if(isset($_POST['id'])) {
                $id = $_POST['id'];
                $nombre = $_POST['nombre'];
                $grupo = $_POST['grupo'];
                $semestre = $_POST['semestre'];
                $turno = $_POST['turno'];

                include("../config/bd.php");

                // Actualizar los datos del alumno en la base de datos
                $sql = $conexion->prepare("UPDATE alumnos SET nombre = :nombre, grupo = :grupo, semestre = :semestre, turno = :turno WHERE id = :id");
                $sql->bindParam(':id', $id);
                $sql->bindParam(':nombre', $nombre);
                $sql->bindParam(':grupo', $grupo);
                $sql->bindParam(':semestre', $semestre);
                $sql->bindParam(':turno', $turno);
                $sql->execute();

                // Cerrar la conexión y redirigir
                $sql = null;
                $conexion = null;
                header("Location: index.php");
            }
            break;

        case "borrar":
            if(isset($_POST['id'])) {
                $id = $_POST['id'];

                include("../config/bd.php");

                // Eliminar el alumno de la base de datos
                $sql = $conexion->prepare("DELETE FROM alumnos WHERE id = :id");
                $sql->bindParam(':id', $id);
                $sql->execute();

                // Cerrar la conexión y redirigir
                $sql = null;
                $conexion = null;
                header("Location: index.php");
            }
            break;
    }
}
?>

<?php include "../template/header.php"; ?>

<div class="card" style="width: 60%; margin:auto">
    <div class="card-header"><h3>Registro de Alumnos</h3></div>
    <div class="card-body">
        <form action="" method="POST" class="row">

            <div class="col-4">
                <label for="id" class="form-label">ID</label>
                <input type="text" name="id" class="form-control" value="<?php echo $alumno['id']; ?>" readonly>
            </div>

            <div class="col-8">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo $alumno['nombre']; ?>">
            </div>

            <div class="col-4">
                <label for="grupo" class="form-label">Grupo</label>
                <input type="text" name="grupo" class="form-control" value="<?php echo $alumno['grupo']; ?>">
            </div>

            <div class="col-4">
                <label for="semestre" class="form-label">Semestre</label>
                <input type="text" name="semestre" class="form-control" value="<?php echo $alumno['semestre']; ?>">
            </div>

            <div class="col-4">
                <label for="turno" class="form-label">Turno</label>
                <input type="text" name="turno" class="form-control" value="<?php echo $alumno['turno']; ?>">
            </div>

    </div>
    <div class="card-footer text-muted">
        <div class="btn-group" role="group">
            <button type="submit" class="btn btn-primary" name="accion" value="editar">Editar</button>
            <button type="submit" class="btn btn-danger" name="accion" value="borrar">Borrar</button>
        </div>
   
        <a class="btn btn-warning" href="index.php" role="button">Regresar</a>
    </form>
    </div>
</div>

<?php include "../template/footer.php"; ?>
