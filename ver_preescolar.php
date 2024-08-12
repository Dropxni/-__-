<?php
session_start();

// Verificar si el usuario está logueado y si es personal
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Personal') {
    header('Location: login_personal.php');
    exit();
}

require_once 'config.php';

$id = $_GET['id'];
$conn = db_connect();
$sql = "SELECT * FROM Preescolar WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$alumno = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Alumno de Preescolar</title>
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
            max-width: 800px;
            margin: 0 auto;
        }

        .input-field input[type=text]:not(.file-input), 
        .input-field input[type=number]:not(.file-input) {
            background-color: #ffe8d6;
            pointer-events: none; /* Evita que se pueda editar */
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
        }

        .preview-image img {
            max-width: 100px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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

        .header h2 {
            font-size: 2rem;
            text-align: center;
        }

        @media (max-width: 768px) {
            .form-container {
                margin: 0 15px;
            }
        }

        @media (min-width: 992px) {
            .form-container {
                max-width: 800px;
                margin: 0 auto;
            }
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

        @media (min-width: 992px) {
            .sidenav {
                display: none; /* Esconde el sidenav en pantallas grandes */
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Modal */
        .modal {
            z-index: 1200 !important; /* Asegura que el modal esté sobre el sidenav */
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-wrapper">
            <a href="personal_dashboard.php" class="brand-logo"><img src="img/logo.png" alt="Logo"></a>
            <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down">
                <li>
                    <a class="dropdown-trigger" href="#!" data-target="dropdown2">Expediente<i class="material-icons right">arrow_drop_down</i></a>
                </li>
                <li>
                    <a href="#modalLogout" class="modal-trigger">Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </nav>

    <ul id="dropdown2" class="dropdown-content">
        <li><a href="expediente_preescolar_p.php">Preescolar</a></li>
        <li><a href="expediente_maternal_p.php">Maternal</a></li>
    </ul>

    <ul class="sidenav" id="mobile-demo">
        <li><a href="expediente_preescolar_p.php"><i class="material-icons">folder_open</i>Preescolar</a></li>
        <li><a href="expediente_maternal_p.php"><i class="material-icons">folder_open</i>Maternal</a></li>
        <li><a href="#modalLogout" class="modal-trigger"><i class="material-icons">exit_to_app</i>Cerrar sesión</a></li>
    </ul>

    <div class="container content">
        <div class="row">
            <div class="col s12">
                <div class="form-container">
                    <div class="header">
                        <h2 class="text-center">ALUMNO DE PREESCOLAR</h2>
                    </div>
                    <div class="preview-image">
                        <?php if($alumno['fotografia']): ?>
                            <img src="<?php echo $alumno['fotografia']; ?>" alt="Fotografía del alumno">
                        <?php endif; ?>
                    </div>
                    <form id="verForm">
                        <div class="input-field">
                            <input type="text" id="nombre" name="nombre" value="<?php echo $alumno['nombre']; ?>" readonly>
                            <label for="nombre">Nombre</label>
                        </div>
                        <div class="input-field">
                            <input type="text" id="apellido_paterno" name="apellido_paterno" value="<?php echo $alumno['apellido_paterno']; ?>" readonly>
                            <label for="apellido_paterno">Apellido paterno</label>
                        </div>
                        <div class="input-field">
                            <input type="text" id="apellido_materno" name="apellido_materno" value="<?php echo $alumno['apellido_materno']; ?>" readonly>
                            <label for="apellido_materno">Apellido materno</label>
                        </div>
                        <div class="input-field">
                            <input type="number" id="edad" name="edad" value="<?php echo $alumno['edad']; ?>" readonly>
                            <label for="edad">Edad</label>
                        </div>
                        <div class="input-field">
                            <label for="curp">CURP</label>
                            <input type="text" id="curp" name="curp" value="<?php echo $alumno['curp_documento']; ?>" readonly>
                            <div class="pdf-buttons file-icons">
                                <?php if($alumno['curp_documento']): ?>
                                    <button type="button" class="btn-small blue lighten-1" onclick="viewPdf('<?php echo $alumno['curp_documento']; ?>')"><i class="material-icons">visibility</i></button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="input-field">
                            <label for="certificado_medico">Certificado Médico</label>
                            <input type="text" id="certificado_medico" name="certificado_medico" value="<?php echo $alumno['certificado_medico']; ?>" readonly>
                            <div class="pdf-buttons file-icons">
                                <?php if($alumno['certificado_medico']): ?>
                                    <button type="button" class="btn-small blue lighten-1" onclick="viewPdf('<?php echo $alumno['certificado_medico']; ?>')"><i class="material-icons">visibility</i></button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <h4 class="text-center">Información del tutor</h4>
                        <div class="input-field">
                            <input type="text" id="nombre_tutor" name="nombre_tutor" value="<?php echo $alumno['tutor_nombre']; ?>" readonly>
                            <label for="nombre_tutor">Nombre del tutor</label>
                        </div>
                        <div class="input-field">
                            <input type="text" id="apellido_paterno_tutor" name="apellido_paterno_tutor" value="<?php echo $alumno['tutor_apellido_paterno']; ?>" readonly>
                            <label for="apellido_paterno_tutor">Apellido paterno del tutor</label>
                        </div>
                        <div class="input-field">
                            <input type="text" id="apellido_materno_tutor" name="apellido_materno_tutor" value="<?php echo $alumno['tutor_apellido_materno']; ?>" readonly>
                            <label for="apellido_materno_tutor">Apellido materno del tutor</label>
                        </div>
                        <div class="input-field">
                            <label for="curp_tutor">CURP del tutor</label>
                            <input type="text" id="curp_tutor" name="curp_tutor" value="<?php echo $alumno['tutor_curp']; ?>" readonly>
                            <div class="pdf-buttons file-icons">
                                <?php if($alumno['tutor_curp']): ?>
                                    <button type="button" class="btn-small blue lighten-1" onclick="viewPdf('<?php echo $alumno['tutor_curp']; ?>')"><i class="material-icons">visibility</i></button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="input-field">
                            <input type="text" id="telefono_tutor" name="telefono_tutor" value="<?php echo $alumno['tutor_telefono']; ?>" readonly>
                            <label for="telefono_tutor">Número de teléfono del tutor</label>
                        </div>
                        <div class="input-field">
                            <input type="text" id="direccion" name="direccion" value="<?php echo $alumno['direccion']; ?>" readonly>
                            <label for="direccion">Dirección</label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Structure for Logout -->
    <div id="modalLogout" class="modal">
        <div class="modal-content">
            <h4>Confirmar Cierre de Sesión</h4>
            <p>¿Está seguro que desea cerrar sesión?</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Cancelar</a>
            <a href="logout_personal.php" class="modal-close waves-effect waves-red btn-flat">Cerrar Sesión</a>
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

        function viewPdf(path) {
            window.open(path, '_blank');
        }
    </script>
</body>
</html>
