<?php
session_start();
include("conexion.php");

$usuario = $_POST['usuario'];
$password = $_POST['password'];

// 1. Buscamos al usuario verificando su existencia, rol y si está activo
$stmt = $conn->prepare("SELECT password, rol, estatus FROM Usuarios WHERE usuario = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $fila = $result->fetch_assoc();

    // 2. Verificar si el usuario está activo ('A')
    if ($fila['estatus'] === 'A') {
        
        // 3. Verificar contraseña (COMPARACIÓN DIRECTA)
        if ($password === $fila['password']) {
            
            // Guardamos los datos en la sesión
            $_SESSION['usuario'] = $usuario;
            $_SESSION['rol'] = $fila['rol'];

            // 4. Redirección única
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