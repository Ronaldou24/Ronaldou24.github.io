<?php 
if(isset($_POST["guardar"])){
    $folio = $_POST["folio"];
    $fecha = $_POST["fecha"];
    $tipo_m = $_POST["tipo_m"];
    $concepto = $_POST["concepto"];
    $importe = $_POST["importe"];
    $forma_pago = $_POST["forma_pago"];
}

try {
    include ("config/bd.php");

    $sql = $conexion->prepare("INSERT INTO movimientos.ing_egr (folio, fecha, tipo_m, concepto, importe, forma_pago) VALUES(:folio, :fecha, :tipo_m, :concepto, :importe, :forma_pago)");

    // Vincular
    $sql->bindParam(":folio", $folio);
    $sql->bindParam(":fecha", $fecha);
    $sql->bindParam(":tipo_m", $tipo_m);
    $sql->bindParam(":concepto", $concepto);
    $sql->bindParam(":importe", $importe);
    $sql->bindParam(":forma_pago", $forma_pago);

    $sql->execute();

    if($sql) {
        // Cerrar la conexión y redirigir
        $sql = null;
        $conexion = null;
        header("Location: index.php");
    } else {
        echo "<script>alert('Error al registrar Movimiento')</script>";
    }

} catch(Exception $ex) {
    echo $ex->getMessage();
}
?>

<style>

body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    color: #333;
    margin: 0;
    padding: 0;
}

/* Card styling */
.card {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
    padding: 20px;
    max-width: 800px;
}

/* Card header styling */
.card-header {
    background-color: #4caf50; /* Verde suave */
    color: white;
    padding: 15px;
    border-radius: 8px 8px 0 0;
    font-size: 1.5em;
    font-weight: bold;
    text-align: center;
}


.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1em;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.btn-primary {
    background-color: #4caf50; /* Verde */
    color: white;
}

.btn-primary:hover {
    background-color: #388e3c; /* Verde más oscuro */
    transform: scale(1.05);
}

.btn-danger {
    background-color: #f44336; /* Rojo */
    color: white;
}

.btn-danger:hover {
    background-color: #d32f2f; /* Rojo más oscuro */
    transform: scale(1.05);
}


.form-label {
    font-weight: bold;
    margin-bottom: 5px;
    display: inline-block;
}

.form-control {
    width: 100%;
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #ccc;
    margin-bottom: 15px;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: #4caf50; /* Verde al enfocar */
    outline: none;
    box-shadow: 0 0 5px rgba(76, 175, 80, 0.2);
}


.card-footer {
    background-color: #f1f1f1;
    padding: 15px;
    text-align: center;
    border-radius: 0 0 8px 8px;
    font-size: 1em;
}

</style>

<!-- El formulario ahora está bien cerrado -->
<div class="card" style="width: 60%; margin:auto">
    <div class="card-header"><h3>Registro de Movimientos</h3></div>
    <div class="card-body">
        <form action="" method="POST" class="row">
            
            <div class="col-md-6">
                <label for="folio" class="form-label">Folio</label>
                <input type="text" name="folio" class="form-control">
            </div>

            <div class="col-md-6">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" name="fecha" class="form-control">
            </div>

            <div class="col-md-6">
                <label for="tipo_m" class="form-label">Tipo de Movimiento</label>
                <input type="text" name="tipo_m" class="form-control">
            </div>

            <div class="col-md-6">
                <label for="concepto" class="form-label">Concepto</label>
                <input type="text" name="concepto" class="form-control">
            </div>

            <div class="col-md-6">
                <label for="importe" class="form-label">Importe</label>
                <input type="text" name="importe" class="form-control">
            </div>

            <div class="col-md-6">
                <label for="forma_pago" class="form-label">Forma de Pago</label>
                <input type="text" name="forma_pago" class="form-control">
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary" name="guardar" value="guardar">
                    Guardar
                </button>
                <a class="btn btn-danger" href="index.php" role="button">
                    Regresar
                </a>
            </div>
        </form>
        
    </div>
</div>
