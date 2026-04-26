<?php
include("conexion.php");

$usuario = $_POST['usuario'];
$password = $_POST['password'];

// Buscar usuario
$stmt = $conn->prepare("SELECT password FROM usuarios WHERE usuario = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $fila = $result->fetch_assoc();
    $hash = $fila['password'];

    // Verificar contraseña
    if (password_verify($password, $hash)) {
        header("Location: ../principal.html");
        exit();
    } else {
        echo "Contraseña incorrecta";
    }

} else {
    echo "Usuario no existe";
}

$stmt->close();
$conn->close();
?>