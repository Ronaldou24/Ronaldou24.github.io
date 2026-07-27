<?php include("../template/cabecera.php");?>
<?php 

print_r($_POST);
print_r($FILES);


echo $txtID."<br/>";
echo $txtNombre."<br/>";
echo $txtImagen."<br/>";
echo $accion."<br/>";
?>

<div class="col-md-5">

<div class="card">
    <div class="card-header">
        Datos Productos
    </div>

    <div class="card-body">
       
    Formulario de agregar productos
    <form method="POST" enctype="multipart/form-data">
    <div class = "form-group">
    <label for="txtID">ID:</label>
    <input type="text" class="form-control" name="txtID" id="txtID"  placeholder=" ID">
    </div>

    <div class = "form-group">
    <label for="txtNombre">Nombre:</label>
    <input type="text" class="form-control" name="txtNombre" id="txtNombre"  placeholder="Nombre del Producto">
    </div>

    <div class = "form-group">
    <label for="txtImagen">Imagen:</label>
    <input type="file" class="form-control" name="txtImagen" id="txtImagen"  placeholder="Imagen del Producto">
    </div>
   

   <div class="btn-group" role="group" aria-label="">
    <button type="button" name="accion" value="Agregar" class="btn btn-success">Agregar</button>
    <button type="button" name="accion" value="Modificar" class="btn btn-warning">Modificar</button>
    <button type="button" name="accion" value="Cancelar" class="btn btn-info">Cancelar</button>
   </div>
    
</div>

</div>
    
</div>


<div class="col-md-7">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>24</td>
                <td>Sauli</td>
                <td>cafe.png</td>
                <td>Seleccionar | Borrar</td>
            </tr>
        </tbody>
    </table>

    </div>

<?php include("../template/pie.php");?>

