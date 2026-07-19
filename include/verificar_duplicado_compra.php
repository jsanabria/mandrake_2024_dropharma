<?php
include "connect.php";
header("Content-Type: application/json; charset=utf-8");

$proveedor = intval($_POST["proveedor"] ?? 0);
$documento = trim($_POST["documento"] ?? "");
$tipo = mysqli_real_escape_string($link, $_POST["tipo_documento"] ?? "");

// Evitar consultas vacías
if ($proveedor <= 0 || $documento == "" || $tipo == "") {
    echo json_encode(["existe" => false]);
    die();
}

// Consulta de duplicidad
$sql = "SELECT documento FROM compra 
        WHERE proveedor = $proveedor 
        AND tipo_documento = '$tipo' 
        AND documento = '$documento' 
        AND anulado = 'N' 
        LIMIT 1";

$result = mysqli_query($link, $sql);

// Si existe al menos un registro, devolvemos true
echo json_encode(["existe" => (mysqli_num_rows($result) > 0)]);
?>