<?php
include("conexion.php");

$usuario = $_POST['usuario'];
$tipo = $_POST['tipo'];
$nueva_password = $_POST['nueva_password'];

// Validaciones básicas
if (empty($usuario) || empty($tipo) || empty($nueva_password)) {
    die("Faltan datos");
}

if ($tipo !== 'T' && $tipo !== 'R') {
    die("Tipo inválido");
}

// Verificar que el usuario exista con ese tipo
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ? AND tipo = ?");
$stmt->bind_param("ss", $usuario, $tipo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

    // Encriptar nueva contraseña
    $hash = password_hash($nueva_password, PASSWORD_DEFAULT);

    // Actualizar contraseña
    $update = $conn->prepare("UPDATE usuarios SET password = ? WHERE usuario = ? AND tipo = ?");
    $update->bind_param("sss", $hash, $usuario, $tipo);

    if ($update->execute()) {
        echo "Contraseña actualizada correctamente";
        header("Location: ../index.html");  
    } else {
        echo "Error al actualizar";
    }

    $update->close();

} else {
    echo "Usuario o tipo incorrecto";
}

$stmt->close();
$conn->close();
?>