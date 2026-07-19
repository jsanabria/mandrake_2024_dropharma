<?php

namespace PHPMaker2024\mandrake;

// Page object
$CrearNotaRecepcionAutomaticaWait = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$pedido = intval($_REQUEST["id"] ?? 0);
$return = trim((string)($_REQUEST["return"] ?? "ViewOutTdcfcvList"));

// La ruta de retorno no puede aceptar URLs externas.
if (!preg_match('/^[A-Za-z0-9_\/?=&.-]+$/', $return)) {
    $return = "ViewOutTdcfcvList";
}

$ok = false;
$mensaje = "";
$idNotaRecepcion = 0;

try {
    if ($pedido <= 0) {
        throw new \Exception("Documento de origen no válido.");
    }

    $salida = ExecuteRow("
        SELECT
            id,
            cliente,
            IFNULL(descuento, 0) AS descuento,
            IFNULL(moneda, 'Bs.') AS moneda,
            IFNULL(nro_documento, '') AS nro_documento,
            IFNULL(nota, '') AS nota,
            IFNULL(documento, '') AS documento,
            IFNULL(estatus, '') AS estatus
        FROM salidas
        WHERE id = {$pedido}
          AND tipo_documento = 'TDCFCV'
        LIMIT 1
    ");

    if (!$salida) {
        throw new \Exception("No se encontró la Nota de Crédito de origen.");
    }

    if (strtoupper(trim((string)$salida["documento"])) !== "NC") {
        throw new \Exception("El documento indicado no es una Nota de Crédito.");
    }

    if (strtoupper(trim((string)$salida["estatus"])) !== "PROCESADO") {
        throw new \Exception("La Nota de Crédito todavía no está procesada.");
    }

    // Guardia idempotente: F5 o doble clic no pueden duplicar la recepción.
    $idExistente = intval(ExecuteScalar("
        SELECT id
        FROM entradas
        WHERE tipo_documento = 'TDCNRP'
          AND id_documento_padre = {$pedido}
        ORDER BY id DESC
        LIMIT 1
    "));

    if ($idExistente > 0) {
        $idNotaRecepcion = $idExistente;
        $ok = true;
        $mensaje = "La Nota de Recepción ya había sido generada anteriormente.";
    } else {
        $detalles = ExecuteRows("
            SELECT
                es.articulo,
                es.lote,
                IFNULL(es.fecha_vencimiento, '1990-01-01') AS fecha_vencimiento,
                IFNULL(es.cantidad_articulo, 0) AS cantidad,
                IFNULL(es.almacen, '') AS almacen_detalle,
                IFNULL(es.costo_unidad, 0) AS costo_unidad_detalle,
                IFNULL(a.fabricante, 1) AS fabricante,
                IFNULL(a.ultimo_costo, 0) AS ultimo_costo
            FROM entradas_salidas AS es
            INNER JOIN articulo AS a ON a.id = es.articulo
            WHERE es.id_documento = {$pedido}
              AND es.tipo_documento = 'TDCFCV'
              AND IFNULL(a.articulo_inventario, 'N') = 'S'
              AND IFNULL(es.cantidad_articulo, 0) > 0
            ORDER BY es.id
        ");

        if (!$detalles) {
            throw new \Exception(
                "La Nota de Crédito no contiene artículos configurados como inventario."
            );
        }

        $almacenDefecto = trim((string)ExecuteScalar("
            SELECT valor1 FROM parametro WHERE codigo = '002' LIMIT 1
        "));

        if ($almacenDefecto === "") {
            throw new \Exception("No está configurado el almacén principal (parámetro 002).");
        }

        $cliente = intval($salida["cliente"] ?? 0);
        $descuento = floatval($salida["descuento"] ?? 0);
        $moneda = AdjustSql($salida["moneda"] ?? "Bs.");
        $nroFiscal = trim((string)($salida["nro_documento"] ?? ""));
        $nroRecepcion = AdjustSql("NR-NC-" . ($nroFiscal !== "" ? $nroFiscal : $pedido));
        $username = AdjustSql(CurrentUserName());
        $nota = AdjustSql(
            "Recepción automática por Nota de Crédito fiscal " .
            ($nroFiscal !== "" ? $nroFiscal : "ID " . $pedido)
        );

        $conn = Conn();
        $conn->beginTransaction();

        try {
            ExecuteStatement("
                INSERT INTO entradas
                    (
                        id, tipo_documento, username, fecha,
                        proveedor, nro_documento, almacen, estatus,
                        id_documento_padre, consignacion, cliente,
                        moneda, nota, descuento
                    )
                VALUES
                    (
                        NULL, 'TDCNRP', '{$username}', NOW(),
                        1, '{$nroRecepcion}', '" . AdjustSql($almacenDefecto) . "', 'PROCESADO',
                        {$pedido}, 'N', {$cliente},
                        '{$moneda}', '{$nota}', {$descuento}
                    )
            ");

            $idNotaRecepcion = intval(ExecuteScalar("SELECT LAST_INSERT_ID()"));

            if ($idNotaRecepcion <= 0) {
                throw new \Exception("No se pudo obtener el ID de la Nota de Recepción.");
            }

            $unidades = 0.0;

            foreach ($detalles as $detalle) {
                $articulo = intval($detalle["articulo"] ?? 0);
                $lote = intval($detalle["lote"] ?? "");
                $fecha_vencimiento = $detalle["fecha_vencimiento"];
                $fabricante = intval($detalle["fabricante"] ?? 1);
                $cantidad = floatval($detalle["cantidad"] ?? 0);
                $almacen = trim((string)($detalle["almacen_detalle"] ?? ""));
                $costoUnidad = floatval($detalle["costo_unidad_detalle"] ?? 0);

                if ($articulo <= 0 || $cantidad <= 0) {
                    continue;
                }

                if ($almacen === "") {
                    $almacen = $almacenDefecto;
                }

                if ($costoUnidad <= 0) {
                    $costoUnidad = floatval($detalle["ultimo_costo"] ?? 0);
                }

                $costoTotal = $cantidad * $costoUnidad;
                $almacenSql = AdjustSql($almacen);

                ExecuteStatement("
                    INSERT INTO entradas_salidas
                        (
                            id, tipo_documento, id_documento,
                            fabricante, articulo, lote, fecha_vencimiento, almacen,
                            cantidad_articulo, articulo_unidad_medida,
                            cantidad_unidad_medida, cantidad_movimiento,
                            costo_unidad, costo, check_ne
                        )
                    VALUES
                        (
                            NULL, 'TDCNRP', {$idNotaRecepcion},
                            {$fabricante}, {$articulo}, '{$lote}', '{$fecha_vencimiento}', '{$almacenSql}',
                            {$cantidad}, 'UDM001',
                            1, {$cantidad},
                            {$costoUnidad}, {$costoTotal}, 'S'
                        )
                ");

                $unidades += $cantidad;
            }

            if ($unidades <= 0) {
                throw new \Exception("No se insertó ningún artículo de inventario.");
            }

            ExecuteStatement("
                UPDATE entradas
                SET unidades = {$unidades}
                WHERE id = {$idNotaRecepcion}
                  AND tipo_documento = 'TDCNRP'
            ");

            $auditScript = AdjustSql(
                "Generó Nota de Recepción automática #{$idNotaRecepcion} " .
                "desde Nota de Crédito fiscal ID {$pedido}"
            );

            ExecuteStatement("
                INSERT INTO audittrail
                    (`datetime`, `script`, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
                VALUES
                    (
                        NOW(), '{$auditScript}', '{$username}',
                        'INVENTARIO: A', 'entradas', 'id_documento_padre',
                        '{$idNotaRecepcion}', '', '{$pedido}'
                    )
            ");

            $conn->commit();
            $ok = true;
            $mensaje = "La Nota de Recepción fue creada y el inventario fue reincorporado correctamente.";
        } catch (\Throwable $e) {
            if ($conn->isTransactionActive()) {
                $conn->rollBack();
            }
            throw $e;
        }
    }
} catch (\Throwable $e) {
    $ok = false;
    $mensaje = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nota de Recepción automática</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width: 760px;">
    <div class="card shadow border-<?= $ok ? 'success' : 'danger' ?>">
        <div class="card-header bg-<?= $ok ? 'success' : 'danger' ?> text-white">
            <h4 class="mb-0"><?= $ok ? 'Nota de Recepción generada' : 'No se pudo generar la Nota de Recepción' ?></h4>
        </div>
        <div class="card-body p-4">
            <p class="fs-5 mb-2"><?= htmlspecialchars($mensaje, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
            <?php if ($idNotaRecepcion > 0) { ?>
                <p class="text-muted mb-0">Nota de Recepción ID: <strong><?= $idNotaRecepcion ?></strong></p>
            <?php } ?>
        </div>
    </div>
</div>
<script>
setTimeout(function () {
    window.location.href = <?= json_encode($return, JSON_UNESCAPED_UNICODE) ?>;
}, <?= $ok ? 1400 : 3500 ?>);
</script>
</body>
</html>
<?= GetDebugMessage() ?>
