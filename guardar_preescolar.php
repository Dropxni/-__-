<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login_admin.php');
    exit();
}

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $edad = $_POST['edad'];
    $nombre_tutor = $_POST['nombre_tutor'];
    $apellido_paterno_tutor = $_POST['apellido_paterno_tutor'];
    $apellido_materno_tutor = $_POST['apellido_materno_tutor'];
    $telefono_tutor = $_POST['telefono_tutor'];
    $direccion = $_POST['direccion'];

    // Manejo de archivos
    $curp_pdf = '';
    $certificado_medico = '';
    $fotografia = '';
    $curp_tutor_pdf = '';

    if (isset($_FILES['curp_pdf']) && $_FILES['curp_pdf']['error'] === UPLOAD_ERR_OK) {
        $curp_pdf = 'uploads/' . uniqid() . '_' . basename($_FILES['curp_pdf']['name']);
        move_uploaded_file($_FILES['curp_pdf']['tmp_name'], $curp_pdf);
    }

    if (isset($_FILES['certificado_medico']) && $_FILES['certificado_medico']['error'] === UPLOAD_ERR_OK) {
        $certificado_medico = 'uploads/preescolar/' . uniqid() . '_' . basename($_FILES['certificado_medico']['name']);
        move_uploaded_file($_FILES['certificado_medico']['tmp_name'], $certificado_medico);
    }

    if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] === UPLOAD_ERR_OK) {
        $fotografia = 'uploads/preescolar/' . uniqid() . '_' . basename($_FILES['fotografia']['name']);
        move_uploaded_file($_FILES['fotografia']['tmp_name'], $fotografia);
    }

    if (isset($_FILES['curp_tutor_pdf']) && $_FILES['curp_tutor_pdf']['error'] === UPLOAD_ERR_OK) {
        $curp_tutor_pdf = 'uploads/preescolar/' . uniqid() . '_' . basename($_FILES['curp_tutor_pdf']['name']);
        move_uploaded_file($_FILES['curp_tutor_pdf']['tmp_name'], $curp_tutor_pdf);
    }

    $conn = db_connect();

    $sql = "INSERT INTO Preescolar (nombre, apellido_paterno, apellido_materno, edad, curp_documento, certificado_medico, fotografia, tutor_nombre, tutor_apellido_paterno, tutor_apellido_materno, tutor_curp, tutor_telefono, direccion, activo) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('sssisssssssss', $nombre, $apellido_paterno, $apellido_materno, $edad, $curp_pdf, $certificado_medico, $fotografia, $nombre_tutor, $apellido_paterno_tutor, $apellido_materno_tutor, $curp_tutor_pdf, $telefono_tutor, $direccion);
        
        if ($stmt->execute()) {
            echo 'success';
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
