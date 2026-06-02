<?php

namespace PHPMaker2024\mandrake;

// Page object
$NotaDeEntregaParcial = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = intval($_REQUEST["id"]);

// Obtener datos de la cabecera
$sql = "SELECT 
			a.id, c.descripcion AS documento, date_format(a.fecha, '%d/%m/%Y') AS fecha, 
			b.nombre AS cliente, a.nro_documento, a.nota, a.tipo_documento
		FROM 
			salidas AS a 
			JOIN cliente AS b ON b.id = a.cliente 
			JOIN tipo_documento AS c ON c.codigo = a.tipo_documento 
		WHERE a.id = $id;";
$row = ExecuteRow($sql);

if (!$row) {
    echo "<div class='alert alert-danger'>Nota de Entrega no encontrada.</div>";
    return;
}

$documento = $row["documento"];
$fecha = $row["fecha"]; 
$cliente = $row["cliente"];
$nro_documento = $row["nro_documento"];
$nota = $row["nota"];
$tipo_documento = $row["tipo_documento"];
?>

<div class="card card-default">
    <div class="card-header bg-light">
        <h3 class="card-title text-primary"><i class="fa-solid fa-boxes-packing"></i> Control y Checklist de Entrega Física</h3>
    </div>
    <div class="card-body">
        <form id="frmEntrega" name="frmEntrega" method="post" action="NotaDeEntregaParcialProcesar">
            <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
            
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Documento Origen</label>
                    <input type="text" readonly class="form-control bg-white" value="<?php echo $documento . ' #' . $nro_documento; ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Fecha Emisión</label>
                    <input type="text" readonly class="form-control bg-white" value="<?php echo $fecha; ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Cliente</label>
                    <input type="text" readonly class="form-control bg-white" value="<?php echo $cliente; ?>">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <label class="form-label fw-bold">Observaciones / Nota</label>
                    <textarea readonly class="form-control bg-white" rows="2"><?php echo $nota; ?></textarea>
                </div>
            </div>

            <div class="table-responsive">
                <h4 class="text-secondary border-bottom pb-2">Checklist de Artículos por Despachar</h4>
                <table class="table table-striped table-bordered table-hover align-middle">
                    <thead class="table-light text-center small fw-bold">
                        <tr>
                            <th width="4%"><i class="fa-solid fa-square-check"></i></th>
                            <th width="5%">#</th>
                            <th width="12%">CÓDIGO</th>
                            <th>ARTÍCULO / LABORATORIO</th>
                            <th width="10%">CANT. PEDIDA</th>
                            <!--
                            <th width="12%">ENTREGADO PREVIO</th>
                            <th width="10%">SALDO PENDIENTE</th>
                            -->
                            <th width="12%">CANT. A ENTREGAR FÍSICO</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $sql = "SELECT a.id, 
                                b.codigo, 
                                IFNULL(c.nombre, 'SIN LABORATORIO') AS laboratorio, 
                                CONCAT(IFNULL(b.nombre_comercial, ''), IF(IFNULL(b.nombre_comercial, '')='', '', ' - '), IFNULL(b.principio_activo, ''), ' ', IFNULL(b.presentacion, ''), ' ') AS articulo, 
                                a.cantidad_articulo AS cantidad_original, 
                                IFNULL(a.cantidad_entregada, 0) AS cantidad_entregada_previal
                            FROM 
                                entradas_salidas AS a 
                                LEFT OUTER JOIN articulo AS b ON b.id = a.articulo 
                                LEFT OUTER JOIN fabricante AS c ON c.Id = a.fabricante 
                            WHERE 
                                a.id_documento = $id AND a.tipo_documento = '$tipo_documento' 
                            ORDER BY c.nombre, b.principio_activo, b.presentacion;"; 
                    
                    $rows = ExecuteRows($sql);
                    $i = 1;
                    
                    foreach ($rows as $value) { 
                        $cant_original = intval($value["cantidad_original"]);
                        $entregado_previo = intval($value["cantidad_entregada_previal"]);
                        $pendiente = $cant_original - $entregado_previo;
                        
                        if ($pendiente <= 0) continue;
                        
                        echo '<tr id="fila_' . $value["id"] . '">';
                            // Checkbox Checklist (Sin onchange inline)
                            echo '<td class="text-center">
                                    <input type="checkbox" class="form-check-input chk-item" name="item_seleccionado[' . $value["id"] . ']" value="' . $value["id"] . '">
                                </td>';
                            echo '<td class="text-center small">' . $i++ . '</td>';
                            echo '<td>' . $value["codigo"] . '</td>';
                            echo '<td><span class="badge bg-secondary small">' . $value["laboratorio"] . '</span><br>' . $value["articulo"] . '</td>';
                            echo '<td class="text-center fw-bold text-secondary">' . $cant_original . '</td>';
                            // echo '<td class="text-center text-success">' . $entregado_previo . '</td>';
                            // echo '<td class="text-center fw-bold text-danger" id="saldo_' . $value["id"] . '">' . $pendiente . '</td>';
                            
                            // Input numérico (Sin onchange inline, agregamos data-max para validación rápida)
                            echo '<td>
                                    <input type="number" class="form-control form-control-sm text-center cant-despacho" 
                                        id="cantidad_' . $value["id"] . '" 
                                        name="cantidad_despacho[' . $value["id"] . ']" 
                                        value="' . $pendiente . '" min="0" max="' . $pendiente . '" 
                                        data-id="' . $value["id"] . '"
                                        data-max="' . $pendiente . '"
                                        disabled>
                                </td>';
                        echo '</tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 border-top pt-3 d-flex justify-content-end">
                <a href="SalidasList?tipo=<?php echo $tipo_documento; ?>" class="btn btn-outline-secondary me-2">
                    <i class="fa-solid fa-arrow-left"></i> Regresar
                </a>
                <button type="button" class="btn btn-primary" id="btnProcesarEntrega">
                    <i class="fa-solid fa-circle-check"></i> Confirmar Despacho Físico
                </button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalConfirmarDespacho" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalConfirmarDespachoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-primary fw-bold" id="modalConfirmarDespachoLabel">
                    <i class="fa-solid fa-triangle-exclamation text-warning me-2"></i> Confirmación de Despacho
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="fs-6 text-dark">¿Está seguro de confirmar este despacho físico?</p>
                <p class="small text-muted mb-0">Este proceso asentará los estados de las Notas de Entrega, generará los remanentes correspondientes y actualizará el inventario físico.</p>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="btnConfirmarSubmitModal">
                    <i class="fa-solid fa-circle-check"></i> Sí, Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
// PHPMaker 2024 garantiza la carga segura mediante loadjs.ready
loadjs.ready("makerjs", function() {
    
    // Event Listener para controlar el encendido/apagado del Checklist (Reemplaza a toggleLinea)
    jQuery(document).on("change", ".chk-item", function() {
        var id = jQuery(this).val();
        var $inputCant = jQuery("#cantidad_" + id);
        var saldoPendiente = Number(jQuery("#saldo_" + id).text());

        if (jQuery(this).is(':checked')) {
            $inputCant.prop('disabled', false);
            // $inputCant.val(saldoPendiente);
            jQuery("#fila_" + id).addClass("table-info");
        } else {
            $inputCant.prop('disabled', true);
            // $inputCant.val(0);
            jQuery("#fila_" + id).removeClass("table-info");
        }
    });

    // Event Listener para la validación reactiva del input numérico (Reemplaza a validarCantidadInput)
    jQuery(document).on("change textup keyup", ".cant-despacho", function() {
        var valor = Number(jQuery(this).val());
        var maximo = Number(jQuery(this).data("max"));
        
        if (valor > maximo) {
            ew.alert("⚠️ La cantidad ingresada (" + valor + ") excede el saldo físico pendiente (" + maximo + ").");
            jQuery(this).val(maximo);
            jQuery(this).focus();
        } else if (valor < 0) {
            ew.alert("⚠️ La cantidad no puede ser menor a cero.");
            jQuery(this).val(0);
        }
    });

    // Manejador del botón Confirmar Despacho Físico
    jQuery("#btnProcesarEntrega").on("click", function(e) {
        e.preventDefault(); // Detiene cualquier propagación indeseada
        
        var temporalSeleccionado = false;
        var cantidadIncoherente = false;

        jQuery(".chk-item").each(function() {
            if (jQuery(this).is(':checked')) {
                temporalSeleccionado = true;
                var id = jQuery(this).val();
                var cantCargada = Number(jQuery("#cantidad_" + id).val());
                if (cantCargada <= 0) {
                    cantidadIncoherente = true;
                }
            }
        });

        if (!temporalSeleccionado) {
            ew.alert("❌ Error: Debe marcar al menos un artículo del checklist para procesar la entrega.");
            return false;
        }

        if (cantidadIncoherente) {
            ew.alert("❌ Error: Los ítems seleccionados en el checklist deben tener una cantidad a entregar mayor a 0.");
            return false;
        }

        // NUEVO: En vez de confirm(), levantamos el modal de Bootstrap 5 de forma limpia
        var miModal = new bootstrap.Modal(document.getElementById('modalConfirmarDespacho'));
        miModal.show();
    });

    // NUEVO: Manejador del botón "Sí, Confirmar" DENTRO del Modal
    jQuery("#btnConfirmarSubmitModal").on("click", function() {
        // Deshabilitamos el botón para evitar doble envío (double-submit click) si el servidor tarda
        jQuery(this).prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Procesando...');
        
        // Enviamos el formulario formalmente
        jQuery("#frmEntrega").submit();
    });
});
</script>
<?= GetDebugMessage() ?>
