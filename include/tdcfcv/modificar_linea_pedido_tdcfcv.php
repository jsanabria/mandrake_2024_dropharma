<?php

namespace PHPMaker2024\mandrake;

require_once __DIR__ . "/tdcfcv_bootstrap.php";

$pedido = TdcfcvRequestInt("pedido");
$id_item = TdcfcvRequestInt("id_item");
$articulo = TdcfcvRequestInt("articulo");

$cantidad = TdcfcvRequestFloat("cantidad");
$precio_full = TdcfcvRequestFloat("precio_full");
$desc_item = TdcfcvRequestFloat("descuento_item");
$desc_item2 = TdcfcvRequestFloat("descuento2_item");

$lote = TdcfcvRequestText("lote");
$vence = TdcfcvRequestText("vence");

$tasa_usd = TdcfcvRequestFloat("tasa_usd", 1);
$moneda = TdcfcvRequestText("moneda", "Bs.");
$username = TdcfcvCurrentUser();

$descuento_global = TdcfcvRequestFloat("descuento_global");
$descTransferencista = TdcfcvRequestFloat("descTransferencista");
$descFabricante = TdcfcvRequestFloat("descFabricante");
$nota = TdcfcvRequestText("nota");

$tipo_documento = "TDCFCV";
$tasa_usd = ($tasa_usd <= 0 ? 1 : $tasa_usd);
$vence = ($vence === "" ? "1990-01-01" : $vence);

if ($pedido <= 0) {
    TdcfcvJsonError("Pedido no válido.");
}

if ($id_item <= 0) {
    TdcfcvJsonError("Item no válido.");
}

if ($articulo <= 0) {
    TdcfcvJsonError("Artículo no válido.");
}

if ($cantidad <= 0) {
    TdcfcvJsonError("La cantidad debe ser mayor a cero.");
}

if ($precio_full <= 0) {
    TdcfcvJsonError("El precio debe ser mayor a cero.");
}

if ($desc_item == 100) {
    TdcfcvJsonError("El descuento del item no puede ser 100%.");
}

if ($desc_item2 == 100) {
    TdcfcvJsonError("El descuento fabricante del item no puede ser 100%.");
}

if ($descTransferencista == 100) {
    TdcfcvJsonError("El descuento transferencista no puede ser 100%.");
}

if ($descFabricante == 100) {
    TdcfcvJsonError("El descuento fabricante no puede ser 100%.");
}

$rowCab = ExecuteRow("
    SELECT
        IFNULL(documento, '') AS documento,
        IFNULL(doc_afe, 0) AS doc_afe,
        IFNULL(nro_documento, '') AS nro_documento
    FROM salidas
    WHERE id = {$pedido}
      AND tipo_documento = '{$tipo_documento}'
    LIMIT 1
");

if (!$rowCab) {
    TdcfcvJsonError("No se encontró la cabecera del documento.");
}

$tipo_doc_actual = $rowCab["documento"];
$doc_afectado = intval($rowCab["doc_afe"]);
$nro_documento = $rowCab["nro_documento"];

$rowItemActual = ExecuteRow("
    SELECT
        IFNULL(cantidad_articulo, 0) AS cantidad_articulo,
        IFNULL(precio_unidad_sin_desc, 0) AS precio_unidad_sin_desc,
        IFNULL(descuento, 0) AS descuento,
        IFNULL(descuento2, 0) AS descuento2,
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

if (!$rowItemActual) {
    TdcfcvJsonError("No se encontró la línea a modificar.");
}

$oldCantidad = floatval($rowItemActual["cantidad_articulo"]);
$oldPrecioFull = floatval($rowItemActual["precio_unidad_sin_desc"]);
$oldDescItem = floatval($rowItemActual["descuento"]);
$oldDescItem2 = floatval($rowItemActual["descuento2"]);
$oldPrecio = floatval($rowItemActual["precio_unidad"]);
$oldTotal = floatval($rowItemActual["precio"]);
$oldLote = $rowItemActual["lote"];
$oldVence = $rowItemActual["fecha_vencimiento"];

$nuevo_precio_neto = $precio_full - ($precio_full * ($desc_item / 100));
$nuevo_precio_neto = $nuevo_precio_neto - ($nuevo_precio_neto * ($desc_item2 / 100));
$nuevo_total_linea = $nuevo_precio_neto * $cantidad;

/**
 * Validación especial para Nota de Crédito.
 */
if ($tipo_doc_actual == "NC") {

    if ($doc_afectado <= 0) {
        TdcfcvJsonError(
            "La Nota de Crédito debe afectar a un documento origen."
        );
    }


    // ---------------------------------------------------------
    // 1. Parámetro 065
    // ---------------------------------------------------------
    $permite_articulo_no_inventario_nc =
        strtoupper(trim((string) ExecuteScalar("
            SELECT IFNULL(valor1, 'N')
            FROM parametro
            WHERE codigo = '065'
            LIMIT 1
        "))) === 'S';


    // ---------------------------------------------------------
    // 2. Determinar si el artículo maneja inventario
    // ---------------------------------------------------------
    $articulo_inventario =
        strtoupper(trim((string) ExecuteScalar("
            SELECT IFNULL(articulo_inventario, 'N')
            FROM articulo
            WHERE id = {$articulo}
            LIMIT 1
        ")));

    $es_articulo_inventario =
        ($articulo_inventario === 'S');


    // ---------------------------------------------------------
    // 3. Determinar si el artículo existe en factura origen
    // ---------------------------------------------------------
    $articulo_existe_en_origen =
        intval(ExecuteScalar("
            SELECT COUNT(*)
            FROM entradas_salidas
            WHERE id_documento = {$doc_afectado}
              AND tipo_documento = '{$tipo_documento}'
              AND articulo = {$articulo}
        ")) > 0;


    // ---------------------------------------------------------
    // 4. Obtener monto base de la factura origen
    // ---------------------------------------------------------
    $monto_base_origen = floatval(ExecuteScalar("
        SELECT IFNULL(monto_total, 0)
        FROM salidas
        WHERE id = {$doc_afectado}
          AND tipo_documento = '{$tipo_documento}'
        LIMIT 1
    "));

    if ($monto_base_origen <= 0) {
        TdcfcvJsonError(
            "No se pudo determinar el monto base del documento origen."
        );
    }


    // ---------------------------------------------------------
    // 5. Calcular monto proyectado de TODA la NC
    //
    // IMPORTANTE:
    // Se excluye el renglón que estamos modificando y luego
    // se agrega $nuevo_total_linea.
    // ---------------------------------------------------------
    $monto_bruto_otros_items = floatval(ExecuteScalar("
        SELECT IFNULL(SUM(precio), 0)
        FROM entradas_salidas
        WHERE id_documento = {$pedido}
          AND tipo_documento = '{$tipo_documento}'
          AND id <> {$id_item}
    "));

    $monto_bruto_proyectado =
        $monto_bruto_otros_items +
        $nuevo_total_linea;


    // Descuento global
    $monto_base_proyectado =
        $monto_bruto_proyectado -
        (
            $monto_bruto_proyectado *
            ($descuento_global / 100)
        );


    // Descuento transferencista
    $monto_base_proyectado =
        $monto_base_proyectado -
        (
            $monto_base_proyectado *
            ($descTransferencista / 100)
        );


    // Descuento fabricante
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
    // 6. Toda la NC, incluyendo la línea modificada,
    //    debe estar dentro del monto base del origen
    // ---------------------------------------------------------
    if ($monto_base_proyectado > $monto_base_origen) {

        TdcfcvJsonError(
            "El monto acumulado de la Nota de Crédito (" .
            number_format($monto_base_proyectado, 2, ',', '.') .
            ") no puede ser mayor al monto base del documento origen (" .
            number_format($monto_base_origen, 2, ',', '.') .
            ")."
        );
    }


    // ---------------------------------------------------------
    // 7. Determinar si estamos modificando el artículo
    //    especial autorizado por parámetro 065
    //
    // Debe cumplir:
    //   065 = S
    //   articulo_inventario <> S
    //   NO existe en factura origen
    // ---------------------------------------------------------
    $es_articulo_especial_nc =
        (
            $permite_articulo_no_inventario_nc &&
            !$es_articulo_inventario &&
            !$articulo_existe_en_origen
        );


    if ($es_articulo_especial_nc) {

        // -----------------------------------------------------
        // El artículo actual ES el artículo especial.
        //
        // Verificar que no exista OTRO artículo especial
        // distinto al renglón que estamos modificando.
        // -----------------------------------------------------
        $cantidad_otros_especiales = intval(ExecuteScalar("
            SELECT COUNT(*)
            FROM entradas_salidas es

            INNER JOIN articulo a
                ON a.id = es.articulo

            WHERE es.id_documento = {$pedido}
              AND es.tipo_documento = '{$tipo_documento}'
              AND es.id <> {$id_item}
              AND IFNULL(a.articulo_inventario, 'N') <> 'S'

              AND NOT EXISTS (
                  SELECT 1
                  FROM entradas_salidas eso
                  WHERE eso.id_documento = {$doc_afectado}
                    AND eso.tipo_documento = '{$tipo_documento}'
                    AND eso.articulo = es.articulo
              )
        "));

        if ($cantidad_otros_especiales > 0) {

            TdcfcvJsonError(
                "La Nota de Crédito solamente permite un artículo no inventariable adicional."
            );
        }


        // -----------------------------------------------------
        // Al artículo especial NO se le valida:
        //
        // - existencia en factura origen
        // - precio original
        // - cantidad original
        //
        // Sí queda limitado por el monto acumulado de la NC,
        // validado anteriormente.
        // -----------------------------------------------------

    } else {

        // -----------------------------------------------------
        // 8. VALIDACIÓN NORMAL
        //
        // Aplica a:
        //   - artículos inventariables
        //   - artículos no inventariables que sí estaban
        //     originalmente en la factura
        //   - artículos no inventariables cuando 065 = N
        // -----------------------------------------------------
        $rowOrig = ExecuteRow("
            SELECT
                IFNULL(SUM(cantidad_articulo), 0) AS cantidad_articulo,
                IFNULL(MIN(precio_unidad), 0) AS precio_unidad
            FROM entradas_salidas
            WHERE id_documento = {$doc_afectado}
              AND articulo = {$articulo}
              AND tipo_documento = '{$tipo_documento}'
        ");

        $cant_original =
            floatval($rowOrig["cantidad_articulo"] ?? 0);

        $precio_original =
            floatval($rowOrig["precio_unidad"] ?? 0);


        // Debe existir en factura origen
        if ($cant_original <= 0) {

            TdcfcvJsonError(
                "El artículo no existe en la factura afectada."
            );
        }


        // Debe conservar precio original
        if (abs($nuevo_precio_neto - $precio_original) > 0.01) {

            TdcfcvJsonError(
                "El nuevo precio neto ({$nuevo_precio_neto}) debe ser igual al original ({$precio_original})."
            );
        }


        // -----------------------------------------------------
        // Cantidad acumulada de otras NC.
        //
        // Se excluye $id_item actual porque estamos
        // reemplazando su cantidad.
        // -----------------------------------------------------
        $cant_acumulada_nc = floatval(ExecuteScalar("
            SELECT IFNULL(SUM(es.cantidad_articulo), 0)
            FROM entradas_salidas es

            INNER JOIN salidas s
                ON es.id_documento = s.id

            WHERE s.doc_afe = {$doc_afectado}
              AND es.articulo = {$articulo}
              AND s.documento = 'NC'
              AND es.tipo_documento = '{$tipo_documento}'
              AND es.id <> {$id_item}
        "));


        if (($cant_acumulada_nc + $cantidad) > $cant_original) {

            $disponible =
                $cant_original -
                $cant_acumulada_nc;

            TdcfcvJsonError(
                "La cantidad solicitada ({$cantidad}) supera la disponible. " .
                "Original: {$cant_original}, " .
                "Ya devuelto: {$cant_acumulada_nc}, " .
                "Disponible: {$disponible}."
            );
        }
    }
}

$cant_mov = ($tipo_doc_actual == "NC") ? $cantidad : ($cantidad * -1);

$loteSql = AdjustSql($lote);
$venceSql = AdjustSql($vence);
$monedaSql = AdjustSql($moneda);
$notaSql = AdjustSql($nota);
$usernameSql = AdjustSql($username);

$conn = Conn();

try {
    $conn->beginTransaction();

    ExecuteStatement("
        UPDATE entradas_salidas
        SET
            cantidad_articulo = {$cantidad},
            cantidad_movimiento = {$cant_mov},
            precio_unidad_sin_desc = {$precio_full},
            descuento = {$desc_item},
            descuento2 = {$desc_item2},
            precio_unidad = {$nuevo_precio_neto},
            precio = {$nuevo_total_linea},
            lote = '{$loteSql}',
            fecha_vencimiento = '{$venceSql}'
        WHERE id = {$id_item}
          AND id_documento = {$pedido}
          AND tipo_documento = '{$tipo_documento}'
    ");

    ExecuteStatement("
        UPDATE salidas
        SET
            descuento = {$descuento_global},
            descuento2 = {$descTransferencista},
            descuento3 = {$descFabricante},
            nota = '{$notaSql}',
            tasa_dia = {$tasa_usd},
            moneda = '{$monedaSql}'
        WHERE id = {$pedido}
          AND tipo_documento = '{$tipo_documento}'
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
            IFNULL(SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ({$descuento_global}/100)), 0)), 0) AS exento,
            IFNULL(SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ({$descuento_global}/100)))), 0) AS gravado,
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

    $nro_documento = ExecuteScalar("
        SELECT IFNULL(nro_documento, '')
        FROM salidas
        WHERE id = {$pedido}
          AND tipo_documento = '{$tipo_documento}'
        LIMIT 1
    ");

    $oldValue = AdjustSql(
        "Cant: {$oldCantidad}; PrecioFull: {$oldPrecioFull}; Desc1: {$oldDescItem}; Desc2: {$oldDescItem2}; Precio: {$oldPrecio}; Total: {$oldTotal}; Lote: {$oldLote}; Vence: {$oldVence}"
    );

    $newValue = AdjustSql(
        "Cant: {$cantidad}; PrecioFull: {$precio_full}; Desc1: {$desc_item}; Desc2: {$desc_item2}; Precio: {$nuevo_precio_neto}; Total: {$nuevo_total_linea}; Lote: {$lote}; Vence: {$vence}"
    );

    $mensajeAudit = AdjustSql(
        "Modificar Articulo de Factura de Venta NRO/ID {$nro_documento}/({$pedido}) Articulo {$articulo}"
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
                '{$oldValue}',
                '{$newValue}'
            )
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
        "mensaje" => "Item modificado y totales actualizados"
    ]);
} catch (\Throwable $e) {
    if ($conn->isTransactionActive()) {
        $conn->rollBack();
    }

    TdcfcvJsonError("Error modificando línea: " . $e->getMessage());
}