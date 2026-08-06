<?php

namespace PHPMaker2024\mandrake;

// Set up and run Grid object
$Grid = Container("ViewCuentasPorCobrarGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fview_cuentas_por_cobrargrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { view_cuentas_por_cobrar: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fview_cuentas_por_cobrargrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["cliente", [fields.cliente.visible && fields.cliente.required ? ew.Validators.required(fields.cliente.caption) : null], fields.cliente.isInvalid],
            ["cliente_rif", [fields.cliente_rif.visible && fields.cliente_rif.required ? ew.Validators.required(fields.cliente_rif.caption) : null], fields.cliente_rif.isInvalid],
            ["cliente_nombre", [fields.cliente_nombre.visible && fields.cliente_nombre.required ? ew.Validators.required(fields.cliente_nombre.caption) : null], fields.cliente_nombre.isInvalid],
            ["tipo_documento_fiscal", [fields.tipo_documento_fiscal.visible && fields.tipo_documento_fiscal.required ? ew.Validators.required(fields.tipo_documento_fiscal.caption) : null], fields.tipo_documento_fiscal.isInvalid],
            ["nro_documento", [fields.nro_documento.visible && fields.nro_documento.required ? ew.Validators.required(fields.nro_documento.caption) : null], fields.nro_documento.isInvalid],
            ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(fields.fecha.clientFormatPattern)], fields.fecha.isInvalid],
            ["monto_documento_bs", [fields.monto_documento_bs.visible && fields.monto_documento_bs.required ? ew.Validators.required(fields.monto_documento_bs.caption) : null, ew.Validators.float], fields.monto_documento_bs.isInvalid],
            ["total_cobrado_bs", [fields.total_cobrado_bs.visible && fields.total_cobrado_bs.required ? ew.Validators.required(fields.total_cobrado_bs.caption) : null, ew.Validators.float], fields.total_cobrado_bs.isInvalid],
            ["saldo_bs", [fields.saldo_bs.visible && fields.saldo_bs.required ? ew.Validators.required(fields.saldo_bs.caption) : null, ew.Validators.float], fields.saldo_bs.isInvalid],
            ["dias_vencido", [fields.dias_vencido.visible && fields.dias_vencido.required ? ew.Validators.required(fields.dias_vencido.caption) : null, ew.Validators.integer], fields.dias_vencido.isInvalid],
            ["antiguedad", [fields.antiguedad.visible && fields.antiguedad.required ? ew.Validators.required(fields.antiguedad.caption) : null], fields.antiguedad.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["cliente",false],["cliente_rif",false],["cliente_nombre",false],["tipo_documento_fiscal",false],["nro_documento",false],["fecha",false],["monto_documento_bs",false],["total_cobrado_bs",false],["saldo_bs",false],["dias_vencido",false],["antiguedad",false]];
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
            "tipo_documento_fiscal": <?= $Grid->tipo_documento_fiscal->toClientList($Grid) ?>,
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
<div id="fview_cuentas_por_cobrargrid" class="ew-form ew-list-form">
<div id="gmp_view_cuentas_por_cobrar" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_view_cuentas_por_cobrargrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
        <th data-name="cliente" class="<?= $Grid->cliente->headerCellClass() ?>"><div id="elh_view_cuentas_por_cobrar_cliente" class="view_cuentas_por_cobrar_cliente"><?= $Grid->renderFieldHeader($Grid->cliente) ?></div></th>
<?php } ?>
<?php if ($Grid->cliente_rif->Visible) { // cliente_rif ?>
        <th data-name="cliente_rif" class="<?= $Grid->cliente_rif->headerCellClass() ?>"><div id="elh_view_cuentas_por_cobrar_cliente_rif" class="view_cuentas_por_cobrar_cliente_rif"><?= $Grid->renderFieldHeader($Grid->cliente_rif) ?></div></th>
<?php } ?>
<?php if ($Grid->cliente_nombre->Visible) { // cliente_nombre ?>
        <th data-name="cliente_nombre" class="<?= $Grid->cliente_nombre->headerCellClass() ?>"><div id="elh_view_cuentas_por_cobrar_cliente_nombre" class="view_cuentas_por_cobrar_cliente_nombre"><?= $Grid->renderFieldHeader($Grid->cliente_nombre) ?></div></th>
<?php } ?>
<?php if ($Grid->tipo_documento_fiscal->Visible) { // tipo_documento_fiscal ?>
        <th data-name="tipo_documento_fiscal" class="<?= $Grid->tipo_documento_fiscal->headerCellClass() ?>"><div id="elh_view_cuentas_por_cobrar_tipo_documento_fiscal" class="view_cuentas_por_cobrar_tipo_documento_fiscal"><?= $Grid->renderFieldHeader($Grid->tipo_documento_fiscal) ?></div></th>
<?php } ?>
<?php if ($Grid->nro_documento->Visible) { // nro_documento ?>
        <th data-name="nro_documento" class="<?= $Grid->nro_documento->headerCellClass() ?>"><div id="elh_view_cuentas_por_cobrar_nro_documento" class="view_cuentas_por_cobrar_nro_documento"><?= $Grid->renderFieldHeader($Grid->nro_documento) ?></div></th>
<?php } ?>
<?php if ($Grid->fecha->Visible) { // fecha ?>
        <th data-name="fecha" class="<?= $Grid->fecha->headerCellClass() ?>"><div id="elh_view_cuentas_por_cobrar_fecha" class="view_cuentas_por_cobrar_fecha"><?= $Grid->renderFieldHeader($Grid->fecha) ?></div></th>
<?php } ?>
<?php if ($Grid->monto_documento_bs->Visible) { // monto_documento_bs ?>
        <th data-name="monto_documento_bs" class="<?= $Grid->monto_documento_bs->headerCellClass() ?>"><div id="elh_view_cuentas_por_cobrar_monto_documento_bs" class="view_cuentas_por_cobrar_monto_documento_bs"><?= $Grid->renderFieldHeader($Grid->monto_documento_bs) ?></div></th>
<?php } ?>
<?php if ($Grid->total_cobrado_bs->Visible) { // total_cobrado_bs ?>
        <th data-name="total_cobrado_bs" class="<?= $Grid->total_cobrado_bs->headerCellClass() ?>"><div id="elh_view_cuentas_por_cobrar_total_cobrado_bs" class="view_cuentas_por_cobrar_total_cobrado_bs"><?= $Grid->renderFieldHeader($Grid->total_cobrado_bs) ?></div></th>
<?php } ?>
<?php if ($Grid->saldo_bs->Visible) { // saldo_bs ?>
        <th data-name="saldo_bs" class="<?= $Grid->saldo_bs->headerCellClass() ?>"><div id="elh_view_cuentas_por_cobrar_saldo_bs" class="view_cuentas_por_cobrar_saldo_bs"><?= $Grid->renderFieldHeader($Grid->saldo_bs) ?></div></th>
<?php } ?>
<?php if ($Grid->dias_vencido->Visible) { // dias_vencido ?>
        <th data-name="dias_vencido" class="<?= $Grid->dias_vencido->headerCellClass() ?>"><div id="elh_view_cuentas_por_cobrar_dias_vencido" class="view_cuentas_por_cobrar_dias_vencido"><?= $Grid->renderFieldHeader($Grid->dias_vencido) ?></div></th>
<?php } ?>
<?php if ($Grid->antiguedad->Visible) { // antiguedad ?>
        <th data-name="antiguedad" class="<?= $Grid->antiguedad->headerCellClass() ?>"><div id="elh_view_cuentas_por_cobrar_antiguedad" class="view_cuentas_por_cobrar_antiguedad"><?= $Grid->renderFieldHeader($Grid->antiguedad) ?></div></th>
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
<?php if ($Grid->cliente->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_cliente" class="el_view_cuentas_por_cobrar_cliente">
<span<?= $Grid->cliente->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->cliente->getDisplayValue($Grid->cliente->ViewValue) ?></span></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_cliente" name="x<?= $Grid->RowIndex ?>_cliente" value="<?= HtmlEncode($Grid->cliente->CurrentValue) ?>" data-hidden="1">
</span>
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_cliente" class="el_view_cuentas_por_cobrar_cliente">
    <select
        id="x<?= $Grid->RowIndex ?>_cliente"
        name="x<?= $Grid->RowIndex ?>_cliente"
        class="form-control ew-select<?= $Grid->cliente->isInvalidClass() ?>"
        data-select2-id="fview_cuentas_por_cobrargrid_x<?= $Grid->RowIndex ?>_cliente"
        data-table="view_cuentas_por_cobrar"
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
loadjs.ready("fview_cuentas_por_cobrargrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_cliente", selectId: "fview_cuentas_por_cobrargrid_x<?= $Grid->RowIndex ?>_cliente" };
    if (fview_cuentas_por_cobrargrid.lists.cliente?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_cliente", form: "fview_cuentas_por_cobrargrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_cliente", form: "fview_cuentas_por_cobrargrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.view_cuentas_por_cobrar.fields.cliente.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_cliente" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_cliente" id="o<?= $Grid->RowIndex ?>_cliente" value="<?= HtmlEncode($Grid->cliente->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<?php if ($Grid->cliente->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_cliente" class="el_view_cuentas_por_cobrar_cliente">
<span<?= $Grid->cliente->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->cliente->getDisplayValue($Grid->cliente->ViewValue) ?></span></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_cliente" name="x<?= $Grid->RowIndex ?>_cliente" value="<?= HtmlEncode($Grid->cliente->CurrentValue) ?>" data-hidden="1">
</span>
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_cliente" class="el_view_cuentas_por_cobrar_cliente">
    <select
        id="x<?= $Grid->RowIndex ?>_cliente"
        name="x<?= $Grid->RowIndex ?>_cliente"
        class="form-control ew-select<?= $Grid->cliente->isInvalidClass() ?>"
        data-select2-id="fview_cuentas_por_cobrargrid_x<?= $Grid->RowIndex ?>_cliente"
        data-table="view_cuentas_por_cobrar"
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
loadjs.ready("fview_cuentas_por_cobrargrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_cliente", selectId: "fview_cuentas_por_cobrargrid_x<?= $Grid->RowIndex ?>_cliente" };
    if (fview_cuentas_por_cobrargrid.lists.cliente?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_cliente", form: "fview_cuentas_por_cobrargrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_cliente", form: "fview_cuentas_por_cobrargrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.view_cuentas_por_cobrar.fields.cliente.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_cliente" class="el_view_cuentas_por_cobrar_cliente">
<span<?= $Grid->cliente->viewAttributes() ?>>
<?= $Grid->cliente->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_cliente" data-hidden="1" name="fview_cuentas_por_cobrargrid$x<?= $Grid->RowIndex ?>_cliente" id="fview_cuentas_por_cobrargrid$x<?= $Grid->RowIndex ?>_cliente" value="<?= HtmlEncode($Grid->cliente->FormValue) ?>">
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_cliente" data-hidden="1" data-old name="fview_cuentas_por_cobrargrid$o<?= $Grid->RowIndex ?>_cliente" id="fview_cuentas_por_cobrargrid$o<?= $Grid->RowIndex ?>_cliente" value="<?= HtmlEncode($Grid->cliente->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->cliente_rif->Visible) { // cliente_rif ?>
        <td data-name="cliente_rif"<?= $Grid->cliente_rif->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_cliente_rif" class="el_view_cuentas_por_cobrar_cliente_rif">
<input type="<?= $Grid->cliente_rif->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_cliente_rif" id="x<?= $Grid->RowIndex ?>_cliente_rif" data-table="view_cuentas_por_cobrar" data-field="x_cliente_rif" value="<?= $Grid->cliente_rif->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Grid->cliente_rif->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->cliente_rif->formatPattern()) ?>"<?= $Grid->cliente_rif->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cliente_rif->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_cliente_rif" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_cliente_rif" id="o<?= $Grid->RowIndex ?>_cliente_rif" value="<?= HtmlEncode($Grid->cliente_rif->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_cliente_rif" class="el_view_cuentas_por_cobrar_cliente_rif">
<input type="<?= $Grid->cliente_rif->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_cliente_rif" id="x<?= $Grid->RowIndex ?>_cliente_rif" data-table="view_cuentas_por_cobrar" data-field="x_cliente_rif" value="<?= $Grid->cliente_rif->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Grid->cliente_rif->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->cliente_rif->formatPattern()) ?>"<?= $Grid->cliente_rif->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cliente_rif->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_cliente_rif" class="el_view_cuentas_por_cobrar_cliente_rif">
<span<?= $Grid->cliente_rif->viewAttributes() ?>>
<?= $Grid->cliente_rif->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_cliente_rif" data-hidden="1" name="fview_cuentas_por_cobrargrid$x<?= $Grid->RowIndex ?>_cliente_rif" id="fview_cuentas_por_cobrargrid$x<?= $Grid->RowIndex ?>_cliente_rif" value="<?= HtmlEncode($Grid->cliente_rif->FormValue) ?>">
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_cliente_rif" data-hidden="1" data-old name="fview_cuentas_por_cobrargrid$o<?= $Grid->RowIndex ?>_cliente_rif" id="fview_cuentas_por_cobrargrid$o<?= $Grid->RowIndex ?>_cliente_rif" value="<?= HtmlEncode($Grid->cliente_rif->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->cliente_nombre->Visible) { // cliente_nombre ?>
        <td data-name="cliente_nombre"<?= $Grid->cliente_nombre->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_cliente_nombre" class="el_view_cuentas_por_cobrar_cliente_nombre">
<input type="<?= $Grid->cliente_nombre->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_cliente_nombre" id="x<?= $Grid->RowIndex ?>_cliente_nombre" data-table="view_cuentas_por_cobrar" data-field="x_cliente_nombre" value="<?= $Grid->cliente_nombre->EditValue ?>" size="30" maxlength="80" placeholder="<?= HtmlEncode($Grid->cliente_nombre->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->cliente_nombre->formatPattern()) ?>"<?= $Grid->cliente_nombre->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cliente_nombre->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_cliente_nombre" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_cliente_nombre" id="o<?= $Grid->RowIndex ?>_cliente_nombre" value="<?= HtmlEncode($Grid->cliente_nombre->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_cliente_nombre" class="el_view_cuentas_por_cobrar_cliente_nombre">
<input type="<?= $Grid->cliente_nombre->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_cliente_nombre" id="x<?= $Grid->RowIndex ?>_cliente_nombre" data-table="view_cuentas_por_cobrar" data-field="x_cliente_nombre" value="<?= $Grid->cliente_nombre->EditValue ?>" size="30" maxlength="80" placeholder="<?= HtmlEncode($Grid->cliente_nombre->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->cliente_nombre->formatPattern()) ?>"<?= $Grid->cliente_nombre->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cliente_nombre->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_cliente_nombre" class="el_view_cuentas_por_cobrar_cliente_nombre">
<span<?= $Grid->cliente_nombre->viewAttributes() ?>>
<?= $Grid->cliente_nombre->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_cliente_nombre" data-hidden="1" name="fview_cuentas_por_cobrargrid$x<?= $Grid->RowIndex ?>_cliente_nombre" id="fview_cuentas_por_cobrargrid$x<?= $Grid->RowIndex ?>_cliente_nombre" value="<?= HtmlEncode($Grid->cliente_nombre->FormValue) ?>">
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_cliente_nombre" data-hidden="1" data-old name="fview_cuentas_por_cobrargrid$o<?= $Grid->RowIndex ?>_cliente_nombre" id="fview_cuentas_por_cobrargrid$o<?= $Grid->RowIndex ?>_cliente_nombre" value="<?= HtmlEncode($Grid->cliente_nombre->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->tipo_documento_fiscal->Visible) { // tipo_documento_fiscal ?>
        <td data-name="tipo_documento_fiscal"<?= $Grid->tipo_documento_fiscal->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_tipo_documento_fiscal" class="el_view_cuentas_por_cobrar_tipo_documento_fiscal">
    <select
        id="x<?= $Grid->RowIndex ?>_tipo_documento_fiscal"
        name="x<?= $Grid->RowIndex ?>_tipo_documento_fiscal"
        class="form-select ew-select<?= $Grid->tipo_documento_fiscal->isInvalidClass() ?>"
        <?php if (!$Grid->tipo_documento_fiscal->IsNativeSelect) { ?>
        data-select2-id="fview_cuentas_por_cobrargrid_x<?= $Grid->RowIndex ?>_tipo_documento_fiscal"
        <?php } ?>
        data-table="view_cuentas_por_cobrar"
        data-field="x_tipo_documento_fiscal"
        data-value-separator="<?= $Grid->tipo_documento_fiscal->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->tipo_documento_fiscal->getPlaceHolder()) ?>"
        <?= $Grid->tipo_documento_fiscal->editAttributes() ?>>
        <?= $Grid->tipo_documento_fiscal->selectOptionListHtml("x{$Grid->RowIndex}_tipo_documento_fiscal") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->tipo_documento_fiscal->getErrorMessage() ?></div>
<?php if (!$Grid->tipo_documento_fiscal->IsNativeSelect) { ?>
<script>
loadjs.ready("fview_cuentas_por_cobrargrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_tipo_documento_fiscal", selectId: "fview_cuentas_por_cobrargrid_x<?= $Grid->RowIndex ?>_tipo_documento_fiscal" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fview_cuentas_por_cobrargrid.lists.tipo_documento_fiscal?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_tipo_documento_fiscal", form: "fview_cuentas_por_cobrargrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_tipo_documento_fiscal", form: "fview_cuentas_por_cobrargrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.view_cuentas_por_cobrar.fields.tipo_documento_fiscal.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_tipo_documento_fiscal" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_tipo_documento_fiscal" id="o<?= $Grid->RowIndex ?>_tipo_documento_fiscal" value="<?= HtmlEncode($Grid->tipo_documento_fiscal->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_tipo_documento_fiscal" class="el_view_cuentas_por_cobrar_tipo_documento_fiscal">
    <select
        id="x<?= $Grid->RowIndex ?>_tipo_documento_fiscal"
        name="x<?= $Grid->RowIndex ?>_tipo_documento_fiscal"
        class="form-select ew-select<?= $Grid->tipo_documento_fiscal->isInvalidClass() ?>"
        <?php if (!$Grid->tipo_documento_fiscal->IsNativeSelect) { ?>
        data-select2-id="fview_cuentas_por_cobrargrid_x<?= $Grid->RowIndex ?>_tipo_documento_fiscal"
        <?php } ?>
        data-table="view_cuentas_por_cobrar"
        data-field="x_tipo_documento_fiscal"
        data-value-separator="<?= $Grid->tipo_documento_fiscal->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->tipo_documento_fiscal->getPlaceHolder()) ?>"
        <?= $Grid->tipo_documento_fiscal->editAttributes() ?>>
        <?= $Grid->tipo_documento_fiscal->selectOptionListHtml("x{$Grid->RowIndex}_tipo_documento_fiscal") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->tipo_documento_fiscal->getErrorMessage() ?></div>
<?php if (!$Grid->tipo_documento_fiscal->IsNativeSelect) { ?>
<script>
loadjs.ready("fview_cuentas_por_cobrargrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_tipo_documento_fiscal", selectId: "fview_cuentas_por_cobrargrid_x<?= $Grid->RowIndex ?>_tipo_documento_fiscal" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fview_cuentas_por_cobrargrid.lists.tipo_documento_fiscal?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_tipo_documento_fiscal", form: "fview_cuentas_por_cobrargrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_tipo_documento_fiscal", form: "fview_cuentas_por_cobrargrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.view_cuentas_por_cobrar.fields.tipo_documento_fiscal.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_tipo_documento_fiscal" class="el_view_cuentas_por_cobrar_tipo_documento_fiscal">
<span<?= $Grid->tipo_documento_fiscal->viewAttributes() ?>>
<?= $Grid->tipo_documento_fiscal->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_tipo_documento_fiscal" data-hidden="1" name="fview_cuentas_por_cobrargrid$x<?= $Grid->RowIndex ?>_tipo_documento_fiscal" id="fview_cuentas_por_cobrargrid$x<?= $Grid->RowIndex ?>_tipo_documento_fiscal" value="<?= HtmlEncode($Grid->tipo_documento_fiscal->FormValue) ?>">
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_tipo_documento_fiscal" data-hidden="1" data-old name="fview_cuentas_por_cobrargrid$o<?= $Grid->RowIndex ?>_tipo_documento_fiscal" id="fview_cuentas_por_cobrargrid$o<?= $Grid->RowIndex ?>_tipo_documento_fiscal" value="<?= HtmlEncode($Grid->tipo_documento_fiscal->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->nro_documento->Visible) { // nro_documento ?>
        <td data-name="nro_documento"<?= $Grid->nro_documento->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_nro_documento" class="el_view_cuentas_por_cobrar_nro_documento">
<input type="<?= $Grid->nro_documento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_nro_documento" id="x<?= $Grid->RowIndex ?>_nro_documento" data-table="view_cuentas_por_cobrar" data-field="x_nro_documento" value="<?= $Grid->nro_documento->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Grid->nro_documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->nro_documento->formatPattern()) ?>"<?= $Grid->nro_documento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->nro_documento->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_nro_documento" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_nro_documento" id="o<?= $Grid->RowIndex ?>_nro_documento" value="<?= HtmlEncode($Grid->nro_documento->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_nro_documento" class="el_view_cuentas_por_cobrar_nro_documento">
<input type="<?= $Grid->nro_documento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_nro_documento" id="x<?= $Grid->RowIndex ?>_nro_documento" data-table="view_cuentas_por_cobrar" data-field="x_nro_documento" value="<?= $Grid->nro_documento->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Grid->nro_documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->nro_documento->formatPattern()) ?>"<?= $Grid->nro_documento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->nro_documento->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_nro_documento" class="el_view_cuentas_por_cobrar_nro_documento">
<span<?= $Grid->nro_documento->viewAttributes() ?>>
<?= $Grid->nro_documento->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_nro_documento" data-hidden="1" name="fview_cuentas_por_cobrargrid$x<?= $Grid->RowIndex ?>_nro_documento" id="fview_cuentas_por_cobrargrid$x<?= $Grid->RowIndex ?>_nro_documento" value="<?= HtmlEncode($Grid->nro_documento->FormValue) ?>">
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_nro_documento" data-hidden="1" data-old name="fview_cuentas_por_cobrargrid$o<?= $Grid->RowIndex ?>_nro_documento" id="fview_cuentas_por_cobrargrid$o<?= $Grid->RowIndex ?>_nro_documento" value="<?= HtmlEncode($Grid->nro_documento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->fecha->Visible) { // fecha ?>
        <td data-name="fecha"<?= $Grid->fecha->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_fecha" class="el_view_cuentas_por_cobrar_fecha">
<input type="<?= $Grid->fecha->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_fecha" id="x<?= $Grid->RowIndex ?>_fecha" data-table="view_cuentas_por_cobrar" data-field="x_fecha" value="<?= $Grid->fecha->EditValue ?>" placeholder="<?= HtmlEncode($Grid->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fecha->formatPattern()) ?>"<?= $Grid->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha->getErrorMessage() ?></div>
<?php if (!$Grid->fecha->ReadOnly && !$Grid->fecha->Disabled && !isset($Grid->fecha->EditAttrs["readonly"]) && !isset($Grid->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fview_cuentas_por_cobrargrid", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fview_cuentas_por_cobrargrid", "x<?= $Grid->RowIndex ?>_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_fecha" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_fecha" id="o<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_fecha" class="el_view_cuentas_por_cobrar_fecha">
<input type="<?= $Grid->fecha->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_fecha" id="x<?= $Grid->RowIndex ?>_fecha" data-table="view_cuentas_por_cobrar" data-field="x_fecha" value="<?= $Grid->fecha->EditValue ?>" placeholder="<?= HtmlEncode($Grid->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fecha->formatPattern()) ?>"<?= $Grid->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha->getErrorMessage() ?></div>
<?php if (!$Grid->fecha->ReadOnly && !$Grid->fecha->Disabled && !isset($Grid->fecha->EditAttrs["readonly"]) && !isset($Grid->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fview_cuentas_por_cobrargrid", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fview_cuentas_por_cobrargrid", "x<?= $Grid->RowIndex ?>_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_fecha" class="el_view_cuentas_por_cobrar_fecha">
<span<?= $Grid->fecha->viewAttributes() ?>>
<?= $Grid->fecha->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_fecha" data-hidden="1" name="fview_cuentas_por_cobrargrid$x<?= $Grid->RowIndex ?>_fecha" id="fview_cuentas_por_cobrargrid$x<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->FormValue) ?>">
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_fecha" data-hidden="1" data-old name="fview_cuentas_por_cobrargrid$o<?= $Grid->RowIndex ?>_fecha" id="fview_cuentas_por_cobrargrid$o<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->monto_documento_bs->Visible) { // monto_documento_bs ?>
        <td data-name="monto_documento_bs"<?= $Grid->monto_documento_bs->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_monto_documento_bs" class="el_view_cuentas_por_cobrar_monto_documento_bs">
<input type="<?= $Grid->monto_documento_bs->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_monto_documento_bs" id="x<?= $Grid->RowIndex ?>_monto_documento_bs" data-table="view_cuentas_por_cobrar" data-field="x_monto_documento_bs" value="<?= $Grid->monto_documento_bs->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->monto_documento_bs->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->monto_documento_bs->formatPattern()) ?>"<?= $Grid->monto_documento_bs->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_documento_bs->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_monto_documento_bs" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_monto_documento_bs" id="o<?= $Grid->RowIndex ?>_monto_documento_bs" value="<?= HtmlEncode($Grid->monto_documento_bs->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_monto_documento_bs" class="el_view_cuentas_por_cobrar_monto_documento_bs">
<input type="<?= $Grid->monto_documento_bs->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_monto_documento_bs" id="x<?= $Grid->RowIndex ?>_monto_documento_bs" data-table="view_cuentas_por_cobrar" data-field="x_monto_documento_bs" value="<?= $Grid->monto_documento_bs->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->monto_documento_bs->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->monto_documento_bs->formatPattern()) ?>"<?= $Grid->monto_documento_bs->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_documento_bs->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_monto_documento_bs" class="el_view_cuentas_por_cobrar_monto_documento_bs">
<span<?= $Grid->monto_documento_bs->viewAttributes() ?>>
<?= $Grid->monto_documento_bs->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_monto_documento_bs" data-hidden="1" name="fview_cuentas_por_cobrargrid$x<?= $Grid->RowIndex ?>_monto_documento_bs" id="fview_cuentas_por_cobrargrid$x<?= $Grid->RowIndex ?>_monto_documento_bs" value="<?= HtmlEncode($Grid->monto_documento_bs->FormValue) ?>">
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_monto_documento_bs" data-hidden="1" data-old name="fview_cuentas_por_cobrargrid$o<?= $Grid->RowIndex ?>_monto_documento_bs" id="fview_cuentas_por_cobrargrid$o<?= $Grid->RowIndex ?>_monto_documento_bs" value="<?= HtmlEncode($Grid->monto_documento_bs->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->total_cobrado_bs->Visible) { // total_cobrado_bs ?>
        <td data-name="total_cobrado_bs"<?= $Grid->total_cobrado_bs->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_total_cobrado_bs" class="el_view_cuentas_por_cobrar_total_cobrado_bs">
<input type="<?= $Grid->total_cobrado_bs->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_total_cobrado_bs" id="x<?= $Grid->RowIndex ?>_total_cobrado_bs" data-table="view_cuentas_por_cobrar" data-field="x_total_cobrado_bs" value="<?= $Grid->total_cobrado_bs->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->total_cobrado_bs->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->total_cobrado_bs->formatPattern()) ?>"<?= $Grid->total_cobrado_bs->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->total_cobrado_bs->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_total_cobrado_bs" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_total_cobrado_bs" id="o<?= $Grid->RowIndex ?>_total_cobrado_bs" value="<?= HtmlEncode($Grid->total_cobrado_bs->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_total_cobrado_bs" class="el_view_cuentas_por_cobrar_total_cobrado_bs">
<input type="<?= $Grid->total_cobrado_bs->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_total_cobrado_bs" id="x<?= $Grid->RowIndex ?>_total_cobrado_bs" data-table="view_cuentas_por_cobrar" data-field="x_total_cobrado_bs" value="<?= $Grid->total_cobrado_bs->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->total_cobrado_bs->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->total_cobrado_bs->formatPattern()) ?>"<?= $Grid->total_cobrado_bs->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->total_cobrado_bs->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_total_cobrado_bs" class="el_view_cuentas_por_cobrar_total_cobrado_bs">
<span<?= $Grid->total_cobrado_bs->viewAttributes() ?>>
<?= $Grid->total_cobrado_bs->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_total_cobrado_bs" data-hidden="1" name="fview_cuentas_por_cobrargrid$x<?= $Grid->RowIndex ?>_total_cobrado_bs" id="fview_cuentas_por_cobrargrid$x<?= $Grid->RowIndex ?>_total_cobrado_bs" value="<?= HtmlEncode($Grid->total_cobrado_bs->FormValue) ?>">
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_total_cobrado_bs" data-hidden="1" data-old name="fview_cuentas_por_cobrargrid$o<?= $Grid->RowIndex ?>_total_cobrado_bs" id="fview_cuentas_por_cobrargrid$o<?= $Grid->RowIndex ?>_total_cobrado_bs" value="<?= HtmlEncode($Grid->total_cobrado_bs->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->saldo_bs->Visible) { // saldo_bs ?>
        <td data-name="saldo_bs"<?= $Grid->saldo_bs->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_saldo_bs" class="el_view_cuentas_por_cobrar_saldo_bs">
<input type="<?= $Grid->saldo_bs->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_saldo_bs" id="x<?= $Grid->RowIndex ?>_saldo_bs" data-table="view_cuentas_por_cobrar" data-field="x_saldo_bs" value="<?= $Grid->saldo_bs->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->saldo_bs->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->saldo_bs->formatPattern()) ?>"<?= $Grid->saldo_bs->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->saldo_bs->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_saldo_bs" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_saldo_bs" id="o<?= $Grid->RowIndex ?>_saldo_bs" value="<?= HtmlEncode($Grid->saldo_bs->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_saldo_bs" class="el_view_cuentas_por_cobrar_saldo_bs">
<input type="<?= $Grid->saldo_bs->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_saldo_bs" id="x<?= $Grid->RowIndex ?>_saldo_bs" data-table="view_cuentas_por_cobrar" data-field="x_saldo_bs" value="<?= $Grid->saldo_bs->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->saldo_bs->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->saldo_bs->formatPattern()) ?>"<?= $Grid->saldo_bs->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->saldo_bs->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_saldo_bs" class="el_view_cuentas_por_cobrar_saldo_bs">
<span<?= $Grid->saldo_bs->viewAttributes() ?>>
<?= $Grid->saldo_bs->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_saldo_bs" data-hidden="1" name="fview_cuentas_por_cobrargrid$x<?= $Grid->RowIndex ?>_saldo_bs" id="fview_cuentas_por_cobrargrid$x<?= $Grid->RowIndex ?>_saldo_bs" value="<?= HtmlEncode($Grid->saldo_bs->FormValue) ?>">
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_saldo_bs" data-hidden="1" data-old name="fview_cuentas_por_cobrargrid$o<?= $Grid->RowIndex ?>_saldo_bs" id="fview_cuentas_por_cobrargrid$o<?= $Grid->RowIndex ?>_saldo_bs" value="<?= HtmlEncode($Grid->saldo_bs->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->dias_vencido->Visible) { // dias_vencido ?>
        <td data-name="dias_vencido"<?= $Grid->dias_vencido->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_dias_vencido" class="el_view_cuentas_por_cobrar_dias_vencido">
<input type="<?= $Grid->dias_vencido->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_dias_vencido" id="x<?= $Grid->RowIndex ?>_dias_vencido" data-table="view_cuentas_por_cobrar" data-field="x_dias_vencido" value="<?= $Grid->dias_vencido->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->dias_vencido->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->dias_vencido->formatPattern()) ?>"<?= $Grid->dias_vencido->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->dias_vencido->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_dias_vencido" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_dias_vencido" id="o<?= $Grid->RowIndex ?>_dias_vencido" value="<?= HtmlEncode($Grid->dias_vencido->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_dias_vencido" class="el_view_cuentas_por_cobrar_dias_vencido">
<input type="<?= $Grid->dias_vencido->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_dias_vencido" id="x<?= $Grid->RowIndex ?>_dias_vencido" data-table="view_cuentas_por_cobrar" data-field="x_dias_vencido" value="<?= $Grid->dias_vencido->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->dias_vencido->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->dias_vencido->formatPattern()) ?>"<?= $Grid->dias_vencido->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->dias_vencido->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_dias_vencido" class="el_view_cuentas_por_cobrar_dias_vencido">
<span<?= $Grid->dias_vencido->viewAttributes() ?>>
<?= $Grid->dias_vencido->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_dias_vencido" data-hidden="1" name="fview_cuentas_por_cobrargrid$x<?= $Grid->RowIndex ?>_dias_vencido" id="fview_cuentas_por_cobrargrid$x<?= $Grid->RowIndex ?>_dias_vencido" value="<?= HtmlEncode($Grid->dias_vencido->FormValue) ?>">
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_dias_vencido" data-hidden="1" data-old name="fview_cuentas_por_cobrargrid$o<?= $Grid->RowIndex ?>_dias_vencido" id="fview_cuentas_por_cobrargrid$o<?= $Grid->RowIndex ?>_dias_vencido" value="<?= HtmlEncode($Grid->dias_vencido->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->antiguedad->Visible) { // antiguedad ?>
        <td data-name="antiguedad"<?= $Grid->antiguedad->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_antiguedad" class="el_view_cuentas_por_cobrar_antiguedad">
<input type="<?= $Grid->antiguedad->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_antiguedad" id="x<?= $Grid->RowIndex ?>_antiguedad" data-table="view_cuentas_por_cobrar" data-field="x_antiguedad" value="<?= $Grid->antiguedad->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Grid->antiguedad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->antiguedad->formatPattern()) ?>"<?= $Grid->antiguedad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->antiguedad->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_antiguedad" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_antiguedad" id="o<?= $Grid->RowIndex ?>_antiguedad" value="<?= HtmlEncode($Grid->antiguedad->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_antiguedad" class="el_view_cuentas_por_cobrar_antiguedad">
<input type="<?= $Grid->antiguedad->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_antiguedad" id="x<?= $Grid->RowIndex ?>_antiguedad" data-table="view_cuentas_por_cobrar" data-field="x_antiguedad" value="<?= $Grid->antiguedad->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Grid->antiguedad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->antiguedad->formatPattern()) ?>"<?= $Grid->antiguedad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->antiguedad->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_view_cuentas_por_cobrar_antiguedad" class="el_view_cuentas_por_cobrar_antiguedad">
<span<?= $Grid->antiguedad->viewAttributes() ?>>
<?= $Grid->antiguedad->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_antiguedad" data-hidden="1" name="fview_cuentas_por_cobrargrid$x<?= $Grid->RowIndex ?>_antiguedad" id="fview_cuentas_por_cobrargrid$x<?= $Grid->RowIndex ?>_antiguedad" value="<?= HtmlEncode($Grid->antiguedad->FormValue) ?>">
<input type="hidden" data-table="view_cuentas_por_cobrar" data-field="x_antiguedad" data-hidden="1" data-old name="fview_cuentas_por_cobrargrid$o<?= $Grid->RowIndex ?>_antiguedad" id="fview_cuentas_por_cobrargrid$o<?= $Grid->RowIndex ?>_antiguedad" value="<?= HtmlEncode($Grid->antiguedad->OldValue) ?>">
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
loadjs.ready(["fview_cuentas_por_cobrargrid","load"], () => fview_cuentas_por_cobrargrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="fview_cuentas_por_cobrargrid">
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
    ew.addEventHandlers("view_cuentas_por_cobrar");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
