
<?php 
// Se pasan los input a bvariables de php
if(isset($_POST["guardar"])){
    $id = (isset($_POST["id"]))?$_POST["id"] : 1;
    $nombre = (isset($_POST["nombre"]))?$_POST["nombre"]:"NA";
    $grupo = (isset($_POST["grupo"]))?$_POST["grupo"]:"A";
    $semestre = (isset($_POST["semestre"]))?$_POST["semestre"]:0;
    $turno = (isset($_POST["turno"]))?$_POST["turno"]:"M";
}

// Se establece la conexion, se registra el alumno y cerrar la Basa de Datos
try {
    include ("../config/bd.php");
    
    // Corregir la consulta SQL, agregando el paréntesis de cierre
    $sql = $conexion->prepare("INSERT INTO alumnos (id, nombre, grupo, semestre, turno) VALUES(:id, :nombre, :grupo, :semestre, :turno)");
    
    // Vincular parámetros
    $sql->bindParam(":id", $id);
    $sql->bindParam(":nombre", $nombre);
    $sql->bindParam(":grupo", $grupo);
    $sql->bindParam(":semestre", $semestre);
    $sql->bindParam(":turno", $turno);
    
    // Ejecutar la consulta
    $sql->execute();

    if($sql) {
        // Cerrar la conexión y redirigir
        $sql = null;
        $conexion = null;
        header("Location: index.php");
    } else {
        echo "<script>alert('Error al registrar el Alumno')</script>";
    }

} catch(Exception $ex) {
    echo $ex->getMessage();
}


?>


<?php include "../template/header.php"; ?>


|
<div class="card" style="width: 60%; margin:auto">
    <div class="card-header"><h3>Registro de Alumnos</h3></div>
    <div class="card-body">
        <form action="" method="POST" class="row">

            <div class="col-4">
                <label for="id" class="form-label">ID</label>
                <input type="text" name="id" class="form-control">
            </div>

            <div class="col-8">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control">
            </div>

            <div class="col-4">
                <label for="grupo" class="form-label">Grupo</label>
                <input type="text" name="grupo" class="form-control">
            </div>

            <div class="col-4">
                <label for="semestre" class="form-label">Semestre</label>
                <input type="text" name="semestre" class="form-control">
            </div>

            <div class="col-4">
                <label for="turno" class="form-label">Turno</label>
                <input type="text" name="turno" class="form-control">
            </div>

    </div>
    <div class="card-footer text-muted">
        <button type="submit"class="btn btn-primary" name="guardar">
            Guardar
        </button>
        <a name=""id="" class="btn btn-danger" href="index.php"role="button">
            Regresar
        </a>
        
        
    </form>
    </div>
</div>






<?php include "../template/footer.php"; ?>




