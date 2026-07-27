<?php  include"../config/bd.php"; 
$sql = $conexion->prepare("SELECT * FROM alumnos");

$sql->execute();




?>



<?php  include"header.php" ?>
<div class="container">
    <main>
     <div class="card">
        <div class="card-header">Alumnos</div>
        <div class="card-body">
         
        
        <div class="table-responsive">
            <table class="table table-primary">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Grupo</th>
                        <th scope="col">Sem</th>
                        <th scope="col">Turno</th>

                    </tr>
                </thead>

                <tbody>
                    <?php   foreach($alumnos as $alumno){?>
                    <tr class="">
                        <td><?php echo  $alumno{"Id"}?></td>
                        <td><?php echo  $alumno{"Nombre"} ?></td>
                        <td><?php echo  $alumno{"Grupo"}?></td>
                        <td><?php echo  $alumno{"Semestre"}?></td>
                        <td><?php echo  $alumno{"Grupo"}?></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
        


        </div>
        <div class="card-footer text-muted">Fin de la lista</div>
     </div>
     

        </h1>
    </main>
</div>



<?php  include"footer.php" ?>