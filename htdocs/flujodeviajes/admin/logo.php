<?php
include('../admin/bd.php');
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT rango FROM usuarios WHERE usuario = ?");
$stmt->bind_param("s", $_SESSION['usuario']);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario_actual = $resultado->fetch_assoc();

if ($usuario_actual['rango'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$directorio_imagenes = '../uploads/usuarios/';
if (!file_exists($directorio_imagenes)) {
    mkdir($directorio_imagenes, 0777, true);
}

$mensaje = '';
$tipo_mensaje = '';

if (isset($_POST['subir_imagen'])) {
    $id_usuario = $_POST['id_usuario'];
    
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $archivo = $_FILES['imagen'];
        $nombre_archivo = $archivo['name'];
        $tipo_archivo = $archivo['type'];
        $tamano_archivo = $archivo['size'];
        $archivo_tmp = $archivo['tmp_name'];
        
        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($tipo_archivo, $tipos_permitidos)) {
            $mensaje = "Solo se permiten archivos de imagen (JPEG, PNG, GIF, WebP).";
            $tipo_mensaje = "danger";
        }
        elseif ($tamano_archivo > 5 * 1024 * 1024) {
            $mensaje = "El archivo es demasiado grande. Máximo 5MB.";
            $tipo_mensaje = "danger";
        }
        else {
            $stmt = $conn->prepare("SELECT usuario, img FROM usuarios WHERE id = ?");
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $usuario_info = $resultado->fetch_assoc();
            
            if ($usuario_info) {
                if (!empty($usuario_info['img']) && file_exists($directorio_imagenes . $usuario_info['img'])) {
                    unlink($directorio_imagenes . $usuario_info['img']);
                }
                
                $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
                $nombre_unico = $usuario_info['usuario'] . '_' . time() . '.' . $extension;
                $ruta_destino = $directorio_imagenes . $nombre_unico;
                
                if (move_uploaded_file($archivo_tmp, $ruta_destino)) {
                    $stmt = $conn->prepare("UPDATE usuarios SET img = ? WHERE id = ?");
                    $stmt->bind_param("si", $nombre_unico, $id_usuario);
                    
                    if ($stmt->execute()) {
                        $mensaje = "Imagen subida correctamente.";
                        $tipo_mensaje = "success";
                    } else {
                        $mensaje = "Error al actualizar la base de datos: " . $conn->error;
                        $tipo_mensaje = "danger";
                        unlink($ruta_destino);
                    }
                } else {
                    $mensaje = "Error al mover el archivo.";
                    $tipo_mensaje = "danger";
                }
            } else {
                $mensaje = "Usuario no encontrado.";
                $tipo_mensaje = "danger";
            }
        }
    } else {
        $mensaje = "Por favor selecciona una imagen.";
        $tipo_mensaje = "danger";
    }
}

if (isset($_POST['eliminar_imagen'])) {
    $id_usuario = $_POST['id_usuario'];
    
    $stmt = $conn->prepare("SELECT usuario, img FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario_info = $resultado->fetch_assoc();
    
    if ($usuario_info && !empty($usuario_info['img'])) {
        if (file_exists($directorio_imagenes . $usuario_info['img'])) {
            unlink($directorio_imagenes . $usuario_info['img']);
        }
        
        $stmt = $conn->prepare("UPDATE usuarios SET img = NULL WHERE id = ?");
        $stmt->bind_param("i", $id_usuario);
        
        if ($stmt->execute()) {
            $mensaje = "Imagen eliminada correctamente.";
            $tipo_mensaje = "success";
        } else {
            $mensaje = "Error al actualizar la base de datos: " . $conn->error;
            $tipo_mensaje = "danger";
        }
    } else {
        $mensaje = "No hay imagen para eliminar.";
        $tipo_mensaje = "danger";
    }
}

$stmt = $conn->prepare("SELECT id, usuario, rango, img FROM usuarios ORDER BY usuario");
$stmt->execute();
$resultado_usuarios = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Imágenes de Usuarios</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 1em 0;
            text-align: center;
            position: relative;
        }

        .home-button {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .home-button i {
            margin-right: 5px;
        }

        .home-button:hover {
            background-color: #45a049;
        }

        main {
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .card {
            width: 100%;
            max-width: 1000px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #f0f0f0;
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .card-header h2 {
            margin: 0;
            font-size: 1.5em;
            color: #333;
        }

        .card-content {
            padding: 20px;
        }

        .user-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .user-card {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }

        .user-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e9ecef;
            border: 3px solid #ddd;
            overflow: hidden;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-avatar .placeholder {
            font-size: 40px;
            color: #6c757d;
        }

        .user-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .user-role {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 15px;
            padding: 3px 8px;
            border-radius: 12px;
            display: inline-block;
        }

        .role-admin {
            background-color: #dc3545;
            color: white;
        }

        .role-transporte {
            background-color: #28a745;
            color: white;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            text-align: left;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0069d9;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-small {
            padding: 5px 10px;
            font-size: 12px;
        }

        .alert {
            display: flex;
            align-items: center;
            padding: 0.55rem 0.65rem 0.55rem 0.75rem;
            border-radius: 1rem;
            min-width: 300px;
            justify-content: space-between;
            margin-bottom: 1rem;
            box-shadow:
            0px 3.2px 13.8px rgba(0, 0, 0, 0.02),
            0px 7.6px 33.3px rgba(0, 0, 0, 0.028),
            0px 14.4px 62.6px rgba(0, 0, 0, 0.035),
            0px 25.7px 111.7px rgba(0, 0, 0, 0.042),
            0px 48px 208.9px rgba(0, 0, 0, 0.05),
            0px 115px 500px rgba(0, 0, 0, 0.07);
        }
        .alert .content {
            display: flex;
            align-items: center;
        }
        .alert .icon {
            padding: 0.5rem;
            margin-right: 1rem;
            border-radius: 39% 61% 42% 58% / 50% 51% 49% 50%;
            box-shadow:
            0px 3.2px 13.8px rgba(0, 0, 0, 0.02),
            0px 7.6px 33.3px rgba(0, 0, 0, 0.028),
            0px 14.4px 62.6px rgba(0, 0, 0, 0.035),
            0px 25.7px 111.7px rgba(0, 0, 0, 0.042),
            0px 48px 208.9px rgba(0, 0, 0, 0.05),
            0px 115px 500px rgba(0, 0, 0, 0.07);
        }
        .alert p {
            margin-bottom: 0;
        }

        .alert.success {
            background-color: rgba(62, 189, 97, 0.2);
            border: 2px solid #3ebd61;
            color: #333C48;
        }
        .alert.success .icon {
            background-color: #3ebd61;
        }
        .alert.success .icon svg path {
            fill: #fff;
        }

        .alert.danger {
            background-color: rgba(236, 77, 43, 0.2);
            border: 2px solid #EC4D2B;
            color: #333C48;
        }
        .alert.danger .icon {
            background-color: #EC4D2B;
        }
        .alert.danger .icon svg path {
            fill: #fff;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 5px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }

        .preview-image {
            max-width: 200px;
            max-height: 200px;
            margin: 10px 0;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .user-grid {
                grid-template-columns: 1fr;
            }
            
            .home-button {
                position: static;
                transform: none;
                margin: 10px auto;
                display: block;
                width: fit-content;
            }
        }

        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
    </style>
</head>
<body>
    <header>
        <a href="adminindex.php" class="home-button">
            <i class="fas fa-home"></i> Inicio
        </a>
        <h1>Gestión de Imágenes de Usuarios</h1>
    </header>

    <main>
        <?php if (!empty($mensaje)): ?>
            <div class="alert <?= $tipo_mensaje ?>">
                <div class="content">
                    <div class="icon">
                        <?php if ($tipo_mensaje === 'success'): ?>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 0C5.376 0 0 5.376 0 12C0 18.624 5.376 24 12 24C18.624 24 24 18.624 24 12C24 5.376 18.624 0 12 0ZM9.6 18L3.6 12L5.292 10.308L9.6 14.604L18.708 5.496L20.4 7.2L9.6 18Z" fill="#3EBD61"/>
                            </svg>
                        <?php else: ?>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 0C5.376 0 0 5.376 0 12C0 18.624 5.376 24 12 24C18.624 24 24 18.624 24 12C24 5.376 18.624 0 12 0ZM12 13.2C11.336 13.2 10.8 12.664 10.8 12V7.2C10.8 6.536 11.336 6 12 6C12.664 6 13.2 6.536 13.2 7.2V12C13.2 12.664 12.664 13.2 12 13.2ZM13.2 18H10.8V15.6H13.2V18Z" fill="#EC4D2B"/>
                            </svg>
                        <?php endif; ?>
                    </div>
                    <p><?= htmlspecialchars($mensaje) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h2>Usuarios del Sistema</h2>
            </div>
            <div class="card-content">
                <div class="user-grid">
                    <?php while ($usuario = $resultado_usuarios->fetch_assoc()): ?>
                        <div class="user-card">
                            <div class="user-avatar">
                                <?php if (!empty($usuario['img']) && file_exists($directorio_imagenes . $usuario['img'])): ?>
                                    <img src="<?= $directorio_imagenes . htmlspecialchars($usuario['img']) ?>" alt="<?= htmlspecialchars($usuario['usuario']) ?>">
                                <?php else: ?>
                                    <div class="placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="user-name"><?= htmlspecialchars($usuario['usuario']) ?></div>
                            <div class="user-role role-<?= $usuario['rango'] ?>">
                                <?= $usuario['rango'] === 'admin' ? 'Administrador' : 'Transporte' ?>
                            </div>
                            
                            <div style="margin-top: 15px;">
                                <button class="btn btn-primary btn-small" onclick="abrirModal(<?= $usuario['id'] ?>, '<?= htmlspecialchars($usuario['usuario']) ?>')">
                                    <i class="fas fa-upload"></i> Subir Imagen
                                </button>
                                
                                <?php if (!empty($usuario['img'])): ?>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar esta imagen?');">
                                        <input type="hidden" name="id_usuario" value="<?= $usuario['id'] ?>">
                                        <button type="submit" name="eliminar_imagen" class="btn btn-danger btn-small">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </main>

    <div id="modalSubirImagen" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <h3 id="modalTitulo">Subir Imagen</h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" id="modalIdUsuario" name="id_usuario">
                
                <div class="form-group">
                    <label for="imagen">Seleccionar Imagen:</label>
                    <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required onchange="previsualizarImagen(this)">
                </div>
                
                <div id="previewContainer" style="text-align: center; display: none;">
                    <img id="previewImage" class="preview-image" alt="Vista previa">
                </div>
                
                <div style="text-align: center; margin-top: 20px;">
                    <button type="submit" name="subir_imagen" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Subir Imagen
                    </button>
                    <button type="button" class="btn btn-danger" onclick="cerrarModal()">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <footer style="text-align: center; padding: 20px; background-color: #333; color: white; margin-top: 40px;">
        <p>&copy; 2025 Sistema de Gestión de Usuarios</p>
    </footer>

    <script>
        function abrirModal(idUsuario, nombreUsuario) {
            document.getElementById('modalIdUsuario').value = idUsuario;
            document.getElementById('modalTitulo').textContent = 'Subir Imagen para ' + nombreUsuario;
            document.getElementById('modalSubirImagen').style.display = 'block';
            document.getElementById('previewContainer').style.display = 'none';
            document.getElementById('imagen').value = '';
        }

        function cerrarModal() {
            document.getElementById('modalSubirImagen').style.display = 'none';
        }

        function previsualizarImagen(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                    document.getElementById('previewContainer').style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        window.onclick = function(event) {
            const modal = document.getElementById('modalSubirImagen');
            if (event.target == modal) {
                cerrarModal();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const alertElements = document.querySelectorAll('.alert');
            alertElements.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.animation = 'fadeOut 0.5s ease-out forwards';
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 500);
                }, 5000);
            });
        });
    </script>
</body>
</html>