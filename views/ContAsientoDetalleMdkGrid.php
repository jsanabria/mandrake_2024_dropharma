<?php

namespace PHPMaker2024\mandrake;

// Set up and run Grid object
$Grid = Container("ContAsientoDetalleMdkGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fcont_asiento_detalle_mdkgrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { cont_asiento_detalle_mdk: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcont_asiento_detalle_mdkgrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null], fields.id.isInvalid],
            ["asiento_id", [fields.asiento_id.visible && fields.asiento_id.required ? ew.Validators.required(fields.asiento_id.caption) : null, ew.Validators.integer], fields.asiento_id.isInvalid],
            ["cuenta_id", [fields.cuenta_id.visible && fields.cuenta_id.required ? ew.Validators.required(fields.cuenta_id.caption) : null], fields.cuenta_id.isInvalid],
            ["centro_costo_id", [fields.centro_costo_id.visible && fields.centro_costo_id.required ? ew.Validators.required(fields.centro_costo_id.caption) : null], fields.centro_costo_id.isInvalid],
            ["concepto", [fields.concepto.visible && fields.concepto.required ? ew.Validators.required(fields.concepto.caption) : null], fields.concepto.isInvalid],
            ["moneda_trx", [fields.moneda_trx.visible && fields.moneda_trx.required ? ew.Validators.required(fields.moneda_trx.caption) : null], fields.moneda_trx.isInvalid],
            ["tasa_trx", [fields.tasa_trx.visible && fields.tasa_trx.required ? ew.Validators.required(fields.tasa_trx.caption) : null, ew.Validators.float], fields.tasa_trx.isInvalid],
            ["monto_moneda_trx", [fields.monto_moneda_trx.visible && fields.monto_moneda_trx.required ? ew.Validators.required(fields.monto_moneda_trx.caption) : null, ew.Validators.float], fields.monto_moneda_trx.isInvalid],
            ["debe_bs", [fields.debe_bs.visible && fields.debe_bs.required ? ew.Validators.required(fields.debe_bs.caption) : null, ew.Validators.float], fields.debe_bs.isInvalid],
            ["haber_bs", [fields.haber_bs.visible && fields.haber_bs.required ? ew.Validators.required(fields.haber_bs.caption) : null, ew.Validators.float], fields.haber_bs.isInvalid],
            ["created_at", [fields.created_at.visible && fields.created_at.required ? ew.Validators.required(fields.created_at.caption) : null, ew.Validators.datetime(fields.created_at.clientFormatPattern)], fields.created_at.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["asiento_id",false],["cuenta_id",false],["centro_costo_id",false],["concepto",false],["moneda_trx",false],["tasa_trx",false],["monto_moneda_trx",false],["debe_bs",false],["haber_bs",false],["created_at",false]];
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
            "cuenta_id": <?= $Grid->cuenta_id->toClientList($Grid) ?>,
            "centro_costo_id": <?= $Grid->centro_costo_id->toClientList($Grid) ?>,
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
<div id="fcont_asiento_detalle_mdkgrid" class="ew-form ew-list-form">
<div id="gmp_cont_asiento_detalle_mdk" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_cont_asiento_detalle_mdkgrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Grid->id->Visible) { // id ?>
        <th data-name="id" class="<?= $Grid->id->headerCellClass() ?>"><div id="elh_cont_asiento_detalle_mdk_id" class="cont_asiento_detalle_mdk_id"><?= $Grid->renderFieldHeader($Grid->id) ?></div></th>
<?php } ?>
<?php if ($Grid->asiento_id->Visible) { // asiento_id ?>
        <th data-name="asiento_id" class="<?= $Grid->asiento_id->headerCellClass() ?>"><div id="elh_cont_asiento_detalle_mdk_asiento_id" class="cont_asiento_detalle_mdk_asiento_id"><?= $Grid->renderFieldHeader($Grid->asiento_id) ?></div></th>
<?php } ?>
<?php if ($Grid->cuenta_id->Visible) { // cuenta_id ?>
        <th data-name="cuenta_id" class="<?= $Grid->cuenta_id->headerCellClass() ?>"><div id="elh_cont_asiento_detalle_mdk_cuenta_id" class="cont_asiento_detalle_mdk_cuenta_id"><?= $Grid->renderFieldHeader($Grid->cuenta_id) ?></div></th>
<?php } ?>
<?php if ($Grid->centro_costo_id->Visible) { // centro_costo_id ?>
        <th data-name="centro_costo_id" class="<?= $Grid->centro_costo_id->headerCellClass() ?>"><div id="elh_cont_asiento_detalle_mdk_centro_costo_id" class="cont_asiento_detalle_mdk_centro_costo_id"><?= $Grid->renderFieldHeader($Grid->centro_costo_id) ?></div></th>
<?php } ?>
<?php if ($Grid->concepto->Visible) { // concepto ?>
        <th data-name="concepto" class="<?= $Grid->concepto->headerCellClass() ?>"><div id="elh_cont_asiento_detalle_mdk_concepto" class="cont_asiento_detalle_mdk_concepto"><?= $Grid->renderFieldHeader($Grid->concepto) ?></div></th>
<?php } ?>
<?php if ($Grid->moneda_trx->Visible) { // moneda_trx ?>
        <th data-name="moneda_trx" class="<?= $Grid->moneda_trx->headerCellClass() ?>"><div id="elh_cont_asiento_detalle_mdk_moneda_trx" class="cont_asiento_detalle_mdk_moneda_trx"><?= $Grid->renderFieldHeader($Grid->moneda_trx) ?></div></th>
<?php } ?>
<?php if ($Grid->tasa_trx->Visible) { // tasa_trx ?>
        <th data-name="tasa_trx" class="<?= $Grid->tasa_trx->headerCellClass() ?>"><div id="elh_cont_asiento_detalle_mdk_tasa_trx" class="cont_asiento_detalle_mdk_tasa_trx"><?= $Grid->renderFieldHeader($Grid->tasa_trx) ?></div></th>
<?php } ?>
<?php if ($Grid->monto_moneda_trx->Visible) { // monto_moneda_trx ?>
        <th data-name="monto_moneda_trx" class="<?= $Grid->monto_moneda_trx->headerCellClass() ?>"><div id="elh_cont_asiento_detalle_mdk_monto_moneda_trx" class="cont_asiento_detalle_mdk_monto_moneda_trx"><?= $Grid->renderFieldHeader($Grid->monto_moneda_trx) ?></div></th>
<?php } ?>
<?php if ($Grid->debe_bs->Visible) { // debe_bs ?>
        <th data-name="debe_bs" class="<?= $Grid->debe_bs->headerCellClass() ?>"><div id="elh_cont_asiento_detalle_mdk_debe_bs" class="cont_asiento_detalle_mdk_debe_bs"><?= $Grid->renderFieldHeader($Grid->debe_bs) ?></div></th>
<?php } ?>
<?php if ($Grid->haber_bs->Visible) { // haber_bs ?>
        <th data-name="haber_bs" class="<?= $Grid->haber_bs->headerCellClass() ?>"><div id="elh_cont_asiento_detalle_mdk_haber_bs" class="cont_asiento_detalle_mdk_haber_bs"><?= $Grid->renderFieldHeader($Grid->haber_bs) ?></div></th>
<?php } ?>
<?php if ($Grid->created_at->Visible) { // created_at ?>
        <th data-name="created_at" class="<?= $Grid->created_at->headerCellClass() ?>"><div id="elh_cont_asiento_detalle_mdk_created_at" class="cont_asiento_detalle_mdk_created_at"><?= $Grid->renderFieldHeader($Grid->created_at) ?></div></th>
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
    <?php if ($Grid->id->Visible) { // id ?>
        <td data-name="id"<?= $Grid->id->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_id" class="el_cont_asiento_detalle_mdk_id"></span>
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_id" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_id" id="o<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_id" class="el_cont_asiento_detalle_mdk_id">
<span<?= $Grid->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id->getDisplayValue($Grid->id->EditValue))) ?>"></span>
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_id" id="x<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_id" class="el_cont_asiento_detalle_mdk_id">
<span<?= $Grid->id->viewAttributes() ?>>
<?= $Grid->id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_id" data-hidden="1" name="fcont_asiento_detalle_mdkgrid$x<?= $Grid->RowIndex ?>_id" id="fcont_asiento_detalle_mdkgrid$x<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->FormValue) ?>">
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_id" data-hidden="1" data-old name="fcont_asiento_detalle_mdkgrid$o<?= $Grid->RowIndex ?>_id" id="fcont_asiento_detalle_mdkgrid$o<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_id" id="x<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Grid->asiento_id->Visible) { // asiento_id ?>
        <td data-name="asiento_id"<?= $Grid->asiento_id->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<?php if ($Grid->asiento_id->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_asiento_id" class="el_cont_asiento_detalle_mdk_asiento_id">
<span<?= $Grid->asiento_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->asiento_id->getDisplayValue($Grid->asiento_id->ViewValue))) ?>"></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_asiento_id" name="x<?= $Grid->RowIndex ?>_asiento_id" value="<?= HtmlEncode($Grid->asiento_id->CurrentValue) ?>" data-hidden="1">
</span>
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_asiento_id" class="el_cont_asiento_detalle_mdk_asiento_id">
<input type="<?= $Grid->asiento_id->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_asiento_id" id="x<?= $Grid->RowIndex ?>_asiento_id" data-table="cont_asiento_detalle_mdk" data-field="x_asiento_id" value="<?= $Grid->asiento_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->asiento_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->asiento_id->formatPattern()) ?>"<?= $Grid->asiento_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->asiento_id->getErrorMessage() ?></div>
</span>
<?php } ?>
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_asiento_id" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_asiento_id" id="o<?= $Grid->RowIndex ?>_asiento_id" value="<?= HtmlEncode($Grid->asiento_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<?php if ($Grid->asiento_id->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_asiento_id" class="el_cont_asiento_detalle_mdk_asiento_id">
<span<?= $Grid->asiento_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->asiento_id->getDisplayValue($Grid->asiento_id->ViewValue))) ?>"></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_asiento_id" name="x<?= $Grid->RowIndex ?>_asiento_id" value="<?= HtmlEncode($Grid->asiento_id->CurrentValue) ?>" data-hidden="1">
</span>
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_asiento_id" class="el_cont_asiento_detalle_mdk_asiento_id">
<input type="<?= $Grid->asiento_id->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_asiento_id" id="x<?= $Grid->RowIndex ?>_asiento_id" data-table="cont_asiento_detalle_mdk" data-field="x_asiento_id" value="<?= $Grid->asiento_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->asiento_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->asiento_id->formatPattern()) ?>"<?= $Grid->asiento_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->asiento_id->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_asiento_id" class="el_cont_asiento_detalle_mdk_asiento_id">
<span<?= $Grid->asiento_id->viewAttributes() ?>>
<?= $Grid->asiento_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_asiento_id" data-hidden="1" name="fcont_asiento_detalle_mdkgrid$x<?= $Grid->RowIndex ?>_asiento_id" id="fcont_asiento_detalle_mdkgrid$x<?= $Grid->RowIndex ?>_asiento_id" value="<?= HtmlEncode($Grid->asiento_id->FormValue) ?>">
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_asiento_id" data-hidden="1" data-old name="fcont_asiento_detalle_mdkgrid$o<?= $Grid->RowIndex ?>_asiento_id" id="fcont_asiento_detalle_mdkgrid$o<?= $Grid->RowIndex ?>_asiento_id" value="<?= HtmlEncode($Grid->asiento_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->cuenta_id->Visible) { // cuenta_id ?>
        <td data-name="cuenta_id"<?= $Grid->cuenta_id->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_cuenta_id" class="el_cont_asiento_detalle_mdk_cuenta_id">
    <select
        id="x<?= $Grid->RowIndex ?>_cuenta_id"
        name="x<?= $Grid->RowIndex ?>_cuenta_id"
        class="form-control ew-select<?= $Grid->cuenta_id->isInvalidClass() ?>"
        data-select2-id="fcont_asiento_detalle_mdkgrid_x<?= $Grid->RowIndex ?>_cuenta_id"
        data-table="cont_asiento_detalle_mdk"
        data-field="x_cuenta_id"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->cuenta_id->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->cuenta_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->cuenta_id->getPlaceHolder()) ?>"
        <?= $Grid->cuenta_id->editAttributes() ?>>
        <?= $Grid->cuenta_id->selectOptionListHtml("x{$Grid->RowIndex}_cuenta_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->cuenta_id->getErrorMessage() ?></div>
<?= $Grid->cuenta_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_cuenta_id") ?>
<script>
loadjs.ready("fcont_asiento_detalle_mdkgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_cuenta_id", selectId: "fcont_asiento_detalle_mdkgrid_x<?= $Grid->RowIndex ?>_cuenta_id" };
    if (fcont_asiento_detalle_mdkgrid.lists.cuenta_id?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_cuenta_id", form: "fcont_asiento_detalle_mdkgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_cuenta_id", form: "fcont_asiento_detalle_mdkgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.cont_asiento_detalle_mdk.fields.cuenta_id.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_cuenta_id" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_cuenta_id" id="o<?= $Grid->RowIndex ?>_cuenta_id" value="<?= HtmlEncode($Grid->cuenta_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_cuenta_id" class="el_cont_asiento_detalle_mdk_cuenta_id">
    <select
        id="x<?= $Grid->RowIndex ?>_cuenta_id"
        name="x<?= $Grid->RowIndex ?>_cuenta_id"
        class="form-control ew-select<?= $Grid->cuenta_id->isInvalidClass() ?>"
        data-select2-id="fcont_asiento_detalle_mdkgrid_x<?= $Grid->RowIndex ?>_cuenta_id"
        data-table="cont_asiento_detalle_mdk"
        data-field="x_cuenta_id"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->cuenta_id->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->cuenta_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->cuenta_id->getPlaceHolder()) ?>"
        <?= $Grid->cuenta_id->editAttributes() ?>>
        <?= $Grid->cuenta_id->selectOptionListHtml("x{$Grid->RowIndex}_cuenta_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->cuenta_id->getErrorMessage() ?></div>
<?= $Grid->cuenta_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_cuenta_id") ?>
<script>
loadjs.ready("fcont_asiento_detalle_mdkgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_cuenta_id", selectId: "fcont_asiento_detalle_mdkgrid_x<?= $Grid->RowIndex ?>_cuenta_id" };
    if (fcont_asiento_detalle_mdkgrid.lists.cuenta_id?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_cuenta_id", form: "fcont_asiento_detalle_mdkgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_cuenta_id", form: "fcont_asiento_detalle_mdkgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.cont_asiento_detalle_mdk.fields.cuenta_id.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_cuenta_id" class="el_cont_asiento_detalle_mdk_cuenta_id">
<span<?= $Grid->cuenta_id->viewAttributes() ?>>
<?= $Grid->cuenta_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_cuenta_id" data-hidden="1" name="fcont_asiento_detalle_mdkgrid$x<?= $Grid->RowIndex ?>_cuenta_id" id="fcont_asiento_detalle_mdkgrid$x<?= $Grid->RowIndex ?>_cuenta_id" value="<?= HtmlEncode($Grid->cuenta_id->FormValue) ?>">
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_cuenta_id" data-hidden="1" data-old name="fcont_asiento_detalle_mdkgrid$o<?= $Grid->RowIndex ?>_cuenta_id" id="fcont_asiento_detalle_mdkgrid$o<?= $Grid->RowIndex ?>_cuenta_id" value="<?= HtmlEncode($Grid->cuenta_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->centro_costo_id->Visible) { // centro_costo_id ?>
        <td data-name="centro_costo_id"<?= $Grid->centro_costo_id->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_centro_costo_id" class="el_cont_asiento_detalle_mdk_centro_costo_id">
    <select
        id="x<?= $Grid->RowIndex ?>_centro_costo_id"
        name="x<?= $Grid->RowIndex ?>_centro_costo_id"
        class="form-control ew-select<?= $Grid->centro_costo_id->isInvalidClass() ?>"
        data-select2-id="fcont_asiento_detalle_mdkgrid_x<?= $Grid->RowIndex ?>_centro_costo_id"
        data-table="cont_asiento_detalle_mdk"
        data-field="x_centro_costo_id"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->centro_costo_id->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->centro_costo_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->centro_costo_id->getPlaceHolder()) ?>"
        <?= $Grid->centro_costo_id->editAttributes() ?>>
        <?= $Grid->centro_costo_id->selectOptionListHtml("x{$Grid->RowIndex}_centro_costo_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->centro_costo_id->getErrorMessage() ?></div>
<?= $Grid->centro_costo_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_centro_costo_id") ?>
<script>
loadjs.ready("fcont_asiento_detalle_mdkgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_centro_costo_id", selectId: "fcont_asiento_detalle_mdkgrid_x<?= $Grid->RowIndex ?>_centro_costo_id" };
    if (fcont_asiento_detalle_mdkgrid.lists.centro_costo_id?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_centro_costo_id", form: "fcont_asiento_detalle_mdkgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_centro_costo_id", form: "fcont_asiento_detalle_mdkgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.cont_asiento_detalle_mdk.fields.centro_costo_id.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_centro_costo_id" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_centro_costo_id" id="o<?= $Grid->RowIndex ?>_centro_costo_id" value="<?= HtmlEncode($Grid->centro_costo_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_centro_costo_id" class="el_cont_asiento_detalle_mdk_centro_costo_id">
    <select
        id="x<?= $Grid->RowIndex ?>_centro_costo_id"
        name="x<?= $Grid->RowIndex ?>_centro_costo_id"
        class="form-control ew-select<?= $Grid->centro_costo_id->isInvalidClass() ?>"
        data-select2-id="fcont_asiento_detalle_mdkgrid_x<?= $Grid->RowIndex ?>_centro_costo_id"
        data-table="cont_asiento_detalle_mdk"
        data-field="x_centro_costo_id"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->centro_costo_id->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->centro_costo_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->centro_costo_id->getPlaceHolder()) ?>"
        <?= $Grid->centro_costo_id->editAttributes() ?>>
        <?= $Grid->centro_costo_id->selectOptionListHtml("x{$Grid->RowIndex}_centro_costo_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->centro_costo_id->getErrorMessage() ?></div>
<?= $Grid->centro_costo_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_centro_costo_id") ?>
<script>
loadjs.ready("fcont_asiento_detalle_mdkgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_centro_costo_id", selectId: "fcont_asiento_detalle_mdkgrid_x<?= $Grid->RowIndex ?>_centro_costo_id" };
    if (fcont_asiento_detalle_mdkgrid.lists.centro_costo_id?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_centro_costo_id", form: "fcont_asiento_detalle_mdkgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_centro_costo_id", form: "fcont_asiento_detalle_mdkgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.cont_asiento_detalle_mdk.fields.centro_costo_id.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_centro_costo_id" class="el_cont_asiento_detalle_mdk_centro_costo_id">
<span<?= $Grid->centro_costo_id->viewAttributes() ?>>
<?= $Grid->centro_costo_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_centro_costo_id" data-hidden="1" name="fcont_asiento_detalle_mdkgrid$x<?= $Grid->RowIndex ?>_centro_costo_id" id="fcont_asiento_detalle_mdkgrid$x<?= $Grid->RowIndex ?>_centro_costo_id" value="<?= HtmlEncode($Grid->centro_costo_id->FormValue) ?>">
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_centro_costo_id" data-hidden="1" data-old name="fcont_asiento_detalle_mdkgrid$o<?= $Grid->RowIndex ?>_centro_costo_id" id="fcont_asiento_detalle_mdkgrid$o<?= $Grid->RowIndex ?>_centro_costo_id" value="<?= HtmlEncode($Grid->centro_costo_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->concepto->Visible) { // concepto ?>
        <td data-name="concepto"<?= $Grid->concepto->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_concepto" class="el_cont_asiento_detalle_mdk_concepto">
<input type="<?= $Grid->concepto->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_concepto" id="x<?= $Grid->RowIndex ?>_concepto" data-table="cont_asiento_detalle_mdk" data-field="x_concepto" value="<?= $Grid->concepto->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Grid->concepto->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->concepto->formatPattern()) ?>"<?= $Grid->concepto->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->concepto->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_concepto" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_concepto" id="o<?= $Grid->RowIndex ?>_concepto" value="<?= HtmlEncode($Grid->concepto->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_concepto" class="el_cont_asiento_detalle_mdk_concepto">
<input type="<?= $Grid->concepto->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_concepto" id="x<?= $Grid->RowIndex ?>_concepto" data-table="cont_asiento_detalle_mdk" data-field="x_concepto" value="<?= $Grid->concepto->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Grid->concepto->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->concepto->formatPattern()) ?>"<?= $Grid->concepto->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->concepto->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_concepto" class="el_cont_asiento_detalle_mdk_concepto">
<span<?= $Grid->concepto->viewAttributes() ?>>
<?= $Grid->concepto->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_concepto" data-hidden="1" name="fcont_asiento_detalle_mdkgrid$x<?= $Grid->RowIndex ?>_concepto" id="fcont_asiento_detalle_mdkgrid$x<?= $Grid->RowIndex ?>_concepto" value="<?= HtmlEncode($Grid->concepto->FormValue) ?>">
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_concepto" data-hidden="1" data-old name="fcont_asiento_detalle_mdkgrid$o<?= $Grid->RowIndex ?>_concepto" id="fcont_asiento_detalle_mdkgrid$o<?= $Grid->RowIndex ?>_concepto" value="<?= HtmlEncode($Grid->concepto->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->moneda_trx->Visible) { // moneda_trx ?>
        <td data-name="moneda_trx"<?= $Grid->moneda_trx->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_moneda_trx" class="el_cont_asiento_detalle_mdk_moneda_trx">
<input type="<?= $Grid->moneda_trx->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_moneda_trx" id="x<?= $Grid->RowIndex ?>_moneda_trx" data-table="cont_asiento_detalle_mdk" data-field="x_moneda_trx" value="<?= $Grid->moneda_trx->EditValue ?>" size="30" maxlength="6" placeholder="<?= HtmlEncode($Grid->moneda_trx->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->moneda_trx->formatPattern()) ?>"<?= $Grid->moneda_trx->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->moneda_trx->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_moneda_trx" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_moneda_trx" id="o<?= $Grid->RowIndex ?>_moneda_trx" value="<?= HtmlEncode($Grid->moneda_trx->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_moneda_trx" class="el_cont_asiento_detalle_mdk_moneda_trx">
<input type="<?= $Grid->moneda_trx->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_moneda_trx" id="x<?= $Grid->RowIndex ?>_moneda_trx" data-table="cont_asiento_detalle_mdk" data-field="x_moneda_trx" value="<?= $Grid->moneda_trx->EditValue ?>" size="30" maxlength="6" placeholder="<?= HtmlEncode($Grid->moneda_trx->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->moneda_trx->formatPattern()) ?>"<?= $Grid->moneda_trx->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->moneda_trx->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_moneda_trx" class="el_cont_asiento_detalle_mdk_moneda_trx">
<span<?= $Grid->moneda_trx->viewAttributes() ?>>
<?= $Grid->moneda_trx->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_moneda_trx" data-hidden="1" name="fcont_asiento_detalle_mdkgrid$x<?= $Grid->RowIndex ?>_moneda_trx" id="fcont_asiento_detalle_mdkgrid$x<?= $Grid->RowIndex ?>_moneda_trx" value="<?= HtmlEncode($Grid->moneda_trx->FormValue) ?>">
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_moneda_trx" data-hidden="1" data-old name="fcont_asiento_detalle_mdkgrid$o<?= $Grid->RowIndex ?>_moneda_trx" id="fcont_asiento_detalle_mdkgrid$o<?= $Grid->RowIndex ?>_moneda_trx" value="<?= HtmlEncode($Grid->moneda_trx->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->tasa_trx->Visible) { // tasa_trx ?>
        <td data-name="tasa_trx"<?= $Grid->tasa_trx->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_tasa_trx" class="el_cont_asiento_detalle_mdk_tasa_trx">
<input type="<?= $Grid->tasa_trx->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_tasa_trx" id="x<?= $Grid->RowIndex ?>_tasa_trx" data-table="cont_asiento_detalle_mdk" data-field="x_tasa_trx" value="<?= $Grid->tasa_trx->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->tasa_trx->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->tasa_trx->formatPattern()) ?>"<?= $Grid->tasa_trx->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->tasa_trx->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_tasa_trx" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_tasa_trx" id="o<?= $Grid->RowIndex ?>_tasa_trx" value="<?= HtmlEncode($Grid->tasa_trx->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_tasa_trx" class="el_cont_asiento_detalle_mdk_tasa_trx">
<input type="<?= $Grid->tasa_trx->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_tasa_trx" id="x<?= $Grid->RowIndex ?>_tasa_trx" data-table="cont_asiento_detalle_mdk" data-field="x_tasa_trx" value="<?= $Grid->tasa_trx->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->tasa_trx->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->tasa_trx->formatPattern()) ?>"<?= $Grid->tasa_trx->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->tasa_trx->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_tasa_trx" class="el_cont_asiento_detalle_mdk_tasa_trx">
<span<?= $Grid->tasa_trx->viewAttributes() ?>>
<?= $Grid->tasa_trx->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_tasa_trx" data-hidden="1" name="fcont_asiento_detalle_mdkgrid$x<?= $Grid->RowIndex ?>_tasa_trx" id="fcont_asiento_detalle_mdkgrid$x<?= $Grid->RowIndex ?>_tasa_trx" value="<?= HtmlEncode($Grid->tasa_trx->FormValue) ?>">
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_tasa_trx" data-hidden="1" data-old name="fcont_asiento_detalle_mdkgrid$o<?= $Grid->RowIndex ?>_tasa_trx" id="fcont_asiento_detalle_mdkgrid$o<?= $Grid->RowIndex ?>_tasa_trx" value="<?= HtmlEncode($Grid->tasa_trx->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->monto_moneda_trx->Visible) { // monto_moneda_trx ?>
        <td data-name="monto_moneda_trx"<?= $Grid->monto_moneda_trx->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_monto_moneda_trx" class="el_cont_asiento_detalle_mdk_monto_moneda_trx">
<input type="<?= $Grid->monto_moneda_trx->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_monto_moneda_trx" id="x<?= $Grid->RowIndex ?>_monto_moneda_trx" data-table="cont_asiento_detalle_mdk" data-field="x_monto_moneda_trx" value="<?= $Grid->monto_moneda_trx->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->monto_moneda_trx->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->monto_moneda_trx->formatPattern()) ?>"<?= $Grid->monto_moneda_trx->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_moneda_trx->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_monto_moneda_trx" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_monto_moneda_trx" id="o<?= $Grid->RowIndex ?>_monto_moneda_trx" value="<?= HtmlEncode($Grid->monto_moneda_trx->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_monto_moneda_trx" class="el_cont_asiento_detalle_mdk_monto_moneda_trx">
<input type="<?= $Grid->monto_moneda_trx->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_monto_moneda_trx" id="x<?= $Grid->RowIndex ?>_monto_moneda_trx" data-table="cont_asiento_detalle_mdk" data-field="x_monto_moneda_trx" value="<?= $Grid->monto_moneda_trx->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->monto_moneda_trx->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->monto_moneda_trx->formatPattern()) ?>"<?= $Grid->monto_moneda_trx->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_moneda_trx->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_monto_moneda_trx" class="el_cont_asiento_detalle_mdk_monto_moneda_trx">
<span<?= $Grid->monto_moneda_trx->viewAttributes() ?>>
<?= $Grid->monto_moneda_trx->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_monto_moneda_trx" data-hidden="1" name="fcont_asiento_detalle_mdkgrid$x<?= $Grid->RowIndex ?>_monto_moneda_trx" id="fcont_asiento_detalle_mdkgrid$x<?= $Grid->RowIndex ?>_monto_moneda_trx" value="<?= HtmlEncode($Grid->monto_moneda_trx->FormValue) ?>">
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_monto_moneda_trx" data-hidden="1" data-old name="fcont_asiento_detalle_mdkgrid$o<?= $Grid->RowIndex ?>_monto_moneda_trx" id="fcont_asiento_detalle_mdkgrid$o<?= $Grid->RowIndex ?>_monto_moneda_trx" value="<?= HtmlEncode($Grid->monto_moneda_trx->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->debe_bs->Visible) { // debe_bs ?>
        <td data-name="debe_bs"<?= $Grid->debe_bs->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_debe_bs" class="el_cont_asiento_detalle_mdk_debe_bs">
<input type="<?= $Grid->debe_bs->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_debe_bs" id="x<?= $Grid->RowIndex ?>_debe_bs" data-table="cont_asiento_detalle_mdk" data-field="x_debe_bs" value="<?= $Grid->debe_bs->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->debe_bs->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->debe_bs->formatPattern()) ?>"<?= $Grid->debe_bs->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->debe_bs->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_debe_bs" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_debe_bs" id="o<?= $Grid->RowIndex ?>_debe_bs" value="<?= HtmlEncode($Grid->debe_bs->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_debe_bs" class="el_cont_asiento_detalle_mdk_debe_bs">
<input type="<?= $Grid->debe_bs->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_debe_bs" id="x<?= $Grid->RowIndex ?>_debe_bs" data-table="cont_asiento_detalle_mdk" data-field="x_debe_bs" value="<?= $Grid->debe_bs->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->debe_bs->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->debe_bs->formatPattern()) ?>"<?= $Grid->debe_bs->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->debe_bs->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_debe_bs" class="el_cont_asiento_detalle_mdk_debe_bs">
<span<?= $Grid->debe_bs->viewAttributes() ?>>
<?= $Grid->debe_bs->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_debe_bs" data-hidden="1" name="fcont_asiento_detalle_mdkgrid$x<?= $Grid->RowIndex ?>_debe_bs" id="fcont_asiento_detalle_mdkgrid$x<?= $Grid->RowIndex ?>_debe_bs" value="<?= HtmlEncode($Grid->debe_bs->FormValue) ?>">
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_debe_bs" data-hidden="1" data-old name="fcont_asiento_detalle_mdkgrid$o<?= $Grid->RowIndex ?>_debe_bs" id="fcont_asiento_detalle_mdkgrid$o<?= $Grid->RowIndex ?>_debe_bs" value="<?= HtmlEncode($Grid->debe_bs->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->haber_bs->Visible) { // haber_bs ?>
        <td data-name="haber_bs"<?= $Grid->haber_bs->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_haber_bs" class="el_cont_asiento_detalle_mdk_haber_bs">
<input type="<?= $Grid->haber_bs->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_haber_bs" id="x<?= $Grid->RowIndex ?>_haber_bs" data-table="cont_asiento_detalle_mdk" data-field="x_haber_bs" value="<?= $Grid->haber_bs->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->haber_bs->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->haber_bs->formatPattern()) ?>"<?= $Grid->haber_bs->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->haber_bs->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_haber_bs" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_haber_bs" id="o<?= $Grid->RowIndex ?>_haber_bs" value="<?= HtmlEncode($Grid->haber_bs->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_haber_bs" class="el_cont_asiento_detalle_mdk_haber_bs">
<input type="<?= $Grid->haber_bs->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_haber_bs" id="x<?= $Grid->RowIndex ?>_haber_bs" data-table="cont_asiento_detalle_mdk" data-field="x_haber_bs" value="<?= $Grid->haber_bs->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->haber_bs->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->haber_bs->formatPattern()) ?>"<?= $Grid->haber_bs->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->haber_bs->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_haber_bs" class="el_cont_asiento_detalle_mdk_haber_bs">
<span<?= $Grid->haber_bs->viewAttributes() ?>>
<?= $Grid->haber_bs->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_haber_bs" data-hidden="1" name="fcont_asiento_detalle_mdkgrid$x<?= $Grid->RowIndex ?>_haber_bs" id="fcont_asiento_detalle_mdkgrid$x<?= $Grid->RowIndex ?>_haber_bs" value="<?= HtmlEncode($Grid->haber_bs->FormValue) ?>">
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_haber_bs" data-hidden="1" data-old name="fcont_asiento_detalle_mdkgrid$o<?= $Grid->RowIndex ?>_haber_bs" id="fcont_asiento_detalle_mdkgrid$o<?= $Grid->RowIndex ?>_haber_bs" value="<?= HtmlEncode($Grid->haber_bs->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->created_at->Visible) { // created_at ?>
        <td data-name="created_at"<?= $Grid->created_at->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_created_at" class="el_cont_asiento_detalle_mdk_created_at">
<input type="<?= $Grid->created_at->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_created_at" id="x<?= $Grid->RowIndex ?>_created_at" data-table="cont_asiento_detalle_mdk" data-field="x_created_at" value="<?= $Grid->created_at->EditValue ?>" placeholder="<?= HtmlEncode($Grid->created_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->created_at->formatPattern()) ?>"<?= $Grid->created_at->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->created_at->getErrorMessage() ?></div>
<?php if (!$Grid->created_at->ReadOnly && !$Grid->created_at->Disabled && !isset($Grid->created_at->EditAttrs["readonly"]) && !isset($Grid->created_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcont_asiento_detalle_mdkgrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
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
    ew.createDateTimePicker("fcont_asiento_detalle_mdkgrid", "x<?= $Grid->RowIndex ?>_created_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_created_at" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_created_at" id="o<?= $Grid->RowIndex ?>_created_at" value="<?= HtmlEncode($Grid->created_at->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_created_at" class="el_cont_asiento_detalle_mdk_created_at">
<input type="<?= $Grid->created_at->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_created_at" id="x<?= $Grid->RowIndex ?>_created_at" data-table="cont_asiento_detalle_mdk" data-field="x_created_at" value="<?= $Grid->created_at->EditValue ?>" placeholder="<?= HtmlEncode($Grid->created_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->created_at->formatPattern()) ?>"<?= $Grid->created_at->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->created_at->getErrorMessage() ?></div>
<?php if (!$Grid->created_at->ReadOnly && !$Grid->created_at->Disabled && !isset($Grid->created_at->EditAttrs["readonly"]) && !isset($Grid->created_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcont_asiento_detalle_mdkgrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
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
    ew.createDateTimePicker("fcont_asiento_detalle_mdkgrid", "x<?= $Grid->RowIndex ?>_created_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_detalle_mdk_created_at" class="el_cont_asiento_detalle_mdk_created_at">
<span<?= $Grid->created_at->viewAttributes() ?>>
<?= $Grid->created_at->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_created_at" data-hidden="1" name="fcont_asiento_detalle_mdkgrid$x<?= $Grid->RowIndex ?>_created_at" id="fcont_asiento_detalle_mdkgrid$x<?= $Grid->RowIndex ?>_created_at" value="<?= HtmlEncode($Grid->created_at->FormValue) ?>">
<input type="hidden" data-table="cont_asiento_detalle_mdk" data-field="x_created_at" data-hidden="1" data-old name="fcont_asiento_detalle_mdkgrid$o<?= $Grid->RowIndex ?>_created_at" id="fcont_asiento_detalle_mdkgrid$o<?= $Grid->RowIndex ?>_created_at" value="<?= HtmlEncode($Grid->created_at->OldValue) ?>">
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
loadjs.ready(["fcont_asiento_detalle_mdkgrid","load"], () => fcont_asiento_detalle_mdkgrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<?php
// Render aggregate row
$Grid->RowType = RowType::AGGREGATE;
$Grid->resetAttributes();
$Grid->renderRow();
?>
<?php if ($Grid->TotalRecords > 0 && $Grid->CurrentMode == "view") { ?>
<tfoot><!-- Table footer -->
    <tr class="ew-table-footer">
<?php
// Render list options
$Grid->renderListOptions();

// Render list options (footer, left)
$Grid->ListOptions->render("footer", "left");
?>
    <?php if ($Grid->id->Visible) { // id ?>
        <td data-name="id" class="<?= $Grid->id->footerCellClass() ?>"><span id="elf_cont_asiento_detalle_mdk_id" class="cont_asiento_detalle_mdk_id">
        </span></td>
    <?php } ?>
    <?php if ($Grid->asiento_id->Visible) { // asiento_id ?>
        <td data-name="asiento_id" class="<?= $Grid->asiento_id->footerCellClass() ?>"><span id="elf_cont_asiento_detalle_mdk_asiento_id" class="cont_asiento_detalle_mdk_asiento_id">
        </span></td>
    <?php } ?>
    <?php if ($Grid->cuenta_id->Visible) { // cuenta_id ?>
        <td data-name="cuenta_id" class="<?= $Grid->cuenta_id->footerCellClass() ?>"><span id="elf_cont_asiento_detalle_mdk_cuenta_id" class="cont_asiento_detalle_mdk_cuenta_id">
        </span></td>
    <?php } ?>
    <?php if ($Grid->centro_costo_id->Visible) { // centro_costo_id ?>
        <td data-name="centro_costo_id" class="<?= $Grid->centro_costo_id->footerCellClass() ?>"><span id="elf_cont_asiento_detalle_mdk_centro_costo_id" class="cont_asiento_detalle_mdk_centro_costo_id">
        </span></td>
    <?php } ?>
    <?php if ($Grid->concepto->Visible) { // concepto ?>
        <td data-name="concepto" class="<?= $Grid->concepto->footerCellClass() ?>"><span id="elf_cont_asiento_detalle_mdk_concepto" class="cont_asiento_detalle_mdk_concepto">
        </span></td>
    <?php } ?>
    <?php if ($Grid->moneda_trx->Visible) { // moneda_trx ?>
        <td data-name="moneda_trx" class="<?= $Grid->moneda_trx->footerCellClass() ?>"><span id="elf_cont_asiento_detalle_mdk_moneda_trx" class="cont_asiento_detalle_mdk_moneda_trx">
        </span></td>
    <?php } ?>
    <?php if ($Grid->tasa_trx->Visible) { // tasa_trx ?>
        <td data-name="tasa_trx" class="<?= $Grid->tasa_trx->footerCellClass() ?>"><span id="elf_cont_asiento_detalle_mdk_tasa_trx" class="cont_asiento_detalle_mdk_tasa_trx">
        </span></td>
    <?php } ?>
    <?php if ($Grid->monto_moneda_trx->Visible) { // monto_moneda_trx ?>
        <td data-name="monto_moneda_trx" class="<?= $Grid->monto_moneda_trx->footerCellClass() ?>"><span id="elf_cont_asiento_detalle_mdk_monto_moneda_trx" class="cont_asiento_detalle_mdk_monto_moneda_trx">
        </span></td>
    <?php } ?>
    <?php if ($Grid->debe_bs->Visible) { // debe_bs ?>
        <td data-name="debe_bs" class="<?= $Grid->debe_bs->footerCellClass() ?>"><span id="elf_cont_asiento_detalle_mdk_debe_bs" class="cont_asiento_detalle_mdk_debe_bs">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Grid->debe_bs->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Grid->haber_bs->Visible) { // haber_bs ?>
        <td data-name="haber_bs" class="<?= $Grid->haber_bs->footerCellClass() ?>"><span id="elf_cont_asiento_detalle_mdk_haber_bs" class="cont_asiento_detalle_mdk_haber_bs">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Grid->haber_bs->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Grid->created_at->Visible) { // created_at ?>
        <td data-name="created_at" class="<?= $Grid->created_at->footerCellClass() ?>"><span id="elf_cont_asiento_detalle_mdk_created_at" class="cont_asiento_detalle_mdk_created_at">
        </span></td>
    <?php } ?>
<?php
// Render list options (footer, right)
$Grid->ListOptions->render("footer", "right");
?>
    </tr>
</tfoot>
<?php } ?>
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
<input type="hidden" name="detailpage" value="fcont_asiento_detalle_mdkgrid">
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
    ew.addEventHandlers("cont_asiento_detalle_mdk");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
