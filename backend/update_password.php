<?php
include("conexion.php");

$usuario = $_POST['usuario'];
$rol = $_POST['rol']; // Cambiado de 'tipo' a 'rol' para coincidir con la BD
$nueva_password = $_POST['nueva_password'];

// 1. Validaciones básicas: Que no vengan vacíos
if (empty($usuario) || empty($rol) || empty($nueva_password)) {
    die("Faltan datos por completar");
}

// 2. Verificar que el rol sea uno de los permitidos (A, O o R)
if ($rol !== 'A' && $rol !== 'O' && $rol !== 'R') {
    die("Rol inválido");
}

// 3. Verificar que el usuario exista con ese rol y esté activo ('A')
// Nota: Usamos id_usuario que es el nombre real de tu columna
$stmt = $conn->prepare("SELECT id_usuario FROM Usuarios WHERE usuario = ? AND rol = ? AND estatus = 'A'");
$stmt->bind_param("ss", $usuario, $rol);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

    // 4. Actualizar contraseña (TEXTO PLANO, sin password_hash)
    $update = $conn->prepare("UPDATE Usuarios SET password = ? WHERE usuario = ? AND rol = ?");
    $update->bind_param("sss", $nueva_password, $usuario, $rol);

    if ($update->execute()) {
        // Éxito: enviamos al index para que inicie sesión con la nueva clave
        header("Location: ../index.html");
        exit();
    } else {
        echo "Error interno al intentar actualizar";
    }

    $update->close();

} else {
    echo "Los datos no coinciden con un usuario activo";
}

$stmt->close();
$conn->close();
?>