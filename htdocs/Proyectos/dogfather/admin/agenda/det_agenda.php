<?php  include "../templates/header.php"?>
<?php
include("../config/bd.php");
$sql = $conexion->prepare("SELECT id, nombre FROM mascotas WHERE estatus = 0 ORDER BY id");
$sql->execute();
$mascotas = $sql->fetchAll(PDO::FETCH_ASSOC);

$sql = null;
$conexion = null;

    if(isset($_GET['detalle'])){
        $folio = $_GET['folio'];
        $idm = $_GET['idm'];
        include("../config/bd.php");
        $sql = $conexion->prepare("SELECT * FROM agenda WHERE folio = :folio");
        $sql->bindParam(':folio', $folio);
        $sql->execute();
        $actividadEnc = $sql->fetch(PDO::FETCH_LAZY); #LAZY SACA SOLO UN DATO DE LA TABLA 
        $sql      = null; #Cerramos la base de datos con null
        $conexion = null; #Cerramos la conexion
        include("../config/bd.php");
        $sql = $conexion->prepare("SELECT id, nombre FROM mascotas WHERE id = :idm");
        $sql->bindParam(':idm', $idm);
        $sql->execute();
        $mascotaEnc = $sql->fetch(PDO::FETCH_LAZY);
        $sql      = null; #Cerramos la base de datos con null
        $conexion = null; #Cerramos la conexion
    }

     if(isset($_POST['accion'])){
        $accion = $_POST['accion'];
        

        switch($accion){
    case "eliminar":
        $folio =  $_POST['folio'];
        include("../config/bd.php");
        $sql = $conexion->prepare("UPDATE agenda SET estatus = 1 WHERE folio = :folio");
        $sql->bindParam(':folio',$folio);
        $sql->execute();
        $sql = null;
        $conexion = null;
        header("Location: index.php");
        break;
        
    case "editar":
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];
        $folio = $_POST['folio'];
        $servicio = $_POST['servicio'];
        $costo = $_POST['costo'];
        $idm = $_POST['idmascota'];
       include("../config/bd.php");
        $sql = $conexion->prepare("UPDATE agenda SET fecha = :fecha, hora = :hora, servicio = :servicio, costo = :costo, id_mascota = :idm WHERE folio = :folio");
        $sql ->bindParam(':fecha',$fecha);
        $sql ->bindParam(':hora',$hora);
        $sql ->bindParam(':servicio',$servicio);
        $sql ->bindParam(':costo',$costo);
        $sql ->bindParam(':folio',$folio);
        $sql ->bindParam(':idm',$idm);
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
            <div class="card-header"><h3>Registro de Actividades</h3></div>
            <div class="card-body">
                <form action="" method="POST" class="row">
                    <input type="text" name="folio" value = "<?php echo $actividadEnc[0];?>"hidden>
                            <div class="col-6">
                                <label for="fecha" class="form-label">Fecha: </label>
                                <input type="date" class="form-control" name="fecha"
                                value = "<?php echo $actividadEnc[1];?>" />
                                
                            </div>
                    
                            <div class="col-6">
                                <label for="hora" class="form-label">Hora: </label>
                                <input type="time" class="form-control" name="hora" 
                                value = "<?php echo $actividadEnc[2];?>"/>
                                
                            </div>
            
                            <div class="col-6">
                                <label for="idmascota" class="form-label">Mascota</label>
                                <select class="form-select form-select-lg" name="idmascota">   
                                <option selected value = "<?php echo $mascotaEnc[0];?>"><?php echo $mascotaEnc[1];?></option> 
                                    <?php foreach($mascotas as $mascota){ ?>
                                        <?php if($mascotaEnc[0] != $mascota['id']) { ?>
                                            <option value="<?php echo $mascota['id']; ?>"> <?php echo $mascota['nombre'];?> </option>
                                        <?php } else { ?>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>

               <div class="col-6">
                        <label for="servicio" class="form-label">Servicio</label>
                        <select class="form-select form-select-lg" name="servicio">
                        <option selected value ="<?php echo $actividadEnc[4];?>"><?php echo $actividadEnc[4];?></option>  
                            <option value="BAÑO">BAÑO</option>
                            <option value="CORTE">CORTE</option>
                            <option value="BAÑO Y CORTE">BAÑO Y CORTE</option>
                        </select>
                </div>

                            <div class="col-8">
                                <label for="costo" class="form-label">Costo:</label>
                                <input type="text" class="form-control" name="costo" 
                                value = "<?php echo $actividadEnc[5];?>"/>
                                
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