<?php  include "../templates/header.php";
  $comentario = [];
?>

<?php



if(isset($_GET['detalle']) && isset($_GET['fecha'])){
    $fecha = $_GET['fecha'];
        include("../config/bd.php");
        $sql = $conexion->prepare("SELECT * FROM buzon WHERE fecha = :fecha");
        $sql->bindParam(':fecha', $fecha);
        $sql->execute();
        $comentario = $sql->fetch(PDO::FETCH_ASSOC); #LAZY SACA SOLO UN DATO DE LA TABLA 
        $sql      = null; #Cerramos la base de datos con null
        $conexion= null; #Cerramos la conexion

    }

     if(isset($_POST['accion'])){
        $accion = $_POST['accion'];
        

        switch($accion){


    case "eliminar":
        $fecha =  $_POST['fecha'];
        include("../config/bd.php");
        $sql = $conexion->prepare("DELETE * FROM buzon  WHERE fecha = :fecha");
        $sql->bindParam(':fecha',$fecha);
        $sql->execute();
        $sql = null;
        $conexion = null;
        header("Location: index.php");
        break;
        
    case "editar":
        $fecha = $_POST['fecha'];
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $comentarios = $_POST['comentarios'];

        
       include("../config/bd.php");
        $sql = $conexion->prepare("UPDATE buzon SET leido = 1 WHERE fecha = :fecha");
        $sql ->bindParam(':fecha',$fecha);
        $sql ->bindParam(':nombre',$nombre);
        $sql ->bindParam(':correo',$correo);
        $sql ->bindParam(':comentarios',$comentarios);
        $sql->execute();
        $sql = null;
        $conexion = null;
        header("Location: index.php");
        break;
        }
        
        }
?>
<main>

         <div class="card" style="width: 60%; margin: auto;">
            <div class="card-header"><h3>Registro de buzon</h3></div>
            <div class="card-body">
                <form action="" method="POST" class="row">
            
                            <div class="col-6">
                                <label for="fecha" class="form-label">Fecha: </label>
                                <input type="date" class="form-control" name="fecha"
                                value = "<?php echo $comentario[0];?>" />
                                
                            </div>
                    
                            <div class="col-6">
                                <label for="nombre" class="form-label">Nombre: </label>
                                <input type="text" class="form-control" name="nombre" 
                                value = "<?php echo $comentario[1];?>"/>
                                
                            </div>

                            <div class="col-6">
                                <label for="correo" class="form-label">Correo: </label>
                                <input type="email" class="form-control" name="correo" 
                                value = "<?php echo $comentario[2];?>"/>
                                
                            </div>
            
                           

                            <div class="col-8">
                                <label for="comentarios" class="form-label">Comentarios:</label>
                                <input type="text" class="form-control" name="comentarios" 
                                value = "<?php echo $comentario[3];?>"/>
                                
                            </div>

                            
              
            </div>
            <div class="card-footer text-muted">
                <div class="btn-group" role="group"> 
                <button type="submit" class="btn btn-primary" name="accion" value="editar" onclick="return confirma_editar()">Editar</button>
                <button type="submit" class="btn btn-danger" name="accion" value="eliminar" onclick="return confirma_eliminar()">Eliminar</button>
                </div>
                <a class="btn btn-warning" href="index.php" role="button" >Regresar</a>
                
                
               </form>

            </div>
         </div>
         </main>
         <br><br>
         



<?php  include "../templates/footer.php"?>