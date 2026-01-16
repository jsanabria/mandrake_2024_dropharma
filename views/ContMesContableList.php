<?php

namespace PHPMaker2024\mandrake;

// Page object
$ContMesContableList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cont_mes_contable: currentTable } });
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
<form name="fcont_mes_contablesrch" id="fcont_mes_contablesrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" autocomplete="off">
<div id="fcont_mes_contablesrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cont_mes_contable: currentTable } });
var currentForm;
var fcont_mes_contablesrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fcont_mes_contablesrch")
        .setPageId("list")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["M01", [], fields.M01.isInvalid],
            ["M02", [], fields.M02.isInvalid],
            ["M03", [], fields.M03.isInvalid],
            ["M04", [], fields.M04.isInvalid],
            ["M05", [], fields.M05.isInvalid],
            ["M06", [], fields.M06.isInvalid],
            ["M07", [], fields.M07.isInvalid],
            ["M08", [], fields.M08.isInvalid],
            ["M09", [], fields.M09.isInvalid],
            ["M10", [], fields.M10.isInvalid],
            ["M11", [], fields.M11.isInvalid],
            ["M12", [], fields.M12.isInvalid],
            ["activo", [], fields.activo.isInvalid]
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
            "M01": <?= $Page->M01->toClientList($Page) ?>,
            "M02": <?= $Page->M02->toClientList($Page) ?>,
            "M03": <?= $Page->M03->toClientList($Page) ?>,
            "M04": <?= $Page->M04->toClientList($Page) ?>,
            "M05": <?= $Page->M05->toClientList($Page) ?>,
            "M06": <?= $Page->M06->toClientList($Page) ?>,
            "M07": <?= $Page->M07->toClientList($Page) ?>,
            "M08": <?= $Page->M08->toClientList($Page) ?>,
            "M09": <?= $Page->M09->toClientList($Page) ?>,
            "M10": <?= $Page->M10->toClientList($Page) ?>,
            "M11": <?= $Page->M11->toClientList($Page) ?>,
            "M12": <?= $Page->M12->toClientList($Page) ?>,
            "activo": <?= $Page->activo->toClientList($Page) ?>,
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
<?php if ($Page->M01->Visible) { // M01 ?>
<?php
if (!$Page->M01->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_M01" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->M01->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->M01->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_M01" id="z_M01" value="=">
</div>
        </div>
        <div id="el_cont_mes_contable_M01" class="ew-search-field">
<template id="tp_x_M01">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="cont_mes_contable" data-field="x_M01" name="x_M01" id="x_M01"<?= $Page->M01->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_M01" class="ew-item-list"></div>
<selection-list hidden
    id="x_M01"
    name="x_M01"
    value="<?= HtmlEncode($Page->M01->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_M01"
    data-target="dsl_x_M01"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M01->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M01"
    data-value-separator="<?= $Page->M01->displayValueSeparatorAttribute() ?>"
    <?= $Page->M01->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->M01->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->M02->Visible) { // M02 ?>
<?php
if (!$Page->M02->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_M02" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->M02->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->M02->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_M02" id="z_M02" value="=">
</div>
        </div>
        <div id="el_cont_mes_contable_M02" class="ew-search-field">
<template id="tp_x_M02">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="cont_mes_contable" data-field="x_M02" name="x_M02" id="x_M02"<?= $Page->M02->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_M02" class="ew-item-list"></div>
<selection-list hidden
    id="x_M02"
    name="x_M02"
    value="<?= HtmlEncode($Page->M02->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_M02"
    data-target="dsl_x_M02"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M02->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M02"
    data-value-separator="<?= $Page->M02->displayValueSeparatorAttribute() ?>"
    <?= $Page->M02->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->M02->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->M03->Visible) { // M03 ?>
<?php
if (!$Page->M03->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_M03" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->M03->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->M03->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_M03" id="z_M03" value="=">
</div>
        </div>
        <div id="el_cont_mes_contable_M03" class="ew-search-field">
<template id="tp_x_M03">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="cont_mes_contable" data-field="x_M03" name="x_M03" id="x_M03"<?= $Page->M03->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_M03" class="ew-item-list"></div>
<selection-list hidden
    id="x_M03"
    name="x_M03"
    value="<?= HtmlEncode($Page->M03->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_M03"
    data-target="dsl_x_M03"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M03->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M03"
    data-value-separator="<?= $Page->M03->displayValueSeparatorAttribute() ?>"
    <?= $Page->M03->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->M03->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->M04->Visible) { // M04 ?>
<?php
if (!$Page->M04->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_M04" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->M04->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->M04->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_M04" id="z_M04" value="=">
</div>
        </div>
        <div id="el_cont_mes_contable_M04" class="ew-search-field">
<template id="tp_x_M04">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="cont_mes_contable" data-field="x_M04" name="x_M04" id="x_M04"<?= $Page->M04->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_M04" class="ew-item-list"></div>
<selection-list hidden
    id="x_M04"
    name="x_M04"
    value="<?= HtmlEncode($Page->M04->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_M04"
    data-target="dsl_x_M04"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M04->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M04"
    data-value-separator="<?= $Page->M04->displayValueSeparatorAttribute() ?>"
    <?= $Page->M04->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->M04->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->M05->Visible) { // M05 ?>
<?php
if (!$Page->M05->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_M05" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->M05->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->M05->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_M05" id="z_M05" value="=">
</div>
        </div>
        <div id="el_cont_mes_contable_M05" class="ew-search-field">
<template id="tp_x_M05">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="cont_mes_contable" data-field="x_M05" name="x_M05" id="x_M05"<?= $Page->M05->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_M05" class="ew-item-list"></div>
<selection-list hidden
    id="x_M05"
    name="x_M05"
    value="<?= HtmlEncode($Page->M05->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_M05"
    data-target="dsl_x_M05"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M05->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M05"
    data-value-separator="<?= $Page->M05->displayValueSeparatorAttribute() ?>"
    <?= $Page->M05->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->M05->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->M06->Visible) { // M06 ?>
<?php
if (!$Page->M06->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_M06" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->M06->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->M06->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_M06" id="z_M06" value="=">
</div>
        </div>
        <div id="el_cont_mes_contable_M06" class="ew-search-field">
<template id="tp_x_M06">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="cont_mes_contable" data-field="x_M06" name="x_M06" id="x_M06"<?= $Page->M06->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_M06" class="ew-item-list"></div>
<selection-list hidden
    id="x_M06"
    name="x_M06"
    value="<?= HtmlEncode($Page->M06->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_M06"
    data-target="dsl_x_M06"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M06->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M06"
    data-value-separator="<?= $Page->M06->displayValueSeparatorAttribute() ?>"
    <?= $Page->M06->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->M06->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->M07->Visible) { // M07 ?>
<?php
if (!$Page->M07->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_M07" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->M07->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->M07->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_M07" id="z_M07" value="=">
</div>
        </div>
        <div id="el_cont_mes_contable_M07" class="ew-search-field">
<template id="tp_x_M07">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="cont_mes_contable" data-field="x_M07" name="x_M07" id="x_M07"<?= $Page->M07->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_M07" class="ew-item-list"></div>
<selection-list hidden
    id="x_M07"
    name="x_M07"
    value="<?= HtmlEncode($Page->M07->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_M07"
    data-target="dsl_x_M07"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M07->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M07"
    data-value-separator="<?= $Page->M07->displayValueSeparatorAttribute() ?>"
    <?= $Page->M07->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->M07->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->M08->Visible) { // M08 ?>
<?php
if (!$Page->M08->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_M08" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->M08->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->M08->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_M08" id="z_M08" value="=">
</div>
        </div>
        <div id="el_cont_mes_contable_M08" class="ew-search-field">
<template id="tp_x_M08">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="cont_mes_contable" data-field="x_M08" name="x_M08" id="x_M08"<?= $Page->M08->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_M08" class="ew-item-list"></div>
<selection-list hidden
    id="x_M08"
    name="x_M08"
    value="<?= HtmlEncode($Page->M08->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_M08"
    data-target="dsl_x_M08"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M08->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M08"
    data-value-separator="<?= $Page->M08->displayValueSeparatorAttribute() ?>"
    <?= $Page->M08->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->M08->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->M09->Visible) { // M09 ?>
<?php
if (!$Page->M09->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_M09" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->M09->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->M09->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_M09" id="z_M09" value="=">
</div>
        </div>
        <div id="el_cont_mes_contable_M09" class="ew-search-field">
<template id="tp_x_M09">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="cont_mes_contable" data-field="x_M09" name="x_M09" id="x_M09"<?= $Page->M09->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_M09" class="ew-item-list"></div>
<selection-list hidden
    id="x_M09"
    name="x_M09"
    value="<?= HtmlEncode($Page->M09->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_M09"
    data-target="dsl_x_M09"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M09->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M09"
    data-value-separator="<?= $Page->M09->displayValueSeparatorAttribute() ?>"
    <?= $Page->M09->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->M09->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->M10->Visible) { // M10 ?>
<?php
if (!$Page->M10->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_M10" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->M10->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->M10->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_M10" id="z_M10" value="=">
</div>
        </div>
        <div id="el_cont_mes_contable_M10" class="ew-search-field">
<template id="tp_x_M10">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="cont_mes_contable" data-field="x_M10" name="x_M10" id="x_M10"<?= $Page->M10->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_M10" class="ew-item-list"></div>
<selection-list hidden
    id="x_M10"
    name="x_M10"
    value="<?= HtmlEncode($Page->M10->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_M10"
    data-target="dsl_x_M10"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M10->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M10"
    data-value-separator="<?= $Page->M10->displayValueSeparatorAttribute() ?>"
    <?= $Page->M10->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->M10->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->M11->Visible) { // M11 ?>
<?php
if (!$Page->M11->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_M11" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->M11->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->M11->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_M11" id="z_M11" value="=">
</div>
        </div>
        <div id="el_cont_mes_contable_M11" class="ew-search-field">
<template id="tp_x_M11">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="cont_mes_contable" data-field="x_M11" name="x_M11" id="x_M11"<?= $Page->M11->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_M11" class="ew-item-list"></div>
<selection-list hidden
    id="x_M11"
    name="x_M11"
    value="<?= HtmlEncode($Page->M11->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_M11"
    data-target="dsl_x_M11"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M11->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M11"
    data-value-separator="<?= $Page->M11->displayValueSeparatorAttribute() ?>"
    <?= $Page->M11->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->M11->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->M12->Visible) { // M12 ?>
<?php
if (!$Page->M12->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_M12" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->M12->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->M12->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_M12" id="z_M12" value="=">
</div>
        </div>
        <div id="el_cont_mes_contable_M12" class="ew-search-field">
<template id="tp_x_M12">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="cont_mes_contable" data-field="x_M12" name="x_M12" id="x_M12"<?= $Page->M12->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_M12" class="ew-item-list"></div>
<selection-list hidden
    id="x_M12"
    name="x_M12"
    value="<?= HtmlEncode($Page->M12->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_M12"
    data-target="dsl_x_M12"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M12->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M12"
    data-value-separator="<?= $Page->M12->displayValueSeparatorAttribute() ?>"
    <?= $Page->M12->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->M12->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
<?php
if (!$Page->activo->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_activo" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->activo->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->activo->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_activo" id="z_activo" value="=">
</div>
        </div>
        <div id="el_cont_mes_contable_activo" class="ew-search-field">
<template id="tp_x_activo">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="cont_mes_contable" data-field="x_activo" name="x_activo" id="x_activo"<?= $Page->activo->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_activo" class="ew-item-list"></div>
<selection-list hidden
    id="x_activo"
    name="x_activo"
    value="<?= HtmlEncode($Page->activo->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_activo"
    data-target="dsl_x_activo"
    data-repeatcolumn="5"
    class="form-control<?= $Page->activo->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_activo"
    data-value-separator="<?= $Page->activo->displayValueSeparatorAttribute() ?>"
    <?= $Page->activo->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->activo->getErrorMessage(false) ?></div>
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fcont_mes_contablesrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fcont_mes_contablesrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fcont_mes_contablesrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fcont_mes_contablesrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<input type="hidden" name="t" value="cont_mes_contable">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_cont_mes_contable" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_cont_mes_contablelist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Page->descripcion->Visible) { // descripcion ?>
        <th data-name="descripcion" class="<?= $Page->descripcion->headerCellClass() ?>"><div id="elh_cont_mes_contable_descripcion" class="cont_mes_contable_descripcion"><?= $Page->renderFieldHeader($Page->descripcion) ?></div></th>
<?php } ?>
<?php if ($Page->M01->Visible) { // M01 ?>
        <th data-name="M01" class="<?= $Page->M01->headerCellClass() ?>"><div id="elh_cont_mes_contable_M01" class="cont_mes_contable_M01"><?= $Page->renderFieldHeader($Page->M01) ?></div></th>
<?php } ?>
<?php if ($Page->M02->Visible) { // M02 ?>
        <th data-name="M02" class="<?= $Page->M02->headerCellClass() ?>"><div id="elh_cont_mes_contable_M02" class="cont_mes_contable_M02"><?= $Page->renderFieldHeader($Page->M02) ?></div></th>
<?php } ?>
<?php if ($Page->M03->Visible) { // M03 ?>
        <th data-name="M03" class="<?= $Page->M03->headerCellClass() ?>"><div id="elh_cont_mes_contable_M03" class="cont_mes_contable_M03"><?= $Page->renderFieldHeader($Page->M03) ?></div></th>
<?php } ?>
<?php if ($Page->M04->Visible) { // M04 ?>
        <th data-name="M04" class="<?= $Page->M04->headerCellClass() ?>"><div id="elh_cont_mes_contable_M04" class="cont_mes_contable_M04"><?= $Page->renderFieldHeader($Page->M04) ?></div></th>
<?php } ?>
<?php if ($Page->M05->Visible) { // M05 ?>
        <th data-name="M05" class="<?= $Page->M05->headerCellClass() ?>"><div id="elh_cont_mes_contable_M05" class="cont_mes_contable_M05"><?= $Page->renderFieldHeader($Page->M05) ?></div></th>
<?php } ?>
<?php if ($Page->M06->Visible) { // M06 ?>
        <th data-name="M06" class="<?= $Page->M06->headerCellClass() ?>"><div id="elh_cont_mes_contable_M06" class="cont_mes_contable_M06"><?= $Page->renderFieldHeader($Page->M06) ?></div></th>
<?php } ?>
<?php if ($Page->M07->Visible) { // M07 ?>
        <th data-name="M07" class="<?= $Page->M07->headerCellClass() ?>"><div id="elh_cont_mes_contable_M07" class="cont_mes_contable_M07"><?= $Page->renderFieldHeader($Page->M07) ?></div></th>
<?php } ?>
<?php if ($Page->M08->Visible) { // M08 ?>
        <th data-name="M08" class="<?= $Page->M08->headerCellClass() ?>"><div id="elh_cont_mes_contable_M08" class="cont_mes_contable_M08"><?= $Page->renderFieldHeader($Page->M08) ?></div></th>
<?php } ?>
<?php if ($Page->M09->Visible) { // M09 ?>
        <th data-name="M09" class="<?= $Page->M09->headerCellClass() ?>"><div id="elh_cont_mes_contable_M09" class="cont_mes_contable_M09"><?= $Page->renderFieldHeader($Page->M09) ?></div></th>
<?php } ?>
<?php if ($Page->M10->Visible) { // M10 ?>
        <th data-name="M10" class="<?= $Page->M10->headerCellClass() ?>"><div id="elh_cont_mes_contable_M10" class="cont_mes_contable_M10"><?= $Page->renderFieldHeader($Page->M10) ?></div></th>
<?php } ?>
<?php if ($Page->M11->Visible) { // M11 ?>
        <th data-name="M11" class="<?= $Page->M11->headerCellClass() ?>"><div id="elh_cont_mes_contable_M11" class="cont_mes_contable_M11"><?= $Page->renderFieldHeader($Page->M11) ?></div></th>
<?php } ?>
<?php if ($Page->M12->Visible) { // M12 ?>
        <th data-name="M12" class="<?= $Page->M12->headerCellClass() ?>"><div id="elh_cont_mes_contable_M12" class="cont_mes_contable_M12"><?= $Page->renderFieldHeader($Page->M12) ?></div></th>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <th data-name="activo" class="<?= $Page->activo->headerCellClass() ?>"><div id="elh_cont_mes_contable_activo" class="cont_mes_contable_activo"><?= $Page->renderFieldHeader($Page->activo) ?></div></th>
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
    <?php if ($Page->descripcion->Visible) { // descripcion ?>
        <td data-name="descripcion"<?= $Page->descripcion->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cont_mes_contable_descripcion" class="el_cont_mes_contable_descripcion">
<span<?= $Page->descripcion->viewAttributes() ?>>
<?= $Page->descripcion->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M01->Visible) { // M01 ?>
        <td data-name="M01"<?= $Page->M01->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cont_mes_contable_M01" class="el_cont_mes_contable_M01">
<span<?= $Page->M01->viewAttributes() ?>>
<?= $Page->M01->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M02->Visible) { // M02 ?>
        <td data-name="M02"<?= $Page->M02->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cont_mes_contable_M02" class="el_cont_mes_contable_M02">
<span<?= $Page->M02->viewAttributes() ?>>
<?= $Page->M02->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M03->Visible) { // M03 ?>
        <td data-name="M03"<?= $Page->M03->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cont_mes_contable_M03" class="el_cont_mes_contable_M03">
<span<?= $Page->M03->viewAttributes() ?>>
<?= $Page->M03->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M04->Visible) { // M04 ?>
        <td data-name="M04"<?= $Page->M04->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cont_mes_contable_M04" class="el_cont_mes_contable_M04">
<span<?= $Page->M04->viewAttributes() ?>>
<?= $Page->M04->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M05->Visible) { // M05 ?>
        <td data-name="M05"<?= $Page->M05->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cont_mes_contable_M05" class="el_cont_mes_contable_M05">
<span<?= $Page->M05->viewAttributes() ?>>
<?= $Page->M05->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M06->Visible) { // M06 ?>
        <td data-name="M06"<?= $Page->M06->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cont_mes_contable_M06" class="el_cont_mes_contable_M06">
<span<?= $Page->M06->viewAttributes() ?>>
<?= $Page->M06->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M07->Visible) { // M07 ?>
        <td data-name="M07"<?= $Page->M07->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cont_mes_contable_M07" class="el_cont_mes_contable_M07">
<span<?= $Page->M07->viewAttributes() ?>>
<?= $Page->M07->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M08->Visible) { // M08 ?>
        <td data-name="M08"<?= $Page->M08->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cont_mes_contable_M08" class="el_cont_mes_contable_M08">
<span<?= $Page->M08->viewAttributes() ?>>
<?= $Page->M08->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M09->Visible) { // M09 ?>
        <td data-name="M09"<?= $Page->M09->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cont_mes_contable_M09" class="el_cont_mes_contable_M09">
<span<?= $Page->M09->viewAttributes() ?>>
<?= $Page->M09->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M10->Visible) { // M10 ?>
        <td data-name="M10"<?= $Page->M10->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cont_mes_contable_M10" class="el_cont_mes_contable_M10">
<span<?= $Page->M10->viewAttributes() ?>>
<?= $Page->M10->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M11->Visible) { // M11 ?>
        <td data-name="M11"<?= $Page->M11->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cont_mes_contable_M11" class="el_cont_mes_contable_M11">
<span<?= $Page->M11->viewAttributes() ?>>
<?= $Page->M11->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M12->Visible) { // M12 ?>
        <td data-name="M12"<?= $Page->M12->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cont_mes_contable_M12" class="el_cont_mes_contable_M12">
<span<?= $Page->M12->viewAttributes() ?>>
<?= $Page->M12->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->activo->Visible) { // activo ?>
        <td data-name="activo"<?= $Page->activo->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_cont_mes_contable_activo" class="el_cont_mes_contable_activo">
<span<?= $Page->activo->viewAttributes() ?>>
<?= $Page->activo->getViewValue() ?></span>
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
    ew.addEventHandlers("cont_mes_contable");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
