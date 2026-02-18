<?php

namespace PHPMaker2024\mandrake;

// Page object
$ViewOutTdcfcvList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { view_out_tdcfcv: currentTable } });
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
<form name="fview_out_tdcfcvsrch" id="fview_out_tdcfcvsrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" autocomplete="off">
<div id="fview_out_tdcfcvsrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { view_out_tdcfcv: currentTable } });
var currentForm;
var fview_out_tdcfcvsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fview_out_tdcfcvsrch")
        .setPageId("list")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["documento", [], fields.documento.isInvalid],
            ["nro_documento", [], fields.nro_documento.isInvalid],
            ["fecha", [ew.Validators.datetime(fields.fecha.clientFormatPattern)], fields.fecha.isInvalid],
            ["y_fecha", [ew.Validators.between], false],
            ["cliente", [], fields.cliente.isInvalid]
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
            "documento": <?= $Page->documento->toClientList($Page) ?>,
            "cliente": <?= $Page->cliente->toClientList($Page) ?>,
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
<?php if ($Page->documento->Visible) { // documento ?>
<?php
if (!$Page->documento->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_documento" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->documento->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_documento" class="ew-search-caption ew-label"><?= $Page->documento->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_documento" id="z_documento" value="=">
</div>
        </div>
        <div id="el_view_out_tdcfcv_documento" class="ew-search-field">
    <select
        id="x_documento"
        name="x_documento"
        class="form-select ew-select<?= $Page->documento->isInvalidClass() ?>"
        <?php if (!$Page->documento->IsNativeSelect) { ?>
        data-select2-id="fview_out_tdcfcvsrch_x_documento"
        <?php } ?>
        data-table="view_out_tdcfcv"
        data-field="x_documento"
        data-value-separator="<?= $Page->documento->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->documento->getPlaceHolder()) ?>"
        <?= $Page->documento->editAttributes() ?>>
        <?= $Page->documento->selectOptionListHtml("x_documento") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->documento->getErrorMessage(false) ?></div>
<?php if (!$Page->documento->IsNativeSelect) { ?>
<script>
loadjs.ready("fview_out_tdcfcvsrch", function() {
    var options = { name: "x_documento", selectId: "fview_out_tdcfcvsrch_x_documento" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fview_out_tdcfcvsrch.lists.documento?.lookupOptions.length) {
        options.data = { id: "x_documento", form: "fview_out_tdcfcvsrch" };
    } else {
        options.ajax = { id: "x_documento", form: "fview_out_tdcfcvsrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.view_out_tdcfcv.fields.documento.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
<?php
if (!$Page->nro_documento->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_nro_documento" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->nro_documento->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_nro_documento" class="ew-search-caption ew-label"><?= $Page->nro_documento->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_nro_documento" id="z_nro_documento" value="LIKE">
</div>
        </div>
        <div id="el_view_out_tdcfcv_nro_documento" class="ew-search-field">
<input type="<?= $Page->nro_documento->getInputTextType() ?>" name="x_nro_documento" id="x_nro_documento" data-table="view_out_tdcfcv" data-field="x_nro_documento" value="<?= $Page->nro_documento->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->nro_documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nro_documento->formatPattern()) ?>"<?= $Page->nro_documento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nro_documento->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
<?php
if (!$Page->fecha->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_fecha" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->fecha->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_fecha" class="ew-search-caption ew-label"><?= $Page->fecha->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("BETWEEN") ?>
<input type="hidden" name="z_fecha" id="z_fecha" value="BETWEEN">
</div>
        </div>
        <div id="el_view_out_tdcfcv_fecha" class="ew-search-field">
<input type="<?= $Page->fecha->getInputTextType() ?>" name="x_fecha" id="x_fecha" data-table="view_out_tdcfcv" data-field="x_fecha" value="<?= $Page->fecha->EditValue ?>" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha->formatPattern()) ?>"<?= $Page->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage(false) ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fview_out_tdcfcvsrch", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fview_out_tdcfcvsrch", "x_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_view_out_tdcfcv_fecha" class="ew-search-field2">
<input type="<?= $Page->fecha->getInputTextType() ?>" name="y_fecha" id="y_fecha" data-table="view_out_tdcfcv" data-field="x_fecha" value="<?= $Page->fecha->EditValue2 ?>" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha->formatPattern()) ?>"<?= $Page->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage(false) ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fview_out_tdcfcvsrch", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fview_out_tdcfcvsrch", "y_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
<?php
if (!$Page->cliente->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_cliente" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->cliente->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_cliente" class="ew-search-caption ew-label"><?= $Page->cliente->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_cliente" id="z_cliente" value="=">
</div>
        </div>
        <div id="el_view_out_tdcfcv_cliente" class="ew-search-field">
    <select
        id="x_cliente"
        name="x_cliente"
        class="form-control ew-select<?= $Page->cliente->isInvalidClass() ?>"
        data-select2-id="fview_out_tdcfcvsrch_x_cliente"
        data-table="view_out_tdcfcv"
        data-field="x_cliente"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->cliente->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->cliente->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->cliente->getPlaceHolder()) ?>"
        <?= $Page->cliente->editAttributes() ?>>
        <?= $Page->cliente->selectOptionListHtml("x_cliente") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->cliente->getErrorMessage(false) ?></div>
<?= $Page->cliente->Lookup->getParamTag($Page, "p_x_cliente") ?>
<script>
loadjs.ready("fview_out_tdcfcvsrch", function() {
    var options = { name: "x_cliente", selectId: "fview_out_tdcfcvsrch_x_cliente" };
    if (fview_out_tdcfcvsrch.lists.cliente?.lookupOptions.length) {
        options.data = { id: "x_cliente", form: "fview_out_tdcfcvsrch" };
    } else {
        options.ajax = { id: "x_cliente", form: "fview_out_tdcfcvsrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.view_out_tdcfcv.fields.cliente.modalLookupOptions);
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fview_out_tdcfcvsrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fview_out_tdcfcvsrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fview_out_tdcfcvsrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fview_out_tdcfcvsrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<input type="hidden" name="t" value="view_out_tdcfcv">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_view_out_tdcfcv" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_view_out_tdcfcvlist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Page->documento->Visible) { // documento ?>
        <th data-name="documento" class="<?= $Page->documento->headerCellClass() ?>"><div id="elh_view_out_tdcfcv_documento" class="view_out_tdcfcv_documento"><?= $Page->renderFieldHeader($Page->documento) ?></div></th>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <th data-name="nro_documento" class="<?= $Page->nro_documento->headerCellClass() ?>"><div id="elh_view_out_tdcfcv_nro_documento" class="view_out_tdcfcv_nro_documento"><?= $Page->renderFieldHeader($Page->nro_documento) ?></div></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th data-name="fecha" class="<?= $Page->fecha->headerCellClass() ?>"><div id="elh_view_out_tdcfcv_fecha" class="view_out_tdcfcv_fecha"><?= $Page->renderFieldHeader($Page->fecha) ?></div></th>
<?php } ?>
<?php if ($Page->nro_control->Visible) { // nro_control ?>
        <th data-name="nro_control" class="<?= $Page->nro_control->headerCellClass() ?>"><div id="elh_view_out_tdcfcv_nro_control" class="view_out_tdcfcv_nro_control"><?= $Page->renderFieldHeader($Page->nro_control) ?></div></th>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
        <th data-name="cliente" class="<?= $Page->cliente->headerCellClass() ?>"><div id="elh_view_out_tdcfcv_cliente" class="view_out_tdcfcv_cliente"><?= $Page->renderFieldHeader($Page->cliente) ?></div></th>
<?php } ?>
<?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
        <th data-name="doc_afectado" class="<?= $Page->doc_afectado->headerCellClass() ?>"><div id="elh_view_out_tdcfcv_doc_afectado" class="view_out_tdcfcv_doc_afectado"><?= $Page->renderFieldHeader($Page->doc_afectado) ?></div></th>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
        <th data-name="total" class="<?= $Page->total->headerCellClass() ?>"><div id="elh_view_out_tdcfcv_total" class="view_out_tdcfcv_total"><?= $Page->renderFieldHeader($Page->total) ?></div></th>
<?php } ?>
<?php if ($Page->igtf->Visible) { // igtf ?>
        <th data-name="igtf" class="<?= $Page->igtf->headerCellClass() ?>"><div id="elh_view_out_tdcfcv_igtf" class="view_out_tdcfcv_igtf"><?= $Page->renderFieldHeader($Page->igtf) ?></div></th>
<?php } ?>
<?php if ($Page->monto_base_igtf->Visible) { // monto_base_igtf ?>
        <th data-name="monto_base_igtf" class="<?= $Page->monto_base_igtf->headerCellClass() ?>"><div id="elh_view_out_tdcfcv_monto_base_igtf" class="view_out_tdcfcv_monto_base_igtf"><?= $Page->renderFieldHeader($Page->monto_base_igtf) ?></div></th>
<?php } ?>
<?php if ($Page->monto_igtf->Visible) { // monto_igtf ?>
        <th data-name="monto_igtf" class="<?= $Page->monto_igtf->headerCellClass() ?>"><div id="elh_view_out_tdcfcv_monto_igtf" class="view_out_tdcfcv_monto_igtf"><?= $Page->renderFieldHeader($Page->monto_igtf) ?></div></th>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
        <th data-name="moneda" class="<?= $Page->moneda->headerCellClass() ?>"><div id="elh_view_out_tdcfcv_moneda" class="view_out_tdcfcv_moneda"><?= $Page->renderFieldHeader($Page->moneda) ?></div></th>
<?php } ?>
<?php if ($Page->unidades->Visible) { // unidades ?>
        <th data-name="unidades" class="<?= $Page->unidades->headerCellClass() ?>"><div id="elh_view_out_tdcfcv_unidades" class="view_out_tdcfcv_unidades"><?= $Page->renderFieldHeader($Page->unidades) ?></div></th>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
        <th data-name="estatus" class="<?= $Page->estatus->headerCellClass() ?>"><div id="elh_view_out_tdcfcv_estatus" class="view_out_tdcfcv_estatus"><?= $Page->renderFieldHeader($Page->estatus) ?></div></th>
<?php } ?>
<?php if ($Page->id_documento_padre->Visible) { // id_documento_padre ?>
        <th data-name="id_documento_padre" class="<?= $Page->id_documento_padre->headerCellClass() ?>"><div id="elh_view_out_tdcfcv_id_documento_padre" class="view_out_tdcfcv_id_documento_padre"><?= $Page->renderFieldHeader($Page->id_documento_padre) ?></div></th>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
        <th data-name="descuento" class="<?= $Page->descuento->headerCellClass() ?>"><div id="elh_view_out_tdcfcv_descuento" class="view_out_tdcfcv_descuento"><?= $Page->renderFieldHeader($Page->descuento) ?></div></th>
<?php } ?>
<?php if ($Page->descuento2->Visible) { // descuento2 ?>
        <th data-name="descuento2" class="<?= $Page->descuento2->headerCellClass() ?>"><div id="elh_view_out_tdcfcv_descuento2" class="view_out_tdcfcv_descuento2"><?= $Page->renderFieldHeader($Page->descuento2) ?></div></th>
<?php } ?>
<?php if ($Page->asesor_asignado->Visible) { // asesor_asignado ?>
        <th data-name="asesor_asignado" class="<?= $Page->asesor_asignado->headerCellClass() ?>"><div id="elh_view_out_tdcfcv_asesor_asignado" class="view_out_tdcfcv_asesor_asignado"><?= $Page->renderFieldHeader($Page->asesor_asignado) ?></div></th>
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
    <?php if ($Page->documento->Visible) { // documento ?>
        <td data-name="documento"<?= $Page->documento->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_out_tdcfcv_documento" class="el_view_out_tdcfcv_documento">
<span<?= $Page->documento->viewAttributes() ?>>
<?= $Page->documento->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <td data-name="nro_documento"<?= $Page->nro_documento->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_out_tdcfcv_nro_documento" class="el_view_out_tdcfcv_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->fecha->Visible) { // fecha ?>
        <td data-name="fecha"<?= $Page->fecha->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_out_tdcfcv_fecha" class="el_view_out_tdcfcv_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->nro_control->Visible) { // nro_control ?>
        <td data-name="nro_control"<?= $Page->nro_control->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_out_tdcfcv_nro_control" class="el_view_out_tdcfcv_nro_control">
<span<?= $Page->nro_control->viewAttributes() ?>>
<?= $Page->nro_control->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->cliente->Visible) { // cliente ?>
        <td data-name="cliente"<?= $Page->cliente->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_out_tdcfcv_cliente" class="el_view_out_tdcfcv_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
        <td data-name="doc_afectado"<?= $Page->doc_afectado->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_out_tdcfcv_doc_afectado" class="el_view_out_tdcfcv_doc_afectado">
<span<?= $Page->doc_afectado->viewAttributes() ?>>
<?= $Page->doc_afectado->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->total->Visible) { // total ?>
        <td data-name="total"<?= $Page->total->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_out_tdcfcv_total" class="el_view_out_tdcfcv_total">
<span<?= $Page->total->viewAttributes() ?>>
<?= $Page->total->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->igtf->Visible) { // igtf ?>
        <td data-name="igtf"<?= $Page->igtf->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_out_tdcfcv_igtf" class="el_view_out_tdcfcv_igtf">
<span<?= $Page->igtf->viewAttributes() ?>>
<?= $Page->igtf->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->monto_base_igtf->Visible) { // monto_base_igtf ?>
        <td data-name="monto_base_igtf"<?= $Page->monto_base_igtf->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_out_tdcfcv_monto_base_igtf" class="el_view_out_tdcfcv_monto_base_igtf">
<span<?= $Page->monto_base_igtf->viewAttributes() ?>>
<?= $Page->monto_base_igtf->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->monto_igtf->Visible) { // monto_igtf ?>
        <td data-name="monto_igtf"<?= $Page->monto_igtf->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_out_tdcfcv_monto_igtf" class="el_view_out_tdcfcv_monto_igtf">
<span<?= $Page->monto_igtf->viewAttributes() ?>>
<?= $Page->monto_igtf->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->moneda->Visible) { // moneda ?>
        <td data-name="moneda"<?= $Page->moneda->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_out_tdcfcv_moneda" class="el_view_out_tdcfcv_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->unidades->Visible) { // unidades ?>
        <td data-name="unidades"<?= $Page->unidades->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_out_tdcfcv_unidades" class="el_view_out_tdcfcv_unidades">
<span<?= $Page->unidades->viewAttributes() ?>>
<?= $Page->unidades->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->estatus->Visible) { // estatus ?>
        <td data-name="estatus"<?= $Page->estatus->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_out_tdcfcv_estatus" class="el_view_out_tdcfcv_estatus">
<span<?= $Page->estatus->viewAttributes() ?>>
<?= $Page->estatus->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->id_documento_padre->Visible) { // id_documento_padre ?>
        <td data-name="id_documento_padre"<?= $Page->id_documento_padre->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_out_tdcfcv_id_documento_padre" class="el_view_out_tdcfcv_id_documento_padre">
<span<?= $Page->id_documento_padre->viewAttributes() ?>>
<?= $Page->id_documento_padre->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->descuento->Visible) { // descuento ?>
        <td data-name="descuento"<?= $Page->descuento->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_out_tdcfcv_descuento" class="el_view_out_tdcfcv_descuento">
<span<?= $Page->descuento->viewAttributes() ?>>
<?= $Page->descuento->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->descuento2->Visible) { // descuento2 ?>
        <td data-name="descuento2"<?= $Page->descuento2->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_out_tdcfcv_descuento2" class="el_view_out_tdcfcv_descuento2">
<span<?= $Page->descuento2->viewAttributes() ?>>
<?= $Page->descuento2->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->asesor_asignado->Visible) { // asesor_asignado ?>
        <td data-name="asesor_asignado"<?= $Page->asesor_asignado->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_out_tdcfcv_asesor_asignado" class="el_view_out_tdcfcv_asesor_asignado">
<span<?= $Page->asesor_asignado->viewAttributes() ?>>
<?= $Page->asesor_asignado->getViewValue() ?></span>
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
    ew.addEventHandlers("view_out_tdcfcv");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here, no need to add script tags.

    // Listener para el nuevo botón de exportación
    $("#btnExportExcel").on("click", function(e) {
        e.preventDefault();

        // 1. Obtener los valores de las fechas de los campos de filtro
        // Asumimos que los IDs de los campos de filtro de fecha de PHPMaker son #x_fecha y #y_fecha
        let fechaDesde = $("#x_fecha").val();
        let fechaHasta = $("#y_fecha").val();

        // 2. Validación de Fechas en el Cliente
        if (fechaDesde === "" || fechaHasta === "") {
            // Usar la función de alerta de PHPMaker o una alerta simple
            ew.alert("¡Atención! Debe seleccionar un rango de fechas ('Desde' y 'Hasta') en el filtro para poder descargar el reporte.");
            return; // Detener la ejecución si la validación falla
        }

        // 3. Construir la URL del script cxc_fact.php
        // Incluimos las fechas como parámetros GET para que el script PHP las reciba
        let downloadUrl = "include/cxc_fact.php?action=export&tipo_documento=TDCFCV&fecha_desde=" + fechaDesde + "&fecha_hasta=" + fechaHasta;

        // 4. Forzar la descarga
        // La forma más simple de forzar la descarga desde JavaScript es
        // navegando a la URL, lo que provoca la ejecución del script PHP
        // y la respuesta del archivo (sin usar una llamada Ajax formal que maneje el archivo).
        window.open(downloadUrl, '_blank');
    });
});
</script>
<?php } ?>
