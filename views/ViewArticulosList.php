<?php

namespace PHPMaker2024\mandrake;

// Page object
$ViewArticulosList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { view_articulos: currentTable } });
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

        // Add fields
        .setFields([
            ["codigo", [fields.codigo.visible && fields.codigo.required ? ew.Validators.required(fields.codigo.caption) : null], fields.codigo.isInvalid],
            ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null], fields.fabricante.isInvalid],
            ["nombre_comercial", [fields.nombre_comercial.visible && fields.nombre_comercial.required ? ew.Validators.required(fields.nombre_comercial.caption) : null], fields.nombre_comercial.isInvalid],
            ["principio_activo", [fields.principio_activo.visible && fields.principio_activo.required ? ew.Validators.required(fields.principio_activo.caption) : null], fields.principio_activo.isInvalid],
            ["presentacion", [fields.presentacion.visible && fields.presentacion.required ? ew.Validators.required(fields.presentacion.caption) : null], fields.presentacion.isInvalid],
            ["cantidad_en_mano", [fields.cantidad_en_mano.visible && fields.cantidad_en_mano.required ? ew.Validators.required(fields.cantidad_en_mano.caption) : null], fields.cantidad_en_mano.isInvalid],
            ["ultimo_costo", [fields.ultimo_costo.visible && fields.ultimo_costo.required ? ew.Validators.required(fields.ultimo_costo.caption) : null, ew.Validators.float], fields.ultimo_costo.isInvalid]
        ])

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
            "fabricante": <?= $Page->fabricante->toClientList($Page) ?>,
        })
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
<form name="fview_articulossrch" id="fview_articulossrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" autocomplete="off">
<div id="fview_articulossrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { view_articulos: currentTable } });
var currentForm;
var fview_articulossrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fview_articulossrch")
        .setPageId("list")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["fabricante", [], fields.fabricante.isInvalid]
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
            "fabricante": <?= $Page->fabricante->toClientList($Page) ?>,
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
<?php if ($Page->fabricante->Visible) { // fabricante ?>
<?php
if (!$Page->fabricante->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_fabricante" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->fabricante->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_fabricante" class="ew-search-caption ew-label"><?= $Page->fabricante->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_fabricante" id="z_fabricante" value="=">
</div>
        </div>
        <div id="el_view_articulos_fabricante" class="ew-search-field">
    <select
        id="x_fabricante"
        name="x_fabricante"
        class="form-control ew-select<?= $Page->fabricante->isInvalidClass() ?>"
        data-select2-id="fview_articulossrch_x_fabricante"
        data-table="view_articulos"
        data-field="x_fabricante"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->fabricante->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->fabricante->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->fabricante->getPlaceHolder()) ?>"
        <?= $Page->fabricante->editAttributes() ?>>
        <?= $Page->fabricante->selectOptionListHtml("x_fabricante") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->fabricante->getErrorMessage(false) ?></div>
<?= $Page->fabricante->Lookup->getParamTag($Page, "p_x_fabricante") ?>
<script>
loadjs.ready("fview_articulossrch", function() {
    var options = { name: "x_fabricante", selectId: "fview_articulossrch_x_fabricante" };
    if (fview_articulossrch.lists.fabricante?.lookupOptions.length) {
        options.data = { id: "x_fabricante", form: "fview_articulossrch" };
    } else {
        options.ajax = { id: "x_fabricante", form: "fview_articulossrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.view_articulos.fields.fabricante.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fview_articulossrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fview_articulossrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fview_articulossrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fview_articulossrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<input type="hidden" name="t" value="view_articulos">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_view_articulos" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_view_articuloslist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Page->codigo->Visible) { // codigo ?>
        <th data-name="codigo" class="<?= $Page->codigo->headerCellClass() ?>"><div id="elh_view_articulos_codigo" class="view_articulos_codigo"><?= $Page->renderFieldHeader($Page->codigo) ?></div></th>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
        <th data-name="fabricante" class="<?= $Page->fabricante->headerCellClass() ?>"><div id="elh_view_articulos_fabricante" class="view_articulos_fabricante"><?= $Page->renderFieldHeader($Page->fabricante) ?></div></th>
<?php } ?>
<?php if ($Page->nombre_comercial->Visible) { // nombre_comercial ?>
        <th data-name="nombre_comercial" class="<?= $Page->nombre_comercial->headerCellClass() ?>"><div id="elh_view_articulos_nombre_comercial" class="view_articulos_nombre_comercial"><?= $Page->renderFieldHeader($Page->nombre_comercial) ?></div></th>
<?php } ?>
<?php if ($Page->principio_activo->Visible) { // principio_activo ?>
        <th data-name="principio_activo" class="<?= $Page->principio_activo->headerCellClass() ?>"><div id="elh_view_articulos_principio_activo" class="view_articulos_principio_activo"><?= $Page->renderFieldHeader($Page->principio_activo) ?></div></th>
<?php } ?>
<?php if ($Page->presentacion->Visible) { // presentacion ?>
        <th data-name="presentacion" class="<?= $Page->presentacion->headerCellClass() ?>"><div id="elh_view_articulos_presentacion" class="view_articulos_presentacion"><?= $Page->renderFieldHeader($Page->presentacion) ?></div></th>
<?php } ?>
<?php if ($Page->cantidad_en_mano->Visible) { // cantidad_en_mano ?>
        <th data-name="cantidad_en_mano" class="<?= $Page->cantidad_en_mano->headerCellClass() ?>"><div id="elh_view_articulos_cantidad_en_mano" class="view_articulos_cantidad_en_mano"><?= $Page->renderFieldHeader($Page->cantidad_en_mano) ?></div></th>
<?php } ?>
<?php if ($Page->ultimo_costo->Visible) { // ultimo_costo ?>
        <th data-name="ultimo_costo" class="<?= $Page->ultimo_costo->headerCellClass() ?>"><div id="elh_view_articulos_ultimo_costo" class="view_articulos_ultimo_costo"><?= $Page->renderFieldHeader($Page->ultimo_costo) ?></div></th>
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

        // Skip 1) delete row / empty row for confirm page, 2) hidden row
        if (
            $Page->RowAction != "delete" &&
            $Page->RowAction != "insertdelete" &&
            !($Page->RowAction == "insert" && $Page->isConfirm() && $Page->emptyRow()) &&
            $Page->RowAction != "hide"
        ) {
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->codigo->Visible) { // codigo ?>
        <td data-name="codigo"<?= $Page->codigo->cellAttributes() ?>>
<?php if ($Page->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_articulos_codigo" class="el_view_articulos_codigo">
<input type="<?= $Page->codigo->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_codigo" id="x<?= $Page->RowIndex ?>_codigo" data-table="view_articulos" data-field="x_codigo" value="<?= $Page->codigo->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->codigo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->codigo->formatPattern()) ?>"<?= $Page->codigo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->codigo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_codigo" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_codigo" id="o<?= $Page->RowIndex ?>_codigo" value="<?= HtmlEncode($Page->codigo->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_articulos_codigo" class="el_view_articulos_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->codigo->getDisplayValue($Page->codigo->EditValue))) ?>"></span>
<input type="hidden" data-table="view_articulos" data-field="x_codigo" data-hidden="1" name="x<?= $Page->RowIndex ?>_codigo" id="x<?= $Page->RowIndex ?>_codigo" value="<?= HtmlEncode($Page->codigo->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Page->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_articulos_codigo" class="el_view_articulos_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<?= $Page->codigo->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->fabricante->Visible) { // fabricante ?>
        <td data-name="fabricante"<?= $Page->fabricante->cellAttributes() ?>>
<?php if ($Page->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_articulos_fabricante" class="el_view_articulos_fabricante">
    <select
        id="x<?= $Page->RowIndex ?>_fabricante"
        name="x<?= $Page->RowIndex ?>_fabricante"
        class="form-control ew-select<?= $Page->fabricante->isInvalidClass() ?>"
        data-select2-id="<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_fabricante"
        data-table="view_articulos"
        data-field="x_fabricante"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->fabricante->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->fabricante->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->fabricante->getPlaceHolder()) ?>"
        <?= $Page->fabricante->editAttributes() ?>>
        <?= $Page->fabricante->selectOptionListHtml("x{$Page->RowIndex}_fabricante") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->fabricante->getErrorMessage() ?></div>
<?= $Page->fabricante->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_fabricante") ?>
<script>
loadjs.ready("<?= $Page->FormName ?>", function() {
    var options = { name: "x<?= $Page->RowIndex ?>_fabricante", selectId: "<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_fabricante" };
    if (<?= $Page->FormName ?>.lists.fabricante?.lookupOptions.length) {
        options.data = { id: "x<?= $Page->RowIndex ?>_fabricante", form: "<?= $Page->FormName ?>" };
    } else {
        options.ajax = { id: "x<?= $Page->RowIndex ?>_fabricante", form: "<?= $Page->FormName ?>", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.view_articulos.fields.fabricante.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_fabricante" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_fabricante" id="o<?= $Page->RowIndex ?>_fabricante" value="<?= HtmlEncode($Page->fabricante->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_articulos_fabricante" class="el_view_articulos_fabricante">
<span<?= $Page->fabricante->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->fabricante->getDisplayValue($Page->fabricante->EditValue) ?></span></span>
<input type="hidden" data-table="view_articulos" data-field="x_fabricante" data-hidden="1" name="x<?= $Page->RowIndex ?>_fabricante" id="x<?= $Page->RowIndex ?>_fabricante" value="<?= HtmlEncode($Page->fabricante->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Page->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_articulos_fabricante" class="el_view_articulos_fabricante">
<span<?= $Page->fabricante->viewAttributes() ?>>
<?= $Page->fabricante->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->nombre_comercial->Visible) { // nombre_comercial ?>
        <td data-name="nombre_comercial"<?= $Page->nombre_comercial->cellAttributes() ?>>
<?php if ($Page->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_articulos_nombre_comercial" class="el_view_articulos_nombre_comercial">
<input type="<?= $Page->nombre_comercial->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_nombre_comercial" id="x<?= $Page->RowIndex ?>_nombre_comercial" data-table="view_articulos" data-field="x_nombre_comercial" value="<?= $Page->nombre_comercial->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->nombre_comercial->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nombre_comercial->formatPattern()) ?>"<?= $Page->nombre_comercial->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nombre_comercial->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_nombre_comercial" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_nombre_comercial" id="o<?= $Page->RowIndex ?>_nombre_comercial" value="<?= HtmlEncode($Page->nombre_comercial->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_articulos_nombre_comercial" class="el_view_articulos_nombre_comercial">
<span<?= $Page->nombre_comercial->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->nombre_comercial->getDisplayValue($Page->nombre_comercial->EditValue))) ?>"></span>
<input type="hidden" data-table="view_articulos" data-field="x_nombre_comercial" data-hidden="1" name="x<?= $Page->RowIndex ?>_nombre_comercial" id="x<?= $Page->RowIndex ?>_nombre_comercial" value="<?= HtmlEncode($Page->nombre_comercial->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Page->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_articulos_nombre_comercial" class="el_view_articulos_nombre_comercial">
<span<?= $Page->nombre_comercial->viewAttributes() ?>>
<?= $Page->nombre_comercial->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->principio_activo->Visible) { // principio_activo ?>
        <td data-name="principio_activo"<?= $Page->principio_activo->cellAttributes() ?>>
<?php if ($Page->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_articulos_principio_activo" class="el_view_articulos_principio_activo">
<input type="<?= $Page->principio_activo->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_principio_activo" id="x<?= $Page->RowIndex ?>_principio_activo" data-table="view_articulos" data-field="x_principio_activo" value="<?= $Page->principio_activo->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->principio_activo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->principio_activo->formatPattern()) ?>"<?= $Page->principio_activo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->principio_activo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_principio_activo" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_principio_activo" id="o<?= $Page->RowIndex ?>_principio_activo" value="<?= HtmlEncode($Page->principio_activo->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_articulos_principio_activo" class="el_view_articulos_principio_activo">
<span<?= $Page->principio_activo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->principio_activo->getDisplayValue($Page->principio_activo->EditValue))) ?>"></span>
<input type="hidden" data-table="view_articulos" data-field="x_principio_activo" data-hidden="1" name="x<?= $Page->RowIndex ?>_principio_activo" id="x<?= $Page->RowIndex ?>_principio_activo" value="<?= HtmlEncode($Page->principio_activo->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Page->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_articulos_principio_activo" class="el_view_articulos_principio_activo">
<span<?= $Page->principio_activo->viewAttributes() ?>>
<?= $Page->principio_activo->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->presentacion->Visible) { // presentacion ?>
        <td data-name="presentacion"<?= $Page->presentacion->cellAttributes() ?>>
<?php if ($Page->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_articulos_presentacion" class="el_view_articulos_presentacion">
<input type="<?= $Page->presentacion->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_presentacion" id="x<?= $Page->RowIndex ?>_presentacion" data-table="view_articulos" data-field="x_presentacion" value="<?= $Page->presentacion->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->presentacion->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->presentacion->formatPattern()) ?>"<?= $Page->presentacion->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->presentacion->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_presentacion" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_presentacion" id="o<?= $Page->RowIndex ?>_presentacion" value="<?= HtmlEncode($Page->presentacion->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_articulos_presentacion" class="el_view_articulos_presentacion">
<span<?= $Page->presentacion->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->presentacion->getDisplayValue($Page->presentacion->EditValue))) ?>"></span>
<input type="hidden" data-table="view_articulos" data-field="x_presentacion" data-hidden="1" name="x<?= $Page->RowIndex ?>_presentacion" id="x<?= $Page->RowIndex ?>_presentacion" value="<?= HtmlEncode($Page->presentacion->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Page->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_articulos_presentacion" class="el_view_articulos_presentacion">
<span<?= $Page->presentacion->viewAttributes() ?>>
<?= $Page->presentacion->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->cantidad_en_mano->Visible) { // cantidad_en_mano ?>
        <td data-name="cantidad_en_mano"<?= $Page->cantidad_en_mano->cellAttributes() ?>>
<?php if ($Page->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_articulos_cantidad_en_mano" class="el_view_articulos_cantidad_en_mano">
<input type="<?= $Page->cantidad_en_mano->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_cantidad_en_mano" id="x<?= $Page->RowIndex ?>_cantidad_en_mano" data-table="view_articulos" data-field="x_cantidad_en_mano" value="<?= $Page->cantidad_en_mano->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->cantidad_en_mano->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cantidad_en_mano->formatPattern()) ?>"<?= $Page->cantidad_en_mano->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->cantidad_en_mano->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_cantidad_en_mano" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_cantidad_en_mano" id="o<?= $Page->RowIndex ?>_cantidad_en_mano" value="<?= HtmlEncode($Page->cantidad_en_mano->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_articulos_cantidad_en_mano" class="el_view_articulos_cantidad_en_mano">
<span<?= $Page->cantidad_en_mano->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->cantidad_en_mano->getDisplayValue($Page->cantidad_en_mano->EditValue))) ?>"></span>
<input type="hidden" data-table="view_articulos" data-field="x_cantidad_en_mano" data-hidden="1" name="x<?= $Page->RowIndex ?>_cantidad_en_mano" id="x<?= $Page->RowIndex ?>_cantidad_en_mano" value="<?= HtmlEncode($Page->cantidad_en_mano->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Page->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_articulos_cantidad_en_mano" class="el_view_articulos_cantidad_en_mano">
<span<?= $Page->cantidad_en_mano->viewAttributes() ?>>
<?= $Page->cantidad_en_mano->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->ultimo_costo->Visible) { // ultimo_costo ?>
        <td data-name="ultimo_costo"<?= $Page->ultimo_costo->cellAttributes() ?>>
<?php if ($Page->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_articulos_ultimo_costo" class="el_view_articulos_ultimo_costo">
<input type="<?= $Page->ultimo_costo->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_ultimo_costo" id="x<?= $Page->RowIndex ?>_ultimo_costo" data-table="view_articulos" data-field="x_ultimo_costo" value="<?= $Page->ultimo_costo->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Page->ultimo_costo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ultimo_costo->formatPattern()) ?>"<?= $Page->ultimo_costo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ultimo_costo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_ultimo_costo" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_ultimo_costo" id="o<?= $Page->RowIndex ?>_ultimo_costo" value="<?= HtmlEncode($Page->ultimo_costo->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_articulos_ultimo_costo" class="el_view_articulos_ultimo_costo">
<input type="<?= $Page->ultimo_costo->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_ultimo_costo" id="x<?= $Page->RowIndex ?>_ultimo_costo" data-table="view_articulos" data-field="x_ultimo_costo" value="<?= $Page->ultimo_costo->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Page->ultimo_costo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ultimo_costo->formatPattern()) ?>"<?= $Page->ultimo_costo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ultimo_costo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_articulos_ultimo_costo" class="el_view_articulos_ultimo_costo">
<span<?= $Page->ultimo_costo->viewAttributes() ?>>
<?= $Page->ultimo_costo->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php if ($Page->RowType == RowType::ADD || $Page->RowType == RowType::EDIT) { ?>
<script data-rowindex="<?= $Page->RowIndex ?>">
loadjs.ready(["<?= $Page->FormName ?>","load"], () => <?= $Page->FormName ?>.updateLists(<?= $Page->RowIndex ?><?= $Page->isAdd() || $Page->isEdit() || $Page->isCopy() || $Page->RowIndex === '$rowindex$' ? ", true" : "" ?>));
</script>
<?php } ?>
<?php
    }
    } // End delete row checking

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
<?php if ($Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<?php if ($Page->isGridEdit()) { ?>
<input type="hidden" name="action" id="action" value="gridupdate">
<?php } elseif ($Page->isMultiEdit()) { ?>
<input type="hidden" name="action" id="action" value="multiupdate">
<?php } ?>
<input type="hidden" name="<?= $Page->FormKeyCountName ?>" id="<?= $Page->FormKeyCountName ?>" value="<?= $Page->KeyCount ?>">
<?= $Page->MultiSelectKey ?>
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
    ew.addEventHandlers("view_articulos");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
