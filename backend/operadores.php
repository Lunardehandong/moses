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



// 🧩 Vista
include("../operadores.html");