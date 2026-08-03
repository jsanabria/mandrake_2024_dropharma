<?php

namespace PHPMaker2024\mandrake;

// Set up and run Grid object
$Grid = Container("PagosComprasDetalleGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fpagos_compras_detallegrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { pagos_compras_detalle: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpagos_compras_detallegrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["metodo_pago", [fields.metodo_pago.visible && fields.metodo_pago.required ? ew.Validators.required(fields.metodo_pago.caption) : null], fields.metodo_pago.isInvalid],
            ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
            ["monto_moneda", [fields.monto_moneda.visible && fields.monto_moneda.required ? ew.Validators.required(fields.monto_moneda.caption) : null, ew.Validators.float], fields.monto_moneda.isInvalid],
            ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
            ["tasa_moneda", [fields.tasa_moneda.visible && fields.tasa_moneda.required ? ew.Validators.required(fields.tasa_moneda.caption) : null, ew.Validators.float], fields.tasa_moneda.isInvalid],
            ["monto_bs", [fields.monto_bs.visible && fields.monto_bs.required ? ew.Validators.required(fields.monto_bs.caption) : null, ew.Validators.float], fields.monto_bs.isInvalid],
            ["tasa_usd", [fields.tasa_usd.visible && fields.tasa_usd.required ? ew.Validators.required(fields.tasa_usd.caption) : null, ew.Validators.float], fields.tasa_usd.isInvalid],
            ["monto_usd", [fields.monto_usd.visible && fields.monto_usd.required ? ew.Validators.required(fields.monto_usd.caption) : null, ew.Validators.float], fields.monto_usd.isInvalid],
            ["banco", [fields.banco.visible && fields.banco.required ? ew.Validators.required(fields.banco.caption) : null, ew.Validators.integer], fields.banco.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["metodo_pago",false],["referencia",false],["monto_moneda",false],["moneda",false],["tasa_moneda",false],["monto_bs",false],["tasa_usd",false],["monto_usd",false],["banco",false]];
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
            "metodo_pago": <?= $Grid->metodo_pago->toClientList($Grid) ?>,
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
<div id="fpagos_compras_detallegrid" class="ew-form ew-list-form">
<div id="gmp_pagos_compras_detalle" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_pagos_compras_detallegrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Grid->metodo_pago->Visible) { // metodo_pago ?>
        <th data-name="metodo_pago" class="<?= $Grid->metodo_pago->headerCellClass() ?>"><div id="elh_pagos_compras_detalle_metodo_pago" class="pagos_compras_detalle_metodo_pago"><?= $Grid->renderFieldHeader($Grid->metodo_pago) ?></div></th>
<?php } ?>
<?php if ($Grid->referencia->Visible) { // referencia ?>
        <th data-name="referencia" class="<?= $Grid->referencia->headerCellClass() ?>"><div id="elh_pagos_compras_detalle_referencia" class="pagos_compras_detalle_referencia"><?= $Grid->renderFieldHeader($Grid->referencia) ?></div></th>
<?php } ?>
<?php if ($Grid->monto_moneda->Visible) { // monto_moneda ?>
        <th data-name="monto_moneda" class="<?= $Grid->monto_moneda->headerCellClass() ?>"><div id="elh_pagos_compras_detalle_monto_moneda" class="pagos_compras_detalle_monto_moneda"><?= $Grid->renderFieldHeader($Grid->monto_moneda) ?></div></th>
<?php } ?>
<?php if ($Grid->moneda->Visible) { // moneda ?>
        <th data-name="moneda" class="<?= $Grid->moneda->headerCellClass() ?>"><div id="elh_pagos_compras_detalle_moneda" class="pagos_compras_detalle_moneda"><?= $Grid->renderFieldHeader($Grid->moneda) ?></div></th>
<?php } ?>
<?php if ($Grid->tasa_moneda->Visible) { // tasa_moneda ?>
        <th data-name="tasa_moneda" class="<?= $Grid->tasa_moneda->headerCellClass() ?>"><div id="elh_pagos_compras_detalle_tasa_moneda" class="pagos_compras_detalle_tasa_moneda"><?= $Grid->renderFieldHeader($Grid->tasa_moneda) ?></div></th>
<?php } ?>
<?php if ($Grid->monto_bs->Visible) { // monto_bs ?>
        <th data-name="monto_bs" class="<?= $Grid->monto_bs->headerCellClass() ?>"><div id="elh_pagos_compras_detalle_monto_bs" class="pagos_compras_detalle_monto_bs"><?= $Grid->renderFieldHeader($Grid->monto_bs) ?></div></th>
<?php } ?>
<?php if ($Grid->tasa_usd->Visible) { // tasa_usd ?>
        <th data-name="tasa_usd" class="<?= $Grid->tasa_usd->headerCellClass() ?>"><div id="elh_pagos_compras_detalle_tasa_usd" class="pagos_compras_detalle_tasa_usd"><?= $Grid->renderFieldHeader($Grid->tasa_usd) ?></div></th>
<?php } ?>
<?php if ($Grid->monto_usd->Visible) { // monto_usd ?>
        <th data-name="monto_usd" class="<?= $Grid->monto_usd->headerCellClass() ?>"><div id="elh_pagos_compras_detalle_monto_usd" class="pagos_compras_detalle_monto_usd"><?= $Grid->renderFieldHeader($Grid->monto_usd) ?></div></th>
<?php } ?>
<?php if ($Grid->banco->Visible) { // banco ?>
        <th data-name="banco" class="<?= $Grid->banco->headerCellClass() ?>"><div id="elh_pagos_compras_detalle_banco" class="pagos_compras_detalle_banco"><?= $Grid->renderFieldHeader($Grid->banco) ?></div></th>
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
    <?php if ($Grid->metodo_pago->Visible) { // metodo_pago ?>
        <td data-name="metodo_pago"<?= $Grid->metodo_pago->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_metodo_pago" class="el_pagos_compras_detalle_metodo_pago">
<?php
if (IsRTL()) {
    $Grid->metodo_pago->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x<?= $Grid->RowIndex ?>_metodo_pago" class="ew-auto-suggest">
    <input type="<?= $Grid->metodo_pago->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_metodo_pago" id="sv_x<?= $Grid->RowIndex ?>_metodo_pago" value="<?= RemoveHtml($Grid->metodo_pago->EditValue) ?>" autocomplete="off" size="30" maxlength="10" placeholder="<?= HtmlEncode($Grid->metodo_pago->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->metodo_pago->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->metodo_pago->formatPattern()) ?>"<?= $Grid->metodo_pago->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="pagos_compras_detalle" data-field="x_metodo_pago" data-input="sv_x<?= $Grid->RowIndex ?>_metodo_pago" data-value-separator="<?= $Grid->metodo_pago->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_metodo_pago" id="x<?= $Grid->RowIndex ?>_metodo_pago" value="<?= HtmlEncode($Grid->metodo_pago->CurrentValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Grid->metodo_pago->getErrorMessage() ?></div>
<script>
loadjs.ready("fpagos_compras_detallegrid", function() {
    fpagos_compras_detallegrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_metodo_pago","forceSelect":false}, { lookupAllDisplayFields: <?= $Grid->metodo_pago->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.pagos_compras_detalle.fields.metodo_pago.autoSuggestOptions));
});
</script>
<?= $Grid->metodo_pago->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_metodo_pago") ?>
</span>
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_metodo_pago" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_metodo_pago" id="o<?= $Grid->RowIndex ?>_metodo_pago" value="<?= HtmlEncode($Grid->metodo_pago->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_metodo_pago" class="el_pagos_compras_detalle_metodo_pago">
<?php
if (IsRTL()) {
    $Grid->metodo_pago->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x<?= $Grid->RowIndex ?>_metodo_pago" class="ew-auto-suggest">
    <input type="<?= $Grid->metodo_pago->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_metodo_pago" id="sv_x<?= $Grid->RowIndex ?>_metodo_pago" value="<?= RemoveHtml($Grid->metodo_pago->EditValue) ?>" autocomplete="off" size="30" maxlength="10" placeholder="<?= HtmlEncode($Grid->metodo_pago->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->metodo_pago->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->metodo_pago->formatPattern()) ?>"<?= $Grid->metodo_pago->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="pagos_compras_detalle" data-field="x_metodo_pago" data-input="sv_x<?= $Grid->RowIndex ?>_metodo_pago" data-value-separator="<?= $Grid->metodo_pago->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_metodo_pago" id="x<?= $Grid->RowIndex ?>_metodo_pago" value="<?= HtmlEncode($Grid->metodo_pago->CurrentValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Grid->metodo_pago->getErrorMessage() ?></div>
<script>
loadjs.ready("fpagos_compras_detallegrid", function() {
    fpagos_compras_detallegrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_metodo_pago","forceSelect":false}, { lookupAllDisplayFields: <?= $Grid->metodo_pago->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.pagos_compras_detalle.fields.metodo_pago.autoSuggestOptions));
});
</script>
<?= $Grid->metodo_pago->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_metodo_pago") ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_metodo_pago" class="el_pagos_compras_detalle_metodo_pago">
<span<?= $Grid->metodo_pago->viewAttributes() ?>>
<?= $Grid->metodo_pago->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_metodo_pago" data-hidden="1" name="fpagos_compras_detallegrid$x<?= $Grid->RowIndex ?>_metodo_pago" id="fpagos_compras_detallegrid$x<?= $Grid->RowIndex ?>_metodo_pago" value="<?= HtmlEncode($Grid->metodo_pago->FormValue) ?>">
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_metodo_pago" data-hidden="1" data-old name="fpagos_compras_detallegrid$o<?= $Grid->RowIndex ?>_metodo_pago" id="fpagos_compras_detallegrid$o<?= $Grid->RowIndex ?>_metodo_pago" value="<?= HtmlEncode($Grid->metodo_pago->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->referencia->Visible) { // referencia ?>
        <td data-name="referencia"<?= $Grid->referencia->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_referencia" class="el_pagos_compras_detalle_referencia">
<input type="<?= $Grid->referencia->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_referencia" id="x<?= $Grid->RowIndex ?>_referencia" data-table="pagos_compras_detalle" data-field="x_referencia" value="<?= $Grid->referencia->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->referencia->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->referencia->formatPattern()) ?>"<?= $Grid->referencia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->referencia->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_referencia" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_referencia" id="o<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_referencia" class="el_pagos_compras_detalle_referencia">
<input type="<?= $Grid->referencia->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_referencia" id="x<?= $Grid->RowIndex ?>_referencia" data-table="pagos_compras_detalle" data-field="x_referencia" value="<?= $Grid->referencia->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->referencia->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->referencia->formatPattern()) ?>"<?= $Grid->referencia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->referencia->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_referencia" class="el_pagos_compras_detalle_referencia">
<span<?= $Grid->referencia->viewAttributes() ?>>
<?= $Grid->referencia->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_referencia" data-hidden="1" name="fpagos_compras_detallegrid$x<?= $Grid->RowIndex ?>_referencia" id="fpagos_compras_detallegrid$x<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->FormValue) ?>">
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_referencia" data-hidden="1" data-old name="fpagos_compras_detallegrid$o<?= $Grid->RowIndex ?>_referencia" id="fpagos_compras_detallegrid$o<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->monto_moneda->Visible) { // monto_moneda ?>
        <td data-name="monto_moneda"<?= $Grid->monto_moneda->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_monto_moneda" class="el_pagos_compras_detalle_monto_moneda">
<input type="<?= $Grid->monto_moneda->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_monto_moneda" id="x<?= $Grid->RowIndex ?>_monto_moneda" data-table="pagos_compras_detalle" data-field="x_monto_moneda" value="<?= $Grid->monto_moneda->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->monto_moneda->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->monto_moneda->formatPattern()) ?>"<?= $Grid->monto_moneda->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_moneda->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_monto_moneda" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_monto_moneda" id="o<?= $Grid->RowIndex ?>_monto_moneda" value="<?= HtmlEncode($Grid->monto_moneda->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_monto_moneda" class="el_pagos_compras_detalle_monto_moneda">
<input type="<?= $Grid->monto_moneda->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_monto_moneda" id="x<?= $Grid->RowIndex ?>_monto_moneda" data-table="pagos_compras_detalle" data-field="x_monto_moneda" value="<?= $Grid->monto_moneda->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->monto_moneda->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->monto_moneda->formatPattern()) ?>"<?= $Grid->monto_moneda->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_moneda->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_monto_moneda" class="el_pagos_compras_detalle_monto_moneda">
<span<?= $Grid->monto_moneda->viewAttributes() ?>>
<?= $Grid->monto_moneda->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_monto_moneda" data-hidden="1" name="fpagos_compras_detallegrid$x<?= $Grid->RowIndex ?>_monto_moneda" id="fpagos_compras_detallegrid$x<?= $Grid->RowIndex ?>_monto_moneda" value="<?= HtmlEncode($Grid->monto_moneda->FormValue) ?>">
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_monto_moneda" data-hidden="1" data-old name="fpagos_compras_detallegrid$o<?= $Grid->RowIndex ?>_monto_moneda" id="fpagos_compras_detallegrid$o<?= $Grid->RowIndex ?>_monto_moneda" value="<?= HtmlEncode($Grid->monto_moneda->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->moneda->Visible) { // moneda ?>
        <td data-name="moneda"<?= $Grid->moneda->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_moneda" class="el_pagos_compras_detalle_moneda">
<input type="<?= $Grid->moneda->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_moneda" id="x<?= $Grid->RowIndex ?>_moneda" data-table="pagos_compras_detalle" data-field="x_moneda" value="<?= $Grid->moneda->EditValue ?>" size="30" maxlength="6" placeholder="<?= HtmlEncode($Grid->moneda->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->moneda->formatPattern()) ?>"<?= $Grid->moneda->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->moneda->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_moneda" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_moneda" id="o<?= $Grid->RowIndex ?>_moneda" value="<?= HtmlEncode($Grid->moneda->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_moneda" class="el_pagos_compras_detalle_moneda">
<input type="<?= $Grid->moneda->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_moneda" id="x<?= $Grid->RowIndex ?>_moneda" data-table="pagos_compras_detalle" data-field="x_moneda" value="<?= $Grid->moneda->EditValue ?>" size="30" maxlength="6" placeholder="<?= HtmlEncode($Grid->moneda->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->moneda->formatPattern()) ?>"<?= $Grid->moneda->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->moneda->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_moneda" class="el_pagos_compras_detalle_moneda">
<span<?= $Grid->moneda->viewAttributes() ?>>
<?= $Grid->moneda->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_moneda" data-hidden="1" name="fpagos_compras_detallegrid$x<?= $Grid->RowIndex ?>_moneda" id="fpagos_compras_detallegrid$x<?= $Grid->RowIndex ?>_moneda" value="<?= HtmlEncode($Grid->moneda->FormValue) ?>">
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_moneda" data-hidden="1" data-old name="fpagos_compras_detallegrid$o<?= $Grid->RowIndex ?>_moneda" id="fpagos_compras_detallegrid$o<?= $Grid->RowIndex ?>_moneda" value="<?= HtmlEncode($Grid->moneda->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->tasa_moneda->Visible) { // tasa_moneda ?>
        <td data-name="tasa_moneda"<?= $Grid->tasa_moneda->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_tasa_moneda" class="el_pagos_compras_detalle_tasa_moneda">
<input type="<?= $Grid->tasa_moneda->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_tasa_moneda" id="x<?= $Grid->RowIndex ?>_tasa_moneda" data-table="pagos_compras_detalle" data-field="x_tasa_moneda" value="<?= $Grid->tasa_moneda->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->tasa_moneda->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->tasa_moneda->formatPattern()) ?>"<?= $Grid->tasa_moneda->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->tasa_moneda->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_tasa_moneda" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_tasa_moneda" id="o<?= $Grid->RowIndex ?>_tasa_moneda" value="<?= HtmlEncode($Grid->tasa_moneda->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_tasa_moneda" class="el_pagos_compras_detalle_tasa_moneda">
<input type="<?= $Grid->tasa_moneda->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_tasa_moneda" id="x<?= $Grid->RowIndex ?>_tasa_moneda" data-table="pagos_compras_detalle" data-field="x_tasa_moneda" value="<?= $Grid->tasa_moneda->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->tasa_moneda->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->tasa_moneda->formatPattern()) ?>"<?= $Grid->tasa_moneda->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->tasa_moneda->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_tasa_moneda" class="el_pagos_compras_detalle_tasa_moneda">
<span<?= $Grid->tasa_moneda->viewAttributes() ?>>
<?= $Grid->tasa_moneda->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_tasa_moneda" data-hidden="1" name="fpagos_compras_detallegrid$x<?= $Grid->RowIndex ?>_tasa_moneda" id="fpagos_compras_detallegrid$x<?= $Grid->RowIndex ?>_tasa_moneda" value="<?= HtmlEncode($Grid->tasa_moneda->FormValue) ?>">
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_tasa_moneda" data-hidden="1" data-old name="fpagos_compras_detallegrid$o<?= $Grid->RowIndex ?>_tasa_moneda" id="fpagos_compras_detallegrid$o<?= $Grid->RowIndex ?>_tasa_moneda" value="<?= HtmlEncode($Grid->tasa_moneda->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->monto_bs->Visible) { // monto_bs ?>
        <td data-name="monto_bs"<?= $Grid->monto_bs->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_monto_bs" class="el_pagos_compras_detalle_monto_bs">
<input type="<?= $Grid->monto_bs->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_monto_bs" id="x<?= $Grid->RowIndex ?>_monto_bs" data-table="pagos_compras_detalle" data-field="x_monto_bs" value="<?= $Grid->monto_bs->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->monto_bs->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->monto_bs->formatPattern()) ?>"<?= $Grid->monto_bs->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_bs->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_monto_bs" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_monto_bs" id="o<?= $Grid->RowIndex ?>_monto_bs" value="<?= HtmlEncode($Grid->monto_bs->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_monto_bs" class="el_pagos_compras_detalle_monto_bs">
<input type="<?= $Grid->monto_bs->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_monto_bs" id="x<?= $Grid->RowIndex ?>_monto_bs" data-table="pagos_compras_detalle" data-field="x_monto_bs" value="<?= $Grid->monto_bs->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->monto_bs->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->monto_bs->formatPattern()) ?>"<?= $Grid->monto_bs->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_bs->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_monto_bs" class="el_pagos_compras_detalle_monto_bs">
<span<?= $Grid->monto_bs->viewAttributes() ?>>
<?= $Grid->monto_bs->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_monto_bs" data-hidden="1" name="fpagos_compras_detallegrid$x<?= $Grid->RowIndex ?>_monto_bs" id="fpagos_compras_detallegrid$x<?= $Grid->RowIndex ?>_monto_bs" value="<?= HtmlEncode($Grid->monto_bs->FormValue) ?>">
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_monto_bs" data-hidden="1" data-old name="fpagos_compras_detallegrid$o<?= $Grid->RowIndex ?>_monto_bs" id="fpagos_compras_detallegrid$o<?= $Grid->RowIndex ?>_monto_bs" value="<?= HtmlEncode($Grid->monto_bs->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->tasa_usd->Visible) { // tasa_usd ?>
        <td data-name="tasa_usd"<?= $Grid->tasa_usd->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_tasa_usd" class="el_pagos_compras_detalle_tasa_usd">
<input type="<?= $Grid->tasa_usd->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_tasa_usd" id="x<?= $Grid->RowIndex ?>_tasa_usd" data-table="pagos_compras_detalle" data-field="x_tasa_usd" value="<?= $Grid->tasa_usd->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->tasa_usd->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->tasa_usd->formatPattern()) ?>"<?= $Grid->tasa_usd->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->tasa_usd->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_tasa_usd" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_tasa_usd" id="o<?= $Grid->RowIndex ?>_tasa_usd" value="<?= HtmlEncode($Grid->tasa_usd->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_tasa_usd" class="el_pagos_compras_detalle_tasa_usd">
<input type="<?= $Grid->tasa_usd->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_tasa_usd" id="x<?= $Grid->RowIndex ?>_tasa_usd" data-table="pagos_compras_detalle" data-field="x_tasa_usd" value="<?= $Grid->tasa_usd->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->tasa_usd->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->tasa_usd->formatPattern()) ?>"<?= $Grid->tasa_usd->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->tasa_usd->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_tasa_usd" class="el_pagos_compras_detalle_tasa_usd">
<span<?= $Grid->tasa_usd->viewAttributes() ?>>
<?= $Grid->tasa_usd->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_tasa_usd" data-hidden="1" name="fpagos_compras_detallegrid$x<?= $Grid->RowIndex ?>_tasa_usd" id="fpagos_compras_detallegrid$x<?= $Grid->RowIndex ?>_tasa_usd" value="<?= HtmlEncode($Grid->tasa_usd->FormValue) ?>">
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_tasa_usd" data-hidden="1" data-old name="fpagos_compras_detallegrid$o<?= $Grid->RowIndex ?>_tasa_usd" id="fpagos_compras_detallegrid$o<?= $Grid->RowIndex ?>_tasa_usd" value="<?= HtmlEncode($Grid->tasa_usd->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->monto_usd->Visible) { // monto_usd ?>
        <td data-name="monto_usd"<?= $Grid->monto_usd->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_monto_usd" class="el_pagos_compras_detalle_monto_usd">
<input type="<?= $Grid->monto_usd->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_monto_usd" id="x<?= $Grid->RowIndex ?>_monto_usd" data-table="pagos_compras_detalle" data-field="x_monto_usd" value="<?= $Grid->monto_usd->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->monto_usd->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->monto_usd->formatPattern()) ?>"<?= $Grid->monto_usd->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_usd->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_monto_usd" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_monto_usd" id="o<?= $Grid->RowIndex ?>_monto_usd" value="<?= HtmlEncode($Grid->monto_usd->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_monto_usd" class="el_pagos_compras_detalle_monto_usd">
<input type="<?= $Grid->monto_usd->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_monto_usd" id="x<?= $Grid->RowIndex ?>_monto_usd" data-table="pagos_compras_detalle" data-field="x_monto_usd" value="<?= $Grid->monto_usd->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->monto_usd->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->monto_usd->formatPattern()) ?>"<?= $Grid->monto_usd->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_usd->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_monto_usd" class="el_pagos_compras_detalle_monto_usd">
<span<?= $Grid->monto_usd->viewAttributes() ?>>
<?= $Grid->monto_usd->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_monto_usd" data-hidden="1" name="fpagos_compras_detallegrid$x<?= $Grid->RowIndex ?>_monto_usd" id="fpagos_compras_detallegrid$x<?= $Grid->RowIndex ?>_monto_usd" value="<?= HtmlEncode($Grid->monto_usd->FormValue) ?>">
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_monto_usd" data-hidden="1" data-old name="fpagos_compras_detallegrid$o<?= $Grid->RowIndex ?>_monto_usd" id="fpagos_compras_detallegrid$o<?= $Grid->RowIndex ?>_monto_usd" value="<?= HtmlEncode($Grid->monto_usd->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->banco->Visible) { // banco ?>
        <td data-name="banco"<?= $Grid->banco->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_banco" class="el_pagos_compras_detalle_banco">
<input type="<?= $Grid->banco->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_banco" id="x<?= $Grid->RowIndex ?>_banco" data-table="pagos_compras_detalle" data-field="x_banco" value="<?= $Grid->banco->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->banco->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->banco->formatPattern()) ?>"<?= $Grid->banco->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->banco->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_banco" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_banco" id="o<?= $Grid->RowIndex ?>_banco" value="<?= HtmlEncode($Grid->banco->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_banco" class="el_pagos_compras_detalle_banco">
<input type="<?= $Grid->banco->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_banco" id="x<?= $Grid->RowIndex ?>_banco" data-table="pagos_compras_detalle" data-field="x_banco" value="<?= $Grid->banco->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->banco->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->banco->formatPattern()) ?>"<?= $Grid->banco->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->banco->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_compras_detalle_banco" class="el_pagos_compras_detalle_banco">
<span<?= $Grid->banco->viewAttributes() ?>>
<?= $Grid->banco->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_banco" data-hidden="1" name="fpagos_compras_detallegrid$x<?= $Grid->RowIndex ?>_banco" id="fpagos_compras_detallegrid$x<?= $Grid->RowIndex ?>_banco" value="<?= HtmlEncode($Grid->banco->FormValue) ?>">
<input type="hidden" data-table="pagos_compras_detalle" data-field="x_banco" data-hidden="1" data-old name="fpagos_compras_detallegrid$o<?= $Grid->RowIndex ?>_banco" id="fpagos_compras_detallegrid$o<?= $Grid->RowIndex ?>_banco" value="<?= HtmlEncode($Grid->banco->OldValue) ?>">
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
loadjs.ready(["fpagos_compras_detallegrid","load"], () => fpagos_compras_detallegrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="fpagos_compras_detallegrid">
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
    ew.addEventHandlers("pagos_compras_detalle");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
