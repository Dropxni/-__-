<?php
session_start();

// Verificar si el usuario está logueado y si es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Administrador') {
    header('Location: login_admin.php');
    exit();
}

require_once 'config.php';

$conn = db_connect();

// Obtener alumnos de preescolar
$sql = "SELECT id, nombre, apellido_paterno, apellido_materno, edad, fotografia FROM Preescolar WHERE activo = 1";
$result = $conn->query($sql);

$alumnos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $alumnos[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expediente de Alumnos Preescolar</title>
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

        .card-custom {
            background-color: #ffccbc;
            border-radius: 10px;
            transition: transform 0.3s ease-in-out;
            margin: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-custom:hover {
            transform: translateY(-5px);
        }

        .card-image-custom {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .card-content-custom {
            padding: 10px;
            text-align: center;
            position: relative;
        }

        .card-content-custom p {
            margin: 5px 0;
        }

        .search-bar {
            margin-bottom: 20px;
            background-color: #ffe8d6;
            padding: 10px;
            border-radius: 5px;
        }

        .trash-button {
            background-color: red;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }

        .edit-button, .delete-button {
            background-color: #C2185B;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 5px;
        }

        .delete-button {
            background-color: red;
        }

        .edit-button i, .delete-button i {
            vertical-align: middle;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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

        .modal {
            max-width: 600px;
            z-index: 1200 !important; /* Asegura que el modal esté sobre el sidenav */
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
        <li><a href="registro_preescolar.php"><i class="material-icons">person_add</i>Preescolar</a></li>
        <li><a href="registro_maternal.php"><i class="material-icons">person_add</i>Maternal</a></li>
        <li><a href="registro_profesor.php"><i class="material-icons">person_add</i>Profesores</a></li>
        <li><a href="expediente_preescolar.php"><i class="material-icons">folder_open</i>Preescolar</a></li>
        <li><a href="expediente_maternal.php"><i class="material-icons">folder_open</i>Maternal</a></li>
        <li><a href="expediente_profesores.php"><i class="material-icons">folder_open</i>Profesores</a></li>
        <li><a href="#logoutModal" class="modal-trigger"><i class="material-icons">exit_to_app</i>Cerrar sesión</a></li>
    </ul>

    <div class="container content">
        <h2 class="text-center">EXPEDIENTE DE ALUMNOS DE PREESCOLAR</h2>
        <div class="row">
            <div class="col s12 m10 l8 offset-m1 offset-l2">
                <div class="input-field">
                    <i class="material-icons prefix">search</i>
                    <input type="text" id="searchBar" class="search-bar" onkeyup="searchStudents()" placeholder="Buscar por nombre...">
                </div>
                <button class="trash-button" onclick="deleteSelectedStudents()"><i class="material-icons">delete</i></button>
            </div>
        </div>
        <form id="deleteForm" method="post" action="eliminar_multiples_preescolar.php">
            <div class="row" id="studentContainer">
                <?php foreach ($alumnos as $alumno): ?>
                    <div class="col s12 m6 l3">
                        <div class="card-custom">
                            <img src="<?php echo $alumno['fotografia']; ?>" alt="Student Photo" class="card-image-custom">
                            <div class="card-content-custom">
                                <input type="checkbox" class="delete-checkbox" name="delete_ids[]" value="<?php echo $alumno['id']; ?>" style="position: absolute; left: 10px; top: 10px;">
                                <p>Nombre: <?php echo $alumno['nombre'] . ' ' . $alumno['apellido_paterno'] . ' ' . $alumno['apellido_materno']; ?></p>
                                <p>Edad: <?php echo $alumno['edad']; ?></p>
                                <button type="button" class="edit-button" onclick="editStudent(<?php echo $alumno['id']; ?>)"><i class="material-icons">edit</i></button>
                                <button type="button" class="delete-button" onclick="deleteStudent(<?php echo $alumno['id']; ?>)"><i class="material-icons">delete</i></button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </form>
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

        function searchStudents() {
            var input, filter, container, cards, name, i;
            input = document.getElementById("searchBar");
            filter = input.value.toLowerCase();
            container = document.getElementById("studentContainer");
            cards = container.getElementsByClassName("col");

            for (i = 0; i < cards.length; i++) {
                name = cards[i].getElementsByClassName("card-content-custom")[0].getElementsByTagName("p")[0].textContent || cards[i].getElementsByClassName("card-content-custom")[0].getElementsByTagName("p")[0].innerText;
                if (name.toLowerCase().indexOf(filter) > -1) {
                    cards[i].style.display = "";
                } else {
                    cards[i].style.display = "none";
                }
            }
        }

        function editStudent(id) {
            window.location.href = 'editar_preescolar.php?id=' + id;
        }

        function deleteStudent(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este alumno?')) {
                window.location.href = 'eliminar_preescolar.php?id=' + id;
            }
        }

        function deleteSelectedStudents() {
            if (confirm('¿Estás seguro de que deseas eliminar los alumnos seleccionados?')) {
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
</body>
</html>
