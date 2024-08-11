<?php
session_start();

// Verificar si el usuario está logueado y si es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Administrador') {
    header('Location: login_admin.php');
    exit();
}

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $edad = $_POST['edad'];
    $curp = $_POST['curp'];
    $nombre_tutor = $_POST['nombre_tutor'];
    $apellido_paterno_tutor = $_POST['apellido_paterno_tutor'];
    $apellido_materno_tutor = $_POST['apellido_materno_tutor'];
    $curp_tutor = $_POST['curp_tutor'];
    $telefono_tutor = $_POST['telefono_tutor'];
    $direccion = $_POST['direccion'];

    // Manejo de archivos
    $curp_pdf = '';
    $certificado_medico = '';
    $curp_tutor_pdf = '';

    $conn = db_connect();

    // Obtener las rutas actuales de los archivos
    $sql = "SELECT curp_documento, certificado_medico, tutor_curp FROM Preescolar WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($curp_pdf_old, $certificado_medico_old, $curp_tutor_pdf_old);
    $stmt->fetch();
    $stmt->close();

    // CURP Documento
    if (isset($_FILES['curp_pdf']) && $_FILES['curp_pdf']['error'] === UPLOAD_ERR_OK) {
        if (file_exists($curp_pdf_old)) {
            unlink($curp_pdf_old);
        }
        $curp_pdf = 'uploads/preescolar/' . uniqid() . '_' . basename($_FILES['curp_pdf']['name']);
        move_uploaded_file($_FILES['curp_pdf']['tmp_name'], $curp_pdf);
    } else {
        $curp_pdf = $curp_pdf_old;
    }

    // Certificado Médico
    if (isset($_FILES['certificado_medico_pdf']) && $_FILES['certificado_medico_pdf']['error'] === UPLOAD_ERR_OK) {
        if (file_exists($certificado_medico_old)) {
            unlink($certificado_medico_old);
        }
        $certificado_medico = 'uploads/preescolar/' . uniqid() . '_' . basename($_FILES['certificado_medico_pdf']['name']);
        move_uploaded_file($_FILES['certificado_medico_pdf']['tmp_name'], $certificado_medico);
    } else {
        $certificado_medico = $certificado_medico_old;
    }

    // CURP Tutor
    if (isset($_FILES['curp_tutor_pdf']) && $_FILES['curp_tutor_pdf']['error'] === UPLOAD_ERR_OK) {
        if (file_exists($curp_tutor_pdf_old)) {
            unlink($curp_tutor_pdf_old);
        }
        $curp_tutor_pdf = 'uploads/preescolar/' . uniqid() . '_' . basename($_FILES['curp_tutor_pdf']['name']);
        move_uploaded_file($_FILES['curp_tutor_pdf']['tmp_name'], $curp_tutor_pdf);
    } else {
        $curp_tutor_pdf = $curp_tutor_pdf_old;
    }

    $sql = "UPDATE Preescolar SET nombre = ?, apellido_paterno = ?, apellido_materno = ?, edad = ?, curp_documento = ?, certificado_medico = ?, tutor_nombre = ?, tutor_apellido_paterno = ?, tutor_apellido_materno = ?, tutor_curp = ?, tutor_telefono = ?, direccion = ? WHERE id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('sssissssssssi', $nombre, $apellido_paterno, $apellido_materno, $edad, $curp_pdf, $certificado_medico, $nombre_tutor, $apellido_paterno_tutor, $apellido_materno_tutor, $curp_tutor_pdf, $telefono_tutor, $direccion, $id);
        
        if ($stmt->execute()) {
            header("Location: expediente_preescolar.php?message=Alumno editado con éxito");
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error de preparación de consulta: " . $conn->error;
    }

    $conn->close();
}
?>
