<?php
session_start();
if (!isset($_SESSION['documento_usuario'])) {
    header("Location: ../index.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "taller"); // Cambiado a "taller"
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$is_super_admin = $_SESSION['id_rol'] == 1;

// Si es un usuario regular rol 3 solo puede ver los vehículos asociados a su documento_usuario
if ($_SESSION['id_rol'] == 3) {
    $sql = "SELECT v.placa_vehiculo, v.documento_usuario, v.marca, v.modelo, v.anio, u.nombre as usuario 
            FROM vehiculos v 
            LEFT JOIN usuarios u ON v.documento_usuario = u.documento 
            WHERE v.documento_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['documento_usuario']);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Para Super Admin (rol 1) y Admin (rol 2), mostramos todos los vehículos
    $sql = "SELECT v.placa_vehiculo, v.documento_usuario, v.marca, v.modelo, v.anio, u.nombre as usuario 
            FROM vehiculos v 
            LEFT JOIN usuarios u ON v.documento_usuario = u.documento";
    $result = $conn->query($sql);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Vehículos - Prestación de Herramientas</title>
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
        <h2>Lista de Vehículos</h2>
        <div class="new">
            <a href="crear.php">Crear Nuevo Vehículo</a>
        </div>
        <table>
            <tr>
                <th>Placa</th>
                <th>Usuario Responsable</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Año</th>
                <th>Acciones</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['placa_vehiculo']; ?></td>
                    <td><?php echo $row['usuario'] . ' (' . $row['documento_usuario'] . ')'; ?></td>
                    <td><?php echo $row['marca']; ?></td>
                    <td><?php echo $row['modelo']; ?></td>
                    <td><?php echo $row['anio']; ?></td>
                    <td class="actions">
                        <a href="editar.php?placa_vehiculo=<?php echo $row['placa_vehiculo']; ?>">Editar</a>
                        <a href="eliminar.php?placa_vehiculo=<?php echo $row['placa_vehiculo']; ?>" class="delete" onclick="return confirm('¿Estás seguro de eliminar este vehículo?')">Eliminar</a>
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