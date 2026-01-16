<?php

namespace PHPMaker2024\mandrake;

// Set up and run Grid object
$Grid = Container("ProveedorArticuloGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fproveedor_articulogrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { proveedor_articulo: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fproveedor_articulogrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null], fields.fabricante.isInvalid],
            ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null], fields.articulo.isInvalid],
            ["codigo_proveedor", [fields.codigo_proveedor.visible && fields.codigo_proveedor.required ? ew.Validators.required(fields.codigo_proveedor.caption) : null], fields.codigo_proveedor.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["fabricante",false],["articulo",false],["codigo_proveedor",false]];
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
<div id="fproveedor_articulogrid" class="ew-form ew-list-form">
<div id="gmp_proveedor_articulo" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_proveedor_articulogrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
        <th data-name="fabricante" class="<?= $Grid->fabricante->headerCellClass() ?>"><div id="elh_proveedor_articulo_fabricante" class="proveedor_articulo_fabricante"><?= $Grid->renderFieldHeader($Grid->fabricante) ?></div></th>
<?php } ?>
<?php if ($Grid->articulo->Visible) { // articulo ?>
        <th data-name="articulo" class="<?= $Grid->articulo->headerCellClass() ?>"><div id="elh_proveedor_articulo_articulo" class="proveedor_articulo_articulo"><?= $Grid->renderFieldHeader($Grid->articulo) ?></div></th>
<?php } ?>
<?php if ($Grid->codigo_proveedor->Visible) { // codigo_proveedor ?>
        <th data-name="codigo_proveedor" class="<?= $Grid->codigo_proveedor->headerCellClass() ?>"><div id="elh_proveedor_articulo_codigo_proveedor" class="proveedor_articulo_codigo_proveedor"><?= $Grid->renderFieldHeader($Grid->codigo_proveedor) ?></div></th>
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
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_proveedor_articulo_fabricante" class="el_proveedor_articulo_fabricante">
    <select
        id="x<?= $Grid->RowIndex ?>_fabricante"
        name="x<?= $Grid->RowIndex ?>_fabricante"
        class="form-control ew-select<?= $Grid->fabricante->isInvalidClass() ?>"
        data-select2-id="fproveedor_articulogrid_x<?= $Grid->RowIndex ?>_fabricante"
        data-table="proveedor_articulo"
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
loadjs.ready("fproveedor_articulogrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_fabricante", selectId: "fproveedor_articulogrid_x<?= $Grid->RowIndex ?>_fabricante" };
    if (fproveedor_articulogrid.lists.fabricante?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_fabricante", form: "fproveedor_articulogrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_fabricante", form: "fproveedor_articulogrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.proveedor_articulo.fields.fabricante.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<input type="hidden" data-table="proveedor_articulo" data-field="x_fabricante" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_fabricante" id="o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_proveedor_articulo_fabricante" class="el_proveedor_articulo_fabricante">
    <select
        id="x<?= $Grid->RowIndex ?>_fabricante"
        name="x<?= $Grid->RowIndex ?>_fabricante"
        class="form-control ew-select<?= $Grid->fabricante->isInvalidClass() ?>"
        data-select2-id="fproveedor_articulogrid_x<?= $Grid->RowIndex ?>_fabricante"
        data-table="proveedor_articulo"
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
loadjs.ready("fproveedor_articulogrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_fabricante", selectId: "fproveedor_articulogrid_x<?= $Grid->RowIndex ?>_fabricante" };
    if (fproveedor_articulogrid.lists.fabricante?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_fabricante", form: "fproveedor_articulogrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_fabricante", form: "fproveedor_articulogrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.proveedor_articulo.fields.fabricante.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_proveedor_articulo_fabricante" class="el_proveedor_articulo_fabricante">
<span<?= $Grid->fabricante->viewAttributes() ?>>
<?= $Grid->fabricante->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="proveedor_articulo" data-field="x_fabricante" data-hidden="1" name="fproveedor_articulogrid$x<?= $Grid->RowIndex ?>_fabricante" id="fproveedor_articulogrid$x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->FormValue) ?>">
<input type="hidden" data-table="proveedor_articulo" data-field="x_fabricante" data-hidden="1" data-old name="fproveedor_articulogrid$o<?= $Grid->RowIndex ?>_fabricante" id="fproveedor_articulogrid$o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->articulo->Visible) { // articulo ?>
        <td data-name="articulo"<?= $Grid->articulo->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_proveedor_articulo_articulo" class="el_proveedor_articulo_articulo">
    <select
        id="x<?= $Grid->RowIndex ?>_articulo"
        name="x<?= $Grid->RowIndex ?>_articulo"
        class="form-control ew-select<?= $Grid->articulo->isInvalidClass() ?>"
        data-select2-id="fproveedor_articulogrid_x<?= $Grid->RowIndex ?>_articulo"
        data-table="proveedor_articulo"
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
loadjs.ready("fproveedor_articulogrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_articulo", selectId: "fproveedor_articulogrid_x<?= $Grid->RowIndex ?>_articulo" };
    if (fproveedor_articulogrid.lists.articulo?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_articulo", form: "fproveedor_articulogrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_articulo", form: "fproveedor_articulogrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.proveedor_articulo.fields.articulo.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<input type="hidden" data-table="proveedor_articulo" data-field="x_articulo" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_articulo" id="o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_proveedor_articulo_articulo" class="el_proveedor_articulo_articulo">
    <select
        id="x<?= $Grid->RowIndex ?>_articulo"
        name="x<?= $Grid->RowIndex ?>_articulo"
        class="form-control ew-select<?= $Grid->articulo->isInvalidClass() ?>"
        data-select2-id="fproveedor_articulogrid_x<?= $Grid->RowIndex ?>_articulo"
        data-table="proveedor_articulo"
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
loadjs.ready("fproveedor_articulogrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_articulo", selectId: "fproveedor_articulogrid_x<?= $Grid->RowIndex ?>_articulo" };
    if (fproveedor_articulogrid.lists.articulo?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_articulo", form: "fproveedor_articulogrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_articulo", form: "fproveedor_articulogrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.proveedor_articulo.fields.articulo.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_proveedor_articulo_articulo" class="el_proveedor_articulo_articulo">
<span<?= $Grid->articulo->viewAttributes() ?>>
<?= $Grid->articulo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="proveedor_articulo" data-field="x_articulo" data-hidden="1" name="fproveedor_articulogrid$x<?= $Grid->RowIndex ?>_articulo" id="fproveedor_articulogrid$x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->FormValue) ?>">
<input type="hidden" data-table="proveedor_articulo" data-field="x_articulo" data-hidden="1" data-old name="fproveedor_articulogrid$o<?= $Grid->RowIndex ?>_articulo" id="fproveedor_articulogrid$o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->codigo_proveedor->Visible) { // codigo_proveedor ?>
        <td data-name="codigo_proveedor"<?= $Grid->codigo_proveedor->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_proveedor_articulo_codigo_proveedor" class="el_proveedor_articulo_codigo_proveedor">
<input type="<?= $Grid->codigo_proveedor->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_codigo_proveedor" id="x<?= $Grid->RowIndex ?>_codigo_proveedor" data-table="proveedor_articulo" data-field="x_codigo_proveedor" value="<?= $Grid->codigo_proveedor->EditValue ?>" size="10" maxlength="30" placeholder="<?= HtmlEncode($Grid->codigo_proveedor->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->codigo_proveedor->formatPattern()) ?>"<?= $Grid->codigo_proveedor->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->codigo_proveedor->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="proveedor_articulo" data-field="x_codigo_proveedor" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_codigo_proveedor" id="o<?= $Grid->RowIndex ?>_codigo_proveedor" value="<?= HtmlEncode($Grid->codigo_proveedor->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_proveedor_articulo_codigo_proveedor" class="el_proveedor_articulo_codigo_proveedor">
<input type="<?= $Grid->codigo_proveedor->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_codigo_proveedor" id="x<?= $Grid->RowIndex ?>_codigo_proveedor" data-table="proveedor_articulo" data-field="x_codigo_proveedor" value="<?= $Grid->codigo_proveedor->EditValue ?>" size="10" maxlength="30" placeholder="<?= HtmlEncode($Grid->codigo_proveedor->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->codigo_proveedor->formatPattern()) ?>"<?= $Grid->codigo_proveedor->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->codigo_proveedor->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_proveedor_articulo_codigo_proveedor" class="el_proveedor_articulo_codigo_proveedor">
<span<?= $Grid->codigo_proveedor->viewAttributes() ?>>
<?= $Grid->codigo_proveedor->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="proveedor_articulo" data-field="x_codigo_proveedor" data-hidden="1" name="fproveedor_articulogrid$x<?= $Grid->RowIndex ?>_codigo_proveedor" id="fproveedor_articulogrid$x<?= $Grid->RowIndex ?>_codigo_proveedor" value="<?= HtmlEncode($Grid->codigo_proveedor->FormValue) ?>">
<input type="hidden" data-table="proveedor_articulo" data-field="x_codigo_proveedor" data-hidden="1" data-old name="fproveedor_articulogrid$o<?= $Grid->RowIndex ?>_codigo_proveedor" id="fproveedor_articulogrid$o<?= $Grid->RowIndex ?>_codigo_proveedor" value="<?= HtmlEncode($Grid->codigo_proveedor->OldValue) ?>">
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
loadjs.ready(["fproveedor_articulogrid","load"], () => fproveedor_articulogrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="fproveedor_articulogrid">
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
    ew.addEventHandlers("proveedor_articulo");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
