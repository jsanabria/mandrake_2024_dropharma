<?php

namespace PHPMaker2024\mandrake;

// Set up and run Grid object
$Grid = Container("CompaniaCuentaGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fcompania_cuentagrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { compania_cuenta: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcompania_cuentagrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["banco", [fields.banco.visible && fields.banco.required ? ew.Validators.required(fields.banco.caption) : null], fields.banco.isInvalid],
            ["titular", [fields.titular.visible && fields.titular.required ? ew.Validators.required(fields.titular.caption) : null], fields.titular.isInvalid],
            ["tipo", [fields.tipo.visible && fields.tipo.required ? ew.Validators.required(fields.tipo.caption) : null], fields.tipo.isInvalid],
            ["numero", [fields.numero.visible && fields.numero.required ? ew.Validators.required(fields.numero.caption) : null], fields.numero.isInvalid],
            ["mostrar", [fields.mostrar.visible && fields.mostrar.required ? ew.Validators.required(fields.mostrar.caption) : null], fields.mostrar.isInvalid],
            ["cuenta", [fields.cuenta.visible && fields.cuenta.required ? ew.Validators.required(fields.cuenta.caption) : null], fields.cuenta.isInvalid],
            ["activo", [fields.activo.visible && fields.activo.required ? ew.Validators.required(fields.activo.caption) : null], fields.activo.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["banco",false],["titular",false],["tipo",false],["numero",false],["mostrar",false],["cuenta",false],["activo",false]];
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
            "banco": <?= $Grid->banco->toClientList($Grid) ?>,
            "tipo": <?= $Grid->tipo->toClientList($Grid) ?>,
            "mostrar": <?= $Grid->mostrar->toClientList($Grid) ?>,
            "cuenta": <?= $Grid->cuenta->toClientList($Grid) ?>,
            "activo": <?= $Grid->activo->toClientList($Grid) ?>,
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
<div id="fcompania_cuentagrid" class="ew-form ew-list-form">
<div id="gmp_compania_cuenta" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_compania_cuentagrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Grid->banco->Visible) { // banco ?>
        <th data-name="banco" class="<?= $Grid->banco->headerCellClass() ?>"><div id="elh_compania_cuenta_banco" class="compania_cuenta_banco"><?= $Grid->renderFieldHeader($Grid->banco) ?></div></th>
<?php } ?>
<?php if ($Grid->titular->Visible) { // titular ?>
        <th data-name="titular" class="<?= $Grid->titular->headerCellClass() ?>"><div id="elh_compania_cuenta_titular" class="compania_cuenta_titular"><?= $Grid->renderFieldHeader($Grid->titular) ?></div></th>
<?php } ?>
<?php if ($Grid->tipo->Visible) { // tipo ?>
        <th data-name="tipo" class="<?= $Grid->tipo->headerCellClass() ?>"><div id="elh_compania_cuenta_tipo" class="compania_cuenta_tipo"><?= $Grid->renderFieldHeader($Grid->tipo) ?></div></th>
<?php } ?>
<?php if ($Grid->numero->Visible) { // numero ?>
        <th data-name="numero" class="<?= $Grid->numero->headerCellClass() ?>"><div id="elh_compania_cuenta_numero" class="compania_cuenta_numero"><?= $Grid->renderFieldHeader($Grid->numero) ?></div></th>
<?php } ?>
<?php if ($Grid->mostrar->Visible) { // mostrar ?>
        <th data-name="mostrar" class="<?= $Grid->mostrar->headerCellClass() ?>"><div id="elh_compania_cuenta_mostrar" class="compania_cuenta_mostrar"><?= $Grid->renderFieldHeader($Grid->mostrar) ?></div></th>
<?php } ?>
<?php if ($Grid->cuenta->Visible) { // cuenta ?>
        <th data-name="cuenta" class="<?= $Grid->cuenta->headerCellClass() ?>"><div id="elh_compania_cuenta_cuenta" class="compania_cuenta_cuenta"><?= $Grid->renderFieldHeader($Grid->cuenta) ?></div></th>
<?php } ?>
<?php if ($Grid->activo->Visible) { // activo ?>
        <th data-name="activo" class="<?= $Grid->activo->headerCellClass() ?>"><div id="elh_compania_cuenta_activo" class="compania_cuenta_activo"><?= $Grid->renderFieldHeader($Grid->activo) ?></div></th>
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
    <?php if ($Grid->banco->Visible) { // banco ?>
        <td data-name="banco"<?= $Grid->banco->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_compania_cuenta_banco" class="el_compania_cuenta_banco">
    <select
        id="x<?= $Grid->RowIndex ?>_banco"
        name="x<?= $Grid->RowIndex ?>_banco"
        class="form-control ew-select<?= $Grid->banco->isInvalidClass() ?>"
        data-select2-id="fcompania_cuentagrid_x<?= $Grid->RowIndex ?>_banco"
        data-table="compania_cuenta"
        data-field="x_banco"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->banco->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->banco->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->banco->getPlaceHolder()) ?>"
        <?= $Grid->banco->editAttributes() ?>>
        <?= $Grid->banco->selectOptionListHtml("x{$Grid->RowIndex}_banco") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->banco->getErrorMessage() ?></div>
<?= $Grid->banco->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_banco") ?>
<script>
loadjs.ready("fcompania_cuentagrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_banco", selectId: "fcompania_cuentagrid_x<?= $Grid->RowIndex ?>_banco" };
    if (fcompania_cuentagrid.lists.banco?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_banco", form: "fcompania_cuentagrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_banco", form: "fcompania_cuentagrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.compania_cuenta.fields.banco.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<input type="hidden" data-table="compania_cuenta" data-field="x_banco" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_banco" id="o<?= $Grid->RowIndex ?>_banco" value="<?= HtmlEncode($Grid->banco->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_compania_cuenta_banco" class="el_compania_cuenta_banco">
    <select
        id="x<?= $Grid->RowIndex ?>_banco"
        name="x<?= $Grid->RowIndex ?>_banco"
        class="form-control ew-select<?= $Grid->banco->isInvalidClass() ?>"
        data-select2-id="fcompania_cuentagrid_x<?= $Grid->RowIndex ?>_banco"
        data-table="compania_cuenta"
        data-field="x_banco"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->banco->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->banco->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->banco->getPlaceHolder()) ?>"
        <?= $Grid->banco->editAttributes() ?>>
        <?= $Grid->banco->selectOptionListHtml("x{$Grid->RowIndex}_banco") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->banco->getErrorMessage() ?></div>
<?= $Grid->banco->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_banco") ?>
<script>
loadjs.ready("fcompania_cuentagrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_banco", selectId: "fcompania_cuentagrid_x<?= $Grid->RowIndex ?>_banco" };
    if (fcompania_cuentagrid.lists.banco?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_banco", form: "fcompania_cuentagrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_banco", form: "fcompania_cuentagrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.compania_cuenta.fields.banco.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_compania_cuenta_banco" class="el_compania_cuenta_banco">
<span<?= $Grid->banco->viewAttributes() ?>>
<?= $Grid->banco->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="compania_cuenta" data-field="x_banco" data-hidden="1" name="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_banco" id="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_banco" value="<?= HtmlEncode($Grid->banco->FormValue) ?>">
<input type="hidden" data-table="compania_cuenta" data-field="x_banco" data-hidden="1" data-old name="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_banco" id="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_banco" value="<?= HtmlEncode($Grid->banco->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->titular->Visible) { // titular ?>
        <td data-name="titular"<?= $Grid->titular->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_compania_cuenta_titular" class="el_compania_cuenta_titular">
<input type="<?= $Grid->titular->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_titular" id="x<?= $Grid->RowIndex ?>_titular" data-table="compania_cuenta" data-field="x_titular" value="<?= $Grid->titular->EditValue ?>" size="30" maxlength="80" placeholder="<?= HtmlEncode($Grid->titular->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->titular->formatPattern()) ?>"<?= $Grid->titular->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->titular->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="compania_cuenta" data-field="x_titular" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_titular" id="o<?= $Grid->RowIndex ?>_titular" value="<?= HtmlEncode($Grid->titular->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_compania_cuenta_titular" class="el_compania_cuenta_titular">
<input type="<?= $Grid->titular->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_titular" id="x<?= $Grid->RowIndex ?>_titular" data-table="compania_cuenta" data-field="x_titular" value="<?= $Grid->titular->EditValue ?>" size="30" maxlength="80" placeholder="<?= HtmlEncode($Grid->titular->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->titular->formatPattern()) ?>"<?= $Grid->titular->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->titular->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_compania_cuenta_titular" class="el_compania_cuenta_titular">
<span<?= $Grid->titular->viewAttributes() ?>>
<?= $Grid->titular->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="compania_cuenta" data-field="x_titular" data-hidden="1" name="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_titular" id="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_titular" value="<?= HtmlEncode($Grid->titular->FormValue) ?>">
<input type="hidden" data-table="compania_cuenta" data-field="x_titular" data-hidden="1" data-old name="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_titular" id="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_titular" value="<?= HtmlEncode($Grid->titular->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->tipo->Visible) { // tipo ?>
        <td data-name="tipo"<?= $Grid->tipo->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_compania_cuenta_tipo" class="el_compania_cuenta_tipo">
    <select
        id="x<?= $Grid->RowIndex ?>_tipo"
        name="x<?= $Grid->RowIndex ?>_tipo"
        class="form-select ew-select<?= $Grid->tipo->isInvalidClass() ?>"
        <?php if (!$Grid->tipo->IsNativeSelect) { ?>
        data-select2-id="fcompania_cuentagrid_x<?= $Grid->RowIndex ?>_tipo"
        <?php } ?>
        data-table="compania_cuenta"
        data-field="x_tipo"
        data-value-separator="<?= $Grid->tipo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->tipo->getPlaceHolder()) ?>"
        <?= $Grid->tipo->editAttributes() ?>>
        <?= $Grid->tipo->selectOptionListHtml("x{$Grid->RowIndex}_tipo") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->tipo->getErrorMessage() ?></div>
<?php if (!$Grid->tipo->IsNativeSelect) { ?>
<script>
loadjs.ready("fcompania_cuentagrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_tipo", selectId: "fcompania_cuentagrid_x<?= $Grid->RowIndex ?>_tipo" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fcompania_cuentagrid.lists.tipo?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_tipo", form: "fcompania_cuentagrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_tipo", form: "fcompania_cuentagrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.compania_cuenta.fields.tipo.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="compania_cuenta" data-field="x_tipo" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_tipo" id="o<?= $Grid->RowIndex ?>_tipo" value="<?= HtmlEncode($Grid->tipo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_compania_cuenta_tipo" class="el_compania_cuenta_tipo">
    <select
        id="x<?= $Grid->RowIndex ?>_tipo"
        name="x<?= $Grid->RowIndex ?>_tipo"
        class="form-select ew-select<?= $Grid->tipo->isInvalidClass() ?>"
        <?php if (!$Grid->tipo->IsNativeSelect) { ?>
        data-select2-id="fcompania_cuentagrid_x<?= $Grid->RowIndex ?>_tipo"
        <?php } ?>
        data-table="compania_cuenta"
        data-field="x_tipo"
        data-value-separator="<?= $Grid->tipo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->tipo->getPlaceHolder()) ?>"
        <?= $Grid->tipo->editAttributes() ?>>
        <?= $Grid->tipo->selectOptionListHtml("x{$Grid->RowIndex}_tipo") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->tipo->getErrorMessage() ?></div>
<?php if (!$Grid->tipo->IsNativeSelect) { ?>
<script>
loadjs.ready("fcompania_cuentagrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_tipo", selectId: "fcompania_cuentagrid_x<?= $Grid->RowIndex ?>_tipo" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fcompania_cuentagrid.lists.tipo?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_tipo", form: "fcompania_cuentagrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_tipo", form: "fcompania_cuentagrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.compania_cuenta.fields.tipo.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_compania_cuenta_tipo" class="el_compania_cuenta_tipo">
<span<?= $Grid->tipo->viewAttributes() ?>>
<?= $Grid->tipo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="compania_cuenta" data-field="x_tipo" data-hidden="1" name="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_tipo" id="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_tipo" value="<?= HtmlEncode($Grid->tipo->FormValue) ?>">
<input type="hidden" data-table="compania_cuenta" data-field="x_tipo" data-hidden="1" data-old name="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_tipo" id="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_tipo" value="<?= HtmlEncode($Grid->tipo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->numero->Visible) { // numero ?>
        <td data-name="numero"<?= $Grid->numero->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_compania_cuenta_numero" class="el_compania_cuenta_numero">
<input type="<?= $Grid->numero->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_numero" id="x<?= $Grid->RowIndex ?>_numero" data-table="compania_cuenta" data-field="x_numero" value="<?= $Grid->numero->EditValue ?>" size="30" maxlength="40" placeholder="<?= HtmlEncode($Grid->numero->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->numero->formatPattern()) ?>"<?= $Grid->numero->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->numero->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="compania_cuenta" data-field="x_numero" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_numero" id="o<?= $Grid->RowIndex ?>_numero" value="<?= HtmlEncode($Grid->numero->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_compania_cuenta_numero" class="el_compania_cuenta_numero">
<input type="<?= $Grid->numero->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_numero" id="x<?= $Grid->RowIndex ?>_numero" data-table="compania_cuenta" data-field="x_numero" value="<?= $Grid->numero->EditValue ?>" size="30" maxlength="40" placeholder="<?= HtmlEncode($Grid->numero->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->numero->formatPattern()) ?>"<?= $Grid->numero->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->numero->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_compania_cuenta_numero" class="el_compania_cuenta_numero">
<span<?= $Grid->numero->viewAttributes() ?>>
<?= $Grid->numero->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="compania_cuenta" data-field="x_numero" data-hidden="1" name="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_numero" id="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_numero" value="<?= HtmlEncode($Grid->numero->FormValue) ?>">
<input type="hidden" data-table="compania_cuenta" data-field="x_numero" data-hidden="1" data-old name="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_numero" id="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_numero" value="<?= HtmlEncode($Grid->numero->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->mostrar->Visible) { // mostrar ?>
        <td data-name="mostrar"<?= $Grid->mostrar->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_compania_cuenta_mostrar" class="el_compania_cuenta_mostrar">
    <select
        id="x<?= $Grid->RowIndex ?>_mostrar"
        name="x<?= $Grid->RowIndex ?>_mostrar"
        class="form-select ew-select<?= $Grid->mostrar->isInvalidClass() ?>"
        <?php if (!$Grid->mostrar->IsNativeSelect) { ?>
        data-select2-id="fcompania_cuentagrid_x<?= $Grid->RowIndex ?>_mostrar"
        <?php } ?>
        data-table="compania_cuenta"
        data-field="x_mostrar"
        data-value-separator="<?= $Grid->mostrar->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->mostrar->getPlaceHolder()) ?>"
        <?= $Grid->mostrar->editAttributes() ?>>
        <?= $Grid->mostrar->selectOptionListHtml("x{$Grid->RowIndex}_mostrar") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->mostrar->getErrorMessage() ?></div>
<?php if (!$Grid->mostrar->IsNativeSelect) { ?>
<script>
loadjs.ready("fcompania_cuentagrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_mostrar", selectId: "fcompania_cuentagrid_x<?= $Grid->RowIndex ?>_mostrar" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fcompania_cuentagrid.lists.mostrar?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_mostrar", form: "fcompania_cuentagrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_mostrar", form: "fcompania_cuentagrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.compania_cuenta.fields.mostrar.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="compania_cuenta" data-field="x_mostrar" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_mostrar" id="o<?= $Grid->RowIndex ?>_mostrar" value="<?= HtmlEncode($Grid->mostrar->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_compania_cuenta_mostrar" class="el_compania_cuenta_mostrar">
    <select
        id="x<?= $Grid->RowIndex ?>_mostrar"
        name="x<?= $Grid->RowIndex ?>_mostrar"
        class="form-select ew-select<?= $Grid->mostrar->isInvalidClass() ?>"
        <?php if (!$Grid->mostrar->IsNativeSelect) { ?>
        data-select2-id="fcompania_cuentagrid_x<?= $Grid->RowIndex ?>_mostrar"
        <?php } ?>
        data-table="compania_cuenta"
        data-field="x_mostrar"
        data-value-separator="<?= $Grid->mostrar->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->mostrar->getPlaceHolder()) ?>"
        <?= $Grid->mostrar->editAttributes() ?>>
        <?= $Grid->mostrar->selectOptionListHtml("x{$Grid->RowIndex}_mostrar") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->mostrar->getErrorMessage() ?></div>
<?php if (!$Grid->mostrar->IsNativeSelect) { ?>
<script>
loadjs.ready("fcompania_cuentagrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_mostrar", selectId: "fcompania_cuentagrid_x<?= $Grid->RowIndex ?>_mostrar" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fcompania_cuentagrid.lists.mostrar?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_mostrar", form: "fcompania_cuentagrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_mostrar", form: "fcompania_cuentagrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.compania_cuenta.fields.mostrar.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_compania_cuenta_mostrar" class="el_compania_cuenta_mostrar">
<span<?= $Grid->mostrar->viewAttributes() ?>>
<?= $Grid->mostrar->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="compania_cuenta" data-field="x_mostrar" data-hidden="1" name="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_mostrar" id="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_mostrar" value="<?= HtmlEncode($Grid->mostrar->FormValue) ?>">
<input type="hidden" data-table="compania_cuenta" data-field="x_mostrar" data-hidden="1" data-old name="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_mostrar" id="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_mostrar" value="<?= HtmlEncode($Grid->mostrar->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->cuenta->Visible) { // cuenta ?>
        <td data-name="cuenta"<?= $Grid->cuenta->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_compania_cuenta_cuenta" class="el_compania_cuenta_cuenta">
    <select
        id="x<?= $Grid->RowIndex ?>_cuenta"
        name="x<?= $Grid->RowIndex ?>_cuenta"
        class="form-control ew-select<?= $Grid->cuenta->isInvalidClass() ?>"
        data-select2-id="fcompania_cuentagrid_x<?= $Grid->RowIndex ?>_cuenta"
        data-table="compania_cuenta"
        data-field="x_cuenta"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->cuenta->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->cuenta->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->cuenta->getPlaceHolder()) ?>"
        <?= $Grid->cuenta->editAttributes() ?>>
        <?= $Grid->cuenta->selectOptionListHtml("x{$Grid->RowIndex}_cuenta") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->cuenta->getErrorMessage() ?></div>
<?= $Grid->cuenta->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_cuenta") ?>
<script>
loadjs.ready("fcompania_cuentagrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_cuenta", selectId: "fcompania_cuentagrid_x<?= $Grid->RowIndex ?>_cuenta" };
    if (fcompania_cuentagrid.lists.cuenta?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_cuenta", form: "fcompania_cuentagrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_cuenta", form: "fcompania_cuentagrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.compania_cuenta.fields.cuenta.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<input type="hidden" data-table="compania_cuenta" data-field="x_cuenta" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_cuenta" id="o<?= $Grid->RowIndex ?>_cuenta" value="<?= HtmlEncode($Grid->cuenta->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_compania_cuenta_cuenta" class="el_compania_cuenta_cuenta">
    <select
        id="x<?= $Grid->RowIndex ?>_cuenta"
        name="x<?= $Grid->RowIndex ?>_cuenta"
        class="form-control ew-select<?= $Grid->cuenta->isInvalidClass() ?>"
        data-select2-id="fcompania_cuentagrid_x<?= $Grid->RowIndex ?>_cuenta"
        data-table="compania_cuenta"
        data-field="x_cuenta"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->cuenta->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->cuenta->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->cuenta->getPlaceHolder()) ?>"
        <?= $Grid->cuenta->editAttributes() ?>>
        <?= $Grid->cuenta->selectOptionListHtml("x{$Grid->RowIndex}_cuenta") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->cuenta->getErrorMessage() ?></div>
<?= $Grid->cuenta->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_cuenta") ?>
<script>
loadjs.ready("fcompania_cuentagrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_cuenta", selectId: "fcompania_cuentagrid_x<?= $Grid->RowIndex ?>_cuenta" };
    if (fcompania_cuentagrid.lists.cuenta?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_cuenta", form: "fcompania_cuentagrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_cuenta", form: "fcompania_cuentagrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.compania_cuenta.fields.cuenta.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_compania_cuenta_cuenta" class="el_compania_cuenta_cuenta">
<span<?= $Grid->cuenta->viewAttributes() ?>>
<?= $Grid->cuenta->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="compania_cuenta" data-field="x_cuenta" data-hidden="1" name="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_cuenta" id="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_cuenta" value="<?= HtmlEncode($Grid->cuenta->FormValue) ?>">
<input type="hidden" data-table="compania_cuenta" data-field="x_cuenta" data-hidden="1" data-old name="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_cuenta" id="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_cuenta" value="<?= HtmlEncode($Grid->cuenta->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->activo->Visible) { // activo ?>
        <td data-name="activo"<?= $Grid->activo->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_compania_cuenta_activo" class="el_compania_cuenta_activo">
    <select
        id="x<?= $Grid->RowIndex ?>_activo"
        name="x<?= $Grid->RowIndex ?>_activo"
        class="form-select ew-select<?= $Grid->activo->isInvalidClass() ?>"
        <?php if (!$Grid->activo->IsNativeSelect) { ?>
        data-select2-id="fcompania_cuentagrid_x<?= $Grid->RowIndex ?>_activo"
        <?php } ?>
        data-table="compania_cuenta"
        data-field="x_activo"
        data-value-separator="<?= $Grid->activo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->activo->getPlaceHolder()) ?>"
        <?= $Grid->activo->editAttributes() ?>>
        <?= $Grid->activo->selectOptionListHtml("x{$Grid->RowIndex}_activo") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->activo->getErrorMessage() ?></div>
<?php if (!$Grid->activo->IsNativeSelect) { ?>
<script>
loadjs.ready("fcompania_cuentagrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_activo", selectId: "fcompania_cuentagrid_x<?= $Grid->RowIndex ?>_activo" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fcompania_cuentagrid.lists.activo?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_activo", form: "fcompania_cuentagrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_activo", form: "fcompania_cuentagrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.compania_cuenta.fields.activo.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="compania_cuenta" data-field="x_activo" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_activo" id="o<?= $Grid->RowIndex ?>_activo" value="<?= HtmlEncode($Grid->activo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_compania_cuenta_activo" class="el_compania_cuenta_activo">
    <select
        id="x<?= $Grid->RowIndex ?>_activo"
        name="x<?= $Grid->RowIndex ?>_activo"
        class="form-select ew-select<?= $Grid->activo->isInvalidClass() ?>"
        <?php if (!$Grid->activo->IsNativeSelect) { ?>
        data-select2-id="fcompania_cuentagrid_x<?= $Grid->RowIndex ?>_activo"
        <?php } ?>
        data-table="compania_cuenta"
        data-field="x_activo"
        data-value-separator="<?= $Grid->activo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->activo->getPlaceHolder()) ?>"
        <?= $Grid->activo->editAttributes() ?>>
        <?= $Grid->activo->selectOptionListHtml("x{$Grid->RowIndex}_activo") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->activo->getErrorMessage() ?></div>
<?php if (!$Grid->activo->IsNativeSelect) { ?>
<script>
loadjs.ready("fcompania_cuentagrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_activo", selectId: "fcompania_cuentagrid_x<?= $Grid->RowIndex ?>_activo" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fcompania_cuentagrid.lists.activo?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_activo", form: "fcompania_cuentagrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_activo", form: "fcompania_cuentagrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.compania_cuenta.fields.activo.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_compania_cuenta_activo" class="el_compania_cuenta_activo">
<span<?= $Grid->activo->viewAttributes() ?>>
<?= $Grid->activo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="compania_cuenta" data-field="x_activo" data-hidden="1" name="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_activo" id="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_activo" value="<?= HtmlEncode($Grid->activo->FormValue) ?>">
<input type="hidden" data-table="compania_cuenta" data-field="x_activo" data-hidden="1" data-old name="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_activo" id="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_activo" value="<?= HtmlEncode($Grid->activo->OldValue) ?>">
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
loadjs.ready(["fcompania_cuentagrid","load"], () => fcompania_cuentagrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="fcompania_cuentagrid">
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
    ew.addEventHandlers("compania_cuenta");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
