<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login_admin.php');
    exit();
}

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_ids'])) {
    $delete_ids = $_POST['delete_ids'];

    $conn = db_connect();

    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        $stmt_select = $conn->prepare("SELECT curp_documento, acta_nacimiento, certificado_medico, fotografia, username FROM Profesores WHERE id = ?");
        $stmt_delete = $conn->prepare("DELETE FROM Profesores WHERE id = ?");
        $stmt_delete_usuario = $conn->prepare("DELETE FROM Usuarios WHERE username = ?");

        foreach ($delete_ids as $id) {
            // Obtener las rutas de los archivos a eliminar
            $stmt_select->bind_param('i', $id);
            $stmt_select->execute();
            $stmt_select->bind_result($curp_documento, $acta_nacimiento, $certificado_medico, $fotografia, $username);
            $stmt_select->fetch();

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
            $stmt_delete->bind_param('i', $id);
            $stmt_delete->execute();

            // Eliminar el registro de la tabla Usuarios
            $stmt_delete_usuario->bind_param('s', $username);
            $stmt_delete_usuario->execute();
        }

        $stmt_select->close();
        $stmt_delete->close();
        $stmt_delete_usuario->close();

        // Confirmar la transacción
        $conn->commit();
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
    }

    $conn->close();
    header('Location: expediente_profesores.php');
    exit();
} else {
    header('Location: expediente_profesores.php');
    exit();
}
?>
