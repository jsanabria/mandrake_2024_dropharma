<?php
header('Content-Type: application/json');
include "connect.php";

$tipo_pago = $_REQUEST["tipo_pago"] ?? "";
$referencia = $_REQUEST["referencia"] ?? "";

$existe = false;
$message = "";

if (empty($tipo_pago) || empty($referencia)) {
    echo json_encode(["status" => "error", "existe" => false, "message" => "Datos incompletos"]);
    exit;
}

// Usamos Prepared Statements para seguridad
$sql = "SELECT referencia FROM cobros_cliente_detalle WHERE metodo_pago = ? AND referencia = ? 
        UNION ALL 
        SELECT referencia FROM recarga WHERE metodo_pago = ? AND referencia = ?";

$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "ssss", $tipo_pago, $referencia, $tipo_pago, $referencia);
mysqli_stmt_execute($stmt);
$rs = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_array($rs)) {
    $existe = true;
    $message = "El número de referencia ya existe en los registros.";
} else {
    $existe = false;
    $message = "Referencia disponible.";
}

echo json_encode([
    "status" => "success",
    "existe" => $existe,
    "message" => $message
]);