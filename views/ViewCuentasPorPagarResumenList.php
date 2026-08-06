<?php

namespace PHPMaker2024\mandrake;

// Page object
$ViewCuentasPorPagarResumenList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { view_cuentas_por_pagar_resumen: currentTable } });
var currentPageID = ew.PAGE_ID = "list";
var currentForm;
var <?= $Page->FormName ?>;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("<?= $Page->FormName ?>")
        .setPageId("list")
        .setSubmitWithFetch(<?= $Page->UseAjaxActions ? "true" : "false" ?>)
        .setFormKeyCountName("<?= $Page->FormKeyCountName ?>")
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<script>
ew.PREVIEW_SELECTOR ??= ".ew-preview-btn";
ew.PREVIEW_TYPE ??= "row";
ew.PREVIEW_NAV_STYLE ??= "tabs"; // tabs/pills/underline
ew.PREVIEW_MODAL_CLASS ??= "modal modal-fullscreen-sm-down";
ew.PREVIEW_ROW ??= true;
ew.PREVIEW_SINGLE_ROW ??= false;
ew.PREVIEW || ew.ready("head", ew.PATH_BASE + "js/preview.min.js?v=24.16.0", "preview");
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php if ($Page->TotalRecords > 0 && $Page->ExportOptions->visible()) { ?>
<?php $Page->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->ImportOptions->visible()) { ?>
<?php $Page->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->SearchOptions->visible()) { ?>
<?php $Page->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($Page->FilterOptions->visible()) { ?>
<?php $Page->FilterOptions->render("body") ?>
<?php } ?>
</div>
<?php } ?>
<?php if ($Page->ShowCurrentFilter) { ?>
<?php $Page->showFilterList() ?>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<form name="fview_cuentas_por_pagar_resumensrch" id="fview_cuentas_por_pagar_resumensrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" autocomplete="off">
<div id="fview_cuentas_por_pagar_resumensrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { view_cuentas_por_pagar_resumen: currentTable } });
var currentForm;
var fview_cuentas_por_pagar_resumensrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fview_cuentas_por_pagar_resumensrch")
        .setPageId("list")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["proveedor_rif", [], fields.proveedor_rif.isInvalid],
            ["proveedor_nombre", [], fields.proveedor_nombre.isInvalid]
        ])
        // Validate form
        .setValidate(
            async function () {
                if (!this.validateRequired)
                    return true; // Ignore validation
                let fobj = this.getForm();

                // Validate fields
                if (!this.validateFields())
                    return false;

                // Call Form_CustomValidate event
                if (!(await this.customValidate?.(fobj) ?? true)) {
                    this.focus();
                    return false;
                }
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
        })

        // Filters
        .setFilterList(<?= $Page->getFilterList() ?>)

        // Init search panel as collapsed
        .setInitSearchPanel(true)
        .build();
    window[form.id] = form;
    currentSearchForm = form;
    loadjs.done(form.id);
});
</script>
<input type="hidden" name="cmd" value="search">
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !($Page->CurrentAction && $Page->CurrentAction != "search") && $Page->hasSearchFields()) { ?>
<div class="ew-extended-search container-fluid ps-2">
<div class="row mb-0<?= ($Page->SearchFieldsPerRow > 0) ? " row-cols-sm-" . $Page->SearchFieldsPerRow : "" ?>">
<?php
// Render search row
$Page->RowType = RowType::SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->proveedor_rif->Visible) { // proveedor_rif ?>
<?php
if (!$Page->proveedor_rif->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_proveedor_rif" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->proveedor_rif->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_proveedor_rif" class="ew-search-caption ew-label"><?= $Page->proveedor_rif->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_proveedor_rif" id="z_proveedor_rif" value="LIKE">
</div>
        </div>
        <div id="el_view_cuentas_por_pagar_resumen_proveedor_rif" class="ew-search-field">
<input type="<?= $Page->proveedor_rif->getInputTextType() ?>" name="x_proveedor_rif" id="x_proveedor_rif" data-table="view_cuentas_por_pagar_resumen" data-field="x_proveedor_rif" value="<?= $Page->proveedor_rif->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->proveedor_rif->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->proveedor_rif->formatPattern()) ?>"<?= $Page->proveedor_rif->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->proveedor_rif->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->proveedor_nombre->Visible) { // proveedor_nombre ?>
<?php
if (!$Page->proveedor_nombre->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_proveedor_nombre" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->proveedor_nombre->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_proveedor_nombre" class="ew-search-caption ew-label"><?= $Page->proveedor_nombre->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_proveedor_nombre" id="z_proveedor_nombre" value="LIKE">
</div>
        </div>
        <div id="el_view_cuentas_por_pagar_resumen_proveedor_nombre" class="ew-search-field">
<input type="<?= $Page->proveedor_nombre->getInputTextType() ?>" name="x_proveedor_nombre" id="x_proveedor_nombre" data-table="view_cuentas_por_pagar_resumen" data-field="x_proveedor_nombre" value="<?= $Page->proveedor_nombre->EditValue ?>" size="30" maxlength="80" placeholder="<?= HtmlEncode($Page->proveedor_nombre->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->proveedor_nombre->formatPattern()) ?>"<?= $Page->proveedor_nombre->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->proveedor_nombre->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
</div><!-- /.row -->
<div class="row mb-0">
    <div class="col-sm-auto px-0 pe-sm-2">
        <div class="ew-basic-search input-group">
            <input type="search" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control ew-basic-search-keyword" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>" aria-label="<?= HtmlEncode($Language->phrase("Search")) ?>">
            <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" class="ew-basic-search-type" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
            <button type="button" data-bs-toggle="dropdown" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false">
                <span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fview_cuentas_por_pagar_resumensrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fview_cuentas_por_pagar_resumensrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fview_cuentas_por_pagar_resumensrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fview_cuentas_por_pagar_resumensrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
            </div>
        </div>
    </div>
    <div class="col-sm-auto mb-3">
        <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
    </div>
</div>
</div><!-- /.ew-extended-search -->
<?php } ?>
<?php } ?>
</div><!-- /.ew-search-panel -->
</form>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="list<?= ($Page->TotalRecords == 0 && !$Page->isAdd()) ? " ew-no-record" : "" ?>">
<div id="ew-header-options">
<?php $Page->HeaderOptions?->render("body") ?>
</div>
<div id="ew-list">
<?php if ($Page->TotalRecords > 0 || $Page->CurrentAction) { ?>
<div class="card ew-card ew-grid<?= $Page->isAddOrEdit() ? " ew-grid-add-edit" : "" ?> <?= $Page->TableGridClass ?>">
<?php if (!$Page->isExport()) { ?>
<div class="card-header ew-grid-upper-panel">
<?php if (!$Page->isGridAdd() && !($Page->isGridEdit() && $Page->ModalGridEdit) && !$Page->isMultiEdit()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
</div>
<?php } ?>
<form name="<?= $Page->FormName ?>" id="<?= $Page->FormName ?>" class="ew-form ew-list-form" action="<?= $Page->PageAction ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="view_cuentas_por_pagar_resumen">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_view_cuentas_por_pagar_resumen" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_view_cuentas_por_pagar_resumenlist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Page->RowType = RowType::HEADER;

// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left");
?>
<?php if ($Page->proveedor_rif->Visible) { // proveedor_rif ?>
        <th data-name="proveedor_rif" class="<?= $Page->proveedor_rif->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_resumen_proveedor_rif" class="view_cuentas_por_pagar_resumen_proveedor_rif"><?= $Page->renderFieldHeader($Page->proveedor_rif) ?></div></th>
<?php } ?>
<?php if ($Page->proveedor_nombre->Visible) { // proveedor_nombre ?>
        <th data-name="proveedor_nombre" class="<?= $Page->proveedor_nombre->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_resumen_proveedor_nombre" class="view_cuentas_por_pagar_resumen_proveedor_nombre"><?= $Page->renderFieldHeader($Page->proveedor_nombre) ?></div></th>
<?php } ?>
<?php if ($Page->cantidad_documentos->Visible) { // cantidad_documentos ?>
        <th data-name="cantidad_documentos" class="<?= $Page->cantidad_documentos->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_resumen_cantidad_documentos" class="view_cuentas_por_pagar_resumen_cantidad_documentos"><?= $Page->renderFieldHeader($Page->cantidad_documentos) ?></div></th>
<?php } ?>
<?php if ($Page->documentos_pendientes->Visible) { // documentos_pendientes ?>
        <th data-name="documentos_pendientes" class="<?= $Page->documentos_pendientes->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_resumen_documentos_pendientes" class="view_cuentas_por_pagar_resumen_documentos_pendientes"><?= $Page->renderFieldHeader($Page->documentos_pendientes) ?></div></th>
<?php } ?>
<?php if ($Page->documentos_parciales->Visible) { // documentos_parciales ?>
        <th data-name="documentos_parciales" class="<?= $Page->documentos_parciales->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_resumen_documentos_parciales" class="view_cuentas_por_pagar_resumen_documentos_parciales"><?= $Page->renderFieldHeader($Page->documentos_parciales) ?></div></th>
<?php } ?>
<?php if ($Page->monto_documentos_bs->Visible) { // monto_documentos_bs ?>
        <th data-name="monto_documentos_bs" class="<?= $Page->monto_documentos_bs->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_resumen_monto_documentos_bs" class="view_cuentas_por_pagar_resumen_monto_documentos_bs"><?= $Page->renderFieldHeader($Page->monto_documentos_bs) ?></div></th>
<?php } ?>
<?php if ($Page->monto_documentos_usd->Visible) { // monto_documentos_usd ?>
        <th data-name="monto_documentos_usd" class="<?= $Page->monto_documentos_usd->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_resumen_monto_documentos_usd" class="view_cuentas_por_pagar_resumen_monto_documentos_usd"><?= $Page->renderFieldHeader($Page->monto_documentos_usd) ?></div></th>
<?php } ?>
<?php if ($Page->total_pagado_bs->Visible) { // total_pagado_bs ?>
        <th data-name="total_pagado_bs" class="<?= $Page->total_pagado_bs->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_resumen_total_pagado_bs" class="view_cuentas_por_pagar_resumen_total_pagado_bs"><?= $Page->renderFieldHeader($Page->total_pagado_bs) ?></div></th>
<?php } ?>
<?php if ($Page->total_pagado_usd->Visible) { // total_pagado_usd ?>
        <th data-name="total_pagado_usd" class="<?= $Page->total_pagado_usd->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_resumen_total_pagado_usd" class="view_cuentas_por_pagar_resumen_total_pagado_usd"><?= $Page->renderFieldHeader($Page->total_pagado_usd) ?></div></th>
<?php } ?>
<?php if ($Page->saldo_bs->Visible) { // saldo_bs ?>
        <th data-name="saldo_bs" class="<?= $Page->saldo_bs->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_resumen_saldo_bs" class="view_cuentas_por_pagar_resumen_saldo_bs"><?= $Page->renderFieldHeader($Page->saldo_bs) ?></div></th>
<?php } ?>
<?php if ($Page->saldo_usd->Visible) { // saldo_usd ?>
        <th data-name="saldo_usd" class="<?= $Page->saldo_usd->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_resumen_saldo_usd" class="view_cuentas_por_pagar_resumen_saldo_usd"><?= $Page->renderFieldHeader($Page->saldo_usd) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody data-page="<?= $Page->getPageNumber() ?>">
<?php
$Page->setupGrid();
$isInlineAddOrCopy = ($Page->isCopy() || $Page->isAdd());
while ($Page->RecordCount < $Page->StopRecord || $Page->RowIndex === '$rowindex$' || $isInlineAddOrCopy && $Page->RowIndex == 0) {
    if (
        $Page->CurrentRow !== false &&
        $Page->RowIndex !== '$rowindex$' &&
        (!$Page->isGridAdd() || $Page->CurrentMode == "copy") &&
        !($isInlineAddOrCopy && $Page->RowIndex == 0)
    ) {
        $Page->fetch();
    }
    $Page->RecordCount++;
    if ($Page->RecordCount >= $Page->StartRecord) {
        $Page->setupRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->proveedor_rif->Visible) { // proveedor_rif ?>
        <td data-name="proveedor_rif"<?= $Page->proveedor_rif->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cuentas_por_pagar_resumen_proveedor_rif" class="el_view_cuentas_por_pagar_resumen_proveedor_rif">
<span<?= $Page->proveedor_rif->viewAttributes() ?>>
<?= $Page->proveedor_rif->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->proveedor_nombre->Visible) { // proveedor_nombre ?>
        <td data-name="proveedor_nombre"<?= $Page->proveedor_nombre->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cuentas_por_pagar_resumen_proveedor_nombre" class="el_view_cuentas_por_pagar_resumen_proveedor_nombre">
<span<?= $Page->proveedor_nombre->viewAttributes() ?>>
<?= $Page->proveedor_nombre->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->cantidad_documentos->Visible) { // cantidad_documentos ?>
        <td data-name="cantidad_documentos"<?= $Page->cantidad_documentos->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cuentas_por_pagar_resumen_cantidad_documentos" class="el_view_cuentas_por_pagar_resumen_cantidad_documentos">
<span<?= $Page->cantidad_documentos->viewAttributes() ?>>
<?= $Page->cantidad_documentos->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->documentos_pendientes->Visible) { // documentos_pendientes ?>
        <td data-name="documentos_pendientes"<?= $Page->documentos_pendientes->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cuentas_por_pagar_resumen_documentos_pendientes" class="el_view_cuentas_por_pagar_resumen_documentos_pendientes">
<span<?= $Page->documentos_pendientes->viewAttributes() ?>>
<?= $Page->documentos_pendientes->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->documentos_parciales->Visible) { // documentos_parciales ?>
        <td data-name="documentos_parciales"<?= $Page->documentos_parciales->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cuentas_por_pagar_resumen_documentos_parciales" class="el_view_cuentas_por_pagar_resumen_documentos_parciales">
<span<?= $Page->documentos_parciales->viewAttributes() ?>>
<?= $Page->documentos_parciales->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->monto_documentos_bs->Visible) { // monto_documentos_bs ?>
        <td data-name="monto_documentos_bs"<?= $Page->monto_documentos_bs->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cuentas_por_pagar_resumen_monto_documentos_bs" class="el_view_cuentas_por_pagar_resumen_monto_documentos_bs">
<span<?= $Page->monto_documentos_bs->viewAttributes() ?>>
<?= $Page->monto_documentos_bs->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->monto_documentos_usd->Visible) { // monto_documentos_usd ?>
        <td data-name="monto_documentos_usd"<?= $Page->monto_documentos_usd->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cuentas_por_pagar_resumen_monto_documentos_usd" class="el_view_cuentas_por_pagar_resumen_monto_documentos_usd">
<span<?= $Page->monto_documentos_usd->viewAttributes() ?>>
<?= $Page->monto_documentos_usd->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->total_pagado_bs->Visible) { // total_pagado_bs ?>
        <td data-name="total_pagado_bs"<?= $Page->total_pagado_bs->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cuentas_por_pagar_resumen_total_pagado_bs" class="el_view_cuentas_por_pagar_resumen_total_pagado_bs">
<span<?= $Page->total_pagado_bs->viewAttributes() ?>>
<?= $Page->total_pagado_bs->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->total_pagado_usd->Visible) { // total_pagado_usd ?>
        <td data-name="total_pagado_usd"<?= $Page->total_pagado_usd->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cuentas_por_pagar_resumen_total_pagado_usd" class="el_view_cuentas_por_pagar_resumen_total_pagado_usd">
<span<?= $Page->total_pagado_usd->viewAttributes() ?>>
<?= $Page->total_pagado_usd->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->saldo_bs->Visible) { // saldo_bs ?>
        <td data-name="saldo_bs"<?= $Page->saldo_bs->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cuentas_por_pagar_resumen_saldo_bs" class="el_view_cuentas_por_pagar_resumen_saldo_bs">
<span<?= $Page->saldo_bs->viewAttributes() ?>>
<?= $Page->saldo_bs->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->saldo_usd->Visible) { // saldo_usd ?>
        <td data-name="saldo_usd"<?= $Page->saldo_usd->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cuentas_por_pagar_resumen_saldo_usd" class="el_view_cuentas_por_pagar_resumen_saldo_usd">
<span<?= $Page->saldo_usd->viewAttributes() ?>>
<?= $Page->saldo_usd->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php
    }

    // Reset for template row
    if ($Page->RowIndex === '$rowindex$') {
        $Page->RowIndex = 0;
    }
    // Reset inline add/copy row
    if (($Page->isCopy() || $Page->isAdd()) && $Page->RowIndex == 0) {
        $Page->RowIndex = 1;
    }
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if (!$Page->CurrentAction && !$Page->UseAjaxActions) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</form><!-- /.ew-list-form -->
<?php
// Close result set
$Page->Recordset?->free();
?>
<?php if (!$Page->isExport()) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php if (!$Page->isGridAdd() && !($Page->isGridEdit() && $Page->ModalGridEdit) && !$Page->isMultiEdit()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body", "bottom") ?>
</div>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } else { ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
</div>
<div id="ew-footer-options">
<?php $Page->FooterOptions?->render("body") ?>
</div>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("view_cuentas_por_pagar_resumen");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
