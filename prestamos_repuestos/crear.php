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

// Obtener repuestos disponibles
$sql_repuestos = "SELECT codigo_barras, nombre_repuesto FROM repuestos";
$repuestos = $conn->query($sql_repuestos);

// Obtener vehículos disponibles (los que no tienen ningún préstamo)
$sql_vehiculos = "SELECT v.placa_vehiculo, v.marca, v.modelo 
                 FROM vehiculos v 
                 LEFT JOIN prestamos_repuestos pr ON v.placa_vehiculo = pr.placa_vehiculo
                 WHERE pr.placa_vehiculo IS NULL";
$vehiculos = $conn->query($sql_vehiculos);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo_barras_repuesto = $_POST['codigo_repuesto'];
    $placa_vehiculo = $_POST['placa_vehiculo'];
    $fecha_prestamo = $_POST['fecha_prestamo'];
    $cantidad_utilizada = (int)$_POST['cantidad_utilizada'];

    // Insertar el préstamo
    $sql = "INSERT INTO prestamos_repuestos (codigo_barras_repuesto, placa_vehiculo, fecha_prestamo, cantidad_utilizada) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $codigo_barras_repuesto, $placa_vehiculo, $fecha_prestamo, $cantidad_utilizada);
    $stmt->execute();

    header("Location: listar.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Préstamo - Prestación de Herramientas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f0f0f0; }
        .container { max-width: 800px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .container h2 { text-align: center; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input, .form-group select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .form-group button { background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .form-group button:hover { background-color: #0056b3; }
        .back { text-align: center; margin-top: 20px; }
        .back a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registrar Préstamo</h2>
        <form action="crear.php" method="POST">
            <div class="form-group">
                <label for="codigo_repuesto">Repuesto:</label>
                <select name="codigo_repuesto" id="codigo_repuesto" required>
                    <option value="">Seleccione un repuesto</option>
                    <?php while ($repuesto = $repuestos->fetch_assoc()) { ?>
                        <option value="<?php echo $repuesto['codigo_barras']; ?>">
                            <?php echo $repuesto['nombre_repuesto']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="placa_vehiculo">Vehículo:</label>
                <select name="placa_vehiculo" id="placa_vehiculo" required>
                    <option value="">Seleccione un vehículo</option>
                    <?php while ($vehiculo = $vehiculos->fetch_assoc()) { ?>
                        <option value="<?php echo $vehiculo['placa_vehiculo']; ?>">
                            <?php echo $vehiculo['placa_vehiculo'] . ' (' . $vehiculo['marca'] . ' ' . $vehiculo['modelo'] . ')'; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="fecha_prestamo">Fecha de Préstamo:</label>
                <input type="date" name="fecha_prestamo" id="fecha_prestamo" required>
            </div>
            <div class="form-group">
                <label for="cantidad_utilizada">Cantidad Utilizada:</label>
                <input type="number" name="cantidad_utilizada" id="cantidad_utilizada" required>
            </div>
            <div class="form-group">
                <button type="submit">Registrar Préstamo</button>
            </div>
        </form>
        <div class="back">
            <a href="../dashboard.php">Volver al Dashboard</a>
        </div>
    </div>
</body>
</html>