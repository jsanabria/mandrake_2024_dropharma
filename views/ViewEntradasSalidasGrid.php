<?php

namespace PHPMaker2024\mandrake;

// Set up and run Grid object
$Grid = Container("ViewEntradasSalidasGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fview_entradas_salidasgrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { view_entradas_salidas: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fview_entradas_salidasgrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null, ew.Validators.integer], fields.fabricante.isInvalid],
            ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null, ew.Validators.integer], fields.articulo.isInvalid],
            ["lote", [fields.lote.visible && fields.lote.required ? ew.Validators.required(fields.lote.caption) : null], fields.lote.isInvalid],
            ["fecha_vencimiento", [fields.fecha_vencimiento.visible && fields.fecha_vencimiento.required ? ew.Validators.required(fields.fecha_vencimiento.caption) : null, ew.Validators.datetime(fields.fecha_vencimiento.clientFormatPattern)], fields.fecha_vencimiento.isInvalid],
            ["cantidad_articulo", [fields.cantidad_articulo.visible && fields.cantidad_articulo.required ? ew.Validators.required(fields.cantidad_articulo.caption) : null, ew.Validators.float], fields.cantidad_articulo.isInvalid],
            ["articulo_unidad_medida", [fields.articulo_unidad_medida.visible && fields.articulo_unidad_medida.required ? ew.Validators.required(fields.articulo_unidad_medida.caption) : null], fields.articulo_unidad_medida.isInvalid],
            ["packer_cantidad", [fields.packer_cantidad.visible && fields.packer_cantidad.required ? ew.Validators.required(fields.packer_cantidad.caption) : null, ew.Validators.float], fields.packer_cantidad.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["fabricante",false],["articulo",false],["lote",false],["fecha_vencimiento",false],["cantidad_articulo",false],["articulo_unidad_medida",false],["packer_cantidad",false]];
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
            "fabricante": <?= $Grid->fabricante->toClientList($Grid) ?>,
            "articulo": <?= $Grid->articulo->toClientList($Grid) ?>,
            "articulo_unidad_medida": <?= $Grid->articulo_unidad_medida->toClientList($Grid) ?>,
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
<div id="fview_entradas_salidasgrid" class="ew-form ew-list-form">
<div id="gmp_view_entradas_salidas" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_view_entradas_salidasgrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Grid->fabricante->Visible) { // fabricante ?>
        <th data-name="fabricante" class="<?= $Grid->fabricante->headerCellClass() ?>"><div id="elh_view_entradas_salidas_fabricante" class="view_entradas_salidas_fabricante"><?= $Grid->renderFieldHeader($Grid->fabricante) ?></div></th>
<?php } ?>
<?php if ($Grid->articulo->Visible) { // articulo ?>
        <th data-name="articulo" class="<?= $Grid->articulo->headerCellClass() ?>"><div id="elh_view_entradas_salidas_articulo" class="view_entradas_salidas_articulo"><?= $Grid->renderFieldHeader($Grid->articulo) ?></div></th>
<?php } ?>
<?php if ($Grid->lote->Visible) { // lote ?>
        <th data-name="lote" class="<?= $Grid->lote->headerCellClass() ?>"><div id="elh_view_entradas_salidas_lote" class="view_entradas_salidas_lote"><?= $Grid->renderFieldHeader($Grid->lote) ?></div></th>
<?php } ?>
<?php if ($Grid->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
        <th data-name="fecha_vencimiento" class="<?= $Grid->fecha_vencimiento->headerCellClass() ?>"><div id="elh_view_entradas_salidas_fecha_vencimiento" class="view_entradas_salidas_fecha_vencimiento"><?= $Grid->renderFieldHeader($Grid->fecha_vencimiento) ?></div></th>
<?php } ?>
<?php if ($Grid->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <th data-name="cantidad_articulo" class="<?= $Grid->cantidad_articulo->headerCellClass() ?>"><div id="elh_view_entradas_salidas_cantidad_articulo" class="view_entradas_salidas_cantidad_articulo"><?= $Grid->renderFieldHeader($Grid->cantidad_articulo) ?></div></th>
<?php } ?>
<?php if ($Grid->articulo_unidad_medida->Visible) { // articulo_unidad_medida ?>
        <th data-name="articulo_unidad_medida" class="<?= $Grid->articulo_unidad_medida->headerCellClass() ?>"><div id="elh_view_entradas_salidas_articulo_unidad_medida" class="view_entradas_salidas_articulo_unidad_medida"><?= $Grid->renderFieldHeader($Grid->articulo_unidad_medida) ?></div></th>
<?php } ?>
<?php if ($Grid->packer_cantidad->Visible) { // packer_cantidad ?>
        <th data-name="packer_cantidad" class="<?= $Grid->packer_cantidad->headerCellClass() ?>"><div id="elh_view_entradas_salidas_packer_cantidad" class="view_entradas_salidas_packer_cantidad"><?= $Grid->renderFieldHeader($Grid->packer_cantidad) ?></div></th>
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
    <?php if ($Grid->fabricante->Visible) { // fabricante ?>
        <td data-name="fabricante"<?= $Grid->fabricante->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_entradas_salidas_fabricante" class="el_view_entradas_salidas_fabricante">
<?php
if (IsRTL()) {
    $Grid->fabricante->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x<?= $Grid->RowIndex ?>_fabricante" class="ew-auto-suggest">
    <input type="<?= $Grid->fabricante->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_fabricante" id="sv_x<?= $Grid->RowIndex ?>_fabricante" value="<?= RemoveHtml($Grid->fabricante->EditValue) ?>" autocomplete="off" size="30" placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fabricante->formatPattern()) ?>"<?= $Grid->fabricante->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="view_entradas_salidas" data-field="x_fabricante" data-input="sv_x<?= $Grid->RowIndex ?>_fabricante" data-value-separator="<?= $Grid->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->CurrentValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Grid->fabricante->getErrorMessage() ?></div>
<script>
loadjs.ready("fview_entradas_salidasgrid", function() {
    fview_entradas_salidasgrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_fabricante","forceSelect":false}, { lookupAllDisplayFields: <?= $Grid->fabricante->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.view_entradas_salidas.fields.fabricante.autoSuggestOptions));
});
</script>
<?= $Grid->fabricante->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_fabricante") ?>
</span>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_fabricante" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_fabricante" id="o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_entradas_salidas_fabricante" class="el_view_entradas_salidas_fabricante">
<?php
if (IsRTL()) {
    $Grid->fabricante->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x<?= $Grid->RowIndex ?>_fabricante" class="ew-auto-suggest">
    <input type="<?= $Grid->fabricante->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_fabricante" id="sv_x<?= $Grid->RowIndex ?>_fabricante" value="<?= RemoveHtml($Grid->fabricante->EditValue) ?>" autocomplete="off" size="30" placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fabricante->formatPattern()) ?>"<?= $Grid->fabricante->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="view_entradas_salidas" data-field="x_fabricante" data-input="sv_x<?= $Grid->RowIndex ?>_fabricante" data-value-separator="<?= $Grid->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->CurrentValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Grid->fabricante->getErrorMessage() ?></div>
<script>
loadjs.ready("fview_entradas_salidasgrid", function() {
    fview_entradas_salidasgrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_fabricante","forceSelect":false}, { lookupAllDisplayFields: <?= $Grid->fabricante->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.view_entradas_salidas.fields.fabricante.autoSuggestOptions));
});
</script>
<?= $Grid->fabricante->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_fabricante") ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_entradas_salidas_fabricante" class="el_view_entradas_salidas_fabricante">
<span<?= $Grid->fabricante->viewAttributes() ?>>
<?= $Grid->fabricante->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_fabricante" data-hidden="1" name="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_fabricante" id="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->FormValue) ?>">
<input type="hidden" data-table="view_entradas_salidas" data-field="x_fabricante" data-hidden="1" data-old name="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_fabricante" id="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->articulo->Visible) { // articulo ?>
        <td data-name="articulo"<?= $Grid->articulo->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_entradas_salidas_articulo" class="el_view_entradas_salidas_articulo">
<?php
if (IsRTL()) {
    $Grid->articulo->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x<?= $Grid->RowIndex ?>_articulo" class="ew-auto-suggest">
    <input type="<?= $Grid->articulo->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_articulo" id="sv_x<?= $Grid->RowIndex ?>_articulo" value="<?= RemoveHtml($Grid->articulo->EditValue) ?>" autocomplete="off" size="30" placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->articulo->formatPattern()) ?>"<?= $Grid->articulo->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="view_entradas_salidas" data-field="x_articulo" data-input="sv_x<?= $Grid->RowIndex ?>_articulo" data-value-separator="<?= $Grid->articulo->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_articulo" id="x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->CurrentValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Grid->articulo->getErrorMessage() ?></div>
<script>
loadjs.ready("fview_entradas_salidasgrid", function() {
    fview_entradas_salidasgrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_articulo","forceSelect":false}, { lookupAllDisplayFields: <?= $Grid->articulo->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.view_entradas_salidas.fields.articulo.autoSuggestOptions));
});
</script>
<?= $Grid->articulo->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo") ?>
</span>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_articulo" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_articulo" id="o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_entradas_salidas_articulo" class="el_view_entradas_salidas_articulo">
<?php
if (IsRTL()) {
    $Grid->articulo->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x<?= $Grid->RowIndex ?>_articulo" class="ew-auto-suggest">
    <input type="<?= $Grid->articulo->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_articulo" id="sv_x<?= $Grid->RowIndex ?>_articulo" value="<?= RemoveHtml($Grid->articulo->EditValue) ?>" autocomplete="off" size="30" placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->articulo->formatPattern()) ?>"<?= $Grid->articulo->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="view_entradas_salidas" data-field="x_articulo" data-input="sv_x<?= $Grid->RowIndex ?>_articulo" data-value-separator="<?= $Grid->articulo->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_articulo" id="x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->CurrentValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Grid->articulo->getErrorMessage() ?></div>
<script>
loadjs.ready("fview_entradas_salidasgrid", function() {
    fview_entradas_salidasgrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_articulo","forceSelect":false}, { lookupAllDisplayFields: <?= $Grid->articulo->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.view_entradas_salidas.fields.articulo.autoSuggestOptions));
});
</script>
<?= $Grid->articulo->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo") ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_entradas_salidas_articulo" class="el_view_entradas_salidas_articulo">
<span<?= $Grid->articulo->viewAttributes() ?>>
<?= $Grid->articulo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_articulo" data-hidden="1" name="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_articulo" id="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->FormValue) ?>">
<input type="hidden" data-table="view_entradas_salidas" data-field="x_articulo" data-hidden="1" data-old name="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_articulo" id="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->lote->Visible) { // lote ?>
        <td data-name="lote"<?= $Grid->lote->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_entradas_salidas_lote" class="el_view_entradas_salidas_lote">
<input type="<?= $Grid->lote->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_lote" id="x<?= $Grid->RowIndex ?>_lote" data-table="view_entradas_salidas" data-field="x_lote" value="<?= $Grid->lote->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Grid->lote->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->lote->formatPattern()) ?>"<?= $Grid->lote->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->lote->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_lote" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_lote" id="o<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_entradas_salidas_lote" class="el_view_entradas_salidas_lote">
<input type="<?= $Grid->lote->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_lote" id="x<?= $Grid->RowIndex ?>_lote" data-table="view_entradas_salidas" data-field="x_lote" value="<?= $Grid->lote->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Grid->lote->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->lote->formatPattern()) ?>"<?= $Grid->lote->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->lote->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_entradas_salidas_lote" class="el_view_entradas_salidas_lote">
<span<?= $Grid->lote->viewAttributes() ?>>
<?= $Grid->lote->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_lote" data-hidden="1" name="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_lote" id="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->FormValue) ?>">
<input type="hidden" data-table="view_entradas_salidas" data-field="x_lote" data-hidden="1" data-old name="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_lote" id="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
        <td data-name="fecha_vencimiento"<?= $Grid->fecha_vencimiento->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_entradas_salidas_fecha_vencimiento" class="el_view_entradas_salidas_fecha_vencimiento">
<input type="<?= $Grid->fecha_vencimiento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_fecha_vencimiento" id="x<?= $Grid->RowIndex ?>_fecha_vencimiento" data-table="view_entradas_salidas" data-field="x_fecha_vencimiento" value="<?= $Grid->fecha_vencimiento->EditValue ?>" placeholder="<?= HtmlEncode($Grid->fecha_vencimiento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fecha_vencimiento->formatPattern()) ?>"<?= $Grid->fecha_vencimiento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha_vencimiento->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_fecha_vencimiento" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_fecha_vencimiento" id="o<?= $Grid->RowIndex ?>_fecha_vencimiento" value="<?= HtmlEncode($Grid->fecha_vencimiento->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_entradas_salidas_fecha_vencimiento" class="el_view_entradas_salidas_fecha_vencimiento">
<input type="<?= $Grid->fecha_vencimiento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_fecha_vencimiento" id="x<?= $Grid->RowIndex ?>_fecha_vencimiento" data-table="view_entradas_salidas" data-field="x_fecha_vencimiento" value="<?= $Grid->fecha_vencimiento->EditValue ?>" placeholder="<?= HtmlEncode($Grid->fecha_vencimiento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fecha_vencimiento->formatPattern()) ?>"<?= $Grid->fecha_vencimiento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha_vencimiento->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_entradas_salidas_fecha_vencimiento" class="el_view_entradas_salidas_fecha_vencimiento">
<span<?= $Grid->fecha_vencimiento->viewAttributes() ?>>
<?= $Grid->fecha_vencimiento->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_fecha_vencimiento" data-hidden="1" name="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_fecha_vencimiento" id="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_fecha_vencimiento" value="<?= HtmlEncode($Grid->fecha_vencimiento->FormValue) ?>">
<input type="hidden" data-table="view_entradas_salidas" data-field="x_fecha_vencimiento" data-hidden="1" data-old name="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_fecha_vencimiento" id="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_fecha_vencimiento" value="<?= HtmlEncode($Grid->fecha_vencimiento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <td data-name="cantidad_articulo"<?= $Grid->cantidad_articulo->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_entradas_salidas_cantidad_articulo" class="el_view_entradas_salidas_cantidad_articulo">
<input type="<?= $Grid->cantidad_articulo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_cantidad_articulo" id="x<?= $Grid->RowIndex ?>_cantidad_articulo" data-table="view_entradas_salidas" data-field="x_cantidad_articulo" value="<?= $Grid->cantidad_articulo->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->cantidad_articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->cantidad_articulo->formatPattern()) ?>"<?= $Grid->cantidad_articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cantidad_articulo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_cantidad_articulo" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_cantidad_articulo" id="o<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_entradas_salidas_cantidad_articulo" class="el_view_entradas_salidas_cantidad_articulo">
<input type="<?= $Grid->cantidad_articulo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_cantidad_articulo" id="x<?= $Grid->RowIndex ?>_cantidad_articulo" data-table="view_entradas_salidas" data-field="x_cantidad_articulo" value="<?= $Grid->cantidad_articulo->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->cantidad_articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->cantidad_articulo->formatPattern()) ?>"<?= $Grid->cantidad_articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cantidad_articulo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_entradas_salidas_cantidad_articulo" class="el_view_entradas_salidas_cantidad_articulo">
<span<?= $Grid->cantidad_articulo->viewAttributes() ?>>
<?= $Grid->cantidad_articulo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_cantidad_articulo" data-hidden="1" name="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_cantidad_articulo" id="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->FormValue) ?>">
<input type="hidden" data-table="view_entradas_salidas" data-field="x_cantidad_articulo" data-hidden="1" data-old name="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_cantidad_articulo" id="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->articulo_unidad_medida->Visible) { // articulo_unidad_medida ?>
        <td data-name="articulo_unidad_medida"<?= $Grid->articulo_unidad_medida->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_entradas_salidas_articulo_unidad_medida" class="el_view_entradas_salidas_articulo_unidad_medida">
<?php
if (IsRTL()) {
    $Grid->articulo_unidad_medida->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x<?= $Grid->RowIndex ?>_articulo_unidad_medida" class="ew-auto-suggest">
    <input type="<?= $Grid->articulo_unidad_medida->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_articulo_unidad_medida" id="sv_x<?= $Grid->RowIndex ?>_articulo_unidad_medida" value="<?= RemoveHtml($Grid->articulo_unidad_medida->EditValue) ?>" autocomplete="off" size="30" maxlength="6" placeholder="<?= HtmlEncode($Grid->articulo_unidad_medida->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->articulo_unidad_medida->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->articulo_unidad_medida->formatPattern()) ?>"<?= $Grid->articulo_unidad_medida->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="view_entradas_salidas" data-field="x_articulo_unidad_medida" data-input="sv_x<?= $Grid->RowIndex ?>_articulo_unidad_medida" data-value-separator="<?= $Grid->articulo_unidad_medida->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_articulo_unidad_medida" id="x<?= $Grid->RowIndex ?>_articulo_unidad_medida" value="<?= HtmlEncode($Grid->articulo_unidad_medida->CurrentValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Grid->articulo_unidad_medida->getErrorMessage() ?></div>
<script>
loadjs.ready("fview_entradas_salidasgrid", function() {
    fview_entradas_salidasgrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_articulo_unidad_medida","forceSelect":false}, { lookupAllDisplayFields: <?= $Grid->articulo_unidad_medida->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.view_entradas_salidas.fields.articulo_unidad_medida.autoSuggestOptions));
});
</script>
<?= $Grid->articulo_unidad_medida->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo_unidad_medida") ?>
</span>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_articulo_unidad_medida" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_articulo_unidad_medida" id="o<?= $Grid->RowIndex ?>_articulo_unidad_medida" value="<?= HtmlEncode($Grid->articulo_unidad_medida->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_entradas_salidas_articulo_unidad_medida" class="el_view_entradas_salidas_articulo_unidad_medida">
<?php
if (IsRTL()) {
    $Grid->articulo_unidad_medida->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x<?= $Grid->RowIndex ?>_articulo_unidad_medida" class="ew-auto-suggest">
    <input type="<?= $Grid->articulo_unidad_medida->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_articulo_unidad_medida" id="sv_x<?= $Grid->RowIndex ?>_articulo_unidad_medida" value="<?= RemoveHtml($Grid->articulo_unidad_medida->EditValue) ?>" autocomplete="off" size="30" maxlength="6" placeholder="<?= HtmlEncode($Grid->articulo_unidad_medida->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->articulo_unidad_medida->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->articulo_unidad_medida->formatPattern()) ?>"<?= $Grid->articulo_unidad_medida->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="view_entradas_salidas" data-field="x_articulo_unidad_medida" data-input="sv_x<?= $Grid->RowIndex ?>_articulo_unidad_medida" data-value-separator="<?= $Grid->articulo_unidad_medida->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_articulo_unidad_medida" id="x<?= $Grid->RowIndex ?>_articulo_unidad_medida" value="<?= HtmlEncode($Grid->articulo_unidad_medida->CurrentValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Grid->articulo_unidad_medida->getErrorMessage() ?></div>
<script>
loadjs.ready("fview_entradas_salidasgrid", function() {
    fview_entradas_salidasgrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_articulo_unidad_medida","forceSelect":false}, { lookupAllDisplayFields: <?= $Grid->articulo_unidad_medida->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.view_entradas_salidas.fields.articulo_unidad_medida.autoSuggestOptions));
});
</script>
<?= $Grid->articulo_unidad_medida->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo_unidad_medida") ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_entradas_salidas_articulo_unidad_medida" class="el_view_entradas_salidas_articulo_unidad_medida">
<span<?= $Grid->articulo_unidad_medida->viewAttributes() ?>>
<?= $Grid->articulo_unidad_medida->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_articulo_unidad_medida" data-hidden="1" name="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_articulo_unidad_medida" id="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_articulo_unidad_medida" value="<?= HtmlEncode($Grid->articulo_unidad_medida->FormValue) ?>">
<input type="hidden" data-table="view_entradas_salidas" data-field="x_articulo_unidad_medida" data-hidden="1" data-old name="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_articulo_unidad_medida" id="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_articulo_unidad_medida" value="<?= HtmlEncode($Grid->articulo_unidad_medida->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->packer_cantidad->Visible) { // packer_cantidad ?>
        <td data-name="packer_cantidad"<?= $Grid->packer_cantidad->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_entradas_salidas_packer_cantidad" class="el_view_entradas_salidas_packer_cantidad">
<input type="<?= $Grid->packer_cantidad->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_packer_cantidad" id="x<?= $Grid->RowIndex ?>_packer_cantidad" data-table="view_entradas_salidas" data-field="x_packer_cantidad" value="<?= $Grid->packer_cantidad->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->packer_cantidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->packer_cantidad->formatPattern()) ?>"<?= $Grid->packer_cantidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->packer_cantidad->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_packer_cantidad" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_packer_cantidad" id="o<?= $Grid->RowIndex ?>_packer_cantidad" value="<?= HtmlEncode($Grid->packer_cantidad->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_entradas_salidas_packer_cantidad" class="el_view_entradas_salidas_packer_cantidad">
<input type="<?= $Grid->packer_cantidad->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_packer_cantidad" id="x<?= $Grid->RowIndex ?>_packer_cantidad" data-table="view_entradas_salidas" data-field="x_packer_cantidad" value="<?= $Grid->packer_cantidad->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->packer_cantidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->packer_cantidad->formatPattern()) ?>"<?= $Grid->packer_cantidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->packer_cantidad->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_entradas_salidas_packer_cantidad" class="el_view_entradas_salidas_packer_cantidad">
<span<?= $Grid->packer_cantidad->viewAttributes() ?>>
<?= $Grid->packer_cantidad->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_packer_cantidad" data-hidden="1" name="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_packer_cantidad" id="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_packer_cantidad" value="<?= HtmlEncode($Grid->packer_cantidad->FormValue) ?>">
<input type="hidden" data-table="view_entradas_salidas" data-field="x_packer_cantidad" data-hidden="1" data-old name="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_packer_cantidad" id="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_packer_cantidad" value="<?= HtmlEncode($Grid->packer_cantidad->OldValue) ?>">
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
loadjs.ready(["fview_entradas_salidasgrid","load"], () => fview_entradas_salidasgrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="fview_entradas_salidasgrid">
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
    ew.addEventHandlers("view_entradas_salidas");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
