<?php

namespace PHPMaker2024\mandrake;

// Set up and run Grid object
$Grid = Container("TarifaDescuentoUtilidadGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var ftarifa_descuento_utilidadgrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { tarifa_descuento_utilidad: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftarifa_descuento_utilidadgrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null], fields.fabricante.isInvalid],
            ["descuento", [fields.descuento.visible && fields.descuento.required ? ew.Validators.required(fields.descuento.caption) : null, ew.Validators.float], fields.descuento.isInvalid],
            ["utilidad", [fields.utilidad.visible && fields.utilidad.required ? ew.Validators.required(fields.utilidad.caption) : null, ew.Validators.float], fields.utilidad.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["fabricante",false],["descuento",false],["utilidad",false]];
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
<div id="ftarifa_descuento_utilidadgrid" class="ew-form ew-list-form">
<div id="gmp_tarifa_descuento_utilidad" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_tarifa_descuento_utilidadgrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
        <th data-name="fabricante" class="<?= $Grid->fabricante->headerCellClass() ?>"><div id="elh_tarifa_descuento_utilidad_fabricante" class="tarifa_descuento_utilidad_fabricante"><?= $Grid->renderFieldHeader($Grid->fabricante) ?></div></th>
<?php } ?>
<?php if ($Grid->descuento->Visible) { // descuento ?>
        <th data-name="descuento" class="<?= $Grid->descuento->headerCellClass() ?>"><div id="elh_tarifa_descuento_utilidad_descuento" class="tarifa_descuento_utilidad_descuento"><?= $Grid->renderFieldHeader($Grid->descuento) ?></div></th>
<?php } ?>
<?php if ($Grid->utilidad->Visible) { // utilidad ?>
        <th data-name="utilidad" class="<?= $Grid->utilidad->headerCellClass() ?>"><div id="elh_tarifa_descuento_utilidad_utilidad" class="tarifa_descuento_utilidad_utilidad"><?= $Grid->renderFieldHeader($Grid->utilidad) ?></div></th>
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
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_tarifa_descuento_utilidad_fabricante" class="el_tarifa_descuento_utilidad_fabricante">
    <select
        id="x<?= $Grid->RowIndex ?>_fabricante"
        name="x<?= $Grid->RowIndex ?>_fabricante"
        class="form-control ew-select<?= $Grid->fabricante->isInvalidClass() ?>"
        data-select2-id="ftarifa_descuento_utilidadgrid_x<?= $Grid->RowIndex ?>_fabricante"
        data-table="tarifa_descuento_utilidad"
        data-field="x_fabricante"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->fabricante->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->fabricante->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>"
        <?= $Grid->fabricante->editAttributes() ?>>
        <?= $Grid->fabricante->selectOptionListHtml("x{$Grid->RowIndex}_fabricante") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->fabricante->getErrorMessage() ?></div>
<?= $Grid->fabricante->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_fabricante") ?>
<script>
loadjs.ready("ftarifa_descuento_utilidadgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_fabricante", selectId: "ftarifa_descuento_utilidadgrid_x<?= $Grid->RowIndex ?>_fabricante" };
    if (ftarifa_descuento_utilidadgrid.lists.fabricante?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_fabricante", form: "ftarifa_descuento_utilidadgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_fabricante", form: "ftarifa_descuento_utilidadgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.tarifa_descuento_utilidad.fields.fabricante.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<input type="hidden" data-table="tarifa_descuento_utilidad" data-field="x_fabricante" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_fabricante" id="o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_tarifa_descuento_utilidad_fabricante" class="el_tarifa_descuento_utilidad_fabricante">
    <select
        id="x<?= $Grid->RowIndex ?>_fabricante"
        name="x<?= $Grid->RowIndex ?>_fabricante"
        class="form-control ew-select<?= $Grid->fabricante->isInvalidClass() ?>"
        data-select2-id="ftarifa_descuento_utilidadgrid_x<?= $Grid->RowIndex ?>_fabricante"
        data-table="tarifa_descuento_utilidad"
        data-field="x_fabricante"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->fabricante->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->fabricante->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>"
        <?= $Grid->fabricante->editAttributes() ?>>
        <?= $Grid->fabricante->selectOptionListHtml("x{$Grid->RowIndex}_fabricante") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->fabricante->getErrorMessage() ?></div>
<?= $Grid->fabricante->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_fabricante") ?>
<script>
loadjs.ready("ftarifa_descuento_utilidadgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_fabricante", selectId: "ftarifa_descuento_utilidadgrid_x<?= $Grid->RowIndex ?>_fabricante" };
    if (ftarifa_descuento_utilidadgrid.lists.fabricante?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_fabricante", form: "ftarifa_descuento_utilidadgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_fabricante", form: "ftarifa_descuento_utilidadgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.tarifa_descuento_utilidad.fields.fabricante.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_tarifa_descuento_utilidad_fabricante" class="el_tarifa_descuento_utilidad_fabricante">
<span<?= $Grid->fabricante->viewAttributes() ?>>
<?= $Grid->fabricante->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="tarifa_descuento_utilidad" data-field="x_fabricante" data-hidden="1" name="ftarifa_descuento_utilidadgrid$x<?= $Grid->RowIndex ?>_fabricante" id="ftarifa_descuento_utilidadgrid$x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->FormValue) ?>">
<input type="hidden" data-table="tarifa_descuento_utilidad" data-field="x_fabricante" data-hidden="1" data-old name="ftarifa_descuento_utilidadgrid$o<?= $Grid->RowIndex ?>_fabricante" id="ftarifa_descuento_utilidadgrid$o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->descuento->Visible) { // descuento ?>
        <td data-name="descuento"<?= $Grid->descuento->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_tarifa_descuento_utilidad_descuento" class="el_tarifa_descuento_utilidad_descuento">
<input type="<?= $Grid->descuento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_descuento" id="x<?= $Grid->RowIndex ?>_descuento" data-table="tarifa_descuento_utilidad" data-field="x_descuento" value="<?= $Grid->descuento->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Grid->descuento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->descuento->formatPattern()) ?>"<?= $Grid->descuento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->descuento->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="tarifa_descuento_utilidad" data-field="x_descuento" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_descuento" id="o<?= $Grid->RowIndex ?>_descuento" value="<?= HtmlEncode($Grid->descuento->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_tarifa_descuento_utilidad_descuento" class="el_tarifa_descuento_utilidad_descuento">
<input type="<?= $Grid->descuento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_descuento" id="x<?= $Grid->RowIndex ?>_descuento" data-table="tarifa_descuento_utilidad" data-field="x_descuento" value="<?= $Grid->descuento->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Grid->descuento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->descuento->formatPattern()) ?>"<?= $Grid->descuento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->descuento->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_tarifa_descuento_utilidad_descuento" class="el_tarifa_descuento_utilidad_descuento">
<span<?= $Grid->descuento->viewAttributes() ?>>
<?= $Grid->descuento->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="tarifa_descuento_utilidad" data-field="x_descuento" data-hidden="1" name="ftarifa_descuento_utilidadgrid$x<?= $Grid->RowIndex ?>_descuento" id="ftarifa_descuento_utilidadgrid$x<?= $Grid->RowIndex ?>_descuento" value="<?= HtmlEncode($Grid->descuento->FormValue) ?>">
<input type="hidden" data-table="tarifa_descuento_utilidad" data-field="x_descuento" data-hidden="1" data-old name="ftarifa_descuento_utilidadgrid$o<?= $Grid->RowIndex ?>_descuento" id="ftarifa_descuento_utilidadgrid$o<?= $Grid->RowIndex ?>_descuento" value="<?= HtmlEncode($Grid->descuento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->utilidad->Visible) { // utilidad ?>
        <td data-name="utilidad"<?= $Grid->utilidad->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_tarifa_descuento_utilidad_utilidad" class="el_tarifa_descuento_utilidad_utilidad">
<input type="<?= $Grid->utilidad->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_utilidad" id="x<?= $Grid->RowIndex ?>_utilidad" data-table="tarifa_descuento_utilidad" data-field="x_utilidad" value="<?= $Grid->utilidad->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Grid->utilidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->utilidad->formatPattern()) ?>"<?= $Grid->utilidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->utilidad->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="tarifa_descuento_utilidad" data-field="x_utilidad" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_utilidad" id="o<?= $Grid->RowIndex ?>_utilidad" value="<?= HtmlEncode($Grid->utilidad->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_tarifa_descuento_utilidad_utilidad" class="el_tarifa_descuento_utilidad_utilidad">
<input type="<?= $Grid->utilidad->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_utilidad" id="x<?= $Grid->RowIndex ?>_utilidad" data-table="tarifa_descuento_utilidad" data-field="x_utilidad" value="<?= $Grid->utilidad->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Grid->utilidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->utilidad->formatPattern()) ?>"<?= $Grid->utilidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->utilidad->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_tarifa_descuento_utilidad_utilidad" class="el_tarifa_descuento_utilidad_utilidad">
<span<?= $Grid->utilidad->viewAttributes() ?>>
<?= $Grid->utilidad->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="tarifa_descuento_utilidad" data-field="x_utilidad" data-hidden="1" name="ftarifa_descuento_utilidadgrid$x<?= $Grid->RowIndex ?>_utilidad" id="ftarifa_descuento_utilidadgrid$x<?= $Grid->RowIndex ?>_utilidad" value="<?= HtmlEncode($Grid->utilidad->FormValue) ?>">
<input type="hidden" data-table="tarifa_descuento_utilidad" data-field="x_utilidad" data-hidden="1" data-old name="ftarifa_descuento_utilidadgrid$o<?= $Grid->RowIndex ?>_utilidad" id="ftarifa_descuento_utilidadgrid$o<?= $Grid->RowIndex ?>_utilidad" value="<?= HtmlEncode($Grid->utilidad->OldValue) ?>">
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
loadjs.ready(["ftarifa_descuento_utilidadgrid","load"], () => ftarifa_descuento_utilidadgrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="ftarifa_descuento_utilidadgrid">
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
    ew.addEventHandlers("tarifa_descuento_utilidad");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
