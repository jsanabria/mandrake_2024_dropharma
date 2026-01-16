<?php

namespace PHPMaker2024\mandrake;

// Set up and run Grid object
$Grid = Container("CobrosClienteFacturaGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fcobros_cliente_facturagrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { cobros_cliente_factura: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcobros_cliente_facturagrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["tipo_documento", [fields.tipo_documento.visible && fields.tipo_documento.required ? ew.Validators.required(fields.tipo_documento.caption) : null], fields.tipo_documento.isInvalid],
            ["abono", [fields.abono.visible && fields.abono.required ? ew.Validators.required(fields.abono.caption) : null], fields.abono.isInvalid],
            ["monto", [fields.monto.visible && fields.monto.required ? ew.Validators.required(fields.monto.caption) : null], fields.monto.isInvalid],
            ["retivamonto", [fields.retivamonto.visible && fields.retivamonto.required ? ew.Validators.required(fields.retivamonto.caption) : null], fields.retivamonto.isInvalid],
            ["retiva", [fields.retiva.visible && fields.retiva.required ? ew.Validators.required(fields.retiva.caption) : null], fields.retiva.isInvalid],
            ["retislrmonto", [fields.retislrmonto.visible && fields.retislrmonto.required ? ew.Validators.required(fields.retislrmonto.caption) : null], fields.retislrmonto.isInvalid],
            ["retislr", [fields.retislr.visible && fields.retislr.required ? ew.Validators.required(fields.retislr.caption) : null], fields.retislr.isInvalid],
            ["comprobante", [fields.comprobante.visible && fields.comprobante.required ? ew.Validators.required(fields.comprobante.caption) : null], fields.comprobante.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["tipo_documento",false],["abono",false],["monto",false],["retivamonto",false],["retiva",false],["retislrmonto",false],["retislr",false],["comprobante",false]];
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
            "tipo_documento": <?= $Grid->tipo_documento->toClientList($Grid) ?>,
            "abono": <?= $Grid->abono->toClientList($Grid) ?>,
            "comprobante": <?= $Grid->comprobante->toClientList($Grid) ?>,
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
<div id="fcobros_cliente_facturagrid" class="ew-form ew-list-form">
<div id="gmp_cobros_cliente_factura" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_cobros_cliente_facturagrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Grid->tipo_documento->Visible) { // tipo_documento ?>
        <th data-name="tipo_documento" class="<?= $Grid->tipo_documento->headerCellClass() ?>"><div id="elh_cobros_cliente_factura_tipo_documento" class="cobros_cliente_factura_tipo_documento"><?= $Grid->renderFieldHeader($Grid->tipo_documento) ?></div></th>
<?php } ?>
<?php if ($Grid->abono->Visible) { // abono ?>
        <th data-name="abono" class="<?= $Grid->abono->headerCellClass() ?>"><div id="elh_cobros_cliente_factura_abono" class="cobros_cliente_factura_abono"><?= $Grid->renderFieldHeader($Grid->abono) ?></div></th>
<?php } ?>
<?php if ($Grid->monto->Visible) { // monto ?>
        <th data-name="monto" class="<?= $Grid->monto->headerCellClass() ?>"><div id="elh_cobros_cliente_factura_monto" class="cobros_cliente_factura_monto"><?= $Grid->renderFieldHeader($Grid->monto) ?></div></th>
<?php } ?>
<?php if ($Grid->retivamonto->Visible) { // retivamonto ?>
        <th data-name="retivamonto" class="<?= $Grid->retivamonto->headerCellClass() ?>"><div id="elh_cobros_cliente_factura_retivamonto" class="cobros_cliente_factura_retivamonto"><?= $Grid->renderFieldHeader($Grid->retivamonto) ?></div></th>
<?php } ?>
<?php if ($Grid->retiva->Visible) { // retiva ?>
        <th data-name="retiva" class="<?= $Grid->retiva->headerCellClass() ?>"><div id="elh_cobros_cliente_factura_retiva" class="cobros_cliente_factura_retiva"><?= $Grid->renderFieldHeader($Grid->retiva) ?></div></th>
<?php } ?>
<?php if ($Grid->retislrmonto->Visible) { // retislrmonto ?>
        <th data-name="retislrmonto" class="<?= $Grid->retislrmonto->headerCellClass() ?>"><div id="elh_cobros_cliente_factura_retislrmonto" class="cobros_cliente_factura_retislrmonto"><?= $Grid->renderFieldHeader($Grid->retislrmonto) ?></div></th>
<?php } ?>
<?php if ($Grid->retislr->Visible) { // retislr ?>
        <th data-name="retislr" class="<?= $Grid->retislr->headerCellClass() ?>"><div id="elh_cobros_cliente_factura_retislr" class="cobros_cliente_factura_retislr"><?= $Grid->renderFieldHeader($Grid->retislr) ?></div></th>
<?php } ?>
<?php if ($Grid->comprobante->Visible) { // comprobante ?>
        <th data-name="comprobante" class="<?= $Grid->comprobante->headerCellClass() ?>"><div id="elh_cobros_cliente_factura_comprobante" class="cobros_cliente_factura_comprobante"><?= $Grid->renderFieldHeader($Grid->comprobante) ?></div></th>
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
    <?php if ($Grid->tipo_documento->Visible) { // tipo_documento ?>
        <td data-name="tipo_documento"<?= $Grid->tipo_documento->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cobros_cliente_factura_tipo_documento" class="el_cobros_cliente_factura_tipo_documento">
<?php
if (IsRTL()) {
    $Grid->tipo_documento->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x<?= $Grid->RowIndex ?>_tipo_documento" class="ew-auto-suggest">
    <input type="<?= $Grid->tipo_documento->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_tipo_documento" id="sv_x<?= $Grid->RowIndex ?>_tipo_documento" value="<?= RemoveHtml($Grid->tipo_documento->EditValue) ?>" autocomplete="off" size="30" maxlength="6" placeholder="<?= HtmlEncode($Grid->tipo_documento->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->tipo_documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->tipo_documento->formatPattern()) ?>"<?= $Grid->tipo_documento->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="cobros_cliente_factura" data-field="x_tipo_documento" data-input="sv_x<?= $Grid->RowIndex ?>_tipo_documento" data-value-separator="<?= $Grid->tipo_documento->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_tipo_documento" id="x<?= $Grid->RowIndex ?>_tipo_documento" value="<?= HtmlEncode($Grid->tipo_documento->CurrentValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Grid->tipo_documento->getErrorMessage() ?></div>
<script>
loadjs.ready("fcobros_cliente_facturagrid", function() {
    fcobros_cliente_facturagrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_tipo_documento","forceSelect":false}, { lookupAllDisplayFields: <?= $Grid->tipo_documento->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.cobros_cliente_factura.fields.tipo_documento.autoSuggestOptions));
});
</script>
<?= $Grid->tipo_documento->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_tipo_documento") ?>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_tipo_documento" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_tipo_documento" id="o<?= $Grid->RowIndex ?>_tipo_documento" value="<?= HtmlEncode($Grid->tipo_documento->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cobros_cliente_factura_tipo_documento" class="el_cobros_cliente_factura_tipo_documento">
<span<?= $Grid->tipo_documento->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->tipo_documento->getDisplayValue($Grid->tipo_documento->EditValue) ?></span></span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_tipo_documento" data-hidden="1" name="x<?= $Grid->RowIndex ?>_tipo_documento" id="x<?= $Grid->RowIndex ?>_tipo_documento" value="<?= HtmlEncode($Grid->tipo_documento->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cobros_cliente_factura_tipo_documento" class="el_cobros_cliente_factura_tipo_documento">
<span<?= $Grid->tipo_documento->viewAttributes() ?>>
<?= $Grid->tipo_documento->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_tipo_documento" data-hidden="1" name="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_tipo_documento" id="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_tipo_documento" value="<?= HtmlEncode($Grid->tipo_documento->FormValue) ?>">
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_tipo_documento" data-hidden="1" data-old name="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_tipo_documento" id="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_tipo_documento" value="<?= HtmlEncode($Grid->tipo_documento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->abono->Visible) { // abono ?>
        <td data-name="abono"<?= $Grid->abono->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cobros_cliente_factura_abono" class="el_cobros_cliente_factura_abono">
<template id="tp_x<?= $Grid->RowIndex ?>_abono">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="cobros_cliente_factura" data-field="x_abono" name="x<?= $Grid->RowIndex ?>_abono" id="x<?= $Grid->RowIndex ?>_abono"<?= $Grid->abono->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_abono" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Grid->RowIndex ?>_abono"
    name="x<?= $Grid->RowIndex ?>_abono"
    value="<?= HtmlEncode($Grid->abono->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_abono"
    data-target="dsl_x<?= $Grid->RowIndex ?>_abono"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->abono->isInvalidClass() ?>"
    data-table="cobros_cliente_factura"
    data-field="x_abono"
    data-value-separator="<?= $Grid->abono->displayValueSeparatorAttribute() ?>"
    <?= $Grid->abono->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->abono->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_abono" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_abono" id="o<?= $Grid->RowIndex ?>_abono" value="<?= HtmlEncode($Grid->abono->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cobros_cliente_factura_abono" class="el_cobros_cliente_factura_abono">
<span<?= $Grid->abono->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->abono->getDisplayValue($Grid->abono->EditValue) ?></span></span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_abono" data-hidden="1" name="x<?= $Grid->RowIndex ?>_abono" id="x<?= $Grid->RowIndex ?>_abono" value="<?= HtmlEncode($Grid->abono->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cobros_cliente_factura_abono" class="el_cobros_cliente_factura_abono">
<span<?= $Grid->abono->viewAttributes() ?>>
<?= $Grid->abono->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_abono" data-hidden="1" name="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_abono" id="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_abono" value="<?= HtmlEncode($Grid->abono->FormValue) ?>">
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_abono" data-hidden="1" data-old name="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_abono" id="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_abono" value="<?= HtmlEncode($Grid->abono->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->monto->Visible) { // monto ?>
        <td data-name="monto"<?= $Grid->monto->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cobros_cliente_factura_monto" class="el_cobros_cliente_factura_monto">
<input type="<?= $Grid->monto->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_monto" id="x<?= $Grid->RowIndex ?>_monto" data-table="cobros_cliente_factura" data-field="x_monto" value="<?= $Grid->monto->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->monto->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->monto->formatPattern()) ?>"<?= $Grid->monto->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_monto" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_monto" id="o<?= $Grid->RowIndex ?>_monto" value="<?= HtmlEncode($Grid->monto->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cobros_cliente_factura_monto" class="el_cobros_cliente_factura_monto">
<span<?= $Grid->monto->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->monto->getDisplayValue($Grid->monto->EditValue))) ?>"></span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_monto" data-hidden="1" name="x<?= $Grid->RowIndex ?>_monto" id="x<?= $Grid->RowIndex ?>_monto" value="<?= HtmlEncode($Grid->monto->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cobros_cliente_factura_monto" class="el_cobros_cliente_factura_monto">
<span<?= $Grid->monto->viewAttributes() ?>>
<?= $Grid->monto->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_monto" data-hidden="1" name="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_monto" id="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_monto" value="<?= HtmlEncode($Grid->monto->FormValue) ?>">
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_monto" data-hidden="1" data-old name="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_monto" id="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_monto" value="<?= HtmlEncode($Grid->monto->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->retivamonto->Visible) { // retivamonto ?>
        <td data-name="retivamonto"<?= $Grid->retivamonto->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cobros_cliente_factura_retivamonto" class="el_cobros_cliente_factura_retivamonto">
<input type="<?= $Grid->retivamonto->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_retivamonto" id="x<?= $Grid->RowIndex ?>_retivamonto" data-table="cobros_cliente_factura" data-field="x_retivamonto" value="<?= $Grid->retivamonto->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->retivamonto->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->retivamonto->formatPattern()) ?>"<?= $Grid->retivamonto->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->retivamonto->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retivamonto" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_retivamonto" id="o<?= $Grid->RowIndex ?>_retivamonto" value="<?= HtmlEncode($Grid->retivamonto->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cobros_cliente_factura_retivamonto" class="el_cobros_cliente_factura_retivamonto">
<span<?= $Grid->retivamonto->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->retivamonto->getDisplayValue($Grid->retivamonto->EditValue))) ?>"></span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retivamonto" data-hidden="1" name="x<?= $Grid->RowIndex ?>_retivamonto" id="x<?= $Grid->RowIndex ?>_retivamonto" value="<?= HtmlEncode($Grid->retivamonto->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cobros_cliente_factura_retivamonto" class="el_cobros_cliente_factura_retivamonto">
<span<?= $Grid->retivamonto->viewAttributes() ?>>
<?= $Grid->retivamonto->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retivamonto" data-hidden="1" name="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_retivamonto" id="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_retivamonto" value="<?= HtmlEncode($Grid->retivamonto->FormValue) ?>">
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retivamonto" data-hidden="1" data-old name="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_retivamonto" id="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_retivamonto" value="<?= HtmlEncode($Grid->retivamonto->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->retiva->Visible) { // retiva ?>
        <td data-name="retiva"<?= $Grid->retiva->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cobros_cliente_factura_retiva" class="el_cobros_cliente_factura_retiva">
<input type="<?= $Grid->retiva->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_retiva" id="x<?= $Grid->RowIndex ?>_retiva" data-table="cobros_cliente_factura" data-field="x_retiva" value="<?= $Grid->retiva->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Grid->retiva->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->retiva->formatPattern()) ?>"<?= $Grid->retiva->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->retiva->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retiva" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_retiva" id="o<?= $Grid->RowIndex ?>_retiva" value="<?= HtmlEncode($Grid->retiva->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cobros_cliente_factura_retiva" class="el_cobros_cliente_factura_retiva">
<input type="<?= $Grid->retiva->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_retiva" id="x<?= $Grid->RowIndex ?>_retiva" data-table="cobros_cliente_factura" data-field="x_retiva" value="<?= $Grid->retiva->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Grid->retiva->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->retiva->formatPattern()) ?>"<?= $Grid->retiva->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->retiva->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cobros_cliente_factura_retiva" class="el_cobros_cliente_factura_retiva">
<span<?= $Grid->retiva->viewAttributes() ?>>
<?= $Grid->retiva->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retiva" data-hidden="1" name="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_retiva" id="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_retiva" value="<?= HtmlEncode($Grid->retiva->FormValue) ?>">
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retiva" data-hidden="1" data-old name="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_retiva" id="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_retiva" value="<?= HtmlEncode($Grid->retiva->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->retislrmonto->Visible) { // retislrmonto ?>
        <td data-name="retislrmonto"<?= $Grid->retislrmonto->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cobros_cliente_factura_retislrmonto" class="el_cobros_cliente_factura_retislrmonto">
<input type="<?= $Grid->retislrmonto->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_retislrmonto" id="x<?= $Grid->RowIndex ?>_retislrmonto" data-table="cobros_cliente_factura" data-field="x_retislrmonto" value="<?= $Grid->retislrmonto->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->retislrmonto->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->retislrmonto->formatPattern()) ?>"<?= $Grid->retislrmonto->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->retislrmonto->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retislrmonto" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_retislrmonto" id="o<?= $Grid->RowIndex ?>_retislrmonto" value="<?= HtmlEncode($Grid->retislrmonto->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cobros_cliente_factura_retislrmonto" class="el_cobros_cliente_factura_retislrmonto">
<span<?= $Grid->retislrmonto->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->retislrmonto->getDisplayValue($Grid->retislrmonto->EditValue))) ?>"></span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retislrmonto" data-hidden="1" name="x<?= $Grid->RowIndex ?>_retislrmonto" id="x<?= $Grid->RowIndex ?>_retislrmonto" value="<?= HtmlEncode($Grid->retislrmonto->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cobros_cliente_factura_retislrmonto" class="el_cobros_cliente_factura_retislrmonto">
<span<?= $Grid->retislrmonto->viewAttributes() ?>>
<?= $Grid->retislrmonto->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retislrmonto" data-hidden="1" name="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_retislrmonto" id="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_retislrmonto" value="<?= HtmlEncode($Grid->retislrmonto->FormValue) ?>">
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retislrmonto" data-hidden="1" data-old name="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_retislrmonto" id="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_retislrmonto" value="<?= HtmlEncode($Grid->retislrmonto->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->retislr->Visible) { // retislr ?>
        <td data-name="retislr"<?= $Grid->retislr->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cobros_cliente_factura_retislr" class="el_cobros_cliente_factura_retislr">
<input type="<?= $Grid->retislr->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_retislr" id="x<?= $Grid->RowIndex ?>_retislr" data-table="cobros_cliente_factura" data-field="x_retislr" value="<?= $Grid->retislr->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Grid->retislr->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->retislr->formatPattern()) ?>"<?= $Grid->retislr->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->retislr->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retislr" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_retislr" id="o<?= $Grid->RowIndex ?>_retislr" value="<?= HtmlEncode($Grid->retislr->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cobros_cliente_factura_retislr" class="el_cobros_cliente_factura_retislr">
<input type="<?= $Grid->retislr->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_retislr" id="x<?= $Grid->RowIndex ?>_retislr" data-table="cobros_cliente_factura" data-field="x_retislr" value="<?= $Grid->retislr->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Grid->retislr->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->retislr->formatPattern()) ?>"<?= $Grid->retislr->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->retislr->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cobros_cliente_factura_retislr" class="el_cobros_cliente_factura_retislr">
<span<?= $Grid->retislr->viewAttributes() ?>>
<?= $Grid->retislr->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retislr" data-hidden="1" name="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_retislr" id="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_retislr" value="<?= HtmlEncode($Grid->retislr->FormValue) ?>">
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retislr" data-hidden="1" data-old name="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_retislr" id="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_retislr" value="<?= HtmlEncode($Grid->retislr->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->comprobante->Visible) { // comprobante ?>
        <td data-name="comprobante"<?= $Grid->comprobante->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cobros_cliente_factura_comprobante" class="el_cobros_cliente_factura_comprobante">
<?php
if (IsRTL()) {
    $Grid->comprobante->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x<?= $Grid->RowIndex ?>_comprobante" class="ew-auto-suggest">
    <input type="<?= $Grid->comprobante->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_comprobante" id="sv_x<?= $Grid->RowIndex ?>_comprobante" value="<?= RemoveHtml($Grid->comprobante->EditValue) ?>" autocomplete="off" size="30" placeholder="<?= HtmlEncode($Grid->comprobante->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->comprobante->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->comprobante->formatPattern()) ?>"<?= $Grid->comprobante->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="cobros_cliente_factura" data-field="x_comprobante" data-input="sv_x<?= $Grid->RowIndex ?>_comprobante" data-value-separator="<?= $Grid->comprobante->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_comprobante" id="x<?= $Grid->RowIndex ?>_comprobante" value="<?= HtmlEncode($Grid->comprobante->CurrentValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Grid->comprobante->getErrorMessage() ?></div>
<script>
loadjs.ready("fcobros_cliente_facturagrid", function() {
    fcobros_cliente_facturagrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_comprobante","forceSelect":false}, { lookupAllDisplayFields: <?= $Grid->comprobante->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.cobros_cliente_factura.fields.comprobante.autoSuggestOptions));
});
</script>
<?= $Grid->comprobante->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_comprobante") ?>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_comprobante" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_comprobante" id="o<?= $Grid->RowIndex ?>_comprobante" value="<?= HtmlEncode($Grid->comprobante->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cobros_cliente_factura_comprobante" class="el_cobros_cliente_factura_comprobante">
<span<?= $Grid->comprobante->viewAttributes() ?>>
<?php if (!EmptyString($Grid->comprobante->EditValue) && $Grid->comprobante->linkAttributes() != "") { ?>
<a<?= $Grid->comprobante->linkAttributes() ?>><span class="form-control-plaintext"><?= $Grid->comprobante->getDisplayValue($Grid->comprobante->EditValue) ?></span></a>
<?php } else { ?>
<span class="form-control-plaintext"><?= $Grid->comprobante->getDisplayValue($Grid->comprobante->EditValue) ?></span>
<?php } ?>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_comprobante" data-hidden="1" name="x<?= $Grid->RowIndex ?>_comprobante" id="x<?= $Grid->RowIndex ?>_comprobante" value="<?= HtmlEncode($Grid->comprobante->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cobros_cliente_factura_comprobante" class="el_cobros_cliente_factura_comprobante">
<span<?= $Grid->comprobante->viewAttributes() ?>>
<?php if (!EmptyString($Grid->comprobante->getViewValue()) && $Grid->comprobante->linkAttributes() != "") { ?>
<a<?= $Grid->comprobante->linkAttributes() ?>><?= $Grid->comprobante->getViewValue() ?></a>
<?php } else { ?>
<?= $Grid->comprobante->getViewValue() ?>
<?php } ?>
</span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_comprobante" data-hidden="1" name="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_comprobante" id="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_comprobante" value="<?= HtmlEncode($Grid->comprobante->FormValue) ?>">
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_comprobante" data-hidden="1" data-old name="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_comprobante" id="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_comprobante" value="<?= HtmlEncode($Grid->comprobante->OldValue) ?>">
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
loadjs.ready(["fcobros_cliente_facturagrid","load"], () => fcobros_cliente_facturagrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="fcobros_cliente_facturagrid">
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
    ew.addEventHandlers("cobros_cliente_factura");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
