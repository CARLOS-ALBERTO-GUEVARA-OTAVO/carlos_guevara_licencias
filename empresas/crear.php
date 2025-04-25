<?php
session_start();
if (!isset($_SESSION['documento']) || $_SESSION['id_rol'] != 1) {
    header("Location: ../index.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "taller");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    $sql = "INSERT INTO empresa (nombre, correo, telefono, direccion) 
            VALUES ('$nombre', '$correo', '$telefono', '$direccion')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: listar.php");
        exit();
    } else {
        $error = "Error al registrar empresa: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Empresa - Prestación de Herramientas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f0f0f0; }
        .container { max-width: 600px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .container h2 { text-align: center; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .form-group button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .form-group button:hover { background-color: #0056b3; }
        .error { color: red; text-align: center; }
        .back { text-align: center; margin-top: 10px; }
        .back a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registrar Nueva Empresa</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" name="correo" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" required>
            </div>
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" name="direccion" required>
            </div>
            <div class="form-group">
                <button type="submit">Registrar</button>
            </div>
        </form>
        <div class="back">
            <a href="listar.php">Volver a la lista</a>
        </div>
    </div>
</body>
</html>