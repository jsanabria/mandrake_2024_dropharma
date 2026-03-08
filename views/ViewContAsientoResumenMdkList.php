<?php

namespace PHPMaker2024\mandrake;

// Page object
$ViewContAsientoResumenMdkList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { view_cont_asiento_resumen_mdk: currentTable } });
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
ew.PREVIEW || ew.ready("head", ew.PATH_BASE + "js/preview.js?v=24.16.0", "preview");
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
<?php if (!$Page->IsModal) { ?>
<form name="fview_cont_asiento_resumen_mdksrch" id="fview_cont_asiento_resumen_mdksrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" autocomplete="off">
<div id="fview_cont_asiento_resumen_mdksrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { view_cont_asiento_resumen_mdk: currentTable } });
var currentForm;
var fview_cont_asiento_resumen_mdksrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fview_cont_asiento_resumen_mdksrch")
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fview_cont_asiento_resumen_mdksrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fview_cont_asiento_resumen_mdksrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fview_cont_asiento_resumen_mdksrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fview_cont_asiento_resumen_mdksrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<input type="hidden" name="t" value="view_cont_asiento_resumen_mdk">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_view_cont_asiento_resumen_mdk" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_view_cont_asiento_resumen_mdklist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Page->id->Visible) { // id ?>
        <th data-name="id" class="<?= $Page->id->headerCellClass() ?>"><div id="elh_view_cont_asiento_resumen_mdk_id" class="view_cont_asiento_resumen_mdk_id"><?= $Page->renderFieldHeader($Page->id) ?></div></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th data-name="fecha" class="<?= $Page->fecha->headerCellClass() ?>"><div id="elh_view_cont_asiento_resumen_mdk_fecha" class="view_cont_asiento_resumen_mdk_fecha"><?= $Page->renderFieldHeader($Page->fecha) ?></div></th>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
        <th data-name="referencia" class="<?= $Page->referencia->headerCellClass() ?>"><div id="elh_view_cont_asiento_resumen_mdk_referencia" class="view_cont_asiento_resumen_mdk_referencia"><?= $Page->renderFieldHeader($Page->referencia) ?></div></th>
<?php } ?>
<?php if ($Page->modulo_origen->Visible) { // modulo_origen ?>
        <th data-name="modulo_origen" class="<?= $Page->modulo_origen->headerCellClass() ?>"><div id="elh_view_cont_asiento_resumen_mdk_modulo_origen" class="view_cont_asiento_resumen_mdk_modulo_origen"><?= $Page->renderFieldHeader($Page->modulo_origen) ?></div></th>
<?php } ?>
<?php if ($Page->origen_id->Visible) { // origen_id ?>
        <th data-name="origen_id" class="<?= $Page->origen_id->headerCellClass() ?>"><div id="elh_view_cont_asiento_resumen_mdk_origen_id" class="view_cont_asiento_resumen_mdk_origen_id"><?= $Page->renderFieldHeader($Page->origen_id) ?></div></th>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
        <th data-name="descripcion" class="<?= $Page->descripcion->headerCellClass() ?>"><div id="elh_view_cont_asiento_resumen_mdk_descripcion" class="view_cont_asiento_resumen_mdk_descripcion"><?= $Page->renderFieldHeader($Page->descripcion) ?></div></th>
<?php } ?>
<?php if ($Page->anulado->Visible) { // anulado ?>
        <th data-name="anulado" class="<?= $Page->anulado->headerCellClass() ?>"><div id="elh_view_cont_asiento_resumen_mdk_anulado" class="view_cont_asiento_resumen_mdk_anulado"><?= $Page->renderFieldHeader($Page->anulado) ?></div></th>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <th data-name="created_at" class="<?= $Page->created_at->headerCellClass() ?>"><div id="elh_view_cont_asiento_resumen_mdk_created_at" class="view_cont_asiento_resumen_mdk_created_at"><?= $Page->renderFieldHeader($Page->created_at) ?></div></th>
<?php } ?>
<?php if ($Page->total_debe_bs->Visible) { // total_debe_bs ?>
        <th data-name="total_debe_bs" class="<?= $Page->total_debe_bs->headerCellClass() ?>"><div id="elh_view_cont_asiento_resumen_mdk_total_debe_bs" class="view_cont_asiento_resumen_mdk_total_debe_bs"><?= $Page->renderFieldHeader($Page->total_debe_bs) ?></div></th>
<?php } ?>
<?php if ($Page->total_haber_bs->Visible) { // total_haber_bs ?>
        <th data-name="total_haber_bs" class="<?= $Page->total_haber_bs->headerCellClass() ?>"><div id="elh_view_cont_asiento_resumen_mdk_total_haber_bs" class="view_cont_asiento_resumen_mdk_total_haber_bs"><?= $Page->renderFieldHeader($Page->total_haber_bs) ?></div></th>
<?php } ?>
<?php if ($Page->diferencia_bs->Visible) { // diferencia_bs ?>
        <th data-name="diferencia_bs" class="<?= $Page->diferencia_bs->headerCellClass() ?>"><div id="elh_view_cont_asiento_resumen_mdk_diferencia_bs" class="view_cont_asiento_resumen_mdk_diferencia_bs"><?= $Page->renderFieldHeader($Page->diferencia_bs) ?></div></th>
<?php } ?>
<?php if ($Page->estado_cuadre->Visible) { // estado_cuadre ?>
        <th data-name="estado_cuadre" class="<?= $Page->estado_cuadre->headerCellClass() ?>"><div id="elh_view_cont_asiento_resumen_mdk_estado_cuadre" class="view_cont_asiento_resumen_mdk_estado_cuadre"><?= $Page->renderFieldHeader($Page->estado_cuadre) ?></div></th>
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
    <?php if ($Page->id->Visible) { // id ?>
        <td data-name="id"<?= $Page->id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cont_asiento_resumen_mdk_id" class="el_view_cont_asiento_resumen_mdk_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->fecha->Visible) { // fecha ?>
        <td data-name="fecha"<?= $Page->fecha->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cont_asiento_resumen_mdk_fecha" class="el_view_cont_asiento_resumen_mdk_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->referencia->Visible) { // referencia ?>
        <td data-name="referencia"<?= $Page->referencia->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cont_asiento_resumen_mdk_referencia" class="el_view_cont_asiento_resumen_mdk_referencia">
<span<?= $Page->referencia->viewAttributes() ?>>
<?= $Page->referencia->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->modulo_origen->Visible) { // modulo_origen ?>
        <td data-name="modulo_origen"<?= $Page->modulo_origen->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cont_asiento_resumen_mdk_modulo_origen" class="el_view_cont_asiento_resumen_mdk_modulo_origen">
<span<?= $Page->modulo_origen->viewAttributes() ?>>
<?= $Page->modulo_origen->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->origen_id->Visible) { // origen_id ?>
        <td data-name="origen_id"<?= $Page->origen_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cont_asiento_resumen_mdk_origen_id" class="el_view_cont_asiento_resumen_mdk_origen_id">
<span<?= $Page->origen_id->viewAttributes() ?>>
<?= $Page->origen_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->descripcion->Visible) { // descripcion ?>
        <td data-name="descripcion"<?= $Page->descripcion->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cont_asiento_resumen_mdk_descripcion" class="el_view_cont_asiento_resumen_mdk_descripcion">
<span<?= $Page->descripcion->viewAttributes() ?>>
<?= $Page->descripcion->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->anulado->Visible) { // anulado ?>
        <td data-name="anulado"<?= $Page->anulado->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cont_asiento_resumen_mdk_anulado" class="el_view_cont_asiento_resumen_mdk_anulado">
<span<?= $Page->anulado->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->anulado->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->created_at->Visible) { // created_at ?>
        <td data-name="created_at"<?= $Page->created_at->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cont_asiento_resumen_mdk_created_at" class="el_view_cont_asiento_resumen_mdk_created_at">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->total_debe_bs->Visible) { // total_debe_bs ?>
        <td data-name="total_debe_bs"<?= $Page->total_debe_bs->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cont_asiento_resumen_mdk_total_debe_bs" class="el_view_cont_asiento_resumen_mdk_total_debe_bs">
<span<?= $Page->total_debe_bs->viewAttributes() ?>>
<?= $Page->total_debe_bs->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->total_haber_bs->Visible) { // total_haber_bs ?>
        <td data-name="total_haber_bs"<?= $Page->total_haber_bs->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cont_asiento_resumen_mdk_total_haber_bs" class="el_view_cont_asiento_resumen_mdk_total_haber_bs">
<span<?= $Page->total_haber_bs->viewAttributes() ?>>
<?= $Page->total_haber_bs->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->diferencia_bs->Visible) { // diferencia_bs ?>
        <td data-name="diferencia_bs"<?= $Page->diferencia_bs->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cont_asiento_resumen_mdk_diferencia_bs" class="el_view_cont_asiento_resumen_mdk_diferencia_bs">
<span<?= $Page->diferencia_bs->viewAttributes() ?>>
<?= $Page->diferencia_bs->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->estado_cuadre->Visible) { // estado_cuadre ?>
        <td data-name="estado_cuadre"<?= $Page->estado_cuadre->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_cont_asiento_resumen_mdk_estado_cuadre" class="el_view_cont_asiento_resumen_mdk_estado_cuadre">
<span<?= $Page->estado_cuadre->viewAttributes() ?>>
<?= $Page->estado_cuadre->getViewValue() ?></span>
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
    ew.addEventHandlers("view_cont_asiento_resumen_mdk");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
