<?php

namespace PHPMaker2024\mandrake;

// Set up and run Grid object
$Grid = Container("ViewOutGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fview_outgrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { view_out: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fview_outgrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null, ew.Validators.integer], fields.fabricante.isInvalid],
            ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null], fields.articulo.isInvalid],
            ["lote", [fields.lote.visible && fields.lote.required ? ew.Validators.required(fields.lote.caption) : null], fields.lote.isInvalid],
            ["fecha_vencimiento", [fields.fecha_vencimiento.visible && fields.fecha_vencimiento.required ? ew.Validators.required(fields.fecha_vencimiento.caption) : null, ew.Validators.datetime(fields.fecha_vencimiento.clientFormatPattern)], fields.fecha_vencimiento.isInvalid],
            ["almacen", [fields.almacen.visible && fields.almacen.required ? ew.Validators.required(fields.almacen.caption) : null], fields.almacen.isInvalid],
            ["cantidad_articulo", [fields.cantidad_articulo.visible && fields.cantidad_articulo.required ? ew.Validators.required(fields.cantidad_articulo.caption) : null, ew.Validators.float], fields.cantidad_articulo.isInvalid],
            ["precio_unidad", [fields.precio_unidad.visible && fields.precio_unidad.required ? ew.Validators.required(fields.precio_unidad.caption) : null, ew.Validators.float], fields.precio_unidad.isInvalid],
            ["precio", [fields.precio.visible && fields.precio.required ? ew.Validators.required(fields.precio.caption) : null, ew.Validators.float], fields.precio.isInvalid],
            ["alicuota", [fields.alicuota.visible && fields.alicuota.required ? ew.Validators.required(fields.alicuota.caption) : null, ew.Validators.float], fields.alicuota.isInvalid],
            ["descuento", [fields.descuento.visible && fields.descuento.required ? ew.Validators.required(fields.descuento.caption) : null, ew.Validators.float], fields.descuento.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["fabricante",false],["articulo",false],["lote",false],["fecha_vencimiento",false],["almacen",false],["cantidad_articulo",false],["precio_unidad",false],["precio",false],["alicuota",false],["descuento",false]];
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
            "almacen": <?= $Grid->almacen->toClientList($Grid) ?>,
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
<div id="fview_outgrid" class="ew-form ew-list-form">
<div id="gmp_view_out" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_view_outgrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
        <th data-name="fabricante" class="<?= $Grid->fabricante->headerCellClass() ?>"><div id="elh_view_out_fabricante" class="view_out_fabricante"><?= $Grid->renderFieldHeader($Grid->fabricante) ?></div></th>
<?php } ?>
<?php if ($Grid->articulo->Visible) { // articulo ?>
        <th data-name="articulo" class="<?= $Grid->articulo->headerCellClass() ?>"><div id="elh_view_out_articulo" class="view_out_articulo"><?= $Grid->renderFieldHeader($Grid->articulo) ?></div></th>
<?php } ?>
<?php if ($Grid->lote->Visible) { // lote ?>
        <th data-name="lote" class="<?= $Grid->lote->headerCellClass() ?>"><div id="elh_view_out_lote" class="view_out_lote"><?= $Grid->renderFieldHeader($Grid->lote) ?></div></th>
<?php } ?>
<?php if ($Grid->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
        <th data-name="fecha_vencimiento" class="<?= $Grid->fecha_vencimiento->headerCellClass() ?>"><div id="elh_view_out_fecha_vencimiento" class="view_out_fecha_vencimiento"><?= $Grid->renderFieldHeader($Grid->fecha_vencimiento) ?></div></th>
<?php } ?>
<?php if ($Grid->almacen->Visible) { // almacen ?>
        <th data-name="almacen" class="<?= $Grid->almacen->headerCellClass() ?>"><div id="elh_view_out_almacen" class="view_out_almacen"><?= $Grid->renderFieldHeader($Grid->almacen) ?></div></th>
<?php } ?>
<?php if ($Grid->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <th data-name="cantidad_articulo" class="<?= $Grid->cantidad_articulo->headerCellClass() ?>"><div id="elh_view_out_cantidad_articulo" class="view_out_cantidad_articulo"><?= $Grid->renderFieldHeader($Grid->cantidad_articulo) ?></div></th>
<?php } ?>
<?php if ($Grid->precio_unidad->Visible) { // precio_unidad ?>
        <th data-name="precio_unidad" class="<?= $Grid->precio_unidad->headerCellClass() ?>"><div id="elh_view_out_precio_unidad" class="view_out_precio_unidad"><?= $Grid->renderFieldHeader($Grid->precio_unidad) ?></div></th>
<?php } ?>
<?php if ($Grid->precio->Visible) { // precio ?>
        <th data-name="precio" class="<?= $Grid->precio->headerCellClass() ?>"><div id="elh_view_out_precio" class="view_out_precio"><?= $Grid->renderFieldHeader($Grid->precio) ?></div></th>
<?php } ?>
<?php if ($Grid->alicuota->Visible) { // alicuota ?>
        <th data-name="alicuota" class="<?= $Grid->alicuota->headerCellClass() ?>"><div id="elh_view_out_alicuota" class="view_out_alicuota"><?= $Grid->renderFieldHeader($Grid->alicuota) ?></div></th>
<?php } ?>
<?php if ($Grid->descuento->Visible) { // descuento ?>
        <th data-name="descuento" class="<?= $Grid->descuento->headerCellClass() ?>"><div id="elh_view_out_descuento" class="view_out_descuento"><?= $Grid->renderFieldHeader($Grid->descuento) ?></div></th>
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
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_fabricante" class="el_view_out_fabricante">
<?php
if (IsRTL()) {
    $Grid->fabricante->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x<?= $Grid->RowIndex ?>_fabricante" class="ew-auto-suggest">
    <input type="<?= $Grid->fabricante->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_fabricante" id="sv_x<?= $Grid->RowIndex ?>_fabricante" value="<?= RemoveHtml($Grid->fabricante->EditValue) ?>" autocomplete="off" size="30" placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fabricante->formatPattern()) ?>"<?= $Grid->fabricante->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="view_out" data-field="x_fabricante" data-input="sv_x<?= $Grid->RowIndex ?>_fabricante" data-value-separator="<?= $Grid->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->CurrentValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Grid->fabricante->getErrorMessage() ?></div>
<script>
loadjs.ready("fview_outgrid", function() {
    fview_outgrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_fabricante","forceSelect":false}, { lookupAllDisplayFields: <?= $Grid->fabricante->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.view_out.fields.fabricante.autoSuggestOptions));
});
</script>
<?= $Grid->fabricante->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_fabricante") ?>
</span>
<input type="hidden" data-table="view_out" data-field="x_fabricante" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_fabricante" id="o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_fabricante" class="el_view_out_fabricante">
<?php
if (IsRTL()) {
    $Grid->fabricante->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x<?= $Grid->RowIndex ?>_fabricante" class="ew-auto-suggest">
    <input type="<?= $Grid->fabricante->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_fabricante" id="sv_x<?= $Grid->RowIndex ?>_fabricante" value="<?= RemoveHtml($Grid->fabricante->EditValue) ?>" autocomplete="off" size="30" placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fabricante->formatPattern()) ?>"<?= $Grid->fabricante->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="view_out" data-field="x_fabricante" data-input="sv_x<?= $Grid->RowIndex ?>_fabricante" data-value-separator="<?= $Grid->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->CurrentValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Grid->fabricante->getErrorMessage() ?></div>
<script>
loadjs.ready("fview_outgrid", function() {
    fview_outgrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_fabricante","forceSelect":false}, { lookupAllDisplayFields: <?= $Grid->fabricante->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.view_out.fields.fabricante.autoSuggestOptions));
});
</script>
<?= $Grid->fabricante->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_fabricante") ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_fabricante" class="el_view_out_fabricante">
<span<?= $Grid->fabricante->viewAttributes() ?>>
<?= $Grid->fabricante->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_out" data-field="x_fabricante" data-hidden="1" name="fview_outgrid$x<?= $Grid->RowIndex ?>_fabricante" id="fview_outgrid$x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->FormValue) ?>">
<input type="hidden" data-table="view_out" data-field="x_fabricante" data-hidden="1" data-old name="fview_outgrid$o<?= $Grid->RowIndex ?>_fabricante" id="fview_outgrid$o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->articulo->Visible) { // articulo ?>
        <td data-name="articulo"<?= $Grid->articulo->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_articulo" class="el_view_out_articulo">
    <select
        id="x<?= $Grid->RowIndex ?>_articulo"
        name="x<?= $Grid->RowIndex ?>_articulo"
        class="form-select ew-select<?= $Grid->articulo->isInvalidClass() ?>"
        <?php if (!$Grid->articulo->IsNativeSelect) { ?>
        data-select2-id="fview_outgrid_x<?= $Grid->RowIndex ?>_articulo"
        <?php } ?>
        data-table="view_out"
        data-field="x_articulo"
        data-value-separator="<?= $Grid->articulo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>"
        <?= $Grid->articulo->editAttributes() ?>>
        <?= $Grid->articulo->selectOptionListHtml("x{$Grid->RowIndex}_articulo") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->articulo->getErrorMessage() ?></div>
<?= $Grid->articulo->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo") ?>
<?php if (!$Grid->articulo->IsNativeSelect) { ?>
<script>
loadjs.ready("fview_outgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_articulo", selectId: "fview_outgrid_x<?= $Grid->RowIndex ?>_articulo" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fview_outgrid.lists.articulo?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_articulo", form: "fview_outgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_articulo", form: "fview_outgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.view_out.fields.articulo.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="view_out" data-field="x_articulo" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_articulo" id="o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_articulo" class="el_view_out_articulo">
    <select
        id="x<?= $Grid->RowIndex ?>_articulo"
        name="x<?= $Grid->RowIndex ?>_articulo"
        class="form-select ew-select<?= $Grid->articulo->isInvalidClass() ?>"
        <?php if (!$Grid->articulo->IsNativeSelect) { ?>
        data-select2-id="fview_outgrid_x<?= $Grid->RowIndex ?>_articulo"
        <?php } ?>
        data-table="view_out"
        data-field="x_articulo"
        data-value-separator="<?= $Grid->articulo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>"
        <?= $Grid->articulo->editAttributes() ?>>
        <?= $Grid->articulo->selectOptionListHtml("x{$Grid->RowIndex}_articulo") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->articulo->getErrorMessage() ?></div>
<?= $Grid->articulo->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo") ?>
<?php if (!$Grid->articulo->IsNativeSelect) { ?>
<script>
loadjs.ready("fview_outgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_articulo", selectId: "fview_outgrid_x<?= $Grid->RowIndex ?>_articulo" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fview_outgrid.lists.articulo?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_articulo", form: "fview_outgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_articulo", form: "fview_outgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.view_out.fields.articulo.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_articulo" class="el_view_out_articulo">
<span<?= $Grid->articulo->viewAttributes() ?>>
<?= $Grid->articulo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_out" data-field="x_articulo" data-hidden="1" name="fview_outgrid$x<?= $Grid->RowIndex ?>_articulo" id="fview_outgrid$x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->FormValue) ?>">
<input type="hidden" data-table="view_out" data-field="x_articulo" data-hidden="1" data-old name="fview_outgrid$o<?= $Grid->RowIndex ?>_articulo" id="fview_outgrid$o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->lote->Visible) { // lote ?>
        <td data-name="lote"<?= $Grid->lote->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_lote" class="el_view_out_lote">
<input type="<?= $Grid->lote->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_lote" id="x<?= $Grid->RowIndex ?>_lote" data-table="view_out" data-field="x_lote" value="<?= $Grid->lote->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Grid->lote->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->lote->formatPattern()) ?>"<?= $Grid->lote->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->lote->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_out" data-field="x_lote" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_lote" id="o<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_lote" class="el_view_out_lote">
<input type="<?= $Grid->lote->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_lote" id="x<?= $Grid->RowIndex ?>_lote" data-table="view_out" data-field="x_lote" value="<?= $Grid->lote->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Grid->lote->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->lote->formatPattern()) ?>"<?= $Grid->lote->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->lote->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_lote" class="el_view_out_lote">
<span<?= $Grid->lote->viewAttributes() ?>>
<?= $Grid->lote->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_out" data-field="x_lote" data-hidden="1" name="fview_outgrid$x<?= $Grid->RowIndex ?>_lote" id="fview_outgrid$x<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->FormValue) ?>">
<input type="hidden" data-table="view_out" data-field="x_lote" data-hidden="1" data-old name="fview_outgrid$o<?= $Grid->RowIndex ?>_lote" id="fview_outgrid$o<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
        <td data-name="fecha_vencimiento"<?= $Grid->fecha_vencimiento->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_fecha_vencimiento" class="el_view_out_fecha_vencimiento">
<input type="<?= $Grid->fecha_vencimiento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_fecha_vencimiento" id="x<?= $Grid->RowIndex ?>_fecha_vencimiento" data-table="view_out" data-field="x_fecha_vencimiento" value="<?= $Grid->fecha_vencimiento->EditValue ?>" placeholder="<?= HtmlEncode($Grid->fecha_vencimiento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fecha_vencimiento->formatPattern()) ?>"<?= $Grid->fecha_vencimiento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha_vencimiento->getErrorMessage() ?></div>
<?php if (!$Grid->fecha_vencimiento->ReadOnly && !$Grid->fecha_vencimiento->Disabled && !isset($Grid->fecha_vencimiento->EditAttrs["readonly"]) && !isset($Grid->fecha_vencimiento->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fview_outgrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(7) ?>",
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
    ew.createDateTimePicker("fview_outgrid", "x<?= $Grid->RowIndex ?>_fecha_vencimiento", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="view_out" data-field="x_fecha_vencimiento" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_fecha_vencimiento" id="o<?= $Grid->RowIndex ?>_fecha_vencimiento" value="<?= HtmlEncode($Grid->fecha_vencimiento->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_fecha_vencimiento" class="el_view_out_fecha_vencimiento">
<input type="<?= $Grid->fecha_vencimiento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_fecha_vencimiento" id="x<?= $Grid->RowIndex ?>_fecha_vencimiento" data-table="view_out" data-field="x_fecha_vencimiento" value="<?= $Grid->fecha_vencimiento->EditValue ?>" placeholder="<?= HtmlEncode($Grid->fecha_vencimiento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fecha_vencimiento->formatPattern()) ?>"<?= $Grid->fecha_vencimiento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha_vencimiento->getErrorMessage() ?></div>
<?php if (!$Grid->fecha_vencimiento->ReadOnly && !$Grid->fecha_vencimiento->Disabled && !isset($Grid->fecha_vencimiento->EditAttrs["readonly"]) && !isset($Grid->fecha_vencimiento->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fview_outgrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(7) ?>",
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
    ew.createDateTimePicker("fview_outgrid", "x<?= $Grid->RowIndex ?>_fecha_vencimiento", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_fecha_vencimiento" class="el_view_out_fecha_vencimiento">
<span<?= $Grid->fecha_vencimiento->viewAttributes() ?>>
<?= $Grid->fecha_vencimiento->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_out" data-field="x_fecha_vencimiento" data-hidden="1" name="fview_outgrid$x<?= $Grid->RowIndex ?>_fecha_vencimiento" id="fview_outgrid$x<?= $Grid->RowIndex ?>_fecha_vencimiento" value="<?= HtmlEncode($Grid->fecha_vencimiento->FormValue) ?>">
<input type="hidden" data-table="view_out" data-field="x_fecha_vencimiento" data-hidden="1" data-old name="fview_outgrid$o<?= $Grid->RowIndex ?>_fecha_vencimiento" id="fview_outgrid$o<?= $Grid->RowIndex ?>_fecha_vencimiento" value="<?= HtmlEncode($Grid->fecha_vencimiento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->almacen->Visible) { // almacen ?>
        <td data-name="almacen"<?= $Grid->almacen->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_almacen" class="el_view_out_almacen">
    <select
        id="x<?= $Grid->RowIndex ?>_almacen"
        name="x<?= $Grid->RowIndex ?>_almacen"
        class="form-select ew-select<?= $Grid->almacen->isInvalidClass() ?>"
        <?php if (!$Grid->almacen->IsNativeSelect) { ?>
        data-select2-id="fview_outgrid_x<?= $Grid->RowIndex ?>_almacen"
        <?php } ?>
        data-table="view_out"
        data-field="x_almacen"
        data-value-separator="<?= $Grid->almacen->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->almacen->getPlaceHolder()) ?>"
        <?= $Grid->almacen->editAttributes() ?>>
        <?= $Grid->almacen->selectOptionListHtml("x{$Grid->RowIndex}_almacen") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->almacen->getErrorMessage() ?></div>
<?= $Grid->almacen->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_almacen") ?>
<?php if (!$Grid->almacen->IsNativeSelect) { ?>
<script>
loadjs.ready("fview_outgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_almacen", selectId: "fview_outgrid_x<?= $Grid->RowIndex ?>_almacen" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fview_outgrid.lists.almacen?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_almacen", form: "fview_outgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_almacen", form: "fview_outgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.view_out.fields.almacen.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="view_out" data-field="x_almacen" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_almacen" id="o<?= $Grid->RowIndex ?>_almacen" value="<?= HtmlEncode($Grid->almacen->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_almacen" class="el_view_out_almacen">
    <select
        id="x<?= $Grid->RowIndex ?>_almacen"
        name="x<?= $Grid->RowIndex ?>_almacen"
        class="form-select ew-select<?= $Grid->almacen->isInvalidClass() ?>"
        <?php if (!$Grid->almacen->IsNativeSelect) { ?>
        data-select2-id="fview_outgrid_x<?= $Grid->RowIndex ?>_almacen"
        <?php } ?>
        data-table="view_out"
        data-field="x_almacen"
        data-value-separator="<?= $Grid->almacen->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->almacen->getPlaceHolder()) ?>"
        <?= $Grid->almacen->editAttributes() ?>>
        <?= $Grid->almacen->selectOptionListHtml("x{$Grid->RowIndex}_almacen") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->almacen->getErrorMessage() ?></div>
<?= $Grid->almacen->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_almacen") ?>
<?php if (!$Grid->almacen->IsNativeSelect) { ?>
<script>
loadjs.ready("fview_outgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_almacen", selectId: "fview_outgrid_x<?= $Grid->RowIndex ?>_almacen" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fview_outgrid.lists.almacen?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_almacen", form: "fview_outgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_almacen", form: "fview_outgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.view_out.fields.almacen.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_almacen" class="el_view_out_almacen">
<span<?= $Grid->almacen->viewAttributes() ?>>
<?= $Grid->almacen->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_out" data-field="x_almacen" data-hidden="1" name="fview_outgrid$x<?= $Grid->RowIndex ?>_almacen" id="fview_outgrid$x<?= $Grid->RowIndex ?>_almacen" value="<?= HtmlEncode($Grid->almacen->FormValue) ?>">
<input type="hidden" data-table="view_out" data-field="x_almacen" data-hidden="1" data-old name="fview_outgrid$o<?= $Grid->RowIndex ?>_almacen" id="fview_outgrid$o<?= $Grid->RowIndex ?>_almacen" value="<?= HtmlEncode($Grid->almacen->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <td data-name="cantidad_articulo"<?= $Grid->cantidad_articulo->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_cantidad_articulo" class="el_view_out_cantidad_articulo">
<input type="<?= $Grid->cantidad_articulo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_cantidad_articulo" id="x<?= $Grid->RowIndex ?>_cantidad_articulo" data-table="view_out" data-field="x_cantidad_articulo" value="<?= $Grid->cantidad_articulo->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->cantidad_articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->cantidad_articulo->formatPattern()) ?>"<?= $Grid->cantidad_articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cantidad_articulo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_out" data-field="x_cantidad_articulo" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_cantidad_articulo" id="o<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_cantidad_articulo" class="el_view_out_cantidad_articulo">
<input type="<?= $Grid->cantidad_articulo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_cantidad_articulo" id="x<?= $Grid->RowIndex ?>_cantidad_articulo" data-table="view_out" data-field="x_cantidad_articulo" value="<?= $Grid->cantidad_articulo->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->cantidad_articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->cantidad_articulo->formatPattern()) ?>"<?= $Grid->cantidad_articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cantidad_articulo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_cantidad_articulo" class="el_view_out_cantidad_articulo">
<span<?= $Grid->cantidad_articulo->viewAttributes() ?>>
<?= $Grid->cantidad_articulo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_out" data-field="x_cantidad_articulo" data-hidden="1" name="fview_outgrid$x<?= $Grid->RowIndex ?>_cantidad_articulo" id="fview_outgrid$x<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->FormValue) ?>">
<input type="hidden" data-table="view_out" data-field="x_cantidad_articulo" data-hidden="1" data-old name="fview_outgrid$o<?= $Grid->RowIndex ?>_cantidad_articulo" id="fview_outgrid$o<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->precio_unidad->Visible) { // precio_unidad ?>
        <td data-name="precio_unidad"<?= $Grid->precio_unidad->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_precio_unidad" class="el_view_out_precio_unidad">
<input type="<?= $Grid->precio_unidad->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_precio_unidad" id="x<?= $Grid->RowIndex ?>_precio_unidad" data-table="view_out" data-field="x_precio_unidad" value="<?= $Grid->precio_unidad->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->precio_unidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->precio_unidad->formatPattern()) ?>"<?= $Grid->precio_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio_unidad->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_out" data-field="x_precio_unidad" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_precio_unidad" id="o<?= $Grid->RowIndex ?>_precio_unidad" value="<?= HtmlEncode($Grid->precio_unidad->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_precio_unidad" class="el_view_out_precio_unidad">
<input type="<?= $Grid->precio_unidad->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_precio_unidad" id="x<?= $Grid->RowIndex ?>_precio_unidad" data-table="view_out" data-field="x_precio_unidad" value="<?= $Grid->precio_unidad->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->precio_unidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->precio_unidad->formatPattern()) ?>"<?= $Grid->precio_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio_unidad->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_precio_unidad" class="el_view_out_precio_unidad">
<span<?= $Grid->precio_unidad->viewAttributes() ?>>
<?= $Grid->precio_unidad->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_out" data-field="x_precio_unidad" data-hidden="1" name="fview_outgrid$x<?= $Grid->RowIndex ?>_precio_unidad" id="fview_outgrid$x<?= $Grid->RowIndex ?>_precio_unidad" value="<?= HtmlEncode($Grid->precio_unidad->FormValue) ?>">
<input type="hidden" data-table="view_out" data-field="x_precio_unidad" data-hidden="1" data-old name="fview_outgrid$o<?= $Grid->RowIndex ?>_precio_unidad" id="fview_outgrid$o<?= $Grid->RowIndex ?>_precio_unidad" value="<?= HtmlEncode($Grid->precio_unidad->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->precio->Visible) { // precio ?>
        <td data-name="precio"<?= $Grid->precio->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_precio" class="el_view_out_precio">
<input type="<?= $Grid->precio->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_precio" id="x<?= $Grid->RowIndex ?>_precio" data-table="view_out" data-field="x_precio" value="<?= $Grid->precio->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->precio->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->precio->formatPattern()) ?>"<?= $Grid->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_out" data-field="x_precio" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_precio" id="o<?= $Grid->RowIndex ?>_precio" value="<?= HtmlEncode($Grid->precio->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_precio" class="el_view_out_precio">
<input type="<?= $Grid->precio->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_precio" id="x<?= $Grid->RowIndex ?>_precio" data-table="view_out" data-field="x_precio" value="<?= $Grid->precio->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->precio->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->precio->formatPattern()) ?>"<?= $Grid->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_precio" class="el_view_out_precio">
<span<?= $Grid->precio->viewAttributes() ?>>
<?= $Grid->precio->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_out" data-field="x_precio" data-hidden="1" name="fview_outgrid$x<?= $Grid->RowIndex ?>_precio" id="fview_outgrid$x<?= $Grid->RowIndex ?>_precio" value="<?= HtmlEncode($Grid->precio->FormValue) ?>">
<input type="hidden" data-table="view_out" data-field="x_precio" data-hidden="1" data-old name="fview_outgrid$o<?= $Grid->RowIndex ?>_precio" id="fview_outgrid$o<?= $Grid->RowIndex ?>_precio" value="<?= HtmlEncode($Grid->precio->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->alicuota->Visible) { // alicuota ?>
        <td data-name="alicuota"<?= $Grid->alicuota->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_alicuota" class="el_view_out_alicuota">
<input type="<?= $Grid->alicuota->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_alicuota" id="x<?= $Grid->RowIndex ?>_alicuota" data-table="view_out" data-field="x_alicuota" value="<?= $Grid->alicuota->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->alicuota->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->alicuota->formatPattern()) ?>"<?= $Grid->alicuota->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->alicuota->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_out" data-field="x_alicuota" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_alicuota" id="o<?= $Grid->RowIndex ?>_alicuota" value="<?= HtmlEncode($Grid->alicuota->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_alicuota" class="el_view_out_alicuota">
<input type="<?= $Grid->alicuota->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_alicuota" id="x<?= $Grid->RowIndex ?>_alicuota" data-table="view_out" data-field="x_alicuota" value="<?= $Grid->alicuota->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->alicuota->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->alicuota->formatPattern()) ?>"<?= $Grid->alicuota->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->alicuota->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_alicuota" class="el_view_out_alicuota">
<span<?= $Grid->alicuota->viewAttributes() ?>>
<?= $Grid->alicuota->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_out" data-field="x_alicuota" data-hidden="1" name="fview_outgrid$x<?= $Grid->RowIndex ?>_alicuota" id="fview_outgrid$x<?= $Grid->RowIndex ?>_alicuota" value="<?= HtmlEncode($Grid->alicuota->FormValue) ?>">
<input type="hidden" data-table="view_out" data-field="x_alicuota" data-hidden="1" data-old name="fview_outgrid$o<?= $Grid->RowIndex ?>_alicuota" id="fview_outgrid$o<?= $Grid->RowIndex ?>_alicuota" value="<?= HtmlEncode($Grid->alicuota->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->descuento->Visible) { // descuento ?>
        <td data-name="descuento"<?= $Grid->descuento->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_descuento" class="el_view_out_descuento">
<input type="<?= $Grid->descuento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_descuento" id="x<?= $Grid->RowIndex ?>_descuento" data-table="view_out" data-field="x_descuento" value="<?= $Grid->descuento->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->descuento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->descuento->formatPattern()) ?>"<?= $Grid->descuento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->descuento->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_out" data-field="x_descuento" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_descuento" id="o<?= $Grid->RowIndex ?>_descuento" value="<?= HtmlEncode($Grid->descuento->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_descuento" class="el_view_out_descuento">
<input type="<?= $Grid->descuento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_descuento" id="x<?= $Grid->RowIndex ?>_descuento" data-table="view_out" data-field="x_descuento" value="<?= $Grid->descuento->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->descuento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->descuento->formatPattern()) ?>"<?= $Grid->descuento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->descuento->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_out_descuento" class="el_view_out_descuento">
<span<?= $Grid->descuento->viewAttributes() ?>>
<?= $Grid->descuento->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_out" data-field="x_descuento" data-hidden="1" name="fview_outgrid$x<?= $Grid->RowIndex ?>_descuento" id="fview_outgrid$x<?= $Grid->RowIndex ?>_descuento" value="<?= HtmlEncode($Grid->descuento->FormValue) ?>">
<input type="hidden" data-table="view_out" data-field="x_descuento" data-hidden="1" data-old name="fview_outgrid$o<?= $Grid->RowIndex ?>_descuento" id="fview_outgrid$o<?= $Grid->RowIndex ?>_descuento" value="<?= HtmlEncode($Grid->descuento->OldValue) ?>">
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
loadjs.ready(["fview_outgrid","load"], () => fview_outgrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="fview_outgrid">
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
    ew.addEventHandlers("view_out");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
