<?php

namespace PHPMaker2024\mandrake;

// Page object
$ViewFacturasCobranzaList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { view_facturas_cobranza: currentTable } });
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
<form name="fview_facturas_cobranzasrch" id="fview_facturas_cobranzasrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" autocomplete="off">
<div id="fview_facturas_cobranzasrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { view_facturas_cobranza: currentTable } });
var currentForm;
var fview_facturas_cobranzasrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fview_facturas_cobranzasrch")
        .setPageId("list")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["documento", [], fields.documento.isInvalid],
            ["codcli", [], fields.codcli.isInvalid],
            ["fecha", [ew.Validators.datetime(fields.fecha.clientFormatPattern)], fields.fecha.isInvalid],
            ["y_fecha", [ew.Validators.between], false],
            ["nro_documento", [], fields.nro_documento.isInvalid],
            ["pagado", [], fields.pagado.isInvalid],
            ["asesor_asignado", [], fields.asesor_asignado.isInvalid]
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
            "codcli": <?= $Page->codcli->toClientList($Page) ?>,
            "pagado": <?= $Page->pagado->toClientList($Page) ?>,
            "asesor_asignado": <?= $Page->asesor_asignado->toClientList($Page) ?>,
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
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_documento" id="z_documento" value="LIKE">
</div>
        </div>
        <div id="el_view_facturas_cobranza_documento" class="ew-search-field">
    <select
        id="x_documento"
        name="x_documento"
        class="form-select ew-select<?= $Page->documento->isInvalidClass() ?>"
        <?php if (!$Page->documento->IsNativeSelect) { ?>
        data-select2-id="fview_facturas_cobranzasrch_x_documento"
        <?php } ?>
        data-table="view_facturas_cobranza"
        data-field="x_documento"
        data-value-separator="<?= $Page->documento->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->documento->getPlaceHolder()) ?>"
        <?= $Page->documento->editAttributes() ?>>
        <?= $Page->documento->selectOptionListHtml("x_documento") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->documento->getErrorMessage(false) ?></div>
<?php if (!$Page->documento->IsNativeSelect) { ?>
<script>
loadjs.ready("fview_facturas_cobranzasrch", function() {
    var options = { name: "x_documento", selectId: "fview_facturas_cobranzasrch_x_documento" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fview_facturas_cobranzasrch.lists.documento?.lookupOptions.length) {
        options.data = { id: "x_documento", form: "fview_facturas_cobranzasrch" };
    } else {
        options.ajax = { id: "x_documento", form: "fview_facturas_cobranzasrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.view_facturas_cobranza.fields.documento.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->codcli->Visible) { // codcli ?>
<?php
if (!$Page->codcli->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_codcli" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->codcli->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_codcli" class="ew-search-caption ew-label"><?= $Page->codcli->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_codcli" id="z_codcli" value="=">
</div>
        </div>
        <div id="el_view_facturas_cobranza_codcli" class="ew-search-field">
    <select
        id="x_codcli"
        name="x_codcli"
        class="form-control ew-select<?= $Page->codcli->isInvalidClass() ?>"
        data-select2-id="fview_facturas_cobranzasrch_x_codcli"
        data-table="view_facturas_cobranza"
        data-field="x_codcli"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->codcli->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->codcli->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->codcli->getPlaceHolder()) ?>"
        <?= $Page->codcli->editAttributes() ?>>
        <?= $Page->codcli->selectOptionListHtml("x_codcli") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->codcli->getErrorMessage(false) ?></div>
<?= $Page->codcli->Lookup->getParamTag($Page, "p_x_codcli") ?>
<script>
loadjs.ready("fview_facturas_cobranzasrch", function() {
    var options = { name: "x_codcli", selectId: "fview_facturas_cobranzasrch_x_codcli" };
    if (fview_facturas_cobranzasrch.lists.codcli?.lookupOptions.length) {
        options.data = { id: "x_codcli", form: "fview_facturas_cobranzasrch" };
    } else {
        options.ajax = { id: "x_codcli", form: "fview_facturas_cobranzasrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.view_facturas_cobranza.fields.codcli.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
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
        <div id="el_view_facturas_cobranza_fecha" class="ew-search-field">
<input type="<?= $Page->fecha->getInputTextType() ?>" name="x_fecha" id="x_fecha" data-table="view_facturas_cobranza" data-field="x_fecha" value="<?= $Page->fecha->EditValue ?>" maxlength="19" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha->formatPattern()) ?>"<?= $Page->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage(false) ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fview_facturas_cobranzasrch", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fview_facturas_cobranzasrch", "x_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_view_facturas_cobranza_fecha" class="ew-search-field2">
<input type="<?= $Page->fecha->getInputTextType() ?>" name="y_fecha" id="y_fecha" data-table="view_facturas_cobranza" data-field="x_fecha" value="<?= $Page->fecha->EditValue2 ?>" maxlength="19" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha->formatPattern()) ?>"<?= $Page->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage(false) ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fview_facturas_cobranzasrch", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fview_facturas_cobranzasrch", "y_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</div>
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
        <div id="el_view_facturas_cobranza_nro_documento" class="ew-search-field">
<input type="<?= $Page->nro_documento->getInputTextType() ?>" name="x_nro_documento" id="x_nro_documento" data-table="view_facturas_cobranza" data-field="x_nro_documento" value="<?= $Page->nro_documento->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->nro_documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nro_documento->formatPattern()) ?>"<?= $Page->nro_documento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nro_documento->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->pagado->Visible) { // pagado ?>
<?php
if (!$Page->pagado->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_pagado" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->pagado->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->pagado->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_pagado" id="z_pagado" value="=">
</div>
        </div>
        <div id="el_view_facturas_cobranza_pagado" class="ew-search-field">
<template id="tp_x_pagado">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="view_facturas_cobranza" data-field="x_pagado" name="x_pagado" id="x_pagado"<?= $Page->pagado->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_pagado" class="ew-item-list"></div>
<selection-list hidden
    id="x_pagado"
    name="x_pagado"
    value="<?= HtmlEncode($Page->pagado->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_pagado"
    data-target="dsl_x_pagado"
    data-repeatcolumn="5"
    class="form-control<?= $Page->pagado->isInvalidClass() ?>"
    data-table="view_facturas_cobranza"
    data-field="x_pagado"
    data-value-separator="<?= $Page->pagado->displayValueSeparatorAttribute() ?>"
    <?= $Page->pagado->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->pagado->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->asesor_asignado->Visible) { // asesor_asignado ?>
<?php
if (!$Page->asesor_asignado->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_asesor_asignado" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->asesor_asignado->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->asesor_asignado->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_asesor_asignado" id="z_asesor_asignado" value="LIKE">
</div>
        </div>
        <div id="el_view_facturas_cobranza_asesor_asignado" class="ew-search-field">
<?php
if (IsRTL()) {
    $Page->asesor_asignado->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_asesor_asignado" class="ew-auto-suggest">
    <input type="<?= $Page->asesor_asignado->getInputTextType() ?>" class="form-control" name="sv_x_asesor_asignado" id="sv_x_asesor_asignado" value="<?= RemoveHtml($Page->asesor_asignado->EditValue) ?>" autocomplete="off" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->asesor_asignado->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->asesor_asignado->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->asesor_asignado->formatPattern()) ?>"<?= $Page->asesor_asignado->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="view_facturas_cobranza" data-field="x_asesor_asignado" data-input="sv_x_asesor_asignado" data-value-separator="<?= $Page->asesor_asignado->displayValueSeparatorAttribute() ?>" name="x_asesor_asignado" id="x_asesor_asignado" value="<?= HtmlEncode($Page->asesor_asignado->AdvancedSearch->SearchValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Page->asesor_asignado->getErrorMessage(false) ?></div>
<script>
loadjs.ready("fview_facturas_cobranzasrch", function() {
    fview_facturas_cobranzasrch.createAutoSuggest(Object.assign({"id":"x_asesor_asignado","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->asesor_asignado->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.view_facturas_cobranza.fields.asesor_asignado.autoSuggestOptions));
});
</script>
<?= $Page->asesor_asignado->Lookup->getParamTag($Page, "p_x_asesor_asignado") ?>
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fview_facturas_cobranzasrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fview_facturas_cobranzasrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fview_facturas_cobranzasrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fview_facturas_cobranzasrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<input type="hidden" name="t" value="view_facturas_cobranza">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_view_facturas_cobranza" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_view_facturas_cobranzalist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
        <th data-name="documento" class="<?= $Page->documento->headerCellClass() ?>"><div id="elh_view_facturas_cobranza_documento" class="view_facturas_cobranza_documento"><?= $Page->renderFieldHeader($Page->documento) ?></div></th>
<?php } ?>
<?php if ($Page->codcli->Visible) { // codcli ?>
        <th data-name="codcli" class="<?= $Page->codcli->headerCellClass() ?>"><div id="elh_view_facturas_cobranza_codcli" class="view_facturas_cobranza_codcli"><?= $Page->renderFieldHeader($Page->codcli) ?></div></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th data-name="fecha" class="<?= $Page->fecha->headerCellClass() ?>"><div id="elh_view_facturas_cobranza_fecha" class="view_facturas_cobranza_fecha"><?= $Page->renderFieldHeader($Page->fecha) ?></div></th>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <th data-name="nro_documento" class="<?= $Page->nro_documento->headerCellClass() ?>"><div id="elh_view_facturas_cobranza_nro_documento" class="view_facturas_cobranza_nro_documento"><?= $Page->renderFieldHeader($Page->nro_documento) ?></div></th>
<?php } ?>
<?php if ($Page->nro_nota->Visible) { // nro_nota ?>
        <th data-name="nro_nota" class="<?= $Page->nro_nota->headerCellClass() ?>"><div id="elh_view_facturas_cobranza_nro_nota" class="view_facturas_cobranza_nro_nota"><?= $Page->renderFieldHeader($Page->nro_nota) ?></div></th>
<?php } ?>
<?php if ($Page->ciudad->Visible) { // ciudad ?>
        <th data-name="ciudad" class="<?= $Page->ciudad->headerCellClass() ?>"><div id="elh_view_facturas_cobranza_ciudad" class="view_facturas_cobranza_ciudad"><?= $Page->renderFieldHeader($Page->ciudad) ?></div></th>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
        <th data-name="moneda" class="<?= $Page->moneda->headerCellClass() ?>"><div id="elh_view_facturas_cobranza_moneda" class="view_facturas_cobranza_moneda"><?= $Page->renderFieldHeader($Page->moneda) ?></div></th>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
        <th data-name="total" class="<?= $Page->total->headerCellClass() ?>"><div id="elh_view_facturas_cobranza_total" class="view_facturas_cobranza_total"><?= $Page->renderFieldHeader($Page->total) ?></div></th>
<?php } ?>
<?php if ($Page->monto_pagado->Visible) { // monto_pagado ?>
        <th data-name="monto_pagado" class="<?= $Page->monto_pagado->headerCellClass() ?>"><div id="elh_view_facturas_cobranza_monto_pagado" class="view_facturas_cobranza_monto_pagado"><?= $Page->renderFieldHeader($Page->monto_pagado) ?></div></th>
<?php } ?>
<?php if ($Page->pendiente->Visible) { // pendiente ?>
        <th data-name="pendiente" class="<?= $Page->pendiente->headerCellClass() ?>"><div id="elh_view_facturas_cobranza_pendiente" class="view_facturas_cobranza_pendiente"><?= $Page->renderFieldHeader($Page->pendiente) ?></div></th>
<?php } ?>
<?php if ($Page->pendiente2->Visible) { // pendiente2 ?>
        <th data-name="pendiente2" class="<?= $Page->pendiente2->headerCellClass() ?>"><div id="elh_view_facturas_cobranza_pendiente2" class="view_facturas_cobranza_pendiente2"><?= $Page->renderFieldHeader($Page->pendiente2) ?></div></th>
<?php } ?>
<?php if ($Page->pendiente3->Visible) { // pendiente3 ?>
        <th data-name="pendiente3" class="<?= $Page->pendiente3->headerCellClass() ?>"><div id="elh_view_facturas_cobranza_pendiente3" class="view_facturas_cobranza_pendiente3"><?= $Page->renderFieldHeader($Page->pendiente3) ?></div></th>
<?php } ?>
<?php if ($Page->fecha_despacho->Visible) { // fecha_despacho ?>
        <th data-name="fecha_despacho" class="<?= $Page->fecha_despacho->headerCellClass() ?>"><div id="elh_view_facturas_cobranza_fecha_despacho" class="view_facturas_cobranza_fecha_despacho"><?= $Page->renderFieldHeader($Page->fecha_despacho) ?></div></th>
<?php } ?>
<?php if ($Page->fecha_entrega->Visible) { // fecha_entrega ?>
        <th data-name="fecha_entrega" class="<?= $Page->fecha_entrega->headerCellClass() ?>"><div id="elh_view_facturas_cobranza_fecha_entrega" class="view_facturas_cobranza_fecha_entrega"><?= $Page->renderFieldHeader($Page->fecha_entrega) ?></div></th>
<?php } ?>
<?php if ($Page->dias_credito->Visible) { // dias_credito ?>
        <th data-name="dias_credito" class="<?= $Page->dias_credito->headerCellClass() ?>"><div id="elh_view_facturas_cobranza_dias_credito" class="view_facturas_cobranza_dias_credito"><?= $Page->renderFieldHeader($Page->dias_credito) ?></div></th>
<?php } ?>
<?php if ($Page->dias_transcurridos->Visible) { // dias_transcurridos ?>
        <th data-name="dias_transcurridos" class="<?= $Page->dias_transcurridos->headerCellClass() ?>"><div id="elh_view_facturas_cobranza_dias_transcurridos" class="view_facturas_cobranza_dias_transcurridos"><?= $Page->renderFieldHeader($Page->dias_transcurridos) ?></div></th>
<?php } ?>
<?php if ($Page->dias_vencidos->Visible) { // dias_vencidos ?>
        <th data-name="dias_vencidos" class="<?= $Page->dias_vencidos->headerCellClass() ?>"><div id="elh_view_facturas_cobranza_dias_vencidos" class="view_facturas_cobranza_dias_vencidos"><?= $Page->renderFieldHeader($Page->dias_vencidos) ?></div></th>
<?php } ?>
<?php if ($Page->pagado->Visible) { // pagado ?>
        <th data-name="pagado" class="<?= $Page->pagado->headerCellClass() ?>"><div id="elh_view_facturas_cobranza_pagado" class="view_facturas_cobranza_pagado"><?= $Page->renderFieldHeader($Page->pagado) ?></div></th>
<?php } ?>
<?php if ($Page->bultos->Visible) { // bultos ?>
        <th data-name="bultos" class="<?= $Page->bultos->headerCellClass() ?>"><div id="elh_view_facturas_cobranza_bultos" class="view_facturas_cobranza_bultos"><?= $Page->renderFieldHeader($Page->bultos) ?></div></th>
<?php } ?>
<?php if ($Page->asesor_asignado->Visible) { // asesor_asignado ?>
        <th data-name="asesor_asignado" class="<?= $Page->asesor_asignado->headerCellClass() ?>"><div id="elh_view_facturas_cobranza_asesor_asignado" class="view_facturas_cobranza_asesor_asignado"><?= $Page->renderFieldHeader($Page->asesor_asignado) ?></div></th>
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
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_facturas_cobranza_documento" class="el_view_facturas_cobranza_documento">
<span<?= $Page->documento->viewAttributes() ?>>
<?= $Page->documento->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->codcli->Visible) { // codcli ?>
        <td data-name="codcli"<?= $Page->codcli->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_facturas_cobranza_codcli" class="el_view_facturas_cobranza_codcli">
<span<?= $Page->codcli->viewAttributes() ?>>
<?= $Page->codcli->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->fecha->Visible) { // fecha ?>
        <td data-name="fecha"<?= $Page->fecha->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_facturas_cobranza_fecha" class="el_view_facturas_cobranza_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <td data-name="nro_documento"<?= $Page->nro_documento->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_facturas_cobranza_nro_documento" class="el_view_facturas_cobranza_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->nro_nota->Visible) { // nro_nota ?>
        <td data-name="nro_nota"<?= $Page->nro_nota->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_facturas_cobranza_nro_nota" class="el_view_facturas_cobranza_nro_nota">
<span<?= $Page->nro_nota->viewAttributes() ?>>
<?= $Page->nro_nota->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->ciudad->Visible) { // ciudad ?>
        <td data-name="ciudad"<?= $Page->ciudad->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_facturas_cobranza_ciudad" class="el_view_facturas_cobranza_ciudad">
<span<?= $Page->ciudad->viewAttributes() ?>>
<?= $Page->ciudad->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->moneda->Visible) { // moneda ?>
        <td data-name="moneda"<?= $Page->moneda->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_facturas_cobranza_moneda" class="el_view_facturas_cobranza_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->total->Visible) { // total ?>
        <td data-name="total"<?= $Page->total->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_facturas_cobranza_total" class="el_view_facturas_cobranza_total">
<span<?= $Page->total->viewAttributes() ?>>
<?= $Page->total->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->monto_pagado->Visible) { // monto_pagado ?>
        <td data-name="monto_pagado"<?= $Page->monto_pagado->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_facturas_cobranza_monto_pagado" class="el_view_facturas_cobranza_monto_pagado">
<span<?= $Page->monto_pagado->viewAttributes() ?>>
<?= $Page->monto_pagado->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->pendiente->Visible) { // pendiente ?>
        <td data-name="pendiente"<?= $Page->pendiente->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_facturas_cobranza_pendiente" class="el_view_facturas_cobranza_pendiente">
<span<?= $Page->pendiente->viewAttributes() ?>>
<?= $Page->pendiente->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->pendiente2->Visible) { // pendiente2 ?>
        <td data-name="pendiente2"<?= $Page->pendiente2->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_facturas_cobranza_pendiente2" class="el_view_facturas_cobranza_pendiente2">
<span<?= $Page->pendiente2->viewAttributes() ?>>
<?= $Page->pendiente2->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->pendiente3->Visible) { // pendiente3 ?>
        <td data-name="pendiente3"<?= $Page->pendiente3->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_facturas_cobranza_pendiente3" class="el_view_facturas_cobranza_pendiente3">
<span<?= $Page->pendiente3->viewAttributes() ?>>
<?= $Page->pendiente3->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->fecha_despacho->Visible) { // fecha_despacho ?>
        <td data-name="fecha_despacho"<?= $Page->fecha_despacho->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_facturas_cobranza_fecha_despacho" class="el_view_facturas_cobranza_fecha_despacho">
<span<?= $Page->fecha_despacho->viewAttributes() ?>>
<?= $Page->fecha_despacho->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->fecha_entrega->Visible) { // fecha_entrega ?>
        <td data-name="fecha_entrega"<?= $Page->fecha_entrega->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_facturas_cobranza_fecha_entrega" class="el_view_facturas_cobranza_fecha_entrega">
<span<?= $Page->fecha_entrega->viewAttributes() ?>>
<?= $Page->fecha_entrega->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->dias_credito->Visible) { // dias_credito ?>
        <td data-name="dias_credito"<?= $Page->dias_credito->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_facturas_cobranza_dias_credito" class="el_view_facturas_cobranza_dias_credito">
<span<?= $Page->dias_credito->viewAttributes() ?>>
<?= $Page->dias_credito->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->dias_transcurridos->Visible) { // dias_transcurridos ?>
        <td data-name="dias_transcurridos"<?= $Page->dias_transcurridos->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_facturas_cobranza_dias_transcurridos" class="el_view_facturas_cobranza_dias_transcurridos">
<span<?= $Page->dias_transcurridos->viewAttributes() ?>>
<?= $Page->dias_transcurridos->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->dias_vencidos->Visible) { // dias_vencidos ?>
        <td data-name="dias_vencidos"<?= $Page->dias_vencidos->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_facturas_cobranza_dias_vencidos" class="el_view_facturas_cobranza_dias_vencidos">
<span<?= $Page->dias_vencidos->viewAttributes() ?>>
<?= $Page->dias_vencidos->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->pagado->Visible) { // pagado ?>
        <td data-name="pagado"<?= $Page->pagado->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_facturas_cobranza_pagado" class="el_view_facturas_cobranza_pagado">
<span<?= $Page->pagado->viewAttributes() ?>>
<?= $Page->pagado->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->bultos->Visible) { // bultos ?>
        <td data-name="bultos"<?= $Page->bultos->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_facturas_cobranza_bultos" class="el_view_facturas_cobranza_bultos">
<span<?= $Page->bultos->viewAttributes() ?>>
<?= $Page->bultos->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->asesor_asignado->Visible) { // asesor_asignado ?>
        <td data-name="asesor_asignado"<?= $Page->asesor_asignado->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_view_facturas_cobranza_asesor_asignado" class="el_view_facturas_cobranza_asesor_asignado">
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
    ew.addEventHandlers("view_facturas_cobranza");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
