<?php
include("conexion.php");

$nombre = $_POST['nombre'];
$usuario = $_POST['usuario'];
$password = $_POST['password'];
$rol = $_POST['rol']; 

// 1. Validación básica
if (empty($nombre) || empty($usuario) || empty($password) || empty($rol)) {
    die("Error: Todos los campos son obligatorios.");
}

// 2. Validar que el rol sea uno de los permitidos por la BD
if ($rol !== 'A' && $rol !== 'O' && $rol !== 'R') {
    die("Error: Rol no válido.");
}


// Ajustado a la tabla 'Usuarios' con mayúscula según el script SQL
$stmt = $conn->prepare("INSERT INTO Usuarios (nombre, usuario, password, rol, estatus) VALUES (?, ?, ?, ?, 'A')");
$stmt->bind_param("ssss", $nombre, $usuario, $password, $rol);

if ($stmt->execute()) {
    // Nota: Quitamos el echo previo para que el header funcione siempre
    header("Location: ../index.html");
    exit(); // Siempre usa exit después de un header
} else {
    echo "Error al registrar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>