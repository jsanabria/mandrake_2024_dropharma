<?php

namespace PHPMaker2024\mandrake;

// Page object
$TransferenciaGuardar = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php

function sqlValue($value) {
    if ($value === null || trim((string)$value) === "") {
        return "NULL";
    }
    return "'" . AdjustSql($value) . "'";
}

function getExistenciaTransferencia($articulo, $lote, $fecha_vencimiento, $almacen, $tipo_documento_050) {

    $articulo = intval($articulo);
    $lote_sql = AdjustSql($lote);
    $almacen_sql = AdjustSql($almacen);
    $tipo_documento_050_sql = AdjustSql($tipo_documento_050);

    if ($fecha_vencimiento === null || trim($fecha_vencimiento) === "") {
        $cond_fecha = "a.fecha_vencimiento IS NULL";
    } else {
        $fecha_sql = AdjustSql($fecha_vencimiento);
        $cond_fecha = "a.fecha_vencimiento = '$fecha_sql'";
    }

    $sql = "
        SELECT IFNULL(SUM(x.cantidad_movimiento), 0) AS existencia
        FROM (
            SELECT a.cantidad_movimiento
            FROM entradas_salidas AS a
            JOIN entradas AS b
                ON b.tipo_documento = a.tipo_documento
               AND b.id = a.id_documento
            JOIN almacen AS c
                ON c.codigo = a.almacen
               AND c.movimiento = 'S'
            WHERE (
                    (a.tipo_documento = 'TDCAEN' AND b.estatus <> 'ANULADO')
                    OR
                    (a.tipo_documento = 'TDCNRP' AND a.check_ne = 'S' AND b.estatus <> 'ANULADO')
                  )
              AND a.articulo = $articulo
              AND IFNULL(a.lote, '') = '$lote_sql'
              AND $cond_fecha
              AND a.almacen = '$almacen_sql'
              AND a.newdata = 'S'

            UNION ALL

            SELECT a.cantidad_movimiento
            FROM entradas_salidas AS a
            JOIN salidas AS b
                ON b.tipo_documento = a.tipo_documento
               AND b.id = a.id_documento
            JOIN almacen AS c
                ON c.codigo = a.almacen
               AND c.movimiento = 'S'
            WHERE (
                    (a.tipo_documento = 'TDCPDV' AND b.estatus = 'NUEVO')
                    OR
                    (a.tipo_documento IN ('$tipo_documento_050_sql', 'TDCASA') AND b.estatus <> 'ANULADO')
                  )
              AND a.articulo = $articulo
              AND IFNULL(a.lote, '') = '$lote_sql'
              AND $cond_fecha
              AND a.almacen = '$almacen_sql'
              AND a.newdata = 'S'
        ) AS x
    ";

    return floatval(ExecuteScalar($sql));
}

try {

    $items = isset($_POST["CantItem"]) ? intval($_POST["CantItem"]) : 0;

    if ($items <= 0) {
        throw new \Exception("No se recibieron renglones para procesar.");
    }

    $tipo_documento_050 = ExecuteScalar("SELECT valor1 FROM parametro WHERE codigo = '050' LIMIT 1");
    if ($tipo_documento_050 == "") {
        $tipo_documento_050 = "TDCNET";
    }

    $cliente = intval(ExecuteScalar("SELECT id FROM cliente WHERE nombre LIKE '%ajuste%' LIMIT 1"));
    if ($cliente <= 0) {
        throw new \Exception("No se encontró el cliente de ajuste.");
    }

    $proveedor = intval(ExecuteScalar("SELECT id FROM proveedor WHERE nombre LIKE '%ajuste%' LIMIT 1"));
    if ($proveedor <= 0) {
        throw new \Exception("No se encontró el proveedor de ajuste.");
    }

    $detalle = [];
    $total_unidades = 0;

    for ($i = 1; $i <= $items; $i++) {

        $control_lote = "x" . $i . "_Lote";
        $control_almacen = "x" . $i . "_Almacen";
        $control_cantidad = "x" . $i . "_Cantidad";

        if (!isset($_POST[$control_lote])) {
            continue;
        }

        $raw_lote = $_POST[$control_lote];
        $almacen_destino = trim($_POST[$control_almacen] ?? "");
        $cantidad = floatval(str_replace(",", ".", $_POST[$control_cantidad] ?? "0"));

        $partes = explode("|", $raw_lote);

        if (count($partes) < 5) {
            throw new \Exception("Datos incompletos en la fila $i.");
        }

        $articulo = intval($partes[0]);
        $lote = trim($partes[1]);
        $fecha_vencimiento = trim($partes[2]);
        $existencia_pantalla = floatval($partes[3]);
        $almacen_origen = trim($partes[4]);

        if ($articulo <= 0) {
            throw new \Exception("Artículo inválido en la fila $i.");
        }

        if ($almacen_origen == "") {
            throw new \Exception("Almacén origen inválido en la fila $i.");
        }

        if ($almacen_destino == "") {
            throw new \Exception("Debe seleccionar almacén destino en la fila $i.");
        }

        if ($almacen_origen == $almacen_destino) {
            throw new \Exception("El almacén destino no puede ser igual al almacén origen en la fila $i.");
        }

        if ($cantidad <= 0) {
            throw new \Exception("La cantidad debe ser mayor a cero en la fila $i.");
        }

        $almacen_destino_existe = intval(ExecuteScalar("
            SELECT COUNT(*) 
            FROM almacen 
            WHERE codigo = '" . AdjustSql($almacen_destino) . "' 
              AND movimiento = 'S'
        "));

        if ($almacen_destino_existe <= 0) {
            throw new \Exception("El almacén destino no es válido en la fila $i.");
        }

        $existencia_real = getExistenciaTransferencia(
            $articulo,
            $lote,
            $fecha_vencimiento,
            $almacen_origen,
            $tipo_documento_050
        );

        if ($cantidad > $existencia_real) {
            throw new \Exception("La cantidad indicada en la fila $i supera la existencia real disponible.");
        }

        $row_articulo = ExecuteRow("
            SELECT fabricante, ultimo_costo 
            FROM articulo 
            WHERE id = $articulo
        ");

        if (!$row_articulo) {
            throw new \Exception("No se encontró el artículo de la fila $i.");
        }

        $detalle[] = [
            "articulo" => $articulo,
            "fabricante" => intval($row_articulo["fabricante"]),
            "costo" => floatval($row_articulo["ultimo_costo"]),
            "lote" => $lote,
            "fecha_vencimiento" => ($fecha_vencimiento == "" ? null : $fecha_vencimiento),
            "almacen_origen" => $almacen_origen,
            "almacen_destino" => $almacen_destino,
            "cantidad" => $cantidad
        ];

        $total_unidades += $cantidad;
    }

    if (count($detalle) == 0) {
        throw new \Exception("Debe seleccionar al menos un lote para transferir.");
    }

    Execute("START TRANSACTION");

    $fecha_actual = date("Y-m-d H:i:s");
    $usuario = AdjustSql(CurrentUserName());

    $consecutivo_salida = ReservarConsecutivoDocumento("TDCASA");

    $sql = "
        INSERT INTO salidas
            (id, tipo_documento, username, fecha,
             cliente, nro_documento,
             nota, estatus, documento, nombre, moneda, unidades)
        VALUES
            (NULL, 'TDCASA', '$usuario', '$fecha_actual',
             $cliente, '$consecutivo_salida',
             'TRANSFERENCIA ENTRE ALMACENES',
             'PROCESADO', 'TR', 'TRANSFERENCIA', 'Bs', $total_unidades)
    ";
    Execute($sql);

    $salida_id = intval(ExecuteScalar("SELECT LAST_INSERT_ID()"));

    $consecutivo_entrada = ReservarConsecutivoDocumento("TDCAEN");

    $sql = "
        INSERT INTO entradas
            (id, tipo_documento, username, fecha,
             proveedor, nro_documento,
             nota, estatus, documento, moneda, unidades)
        VALUES
            (NULL, 'TDCAEN', '$usuario', '$fecha_actual',
             $proveedor, '$consecutivo_entrada',
             'TRANSFERENCIA ENTRE ALMACENES',
             'PROCESADO', 'TR', 'Bs', $total_unidades)
    ";
    Execute($sql);

    $entrada_id = intval(ExecuteScalar("SELECT LAST_INSERT_ID()"));

    Execute("
        UPDATE salidas 
        SET doc_afectado = '$consecutivo_entrada'
        WHERE id = $salida_id
          AND tipo_documento = 'TDCASA'
    ");

    Execute("
        UPDATE entradas 
        SET doc_afectado = '$consecutivo_salida'
        WHERE id = $entrada_id
          AND tipo_documento = 'TDCAEN'
    ");

    foreach ($detalle as $item) {

        $articulo = intval($item["articulo"]);
        $fabricante = intval($item["fabricante"]);
        $costo = floatval($item["costo"]);
        $cantidad = floatval($item["cantidad"]);
        $costo_total = $cantidad * $costo;

        $lote_sql = sqlValue($item["lote"]);
        $fecha_sql = sqlValue($item["fecha_vencimiento"]);

        $almacen_origen = AdjustSql($item["almacen_origen"]);
        $almacen_destino = AdjustSql($item["almacen_destino"]);

        $sql = "
            INSERT INTO entradas_salidas
                (id, tipo_documento, id_documento,
                 fabricante, articulo, almacen, lote, fecha_vencimiento,
                 cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida,
                 cantidad_movimiento, costo_unidad, costo, newdata)
            VALUES
                (NULL, 'TDCASA', $salida_id,
                 $fabricante, $articulo, '$almacen_origen', $lote_sql, $fecha_sql,
                 $cantidad, 'UDM001', 1,
                 (-1) * $cantidad, $costo, $costo_total, 'S')
        ";
        Execute($sql);

        $sql = "
            INSERT INTO entradas_salidas
                (id, tipo_documento, id_documento,
                 fabricante, articulo, almacen, lote, fecha_vencimiento,
                 cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida,
                 cantidad_movimiento, costo_unidad, costo, newdata)
            VALUES
                (NULL, 'TDCAEN', $entrada_id,
                 $fabricante, $articulo, '$almacen_destino', $lote_sql, $fecha_sql,
                 $cantidad, 'UDM001', 1,
                 $cantidad, $costo, $costo_total, 'S')
        ";
        Execute($sql);
    }

    Execute("COMMIT");

    header("Location: TransferenciaResultado?salida=$salida_id&entrada=$entrada_id");
    die();

} catch (\Throwable $e) {

    Execute("ROLLBACK");

    $_SESSION["error"] = "No se pudo procesar la transferencia: " . $e->getMessage();

    header("Location: TransferenciaArticulo");
    die();
}

?>

<?= GetDebugMessage() ?>
