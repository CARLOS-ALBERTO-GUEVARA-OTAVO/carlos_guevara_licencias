<?php
session_start();
if (!isset($_SESSION['documento_usuario'])) {
    header("Location: ../index.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "taller");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$is_super_admin = $_SESSION['id_rol'] == 1;

if ($_SESSION['id_rol'] == 3) {
    $sql = "SELECT pr.id_prestamo, pr.codigo_barras_repuesto, pr.placa_vehiculo, pr.fecha_prestamo, pr.cantidad_utilizada, 
                   r.nombre_repuesto as repuesto, v.marca, v.modelo 
            FROM prestamos_repuestos pr 
            LEFT JOIN repuestos r ON pr.codigo_barras_repuesto = r.codigo_barras 
            LEFT JOIN vehiculos v ON pr.placa_vehiculo = v.placa_vehiculo 
            WHERE v.documento_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['documento_usuario']);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT pr.id_prestamo, pr.codigo_barras_repuesto, pr.placa_vehiculo, pr.fecha_prestamo, pr.cantidad_utilizada, 
                   r.nombre_repuesto as repuesto, v.marca, v.modelo 
            FROM prestamos_repuestos pr 
            LEFT JOIN repuestos r ON pr.codigo_barras_repuesto = r.codigo_barras 
            LEFT JOIN vehiculos v ON pr.placa_vehiculo = v.placa_vehiculo";
    $result = $conn->query($sql);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Préstamos - Prestación de Herramientas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f0f0f0; }
        .container { max-width: 1000px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .container h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 10px; text-align: left; }
        th { background-color: #007bff; color: white; }
        .back { text-align: center; margin-top: 20px; }
        .back a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Lista de Préstamos</h2>
        <table>
            <tr>
                <th>Repuesto</th>
                <th>Vehículo</th>
                <th>Fecha de Préstamo</th>
                <th>Cantidad Utilizada</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['repuesto'] . ' (' . $row['codigo_barras_repuesto'] . ')'; ?></td>
                    <td><?php echo $row['placa_vehiculo'] . ' (' . $row['marca'] . ' ' . $row['modelo'] . ')'; ?></td>
                    <td><?php echo $row['fecha_prestamo']; ?></td>
                    <td><?php echo $row['cantidad_utilizada']; ?></td>
                </tr>
            <?php } ?>
        </table>
        <div class="back">
            <a href="../dashboard.php">Volver al Dashboard</a>
        </div>
    </div>
</body>
</html>