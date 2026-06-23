<?php

namespace PHPMaker2024\mandrake;

// Page object
$CrearNotaEntregaAutomatica = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
ob_start();
$id = isset($_REQUEST["id"]) ? intval($_REQUEST["id"]) : intval($_REQUEST["pedido"] ?? 0);

$ajax = strtoupper(trim($_POST["ajax"] ?? $_GET["ajax"] ?? "N"));

if ($id <= 0) {
    if ($ajax == "S") {
        header('Content-Type: application/json');
        echo json_encode([
            "success" => false,
            "message" => "Documento inválido."
        ]);
        exit();
    }

    die("Documento inválido.");
}


function CneaJson($data)
{
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    header_remove();
    header("Content-Type: application/json; charset=utf-8");

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * CrearNotaEntregaAutomatica.php
 *
 * Custom File PHPMaker 2024.
 * Genera automáticamente una Nota/Orden de Entrega TDCNET desde una factura TDCFCV.
 * - Recibe salidas.id de la factura por GET/POST: id o pedido.
 * - Solo toma artículos con articulo.articulo_inventario = 'S'.
 * - Asigna lotes por FEFO, primero vencimiento más próximo.
 * - Si un lote no alcanza, divide el renglón entre varios lotes.
 * - No crea encabezado si no hay al menos un renglón para registrar.
 * - Usa ReservarConsecutivoDocumento('TDCNET', 'DOC').
 * - Respeta parámetro 110:
 *      S = solo almacén principal parámetro 002
 *      N = todos los almacenes con movimiento = 'S'
 */

function CneaSql($value)
{
    return AdjustSql(trim((string)$value));
}

function CneaFechaSql($fecha)
{
    $fecha = trim((string)$fecha);
    return ($fecha == "" || $fecha == "0000-00-00") ? "1990-01-01" : $fecha;
}

function CneaParam($codigo, $default = "")
{
    $codigo = CneaSql($codigo);
    $valor = ExecuteScalar("SELECT valor1 FROM parametro WHERE codigo = '$codigo' LIMIT 1");
    return ($valor === null || $valor === false || $valor === "") ? $default : $valor;
}

function CneaMoney($value)
{
    return floatval($value);
}

function CneaArticuloNombre($articulo)
{
    $articulo = intval($articulo);
    $row = ExecuteRow("SELECT codigo, TRIM(CONCAT(IFNULL(principio_activo,''), ' ', IFNULL(presentacion,''), ' ', IFNULL(nombre_comercial,''))) AS descripcion FROM articulo WHERE id = $articulo LIMIT 1");
    if (!$row) {
        return "Artículo ID $articulo";
    }

    $codigo = trim((string)($row["codigo"] ?? ""));
    $descripcion = trim((string)($row["descripcion"] ?? ""));

    if ($codigo != "" && $descripcion != "") {
        return "$codigo - $descripcion";
    }
    return $descripcion != "" ? $descripcion : ($codigo != "" ? $codigo : "Artículo ID $articulo");
}

function CneaSetMessage($type, $message)
{
    $_SESSION[$type] = $message;
}

$returnUrl = trim((string)($_REQUEST["return"] ?? "ViewOutTdcfcvView/" . $id . "?showdetail="));

if ($id <= 0) {
    CneaSetMessage("failure", "No se pudo generar la Orden de Entrega automática: factura inválida.");
    header("Location: $returnUrl");
    die();
}

$new_id = 0;
$tipo_documento = "TDCNET";
$transaccionIniciada = false;
$username = CneaSql(CurrentUserName());

try {
    $tipo_documento_inventario = CneaSql(CneaParam("050", "TDCNET"));
    $almacenPrincipal = CneaParam("002", "");
    $param110 = strtoupper(trim((string)CneaParam("110", "S")));

    $filtroAlmacen = "";
    if ($param110 == "S") {
        $filtroAlmacen = " AND a.almacen = '" . CneaSql($almacenPrincipal) . "' ";
    }

    $factura = ExecuteRow("SELECT id, tipo_documento, cliente, estatus, nota, asesor, asesor_asignado, dias_credito, moneda, tasa_dia, descuento, descuento2 FROM salidas WHERE id = $id LIMIT 1");

    if (!$factura) {
        throw new \Exception("No se encontró la factura origen.");
    }

    if (($factura["tipo_documento"] ?? "") != "TDCFCV") {
        throw new \Exception("El documento origen no es una Factura de Venta TDCFCV.");
    }

    $cliente = intval($factura["cliente"] ?? 0);
    $estatusFactura = strtoupper(trim((string)($factura["estatus"] ?? "")));

    // Permite ejecutarse luego del procesamiento fiscal; si aún está NUEVO, se acepta por compatibilidad
    // cuando TdcfcvProcess llama justo antes de redirigir.
    if ($cliente <= 0) {
        throw new \Exception("La factura no tiene cliente válido.");
    }

    $consignacion = CneaSql(ExecuteScalar("SELECT consignacion FROM cliente WHERE id = $cliente LIMIT 1"));
    $tasa = floatval($factura["tasa_dia"] ?? 0);
    if ($tasa <= 0) {
        $tasa = floatval(ExecuteScalar("SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 1"));
    }
    if ($tasa <= 0) {
        $tasa = 1;
    }

    $moneda = CneaSql($factura["moneda"] ?? "");
    if ($moneda == "") {
        $moneda = CneaSql(ExecuteScalar("SELECT valor1 FROM parametro WHERE codigo = '006' AND valor2 = 'default' LIMIT 1"));
    }
    if ($moneda == "") {
        $moneda = "Bs.";
    }

    /**************************************************************************
     * FASE 1: preparar asignación FEFO de artículos inventariables
     **************************************************************************/
    $lineas = ExecuteRows("SELECT
            es.id,
            es.articulo,
            es.fabricante,
            ABS(es.cantidad_movimiento) AS cantidad_solicitada,
            es.cantidad_articulo,
            es.articulo_unidad_medida,
            IFNULL(es.cantidad_unidad_medida, 1) AS cantidad_unidad_medida,
            es.precio_unidad,
            es.precio,
            es.alicuota,
            IFNULL(es.descuento, 0) AS descuento,
            IFNULL(es.descuento2, 0) AS descuento2,
            IFNULL(es.precio_unidad_sin_desc, es.precio_unidad) AS precio_unidad_sin_desc
        FROM entradas_salidas AS es
        JOIN articulo AS ar ON ar.id = es.articulo
        WHERE es.tipo_documento = 'TDCFCV'
          AND es.id_documento = $id
          AND IFNULL(ar.articulo_inventario, 'N') = 'S'
          AND ABS(IFNULL(es.cantidad_movimiento, 0)) > 0
        ORDER BY es.id") ?: [];

    if (count($lineas) <= 0) {
        CneaSetMessage("success", "Factura procesada. No se generó Orden de Entrega porque no hay artículos de inventario.");
        header("Location: $returnUrl");
        die();
    }

    $detalleValidado = [];
    $articulosProcesados = [];
    $consumoPorLote = [];
    $articulosOmitidos = [];

    foreach ($lineas as $linea) {
        $idDetalle = intval($linea["id"]);
        $articulo = intval($linea["articulo"]);
        $fabricante = intval($linea["fabricante"] ?? 0);
        $cantidadPendiente = abs(floatval($linea["cantidad_solicitada"] ?? $linea["cantidad_articulo"] ?? 0));

        if ($articulo <= 0 || $cantidadPendiente <= 0) {
            continue;
        }

        $lotes = ExecuteRows("SELECT
                x.articulo,
                x.lote,
                x.fecha_vencimiento,
                x.codalm,
                x.almacen,
                SUM(x.cantidad_movimiento) AS cantidad
            FROM (
                SELECT
                    a.articulo,
                    IFNULL(a.lote, '') AS lote,
                    IFNULL(a.fecha_vencimiento, '1990-01-01') AS fecha_vencimiento,
                    a.cantidad_movimiento,
                    a.almacen AS codalm,
                    c.descripcion AS almacen
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
                  $filtroAlmacen
                  AND a.newdata = 'S'

                UNION ALL

                SELECT
                    a.articulo,
                    IFNULL(a.lote, '') AS lote,
                    IFNULL(a.fecha_vencimiento, '1990-01-01') AS fecha_vencimiento,
                    a.cantidad_movimiento,
                    a.almacen AS codalm,
                    c.descripcion AS almacen
                FROM entradas_salidas AS a
                JOIN salidas AS b ON b.tipo_documento = a.tipo_documento
                                 AND b.id = a.id_documento
                JOIN almacen AS c ON c.codigo = a.almacen
                                  AND c.movimiento = 'S'
                WHERE (a.tipo_documento IN ('$tipo_documento_inventario', 'TDCASA') AND b.estatus <> 'ANULADO')
                  AND a.articulo = $articulo
                  $filtroAlmacen
                  AND a.newdata = 'S'
            ) AS x
            WHERE (x.fecha_vencimiento = '1990-01-01' OR x.fecha_vencimiento >= CURDATE())
            GROUP BY x.articulo, x.lote, x.fecha_vencimiento, x.codalm, x.almacen
            HAVING SUM(x.cantidad_movimiento) > 0
            ORDER BY
                CASE
                    WHEN x.fecha_vencimiento = '1990-01-01' THEN 1
                    WHEN x.fecha_vencimiento = '2027-01-01' THEN 1
                    ELSE 0
                END,
                x.fecha_vencimiento ASC,
                x.lote ASC") ?: [];

        foreach ($lotes as $loteRow) {
            if ($cantidadPendiente <= 0) {
                break;
            }

            $lote = CneaSql($loteRow["lote"] ?? "");
            $fecha_vencimiento = CneaSql(CneaFechaSql($loteRow["fecha_vencimiento"] ?? ""));
            $almacen = CneaSql($loteRow["codalm"] ?? "");
            $disponible = floatval($loteRow["cantidad"] ?? 0);

            if ($almacen == "" || $disponible <= 0) {
                continue;
            }

            $keyLote = $articulo . "|" . $lote . "|" . $fecha_vencimiento . "|" . $almacen;
            $yaConsumido = floatval($consumoPorLote[$keyLote] ?? 0);
            $disponibleNeto = $disponible - $yaConsumido;

            if ($disponibleNeto <= 0) {
                continue;
            }

            $cantidadAsignada = min($cantidadPendiente, $disponibleNeto);
            $consumoPorLote[$keyLote] = $yaConsumido + $cantidadAsignada;
            $cantidadPendiente -= $cantidadAsignada;

            $precio_unidad = floatval($linea["precio_unidad"] ?? 0);
            $precio = $cantidadAsignada * $precio_unidad;

            $detalleValidado[] = [
                "id_origen" => $idDetalle,
                "articulo" => $articulo,
                "fabricante" => $fabricante,
                "lote" => $lote,
                "fecha_vencimiento" => $fecha_vencimiento,
                "almacen" => $almacen,
                "cantidad_articulo" => $cantidadAsignada,
                "unidad" => CneaSql($linea["articulo_unidad_medida"] ?? ""),
                "cantidad_um" => floatval($linea["cantidad_unidad_medida"] ?? 1),
                "cantidad_movimiento" => $cantidadAsignada * -1,
                "precio_unidad" => $precio_unidad,
                "precio" => $precio,
                "alicuota" => floatval($linea["alicuota"] ?? 0),
                "descuento" => floatval($linea["descuento"] ?? 0),
                "descuento2" => floatval($linea["descuento2"] ?? 0),
                "precio_unidad_sin_desc" => floatval($linea["precio_unidad_sin_desc"] ?? 0)
            ];

            $articulosProcesados[$articulo] = true;
        }

        if ($cantidadPendiente > 0) {
            $articulosOmitidos[] = CneaArticuloNombre($articulo) .
                " - Faltante: " . number_format($cantidadPendiente, 2, ",", ".");

            continue;
        }        
    }

    if (count($detalleValidado) <= 0) {
        CneaSetMessage("success", "Factura procesada. No se generó Orden de Entrega porque no hubo cantidades disponibles para artículos de inventario.");
        header("Location: $returnUrl");
        die();
    }

    /**************************************************************************
     * FASE 2: crear TDCNET
     **************************************************************************/
    if (!function_exists(__NAMESPACE__ . "\\ReservarConsecutivoDocumento")) {
        throw new \Exception("No existe la función ReservarConsecutivoDocumento() en Global Code.");
    }

    Execute("START TRANSACTION");
    $transaccionIniciada = true;

    $numeroReservado = ReservarConsecutivoDocumento($tipo_documento, "DOC");
    $nro_documento = str_pad(intval($numeroReservado), 7, "0", STR_PAD_LEFT);
    $nota = CneaSql("Orden de Entrega generada automáticamente desde factura ID $id");

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
        throw new \Exception("No se pudo crear el encabezado de la Orden de Entrega.");
    }

    foreach ($detalleValidado as $item) {
        $fabricante = intval($item["fabricante"]);
        $articulo = intval($item["articulo"]);
        $lote = CneaSql($item["lote"]);
        $fecha_vencimiento = CneaSql($item["fecha_vencimiento"]);
        $almacen = CneaSql($item["almacen"]);
        $cnt = floatval($item["cantidad_articulo"]);
        $un = CneaSql($item["unidad"]);
        $cantidad_um = floatval($item["cantidad_um"]);
        $cantidad_movimiento = floatval($item["cantidad_movimiento"]);
        $precio_unidad = floatval($item["precio_unidad"]);
        $precio = floatval($item["precio"]);
        $alicuota = floatval($item["alicuota"]);
        $descuento = floatval($item["descuento"]);
        $descuento2 = floatval($item["descuento2"]);
        $precio_unidad_sin_desc = floatval($item["precio_unidad_sin_desc"]);

        Execute("INSERT INTO entradas_salidas
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
                     $descuento, $descuento2, $precio_unidad_sin_desc)");
    }

    $rowTot = ExecuteRow("SELECT
                IFNULL(SUM(precio), 0) AS precio,
                IFNULL(SUM((precio * (alicuota/100))), 0) AS iva,
                IFNULL(SUM(precio), 0) + IFNULL(SUM((precio * (alicuota/100))), 0) AS total
            FROM entradas_salidas
            WHERE tipo_documento = '$tipo_documento'
              AND id_documento = $new_id");

    $precio = CneaMoney($rowTot["precio"] ?? 0);
    $iva = CneaMoney($rowTot["iva"] ?? 0);
    $total = CneaMoney($rowTot["total"] ?? 0);

    $cantAlicuotas = intval(ExecuteScalar("SELECT COUNT(DISTINCT IFNULL(alicuota, 0)) FROM entradas_salidas WHERE tipo_documento = '$tipo_documento' AND id_documento = $new_id"));
    $alicuotaCab = $cantAlicuotas > 1 ? 0 : floatval(ExecuteScalar("SELECT DISTINCT IFNULL(alicuota, 0) FROM entradas_salidas WHERE tipo_documento = '$tipo_documento' AND id_documento = $new_id LIMIT 1"));

    $monto_usd = $total / (substr(strtoupper($moneda), 0, 2) == "BS" ? 1 : $tasa);

    Execute("UPDATE salidas
            SET monto_total = $precio,
                alicuota_iva = $alicuotaCab,
                iva = $iva,
                total = $total,
                monto_usd = $monto_usd,
                estatus = 'PROCESADO'
            WHERE id = $new_id");

    Execute("UPDATE salidas AS a
            JOIN (
                SELECT id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad
                FROM entradas_salidas
                WHERE tipo_documento = '$tipo_documento'
                  AND id_documento = $new_id
                GROUP BY id_documento, tipo_documento
            ) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento
            SET a.unidades = b.cantidad
            WHERE a.id = $new_id");

    // Relación factura -> orden de entrega automática.
    Execute("UPDATE salidas SET id_documento_padre = $new_id WHERE id = $id");

    $fechaActual = date("Y-m-d H:i:s");
    Execute("INSERT INTO audittrail
        (id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
        VALUES
        (NULL, '$fechaActual', 'Crea Orden de Entrega automática NRO $nro_documento desde factura ID $id', '$username', 'I', 'view_out_tdcnet', 'id', '$new_id', '$id', '$nro_documento')");

    foreach (array_keys($articulosProcesados) as $articuloProcesado) {
        Execute("CALL sp_onhand_item(" . intval($articuloProcesado) . ")");
    }

    Execute("COMMIT");
    $mensajeFinal = "Factura procesada y Orden de Entrega automática Nro. $nro_documento generada correctamente.";
    $mensajeWarning = "";

    if (count($articulosOmitidos) > 0) {
        $mensajeWarning = "Algunos artículos no fueron incluidos en la Orden de Entrega por falta de existencia: "
            . implode("; ", $articulosOmitidos);
    }

    if ($ajax == "S") {
        CneaJson([
            "success" => true,
            "message" => $mensajeFinal,
            "warning" => $mensajeWarning,
            "id" => $new_id,
            "nro_documento" => $nro_documento
        ]);
    }

    CneaSetMessage("success", $mensajeFinal);
    if ($mensajeWarning != "") {
        CneaSetMessage("warning", $mensajeWarning);
    }

    header("Location: $returnUrl");
    die();  
} catch (\Throwable $e) {
    if ($transaccionIniciada) {
        Execute("ROLLBACK");
    }

    if ($new_id > 0) {
        Execute("DELETE FROM entradas_salidas WHERE tipo_documento = '$tipo_documento' AND id_documento = $new_id");
        Execute("DELETE FROM salidas WHERE tipo_documento = '$tipo_documento' AND id = $new_id");
    }

    if ($ajax == "S") {
        CneaJson([
            "success" => false,
            "message" => "La factura fue procesada, pero no se pudo generar la Orden de Entrega automática: " . $e->getMessage()
        ]);
    }

    CneaSetMessage("failure", "La factura fue procesada, pero no se pudo generar la Orden de Entrega automática: " . $e->getMessage());
    header("Location: $returnUrl");
    die();
}
?>

<?= GetDebugMessage() ?>
