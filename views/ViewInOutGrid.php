<?php

namespace PHPMaker2024\mandrake;

// Set up and run Grid object
$Grid = Container("ViewInOutGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fview_in_outgrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { view_in_out: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fview_in_outgrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null], fields.id.isInvalid],
            ["tipo_documento", [fields.tipo_documento.visible && fields.tipo_documento.required ? ew.Validators.required(fields.tipo_documento.caption) : null], fields.tipo_documento.isInvalid],
            ["id_documento", [fields.id_documento.visible && fields.id_documento.required ? ew.Validators.required(fields.id_documento.caption) : null, ew.Validators.integer], fields.id_documento.isInvalid],
            ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null, ew.Validators.integer], fields.fabricante.isInvalid],
            ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null, ew.Validators.integer], fields.articulo.isInvalid],
            ["lote", [fields.lote.visible && fields.lote.required ? ew.Validators.required(fields.lote.caption) : null], fields.lote.isInvalid],
            ["fecha_vencimiento", [fields.fecha_vencimiento.visible && fields.fecha_vencimiento.required ? ew.Validators.required(fields.fecha_vencimiento.caption) : null, ew.Validators.datetime(fields.fecha_vencimiento.clientFormatPattern)], fields.fecha_vencimiento.isInvalid],
            ["almacen", [fields.almacen.visible && fields.almacen.required ? ew.Validators.required(fields.almacen.caption) : null], fields.almacen.isInvalid],
            ["cantidad_articulo", [fields.cantidad_articulo.visible && fields.cantidad_articulo.required ? ew.Validators.required(fields.cantidad_articulo.caption) : null, ew.Validators.float], fields.cantidad_articulo.isInvalid],
            ["articulo_unidad_medida", [fields.articulo_unidad_medida.visible && fields.articulo_unidad_medida.required ? ew.Validators.required(fields.articulo_unidad_medida.caption) : null], fields.articulo_unidad_medida.isInvalid],
            ["cantidad_unidad_medida", [fields.cantidad_unidad_medida.visible && fields.cantidad_unidad_medida.required ? ew.Validators.required(fields.cantidad_unidad_medida.caption) : null, ew.Validators.float], fields.cantidad_unidad_medida.isInvalid],
            ["cantidad_movimiento", [fields.cantidad_movimiento.visible && fields.cantidad_movimiento.required ? ew.Validators.required(fields.cantidad_movimiento.caption) : null, ew.Validators.float], fields.cantidad_movimiento.isInvalid],
            ["costo_unidad", [fields.costo_unidad.visible && fields.costo_unidad.required ? ew.Validators.required(fields.costo_unidad.caption) : null, ew.Validators.float], fields.costo_unidad.isInvalid],
            ["costo", [fields.costo.visible && fields.costo.required ? ew.Validators.required(fields.costo.caption) : null, ew.Validators.float], fields.costo.isInvalid],
            ["precio_unidad", [fields.precio_unidad.visible && fields.precio_unidad.required ? ew.Validators.required(fields.precio_unidad.caption) : null, ew.Validators.float], fields.precio_unidad.isInvalid],
            ["precio", [fields.precio.visible && fields.precio.required ? ew.Validators.required(fields.precio.caption) : null, ew.Validators.float], fields.precio.isInvalid],
            ["id_compra", [fields.id_compra.visible && fields.id_compra.required ? ew.Validators.required(fields.id_compra.caption) : null, ew.Validators.integer], fields.id_compra.isInvalid],
            ["alicuota", [fields.alicuota.visible && fields.alicuota.required ? ew.Validators.required(fields.alicuota.caption) : null, ew.Validators.float], fields.alicuota.isInvalid],
            ["cantidad_movimiento_consignacion", [fields.cantidad_movimiento_consignacion.visible && fields.cantidad_movimiento_consignacion.required ? ew.Validators.required(fields.cantidad_movimiento_consignacion.caption) : null, ew.Validators.float], fields.cantidad_movimiento_consignacion.isInvalid],
            ["id_consignacion", [fields.id_consignacion.visible && fields.id_consignacion.required ? ew.Validators.required(fields.id_consignacion.caption) : null, ew.Validators.integer], fields.id_consignacion.isInvalid],
            ["descuento", [fields.descuento.visible && fields.descuento.required ? ew.Validators.required(fields.descuento.caption) : null, ew.Validators.float], fields.descuento.isInvalid],
            ["precio_unidad_sin_desc", [fields.precio_unidad_sin_desc.visible && fields.precio_unidad_sin_desc.required ? ew.Validators.required(fields.precio_unidad_sin_desc.caption) : null, ew.Validators.float], fields.precio_unidad_sin_desc.isInvalid],
            ["check_ne", [fields.check_ne.visible && fields.check_ne.required ? ew.Validators.required(fields.check_ne.caption) : null], fields.check_ne.isInvalid],
            ["packer_cantidad", [fields.packer_cantidad.visible && fields.packer_cantidad.required ? ew.Validators.required(fields.packer_cantidad.caption) : null, ew.Validators.float], fields.packer_cantidad.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["tipo_documento",false],["id_documento",false],["fabricante",false],["articulo",false],["lote",false],["fecha_vencimiento",false],["almacen",false],["cantidad_articulo",false],["articulo_unidad_medida",false],["cantidad_unidad_medida",false],["cantidad_movimiento",false],["costo_unidad",false],["costo",false],["precio_unidad",false],["precio",false],["id_compra",false],["alicuota",false],["cantidad_movimiento_consignacion",false],["id_consignacion",false],["descuento",false],["precio_unidad_sin_desc",false],["check_ne",false],["packer_cantidad",false]];
                if (fields.some(field => ew.valueChanged(fobj, rowIndex, ...field)))
                    return false;
                return true;
            }
        )

        // Form_CustomValidate
        .setCustomValidate(
            function (fobj) { // DO NOT CHANGE THIS LINE! (except for adding "async" keyword)!
                    // Your custom validation code in JAVASCRIPT here, return false if invalid.
                    return true;
                }
        )

        // Use JavaScript validation or not
        .setValidateRequired(ew.CLIENT_VALIDATE)

        // Dynamic selection lists
        .setLists({
            "check_ne": <?= $Grid->check_ne->toClientList($Grid) ?>,
        })
        .build();
    window[form.id] = form;
    loadjs.done(form.id);
});
</script>
<?php } ?>
<main class="list">
<div id="ew-header-options">
<?php $Grid->HeaderOptions?->render("body") ?>
</div>
<div id="ew-list">
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?= $Grid->isAddOrEdit() ? " ew-grid-add-edit" : "" ?> <?= $Grid->TableGridClass ?>">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<?php } ?>
<div id="fview_in_outgrid" class="ew-form ew-list-form">
<div id="gmp_view_in_out" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_view_in_outgrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Grid->RowType = RowType::HEADER;

// Render list options
$Grid->renderListOptions();

// Render list options (header, left)
$Grid->ListOptions->render("header", "left");
?>
<?php if ($Grid->id->Visible) { // id ?>
        <th data-name="id" class="<?= $Grid->id->headerCellClass() ?>"><div id="elh_view_in_out_id" class="view_in_out_id"><?= $Grid->renderFieldHeader($Grid->id) ?></div></th>
<?php } ?>
<?php if ($Grid->tipo_documento->Visible) { // tipo_documento ?>
        <th data-name="tipo_documento" class="<?= $Grid->tipo_documento->headerCellClass() ?>"><div id="elh_view_in_out_tipo_documento" class="view_in_out_tipo_documento"><?= $Grid->renderFieldHeader($Grid->tipo_documento) ?></div></th>
<?php } ?>
<?php if ($Grid->id_documento->Visible) { // id_documento ?>
        <th data-name="id_documento" class="<?= $Grid->id_documento->headerCellClass() ?>"><div id="elh_view_in_out_id_documento" class="view_in_out_id_documento"><?= $Grid->renderFieldHeader($Grid->id_documento) ?></div></th>
<?php } ?>
<?php if ($Grid->fabricante->Visible) { // fabricante ?>
        <th data-name="fabricante" class="<?= $Grid->fabricante->headerCellClass() ?>"><div id="elh_view_in_out_fabricante" class="view_in_out_fabricante"><?= $Grid->renderFieldHeader($Grid->fabricante) ?></div></th>
<?php } ?>
<?php if ($Grid->articulo->Visible) { // articulo ?>
        <th data-name="articulo" class="<?= $Grid->articulo->headerCellClass() ?>"><div id="elh_view_in_out_articulo" class="view_in_out_articulo"><?= $Grid->renderFieldHeader($Grid->articulo) ?></div></th>
<?php } ?>
<?php if ($Grid->lote->Visible) { // lote ?>
        <th data-name="lote" class="<?= $Grid->lote->headerCellClass() ?>"><div id="elh_view_in_out_lote" class="view_in_out_lote"><?= $Grid->renderFieldHeader($Grid->lote) ?></div></th>
<?php } ?>
<?php if ($Grid->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
        <th data-name="fecha_vencimiento" class="<?= $Grid->fecha_vencimiento->headerCellClass() ?>"><div id="elh_view_in_out_fecha_vencimiento" class="view_in_out_fecha_vencimiento"><?= $Grid->renderFieldHeader($Grid->fecha_vencimiento) ?></div></th>
<?php } ?>
<?php if ($Grid->almacen->Visible) { // almacen ?>
        <th data-name="almacen" class="<?= $Grid->almacen->headerCellClass() ?>"><div id="elh_view_in_out_almacen" class="view_in_out_almacen"><?= $Grid->renderFieldHeader($Grid->almacen) ?></div></th>
<?php } ?>
<?php if ($Grid->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <th data-name="cantidad_articulo" class="<?= $Grid->cantidad_articulo->headerCellClass() ?>"><div id="elh_view_in_out_cantidad_articulo" class="view_in_out_cantidad_articulo"><?= $Grid->renderFieldHeader($Grid->cantidad_articulo) ?></div></th>
<?php } ?>
<?php if ($Grid->articulo_unidad_medida->Visible) { // articulo_unidad_medida ?>
        <th data-name="articulo_unidad_medida" class="<?= $Grid->articulo_unidad_medida->headerCellClass() ?>"><div id="elh_view_in_out_articulo_unidad_medida" class="view_in_out_articulo_unidad_medida"><?= $Grid->renderFieldHeader($Grid->articulo_unidad_medida) ?></div></th>
<?php } ?>
<?php if ($Grid->cantidad_unidad_medida->Visible) { // cantidad_unidad_medida ?>
        <th data-name="cantidad_unidad_medida" class="<?= $Grid->cantidad_unidad_medida->headerCellClass() ?>"><div id="elh_view_in_out_cantidad_unidad_medida" class="view_in_out_cantidad_unidad_medida"><?= $Grid->renderFieldHeader($Grid->cantidad_unidad_medida) ?></div></th>
<?php } ?>
<?php if ($Grid->cantidad_movimiento->Visible) { // cantidad_movimiento ?>
        <th data-name="cantidad_movimiento" class="<?= $Grid->cantidad_movimiento->headerCellClass() ?>"><div id="elh_view_in_out_cantidad_movimiento" class="view_in_out_cantidad_movimiento"><?= $Grid->renderFieldHeader($Grid->cantidad_movimiento) ?></div></th>
<?php } ?>
<?php if ($Grid->costo_unidad->Visible) { // costo_unidad ?>
        <th data-name="costo_unidad" class="<?= $Grid->costo_unidad->headerCellClass() ?>"><div id="elh_view_in_out_costo_unidad" class="view_in_out_costo_unidad"><?= $Grid->renderFieldHeader($Grid->costo_unidad) ?></div></th>
<?php } ?>
<?php if ($Grid->costo->Visible) { // costo ?>
        <th data-name="costo" class="<?= $Grid->costo->headerCellClass() ?>"><div id="elh_view_in_out_costo" class="view_in_out_costo"><?= $Grid->renderFieldHeader($Grid->costo) ?></div></th>
<?php } ?>
<?php if ($Grid->precio_unidad->Visible) { // precio_unidad ?>
        <th data-name="precio_unidad" class="<?= $Grid->precio_unidad->headerCellClass() ?>"><div id="elh_view_in_out_precio_unidad" class="view_in_out_precio_unidad"><?= $Grid->renderFieldHeader($Grid->precio_unidad) ?></div></th>
<?php } ?>
<?php if ($Grid->precio->Visible) { // precio ?>
        <th data-name="precio" class="<?= $Grid->precio->headerCellClass() ?>"><div id="elh_view_in_out_precio" class="view_in_out_precio"><?= $Grid->renderFieldHeader($Grid->precio) ?></div></th>
<?php } ?>
<?php if ($Grid->id_compra->Visible) { // id_compra ?>
        <th data-name="id_compra" class="<?= $Grid->id_compra->headerCellClass() ?>"><div id="elh_view_in_out_id_compra" class="view_in_out_id_compra"><?= $Grid->renderFieldHeader($Grid->id_compra) ?></div></th>
<?php } ?>
<?php if ($Grid->alicuota->Visible) { // alicuota ?>
        <th data-name="alicuota" class="<?= $Grid->alicuota->headerCellClass() ?>"><div id="elh_view_in_out_alicuota" class="view_in_out_alicuota"><?= $Grid->renderFieldHeader($Grid->alicuota) ?></div></th>
<?php } ?>
<?php if ($Grid->cantidad_movimiento_consignacion->Visible) { // cantidad_movimiento_consignacion ?>
        <th data-name="cantidad_movimiento_consignacion" class="<?= $Grid->cantidad_movimiento_consignacion->headerCellClass() ?>"><div id="elh_view_in_out_cantidad_movimiento_consignacion" class="view_in_out_cantidad_movimiento_consignacion"><?= $Grid->renderFieldHeader($Grid->cantidad_movimiento_consignacion) ?></div></th>
<?php } ?>
<?php if ($Grid->id_consignacion->Visible) { // id_consignacion ?>
        <th data-name="id_consignacion" class="<?= $Grid->id_consignacion->headerCellClass() ?>"><div id="elh_view_in_out_id_consignacion" class="view_in_out_id_consignacion"><?= $Grid->renderFieldHeader($Grid->id_consignacion) ?></div></th>
<?php } ?>
<?php if ($Grid->descuento->Visible) { // descuento ?>
        <th data-name="descuento" class="<?= $Grid->descuento->headerCellClass() ?>"><div id="elh_view_in_out_descuento" class="view_in_out_descuento"><?= $Grid->renderFieldHeader($Grid->descuento) ?></div></th>
<?php } ?>
<?php if ($Grid->precio_unidad_sin_desc->Visible) { // precio_unidad_sin_desc ?>
        <th data-name="precio_unidad_sin_desc" class="<?= $Grid->precio_unidad_sin_desc->headerCellClass() ?>"><div id="elh_view_in_out_precio_unidad_sin_desc" class="view_in_out_precio_unidad_sin_desc"><?= $Grid->renderFieldHeader($Grid->precio_unidad_sin_desc) ?></div></th>
<?php } ?>
<?php if ($Grid->check_ne->Visible) { // check_ne ?>
        <th data-name="check_ne" class="<?= $Grid->check_ne->headerCellClass() ?>"><div id="elh_view_in_out_check_ne" class="view_in_out_check_ne"><?= $Grid->renderFieldHeader($Grid->check_ne) ?></div></th>
<?php } ?>
<?php if ($Grid->packer_cantidad->Visible) { // packer_cantidad ?>
        <th data-name="packer_cantidad" class="<?= $Grid->packer_cantidad->headerCellClass() ?>"><div id="elh_view_in_out_packer_cantidad" class="view_in_out_packer_cantidad"><?= $Grid->renderFieldHeader($Grid->packer_cantidad) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Grid->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody data-page="<?= $Grid->getPageNumber() ?>">
<?php
$Grid->setupGrid();
$isInlineAddOrCopy = ($Grid->isCopy() || $Grid->isAdd());
while ($Grid->RecordCount < $Grid->StopRecord || $Grid->RowIndex === '$rowindex$' || $isInlineAddOrCopy && $Grid->RowIndex == 0) {
    if (
        $Grid->CurrentRow !== false &&
        $Grid->RowIndex !== '$rowindex$' &&
        (!$Grid->isGridAdd() || $Grid->CurrentMode == "copy") &&
        !($isInlineAddOrCopy && $Grid->RowIndex == 0)
    ) {
        $Grid->fetch();
    }
    $Grid->RecordCount++;
    if ($Grid->RecordCount >= $Grid->StartRecord) {
        $Grid->setupRow();

        // Skip 1) delete row / empty row for confirm page, 2) hidden row
        if (
            $Grid->RowAction != "delete" &&
            $Grid->RowAction != "insertdelete" &&
            !($Grid->RowAction == "insert" && $Grid->isConfirm() && $Grid->emptyRow()) &&
            $Grid->RowAction != "hide"
        ) {
?>
    <tr <?= $Grid->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Grid->ListOptions->render("body", "left", $Grid->RowCount);
?>
    <?php if ($Grid->id->Visible) { // id ?>
        <td data-name="id"<?= $Grid->id->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_id" class="el_view_in_out_id"></span>
<input type="hidden" data-table="view_in_out" data-field="x_id" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_id" id="o<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_id" class="el_view_in_out_id">
<span<?= $Grid->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id->getDisplayValue($Grid->id->EditValue))) ?>"></span>
<input type="hidden" data-table="view_in_out" data-field="x_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_id" id="x<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_id" class="el_view_in_out_id">
<span<?= $Grid->id->viewAttributes() ?>>
<?= $Grid->id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_in_out" data-field="x_id" data-hidden="1" name="fview_in_outgrid$x<?= $Grid->RowIndex ?>_id" id="fview_in_outgrid$x<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->FormValue) ?>">
<input type="hidden" data-table="view_in_out" data-field="x_id" data-hidden="1" data-old name="fview_in_outgrid$o<?= $Grid->RowIndex ?>_id" id="fview_in_outgrid$o<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="view_in_out" data-field="x_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_id" id="x<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Grid->tipo_documento->Visible) { // tipo_documento ?>
        <td data-name="tipo_documento"<?= $Grid->tipo_documento->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<?php if ($Grid->tipo_documento->getSessionValue() != "") { ?>
<span<?= $Grid->tipo_documento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->tipo_documento->getDisplayValue($Grid->tipo_documento->ViewValue))) ?>"></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_tipo_documento" name="x<?= $Grid->RowIndex ?>_tipo_documento" value="<?= HtmlEncode($Grid->tipo_documento->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_tipo_documento" class="el_view_in_out_tipo_documento">
<input type="<?= $Grid->tipo_documento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_tipo_documento" id="x<?= $Grid->RowIndex ?>_tipo_documento" data-table="view_in_out" data-field="x_tipo_documento" value="<?= $Grid->tipo_documento->EditValue ?>" size="30" maxlength="6" placeholder="<?= HtmlEncode($Grid->tipo_documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->tipo_documento->formatPattern()) ?>"<?= $Grid->tipo_documento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->tipo_documento->getErrorMessage() ?></div>
</span>
<?php } ?>
<input type="hidden" data-table="view_in_out" data-field="x_tipo_documento" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_tipo_documento" id="o<?= $Grid->RowIndex ?>_tipo_documento" value="<?= HtmlEncode($Grid->tipo_documento->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<?php if ($Grid->tipo_documento->getSessionValue() != "") { ?>
<span<?= $Grid->tipo_documento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->tipo_documento->getDisplayValue($Grid->tipo_documento->ViewValue))) ?>"></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_tipo_documento" name="x<?= $Grid->RowIndex ?>_tipo_documento" value="<?= HtmlEncode($Grid->tipo_documento->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_tipo_documento" class="el_view_in_out_tipo_documento">
<input type="<?= $Grid->tipo_documento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_tipo_documento" id="x<?= $Grid->RowIndex ?>_tipo_documento" data-table="view_in_out" data-field="x_tipo_documento" value="<?= $Grid->tipo_documento->EditValue ?>" size="30" maxlength="6" placeholder="<?= HtmlEncode($Grid->tipo_documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->tipo_documento->formatPattern()) ?>"<?= $Grid->tipo_documento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->tipo_documento->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_tipo_documento" class="el_view_in_out_tipo_documento">
<span<?= $Grid->tipo_documento->viewAttributes() ?>>
<?= $Grid->tipo_documento->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_in_out" data-field="x_tipo_documento" data-hidden="1" name="fview_in_outgrid$x<?= $Grid->RowIndex ?>_tipo_documento" id="fview_in_outgrid$x<?= $Grid->RowIndex ?>_tipo_documento" value="<?= HtmlEncode($Grid->tipo_documento->FormValue) ?>">
<input type="hidden" data-table="view_in_out" data-field="x_tipo_documento" data-hidden="1" data-old name="fview_in_outgrid$o<?= $Grid->RowIndex ?>_tipo_documento" id="fview_in_outgrid$o<?= $Grid->RowIndex ?>_tipo_documento" value="<?= HtmlEncode($Grid->tipo_documento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->id_documento->Visible) { // id_documento ?>
        <td data-name="id_documento"<?= $Grid->id_documento->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<?php if ($Grid->id_documento->getSessionValue() != "") { ?>
<span<?= $Grid->id_documento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id_documento->getDisplayValue($Grid->id_documento->ViewValue))) ?>"></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_id_documento" name="x<?= $Grid->RowIndex ?>_id_documento" value="<?= HtmlEncode($Grid->id_documento->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_id_documento" class="el_view_in_out_id_documento">
<input type="<?= $Grid->id_documento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_id_documento" id="x<?= $Grid->RowIndex ?>_id_documento" data-table="view_in_out" data-field="x_id_documento" value="<?= $Grid->id_documento->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->id_documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->id_documento->formatPattern()) ?>"<?= $Grid->id_documento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->id_documento->getErrorMessage() ?></div>
</span>
<?php } ?>
<input type="hidden" data-table="view_in_out" data-field="x_id_documento" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_id_documento" id="o<?= $Grid->RowIndex ?>_id_documento" value="<?= HtmlEncode($Grid->id_documento->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<?php if ($Grid->id_documento->getSessionValue() != "") { ?>
<span<?= $Grid->id_documento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id_documento->getDisplayValue($Grid->id_documento->ViewValue))) ?>"></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_id_documento" name="x<?= $Grid->RowIndex ?>_id_documento" value="<?= HtmlEncode($Grid->id_documento->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_id_documento" class="el_view_in_out_id_documento">
<input type="<?= $Grid->id_documento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_id_documento" id="x<?= $Grid->RowIndex ?>_id_documento" data-table="view_in_out" data-field="x_id_documento" value="<?= $Grid->id_documento->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->id_documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->id_documento->formatPattern()) ?>"<?= $Grid->id_documento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->id_documento->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_id_documento" class="el_view_in_out_id_documento">
<span<?= $Grid->id_documento->viewAttributes() ?>>
<?= $Grid->id_documento->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_in_out" data-field="x_id_documento" data-hidden="1" name="fview_in_outgrid$x<?= $Grid->RowIndex ?>_id_documento" id="fview_in_outgrid$x<?= $Grid->RowIndex ?>_id_documento" value="<?= HtmlEncode($Grid->id_documento->FormValue) ?>">
<input type="hidden" data-table="view_in_out" data-field="x_id_documento" data-hidden="1" data-old name="fview_in_outgrid$o<?= $Grid->RowIndex ?>_id_documento" id="fview_in_outgrid$o<?= $Grid->RowIndex ?>_id_documento" value="<?= HtmlEncode($Grid->id_documento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->fabricante->Visible) { // fabricante ?>
        <td data-name="fabricante"<?= $Grid->fabricante->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_fabricante" class="el_view_in_out_fabricante">
<input type="<?= $Grid->fabricante->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" data-table="view_in_out" data-field="x_fabricante" value="<?= $Grid->fabricante->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fabricante->formatPattern()) ?>"<?= $Grid->fabricante->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fabricante->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_in_out" data-field="x_fabricante" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_fabricante" id="o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_fabricante" class="el_view_in_out_fabricante">
<input type="<?= $Grid->fabricante->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" data-table="view_in_out" data-field="x_fabricante" value="<?= $Grid->fabricante->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fabricante->formatPattern()) ?>"<?= $Grid->fabricante->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fabricante->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_fabricante" class="el_view_in_out_fabricante">
<span<?= $Grid->fabricante->viewAttributes() ?>>
<?= $Grid->fabricante->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_in_out" data-field="x_fabricante" data-hidden="1" name="fview_in_outgrid$x<?= $Grid->RowIndex ?>_fabricante" id="fview_in_outgrid$x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->FormValue) ?>">
<input type="hidden" data-table="view_in_out" data-field="x_fabricante" data-hidden="1" data-old name="fview_in_outgrid$o<?= $Grid->RowIndex ?>_fabricante" id="fview_in_outgrid$o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->articulo->Visible) { // articulo ?>
        <td data-name="articulo"<?= $Grid->articulo->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_articulo" class="el_view_in_out_articulo">
<input type="<?= $Grid->articulo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_articulo" id="x<?= $Grid->RowIndex ?>_articulo" data-table="view_in_out" data-field="x_articulo" value="<?= $Grid->articulo->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->articulo->formatPattern()) ?>"<?= $Grid->articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->articulo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_in_out" data-field="x_articulo" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_articulo" id="o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_articulo" class="el_view_in_out_articulo">
<input type="<?= $Grid->articulo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_articulo" id="x<?= $Grid->RowIndex ?>_articulo" data-table="view_in_out" data-field="x_articulo" value="<?= $Grid->articulo->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->articulo->formatPattern()) ?>"<?= $Grid->articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->articulo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_articulo" class="el_view_in_out_articulo">
<span<?= $Grid->articulo->viewAttributes() ?>>
<?= $Grid->articulo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_in_out" data-field="x_articulo" data-hidden="1" name="fview_in_outgrid$x<?= $Grid->RowIndex ?>_articulo" id="fview_in_outgrid$x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->FormValue) ?>">
<input type="hidden" data-table="view_in_out" data-field="x_articulo" data-hidden="1" data-old name="fview_in_outgrid$o<?= $Grid->RowIndex ?>_articulo" id="fview_in_outgrid$o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->lote->Visible) { // lote ?>
        <td data-name="lote"<?= $Grid->lote->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_lote" class="el_view_in_out_lote">
<input type="<?= $Grid->lote->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_lote" id="x<?= $Grid->RowIndex ?>_lote" data-table="view_in_out" data-field="x_lote" value="<?= $Grid->lote->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Grid->lote->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->lote->formatPattern()) ?>"<?= $Grid->lote->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->lote->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_in_out" data-field="x_lote" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_lote" id="o<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_lote" class="el_view_in_out_lote">
<input type="<?= $Grid->lote->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_lote" id="x<?= $Grid->RowIndex ?>_lote" data-table="view_in_out" data-field="x_lote" value="<?= $Grid->lote->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Grid->lote->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->lote->formatPattern()) ?>"<?= $Grid->lote->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->lote->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_lote" class="el_view_in_out_lote">
<span<?= $Grid->lote->viewAttributes() ?>>
<?= $Grid->lote->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_in_out" data-field="x_lote" data-hidden="1" name="fview_in_outgrid$x<?= $Grid->RowIndex ?>_lote" id="fview_in_outgrid$x<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->FormValue) ?>">
<input type="hidden" data-table="view_in_out" data-field="x_lote" data-hidden="1" data-old name="fview_in_outgrid$o<?= $Grid->RowIndex ?>_lote" id="fview_in_outgrid$o<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
        <td data-name="fecha_vencimiento"<?= $Grid->fecha_vencimiento->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_fecha_vencimiento" class="el_view_in_out_fecha_vencimiento">
<input type="<?= $Grid->fecha_vencimiento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_fecha_vencimiento" id="x<?= $Grid->RowIndex ?>_fecha_vencimiento" data-table="view_in_out" data-field="x_fecha_vencimiento" value="<?= $Grid->fecha_vencimiento->EditValue ?>" placeholder="<?= HtmlEncode($Grid->fecha_vencimiento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fecha_vencimiento->formatPattern()) ?>"<?= $Grid->fecha_vencimiento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha_vencimiento->getErrorMessage() ?></div>
<?php if (!$Grid->fecha_vencimiento->ReadOnly && !$Grid->fecha_vencimiento->Disabled && !isset($Grid->fecha_vencimiento->EditAttrs["readonly"]) && !isset($Grid->fecha_vencimiento->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fview_in_outgrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    clock: !!format.match(/h/i) || !!format.match(/m/) || !!format.match(/s/i),
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.getPreferredTheme()
            }
        };
    ew.createDateTimePicker("fview_in_outgrid", "x<?= $Grid->RowIndex ?>_fecha_vencimiento", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="view_in_out" data-field="x_fecha_vencimiento" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_fecha_vencimiento" id="o<?= $Grid->RowIndex ?>_fecha_vencimiento" value="<?= HtmlEncode($Grid->fecha_vencimiento->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_fecha_vencimiento" class="el_view_in_out_fecha_vencimiento">
<input type="<?= $Grid->fecha_vencimiento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_fecha_vencimiento" id="x<?= $Grid->RowIndex ?>_fecha_vencimiento" data-table="view_in_out" data-field="x_fecha_vencimiento" value="<?= $Grid->fecha_vencimiento->EditValue ?>" placeholder="<?= HtmlEncode($Grid->fecha_vencimiento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fecha_vencimiento->formatPattern()) ?>"<?= $Grid->fecha_vencimiento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha_vencimiento->getErrorMessage() ?></div>
<?php if (!$Grid->fecha_vencimiento->ReadOnly && !$Grid->fecha_vencimiento->Disabled && !isset($Grid->fecha_vencimiento->EditAttrs["readonly"]) && !isset($Grid->fecha_vencimiento->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fview_in_outgrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    clock: !!format.match(/h/i) || !!format.match(/m/) || !!format.match(/s/i),
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.getPreferredTheme()
            }
        };
    ew.createDateTimePicker("fview_in_outgrid", "x<?= $Grid->RowIndex ?>_fecha_vencimiento", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_fecha_vencimiento" class="el_view_in_out_fecha_vencimiento">
<span<?= $Grid->fecha_vencimiento->viewAttributes() ?>>
<?= $Grid->fecha_vencimiento->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_in_out" data-field="x_fecha_vencimiento" data-hidden="1" name="fview_in_outgrid$x<?= $Grid->RowIndex ?>_fecha_vencimiento" id="fview_in_outgrid$x<?= $Grid->RowIndex ?>_fecha_vencimiento" value="<?= HtmlEncode($Grid->fecha_vencimiento->FormValue) ?>">
<input type="hidden" data-table="view_in_out" data-field="x_fecha_vencimiento" data-hidden="1" data-old name="fview_in_outgrid$o<?= $Grid->RowIndex ?>_fecha_vencimiento" id="fview_in_outgrid$o<?= $Grid->RowIndex ?>_fecha_vencimiento" value="<?= HtmlEncode($Grid->fecha_vencimiento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->almacen->Visible) { // almacen ?>
        <td data-name="almacen"<?= $Grid->almacen->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_almacen" class="el_view_in_out_almacen">
<input type="<?= $Grid->almacen->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_almacen" id="x<?= $Grid->RowIndex ?>_almacen" data-table="view_in_out" data-field="x_almacen" value="<?= $Grid->almacen->EditValue ?>" size="30" maxlength="6" placeholder="<?= HtmlEncode($Grid->almacen->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->almacen->formatPattern()) ?>"<?= $Grid->almacen->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->almacen->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_in_out" data-field="x_almacen" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_almacen" id="o<?= $Grid->RowIndex ?>_almacen" value="<?= HtmlEncode($Grid->almacen->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_almacen" class="el_view_in_out_almacen">
<input type="<?= $Grid->almacen->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_almacen" id="x<?= $Grid->RowIndex ?>_almacen" data-table="view_in_out" data-field="x_almacen" value="<?= $Grid->almacen->EditValue ?>" size="30" maxlength="6" placeholder="<?= HtmlEncode($Grid->almacen->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->almacen->formatPattern()) ?>"<?= $Grid->almacen->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->almacen->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_almacen" class="el_view_in_out_almacen">
<span<?= $Grid->almacen->viewAttributes() ?>>
<?= $Grid->almacen->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_in_out" data-field="x_almacen" data-hidden="1" name="fview_in_outgrid$x<?= $Grid->RowIndex ?>_almacen" id="fview_in_outgrid$x<?= $Grid->RowIndex ?>_almacen" value="<?= HtmlEncode($Grid->almacen->FormValue) ?>">
<input type="hidden" data-table="view_in_out" data-field="x_almacen" data-hidden="1" data-old name="fview_in_outgrid$o<?= $Grid->RowIndex ?>_almacen" id="fview_in_outgrid$o<?= $Grid->RowIndex ?>_almacen" value="<?= HtmlEncode($Grid->almacen->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <td data-name="cantidad_articulo"<?= $Grid->cantidad_articulo->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_cantidad_articulo" class="el_view_in_out_cantidad_articulo">
<input type="<?= $Grid->cantidad_articulo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_cantidad_articulo" id="x<?= $Grid->RowIndex ?>_cantidad_articulo" data-table="view_in_out" data-field="x_cantidad_articulo" value="<?= $Grid->cantidad_articulo->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->cantidad_articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->cantidad_articulo->formatPattern()) ?>"<?= $Grid->cantidad_articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cantidad_articulo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_in_out" data-field="x_cantidad_articulo" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_cantidad_articulo" id="o<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_cantidad_articulo" class="el_view_in_out_cantidad_articulo">
<input type="<?= $Grid->cantidad_articulo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_cantidad_articulo" id="x<?= $Grid->RowIndex ?>_cantidad_articulo" data-table="view_in_out" data-field="x_cantidad_articulo" value="<?= $Grid->cantidad_articulo->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->cantidad_articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->cantidad_articulo->formatPattern()) ?>"<?= $Grid->cantidad_articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cantidad_articulo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_cantidad_articulo" class="el_view_in_out_cantidad_articulo">
<span<?= $Grid->cantidad_articulo->viewAttributes() ?>>
<?= $Grid->cantidad_articulo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_in_out" data-field="x_cantidad_articulo" data-hidden="1" name="fview_in_outgrid$x<?= $Grid->RowIndex ?>_cantidad_articulo" id="fview_in_outgrid$x<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->FormValue) ?>">
<input type="hidden" data-table="view_in_out" data-field="x_cantidad_articulo" data-hidden="1" data-old name="fview_in_outgrid$o<?= $Grid->RowIndex ?>_cantidad_articulo" id="fview_in_outgrid$o<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->articulo_unidad_medida->Visible) { // articulo_unidad_medida ?>
        <td data-name="articulo_unidad_medida"<?= $Grid->articulo_unidad_medida->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_articulo_unidad_medida" class="el_view_in_out_articulo_unidad_medida">
<input type="<?= $Grid->articulo_unidad_medida->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_articulo_unidad_medida" id="x<?= $Grid->RowIndex ?>_articulo_unidad_medida" data-table="view_in_out" data-field="x_articulo_unidad_medida" value="<?= $Grid->articulo_unidad_medida->EditValue ?>" size="30" maxlength="6" placeholder="<?= HtmlEncode($Grid->articulo_unidad_medida->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->articulo_unidad_medida->formatPattern()) ?>"<?= $Grid->articulo_unidad_medida->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->articulo_unidad_medida->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_in_out" data-field="x_articulo_unidad_medida" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_articulo_unidad_medida" id="o<?= $Grid->RowIndex ?>_articulo_unidad_medida" value="<?= HtmlEncode($Grid->articulo_unidad_medida->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_articulo_unidad_medida" class="el_view_in_out_articulo_unidad_medida">
<input type="<?= $Grid->articulo_unidad_medida->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_articulo_unidad_medida" id="x<?= $Grid->RowIndex ?>_articulo_unidad_medida" data-table="view_in_out" data-field="x_articulo_unidad_medida" value="<?= $Grid->articulo_unidad_medida->EditValue ?>" size="30" maxlength="6" placeholder="<?= HtmlEncode($Grid->articulo_unidad_medida->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->articulo_unidad_medida->formatPattern()) ?>"<?= $Grid->articulo_unidad_medida->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->articulo_unidad_medida->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_articulo_unidad_medida" class="el_view_in_out_articulo_unidad_medida">
<span<?= $Grid->articulo_unidad_medida->viewAttributes() ?>>
<?= $Grid->articulo_unidad_medida->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_in_out" data-field="x_articulo_unidad_medida" data-hidden="1" name="fview_in_outgrid$x<?= $Grid->RowIndex ?>_articulo_unidad_medida" id="fview_in_outgrid$x<?= $Grid->RowIndex ?>_articulo_unidad_medida" value="<?= HtmlEncode($Grid->articulo_unidad_medida->FormValue) ?>">
<input type="hidden" data-table="view_in_out" data-field="x_articulo_unidad_medida" data-hidden="1" data-old name="fview_in_outgrid$o<?= $Grid->RowIndex ?>_articulo_unidad_medida" id="fview_in_outgrid$o<?= $Grid->RowIndex ?>_articulo_unidad_medida" value="<?= HtmlEncode($Grid->articulo_unidad_medida->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->cantidad_unidad_medida->Visible) { // cantidad_unidad_medida ?>
        <td data-name="cantidad_unidad_medida"<?= $Grid->cantidad_unidad_medida->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_cantidad_unidad_medida" class="el_view_in_out_cantidad_unidad_medida">
<input type="<?= $Grid->cantidad_unidad_medida->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_cantidad_unidad_medida" id="x<?= $Grid->RowIndex ?>_cantidad_unidad_medida" data-table="view_in_out" data-field="x_cantidad_unidad_medida" value="<?= $Grid->cantidad_unidad_medida->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->cantidad_unidad_medida->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->cantidad_unidad_medida->formatPattern()) ?>"<?= $Grid->cantidad_unidad_medida->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cantidad_unidad_medida->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_in_out" data-field="x_cantidad_unidad_medida" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_cantidad_unidad_medida" id="o<?= $Grid->RowIndex ?>_cantidad_unidad_medida" value="<?= HtmlEncode($Grid->cantidad_unidad_medida->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_cantidad_unidad_medida" class="el_view_in_out_cantidad_unidad_medida">
<input type="<?= $Grid->cantidad_unidad_medida->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_cantidad_unidad_medida" id="x<?= $Grid->RowIndex ?>_cantidad_unidad_medida" data-table="view_in_out" data-field="x_cantidad_unidad_medida" value="<?= $Grid->cantidad_unidad_medida->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->cantidad_unidad_medida->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->cantidad_unidad_medida->formatPattern()) ?>"<?= $Grid->cantidad_unidad_medida->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cantidad_unidad_medida->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_cantidad_unidad_medida" class="el_view_in_out_cantidad_unidad_medida">
<span<?= $Grid->cantidad_unidad_medida->viewAttributes() ?>>
<?= $Grid->cantidad_unidad_medida->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_in_out" data-field="x_cantidad_unidad_medida" data-hidden="1" name="fview_in_outgrid$x<?= $Grid->RowIndex ?>_cantidad_unidad_medida" id="fview_in_outgrid$x<?= $Grid->RowIndex ?>_cantidad_unidad_medida" value="<?= HtmlEncode($Grid->cantidad_unidad_medida->FormValue) ?>">
<input type="hidden" data-table="view_in_out" data-field="x_cantidad_unidad_medida" data-hidden="1" data-old name="fview_in_outgrid$o<?= $Grid->RowIndex ?>_cantidad_unidad_medida" id="fview_in_outgrid$o<?= $Grid->RowIndex ?>_cantidad_unidad_medida" value="<?= HtmlEncode($Grid->cantidad_unidad_medida->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->cantidad_movimiento->Visible) { // cantidad_movimiento ?>
        <td data-name="cantidad_movimiento"<?= $Grid->cantidad_movimiento->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_cantidad_movimiento" class="el_view_in_out_cantidad_movimiento">
<input type="<?= $Grid->cantidad_movimiento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_cantidad_movimiento" id="x<?= $Grid->RowIndex ?>_cantidad_movimiento" data-table="view_in_out" data-field="x_cantidad_movimiento" value="<?= $Grid->cantidad_movimiento->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->cantidad_movimiento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->cantidad_movimiento->formatPattern()) ?>"<?= $Grid->cantidad_movimiento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cantidad_movimiento->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_in_out" data-field="x_cantidad_movimiento" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_cantidad_movimiento" id="o<?= $Grid->RowIndex ?>_cantidad_movimiento" value="<?= HtmlEncode($Grid->cantidad_movimiento->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_cantidad_movimiento" class="el_view_in_out_cantidad_movimiento">
<input type="<?= $Grid->cantidad_movimiento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_cantidad_movimiento" id="x<?= $Grid->RowIndex ?>_cantidad_movimiento" data-table="view_in_out" data-field="x_cantidad_movimiento" value="<?= $Grid->cantidad_movimiento->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->cantidad_movimiento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->cantidad_movimiento->formatPattern()) ?>"<?= $Grid->cantidad_movimiento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cantidad_movimiento->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_cantidad_movimiento" class="el_view_in_out_cantidad_movimiento">
<span<?= $Grid->cantidad_movimiento->viewAttributes() ?>>
<?= $Grid->cantidad_movimiento->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_in_out" data-field="x_cantidad_movimiento" data-hidden="1" name="fview_in_outgrid$x<?= $Grid->RowIndex ?>_cantidad_movimiento" id="fview_in_outgrid$x<?= $Grid->RowIndex ?>_cantidad_movimiento" value="<?= HtmlEncode($Grid->cantidad_movimiento->FormValue) ?>">
<input type="hidden" data-table="view_in_out" data-field="x_cantidad_movimiento" data-hidden="1" data-old name="fview_in_outgrid$o<?= $Grid->RowIndex ?>_cantidad_movimiento" id="fview_in_outgrid$o<?= $Grid->RowIndex ?>_cantidad_movimiento" value="<?= HtmlEncode($Grid->cantidad_movimiento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->costo_unidad->Visible) { // costo_unidad ?>
        <td data-name="costo_unidad"<?= $Grid->costo_unidad->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_costo_unidad" class="el_view_in_out_costo_unidad">
<input type="<?= $Grid->costo_unidad->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_costo_unidad" id="x<?= $Grid->RowIndex ?>_costo_unidad" data-table="view_in_out" data-field="x_costo_unidad" value="<?= $Grid->costo_unidad->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->costo_unidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->costo_unidad->formatPattern()) ?>"<?= $Grid->costo_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->costo_unidad->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_in_out" data-field="x_costo_unidad" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_costo_unidad" id="o<?= $Grid->RowIndex ?>_costo_unidad" value="<?= HtmlEncode($Grid->costo_unidad->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_costo_unidad" class="el_view_in_out_costo_unidad">
<input type="<?= $Grid->costo_unidad->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_costo_unidad" id="x<?= $Grid->RowIndex ?>_costo_unidad" data-table="view_in_out" data-field="x_costo_unidad" value="<?= $Grid->costo_unidad->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->costo_unidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->costo_unidad->formatPattern()) ?>"<?= $Grid->costo_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->costo_unidad->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_costo_unidad" class="el_view_in_out_costo_unidad">
<span<?= $Grid->costo_unidad->viewAttributes() ?>>
<?= $Grid->costo_unidad->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_in_out" data-field="x_costo_unidad" data-hidden="1" name="fview_in_outgrid$x<?= $Grid->RowIndex ?>_costo_unidad" id="fview_in_outgrid$x<?= $Grid->RowIndex ?>_costo_unidad" value="<?= HtmlEncode($Grid->costo_unidad->FormValue) ?>">
<input type="hidden" data-table="view_in_out" data-field="x_costo_unidad" data-hidden="1" data-old name="fview_in_outgrid$o<?= $Grid->RowIndex ?>_costo_unidad" id="fview_in_outgrid$o<?= $Grid->RowIndex ?>_costo_unidad" value="<?= HtmlEncode($Grid->costo_unidad->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->costo->Visible) { // costo ?>
        <td data-name="costo"<?= $Grid->costo->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_costo" class="el_view_in_out_costo">
<input type="<?= $Grid->costo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_costo" id="x<?= $Grid->RowIndex ?>_costo" data-table="view_in_out" data-field="x_costo" value="<?= $Grid->costo->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->costo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->costo->formatPattern()) ?>"<?= $Grid->costo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->costo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_in_out" data-field="x_costo" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_costo" id="o<?= $Grid->RowIndex ?>_costo" value="<?= HtmlEncode($Grid->costo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_costo" class="el_view_in_out_costo">
<input type="<?= $Grid->costo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_costo" id="x<?= $Grid->RowIndex ?>_costo" data-table="view_in_out" data-field="x_costo" value="<?= $Grid->costo->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->costo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->costo->formatPattern()) ?>"<?= $Grid->costo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->costo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_costo" class="el_view_in_out_costo">
<span<?= $Grid->costo->viewAttributes() ?>>
<?= $Grid->costo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_in_out" data-field="x_costo" data-hidden="1" name="fview_in_outgrid$x<?= $Grid->RowIndex ?>_costo" id="fview_in_outgrid$x<?= $Grid->RowIndex ?>_costo" value="<?= HtmlEncode($Grid->costo->FormValue) ?>">
<input type="hidden" data-table="view_in_out" data-field="x_costo" data-hidden="1" data-old name="fview_in_outgrid$o<?= $Grid->RowIndex ?>_costo" id="fview_in_outgrid$o<?= $Grid->RowIndex ?>_costo" value="<?= HtmlEncode($Grid->costo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->precio_unidad->Visible) { // precio_unidad ?>
        <td data-name="precio_unidad"<?= $Grid->precio_unidad->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_precio_unidad" class="el_view_in_out_precio_unidad">
<input type="<?= $Grid->precio_unidad->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_precio_unidad" id="x<?= $Grid->RowIndex ?>_precio_unidad" data-table="view_in_out" data-field="x_precio_unidad" value="<?= $Grid->precio_unidad->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->precio_unidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->precio_unidad->formatPattern()) ?>"<?= $Grid->precio_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio_unidad->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_in_out" data-field="x_precio_unidad" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_precio_unidad" id="o<?= $Grid->RowIndex ?>_precio_unidad" value="<?= HtmlEncode($Grid->precio_unidad->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_precio_unidad" class="el_view_in_out_precio_unidad">
<input type="<?= $Grid->precio_unidad->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_precio_unidad" id="x<?= $Grid->RowIndex ?>_precio_unidad" data-table="view_in_out" data-field="x_precio_unidad" value="<?= $Grid->precio_unidad->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->precio_unidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->precio_unidad->formatPattern()) ?>"<?= $Grid->precio_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio_unidad->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_precio_unidad" class="el_view_in_out_precio_unidad">
<span<?= $Grid->precio_unidad->viewAttributes() ?>>
<?= $Grid->precio_unidad->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_in_out" data-field="x_precio_unidad" data-hidden="1" name="fview_in_outgrid$x<?= $Grid->RowIndex ?>_precio_unidad" id="fview_in_outgrid$x<?= $Grid->RowIndex ?>_precio_unidad" value="<?= HtmlEncode($Grid->precio_unidad->FormValue) ?>">
<input type="hidden" data-table="view_in_out" data-field="x_precio_unidad" data-hidden="1" data-old name="fview_in_outgrid$o<?= $Grid->RowIndex ?>_precio_unidad" id="fview_in_outgrid$o<?= $Grid->RowIndex ?>_precio_unidad" value="<?= HtmlEncode($Grid->precio_unidad->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->precio->Visible) { // precio ?>
        <td data-name="precio"<?= $Grid->precio->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_precio" class="el_view_in_out_precio">
<input type="<?= $Grid->precio->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_precio" id="x<?= $Grid->RowIndex ?>_precio" data-table="view_in_out" data-field="x_precio" value="<?= $Grid->precio->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->precio->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->precio->formatPattern()) ?>"<?= $Grid->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_in_out" data-field="x_precio" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_precio" id="o<?= $Grid->RowIndex ?>_precio" value="<?= HtmlEncode($Grid->precio->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_precio" class="el_view_in_out_precio">
<input type="<?= $Grid->precio->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_precio" id="x<?= $Grid->RowIndex ?>_precio" data-table="view_in_out" data-field="x_precio" value="<?= $Grid->precio->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->precio->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->precio->formatPattern()) ?>"<?= $Grid->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_precio" class="el_view_in_out_precio">
<span<?= $Grid->precio->viewAttributes() ?>>
<?= $Grid->precio->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_in_out" data-field="x_precio" data-hidden="1" name="fview_in_outgrid$x<?= $Grid->RowIndex ?>_precio" id="fview_in_outgrid$x<?= $Grid->RowIndex ?>_precio" value="<?= HtmlEncode($Grid->precio->FormValue) ?>">
<input type="hidden" data-table="view_in_out" data-field="x_precio" data-hidden="1" data-old name="fview_in_outgrid$o<?= $Grid->RowIndex ?>_precio" id="fview_in_outgrid$o<?= $Grid->RowIndex ?>_precio" value="<?= HtmlEncode($Grid->precio->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->id_compra->Visible) { // id_compra ?>
        <td data-name="id_compra"<?= $Grid->id_compra->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_id_compra" class="el_view_in_out_id_compra">
<input type="<?= $Grid->id_compra->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_id_compra" id="x<?= $Grid->RowIndex ?>_id_compra" data-table="view_in_out" data-field="x_id_compra" value="<?= $Grid->id_compra->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->id_compra->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->id_compra->formatPattern()) ?>"<?= $Grid->id_compra->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->id_compra->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_in_out" data-field="x_id_compra" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_id_compra" id="o<?= $Grid->RowIndex ?>_id_compra" value="<?= HtmlEncode($Grid->id_compra->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_id_compra" class="el_view_in_out_id_compra">
<input type="<?= $Grid->id_compra->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_id_compra" id="x<?= $Grid->RowIndex ?>_id_compra" data-table="view_in_out" data-field="x_id_compra" value="<?= $Grid->id_compra->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->id_compra->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->id_compra->formatPattern()) ?>"<?= $Grid->id_compra->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->id_compra->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_id_compra" class="el_view_in_out_id_compra">
<span<?= $Grid->id_compra->viewAttributes() ?>>
<?= $Grid->id_compra->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_in_out" data-field="x_id_compra" data-hidden="1" name="fview_in_outgrid$x<?= $Grid->RowIndex ?>_id_compra" id="fview_in_outgrid$x<?= $Grid->RowIndex ?>_id_compra" value="<?= HtmlEncode($Grid->id_compra->FormValue) ?>">
<input type="hidden" data-table="view_in_out" data-field="x_id_compra" data-hidden="1" data-old name="fview_in_outgrid$o<?= $Grid->RowIndex ?>_id_compra" id="fview_in_outgrid$o<?= $Grid->RowIndex ?>_id_compra" value="<?= HtmlEncode($Grid->id_compra->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->alicuota->Visible) { // alicuota ?>
        <td data-name="alicuota"<?= $Grid->alicuota->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_alicuota" class="el_view_in_out_alicuota">
<input type="<?= $Grid->alicuota->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_alicuota" id="x<?= $Grid->RowIndex ?>_alicuota" data-table="view_in_out" data-field="x_alicuota" value="<?= $Grid->alicuota->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->alicuota->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->alicuota->formatPattern()) ?>"<?= $Grid->alicuota->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->alicuota->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_in_out" data-field="x_alicuota" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_alicuota" id="o<?= $Grid->RowIndex ?>_alicuota" value="<?= HtmlEncode($Grid->alicuota->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_alicuota" class="el_view_in_out_alicuota">
<input type="<?= $Grid->alicuota->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_alicuota" id="x<?= $Grid->RowIndex ?>_alicuota" data-table="view_in_out" data-field="x_alicuota" value="<?= $Grid->alicuota->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->alicuota->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->alicuota->formatPattern()) ?>"<?= $Grid->alicuota->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->alicuota->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_alicuota" class="el_view_in_out_alicuota">
<span<?= $Grid->alicuota->viewAttributes() ?>>
<?= $Grid->alicuota->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_in_out" data-field="x_alicuota" data-hidden="1" name="fview_in_outgrid$x<?= $Grid->RowIndex ?>_alicuota" id="fview_in_outgrid$x<?= $Grid->RowIndex ?>_alicuota" value="<?= HtmlEncode($Grid->alicuota->FormValue) ?>">
<input type="hidden" data-table="view_in_out" data-field="x_alicuota" data-hidden="1" data-old name="fview_in_outgrid$o<?= $Grid->RowIndex ?>_alicuota" id="fview_in_outgrid$o<?= $Grid->RowIndex ?>_alicuota" value="<?= HtmlEncode($Grid->alicuota->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->cantidad_movimiento_consignacion->Visible) { // cantidad_movimiento_consignacion ?>
        <td data-name="cantidad_movimiento_consignacion"<?= $Grid->cantidad_movimiento_consignacion->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_cantidad_movimiento_consignacion" class="el_view_in_out_cantidad_movimiento_consignacion">
<input type="<?= $Grid->cantidad_movimiento_consignacion->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_cantidad_movimiento_consignacion" id="x<?= $Grid->RowIndex ?>_cantidad_movimiento_consignacion" data-table="view_in_out" data-field="x_cantidad_movimiento_consignacion" value="<?= $Grid->cantidad_movimiento_consignacion->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->cantidad_movimiento_consignacion->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->cantidad_movimiento_consignacion->formatPattern()) ?>"<?= $Grid->cantidad_movimiento_consignacion->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cantidad_movimiento_consignacion->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_in_out" data-field="x_cantidad_movimiento_consignacion" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_cantidad_movimiento_consignacion" id="o<?= $Grid->RowIndex ?>_cantidad_movimiento_consignacion" value="<?= HtmlEncode($Grid->cantidad_movimiento_consignacion->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_cantidad_movimiento_consignacion" class="el_view_in_out_cantidad_movimiento_consignacion">
<input type="<?= $Grid->cantidad_movimiento_consignacion->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_cantidad_movimiento_consignacion" id="x<?= $Grid->RowIndex ?>_cantidad_movimiento_consignacion" data-table="view_in_out" data-field="x_cantidad_movimiento_consignacion" value="<?= $Grid->cantidad_movimiento_consignacion->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->cantidad_movimiento_consignacion->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->cantidad_movimiento_consignacion->formatPattern()) ?>"<?= $Grid->cantidad_movimiento_consignacion->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cantidad_movimiento_consignacion->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_cantidad_movimiento_consignacion" class="el_view_in_out_cantidad_movimiento_consignacion">
<span<?= $Grid->cantidad_movimiento_consignacion->viewAttributes() ?>>
<?= $Grid->cantidad_movimiento_consignacion->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_in_out" data-field="x_cantidad_movimiento_consignacion" data-hidden="1" name="fview_in_outgrid$x<?= $Grid->RowIndex ?>_cantidad_movimiento_consignacion" id="fview_in_outgrid$x<?= $Grid->RowIndex ?>_cantidad_movimiento_consignacion" value="<?= HtmlEncode($Grid->cantidad_movimiento_consignacion->FormValue) ?>">
<input type="hidden" data-table="view_in_out" data-field="x_cantidad_movimiento_consignacion" data-hidden="1" data-old name="fview_in_outgrid$o<?= $Grid->RowIndex ?>_cantidad_movimiento_consignacion" id="fview_in_outgrid$o<?= $Grid->RowIndex ?>_cantidad_movimiento_consignacion" value="<?= HtmlEncode($Grid->cantidad_movimiento_consignacion->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->id_consignacion->Visible) { // id_consignacion ?>
        <td data-name="id_consignacion"<?= $Grid->id_consignacion->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_id_consignacion" class="el_view_in_out_id_consignacion">
<input type="<?= $Grid->id_consignacion->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_id_consignacion" id="x<?= $Grid->RowIndex ?>_id_consignacion" data-table="view_in_out" data-field="x_id_consignacion" value="<?= $Grid->id_consignacion->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->id_consignacion->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->id_consignacion->formatPattern()) ?>"<?= $Grid->id_consignacion->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->id_consignacion->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_in_out" data-field="x_id_consignacion" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_id_consignacion" id="o<?= $Grid->RowIndex ?>_id_consignacion" value="<?= HtmlEncode($Grid->id_consignacion->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_id_consignacion" class="el_view_in_out_id_consignacion">
<input type="<?= $Grid->id_consignacion->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_id_consignacion" id="x<?= $Grid->RowIndex ?>_id_consignacion" data-table="view_in_out" data-field="x_id_consignacion" value="<?= $Grid->id_consignacion->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->id_consignacion->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->id_consignacion->formatPattern()) ?>"<?= $Grid->id_consignacion->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->id_consignacion->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_id_consignacion" class="el_view_in_out_id_consignacion">
<span<?= $Grid->id_consignacion->viewAttributes() ?>>
<?= $Grid->id_consignacion->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_in_out" data-field="x_id_consignacion" data-hidden="1" name="fview_in_outgrid$x<?= $Grid->RowIndex ?>_id_consignacion" id="fview_in_outgrid$x<?= $Grid->RowIndex ?>_id_consignacion" value="<?= HtmlEncode($Grid->id_consignacion->FormValue) ?>">
<input type="hidden" data-table="view_in_out" data-field="x_id_consignacion" data-hidden="1" data-old name="fview_in_outgrid$o<?= $Grid->RowIndex ?>_id_consignacion" id="fview_in_outgrid$o<?= $Grid->RowIndex ?>_id_consignacion" value="<?= HtmlEncode($Grid->id_consignacion->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->descuento->Visible) { // descuento ?>
        <td data-name="descuento"<?= $Grid->descuento->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_descuento" class="el_view_in_out_descuento">
<input type="<?= $Grid->descuento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_descuento" id="x<?= $Grid->RowIndex ?>_descuento" data-table="view_in_out" data-field="x_descuento" value="<?= $Grid->descuento->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->descuento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->descuento->formatPattern()) ?>"<?= $Grid->descuento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->descuento->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_in_out" data-field="x_descuento" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_descuento" id="o<?= $Grid->RowIndex ?>_descuento" value="<?= HtmlEncode($Grid->descuento->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_descuento" class="el_view_in_out_descuento">
<input type="<?= $Grid->descuento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_descuento" id="x<?= $Grid->RowIndex ?>_descuento" data-table="view_in_out" data-field="x_descuento" value="<?= $Grid->descuento->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->descuento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->descuento->formatPattern()) ?>"<?= $Grid->descuento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->descuento->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_descuento" class="el_view_in_out_descuento">
<span<?= $Grid->descuento->viewAttributes() ?>>
<?= $Grid->descuento->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_in_out" data-field="x_descuento" data-hidden="1" name="fview_in_outgrid$x<?= $Grid->RowIndex ?>_descuento" id="fview_in_outgrid$x<?= $Grid->RowIndex ?>_descuento" value="<?= HtmlEncode($Grid->descuento->FormValue) ?>">
<input type="hidden" data-table="view_in_out" data-field="x_descuento" data-hidden="1" data-old name="fview_in_outgrid$o<?= $Grid->RowIndex ?>_descuento" id="fview_in_outgrid$o<?= $Grid->RowIndex ?>_descuento" value="<?= HtmlEncode($Grid->descuento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->precio_unidad_sin_desc->Visible) { // precio_unidad_sin_desc ?>
        <td data-name="precio_unidad_sin_desc"<?= $Grid->precio_unidad_sin_desc->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_precio_unidad_sin_desc" class="el_view_in_out_precio_unidad_sin_desc">
<input type="<?= $Grid->precio_unidad_sin_desc->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" id="x<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" data-table="view_in_out" data-field="x_precio_unidad_sin_desc" value="<?= $Grid->precio_unidad_sin_desc->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->precio_unidad_sin_desc->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->precio_unidad_sin_desc->formatPattern()) ?>"<?= $Grid->precio_unidad_sin_desc->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio_unidad_sin_desc->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_in_out" data-field="x_precio_unidad_sin_desc" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" id="o<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" value="<?= HtmlEncode($Grid->precio_unidad_sin_desc->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_precio_unidad_sin_desc" class="el_view_in_out_precio_unidad_sin_desc">
<input type="<?= $Grid->precio_unidad_sin_desc->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" id="x<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" data-table="view_in_out" data-field="x_precio_unidad_sin_desc" value="<?= $Grid->precio_unidad_sin_desc->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->precio_unidad_sin_desc->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->precio_unidad_sin_desc->formatPattern()) ?>"<?= $Grid->precio_unidad_sin_desc->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio_unidad_sin_desc->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_precio_unidad_sin_desc" class="el_view_in_out_precio_unidad_sin_desc">
<span<?= $Grid->precio_unidad_sin_desc->viewAttributes() ?>>
<?= $Grid->precio_unidad_sin_desc->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_in_out" data-field="x_precio_unidad_sin_desc" data-hidden="1" name="fview_in_outgrid$x<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" id="fview_in_outgrid$x<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" value="<?= HtmlEncode($Grid->precio_unidad_sin_desc->FormValue) ?>">
<input type="hidden" data-table="view_in_out" data-field="x_precio_unidad_sin_desc" data-hidden="1" data-old name="fview_in_outgrid$o<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" id="fview_in_outgrid$o<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" value="<?= HtmlEncode($Grid->precio_unidad_sin_desc->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->check_ne->Visible) { // check_ne ?>
        <td data-name="check_ne"<?= $Grid->check_ne->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_check_ne" class="el_view_in_out_check_ne">
<template id="tp_x<?= $Grid->RowIndex ?>_check_ne">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="view_in_out" data-field="x_check_ne" name="x<?= $Grid->RowIndex ?>_check_ne" id="x<?= $Grid->RowIndex ?>_check_ne"<?= $Grid->check_ne->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_check_ne" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Grid->RowIndex ?>_check_ne"
    name="x<?= $Grid->RowIndex ?>_check_ne"
    value="<?= HtmlEncode($Grid->check_ne->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_check_ne"
    data-target="dsl_x<?= $Grid->RowIndex ?>_check_ne"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->check_ne->isInvalidClass() ?>"
    data-table="view_in_out"
    data-field="x_check_ne"
    data-value-separator="<?= $Grid->check_ne->displayValueSeparatorAttribute() ?>"
    <?= $Grid->check_ne->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->check_ne->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_in_out" data-field="x_check_ne" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_check_ne" id="o<?= $Grid->RowIndex ?>_check_ne" value="<?= HtmlEncode($Grid->check_ne->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_check_ne" class="el_view_in_out_check_ne">
<template id="tp_x<?= $Grid->RowIndex ?>_check_ne">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="view_in_out" data-field="x_check_ne" name="x<?= $Grid->RowIndex ?>_check_ne" id="x<?= $Grid->RowIndex ?>_check_ne"<?= $Grid->check_ne->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_check_ne" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Grid->RowIndex ?>_check_ne"
    name="x<?= $Grid->RowIndex ?>_check_ne"
    value="<?= HtmlEncode($Grid->check_ne->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_check_ne"
    data-target="dsl_x<?= $Grid->RowIndex ?>_check_ne"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->check_ne->isInvalidClass() ?>"
    data-table="view_in_out"
    data-field="x_check_ne"
    data-value-separator="<?= $Grid->check_ne->displayValueSeparatorAttribute() ?>"
    <?= $Grid->check_ne->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->check_ne->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_check_ne" class="el_view_in_out_check_ne">
<span<?= $Grid->check_ne->viewAttributes() ?>>
<?= $Grid->check_ne->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_in_out" data-field="x_check_ne" data-hidden="1" name="fview_in_outgrid$x<?= $Grid->RowIndex ?>_check_ne" id="fview_in_outgrid$x<?= $Grid->RowIndex ?>_check_ne" value="<?= HtmlEncode($Grid->check_ne->FormValue) ?>">
<input type="hidden" data-table="view_in_out" data-field="x_check_ne" data-hidden="1" data-old name="fview_in_outgrid$o<?= $Grid->RowIndex ?>_check_ne" id="fview_in_outgrid$o<?= $Grid->RowIndex ?>_check_ne" value="<?= HtmlEncode($Grid->check_ne->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->packer_cantidad->Visible) { // packer_cantidad ?>
        <td data-name="packer_cantidad"<?= $Grid->packer_cantidad->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_packer_cantidad" class="el_view_in_out_packer_cantidad">
<input type="<?= $Grid->packer_cantidad->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_packer_cantidad" id="x<?= $Grid->RowIndex ?>_packer_cantidad" data-table="view_in_out" data-field="x_packer_cantidad" value="<?= $Grid->packer_cantidad->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->packer_cantidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->packer_cantidad->formatPattern()) ?>"<?= $Grid->packer_cantidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->packer_cantidad->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_in_out" data-field="x_packer_cantidad" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_packer_cantidad" id="o<?= $Grid->RowIndex ?>_packer_cantidad" value="<?= HtmlEncode($Grid->packer_cantidad->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_packer_cantidad" class="el_view_in_out_packer_cantidad">
<input type="<?= $Grid->packer_cantidad->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_packer_cantidad" id="x<?= $Grid->RowIndex ?>_packer_cantidad" data-table="view_in_out" data-field="x_packer_cantidad" value="<?= $Grid->packer_cantidad->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->packer_cantidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->packer_cantidad->formatPattern()) ?>"<?= $Grid->packer_cantidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->packer_cantidad->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_in_out_packer_cantidad" class="el_view_in_out_packer_cantidad">
<span<?= $Grid->packer_cantidad->viewAttributes() ?>>
<?= $Grid->packer_cantidad->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_in_out" data-field="x_packer_cantidad" data-hidden="1" name="fview_in_outgrid$x<?= $Grid->RowIndex ?>_packer_cantidad" id="fview_in_outgrid$x<?= $Grid->RowIndex ?>_packer_cantidad" value="<?= HtmlEncode($Grid->packer_cantidad->FormValue) ?>">
<input type="hidden" data-table="view_in_out" data-field="x_packer_cantidad" data-hidden="1" data-old name="fview_in_outgrid$o<?= $Grid->RowIndex ?>_packer_cantidad" id="fview_in_outgrid$o<?= $Grid->RowIndex ?>_packer_cantidad" value="<?= HtmlEncode($Grid->packer_cantidad->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowCount);
?>
    </tr>
<?php if ($Grid->RowType == RowType::ADD || $Grid->RowType == RowType::EDIT) { ?>
<script data-rowindex="<?= $Grid->RowIndex ?>">
loadjs.ready(["fview_in_outgrid","load"], () => fview_in_outgrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
</script>
<?php } ?>
<?php
    }
    } // End delete row checking

    // Reset for template row
    if ($Grid->RowIndex === '$rowindex$') {
        $Grid->RowIndex = 0;
    }
    // Reset inline add/copy row
    if (($Grid->isCopy() || $Grid->isAdd()) && $Grid->RowIndex == 0) {
        $Grid->RowIndex = 1;
    }
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php if ($Grid->CurrentMode == "add" || $Grid->CurrentMode == "copy") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
<?php if ($Grid->CurrentMode == "edit") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if ($Grid->CurrentMode == "") { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fview_in_outgrid">
</div><!-- /.ew-list-form -->
<?php
// Close result set
$Grid->Recordset?->free();
?>
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php $Grid->OtherOptions->render("body", "bottom") ?>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } else { ?>
<div class="ew-list-other-options">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<?php } ?>
</div>
<div id="ew-footer-options">
<?php $Grid->FooterOptions?->render("body") ?>
</div>
</main>
<?php if (!$Grid->isExport()) { ?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("view_in_out");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
