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

$is_super_admin = $_SESSION['id_rol'] == 1;

// Corrected table name to taller_repuestos
$sql = "SELECT codigo_barras, nombre_repuesto, descripcion, precio, cantidad 
        FROM repuestos";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Herramientas/Repuestos - Prestación de Herramientas</title>
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
        .new { text-align: center; margin-bottom: 10px; }
        .new a, .scan a { background-color: #007bff; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; margin: 0 5px; }
        .new a:hover, .scan a:hover { background-color: #0056b3; }
        .scan { text-align: center; margin-bottom: 20px; }
        .back { text-align: center; margin-top: 20px; }
        .back a { color: #007bff; text-decoration: none; }
        .barcode-img { max-width: 100px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Lista de Herramientas/Repuestos</h2>
        <div class="new">
            <a href="crear.php">Crear Nuevo Repuesto</a>
        </div>
        <div class="scan">
            <a href="scan.php">Escanear para Prestar</a>
        </div>
        <table>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Barcode</th>
                <th>Acciones</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['codigo_barras']; ?></td>
                    <td><?php echo $row['nombre_repuesto']; ?></td>
                    <td><?php echo $row['descripcion']; ?></td>
                    <td><?php echo $row['precio']; ?></td>
                    <td><?php echo $row['cantidad']; ?></td>
                    <td>
                        <?php
                        $barcodeFile = "barcodes/{$row['codigo_barras']}.png";
                        if (file_exists($barcodeFile)) {
                            echo "<img src='$barcodeFile' alt='Barcode' class='barcode-img'>";
                        } else {
                            echo "No barcode";
                        }
                        ?>
                    </td>
                    <td class="actions">
                        <a href="editar.php?codigo_barras=<?php echo $row['codigo_barras']; ?>">Editar</a>
                        <a href="eliminar.php?codigo_barras=<?php echo $row['codigo_barras']; ?>" class="delete" onclick="return confirm('¿Estás seguro de eliminar este repuesto?')">Eliminar</a>
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