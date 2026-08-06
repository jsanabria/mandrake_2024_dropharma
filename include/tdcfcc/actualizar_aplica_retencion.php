<?php

include "../connect.php";

header("Content-Type: application/json; charset=UTF-8");

$pedido = intval($_POST["pedido"] ?? 0);

$aplica_retencion = (
    strtoupper(trim($_POST["aplica_retencion"] ?? "N")) === "S"
) ? "S" : "N";

if ($pedido <= 0) {
    echo json_encode([
        "estatus" => 0,
        "mensaje" => "El documento indicado no es válido."
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

$sql = "UPDATE entradas
        SET aplica_retencion = '$aplica_retencion'
        WHERE id = $pedido
          AND tipo_documento = 'TDCFCC'
        LIMIT 1;";

$resultado = mysqli_query($link, $sql);

if (!$resultado) {
    echo json_encode([
        "estatus" => 0,
        "mensaje" => "Error actualizando la retención: " . mysqli_error($link)
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

echo json_encode([
    "estatus" => 1,
    "aplica_retencion" => $aplica_retencion,
    "mensaje" => "Condición de retención actualizada correctamente."
], JSON_UNESCAPED_UNICODE);
?>