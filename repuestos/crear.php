<?php
session_start();
if (!isset($_SESSION['documento_usuario']) || !in_array($_SESSION['id_rol'], [1, 2])) {
    header("Location: ../index.php");
    exit();
}

require '../vendor/autoload.php'; // Include Composer autoloader (vendor/ is in the project root)

use Picqer\Barcode\BarcodeGeneratorPNG;

$conn = new mysqli("localhost", "root", "", "taller");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo_barras = $_POST['codigo_barras'];
    $nombre_repuesto = $_POST['nombre_repuesto'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];

    // Validate codigo_barras for EAN-13 (optional, but recommended)
    $is_ean13 = preg_match('/^\d{13}$/', $codigo_barras); // Must be exactly 13 digits

    // Insert into database
    $sql = "INSERT INTO repuestos (codigo_barras, nombre_repuesto, descripcion, precio, cantidad) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdi", $codigo_barras, $nombre_repuesto, $descripcion, $precio, $cantidad);
    
    if ($stmt->execute()) {
        try {
            // Generate barcode image
            $generator = new BarcodeGeneratorPNG();
            // Use EAN-13 if the barcode is 13 digits, otherwise fall back to Code 128
            $barcodeType = $is_ean13 ? $generator::TYPE_EAN_13 : $generator::TYPE_CODE_128;
            $barcodeImage = $generator->getBarcode($codigo_barras, $barcodeType);

            // Save barcode image to repuestos/barcodes/
            $barcodeDir = "barcodes";
            $barcodeFilePath = "$barcodeDir/{$codigo_barras}.png";
            if (!is_dir($barcodeDir)) {
                mkdir($barcodeDir, 0755, true);
            }
            file_put_contents($barcodeFilePath, $barcodeImage);

            header("Location: listar.php?message=" . urlencode("Repuesto creado exitosamente."));
            exit();
        } catch (Exception $e) {
            $error = "Error al generar el código de barras: " . $e->getMessage();
        }
    } else {
        // Check if the error is due to a duplicate codigo_barras
        if ($conn->errno === 1062) { // MySQL error code for duplicate entry
            $error = "El código de barras '$codigo_barras' ya existe. Por favor, usa un código único.";
        } else {
            $error = "Error al crear el repuesto: " . $conn->error;
        }
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Repuesto - Prestación de Herramientas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f0f0f0; }
        .container { max-width: 800px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .container h2 { text-align: center; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .form-group button { background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .form-group button:hover { background-color: #0056b3; }
        .back { text-align: center; margin-top: 20px; }
        .back a { color: #007bff; text-decoration: none; }
        .error { color: red; text-align: center; }
        .message { color: green; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Crear Nuevo Repuesto</h2>
        <?php if ($error) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>
        <?php if (isset($_GET['message'])) { ?>
            <p class="message"><?php echo htmlspecialchars($_GET['message']); ?></p>
        <?php } ?>
        <form action="crear.php" method="POST">
            <div class="form-group">
                <label for="codigo_barras">Código de Barras:</label>
                <input type="text" name="codigo_barras" id="codigo_barras" maxlength="30" required>
            </div>
            <div class="form-group">
                <label for="nombre_repuesto">Nombre del Repuesto:</label>
                <input type="text" name="nombre_repuesto" id="nombre_repuesto" maxlength="100" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea name="descripcion" id="descripcion"></textarea>
            </div>
            <div class="form-group">
                <label for="precio">Precio:</label>
                <input type="number" step="0.01" min="0" name="precio" id="precio" required>
            </div>
            <div class="form-group">
                <label for="cantidad">Cantidad:</label>
                <input type="number" min="0" name="cantidad" id="cantidad" required>
            </div>
            <div class="form-group">
                <button type="submit">Crear Repuesto</button>
            </div>
        </form>
        <div class="back">
            <a href="listar.php">Volver a la Lista</a>
        </div>
    </div>
</body>
</html>