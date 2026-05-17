<?php
include("conexion.php");

$usuario = $_POST['usuario'];
$rol = $_POST['rol'];
$nueva_password = md5($_POST['nueva_password']);

// 1. Validaciones básicas
if (empty($usuario) || empty($rol) || empty($_POST['nueva_password'])) {
    die("Faltan datos por completar");
}

// 2. Validar rol
if ($rol !== 'A' && $rol !== 'O' && $rol !== 'R') {
    die("Rol inválido");
}

// 3. Verificar usuario activo
$stmt = $conn->prepare("
    SELECT id_usuario 
    FROM Usuarios 
    WHERE usuario = ? 
    AND rol = ? 
    AND estatus = 'A'
");

$stmt->bind_param("ss", $usuario, $rol);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    // 4. Actualizar contraseña cifrada con md5
    $update = $conn->prepare("
        UPDATE Usuarios 
        SET password = ? 
        WHERE usuario = ? 
        AND rol = ?
    ");

    $update->bind_param("sss", $nueva_password, $usuario, $rol);

    if ($update->execute()) {
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
