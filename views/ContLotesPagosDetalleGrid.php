<?php

namespace PHPMaker2024\mandrake;

// Set up and run Grid object
$Grid = Container("ContLotesPagosDetalleGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fcont_lotes_pagos_detallegrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { cont_lotes_pagos_detalle: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcont_lotes_pagos_detallegrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["proveedor", [fields.proveedor.visible && fields.proveedor.required ? ew.Validators.required(fields.proveedor.caption) : null, ew.Validators.integer], fields.proveedor.isInvalid],
            ["tipodoc", [fields.tipodoc.visible && fields.tipodoc.required ? ew.Validators.required(fields.tipodoc.caption) : null], fields.tipodoc.isInvalid],
            ["nro_documento", [fields.nro_documento.visible && fields.nro_documento.required ? ew.Validators.required(fields.nro_documento.caption) : null], fields.nro_documento.isInvalid],
            ["monto_a_pagar", [fields.monto_a_pagar.visible && fields.monto_a_pagar.required ? ew.Validators.required(fields.monto_a_pagar.caption) : null, ew.Validators.float], fields.monto_a_pagar.isInvalid],
            ["monto_pagdo", [fields.monto_pagdo.visible && fields.monto_pagdo.required ? ew.Validators.required(fields.monto_pagdo.caption) : null, ew.Validators.float], fields.monto_pagdo.isInvalid],
            ["saldo", [fields.saldo.visible && fields.saldo.required ? ew.Validators.required(fields.saldo.caption) : null, ew.Validators.float], fields.saldo.isInvalid],
            ["comprobante", [fields.comprobante.visible && fields.comprobante.required ? ew.Validators.required(fields.comprobante.caption) : null, ew.Validators.integer], fields.comprobante.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["proveedor",false],["tipodoc",false],["nro_documento",false],["monto_a_pagar",false],["monto_pagdo",false],["saldo",false],["comprobante",false]];
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
            "proveedor": <?= $Grid->proveedor->toClientList($Grid) ?>,
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
<div id="fcont_lotes_pagos_detallegrid" class="ew-form ew-list-form">
<div id="gmp_cont_lotes_pagos_detalle" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_cont_lotes_pagos_detallegrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Grid->proveedor->Visible) { // proveedor ?>
        <th data-name="proveedor" class="<?= $Grid->proveedor->headerCellClass() ?>"><div id="elh_cont_lotes_pagos_detalle_proveedor" class="cont_lotes_pagos_detalle_proveedor"><?= $Grid->renderFieldHeader($Grid->proveedor) ?></div></th>
<?php } ?>
<?php if ($Grid->tipodoc->Visible) { // tipodoc ?>
        <th data-name="tipodoc" class="<?= $Grid->tipodoc->headerCellClass() ?>"><div id="elh_cont_lotes_pagos_detalle_tipodoc" class="cont_lotes_pagos_detalle_tipodoc"><?= $Grid->renderFieldHeader($Grid->tipodoc) ?></div></th>
<?php } ?>
<?php if ($Grid->nro_documento->Visible) { // nro_documento ?>
        <th data-name="nro_documento" class="<?= $Grid->nro_documento->headerCellClass() ?>"><div id="elh_cont_lotes_pagos_detalle_nro_documento" class="cont_lotes_pagos_detalle_nro_documento"><?= $Grid->renderFieldHeader($Grid->nro_documento) ?></div></th>
<?php } ?>
<?php if ($Grid->monto_a_pagar->Visible) { // monto_a_pagar ?>
        <th data-name="monto_a_pagar" class="<?= $Grid->monto_a_pagar->headerCellClass() ?>"><div id="elh_cont_lotes_pagos_detalle_monto_a_pagar" class="cont_lotes_pagos_detalle_monto_a_pagar"><?= $Grid->renderFieldHeader($Grid->monto_a_pagar) ?></div></th>
<?php } ?>
<?php if ($Grid->monto_pagdo->Visible) { // monto_pagdo ?>
        <th data-name="monto_pagdo" class="<?= $Grid->monto_pagdo->headerCellClass() ?>"><div id="elh_cont_lotes_pagos_detalle_monto_pagdo" class="cont_lotes_pagos_detalle_monto_pagdo"><?= $Grid->renderFieldHeader($Grid->monto_pagdo) ?></div></th>
<?php } ?>
<?php if ($Grid->saldo->Visible) { // saldo ?>
        <th data-name="saldo" class="<?= $Grid->saldo->headerCellClass() ?>"><div id="elh_cont_lotes_pagos_detalle_saldo" class="cont_lotes_pagos_detalle_saldo"><?= $Grid->renderFieldHeader($Grid->saldo) ?></div></th>
<?php } ?>
<?php if ($Grid->comprobante->Visible) { // comprobante ?>
        <th data-name="comprobante" class="<?= $Grid->comprobante->headerCellClass() ?>"><div id="elh_cont_lotes_pagos_detalle_comprobante" class="cont_lotes_pagos_detalle_comprobante"><?= $Grid->renderFieldHeader($Grid->comprobante) ?></div></th>
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
    <?php if ($Grid->proveedor->Visible) { // proveedor ?>
        <td data-name="proveedor"<?= $Grid->proveedor->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_lotes_pagos_detalle_proveedor" class="el_cont_lotes_pagos_detalle_proveedor">
<?php
if (IsRTL()) {
    $Grid->proveedor->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x<?= $Grid->RowIndex ?>_proveedor" class="ew-auto-suggest">
    <input type="<?= $Grid->proveedor->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_proveedor" id="sv_x<?= $Grid->RowIndex ?>_proveedor" value="<?= RemoveHtml($Grid->proveedor->EditValue) ?>" autocomplete="off" size="30" placeholder="<?= HtmlEncode($Grid->proveedor->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->proveedor->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->proveedor->formatPattern()) ?>"<?= $Grid->proveedor->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="cont_lotes_pagos_detalle" data-field="x_proveedor" data-input="sv_x<?= $Grid->RowIndex ?>_proveedor" data-value-separator="<?= $Grid->proveedor->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_proveedor" id="x<?= $Grid->RowIndex ?>_proveedor" value="<?= HtmlEncode($Grid->proveedor->CurrentValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Grid->proveedor->getErrorMessage() ?></div>
<script>
loadjs.ready("fcont_lotes_pagos_detallegrid", function() {
    fcont_lotes_pagos_detallegrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_proveedor","forceSelect":false}, { lookupAllDisplayFields: <?= $Grid->proveedor->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.cont_lotes_pagos_detalle.fields.proveedor.autoSuggestOptions));
});
</script>
<?= $Grid->proveedor->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_proveedor") ?>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_proveedor" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_proveedor" id="o<?= $Grid->RowIndex ?>_proveedor" value="<?= HtmlEncode($Grid->proveedor->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_lotes_pagos_detalle_proveedor" class="el_cont_lotes_pagos_detalle_proveedor">
<?php
if (IsRTL()) {
    $Grid->proveedor->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x<?= $Grid->RowIndex ?>_proveedor" class="ew-auto-suggest">
    <input type="<?= $Grid->proveedor->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_proveedor" id="sv_x<?= $Grid->RowIndex ?>_proveedor" value="<?= RemoveHtml($Grid->proveedor->EditValue) ?>" autocomplete="off" size="30" placeholder="<?= HtmlEncode($Grid->proveedor->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->proveedor->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->proveedor->formatPattern()) ?>"<?= $Grid->proveedor->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="cont_lotes_pagos_detalle" data-field="x_proveedor" data-input="sv_x<?= $Grid->RowIndex ?>_proveedor" data-value-separator="<?= $Grid->proveedor->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_proveedor" id="x<?= $Grid->RowIndex ?>_proveedor" value="<?= HtmlEncode($Grid->proveedor->CurrentValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Grid->proveedor->getErrorMessage() ?></div>
<script>
loadjs.ready("fcont_lotes_pagos_detallegrid", function() {
    fcont_lotes_pagos_detallegrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_proveedor","forceSelect":false}, { lookupAllDisplayFields: <?= $Grid->proveedor->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.cont_lotes_pagos_detalle.fields.proveedor.autoSuggestOptions));
});
</script>
<?= $Grid->proveedor->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_proveedor") ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_lotes_pagos_detalle_proveedor" class="el_cont_lotes_pagos_detalle_proveedor">
<span<?= $Grid->proveedor->viewAttributes() ?>>
<?= $Grid->proveedor->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_proveedor" data-hidden="1" name="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_proveedor" id="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_proveedor" value="<?= HtmlEncode($Grid->proveedor->FormValue) ?>">
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_proveedor" data-hidden="1" data-old name="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_proveedor" id="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_proveedor" value="<?= HtmlEncode($Grid->proveedor->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->tipodoc->Visible) { // tipodoc ?>
        <td data-name="tipodoc"<?= $Grid->tipodoc->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_lotes_pagos_detalle_tipodoc" class="el_cont_lotes_pagos_detalle_tipodoc">
<input type="<?= $Grid->tipodoc->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_tipodoc" id="x<?= $Grid->RowIndex ?>_tipodoc" data-table="cont_lotes_pagos_detalle" data-field="x_tipodoc" value="<?= $Grid->tipodoc->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->tipodoc->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->tipodoc->formatPattern()) ?>"<?= $Grid->tipodoc->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->tipodoc->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_tipodoc" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_tipodoc" id="o<?= $Grid->RowIndex ?>_tipodoc" value="<?= HtmlEncode($Grid->tipodoc->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_lotes_pagos_detalle_tipodoc" class="el_cont_lotes_pagos_detalle_tipodoc">
<input type="<?= $Grid->tipodoc->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_tipodoc" id="x<?= $Grid->RowIndex ?>_tipodoc" data-table="cont_lotes_pagos_detalle" data-field="x_tipodoc" value="<?= $Grid->tipodoc->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->tipodoc->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->tipodoc->formatPattern()) ?>"<?= $Grid->tipodoc->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->tipodoc->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_lotes_pagos_detalle_tipodoc" class="el_cont_lotes_pagos_detalle_tipodoc">
<span<?= $Grid->tipodoc->viewAttributes() ?>>
<?= $Grid->tipodoc->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_tipodoc" data-hidden="1" name="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_tipodoc" id="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_tipodoc" value="<?= HtmlEncode($Grid->tipodoc->FormValue) ?>">
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_tipodoc" data-hidden="1" data-old name="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_tipodoc" id="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_tipodoc" value="<?= HtmlEncode($Grid->tipodoc->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->nro_documento->Visible) { // nro_documento ?>
        <td data-name="nro_documento"<?= $Grid->nro_documento->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_lotes_pagos_detalle_nro_documento" class="el_cont_lotes_pagos_detalle_nro_documento">
<input type="<?= $Grid->nro_documento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_nro_documento" id="x<?= $Grid->RowIndex ?>_nro_documento" data-table="cont_lotes_pagos_detalle" data-field="x_nro_documento" value="<?= $Grid->nro_documento->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->nro_documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->nro_documento->formatPattern()) ?>"<?= $Grid->nro_documento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->nro_documento->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_nro_documento" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_nro_documento" id="o<?= $Grid->RowIndex ?>_nro_documento" value="<?= HtmlEncode($Grid->nro_documento->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_lotes_pagos_detalle_nro_documento" class="el_cont_lotes_pagos_detalle_nro_documento">
<input type="<?= $Grid->nro_documento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_nro_documento" id="x<?= $Grid->RowIndex ?>_nro_documento" data-table="cont_lotes_pagos_detalle" data-field="x_nro_documento" value="<?= $Grid->nro_documento->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->nro_documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->nro_documento->formatPattern()) ?>"<?= $Grid->nro_documento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->nro_documento->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_lotes_pagos_detalle_nro_documento" class="el_cont_lotes_pagos_detalle_nro_documento">
<span<?= $Grid->nro_documento->viewAttributes() ?>>
<?= $Grid->nro_documento->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_nro_documento" data-hidden="1" name="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_nro_documento" id="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_nro_documento" value="<?= HtmlEncode($Grid->nro_documento->FormValue) ?>">
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_nro_documento" data-hidden="1" data-old name="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_nro_documento" id="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_nro_documento" value="<?= HtmlEncode($Grid->nro_documento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->monto_a_pagar->Visible) { // monto_a_pagar ?>
        <td data-name="monto_a_pagar"<?= $Grid->monto_a_pagar->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_lotes_pagos_detalle_monto_a_pagar" class="el_cont_lotes_pagos_detalle_monto_a_pagar">
<input type="<?= $Grid->monto_a_pagar->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_monto_a_pagar" id="x<?= $Grid->RowIndex ?>_monto_a_pagar" data-table="cont_lotes_pagos_detalle" data-field="x_monto_a_pagar" value="<?= $Grid->monto_a_pagar->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->monto_a_pagar->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->monto_a_pagar->formatPattern()) ?>"<?= $Grid->monto_a_pagar->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_a_pagar->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_monto_a_pagar" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_monto_a_pagar" id="o<?= $Grid->RowIndex ?>_monto_a_pagar" value="<?= HtmlEncode($Grid->monto_a_pagar->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_lotes_pagos_detalle_monto_a_pagar" class="el_cont_lotes_pagos_detalle_monto_a_pagar">
<input type="<?= $Grid->monto_a_pagar->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_monto_a_pagar" id="x<?= $Grid->RowIndex ?>_monto_a_pagar" data-table="cont_lotes_pagos_detalle" data-field="x_monto_a_pagar" value="<?= $Grid->monto_a_pagar->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->monto_a_pagar->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->monto_a_pagar->formatPattern()) ?>"<?= $Grid->monto_a_pagar->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_a_pagar->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_lotes_pagos_detalle_monto_a_pagar" class="el_cont_lotes_pagos_detalle_monto_a_pagar">
<span<?= $Grid->monto_a_pagar->viewAttributes() ?>>
<?= $Grid->monto_a_pagar->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_monto_a_pagar" data-hidden="1" name="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_monto_a_pagar" id="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_monto_a_pagar" value="<?= HtmlEncode($Grid->monto_a_pagar->FormValue) ?>">
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_monto_a_pagar" data-hidden="1" data-old name="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_monto_a_pagar" id="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_monto_a_pagar" value="<?= HtmlEncode($Grid->monto_a_pagar->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->monto_pagdo->Visible) { // monto_pagdo ?>
        <td data-name="monto_pagdo"<?= $Grid->monto_pagdo->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_lotes_pagos_detalle_monto_pagdo" class="el_cont_lotes_pagos_detalle_monto_pagdo">
<input type="<?= $Grid->monto_pagdo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_monto_pagdo" id="x<?= $Grid->RowIndex ?>_monto_pagdo" data-table="cont_lotes_pagos_detalle" data-field="x_monto_pagdo" value="<?= $Grid->monto_pagdo->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->monto_pagdo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->monto_pagdo->formatPattern()) ?>"<?= $Grid->monto_pagdo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_pagdo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_monto_pagdo" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_monto_pagdo" id="o<?= $Grid->RowIndex ?>_monto_pagdo" value="<?= HtmlEncode($Grid->monto_pagdo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_lotes_pagos_detalle_monto_pagdo" class="el_cont_lotes_pagos_detalle_monto_pagdo">
<input type="<?= $Grid->monto_pagdo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_monto_pagdo" id="x<?= $Grid->RowIndex ?>_monto_pagdo" data-table="cont_lotes_pagos_detalle" data-field="x_monto_pagdo" value="<?= $Grid->monto_pagdo->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->monto_pagdo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->monto_pagdo->formatPattern()) ?>"<?= $Grid->monto_pagdo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_pagdo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_lotes_pagos_detalle_monto_pagdo" class="el_cont_lotes_pagos_detalle_monto_pagdo">
<span<?= $Grid->monto_pagdo->viewAttributes() ?>>
<?= $Grid->monto_pagdo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_monto_pagdo" data-hidden="1" name="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_monto_pagdo" id="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_monto_pagdo" value="<?= HtmlEncode($Grid->monto_pagdo->FormValue) ?>">
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_monto_pagdo" data-hidden="1" data-old name="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_monto_pagdo" id="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_monto_pagdo" value="<?= HtmlEncode($Grid->monto_pagdo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->saldo->Visible) { // saldo ?>
        <td data-name="saldo"<?= $Grid->saldo->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_lotes_pagos_detalle_saldo" class="el_cont_lotes_pagos_detalle_saldo">
<input type="<?= $Grid->saldo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_saldo" id="x<?= $Grid->RowIndex ?>_saldo" data-table="cont_lotes_pagos_detalle" data-field="x_saldo" value="<?= $Grid->saldo->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->saldo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->saldo->formatPattern()) ?>"<?= $Grid->saldo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->saldo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_saldo" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_saldo" id="o<?= $Grid->RowIndex ?>_saldo" value="<?= HtmlEncode($Grid->saldo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_lotes_pagos_detalle_saldo" class="el_cont_lotes_pagos_detalle_saldo">
<input type="<?= $Grid->saldo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_saldo" id="x<?= $Grid->RowIndex ?>_saldo" data-table="cont_lotes_pagos_detalle" data-field="x_saldo" value="<?= $Grid->saldo->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->saldo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->saldo->formatPattern()) ?>"<?= $Grid->saldo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->saldo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_lotes_pagos_detalle_saldo" class="el_cont_lotes_pagos_detalle_saldo">
<span<?= $Grid->saldo->viewAttributes() ?>>
<?= $Grid->saldo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_saldo" data-hidden="1" name="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_saldo" id="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_saldo" value="<?= HtmlEncode($Grid->saldo->FormValue) ?>">
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_saldo" data-hidden="1" data-old name="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_saldo" id="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_saldo" value="<?= HtmlEncode($Grid->saldo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->comprobante->Visible) { // comprobante ?>
        <td data-name="comprobante"<?= $Grid->comprobante->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_lotes_pagos_detalle_comprobante" class="el_cont_lotes_pagos_detalle_comprobante">
<input type="<?= $Grid->comprobante->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_comprobante" id="x<?= $Grid->RowIndex ?>_comprobante" data-table="cont_lotes_pagos_detalle" data-field="x_comprobante" value="<?= $Grid->comprobante->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->comprobante->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->comprobante->formatPattern()) ?>"<?= $Grid->comprobante->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->comprobante->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_comprobante" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_comprobante" id="o<?= $Grid->RowIndex ?>_comprobante" value="<?= HtmlEncode($Grid->comprobante->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_lotes_pagos_detalle_comprobante" class="el_cont_lotes_pagos_detalle_comprobante">
<input type="<?= $Grid->comprobante->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_comprobante" id="x<?= $Grid->RowIndex ?>_comprobante" data-table="cont_lotes_pagos_detalle" data-field="x_comprobante" value="<?= $Grid->comprobante->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->comprobante->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->comprobante->formatPattern()) ?>"<?= $Grid->comprobante->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->comprobante->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_lotes_pagos_detalle_comprobante" class="el_cont_lotes_pagos_detalle_comprobante">
<span<?= $Grid->comprobante->viewAttributes() ?>>
<?= $Grid->comprobante->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_comprobante" data-hidden="1" name="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_comprobante" id="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_comprobante" value="<?= HtmlEncode($Grid->comprobante->FormValue) ?>">
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_comprobante" data-hidden="1" data-old name="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_comprobante" id="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_comprobante" value="<?= HtmlEncode($Grid->comprobante->OldValue) ?>">
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
loadjs.ready(["fcont_lotes_pagos_detallegrid","load"], () => fcont_lotes_pagos_detallegrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="fcont_lotes_pagos_detallegrid">
</div><!-- /.ew-list-form -->
<?php
// Close result set
$Grid->Recordset?->free();
?>
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
    ew.addEventHandlers("cont_lotes_pagos_detalle");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
