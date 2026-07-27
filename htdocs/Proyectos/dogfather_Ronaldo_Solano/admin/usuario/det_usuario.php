<?php  include "../templates/header.php"?>
<?php
    if(isset($_GET['detalle'])){
        $idm = $_GET['idusuario'];
        
        include("../config/bd.php");
        $sql = $conexion->prepare("SELECT * FROM usuarios WHERE id = :idm");
        $sql->bindParam('idm', $idm);
        $sql->execute();
        $usuarioEnc = $sql->fetch(PDO::FETCH_LAZY); #LAZY SACA SOLO UN DATO DE LA TABLA 
        
    
        $sql      = null; #Cerramos la base de datos con null
        $conexion = null;
    }

     if(isset($_POST['accion'])){
        $accion = $_POST['accion'];
        

        switch($accion){
    case "eliminar":
        $idusuario =  $_POST['idusuario'];
        include("../config/bd.php");
        $sql = $conexion->prepare("UPDATE usuarios SET estatus = 1 WHERE id = :idm");
        $sql->bindParam(':idm',$idusuario);
        $sql->execute();
        $sql = null;
        $conexion = null;
        header("Location: index.php");
        break;
        
    case "editar":

        break;
        }
        
        }
?>
<main>

         <div class="card" style="width: 60%; margin: auto;">
            <div class="card-header"><h3>Registro de Usuarios</h3></div>
            <div class="card-body">
                <form action="" method="POST">
                    <input type="text" name="idusuario" value = "<?php echo $usuarioEnc[0];?>"hidden>
                    <div class="mb-3">
                                <label for="id" class="form-label">ID del usuario: </label>
                                <input type="text" class="form-control" name="id"
                                value = "<?php echo $usuarioEnc[0];?>" />
                                
                            </div>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre del usuario: </label>
                                <input type="text" class="form-control" name="nombre"
                                value = "<?php echo $usuarioEnc[1];?>" />
                                
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña: </label>
                                <input type="text" class="form-control" name="password" 
                                value = "<?php echo $usuarioEnc[2];?>"/>
                                
                            </div>  
                           
              
            </div>
            <div class="card-footer text-muted">
                <div class="btn-group" role="group"> 
                <button type="submit" class="btn btn-primary" name="accion" value="editar">Editar</button>
                <button type="submit" class="btn btn-danger" name="accion" value="eliminar" onclick="return confirma_eliminar()">Eliminar</button>
                </div>
                <a class="btn btn-warning" href="index.php" role="button" >Regresar</a>
                 </form>

            </div>
         </div>
         </main>
         <br><br>
         



<?php  include "../templates/footer.php"?>