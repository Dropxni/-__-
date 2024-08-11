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
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Manejo de archivos
    $curp_pdf = '';
    $acta_nacimiento = '';
    $certificado_medico = '';
    $fotografia = '';

    $conn = db_connect();

    // Obtener las rutas actuales de los archivos
    $sql = "SELECT curp_documento, acta_nacimiento, certificado_medico, fotografia FROM Profesores WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($curp_pdf_old, $acta_nacimiento_old, $certificado_medico_old, $fotografia_old);
    $stmt->fetch();
    $stmt->close();

    // CURP Documento
    if (isset($_FILES['curp_pdf']) && $_FILES['curp_pdf']['error'] === UPLOAD_ERR_OK) {
        if (file_exists($curp_pdf_old)) {
            unlink($curp_pdf_old);
        }
        $curp_pdf = 'uploads/profesor/' . uniqid() . '_' . basename($_FILES['curp_pdf']['name']);
        move_uploaded_file($_FILES['curp_pdf']['tmp_name'], $curp_pdf);
    } else {
        $curp_pdf = $curp_pdf_old;
    }

    // Acta de Nacimiento
    if (isset($_FILES['acta_nacimiento']) && $_FILES['acta_nacimiento']['error'] === UPLOAD_ERR_OK) {
        if (file_exists($acta_nacimiento_old)) {
            unlink($acta_nacimiento_old);
        }
        $acta_nacimiento = 'uploads/profesor/' . uniqid() . '_' . basename($_FILES['acta_nacimiento']['name']);
        move_uploaded_file($_FILES['acta_nacimiento']['tmp_name'], $acta_nacimiento);
    } else {
        $acta_nacimiento = $acta_nacimiento_old;
    }

    // Certificado Médico
    if (isset($_FILES['certificado_medico']) && $_FILES['certificado_medico']['error'] === UPLOAD_ERR_OK) {
        if (file_exists($certificado_medico_old)) {
            unlink($certificado_medico_old);
        }
        $certificado_medico = 'uploads/profesor/' . uniqid() . '_' . basename($_FILES['certificado_medico']['name']);
        move_uploaded_file($_FILES['certificado_medico']['tmp_name'], $certificado_medico);
    } else {
        $certificado_medico = $certificado_medico_old;
    }

    // Fotografía
    if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] === UPLOAD_ERR_OK) {
        if (file_exists($fotografia_old)) {
            unlink($fotografia_old);
        }
        $fotografia = 'uploads/profesor/' . uniqid() . '_' . basename($_FILES['fotografia']['name']);
        move_uploaded_file($_FILES['fotografia']['tmp_name'], $fotografia);
    } else {
        $fotografia = $fotografia_old;
    }

    // Actualización de datos
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE Profesores SET nombre = ?, apellido_paterno = ?, apellido_materno = ?, edad = ?, curp_documento = ?, direccion = ?, telefono = ?, acta_nacimiento = ?, certificado_medico = ?, fotografia = ?, username = ?, password_hash = ? WHERE id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('sssissssssssi', $nombre, $apellido_paterno, $apellido_materno, $edad, $curp_pdf, $direccion, $telefono, $acta_nacimiento, $certificado_medico, $fotografia, $username, $password_hash, $id);
        $stmt->execute();
        $stmt->close();

        // Actualizar también la tabla Usuarios
        $sql_usuario = "UPDATE Usuarios SET username = ?, password_hash = ? WHERE username = (SELECT username FROM Profesores WHERE id = ?)";
        if ($stmt_usuario = $conn->prepare($sql_usuario)) {
            $stmt_usuario->bind_param('ssi', $username, $password_hash, $id);
            $stmt_usuario->execute();
            $stmt_usuario->close();
        }
    }

    $conn->close();
    header("Location: expediente_profesores.php");
    exit();
}
?>
