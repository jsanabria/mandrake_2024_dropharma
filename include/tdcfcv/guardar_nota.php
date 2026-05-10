<?php

namespace PHPMaker2024\mandrake;

require_once __DIR__ . "/tdcfcv_bootstrap.php";

$pedido = TdcfcvRequestInt("pedido");
$nota = TdcfcvRequestText("nota");
$username = TdcfcvCurrentUser();

$tipo_documento = "TDCFCV";

if ($pedido <= 0) {
    TdcfcvJsonError("Pedido no válido.");
}

$rowCab = ExecuteRow("
    SELECT IFNULL(nro_documento, '') AS nro_documento, nota 
    FROM salidas
    WHERE id = {$pedido}
      AND tipo_documento = '{$tipo_documento}'
    LIMIT 1
");

if (!$rowCab) {
    TdcfcvJsonError("No se encontró la cabecera del documento.");
}

$nro_documento = $rowCab["nro_documento"];
$nota_old = $rowCab["nota"];

$notaSql = AdjustSql($nota);
$usernameSql = AdjustSql($username);

$conn = Conn();

try {
    $conn->beginTransaction();

    ExecuteStatement("
        UPDATE salidas
        SET nota = '{$notaSql}'
        WHERE id = {$pedido}
          AND tipo_documento = '{$tipo_documento}'
    ");

    $mensajeAudit = AdjustSql(
        "Actualiza nota en Factura de Venta NRO/ID {$nro_documento}/({$pedido})"
    );

    ExecuteStatement("
        INSERT INTO audittrail
            (id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
        VALUES
            (
                NULL,
                '" . date("Y-m-d H:i:s") . "',
                '{$mensajeAudit}',
                '{$usernameSql}',
                'U',
                'view_out_tdcfcv',
                'nota',
                '{$pedido}',
                '{$nota_old}',
                '{$notaSql}'
            )
    ");

    $conn->commit();

    TdcfcvJsonOk([
        "pedido" => (string)$pedido,
        "nro_documento" => (string)$nro_documento,
        "nota" => $nota,
        "mensaje" => "Se actualizó la nota exitosamente."
    ]);
} catch (\Throwable $e) {
    if ($conn->isTransactionActive()) {
        $conn->rollBack();
    }

    TdcfcvJsonError("Error guardando nota: " . $e->getMessage());
}