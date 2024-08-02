<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);
    $errors = [];

    // Validación del lado del servidor
    if (empty($username)) {
        $errors[] = 'El nombre de usuario es obligatorio.';
    }

    if (empty($password)) {
        $errors[] = 'La contraseña es obligatoria.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Las contraseñas no coinciden.';
    }

    if (empty($errors)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $conn = db_connect();

        // Verificar si el usuario ya existe
        $sql = "SELECT id FROM Usuarios WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $errors[] = 'El nombre de usuario ya existe.';
            } else {
                // Insertar el nuevo usuario
                $sql = "INSERT INTO Usuarios (username, password_hash, tipo, activo) VALUES (?, ?, 'Administrador', 1)";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param('ss', $username, $passwordHash);
                    if ($stmt->execute()) {
                        echo "Usuario registrado con éxito.";
                    } else {
                        $errors[] = "Error: " . $conn->error;
                    }
                }
            }

            $stmt->close();
        } else {
            $errors[] = "Error de preparación de consulta: " . $conn->error;
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario Administrador</title>
    <style>
        /* Aquí va el estilo del formulario */
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registro de Administrador</h2>
        <?php
        if (!empty($errors)) {
            echo "<div class='error-message'>";
            foreach ($errors as $error) {
                echo "<p>$error</p>";
            }
            echo "</div>";
        }
        ?>
        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="username">Nombre de Usuario</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirmar Contraseña</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>
            </div>
            <div class="form-group">
                <button type="submit">Registrar</button>
            </div>
        </form>
    </div>
</body>
</html>
