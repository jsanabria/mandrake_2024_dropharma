<?php

namespace PHPMaker2024\mandrake;

require_once __DIR__ . "/tdcfcv_bootstrap.php";

$pedido = TdcfcvRequestInt("pedido");
$articulo = TdcfcvRequestInt("articulo");
$id_item = TdcfcvRequestInt("id_item");

$tasa_usd = TdcfcvRequestFloat("tasa_usd", 1);
$moneda = TdcfcvRequestText("moneda", "Bs.");
$username = TdcfcvCurrentUser();

$descuento = TdcfcvRequestFloat("descuento");
$descTransferencista = TdcfcvRequestFloat("descTransferencista");
$descFabricante = TdcfcvRequestFloat("descFabricante");
$nota = TdcfcvRequestText("nota");

$tipo_documento = "TDCFCV";
$tasa_usd = ($tasa_usd <= 0 ? 1 : $tasa_usd);

if ($pedido <= 0) {
    TdcfcvJsonError("Pedido no válido.");
}

if ($id_item <= 0) {
    TdcfcvJsonError("Item no válido.");
}

if ($articulo <= 0) {
    TdcfcvJsonError("Artículo no válido.");
}

$rowItem = ExecuteRow("
    SELECT
        IFNULL(cantidad_movimiento, 0) AS cantidad_movimiento,
        IFNULL(id_consignacion, 0) AS id_consignacion,
        IFNULL(cantidad_articulo, 0) AS cantidad_articulo,
        IFNULL(precio_unidad, 0) AS precio_unidad,
        IFNULL(precio, 0) AS precio,
        IFNULL(lote, '') AS lote,
        IFNULL(fecha_vencimiento, '') AS fecha_vencimiento
    FROM entradas_salidas
    WHERE id = {$id_item}
      AND id_documento = {$pedido}
      AND tipo_documento = '{$tipo_documento}'
    LIMIT 1
");

if (!$rowItem) {
    TdcfcvJsonError("No se encontró el item a eliminar.");
}

$rowCab = ExecuteRow("
    SELECT
        IFNULL(nro_documento, '') AS nro_documento,
        IFNULL(consignacion, '') AS consignacion,
        IFNULL(id_documento_padre, 0) AS id_documento_padre,
        IFNULL(direccion, '') AS direccion
    FROM salidas
    WHERE id = {$pedido}
      AND tipo_documento = '{$tipo_documento}'
    LIMIT 1
");

if (!$rowCab) {
    TdcfcvJsonError("No se encontró la cabecera del documento.");
}

$nro_documento = $rowCab["nro_documento"];
$consignacion = $rowCab["consignacion"];
$id_documento_padre = intval($rowCab["id_documento_padre"]);
$direccion = trim((string)$rowCab["direccion"]);

$monedaSql = AdjustSql($moneda);
$notaSql = AdjustSql($nota);
$usernameSql = AdjustSql($username);

$conn = Conn();

try {
    $conn->beginTransaction();

    /**
     * Reversar consignación si aplica.
     */
    if ($consignacion == "S") {
        $id_consignacion = intval($rowItem["id_consignacion"]);
        $cantidad_mov = intval($rowItem["cantidad_movimiento"]);

        if ($id_consignacion > 0) {
            ExecuteStatement("
                UPDATE entradas_salidas
                SET cantidad_movimiento_consignacion = cantidad_movimiento_consignacion + ({$cantidad_mov})
                WHERE id = {$id_consignacion}
            ");
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
     * Actualizar cabecera antes de eliminar.
     */
    ExecuteStatement("
        UPDATE salidas
        SET
            descuento = {$descuento},
            descuento2 = {$descTransferencista},
            descuento3 = {$descFabricante},
            nota = '{$notaSql}',
            tasa_dia = {$tasa_usd},
            moneda = '{$monedaSql}'
        WHERE id = {$pedido}
          AND tipo_documento = '{$tipo_documento}'
    ");

    /**
     * Eliminar item.
     */
    ExecuteStatement("
        DELETE FROM entradas_salidas
        WHERE id = {$id_item}
          AND id_documento = {$pedido}
          AND tipo_documento = '{$tipo_documento}'
    ");

    $mensajeAudit = AdjustSql(
        "Eliminar Articulo de Factura de Venta NRO/ID {$nro_documento}/({$pedido}) Articulo {$articulo}"
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
                '{$articulo}',
                ''
            )
    ");

    /**
     * Verificar si quedan renglones.
     */
    $renglonesRestantes = intval(ExecuteScalar("
        SELECT COUNT(*)
        FROM entradas_salidas
        WHERE id_documento = {$pedido}
          AND tipo_documento = '{$tipo_documento}'
    "));

    if ($renglonesRestantes <= 0) {
        ExecuteStatement("
            DELETE FROM salidas
            WHERE id = {$pedido}
              AND tipo_documento = '{$tipo_documento}'
        ");

        $conn->commit();

        TdcfcvJsonOk([
            "pedido" => "0",
            "total" => "0.00",
            "total_usd" => "0.00",
            "monto_sin_descuento" => "0.00",
            "total_usd_sin_descuento" => "0.00",
            "renglones" => "0",
            "unidades" => "0",
            "nro_documento" => "0000000",
            "mensaje" => "Item eliminado. Documento vacío eliminado."
        ]);
    }

    /**
     * Recalcular totales.
     */
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
            tasa_dia = {$tasa_usd},
            monto_total = {$costo},
            alicuota_iva = {$xalicuota},
            iva = {$iva},
            total = {$total_final},
            unidades = {$unidades},
            monto_usd = {$total_usd_real},
            moneda = '{$monedaSql}',
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
        "mensaje" => "Item eliminado y totales actualizados"
    ]);
} catch (\Throwable $e) {
    if ($conn->isTransactionActive()) {
        $conn->rollBack();
    }

    TdcfcvJsonError("Error eliminando línea: " . $e->getMessage());
}