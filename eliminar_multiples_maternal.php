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
        $stmt_select = $conn->prepare("SELECT curp_documento, certificado_medico, fotografia, tutor_curp FROM Maternal WHERE id = ?");
        $stmt_delete = $conn->prepare("DELETE FROM Maternal WHERE id = ?");

        foreach ($delete_ids as $id) {
            // Obtener las rutas de los archivos a eliminar
            $stmt_select->bind_param('i', $id);
            $stmt_select->execute();
            $stmt_select->bind_result($curp_documento, $certificado_medico, $fotografia, $curp_tutor);
            $stmt_select->fetch();

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
            $stmt_delete->bind_param('i', $id);
            $stmt_delete->execute();
        }

        $stmt_select->close();
        $stmt_delete->close();

        // Confirmar la transacción
        $conn->commit();
        header('Location: expediente_maternal.php?message=Alumnos eliminados con éxito');
        exit();
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $conn->close();
} else {
    header('Location: expediente_maternal.php');
    exit();
}
?>
