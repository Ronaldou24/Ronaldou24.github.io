<?php  include "../templates/header.php"?>

<?php 
    include("../config/bd.php");
    $sql = $conexion->prepare("SELECT agenda.*, mascotas.nombre FROM agenda, mascotas WHERE agenda.id_mascota = mascotas.id && mascotas.estatus = 0 && agenda.estatus = 0");
    $sql->execute();
    $Actividades = $sql->fetchAll(PDO::FETCH_ASSOC);
    

    $sql      = null; #Cerramos la base de datos con null
    $conexion = null;

?>
    <main>
        
    <div class="card">
            <div class="card-header"><h3>Actividades Registradas:</h3></div>
            <a class="btn btn-primary" href="form_nuevo.php" role="button">Nuevo</a>
            <link rel="stylesheet" href="../css/tablas.css">
            
            <div class="card-body">
            <div class="table-responsive">
               
             <table class="table table-light">
                    <thead>
                        <tr>
                            <th scope="col">Folio</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Hora</th>
                            <th scope="col">Id_Macota</th>
                            <th scope="col">Mascota</th>
                            <th scope="col">Servicio</th>
                            <th scope="col">Costo</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                  <tbody>
                    <?php foreach($Actividades as $actividad){  ?>
                    <tr class="">
                            <td><?php echo $actividad['folio']; ?></td>
                            <td><?php echo $actividad['fecha']; ?></td>
                            <td><?php echo $actividad['hora']; ?></td>
                            <td><?php echo $actividad['id_mascota']; ?></td>
                            <td><?php echo $actividad['nombre']; ?></td>
                            <td><?php echo $actividad['servicio']; ?></td>
                            <td><?php echo $actividad['costo']; ?></td>
                            <td>
                                <form action="det_agenda.php" method="GET">
                                <input type="text" name="idm" value="<?php echo $actividad['id_mascota']; ?>"hidden>
                                    <input type="text" name="folio" value="<?php echo $actividad['folio']; ?>"hidden>
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

<?php include "../templates/footer.php"?>