<?php
include("conexion.php");

$usuario = $_POST['usuario'];
$password = $_POST['password'];
$tipo = $_POST['tipo']; // 'T' o 'R'

// Validación básica
if (empty($usuario) || empty($password) || empty($tipo)) {
    die("Faltan datos");
}

if ($tipo !== 'T' && $tipo !== 'R') {
    die("Tipo inválido");
}

// Encriptar contraseña
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Insertar
$stmt = $conn->prepare("INSERT INTO usuarios (usuario, password, tipo) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $usuario, $passwordHash, $tipo);

if ($stmt->execute()) {
    echo "Usuario creado correctamente";
     header("Location: ../index.html");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>