<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
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

    $certificado_medico = '';
    $fotografia = '';

    // Manejo del archivo de certificado médico
    if (isset($_FILES['certificado_medico']) && $_FILES['certificado_medico']['error'] === UPLOAD_ERR_OK) {
        $certificado_medico = 'uploads/' . uniqid() . '_' . basename($_FILES['certificado_medico']['name']);
        move_uploaded_file($_FILES['certificado_medico']['tmp_name'], $certificado_medico);
    }

    // Manejo del archivo de fotografía
    if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] === UPLOAD_ERR_OK) {
        $fotografia = 'uploads/' . uniqid() . '_' . basename($_FILES['fotografia']['name']);
        move_uploaded_file($_FILES['fotografia']['tmp_name'], $fotografia);
    }

    $conn = db_connect();

    $sql = "UPDATE Preescolar SET nombre = ?, apellido_paterno = ?, apellido_materno = ?, edad = ?, curp_documento = ?, certificado_medico = ?, fotografia = ?, tutor_nombre = ?, tutor_apellido_paterno = ?, tutor_apellido_materno = ?, tutor_curp = ?, tutor_telefono = ?, direccion = ? WHERE id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('sssisssssssssi', $nombre, $apellido_paterno, $apellido_materno, $edad, $curp, $certificado_medico, $fotografia, $nombre_tutor, $apellido_paterno_tutor, $apellido_materno_tutor, $curp_tutor, $telefono_tutor, $direccion, $id);
        
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
