<?php
require_once "connect.php"; 

$fecha = isset($_GET["fecha"]) ? $_GET["fecha"] : "";

// --- NUEVA LÓGICA DE CONVERSIÓN ---
// Si la fecha viene como dd/mm/yyyy, la convertimos a yyyy-mm-dd
$fecha_convertida = "";
if (strpos($fecha, '/') !== false) {
    $partes = explode('/', $fecha);
    // Aseguramos formato [día, mes, año] -> [año, mes, día]
    if (count($partes) == 3) {
        $fecha_convertida = $partes[2] . '-' . $partes[1] . '-' . $partes[0];
    }
} else {
    $fecha_convertida = $fecha; // Ya viene en otro formato o está vacía
}
// ----------------------------------

$fecha_sql = mysqli_real_escape_string($link, $fecha_convertida);

$tasa = 0;

$sql = "SELECT tasa FROM tasa_usd WHERE fecha = '$fecha_sql' AND moneda = 'USD' LIMIT 1";
$result = mysqli_query($link, $sql);

if ($result) {
    if ($row = mysqli_fetch_assoc($result)) {
        $tasa = floatval($row["tasa"]);
    }
    mysqli_free_result($result);
}

echo $tasa;
?>