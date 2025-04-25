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

$empresas = [];
$roles = [];
$is_super_admin = $_SESSION['id_rol'] == 1;
$user_empresa = $_SESSION['id_empresa'];

// Obtener roles
$sql_roles = "SELECT id, nombre FROM rol";
$result_roles = $conn->query($sql_roles);
while ($row = $result_roles->fetch_assoc()) {
    $roles[] = $row;
}


if ($is_super_admin) {
    $sql_empresas = "SELECT id, nombre FROM empresa";
} else {
    $sql_empresas = "SELECT id, nombre FROM empresa WHERE id = ?";
    $stmt = $conn->prepare($sql_empresas);
    $stmt->bind_param("i", $user_empresa);
    $stmt->execute();
    $result_empresas = $stmt->get_result();
    $stmt->close();
}

if (!isset($result_empresas)) {
    $result_empresas = $conn->query($sql_empresas);
}
while ($row = $result_empresas->fetch_assoc()) {
    $empresas[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $documento = $_POST['documento'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $id_rol = $_POST['id_rol'];
    $id_empresa = $is_super_admin ? $_POST['id_empresa'] : $user_empresa;

    $sql = "INSERT INTO taller_usuarios (documento, nombre, telefono, correo, contrasena, id_rol, id_empresa) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssii", $documento, $nombre, $telefono, $correo, $contrasena, $id_rol, $id_empresa);
    
    if ($stmt->execute()) {
        header("Location: listar.php");
        exit();
    } else {
        $error = "Error al registrar usuario: " . $conn->error;
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
    <title>Crear Usuario - Prestación de Herramientas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f0f0f0; }
        .container { max-width: 600px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .container h2 { text-align: center; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input, .form-group select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .form-group button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .form-group button:hover { background-color: #0056b3; }
        .error { color: red; text-align: center; }
        .back { text-align: center; margin-top: 10px; }
        .back a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body onload="formulario.documento.focus()">
    <div class="container">
        <h2>Registrar Nuevo Usuario</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" name="formulario">
            <div class="form-group">
                <label for="documento">Documento:</label>
                <input type="text" name="documento" maxlength="20" required tabindex="1">
            </div>
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" maxlength="100" required tabindex="2">
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" maxlength="20" required tabindex="3">
            </div>
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" name="correo" maxlength="100" required tabindex="4">
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" name="contrasena" maxlength="255" required tabindex="5">
            </div>
            <div class="form-group">
                <label for="id_rol">Rol:</label>
                <select name="id_rol" required>
                    <?php foreach ($roles as $rol) { ?>
                        <option value="<?php echo $rol['id']; ?>"><?php echo $rol['nombre']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <?php if ($is_super_admin) { ?>
            <div class="form-group">
                <label for="id_empresa">Empresa:</label>
                <select name="id_empresa" required>
                    <?php foreach ($empresas as $empresa) { ?>
                        <option value="<?php echo $empresa['id']; ?>"><?php echo $empresa['nombre']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <?php } ?>
            <div class="form-group">
                <button type="submit">Registrar</button>
            </div>
        </form>
        <div class="back">
            <a href="listar.php">Volver a la lista</a>
        </div>
    </div>
</body>
</html>