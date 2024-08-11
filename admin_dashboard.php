<?php
session_start();

// Verificar si el usuario está logueado y si es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Administrador') {
    header('Location: login_admin.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Administrador</title>
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
        nav, .dropdown-content {
            background-color: #f8c291 !important;
        }
        nav a, .dropdown-content a {
            color: #555 !important;
        }
        .brand-logo img {
            max-width: 50px;
            margin-top: 7px;
        }
        .content {
            text-align: center;
            margin-top: 100px;
            animation: fadeIn 2s;
        }
        .content img {
            max-width: 300px;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .modal {
            max-width: 600px;
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
        <li><a href="registro_preescolar.php">Registrar Alumno Preescolar</a></li>
        <li><a href="registro_maternal.php">Registrar Alumno Maternal</a></li>
        <li><a href="registro_profesor.php">Registrar Profesores</a></li>
        <li><a href="expediente_preescolar.php">Ver Alumnos Preescolar</a></li>
        <li><a href="expediente_maternal.php">Ver Alumnos Maternal</a></li>
        <li><a href="expediente_profesores.php">Ver Profesores</a></li>
        <li><a href="#modalLogout" class="modal-trigger">Cerrar sesión</a></li>
    </ul>

    <div class="content">
        <h1 class="display-4">BIENVENIDO</h1>
        <img src="img/welcome-lion.png" alt="Welcome Lion">
    </div>

    <!-- Modal Structure for Logout -->
    <div id="modalLogout" class="modal">
        <div class="modal-content">
            <h4>Confirmar Cierre de Sesión</h4>
            <p>¿Está seguro que desea cerrar sesión?</p>
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
        });
    </script>
</body>
</html>
