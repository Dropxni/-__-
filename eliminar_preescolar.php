<?php
session_start();

// Verificar si el usuario está logueado y si es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Administrador') {
    header('Location: login_admin.php');
    exit();
}

require_once 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $conn = db_connect();

    // Obtener las rutas de los archivos a eliminar
    $sql = "SELECT curp_documento, certificado_medico, fotografia, tutor_curp FROM Preescolar WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($curp_documento, $certificado_medico, $fotografia, $curp_tutor);
        $stmt->fetch();
        $stmt->close();

        // Eliminar archivos
        if (file_exists($curp_documento)) {
            unlink($curp_documento);
        }
        if (file_exists($certificado_medico)) {
            unlink($certificado_medico);
        }
        if (file_exists($fotografia)) {
            unlink($fotografia);
        }
        if (file_exists($curp_tutor)) {
            unlink($curp_tutor);
        }

        // Eliminar el registro de la base de datos
        $sql = "DELETE FROM Preescolar WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
        }
    }

    $conn->close();

    header('Location: expediente_preescolar.php?message=Alumno eliminado con éxito');
    exit();
}
?>
