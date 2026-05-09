<?php
include("conexion.php");

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Reporte_Bitacora_Moses.xls");
?>
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />

<!-- SECCIÓN 1: UNIDADES -->
<table border="1">
    <tr style="background-color: #2c3e50; color: white;">
        <th colspan="5">REPORTE DE UNIDADES (LIBRO 1)</th>
    </tr>
    <tr>
        <th>Placas</th><th>Eco</th><th>Modelo</th><th>Mecánico</th><th>Estatus VH</th>
    </tr>
    <?php
    $u = mysqli_query($conn, "SELECT * FROM Vehiculos");
    while($r = mysqli_fetch_assoc($u)) {
        echo "<tr><td>{$r['placas']}</td><td>{$r['numero_unidad']}</td><td>{$r['modelo']}</td><td>{$r['estatus_mecanico']}</td><td>{$r['estatus_vh']}</td></tr>";
    }
    ?>
</table>

<br>

<!-- SECCIÓN 2: LLANTAS -->
<table border="1">
    <tr style="background-color: #2c3e50; color: white;">
        <th colspan="4">REPORTE DE LLANTAS (LIBRO 2)</th>
    </tr>
    <tr>
        <th>ID Vehículo</th><th>Kilometraje</th><th>Estado Físico</th><th>Semáforo</th>
    </tr>
    <?php
    $l = mysqli_query($conn, "SELECT * FROM Llantas");
    while($r = mysqli_fetch_assoc($l)) {
        echo "<tr><td>{$r['id_vehiculo']}</td><td>{$r['kilometraje']}</td><td>{$r['estado_fisico']}</td><td>{$r['nivel_estado']}</td></tr>";
    }
    ?>
</table>

<br>

<!-- SECCIÓN 3: REPARACIONES Y OPERACIÓN -->
<table border="1">
    <tr style="background-color: #e74c3c; color: white;">
        <th colspan="4">BITÁCORA DE REPARACIONES Y OPERACIÓN (LIBRO 3)</th>
    </tr>
    <tr>
        <th>Prioridad</th><th>Elemento</th><th>Situación</th><th>Fecha</th>
    </tr>
    <?php
    $bit = mysqli_query($conn, "SELECT 'URGENTE' as p, placas as e, 'Falla Mecánica' as s, NOW() as f FROM Vehiculos WHERE estatus_mecanico = 'F' 
                                UNION 
                                SELECT 'CRÍTICO', CAST(id_llanta AS CHAR), 'Cambio Requerido', fecha_registro FROM Llantas WHERE nivel_estado = 'R'");
    while($r = mysqli_fetch_assoc($bit)) {
        echo "<tr><td>{$r['p']}</td><td>{$r['e']}</td><td>{$r['s']}</td><td>{$r['f']}</td></tr>";
    }
    ?>
</table>