
<?php 
// Se pasan los input a variables de php
if(isset($_POST["guardar"])){
    $idmat = (isset($_POST["idmat"])) ? $_POST["idmat"] : 1;
    $nombre = (isset($_POST["nombre"])) ? $_POST["nombre"] : "NA";
    $hrst = (isset($_POST["hrst"])) ? $_POST["hrst"] : "A";
    $hrsp = (isset($_POST["hrsp"])) ? $_POST["hrsp"] : 0;
    $creditos = (isset($_POST["creditos"])) ? $_POST["creditos"] : "M";
}

try {
    include ("../config/bd.php");
    
    // Corregir la consulta SQL
    $sql = $conexion->prepare("INSERT INTO materias (idmat, nombre, hrst, hrsp, creditos) VALUES(:idmat, :nombre, :hrst, :hrsp, :creditos)");
    
    // Vincular parámetros
    $sql->bindParam(":idmat", $idmat);  // Aquí debe ser $idmat
    $sql->bindParam(":nombre", $nombre);
    $sql->bindParam(":hrst", $hrst);
    $sql->bindParam(":hrsp", $hrsp);
    $sql->bindParam(":creditos", $creditos);
    
    // Ejecutar la consulta
    $sql->execute();

    if($sql) {
        // Cerrar la conexión y redirigir
        $sql = null;
        $conexion = null;
       // header("Location: index.php");
    } else {
        echo "<script>alert('Error al registrar Materia')</script>";
    }

} catch(Exception $ex) {
    echo $ex->getMessage();
}
?>


<?php include "../template/header.php";  ?>


|
<div class="card" style="width: 60%; margin:auto">
    <div class="card-header"><h3>Registro de Materias</h3></div>
    <div class="card-body">
        <form action="" method="POST" class="row">

            <div class="col-4">
                <label for="idmat" class="form-label">ID MAT</label>
                <input type="text" name="idmat" class="form-control">
            </div>

            <div class="col-8">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control">
            </div>

            <div class="col-4">
                <label for="hrst" class="form-label">HRST</label>
                <input type="number" name="hrst" class="form-control">
            </div>

            <div class="col-4">
                <label for="hrsp" class="form-label">HRSP</label>
                <input type="number" name="hrsp" class="form-control">
            </div>

            <div class="col-4">
                <label for="creditos" class="form-label">Creditos</label>
                <input type="number" name="creditos" class="form-control">
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




