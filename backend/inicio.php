<?php
session_start();
include("conexion.php");

$usuario = $_POST['usuario'];
$password = md5($_POST['password']);

// Buscar usuario
$stmt = $conn->prepare("
    SELECT password, rol, estatus 
    FROM Usuarios 
    WHERE usuario = ?
");

$stmt->bind_param("s", $usuario);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $fila = $result->fetch_assoc();

    // Verificar estatus
    if ($fila['estatus'] === 'A') {

        // Comparar contraseña cifrada
        if ($password === $fila['password']) {

            $_SESSION['usuario'] = $usuario;
            $_SESSION['rol'] = $fila['rol'];

            header("Location: principal.php");
            exit();

        } else {
            echo "Contraseña incorrecta";
        }

    } else {
        echo "El usuario no está activo";
    }

} else {
    echo "El usuario no existe";
}

$stmt->close();
$conn->close();
?>
