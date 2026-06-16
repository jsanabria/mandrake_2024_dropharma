<?php

namespace PHPMaker2024\mandrake;

// Page object
$CrearNotaEntradaUpdate = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
/**
 * CrearNotaEntradaUpdate.php
 *
 * Pantalla opcional de revisión posterior a la creación de Nota de Entrega.
 * - Look alineado con CrearNotaEntrega.php.
 * - Permite revisar y, si se requiere, ajustar lote/cantidad/unidad antes de finalizar.
 * - Aplica lógica parametro 110:
 *      110 = S => validar/mostrar existencia solo en almacén principal.
 *      110 = N => validar/mostrar existencia en todos los almacenes.
 */

function CneuSql($value)
{
    return AdjustSql(trim((string)$value));
}

function CneuHtml($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

function CneuFechaSql($fecha)
{
    $fecha = trim((string)$fecha);
    return ($fecha == "" || $fecha == "0000-00-00") ? "1990-01-01" : $fecha;
}

function CneuFechaMostrar($fecha)
{
    $fecha = CneuFechaSql($fecha);
    if ($fecha == "1990-01-01" || $fecha == "2027-01-01") {
        return "";
    }
    $ts = strtotime($fecha);
    return $ts ? date("d/m/Y", $ts) : "";
}

function CneuParam($codigo, $default = "")
{
    $codigo = CneuSql($codigo);
    $valor = ExecuteScalar("SELECT valor1 FROM parametro WHERE codigo = '$codigo' LIMIT 1");
    return ($valor === null || $valor === false || $valor === "") ? $default : $valor;
}

$id = isset($_REQUEST["id"]) ? intval($_REQUEST["id"]) : 0;

if ($id <= 0) {
    $_SESSION["failure"] = "Nota de Entrega inválida.";
    header("Location: ViewOutTdcnetList");
    die();
}

$tipo_documento_inventario = CneuParam("050", "TDCNET");
$almacen = CneuParam("002", "");
$almacenconsig = CneuParam("014", "");

$param_no_aplicar_almacenes = ExecuteScalar("SELECT valor1 FROM parametro WHERE codigo = '110' LIMIT 1");
$param_no_aplicar_almacenes = strtoupper(trim($param_no_aplicar_almacenes ?? "S"));

$filtroAlmacen = "";
if ($param_no_aplicar_almacenes == "S") {
    $filtroAlmacen = " AND a.almacen = '" . CneuSql($almacen) . "' ";
}

$sql = "SELECT
            c.descripcion AS tipo,
            b.nombre AS cliente,
            a.nro_documento,
            DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha_documento,
            a.fecha,
            a.tipo_documento,
            a.nota,
            a.estatus,
            IFNULL(a.unidades, 0) AS unidades
        FROM salidas AS a
        LEFT JOIN cliente AS b ON b.id = a.cliente
        LEFT JOIN tipo_documento AS c ON c.codigo = a.tipo_documento
        WHERE a.id = $id
        LIMIT 1";
$doc = ExecuteRow($sql);

if (!$doc) {
    $_SESSION["failure"] = "No se encontró la Nota de Entrega.";
    header("Location: ViewOutTdcnetList");
    die();
}

$tipo = $doc["tipo"] ?? "";
$tipo_documento = $doc["tipo_documento"] ?? "";
$cliente = $doc["cliente"] ?? "";
$nro_documento = $doc["nro_documento"] ?? "";
$fecha_documento = $doc["fecha_documento"] ?? "";
$nota = $doc["nota"] ?? "";
$estatus = $doc["estatus"] ?? "";
$unidades = floatval($doc["unidades"] ?? 0);

$sql = "SELECT
            a.id,
            IFNULL(b.nombre, '') AS fabricante,
            TRIM(CONCAT(
                IFNULL(c.principio_activo, ''),
                IF(IFNULL(c.presentacion, '') <> '', CONCAT(', ', c.presentacion), ''),
                IF(IFNULL(c.nombre_comercial, '') <> '', CONCAT(', ', c.nombre_comercial), '')
            )) AS articulo,
            a.cantidad_articulo,
            IFNULL(d.descripcion, '') AS unidad_medida,
            IFNULL(d.cantidad, 1) AS unidad_factor,
            a.articulo AS codart,
            a.lote,
            IFNULL(a.fecha_vencimiento, '1990-01-01') AS fecha_vencimiento,
            a.almacen AS codalm,
            IFNULL(al.descripcion, a.almacen) AS almacen_nombre,
            a.articulo_unidad_medida,
            IFNULL(a.id_compra, 999999) AS id_compra
        FROM entradas_salidas AS a
        LEFT JOIN fabricante AS b ON b.Id = a.fabricante
        LEFT JOIN articulo AS c ON c.id = a.articulo
        LEFT JOIN unidad_medida AS d ON d.codigo = a.articulo_unidad_medida
        LEFT JOIN almacen AS al ON al.codigo = a.almacen
        WHERE a.tipo_documento = '" . CneuSql($tipo_documento) . "'
          AND a.id_documento = $id
        ORDER BY articulo, a.id";
$rows = ExecuteRows($sql) ?: [];
$cantidad = count($rows);
?>

<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
        <h1 class="h4 text-secondary mb-0">
            <i class="fa fa-clipboard-check"></i> Revisar Nota de Entrega
        </h1>
        <a href="ViewOutTdcnetList" class="btn btn-sm btn-outline-secondary">
            <i class="fa fa-arrow-left"></i> Volver al listado
        </a>
    </div>

    <?php if (!empty($_SESSION["failure"])) { ?>
        <div id="msgFailure" class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-triangle"></i>
            <?= CneuHtml($_SESSION["failure"]) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <script>
        loadjs.ready(["jquery"], function () {
            setTimeout(function () { jQuery("#msgFailure").fadeOut(); }, 5000);
        });
        </script>
    <?php unset($_SESSION["failure"]); } ?>

    <form id="frm" name="frm" method="post" action="#">
        <?php if (isset($TokenNameKey, $TokenName, $TokenValueKey, $TokenValue)) { ?>
            <input type="hidden" name="<?= CneuHtml($TokenNameKey) ?>" value="<?= CneuHtml($TokenName) ?>">
            <input type="hidden" name="<?= CneuHtml($TokenValueKey) ?>" value="<?= CneuHtml($TokenValue) ?>">
        <?php } ?>

        <div class="card shadow-sm mb-3">
            <div class="card-header bg-light py-2">
                <span class="small fw-bold text-secondary">Nota de Entrega generada</span>
            </div>
            <div class="card-body p-3">
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold mb-1 d-block">Tipo</label>
                        <input type="text" class="form-control form-control-sm bg-light" value="<?= CneuHtml($tipo) ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold mb-1 d-block">Nro Documento</label>
                        <input type="text" class="form-control form-control-sm bg-light fw-bold text-primary" value="<?= CneuHtml($nro_documento) ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold mb-1 d-block">Fecha</label>
                        <input type="text" class="form-control form-control-sm bg-light" value="<?= CneuHtml($fecha_documento) ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold mb-1 d-block">Estatus</label>
                        <input type="text" class="form-control form-control-sm bg-light" value="<?= CneuHtml($estatus) ?>" readonly>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold mb-1 d-block">Almacén referencia</label>
                        <input type="text" class="form-control form-control-sm bg-light" value="<?= CneuHtml($almacen) ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold mb-1 d-block">Filtro almacén</label>
                        <input type="text" class="form-control form-control-sm bg-light" value="<?= ($param_no_aplicar_almacenes == 'S') ? 'Solo almacén principal' : 'Todos los almacenes' ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold mb-1 d-block">Renglones</label>
                        <input type="text" class="form-control form-control-sm bg-light" value="<?= intval($cantidad) ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold mb-1 d-block">Unidades</label>
                        <input type="text" class="form-control form-control-sm bg-light" value="<?= number_format($unidades, 2, ',', '.') ?>" readonly>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-bold mb-1 d-block">Cliente</label>
                        <textarea class="form-control form-control-sm bg-light fw-bold text-primary" rows="1" readonly><?= CneuHtml($cliente) ?></textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-bold mb-1 d-block">Nota / Observación</label>
                        <textarea id="nota" name="nota" class="form-control form-control-sm" rows="2"><?= CneuHtml($nota) ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-info py-2">
            <i class="fa fa-info-circle"></i>
            Esta pantalla es una revisión opcional. Los lotes y cantidades ya fueron asignados automáticamente por FEFO. Solo modifica si necesitas corregir algo puntualmente.
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                <span class="small fw-bold text-secondary">Detalle de la Nota de Entrega</span>
                <button id="enviar" name="enviar" class="btn btn-sm btn-primary" type="button">
                    <i class="fa fa-save"></i> Finalizar revisión
                </button>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-striped align-middle mb-0" id="tablaNotaEntregaUpdate" style="font-size:0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th style="width:45px;"></th>
                                <th style="width:45px;">#</th>
                                <th>Fabricante</th>
                                <th>Artículo</th>
                                <th class="text-end">Cantidad</th>
                                <th>U.M.</th>
                                <th>Lote / Venc. / Existencia</th>
                                <th class="text-end" style="width:120px;">Cantidad</th>
                                <th style="width:130px;">Unidad</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($cantidad == 0) { ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-3">No hay renglones para revisar.</td>
                            </tr>
                        <?php } ?>

                        <?php foreach ($rows as $i => $row) {
                            $codart = intval($row["codart"]);
                            $loteActual = (string)($row["lote"] ?? "");
                            $fechaActual = CneuFechaSql($row["fecha_vencimiento"] ?? "");
                            $almacenActual = (string)($row["codalm"] ?? "");

                            $sqlLots = "SELECT
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
                                              AND a.articulo = $codart
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
                                            WHERE (
                                                    (a.tipo_documento IN ('" . CneuSql($tipo_documento_inventario) . "', 'TDCASA') AND b.estatus <> 'ANULADO')
                                                  )
                                              AND a.articulo = $codart
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
                                            x.lote ASC";
                            $lotes = ExecuteRows($sqlLots) ?: [];
                        ?>
                            <tr>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminar_linea(<?= intval($row["id"]) ?>);" title="Eliminar línea">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                                <td>
                                    <?= ($i + 1) ?>
                                    <input type="hidden" id="id_<?= $i ?>" name="id_<?= $i ?>" value="<?= intval($row["id"]) ?>">
                                    <input type="hidden" id="articulo_<?= $i ?>" name="articulo_<?= $i ?>" value="<?= intval($row["codart"]) ?>">
                                </td>
                                <td><?= CneuHtml($row["fabricante"] ?? "") ?></td>
                                <td><?= CneuHtml($row["articulo"] ?? "") ?></td>
                                <td class="text-end"><?= number_format(floatval($row["cantidad_articulo"] ?? 0), 2, ",", ".") ?></td>
                                <td><?= CneuHtml($row["unidad_medida"] ?? "") ?>, <?= CneuHtml($row["unidad_factor"] ?? 1) ?></td>
                                <td>
                                    <select id="lote_<?= $i ?>" name="lote_<?= $i ?>" class="form-select form-select-sm" onchange="RecorrerLotes(this.value, <?= $i ?>);">
                                        <option value=""></option>
                                        <?php
                                        $seleccionadoEncontrado = false;
                                        foreach ($lotes as $row2) {
                                            $lote = (string)($row2["lote"] ?? "");
                                            $fecha = CneuFechaSql($row2["fecha_vencimiento"] ?? "");
                                            $codalm = (string)($row2["codalm"] ?? "");
                                            $exist = intval($row2["cantidad"] ?? 0);
                                            $textoFecha = CneuFechaMostrar($fecha);
                                            $selected = ($loteActual . "|" . $fechaActual . "|" . $almacenActual == $lote . "|" . $fecha . "|" . $codalm);
                                            if ($selected) {
                                                $seleccionadoEncontrado = true;
                                            }
                                            $value = $lote . "|" . $fecha . "|" . $exist . "|" . $codalm;
                                            $texto = trim($lote . ", " . $textoFecha . " | " . $exist . " | " . ($row2["almacen"] ?? ""));
                                        ?>
                                            <option value="<?= CneuHtml($value) ?>" <?= $selected ? "selected" : "" ?>><?= CneuHtml($texto) ?></option>
                                        <?php } ?>
                                        <?php if (!$seleccionadoEncontrado && intval($row["id_compra"] ?? 999999) == 0) {
                                            $value = $loteActual . "|" . $fechaActual . "|0|" . $almacenActual;
                                            $texto = trim($loteActual . ", " . CneuFechaMostrar($fechaActual) . " | 0 | " . ($row["almacen_nombre"] ?? $almacenActual));
                                        ?>
                                            <option value="<?= CneuHtml($value) ?>" selected><?= CneuHtml($texto) ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="number"
                                           id="cantidad_<?= $i ?>"
                                           name="cantidad_<?= $i ?>"
                                           class="form-control form-control-sm text-end"
                                           min="0"
                                           step="1"
                                           onchange="validar_existencia(<?= $i ?>);"
                                           value="<?= floatval($row["cantidad_articulo"] ?? 0) ?>">
                                </td>
                                <td>
                                    <select id="unidad_<?= $i ?>" name="unidad_<?= $i ?>" class="form-select form-select-sm" onchange="validar_existencia(<?= $i ?>);">
                                        <option value="<?= CneuHtml($row["articulo_unidad_medida"] ?? "") ?>"><?= CneuHtml($row["unidad_medida"] ?? "") ?></option>
                                    </select>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" id="cantidad" name="cantidad" value="<?= intval($cantidad) ?>">
        <input type="hidden" name="username" value="<?= CneuHtml(CurrentUserName()) ?>">
    </form>
</div>

<script>
loadjs.ready(["jquery"], function () {
    const $ = jQuery;

    window.validar_existencia = function (i) {
        const id = $("#id_" + i).val();
        const lote = $("#lote_" + i).val();
        const cantidad = $("#cantidad_" + i).val();
        const unidad = $("#unidad_" + i).val();
        const articulo = $("#articulo_" + i).val();

        if (!lote || !cantidad || !unidad) {
            return false;
        }

        if (parseFloat(cantidad) < 0) {
            ew.alert("La cantidad no puede ser menor a 0.");
            $("#cantidad_" + i).val("").focus();
            return false;
        }

        $("#enviar").prop("disabled", true);

        $.ajax({
            data: {
                id: id,
                lote: lote,
                cantidad: cantidad,
                unidad: unidad,
                articulo: articulo
            },
            url: "include/verificar_existencia_update.php",
            type: "POST"
        })
        .done(function (response) {
            if (String(response).trim() === "0") {
                ew.alert("La cantidad solicitada o lo pedido en el ítem es mayor a la existencia del lote.");
                $("#cantidad_" + i).val("").focus();
                $("#enviar").prop("disabled", false);
                return false;
            }

            ew.alert("Se actualizó la cantidad solicitada de este artículo.");
            $("#enviar").prop("disabled", false);
            location.reload();
            return true;
        })
        .fail(function () {
            ew.alert("Error de comunicación al validar existencia.");
            $("#enviar").prop("disabled", false);
        });
    };

    window.RecorrerLotes = function (id, ind) {
        validar_existencia(ind);
    };

    window.eliminar_linea = function (id) {
        if (!confirm("¿Está seguro de eliminar este registro?")) {
            return false;
        }

        $("#enviar").prop("disabled", true);

        $.ajax({
            data: { id: id },
            url: "include/eliminar_linea.php",
            type: "POST"
        })
        .done(function (response) {
            if (String(response).trim() === "1") {
                location.reload();
            } else {
                ew.alert("No se pudo eliminar la línea.");
                $("#enviar").prop("disabled", false);
            }
        })
        .fail(function () {
            ew.alert("Error de comunicación al eliminar la línea.");
            $("#enviar").prop("disabled", false);
        });
    };

    $("#enviar").on("click", function () {
        const cantidadFilas = <?= intval($cantidad) ?>;

        for (let i = 0; i < cantidadFilas; i++) {
            const lote = $("#lote_" + i).val();
            const cantidad = $("#cantidad_" + i).val();
            const unidad = $("#unidad_" + i).val();

            if (!lote) {
                ew.alert("Debe indicar el lote en la fila " + (i + 1));
                $("#lote_" + i).focus();
                return false;
            }

            if (!cantidad) {
                ew.alert("Debe indicar la cantidad en la fila " + (i + 1));
                $("#cantidad_" + i).focus();
                return false;
            }

            if (!unidad) {
                ew.alert("Debe indicar la unidad de medida en la fila " + (i + 1));
                $("#unidad_" + i).focus();
                return false;
            }
        }

        if (!confirm("¿Desea finalizar la revisión de esta Nota de Entrega?")) {
            return false;
        }

        const xid = <?= intval($id) ?>;
        const xnota = $("#nota").val();

        $("#enviar").prop("disabled", true);

        $.ajax({
            data: {
                id: xid,
                nota: xnota
            },
            url: "ActualizarNotaEntrega",
            type: "POST"
        })
        .done(function () {
            window.location.href = "ViewOutTdcnetList";
        })
        .fail(function () {
            ew.alert("Error de comunicación al actualizar la Nota de Entrega.");
            $("#enviar").prop("disabled", false);
        });
    });
});
</script>

<style>
#tablaNotaEntregaUpdate td,
#tablaNotaEntregaUpdate th {
    vertical-align: middle;
}

.form-control:focus,
.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 .15rem rgba(13, 110, 253, .20);
}
</style>

<?= GetDebugMessage() ?>
