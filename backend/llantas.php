<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.html");
    exit();
}

$nombre_usuario = $_SESSION['usuario'];
$rol_usuario    = $_SESSION['rol']; // 'A', 'O', 'R'

// Etiquetas de Rol
$rol_label = 'Desconocido';
if ($rol_usuario === 'A') $rol_label = 'Administrador';
if ($rol_usuario === 'O') $rol_label = 'Operador';
if ($rol_usuario === 'R') $rol_label = 'Reparador';

// --- LÓGICA DE ELIMINAR (Solo Admin) ---
if (isset($_GET['eliminar']) && $rol_usuario === 'A') {
    $id_borrar = intval($_GET['eliminar']);
    mysqli_query($conn, "DELETE FROM Llantas WHERE id_llanta = $id_borrar");
    header("Location: llantas.php");
    exit();
}

// --- LÓGICA DE ACTUALIZAR ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_actualizar'])) {
    $id = intval($_POST['id_actualizar']);
    $fisico = $_POST['estado_fisico'];
    $nivel  = $_POST['nivel_estado'];

    if ($rol_usuario === 'A') {
        // Administrador: Modifica TODO
        $id_v  = intval($_POST['id_vehiculo']);
        $clave = $_POST['clave_prod'];
        $km    = $_POST['kilometraje'];
        $sql   = "UPDATE Llantas SET id_vehiculo='$id_v', clave_producto_servicio='$clave', kilometraje='$km', estado_fisico='$fisico', nivel_estado='$nivel' WHERE id_llanta=$id";
    } else {
        // Operador y Reparador: Solo modifican estados
        $sql = "UPDATE Llantas SET estado_fisico='$fisico', nivel_estado='$nivel' WHERE id_llanta=$id";
    }
    
    mysqli_query($conn, $sql);
    header("Location: llantas.php?upd=ok");
    exit();
}

// --- LÓGICA DE INSERCIÓN (Bloqueada para Reparador) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['id_actualizar']) && $rol_usuario !== 'R') {
    $id_vehiculo = intval($_POST['id_vehiculo']);
    $clave       = $_POST['clave_prod'];
    $km          = $_POST['kilometraje'];
    $fisico      = $_POST['estado_fisico'];
    $nivel       = $_POST['nivel_estado'];

    $sql_ins = "INSERT INTO Llantas (id_vehiculo, clave_producto_servicio, kilometraje, estado_fisico, nivel_estado, fecha_registro) 
                VALUES ('$id_vehiculo', '$clave', '$km', '$fisico', '$nivel', NOW())";

    mysqli_query($conn, $sql_ins);
    header("Location: llantas.php");
    exit();
}

// Consulta de tabla (Todos pueden verla ahora)
$resultado_llantas = mysqli_query($conn, "SELECT * FROM Llantas ORDER BY fecha_registro DESC");

include("../llantas.html");
?>