<?php
session_start();
if (!isset($_SESSION['documento_usuario'])) {
    header("Location: index.php");
    exit();
}

$nombre_usuario = $_SESSION['nombre_usuario'] ?? 'Usuario';
$rol = $_SESSION['id_rol'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Prestación de Herramientas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f0f0f0; }
        .container { max-width: 800px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .container h2 { text-align: center; }
        .links { display: flex; flex-direction: column; gap: 10px; margin-top: 20px; }
        .links a { background-color: #007bff; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; text-align: center; }
        .links a:hover { background-color: #0056b3; }
        .logout { text-align: center; margin-top: 20px; }
        .logout a { color: #dc3545; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Bienvenido, <?php echo htmlspecialchars($nombre_usuario); ?></h2>
        <div class="links">
            <a href="prestamos_repuestos/crear.php">Registrar Préstamo</a>
            <a href="prestamos_repuestos/devolver.php">Devolver Préstamo</a>
            <a href="prestamos_repuestos/listar.php">Ver Préstamos</a>
            <?php if (in_array($rol, [1, 2])) { ?>
                <a href="vehiculos/listar.php">Gestionar Vehículos</a>
                <a href="repuestos/listar.php">Gestionar Herramientas/Repuestos</a>
                <a href="reportes.php">Ver Reportes</a>
            <?php } ?>
            <?php if ($rol == 1) { ?>
                <a href="usuarios/listar.php">Gestionar Usuarios</a>
                <a href="empresas/listar.php">Gestionar Empresas</a>
            <?php } ?>
        </div>
        <div class="logout">
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </div>
</body>
</html>