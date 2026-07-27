<?php  include "../templates/header.php"?>
<?php
    if(isset($_GET['detalle'])){
        $idm = $_GET['idmascota'];
        
        include("../config/bd.php");
        $sql = $conexion->prepare("SELECT * FROM mascotas WHERE id = :idm");
        $sql->bindParam('idm', $idm);
        $sql->execute();
        $mascotaEnc = $sql->fetch(PDO::FETCH_LAZY); #LAZY SACA SOLO UN DATO DE LA TABLA 
        
    
        $sql      = null; #Cerramos la base de datos con null
        $conexion = null; #Cerramos la conexion
    }

     if(isset($_POST['accion'])){
        $accion = $_POST['accion'];
        

        switch($accion){
    case "eliminar":
        $idmascota =  $_POST['idmascota'];
        include("../config/bd.php");
        $sql = $conexion->prepare("UPDATE mascotas SET estatus = 1 WHERE id = :idm");
        $sql->bindParam(':idm',$idmascota);
        $sql->execute();
        $sql = null;
        $conexion = null;
        header("Location: index.php");
        break;
        
    case "editar":
        $idm = $_POST['idmascota'];
        $nom = $_POST['nombre'];
        $cte = $_POST['cliente'];
        $fna = $_POST['fecha_nac'];
        $raza = $_POST['raza'];
        $size = $_POST['size'];
        $cel = $_POST['celular'];
       include("../config/bd.php");
        $sql = $conexion->prepare("UPDATE mascotas SET nombre= :nom, cliente = :cte, fecha_nac = :fna, raza = :raza, size = :size, celular = :cel WHERE id = :idm");
        $sql ->bindParam(':idm',$idm);
        $sql ->bindParam(':nom',$nom);
        $sql ->bindParam(':cte',$cte);
        $sql ->bindParam(':fna',$fna);
        $sql ->bindParam(':raza',$raza);
        $sql ->bindParam(':size',$size);
        $sql ->bindParam(':cel',$cel);
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
            <div class="card-header"><h3>Registro de mascotas</h3></div>
            <div class="card-body">
                <form action="" method="POST" class="row">
                    <input type="text" name="idmascota" value = "<?php echo $mascotaEnc[0];?>"hidden>
                            <div class="col-6">
                                <label for="nombre" class="form-label">Nombre: </label>
                                <input type="text" class="form-control" name="nombre"
                                value = "<?php echo $mascotaEnc[1];?>" />
                                
                            </div>
                    
                            <div class="col-6">
                                <label for="fecha_nac" class="form-label">Fecha de nacimiento: </label>
                                <input type="date" class="form-control" name="fecha_nac" 
                                value = "<?php echo $mascotaEnc[3];?>"/>
                                
                            </div>
                            <div class="col-6">
                                <label for="raza" class="form-label">Raza</label>
                                <select class="form-select form-select-lg" name="raza">
                                    <option selected value = "<?php echo $mascotaEnc[4];?>"><?php echo $mascotaEnc[4];?></option>
                                    <option value="Chihuahua">Chihuahua</option>
                                    <option value="Snauzer">Schnauzer</option>
                                    <option value="Labrador">Labrador</option>
                                    <option value="PitBull">PitBull</option>
                                    <option value="Pastor Aleman">Pastor Aleman</option>
                                </select>
                            </div>

                            <div class="col-6">
                                <label for="size" class="form-label">Tamaño</label>
                                <select class="form-select form-select-lg" name="size">
                                <option selected value ="<?php echo $mascotaEnc[5];?>"><?php echo $mascotaEnc[5];?></option>    
                                <option value="S">Pequeño</option>
                                    <option value="M">Mediano</option>
                                    <option value="G">Grande</option>
                                </select>
                            </div>
                            
                            <div class="col-8">
                                <label for="cliente" class="form-label">Cliente: </label>
                                <input type="text" class="form-control" name="cliente" 
                                value = "<?php echo $mascotaEnc[2];?>"/>
                                
                            </div>

                            <div class="col-4">
                                <label for="celular" class="form-label">Celular del propietario: </label>
                                <input type="text" class="form-control" name="celular" 
                                value = "<?php echo $mascotaEnc[6];?>"/>
                                
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