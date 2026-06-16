<?php

namespace PHPMaker2024\mandrake;

// Page object
$CrearNotaEntrega = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
/**
 * CrearNotaEntrega.php
 *
 * Optimización:
 * - Interfaz Bootstrap más limpia.
 * - Asignación sugerida automática por FEFO: primero el lote con fecha de vencimiento más próxima.
 * - Si un lote no alcanza, divide el renglón en varios lotes.
 * - Mantiene compatibilidad con CrearNotaEntregaGuardar:
 *   id_#, articulo_#, lote_#, cantidad_#, unidad_#, cantidad.
 */

function CneSql($value)
{
    return AdjustSql(trim((string)$value));
}

function CneFechaSql($fecha)
{
    $fecha = trim((string)$fecha);
    return ($fecha == "" || $fecha == "0000-00-00") ? "1990-01-01" : $fecha;
}

function CneFechaMostrar($fecha)
{
    $fecha = CneFechaSql($fecha);

    if ($fecha == "1990-01-01" || $fecha == "2027-01-01") {
        return "";
    }

    $ts = strtotime($fecha);
    return $ts ? date("d/m/Y", $ts) : "";
}

function CneParam($codigo, $default = "")
{
    $codigo = CneSql($codigo);
    $valor = ExecuteScalar("SELECT valor1 FROM parametro WHERE codigo = '$codigo' LIMIT 1");
    return ($valor === null || $valor === false || $valor === "") ? $default : $valor;
}

function CneHtml($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

$id = isset($_REQUEST["id"]) ? intval($_REQUEST["id"]) : 0;

if ($id <= 0) {
    $_SESSION["failure"] = "Documento origen inválido.";
    header("Location: ViewOutTdcpdvList");
    die();
}

$tipo_documento_inventario = CneParam("050", "TDCNET");
$almacen = CneParam("002", "");
$almacenconsig = CneParam("014", "");

$param_no_aplicar_almacenes = ExecuteScalar("SELECT valor1 FROM parametro WHERE codigo = '110' LIMIT 1");
$param_no_aplicar_almacenes = strtoupper(trim($param_no_aplicar_almacenes ?? "S"));

$filtroAlmacen = "";

if ($param_no_aplicar_almacenes == "S") {
    $filtroAlmacen = " AND a.almacen = '" . CneSql($almacen) . "' ";
}

$sql = "SELECT
            c.descripcion AS tipo,
            b.nombre AS cliente,
            a.nro_documento,
            DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha_documento,
            a.fecha,
            a.tipo_documento,
            a.nota
        FROM salidas AS a
        LEFT JOIN cliente AS b ON b.id = a.cliente
        LEFT JOIN tipo_documento AS c ON c.codigo = a.tipo_documento
        WHERE a.id = $id
        LIMIT 1";
$doc = ExecuteRow($sql);

if (!$doc) {
    $_SESSION["failure"] = "No se encontró el documento origen.";
    header("Location: ViewOutTdcpdvList");
    die();
}

$tipo = $doc["tipo"] ?? "";
$tipo_documento = $doc["tipo_documento"] ?? "";
$cliente = $doc["cliente"] ?? "";
$nro_documento = $doc["nro_documento"] ?? "";
$fecha_documento = $doc["fecha_documento"] ?? "";
$nota = $doc["nota"] ?? "";

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
            a.fabricante AS codfab,
            a.articulo_unidad_medida
        FROM entradas_salidas AS a
        LEFT JOIN fabricante AS b ON b.Id = a.fabricante
        LEFT JOIN articulo AS c ON c.id = a.articulo
        LEFT JOIN unidad_medida AS d ON d.codigo = a.articulo_unidad_medida
        WHERE a.tipo_documento = '" . CneSql($tipo_documento) . "'
          AND a.id_documento = $id
        ORDER BY articulo";
$lineasOrigen = ExecuteRows($sql) ?: [];

$asignaciones = [];
$alertas = [];
$totalSolicitado = 0;
$totalAsignado = 0;

foreach ($lineasOrigen as $linea) {
    $idDetalle = intval($linea["id"]);
    $codart = intval($linea["codart"]);
    $cantidadSolicitada = floatval($linea["cantidad_articulo"]);
    $totalSolicitado += $cantidadSolicitada;

    $sqlLots = "SELECT
                    x.articulo,
                    x.lote,
                    x.fecha,
                    x.fecha_vencimiento,
                    x.codalm,
                    x.almacen,
                    SUM(x.cantidad_movimiento) AS cantidad
                FROM (
                    SELECT
                        a.articulo,
                        IFNULL(a.lote, '') AS lote,
                        DATE_FORMAT(IFNULL(a.fecha_vencimiento, '1990-01-01'), '%d/%m/%Y') AS fecha,
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
                        DATE_FORMAT(IFNULL(a.fecha_vencimiento, '1990-01-01'), '%d/%m/%Y') AS fecha,
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
		                    -- (a.tipo_documento = 'TDCPDV' AND b.estatus = 'NUEVO')
		                    -- OR
                            (a.tipo_documento IN ('" . CneSql($tipo_documento_inventario) . "', 'TDCASA') AND b.estatus <> 'ANULADO')
                          )
                      AND a.articulo = $codart
                      $filtroAlmacen
                      AND a.newdata = 'S'
                ) AS x
                WHERE (x.fecha_vencimiento = '1990-01-01' OR x.fecha_vencimiento >= CURDATE())
                GROUP BY x.articulo, x.lote, x.fecha, x.fecha_vencimiento, x.codalm, x.almacen
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

    $restante = $cantidadSolicitada;
    $existenciaTotal = 0;

    foreach ($lotes as $loteRow) {
        $existenciaTotal += floatval($loteRow["cantidad"]);
    }

    if ($existenciaTotal <= 0) {
        $alertas[] = "Sin existencia disponible para: " . ($linea["articulo"] ?? "Artículo #$codart");
        continue;
    }

    foreach ($lotes as $loteRow) {
        if ($restante <= 0) {
            break;
        }

        $disponible = floatval($loteRow["cantidad"]);
        if ($disponible <= 0) {
            continue;
        }

        $cantidadAsignada = min($restante, $disponible);
        $restante -= $cantidadAsignada;
        $totalAsignado += $cantidadAsignada;

        $asignaciones[] = [
            "id_origen" => $idDetalle,
            "fabricante" => $linea["fabricante"] ?? "",
            "articulo_texto" => $linea["articulo"] ?? "",
            "articulo" => $codart,
            "cantidad_origen" => $cantidadSolicitada,
            "unidad_medida" => $linea["unidad_medida"] ?? "",
            "unidad_factor" => $linea["unidad_factor"] ?? 1,
            "articulo_unidad_medida" => $linea["articulo_unidad_medida"] ?? "",
            "lote" => $loteRow["lote"] ?? "",
            "fecha_vencimiento" => CneFechaSql($loteRow["fecha_vencimiento"] ?? ""),
            "fecha_mostrar" => CneFechaMostrar($loteRow["fecha_vencimiento"] ?? ""),
            "existencia" => $disponible,
            "cantidad" => $cantidadAsignada,
            "codalm" => $loteRow["codalm"] ?? "",
            "almacen" => $loteRow["almacen"] ?? ""
        ];
    }

    if ($restante > 0) {
        $alertas[] = "Existencia insuficiente para " . ($linea["articulo"] ?? "Artículo #$codart") .
            ". Solicitado: " . number_format($cantidadSolicitada, 2, ",", ".") .
            ". Disponible: " . number_format($existenciaTotal, 2, ",", ".") . ".";
    }
}

$cantidad = count($asignaciones);
$puedeEnviar = ($cantidad > 0 && count($alertas) == 0);
?>

<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
        <h1 class="h4 text-secondary mb-0">
            <i class="fa fa-truck"></i> Crear Nota de Entrega 
        </h1>
        <a href="javascript:history.back();" class="btn btn-sm btn-outline-secondary">
            <i class="fa fa-arrow-left"></i> Regresar
        </a>
    </div>

	<?php if (!empty($_SESSION["failure"])) { ?>
	    <div id="msgFailure" class="alert alert-danger alert-dismissible fade show" role="alert">
	        <i class="fa fa-exclamation-triangle"></i>
	        <?= CneHtml($_SESSION["failure"]) ?>
	        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
	    </div>

	    <script>
	    setTimeout(function () {
	        $("#msgFailure").fadeOut();
	    }, 5000);
	    </script>
	<?php
	    unset($_SESSION["failure"]);
	}
	?>    

    <form id="frm" name="frm" method="post" action="CrearNotaEntregaGuardar">
        <?php if (isset($TokenNameKey, $TokenName, $TokenValueKey, $TokenValue)) { ?>
            <input type="hidden" name="<?= CneHtml($TokenNameKey) ?>" value="<?= CneHtml($TokenName) ?>">
            <input type="hidden" name="<?= CneHtml($TokenValueKey) ?>" value="<?= CneHtml($TokenValue) ?>">
        <?php } ?>

        <div class="card shadow-sm mb-3">
            <div class="card-header bg-light py-2">
                <span class="small fw-bold text-secondary">Documento origen</span>
            </div>
            <div class="card-body p-3">
                <div class="row g-2">
					<div class="col-md-3">
					    <label class="form-label small fw-bold mb-1 d-block">Tipo</label>
					    <input type="text"
					           class="form-control form-control-sm bg-light"
					           value="<?= CneHtml($tipo) ?>"
					           readonly>
					</div>                    
                    <div class="col-md-3">
                        <label class="form-label small fw-bold mb-1">Nro Documento</label>
                        <input type="text" class="form-control form-control-sm bg-light fw-bold text-primary" value="<?= CneHtml($nro_documento) ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold mb-1">Fecha</label>
                        <input type="text" class="form-control form-control-sm bg-light" value="<?= CneHtml($fecha_documento) ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold mb-1">Almacén despacho</label>
                        <input type="text" class="form-control form-control-sm bg-light" value="<?= CneHtml($almacen) ?>" readonly>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-bold mb-1">Cliente</label>
                        <textarea class="form-control form-control-sm bg-light fw-bold text-primary" rows="1" readonly><?= CneHtml($cliente) ?></textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-bold mb-1">Nota / Observación</label>
                        <textarea id="nota" name="nota" class="form-control form-control-sm" rows="2"><?= CneHtml($nota) ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <?php if (count($alertas) > 0) { ?>
            <div class="alert alert-warning">
                <div class="fw-bold mb-1"><i class="fa fa-triangle-exclamation"></i> Atención</div>
                <ul class="mb-0">
                    <?php foreach ($alertas as $msg) { ?>
                        <li><?= CneHtml($msg) ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <div class="alert alert-info py-2">
            <i class="fa fa-info-circle"></i>
            La asignación sugerida usa FEFO: primero los lotes con vencimiento más próximo. Si un lote no alcanza, el renglón se divide automáticamente.
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                <span class="small fw-bold text-secondary">Renglones sugeridos para la Nota de Entrega</span>
                <div class="small">
                    <span class="badge text-bg-secondary">Solicitado: <?= number_format($totalSolicitado, 2, ",", ".") ?></span>
                    <span class="badge text-bg-success">Asignado: <?= number_format($totalAsignado, 2, ",", ".") ?></span>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-striped align-middle mb-0" id="tablaNotaEntrega" style="font-size:0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th style="width:40px;"></th>
    							<th style="width:45px;">#</th>
                                <th>Fabricante</th>
                                <th>Artículo</th>
                                <th class="text-end">Pedido</th>
                                <th>U.M.</th>
                                <th>Lote</th>
                                <th>Venc.</th>
                                <th>Almacén</th>
                                <th class="text-end">Exist.</th>
                                <th class="text-end" style="width:120px;">Cantidad</th>
                                <th>Unidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($cantidad == 0) { ?>
                                <tr>
                                    <td colspan="11" class="text-center text-muted py-3">
                                        No hay renglones disponibles para crear la Nota de Entrega.
                                    </td>
                                </tr>
                            <?php } ?>

                            <?php foreach ($asignaciones as $i => $row) { 
                                $loteValue = $row["lote"] . "|" . $row["fecha_vencimiento"] . "|" . intval($row["existencia"]) . "|" . $row["codalm"];
                            ?>
                                <tr id="fila_<?= $i ?>">
								    <td class="text-center">
								        <button type="button"
								                class="btn btn-sm btn-outline-danger eliminar-fila"
								                data-row="<?= $i ?>"
								                title="Eliminar">
								            <i class="fa fa-trash"></i>
								        </button>
								    </td>

								    <td>
								        <?= ($i + 1) ?>
                                        <input type="hidden" id="id_<?= $i ?>" name="id_<?= $i ?>" value="<?= intval($row["id_origen"]) ?>">
                                        <input type="hidden" id="articulo_<?= $i ?>" name="articulo_<?= $i ?>" value="<?= intval($row["articulo"]) ?>">
                                        <input type="hidden"
									       id="eliminar_<?= $i ?>"
									       name="eliminar_<?= $i ?>"
									       value="N">
                                        <input type="hidden" id="lote_<?= $i ?>" name="lote_<?= $i ?>" value="<?= CneHtml($loteValue) ?>">
                                    </td>
                                    <td><?= CneHtml($row["fabricante"]) ?></td>
                                    <td><?= CneHtml($row["articulo_texto"]) ?></td>
                                    <td class="text-end"><?= number_format(floatval($row["cantidad_origen"]), 2, ",", ".") ?></td>
                                    <td><?= CneHtml($row["unidad_medida"]) ?>, <?= CneHtml($row["unidad_factor"]) ?></td>
                                    <td class="fw-bold"><?= CneHtml($row["lote"]) ?></td>
                                    <td><?= CneHtml($row["fecha_mostrar"]) ?></td>
                                    <td><?= CneHtml($row["almacen"]) ?></td>
                                    <td class="text-end"><?= number_format(floatval($row["existencia"]), 2, ",", ".") ?></td>
                                    <td>
                                        <input type="number"
                                               id="cantidad_<?= $i ?>"
                                               name="cantidad_<?= $i ?>"
                                               class="form-control form-control-sm text-end cne-cantidad"
                                               min="1"
                                               step="1"
                                               max="<?= floatval($row["existencia"]) ?>"
                                               data-max="<?= floatval($row["existencia"]) ?>"
                                               value="<?= floatval($row["cantidad"]) ?>">
                                    </td>
                                    <td>
                                        <select id="unidad_<?= $i ?>" name="unidad_<?= $i ?>" class="form-select form-select-sm cne-unidad">
                                            <option value="<?= CneHtml($row["articulo_unidad_medida"]) ?>"><?= CneHtml($row["unidad_medida"]) ?></option>
                                        </select>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer text-end">
                <button id="enviar" name="enviar" class="btn btn-primary" type="button" <?= $puedeEnviar ? "" : "disabled" ?>>
                    <i class="fa fa-paper-plane"></i> Crear Nota de Entrega
                </button>
            </div>
        </div>

        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="cantidad" value="<?= $cantidad ?>">
        <input type="hidden" name="username" value="<?= CneHtml(CurrentUserName()) ?>">
    </form>
</div>

<script>
loadjs.ready(["jquery"], function () {
    const $ = jQuery;

    function validarExistenciaFila(i, callback) {
        const id = $("#id_" + i).val();
        const lote = $("#lote_" + i).val();
        const cantidad = $("#cantidad_" + i).val();
        const unidad = $("#unidad_" + i).val();

        if (!id || !lote || !cantidad || !unidad) {
            if (callback) callback(false);
            return false;
        }

        $("#enviar").prop("disabled", true);

        $.ajax({
            data: {
                id: id,
                lote: lote,
                cantidad: cantidad,
                unidad: unidad
            },
            url: "VerificarExistencia",
            type: "POST"
        })
        .done(function (response) {
            const paso = String(response).trim();

            if (paso === "0") {
                ew.alert("La cantidad solicitada es mayor a la existencia del lote o a lo pedido en el ítem.");
                $("#cantidad_" + i).val("").focus();
                $("#enviar").prop("disabled", false);
                if (callback) callback(false);
                return;
            }

            $("#enviar").prop("disabled", false);
            if (callback) callback(true);
        })
        .fail(function () {
            ew.alert("Error de comunicación al verificar existencia.");
            $("#enviar").prop("disabled", false);
            if (callback) callback(false);
        });

        return true;
    }

    window.validar_existencia = function (i) {
        return validarExistenciaFila(i);
    };

    $(document).on("change", ".cne-cantidad, .cne-unidad", function () {
        const id = this.id || "";
        const partes = id.split("_");
        const i = partes.length > 1 ? partes[1] : "";
        if (i !== "") {
            validarExistenciaFila(i);
        }
    });

    $("#enviar").on("click", function () {
        const cantidadFilas = <?= intval($cantidad) ?>;

        if (cantidadFilas <= 0) {
            ew.alert("No hay renglones disponibles para crear la Nota de Entrega.");
            return false;
        }

        for (let i = 0; i < cantidadFilas; i++) {
        	if ($("#eliminar_" + i).val() === "S") {
			    continue;
			}
        	
            const lote = $("#lote_" + i).val();
            const cantidad = $("#cantidad_" + i).val();
            const unidad = $("#unidad_" + i).val();

            if (!lote) {
                ew.alert("Debe indicar el lote en la fila " + (i + 1));
                return false;
            }

            if (!cantidad || parseFloat(cantidad) <= 0) {
                ew.alert("Debe indicar una cantidad válida en la fila " + (i + 1));
                $("#cantidad_" + i).focus();
                return false;
            }

            if (!unidad) {
                ew.alert("Debe indicar la unidad de medida en la fila " + (i + 1));
                return false;
            }
        }

        if (confirm("¿Está seguro de crear la nota de entrega?")) {
            $("#enviar").prop("disabled", true);
            $("#frm").submit();
        }

        return false;
    });

	$(document).on("click", ".eliminar-fila", function () {

	    const fila = $(this).data("row");

	    if (!confirm("¿Desea eliminar este renglón de la Nota de Entrega?")) {
	        return;
	    }

	    $("#eliminar_" + fila).val("S");

	    $("#fila_" + fila).fadeOut(200, function () {
	        $(this).addClass("d-none");
	    });
	});

});
</script>

<style>
#tablaNotaEntrega td,
#tablaNotaEntrega th {
    vertical-align: middle;
}

.form-control:focus,
.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 .15rem rgba(13, 110, 253, .20);
}
</style>

<?= GetDebugMessage() ?>
