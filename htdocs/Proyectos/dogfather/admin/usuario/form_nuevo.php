<?php  include "../templates/header.php"?>
<?php
    if(isset($_POST['guardar'])){
        $id     = (isset($_POST['id']))?$_POST['id']:"";
        $nombre    = (isset($_POST['nombre']))?$_POST['nombre']:"";
        $password   = (isset($_POST['password']))?$_POST['password']:"";

        try{
            include("../config/bd.php");
            $sql = $conexion->prepare("INSERT INTO usuarios(id,nombre,password) VALUES(:id,:nombre,:password)");
             $sql->bindParam(':id',$id);
             $sql->bindParam(':nombre',$nombre);
             $sql->bindParam(':password',$password);
             $sql->execute();
             if($sql) {
                $sql = null;
                $conexion =null;
                header("Location: index.php");

             }else {
                echo "<script>alert('Error al querer guardar..');</script>";
             }
        }catch (Exception $ex){
            echo $ex->getMessage();
        }
    }
?>
<main>

         <div class="card" style="width: 60%; margin: auto;">
            <div class="card-header"><h3>Registro de usuarios:</h3></div>
            <div class="card-body">
                <form action="" method="POST">
                            <div class="mb-3">
                                <label for="id" class="form-label">ID del usuario: </label>
                                <input type="text" class="form-control" name="id" />
                                
                            </div>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre del usuario: </label>
                                <input type="text" class="form-control" name="nombre" />
                                
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña: </label>
                                <input type="text" class="form-control" name="password" />
                                
                            </div>                
                            
              
            </div>
            <div class="card-footer text-muted">
                <button type="submit" class="btn btn-primary" name="guardar" >
                  Guardar
                </button>
                <a class="btn btn-warning" href="index.php" role="button" >Regresar</a>
        </form>
            </div>
         </div>
         </main>
         <br><br>
         



<?php  include "../templates/footer.php"?>