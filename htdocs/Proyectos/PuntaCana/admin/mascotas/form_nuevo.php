<?php  include "../templates/header.php"?>
<?php
    if(isset($_POST['guardar'])){
        $nombre     = (isset($_POST['nombre']))?$_POST['nombre']:"";
        $cliente    = (isset($_POST['cliente']))?$_POST['cliente']:"";
        $fechanac   = (isset($_POST['fecha_nac']))?$_POST['fecha_nac']:"";
        $raza       = (isset($_POST['raza']))?$_POST['raza']:"";
        $size       = (isset($_POST['size']))?$_POST['size']:"";
        $celular    = (isset($_POST['celular']))?$_POST['celular']:"";

        try{
            include("../config/bd.php");
            $sql = $conexion->prepare("INSERT INTO mascotas(nombre,cliente,fecha_nac,raza,size,celular,estatus) VALUES(:nombre,:cliente,:fechanac,:raza,:size,:celular,0)");
             $sql->bindParam(':nombre',$nombre);
             $sql->bindParam(':cliente',$cliente);
             $sql->bindParam(':fechanac',$fechanac);
             $sql->bindParam(':raza',$raza);
             $sql->bindParam(':size',$size);
             $sql->bindParam(':celular',$celular);
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
            <div class="card-header"><h3>Registro de mascotas</h3></div>
            <div class="card-body">
                <form action="" method="POST" class= "row">
                            <div class="col-6">
                                <label for="nombre" class="form-label">Nombre: </label>
                                <input type="text" class="form-control" name="nombre" />
                                
                            </div>

                            <div class="col-6">
                                <label for="fecha_nac" class="form-label">Fecha de nacimiento: </label>
                                <input type="date" class="form-control" name="fecha_nac" />
                                
                            </div>
          
                            <div class="col-6">
                                <label for="raza" class="form-label">Raza</label>
                                <select class="form-select form-select-lg" name="raza">
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
                                    <option value="S">Pequeño</option>
                                    <option value="M">Mediano</option>
                                    <option value="G">Grande</option>
                                </select>
                            </div>
                            
                            <div class="col-8">
                                <label for="cliente" class="form-label">Propiedad de: </label>
                                <input type="text" class="form-control" name="cliente" />
                                
                            </div>

                            <div class="col-4">
                                <label for="celular" class="form-label">Celular del propietario: </label>
                                <input type="text" class="form-control" name="celular" />
                                
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