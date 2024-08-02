<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
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
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .card-custom {
            width: calc(25% - 20px);
            margin: 10px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }
        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }
        .card-image-custom {
            height: 200px;
            background-size: cover;
            background-position: center;
        }
        .card-content-custom {
            background-color: white;
            padding: 20px;
            text-align: center;
        }
        .card-content-custom h5 {
            margin: 10px 0;
        }
        .card-content-custom p {
            margin: 0;
            font-size: 1.1em;
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
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px 5px;
        }
        .delete-button {
            background-color: red;
        }
        @media (max-width: 992px) {
            .card-custom {
                width: calc(50% - 20px);
            }
        }
        @media (max-width: 600px) {
            .card-custom {
                width: calc(100% - 20px);
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
        <h2 class="text-center">EXPEDIENTE DE ALUMNOS DE PREESCOLAR</h2>
        <div class="d-flex justify-content-between">
            <input type="text" class="form-control search-bar" id="searchBar" onkeyup="searchStudents()" placeholder="Buscar por nombre...">
            <button class="trash-button" onclick="deleteSelectedStudents()">Eliminar seleccionados</button>
        </div>
        <form id="deleteForm" method="post" action="eliminar_multiples_preescolar.php">
            <div class="card-container" id="studentContainer">
                <?php foreach ($alumnos as $alumno): ?>
                    <div class="card-custom">
                        <div class="card-image-custom" style="background-image: url('<?php echo $alumno['fotografia']; ?>');"></div>
                        <div class="card-content-custom">
                            <h5><?php echo $alumno['nombre'] . ' ' . $alumno['apellido_paterno']; ?></h5>
                            <p>Edad: <?php echo $alumno['edad']; ?></p>
                            <input type="checkbox" class="delete-checkbox" name="delete_ids[]" value="<?php echo $alumno['id']; ?>" style="position: absolute; left: 10px; top: 10px;">
                            <div class="card-action">
                                <button type="button" class="edit-button" onclick="editStudent(<?php echo $alumno['id']; ?>)">Editar</button>
                                <button type="button" class="delete-button" onclick="deleteStudent(<?php echo $alumno['id']; ?>)">Eliminar</button>
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
            <a href="logout.php" class="waves-effect waves-red btn-flat">Cerrar sesión</a>
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
            cards = container.getElementsByClassName("card-custom");

            for (i = 0; i < cards.length; i++) {
                name = cards[i].getElementsByTagName("h5")[0].textContent || cards[i].getElementsByTagName("h5")[0].innerText;
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
