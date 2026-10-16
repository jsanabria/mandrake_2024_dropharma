<?php

namespace PHPMaker2024\mandrake;

require_once __DIR__ . "/tdcfcv_bootstrap.php";

$pedido = TdcfcvRequestInt("pedido");
$cliente = TdcfcvRequestInt("cliente");
$precioFull = TdcfcvRequestFloat("precioFull");
$descuento = TdcfcvRequestFloat("descuento");
$descuento2 = TdcfcvRequestFloat("descuento2");
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

if ($descuento2 == 100) {
    TdcfcvJsonError("El descuento fabricante del item no puede ser 100%.");
}

if ($descTransferencista == 100) {
    TdcfcvJsonError("El descuento transferencista no puede ser 100%.");
}

if ($descFabricante == 100) {
    TdcfcvJsonError("El descuento fabricante no puede ser 100%.");
}

// Regla especial NC para artículos no inventariables
// ---------------------------------------------------------
// PARAMETRO 065
// Permitir artículo NO inventariable especial en las NC
// ---------------------------------------------------------
$existeParametro065 = intval(ExecuteScalar("
    SELECT COUNT(*)
    FROM parametro
    WHERE codigo = '065'
"));

if ($existeParametro065 == 0) {
    ExecuteStatement("
        INSERT INTO parametro
            (codigo, descripcion, valor1)
        VALUES
            (
                '065',
                'NC ARTICULO NO INVENTARIO',
                'N'
            )
    ");
}

$permite_articulo_no_inventario_nc =
    strtoupper(trim((string) ExecuteScalar("
        SELECT IFNULL(valor1, 'N')
        FROM parametro
        WHERE codigo = '065'
        LIMIT 1
    "))) === 'S';


// Determinar si el artículo actual maneja inventario
$articulo_inventario =
    strtoupper(trim((string) ExecuteScalar("
        SELECT IFNULL(articulo_inventario, 'N')
        FROM articulo
        WHERE id = {$articulo}
        LIMIT 1
    ")));

$es_articulo_inventario = ($articulo_inventario === 'S');

/**
 * Si es NC, validar contra documento afectado.
 */
/**
 * Si es NC, validar contra documento afectado.
 */
if ($consignacion == "NC") {

    // ---------------------------------------------------------
    // 1. Determinar documento origen
    // ---------------------------------------------------------
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
        TdcfcvJsonError(
            "La Nota de Crédito debe afectar a un documento origen."
        );
    }


    // ---------------------------------------------------------
    // 2. Obtener monto base del documento origen
    // ---------------------------------------------------------
    $monto_base_origen = floatval(ExecuteScalar("
        SELECT IFNULL(monto_total, 0)
        FROM salidas
        WHERE id = {$doc_afectado_id}
          AND tipo_documento = '{$tipo_documento}'
        LIMIT 1
    "));

    if ($monto_base_origen <= 0) {
        TdcfcvJsonError(
            "No se pudo determinar el monto base del documento origen."
        );
    }


    // ---------------------------------------------------------
    // 3. NUEVA VALIDACIÓN DEL MONTO TOTAL ACUMULADO DE LA NC
    //
    // Se toma:
    //     artículos ya cargados
    //   + artículo que se está intentando agregar
    //
    // y se aplican los mismos descuentos globales que utiliza
    // posteriormente el cálculo de salidas.monto_total.
    // ---------------------------------------------------------

    $monto_bruto_actual_nc = 0;

    if ($pedido > 0) {
        $monto_bruto_actual_nc = floatval(ExecuteScalar("
            SELECT IFNULL(SUM(precio), 0)
            FROM entradas_salidas
            WHERE id_documento = {$pedido}
              AND tipo_documento = '{$tipo_documento}'
        "));
    }

    // $total es el precio/base de la nueva línea que se va a insertar
    $monto_bruto_proyectado =
        $monto_bruto_actual_nc +
        floatval($total);


    // Aplicar descuento general
    $monto_base_proyectado =
        $monto_bruto_proyectado -
        (
            $monto_bruto_proyectado *
            ($descuentoG / 100)
        );


    // Aplicar descuento transferencista
    $monto_base_proyectado =
        $monto_base_proyectado -
        (
            $monto_base_proyectado *
            ($descTransferencista / 100)
        );


    // Aplicar descuento fabricante
    $monto_base_proyectado =
        $monto_base_proyectado -
        (
            $monto_base_proyectado *
            ($descFabricante / 100)
        );


    $monto_base_proyectado =
        round($monto_base_proyectado, 2);

    $monto_base_origen =
        round($monto_base_origen, 2);


    // ---------------------------------------------------------
    // La totalidad de la NC no puede superar la base
    // del documento origen.
    // Esta validación incluye artículos inventariables
    // y no inventariables.
    // ---------------------------------------------------------
    if ($monto_base_proyectado > $monto_base_origen) {

        $disponible =
            $monto_base_origen -
            (
                $monto_base_proyectado -
                floatval($total)
            );

        TdcfcvJsonError(
            "El monto acumulado de la Nota de Crédito (" .
            number_format($monto_base_proyectado, 2, ',', '.') .
            ") no puede ser mayor al monto base del documento origen (" .
            number_format($monto_base_origen, 2, ',', '.') .
            ")."
        );
    }


    // ---------------------------------------------------------
    // Determinar si el artículo existe en el documento origen
    // ---------------------------------------------------------
    $articulo_existe_en_origen = intval(ExecuteScalar("
        SELECT COUNT(*)
        FROM entradas_salidas
        WHERE id_documento = {$doc_afectado_id}
          AND tipo_documento = '{$tipo_documento}'
          AND articulo = {$articulo}
    ")) > 0;

    // ---------------------------------------------------------
    // 4. REGLA ESPECIAL PARAMETRO 065
    //
    // Si:
    //     parametro 065 = S
    //     Y artículo_inventario <> S
    //
    // permitir agregar UN SOLO artículo no inventariable,
    // aunque no exista en la factura original.
    // ---------------------------------------------------------
    if (
        $permite_articulo_no_inventario_nc &&
        !$es_articulo_inventario &&
        !$articulo_existe_en_origen
    ) {
        // -----------------------------------------------------
        // Verificar si esta NC ya contiene un artículo
        // NO inventariable adicional, es decir, uno que
        // NO pertenecía al documento origen.
        // -----------------------------------------------------
        $cantidad_no_inventario_especial = 0;

        if ($pedido > 0) {

            $cantidad_no_inventario_especial = intval(ExecuteScalar("
                SELECT COUNT(*)
                FROM entradas_salidas es
                INNER JOIN articulo a
                    ON a.id = es.articulo
                WHERE es.id_documento = {$pedido}
                  AND es.tipo_documento = '{$tipo_documento}'
                  AND IFNULL(a.articulo_inventario, 'N') <> 'S'

                  AND NOT EXISTS (
                      SELECT 1
                      FROM entradas_salidas eso
                      WHERE eso.id_documento = {$doc_afectado_id}
                        AND eso.tipo_documento = '{$tipo_documento}'
                        AND eso.articulo = es.articulo
                  )
            "));
        }

        if ($cantidad_no_inventario_especial > 0) {

            TdcfcvJsonError(
                "La Nota de Crédito solamente permite un artículo no inventariable adicional."
            );
        }


        // -----------------------------------------------------
        // NO se valida:
        //
        // - que exista en la factura origen
        // - precio original
        // - cantidad original
        //
        // porque ésta es precisamente la excepción autorizada
        // por el parámetro 065.
        //
        // El monto ya fue validado arriba considerando TODA
        // la Nota de Crédito.
        // -----------------------------------------------------

    } else {

        // ---------------------------------------------------------
        // 5. VALIDACIÓN NORMAL ACTUAL
        //
        // Los artículos inventariables continúan funcionando
        // exactamente como hasta ahora.
        //
        // También los no inventariables continúan con esta lógica
        // cuando parámetro 065 <> S.
        // ---------------------------------------------------------

        $rowOrig = ExecuteRow("
            SELECT
                IFNULL(SUM(cantidad_articulo), 0) AS cantidad_articulo,
                IFNULL(MIN(precio_unidad), 0) AS precio_unidad
            FROM entradas_salidas
            WHERE id_documento = {$doc_afectado_id}
              AND articulo = {$articulo}
              AND tipo_documento = '{$tipo_documento}'
        ");

        $cant_original =
            floatval($rowOrig["cantidad_articulo"] ?? 0);

        $precio_original =
            floatval($rowOrig["precio_unidad"] ?? 0);


        // Debe existir en la factura afectada
        if ($cant_original <= 0) {

            TdcfcvJsonError(
                "El artículo no existe en la factura afectada."
            );
        }


        // Debe conservar el precio de la factura original
        if (abs($precio - $precio_original) > 0.01) {

            TdcfcvJsonError(
                "El precio unidad ({$precio}) no coincide con el de la factura original ({$precio_original})."
            );
        }


        // -----------------------------------------------------
        // Cantidades devueltas previamente
        // -----------------------------------------------------
        $cant_acumulada_nc = floatval(ExecuteScalar("
            SELECT IFNULL(SUM(es.cantidad_articulo), 0)
            FROM entradas_salidas es
            INNER JOIN salidas s
                ON es.id_documento = s.id
            WHERE s.doc_afe = {$doc_afectado_id}
              AND es.articulo = {$articulo}
              AND s.documento = 'NC'
              AND es.tipo_documento = '{$tipo_documento}'
        "));


        if (($cant_acumulada_nc + $cantidad) > $cant_original) {

            $disponible =
                $cant_original -
                $cant_acumulada_nc;

            TdcfcvJsonError(
                "Excede la cantidad disponible. " .
                "Original: {$cant_original}, " .
                "Ya devuelto: {$cant_acumulada_nc}, " .
                "Disponible: {$disponible}"
            );
        }
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
                 descuento, descuento2, descuento3, documento, doc_afectado, doc_afe, igtf_alicuota, fecha_despacho, entregado)
            VALUES
                (NULL, '{$tipo_documento}', '{$usernameSql}', NULL, {$cliente}, '{$notaSql}', 'NUEVO', '{$monedaSql}', 'N',
                 {$descuentoG}, {$descTransferencista}, {$descFabricante}, '{$consignacionSql}', '{$docAfectadoSql}', {$doc_afectado_id},
                    (
                        SELECT alicuota
                        FROM alicuota
                        WHERE codigo = 'IGT'
                          AND activo = 'S'
                        LIMIT 1
                    ), NOW(), 'S')
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
             alicuota, descuento, descuento2, precio_unidad_sin_desc, newdata, lote, fecha_vencimiento)
        VALUES
            (NULL, '{$tipo_documento}', {$pedido}, {$fabricante}, {$articulo}, '{$almacenSql}',
             {$cantidad}, '{$unidad_medida}', {$cantidad_unidad},
             {$cantidad_movimiento}, {$costo_unidad}, {$costo_total_item}, {$precio}, {$total},
             {$alicuota_item}, {$descuento}, {$descuento2}, {$precioFull}, 'S', '{$loteSql}', '{$venceSql}')
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