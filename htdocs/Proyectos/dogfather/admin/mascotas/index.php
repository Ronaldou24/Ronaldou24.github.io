<?php  include "../templates/header.php";?>
<?php 
    include("../config/bd.php");
    $sql = $conexion->prepare("SELECT * FROM mascotas WHERE estatus = 0");
    $sql->execute();
    $listaMascotas = $sql->fetchAll(PDO::FETCH_ASSOC);
    

    $sql      = null; #Cerramos la base de datos con null
    $conexion = null;

?>
    <main>
        
    <div class="card">
            <div class="card-header"><h3>Mascotas registradas:</h3></div>
            <a class="btn btn-primary" href="form_nuevo.php" role="button">Nuevo</a>
            
            <div class="card-body">
            <div class="table-responsive">
               
             <table class="table table-light">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Fecha de Nac</th>
                            <th scope="col">Raza</th>
                            <th scope="col">Tamaño</th>
                            <th scope="col">Celular</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                  <tbody>
                    <?php foreach($listaMascotas as $mascota){  ?>
                    <tr class="">
                            <td><?php echo $mascota['id']; ?></td>
                            <td><?php echo $mascota['nombre']; ?></td>
                            <td><?php echo $mascota['cliente']; ?></td>
                            <td><?php echo $mascota['fecha_nac']; ?></td>
                            <td><?php echo $mascota['raza']; ?></td>
                            <td><?php echo $mascota['size']; ?></td>
                            <td><?php echo $mascota['celular']; ?></td>
                            <td>
                                <form action="det_mascota.php" method="GET">
                                    <input type="text" name="idmascota" value="<?php echo $mascota['id']; ?>"hidden>
                                    <button type="submit" class="btn btn-primary" name="detalle">Detalles</button>
                                    
                                </form>
                            </td>
                        </tr>
                    </tbody>
                    <?php } ?>
                </table>
            
            </div>
               
            </div>
            <div class="card-footer text-muted">
            
            </div>
        </div>
</main>
<?php include "../templates/footer.php";?>