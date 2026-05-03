<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.html");
    exit();
}

$nombre_usuario = $_SESSION['usuario'];
$rol_usuario    = $_SESSION['rol']; 

$rol_label = $rol_usuario; 
switch ($rol_label) {
    case 'A': $rol_label = 'Administrador'; break;
    case 'O': $rol_label = 'Operador'; break;
    case 'R': $rol_label = 'Reparador'; break;
}

if (isset($_GET['eliminar']) && $rol_usuario === 'A') {
    $id_borrar = intval($_GET['eliminar']);
    mysqli_query($conn, "DELETE FROM Operadores WHERE id_operador = $id_borrar");
    header("Location: operadores.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_actualizar'])) {
    $id = intval($_POST['id_actualizar']);
    $est = $_POST['estatus'];
    if ($rol_usuario === 'A') {
        $nom = $_POST['nombre'];
        $lic = $_POST['licencia'];
        $sql = "UPDATE Operadores SET nombre='$nom', licencia='$lic', estatus='$est' WHERE id_operador=$id";
    } else {
        $sql = "UPDATE Operadores SET estatus='$est' WHERE id_operador=$id";
    }
    mysqli_query($conn, $sql);
    header("Location: operadores.php?upd=ok");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['id_actualizar'])) {
    $nom = $_POST['nombre'];
    $lic = $_POST['licencia'];
    $tel = $_POST['telefono'];
    mysqli_query($conn, "INSERT INTO Operadores (nombre, licencia, telefono, estatus) VALUES ('$nom', '$lic', '$tel', 'A')");
    header("Location: operadores.php");
    exit();
}

$mostrar_tabla = ($rol_usuario === 'A' || $rol_usuario === 'O');
$resultado_ops = ($mostrar_tabla) ? mysqli_query($conn, "SELECT * FROM Operadores ORDER BY id_operador DESC") : null;

include("../operadores.html");
?>
