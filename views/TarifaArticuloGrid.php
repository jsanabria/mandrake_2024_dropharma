<?php

namespace PHPMaker2024\mandrake;

// Set up and run Grid object
$Grid = Container("TarifaArticuloGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var ftarifa_articulogrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { tarifa_articulo: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftarifa_articulogrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["tarifa", [fields.tarifa.visible && fields.tarifa.required ? ew.Validators.required(fields.tarifa.caption) : null], fields.tarifa.isInvalid],
            ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null], fields.fabricante.isInvalid],
            ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null], fields.articulo.isInvalid],
            ["precio", [fields.precio.visible && fields.precio.required ? ew.Validators.required(fields.precio.caption) : null, ew.Validators.float], fields.precio.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["tarifa",false],["fabricante",false],["articulo",false],["precio",false]];
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
            "tarifa": <?= $Grid->tarifa->toClientList($Grid) ?>,
            "fabricante": <?= $Grid->fabricante->toClientList($Grid) ?>,
            "articulo": <?= $Grid->articulo->toClientList($Grid) ?>,
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
<div id="ftarifa_articulogrid" class="ew-form ew-list-form">
<div id="gmp_tarifa_articulo" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_tarifa_articulogrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Grid->tarifa->Visible) { // tarifa ?>
        <th data-name="tarifa" class="<?= $Grid->tarifa->headerCellClass() ?>"><div id="elh_tarifa_articulo_tarifa" class="tarifa_articulo_tarifa"><?= $Grid->renderFieldHeader($Grid->tarifa) ?></div></th>
<?php } ?>
<?php if ($Grid->fabricante->Visible) { // fabricante ?>
        <th data-name="fabricante" class="<?= $Grid->fabricante->headerCellClass() ?>"><div id="elh_tarifa_articulo_fabricante" class="tarifa_articulo_fabricante"><?= $Grid->renderFieldHeader($Grid->fabricante) ?></div></th>
<?php } ?>
<?php if ($Grid->articulo->Visible) { // articulo ?>
        <th data-name="articulo" class="<?= $Grid->articulo->headerCellClass() ?>"><div id="elh_tarifa_articulo_articulo" class="tarifa_articulo_articulo"><?= $Grid->renderFieldHeader($Grid->articulo) ?></div></th>
<?php } ?>
<?php if ($Grid->precio->Visible) { // precio ?>
        <th data-name="precio" class="<?= $Grid->precio->headerCellClass() ?>"><div id="elh_tarifa_articulo_precio" class="tarifa_articulo_precio"><?= $Grid->renderFieldHeader($Grid->precio) ?></div></th>
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
    <?php if ($Grid->tarifa->Visible) { // tarifa ?>
        <td data-name="tarifa"<?= $Grid->tarifa->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<?php if ($Grid->tarifa->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_tarifa_articulo_tarifa" class="el_tarifa_articulo_tarifa">
<span<?= $Grid->tarifa->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->tarifa->getDisplayValue($Grid->tarifa->ViewValue) ?></span></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_tarifa" name="x<?= $Grid->RowIndex ?>_tarifa" value="<?= HtmlEncode($Grid->tarifa->CurrentValue) ?>" data-hidden="1">
</span>
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_tarifa_articulo_tarifa" class="el_tarifa_articulo_tarifa">
    <select
        id="x<?= $Grid->RowIndex ?>_tarifa"
        name="x<?= $Grid->RowIndex ?>_tarifa"
        class="form-select ew-select<?= $Grid->tarifa->isInvalidClass() ?>"
        <?php if (!$Grid->tarifa->IsNativeSelect) { ?>
        data-select2-id="ftarifa_articulogrid_x<?= $Grid->RowIndex ?>_tarifa"
        <?php } ?>
        data-table="tarifa_articulo"
        data-field="x_tarifa"
        data-value-separator="<?= $Grid->tarifa->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->tarifa->getPlaceHolder()) ?>"
        <?= $Grid->tarifa->editAttributes() ?>>
        <?= $Grid->tarifa->selectOptionListHtml("x{$Grid->RowIndex}_tarifa") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->tarifa->getErrorMessage() ?></div>
<?= $Grid->tarifa->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_tarifa") ?>
<?php if (!$Grid->tarifa->IsNativeSelect) { ?>
<script>
loadjs.ready("ftarifa_articulogrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_tarifa", selectId: "ftarifa_articulogrid_x<?= $Grid->RowIndex ?>_tarifa" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (ftarifa_articulogrid.lists.tarifa?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_tarifa", form: "ftarifa_articulogrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_tarifa", form: "ftarifa_articulogrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.tarifa_articulo.fields.tarifa.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<input type="hidden" data-table="tarifa_articulo" data-field="x_tarifa" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_tarifa" id="o<?= $Grid->RowIndex ?>_tarifa" value="<?= HtmlEncode($Grid->tarifa->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<?php if ($Grid->tarifa->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_tarifa_articulo_tarifa" class="el_tarifa_articulo_tarifa">
<span<?= $Grid->tarifa->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->tarifa->getDisplayValue($Grid->tarifa->ViewValue) ?></span></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_tarifa" name="x<?= $Grid->RowIndex ?>_tarifa" value="<?= HtmlEncode($Grid->tarifa->CurrentValue) ?>" data-hidden="1">
</span>
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_tarifa_articulo_tarifa" class="el_tarifa_articulo_tarifa">
    <select
        id="x<?= $Grid->RowIndex ?>_tarifa"
        name="x<?= $Grid->RowIndex ?>_tarifa"
        class="form-select ew-select<?= $Grid->tarifa->isInvalidClass() ?>"
        <?php if (!$Grid->tarifa->IsNativeSelect) { ?>
        data-select2-id="ftarifa_articulogrid_x<?= $Grid->RowIndex ?>_tarifa"
        <?php } ?>
        data-table="tarifa_articulo"
        data-field="x_tarifa"
        data-value-separator="<?= $Grid->tarifa->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->tarifa->getPlaceHolder()) ?>"
        <?= $Grid->tarifa->editAttributes() ?>>
        <?= $Grid->tarifa->selectOptionListHtml("x{$Grid->RowIndex}_tarifa") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->tarifa->getErrorMessage() ?></div>
<?= $Grid->tarifa->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_tarifa") ?>
<?php if (!$Grid->tarifa->IsNativeSelect) { ?>
<script>
loadjs.ready("ftarifa_articulogrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_tarifa", selectId: "ftarifa_articulogrid_x<?= $Grid->RowIndex ?>_tarifa" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (ftarifa_articulogrid.lists.tarifa?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_tarifa", form: "ftarifa_articulogrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_tarifa", form: "ftarifa_articulogrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.tarifa_articulo.fields.tarifa.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_tarifa_articulo_tarifa" class="el_tarifa_articulo_tarifa">
<span<?= $Grid->tarifa->viewAttributes() ?>>
<?= $Grid->tarifa->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="tarifa_articulo" data-field="x_tarifa" data-hidden="1" name="ftarifa_articulogrid$x<?= $Grid->RowIndex ?>_tarifa" id="ftarifa_articulogrid$x<?= $Grid->RowIndex ?>_tarifa" value="<?= HtmlEncode($Grid->tarifa->FormValue) ?>">
<input type="hidden" data-table="tarifa_articulo" data-field="x_tarifa" data-hidden="1" data-old name="ftarifa_articulogrid$o<?= $Grid->RowIndex ?>_tarifa" id="ftarifa_articulogrid$o<?= $Grid->RowIndex ?>_tarifa" value="<?= HtmlEncode($Grid->tarifa->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->fabricante->Visible) { // fabricante ?>
        <td data-name="fabricante"<?= $Grid->fabricante->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_tarifa_articulo_fabricante" class="el_tarifa_articulo_fabricante">
    <select
        id="x<?= $Grid->RowIndex ?>_fabricante"
        name="x<?= $Grid->RowIndex ?>_fabricante"
        class="form-control ew-select<?= $Grid->fabricante->isInvalidClass() ?>"
        data-select2-id="ftarifa_articulogrid_x<?= $Grid->RowIndex ?>_fabricante"
        data-table="tarifa_articulo"
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
loadjs.ready("ftarifa_articulogrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_fabricante", selectId: "ftarifa_articulogrid_x<?= $Grid->RowIndex ?>_fabricante" };
    if (ftarifa_articulogrid.lists.fabricante?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_fabricante", form: "ftarifa_articulogrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_fabricante", form: "ftarifa_articulogrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.tarifa_articulo.fields.fabricante.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<input type="hidden" data-table="tarifa_articulo" data-field="x_fabricante" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_fabricante" id="o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_tarifa_articulo_fabricante" class="el_tarifa_articulo_fabricante">
    <select
        id="x<?= $Grid->RowIndex ?>_fabricante"
        name="x<?= $Grid->RowIndex ?>_fabricante"
        class="form-control ew-select<?= $Grid->fabricante->isInvalidClass() ?>"
        data-select2-id="ftarifa_articulogrid_x<?= $Grid->RowIndex ?>_fabricante"
        data-table="tarifa_articulo"
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
loadjs.ready("ftarifa_articulogrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_fabricante", selectId: "ftarifa_articulogrid_x<?= $Grid->RowIndex ?>_fabricante" };
    if (ftarifa_articulogrid.lists.fabricante?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_fabricante", form: "ftarifa_articulogrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_fabricante", form: "ftarifa_articulogrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.tarifa_articulo.fields.fabricante.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_tarifa_articulo_fabricante" class="el_tarifa_articulo_fabricante">
<span<?= $Grid->fabricante->viewAttributes() ?>>
<?= $Grid->fabricante->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="tarifa_articulo" data-field="x_fabricante" data-hidden="1" name="ftarifa_articulogrid$x<?= $Grid->RowIndex ?>_fabricante" id="ftarifa_articulogrid$x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->FormValue) ?>">
<input type="hidden" data-table="tarifa_articulo" data-field="x_fabricante" data-hidden="1" data-old name="ftarifa_articulogrid$o<?= $Grid->RowIndex ?>_fabricante" id="ftarifa_articulogrid$o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->articulo->Visible) { // articulo ?>
        <td data-name="articulo"<?= $Grid->articulo->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_tarifa_articulo_articulo" class="el_tarifa_articulo_articulo">
    <select
        id="x<?= $Grid->RowIndex ?>_articulo"
        name="x<?= $Grid->RowIndex ?>_articulo"
        class="form-control ew-select<?= $Grid->articulo->isInvalidClass() ?>"
        data-select2-id="ftarifa_articulogrid_x<?= $Grid->RowIndex ?>_articulo"
        data-table="tarifa_articulo"
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
loadjs.ready("ftarifa_articulogrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_articulo", selectId: "ftarifa_articulogrid_x<?= $Grid->RowIndex ?>_articulo" };
    if (ftarifa_articulogrid.lists.articulo?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_articulo", form: "ftarifa_articulogrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_articulo", form: "ftarifa_articulogrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.tarifa_articulo.fields.articulo.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<input type="hidden" data-table="tarifa_articulo" data-field="x_articulo" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_articulo" id="o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_tarifa_articulo_articulo" class="el_tarifa_articulo_articulo">
    <select
        id="x<?= $Grid->RowIndex ?>_articulo"
        name="x<?= $Grid->RowIndex ?>_articulo"
        class="form-control ew-select<?= $Grid->articulo->isInvalidClass() ?>"
        data-select2-id="ftarifa_articulogrid_x<?= $Grid->RowIndex ?>_articulo"
        data-table="tarifa_articulo"
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
loadjs.ready("ftarifa_articulogrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_articulo", selectId: "ftarifa_articulogrid_x<?= $Grid->RowIndex ?>_articulo" };
    if (ftarifa_articulogrid.lists.articulo?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_articulo", form: "ftarifa_articulogrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_articulo", form: "ftarifa_articulogrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.tarifa_articulo.fields.articulo.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_tarifa_articulo_articulo" class="el_tarifa_articulo_articulo">
<span<?= $Grid->articulo->viewAttributes() ?>>
<?= $Grid->articulo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="tarifa_articulo" data-field="x_articulo" data-hidden="1" name="ftarifa_articulogrid$x<?= $Grid->RowIndex ?>_articulo" id="ftarifa_articulogrid$x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->FormValue) ?>">
<input type="hidden" data-table="tarifa_articulo" data-field="x_articulo" data-hidden="1" data-old name="ftarifa_articulogrid$o<?= $Grid->RowIndex ?>_articulo" id="ftarifa_articulogrid$o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->precio->Visible) { // precio ?>
        <td data-name="precio"<?= $Grid->precio->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_tarifa_articulo_precio" class="el_tarifa_articulo_precio">
<input type="<?= $Grid->precio->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_precio" id="x<?= $Grid->RowIndex ?>_precio" data-table="tarifa_articulo" data-field="x_precio" value="<?= $Grid->precio->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Grid->precio->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->precio->formatPattern()) ?>"<?= $Grid->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="tarifa_articulo" data-field="x_precio" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_precio" id="o<?= $Grid->RowIndex ?>_precio" value="<?= HtmlEncode($Grid->precio->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_tarifa_articulo_precio" class="el_tarifa_articulo_precio">
<input type="<?= $Grid->precio->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_precio" id="x<?= $Grid->RowIndex ?>_precio" data-table="tarifa_articulo" data-field="x_precio" value="<?= $Grid->precio->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Grid->precio->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->precio->formatPattern()) ?>"<?= $Grid->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_tarifa_articulo_precio" class="el_tarifa_articulo_precio">
<span<?= $Grid->precio->viewAttributes() ?>>
<?= $Grid->precio->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="tarifa_articulo" data-field="x_precio" data-hidden="1" name="ftarifa_articulogrid$x<?= $Grid->RowIndex ?>_precio" id="ftarifa_articulogrid$x<?= $Grid->RowIndex ?>_precio" value="<?= HtmlEncode($Grid->precio->FormValue) ?>">
<input type="hidden" data-table="tarifa_articulo" data-field="x_precio" data-hidden="1" data-old name="ftarifa_articulogrid$o<?= $Grid->RowIndex ?>_precio" id="ftarifa_articulogrid$o<?= $Grid->RowIndex ?>_precio" value="<?= HtmlEncode($Grid->precio->OldValue) ?>">
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
loadjs.ready(["ftarifa_articulogrid","load"], () => ftarifa_articulogrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="ftarifa_articulogrid">
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
    ew.addEventHandlers("tarifa_articulo");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
