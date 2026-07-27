<?php
session_start();

// Verificar qué archivo de conexión existe y usar el correcto
if (file_exists("conexionPDO.php")) {
    include("conexionPDO.php");
} elseif (file_exists("admin/bd.php")) {
    include("admin/bd.php");
} elseif (file_exists("../admin/bd.php")) {
    include("../admin/bd.php");
} else {
    die("Error: No se puede encontrar el archivo de conexión a la base de datos.");
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario_actual = $_SESSION['usuario'];
$mensaje = "";
$error = "";

// Procesar la subida de imagen
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['imagen_perfil'])) {
    $archivo = $_FILES['imagen_perfil'];
    
    // Verificar si se subió un archivo
    if ($archivo['error'] == UPLOAD_ERR_OK) {
        $nombreArchivo = $archivo['name'];
        $tipoArchivo = $archivo['type'];
        $tamañoArchivo = $archivo['size'];
        $archivoTemporal = $archivo['tmp_name'];
        
        // Verificar tipo de archivo (solo imágenes)
        $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!in_array($tipoArchivo, $tiposPermitidos)) {
            $error = "Solo se permiten archivos de imagen (JPG, PNG, GIF)";
        }
        
        // Verificar tamaño del archivo (máximo 5MB)
        elseif ($tamañoArchivo > 5 * 1024 * 1024) {
            $error = "El archivo es demasiado grande. Máximo 5MB.";
        }
        
        else {
            // Generar nombre único para el archivo
            $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
            $nombreUnico = $usuario_actual . '_' . time() . '.' . $extension;
            $rutaDestino = "uploads/usuarios/" . $nombreUnico;
            
            // Crear directorio si no existe
            if (!file_exists("uploads/usuarios/")) {
                mkdir("uploads/usuarios/", 0777, true);
            }
            
            // Mover archivo a la carpeta de destino
            if (move_uploaded_file($archivoTemporal, $rutaDestino)) {
                // Actualizar base de datos
                try {
                    $sql = "UPDATE usuarios SET img = ? WHERE usuario = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ss", $nombreUnico, $usuario_actual);
                    
                    if ($stmt->execute()) {
                        $mensaje = "Imagen de perfil actualizada correctamente.";
                    } else {
                        $error = "Error al actualizar la base de datos.";
                    }
                    
                    $stmt->close();
                } catch (Exception $e) {
                    $error = "Error: " . $e->getMessage();
                }
            } else {
                $error = "Error al subir el archivo.";
            }
        }
    } else {
        $error = "Error en la subida del archivo.";
    }
}


try {
    $sql = "SELECT id, usuario, rango, img FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario_actual);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
    } else {
        $error = "Usuario no encontrado.";
    }
    
    $stmt->close();
} catch (Exception $e) {
    $error = "Error: " . $e->getMessage();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Importar las fuentes si no están ya en otro CSS global */
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Poppins:wght@400;600;700&display=swap');

        /* Definición de Variables (si no están ya en un archivo global) */
        :root {
            --primary-blue: #007bff; /* Azul vibrante */
            --light-blue: #e7f3ff; /* Azul muy claro para acentos sutiles */
            --dark-blue: #0056b3; /* Azul más oscuro para hover/activo */
            --text-color: #333; /* Color de texto oscuro */
            --light-gray: #f8f9fa; /* Para fondo del cuerpo */
            --white: #ffffff; /* Blanco */
            --shadow-light: rgba(0, 0, 0, 0.08); /* Sombra ligera */
            --border-color: #dee2e6; /* Color de borde para inputs, etc. */
            --gradient-blue: linear-gradient(135deg, #007bff 0%, #0056b3 100%); /* Gradiente para el header */
            
            /* Colores para mensajes de estado */
            --success-color: #28a745; /* Verde para éxito */
            --success-bg-light: #d4edda;
            --success-border: #c3e6cb;

            --error-color: #dc3545; /* Rojo para error */
            --error-bg-light: #f8d7da;
            --error-border: #f5c6cb;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-gray); /* Usa el color de fondo claro */
            color: var(--text-color);
            line-height: 1.6;
            display: flex; /* Para centrar el contenido */
            justify-content: center; /* Centrado horizontal */
            align-items: center; /* Centrado vertical */
            min-height: 100vh; /* Ocupa al menos el alto completo de la ventana */
            padding: 20px; /* Padding para que no toque los bordes */
            box-sizing: border-box; /* Incluir padding y border en el tamaño total */
        }

        .container {
            max-width: 650px; /* Ancho máximo ajustado para el formulario de perfil */
            margin: 0 auto;
            background-color: var(--white);
            border-radius: 12px; /* Bordes más redondeados */
            box-shadow: 0 8px 20px var(--shadow-light); /* Sombra más pronunciada pero suave */
            overflow: hidden; /* Asegura que los bordes redondeados se apliquen al contenido */
            border: 1px solid rgba(0, 0, 0, 0.05); /* Borde sutil */
        }

        .header {
            background: var(--gradient-blue); /* Usa el gradiente definido */
            color: var(--white);
            padding: 30px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2); /* Borde suave en la parte inferior del header */
        }

        .header h1 {
            font-size: 2.5rem; /* Tamaño de título más grande */
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }

        .header p {
            font-size: 1.1rem;
            margin-bottom: 0;
            opacity: 0.9;
        }

        .imagen-actual {
            width: 120px; /* Tamaño de imagen ligeramente más grande */
            height: 120px;
            border-radius: 50%;
            border: 4px solid var(--white); /* Borde más pronunciado y blanco */
            object-fit: cover;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Sombra para la imagen de perfil */
            transition: transform 0.3s ease;
        }

        .imagen-actual:hover {
            transform: scale(1.05); /* Efecto sutil al pasar el ratón */
        }
        
        .form-container {
            padding: 40px; /* Espaciado interno generoso */
        }

        .form-group {
            margin-bottom: 25px; /* Más espacio entre grupos de formulario */
        }

        .form-group label {
            display: block;
            margin-bottom: 8px; /* Pequeño margen inferior */
            font-weight: 600; /* Texto de etiqueta más negrita */
            color: var(--text-color);
            display: flex; /* Para alinear ícono y texto */
            align-items: center;
        }
        .form-group label i {
            margin-right: 8px; /* Espacio entre ícono y texto */
            color: var(--primary-blue); /* Ícono en azul vibrante */
            font-size: 1.1rem; /* Tamaño de ícono ligeramente más grande */
        }

        .form-group input[type="file"] {
            width: 100%;
            padding: 12px 15px; /* Relleno generoso */
            border: 1px solid var(--border-color); /* Borde sutil */
            border-radius: 8px; /* Bordes redondeados */
            background-color: var(--white);
            font-size: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .form-group input[type="file"]:focus {
            border-color: var(--primary-blue); /* Borde azul al enfocar */
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25); /* Sombra de enfoque de Bootstrap */
            outline: none; /* Quita el outline por defecto */
        }

        .form-group small {
            display: block; /* Para que ocupe su propia línea */
            margin-top: 5px;
            color: #666;
            font-size: 0.9rem;
        }

        .preview-imagen {
            max-width: 150px; /* Ajusta el tamaño de la vista previa */
            max-height: 150px;
            margin-top: 15px;
            border-radius: 8px; /* Bordes redondeados */
            box-shadow: 0 4px 10px var(--shadow-light);
            border: 1px solid var(--border-color);
            display: none; /* Se oculta por defecto */
            object-fit: cover;
        }

        .botones-container {
            display: flex;
            justify-content: flex-end; /* Alinear botones a la derecha */
            gap: 15px; /* Espacio entre los botones */
            margin-top: 30px;
        }

        /* Botón primario (Actualizar Imagen) */
        .btn-primary {
            background-color: var(--primary-blue);
            color: var(--white);
            border: none;
            padding: 12px 28px; /* Relleno generoso */
            border-radius: 8px; /* Bordes redondeados */
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3); /* Sombra para el botón */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary:hover {
            background-color: var(--dark-blue); /* Color más oscuro al pasar el ratón */
            box-shadow: 0 6px 15px rgba(0, 123, 255, 0.4); /* Sombra más intensa al pasar el ratón */
            transform: translateY(-2px); /* Pequeño levantamiento */
        }
        .btn-primary i {
            margin-right: 8px;
        }

        /* Botón secundario (Cancelar) */
        .btn-secondary {
            background-color: var(--light-gray); /* Fondo claro para el botón de volver */
            color: var(--dark-blue); /* Texto azul oscuro */
            border: 2px solid var(--primary-blue); /* Borde azul vibrante */
            padding: 12px 28px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none; /* Quitar subrayado del enlace */
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-secondary:hover {
            background-color: var(--primary-blue); /* Fondo azul vibrante al pasar el ratón */
            color: var(--white); /* Texto blanco al pasar el ratón */
            transform: translateY(-2px); /* Pequeño levantamiento */
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2); /* Sombra sutil */
        }
        .btn-secondary i {
            margin-right: 8px;
            color: var(--primary-blue);
        }
        .btn-secondary:hover i {
            color: var(--white);
        }

        /* Mensajes de estado (éxito/error) */
        .mensaje {
            padding: 15px;
            border-radius: 8px; /* Más redondeado */
            margin-bottom: 25px; /* Más espacio */
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mensaje i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .mensaje.exito {
            background-color: var(--success-bg-light);
            color: var(--success-color);
            border: 1px solid var(--success-border);
        }

        .mensaje.error {
            background-color: var(--error-bg-light);
            color: var(--error-color);
            border: 1px solid var(--error-border);
        }

        /* Información actual del usuario */
        .info-actual {
            background-color: var(--light-blue); /* Fondo azul claro para esta sección */
            padding: 25px;
            border-radius: 10px; /* Bordes redondeados */
            margin-bottom: 30px; /* Más espacio inferior */
            box-shadow: inset 0 1px 5px rgba(0, 0, 0, 0.05); /* Sombra interna sutil */
        }

        .info-actual h3 {
            color: var(--dark-blue); /* Color del título de sección */
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            border-bottom: 2px solid rgba(0, 86, 179, 0.1); /* Borde inferior sutil */
            padding-bottom: 10px;
        }

        .info-actual p {
            margin-bottom: 8px;
            font-size: 1rem;
            color: var(--text-color);
        }

        .info-actual p strong {
            color: var(--dark-blue); /* Texto en negrita en azul oscuro */
            font-weight: 600;
        }
        
        /* Enlace de Inicio al final */
        .container > a {
            display: block;
            text-align: center;
            margin-top: 25px;
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        .container > a:hover {
            color: var(--dark-blue);
            text-decoration: underline;
        }

        /* Media Queries para Responsividad */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            .container {
                margin: 0;
                border-radius: 8px;
            }
            .header {
                padding: 20px;
            }
            .header h1 {
                font-size: 2rem;
            }
            .imagen-actual {
                width: 100px;
                height: 100px;
            }
            .form-container {
                padding: 25px;
            }
            .botones-container {
                flex-direction: column; /* Apilar botones en pantallas pequeñas */
                gap: 10px;
            }
            .btn-primary, .btn-secondary {
                width: 100%; /* Botones de ancho completo */
                padding: 10px 20px;
            }
            .mensaje {
                font-size: 0.9rem;
                padding: 12px;
            }
            .mensaje i {
                font-size: 1rem;
            }
            .info-actual {
                padding: 20px;
                margin-bottom: 20px;
            }
            .info-actual h3 {
                font-size: 1.3rem;
            }
            .info-actual p {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 1.8rem;
            }
            .header p {
                font-size: 1rem;
            }
            .form-group input[type="file"] {
                font-size: 0.9rem;
                padding: 10px;
            }
            .preview-imagen {
                max-width: 100px;
                max-height: 100px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <?php if (!empty($usuario['img']) && file_exists("uploads/usuarios/" . $usuario['img'])): ?>
                <img src="uploads/usuarios/<?php echo htmlspecialchars($usuario['img']); ?>" 
                    alt="Imagen actual" class="imagen-actual">
            <?php else: ?>
                <img src="uploads/usuarios/default.jpg" alt="Imagen por defecto" class="imagen-actual">
            <?php endif; ?>
            <h1>Editar Perfil</h1>
            <p>Usuario: <?php echo htmlspecialchars($usuario['usuario']); ?></p>
        </div>
        
        <div class="form-container">
            <?php if (!empty($mensaje)): ?>
                <div class="mensaje exito"><i class="fas fa-check-circle"></i><?php echo htmlspecialchars($mensaje); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($error)): ?>
                <div class="mensaje error"><i class="fas fa-exclamation-triangle"></i><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <div class="info-actual">
                <h3>Información Actual</h3>
                <p><strong>Usuario:</strong> <?php echo htmlspecialchars($usuario['usuario']); ?></p>
                <p><strong>Rango:</strong> <?php echo htmlspecialchars($usuario['rango']); ?></p>
                <p><strong>Imagen actual:</strong> <?php echo htmlspecialchars($usuario['img'] ? $usuario['img'] : 'No definida'); ?></p>
            </div>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="imagen_perfil"><i class="fas fa-image"></i>Nueva Imagen de Perfil:</label>
                    <input type="file" 
                            id="imagen_perfil" 
                            name="imagen_perfil" 
                            accept="image/*" 
                            onchange="previewImagen(this)">
                    <small>Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 5MB</small>
                    <img id="preview" class="preview-imagen" alt="Vista previa">
                </div>
                
                <div class="botones-container">
                    <button type="submit" class="btn-primary"><i class="fas fa-save"></i>Actualizar Imagen</button>
                    <button type="button" class="btn-secondary" onclick="window.location.href='perfil.php'"><i class="fas fa-arrow-left"></i>Cancelar</button>
                </div>
            </form>
        </div>
        <a href="index.php">Ir a Inicio</a>
    </div>

    <script>
        function previewImagen(input) {
            const preview = document.getElementById('preview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none';
                preview.src = ''; // Limpiar la vista previa si no hay archivo
            }
        }
    </script>
</body>
</html>