<?php 
include("template/header.php");
?>
<?php
if (isset($_POST['enviar'])) {
    $nom = $_POST['nombre'];
    $correo = $_POST['correo'];
    $com = $_POST['comentario'];

    include("../config/bd.php");

    try {
        $sql = $conexion->prepare("INSERT INTO dogfather.buzon (fecha, nombre, correo, comentario) VALUES (now(), :nombre, :correo, :comentario);");
        $sql->bindParam(':nombre', $nom);
        $sql->bindParam(':correo', $correo);
        $sql->bindParam(':comentario', $com);
        $sql->execute();

        if ($sql) {
            $sql = null;
            $conexion = null;
            echo "Todo flow";
        }
    } catch (Exception $ex) {
        $sql = null;
        $conexion = null;
        echo "Error: " . $ex->getMessage();
    }
}
?>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<main>
    <div class="card">
        <div class="card-header">Header</div>
        <div class="card-body">
            <h4 class="card-title">Title</h4>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Name</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" aria-describedby="helpId" placeholder="Pon tu nombre" value="" />
                </div>
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo</label>
                    <input type="email" class="form-control" name="correo" id="correo" aria-describedby="helpId" placeholder="Pon tu correo" value="" />
                </div>
                <div class="mb-3">
                    <label for="comentario" class="form-label">Comentario</label>
                    <input type="text" class="form-control" name="comentario" id="comentario" aria-describedby="helpId" placeholder="Pon tu comentario" value="" />
                </div>
                <button type="submit" class="btn btn-primary" value="ok" name="enviar">Enviar</button>
            </form>
        </div>
        <div class="card-footer text-muted">Footer</div>
    </div>
</main>

<?php 
include("template/footer.php");
?>
