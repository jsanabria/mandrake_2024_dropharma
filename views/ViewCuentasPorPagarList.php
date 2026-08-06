<?php

namespace PHPMaker2024\mandrake;

// Page object
$ViewCuentasPorPagarList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { view_cuentas_por_pagar: currentTable } });
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
<?php if (!$Page->isExport() || Config("EXPORT_MASTER_RECORD") && $Page->isExport("print")) { ?>
<?php
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "view_cuentas_por_pagar_resumen") {
    if ($Page->MasterRecordExists) {
        include_once "views/ViewCuentasPorPagarResumenMaster.php";
    }
}
?>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<form name="fview_cuentas_por_pagarsrch" id="fview_cuentas_por_pagarsrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" autocomplete="off">
<div id="fview_cuentas_por_pagarsrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { view_cuentas_por_pagar: currentTable } });
var currentForm;
var fview_cuentas_por_pagarsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fview_cuentas_por_pagarsrch")
        .setPageId("list")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

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
<div class="row mb-0">
    <div class="col-sm-auto px-0 pe-sm-2">
        <div class="ew-basic-search input-group">
            <input type="search" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control ew-basic-search-keyword" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>" aria-label="<?= HtmlEncode($Language->phrase("Search")) ?>">
            <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" class="ew-basic-search-type" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
            <button type="button" data-bs-toggle="dropdown" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false">
                <span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fview_cuentas_por_pagarsrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fview_cuentas_por_pagarsrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fview_cuentas_por_pagarsrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fview_cuentas_por_pagarsrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<input type="hidden" name="t" value="view_cuentas_por_pagar">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "view_cuentas_por_pagar_resumen" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="view_cuentas_por_pagar_resumen">
<input type="hidden" name="fk_proveedor" value="<?= HtmlEncode($Page->proveedor->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_view_cuentas_por_pagar" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_view_cuentas_por_pagarlist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Page->proveedor->Visible) { // proveedor ?>
        <th data-name="proveedor" class="<?= $Page->proveedor->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_proveedor" class="view_cuentas_por_pagar_proveedor"><?= $Page->renderFieldHeader($Page->proveedor) ?></div></th>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <th data-name="tipo_documento" class="<?= $Page->tipo_documento->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_tipo_documento" class="view_cuentas_por_pagar_tipo_documento"><?= $Page->renderFieldHeader($Page->tipo_documento) ?></div></th>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
        <th data-name="documento" class="<?= $Page->documento->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_documento" class="view_cuentas_por_pagar_documento"><?= $Page->renderFieldHeader($Page->documento) ?></div></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th data-name="fecha" class="<?= $Page->fecha->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_fecha" class="view_cuentas_por_pagar_fecha"><?= $Page->renderFieldHeader($Page->fecha) ?></div></th>
<?php } ?>
<?php if ($Page->fecha_ultimo_pago->Visible) { // fecha_ultimo_pago ?>
        <th data-name="fecha_ultimo_pago" class="<?= $Page->fecha_ultimo_pago->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_fecha_ultimo_pago" class="view_cuentas_por_pagar_fecha_ultimo_pago"><?= $Page->renderFieldHeader($Page->fecha_ultimo_pago) ?></div></th>
<?php } ?>
<?php if ($Page->monto_documento_bs->Visible) { // monto_documento_bs ?>
        <th data-name="monto_documento_bs" class="<?= $Page->monto_documento_bs->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_monto_documento_bs" class="view_cuentas_por_pagar_monto_documento_bs"><?= $Page->renderFieldHeader($Page->monto_documento_bs) ?></div></th>
<?php } ?>
<?php if ($Page->total_pagado_bs->Visible) { // total_pagado_bs ?>
        <th data-name="total_pagado_bs" class="<?= $Page->total_pagado_bs->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_total_pagado_bs" class="view_cuentas_por_pagar_total_pagado_bs"><?= $Page->renderFieldHeader($Page->total_pagado_bs) ?></div></th>
<?php } ?>
<?php if ($Page->saldo_bs->Visible) { // saldo_bs ?>
        <th data-name="saldo_bs" class="<?= $Page->saldo_bs->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_saldo_bs" class="view_cuentas_por_pagar_saldo_bs"><?= $Page->renderFieldHeader($Page->saldo_bs) ?></div></th>
<?php } ?>
<?php if ($Page->antiguedad->Visible) { // antiguedad ?>
        <th data-name="antiguedad" class="<?= $Page->antiguedad->headerCellClass() ?>"><div id="elh_view_cuentas_por_pagar_antiguedad" class="view_cuentas_por_pagar_antiguedad"><?= $Page->renderFieldHeader($Page->antiguedad) ?></div></th>
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
    <?php if ($Page->proveedor->Visible) { // proveedor ?>
        <td data-name="proveedor"<?= $Page->proveedor->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cuentas_por_pagar_proveedor" class="el_view_cuentas_por_pagar_proveedor">
<span<?= $Page->proveedor->viewAttributes() ?>>
<?= $Page->proveedor->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <td data-name="tipo_documento"<?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cuentas_por_pagar_tipo_documento" class="el_view_cuentas_por_pagar_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->documento->Visible) { // documento ?>
        <td data-name="documento"<?= $Page->documento->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cuentas_por_pagar_documento" class="el_view_cuentas_por_pagar_documento">
<span<?= $Page->documento->viewAttributes() ?>>
<?= $Page->documento->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->fecha->Visible) { // fecha ?>
        <td data-name="fecha"<?= $Page->fecha->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cuentas_por_pagar_fecha" class="el_view_cuentas_por_pagar_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->fecha_ultimo_pago->Visible) { // fecha_ultimo_pago ?>
        <td data-name="fecha_ultimo_pago"<?= $Page->fecha_ultimo_pago->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cuentas_por_pagar_fecha_ultimo_pago" class="el_view_cuentas_por_pagar_fecha_ultimo_pago">
<span<?= $Page->fecha_ultimo_pago->viewAttributes() ?>>
<?= $Page->fecha_ultimo_pago->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->monto_documento_bs->Visible) { // monto_documento_bs ?>
        <td data-name="monto_documento_bs"<?= $Page->monto_documento_bs->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cuentas_por_pagar_monto_documento_bs" class="el_view_cuentas_por_pagar_monto_documento_bs">
<span<?= $Page->monto_documento_bs->viewAttributes() ?>>
<?= $Page->monto_documento_bs->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->total_pagado_bs->Visible) { // total_pagado_bs ?>
        <td data-name="total_pagado_bs"<?= $Page->total_pagado_bs->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cuentas_por_pagar_total_pagado_bs" class="el_view_cuentas_por_pagar_total_pagado_bs">
<span<?= $Page->total_pagado_bs->viewAttributes() ?>>
<?= $Page->total_pagado_bs->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->saldo_bs->Visible) { // saldo_bs ?>
        <td data-name="saldo_bs"<?= $Page->saldo_bs->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cuentas_por_pagar_saldo_bs" class="el_view_cuentas_por_pagar_saldo_bs">
<span<?= $Page->saldo_bs->viewAttributes() ?>>
<?= $Page->saldo_bs->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->antiguedad->Visible) { // antiguedad ?>
        <td data-name="antiguedad"<?= $Page->antiguedad->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cuentas_por_pagar_antiguedad" class="el_view_cuentas_por_pagar_antiguedad">
<span<?= $Page->antiguedad->viewAttributes() ?>>
<?= $Page->antiguedad->getViewValue() ?></span>
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
    ew.addEventHandlers("view_cuentas_por_pagar");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
