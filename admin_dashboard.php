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
            z-index: 1200 !important; /* Asegura que el modal esté sobre el sidenav */
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
        <li><a href="registro_preescolar.php">Preescolar</a></li>
        <li><a href="registro_maternal.php">Maternal</a></li>
        <li><a href="registro_profesor.php">Profesores</a></li>
    </ul>
    <ul id="dropdown2" class="dropdown-content">
        <li><a href="expediente_preescolar.php">Preescolar</a></li>
        <li><a href="expediente_maternal.php">Maternal</a></li>
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
