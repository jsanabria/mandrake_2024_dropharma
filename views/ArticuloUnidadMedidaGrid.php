<?php

namespace PHPMaker2024\mandrake;

// Set up and run Grid object
$Grid = Container("ArticuloUnidadMedidaGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var farticulo_unidad_medidagrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { articulo_unidad_medida: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("farticulo_unidad_medidagrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["unidad_medida", [fields.unidad_medida.visible && fields.unidad_medida.required ? ew.Validators.required(fields.unidad_medida.caption) : null], fields.unidad_medida.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["unidad_medida",false]];
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
            "unidad_medida": <?= $Grid->unidad_medida->toClientList($Grid) ?>,
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
<div id="farticulo_unidad_medidagrid" class="ew-form ew-list-form">
<div id="gmp_articulo_unidad_medida" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_articulo_unidad_medidagrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Grid->unidad_medida->Visible) { // unidad_medida ?>
        <th data-name="unidad_medida" class="<?= $Grid->unidad_medida->headerCellClass() ?>"><div id="elh_articulo_unidad_medida_unidad_medida" class="articulo_unidad_medida_unidad_medida"><?= $Grid->renderFieldHeader($Grid->unidad_medida) ?></div></th>
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
    <?php if ($Grid->unidad_medida->Visible) { // unidad_medida ?>
        <td data-name="unidad_medida"<?= $Grid->unidad_medida->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_articulo_unidad_medida_unidad_medida" class="el_articulo_unidad_medida_unidad_medida">
    <select
        id="x<?= $Grid->RowIndex ?>_unidad_medida"
        name="x<?= $Grid->RowIndex ?>_unidad_medida"
        class="form-select ew-select<?= $Grid->unidad_medida->isInvalidClass() ?>"
        <?php if (!$Grid->unidad_medida->IsNativeSelect) { ?>
        data-select2-id="farticulo_unidad_medidagrid_x<?= $Grid->RowIndex ?>_unidad_medida"
        <?php } ?>
        data-table="articulo_unidad_medida"
        data-field="x_unidad_medida"
        data-value-separator="<?= $Grid->unidad_medida->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->unidad_medida->getPlaceHolder()) ?>"
        <?= $Grid->unidad_medida->editAttributes() ?>>
        <?= $Grid->unidad_medida->selectOptionListHtml("x{$Grid->RowIndex}_unidad_medida") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->unidad_medida->getErrorMessage() ?></div>
<?= $Grid->unidad_medida->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_unidad_medida") ?>
<?php if (!$Grid->unidad_medida->IsNativeSelect) { ?>
<script>
loadjs.ready("farticulo_unidad_medidagrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_unidad_medida", selectId: "farticulo_unidad_medidagrid_x<?= $Grid->RowIndex ?>_unidad_medida" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (farticulo_unidad_medidagrid.lists.unidad_medida?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_unidad_medida", form: "farticulo_unidad_medidagrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_unidad_medida", form: "farticulo_unidad_medidagrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.articulo_unidad_medida.fields.unidad_medida.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="articulo_unidad_medida" data-field="x_unidad_medida" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_unidad_medida" id="o<?= $Grid->RowIndex ?>_unidad_medida" value="<?= HtmlEncode($Grid->unidad_medida->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_articulo_unidad_medida_unidad_medida" class="el_articulo_unidad_medida_unidad_medida">
    <select
        id="x<?= $Grid->RowIndex ?>_unidad_medida"
        name="x<?= $Grid->RowIndex ?>_unidad_medida"
        class="form-select ew-select<?= $Grid->unidad_medida->isInvalidClass() ?>"
        <?php if (!$Grid->unidad_medida->IsNativeSelect) { ?>
        data-select2-id="farticulo_unidad_medidagrid_x<?= $Grid->RowIndex ?>_unidad_medida"
        <?php } ?>
        data-table="articulo_unidad_medida"
        data-field="x_unidad_medida"
        data-value-separator="<?= $Grid->unidad_medida->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->unidad_medida->getPlaceHolder()) ?>"
        <?= $Grid->unidad_medida->editAttributes() ?>>
        <?= $Grid->unidad_medida->selectOptionListHtml("x{$Grid->RowIndex}_unidad_medida") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->unidad_medida->getErrorMessage() ?></div>
<?= $Grid->unidad_medida->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_unidad_medida") ?>
<?php if (!$Grid->unidad_medida->IsNativeSelect) { ?>
<script>
loadjs.ready("farticulo_unidad_medidagrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_unidad_medida", selectId: "farticulo_unidad_medidagrid_x<?= $Grid->RowIndex ?>_unidad_medida" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (farticulo_unidad_medidagrid.lists.unidad_medida?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_unidad_medida", form: "farticulo_unidad_medidagrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_unidad_medida", form: "farticulo_unidad_medidagrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.articulo_unidad_medida.fields.unidad_medida.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_articulo_unidad_medida_unidad_medida" class="el_articulo_unidad_medida_unidad_medida">
<span<?= $Grid->unidad_medida->viewAttributes() ?>>
<?= $Grid->unidad_medida->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="articulo_unidad_medida" data-field="x_unidad_medida" data-hidden="1" name="farticulo_unidad_medidagrid$x<?= $Grid->RowIndex ?>_unidad_medida" id="farticulo_unidad_medidagrid$x<?= $Grid->RowIndex ?>_unidad_medida" value="<?= HtmlEncode($Grid->unidad_medida->FormValue) ?>">
<input type="hidden" data-table="articulo_unidad_medida" data-field="x_unidad_medida" data-hidden="1" data-old name="farticulo_unidad_medidagrid$o<?= $Grid->RowIndex ?>_unidad_medida" id="farticulo_unidad_medidagrid$o<?= $Grid->RowIndex ?>_unidad_medida" value="<?= HtmlEncode($Grid->unidad_medida->OldValue) ?>">
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
loadjs.ready(["farticulo_unidad_medidagrid","load"], () => farticulo_unidad_medidagrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="farticulo_unidad_medidagrid">
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
    ew.addEventHandlers("articulo_unidad_medida");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
