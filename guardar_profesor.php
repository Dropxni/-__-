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
    $curp = $_POST['curp'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $curp_pdf = '';
    $acta_nacimiento = '';
    $certificado_medico = '';
    $fotografia = '';

    // Directorio de subidas
    $upload_dir = 'uploads/profesor/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Manejo del archivo de CURP
    if (isset($_FILES['curp_pdf']) && $_FILES['curp_pdf']['error'] === UPLOAD_ERR_OK) {
        $curp_pdf = $upload_dir . uniqid() . '_' . basename($_FILES['curp_pdf']['name']);
        move_uploaded_file($_FILES['curp_pdf']['tmp_name'], $curp_pdf);
    }

    // Manejo del archivo de acta de nacimiento
    if (isset($_FILES['acta_nacimiento']) && $_FILES['acta_nacimiento']['error'] === UPLOAD_ERR_OK) {
        $acta_nacimiento = $upload_dir . uniqid() . '_' . basename($_FILES['acta_nacimiento']['name']);
        move_uploaded_file($_FILES['acta_nacimiento']['tmp_name'], $acta_nacimiento);
    }

    // Manejo del archivo de certificado médico
    if (isset($_FILES['certificado_medico']) && $_FILES['certificado_medico']['error'] === UPLOAD_ERR_OK) {
        $certificado_medico = $upload_dir . uniqid() . '_' . basename($_FILES['certificado_medico']['name']);
        move_uploaded_file($_FILES['certificado_medico']['tmp_name'], $certificado_medico);
    }

    // Manejo del archivo de fotografía
    if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] === UPLOAD_ERR_OK) {
        $fotografia = $upload_dir . uniqid() . '_' . basename($_FILES['fotografia']['name']);
        move_uploaded_file($_FILES['fotografia']['tmp_name'], $fotografia);
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $conn = db_connect();

    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        // Insertar en la tabla Profesores
        $sql_profesor = "INSERT INTO Profesores (nombre, apellido_paterno, apellido_materno, edad, curp_documento, direccion, telefono, acta_nacimiento, certificado_medico, fotografia, username, password_hash, activo) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
        if ($stmt_profesor = $conn->prepare($sql_profesor)) {
            $stmt_profesor->bind_param('sssissssssss', $nombre, $apellido_paterno, $apellido_materno, $edad, $curp_pdf, $direccion, $telefono, $acta_nacimiento, $certificado_medico, $fotografia, $username, $password_hash);
            $stmt_profesor->execute();
            $stmt_profesor->close();
        } else {
            throw new Exception("Error de preparación de consulta: " . $conn->error);
        }

        // Insertar en la tabla Usuarios
        $sql_usuario = "INSERT INTO Usuarios (username, password_hash, tipo, activo) VALUES (?, ?, 'Personal', 1)";
        if ($stmt_usuario = $conn->prepare($sql_usuario)) {
            $stmt_usuario->bind_param('ss', $username, $password_hash);
            $stmt_usuario->execute();
            $stmt_usuario->close();
        } else {
            throw new Exception("Error de preparación de consulta: " . $conn->error);
        }

        // Confirmar la transacción
        $conn->commit();

        header("Location: admin_dashboard.php?message=Profesor registrado con éxito");
    } catch (Exception $e) {
        // Revertir la transacción
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $conn->close();
}
?>
