<?php

namespace PHPMaker2024\mandrake;

require_once __DIR__ . "/tdcfcv_bootstrap.php";

$pedido = TdcfcvRequestInt("pedido");
$username = TdcfcvCurrentUser();

$tipo_documento = "TDCFCV";

if ($pedido <= 0) {
    TdcfcvJsonError("Pedido no válido.", [
        "pedido" => "0",
        "nro_documento" => "0000000",
        "doc_afectado" => "",
        "renglones" => "0",
        "unidades" => "0",
        "total" => "0.00",
        "total_usd" => "0.00"
    ]);
}

$rowCab = ExecuteRow("
    SELECT
        IFNULL(nro_documento, '') AS nro_documento,
        IFNULL(consignacion, '') AS consignacion,
        IFNULL(id_documento_padre, 0) AS id_documento_padre,
        IFNULL(direccion, '') AS direccion,
        IFNULL(doc_afectado, '') AS doc_afectado
    FROM salidas
    WHERE id = {$pedido}
      AND tipo_documento = '{$tipo_documento}'
    LIMIT 1
");

if (!$rowCab) {
    TdcfcvJsonError("No se encontró la cabecera del documento.", [
        "pedido" => "0",
        "nro_documento" => "0000000",
        "doc_afectado" => "",
        "renglones" => "0",
        "unidades" => "0",
        "total" => "0.00",
        "total_usd" => "0.00"
    ]);
}

$nro_documento = $rowCab["nro_documento"];
$consignacion = $rowCab["consignacion"];
$id_documento_padre = intval($rowCab["id_documento_padre"]);
$direccion = trim((string)$rowCab["direccion"]);
$doc_afectado = $rowCab["doc_afectado"];

$usernameSql = AdjustSql($username);

$conn = Conn();

try {
    $conn->beginTransaction();

    /**
     * Reversar consignaciones si aplica.
     */
    if ($consignacion == "S") {
        $rowsConsignacion = ExecuteRows("
            SELECT
                IFNULL(cantidad_movimiento, 0) AS cantidad_movimiento,
                IFNULL(id_consignacion, 0) AS id_consignacion
            FROM entradas_salidas
            WHERE id_documento = {$pedido}
              AND tipo_documento = '{$tipo_documento}'
        ");

        foreach ($rowsConsignacion as $row) {
            $id_consignacion = intval($row["id_consignacion"]);
            $cantidad_mov = intval($row["cantidad_movimiento"]);

            if ($id_consignacion > 0) {
                ExecuteStatement("
                    UPDATE entradas_salidas
                    SET cantidad_movimiento_consignacion = cantidad_movimiento_consignacion + ({$cantidad_mov})
                    WHERE id = {$id_consignacion}
                ");
            }
        }

        if ($id_documento_padre > 0) {
            ExecuteStatement("
                UPDATE salidas
                SET estatus = 'NUEVO'
                WHERE id = {$id_documento_padre}
            ");
        }

        if ($direccion !== "") {
            ExecuteStatement("
                UPDATE salidas
                SET estatus = 'NUEVO'
                WHERE id IN ({$direccion})
            ");
        }
    }

    /**
     * Eliminar todos los renglones del documento.
     */
    ExecuteStatement("
        DELETE FROM entradas_salidas
        WHERE id_documento = {$pedido}
          AND tipo_documento = '{$tipo_documento}'
    ");

    /**
     * Eliminar cabecera.
     */
    ExecuteStatement("
        DELETE FROM salidas
        WHERE id = {$pedido}
          AND tipo_documento = '{$tipo_documento}'
    ");

    /**
     * Auditoría.
     */
    $mensajeAudit = AdjustSql(
        "Eliminar Factura de Venta NRO/ID {$nro_documento}/{$pedido}"
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
                'D',
                'view_out_tdcfcv',
                'id',
                '{$pedido}',
                '{$nro_documento}',
                ''
            )
    ");

    $conn->commit();

    TdcfcvJsonOk([
        "pedido" => "0",
        "nro_documento" => "0000000",
        "doc_afectado" => $doc_afectado,
        "renglones" => "0",
        "unidades" => "0",
        "total" => "0.00",
        "total_usd" => "0.00",
        "monto_sin_descuento" => "0.00",
        "total_usd_sin_descuento" => "0.00",
        "mensaje" => "Documento vaciado correctamente."
    ]);
} catch (\Throwable $e) {
    if ($conn->isTransactionActive()) {
        $conn->rollBack();
    }

    TdcfcvJsonError("Error vaciando documento: " . $e->getMessage());
}