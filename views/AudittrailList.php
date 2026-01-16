<?php

namespace PHPMaker2024\mandrake;

// Page object
$AudittrailList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { audittrail: currentTable } });
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
<form name="faudittrailsrch" id="faudittrailsrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" autocomplete="off">
<div id="faudittrailsrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { audittrail: currentTable } });
var currentForm;
var faudittrailsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("faudittrailsrch")
        .setPageId("list")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["datetime", [ew.Validators.datetime(fields.datetime.clientFormatPattern)], fields.datetime.isInvalid],
            ["y_datetime", [ew.Validators.between], false],
            ["script", [], fields.script.isInvalid],
            ["user", [], fields.user.isInvalid],
            ["_action", [], fields._action.isInvalid],
            ["_table", [], fields._table.isInvalid],
            ["keyvalue", [], fields.keyvalue.isInvalid],
            ["oldvalue", [], fields.oldvalue.isInvalid],
            ["newvalue", [], fields.newvalue.isInvalid]
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
<?php if ($Page->datetime->Visible) { // datetime ?>
<?php
if (!$Page->datetime->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_datetime" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->datetime->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_datetime" class="ew-search-caption ew-label"><?= $Page->datetime->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("BETWEEN") ?>
<input type="hidden" name="z_datetime" id="z_datetime" value="BETWEEN">
</div>
        </div>
        <div id="el_audittrail_datetime" class="ew-search-field">
<input type="<?= $Page->datetime->getInputTextType() ?>" name="x_datetime" id="x_datetime" data-table="audittrail" data-field="x_datetime" value="<?= $Page->datetime->EditValue ?>" placeholder="<?= HtmlEncode($Page->datetime->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->datetime->formatPattern()) ?>"<?= $Page->datetime->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->datetime->getErrorMessage(false) ?></div>
<?php if (!$Page->datetime->ReadOnly && !$Page->datetime->Disabled && !isset($Page->datetime->EditAttrs["readonly"]) && !isset($Page->datetime->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["faudittrailsrch", "datetimepicker"], function () {
    let format = "<?= DateFormat(11) ?>",
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
    ew.createDateTimePicker("faudittrailsrch", "x_datetime", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_audittrail_datetime" class="ew-search-field2">
<input type="<?= $Page->datetime->getInputTextType() ?>" name="y_datetime" id="y_datetime" data-table="audittrail" data-field="x_datetime" value="<?= $Page->datetime->EditValue2 ?>" placeholder="<?= HtmlEncode($Page->datetime->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->datetime->formatPattern()) ?>"<?= $Page->datetime->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->datetime->getErrorMessage(false) ?></div>
<?php if (!$Page->datetime->ReadOnly && !$Page->datetime->Disabled && !isset($Page->datetime->EditAttrs["readonly"]) && !isset($Page->datetime->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["faudittrailsrch", "datetimepicker"], function () {
    let format = "<?= DateFormat(11) ?>",
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
    ew.createDateTimePicker("faudittrailsrch", "y_datetime", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->script->Visible) { // script ?>
<?php
if (!$Page->script->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_script" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->script->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_script" class="ew-search-caption ew-label"><?= $Page->script->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_script" id="z_script" value="LIKE">
</div>
        </div>
        <div id="el_audittrail_script" class="ew-search-field">
<input type="<?= $Page->script->getInputTextType() ?>" name="x_script" id="x_script" data-table="audittrail" data-field="x_script" value="<?= $Page->script->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->script->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->script->formatPattern()) ?>"<?= $Page->script->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->script->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->user->Visible) { // user ?>
<?php
if (!$Page->user->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_user" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->user->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_user" class="ew-search-caption ew-label"><?= $Page->user->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_user" id="z_user" value="LIKE">
</div>
        </div>
        <div id="el_audittrail_user" class="ew-search-field">
<input type="<?= $Page->user->getInputTextType() ?>" name="x_user" id="x_user" data-table="audittrail" data-field="x_user" value="<?= $Page->user->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->user->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->user->formatPattern()) ?>"<?= $Page->user->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->user->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->_action->Visible) { // action ?>
<?php
if (!$Page->_action->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs__action" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->_action->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x__action" class="ew-search-caption ew-label"><?= $Page->_action->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z__action" id="z__action" value="LIKE">
</div>
        </div>
        <div id="el_audittrail__action" class="ew-search-field">
<input type="<?= $Page->_action->getInputTextType() ?>" name="x__action" id="x__action" data-table="audittrail" data-field="x__action" value="<?= $Page->_action->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->_action->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_action->formatPattern()) ?>"<?= $Page->_action->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->_action->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->_table->Visible) { // table ?>
<?php
if (!$Page->_table->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs__table" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->_table->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x__table" class="ew-search-caption ew-label"><?= $Page->_table->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z__table" id="z__table" value="LIKE">
</div>
        </div>
        <div id="el_audittrail__table" class="ew-search-field">
<input type="<?= $Page->_table->getInputTextType() ?>" name="x__table" id="x__table" data-table="audittrail" data-field="x__table" value="<?= $Page->_table->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->_table->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_table->formatPattern()) ?>"<?= $Page->_table->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->_table->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->keyvalue->Visible) { // keyvalue ?>
<?php
if (!$Page->keyvalue->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_keyvalue" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->keyvalue->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_keyvalue" class="ew-search-caption ew-label"><?= $Page->keyvalue->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_keyvalue" id="z_keyvalue" value="LIKE">
</div>
        </div>
        <div id="el_audittrail_keyvalue" class="ew-search-field">
<input type="<?= $Page->keyvalue->getInputTextType() ?>" name="x_keyvalue" id="x_keyvalue" data-table="audittrail" data-field="x_keyvalue" value="<?= $Page->keyvalue->EditValue ?>" size="35" placeholder="<?= HtmlEncode($Page->keyvalue->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->keyvalue->formatPattern()) ?>"<?= $Page->keyvalue->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->keyvalue->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->oldvalue->Visible) { // oldvalue ?>
<?php
if (!$Page->oldvalue->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_oldvalue" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->oldvalue->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_oldvalue" class="ew-search-caption ew-label"><?= $Page->oldvalue->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_oldvalue" id="z_oldvalue" value="LIKE">
</div>
        </div>
        <div id="el_audittrail_oldvalue" class="ew-search-field">
<input type="<?= $Page->oldvalue->getInputTextType() ?>" name="x_oldvalue" id="x_oldvalue" data-table="audittrail" data-field="x_oldvalue" value="<?= $Page->oldvalue->EditValue ?>" size="35" placeholder="<?= HtmlEncode($Page->oldvalue->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->oldvalue->formatPattern()) ?>"<?= $Page->oldvalue->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->oldvalue->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->newvalue->Visible) { // newvalue ?>
<?php
if (!$Page->newvalue->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_newvalue" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->newvalue->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_newvalue" class="ew-search-caption ew-label"><?= $Page->newvalue->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_newvalue" id="z_newvalue" value="LIKE">
</div>
        </div>
        <div id="el_audittrail_newvalue" class="ew-search-field">
<input type="<?= $Page->newvalue->getInputTextType() ?>" name="x_newvalue" id="x_newvalue" data-table="audittrail" data-field="x_newvalue" value="<?= $Page->newvalue->EditValue ?>" size="35" placeholder="<?= HtmlEncode($Page->newvalue->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->newvalue->formatPattern()) ?>"<?= $Page->newvalue->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->newvalue->getErrorMessage(false) ?></div>
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="faudittrailsrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="faudittrailsrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="faudittrailsrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="faudittrailsrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<input type="hidden" name="t" value="audittrail">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_audittrail" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_audittraillist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
        <th data-name="id" class="<?= $Page->id->headerCellClass() ?>"><div id="elh_audittrail_id" class="audittrail_id"><?= $Page->renderFieldHeader($Page->id) ?></div></th>
<?php } ?>
<?php if ($Page->datetime->Visible) { // datetime ?>
        <th data-name="datetime" class="<?= $Page->datetime->headerCellClass() ?>"><div id="elh_audittrail_datetime" class="audittrail_datetime"><?= $Page->renderFieldHeader($Page->datetime) ?></div></th>
<?php } ?>
<?php if ($Page->script->Visible) { // script ?>
        <th data-name="script" class="<?= $Page->script->headerCellClass() ?>"><div id="elh_audittrail_script" class="audittrail_script"><?= $Page->renderFieldHeader($Page->script) ?></div></th>
<?php } ?>
<?php if ($Page->user->Visible) { // user ?>
        <th data-name="user" class="<?= $Page->user->headerCellClass() ?>"><div id="elh_audittrail_user" class="audittrail_user"><?= $Page->renderFieldHeader($Page->user) ?></div></th>
<?php } ?>
<?php if ($Page->_action->Visible) { // action ?>
        <th data-name="_action" class="<?= $Page->_action->headerCellClass() ?>"><div id="elh_audittrail__action" class="audittrail__action"><?= $Page->renderFieldHeader($Page->_action) ?></div></th>
<?php } ?>
<?php if ($Page->_table->Visible) { // table ?>
        <th data-name="_table" class="<?= $Page->_table->headerCellClass() ?>"><div id="elh_audittrail__table" class="audittrail__table"><?= $Page->renderFieldHeader($Page->_table) ?></div></th>
<?php } ?>
<?php if ($Page->field->Visible) { // field ?>
        <th data-name="field" class="<?= $Page->field->headerCellClass() ?>"><div id="elh_audittrail_field" class="audittrail_field"><?= $Page->renderFieldHeader($Page->field) ?></div></th>
<?php } ?>
<?php if ($Page->keyvalue->Visible) { // keyvalue ?>
        <th data-name="keyvalue" class="<?= $Page->keyvalue->headerCellClass() ?>"><div id="elh_audittrail_keyvalue" class="audittrail_keyvalue"><?= $Page->renderFieldHeader($Page->keyvalue) ?></div></th>
<?php } ?>
<?php if ($Page->oldvalue->Visible) { // oldvalue ?>
        <th data-name="oldvalue" class="<?= $Page->oldvalue->headerCellClass() ?>"><div id="elh_audittrail_oldvalue" class="audittrail_oldvalue"><?= $Page->renderFieldHeader($Page->oldvalue) ?></div></th>
<?php } ?>
<?php if ($Page->newvalue->Visible) { // newvalue ?>
        <th data-name="newvalue" class="<?= $Page->newvalue->headerCellClass() ?>"><div id="elh_audittrail_newvalue" class="audittrail_newvalue"><?= $Page->renderFieldHeader($Page->newvalue) ?></div></th>
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
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_audittrail_id" class="el_audittrail_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->datetime->Visible) { // datetime ?>
        <td data-name="datetime"<?= $Page->datetime->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_audittrail_datetime" class="el_audittrail_datetime">
<span<?= $Page->datetime->viewAttributes() ?>>
<?= $Page->datetime->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->script->Visible) { // script ?>
        <td data-name="script"<?= $Page->script->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_audittrail_script" class="el_audittrail_script">
<span<?= $Page->script->viewAttributes() ?>>
<?= $Page->script->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->user->Visible) { // user ?>
        <td data-name="user"<?= $Page->user->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_audittrail_user" class="el_audittrail_user">
<span<?= $Page->user->viewAttributes() ?>>
<?= $Page->user->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->_action->Visible) { // action ?>
        <td data-name="_action"<?= $Page->_action->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_audittrail__action" class="el_audittrail__action">
<span<?= $Page->_action->viewAttributes() ?>>
<?= $Page->_action->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->_table->Visible) { // table ?>
        <td data-name="_table"<?= $Page->_table->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_audittrail__table" class="el_audittrail__table">
<span<?= $Page->_table->viewAttributes() ?>>
<?= $Page->_table->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->field->Visible) { // field ?>
        <td data-name="field"<?= $Page->field->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_audittrail_field" class="el_audittrail_field">
<span<?= $Page->field->viewAttributes() ?>>
<?= $Page->field->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->keyvalue->Visible) { // keyvalue ?>
        <td data-name="keyvalue"<?= $Page->keyvalue->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_audittrail_keyvalue" class="el_audittrail_keyvalue">
<span<?= $Page->keyvalue->viewAttributes() ?>>
<?= $Page->keyvalue->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->oldvalue->Visible) { // oldvalue ?>
        <td data-name="oldvalue"<?= $Page->oldvalue->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_audittrail_oldvalue" class="el_audittrail_oldvalue">
<span<?= $Page->oldvalue->viewAttributes() ?>>
<?= $Page->oldvalue->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->newvalue->Visible) { // newvalue ?>
        <td data-name="newvalue"<?= $Page->newvalue->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_audittrail_newvalue" class="el_audittrail_newvalue">
<span<?= $Page->newvalue->viewAttributes() ?>>
<?= $Page->newvalue->getViewValue() ?></span>
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
    ew.addEventHandlers("audittrail");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
