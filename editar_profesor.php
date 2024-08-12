<?php
session_start();

// Verificar si el usuario está logueado y si es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Administrador') {
    header('Location: login_admin.php');
    exit();
}

require_once 'config.php';

$id = $_GET['id'];
$conn = db_connect();
$sql = "SELECT * FROM Profesores WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$profesor = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Profesor</title>
    <!-- Import Google Icon Font -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Import Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Import MaterializeCSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Baloo 2', cursive;
            color: #555;
            padding-top: 64px; /* Espacio para el navbar fijo */
        }

        /* Navbar fijo */
        nav, .dropdown-content {
            background-color: #f8c291 !important;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        nav a, .dropdown-content a {
            color: #555 !important;
        }

        .brand-logo img {
            max-width: 50px;
            margin-top: 7px;
        }

        .content {
            margin-top: 100px;
            animation: fadeIn 2s;
        }

        .form-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .form-container:hover {
            transform: translateY(-5px);
        }

        .input-field input[type=text]:not(.file-input), 
        .input-field input[type=number]:not(.file-input), 
        .input-field input[type=password] {
            background-color: #ffe8d6;
        }

        .btn-primary {
            background-color: #C2185B !important;
            border: none;
        }

        .btn-primary:hover {
            background-color: #a71d44 !important;
        }

        .header {
            margin-bottom: 30px;
        }

        .header h2 {
            font-size: 2.5rem;
        }

        .pdf-buttons {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pdf-buttons .btn-small {
            padding: 0 10px;
            font-size: 14px;
        }

        .preview-image {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            position: relative;
        }

        .preview-image img {
            max-width: 100px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .hide {
            display: none;
        }

        .editable-icon {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 10px;
            color: #C2185B;
        }

        .editable-icon:hover {
            color: #a71d44;
        }

        .file-icons .btn-small i {
            margin: 0;
        }

        .file-icons {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .file-field .file-path-wrapper {
            display: flex;
            align-items: center;
        }

        .file-field .file-path-wrapper input {
            flex-grow: 1;
        }

        /* Menú móvil alineado con el navbar */
        .sidenav {
            height: 100%; /* Ocupa todo el alto de la pantalla */
            background-color: #fff !important;
            top: 64px; /* Alinea la parte superior del sidenav con el navbar */
            z-index: 1100; /* Asegura que el sidenav esté por encima del contenido */
            position: fixed;
        }

        .sidenav li {
            padding: 0; /* Elimina el padding para que el tamaño coincida con el navbar */
            font-size: 1.2rem;
            font-weight: 500;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .sidenav li a {
            display: flex;
            align-items: center;
            color: #555;
            height: 64px; /* Establece la altura de los elementos del sidenav igual a la del navbar */
            line-height: 64px; /* Centra el texto verticalmente */
            padding-left: 16px;
            transition: background-color 0.3s ease;
        }

        .sidenav li a:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }

        .sidenav li a i {
            margin-right: 10px;
        }

        .modal {
            max-width: 600px;
            z-index: 1200 !important; /* Asegura que el modal esté sobre el sidenav */
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @media (max-width: 768px) {
            .form-container {
                margin: 0 15px;
            }
            .header h2 {
                font-size: 2rem;
            }
        }

        @media (min-width: 992px) {
            .form-container {
                max-width: 800px;
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-wrapper">
            <a href="admin_dashboard.php" class="brand-logo"><img src="img/logo.png" alt="Logo"></a>
            <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down">
                <li>
                    <a class="dropdown-trigger" href="#!" data-target="dropdown1">Nuevo registro<i class="material-icons right">arrow_drop_down</i></a>
                </li>
                <li>
                    <a class="dropdown-trigger" href="#!" data-target="dropdown2">Expediente<i class="material-icons right">arrow_drop_down</i></a>
                </li>
                <li>
                    <a href="#modalLogout" class="modal-trigger">Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </nav>

    <ul id="dropdown1" class="dropdown-content">
        <li><a href="registro_preescolar.php">Alumnos preescolar</a></li>
        <li><a href="registro_maternal.php">Alumnos maternal</a></li>
        <li><a href="registro_profesor.php">Profesores</a></li>
    </ul>
    <ul id="dropdown2" class="dropdown-content">
        <li><a href="expediente_preescolar.php">Alumnos preescolar</a></li>
        <li><a href="expediente_maternal.php">Alumnos maternal</a></li>
        <li><a href="expediente_profesores.php">Profesores</a></li>
    </ul>

    <ul class="sidenav" id="mobile-demo">
        <li><a href="registro_preescolar.php"><i class="material-icons">person_add</i>Preescolar</a></li>
        <li><a href="registro_maternal.php"><i class="material-icons">person_add</i>Maternal</a></li>
        <li><a href="registro_profesor.php"><i class="material-icons">person_add</i>Profesores</a></li>
        <li><a href="expediente_preescolar.php"><i class="material-icons">folder_open</i>Preescolar</a></li>
        <li><a href="expediente_maternal.php"><i class="material-icons">folder_open</i>Maternal</a></li>
        <li><a href="expediente_profesores.php"><i class="material-icons">folder_open</i>Profesores</a></li>
        <li><a href="#modalLogout" class="modal-trigger"><i class="material-icons">exit_to_app</i>Cerrar sesión</a></li>
    </ul>

    <div class="container content">
        <div class="row">
            <div class="col s12">
                <div class="form-container">
                    <div class="header">
                        <h2 class="text-center">EDITAR PROFESOR</h2>
                    </div>
                    <div class="preview-image">
                        <?php if($profesor['fotografia']): ?>
                            <img src="<?php echo $profesor['fotografia']; ?>" alt="Fotografía del profesor">
                        <?php endif; ?>
                    </div>
                    <form id="editarForm" method="post" action="guardar_editar_profesor.php" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $profesor['id']; ?>">
                        <div class="input-field">
                            <i class="material-icons editable-icon" onclick="enableEdit('nombre')">edit</i>
                            <input type="text" id="nombre" name="nombre" value="<?php echo $profesor['nombre']; ?>" required>
                            <label for="nombre">Nombre</label>
                        </div>
                        <div class="input-field">
                            <i class="material-icons editable-icon" onclick="enableEdit('apellido_paterno')">edit</i>
                            <input type="text" id="apellido_paterno" name="apellido_paterno" value="<?php echo $profesor['apellido_paterno']; ?>" required>
                            <label for="apellido_paterno">Apellido paterno</label>
                        </div>
                        <div class="input-field">
                            <i class="material-icons editable-icon" onclick="enableEdit('apellido_materno')">edit</i>
                            <input type="text" id="apellido_materno" name="apellido_materno" value="<?php echo $profesor['apellido_materno']; ?>">
                            <label for="apellido_materno">Apellido materno</label>
                        </div>
                        <div class="input-field">
                            <i class="material-icons editable-icon" onclick="enableEdit('edad')">edit</i>
                            <input type="number" id="edad" name="edad" value="<?php echo $profesor['edad']; ?>" required>
                            <label for="edad">Edad</label>
                        </div>
                        <div class="input-field">
                            <label for="curp">CURP</label>
                            <input type="text" id="curp" name="curp" value="<?php echo $profesor['curp_documento']; ?>" required>
                            <div class="pdf-buttons file-icons">
                                <?php if($profesor['curp_documento']): ?>
                                    <button type="button" class="btn-small red lighten-1" onclick="removeCurpPdf()"><i class="material-icons">delete</i></button>
                                    <button type="button" class="btn-small blue lighten-1" onclick="viewPdf('<?php echo $profesor['curp_documento']; ?>')"><i class="material-icons">visibility</i></button>
                                <?php endif; ?>
                                <input type="file" id="curp_pdf" name="curp_pdf" accept="application/pdf" class="hide">
                            </div>
                        </div>
                        <div class="input-field">
                            <label for="acta_nacimiento">Acta de Nacimiento</label>
                            <input type="text" id="acta_nacimiento" name="acta_nacimiento" value="<?php echo $profesor['acta_nacimiento']; ?>" placeholder="Acta de Nacimiento">
                            <div class="pdf-buttons file-icons">
                                <?php if($profesor['acta_nacimiento']): ?>
                                    <button type="button" class="btn-small red lighten-1" onclick="removeActaNacimiento()"><i class="material-icons">delete</i></button>
                                    <button type="button" class="btn-small blue lighten-1" onclick="viewPdf('<?php echo $profesor['acta_nacimiento']; ?>')"><i class="material-icons">visibility</i></button>
                                <?php endif; ?>
                                <input type="file" id="acta_nacimiento_pdf" name="acta_nacimiento_pdf" accept="application/pdf" class="hide">
                            </div>
                        </div>
                        <div class="input-field">
                            <label for="certificado_medico">Certificado Médico</label>
                            <input type="text" id="certificado_medico" name="certificado_medico" value="<?php echo $profesor['certificado_medico']; ?>" placeholder="Certificado Médico">
                            <div class="pdf-buttons file-icons">
                                <?php if($profesor['certificado_medico']): ?>
                                    <button type="button" class="btn-small red lighten-1" onclick="removeCertificadoMedico()"><i class="material-icons">delete</i></button>
                                    <button type="button" class="btn-small blue lighten-1" onclick="viewPdf('<?php echo $profesor['certificado_medico']; ?>')"><i class="material-icons">visibility</i></button>
                                <?php endif; ?>
                                <input type="file" id="certificado_medico_pdf" name="certificado_medico_pdf" accept="application/pdf" class="hide">
                            </div>
                        </div>
                        <div class="input-field">
                            <label for="username">Nombre de Usuario</label>
                            <input type="text" id="username" name="username" value="<?php echo $profesor['username']; ?>" required>
                        </div>
                        <div class="input-field">
                            <label for="password">Nueva Contraseña</label>
                            <input type="password" id="password" name="password" placeholder="Dejar en blanco para mantener la contraseña actual">
                        </div>
                        <div class="input-field">
                            <i class="material-icons editable-icon" onclick="enableEdit('telefono')">edit</i>
                            <input type="text" id="telefono" name="telefono" value="<?php echo $profesor['telefono']; ?>" required>
                            <label for="telefono">Teléfono</label>
                        </div>
                        <div class="input-field">
                            <i class="material-icons editable-icon" onclick="enableEdit('direccion')">edit</i>
                            <input type="text" id="direccion" name="direccion" value="<?php echo $profesor['direccion']; ?>" required>
                            <label for="direccion">Dirección</label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block waves-effect waves-light">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Structure for Logout -->
    <div id="modalLogout" class="modal">
        <div class="modal-content">
            <h4>Confirmar Cierre de Sesión</h4>
            <p>¿Está seguro de que desea cerrar sesión?</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Cancelar</a>
            <a href="logout_admin.php" class="modal-close waves-effect waves-red btn-flat">Cerrar Sesión</a>
        </div>
    </div>

    <!-- Import jQuery and MaterializeJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var elemsDropdown = document.querySelectorAll('.dropdown-trigger');
            M.Dropdown.init(elemsDropdown, {
                hover: true,
                coverTrigger: false
            });

            var elemsSidenav = document.querySelectorAll('.sidenav');
            M.Sidenav.init(elemsSidenav);

            var elemsModal = document.querySelectorAll('.modal');
            M.Modal.init(elemsModal);

            M.updateTextFields();
        });

        function enableEdit(id) {
            document.getElementById(id).removeAttribute('readonly');
            document.getElementById(id).focus();
        }

        function removeCurpPdf() {
            document.getElementById('curp_pdf').value = "";
            document.getElementById('curp_pdf').classList.remove('hide');
        }

        function removeActaNacimiento() {
            document.getElementById('acta_nacimiento_pdf').value = "";
            document.getElementById('acta_nacimiento_pdf').classList.remove('hide');
        }

        function removeCertificadoMedico() {
            document.getElementById('certificado_medico_pdf').value = "";
            document.getElementById('certificado_medico_pdf').classList.remove('hide');
        }

        function viewPdf(path) {
            window.open(path, '_blank');
        }
    </script>
</body>
</html>
