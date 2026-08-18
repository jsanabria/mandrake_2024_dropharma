<?php

namespace PHPMaker2024\mandrake;

// Page object
$TransferenciaArticuloListar = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
if (!isset($_POST["articulo"]) || trim($_POST["articulo"]) == "") {
    header("Location: TransferenciaArticulo");
    die();
}

$articulo = trim($_POST["articulo"]);
$articulo_sql = AdjustSql($articulo);

$tipo_documento = ExecuteScalar("SELECT valor1 FROM parametro WHERE codigo = '050' LIMIT 1");
if ($tipo_documento == "") {
    $tipo_documento = "TDCNET";
}
$tipo_documento_sql = AdjustSql($tipo_documento);

$sql = "
SELECT
    a.id,
    a.codigo,
    a.codigo_ims,
    a.codigo_de_barra,
    a.nombre_comercial,
    a.principio_activo,
    a.presentacion,
    b.nombre AS fabricante,

    IFNULL((
        SELECT SUM(x.cantidad_movimiento)
        FROM (
            SELECT
                es.articulo,
                es.cantidad_movimiento
            FROM entradas_salidas AS es
            JOIN entradas AS e
                ON e.tipo_documento = es.tipo_documento
               AND e.id = es.id_documento
            JOIN almacen AS al
                ON al.codigo = es.almacen
               AND al.movimiento = 'S'
            WHERE (
                    (es.tipo_documento = 'TDCAEN'
                     AND e.estatus <> 'ANULADO')
                    OR
                    (es.tipo_documento = 'TDCNRP'
                     AND es.check_ne = 'S'
                     AND e.estatus <> 'ANULADO')
                  )
              AND es.newdata = 'S'

            UNION ALL

            SELECT
                es.articulo,
                es.cantidad_movimiento
            FROM entradas_salidas AS es
            JOIN salidas AS s
                ON s.tipo_documento = es.tipo_documento
               AND s.id = es.id_documento
            JOIN almacen AS al
                ON al.codigo = es.almacen
               AND al.movimiento = 'S'
            WHERE (
                    (es.tipo_documento = 'TDCPDV'
                     AND s.estatus = 'NUEVO')
                    OR
                    (es.tipo_documento IN ('$tipo_documento_sql', 'TDCASA')
                     AND s.estatus <> 'ANULADO')
                  )
              AND es.newdata = 'S'
        ) AS x
        WHERE x.articulo = a.id
    ), 0) AS existencia

FROM articulo AS a
JOIN fabricante AS b
    ON b.Id = a.fabricante

WHERE
       a.codigo LIKE '%$articulo_sql%'
    OR a.codigo_ims LIKE '%$articulo_sql%'
    OR a.codigo_de_barra LIKE '%$articulo_sql%'
    OR a.nombre_comercial LIKE '%$articulo_sql%'
    OR a.principio_activo LIKE '%$articulo_sql%'
    OR a.presentacion LIKE '%$articulo_sql%'

ORDER BY
    a.principio_activo,
    a.nombre_comercial,
    a.presentacion

LIMIT 100;
";

$rows = ExecuteRows($sql);
?>

<form id="frm" name="frm" method="post" action="TransferenciaArticuloDetalle">

<div class="container">

    <h3>Resultado de búsqueda</h3>

    <p class="text-muted">
        Búsqueda: <strong><?= HtmlEncode($articulo) ?></strong>
    </p>

    <?php if (count($rows) == 0) { ?>

        <div class="alert alert-warning">
            No se encontraron artículos con el criterio indicado.
        </div>

        <a href="TransferenciaArticulo" class="btn btn-default">Regresar</a>

    <?php } else { ?>

        <div class="table-responsive">

            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Código</th>
                        <th>Cód. Barra</th>
                        <th>Nombre Comercial</th>
                        <th>Principio Activo</th>
                        <th>Presentación</th>
                        <th>Fabricante</th>
                        <th class="text-right">Existencia</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($rows as $value) { 
                        $existencia = intval($value["existencia"]);
                    ?>
                        <tr>
                            <td class="text-center">
                                <?php if ($existencia > 0) { ?>
                                    <input 
                                        type="radio" 
                                        name="xArticulo" 
                                        value="<?= intval($value["id"]) ?>"
                                    >
                                <?php } ?>
                            </td>

                            <td><?= HtmlEncode($value["codigo"]) ?></td>
                            <td><?= HtmlEncode($value["codigo_de_barra"]) ?></td>
                            <td><?= HtmlEncode($value["nombre_comercial"]) ?></td>
                            <td><?= HtmlEncode($value["principio_activo"]) ?></td>
                            <td><?= HtmlEncode($value["presentacion"]) ?></td>
                            <td><?= HtmlEncode($value["fabricante"]) ?></td>
                            <td class="text-right"><?= $existencia ?></td>
                        </tr>
                    <?php } ?>
                </tbody>

                <tfoot>
                    <tr>
                        <td class="text-center" colspan="8">
                            <button type="button" class="btn btn-primary" onclick="validarSeleccion();">
                                Ver Detalle Existencia
                            </button>

                            &nbsp;

                            <a href="TransferenciaArticulo" class="btn btn-default">
                                Regresar
                            </a>
                        </td>
                    </tr>
                </tfoot>
            </table>

        </div>

    <?php } ?>

</div>

</form>

<script>
function validarSeleccion() {
    if ($("input[name='xArticulo']:checked").length === 0) {
        ew.alert("Debe seleccionar un artículo con existencia disponible.");
        return false;
    }

    $("#frm").submit();
    return true;
}
</script>

<?= GetDebugMessage() ?>
