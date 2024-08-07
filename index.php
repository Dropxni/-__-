<?php
session_start();

// Redirigir a los usuarios autenticados a su dashboard correspondiente
if (isset($_SESSION['user_id'])) {
    // Conexión a la base de datos
    $conn = new mysqli('localhost', 'root', '', 'EduExpedientes');

    // Obtener el tipo de usuario
    $sql = "SELECT tipo FROM Usuarios WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $stmt->bind_result($tipo);
        $stmt->fetch();

        if ($tipo === 'Administrador') {
            header('Location: admin_dashboard.php');
            exit();
        } elseif ($tipo === 'Personal') {
            header('Location: personal_dashboard.php');
            exit();
        }

        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guardería "EL REY LEÓN"</title>
    <!-- Import Google Icon Font -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Import Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Import MaterializeCSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
    <style>
        body {
            background: url('img/index.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            font-family: 'Roboto', sans-serif;
        }
        .navbar {
            background-color: rgba(0, 0, 0, 0.7);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            height: 120px; /* Aumentar altura del navbar */
            display: flex;
            align-items: center;
            justify-content: center; /* Centrar contenido horizontalmente */
            padding: 0 20px; /* Añadir padding para el contenido del navbar */
        }
        .brand-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .brand-logo img {
            max-width: 80px; /* Tamaño adecuado del logo */
            vertical-align: middle;
        }
        .hero-content {
            text-align: center;
            margin-top: 150px;
        }
        .hero-content h1 {
            font-size: 3em;
            font-weight: 500;
            animation: fadeIn 2s ease-in-out;
        }
        .card-action a {
            color: #FF6347;
            transition: color 0.3s;
        }
        .card-action a:hover {
            color: #ff4500;
        }
        .card {
            background-color: rgba(255, 255, 255, 0.9); /* Color blanco con transparencia */
            padding: 10px;
            border-radius: 10px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
        .card .card-title {
            color: #FF6347;
        }
        footer {
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            padding: 20px 0;
            text-align: center;
            margin-top: 50px;
        }
        footer p {
            margin: 0;
            font-size: 14px;
        }
        footer .contact-info {
            margin-top: 10px;
        }
        footer .contact-info a {
            color: #fff;
            display: inline-flex;
            align-items: center;
            margin: 0 10px;
            font-size: 16px;
        }
        footer .contact-info a img {
            margin-right: 5px;
            max-width: 24px;
        }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        @media (max-width: 992px) {
            .brand-wrapper {
                justify-content: center; /* Centrar el logo en pantallas medianas y pequeñas */
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="brand-wrapper">
            <a href="#" class="brand-logo">
                <img src="img/logo.png" alt="Logo">
            </a>
        </div>
    </nav>
    <div class="container hero-content">
       <!-- <h1>Guardería "EL REY LEÓN"</h1> --> 
        <div class="row">
            <div class="col s12 m6">
                <div class="card">
                    <div class="card-content">
                        <img src="img/admin-icon.png" alt="Admin" style="width:100px;height:100px;">
                        <span class="card-title">Administrador</span>
                    </div>
                    <div class="card-action">
                        <a href="login_admin.php">Ingresar</a>
                    </div>
                </div>
            </div>
            <div class="col s12 m6">
                <div class="card">
                    <div class="card-content">
                        <img src="img/personal-icon.png" alt="Personal" style="width:100px;height:100px;">
                        <span class="card-title">Personal</span>
                    </div>
                    <div class="card-action">
                        <a href="login_personal.php">Ingresar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <div class="container">
            <p>&copy; 2024 Guardería "EL REY LEÓN". Todos los derechos reservados.</p>
            <div class="contact-info">
                <a href="tel:9511134056"><img src="img/phone-icon.png" alt="Phone">951-113-40-56</a>
                <a href="https://wa.me/9511134056"><img src="img/whatsapp-icon.png" alt="WhatsApp">951-113-40-56</a>
            </div>
            <p>Desarrollado por Tejones Dev</p>
        </div>
    </footer>
    <!-- Import jQuery and MaterializeJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
