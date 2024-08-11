<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login_admin.php');
    exit();
}

require_once 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $conn = db_connect();

    // Obtener las rutas de los archivos a eliminar
    $sql = "SELECT curp_documento, acta_nacimiento, certificado_medico, fotografia, username FROM Profesores WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($curp_documento, $acta_nacimiento, $certificado_medico, $fotografia, $username);
        $stmt->fetch();
        $stmt->close();

        // Eliminar archivos
        if (file_exists($curp_documento)) {
            unlink($curp_documento);
        }
        if (file_exists($acta_nacimiento)) {
            unlink($acta_nacimiento);
        }
        if (file_exists($certificado_medico)) {
            unlink($certificado_medico);
        }
        if (file_exists($fotografia)) {
            unlink($fotografia);
        }

        // Eliminar el registro de la base de datos
        $sql = "DELETE FROM Profesores WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
        }

        // Eliminar el registro de la tabla Usuarios
        $sql = "DELETE FROM Usuarios WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->close();
        }
    }

    $conn->close();
    header('Location: expediente_profesores.php');
    exit();
}
?>
