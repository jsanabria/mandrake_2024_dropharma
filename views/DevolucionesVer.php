<?php

namespace PHPMaker2024\mandrake;

// Page object
$DevolucionesVer = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = isset($_POST["xNota"]) ? $_POST["xNota"] : "";

if ($id == "") {
?>
    <div class="container my-5">
        <div class="alert alert-danger shadow-sm border-start border-4 border-danger d-flex align-items-center" role="alert">
            <span class="fs-4 me-3">⚠️</span>
            <div>
                <strong>¡Atención!</strong> Debe seleccionar una nota de entrega para procesar la devolución.
            </div>
        </div>
        <a href="Devoluciones" class="btn btn-light border px-4">Regresar</a>
    </div>
<?php
} else {
    $sql = "SELECT a.id, 
                b.nombre, a.tipo_documento, a.nro_documento,
                date_format(a.fecha, '%d/%m/%Y') AS fecha,
                a.moneda, a.monto_total, a.email  
            FROM 
                salidas AS a 
                JOIN cliente AS b ON b.id = a.cliente 
            WHERE
                a.id = $id;";
    $row = ExecuteRow($sql);
    $id = $row["id"];
    $tipo_documento = $row["tipo_documento"];
    $cliente = $row["nombre"];
    $nro_documento = $row["nro_documento"];
    $fecha = $row["fecha"];
    $moneda = $row["moneda"];
    $monto_total = number_format($row["monto_total"], 2, ".", ",");
    $devolucion = $row["email"];
?>

    <form id="frm" name="frm" method="post" action="DevolucionesGuardar" onsubmit="return validarFormulario();">
        <div class="container my-4">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <?php if ($devolucion == "DEVOLUCION"): ?>
                <div class="alert alert-warning shadow-sm border-start border-4 border-warning d-flex align-items-center mb-4" role="alert">
                    <span class="fs-4 me-3">⚠️</span>
                    <div>
                        <strong>Alerta del Sistema:</strong> Esta nota de entrega ya posee historial de devoluciones. ¡Verifique los artículos antes de proceder!
                    </div>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0 mb-4 bg-light">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-secondary mb-3 pb-2 border-bottom w-100">
                        Información de la Nota de Entrega
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-4 col-sm-12">
                            <span class="text-muted d-block small text-uppercase fw-semibold">Cliente</span>
                            <span class="text-dark fw-bold fs-6 d-block mt-1"><?php echo $cliente; ?></span>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <span class="text-muted d-block small text-uppercase fw-semibold">Nro. Documento</span>
                            <span class="badge bg-secondary text-white mt-1 fs-6"><?php echo $tipo_documento . " - " . $nro_documento; ?></span>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <span class="text-muted d-block small text-uppercase fw-semibold">Fecha</span>
                            <span class="text-dark d-block mt-1"><?php echo $fecha; ?></span>
                        </div>
                        <div class="col-md-1 col-sm-4">
                            <span class="text-muted d-block small text-uppercase fw-semibold">Moneda</span>
                            <span class="text-dark fw-bold d-block mt-1"><?php echo $moneda; ?></span>
                        </div>
                        <div class="col-md-2 col-sm-8">
                            <span class="text-muted d-block small text-uppercase fw-semibold">Monto Total</span>
                            <span class="text-dark fw-bold fs-5 d-block mt-1"><?php echo $monto_total; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 overflow-hidden mb-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 50px;" class="text-center">#</th>
                                <th>Artículo / Principio Activo</th>
                                <th style="width: 130px;">Cant. a Devolver</th>
                                <th style="width: 140px;">Precio Unidad</th>
                                <th style="width: 140px;">Precio Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT
                                        a.id,
                                        a.articulo, 
                                        b.principio_activo,
                                        ABS(a.cantidad_movimiento) AS cantidad_movimiento,
                                        a.precio_unidad, a.precio   
                                    FROM 
                                        entradas_salidas AS a 
                                        JOIN articulo AS b ON b.id = a.articulo  
                                    WHERE a.tipo_documento = 'TDCNET' AND a.id_documento = $id
                                    ORDER BY a.articulo DESC;";
                            $rows = ExecuteRows($sql);
                            $i = 1;
                            $xCant = 0;
                            foreach ($rows as $key => $value) {
                                $sql = "SELECT 
                                            SUM(IFNULL(a.cantidad_movimiento, 0)) AS cantidad 
                                        FROM 
                                            entradas_salidas AS a 
                                            JOIN entradas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento 
                                        WHERE 
                                            b.id_documento_padre = $id AND b.tipo_documento = 'TDCNRP' AND a.articulo = " . $value["articulo"] . ";";
                                        
                                $xCant = intval($value["cantidad_movimiento"]) - intval(ExecuteScalar($sql));
                                $disabled = ($xCant <= 0) ? 'disabled' : '';
                            ?>
                                <tr class="<?= ($xCant <= 0 ? 'table-light opacity-50' : '') ?>">
                                    <td class="text-center">
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input check-articulo" type="checkbox" name="x<?php echo $i; ?>_Articulo" id="x<?php echo $i; ?>_Articulo" value="<?php echo $value["articulo"]; ?>" <?= $disabled ?>>
                                            <input type="hidden"
                                                name="x<?php echo $i; ?>_IdMovimiento"
                                                id="x<?php echo $i; ?>_IdMovimiento"
                                                value="<?php echo intval($value["id"]); ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-medium text-dark"><?php echo $value["principio_activo"]; ?></span>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm text-center" name="x<?php echo $i; ?>_Cantidad" id="x<?php echo $i; ?>_Cantidad" value="<?php echo $xCant; ?>" onkeyup="ValCant(this.value, <?= $xCant ?>, <?= $i ?>)" <?= $disabled ?>>
                                    </td>
                                    <td>
                                        <input type="text" readonly class="form-control form-control-sm bg-light text-end border-0" name="x<?php echo $i; ?>_Costo" id="x<?php echo $i; ?>_Costo" value="<?php echo number_format($value["precio_unidad"], 2, ".", ","); ?>">
                                    </td>
                                    <td class="text-end fw-semibold text-secondary px-3">
                                        <?php echo number_format($value["precio"], 2, ".", ","); ?>
                                    </td>
                                </tr>
                            <?php
                                $i++;
                            }
                            $i = count($rows);
                            ?>

                            <tr class="table-light">
                                <td colspan="2" class="align-top py-3 text-end">
                                    <div class="pt-2">
                                        <span class="text-danger fw-bold">*</span> <strong class="text-dark">Motivo de Devolución:</strong>
                                    </div>
                                    <div id="charNum" class="small mt-2">
                                        <span class="badge bg-danger">0 caracteres</span>
                                    </div>
                                </td>
                                <td colspan="3" class="py-3">
                                    <textarea class="form-control" rows="3" id="txtNota" name="txtNota" placeholder="Indique con detalle por qué está realizando esta devolución. Mínimo 20 caracteres..." onkeyup="countChars(this);" required style="border-left: 4px solid #dc3545;"></textarea>                  
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <input type="hidden" name="cantidad" value="<?php echo $i; ?>">
                <a href="Devoluciones" class="btn btn-light border px-4">Regresar</a>
                <button type="submit" class="btn btn-danger px-4 fw-bold" id="procesar">
                    Procesar Devolución
                </button>
            </div>
        </div>
    </form>
<?php
}
?>

<script>
function countChars(obj) {
    const charNumElement = document.getElementById("charNum");
    const len = obj.value.length;
    if (len < 20) {
        charNumElement.innerHTML = '<span class="badge bg-danger">' + len + ' / 20 caracteres</span>';
    } else {
        charNumElement.innerHTML = '<span class="badge bg-success">' + len + ' caracteres guardados</span>';
    }
}

function ValCant(valorActual, valorMaximo, index) {
    const inputCantidad = document.getElementById("x" + index + "_Cantidad");
    if (parseInt(valorActual) > parseInt(valorMaximo)) {
        ew.alert("¡Error! La cantidad ingresada (" + valorActual + ") es mayor a los artículos originalmente entregados (" + valorMaximo + ").");
        inputCantidad.value = valorMaximo;
    }
}

// NUEVA FUNCIÓN: Valida que al menos un checkbox esté seleccionado antes de procesar
function validarFormulario() {
    const checkboxes = document.querySelectorAll('.check-articulo');
    let algunoSeleccionado = false;

    checkboxes.forEach(function(checkbox) {
        if (checkbox.checked) {
            algunoSeleccionado = true;
        }
    });

    if (!algunoSeleccionado) {
        ew.alert("¡Error! Debe seleccionar al menos un artículo mediante la casilla de verificación (#) para poder procesar la devolución.");
        return false; // Detiene el envío del formulario
    }

    return true; // Permite el envío del formulario
}
</script>
<?= GetDebugMessage() ?>
