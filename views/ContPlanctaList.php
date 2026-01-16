<?php

namespace PHPMaker2024\mandrake;

// Page object
$ContPlanctaList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cont_plancta: currentTable } });
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
<form name="fcont_planctasrch" id="fcont_planctasrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" autocomplete="off">
<div id="fcont_planctasrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cont_plancta: currentTable } });
var currentForm;
var fcont_planctasrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fcont_planctasrch")
        .setPageId("list")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["clase", [], fields.clase.isInvalid],
            ["grupo", [], fields.grupo.isInvalid],
            ["cuenta", [], fields.cuenta.isInvalid],
            ["subcuenta", [], fields.subcuenta.isInvalid],
            ["clasificacion", [], fields.clasificacion.isInvalid],
            ["activa", [], fields.activa.isInvalid]
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
            "clasificacion": <?= $Page->clasificacion->toClientList($Page) ?>,
            "activa": <?= $Page->activa->toClientList($Page) ?>,
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
<?php if ($Page->clase->Visible) { // clase ?>
<?php
if (!$Page->clase->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_clase" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->clase->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_clase" class="ew-search-caption ew-label"><?= $Page->clase->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_clase" id="z_clase" value="=">
</div>
        </div>
        <div id="el_cont_plancta_clase" class="ew-search-field">
<input type="<?= $Page->clase->getInputTextType() ?>" name="x_clase" id="x_clase" data-table="cont_plancta" data-field="x_clase" value="<?= $Page->clase->EditValue ?>" size="6" maxlength="6" placeholder="<?= HtmlEncode($Page->clase->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->clase->formatPattern()) ?>"<?= $Page->clase->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->clase->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->grupo->Visible) { // grupo ?>
<?php
if (!$Page->grupo->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_grupo" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->grupo->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_grupo" class="ew-search-caption ew-label"><?= $Page->grupo->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_grupo" id="z_grupo" value="=">
</div>
        </div>
        <div id="el_cont_plancta_grupo" class="ew-search-field">
<input type="<?= $Page->grupo->getInputTextType() ?>" name="x_grupo" id="x_grupo" data-table="cont_plancta" data-field="x_grupo" value="<?= $Page->grupo->EditValue ?>" size="6" maxlength="6" placeholder="<?= HtmlEncode($Page->grupo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->grupo->formatPattern()) ?>"<?= $Page->grupo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->grupo->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
<?php
if (!$Page->cuenta->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_cuenta" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->cuenta->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_cuenta" class="ew-search-caption ew-label"><?= $Page->cuenta->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_cuenta" id="z_cuenta" value="=">
</div>
        </div>
        <div id="el_cont_plancta_cuenta" class="ew-search-field">
<input type="<?= $Page->cuenta->getInputTextType() ?>" name="x_cuenta" id="x_cuenta" data-table="cont_plancta" data-field="x_cuenta" value="<?= $Page->cuenta->EditValue ?>" size="6" maxlength="6" placeholder="<?= HtmlEncode($Page->cuenta->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cuenta->formatPattern()) ?>"<?= $Page->cuenta->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->cuenta->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->subcuenta->Visible) { // subcuenta ?>
<?php
if (!$Page->subcuenta->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_subcuenta" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->subcuenta->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_subcuenta" class="ew-search-caption ew-label"><?= $Page->subcuenta->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("ENDS WITH") ?>
<input type="hidden" name="z_subcuenta" id="z_subcuenta" value="ENDS WITH">
</div>
        </div>
        <div id="el_cont_plancta_subcuenta" class="ew-search-field">
<input type="<?= $Page->subcuenta->getInputTextType() ?>" name="x_subcuenta" id="x_subcuenta" data-table="cont_plancta" data-field="x_subcuenta" value="<?= $Page->subcuenta->EditValue ?>" size="6" maxlength="6" placeholder="<?= HtmlEncode($Page->subcuenta->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->subcuenta->formatPattern()) ?>"<?= $Page->subcuenta->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->subcuenta->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->clasificacion->Visible) { // clasificacion ?>
<?php
if (!$Page->clasificacion->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_clasificacion" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->clasificacion->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_clasificacion" class="ew-search-caption ew-label"><?= $Page->clasificacion->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_clasificacion" id="z_clasificacion" value="=">
</div>
        </div>
        <div id="el_cont_plancta_clasificacion" class="ew-search-field">
    <select
        id="x_clasificacion"
        name="x_clasificacion"
        class="form-select ew-select<?= $Page->clasificacion->isInvalidClass() ?>"
        <?php if (!$Page->clasificacion->IsNativeSelect) { ?>
        data-select2-id="fcont_planctasrch_x_clasificacion"
        <?php } ?>
        data-table="cont_plancta"
        data-field="x_clasificacion"
        data-value-separator="<?= $Page->clasificacion->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->clasificacion->getPlaceHolder()) ?>"
        <?= $Page->clasificacion->editAttributes() ?>>
        <?= $Page->clasificacion->selectOptionListHtml("x_clasificacion") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->clasificacion->getErrorMessage(false) ?></div>
<?php if (!$Page->clasificacion->IsNativeSelect) { ?>
<script>
loadjs.ready("fcont_planctasrch", function() {
    var options = { name: "x_clasificacion", selectId: "fcont_planctasrch_x_clasificacion" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fcont_planctasrch.lists.clasificacion?.lookupOptions.length) {
        options.data = { id: "x_clasificacion", form: "fcont_planctasrch" };
    } else {
        options.ajax = { id: "x_clasificacion", form: "fcont_planctasrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.cont_plancta.fields.clasificacion.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->activa->Visible) { // activa ?>
<?php
if (!$Page->activa->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_activa" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->activa->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->activa->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_activa" id="z_activa" value="=">
</div>
        </div>
        <div id="el_cont_plancta_activa" class="ew-search-field">
<template id="tp_x_activa">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="cont_plancta" data-field="x_activa" name="x_activa" id="x_activa"<?= $Page->activa->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_activa" class="ew-item-list"></div>
<selection-list hidden
    id="x_activa"
    name="x_activa"
    value="<?= HtmlEncode($Page->activa->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_activa"
    data-target="dsl_x_activa"
    data-repeatcolumn="5"
    class="form-control<?= $Page->activa->isInvalidClass() ?>"
    data-table="cont_plancta"
    data-field="x_activa"
    data-value-separator="<?= $Page->activa->displayValueSeparatorAttribute() ?>"
    <?= $Page->activa->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->activa->getErrorMessage(false) ?></div>
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fcont_planctasrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fcont_planctasrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fcont_planctasrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fcont_planctasrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<input type="hidden" name="t" value="cont_plancta">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_cont_plancta" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_cont_planctalist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Page->clase->Visible) { // clase ?>
        <th data-name="clase" class="<?= $Page->clase->headerCellClass() ?>"><div id="elh_cont_plancta_clase" class="cont_plancta_clase"><?= $Page->renderFieldHeader($Page->clase) ?></div></th>
<?php } ?>
<?php if ($Page->grupo->Visible) { // grupo ?>
        <th data-name="grupo" class="<?= $Page->grupo->headerCellClass() ?>"><div id="elh_cont_plancta_grupo" class="cont_plancta_grupo"><?= $Page->renderFieldHeader($Page->grupo) ?></div></th>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
        <th data-name="cuenta" class="<?= $Page->cuenta->headerCellClass() ?>"><div id="elh_cont_plancta_cuenta" class="cont_plancta_cuenta"><?= $Page->renderFieldHeader($Page->cuenta) ?></div></th>
<?php } ?>
<?php if ($Page->subcuenta->Visible) { // subcuenta ?>
        <th data-name="subcuenta" class="<?= $Page->subcuenta->headerCellClass() ?>"><div id="elh_cont_plancta_subcuenta" class="cont_plancta_subcuenta"><?= $Page->renderFieldHeader($Page->subcuenta) ?></div></th>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
        <th data-name="descripcion" class="<?= $Page->descripcion->headerCellClass() ?>"><div id="elh_cont_plancta_descripcion" class="cont_plancta_descripcion"><?= $Page->renderFieldHeader($Page->descripcion) ?></div></th>
<?php } ?>
<?php if ($Page->clasificacion->Visible) { // clasificacion ?>
        <th data-name="clasificacion" class="<?= $Page->clasificacion->headerCellClass() ?>"><div id="elh_cont_plancta_clasificacion" class="cont_plancta_clasificacion"><?= $Page->renderFieldHeader($Page->clasificacion) ?></div></th>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
        <th data-name="moneda" class="<?= $Page->moneda->headerCellClass() ?>"><div id="elh_cont_plancta_moneda" class="cont_plancta_moneda"><?= $Page->renderFieldHeader($Page->moneda) ?></div></th>
<?php } ?>
<?php if ($Page->activa->Visible) { // activa ?>
        <th data-name="activa" class="<?= $Page->activa->headerCellClass() ?>"><div id="elh_cont_plancta_activa" class="cont_plancta_activa"><?= $Page->renderFieldHeader($Page->activa) ?></div></th>
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
    <?php if ($Page->clase->Visible) { // clase ?>
        <td data-name="clase"<?= $Page->clase->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cont_plancta_clase" class="el_cont_plancta_clase">
<span<?= $Page->clase->viewAttributes() ?>>
<?= $Page->clase->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->grupo->Visible) { // grupo ?>
        <td data-name="grupo"<?= $Page->grupo->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cont_plancta_grupo" class="el_cont_plancta_grupo">
<span<?= $Page->grupo->viewAttributes() ?>>
<?= $Page->grupo->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->cuenta->Visible) { // cuenta ?>
        <td data-name="cuenta"<?= $Page->cuenta->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cont_plancta_cuenta" class="el_cont_plancta_cuenta">
<span<?= $Page->cuenta->viewAttributes() ?>>
<?= $Page->cuenta->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->subcuenta->Visible) { // subcuenta ?>
        <td data-name="subcuenta"<?= $Page->subcuenta->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cont_plancta_subcuenta" class="el_cont_plancta_subcuenta">
<span<?= $Page->subcuenta->viewAttributes() ?>>
<?= $Page->subcuenta->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->descripcion->Visible) { // descripcion ?>
        <td data-name="descripcion"<?= $Page->descripcion->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cont_plancta_descripcion" class="el_cont_plancta_descripcion">
<span<?= $Page->descripcion->viewAttributes() ?>>
<?= $Page->descripcion->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->clasificacion->Visible) { // clasificacion ?>
        <td data-name="clasificacion"<?= $Page->clasificacion->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cont_plancta_clasificacion" class="el_cont_plancta_clasificacion">
<span<?= $Page->clasificacion->viewAttributes() ?>>
<?= $Page->clasificacion->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->moneda->Visible) { // moneda ?>
        <td data-name="moneda"<?= $Page->moneda->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cont_plancta_moneda" class="el_cont_plancta_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->activa->Visible) { // activa ?>
        <td data-name="activa"<?= $Page->activa->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cont_plancta_activa" class="el_cont_plancta_activa">
<span<?= $Page->activa->viewAttributes() ?>>
<?= $Page->activa->getViewValue() ?></span>
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
    ew.addEventHandlers("cont_plancta");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
