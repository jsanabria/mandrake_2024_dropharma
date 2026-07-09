<?php
include "connect.php";
header("Content-Type: application/json; charset=utf-8");

$proveedor = intval($_POST["proveedor"] ?? 0);
$doc_afectado = trim($_POST["doc_afectado"] ?? "");

if ($proveedor <= 0 || $doc_afectado == "") {
    echo json_encode(["success" => false, "message" => "Datos incompletos"]);
    die();
}

// Buscar factura existente
$sql = "SELECT proveedor, moneda, tasa_dia, descripcion, aplica_retencion, 
        monto_exento, monto_gravado, alicuota, monto_iva, monto_total, ret_iva, 
        ref_iva, ret_islr, monto_pagar, sustraendo, tipo_iva, tipo_islr 
        FROM compra 
        WHERE proveedor = $proveedor 
        AND documento = '" . mysqli_real_escape_string($link, $doc_afectado) . "' 
        AND tipo_documento = 'FC' 
        AND anulado = 'N' 
        LIMIT 1";

$rs = mysqli_query($link, $sql);

if ($row = mysqli_fetch_assoc($rs)) {
    echo json_encode(["success" => true, "data" => $row]);
} else {
    echo json_encode(["success" => false, "message" => "Factura no encontrada o anulada."]);
}
?>