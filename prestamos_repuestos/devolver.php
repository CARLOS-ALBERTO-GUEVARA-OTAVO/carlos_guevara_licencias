<?php
session_start();
if (!isset($_SESSION['documento_usuario'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devolver Préstamo - Prestación de Herramientas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f0f0f0; }
        .container { max-width: 800px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .container h2 { text-align: center; }
        .message { text-align: center; color: #d9534f; }
        .back { text-align: center; margin-top: 20px; }
        .back a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Devolver Préstamo</h2>
        <div class="message">
            <p>Lo sentimos, la funcionalidad para devolver préstamos no está disponible actualmente.</p>
        </div>
        <div class="back">
            <a href="../dashboard.php">Volver al Dashboard</a>
        </div>
    </div>
</body>
</html>