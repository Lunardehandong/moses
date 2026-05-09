<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.html");
    exit();
}

$nombre_usuario = $_SESSION['usuario'];
$rol_usuario    = $_SESSION['rol'];

// --- TRADUCCIÓN DE ROL PARA LA VISTA ---
$rol_label = "Desconocido";
if ($rol_usuario === 'A') {
    $rol_label = "Administrador";
} elseif ($rol_usuario === 'O') {
    $rol_label = "Operador";
} elseif ($rol_usuario === 'R') {
    $rol_label = "Reparador / Técnico";
}

// --- ELIMINAR (Solo Admin) ---
if (isset($_GET['eliminar']) && $rol_usuario === 'A') {
    $id_borrar = intval($_GET['eliminar']);
    mysqli_query($conn, "DELETE FROM Vehiculos WHERE id_vehiculo = $id_borrar");
    header("Location: unidades.php");
    exit();
}

// --- ACTUALIZAR ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_actualizar'])) {
    $id = intval($_POST['id_actualizar']);
    $est_mec = $_POST['estatus_mecanico'];
    $est_lla = $_POST['estatus_llantas'];
    $val_adm = $_POST['validacion_admin'];
    $est_vh  = $_POST['estatus_vh'];

    $sql = "UPDATE Vehiculos SET 
            estatus_mecanico='$est_mec', 
            estatus_llantas='$est_lla', 
            validacion_admin='$val_adm', 
            estatus_vh='$est_vh' 
            WHERE id_vehiculo=$id";
    
    mysqli_query($conn, $sql);
    header("Location: unidades.php");
    exit();
}

// --- INSERTAR (Bloqueado para Reparador) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['id_actualizar']) && $rol_usuario !== 'R') {
    $placas  = $_POST['placas'];
    $eco     = $_POST['numero_unidad'];
    $modelo  = $_POST['modelo'];
    $anio    = intval($_POST['anio']);
    
    $sql_ins = "INSERT INTO Vehiculos (placas, numero_unidad, modelo, anio, estatus_mecanico, estatus_llantas, validacion_admin, estatus_vh) 
                VALUES ('$placas', '$eco', '$modelo', '$anio', 'O', 'O', 'P', 'A')";
    mysqli_query($conn, $sql_ins);
    header("Location: unidades.php");
    exit();
}

// --- CONSULTA Y DATOS GRÁFICA ---
$resultado_unidades = mysqli_query($conn, "SELECT * FROM Vehiculos ORDER BY id_vehiculo DESC");

$sql_optimos = "SELECT COUNT(*) as total FROM Vehiculos WHERE estatus_mecanico = 'O' AND estatus_llantas = 'O' AND estatus_vh = 'A'";
$res_o = mysqli_query($conn, $sql_optimos);
$optimos = ($res_o) ? (int)mysqli_fetch_assoc($res_o)['total'] : 0;

$sql_no_aptos = "SELECT COUNT(*) as total FROM Vehiculos WHERE estatus_mecanico = 'F' OR estatus_llantas = 'F' OR estatus_vh != 'A'";
$res_n = mysqli_query($conn, $sql_no_aptos);
$no_aptos = ($res_n) ? (int)mysqli_fetch_assoc($res_n)['total'] : 0;

include("../unidades.html");
?>