<?php
include('../admin/bd_login.php'); 
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Documentos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Css/agregarRuta.css">
   
</head>
<body>
<?php require_once '../admin/bd.php'; ?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Poppins:wght@400;600;700&display=swap');

:root {
    --primary-blue: #007bff; 
    --light-blue: #e7f3ff; 
    --dark-blue: #0056b3; 
    --text-color: #333; 
    --light-gray: #f8f9fa; 
    --white: #ffffff; 
    --shadow-light: rgba(0, 0, 0, 0.08); 
    --border-color: #dee2e6; 
}

body {
    font-family: 'Poppins', sans-serif;
    background-color: var(--light-gray); 
    color: var(--text-color);
    line-height: 1.6;
    display: flex; 
    justify-content: center; 
    align-items: center; 
    min-height: 100vh;
    padding: 20px; 
}

.container {
    max-width: 900px; 
}


.form-container {
    background-color: var(--white);
    padding: 40px; 
    border-radius: 12px;
    box-shadow: 0 8px 20px var(--shadow-light); 
    border: 1px solid rgba(0, 0, 0, 0.05); 
}


.text-azul-profundo {
    color: var(--dark-blue); 
    font-weight: 700;
    margin-bottom: 30px !important; 
    font-size: 2.2rem; 
}

.text-azul-profundo .fas {
    color: var(--primary-blue); 
}

.form-label {
    font-weight: 600; 
    color: var(--text-color);
    display: flex; 
    align-items: center;
    margin-bottom: 8px; 
}

.form-label .form-icon {
    color: var(--primary-blue); 
    margin-right: 8px; 
    font-size: 1.1rem;
}

.form-control,
.form-select {
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 12px 15px; 
    font-size: 1rem;
    transition: all 0.3s ease; /
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25); 
    outline: none; 
}

.botones-container {
    display: flex;
    justify-content: center; 
    gap: 20px; 
    margin-top: 40px; 
}

.btn-marino {
    background-color: var(--primary-blue);
    color: var(--white);
    border: none;
    padding: 12px 28px; 
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-marino:hover {
    background-color: var(--dark-blue); 
    box-shadow: 0 6px 15px rgba(0, 123, 255, 0.4); 
    transform: translateY(-2px); 
}

.btn-marino .fas {
    margin-right: 8px; 
}

.btn-volver {
    background-color: var(--light-gray); 
    color: var(--dark-blue); 
    border: 2px solid var(--primary-blue);
    padding: 12px 28px;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    text-decoration: none; 
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-volver:hover {
    background-color: var(--primary-blue); 
    color: var(--white); 
    transform: translateY(-2px); 
    box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2); 
}

.btn-volver .fas {
    margin-right: 8px; 
    color: var(--primary-blue); 
}

.btn-volver:hover .fas {
    color: var(--white); 
}

@media (max-width: 767px) {
    .form-container {
        padding: 25px; 
    }
    .text-azul-profundo {
        font-size: 1.8rem; 
    }
    .botones-container {
        flex-direction: column; 
        gap: 15px; 
    }
    .btn-marino,
    .btn-volver {
        width: 100%; 
        padding: 12px 20px; 
    }
}
</style>
<div class="container my-5">
    <div class="form-container">
        <h3 class="mb-4 text-center text-azul-profundo">
            <i class="fas fa-route me-2"></i>Nueva Ruta
        </h3>

        <form action="procesar.php" method="POST">
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fas fa-map-marker-alt form-icon"></i>Origen
                    </label>
                    <input type="text" class="form-control" name="origen" 
                           oninput="this.value = this.value.toUpperCase()" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fas fa-map-marker-alt form-icon"></i>Destino
                    </label>
                    <input type="text" class="form-control" name="destino" 
                           oninput="this.value = this.value.toUpperCase()" required>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-calendar-day form-icon"></i>Fecha Origen
                    </label>
                    <input type="date" class="form-control" name="fecha_origen" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-clock form-icon"></i>Hora Salida
                    </label>
                    <input type="time" class="form-control" name="hora_salida" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-calendar-day form-icon"></i>Fecha Destino
                    </label>
                    <input type="date" class="form-control" name="fecha_destino" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-clock form-icon"></i>Hora Llegada
                    </label>
                    <input type="time" class="form-control" name="hora_llegada" required>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label"> 
                        <i class="fas fa-ship form-icon"></i>Número del Barco
                    </label>
                    <input type="text" class="form-control" name="numero_barco" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fas fa-anchor form-icon"></i>Tipo de Embarcación
                    </label>
                    <select class="form-select" name="tipo_embarcacion" required>
                        <option value="">Seleccionar...</option>
                        <option value="carga">Carga</option>
                        <option value="pasajeros">Pasajeros</option>
                        <option value="mixta">Mixta</option>
                    </select>
                </div>
            </div>

            <div class="botones-container">
                <button type="submit" class="btn-marino">
                    <i class="fas fa-save me-2"></i>Guardar Ruta
                </button>
                
                <a href="../index.php" class="btn-volver">
                    <i class="fas fa-home me-2"></i>Volver al Inicio
                </a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>