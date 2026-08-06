<?php

namespace PHPMaker2024\mandrake;

// Set up and run Grid object
$Grid = Container("ViewCuentasPorPagarGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fview_cuentas_por_pagargrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { view_cuentas_por_pagar: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fview_cuentas_por_pagargrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["proveedor", [fields.proveedor.visible && fields.proveedor.required ? ew.Validators.required(fields.proveedor.caption) : null, ew.Validators.integer], fields.proveedor.isInvalid],
            ["tipo_documento", [fields.tipo_documento.visible && fields.tipo_documento.required ? ew.Validators.required(fields.tipo_documento.caption) : null], fields.tipo_documento.isInvalid],
            ["documento", [fields.documento.visible && fields.documento.required ? ew.Validators.required(fields.documento.caption) : null], fields.documento.isInvalid],
            ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(fields.fecha.clientFormatPattern)], fields.fecha.isInvalid],
            ["fecha_ultimo_pago", [fields.fecha_ultimo_pago.visible && fields.fecha_ultimo_pago.required ? ew.Validators.required(fields.fecha_ultimo_pago.caption) : null, ew.Validators.datetime(fields.fecha_ultimo_pago.clientFormatPattern)], fields.fecha_ultimo_pago.isInvalid],
            ["monto_documento_bs", [fields.monto_documento_bs.visible && fields.monto_documento_bs.required ? ew.Validators.required(fields.monto_documento_bs.caption) : null, ew.Validators.float], fields.monto_documento_bs.isInvalid],
            ["total_pagado_bs", [fields.total_pagado_bs.visible && fields.total_pagado_bs.required ? ew.Validators.required(fields.total_pagado_bs.caption) : null, ew.Validators.float], fields.total_pagado_bs.isInvalid],
            ["saldo_bs", [fields.saldo_bs.visible && fields.saldo_bs.required ? ew.Validators.required(fields.saldo_bs.caption) : null, ew.Validators.float], fields.saldo_bs.isInvalid],
            ["antiguedad", [fields.antiguedad.visible && fields.antiguedad.required ? ew.Validators.required(fields.antiguedad.caption) : null], fields.antiguedad.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["proveedor",false],["tipo_documento",false],["documento",false],["fecha",false],["fecha_ultimo_pago",false],["monto_documento_bs",false],["total_pagado_bs",false],["saldo_bs",false],["antiguedad",false]];
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
            "tipo_documento": <?= $Grid->tipo_documento->toClientList($Grid) ?>,
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
<div id="fview_cuentas_por_pagargrid" class="ew-form ew-list-form">
<div id="gmp_view_cuentas_por_pagar" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_view_cuentas_por_pagargrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
        <th data-name="proveedor" class="<?= $Grid->proveedor->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_proveedor" class="view_cuentas_por_pagar_proveedor"><?= $Grid->renderFieldHeader($Grid->proveedor) ?></div></th>
<?php } ?>
<?php if ($Grid->tipo_documento->Visible) { // tipo_documento ?>
        <th data-name="tipo_documento" class="<?= $Grid->tipo_documento->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_tipo_documento" class="view_cuentas_por_pagar_tipo_documento"><?= $Grid->renderFieldHeader($Grid->tipo_documento) ?></div></th>
<?php } ?>
<?php if ($Grid->documento->Visible) { // documento ?>
        <th data-name="documento" class="<?= $Grid->documento->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_documento" class="view_cuentas_por_pagar_documento"><?= $Grid->renderFieldHeader($Grid->documento) ?></div></th>
<?php } ?>
<?php if ($Grid->fecha->Visible) { // fecha ?>
        <th data-name="fecha" class="<?= $Grid->fecha->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_fecha" class="view_cuentas_por_pagar_fecha"><?= $Grid->renderFieldHeader($Grid->fecha) ?></div></th>
<?php } ?>
<?php if ($Grid->fecha_ultimo_pago->Visible) { // fecha_ultimo_pago ?>
        <th data-name="fecha_ultimo_pago" class="<?= $Grid->fecha_ultimo_pago->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_fecha_ultimo_pago" class="view_cuentas_por_pagar_fecha_ultimo_pago"><?= $Grid->renderFieldHeader($Grid->fecha_ultimo_pago) ?></div></th>
<?php } ?>
<?php if ($Grid->monto_documento_bs->Visible) { // monto_documento_bs ?>
        <th data-name="monto_documento_bs" class="<?= $Grid->monto_documento_bs->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_monto_documento_bs" class="view_cuentas_por_pagar_monto_documento_bs"><?= $Grid->renderFieldHeader($Grid->monto_documento_bs) ?></div></th>
<?php } ?>
<?php if ($Grid->total_pagado_bs->Visible) { // total_pagado_bs ?>
        <th data-name="total_pagado_bs" class="<?= $Grid->total_pagado_bs->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_total_pagado_bs" class="view_cuentas_por_pagar_total_pagado_bs"><?= $Grid->renderFieldHeader($Grid->total_pagado_bs) ?></div></th>
<?php } ?>
<?php if ($Grid->saldo_bs->Visible) { // saldo_bs ?>
        <th data-name="saldo_bs" class="<?= $Grid->saldo_bs->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_saldo_bs" class="view_cuentas_por_pagar_saldo_bs"><?= $Grid->renderFieldHeader($Grid->saldo_bs) ?></div></th>
<?php } ?>
<?php if ($Grid->antiguedad->Visible) { // antiguedad ?>
        <th data-name="antiguedad" class="<?= $Grid->antiguedad->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_antiguedad" class="view_cuentas_por_pagar_antiguedad"><?= $Grid->renderFieldHeader($Grid->antiguedad) ?></div></th>
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
<?php if ($Grid->proveedor->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_proveedor" class="el_view_cuentas_por_pagar_proveedor">
<span<?= $Grid->proveedor->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->proveedor->getDisplayValue($Grid->proveedor->ViewValue) ?></span></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_proveedor" name="x<?= $Grid->RowIndex ?>_proveedor" value="<?= HtmlEncode($Grid->proveedor->CurrentValue) ?>" data-hidden="1">
</span>
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_proveedor" class="el_view_cuentas_por_pagar_proveedor">
    <select
        id="x<?= $Grid->RowIndex ?>_proveedor"
        name="x<?= $Grid->RowIndex ?>_proveedor"
        class="form-control ew-select<?= $Grid->proveedor->isInvalidClass() ?>"
        data-select2-id="fview_cuentas_por_pagargrid_x<?= $Grid->RowIndex ?>_proveedor"
        data-table="view_cuentas_por_pagar"
        data-field="x_proveedor"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->proveedor->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->proveedor->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->proveedor->getPlaceHolder()) ?>"
        <?= $Grid->proveedor->editAttributes() ?>>
        <?= $Grid->proveedor->selectOptionListHtml("x{$Grid->RowIndex}_proveedor") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->proveedor->getErrorMessage() ?></div>
<?= $Grid->proveedor->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_proveedor") ?>
<script>
loadjs.ready("fview_cuentas_por_pagargrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_proveedor", selectId: "fview_cuentas_por_pagargrid_x<?= $Grid->RowIndex ?>_proveedor" };
    if (fview_cuentas_por_pagargrid.lists.proveedor?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_proveedor", form: "fview_cuentas_por_pagargrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_proveedor", form: "fview_cuentas_por_pagargrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.view_cuentas_por_pagar.fields.proveedor.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_proveedor" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_proveedor" id="o<?= $Grid->RowIndex ?>_proveedor" value="<?= HtmlEncode($Grid->proveedor->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<?php if ($Grid->proveedor->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_proveedor" class="el_view_cuentas_por_pagar_proveedor">
<span<?= $Grid->proveedor->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->proveedor->getDisplayValue($Grid->proveedor->ViewValue) ?></span></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_proveedor" name="x<?= $Grid->RowIndex ?>_proveedor" value="<?= HtmlEncode($Grid->proveedor->CurrentValue) ?>" data-hidden="1">
</span>
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_proveedor" class="el_view_cuentas_por_pagar_proveedor">
    <select
        id="x<?= $Grid->RowIndex ?>_proveedor"
        name="x<?= $Grid->RowIndex ?>_proveedor"
        class="form-control ew-select<?= $Grid->proveedor->isInvalidClass() ?>"
        data-select2-id="fview_cuentas_por_pagargrid_x<?= $Grid->RowIndex ?>_proveedor"
        data-table="view_cuentas_por_pagar"
        data-field="x_proveedor"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->proveedor->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->proveedor->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->proveedor->getPlaceHolder()) ?>"
        <?= $Grid->proveedor->editAttributes() ?>>
        <?= $Grid->proveedor->selectOptionListHtml("x{$Grid->RowIndex}_proveedor") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->proveedor->getErrorMessage() ?></div>
<?= $Grid->proveedor->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_proveedor") ?>
<script>
loadjs.ready("fview_cuentas_por_pagargrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_proveedor", selectId: "fview_cuentas_por_pagargrid_x<?= $Grid->RowIndex ?>_proveedor" };
    if (fview_cuentas_por_pagargrid.lists.proveedor?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_proveedor", form: "fview_cuentas_por_pagargrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_proveedor", form: "fview_cuentas_por_pagargrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.view_cuentas_por_pagar.fields.proveedor.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_proveedor" class="el_view_cuentas_por_pagar_proveedor">
<span<?= $Grid->proveedor->viewAttributes() ?>>
<?= $Grid->proveedor->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_proveedor" data-hidden="1" name="fview_cuentas_por_pagargrid$x<?= $Grid->RowIndex ?>_proveedor" id="fview_cuentas_por_pagargrid$x<?= $Grid->RowIndex ?>_proveedor" value="<?= HtmlEncode($Grid->proveedor->FormValue) ?>">
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_proveedor" data-hidden="1" data-old name="fview_cuentas_por_pagargrid$o<?= $Grid->RowIndex ?>_proveedor" id="fview_cuentas_por_pagargrid$o<?= $Grid->RowIndex ?>_proveedor" value="<?= HtmlEncode($Grid->proveedor->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->tipo_documento->Visible) { // tipo_documento ?>
        <td data-name="tipo_documento"<?= $Grid->tipo_documento->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_tipo_documento" class="el_view_cuentas_por_pagar_tipo_documento">
    <select
        id="x<?= $Grid->RowIndex ?>_tipo_documento"
        name="x<?= $Grid->RowIndex ?>_tipo_documento"
        class="form-select ew-select<?= $Grid->tipo_documento->isInvalidClass() ?>"
        <?php if (!$Grid->tipo_documento->IsNativeSelect) { ?>
        data-select2-id="fview_cuentas_por_pagargrid_x<?= $Grid->RowIndex ?>_tipo_documento"
        <?php } ?>
        data-table="view_cuentas_por_pagar"
        data-field="x_tipo_documento"
        data-value-separator="<?= $Grid->tipo_documento->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->tipo_documento->getPlaceHolder()) ?>"
        <?= $Grid->tipo_documento->editAttributes() ?>>
        <?= $Grid->tipo_documento->selectOptionListHtml("x{$Grid->RowIndex}_tipo_documento") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->tipo_documento->getErrorMessage() ?></div>
<?php if (!$Grid->tipo_documento->IsNativeSelect) { ?>
<script>
loadjs.ready("fview_cuentas_por_pagargrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_tipo_documento", selectId: "fview_cuentas_por_pagargrid_x<?= $Grid->RowIndex ?>_tipo_documento" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fview_cuentas_por_pagargrid.lists.tipo_documento?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_tipo_documento", form: "fview_cuentas_por_pagargrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_tipo_documento", form: "fview_cuentas_por_pagargrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.view_cuentas_por_pagar.fields.tipo_documento.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_tipo_documento" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_tipo_documento" id="o<?= $Grid->RowIndex ?>_tipo_documento" value="<?= HtmlEncode($Grid->tipo_documento->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_tipo_documento" class="el_view_cuentas_por_pagar_tipo_documento">
    <select
        id="x<?= $Grid->RowIndex ?>_tipo_documento"
        name="x<?= $Grid->RowIndex ?>_tipo_documento"
        class="form-select ew-select<?= $Grid->tipo_documento->isInvalidClass() ?>"
        <?php if (!$Grid->tipo_documento->IsNativeSelect) { ?>
        data-select2-id="fview_cuentas_por_pagargrid_x<?= $Grid->RowIndex ?>_tipo_documento"
        <?php } ?>
        data-table="view_cuentas_por_pagar"
        data-field="x_tipo_documento"
        data-value-separator="<?= $Grid->tipo_documento->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->tipo_documento->getPlaceHolder()) ?>"
        <?= $Grid->tipo_documento->editAttributes() ?>>
        <?= $Grid->tipo_documento->selectOptionListHtml("x{$Grid->RowIndex}_tipo_documento") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->tipo_documento->getErrorMessage() ?></div>
<?php if (!$Grid->tipo_documento->IsNativeSelect) { ?>
<script>
loadjs.ready("fview_cuentas_por_pagargrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_tipo_documento", selectId: "fview_cuentas_por_pagargrid_x<?= $Grid->RowIndex ?>_tipo_documento" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fview_cuentas_por_pagargrid.lists.tipo_documento?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_tipo_documento", form: "fview_cuentas_por_pagargrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_tipo_documento", form: "fview_cuentas_por_pagargrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.view_cuentas_por_pagar.fields.tipo_documento.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_tipo_documento" class="el_view_cuentas_por_pagar_tipo_documento">
<span<?= $Grid->tipo_documento->viewAttributes() ?>>
<?= $Grid->tipo_documento->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_tipo_documento" data-hidden="1" name="fview_cuentas_por_pagargrid$x<?= $Grid->RowIndex ?>_tipo_documento" id="fview_cuentas_por_pagargrid$x<?= $Grid->RowIndex ?>_tipo_documento" value="<?= HtmlEncode($Grid->tipo_documento->FormValue) ?>">
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_tipo_documento" data-hidden="1" data-old name="fview_cuentas_por_pagargrid$o<?= $Grid->RowIndex ?>_tipo_documento" id="fview_cuentas_por_pagargrid$o<?= $Grid->RowIndex ?>_tipo_documento" value="<?= HtmlEncode($Grid->tipo_documento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->documento->Visible) { // documento ?>
        <td data-name="documento"<?= $Grid->documento->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_documento" class="el_view_cuentas_por_pagar_documento">
<input type="<?= $Grid->documento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_documento" id="x<?= $Grid->RowIndex ?>_documento" data-table="view_cuentas_por_pagar" data-field="x_documento" value="<?= $Grid->documento->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Grid->documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->documento->formatPattern()) ?>"<?= $Grid->documento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->documento->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_documento" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_documento" id="o<?= $Grid->RowIndex ?>_documento" value="<?= HtmlEncode($Grid->documento->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_documento" class="el_view_cuentas_por_pagar_documento">
<input type="<?= $Grid->documento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_documento" id="x<?= $Grid->RowIndex ?>_documento" data-table="view_cuentas_por_pagar" data-field="x_documento" value="<?= $Grid->documento->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Grid->documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->documento->formatPattern()) ?>"<?= $Grid->documento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->documento->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_documento" class="el_view_cuentas_por_pagar_documento">
<span<?= $Grid->documento->viewAttributes() ?>>
<?= $Grid->documento->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_documento" data-hidden="1" name="fview_cuentas_por_pagargrid$x<?= $Grid->RowIndex ?>_documento" id="fview_cuentas_por_pagargrid$x<?= $Grid->RowIndex ?>_documento" value="<?= HtmlEncode($Grid->documento->FormValue) ?>">
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_documento" data-hidden="1" data-old name="fview_cuentas_por_pagargrid$o<?= $Grid->RowIndex ?>_documento" id="fview_cuentas_por_pagargrid$o<?= $Grid->RowIndex ?>_documento" value="<?= HtmlEncode($Grid->documento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->fecha->Visible) { // fecha ?>
        <td data-name="fecha"<?= $Grid->fecha->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_fecha" class="el_view_cuentas_por_pagar_fecha">
<input type="<?= $Grid->fecha->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_fecha" id="x<?= $Grid->RowIndex ?>_fecha" data-table="view_cuentas_por_pagar" data-field="x_fecha" value="<?= $Grid->fecha->EditValue ?>" placeholder="<?= HtmlEncode($Grid->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fecha->formatPattern()) ?>"<?= $Grid->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha->getErrorMessage() ?></div>
<?php if (!$Grid->fecha->ReadOnly && !$Grid->fecha->Disabled && !isset($Grid->fecha->EditAttrs["readonly"]) && !isset($Grid->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fview_cuentas_por_pagargrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(7) ?>",
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
    ew.createDateTimePicker("fview_cuentas_por_pagargrid", "x<?= $Grid->RowIndex ?>_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_fecha" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_fecha" id="o<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_fecha" class="el_view_cuentas_por_pagar_fecha">
<input type="<?= $Grid->fecha->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_fecha" id="x<?= $Grid->RowIndex ?>_fecha" data-table="view_cuentas_por_pagar" data-field="x_fecha" value="<?= $Grid->fecha->EditValue ?>" placeholder="<?= HtmlEncode($Grid->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fecha->formatPattern()) ?>"<?= $Grid->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha->getErrorMessage() ?></div>
<?php if (!$Grid->fecha->ReadOnly && !$Grid->fecha->Disabled && !isset($Grid->fecha->EditAttrs["readonly"]) && !isset($Grid->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fview_cuentas_por_pagargrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(7) ?>",
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
    ew.createDateTimePicker("fview_cuentas_por_pagargrid", "x<?= $Grid->RowIndex ?>_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_fecha" class="el_view_cuentas_por_pagar_fecha">
<span<?= $Grid->fecha->viewAttributes() ?>>
<?= $Grid->fecha->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_fecha" data-hidden="1" name="fview_cuentas_por_pagargrid$x<?= $Grid->RowIndex ?>_fecha" id="fview_cuentas_por_pagargrid$x<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->FormValue) ?>">
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_fecha" data-hidden="1" data-old name="fview_cuentas_por_pagargrid$o<?= $Grid->RowIndex ?>_fecha" id="fview_cuentas_por_pagargrid$o<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->fecha_ultimo_pago->Visible) { // fecha_ultimo_pago ?>
        <td data-name="fecha_ultimo_pago"<?= $Grid->fecha_ultimo_pago->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_fecha_ultimo_pago" class="el_view_cuentas_por_pagar_fecha_ultimo_pago">
<input type="<?= $Grid->fecha_ultimo_pago->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_fecha_ultimo_pago" id="x<?= $Grid->RowIndex ?>_fecha_ultimo_pago" data-table="view_cuentas_por_pagar" data-field="x_fecha_ultimo_pago" value="<?= $Grid->fecha_ultimo_pago->EditValue ?>" placeholder="<?= HtmlEncode($Grid->fecha_ultimo_pago->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fecha_ultimo_pago->formatPattern()) ?>"<?= $Grid->fecha_ultimo_pago->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha_ultimo_pago->getErrorMessage() ?></div>
<?php if (!$Grid->fecha_ultimo_pago->ReadOnly && !$Grid->fecha_ultimo_pago->Disabled && !isset($Grid->fecha_ultimo_pago->EditAttrs["readonly"]) && !isset($Grid->fecha_ultimo_pago->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fview_cuentas_por_pagargrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(7) ?>",
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
    ew.createDateTimePicker("fview_cuentas_por_pagargrid", "x<?= $Grid->RowIndex ?>_fecha_ultimo_pago", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_fecha_ultimo_pago" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_fecha_ultimo_pago" id="o<?= $Grid->RowIndex ?>_fecha_ultimo_pago" value="<?= HtmlEncode($Grid->fecha_ultimo_pago->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_fecha_ultimo_pago" class="el_view_cuentas_por_pagar_fecha_ultimo_pago">
<input type="<?= $Grid->fecha_ultimo_pago->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_fecha_ultimo_pago" id="x<?= $Grid->RowIndex ?>_fecha_ultimo_pago" data-table="view_cuentas_por_pagar" data-field="x_fecha_ultimo_pago" value="<?= $Grid->fecha_ultimo_pago->EditValue ?>" placeholder="<?= HtmlEncode($Grid->fecha_ultimo_pago->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fecha_ultimo_pago->formatPattern()) ?>"<?= $Grid->fecha_ultimo_pago->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha_ultimo_pago->getErrorMessage() ?></div>
<?php if (!$Grid->fecha_ultimo_pago->ReadOnly && !$Grid->fecha_ultimo_pago->Disabled && !isset($Grid->fecha_ultimo_pago->EditAttrs["readonly"]) && !isset($Grid->fecha_ultimo_pago->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fview_cuentas_por_pagargrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(7) ?>",
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
    ew.createDateTimePicker("fview_cuentas_por_pagargrid", "x<?= $Grid->RowIndex ?>_fecha_ultimo_pago", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_fecha_ultimo_pago" class="el_view_cuentas_por_pagar_fecha_ultimo_pago">
<span<?= $Grid->fecha_ultimo_pago->viewAttributes() ?>>
<?= $Grid->fecha_ultimo_pago->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_fecha_ultimo_pago" data-hidden="1" name="fview_cuentas_por_pagargrid$x<?= $Grid->RowIndex ?>_fecha_ultimo_pago" id="fview_cuentas_por_pagargrid$x<?= $Grid->RowIndex ?>_fecha_ultimo_pago" value="<?= HtmlEncode($Grid->fecha_ultimo_pago->FormValue) ?>">
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_fecha_ultimo_pago" data-hidden="1" data-old name="fview_cuentas_por_pagargrid$o<?= $Grid->RowIndex ?>_fecha_ultimo_pago" id="fview_cuentas_por_pagargrid$o<?= $Grid->RowIndex ?>_fecha_ultimo_pago" value="<?= HtmlEncode($Grid->fecha_ultimo_pago->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->monto_documento_bs->Visible) { // monto_documento_bs ?>
        <td data-name="monto_documento_bs"<?= $Grid->monto_documento_bs->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_monto_documento_bs" class="el_view_cuentas_por_pagar_monto_documento_bs">
<input type="<?= $Grid->monto_documento_bs->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_monto_documento_bs" id="x<?= $Grid->RowIndex ?>_monto_documento_bs" data-table="view_cuentas_por_pagar" data-field="x_monto_documento_bs" value="<?= $Grid->monto_documento_bs->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->monto_documento_bs->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->monto_documento_bs->formatPattern()) ?>"<?= $Grid->monto_documento_bs->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_documento_bs->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_monto_documento_bs" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_monto_documento_bs" id="o<?= $Grid->RowIndex ?>_monto_documento_bs" value="<?= HtmlEncode($Grid->monto_documento_bs->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_monto_documento_bs" class="el_view_cuentas_por_pagar_monto_documento_bs">
<input type="<?= $Grid->monto_documento_bs->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_monto_documento_bs" id="x<?= $Grid->RowIndex ?>_monto_documento_bs" data-table="view_cuentas_por_pagar" data-field="x_monto_documento_bs" value="<?= $Grid->monto_documento_bs->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->monto_documento_bs->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->monto_documento_bs->formatPattern()) ?>"<?= $Grid->monto_documento_bs->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_documento_bs->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_monto_documento_bs" class="el_view_cuentas_por_pagar_monto_documento_bs">
<span<?= $Grid->monto_documento_bs->viewAttributes() ?>>
<?= $Grid->monto_documento_bs->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_monto_documento_bs" data-hidden="1" name="fview_cuentas_por_pagargrid$x<?= $Grid->RowIndex ?>_monto_documento_bs" id="fview_cuentas_por_pagargrid$x<?= $Grid->RowIndex ?>_monto_documento_bs" value="<?= HtmlEncode($Grid->monto_documento_bs->FormValue) ?>">
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_monto_documento_bs" data-hidden="1" data-old name="fview_cuentas_por_pagargrid$o<?= $Grid->RowIndex ?>_monto_documento_bs" id="fview_cuentas_por_pagargrid$o<?= $Grid->RowIndex ?>_monto_documento_bs" value="<?= HtmlEncode($Grid->monto_documento_bs->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->total_pagado_bs->Visible) { // total_pagado_bs ?>
        <td data-name="total_pagado_bs"<?= $Grid->total_pagado_bs->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_total_pagado_bs" class="el_view_cuentas_por_pagar_total_pagado_bs">
<input type="<?= $Grid->total_pagado_bs->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_total_pagado_bs" id="x<?= $Grid->RowIndex ?>_total_pagado_bs" data-table="view_cuentas_por_pagar" data-field="x_total_pagado_bs" value="<?= $Grid->total_pagado_bs->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->total_pagado_bs->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->total_pagado_bs->formatPattern()) ?>"<?= $Grid->total_pagado_bs->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->total_pagado_bs->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_total_pagado_bs" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_total_pagado_bs" id="o<?= $Grid->RowIndex ?>_total_pagado_bs" value="<?= HtmlEncode($Grid->total_pagado_bs->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_total_pagado_bs" class="el_view_cuentas_por_pagar_total_pagado_bs">
<input type="<?= $Grid->total_pagado_bs->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_total_pagado_bs" id="x<?= $Grid->RowIndex ?>_total_pagado_bs" data-table="view_cuentas_por_pagar" data-field="x_total_pagado_bs" value="<?= $Grid->total_pagado_bs->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->total_pagado_bs->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->total_pagado_bs->formatPattern()) ?>"<?= $Grid->total_pagado_bs->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->total_pagado_bs->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_total_pagado_bs" class="el_view_cuentas_por_pagar_total_pagado_bs">
<span<?= $Grid->total_pagado_bs->viewAttributes() ?>>
<?= $Grid->total_pagado_bs->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_total_pagado_bs" data-hidden="1" name="fview_cuentas_por_pagargrid$x<?= $Grid->RowIndex ?>_total_pagado_bs" id="fview_cuentas_por_pagargrid$x<?= $Grid->RowIndex ?>_total_pagado_bs" value="<?= HtmlEncode($Grid->total_pagado_bs->FormValue) ?>">
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_total_pagado_bs" data-hidden="1" data-old name="fview_cuentas_por_pagargrid$o<?= $Grid->RowIndex ?>_total_pagado_bs" id="fview_cuentas_por_pagargrid$o<?= $Grid->RowIndex ?>_total_pagado_bs" value="<?= HtmlEncode($Grid->total_pagado_bs->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->saldo_bs->Visible) { // saldo_bs ?>
        <td data-name="saldo_bs"<?= $Grid->saldo_bs->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_saldo_bs" class="el_view_cuentas_por_pagar_saldo_bs">
<input type="<?= $Grid->saldo_bs->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_saldo_bs" id="x<?= $Grid->RowIndex ?>_saldo_bs" data-table="view_cuentas_por_pagar" data-field="x_saldo_bs" value="<?= $Grid->saldo_bs->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->saldo_bs->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->saldo_bs->formatPattern()) ?>"<?= $Grid->saldo_bs->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->saldo_bs->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_saldo_bs" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_saldo_bs" id="o<?= $Grid->RowIndex ?>_saldo_bs" value="<?= HtmlEncode($Grid->saldo_bs->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_saldo_bs" class="el_view_cuentas_por_pagar_saldo_bs">
<input type="<?= $Grid->saldo_bs->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_saldo_bs" id="x<?= $Grid->RowIndex ?>_saldo_bs" data-table="view_cuentas_por_pagar" data-field="x_saldo_bs" value="<?= $Grid->saldo_bs->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->saldo_bs->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->saldo_bs->formatPattern()) ?>"<?= $Grid->saldo_bs->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->saldo_bs->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_saldo_bs" class="el_view_cuentas_por_pagar_saldo_bs">
<span<?= $Grid->saldo_bs->viewAttributes() ?>>
<?= $Grid->saldo_bs->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_saldo_bs" data-hidden="1" name="fview_cuentas_por_pagargrid$x<?= $Grid->RowIndex ?>_saldo_bs" id="fview_cuentas_por_pagargrid$x<?= $Grid->RowIndex ?>_saldo_bs" value="<?= HtmlEncode($Grid->saldo_bs->FormValue) ?>">
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_saldo_bs" data-hidden="1" data-old name="fview_cuentas_por_pagargrid$o<?= $Grid->RowIndex ?>_saldo_bs" id="fview_cuentas_por_pagargrid$o<?= $Grid->RowIndex ?>_saldo_bs" value="<?= HtmlEncode($Grid->saldo_bs->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->antiguedad->Visible) { // antiguedad ?>
        <td data-name="antiguedad"<?= $Grid->antiguedad->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_antiguedad" class="el_view_cuentas_por_pagar_antiguedad">
<input type="<?= $Grid->antiguedad->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_antiguedad" id="x<?= $Grid->RowIndex ?>_antiguedad" data-table="view_cuentas_por_pagar" data-field="x_antiguedad" value="<?= $Grid->antiguedad->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Grid->antiguedad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->antiguedad->formatPattern()) ?>"<?= $Grid->antiguedad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->antiguedad->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_antiguedad" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_antiguedad" id="o<?= $Grid->RowIndex ?>_antiguedad" value="<?= HtmlEncode($Grid->antiguedad->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_antiguedad" class="el_view_cuentas_por_pagar_antiguedad">
<input type="<?= $Grid->antiguedad->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_antiguedad" id="x<?= $Grid->RowIndex ?>_antiguedad" data-table="view_cuentas_por_pagar" data-field="x_antiguedad" value="<?= $Grid->antiguedad->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Grid->antiguedad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->antiguedad->formatPattern()) ?>"<?= $Grid->antiguedad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->antiguedad->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_pagar_antiguedad" class="el_view_cuentas_por_pagar_antiguedad">
<span<?= $Grid->antiguedad->viewAttributes() ?>>
<?= $Grid->antiguedad->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_antiguedad" data-hidden="1" name="fview_cuentas_por_pagargrid$x<?= $Grid->RowIndex ?>_antiguedad" id="fview_cuentas_por_pagargrid$x<?= $Grid->RowIndex ?>_antiguedad" value="<?= HtmlEncode($Grid->antiguedad->FormValue) ?>">
<input type="hidden" data-table="view_cuentas_por_pagar" data-field="x_antiguedad" data-hidden="1" data-old name="fview_cuentas_por_pagargrid$o<?= $Grid->RowIndex ?>_antiguedad" id="fview_cuentas_por_pagargrid$o<?= $Grid->RowIndex ?>_antiguedad" value="<?= HtmlEncode($Grid->antiguedad->OldValue) ?>">
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
loadjs.ready(["fview_cuentas_por_pagargrid","load"], () => fview_cuentas_por_pagargrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="fview_cuentas_por_pagargrid">
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
    ew.addEventHandlers("view_cuentas_por_pagar");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
