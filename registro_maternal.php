<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login_admin.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Alumno de Maternal</title>
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
        }
        .navbar, .dropdown-content {
            background-color: #f8c291 !important;
        }
        .navbar a, .dropdown-content a {
            color: #555 !important;
        }
        .brand-logo img {
            max-width: 50px;
        }
        .content {
            margin-top: 50px;
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
        .input-field input[type=text], .input-field input[type=number] {
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
        .file-field .btn {
            background-color: #C2185B !important;
            margin-left: 10px;
        }
        .file-field .btn:hover {
            background-color: #a71d44 !important;
        }
        .file-field .file-path-wrapper {
            display: flex;
            align-items: center;
        }
        .file-field .file-path-wrapper input[type=text] {
            background-color: #ffe8d6;
            flex-grow: 1;
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
        <div class="nav-wrapper navbar">
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
                    <a href="#logoutModal" class="modal-trigger">Cerrar sesión</a>
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
        <li><a href="registro_preescolar.php">Alumnos preescolar</a></li>
        <li><a href="registro_maternal.php">Alumnos maternal</a></li>
        <li><a href="registro_profesor.php">Profesores</a></li>
        <li><a href="expediente_preescolar.php">Alumnos preescolar</a></li>
        <li><a href="expediente_maternal.php">Alumnos maternal</a></li>
        <li><a href="expediente_profesores.php">Profesores</a></li>
        <li><a href="#logoutModal" class="modal-trigger">Cerrar sesión</a></li>
    </ul>

    <div class="container content">
        <div class="row">
            <div class="col s12">
                <div class="form-container">
                    <div class="header">
                        <h2 class="text-center">REGISTRAR ALUMNO DE MATERNAL</h2>
                    </div>
                    <form id="registroForm" method="post" action="guardar_maternal.php" enctype="multipart/form-data">
                        <div class="input-field">
                            <input type="text" id="nombre" name="nombre" required>
                            <label for="nombre">Nombre</label>
                        </div>
                        <div class="input-field">
                            <input type="text" id="apellido_paterno" name="apellido_paterno" required>
                            <label for="apellido_paterno">Apellido paterno</label>
                        </div>
                        <div class="input-field">
                            <input type="text" id="apellido_materno" name="apellido_materno">
                            <label for="apellido_materno">Apellido materno</label>
                        </div>
                        <div class="input-field">
                            <input type="number" id="edad" name="edad" required>
                            <label for="edad">Edad</label>
                        </div>
                        <div class="file-field input-field">
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text" placeholder="Añadir CURP">
                                <div class="btn">
                                    <span><i class="material-icons">attach_file</i></span>
                                    <input type="file" id="curp_pdf" name="curp_pdf" accept="application/pdf">
                                </div>
                            </div>
                        </div>
                        <div class="file-field input-field">
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text" placeholder="Añadir certificado médico">
                                <div class="btn">
                                    <span><i class="material-icons">attach_file</i></span>
                                    <input type="file" id="certificado_medico" name="certificado_medico" accept="application/pdf">
                                </div>
                            </div>
                        </div>
                        <div class="file-field input-field">
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text" placeholder="Añadir fotografía">
                                <div class="btn">
                                    <span><i class="material-icons">attach_file</i></span>
                                    <input type="file" id="fotografia" name="fotografia" accept="image/*">
                                </div>
                            </div>
                        </div>
                        <h4 class="text-center">Información del tutor</h4>
                        <div class="input-field">
                            <input type="text" id="nombre_tutor" name="nombre_tutor" required>
                            <label for="nombre_tutor">Nombre del tutor</label>
                        </div>
                        <div class="input-field">
                            <input type="text" id="apellido_paterno_tutor" name="apellido_paterno_tutor" required>
                            <label for="apellido_paterno_tutor">Apellido paterno del tutor</label>
                        </div>
                        <div class="input-field">
                            <input type="text" id="apellido_materno_tutor" name="apellido_materno_tutor">
                            <label for="apellido_materno_tutor">Apellido materno del tutor</label>
                        </div>
                        <div class="file-field input-field">
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text" placeholder="Añadir CURP del tutor">
                                <div class="btn">
                                    <span><i class="material-icons">attach_file</i></span>
                                    <input type="file" id="curp_tutor_pdf" name="curp_tutor_pdf" accept="application/pdf">
                                </div>
                            </div>
                        </div>
                        <div class="input-field">
                            <input type="text" id="telefono_tutor" name="telefono_tutor" required>
                            <label for="telefono_tutor">Número de teléfono del tutor</label>
                        </div>
                        <div class="input-field">
                            <input type="text" id="direccion" name="direccion" required>
                            <label for="direccion">Dirección</label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block waves-effect waves-light">Registrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Structure for Success -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <h4>Registro exitoso</h4>
            <p>¿Desea realizar otro registro o regresar al panel de administrador?</p>
        </div>
        <div class="modal-footer">
            <a href="registro_maternal.php" class="modal-close waves-effect waves-green btn-flat">Nuevo Registro</a>
            <a href="admin_dashboard.php" class="modal-close waves-effect waves-green btn-flat">Ir al Dashboard</a>
        </div>
    </div>

    <!-- Modal Structure for Logout -->
    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <h4>Cerrar sesión</h4>
            <p>¿Está seguro de que desea cerrar sesión?</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Cancelar</a>
            <a href="logout_admin.php" class="waves-effect waves-red btn-flat">Cerrar sesión</a>
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

        document.getElementById('registroForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var form = this;
            $.ajax({
                type: form.method,
                url: form.action,
                data: new FormData(form),
                contentType: false,
                processData: false,
                success: function() {
                    var instance = M.Modal.getInstance(document.getElementById('successModal'));
                    instance.open();
                },
                error: function() {
                    alert('Hubo un error al guardar el registro.');
                }
            });
        });
    </script>
</body>
</html>
