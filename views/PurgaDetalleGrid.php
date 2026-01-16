<?php

namespace PHPMaker2024\mandrake;

// Set up and run Grid object
$Grid = Container("PurgaDetalleGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fpurga_detallegrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { purga_detalle: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpurga_detallegrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null], fields.fabricante.isInvalid],
            ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null], fields.articulo.isInvalid],
            ["almacen", [fields.almacen.visible && fields.almacen.required ? ew.Validators.required(fields.almacen.caption) : null], fields.almacen.isInvalid],
            ["lote", [fields.lote.visible && fields.lote.required ? ew.Validators.required(fields.lote.caption) : null], fields.lote.isInvalid],
            ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(fields.fecha.clientFormatPattern)], fields.fecha.isInvalid],
            ["cantidad_articulo", [fields.cantidad_articulo.visible && fields.cantidad_articulo.required ? ew.Validators.required(fields.cantidad_articulo.caption) : null, ew.Validators.float], fields.cantidad_articulo.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["fabricante",false],["articulo",false],["almacen",false],["lote",false],["fecha",false],["cantidad_articulo",false]];
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
<div id="fpurga_detallegrid" class="ew-form ew-list-form">
<div id="gmp_purga_detalle" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_purga_detallegrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
        <th data-name="fabricante" class="<?= $Grid->fabricante->headerCellClass() ?>"><div id="elh_purga_detalle_fabricante" class="purga_detalle_fabricante"><?= $Grid->renderFieldHeader($Grid->fabricante) ?></div></th>
<?php } ?>
<?php if ($Grid->articulo->Visible) { // articulo ?>
        <th data-name="articulo" class="<?= $Grid->articulo->headerCellClass() ?>"><div id="elh_purga_detalle_articulo" class="purga_detalle_articulo"><?= $Grid->renderFieldHeader($Grid->articulo) ?></div></th>
<?php } ?>
<?php if ($Grid->almacen->Visible) { // almacen ?>
        <th data-name="almacen" class="<?= $Grid->almacen->headerCellClass() ?>"><div id="elh_purga_detalle_almacen" class="purga_detalle_almacen"><?= $Grid->renderFieldHeader($Grid->almacen) ?></div></th>
<?php } ?>
<?php if ($Grid->lote->Visible) { // lote ?>
        <th data-name="lote" class="<?= $Grid->lote->headerCellClass() ?>"><div id="elh_purga_detalle_lote" class="purga_detalle_lote"><?= $Grid->renderFieldHeader($Grid->lote) ?></div></th>
<?php } ?>
<?php if ($Grid->fecha->Visible) { // fecha ?>
        <th data-name="fecha" class="<?= $Grid->fecha->headerCellClass() ?>"><div id="elh_purga_detalle_fecha" class="purga_detalle_fecha"><?= $Grid->renderFieldHeader($Grid->fecha) ?></div></th>
<?php } ?>
<?php if ($Grid->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <th data-name="cantidad_articulo" class="<?= $Grid->cantidad_articulo->headerCellClass() ?>"><div id="elh_purga_detalle_cantidad_articulo" class="purga_detalle_cantidad_articulo"><?= $Grid->renderFieldHeader($Grid->cantidad_articulo) ?></div></th>
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
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_purga_detalle_fabricante" class="el_purga_detalle_fabricante">
    <select
        id="x<?= $Grid->RowIndex ?>_fabricante"
        name="x<?= $Grid->RowIndex ?>_fabricante"
        class="form-control ew-select<?= $Grid->fabricante->isInvalidClass() ?>"
        data-select2-id="fpurga_detallegrid_x<?= $Grid->RowIndex ?>_fabricante"
        data-table="purga_detalle"
        data-field="x_fabricante"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->fabricante->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->fabricante->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>"
        data-ew-action="update-options"
        <?= $Grid->fabricante->editAttributes() ?>>
        <?= $Grid->fabricante->selectOptionListHtml("x{$Grid->RowIndex}_fabricante") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->fabricante->getErrorMessage() ?></div>
<?= $Grid->fabricante->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_fabricante") ?>
<script>
loadjs.ready("fpurga_detallegrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_fabricante", selectId: "fpurga_detallegrid_x<?= $Grid->RowIndex ?>_fabricante" };
    if (fpurga_detallegrid.lists.fabricante?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_fabricante", form: "fpurga_detallegrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_fabricante", form: "fpurga_detallegrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.purga_detalle.fields.fabricante.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<input type="hidden" data-table="purga_detalle" data-field="x_fabricante" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_fabricante" id="o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_purga_detalle_fabricante" class="el_purga_detalle_fabricante">
<span<?= $Grid->fabricante->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->fabricante->getDisplayValue($Grid->fabricante->EditValue) ?></span></span>
<input type="hidden" data-table="purga_detalle" data-field="x_fabricante" data-hidden="1" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_purga_detalle_fabricante" class="el_purga_detalle_fabricante">
<span<?= $Grid->fabricante->viewAttributes() ?>>
<?= $Grid->fabricante->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="purga_detalle" data-field="x_fabricante" data-hidden="1" name="fpurga_detallegrid$x<?= $Grid->RowIndex ?>_fabricante" id="fpurga_detallegrid$x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->FormValue) ?>">
<input type="hidden" data-table="purga_detalle" data-field="x_fabricante" data-hidden="1" data-old name="fpurga_detallegrid$o<?= $Grid->RowIndex ?>_fabricante" id="fpurga_detallegrid$o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->articulo->Visible) { // articulo ?>
        <td data-name="articulo"<?= $Grid->articulo->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_purga_detalle_articulo" class="el_purga_detalle_articulo">
    <select
        id="x<?= $Grid->RowIndex ?>_articulo"
        name="x<?= $Grid->RowIndex ?>_articulo"
        class="form-control ew-select<?= $Grid->articulo->isInvalidClass() ?>"
        data-select2-id="fpurga_detallegrid_x<?= $Grid->RowIndex ?>_articulo"
        data-table="purga_detalle"
        data-field="x_articulo"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->articulo->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->articulo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>"
        <?= $Grid->articulo->editAttributes() ?>>
        <?= $Grid->articulo->selectOptionListHtml("x{$Grid->RowIndex}_articulo") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->articulo->getErrorMessage() ?></div>
<?= $Grid->articulo->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo") ?>
<script>
loadjs.ready("fpurga_detallegrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_articulo", selectId: "fpurga_detallegrid_x<?= $Grid->RowIndex ?>_articulo" };
    if (fpurga_detallegrid.lists.articulo?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_articulo", form: "fpurga_detallegrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_articulo", form: "fpurga_detallegrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.purga_detalle.fields.articulo.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<input type="hidden" data-table="purga_detalle" data-field="x_articulo" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_articulo" id="o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_purga_detalle_articulo" class="el_purga_detalle_articulo">
<span<?= $Grid->articulo->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->articulo->getDisplayValue($Grid->articulo->EditValue) ?></span></span>
<input type="hidden" data-table="purga_detalle" data-field="x_articulo" data-hidden="1" name="x<?= $Grid->RowIndex ?>_articulo" id="x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_purga_detalle_articulo" class="el_purga_detalle_articulo">
<span<?= $Grid->articulo->viewAttributes() ?>>
<?= $Grid->articulo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="purga_detalle" data-field="x_articulo" data-hidden="1" name="fpurga_detallegrid$x<?= $Grid->RowIndex ?>_articulo" id="fpurga_detallegrid$x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->FormValue) ?>">
<input type="hidden" data-table="purga_detalle" data-field="x_articulo" data-hidden="1" data-old name="fpurga_detallegrid$o<?= $Grid->RowIndex ?>_articulo" id="fpurga_detallegrid$o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->almacen->Visible) { // almacen ?>
        <td data-name="almacen"<?= $Grid->almacen->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_purga_detalle_almacen" class="el_purga_detalle_almacen">
    <select
        id="x<?= $Grid->RowIndex ?>_almacen"
        name="x<?= $Grid->RowIndex ?>_almacen"
        class="form-control ew-select<?= $Grid->almacen->isInvalidClass() ?>"
        data-select2-id="fpurga_detallegrid_x<?= $Grid->RowIndex ?>_almacen"
        data-table="purga_detalle"
        data-field="x_almacen"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->almacen->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->almacen->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->almacen->getPlaceHolder()) ?>"
        <?= $Grid->almacen->editAttributes() ?>>
        <?= $Grid->almacen->selectOptionListHtml("x{$Grid->RowIndex}_almacen") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->almacen->getErrorMessage() ?></div>
<?= $Grid->almacen->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_almacen") ?>
<script>
loadjs.ready("fpurga_detallegrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_almacen", selectId: "fpurga_detallegrid_x<?= $Grid->RowIndex ?>_almacen" };
    if (fpurga_detallegrid.lists.almacen?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_almacen", form: "fpurga_detallegrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_almacen", form: "fpurga_detallegrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.purga_detalle.fields.almacen.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<input type="hidden" data-table="purga_detalle" data-field="x_almacen" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_almacen" id="o<?= $Grid->RowIndex ?>_almacen" value="<?= HtmlEncode($Grid->almacen->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_purga_detalle_almacen" class="el_purga_detalle_almacen">
    <select
        id="x<?= $Grid->RowIndex ?>_almacen"
        name="x<?= $Grid->RowIndex ?>_almacen"
        class="form-control ew-select<?= $Grid->almacen->isInvalidClass() ?>"
        data-select2-id="fpurga_detallegrid_x<?= $Grid->RowIndex ?>_almacen"
        data-table="purga_detalle"
        data-field="x_almacen"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->almacen->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->almacen->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->almacen->getPlaceHolder()) ?>"
        <?= $Grid->almacen->editAttributes() ?>>
        <?= $Grid->almacen->selectOptionListHtml("x{$Grid->RowIndex}_almacen") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->almacen->getErrorMessage() ?></div>
<?= $Grid->almacen->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_almacen") ?>
<script>
loadjs.ready("fpurga_detallegrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_almacen", selectId: "fpurga_detallegrid_x<?= $Grid->RowIndex ?>_almacen" };
    if (fpurga_detallegrid.lists.almacen?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_almacen", form: "fpurga_detallegrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_almacen", form: "fpurga_detallegrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.purga_detalle.fields.almacen.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_purga_detalle_almacen" class="el_purga_detalle_almacen">
<span<?= $Grid->almacen->viewAttributes() ?>>
<?= $Grid->almacen->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="purga_detalle" data-field="x_almacen" data-hidden="1" name="fpurga_detallegrid$x<?= $Grid->RowIndex ?>_almacen" id="fpurga_detallegrid$x<?= $Grid->RowIndex ?>_almacen" value="<?= HtmlEncode($Grid->almacen->FormValue) ?>">
<input type="hidden" data-table="purga_detalle" data-field="x_almacen" data-hidden="1" data-old name="fpurga_detallegrid$o<?= $Grid->RowIndex ?>_almacen" id="fpurga_detallegrid$o<?= $Grid->RowIndex ?>_almacen" value="<?= HtmlEncode($Grid->almacen->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->lote->Visible) { // lote ?>
        <td data-name="lote"<?= $Grid->lote->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_purga_detalle_lote" class="el_purga_detalle_lote">
<input type="<?= $Grid->lote->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_lote" id="x<?= $Grid->RowIndex ?>_lote" data-table="purga_detalle" data-field="x_lote" value="<?= $Grid->lote->EditValue ?>" size="10" maxlength="20" placeholder="<?= HtmlEncode($Grid->lote->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->lote->formatPattern()) ?>"<?= $Grid->lote->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->lote->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="purga_detalle" data-field="x_lote" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_lote" id="o<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_purga_detalle_lote" class="el_purga_detalle_lote">
<input type="<?= $Grid->lote->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_lote" id="x<?= $Grid->RowIndex ?>_lote" data-table="purga_detalle" data-field="x_lote" value="<?= $Grid->lote->EditValue ?>" size="10" maxlength="20" placeholder="<?= HtmlEncode($Grid->lote->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->lote->formatPattern()) ?>"<?= $Grid->lote->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->lote->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_purga_detalle_lote" class="el_purga_detalle_lote">
<span<?= $Grid->lote->viewAttributes() ?>>
<?= $Grid->lote->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="purga_detalle" data-field="x_lote" data-hidden="1" name="fpurga_detallegrid$x<?= $Grid->RowIndex ?>_lote" id="fpurga_detallegrid$x<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->FormValue) ?>">
<input type="hidden" data-table="purga_detalle" data-field="x_lote" data-hidden="1" data-old name="fpurga_detallegrid$o<?= $Grid->RowIndex ?>_lote" id="fpurga_detallegrid$o<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->fecha->Visible) { // fecha ?>
        <td data-name="fecha"<?= $Grid->fecha->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_purga_detalle_fecha" class="el_purga_detalle_fecha">
<input type="<?= $Grid->fecha->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_fecha" id="x<?= $Grid->RowIndex ?>_fecha" data-table="purga_detalle" data-field="x_fecha" value="<?= $Grid->fecha->EditValue ?>" size="10" maxlength="10" placeholder="<?= HtmlEncode($Grid->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fecha->formatPattern()) ?>"<?= $Grid->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha->getErrorMessage() ?></div>
<?php if (!$Grid->fecha->ReadOnly && !$Grid->fecha->Disabled && !isset($Grid->fecha->EditAttrs["readonly"]) && !isset($Grid->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpurga_detallegrid", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fpurga_detallegrid", "x<?= $Grid->RowIndex ?>_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="purga_detalle" data-field="x_fecha" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_fecha" id="o<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_purga_detalle_fecha" class="el_purga_detalle_fecha">
<input type="<?= $Grid->fecha->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_fecha" id="x<?= $Grid->RowIndex ?>_fecha" data-table="purga_detalle" data-field="x_fecha" value="<?= $Grid->fecha->EditValue ?>" size="10" maxlength="10" placeholder="<?= HtmlEncode($Grid->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fecha->formatPattern()) ?>"<?= $Grid->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha->getErrorMessage() ?></div>
<?php if (!$Grid->fecha->ReadOnly && !$Grid->fecha->Disabled && !isset($Grid->fecha->EditAttrs["readonly"]) && !isset($Grid->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpurga_detallegrid", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fpurga_detallegrid", "x<?= $Grid->RowIndex ?>_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_purga_detalle_fecha" class="el_purga_detalle_fecha">
<span<?= $Grid->fecha->viewAttributes() ?>>
<?= $Grid->fecha->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="purga_detalle" data-field="x_fecha" data-hidden="1" name="fpurga_detallegrid$x<?= $Grid->RowIndex ?>_fecha" id="fpurga_detallegrid$x<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->FormValue) ?>">
<input type="hidden" data-table="purga_detalle" data-field="x_fecha" data-hidden="1" data-old name="fpurga_detallegrid$o<?= $Grid->RowIndex ?>_fecha" id="fpurga_detallegrid$o<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <td data-name="cantidad_articulo"<?= $Grid->cantidad_articulo->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_purga_detalle_cantidad_articulo" class="el_purga_detalle_cantidad_articulo">
<input type="<?= $Grid->cantidad_articulo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_cantidad_articulo" id="x<?= $Grid->RowIndex ?>_cantidad_articulo" data-table="purga_detalle" data-field="x_cantidad_articulo" value="<?= $Grid->cantidad_articulo->EditValue ?>" size="6" placeholder="<?= HtmlEncode($Grid->cantidad_articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->cantidad_articulo->formatPattern()) ?>"<?= $Grid->cantidad_articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cantidad_articulo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="purga_detalle" data-field="x_cantidad_articulo" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_cantidad_articulo" id="o<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_purga_detalle_cantidad_articulo" class="el_purga_detalle_cantidad_articulo">
<input type="<?= $Grid->cantidad_articulo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_cantidad_articulo" id="x<?= $Grid->RowIndex ?>_cantidad_articulo" data-table="purga_detalle" data-field="x_cantidad_articulo" value="<?= $Grid->cantidad_articulo->EditValue ?>" size="6" placeholder="<?= HtmlEncode($Grid->cantidad_articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->cantidad_articulo->formatPattern()) ?>"<?= $Grid->cantidad_articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cantidad_articulo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_purga_detalle_cantidad_articulo" class="el_purga_detalle_cantidad_articulo">
<span<?= $Grid->cantidad_articulo->viewAttributes() ?>>
<?= $Grid->cantidad_articulo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="purga_detalle" data-field="x_cantidad_articulo" data-hidden="1" name="fpurga_detallegrid$x<?= $Grid->RowIndex ?>_cantidad_articulo" id="fpurga_detallegrid$x<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->FormValue) ?>">
<input type="hidden" data-table="purga_detalle" data-field="x_cantidad_articulo" data-hidden="1" data-old name="fpurga_detallegrid$o<?= $Grid->RowIndex ?>_cantidad_articulo" id="fpurga_detallegrid$o<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->OldValue) ?>">
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
loadjs.ready(["fpurga_detallegrid","load"], () => fpurga_detallegrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="fpurga_detallegrid">
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
    ew.addEventHandlers("purga_detalle");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here, no need to add script tags.
    /*
    $("#btnCerrar").click(function() {
    });
    $("#btnAceptar").click(function() {
    	var xuser = $("#xusername").val();
    	var xpass = $("#xpassword").val();
    	var idPurga = $("#idPurga").val(); 
    	var usercaja = "<?php echo CurrentUserName(); ?>";
    	var almacen = $("#AlmacenOrigen").val();
    	$.ajax({
    	  url : "include/Validar_Usuario.php",
    	  type: "GET",
    	  data : {usernama: xuser, password: xpass, idPurga: idPurga, usercaja: usercaja},
    	  beforeSend: function(){
    	  }
    	})
    	.done(function(MyResult) {
    		if(MyResult == "S") {
    			Procesar(idPurga, almacen);
    		}
    		else {
    			alert("!!! NO AUTORIZADO !!!");
    		}
    		$('#ventanaModal').modal('hide');
    		$("#xusername").val("");
    		$("#xpassword").val("");
    	})
    	.fail(function(data) {
    		alert( "error" + data );
    	})
    	.always(function(data) {
    	});
    });
    */
});
</script>
<?php } ?>
