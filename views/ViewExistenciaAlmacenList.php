<?php

namespace PHPMaker2024\mandrake;

// Page object
$ViewExistenciaAlmacenList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { view_existencia_almacen: currentTable } });
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
<?php if ($Page->ShowCurrentFilter) { ?>
<?php $Page->showFilterList() ?>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<form name="fview_existencia_almacensrch" id="fview_existencia_almacensrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" autocomplete="off">
<div id="fview_existencia_almacensrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { view_existencia_almacen: currentTable } });
var currentForm;
var fview_existencia_almacensrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fview_existencia_almacensrch")
        .setPageId("list")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["codalm", [], fields.codalm.isInvalid],
            ["codfab", [], fields.codfab.isInvalid]
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
            "codalm": <?= $Page->codalm->toClientList($Page) ?>,
            "codfab": <?= $Page->codfab->toClientList($Page) ?>,
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
<?php if ($Page->codalm->Visible) { // codalm ?>
<?php
if (!$Page->codalm->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_codalm" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->codalm->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_codalm" class="ew-search-caption ew-label"><?= $Page->codalm->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_codalm" id="z_codalm" value="=">
</div>
        </div>
        <div id="el_view_existencia_almacen_codalm" class="ew-search-field">
    <select
        id="x_codalm"
        name="x_codalm"
        class="form-select ew-select<?= $Page->codalm->isInvalidClass() ?>"
        <?php if (!$Page->codalm->IsNativeSelect) { ?>
        data-select2-id="fview_existencia_almacensrch_x_codalm"
        <?php } ?>
        data-table="view_existencia_almacen"
        data-field="x_codalm"
        data-value-separator="<?= $Page->codalm->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->codalm->getPlaceHolder()) ?>"
        <?= $Page->codalm->editAttributes() ?>>
        <?= $Page->codalm->selectOptionListHtml("x_codalm") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->codalm->getErrorMessage(false) ?></div>
<?= $Page->codalm->Lookup->getParamTag($Page, "p_x_codalm") ?>
<?php if (!$Page->codalm->IsNativeSelect) { ?>
<script>
loadjs.ready("fview_existencia_almacensrch", function() {
    var options = { name: "x_codalm", selectId: "fview_existencia_almacensrch_x_codalm" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fview_existencia_almacensrch.lists.codalm?.lookupOptions.length) {
        options.data = { id: "x_codalm", form: "fview_existencia_almacensrch" };
    } else {
        options.ajax = { id: "x_codalm", form: "fview_existencia_almacensrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.view_existencia_almacen.fields.codalm.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->codfab->Visible) { // codfab ?>
<?php
if (!$Page->codfab->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_codfab" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->codfab->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_codfab" class="ew-search-caption ew-label"><?= $Page->codfab->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_codfab" id="z_codfab" value="=">
</div>
        </div>
        <div id="el_view_existencia_almacen_codfab" class="ew-search-field">
    <select
        id="x_codfab"
        name="x_codfab"
        class="form-select ew-select<?= $Page->codfab->isInvalidClass() ?>"
        <?php if (!$Page->codfab->IsNativeSelect) { ?>
        data-select2-id="fview_existencia_almacensrch_x_codfab"
        <?php } ?>
        data-table="view_existencia_almacen"
        data-field="x_codfab"
        data-value-separator="<?= $Page->codfab->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->codfab->getPlaceHolder()) ?>"
        <?= $Page->codfab->editAttributes() ?>>
        <?= $Page->codfab->selectOptionListHtml("x_codfab") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->codfab->getErrorMessage(false) ?></div>
<?= $Page->codfab->Lookup->getParamTag($Page, "p_x_codfab") ?>
<?php if (!$Page->codfab->IsNativeSelect) { ?>
<script>
loadjs.ready("fview_existencia_almacensrch", function() {
    var options = { name: "x_codfab", selectId: "fview_existencia_almacensrch_x_codfab" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fview_existencia_almacensrch.lists.codfab?.lookupOptions.length) {
        options.data = { id: "x_codfab", form: "fview_existencia_almacensrch" };
    } else {
        options.ajax = { id: "x_codfab", form: "fview_existencia_almacensrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.view_existencia_almacen.fields.codfab.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fview_existencia_almacensrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fview_existencia_almacensrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fview_existencia_almacensrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fview_existencia_almacensrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<input type="hidden" name="t" value="view_existencia_almacen">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_view_existencia_almacen" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_view_existencia_almacenlist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Page->codalm->Visible) { // codalm ?>
        <th data-name="codalm" class="<?= $Page->codalm->headerCellClass() ?>"><div id="elh_view_existencia_almacen_codalm" class="view_existencia_almacen_codalm"><?= $Page->renderFieldHeader($Page->codalm) ?></div></th>
<?php } ?>
<?php if ($Page->codfab->Visible) { // codfab ?>
        <th data-name="codfab" class="<?= $Page->codfab->headerCellClass() ?>"><div id="elh_view_existencia_almacen_codfab" class="view_existencia_almacen_codfab"><?= $Page->renderFieldHeader($Page->codfab) ?></div></th>
<?php } ?>
<?php if ($Page->nombre_comercial->Visible) { // nombre_comercial ?>
        <th data-name="nombre_comercial" class="<?= $Page->nombre_comercial->headerCellClass() ?>"><div id="elh_view_existencia_almacen_nombre_comercial" class="view_existencia_almacen_nombre_comercial"><?= $Page->renderFieldHeader($Page->nombre_comercial) ?></div></th>
<?php } ?>
<?php if ($Page->principio_activo->Visible) { // principio_activo ?>
        <th data-name="principio_activo" class="<?= $Page->principio_activo->headerCellClass() ?>"><div id="elh_view_existencia_almacen_principio_activo" class="view_existencia_almacen_principio_activo"><?= $Page->renderFieldHeader($Page->principio_activo) ?></div></th>
<?php } ?>
<?php if ($Page->presentacion->Visible) { // presentacion ?>
        <th data-name="presentacion" class="<?= $Page->presentacion->headerCellClass() ?>"><div id="elh_view_existencia_almacen_presentacion" class="view_existencia_almacen_presentacion"><?= $Page->renderFieldHeader($Page->presentacion) ?></div></th>
<?php } ?>
<?php if ($Page->lote->Visible) { // lote ?>
        <th data-name="lote" class="<?= $Page->lote->headerCellClass() ?>"><div id="elh_view_existencia_almacen_lote" class="view_existencia_almacen_lote"><?= $Page->renderFieldHeader($Page->lote) ?></div></th>
<?php } ?>
<?php if ($Page->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
        <th data-name="fecha_vencimiento" class="<?= $Page->fecha_vencimiento->headerCellClass() ?>"><div id="elh_view_existencia_almacen_fecha_vencimiento" class="view_existencia_almacen_fecha_vencimiento"><?= $Page->renderFieldHeader($Page->fecha_vencimiento) ?></div></th>
<?php } ?>
<?php if ($Page->cantidad->Visible) { // cantidad ?>
        <th data-name="cantidad" class="<?= $Page->cantidad->headerCellClass() ?>"><div id="elh_view_existencia_almacen_cantidad" class="view_existencia_almacen_cantidad"><?= $Page->renderFieldHeader($Page->cantidad) ?></div></th>
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
    <?php if ($Page->codalm->Visible) { // codalm ?>
        <td data-name="codalm"<?= $Page->codalm->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_existencia_almacen_codalm" class="el_view_existencia_almacen_codalm">
<span<?= $Page->codalm->viewAttributes() ?>>
<?= $Page->codalm->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->codfab->Visible) { // codfab ?>
        <td data-name="codfab"<?= $Page->codfab->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_existencia_almacen_codfab" class="el_view_existencia_almacen_codfab">
<span<?= $Page->codfab->viewAttributes() ?>>
<?= $Page->codfab->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->nombre_comercial->Visible) { // nombre_comercial ?>
        <td data-name="nombre_comercial"<?= $Page->nombre_comercial->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_existencia_almacen_nombre_comercial" class="el_view_existencia_almacen_nombre_comercial">
<span<?= $Page->nombre_comercial->viewAttributes() ?>>
<?= $Page->nombre_comercial->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->principio_activo->Visible) { // principio_activo ?>
        <td data-name="principio_activo"<?= $Page->principio_activo->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_existencia_almacen_principio_activo" class="el_view_existencia_almacen_principio_activo">
<span<?= $Page->principio_activo->viewAttributes() ?>>
<?= $Page->principio_activo->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->presentacion->Visible) { // presentacion ?>
        <td data-name="presentacion"<?= $Page->presentacion->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_existencia_almacen_presentacion" class="el_view_existencia_almacen_presentacion">
<span<?= $Page->presentacion->viewAttributes() ?>>
<?= $Page->presentacion->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->lote->Visible) { // lote ?>
        <td data-name="lote"<?= $Page->lote->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_existencia_almacen_lote" class="el_view_existencia_almacen_lote">
<span<?= $Page->lote->viewAttributes() ?>>
<?= $Page->lote->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
        <td data-name="fecha_vencimiento"<?= $Page->fecha_vencimiento->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_existencia_almacen_fecha_vencimiento" class="el_view_existencia_almacen_fecha_vencimiento">
<span<?= $Page->fecha_vencimiento->viewAttributes() ?>>
<?= $Page->fecha_vencimiento->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->cantidad->Visible) { // cantidad ?>
        <td data-name="cantidad"<?= $Page->cantidad->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_existencia_almacen_cantidad" class="el_view_existencia_almacen_cantidad">
<span<?= $Page->cantidad->viewAttributes() ?>>
<?= $Page->cantidad->getViewValue() ?></span>
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
    ew.addEventHandlers("view_existencia_almacen");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
