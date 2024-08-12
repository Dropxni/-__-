<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Conexión a la base de datos
    $conn = new mysqli('localhost', 'root', '', 'EduExpedientes');

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Verificamos las credenciales del usuario
    $sql = "SELECT id, password_hash, tipo FROM Usuarios WHERE username = ? AND activo = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($id, $passwordHash, $tipo);
    $stmt->fetch();

    if ($id && password_verify($password, $passwordHash) && $tipo === 'Personal') {
        // Autenticación exitosa
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        $_SESSION['user_role'] = $tipo; // Añadimos el rol del usuario a la sesión

        // Redireccionar a la página de administración o dashboard
        header('Location: personal_dashboard.php');
        exit();
    } else {
        $error_message = "Nombre de usuario o contraseña incorrectos.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrador</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #F8C291;
            color: #555;
            font-family: 'Arial', sans-serif;
        }
        .login-container {
            margin-top: 100px;
            background-color: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .login-container h2 {
            margin-bottom: 20px;
            font-size: 2em;
            color: #333;
        }
        .login-container img {
            max-width: 120px;
            margin-bottom: 20px;
            border: 3px solid #F8C291;
            border-radius: 50%;
            padding: 5px;
            background-color: #fff;
        }
        .btn-back {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 30px;
            color: #555;
            text-decoration: none;
        }
        .btn-back:hover {
            color: #333;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
        }
        .form-control {
            background-color: #ffe8d6;
            border: none;
            border-radius: 5px;
        }
        .btn-primary {
            background-color: #C2185B;
            border: none;
            border-radius: 5px;
        }
        .btn-primary:hover {
            background-color: #a0164a;
        }
    </style>
</head>
<body>
    <a href="index.php" class="btn-back">&larr;</a>
    <div class="container d-flex justify-content-center">
        <div class="login-container">
            <h2>PERSONAL</h2>
            <img src="img/personal-icon.png" alt="Personal">
            <?php
            if (!empty($errors)) {
                echo "<div class='error-message'>";
                foreach ($errors as $error) {
                    echo "<p>$error</p>";
                }
                echo "</div>";
            }
            ?>
            <form method="post" action="login_personal.php">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Usuario / Correo" name="username" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Contraseña" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Aceptar</button>
            </form>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
