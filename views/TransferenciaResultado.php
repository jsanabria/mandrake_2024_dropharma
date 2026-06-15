<?php

namespace PHPMaker2024\mandrake;

// Page object
$TransferenciaResultado = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php

$salida = isset($_REQUEST["salida"]) ? intval($_REQUEST["salida"]) : 0;
$entrada = isset($_REQUEST["entrada"]) ? intval($_REQUEST["entrada"]) : 0;

if ($salida <= 0 || $entrada <= 0) {
    $_SESSION["error"] = "No se recibieron correctamente los documentos de la transferencia.";
    header("Location: TransferenciaArticulo");
    die();
}

$sql = "
SELECT 
    'SALIDA' AS movimiento,
    a.id,
    a.tipo_documento AS td,
    (SELECT descripcion FROM tipo_documento WHERE codigo = a.tipo_documento) AS tipo_documento,
    a.nro_documento,
    a.doc_afectado,
    a.documento,
    a.unidades,
    a.nota,
    DATE_FORMAT(a.fecha, '%d/%m/%Y %H:%i:%s') AS fecha
FROM salidas AS a
WHERE a.id = $salida
  AND a.tipo_documento = 'TDCASA'

UNION ALL

SELECT 
    'ENTRADA' AS movimiento,
    a.id,
    a.tipo_documento AS td,
    (SELECT descripcion FROM tipo_documento WHERE codigo = a.tipo_documento) AS tipo_documento,
    a.nro_documento,
    a.doc_afectado,
    a.documento,
    (
        SELECT IFNULL(SUM(cantidad_articulo), 0)
        FROM entradas_salidas
        WHERE id_documento = a.id
          AND tipo_documento = 'TDCAEN'
    ) AS unidades,
    a.nota,
    DATE_FORMAT(a.fecha, '%d/%m/%Y %H:%i:%s') AS fecha
FROM entradas AS a
WHERE a.id = $entrada
  AND a.tipo_documento = 'TDCAEN'
";

$rows = ExecuteRows($sql);

if (count($rows) == 0) {
    $_SESSION["error"] = "No se encontraron los documentos generados.";
    header("Location: TransferenciaArticulo");
    die();
}

$sql_detalle = "
SELECT
    x.articulo,
    ar.codigo,
    ar.nombre_comercial,
    ar.principio_activo,
    ar.presentacion,
    x.lote,
    DATE_FORMAT(x.fecha_vencimiento, '%d/%m/%Y') AS fecha_vencimiento,
    x.almacen_origen,
    ao.descripcion AS nombre_almacen_origen,
    x.almacen_destino,
    ad.descripcion AS nombre_almacen_destino,
    x.cantidad
FROM (
    SELECT
        s.articulo,
        s.lote,
        s.fecha_vencimiento,
        s.almacen AS almacen_origen,
        e.almacen AS almacen_destino,
        s.cantidad_articulo AS cantidad
    FROM entradas_salidas AS s
    JOIN entradas_salidas AS e
        ON e.tipo_documento = 'TDCAEN'
       AND e.id_documento = $entrada
       AND e.articulo = s.articulo
       AND IFNULL(e.lote, '') = IFNULL(s.lote, '')
       AND IFNULL(e.fecha_vencimiento, '1900-01-01') = IFNULL(s.fecha_vencimiento, '1900-01-01')
       AND e.cantidad_articulo = s.cantidad_articulo
    WHERE s.tipo_documento = 'TDCASA'
      AND s.id_documento = $salida
) AS x
JOIN articulo AS ar
    ON ar.id = x.articulo
LEFT JOIN almacen AS ao
    ON ao.codigo = x.almacen_origen
LEFT JOIN almacen AS ad
    ON ad.codigo = x.almacen_destino
ORDER BY 
    ar.principio_activo,
    ar.nombre_comercial,
    x.lote
";

$detalle = ExecuteRows($sql_detalle);

$total_unidades = 0;
foreach ($detalle as $item) {
    $total_unidades += floatval($item["cantidad"]);
}

?>

<div class="container">

    <div class="alert alert-success">
        <strong>Transferencia procesada correctamente.</strong>
    </div>

    <h3>Documentos generados</h3>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Movimiento</th>
                    <th>Id</th>
                    <th>Tipo</th>
                    <th>Nro Documento</th>
                    <th>Doc. Afectado</th>
                    <th>Fecha</th>
                    <th>Nota</th>
                    <th class="text-right">Unidades</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($rows as $value) { ?>
                    <tr>
                        <td><?= HtmlEncode($value["movimiento"]) ?></td>

                        <td>
                            <?php if ($value["td"] == "TDCASA") { ?>
                                <a href="ViewOutList?showmaster=view_out_tdcasa&fk_id=<?= intval($value["id"]) ?>&fk_tipo_documento=<?= HtmlEncode($value["td"]) ?>" target="_blank">
                                    <?= intval($value["id"]) ?>
                                </a>
                            <?php } else { ?>
                                <a href="ViewInList?showmaster=view_in_tdcaen&fk_id=<?= intval($value["id"]) ?>&fk_tipo_documento=<?= HtmlEncode($value["td"]) ?>" target="_blank">
                                    <?= intval($value["id"]) ?>
                                </a>
                            <?php } ?>
                        </td>

                        <td><?= HtmlEncode($value["tipo_documento"]) ?></td>
                        <td><?= HtmlEncode($value["nro_documento"]) ?></td>
                        <td><?= HtmlEncode($value["doc_afectado"]) ?></td>
                        <td><?= HtmlEncode($value["fecha"]) ?></td>
                        <td><?= HtmlEncode($value["nota"]) ?></td>
                        <td class="text-right"><?= floatval($value["unidades"]) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <h3>Detalle de la transferencia</h3>

    <?php if (count($detalle) == 0) { ?>

        <div class="alert alert-warning">
            No se pudo mostrar el detalle de la transferencia.
        </div>

    <?php } else { ?>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Artículo</th>
                        <th>Lote</th>
                        <th>Vencimiento</th>
                        <th>Almacén Origen</th>
                        <th>Almacén Destino</th>
                        <th class="text-right">Cantidad</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($detalle as $item) { ?>
                        <tr>
                            <td><?= HtmlEncode($item["codigo"]) ?></td>

                            <td>
                                <?= HtmlEncode(trim($item["nombre_comercial"] . " " . $item["principio_activo"] . " " . $item["presentacion"])) ?>
                            </td>

                            <td><?= HtmlEncode($item["lote"]) ?></td>
                            <td><?= HtmlEncode($item["fecha_vencimiento"]) ?></td>

                            <td>
                                <?= HtmlEncode($item["almacen_origen"]) ?>
                                -
                                <?= HtmlEncode($item["nombre_almacen_origen"]) ?>
                            </td>

                            <td>
                                <?= HtmlEncode($item["almacen_destino"]) ?>
                                -
                                <?= HtmlEncode($item["nombre_almacen_destino"]) ?>
                            </td>

                            <td class="text-right"><?= floatval($item["cantidad"]) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="6" class="text-right">Total transferido</th>
                        <th class="text-right"><?= $total_unidades ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>

    <?php } ?>

    <p>
        <a href="TransferenciaArticulo" class="btn btn-primary">
            Realizar Otra Transferencia
        </a>
    </p>

</div>

<?= GetDebugMessage() ?>
