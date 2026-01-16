<?php

namespace PHPMaker2024\mandrake;

// Set up and run Grid object
$Grid = Container("PedidioDetalleOnlineGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fpedidio_detalle_onlinegrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { pedidio_detalle_online: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpedidio_detalle_onlinegrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null, ew.Validators.integer], fields.fabricante.isInvalid],
            ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null], fields.articulo.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["fabricante",false],["articulo",false]];
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
<div id="fpedidio_detalle_onlinegrid" class="ew-form ew-list-form">
<div id="gmp_pedidio_detalle_online" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_pedidio_detalle_onlinegrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
        <th data-name="fabricante" class="<?= $Grid->fabricante->headerCellClass() ?>"><div id="elh_pedidio_detalle_online_fabricante" class="pedidio_detalle_online_fabricante"><?= $Grid->renderFieldHeader($Grid->fabricante) ?></div></th>
<?php } ?>
<?php if ($Grid->articulo->Visible) { // articulo ?>
        <th data-name="articulo" class="<?= $Grid->articulo->headerCellClass() ?>"><div id="elh_pedidio_detalle_online_articulo" class="pedidio_detalle_online_articulo"><?= $Grid->renderFieldHeader($Grid->articulo) ?></div></th>
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
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pedidio_detalle_online_fabricante" class="el_pedidio_detalle_online_fabricante">
<?php
if (IsRTL()) {
    $Grid->fabricante->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x<?= $Grid->RowIndex ?>_fabricante" class="ew-auto-suggest">
    <input type="<?= $Grid->fabricante->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_fabricante" id="sv_x<?= $Grid->RowIndex ?>_fabricante" value="<?= RemoveHtml($Grid->fabricante->EditValue) ?>" autocomplete="off" size="30" placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fabricante->formatPattern()) ?>"<?= $Grid->fabricante->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="pedidio_detalle_online" data-field="x_fabricante" data-input="sv_x<?= $Grid->RowIndex ?>_fabricante" data-value-separator="<?= $Grid->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->CurrentValue) ?>" data-ew-action="update-options"></selection-list>
<div class="invalid-feedback"><?= $Grid->fabricante->getErrorMessage() ?></div>
<script>
loadjs.ready("fpedidio_detalle_onlinegrid", function() {
    fpedidio_detalle_onlinegrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_fabricante","forceSelect":false}, { lookupAllDisplayFields: <?= $Grid->fabricante->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.pedidio_detalle_online.fields.fabricante.autoSuggestOptions));
});
</script>
<?= $Grid->fabricante->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_fabricante") ?>
</span>
<input type="hidden" data-table="pedidio_detalle_online" data-field="x_fabricante" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_fabricante" id="o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pedidio_detalle_online_fabricante" class="el_pedidio_detalle_online_fabricante">
<?php
if (IsRTL()) {
    $Grid->fabricante->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x<?= $Grid->RowIndex ?>_fabricante" class="ew-auto-suggest">
    <input type="<?= $Grid->fabricante->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_fabricante" id="sv_x<?= $Grid->RowIndex ?>_fabricante" value="<?= RemoveHtml($Grid->fabricante->EditValue) ?>" autocomplete="off" size="30" placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fabricante->formatPattern()) ?>"<?= $Grid->fabricante->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="pedidio_detalle_online" data-field="x_fabricante" data-input="sv_x<?= $Grid->RowIndex ?>_fabricante" data-value-separator="<?= $Grid->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->CurrentValue) ?>" data-ew-action="update-options"></selection-list>
<div class="invalid-feedback"><?= $Grid->fabricante->getErrorMessage() ?></div>
<script>
loadjs.ready("fpedidio_detalle_onlinegrid", function() {
    fpedidio_detalle_onlinegrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_fabricante","forceSelect":false}, { lookupAllDisplayFields: <?= $Grid->fabricante->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.pedidio_detalle_online.fields.fabricante.autoSuggestOptions));
});
</script>
<?= $Grid->fabricante->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_fabricante") ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pedidio_detalle_online_fabricante" class="el_pedidio_detalle_online_fabricante">
<span<?= $Grid->fabricante->viewAttributes() ?>>
<?= $Grid->fabricante->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pedidio_detalle_online" data-field="x_fabricante" data-hidden="1" name="fpedidio_detalle_onlinegrid$x<?= $Grid->RowIndex ?>_fabricante" id="fpedidio_detalle_onlinegrid$x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->FormValue) ?>">
<input type="hidden" data-table="pedidio_detalle_online" data-field="x_fabricante" data-hidden="1" data-old name="fpedidio_detalle_onlinegrid$o<?= $Grid->RowIndex ?>_fabricante" id="fpedidio_detalle_onlinegrid$o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->articulo->Visible) { // articulo ?>
        <td data-name="articulo"<?= $Grid->articulo->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pedidio_detalle_online_articulo" class="el_pedidio_detalle_online_articulo">
    <select
        id="x<?= $Grid->RowIndex ?>_articulo"
        name="x<?= $Grid->RowIndex ?>_articulo"
        class="form-select ew-select<?= $Grid->articulo->isInvalidClass() ?>"
        <?php if (!$Grid->articulo->IsNativeSelect) { ?>
        data-select2-id="fpedidio_detalle_onlinegrid_x<?= $Grid->RowIndex ?>_articulo"
        <?php } ?>
        data-table="pedidio_detalle_online"
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
loadjs.ready("fpedidio_detalle_onlinegrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_articulo", selectId: "fpedidio_detalle_onlinegrid_x<?= $Grid->RowIndex ?>_articulo" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fpedidio_detalle_onlinegrid.lists.articulo?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_articulo", form: "fpedidio_detalle_onlinegrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_articulo", form: "fpedidio_detalle_onlinegrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.pedidio_detalle_online.fields.articulo.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="pedidio_detalle_online" data-field="x_articulo" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_articulo" id="o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pedidio_detalle_online_articulo" class="el_pedidio_detalle_online_articulo">
    <select
        id="x<?= $Grid->RowIndex ?>_articulo"
        name="x<?= $Grid->RowIndex ?>_articulo"
        class="form-select ew-select<?= $Grid->articulo->isInvalidClass() ?>"
        <?php if (!$Grid->articulo->IsNativeSelect) { ?>
        data-select2-id="fpedidio_detalle_onlinegrid_x<?= $Grid->RowIndex ?>_articulo"
        <?php } ?>
        data-table="pedidio_detalle_online"
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
loadjs.ready("fpedidio_detalle_onlinegrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_articulo", selectId: "fpedidio_detalle_onlinegrid_x<?= $Grid->RowIndex ?>_articulo" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fpedidio_detalle_onlinegrid.lists.articulo?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_articulo", form: "fpedidio_detalle_onlinegrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_articulo", form: "fpedidio_detalle_onlinegrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.pedidio_detalle_online.fields.articulo.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pedidio_detalle_online_articulo" class="el_pedidio_detalle_online_articulo">
<span<?= $Grid->articulo->viewAttributes() ?>>
<?= $Grid->articulo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pedidio_detalle_online" data-field="x_articulo" data-hidden="1" name="fpedidio_detalle_onlinegrid$x<?= $Grid->RowIndex ?>_articulo" id="fpedidio_detalle_onlinegrid$x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->FormValue) ?>">
<input type="hidden" data-table="pedidio_detalle_online" data-field="x_articulo" data-hidden="1" data-old name="fpedidio_detalle_onlinegrid$o<?= $Grid->RowIndex ?>_articulo" id="fpedidio_detalle_onlinegrid$o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
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
loadjs.ready(["fpedidio_detalle_onlinegrid","load"], () => fpedidio_detalle_onlinegrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="fpedidio_detalle_onlinegrid">
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
    ew.addEventHandlers("pedidio_detalle_online");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
