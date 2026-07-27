<?php  include "../templates/header.php";?>
<?php 
    include("../config/bd.php");
    $sql = $conexion->prepare("SELECT * FROM usuarios WHERE estatus = 0");
    $sql->execute();
    $listaUsuarios = $sql->fetchAll(PDO::FETCH_ASSOC);
    

    $sql      = null; #Cerramos la base de datos con null
    $conexion = null;

?>
    <main>
        
    <div class="card">
            <div class="card-header"><h3>Usuarios:</h3></div>
            <a class="btn btn-primary" href="form_nuevo.php" role="button" sty>Registrar usuario</a>
            
            <div class="card-body">
            <div class="table-responsive">
               
             <table class="table table-light">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Contraseña</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                  <tbody>
                    <?php foreach($listaUsuarios as $usuario){  ?>
                    <tr class="">
                            <td><?php echo $usuario['id']; ?></td>
                            <td><?php echo $usuario['nombre']; ?></td>
                            <td><?php echo $usuario['password']; ?></td>
                            <td>
                                <form action="det_usuario.php" method="GET">
                                    <input type="text" name="idusuario" value="<?php echo $usuario['id']; ?>"hidden>
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