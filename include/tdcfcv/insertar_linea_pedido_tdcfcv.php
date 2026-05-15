<?php

namespace PHPMaker2024\mandrake;

require_once __DIR__ . "/tdcfcv_bootstrap.php";

$pedido = TdcfcvRequestInt("pedido");
$cliente = TdcfcvRequestInt("cliente");
$precioFull = TdcfcvRequestFloat("precioFull");
$descuento = TdcfcvRequestFloat("descuento");
$precio = TdcfcvRequestFloat("precio");
$moneda = TdcfcvRequestText("moneda", "Bs.");
$total = TdcfcvRequestFloat("total");
$cantidad = TdcfcvRequestInt("cantidad");
$articulo = TdcfcvRequestInt("articulo");
$tasa_usd = TdcfcvRequestFloat("tasa_usd", 1);
$username = TdcfcvCurrentUser();
$descuentoG = TdcfcvRequestFloat("descuentoG");
$descTransferencista = TdcfcvRequestFloat("descTransferencista");
$descFabricante = TdcfcvRequestFloat("descFabricante");
$nota = TdcfcvRequestText("nota");
$consignacion = TdcfcvRequestText("consignacion", "FC");
$doc_afectado = TdcfcvRequestText("doc_afectado");
$lote = TdcfcvRequestText("lote");
$vence = TdcfcvRequestText("vence");

$tipo_documento = "TDCFCV";
$tasa_usd = ($tasa_usd <= 0 ? 1 : $tasa_usd);
$vence = ($vence === "" ? "1990-01-01" : $vence);
$doc_afectado_id = 0;

if ($pedido > 0 && $cliente <= 0) {
    $cliente = intval(ExecuteScalar("
        SELECT cliente
        FROM salidas
        WHERE id = {$pedido}
          AND tipo_documento = '{$tipo_documento}'
        LIMIT 1
    "));
}

if ($cliente <= 0) {
    TdcfcvJsonError("Cliente no válido.");
}

if ($articulo <= 0) {
    TdcfcvJsonError("Artículo no válido.");
}

if ($cantidad <= 0) {
    TdcfcvJsonError("La cantidad debe ser mayor a cero.");
}

if ($precioFull <= 0) {
    TdcfcvJsonError("El precio debe ser mayor a cero.");
}

if ($descuento == 100) {
    TdcfcvJsonError("El descuento del item no puede ser 100%.");
}

if ($descTransferencista == 100) {
    TdcfcvJsonError("El descuento transferencista no puede ser 100%.");
}

if ($descFabricante == 100) {
    TdcfcvJsonError("El descuento fabricante no puede ser 100%.");
}

/**
 * Si es NC, validar contra documento afectado.
 */
if ($consignacion == "NC") {
    if ($pedido > 0) {
        $doc_afectado_id = intval(ExecuteScalar("
            SELECT IFNULL(doc_afe, 0)
            FROM salidas
            WHERE id = {$pedido}
            LIMIT 1
        "));
    }

    if ($doc_afectado_id <= 0 && $doc_afectado !== "") {
        $doc_afectado_id = intval($doc_afectado);
    }

    if ($doc_afectado_id <= 0) {
        TdcfcvJsonError("La Nota de Crédito debe afectar a un documento origen.");
    }

    $rowOrig = ExecuteRow("
        SELECT
            IFNULL(SUM(cantidad_articulo), 0) AS cantidad_articulo,
            IFNULL(MIN(precio_unidad), 0) AS precio_unidad
        FROM entradas_salidas
        WHERE id_documento = {$doc_afectado_id}
          AND articulo = {$articulo}
          AND tipo_documento = '{$tipo_documento}'
    ");

    $cant_original = floatval($rowOrig["cantidad_articulo"] ?? 0);
    $precio_original = floatval($rowOrig["precio_unidad"] ?? 0);

    if ($cant_original <= 0) {
        TdcfcvJsonError("El artículo no existe en la factura afectada.");
    }

    if (abs($precio - $precio_original) > 0.01) {
        TdcfcvJsonError("El precio unidad ({$precio}) no coincide con el de la factura original ({$precio_original}).");
    }

    $cant_acumulada_nc = floatval(ExecuteScalar("
        SELECT IFNULL(SUM(es.cantidad_articulo), 0)
        FROM entradas_salidas es
        INNER JOIN salidas s ON es.id_documento = s.id
        WHERE s.doc_afe = {$doc_afectado_id}
          AND es.articulo = {$articulo}
          AND s.documento = 'NC'
          AND es.tipo_documento = '{$tipo_documento}'
    "));

    if (($cant_acumulada_nc + $cantidad) > $cant_original) {
        $disponible = $cant_original - $cant_acumulada_nc;
        TdcfcvJsonError("Excede la cantidad disponible. Original: {$cant_original}, Ya devuelto: {$cant_acumulada_nc}, Disponible: {$disponible}");
    }
}

/**
 * Lote / almacén.
 */
$alma = "0";
$tipo_doc_inv = ExecuteScalar("
    SELECT valor1
    FROM parametro
    WHERE codigo = '050'
    LIMIT 1
");

if ($tipo_doc_inv == "TDCFCV") {
    $xlote = explode("|", $lote);
    $lote = $xlote[0] ?? "";
    $vence = $xlote[1] ?? $vence;
    $alma = $xlote[3] ?? "0";
}

$rowArt = ExecuteRow("
    SELECT
        alicuota,
        cantidad_por_unidad_medida,
        ultimo_costo,
        fabricante,
        unidad_medida_defecto
    FROM articulo
    WHERE id = {$articulo}
    LIMIT 1
");

if (!$rowArt) {
    TdcfcvJsonError("No se encontró el artículo.");
}

$codigo_alicuota = AdjustSql($rowArt["alicuota"] ?? "");
$cantidad_unidad = intval($rowArt["cantidad_por_unidad_medida"] ?? 1);
$cantidad_unidad = ($cantidad_unidad <= 0 ? 1 : $cantidad_unidad);
$fabricante = intval($rowArt["fabricante"] ?? 0);
$unidad_medida = AdjustSql($rowArt["unidad_medida_defecto"] ?? "");
$costo_unidad = floatval($rowArt["ultimo_costo"] ?? 0);

$alicuota_item = floatval(ExecuteScalar("
    SELECT IFNULL(alicuota, 0)
    FROM alicuota
    WHERE codigo = '{$codigo_alicuota}'
      AND activo = 'S'
    LIMIT 1
"));

$almacenDefault = ExecuteScalar("
    SELECT valor1
    FROM parametro
    WHERE codigo = '002'
    LIMIT 1
");

$almacen = ($alma == "0" || $alma == "") ? $almacenDefault : $alma;

$cantidad_movimiento = ($consignacion == "NC")
    ? ($cantidad_unidad * $cantidad)
    : (-1 * ($cantidad_unidad * $cantidad));

$costo_total_item = $cantidad * $costo_unidad;

$notaSql = AdjustSql($nota);
$monedaSql = AdjustSql($moneda);
$usernameSql = AdjustSql($username);
$consignacionSql = AdjustSql($consignacion);
$docAfectadoSql = AdjustSql($doc_afectado);
$loteSql = AdjustSql($lote);
$venceSql = AdjustSql($vence);
$almacenSql = AdjustSql($almacen);

$conn = Conn();

try {
    $conn->beginTransaction();

    if ($pedido == 0) {
        $conn->executeStatement("
            INSERT INTO salidas
                (id, tipo_documento, username, fecha, cliente, nota, estatus, moneda, consignacion,
                 descuento, descuento2, descuento3, documento, doc_afectado, doc_afe)
            VALUES
                (NULL, '{$tipo_documento}', '{$usernameSql}', NULL, {$cliente}, '{$notaSql}', 'NUEVO', '{$monedaSql}', 'N',
                 {$descuentoG}, {$descTransferencista}, {$descFabricante}, '{$consignacionSql}', '{$docAfectadoSql}', {$doc_afectado_id})
        ");

        $pedido = intval($conn->lastInsertId());

        if ($pedido <= 0) {
            $pedido = intval(ExecuteScalar("
                SELECT MAX(id)
                FROM salidas
                WHERE tipo_documento = '{$tipo_documento}'
                  AND cliente = {$cliente}
                  AND username = '{$usernameSql}'
            "));
        }

        if ($pedido <= 0) {
            throw new \Exception("No se pudo obtener el ID del documento creado.");
        }

        $nro_documento = "";
    } else {
        ExecuteStatement("
            UPDATE salidas
            SET
                descuento = {$descuentoG},
                descuento2 = {$descTransferencista},
                descuento3 = {$descFabricante},
                nota = '{$notaSql}',
                tasa_dia = {$tasa_usd},
                moneda = '{$monedaSql}'
            WHERE id = {$pedido}
              AND tipo_documento = '{$tipo_documento}'
        ");

        $nro_documento = ExecuteScalar("
            SELECT IFNULL(nro_documento, '')
            FROM salidas
            WHERE id = {$pedido}
            LIMIT 1
        ");
    }

    $conn->executeStatement("
        INSERT INTO entradas_salidas
            (id, tipo_documento, id_documento, fabricante, articulo, almacen,
             cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida,
             cantidad_movimiento, costo_unidad, costo, precio_unidad, precio,
             alicuota, descuento, precio_unidad_sin_desc, newdata, lote, fecha_vencimiento)
        VALUES
            (NULL, '{$tipo_documento}', {$pedido}, {$fabricante}, {$articulo}, '{$almacenSql}',
             {$cantidad}, '{$unidad_medida}', {$cantidad_unidad},
             {$cantidad_movimiento}, {$costo_unidad}, {$costo_total_item}, {$precio}, {$total},
             {$alicuota_item}, {$descuento}, {$precioFull}, 'S', '{$loteSql}', '{$venceSql}')
    ");

    $id_item = intval($conn->lastInsertId());

    if ($id_item <= 0) {
        $id_item = intval(ExecuteScalar("
            SELECT MAX(id)
            FROM entradas_salidas
            WHERE tipo_documento = '{$tipo_documento}'
              AND id_documento = {$pedido}
              AND articulo = {$articulo}
        "));
    }

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
            IFNULL(SUM(precio), 0) AS precio_unidad_sin_desc,
            IFNULL(SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ({$descuentoG}/100)), 0)), 0) AS exento,
            IFNULL(SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ({$descuentoG}/100)))), 0) AS gravado,
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
    $xExento = $xExento - ($xExento * ($descTransferencista / 100));
    $xExento = $xExento - ($xExento * ($descFabricante / 100));

    $xGravado = floatval($rowTot["gravado"] ?? 0);
    $xGravado = $xGravado - ($xGravado * ($descTransferencista / 100));
    $xGravado = $xGravado - ($xGravado * ($descFabricante / 100));

    $costo_final = $xExento + $xGravado;
    $iva_final = $xGravado * ($xalicuota / 100);
    $total_final = $costo_final + $iva_final;

    $esBs = (strtoupper(substr(trim($moneda), 0, 3)) == "BS.");

    $monto_usd_real = $esBs
        ? round(($total_final / $tasa_usd), 2)
        : round($total_final, 2);

    ExecuteStatement("
        UPDATE salidas
        SET
            monto_total = {$costo_final},
            alicuota_iva = {$xalicuota},
            iva = {$iva_final},
            total = {$total_final},
            tasa_dia = {$tasa_usd},
            unidades = {$unidades},
            monto_usd = {$monto_usd_real},
            moneda = '{$monedaSql}',
            monto_sin_descuento = {$monto_sin_descuento}
        WHERE id = {$pedido}
          AND tipo_documento = '{$tipo_documento}'
    ");

    $nro_documento = ExecuteScalar("
        SELECT IFNULL(nro_documento, '')
        FROM salidas
        WHERE id = {$pedido}
        LIMIT 1
    ");

    ExecuteStatement("
        INSERT INTO audittrail
            (id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
        VALUES
            (NULL, '" . date("Y-m-d H:i:s") . "',
             'Insertar Articulo en Factura de Venta NRO/ID {$nro_documento}/({$pedido}) Articulo {$articulo}',
             '{$usernameSql}', 'A', 'view_out_tdcfcv', 'id', '{$pedido}', '', '{$articulo}')
    ");

    $conn->commit();

    TdcfcvJsonOk([
        "pedido" => (string)$pedido,
        "id_item" => (string)$id_item,
        "total" => (string)($esBs ? round(($total_final / $tasa_usd), 2) : round($total_final, 2)),
        "total_usd" => (string)($esBs ? round($total_final, 2) : round(($total_final * $tasa_usd), 2)),
        "monto_sin_descuento" => (string)($esBs ? round(($monto_sin_descuento / $tasa_usd), 2) : round($monto_sin_descuento, 2)),
        "total_usd_sin_descuento" => (string)($esBs ? round($monto_sin_descuento, 2) : round(($monto_sin_descuento * $tasa_usd), 2)),
        "renglones" => (string)$renglones,
        "unidades" => (string)$unidades,
        "nro_documento" => (string)$nro_documento,
        "mensaje" => "Item insertado y totales actualizados"
    ]);
} catch (\Throwable $e) {
    if ($conn->isTransactionActive()) {
        $conn->rollBack();
    }

    TdcfcvJsonError("Error insertando línea: " . $e->getMessage());
}