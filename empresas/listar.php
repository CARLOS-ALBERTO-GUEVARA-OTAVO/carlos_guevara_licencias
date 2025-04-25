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

$sql = "SELECT id, nombre, correo, telefono, direccion FROM empresa";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Empresas - Prestación de Herramientas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f0f0f0; }
        .container { max-width: 1000px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .container h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 10px; text-align: left; }
        th { background-color: #007bff; color: white; }
        .actions a { margin-right: 10px; color: #007bff; text-decoration: none; }
        .actions a.delete { color: #dc3545; }
        .new { text-align: center; margin-bottom: 20px; }
        .new a { background-color: #007bff; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; }
        .new a:hover { background-color: #0056b3; }
        .back { text-align: center; margin-top: 20px; }
        .back a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Lista de Empresas</h2>
        <div class="new">
            <a href="crear.php">Crear Nueva Empresa</a>
        </div>
        <table>
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Acciones</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['correo']; ?></td>
                    <td><?php echo $row['telefono']; ?></td>
                    <td><?php echo $row['direccion']; ?></td>
                    <td class="actions">
                        <a href="editar.php?id=<?php echo $row['id']; ?>">Editar</a>
                        <a href="eliminar.php?id=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('¿Estás seguro de eliminar esta empresa?')">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <div class="back">
            <a href="../dashboard.php">Volver al Dashboard</a>
        </div>
    </div>
</body>
</html>