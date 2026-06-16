<?php

namespace PHPMaker2024\mandrake;

// Page object
$CrearNotaEntregaGuardar = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
/**
 * CrearNotaEntregaGuardar.php
 *
 * Adaptado para CrearNotaEntrega.php optimizado:
 * - Valida TODOS los renglones antes de crear la cabecera de la Nota de Entrega.
 * - Si ocurre un error, indica el artículo y el renglón que lo produce.
 * - Recibe renglones FEFO ya divididos por lote desde la pantalla.
 * - Valida existencia real acumulada por articulo/lote/fecha/almacen.
 * - Usa ReservarConsecutivoDocumento("TDCNET", "DOC") en lugar de MAX(nro_documento)+1.
 * - Hace limpieza manual si algo falla, útil cuando las tablas están en MyISAM.
 */

function CnegSql($value)
{
    return AdjustSql(trim((string)$value));
}

function CnegFechaSql($fecha)
{
    $fecha = trim((string)$fecha);
    return ($fecha == "" || $fecha == "0000-00-00") ? "1990-01-01" : $fecha;
}

function CnegParam($codigo, $default = "")
{
    $codigo = CnegSql($codigo);
    $valor = ExecuteScalar("SELECT valor1 FROM parametro WHERE codigo = '$codigo' LIMIT 1");
    return ($valor === null || $valor === false || $valor === "") ? $default : $valor;
}

function CnegMoney($value)
{
    return floatval($value);
}

function CnegArticuloNombre($articulo)
{
    $articulo = intval($articulo);

    if ($articulo <= 0) {
        return "Artículo no indicado";
    }

    $sql = "SELECT
                IFNULL(codigo, '') AS codigo,
                TRIM(CONCAT(
                    IFNULL(nombre_comercial, ''),
                    ' ',
                    IFNULL(principio_activo, ''),
                    ' ',
                    IFNULL(presentacion, '')
                )) AS descripcion
            FROM articulo
            WHERE id = $articulo
            LIMIT 1";
    $row = ExecuteRow($sql);

    if (!$row) {
        return "Artículo ID $articulo";
    }

    $codigo = trim((string)($row["codigo"] ?? ""));
    $descripcion = trim((string)($row["descripcion"] ?? ""));

    if ($codigo != "" && $descripcion != "") {
        return "$codigo - $descripcion";
    }

    if ($descripcion != "") {
        return $descripcion;
    }

    if ($codigo != "") {
        return $codigo;
    }

    return "Artículo ID $articulo";
}

function CnegErrorArticulo($mensaje, $articulo = 0, $renglon = 0)
{
    $prefijo = "";

    if ($articulo > 0) {
        $prefijo .= "Artículo: " . CnegArticuloNombre($articulo) . ". ";
    }

    if ($renglon > 0) {
        $prefijo .= "Renglón: " . intval($renglon) . ". ";
    }

    throw new \Exception($prefijo . $mensaje);
}

function CnegExistencia($articulo, $lote, $fecha_vencimiento, $almacen)
{
    $articulo = intval($articulo);
    $lote = CnegSql($lote);
    $fecha_vencimiento = CnegSql(CnegFechaSql($fecha_vencimiento));
    $almacen = CnegSql($almacen);
    $tipo_documento_inventario = CnegSql(CnegParam("050", "TDCNET"));

    // Parámetro 110:
    // S = validar existencia solo contra el almacén recibido.
    // N = validar existencia contra todos los almacenes con movimiento.
    $param_no_aplicar_almacenes = strtoupper(trim((string)CnegParam("110", "S")));
    $filtroAlmacen = "";

    if ($param_no_aplicar_almacenes == "S") {
        $filtroAlmacen = " AND a.almacen = '$almacen' ";
    }

    $sql = "
        SELECT IFNULL(SUM(x.cantidad_movimiento), 0) AS existencia
        FROM (
            SELECT a.cantidad_movimiento
            FROM entradas_salidas AS a
            JOIN entradas AS b ON b.tipo_documento = a.tipo_documento
                              AND b.id = a.id_documento
            JOIN almacen AS c ON c.codigo = a.almacen
                              AND c.movimiento = 'S'
            WHERE (
                    (a.tipo_documento = 'TDCAEN' AND b.estatus <> 'ANULADO')
                    OR
                    (a.tipo_documento = 'TDCNRP' AND a.check_ne = 'S' AND b.estatus <> 'ANULADO')
                  )
              AND a.articulo = $articulo
              AND IFNULL(a.lote, '') = '$lote'
              AND IFNULL(a.fecha_vencimiento, '1990-01-01') = '$fecha_vencimiento'
              $filtroAlmacen
              AND a.newdata = 'S'

            UNION ALL

            SELECT a.cantidad_movimiento
            FROM entradas_salidas AS a
            JOIN salidas AS b ON b.tipo_documento = a.tipo_documento
                             AND b.id = a.id_documento
            JOIN almacen AS c ON c.codigo = a.almacen
                              AND c.movimiento = 'S'
            WHERE (
                    -- (a.tipo_documento = 'TDCPDV' AND b.estatus = 'NUEVO')
                    -- OR
                    (a.tipo_documento IN ('$tipo_documento_inventario', 'TDCASA') AND b.estatus <> 'ANULADO')
                  )
              AND a.articulo = $articulo
              AND IFNULL(a.lote, '') = '$lote'
              AND IFNULL(a.fecha_vencimiento, '1990-01-01') = '$fecha_vencimiento'
              $filtroAlmacen
              AND a.newdata = 'S'
        ) AS x";

    return floatval(ExecuteScalar($sql));
}

$new_id = 0;
$tipo_documento = "TDCNET";
$transaccionIniciada = false;
$id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;

try {
    $cantidad = isset($_POST["cantidad"]) ? intval($_POST["cantidad"]) : 0;
    $nota = CnegSql($_POST["nota"] ?? "");
    $username = CnegSql($_POST["username"] ?? CurrentUserName());

    if ($id <= 0) {
        throw new \Exception("Documento origen inválido.");
    }

    if ($cantidad <= 0) {
        throw new \Exception("No se recibieron renglones para crear la Nota de Entrega.");
    }

    $tasa = floatval(ExecuteScalar("SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 1"));
    if ($tasa <= 0) {
        $tasa = 1;
    }

    $moneda = CnegSql(ExecuteScalar("SELECT valor1 FROM parametro WHERE codigo = '006' AND valor2 = 'default' LIMIT 1"));
    if ($moneda == "") {
        $moneda = "Bs.";
    }

    /**** Consulto el tipo de documento y cliente ****/
    $sql = "SELECT tipo_documento, cliente, estatus FROM salidas WHERE id = $id LIMIT 1";
    $row = ExecuteRow($sql);

    if (!$row) {
        throw new \Exception("No se encontró el documento origen.");
    }

    $tipo = $row["tipo_documento"] ?? "";
    $cliente = intval($row["cliente"] ?? 0);
    $estatus = $row["estatus"] ?? "";

    if ($tipo != "TDCFCV" && $estatus == "PROCESADO") {
        header("Location: YaFueProcesado");
        die();
    }

    /**** Consulto si el cliente compra a consignación ****/
    $consignacion = CnegSql(ExecuteScalar("SELECT consignacion FROM cliente WHERE id = $cliente LIMIT 1"));

    if (!function_exists(__NAMESPACE__ . "\\ReservarConsecutivoDocumento")) {
        throw new \Exception("No existe la función ReservarConsecutivoDocumento() en Global Code.");
    }

    /**************************************************************************
     * FASE 1: VALIDACIÓN COMPLETA
     * Importante: no se crea cabecera ni se reserva consecutivo hasta que todos
     * los renglones estén validados.
     **************************************************************************/
    $detalleValidado = [];
    $articulosProcesados = [];
    $consumoPorLote = [];
    $consumoPorDetalle = [];

    for ($i = 0; $i < $cantidad; $i++) {
		if (
		    isset($_POST["eliminar_" . $i]) &&
		    $_POST["eliminar_" . $i] == "S"
		) {
		    continue;
		}
    	
        if (!isset($_POST["id_$i"])) {
            continue;
        }

        $renglon = $i + 1;
        $dt = intval($_POST["id_$i"] ?? 0);
        $lot = trim((string)($_POST["lote_$i"] ?? ""));
        $cnt = floatval($_POST["cantidad_$i"] ?? 0);
        $un = CnegSql($_POST["unidad_$i"] ?? "");
        $articulo = intval($_POST["articulo_$i"] ?? 0);

        if ($articulo <= 0) {
            CnegErrorArticulo("Código de artículo inválido.", 0, $renglon);
        }

        if ($dt <= 0 || $lot == "" || $cnt <= 0 || $un == "") {
            CnegErrorArticulo("Datos incompletos para crear la Nota de Entrega.", $articulo, $renglon);
        }

        $myArr = explode("|", $lot);
        $lote = CnegSql($myArr[0] ?? "");
        $fecha_vencimiento = CnegSql(CnegFechaSql($myArr[1] ?? ""));
        $almacen = CnegSql($myArr[3] ?? "");

        if ($almacen == "") {
            CnegErrorArticulo("Almacén no recibido.", $articulo, $renglon);
        }

        // Se mantiene factor 1 para compatibilidad con el proceso anterior y con CrearNotaEntrega optimizado.
        $cantidad_um = 1;
        $asignado = $cnt * $cantidad_um;

        /**** Precio y descuentos desde el documento origen ****/
        $sql = "SELECT
                    a.articulo,
                    a.cantidad_movimiento,
                    a.precio_unidad,
                    IFNULL(a.descuento, 0.00) AS descuento,
                    IFNULL(a.descuento2, 0.00) AS descuento2,
                    IFNULL(a.precio_unidad_sin_desc, a.precio_unidad) AS precio_unidad_sin_desc
                FROM entradas_salidas AS a
                WHERE a.id = $dt
                LIMIT 1";
        $rowDet = ExecuteRow($sql);

        if (!$rowDet) {
            CnegErrorArticulo("No se encontró el detalle origen.", $articulo, $renglon);
        }

        $articuloOrigen = intval($rowDet["articulo"] ?? 0);
        if ($articuloOrigen != $articulo) {
            CnegErrorArticulo("El artículo recibido no coincide con el detalle origen.", $articulo, $renglon);
        }

        // Validar que la suma de renglones FEFO no exceda lo solicitado en el detalle origen.
        $solicitadoDetalle = abs(floatval($rowDet["cantidad_movimiento"] ?? 0));
        $consumoPorDetalle[$dt] = ($consumoPorDetalle[$dt] ?? 0) + $asignado;

        if ($consumoPorDetalle[$dt] > $solicitadoDetalle) {
            CnegErrorArticulo(
                "La cantidad total asignada para este renglón supera lo solicitado. Solicitado: " . $solicitadoDetalle . ", asignado: " . $consumoPorDetalle[$dt] . ".",
                $articulo,
                $renglon
            );
        }

        // Validación estricta contra existencia real acumulada al momento de guardar.
        $keyLote = $articulo . "|" . $lote . "|" . $fecha_vencimiento . "|" . $almacen;
        $consumoPorLote[$keyLote] = ($consumoPorLote[$keyLote] ?? 0) + $asignado;

        $existenciaReal = CnegExistencia($articulo, $lote, $fecha_vencimiento, $almacen);
        if ($consumoPorLote[$keyLote] > $existenciaReal) {
            CnegErrorArticulo(
                "La cantidad solicitada supera la existencia real del lote. Lote: {$lote}. Almacén: {$almacen}. Disponible: {$existenciaReal}. Solicitado acumulado: " . $consumoPorLote[$keyLote] . ".",
                $articulo,
                $renglon
            );
        }

        $sql = "SELECT a.fabricante, IFNULL(b.alicuota, 0) AS alicuota
                FROM articulo AS a
                LEFT JOIN alicuota AS b ON b.codigo = a.alicuota AND b.activo = 'S'
                WHERE a.id = $articulo
                LIMIT 1";
        $rowArt = ExecuteRow($sql);

        if (!$rowArt) {
            CnegErrorArticulo("No se encontró el maestro del artículo.", $articulo, $renglon);
        }

        $fabricante = intval($rowArt["fabricante"] ?? 0);
        $alicuota = floatval($rowArt["alicuota"] ?? 0);
        $precio_unidad = floatval($rowDet["precio_unidad"] ?? 0);
        $descuento = floatval($rowDet["descuento"] ?? 0);
        $descuento2 = floatval($rowDet["descuento2"] ?? 0);
        $precio_unidad_sin_desc = floatval($rowDet["precio_unidad_sin_desc"] ?? 0);
        $precio = $asignado * $precio_unidad;
        $cantidad_movimiento = $asignado * -1;

        $detalleValidado[] = [
            "renglon" => $renglon,
            "dt" => $dt,
            "articulo" => $articulo,
            "fabricante" => $fabricante,
            "lote" => $lote,
            "fecha_vencimiento" => $fecha_vencimiento,
            "almacen" => $almacen,
            "cnt" => $cnt,
            "un" => $un,
            "cantidad_um" => $cantidad_um,
            "cantidad_movimiento" => $cantidad_movimiento,
            "precio_unidad" => $precio_unidad,
            "precio" => $precio,
            "alicuota" => $alicuota,
            "descuento" => $descuento,
            "descuento2" => $descuento2,
            "precio_unidad_sin_desc" => $precio_unidad_sin_desc
        ];

        $articulosProcesados[$articulo] = true;
    }

    if (count($detalleValidado) <= 0) {
        throw new \Exception("No se validó ningún renglón para crear la Nota de Entrega.");
    }

    /**************************************************************************
     * FASE 2: CREACIÓN
     * A partir de aquí ya todos los renglones fueron validados.
     **************************************************************************/
    Execute("START TRANSACTION");
    $transaccionIniciada = true;

    $numeroReservado = ReservarConsecutivoDocumento($tipo_documento, "DOC");
    $nro_documento = str_pad(intval($numeroReservado), 7, "0", STR_PAD_LEFT);

    /**** Inserto encabezado de la Nota de Entrega ****/
    $sql = "INSERT INTO salidas
            (id, tipo_documento, username, fecha,
             cliente, nro_documento,
             nota, estatus,
             id_documento_padre, asesor, consignacion, fecha_bultos, fecha_despacho, asesor_asignado,
             dias_credito, tasa_dia, moneda, descuento, descuento2)
        SELECT
             NULL, '$tipo_documento', '$username', '" . date("Y-m-d H:i:s") . "',
             cliente, '$nro_documento',
             '$nota',
             'NUEVO', id, asesor, '$consignacion', NULL, NULL, asesor_asignado,
             dias_credito, $tasa, '$moneda', descuento, descuento2
        FROM salidas
        WHERE id = $id";
    Execute($sql);

    $new_id = intval(ExecuteScalar("SELECT LAST_INSERT_ID()"));
    if ($new_id <= 0) {
        throw new \Exception("No se pudo crear el encabezado de la Nota de Entrega.");
    }

    foreach ($detalleValidado as $item) {
        $fabricante = intval($item["fabricante"]);
        $articulo = intval($item["articulo"]);
        $lote = CnegSql($item["lote"]);
        $fecha_vencimiento = CnegSql($item["fecha_vencimiento"]);
        $almacen = CnegSql($item["almacen"]);
        $cnt = floatval($item["cnt"]);
        $un = CnegSql($item["un"]);
        $cantidad_um = floatval($item["cantidad_um"]);
        $cantidad_movimiento = floatval($item["cantidad_movimiento"]);
        $precio_unidad = floatval($item["precio_unidad"]);
        $precio = floatval($item["precio"]);
        $alicuota = floatval($item["alicuota"]);
        $descuento = floatval($item["descuento"]);
        $descuento2 = floatval($item["descuento2"]);
        $precio_unidad_sin_desc = floatval($item["precio_unidad_sin_desc"]);

        $sql = "INSERT INTO entradas_salidas
                    (id, tipo_documento, id_documento,
                     fabricante, articulo, lote, fecha_vencimiento, almacen, cantidad_articulo,
                     articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento,
                     precio_unidad, precio, alicuota, id_compra,
                     descuento, descuento2, precio_unidad_sin_desc)
                VALUES
                    (NULL, '$tipo_documento', $new_id,
                     $fabricante, $articulo, '$lote', '$fecha_vencimiento', '$almacen', $cnt,
                     '$un', $cantidad_um, $cantidad_movimiento,
                     $precio_unidad, $precio, $alicuota, 0,
                     $descuento, $descuento2, $precio_unidad_sin_desc)";
        Execute($sql);
    }

    $itemsInsertados = intval(ExecuteScalar("SELECT COUNT(*) FROM entradas_salidas WHERE tipo_documento = '$tipo_documento' AND id_documento = $new_id"));
    if ($itemsInsertados <= 0) {
        throw new \Exception("No se insertó ningún renglón en la Nota de Entrega.");
    }

    /**** Totales ****/
    $sql = "SELECT
                IFNULL(SUM(precio), 0) AS precio,
                IFNULL(SUM((precio * (alicuota/100))), 0) AS iva,
                IFNULL(SUM(precio), 0) + IFNULL(SUM((precio * (alicuota/100))), 0) AS total
            FROM entradas_salidas
            WHERE tipo_documento = '$tipo_documento'
              AND id_documento = $new_id";
    $row = ExecuteRow($sql);
    $precio = CnegMoney($row["precio"] ?? 0);
    $iva = CnegMoney($row["iva"] ?? 0);
    $total = CnegMoney($row["total"] ?? 0);

    /**** Alícuota cabecera ****/
    $cantAlicuotas = intval(ExecuteScalar("SELECT COUNT(DISTINCT IFNULL(alicuota, 0)) FROM entradas_salidas WHERE tipo_documento = '$tipo_documento' AND id_documento = $new_id"));
    if ($cantAlicuotas > 1) {
        $alicuotaCab = 0;
    } else {
        $alicuotaCab = floatval(ExecuteScalar("SELECT DISTINCT IFNULL(alicuota, 0) FROM entradas_salidas WHERE tipo_documento = '$tipo_documento' AND id_documento = $new_id LIMIT 1"));
    }

    $monto_usd = $total / (substr(strtoupper($moneda), 0, 2) == "BS" ? 1 : $tasa);
    $estatusNuevoDoc = ($tipo == "TDCFCV" ? "PROCESADO" : "NUEVO");

    $sql = "UPDATE salidas
            SET monto_total = $precio,
                alicuota_iva = $alicuotaCab,
                iva = $iva,
                total = $total,
                monto_usd = $monto_usd,
                estatus = '$estatusNuevoDoc'
            WHERE id = $new_id";
    Execute($sql);

    /**** Unidades encabezado ****/
    $sql = "UPDATE salidas AS a
            JOIN (
                SELECT id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad
                FROM entradas_salidas
                WHERE tipo_documento = '$tipo_documento'
                  AND id_documento = $new_id
                GROUP BY id_documento, tipo_documento
            ) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento
            SET a.unidades = b.cantidad
            WHERE a.id = $new_id";
    Execute($sql);

    /**** Marcar relación con documento origen ****/
    if ($tipo == "TDCFCV") {
        Execute("UPDATE salidas SET id_documento_padre = $new_id WHERE id = $id");
    } else {
        Execute("UPDATE salidas SET estatus = 'PROCESADO' WHERE id = $id");
    }

    /**** Auditoría ****/
    $fechaActual = date("Y-m-d H:i:s");
    $sqlAudit = "INSERT INTO audittrail
        (id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
        VALUES
        (NULL, '$fechaActual', 'Crea Nota de Entrega NRO $nro_documento desde documento origen ID $id', '$username', 'I', 'view_out_tdcnet', 'id', '$new_id', '$id', '$nro_documento')";
    Execute($sqlAudit);

    /**** Recalcular existencia de artículos afectados ****/
    foreach (array_keys($articulosProcesados) as $articuloProcesado) {
        Execute("CALL sp_onhand_item(" . intval($articuloProcesado) . ")");
    }

    Execute("COMMIT");

    // Si deseas evitar la segunda edición manual, cambia esta redirección por:
    // header("Location: ViewOutTdcnetList");
    // o por la pantalla de vista/impresión correspondiente.
    header("Location: CrearNotaEntradaUpdate?id=$new_id");
    die();

} catch (\Throwable $e) {
    if ($transaccionIniciada) {
        Execute("ROLLBACK");
    }

    // Limpieza manual para MyISAM.
    if ($new_id > 0) {
        Execute("DELETE FROM entradas_salidas WHERE tipo_documento = '$tipo_documento' AND id_documento = $new_id");
        Execute("DELETE FROM salidas WHERE tipo_documento = '$tipo_documento' AND id = $new_id");
    }

    $_SESSION["failure"] = "No se pudo crear la Nota de Entrega: " . $e->getMessage();
    header("Location: CrearNotaEntrega?id=$id");
    die();
}
?>
<?= GetDebugMessage() ?>
