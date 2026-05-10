<?php

namespace PHPMaker2024\mandrake;

require_once __DIR__ . "/tdcfcv_bootstrap.php";

$pedido = TdcfcvRequestInt("pedido");
$username = TdcfcvCurrentUser();

$moneda = TdcfcvRequestText("moneda", "Bs.");
$tasa_usd = TdcfcvRequestFloat("tasa_usd", 1);

$tipo_documento = "TDCFCV";
$tasa_usd = ($tasa_usd <= 0 ? 1 : $tasa_usd);

if ($pedido <= 0) {
    TdcfcvJsonError("Pedido no válido.");
}

$rowCab = ExecuteRow("
    SELECT
        IFNULL(nro_documento, '') AS nro_documento,
        IFNULL(descuento, 0) AS descuento,
        IFNULL(descuento2, 0) AS descuento2,
        IFNULL(descuento3, 0) AS descuento3
    FROM salidas
    WHERE id = {$pedido}
      AND tipo_documento = '{$tipo_documento}'
    LIMIT 1
");

if (!$rowCab) {
    TdcfcvJsonError("No se encontró la cabecera del documento.");
}

$nro_documento = $rowCab["nro_documento"];
$descuento = floatval($rowCab["descuento"]);
$descTransferencista = floatval($rowCab["descuento2"]);
$descFabricante = floatval($rowCab["descuento3"]);

$monedaSql = AdjustSql($moneda);
$usernameSql = AdjustSql($username);

$conn = Conn();

try {
    $conn->beginTransaction();

    ExecuteStatement("
        UPDATE salidas
        SET
            moneda = '{$monedaSql}',
            tasa_dia = {$tasa_usd}
        WHERE id = {$pedido}
          AND tipo_documento = '{$tipo_documento}'
    ");

    $mensajeAudit = AdjustSql(
        "Actualiza moneda/tasa en Factura de Venta NRO {$nro_documento} Moneda {$moneda} Tasa {$tasa_usd}"
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
                'id',
                '{$pedido}',
                '',
                'Moneda: {$monedaSql}; Tasa: {$tasa_usd}'
            )
    ");

    $xalicuota = floatval(ExecuteScalar("
        SELECT DISTINCT IFNULL(alicuota, 0)
        FROM entradas_salidas
        WHERE id_documento = {$pedido}
          AND tipo_documento = '{$tipo_documento}'
        ORDER BY 1 DESC
        LIMIT 1
    "));

    $rowTot = ExecuteRow("
        SELECT
            IFNULL(SUM(precio), 0) AS precio_sin_desc_total,
            IFNULL(SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ({$descuento}/100)), 0)), 0) AS exento,
            IFNULL(SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ({$descuento}/100)))), 0) AS gravado,
            IFNULL(COUNT(articulo), 0) AS renglones,
            IFNULL(ABS(SUM(cantidad_movimiento)), 0) AS unidades
        FROM entradas_salidas
        WHERE id_documento = {$pedido}
          AND tipo_documento = '{$tipo_documento}'
    ");

    $monto_sin_descuento = floatval($rowTot["precio_sin_desc_total"] ?? 0);
    $renglones = intval($rowTot["renglones"] ?? 0);
    $unidades = floatval($rowTot["unidades"] ?? 0);

    $xExento = floatval($rowTot["exento"] ?? 0);
    $xExento = $xExento - ($xExento * ($descTransferencista / 100));
    $xExento = $xExento - ($xExento * ($descFabricante / 100));

    $xGravado = floatval($rowTot["gravado"] ?? 0);
    $xGravado = $xGravado - ($xGravado * ($descTransferencista / 100));
    $xGravado = $xGravado - ($xGravado * ($descFabricante / 100));

    $costo = $xExento + $xGravado;
    $iva = $xGravado * ($xalicuota / 100);
    $total_final = $costo + $iva;

    $esBs = (strtoupper(substr(trim($moneda), 0, 3)) == "BS.");

    $total_usd_real = $esBs
        ? round(($total_final / $tasa_usd), 2)
        : round($total_final, 2);

    ExecuteStatement("
        UPDATE salidas
        SET
            monto_total = {$costo},
            alicuota_iva = {$xalicuota},
            iva = {$iva},
            total = {$total_final},
            unidades = {$unidades},
            monto_usd = {$total_usd_real},
            monto_sin_descuento = {$monto_sin_descuento}
        WHERE id = {$pedido}
          AND tipo_documento = '{$tipo_documento}'
    ");

    $conn->commit();

    TdcfcvJsonOk([
        "pedido" => (string)$pedido,
        "total" => (string)($esBs ? round(($costo / $tasa_usd), 2) : round($costo, 2)),
        "total_usd" => (string)($esBs ? round($costo, 2) : round(($costo * $tasa_usd), 2)),
        "monto_sin_descuento" => (string)($esBs ? round(($monto_sin_descuento / $tasa_usd), 2) : round($monto_sin_descuento, 2)),
        "total_usd_sin_descuento" => (string)($esBs ? round($monto_sin_descuento, 2) : round(($monto_sin_descuento * $tasa_usd), 2)),
        "renglones" => (string)$renglones,
        "unidades" => (string)$unidades,
        "nro_documento" => (string)$nro_documento,
        "moneda" => (string)$moneda,
        "tasa_usd" => (string)$tasa_usd,
        "descuento" => (string)$descuento,
        "descTransferencista" => (string)$descTransferencista,
        "descFabricante" => (string)$descFabricante,
        "mensaje" => "Moneda y tasa actualizadas correctamente."
    ]);
} catch (\Throwable $e) {
    if ($conn->isTransactionActive()) {
        $conn->rollBack();
    }

    TdcfcvJsonError("Error actualizando moneda/tasa: " . $e->getMessage());
}