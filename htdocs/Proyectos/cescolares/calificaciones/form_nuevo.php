
<?php 
// Se pasan los input a variables de php
if(isset($_POST["guardar"])){
    $idmat = (isset($_POST["id"])) ? $_POST["id"] : 1;
    $nombre = (isset($_POST["idmat"])) ? $_POST["idmat"] : "NA";
    $hrst = (isset($_POST["parcial1"])) ? $_POST["parcial1"] : "A";
    $hrsp = (isset($_POST["parcial2"])) ? $_POST["parcial2"] : 0;
    $creditos = (isset($_POST["parcial3"])) ? $_POST["parcial3"] : "M";
}

try {
    include ("../config/bd.php");
    
    // Corregir la consulta SQL
    $sql = $conexion->prepare("INSERT INTO calificaciones (id, idmat, parcial1, parcial2, parcial3) VALUES(:id, :idmat, :parcial1, :parcial2, :parcial3");
    
    // Vincular parámetros
    $sql->bindParam(":id", $id);  // Aquí debe ser $idmat
    $sql->bindParam(":idmat", $idmat);
    $sql->bindParam(":parcial1", $parcial1);
    $sql->bindParam(":parcial2", $parcial2);
    $sql->bindParam(":parcial3", $parcial3);

    
    // Ejecutar la consulta
    $sql->execute();

    if($sql) {
        // Cerrar la conexión y redirigir
        $sql = null;
        $conexion = null;
        //header("Location: index.php");
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
    <div class="card-header"><h3>Registro de Calificaciones</h3></div>
    <div class="card-body">
        <form action="" method="POST" class="row">
           
            

            <div class="col-4">
                <label for="id" class="form-label">ID</label>
                <input type="text" name="id" class="form-control">
            </div>

            <div class="col-8">
                <label for="idmat" class="form-label">ID MAT</label>
                <input type="text" name="idmat" class="form-control">
            </div>

            <div class="col-4">
                <label for="parcial1" class="form-label">Parcial 1</label>
                <input type="text" name="parcial1" class="form-control">
            </div>

            <div class="col-4">
                <label for="parcial2" class="form-label">Parcial 2</label>
                <input type="text" name="parcial2" class="form-control">
            </div>

            <div class="col-4">
                <label for="parcial3" class="form-label">Parcial 3</label>
                <input type="text" name="parcial3" class="form-control">
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




