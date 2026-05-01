<?php
session_start();
include("conexion.php");

// 🔒 Seguridad
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.html");
    exit();
}

// 🧠 Sesión
$nombre_usuario = $_SESSION['usuario'];
$rol_usuario    = $_SESSION['rol'];

// 🔄 Rol bonito
switch ($rol_usuario) {
    case 'A': $rol_usuario = 'Administrador'; break;
    case 'O': $rol_usuario = 'Operador'; break;
    case 'R': $rol_usuario = 'Reparador'; break;
}

// 📊 CONSULTAS (🔥 usando $conn, no $conexion)
$queryV = mysqli_query($conn, "SELECT COUNT(*) as total FROM Llantas WHERE nivel_estado = 'V'");
$queryA = mysqli_query($conn, "SELECT COUNT(*) as total FROM Llantas WHERE nivel_estado = 'A'");
$queryR = mysqli_query($conn, "SELECT COUNT(*) as total FROM Llantas WHERE nivel_estado = 'R'");

$v = mysqli_fetch_assoc($queryV)['total'] ?? 0;
$a = mysqli_fetch_assoc($queryA)['total'] ?? 0;
$r = mysqli_fetch_assoc($queryR)['total'] ?? 0;

// 🧩 Vista
include("../principal.html");