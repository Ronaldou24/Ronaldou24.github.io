<?php include "templates/header.php" ?>

<?php 
    if(isset($_POST['enviar'])){
        $nom    = $_POST['nombre'];
        $correo = $_POST['correo'];
        $msg    = $_POST['comentario'];
        include("admin/config/bd.php");
        
        try{
            $sql = $conexion->prepare("INSERT INTO buzon VALUES(now(),:nom,:correo,:mensaje)");
    $sql->bindParam(':nom',$nom);
    $sql->bindParam(':correo',$correo);
    $sql->bindParam(':mensaje',$msg);
    $sql->execute();      
           if($sql){
            $sql      = null; #Cerramos la base de datos con null
            $conexion = null;
            echo "<script>alert('Mensaje enviado....')</script>";
           }
        }catch(Exception $ex){
            $sql      = null; #Cerramos la base de datos con null
            $conexion = null;
            echo $ex->getMessage();
        }
    }

   

?>

<img src="images/banner_1.jpg" alt="" class="banner">    
        </header>
        <div class="container">
<br>
<main>
   <div class="card" style="width: 50%; margin: auto;">
        <div class="card-header"><h2>Ingresa tu comentario</h2></div>
        <div class="card-body">
              <form action="" method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" class="form-control" name="nombre"  placeholder="Ingresa tu nombre"/>
                    </div>
                    <div class="mb-3">
                    <label for="correo" class="form-label">E-mail:</label>
                    <input type="text" class="form-control" name="correo"  placeholder="Ingresa tu correo"/>
                    </div>
                    <div class="mb-3">
                    <label for="comentario" class="form-label">Comentario:</label>
                    <textarea name="comentario" class="form-control" rows="3"></textarea>
                    </div>     
        </div>
        <div class="card-footer text-muted">
        <button type="submit" class="btn btn-primary" value="ok" name="enviar">Enviar</button>
        </form> 
        </div>
    </div>
       
</main>
<?php include "templates/footer.php" ?>
        
       