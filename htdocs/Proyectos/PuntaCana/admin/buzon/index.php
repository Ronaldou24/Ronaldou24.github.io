<?php  include "../templates/header.php"?>
<?php 
    include("../config/bd.php");
    $sql = $conexion->prepare("SELECT * FROM buzon");
    $sql->execute();
    $listaComentarios = $sql->fetchAll(PDO::FETCH_ASSOC);
    

    $sql      = null; #Cerramos la base de datos con null
    $conexion = null;

?>
    <main>
        
    <div class="card">
            <div class="card-header">Buzón del sitio</div>
            <div class="card-body">
            <div class="table-responsive">
            <link rel="stylesheet" href="../css/tablas.css">
               
             <table class="table table-light">
                    <thead>
                        <tr>
                            <th scope="col">Fecha</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Comentario</th>
                        </tr>
                    </thead>
                  <tbody>
                    <?php foreach($listaComentarios as $buzon){  ?>
                    <tr class="">
                            <td><?php echo $buzon['fecha']; ?></td>
                            <td><?php echo $buzon['nombre']; ?></td>
                            <td><?php echo $buzon['correo']; ?></td>
                            <td><?php echo $buzon['comentario']; ?></td>
                            <td>
                                <form action="det_buzon.php" method="GET">
                                    <button type="submit" class="btn btn-primary" name="detalle">Detalles</button>
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