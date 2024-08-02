<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login_personal.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - Personal</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #FFFF;
            color: #555;
        }
        .navbar {
            background-color: #F8C291;
        }
        .navbar-brand img {
            max-width: 50px;
        }
        .navbar-nav .nav-link {
            color: #555 !important;
        }
        .content {
            text-align: center;
            margin-top: 100px;
        }
        .content img {
            max-width: 300px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#"><img src="img/logo.png" alt="Logo"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="expedienteDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Expediente
                    </a>
                    <div class="dropdown-menu" aria-labelledby="expedienteDropdown">
                        <a class="dropdown-item" href="expediente_preescolar.php">Alumnos preescolar</a>
                        <a class="dropdown-item" href="expediente_maternal.php">Alumnos maternal</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout_personal.php">Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="content">
        <h1 class="display-4">BIENVENIDO</h1>
        <img src="img/welcome-lion.png" alt="Welcome Lion">
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
