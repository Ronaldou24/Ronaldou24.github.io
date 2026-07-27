<?php
require_once '../admin/bd.php'; // Conexión ya incluida aquí
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1. Validar y sanitizar inputs
        $campos = [
            'usuario' => $_SESSION['usuario'],
            'origen' => strtoupper(trim($_POST['origen'])),
            'destino' => strtoupper(trim($_POST['destino'])),
            'fecha_origen' => trim($_POST['fecha_origen']),
            'hora_salida' => trim($_POST['hora_salida']),
            'fecha_destino' => trim($_POST['fecha_destino']),
            'hora_llegada' => trim($_POST['hora_llegada']),
            'numero_barco' => (int)$_POST['numero_barco'], // Conversión tipo seguro
            'tipo_embarcacion' => trim($_POST['tipo_embarcacion'])
        ];

        // 2. Validaciones combinadas
        $errores = [];
        foreach (['origen', 'destino', 'fecha_origen', 'hora_salida'] as $campo) {
            if (empty($campos[$campo])) {
                $errores[] = "El campo " . str_replace('_', ' ', $campo) . " es obligatorio";
            }
        }

        // Validación numérica mejorada
        if ($campos['numero_barco'] < 1 || $campos['numero_barco'] > 9999) {
            $errores[] = "Número de barco inválido";
        }

        // 3. Validación de formatos
        $formatos = [
            'fecha_origen' => 'Y-m-d',
            'fecha_destino' => 'Y-m-d',
            'hora_salida' => 'H:i',
            'hora_llegada' => 'H:i'
        ];

        foreach ($formatos as $campo => $formato) {
            $fecha = DateTime::createFromFormat($formato, $campos[$campo]);
            if (!$fecha || $fecha->format($formato) !== $campos[$campo]) {
                $errores[] = "Formato inválido en " . str_replace('_', ' ', $campo);
            }
        }

        if (!empty($errores)) {
            throw new Exception(implode("<br>", $errores));
        }

        // 4. Consulta preparada (usando conexión existente de bd.php)
        $sql = "INSERT INTO rutas (
            usuario, origen, fecha_origen, hora_salida,
            destino, fecha_destino, hora_llegada,
            numero_barco, tipo_embarcacion
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en preparación de consulta: " . $conn->error);
        }

        // 5. Bind param con tipos específicos
        $stmt->bind_param(
            "sssssssis",
            $campos['usuario'],
            $campos['origen'],
            $campos['fecha_origen'],
            $campos['hora_salida'],
            $campos['destino'],
            $campos['fecha_destino'],
            $campos['hora_llegada'],
            $campos['numero_barco'],
            $campos['tipo_embarcacion']
        );

        // 6. Ejecución y manejo de resultados
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = htmlspecialchars(
                "Ruta #" . $conn->insert_id . " registrada!",
                ENT_QUOTES, 'UTF-8'
            );
            header("Location: exito.php");
        } else {
            throw new Exception("Error de ejecución: " . $stmt->error);
        }

    } catch (Exception $e) {
        error_log("[".date('Y-m-d H:i')."] Error: ".$e->getMessage());
        $_SESSION['error'] = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        header("Location: error.php");
    } finally {
        if (isset($stmt)) $stmt->close();
        // La conexión se cierra en el archivo que la creó (bd.php)
    }
    exit();
}

header("Location: consultaRuta.php");
exit();
?>
