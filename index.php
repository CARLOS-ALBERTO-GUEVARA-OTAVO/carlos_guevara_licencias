<?php

session_start();
ob_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Prestación de Herramientas</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: #f0f0f0; }
        .login-container { background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .login-container h2 { text-align: center; }
        .login-container input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; }
        .login-container button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .login-container button:hover { background-color: #0056b3; }
        .error { color: red; text-align: center; }
        .debug { color: green; text-align: center; }
    </style>
</head>
<body onload="form_login.documento.focus()">
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <form action="index.php" method="POST" name="form_login">
            <input type="text" name="documento" placeholder="Documento" required tabindex="1">
            <input type="password" name="contrasena" placeholder="Contraseña" required tabindex="2">
            <button type="submit" name="login">Iniciar Sesión</button>
        </form>
        <?php
        if (isset($_POST['login'])) {
            $documento = $_POST['documento'];
            $contrasena = $_POST['contrasena'];

            $conn = new mysqli("localhost", "root", "", "taller");
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            $table_check = $conn->query("SHOW TABLES LIKE 'usuarios'");
            if ($table_check->num_rows == 0) {
                echo "<p class='error'>Error: La tabla 'usuarios' no existe en la base de datos.</p>";
                $conn->close();
                exit();
            }

            $sql = "SELECT * FROM usuarios WHERE documento = ? AND contrasena = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $documento, $contrasena);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                session_start();
                $_SESSION['documento_usuario'] = $user['documento']; // Cambiado de id_usuario a documento_usuario
                $_SESSION['id_rol'] = $user['id_rol'];
                $_SESSION['id_empresa'] = $user['id_empresa'];
                header("Location: dashboard.php");
                exit();
            } else {
                echo "<p class='error'>Documento o contraseña incorrectos</p>";
            }

            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
</body>
</html>

<?php
ob_end_flush();
?>