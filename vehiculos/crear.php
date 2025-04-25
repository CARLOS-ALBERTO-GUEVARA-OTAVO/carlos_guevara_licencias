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

// Obtener usuarios para el campo documento_usuario
$sql_usuarios = "SELECT documento_usuario, nombre FROM usuarios";
$usuarios = $conn->query($sql_usuarios);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $placa_vehiculo = $_POST['placa_vehiculo'];
    $documento_usuario = $_POST['documento_usuario'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo']; // Ahora es VARCHAR, no INT
    $anio = (int)$_POST['anio'];

    // Insertar el vehículo
    $sql = "INSERT INTO vehiculos (placa_vehiculo, documento_usuario, marca, modelo, anio) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $placa_vehiculo, $documento_usuario, $marca, $modelo, $anio);
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
    <title>Crear Vehículo - Prestación de Herramientas</title>
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
        <h2>Crear Nuevo Vehículo</h2>
        <form action="crear.php" method="POST">
            <div class="form-group">
                <label for="placa_vehiculo">Placa del Vehículo:</label>
                <input type="text" name="placa_vehiculo" id="placa_vehiculo" required>
            </div>
            <div class="form-group">
                <label for="documento_usuario">Usuario Responsable:</label>
                <select name="documento_usuario" id="documento_usuario" required>
                    <option value="">Seleccione un usuario</option>
                    <?php while ($usuario = $usuarios->fetch_assoc()) { ?>
                        <option value="<?php echo $usuario['documento_usuario']; ?>">
                            <?php echo $usuario['nombre'] . ' (' . $usuario['documento_usuario'] . ')'; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="marca">Marca:</ HALL>
                <input type="text" name="marca" id="marca" required>
            </div>
            <div class="form-group">
                <label for="modelo">Modelo:</label>
                <input type="text" name="modelo" id="modelo" required> <!-- Cambiado a text porque modelo es VARCHAR -->
            </div>
            <div class="form-group">
                <label for="anio">Año:</label>
                <input type="number" name="anio" id="anio" required>
            </div>
            <div class="form-group">
                <button type="submit">Crear Vehículo</button>
            </div>
        </form>
        <div class="back">
            <a href="listar.php">Volver a la Lista</a>
        </div>
    </div>
</body>
</html>