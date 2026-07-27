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
$usuario = $resultado->fetch_assoc();

if ($usuario['rango'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$mensaje = '';
$tipo_mensaje = '';
$id_editar = '';
$usuario_editar = '';
$rango_editar = '';
$modo_edicion = false;

if (isset($_POST['eliminar'])) {
    $id_eliminar = $_POST['id_eliminar'];
    
    $stmt = $conn->prepare("SELECT usuario FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id_eliminar);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario_eliminar = $resultado->fetch_assoc();
    
    if ($usuario_eliminar['usuario'] === $_SESSION['usuario']) {
        $mensaje = "No puedes eliminar tu propio usuario.";
        $tipo_mensaje = "danger";
    } else {
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id_eliminar);
        
        if ($stmt->execute()) {
            $mensaje = "Usuario eliminado correctamente.";
            $tipo_mensaje = "success";
        } else {
            $mensaje = "Error al eliminar el usuario: " . $conn->error;
            $tipo_mensaje = "danger";
        }
    }
}

if (isset($_POST['editar'])) {
    $id_editar = $_POST['id_editar'];
    $usuario_editar = $_POST['usuario_editar'];
    $rango_editar = $_POST['rango_editar'];
    $modo_edicion = true;
}

if (isset($_POST['guardar_edicion'])) {
    $id = $_POST['id'];
    $usuario_nuevo = $_POST['usuario'];
    $rango = $_POST['rango'];
    
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ? AND id != ?");
    $stmt->bind_param("si", $usuario_nuevo, $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        $mensaje = "El nombre de usuario ya existe.";
        $tipo_mensaje = "danger";
        $id_editar = $id;
        $usuario_editar = $usuario_nuevo;
        $rango_editar = $rango;
        $modo_edicion = true;
    } else {
        $stmt = $conn->prepare("UPDATE usuarios SET usuario = ?, rango = ? WHERE id = ?");
        $stmt->bind_param("ssi", $usuario_nuevo, $rango, $id);
        
        if ($stmt->execute()) {
            $mensaje = "Usuario actualizado correctamente.";
            $tipo_mensaje = "success";
        } else {
            $mensaje = "Error al actualizar el usuario: " . $conn->error;
            $tipo_mensaje = "danger";
        }
    }
}

if (isset($_POST['cancelar_edicion'])) {
    $modo_edicion = false;
    $id_editar = '';
    $usuario_editar = '';
    $rango_editar = '';
}

if (isset($_POST['agregar'])) {
    $usuario_nuevo = $_POST['nuevo_usuario'];
    $contrasena = password_hash($_POST['nuevo_password'], PASSWORD_DEFAULT);
    $rango = $_POST['nuevo_rango'];
    
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ?");
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("s", $usuario_nuevo);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        $mensaje = "El nombre de usuario ya existe.";
        $tipo_mensaje = "danger";
    } else {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO usuarios (usuario, contrasena, rango) VALUES (?, ?, ?)");
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        $stmt->bind_param("sss", $usuario_nuevo, $contrasena, $rango);
    
        if ($stmt->execute()) {
            $mensaje = "Usuario agregado correctamente.";
            $tipo_mensaje = "success";
        } else {
            $mensaje = "Error al agregar el usuario: " . $conn->error;
            $tipo_mensaje = "danger";
        }
    }
}

$stmt = $conn->prepare("SELECT id, usuario, rango FROM usuarios");
$stmt->execute();
$resultado_usuarios = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
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
            max-width: 900px;
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

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
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
            margin-right: 5px;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0069d9;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .actions {
            display: flex;
            gap: 5px;
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

        @media (max-width: 768px) {
            .home-button {
                position: relative;
                left: auto;
                top: auto;
                transform: none;
                margin: 10px auto;
                display: block;
            }
            
            .actions {
                flex-direction: column;
            }
            
            .actions form {
                margin-bottom: 5px;
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
        <h1>Gestión de Usuarios</h1>
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

        <?php if (!$modo_edicion): ?>
        <div class="card">
            <div class="card-header">
                <h2>Agregar Nuevo Usuario</h2>
            </div>
            <div class="card-content">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="nuevo_usuario">Nombre de Usuario:</label>
                        <input type="text" class="form-control" id="nuevo_usuario" name="nuevo_usuario" required>
                    </div>
                    <div class="form-group">
                        <label for="nuevo_password">Contraseña:</label>
                        <input type="password" class="form-control" id="nuevo_password" name="nuevo_password" required>
                    </div>
                    <div class="form-group">
                        <label for="nuevo_rango">Rango:</label>
                        <select class="form-control" id="nuevo_rango" name="nuevo_rango" required>
                            <option value="admin">Administrador</option>
                            <option value="transporte">Transporte</option>
                        </select>
                    </div>
                    <button type="submit" name="agregar" class="btn btn-success">Agregar Usuario</button>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($modo_edicion): ?>
        <div class="card">
            <div class="card-header">
                <h2>Editar Usuario</h2>
            </div>
            <div class="card-content">
                <form method="POST" action="">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($id_editar) ?>">
                    <div class="form-group">
                        <label for="usuario">Nombre de Usuario:</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" value="<?= htmlspecialchars($usuario_editar) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="rango">Rango:</label>
                        <select class="form-control" id="rango" name="rango" required>
                            <option value="admin" <?= ($rango_editar === 'admin') ? 'selected' : '' ?>>Administrador</option>
                            <option value="transporte" <?= ($rango_editar === 'transporte') ? 'selected' : '' ?>>Transporte</option>
                        </select>
                    </div>
                    <button type="submit" name="guardar_edicion" class="btn btn-primary">Guardar Cambios</button>
                    <button type="submit" name="cancelar_edicion" class="btn btn-secondary">Cancelar</button>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h2>Usuarios Registrados</h2>
            </div>
            <div class="card-content">
                <div class="table-responsive">
                    <?php if ($resultado_usuarios->num_rows > 0): ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Usuario</th>
                                    <th>Rango</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($usuario_tabla = $resultado_usuarios->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($usuario_tabla['id']) ?></td>
                                        <td><?= htmlspecialchars($usuario_tabla['usuario']) ?></td>
                                        <td><?= htmlspecialchars($usuario_tabla['rango']) ?></td>
                                        <td class="actions">
                                            <?php if (!$modo_edicion || $id_editar != $usuario_tabla['id']): ?>
                                                <form method="POST" action="" style="display: inline;">
                                                    <input type="hidden" name="id_editar" value="<?= $usuario_tabla['id'] ?>">
                                                    <input type="hidden" name="usuario_editar" value="<?= $usuario_tabla['usuario'] ?>">
                                                    <input type="hidden" name="rango_editar" value="<?= $usuario_tabla['rango'] ?>">
                                                    <button type="submit" name="editar" class="btn btn-warning">
                                                        <i class="fas fa-edit"></i> Editar
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="btn btn-warning" style="opacity: 0.5;">
                                                    <i class="fas fa-edit"></i> Editando...
                                                </span>
                                            <?php endif; ?>
                                            
                                            <form method="POST" action="" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                                                <input type="hidden" name="id_eliminar" value="<?= $usuario_tabla['id'] ?>">
                                                <button type="submit" name="eliminar" class="btn btn-danger">
                                                    <i class="fas fa-trash-alt"></i> Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert danger">
                            <div class="content">
                                <div class="icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 0C5.376 0 0 5.376 0 12C0 18.624 5.376 24 12 24C18.624 24 24 18.624 24 12C24 5.376 18.624 0 12 0ZM12 13.2C11.336 13.2 10.8 12.664 10.8 12V7.2C10.8 6.536 11.336 6 12 6C12.664 6 13.2 6.536 13.2 7.2V12C13.2 12.664 12.664 13.2 12 13.2ZM13.2 18H10.8V15.6H13.2V18Z" fill="#EC4D2B"/>
                                    </svg>
                                </div>
                                <p>No hay usuarios registrados.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer style="text-align: center; padding: 20px; background-color: #333; color: white; margin-top: 40px;">
        <p>&copy; 2025 Mi Aplicación</p>
    </footer>

    <script>
        // Hacer que las alertas desaparezcan después de 5 segundos
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