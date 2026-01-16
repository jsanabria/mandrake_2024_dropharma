<?php

namespace PHPMaker2024\mandrake;

// Set up and run Grid object
$Grid = Container("AsesorClienteGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fasesor_clientegrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { asesor_cliente: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fasesor_clientegrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["cliente", [fields.cliente.visible && fields.cliente.required ? ew.Validators.required(fields.cliente.caption) : null], fields.cliente.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["cliente",false]];
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
            "cliente": <?= $Grid->cliente->toClientList($Grid) ?>,
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
<div id="fasesor_clientegrid" class="ew-form ew-list-form">
<div id="gmp_asesor_cliente" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_asesor_clientegrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Grid->cliente->Visible) { // cliente ?>
        <th data-name="cliente" class="<?= $Grid->cliente->headerCellClass() ?>"><div id="elh_asesor_cliente_cliente" class="asesor_cliente_cliente"><?= $Grid->renderFieldHeader($Grid->cliente) ?></div></th>
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
    <?php if ($Grid->cliente->Visible) { // cliente ?>
        <td data-name="cliente"<?= $Grid->cliente->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_asesor_cliente_cliente" class="el_asesor_cliente_cliente">
    <select
        id="x<?= $Grid->RowIndex ?>_cliente"
        name="x<?= $Grid->RowIndex ?>_cliente"
        class="form-control ew-select<?= $Grid->cliente->isInvalidClass() ?>"
        data-select2-id="fasesor_clientegrid_x<?= $Grid->RowIndex ?>_cliente"
        data-table="asesor_cliente"
        data-field="x_cliente"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->cliente->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->cliente->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->cliente->getPlaceHolder()) ?>"
        <?= $Grid->cliente->editAttributes() ?>>
        <?= $Grid->cliente->selectOptionListHtml("x{$Grid->RowIndex}_cliente") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->cliente->getErrorMessage() ?></div>
<?= $Grid->cliente->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_cliente") ?>
<script>
loadjs.ready("fasesor_clientegrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_cliente", selectId: "fasesor_clientegrid_x<?= $Grid->RowIndex ?>_cliente" };
    if (fasesor_clientegrid.lists.cliente?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_cliente", form: "fasesor_clientegrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_cliente", form: "fasesor_clientegrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.asesor_cliente.fields.cliente.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<input type="hidden" data-table="asesor_cliente" data-field="x_cliente" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_cliente" id="o<?= $Grid->RowIndex ?>_cliente" value="<?= HtmlEncode($Grid->cliente->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_asesor_cliente_cliente" class="el_asesor_cliente_cliente">
    <select
        id="x<?= $Grid->RowIndex ?>_cliente"
        name="x<?= $Grid->RowIndex ?>_cliente"
        class="form-control ew-select<?= $Grid->cliente->isInvalidClass() ?>"
        data-select2-id="fasesor_clientegrid_x<?= $Grid->RowIndex ?>_cliente"
        data-table="asesor_cliente"
        data-field="x_cliente"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->cliente->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->cliente->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->cliente->getPlaceHolder()) ?>"
        <?= $Grid->cliente->editAttributes() ?>>
        <?= $Grid->cliente->selectOptionListHtml("x{$Grid->RowIndex}_cliente") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->cliente->getErrorMessage() ?></div>
<?= $Grid->cliente->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_cliente") ?>
<script>
loadjs.ready("fasesor_clientegrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_cliente", selectId: "fasesor_clientegrid_x<?= $Grid->RowIndex ?>_cliente" };
    if (fasesor_clientegrid.lists.cliente?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_cliente", form: "fasesor_clientegrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_cliente", form: "fasesor_clientegrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.asesor_cliente.fields.cliente.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_asesor_cliente_cliente" class="el_asesor_cliente_cliente">
<span<?= $Grid->cliente->viewAttributes() ?>>
<?= $Grid->cliente->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="asesor_cliente" data-field="x_cliente" data-hidden="1" name="fasesor_clientegrid$x<?= $Grid->RowIndex ?>_cliente" id="fasesor_clientegrid$x<?= $Grid->RowIndex ?>_cliente" value="<?= HtmlEncode($Grid->cliente->FormValue) ?>">
<input type="hidden" data-table="asesor_cliente" data-field="x_cliente" data-hidden="1" data-old name="fasesor_clientegrid$o<?= $Grid->RowIndex ?>_cliente" id="fasesor_clientegrid$o<?= $Grid->RowIndex ?>_cliente" value="<?= HtmlEncode($Grid->cliente->OldValue) ?>">
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
loadjs.ready(["fasesor_clientegrid","load"], () => fasesor_clientegrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="fasesor_clientegrid">
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
    ew.addEventHandlers("asesor_cliente");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
