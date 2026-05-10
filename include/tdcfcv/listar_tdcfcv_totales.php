<?php

namespace PHPMaker2024\mandrake;

require_once __DIR__ . "/tdcfcv_bootstrap.php";

$pedido = TdcfcvRequestInt("pedido");
$tipo_documento = "TDCFCV";

if ($pedido <= 0) {
    TdcfcvJsonError("Pedido no válido.", [
        "pedido" => "0",
        "total" => "0.00",
        "renglones" => "0",
        "unidades" => "0",
        "total_usd" => "0.00",
        "descuento" => "0.00",
        "monto_sin_descuento" => "0.00",
        "total_usd_sin_descuento" => "0.00",
        "nro_documento" => "0000000",
        "descTransferencista" => "0.00",
        "descFabricante" => "0.00"
    ]);
}

// Moneda por defecto
$monedaDefault = ExecuteScalar("
    SELECT SUBSTRING(valor1, 1, 3) AS moneda
    FROM parametro
    WHERE codigo = '006'
      AND valor2 = 'default'
    LIMIT 1
");

$monedaDefault = $monedaDefault ?: "Bs.";

// Tasa USD por defecto
$tasaDefault = floatval(ExecuteScalar("
    SELECT tasa
    FROM tasa_usd
    WHERE moneda = 'USD'
    ORDER BY id DESC
    LIMIT 1
"));

$tasaDefault = ($tasaDefault <= 0 ? 1 : $tasaDefault);

// Cabecera del documento
$rowCab = ExecuteRow("
    SELECT
        IFNULL(descuento, 0) AS descuento,
        IFNULL(descuento2, 0) AS descuento2,
        IFNULL(descuento3, 0) AS descuento3,
        IFNULL(nro_documento, '') AS nro_documento,
        IFNULL(moneda, '{$monedaDefault}') AS moneda,
        IFNULL(tasa_dia, {$tasaDefault}) AS tasa_dia
    FROM salidas
    WHERE id = {$pedido}
      AND tipo_documento = '{$tipo_documento}'
    LIMIT 1
");

if (!$rowCab) {
    TdcfcvJsonError("No se encontró la cabecera del documento.", [
        "pedido" => (string)$pedido,
        "total" => "0.00",
        "renglones" => "0",
        "unidades" => "0",
        "total_usd" => "0.00",
        "descuento" => "0.00",
        "monto_sin_descuento" => "0.00",
        "total_usd_sin_descuento" => "0.00",
        "nro_documento" => "0000000",
        "descTransferencista" => "0.00",
        "descFabricante" => "0.00"
    ]);
}

$descuento = floatval($rowCab["descuento"]);
$descTransferencista = floatval($rowCab["descuento2"]);
$descFabricante = floatval($rowCab["descuento3"]);
$nro_documento = $rowCab["nro_documento"];
$moneda = $rowCab["moneda"] ?: $monedaDefault;
$tasa_usd = floatval($rowCab["tasa_dia"]);
$tasa_usd = ($tasa_usd <= 0 ? 1 : $tasa_usd);

// Alícuota principal
$xalicuota = floatval(ExecuteScalar("
    SELECT DISTINCT IFNULL(alicuota, 0) AS alicuota
    FROM entradas_salidas
    WHERE id_documento = {$pedido}
      AND tipo_documento = '{$tipo_documento}'
    ORDER BY 1 DESC
    LIMIT 1
"));

$rowTot = ExecuteRow("
    SELECT
        IFNULL(SUM(precio), 0) AS precio_unidad_sin_desc,
        IFNULL(SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ({$descuento}/100)), 0)), 0) AS exento,
        IFNULL(SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ({$descuento}/100)))), 0) AS gravado,
        IFNULL(COUNT(articulo), 0) AS renglones,
        IFNULL(ABS(SUM(cantidad_movimiento)), 0) AS unidades
    FROM entradas_salidas
    WHERE id_documento = {$pedido}
      AND tipo_documento = '{$tipo_documento}'
");

$monto_sin_descuento = floatval($rowTot["precio_unidad_sin_desc"] ?? 0);
$renglones = intval($rowTot["renglones"] ?? 0);
$unidades = floatval($rowTot["unidades"] ?? 0);

$xExento = floatval($rowTot["exento"] ?? 0);
$xGravado = floatval($rowTot["gravado"] ?? 0);

// Aplica descuentos adicionales
$xExento = $xExento - ($xExento * ($descTransferencista / 100));
$xExento = $xExento - ($xExento * ($descFabricante / 100));

$xGravado = $xGravado - ($xGravado * ($descTransferencista / 100));
$xGravado = $xGravado - ($xGravado * ($descFabricante / 100));

$costo = $xExento + $xGravado;
$iva = $xGravado * ($xalicuota / 100);
$total = $costo + $iva;

$esBs = (strtoupper(substr(trim($moneda), 0, 3)) === "BS.");

$total_mostrar = $esBs ? round(($costo / $tasa_usd), 2) : round($costo, 2);
$total_usd_mostrar = $esBs ? round($costo, 2) : round(($costo * $tasa_usd), 2);

$monto_sin_descuento_mostrar = $esBs
    ? round(($monto_sin_descuento / $tasa_usd), 2)
    : round($monto_sin_descuento, 2);

$total_usd_sin_descuento = $esBs
    ? round($monto_sin_descuento, 2)
    : round(($monto_sin_descuento * $tasa_usd), 2);

$total_usd_real = $esBs
    ? round(($total / $tasa_usd), 2)
    : round($total, 2);

// Actualiza cabecera
ExecuteStatement("
    UPDATE salidas
    SET
        monto_total = {$costo},
        alicuota_iva = {$xalicuota},
        iva = {$iva},
        total = {$total},
        tasa_dia = {$tasa_usd},
        monto_usd = {$total_usd_real},
        monto_sin_descuento = {$monto_sin_descuento}
    WHERE id = {$pedido}
      AND tipo_documento = '{$tipo_documento}'
");

TdcfcvJsonOk([
    "mensaje" => "Totales calculados correctamente.",
    "pedido" => (string)$pedido,
    "total" => (string)$total_mostrar,
    "renglones" => (string)$renglones,
    "unidades" => (string)$unidades,
    "total_usd" => (string)$total_usd_mostrar,
    "descuento" => (string)$descuento,
    "monto_sin_descuento" => (string)$monto_sin_descuento_mostrar,
    "total_usd_sin_descuento" => (string)$total_usd_sin_descuento,
    "nro_documento" => (string)$nro_documento,
    "descTransferencista" => (string)$descTransferencista,
    "descFabricante" => (string)$descFabricante
]);