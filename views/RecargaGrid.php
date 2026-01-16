<?php

namespace PHPMaker2024\mandrake;

// Set up and run Grid object
$Grid = Container("RecargaGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var frecargagrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { recarga: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("frecargagrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["cliente", [fields.cliente.visible && fields.cliente.required ? ew.Validators.required(fields.cliente.caption) : null], fields.cliente.isInvalid],
            ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null], fields.fecha.isInvalid],
            ["metodo_pago", [fields.metodo_pago.visible && fields.metodo_pago.required ? ew.Validators.required(fields.metodo_pago.caption) : null], fields.metodo_pago.isInvalid],
            ["banco", [fields.banco.visible && fields.banco.required ? ew.Validators.required(fields.banco.caption) : null, ew.Validators.integer], fields.banco.isInvalid],
            ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
            ["monto_moneda", [fields.monto_moneda.visible && fields.monto_moneda.required ? ew.Validators.required(fields.monto_moneda.caption) : null, ew.Validators.float], fields.monto_moneda.isInvalid],
            ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
            ["monto_bs", [fields.monto_bs.visible && fields.monto_bs.required ? ew.Validators.required(fields.monto_bs.caption) : null, ew.Validators.float], fields.monto_bs.isInvalid],
            ["saldo", [fields.saldo.visible && fields.saldo.required ? ew.Validators.required(fields.saldo.caption) : null, ew.Validators.float], fields.saldo.isInvalid],
            ["_username", [fields._username.visible && fields._username.required ? ew.Validators.required(fields._username.caption) : null], fields._username.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["cliente",false],["fecha",false],["metodo_pago",false],["banco",false],["referencia",false],["monto_moneda",false],["moneda",false],["monto_bs",false],["saldo",false],["_username",false]];
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
            "banco": <?= $Grid->banco->toClientList($Grid) ?>,
            "moneda": <?= $Grid->moneda->toClientList($Grid) ?>,
            "_username": <?= $Grid->_username->toClientList($Grid) ?>,
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
<div id="frecargagrid" class="ew-form ew-list-form">
<div id="gmp_recarga" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_recargagrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
        <th data-name="cliente" class="<?= $Grid->cliente->headerCellClass() ?>"><div id="elh_recarga_cliente" class="recarga_cliente"><?= $Grid->renderFieldHeader($Grid->cliente) ?></div></th>
<?php } ?>
<?php if ($Grid->fecha->Visible) { // fecha ?>
        <th data-name="fecha" class="<?= $Grid->fecha->headerCellClass() ?>"><div id="elh_recarga_fecha" class="recarga_fecha"><?= $Grid->renderFieldHeader($Grid->fecha) ?></div></th>
<?php } ?>
<?php if ($Grid->metodo_pago->Visible) { // metodo_pago ?>
        <th data-name="metodo_pago" class="<?= $Grid->metodo_pago->headerCellClass() ?>"><div id="elh_recarga_metodo_pago" class="recarga_metodo_pago"><?= $Grid->renderFieldHeader($Grid->metodo_pago) ?></div></th>
<?php } ?>
<?php if ($Grid->banco->Visible) { // banco ?>
        <th data-name="banco" class="<?= $Grid->banco->headerCellClass() ?>"><div id="elh_recarga_banco" class="recarga_banco"><?= $Grid->renderFieldHeader($Grid->banco) ?></div></th>
<?php } ?>
<?php if ($Grid->referencia->Visible) { // referencia ?>
        <th data-name="referencia" class="<?= $Grid->referencia->headerCellClass() ?>"><div id="elh_recarga_referencia" class="recarga_referencia"><?= $Grid->renderFieldHeader($Grid->referencia) ?></div></th>
<?php } ?>
<?php if ($Grid->monto_moneda->Visible) { // monto_moneda ?>
        <th data-name="monto_moneda" class="<?= $Grid->monto_moneda->headerCellClass() ?>"><div id="elh_recarga_monto_moneda" class="recarga_monto_moneda"><?= $Grid->renderFieldHeader($Grid->monto_moneda) ?></div></th>
<?php } ?>
<?php if ($Grid->moneda->Visible) { // moneda ?>
        <th data-name="moneda" class="<?= $Grid->moneda->headerCellClass() ?>"><div id="elh_recarga_moneda" class="recarga_moneda"><?= $Grid->renderFieldHeader($Grid->moneda) ?></div></th>
<?php } ?>
<?php if ($Grid->monto_bs->Visible) { // monto_bs ?>
        <th data-name="monto_bs" class="<?= $Grid->monto_bs->headerCellClass() ?>"><div id="elh_recarga_monto_bs" class="recarga_monto_bs"><?= $Grid->renderFieldHeader($Grid->monto_bs) ?></div></th>
<?php } ?>
<?php if ($Grid->saldo->Visible) { // saldo ?>
        <th data-name="saldo" class="<?= $Grid->saldo->headerCellClass() ?>"><div id="elh_recarga_saldo" class="recarga_saldo"><?= $Grid->renderFieldHeader($Grid->saldo) ?></div></th>
<?php } ?>
<?php if ($Grid->_username->Visible) { // username ?>
        <th data-name="_username" class="<?= $Grid->_username->headerCellClass() ?>"><div id="elh_recarga__username" class="recarga__username"><?= $Grid->renderFieldHeader($Grid->_username) ?></div></th>
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
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_cliente" class="el_recarga_cliente">
    <select
        id="x<?= $Grid->RowIndex ?>_cliente"
        name="x<?= $Grid->RowIndex ?>_cliente"
        class="form-control ew-select<?= $Grid->cliente->isInvalidClass() ?>"
        data-select2-id="frecargagrid_x<?= $Grid->RowIndex ?>_cliente"
        data-table="recarga"
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
loadjs.ready("frecargagrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_cliente", selectId: "frecargagrid_x<?= $Grid->RowIndex ?>_cliente" };
    if (frecargagrid.lists.cliente?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_cliente", form: "frecargagrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_cliente", form: "frecargagrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.recarga.fields.cliente.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<input type="hidden" data-table="recarga" data-field="x_cliente" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_cliente" id="o<?= $Grid->RowIndex ?>_cliente" value="<?= HtmlEncode($Grid->cliente->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_cliente" class="el_recarga_cliente">
    <select
        id="x<?= $Grid->RowIndex ?>_cliente"
        name="x<?= $Grid->RowIndex ?>_cliente"
        class="form-control ew-select<?= $Grid->cliente->isInvalidClass() ?>"
        data-select2-id="frecargagrid_x<?= $Grid->RowIndex ?>_cliente"
        data-table="recarga"
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
loadjs.ready("frecargagrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_cliente", selectId: "frecargagrid_x<?= $Grid->RowIndex ?>_cliente" };
    if (frecargagrid.lists.cliente?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_cliente", form: "frecargagrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_cliente", form: "frecargagrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.recarga.fields.cliente.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_cliente" class="el_recarga_cliente">
<span<?= $Grid->cliente->viewAttributes() ?>>
<?= $Grid->cliente->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="recarga" data-field="x_cliente" data-hidden="1" name="frecargagrid$x<?= $Grid->RowIndex ?>_cliente" id="frecargagrid$x<?= $Grid->RowIndex ?>_cliente" value="<?= HtmlEncode($Grid->cliente->FormValue) ?>">
<input type="hidden" data-table="recarga" data-field="x_cliente" data-hidden="1" data-old name="frecargagrid$o<?= $Grid->RowIndex ?>_cliente" id="frecargagrid$o<?= $Grid->RowIndex ?>_cliente" value="<?= HtmlEncode($Grid->cliente->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->fecha->Visible) { // fecha ?>
        <td data-name="fecha"<?= $Grid->fecha->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_fecha" class="el_recarga_fecha">
<input type="<?= $Grid->fecha->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_fecha" id="x<?= $Grid->RowIndex ?>_fecha" data-table="recarga" data-field="x_fecha" value="<?= $Grid->fecha->EditValue ?>" maxlength="10" placeholder="<?= HtmlEncode($Grid->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fecha->formatPattern()) ?>"<?= $Grid->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha->getErrorMessage() ?></div>
<?php if (!$Grid->fecha->ReadOnly && !$Grid->fecha->Disabled && !isset($Grid->fecha->EditAttrs["readonly"]) && !isset($Grid->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["frecargagrid", "datetimepicker"], function () {
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
    ew.createDateTimePicker("frecargagrid", "x<?= $Grid->RowIndex ?>_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="recarga" data-field="x_fecha" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_fecha" id="o<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_fecha" class="el_recarga_fecha">
<span<?= $Grid->fecha->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->fecha->getDisplayValue($Grid->fecha->EditValue))) ?>"></span>
<input type="hidden" data-table="recarga" data-field="x_fecha" data-hidden="1" name="x<?= $Grid->RowIndex ?>_fecha" id="x<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_fecha" class="el_recarga_fecha">
<span<?= $Grid->fecha->viewAttributes() ?>>
<?= $Grid->fecha->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="recarga" data-field="x_fecha" data-hidden="1" name="frecargagrid$x<?= $Grid->RowIndex ?>_fecha" id="frecargagrid$x<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->FormValue) ?>">
<input type="hidden" data-table="recarga" data-field="x_fecha" data-hidden="1" data-old name="frecargagrid$o<?= $Grid->RowIndex ?>_fecha" id="frecargagrid$o<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->metodo_pago->Visible) { // metodo_pago ?>
        <td data-name="metodo_pago"<?= $Grid->metodo_pago->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_metodo_pago" class="el_recarga_metodo_pago">
<input type="<?= $Grid->metodo_pago->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_metodo_pago" id="x<?= $Grid->RowIndex ?>_metodo_pago" data-table="recarga" data-field="x_metodo_pago" value="<?= $Grid->metodo_pago->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Grid->metodo_pago->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->metodo_pago->formatPattern()) ?>"<?= $Grid->metodo_pago->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->metodo_pago->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="recarga" data-field="x_metodo_pago" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_metodo_pago" id="o<?= $Grid->RowIndex ?>_metodo_pago" value="<?= HtmlEncode($Grid->metodo_pago->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_metodo_pago" class="el_recarga_metodo_pago">
<input type="<?= $Grid->metodo_pago->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_metodo_pago" id="x<?= $Grid->RowIndex ?>_metodo_pago" data-table="recarga" data-field="x_metodo_pago" value="<?= $Grid->metodo_pago->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Grid->metodo_pago->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->metodo_pago->formatPattern()) ?>"<?= $Grid->metodo_pago->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->metodo_pago->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_metodo_pago" class="el_recarga_metodo_pago">
<span<?= $Grid->metodo_pago->viewAttributes() ?>>
<?= $Grid->metodo_pago->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="recarga" data-field="x_metodo_pago" data-hidden="1" name="frecargagrid$x<?= $Grid->RowIndex ?>_metodo_pago" id="frecargagrid$x<?= $Grid->RowIndex ?>_metodo_pago" value="<?= HtmlEncode($Grid->metodo_pago->FormValue) ?>">
<input type="hidden" data-table="recarga" data-field="x_metodo_pago" data-hidden="1" data-old name="frecargagrid$o<?= $Grid->RowIndex ?>_metodo_pago" id="frecargagrid$o<?= $Grid->RowIndex ?>_metodo_pago" value="<?= HtmlEncode($Grid->metodo_pago->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->banco->Visible) { // banco ?>
        <td data-name="banco"<?= $Grid->banco->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_banco" class="el_recarga_banco">
<?php
if (IsRTL()) {
    $Grid->banco->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x<?= $Grid->RowIndex ?>_banco" class="ew-auto-suggest">
    <input type="<?= $Grid->banco->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_banco" id="sv_x<?= $Grid->RowIndex ?>_banco" value="<?= RemoveHtml($Grid->banco->EditValue) ?>" autocomplete="off" size="30" placeholder="<?= HtmlEncode($Grid->banco->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->banco->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->banco->formatPattern()) ?>"<?= $Grid->banco->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="recarga" data-field="x_banco" data-input="sv_x<?= $Grid->RowIndex ?>_banco" data-value-separator="<?= $Grid->banco->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_banco" id="x<?= $Grid->RowIndex ?>_banco" value="<?= HtmlEncode($Grid->banco->CurrentValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Grid->banco->getErrorMessage() ?></div>
<script>
loadjs.ready("frecargagrid", function() {
    frecargagrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_banco","forceSelect":false}, { lookupAllDisplayFields: <?= $Grid->banco->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.recarga.fields.banco.autoSuggestOptions));
});
</script>
<?= $Grid->banco->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_banco") ?>
</span>
<input type="hidden" data-table="recarga" data-field="x_banco" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_banco" id="o<?= $Grid->RowIndex ?>_banco" value="<?= HtmlEncode($Grid->banco->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_banco" class="el_recarga_banco">
<?php
if (IsRTL()) {
    $Grid->banco->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x<?= $Grid->RowIndex ?>_banco" class="ew-auto-suggest">
    <input type="<?= $Grid->banco->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_banco" id="sv_x<?= $Grid->RowIndex ?>_banco" value="<?= RemoveHtml($Grid->banco->EditValue) ?>" autocomplete="off" size="30" placeholder="<?= HtmlEncode($Grid->banco->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->banco->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->banco->formatPattern()) ?>"<?= $Grid->banco->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="recarga" data-field="x_banco" data-input="sv_x<?= $Grid->RowIndex ?>_banco" data-value-separator="<?= $Grid->banco->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_banco" id="x<?= $Grid->RowIndex ?>_banco" value="<?= HtmlEncode($Grid->banco->CurrentValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Grid->banco->getErrorMessage() ?></div>
<script>
loadjs.ready("frecargagrid", function() {
    frecargagrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_banco","forceSelect":false}, { lookupAllDisplayFields: <?= $Grid->banco->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.recarga.fields.banco.autoSuggestOptions));
});
</script>
<?= $Grid->banco->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_banco") ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_banco" class="el_recarga_banco">
<span<?= $Grid->banco->viewAttributes() ?>>
<?= $Grid->banco->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="recarga" data-field="x_banco" data-hidden="1" name="frecargagrid$x<?= $Grid->RowIndex ?>_banco" id="frecargagrid$x<?= $Grid->RowIndex ?>_banco" value="<?= HtmlEncode($Grid->banco->FormValue) ?>">
<input type="hidden" data-table="recarga" data-field="x_banco" data-hidden="1" data-old name="frecargagrid$o<?= $Grid->RowIndex ?>_banco" id="frecargagrid$o<?= $Grid->RowIndex ?>_banco" value="<?= HtmlEncode($Grid->banco->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->referencia->Visible) { // referencia ?>
        <td data-name="referencia"<?= $Grid->referencia->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_referencia" class="el_recarga_referencia">
<input type="<?= $Grid->referencia->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_referencia" id="x<?= $Grid->RowIndex ?>_referencia" data-table="recarga" data-field="x_referencia" value="<?= $Grid->referencia->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Grid->referencia->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->referencia->formatPattern()) ?>"<?= $Grid->referencia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->referencia->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="recarga" data-field="x_referencia" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_referencia" id="o<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_referencia" class="el_recarga_referencia">
<input type="<?= $Grid->referencia->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_referencia" id="x<?= $Grid->RowIndex ?>_referencia" data-table="recarga" data-field="x_referencia" value="<?= $Grid->referencia->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Grid->referencia->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->referencia->formatPattern()) ?>"<?= $Grid->referencia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->referencia->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_referencia" class="el_recarga_referencia">
<span<?= $Grid->referencia->viewAttributes() ?>>
<?= $Grid->referencia->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="recarga" data-field="x_referencia" data-hidden="1" name="frecargagrid$x<?= $Grid->RowIndex ?>_referencia" id="frecargagrid$x<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->FormValue) ?>">
<input type="hidden" data-table="recarga" data-field="x_referencia" data-hidden="1" data-old name="frecargagrid$o<?= $Grid->RowIndex ?>_referencia" id="frecargagrid$o<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->monto_moneda->Visible) { // monto_moneda ?>
        <td data-name="monto_moneda"<?= $Grid->monto_moneda->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_monto_moneda" class="el_recarga_monto_moneda">
<input type="<?= $Grid->monto_moneda->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_monto_moneda" id="x<?= $Grid->RowIndex ?>_monto_moneda" data-table="recarga" data-field="x_monto_moneda" value="<?= $Grid->monto_moneda->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->monto_moneda->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->monto_moneda->formatPattern()) ?>"<?= $Grid->monto_moneda->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_moneda->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="recarga" data-field="x_monto_moneda" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_monto_moneda" id="o<?= $Grid->RowIndex ?>_monto_moneda" value="<?= HtmlEncode($Grid->monto_moneda->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_monto_moneda" class="el_recarga_monto_moneda">
<input type="<?= $Grid->monto_moneda->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_monto_moneda" id="x<?= $Grid->RowIndex ?>_monto_moneda" data-table="recarga" data-field="x_monto_moneda" value="<?= $Grid->monto_moneda->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->monto_moneda->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->monto_moneda->formatPattern()) ?>"<?= $Grid->monto_moneda->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_moneda->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_monto_moneda" class="el_recarga_monto_moneda">
<span<?= $Grid->monto_moneda->viewAttributes() ?>>
<?= $Grid->monto_moneda->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="recarga" data-field="x_monto_moneda" data-hidden="1" name="frecargagrid$x<?= $Grid->RowIndex ?>_monto_moneda" id="frecargagrid$x<?= $Grid->RowIndex ?>_monto_moneda" value="<?= HtmlEncode($Grid->monto_moneda->FormValue) ?>">
<input type="hidden" data-table="recarga" data-field="x_monto_moneda" data-hidden="1" data-old name="frecargagrid$o<?= $Grid->RowIndex ?>_monto_moneda" id="frecargagrid$o<?= $Grid->RowIndex ?>_monto_moneda" value="<?= HtmlEncode($Grid->monto_moneda->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->moneda->Visible) { // moneda ?>
        <td data-name="moneda"<?= $Grid->moneda->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_moneda" class="el_recarga_moneda">
    <select
        id="x<?= $Grid->RowIndex ?>_moneda"
        name="x<?= $Grid->RowIndex ?>_moneda"
        class="form-select ew-select<?= $Grid->moneda->isInvalidClass() ?>"
        <?php if (!$Grid->moneda->IsNativeSelect) { ?>
        data-select2-id="frecargagrid_x<?= $Grid->RowIndex ?>_moneda"
        <?php } ?>
        data-table="recarga"
        data-field="x_moneda"
        data-value-separator="<?= $Grid->moneda->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->moneda->getPlaceHolder()) ?>"
        <?= $Grid->moneda->editAttributes() ?>>
        <?= $Grid->moneda->selectOptionListHtml("x{$Grid->RowIndex}_moneda") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->moneda->getErrorMessage() ?></div>
<?= $Grid->moneda->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_moneda") ?>
<?php if (!$Grid->moneda->IsNativeSelect) { ?>
<script>
loadjs.ready("frecargagrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_moneda", selectId: "frecargagrid_x<?= $Grid->RowIndex ?>_moneda" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (frecargagrid.lists.moneda?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_moneda", form: "frecargagrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_moneda", form: "frecargagrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.recarga.fields.moneda.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="recarga" data-field="x_moneda" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_moneda" id="o<?= $Grid->RowIndex ?>_moneda" value="<?= HtmlEncode($Grid->moneda->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_moneda" class="el_recarga_moneda">
    <select
        id="x<?= $Grid->RowIndex ?>_moneda"
        name="x<?= $Grid->RowIndex ?>_moneda"
        class="form-select ew-select<?= $Grid->moneda->isInvalidClass() ?>"
        <?php if (!$Grid->moneda->IsNativeSelect) { ?>
        data-select2-id="frecargagrid_x<?= $Grid->RowIndex ?>_moneda"
        <?php } ?>
        data-table="recarga"
        data-field="x_moneda"
        data-value-separator="<?= $Grid->moneda->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->moneda->getPlaceHolder()) ?>"
        <?= $Grid->moneda->editAttributes() ?>>
        <?= $Grid->moneda->selectOptionListHtml("x{$Grid->RowIndex}_moneda") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->moneda->getErrorMessage() ?></div>
<?= $Grid->moneda->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_moneda") ?>
<?php if (!$Grid->moneda->IsNativeSelect) { ?>
<script>
loadjs.ready("frecargagrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_moneda", selectId: "frecargagrid_x<?= $Grid->RowIndex ?>_moneda" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (frecargagrid.lists.moneda?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_moneda", form: "frecargagrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_moneda", form: "frecargagrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.recarga.fields.moneda.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_moneda" class="el_recarga_moneda">
<span<?= $Grid->moneda->viewAttributes() ?>>
<?= $Grid->moneda->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="recarga" data-field="x_moneda" data-hidden="1" name="frecargagrid$x<?= $Grid->RowIndex ?>_moneda" id="frecargagrid$x<?= $Grid->RowIndex ?>_moneda" value="<?= HtmlEncode($Grid->moneda->FormValue) ?>">
<input type="hidden" data-table="recarga" data-field="x_moneda" data-hidden="1" data-old name="frecargagrid$o<?= $Grid->RowIndex ?>_moneda" id="frecargagrid$o<?= $Grid->RowIndex ?>_moneda" value="<?= HtmlEncode($Grid->moneda->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->monto_bs->Visible) { // monto_bs ?>
        <td data-name="monto_bs"<?= $Grid->monto_bs->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_monto_bs" class="el_recarga_monto_bs">
<input type="<?= $Grid->monto_bs->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_monto_bs" id="x<?= $Grid->RowIndex ?>_monto_bs" data-table="recarga" data-field="x_monto_bs" value="<?= $Grid->monto_bs->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->monto_bs->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->monto_bs->formatPattern()) ?>"<?= $Grid->monto_bs->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_bs->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="recarga" data-field="x_monto_bs" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_monto_bs" id="o<?= $Grid->RowIndex ?>_monto_bs" value="<?= HtmlEncode($Grid->monto_bs->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_monto_bs" class="el_recarga_monto_bs">
<input type="<?= $Grid->monto_bs->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_monto_bs" id="x<?= $Grid->RowIndex ?>_monto_bs" data-table="recarga" data-field="x_monto_bs" value="<?= $Grid->monto_bs->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->monto_bs->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->monto_bs->formatPattern()) ?>"<?= $Grid->monto_bs->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_bs->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_monto_bs" class="el_recarga_monto_bs">
<span<?= $Grid->monto_bs->viewAttributes() ?>>
<?= $Grid->monto_bs->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="recarga" data-field="x_monto_bs" data-hidden="1" name="frecargagrid$x<?= $Grid->RowIndex ?>_monto_bs" id="frecargagrid$x<?= $Grid->RowIndex ?>_monto_bs" value="<?= HtmlEncode($Grid->monto_bs->FormValue) ?>">
<input type="hidden" data-table="recarga" data-field="x_monto_bs" data-hidden="1" data-old name="frecargagrid$o<?= $Grid->RowIndex ?>_monto_bs" id="frecargagrid$o<?= $Grid->RowIndex ?>_monto_bs" value="<?= HtmlEncode($Grid->monto_bs->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->saldo->Visible) { // saldo ?>
        <td data-name="saldo"<?= $Grid->saldo->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_saldo" class="el_recarga_saldo">
<input type="<?= $Grid->saldo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_saldo" id="x<?= $Grid->RowIndex ?>_saldo" data-table="recarga" data-field="x_saldo" value="<?= $Grid->saldo->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->saldo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->saldo->formatPattern()) ?>"<?= $Grid->saldo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->saldo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="recarga" data-field="x_saldo" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_saldo" id="o<?= $Grid->RowIndex ?>_saldo" value="<?= HtmlEncode($Grid->saldo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_saldo" class="el_recarga_saldo">
<input type="<?= $Grid->saldo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_saldo" id="x<?= $Grid->RowIndex ?>_saldo" data-table="recarga" data-field="x_saldo" value="<?= $Grid->saldo->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->saldo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->saldo->formatPattern()) ?>"<?= $Grid->saldo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->saldo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga_saldo" class="el_recarga_saldo">
<span<?= $Grid->saldo->viewAttributes() ?>>
<?= $Grid->saldo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="recarga" data-field="x_saldo" data-hidden="1" name="frecargagrid$x<?= $Grid->RowIndex ?>_saldo" id="frecargagrid$x<?= $Grid->RowIndex ?>_saldo" value="<?= HtmlEncode($Grid->saldo->FormValue) ?>">
<input type="hidden" data-table="recarga" data-field="x_saldo" data-hidden="1" data-old name="frecargagrid$o<?= $Grid->RowIndex ?>_saldo" id="frecargagrid$o<?= $Grid->RowIndex ?>_saldo" value="<?= HtmlEncode($Grid->saldo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->_username->Visible) { // username ?>
        <td data-name="_username"<?= $Grid->_username->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga__username" class="el_recarga__username">
<?php
if (IsRTL()) {
    $Grid->_username->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x<?= $Grid->RowIndex ?>__username" class="ew-auto-suggest">
    <input type="<?= $Grid->_username->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>__username" id="sv_x<?= $Grid->RowIndex ?>__username" value="<?= RemoveHtml($Grid->_username->EditValue) ?>" autocomplete="off" size="30" maxlength="30" placeholder="<?= HtmlEncode($Grid->_username->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->_username->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->_username->formatPattern()) ?>"<?= $Grid->_username->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="recarga" data-field="x__username" data-input="sv_x<?= $Grid->RowIndex ?>__username" data-value-separator="<?= $Grid->_username->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>__username" id="x<?= $Grid->RowIndex ?>__username" value="<?= HtmlEncode($Grid->_username->CurrentValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Grid->_username->getErrorMessage() ?></div>
<script>
loadjs.ready("frecargagrid", function() {
    frecargagrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>__username","forceSelect":false}, { lookupAllDisplayFields: <?= $Grid->_username->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.recarga.fields._username.autoSuggestOptions));
});
</script>
<?= $Grid->_username->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "__username") ?>
</span>
<input type="hidden" data-table="recarga" data-field="x__username" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>__username" id="o<?= $Grid->RowIndex ?>__username" value="<?= HtmlEncode($Grid->_username->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga__username" class="el_recarga__username">
<?php
if (IsRTL()) {
    $Grid->_username->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x<?= $Grid->RowIndex ?>__username" class="ew-auto-suggest">
    <input type="<?= $Grid->_username->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>__username" id="sv_x<?= $Grid->RowIndex ?>__username" value="<?= RemoveHtml($Grid->_username->EditValue) ?>" autocomplete="off" size="30" maxlength="30" placeholder="<?= HtmlEncode($Grid->_username->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->_username->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->_username->formatPattern()) ?>"<?= $Grid->_username->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="recarga" data-field="x__username" data-input="sv_x<?= $Grid->RowIndex ?>__username" data-value-separator="<?= $Grid->_username->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>__username" id="x<?= $Grid->RowIndex ?>__username" value="<?= HtmlEncode($Grid->_username->CurrentValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Grid->_username->getErrorMessage() ?></div>
<script>
loadjs.ready("frecargagrid", function() {
    frecargagrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>__username","forceSelect":false}, { lookupAllDisplayFields: <?= $Grid->_username->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.recarga.fields._username.autoSuggestOptions));
});
</script>
<?= $Grid->_username->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "__username") ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_recarga__username" class="el_recarga__username">
<span<?= $Grid->_username->viewAttributes() ?>>
<?= $Grid->_username->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="recarga" data-field="x__username" data-hidden="1" name="frecargagrid$x<?= $Grid->RowIndex ?>__username" id="frecargagrid$x<?= $Grid->RowIndex ?>__username" value="<?= HtmlEncode($Grid->_username->FormValue) ?>">
<input type="hidden" data-table="recarga" data-field="x__username" data-hidden="1" data-old name="frecargagrid$o<?= $Grid->RowIndex ?>__username" id="frecargagrid$o<?= $Grid->RowIndex ?>__username" value="<?= HtmlEncode($Grid->_username->OldValue) ?>">
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
loadjs.ready(["frecargagrid","load"], () => frecargagrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="frecargagrid">
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
    ew.addEventHandlers("recarga");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
