<?php
session_start();
if (!isset($_SESSION['documento_usuario']) || !in_array($_SESSION['id_rol'], [1, 2])) {
    header("Location: ../index.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "taller");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$repuesto = null;
$vehiculos = [];
$error = "";
$message = "";

// Fetch all vehicles for the dropdown
$sql_vehiculos = "SELECT placa_vehiculo, marca, modelo FROM vehiculos";
$result_vehiculos = $conn->query($sql_vehiculos);
while ($row = $result_vehiculos->fetch_assoc()) {
    $vehiculos[] = $row;
}

// Handle barcode scan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo_barras'])) {
    $codigo_barras = $_POST['codigo_barras'];

    // Look up the repuesto
    $sql = "SELECT codigo_barras, nombre_repuesto, cantidad FROM repuestos WHERE codigo_barras = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $codigo_barras);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $repuesto = $result->fetch_assoc();
    } else {
        $error = "Repuesto no encontrado con el código de barras: " . htmlspecialchars($codigo_barras);
    }
    $stmt->close();
}

// Handle loan creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_prestamo'])) {
    $codigo_barras = $_POST['codigo_barras'];
    $placa_vehiculo = $_POST['placa_vehiculo'];
    $cantidad_utilizada = $_POST['cantidad_utilizada'];
    $fecha_prestamo = date('Y-m-d H:i:s'); // Current date and time

    // Check if repuesto has enough stock
    $sql = "SELECT cantidad FROM taller_repuestos WHERE codigo_barras = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $codigo_barras);
    $stmt->execute();
    $result = $stmt->get_result();
    $repuesto = $result->fetch_assoc();
    $stmt->close();

    if ($repuesto['cantidad'] < $cantidad_utilizada) {
        $error = "No hay suficiente stock. Cantidad disponible: " . $repuesto['cantidad'];
    } else {
        // Create loan record
        $sql = "INSERT INTO prestamos_repuestos (codigo_barras_repuesto, placa_vehiculo, fecha_prestamo, cantidad_utilizada) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $codigo_barras, $placa_vehiculo, $fecha_prestamo, $cantidad_utilizada);
        
        if ($stmt->execute()) {
            // Update stock in taller_repuestos
            $sql_update = "UPDATE repuestos SET cantidad = cantidad - ? WHERE codigo_barras = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("is", $cantidad_utilizada, $codigo_barras);
            $stmt_update->execute();
            $stmt_update->close();

            $message = "Préstamo registrado exitosamente.";
        } else {
            $error = "Error al registrar el préstamo: " . $conn->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escanear Repuesto - Prestación de Herramientas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f0f0f0; }
        .container { max-width: 800px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .container h2 { text-align: center; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input, .form-group select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .form-group button { background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .form-group button:hover { background-color: #0056b3; }
        .error { color: red; text-align: center; }
        .message { color: green; text-align: center; }
        .back { text-align: center; margin-top: 20px; }
        .back a { color: #007bff; text-decoration: none; }
    </style>
    <script>
        // Auto-focus the barcode input field on page load
        window.onload = function() {
            document.getElementById('codigo_barras').focus();
        };
    </script>
</head>
<body>
    <div class="container">
        <h2>Escanear Repuesto para Préstamo</h2>
        <?php if ($error) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>
        <?php if ($message) { ?>
            <p class="message"><?php echo $message; ?></p>
        <?php } ?>

        <!-- Form to scan barcode -->
        <form method="POST" action="scan.php">
            <div class="form-group">
                <label for="codigo_barras">Escanear Código de Barras:</label>
                <input type="text" name="codigo_barras" id="codigo_barras" maxlength="30" required>
            </div>
            <div class="form-group">
                <button type="submit">Buscar Repuesto</button>
            </div>
        </form>

        <!-- Form to create loan if repuesto is found -->
        <?php if ($repuesto) { ?>
            <h3>Repuesto Encontrado</h3>
            <p><strong>Código:</strong> <?php echo htmlspecialchars($repuesto['codigo_barras']); ?></p>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($repuesto['nombre_repuesto']); ?></p>
            <p><strong>Cantidad Disponible:</strong> <?php echo $repuesto['cantidad']; ?></p>

            <form method="POST" action="scan.php">
                <input type="hidden" name="codigo_barras" value="<?php echo htmlspecialchars($repuesto['codigo_barras']); ?>">
                <div class="form-group">
                    <label for="placa_vehiculo">Vehículo:</label>
                    <select name="placa_vehiculo" id="placa_vehiculo" required>
                        <option value="">Seleccione un vehículo</option>
                        <?php foreach ($vehiculos as $vehiculo) { ?>
                            <option value="<?php echo htmlspecialchars($vehiculo['placa_vehiculo']); ?>">
                                <?php echo htmlspecialchars($vehiculo['placa_vehiculo'] . ' - ' . $vehiculo['marca'] . ' ' . $vehiculo['modelo']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cantidad_utilizada">Cantidad a Prestar:</label>
                    <input type="number" name="cantidad_utilizada" id="cantidad_utilizada" min="1" max="<?php echo $repuesto['cantidad']; ?>" required>
                </div>
                <div class="form-group">
                    <button type="submit" name="crear_prestamo">Registrar Préstamo</button>
                </div>
            </form>
        <?php } ?>

        <div class="back">
            <a href="listar.php">Volver a la Lista</a>
        </div>
    </div>
</body>
</html>