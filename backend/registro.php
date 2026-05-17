<?php
include("conexion.php");

$nombre = $_POST['nombre'];
$usuario = $_POST['usuario'];
$password = md5($_POST['password']);
$rol = $_POST['rol']; 

// 1. Validación básica
if (empty($nombre) || empty($usuario) || empty($_POST['password']) || empty($rol)) {
    die("Error: Todos los campos son obligatorios.");
}

// 2. Validar que el rol sea uno de los permitidos
if ($rol !== 'A' && $rol !== 'O' && $rol !== 'R') {
    die("Error: Rol no válido.");
}

// Insertar usuario
$stmt = $conn->prepare("
    INSERT INTO Usuarios (nombre, usuario, password, rol, estatus) 
    VALUES (?, ?, ?, ?, 'A')
");

$stmt->bind_param("ssss", $nombre, $usuario, $password, $rol);

if ($stmt->execute()) {
    header("Location: ../index.html");
    exit();
} else {
    echo "Error al registrar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
