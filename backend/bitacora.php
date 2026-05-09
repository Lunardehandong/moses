<?php
session_start();
include("conexion.php");

// Validación de seguridad para Administradores
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'A') {
    header("Location: ../index.html");
    exit();
}

$nombre_usuario = $_SESSION['usuario'];
$rol_usuario    = $_SESSION['rol'];
$rol_label      = "Administrador";

// --- ACCIÓN: ELIMINAR ---
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    mysqli_query($conn, "DELETE FROM Bitacora WHERE id_movimiento = $id");
    header("Location: bitacora.php");
    exit();
}

// --- ACCIÓN: ACTUALIZAR ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_actualizar'])) {
    $id = intval($_POST['id_actualizar']);
    $accion = mysqli_real_escape_string($conn, $_POST['accion']);
    $modulo = mysqli_real_escape_string($conn, $_POST['modulo']);

    mysqli_query($conn, "UPDATE Bitacora SET accion='$accion', modulo='$modulo' WHERE id_movimiento=$id");
    header("Location: bitacora.php");
    exit();
}

// Consulta de registros
$res_recientes = mysqli_query($conn, "SELECT b.*, u.usuario FROM Bitacora b 
                                      LEFT JOIN Usuarios u ON b.id_usuario = u.id_usuario 
                                      ORDER BY b.fecha_hora DESC");

include("../bitacora.html");
?>