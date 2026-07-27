<?php include "templates/header.php"?>
<img src="imagenes/banner_1.jpg" alt="" class="banner">
        </header>
    <div class="container">
    <br>
        <main>
            <div class="card" style="width: 50%;">
                <div class="card-header"><h2>Ingresa tu comentario</h2></div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" name="nombre" placeholder="Ingresa tu nombre"/>
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">E-mail:</label>
                            <input type="text" class="form-control" name="correo" placeholder="Ingresa tu correo"/>
                        </div>
                        <div class="mb-3">
                            <label for="comentario" class="form-label">Comentario</label>
                            <textarea class="form-control" name="comentario" rows="3"></textarea>
                        </div>
                </div>
                <div class="card-footer text-muted">
                <button type="button" class="btn btn-primary" value="ok">Enviar</button>
                    </form>
                </div>
            </div>
        </main>
    <br>
<?php include "templates/footer.php"?>