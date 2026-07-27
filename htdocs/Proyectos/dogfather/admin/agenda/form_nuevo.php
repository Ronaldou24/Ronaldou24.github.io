<?php  include "../templates/header.php"?>
<?php
 
 include("../config/bd.php");
 $sql = $conexion->prepare("SELECT id, nombre FROM mascotas WHERE estatus = 0 ORDER BY nombre");
 $sql->execute();
 $mascotas = $sql->fetchAll(PDO::FETCH_ASSOC);

 $sql = null;
 $conexion = null;

if(isset($_POST['guardar'])){
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $idm = $_POST['idmascota'];
    $serv = $_POST['servicio'];
    $cto = $_POST['costo'];
    include("../config/bd.php");
    $sql = $conexion->prepare("INSERT INTO agenda(fecha,hora,id_mascota,servicio,costo,estatus) VALUES(:fecha,:hora,:idm,:serv,:cto,0)");
    $sql->bindParam(':fecha',$fecha);
    $sql->bindParam(':hora',$hora);
    $sql->bindParam(':idm',$idm);
    $sql->bindParam(':serv',$serv);
    $sql->bindParam(':cto',$cto);
    $sql->execute();
    $sql = null;
    $conexion = null;
    header("Location: index.php");
}

?>
    <main>
        <div class="card" style="width: 60%; margin: auto;">
            <div class="card-header"><h3>Actividad Nueva</h3></div>
            <div class="card-body">
                <form action="" method="post" class = "row">
                <div class="col-3">
                    <label for="fecha" class="form-label">Fecha:</label>
                    <input type="date" class="form-control" name="fecha"/>
                </div>
                <div class="col-3">
                    <label for="hora" class="form-label">Hora:</label>
                    <input type="time" class="form-control" name="hora"/>
                </div>
                <div class="col-6">

                </div>
                <div class="col-3">
                        <label for="idmascota" class="form-label">Mascota</label>
                        <select class="form-select form-select-lg" name="idmascota">    
                      <?php  foreach($mascotas as $mascota){ ?>
                        <option value="<?php echo $mascota['id']; ?>"> <?php echo $mascota['nombre'];?> </option>
                        <?php  } ?>
                        </select>
                </div>

                <div class="col-6">
                        <label for="servicio" class="form-label">Servicio</label>
                        <select class="form-select form-select-lg" name="servicio">
                            <option value="BAÑO">BAÑO</option>
                            <option value="CORTE">CORTE</option>
                            <option value="BAÑO Y CORTE">BAÑO Y CORTE</option>
                        </select>
                </div>
                
                <div class="col-3">
                    <label for="costo" class="form-label">Costo:</label>
                    <input type="text" class="form-control" name="costo"/>
                </div>

               
            </div>
            
        <div class="card-footer text-muted">
                
                <button type="submit" class="btn btn-primary" name="guardar">Guardar</button>
                <a class="btn btn-warning" href="index.php" role="button">Regresar</a>  
                </form>  
        
            </div>
        </div>
        
    </main>

<?php include "../templates/footer.php"?>