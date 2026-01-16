<?php

namespace PHPMaker2024\mandrake;

// Set up and run Grid object
$Grid = Container("PagosGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fpagosgrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { pagos: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpagosgrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["tipo_pago", [fields.tipo_pago.visible && fields.tipo_pago.required ? ew.Validators.required(fields.tipo_pago.caption) : null], fields.tipo_pago.isInvalid],
            ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(fields.fecha.clientFormatPattern)], fields.fecha.isInvalid],
            ["banco", [fields.banco.visible && fields.banco.required ? ew.Validators.required(fields.banco.caption) : null], fields.banco.isInvalid],
            ["banco_destino", [fields.banco_destino.visible && fields.banco_destino.required ? ew.Validators.required(fields.banco_destino.caption) : null], fields.banco_destino.isInvalid],
            ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
            ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
            ["monto", [fields.monto.visible && fields.monto.required ? ew.Validators.required(fields.monto.caption) : null, ew.Validators.float], fields.monto.isInvalid],
            ["comprobante_pago", [fields.comprobante_pago.visible && fields.comprobante_pago.required ? ew.Validators.fileRequired(fields.comprobante_pago.caption) : null], fields.comprobante_pago.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["tipo_pago",false],["fecha",false],["banco",false],["banco_destino",false],["referencia",false],["moneda",false],["monto",false],["comprobante_pago",false]];
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
            "tipo_pago": <?= $Grid->tipo_pago->toClientList($Grid) ?>,
            "banco": <?= $Grid->banco->toClientList($Grid) ?>,
            "banco_destino": <?= $Grid->banco_destino->toClientList($Grid) ?>,
            "moneda": <?= $Grid->moneda->toClientList($Grid) ?>,
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
<div id="fpagosgrid" class="ew-form ew-list-form">
<div id="gmp_pagos" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_pagosgrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Grid->tipo_pago->Visible) { // tipo_pago ?>
        <th data-name="tipo_pago" class="<?= $Grid->tipo_pago->headerCellClass() ?>"><div id="elh_pagos_tipo_pago" class="pagos_tipo_pago"><?= $Grid->renderFieldHeader($Grid->tipo_pago) ?></div></th>
<?php } ?>
<?php if ($Grid->fecha->Visible) { // fecha ?>
        <th data-name="fecha" class="<?= $Grid->fecha->headerCellClass() ?>"><div id="elh_pagos_fecha" class="pagos_fecha"><?= $Grid->renderFieldHeader($Grid->fecha) ?></div></th>
<?php } ?>
<?php if ($Grid->banco->Visible) { // banco ?>
        <th data-name="banco" class="<?= $Grid->banco->headerCellClass() ?>"><div id="elh_pagos_banco" class="pagos_banco"><?= $Grid->renderFieldHeader($Grid->banco) ?></div></th>
<?php } ?>
<?php if ($Grid->banco_destino->Visible) { // banco_destino ?>
        <th data-name="banco_destino" class="<?= $Grid->banco_destino->headerCellClass() ?>"><div id="elh_pagos_banco_destino" class="pagos_banco_destino"><?= $Grid->renderFieldHeader($Grid->banco_destino) ?></div></th>
<?php } ?>
<?php if ($Grid->referencia->Visible) { // referencia ?>
        <th data-name="referencia" class="<?= $Grid->referencia->headerCellClass() ?>"><div id="elh_pagos_referencia" class="pagos_referencia"><?= $Grid->renderFieldHeader($Grid->referencia) ?></div></th>
<?php } ?>
<?php if ($Grid->moneda->Visible) { // moneda ?>
        <th data-name="moneda" class="<?= $Grid->moneda->headerCellClass() ?>"><div id="elh_pagos_moneda" class="pagos_moneda"><?= $Grid->renderFieldHeader($Grid->moneda) ?></div></th>
<?php } ?>
<?php if ($Grid->monto->Visible) { // monto ?>
        <th data-name="monto" class="<?= $Grid->monto->headerCellClass() ?>"><div id="elh_pagos_monto" class="pagos_monto"><?= $Grid->renderFieldHeader($Grid->monto) ?></div></th>
<?php } ?>
<?php if ($Grid->comprobante_pago->Visible) { // comprobante_pago ?>
        <th data-name="comprobante_pago" class="<?= $Grid->comprobante_pago->headerCellClass() ?>"><div id="elh_pagos_comprobante_pago" class="pagos_comprobante_pago"><?= $Grid->renderFieldHeader($Grid->comprobante_pago) ?></div></th>
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
    <?php if ($Grid->tipo_pago->Visible) { // tipo_pago ?>
        <td data-name="tipo_pago"<?= $Grid->tipo_pago->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_tipo_pago" class="el_pagos_tipo_pago">
    <select
        id="x<?= $Grid->RowIndex ?>_tipo_pago"
        name="x<?= $Grid->RowIndex ?>_tipo_pago"
        class="form-select ew-select<?= $Grid->tipo_pago->isInvalidClass() ?>"
        <?php if (!$Grid->tipo_pago->IsNativeSelect) { ?>
        data-select2-id="fpagosgrid_x<?= $Grid->RowIndex ?>_tipo_pago"
        <?php } ?>
        data-table="pagos"
        data-field="x_tipo_pago"
        data-value-separator="<?= $Grid->tipo_pago->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->tipo_pago->getPlaceHolder()) ?>"
        <?= $Grid->tipo_pago->editAttributes() ?>>
        <?= $Grid->tipo_pago->selectOptionListHtml("x{$Grid->RowIndex}_tipo_pago") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->tipo_pago->getErrorMessage() ?></div>
<?= $Grid->tipo_pago->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_tipo_pago") ?>
<?php if (!$Grid->tipo_pago->IsNativeSelect) { ?>
<script>
loadjs.ready("fpagosgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_tipo_pago", selectId: "fpagosgrid_x<?= $Grid->RowIndex ?>_tipo_pago" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fpagosgrid.lists.tipo_pago?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_tipo_pago", form: "fpagosgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_tipo_pago", form: "fpagosgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.pagos.fields.tipo_pago.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="pagos" data-field="x_tipo_pago" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_tipo_pago" id="o<?= $Grid->RowIndex ?>_tipo_pago" value="<?= HtmlEncode($Grid->tipo_pago->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_tipo_pago" class="el_pagos_tipo_pago">
    <select
        id="x<?= $Grid->RowIndex ?>_tipo_pago"
        name="x<?= $Grid->RowIndex ?>_tipo_pago"
        class="form-select ew-select<?= $Grid->tipo_pago->isInvalidClass() ?>"
        <?php if (!$Grid->tipo_pago->IsNativeSelect) { ?>
        data-select2-id="fpagosgrid_x<?= $Grid->RowIndex ?>_tipo_pago"
        <?php } ?>
        data-table="pagos"
        data-field="x_tipo_pago"
        data-value-separator="<?= $Grid->tipo_pago->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->tipo_pago->getPlaceHolder()) ?>"
        <?= $Grid->tipo_pago->editAttributes() ?>>
        <?= $Grid->tipo_pago->selectOptionListHtml("x{$Grid->RowIndex}_tipo_pago") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->tipo_pago->getErrorMessage() ?></div>
<?= $Grid->tipo_pago->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_tipo_pago") ?>
<?php if (!$Grid->tipo_pago->IsNativeSelect) { ?>
<script>
loadjs.ready("fpagosgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_tipo_pago", selectId: "fpagosgrid_x<?= $Grid->RowIndex ?>_tipo_pago" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fpagosgrid.lists.tipo_pago?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_tipo_pago", form: "fpagosgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_tipo_pago", form: "fpagosgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.pagos.fields.tipo_pago.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_tipo_pago" class="el_pagos_tipo_pago">
<span<?= $Grid->tipo_pago->viewAttributes() ?>>
<?= $Grid->tipo_pago->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pagos" data-field="x_tipo_pago" data-hidden="1" name="fpagosgrid$x<?= $Grid->RowIndex ?>_tipo_pago" id="fpagosgrid$x<?= $Grid->RowIndex ?>_tipo_pago" value="<?= HtmlEncode($Grid->tipo_pago->FormValue) ?>">
<input type="hidden" data-table="pagos" data-field="x_tipo_pago" data-hidden="1" data-old name="fpagosgrid$o<?= $Grid->RowIndex ?>_tipo_pago" id="fpagosgrid$o<?= $Grid->RowIndex ?>_tipo_pago" value="<?= HtmlEncode($Grid->tipo_pago->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->fecha->Visible) { // fecha ?>
        <td data-name="fecha"<?= $Grid->fecha->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_fecha" class="el_pagos_fecha">
<input type="<?= $Grid->fecha->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_fecha" id="x<?= $Grid->RowIndex ?>_fecha" data-table="pagos" data-field="x_fecha" value="<?= $Grid->fecha->EditValue ?>" placeholder="<?= HtmlEncode($Grid->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fecha->formatPattern()) ?>"<?= $Grid->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha->getErrorMessage() ?></div>
<?php if (!$Grid->fecha->ReadOnly && !$Grid->fecha->Disabled && !isset($Grid->fecha->EditAttrs["readonly"]) && !isset($Grid->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpagosgrid", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fpagosgrid", "x<?= $Grid->RowIndex ?>_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="pagos" data-field="x_fecha" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_fecha" id="o<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_fecha" class="el_pagos_fecha">
<input type="<?= $Grid->fecha->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_fecha" id="x<?= $Grid->RowIndex ?>_fecha" data-table="pagos" data-field="x_fecha" value="<?= $Grid->fecha->EditValue ?>" placeholder="<?= HtmlEncode($Grid->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fecha->formatPattern()) ?>"<?= $Grid->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha->getErrorMessage() ?></div>
<?php if (!$Grid->fecha->ReadOnly && !$Grid->fecha->Disabled && !isset($Grid->fecha->EditAttrs["readonly"]) && !isset($Grid->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpagosgrid", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fpagosgrid", "x<?= $Grid->RowIndex ?>_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_fecha" class="el_pagos_fecha">
<span<?= $Grid->fecha->viewAttributes() ?>>
<?= $Grid->fecha->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pagos" data-field="x_fecha" data-hidden="1" name="fpagosgrid$x<?= $Grid->RowIndex ?>_fecha" id="fpagosgrid$x<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->FormValue) ?>">
<input type="hidden" data-table="pagos" data-field="x_fecha" data-hidden="1" data-old name="fpagosgrid$o<?= $Grid->RowIndex ?>_fecha" id="fpagosgrid$o<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->banco->Visible) { // banco ?>
        <td data-name="banco"<?= $Grid->banco->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_banco" class="el_pagos_banco">
    <select
        id="x<?= $Grid->RowIndex ?>_banco"
        name="x<?= $Grid->RowIndex ?>_banco"
        class="form-select ew-select<?= $Grid->banco->isInvalidClass() ?>"
        <?php if (!$Grid->banco->IsNativeSelect) { ?>
        data-select2-id="fpagosgrid_x<?= $Grid->RowIndex ?>_banco"
        <?php } ?>
        data-table="pagos"
        data-field="x_banco"
        data-value-separator="<?= $Grid->banco->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->banco->getPlaceHolder()) ?>"
        <?= $Grid->banco->editAttributes() ?>>
        <?= $Grid->banco->selectOptionListHtml("x{$Grid->RowIndex}_banco") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->banco->getErrorMessage() ?></div>
<?= $Grid->banco->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_banco") ?>
<?php if (!$Grid->banco->IsNativeSelect) { ?>
<script>
loadjs.ready("fpagosgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_banco", selectId: "fpagosgrid_x<?= $Grid->RowIndex ?>_banco" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fpagosgrid.lists.banco?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_banco", form: "fpagosgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_banco", form: "fpagosgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.pagos.fields.banco.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="pagos" data-field="x_banco" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_banco" id="o<?= $Grid->RowIndex ?>_banco" value="<?= HtmlEncode($Grid->banco->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_banco" class="el_pagos_banco">
    <select
        id="x<?= $Grid->RowIndex ?>_banco"
        name="x<?= $Grid->RowIndex ?>_banco"
        class="form-select ew-select<?= $Grid->banco->isInvalidClass() ?>"
        <?php if (!$Grid->banco->IsNativeSelect) { ?>
        data-select2-id="fpagosgrid_x<?= $Grid->RowIndex ?>_banco"
        <?php } ?>
        data-table="pagos"
        data-field="x_banco"
        data-value-separator="<?= $Grid->banco->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->banco->getPlaceHolder()) ?>"
        <?= $Grid->banco->editAttributes() ?>>
        <?= $Grid->banco->selectOptionListHtml("x{$Grid->RowIndex}_banco") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->banco->getErrorMessage() ?></div>
<?= $Grid->banco->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_banco") ?>
<?php if (!$Grid->banco->IsNativeSelect) { ?>
<script>
loadjs.ready("fpagosgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_banco", selectId: "fpagosgrid_x<?= $Grid->RowIndex ?>_banco" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fpagosgrid.lists.banco?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_banco", form: "fpagosgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_banco", form: "fpagosgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.pagos.fields.banco.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_banco" class="el_pagos_banco">
<span<?= $Grid->banco->viewAttributes() ?>>
<?= $Grid->banco->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pagos" data-field="x_banco" data-hidden="1" name="fpagosgrid$x<?= $Grid->RowIndex ?>_banco" id="fpagosgrid$x<?= $Grid->RowIndex ?>_banco" value="<?= HtmlEncode($Grid->banco->FormValue) ?>">
<input type="hidden" data-table="pagos" data-field="x_banco" data-hidden="1" data-old name="fpagosgrid$o<?= $Grid->RowIndex ?>_banco" id="fpagosgrid$o<?= $Grid->RowIndex ?>_banco" value="<?= HtmlEncode($Grid->banco->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->banco_destino->Visible) { // banco_destino ?>
        <td data-name="banco_destino"<?= $Grid->banco_destino->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_banco_destino" class="el_pagos_banco_destino">
    <select
        id="x<?= $Grid->RowIndex ?>_banco_destino"
        name="x<?= $Grid->RowIndex ?>_banco_destino"
        class="form-select ew-select<?= $Grid->banco_destino->isInvalidClass() ?>"
        <?php if (!$Grid->banco_destino->IsNativeSelect) { ?>
        data-select2-id="fpagosgrid_x<?= $Grid->RowIndex ?>_banco_destino"
        <?php } ?>
        data-table="pagos"
        data-field="x_banco_destino"
        data-value-separator="<?= $Grid->banco_destino->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->banco_destino->getPlaceHolder()) ?>"
        <?= $Grid->banco_destino->editAttributes() ?>>
        <?= $Grid->banco_destino->selectOptionListHtml("x{$Grid->RowIndex}_banco_destino") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->banco_destino->getErrorMessage() ?></div>
<?= $Grid->banco_destino->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_banco_destino") ?>
<?php if (!$Grid->banco_destino->IsNativeSelect) { ?>
<script>
loadjs.ready("fpagosgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_banco_destino", selectId: "fpagosgrid_x<?= $Grid->RowIndex ?>_banco_destino" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fpagosgrid.lists.banco_destino?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_banco_destino", form: "fpagosgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_banco_destino", form: "fpagosgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.pagos.fields.banco_destino.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="pagos" data-field="x_banco_destino" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_banco_destino" id="o<?= $Grid->RowIndex ?>_banco_destino" value="<?= HtmlEncode($Grid->banco_destino->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_banco_destino" class="el_pagos_banco_destino">
    <select
        id="x<?= $Grid->RowIndex ?>_banco_destino"
        name="x<?= $Grid->RowIndex ?>_banco_destino"
        class="form-select ew-select<?= $Grid->banco_destino->isInvalidClass() ?>"
        <?php if (!$Grid->banco_destino->IsNativeSelect) { ?>
        data-select2-id="fpagosgrid_x<?= $Grid->RowIndex ?>_banco_destino"
        <?php } ?>
        data-table="pagos"
        data-field="x_banco_destino"
        data-value-separator="<?= $Grid->banco_destino->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->banco_destino->getPlaceHolder()) ?>"
        <?= $Grid->banco_destino->editAttributes() ?>>
        <?= $Grid->banco_destino->selectOptionListHtml("x{$Grid->RowIndex}_banco_destino") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->banco_destino->getErrorMessage() ?></div>
<?= $Grid->banco_destino->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_banco_destino") ?>
<?php if (!$Grid->banco_destino->IsNativeSelect) { ?>
<script>
loadjs.ready("fpagosgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_banco_destino", selectId: "fpagosgrid_x<?= $Grid->RowIndex ?>_banco_destino" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fpagosgrid.lists.banco_destino?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_banco_destino", form: "fpagosgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_banco_destino", form: "fpagosgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.pagos.fields.banco_destino.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_banco_destino" class="el_pagos_banco_destino">
<span<?= $Grid->banco_destino->viewAttributes() ?>>
<?= $Grid->banco_destino->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pagos" data-field="x_banco_destino" data-hidden="1" name="fpagosgrid$x<?= $Grid->RowIndex ?>_banco_destino" id="fpagosgrid$x<?= $Grid->RowIndex ?>_banco_destino" value="<?= HtmlEncode($Grid->banco_destino->FormValue) ?>">
<input type="hidden" data-table="pagos" data-field="x_banco_destino" data-hidden="1" data-old name="fpagosgrid$o<?= $Grid->RowIndex ?>_banco_destino" id="fpagosgrid$o<?= $Grid->RowIndex ?>_banco_destino" value="<?= HtmlEncode($Grid->banco_destino->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->referencia->Visible) { // referencia ?>
        <td data-name="referencia"<?= $Grid->referencia->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_referencia" class="el_pagos_referencia">
<input type="<?= $Grid->referencia->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_referencia" id="x<?= $Grid->RowIndex ?>_referencia" data-table="pagos" data-field="x_referencia" value="<?= $Grid->referencia->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->referencia->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->referencia->formatPattern()) ?>"<?= $Grid->referencia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->referencia->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="pagos" data-field="x_referencia" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_referencia" id="o<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_referencia" class="el_pagos_referencia">
<input type="<?= $Grid->referencia->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_referencia" id="x<?= $Grid->RowIndex ?>_referencia" data-table="pagos" data-field="x_referencia" value="<?= $Grid->referencia->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->referencia->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->referencia->formatPattern()) ?>"<?= $Grid->referencia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->referencia->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_referencia" class="el_pagos_referencia">
<span<?= $Grid->referencia->viewAttributes() ?>>
<?= $Grid->referencia->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pagos" data-field="x_referencia" data-hidden="1" name="fpagosgrid$x<?= $Grid->RowIndex ?>_referencia" id="fpagosgrid$x<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->FormValue) ?>">
<input type="hidden" data-table="pagos" data-field="x_referencia" data-hidden="1" data-old name="fpagosgrid$o<?= $Grid->RowIndex ?>_referencia" id="fpagosgrid$o<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->moneda->Visible) { // moneda ?>
        <td data-name="moneda"<?= $Grid->moneda->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_moneda" class="el_pagos_moneda">
    <select
        id="x<?= $Grid->RowIndex ?>_moneda"
        name="x<?= $Grid->RowIndex ?>_moneda"
        class="form-select ew-select<?= $Grid->moneda->isInvalidClass() ?>"
        <?php if (!$Grid->moneda->IsNativeSelect) { ?>
        data-select2-id="fpagosgrid_x<?= $Grid->RowIndex ?>_moneda"
        <?php } ?>
        data-table="pagos"
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
loadjs.ready("fpagosgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_moneda", selectId: "fpagosgrid_x<?= $Grid->RowIndex ?>_moneda" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fpagosgrid.lists.moneda?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_moneda", form: "fpagosgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_moneda", form: "fpagosgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.pagos.fields.moneda.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="pagos" data-field="x_moneda" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_moneda" id="o<?= $Grid->RowIndex ?>_moneda" value="<?= HtmlEncode($Grid->moneda->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_moneda" class="el_pagos_moneda">
    <select
        id="x<?= $Grid->RowIndex ?>_moneda"
        name="x<?= $Grid->RowIndex ?>_moneda"
        class="form-select ew-select<?= $Grid->moneda->isInvalidClass() ?>"
        <?php if (!$Grid->moneda->IsNativeSelect) { ?>
        data-select2-id="fpagosgrid_x<?= $Grid->RowIndex ?>_moneda"
        <?php } ?>
        data-table="pagos"
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
loadjs.ready("fpagosgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_moneda", selectId: "fpagosgrid_x<?= $Grid->RowIndex ?>_moneda" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fpagosgrid.lists.moneda?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_moneda", form: "fpagosgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_moneda", form: "fpagosgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.pagos.fields.moneda.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_moneda" class="el_pagos_moneda">
<span<?= $Grid->moneda->viewAttributes() ?>>
<?= $Grid->moneda->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pagos" data-field="x_moneda" data-hidden="1" name="fpagosgrid$x<?= $Grid->RowIndex ?>_moneda" id="fpagosgrid$x<?= $Grid->RowIndex ?>_moneda" value="<?= HtmlEncode($Grid->moneda->FormValue) ?>">
<input type="hidden" data-table="pagos" data-field="x_moneda" data-hidden="1" data-old name="fpagosgrid$o<?= $Grid->RowIndex ?>_moneda" id="fpagosgrid$o<?= $Grid->RowIndex ?>_moneda" value="<?= HtmlEncode($Grid->moneda->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->monto->Visible) { // monto ?>
        <td data-name="monto"<?= $Grid->monto->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_monto" class="el_pagos_monto">
<input type="<?= $Grid->monto->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_monto" id="x<?= $Grid->RowIndex ?>_monto" data-table="pagos" data-field="x_monto" value="<?= $Grid->monto->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->monto->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->monto->formatPattern()) ?>"<?= $Grid->monto->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="pagos" data-field="x_monto" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_monto" id="o<?= $Grid->RowIndex ?>_monto" value="<?= HtmlEncode($Grid->monto->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_monto" class="el_pagos_monto">
<input type="<?= $Grid->monto->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_monto" id="x<?= $Grid->RowIndex ?>_monto" data-table="pagos" data-field="x_monto" value="<?= $Grid->monto->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->monto->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->monto->formatPattern()) ?>"<?= $Grid->monto->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_monto" class="el_pagos_monto">
<span<?= $Grid->monto->viewAttributes() ?>>
<?= $Grid->monto->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pagos" data-field="x_monto" data-hidden="1" name="fpagosgrid$x<?= $Grid->RowIndex ?>_monto" id="fpagosgrid$x<?= $Grid->RowIndex ?>_monto" value="<?= HtmlEncode($Grid->monto->FormValue) ?>">
<input type="hidden" data-table="pagos" data-field="x_monto" data-hidden="1" data-old name="fpagosgrid$o<?= $Grid->RowIndex ?>_monto" id="fpagosgrid$o<?= $Grid->RowIndex ?>_monto" value="<?= HtmlEncode($Grid->monto->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->comprobante_pago->Visible) { // comprobante_pago ?>
        <td data-name="comprobante_pago"<?= $Grid->comprobante_pago->cellAttributes() ?>>
<?php if ($Grid->RowAction == "insert") { // Add record ?>
<?php if (!$Grid->isConfirm()) { ?>
<span id="el<?= $Grid->RowIndex ?>_pagos_comprobante_pago" class="el_pagos_comprobante_pago">
<div id="fd_x<?= $Grid->RowIndex ?>_comprobante_pago" class="fileinput-button ew-file-drop-zone">
    <input
        type="file"
        id="x<?= $Grid->RowIndex ?>_comprobante_pago"
        name="x<?= $Grid->RowIndex ?>_comprobante_pago"
        class="form-control ew-file-input"
        title="<?= $Grid->comprobante_pago->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="pagos"
        data-field="x_comprobante_pago"
        data-size="255"
        data-accept-file-types="<?= $Grid->comprobante_pago->acceptFileTypes() ?>"
        data-max-file-size="<?= $Grid->comprobante_pago->UploadMaxFileSize ?>"
        data-max-number-of-files="null"
        data-disable-image-crop="<?= $Grid->comprobante_pago->ImageCropper ? 0 : 1 ?>"
        <?= ($Grid->comprobante_pago->ReadOnly || $Grid->comprobante_pago->Disabled) ? " disabled" : "" ?>
        <?= $Grid->comprobante_pago->editAttributes() ?>
    >
    <div class="text-body-secondary ew-file-text"><?= $Language->phrase("ChooseFile") ?></div>
    <div class="invalid-feedback"><?= $Grid->comprobante_pago->getErrorMessage() ?></div>
</div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_comprobante_pago" id= "fn_x<?= $Grid->RowIndex ?>_comprobante_pago" value="<?= $Grid->comprobante_pago->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_comprobante_pago" id= "fa_x<?= $Grid->RowIndex ?>_comprobante_pago" value="0">
<table id="ft_x<?= $Grid->RowIndex ?>_comprobante_pago" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } else { ?>
<span id="el<?= $Grid->RowIndex ?>_pagos_comprobante_pago" class="el_pagos_comprobante_pago">
<div id="fd_x<?= $Grid->RowIndex ?>_comprobante_pago">
    <input
        type="file"
        id="x<?= $Grid->RowIndex ?>_comprobante_pago"
        name="x<?= $Grid->RowIndex ?>_comprobante_pago"
        class="form-control ew-file-input d-none"
        title="<?= $Grid->comprobante_pago->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="pagos"
        data-field="x_comprobante_pago"
        data-size="255"
        data-accept-file-types="<?= $Grid->comprobante_pago->acceptFileTypes() ?>"
        data-max-file-size="<?= $Grid->comprobante_pago->UploadMaxFileSize ?>"
        data-max-number-of-files="null"
        data-disable-image-crop="<?= $Grid->comprobante_pago->ImageCropper ? 0 : 1 ?>"
        <?= $Grid->comprobante_pago->editAttributes() ?>
    >
    <div class="invalid-feedback"><?= $Grid->comprobante_pago->getErrorMessage() ?></div>
</div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_comprobante_pago" id= "fn_x<?= $Grid->RowIndex ?>_comprobante_pago" value="<?= $Grid->comprobante_pago->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_comprobante_pago" id= "fa_x<?= $Grid->RowIndex ?>_comprobante_pago" value="0">
<table id="ft_x<?= $Grid->RowIndex ?>_comprobante_pago" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } ?>
<input type="hidden" data-table="pagos" data-field="x_comprobante_pago" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_comprobante_pago" id="o<?= $Grid->RowIndex ?>_comprobante_pago" value="<?= HtmlEncode($Grid->comprobante_pago->OldValue) ?>">
<?php } elseif ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_comprobante_pago" class="el_pagos_comprobante_pago">
<span>
<?= GetFileViewTag($Grid->comprobante_pago, $Grid->comprobante_pago->getViewValue(), false) ?>
</span>
</span>
<?php } else  { // Edit record ?>
<?php if (!$Grid->isConfirm()) { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_comprobante_pago" class="el_pagos_comprobante_pago">
<div id="fd_x<?= $Grid->RowIndex ?>_comprobante_pago" class="fileinput-button ew-file-drop-zone">
    <input
        type="file"
        id="x<?= $Grid->RowIndex ?>_comprobante_pago"
        name="x<?= $Grid->RowIndex ?>_comprobante_pago"
        class="form-control ew-file-input"
        title="<?= $Grid->comprobante_pago->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="pagos"
        data-field="x_comprobante_pago"
        data-size="255"
        data-accept-file-types="<?= $Grid->comprobante_pago->acceptFileTypes() ?>"
        data-max-file-size="<?= $Grid->comprobante_pago->UploadMaxFileSize ?>"
        data-max-number-of-files="null"
        data-disable-image-crop="<?= $Grid->comprobante_pago->ImageCropper ? 0 : 1 ?>"
        <?= ($Grid->comprobante_pago->ReadOnly || $Grid->comprobante_pago->Disabled) ? " disabled" : "" ?>
        <?= $Grid->comprobante_pago->editAttributes() ?>
    >
    <div class="text-body-secondary ew-file-text"><?= $Language->phrase("ChooseFile") ?></div>
    <div class="invalid-feedback"><?= $Grid->comprobante_pago->getErrorMessage() ?></div>
</div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_comprobante_pago" id= "fn_x<?= $Grid->RowIndex ?>_comprobante_pago" value="<?= $Grid->comprobante_pago->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_comprobante_pago" id= "fa_x<?= $Grid->RowIndex ?>_comprobante_pago" value="<?= (Post("fa_x<?= $Grid->RowIndex ?>_comprobante_pago") == "0") ? "0" : "1" ?>">
<table id="ft_x<?= $Grid->RowIndex ?>_comprobante_pago" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_pagos_comprobante_pago" class="el_pagos_comprobante_pago">
<div id="fd_x<?= $Grid->RowIndex ?>_comprobante_pago">
    <input
        type="file"
        id="x<?= $Grid->RowIndex ?>_comprobante_pago"
        name="x<?= $Grid->RowIndex ?>_comprobante_pago"
        class="form-control ew-file-input d-none"
        title="<?= $Grid->comprobante_pago->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="pagos"
        data-field="x_comprobante_pago"
        data-size="255"
        data-accept-file-types="<?= $Grid->comprobante_pago->acceptFileTypes() ?>"
        data-max-file-size="<?= $Grid->comprobante_pago->UploadMaxFileSize ?>"
        data-max-number-of-files="null"
        data-disable-image-crop="<?= $Grid->comprobante_pago->ImageCropper ? 0 : 1 ?>"
        <?= $Grid->comprobante_pago->editAttributes() ?>
    >
    <div class="invalid-feedback"><?= $Grid->comprobante_pago->getErrorMessage() ?></div>
</div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_comprobante_pago" id= "fn_x<?= $Grid->RowIndex ?>_comprobante_pago" value="<?= $Grid->comprobante_pago->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_comprobante_pago" id= "fa_x<?= $Grid->RowIndex ?>_comprobante_pago" value="<?= (Post("fa_x<?= $Grid->RowIndex ?>_comprobante_pago") == "0") ? "0" : "1" ?>">
<table id="ft_x<?= $Grid->RowIndex ?>_comprobante_pago" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
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
loadjs.ready(["fpagosgrid","load"], () => fpagosgrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="fpagosgrid">
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
    ew.addEventHandlers("pagos");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
