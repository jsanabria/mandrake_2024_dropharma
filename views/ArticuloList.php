<?php

namespace PHPMaker2024\mandrake;

// Page object
$ArticuloList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { articulo: currentTable } });
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
<form name="farticulosrch" id="farticulosrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" autocomplete="off">
<div id="farticulosrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { articulo: currentTable } });
var currentForm;
var farticulosrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("farticulosrch")
        .setPageId("list")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["fabricante", [], fields.fabricante.isInvalid],
            ["categoria", [], fields.categoria.isInvalid],
            ["lista_pedido", [], fields.lista_pedido.isInvalid],
            ["cantidad_en_mano", [ew.Validators.float], fields.cantidad_en_mano.isInvalid],
            ["y_cantidad_en_mano", [ew.Validators.between], false],
            ["cantidad_en_pedido", [ew.Validators.float], fields.cantidad_en_pedido.isInvalid],
            ["y_cantidad_en_pedido", [ew.Validators.between], false],
            ["cantidad_en_transito", [ew.Validators.float], fields.cantidad_en_transito.isInvalid],
            ["y_cantidad_en_transito", [ew.Validators.between], false],
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
            "fabricante": <?= $Page->fabricante->toClientList($Page) ?>,
            "categoria": <?= $Page->categoria->toClientList($Page) ?>,
            "lista_pedido": <?= $Page->lista_pedido->toClientList($Page) ?>,
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
        <div id="el_articulo_fabricante" class="ew-search-field">
    <select
        id="x_fabricante"
        name="x_fabricante"
        class="form-control ew-select<?= $Page->fabricante->isInvalidClass() ?>"
        data-select2-id="farticulosrch_x_fabricante"
        data-table="articulo"
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
loadjs.ready("farticulosrch", function() {
    var options = { name: "x_fabricante", selectId: "farticulosrch_x_fabricante" };
    if (farticulosrch.lists.fabricante?.lookupOptions.length) {
        options.data = { id: "x_fabricante", form: "farticulosrch" };
    } else {
        options.ajax = { id: "x_fabricante", form: "farticulosrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.articulo.fields.fabricante.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->categoria->Visible) { // categoria ?>
<?php
if (!$Page->categoria->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_categoria" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->categoria->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_categoria" class="ew-search-caption ew-label"><?= $Page->categoria->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_categoria" id="z_categoria" value="LIKE">
</div>
        </div>
        <div id="el_articulo_categoria" class="ew-search-field">
    <select
        id="x_categoria"
        name="x_categoria"
        class="form-select ew-select<?= $Page->categoria->isInvalidClass() ?>"
        <?php if (!$Page->categoria->IsNativeSelect) { ?>
        data-select2-id="farticulosrch_x_categoria"
        <?php } ?>
        data-table="articulo"
        data-field="x_categoria"
        data-value-separator="<?= $Page->categoria->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->categoria->getPlaceHolder()) ?>"
        <?= $Page->categoria->editAttributes() ?>>
        <?= $Page->categoria->selectOptionListHtml("x_categoria") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->categoria->getErrorMessage(false) ?></div>
<?= $Page->categoria->Lookup->getParamTag($Page, "p_x_categoria") ?>
<?php if (!$Page->categoria->IsNativeSelect) { ?>
<script>
loadjs.ready("farticulosrch", function() {
    var options = { name: "x_categoria", selectId: "farticulosrch_x_categoria" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (farticulosrch.lists.categoria?.lookupOptions.length) {
        options.data = { id: "x_categoria", form: "farticulosrch" };
    } else {
        options.ajax = { id: "x_categoria", form: "farticulosrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.articulo.fields.categoria.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->lista_pedido->Visible) { // lista_pedido ?>
<?php
if (!$Page->lista_pedido->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_lista_pedido" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->lista_pedido->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_lista_pedido" class="ew-search-caption ew-label"><?= $Page->lista_pedido->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_lista_pedido" id="z_lista_pedido" value="=">
</div>
        </div>
        <div id="el_articulo_lista_pedido" class="ew-search-field">
    <select
        id="x_lista_pedido"
        name="x_lista_pedido"
        class="form-select ew-select<?= $Page->lista_pedido->isInvalidClass() ?>"
        <?php if (!$Page->lista_pedido->IsNativeSelect) { ?>
        data-select2-id="farticulosrch_x_lista_pedido"
        <?php } ?>
        data-table="articulo"
        data-field="x_lista_pedido"
        data-value-separator="<?= $Page->lista_pedido->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->lista_pedido->getPlaceHolder()) ?>"
        <?= $Page->lista_pedido->editAttributes() ?>>
        <?= $Page->lista_pedido->selectOptionListHtml("x_lista_pedido") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->lista_pedido->getErrorMessage(false) ?></div>
<?= $Page->lista_pedido->Lookup->getParamTag($Page, "p_x_lista_pedido") ?>
<?php if (!$Page->lista_pedido->IsNativeSelect) { ?>
<script>
loadjs.ready("farticulosrch", function() {
    var options = { name: "x_lista_pedido", selectId: "farticulosrch_x_lista_pedido" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (farticulosrch.lists.lista_pedido?.lookupOptions.length) {
        options.data = { id: "x_lista_pedido", form: "farticulosrch" };
    } else {
        options.ajax = { id: "x_lista_pedido", form: "farticulosrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.articulo.fields.lista_pedido.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->cantidad_en_mano->Visible) { // cantidad_en_mano ?>
<?php
if (!$Page->cantidad_en_mano->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_cantidad_en_mano" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->cantidad_en_mano->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_cantidad_en_mano" class="ew-search-caption ew-label"><?= $Page->cantidad_en_mano->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_cantidad_en_mano" id="z_cantidad_en_mano" class="form-select ew-operator-select" data-ew-action="search-operator">
<?php foreach ($Page->cantidad_en_mano->SearchOperators as $opr) { ?>
<option value="<?= HtmlEncode($opr) ?>"<?= $Page->cantidad_en_mano->AdvancedSearch->SearchOperator == $opr ? " selected" : "" ?>><?= $Language->phrase($opr == "=" ? "EQUAL" : $opr) ?></option>
<?php } ?>
</select>
</div>
        </div>
        <div id="el_articulo_cantidad_en_mano" class="ew-search-field">
<input type="<?= $Page->cantidad_en_mano->getInputTextType() ?>" name="x_cantidad_en_mano" id="x_cantidad_en_mano" data-table="articulo" data-field="x_cantidad_en_mano" value="<?= $Page->cantidad_en_mano->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->cantidad_en_mano->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cantidad_en_mano->formatPattern()) ?>"<?= $Page->cantidad_en_mano->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->cantidad_en_mano->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_articulo_cantidad_en_mano" class="ew-search-field2 d-none">
<input type="<?= $Page->cantidad_en_mano->getInputTextType() ?>" name="y_cantidad_en_mano" id="y_cantidad_en_mano" data-table="articulo" data-field="x_cantidad_en_mano" value="<?= $Page->cantidad_en_mano->EditValue2 ?>" size="30" placeholder="<?= HtmlEncode($Page->cantidad_en_mano->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cantidad_en_mano->formatPattern()) ?>"<?= $Page->cantidad_en_mano->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->cantidad_en_mano->getErrorMessage(false) ?></div>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->cantidad_en_pedido->Visible) { // cantidad_en_pedido ?>
<?php
if (!$Page->cantidad_en_pedido->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_cantidad_en_pedido" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->cantidad_en_pedido->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_cantidad_en_pedido" class="ew-search-caption ew-label"><?= $Page->cantidad_en_pedido->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_cantidad_en_pedido" id="z_cantidad_en_pedido" class="form-select ew-operator-select" data-ew-action="search-operator">
<?php foreach ($Page->cantidad_en_pedido->SearchOperators as $opr) { ?>
<option value="<?= HtmlEncode($opr) ?>"<?= $Page->cantidad_en_pedido->AdvancedSearch->SearchOperator == $opr ? " selected" : "" ?>><?= $Language->phrase($opr == "=" ? "EQUAL" : $opr) ?></option>
<?php } ?>
</select>
</div>
        </div>
        <div id="el_articulo_cantidad_en_pedido" class="ew-search-field">
<input type="<?= $Page->cantidad_en_pedido->getInputTextType() ?>" name="x_cantidad_en_pedido" id="x_cantidad_en_pedido" data-table="articulo" data-field="x_cantidad_en_pedido" value="<?= $Page->cantidad_en_pedido->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->cantidad_en_pedido->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cantidad_en_pedido->formatPattern()) ?>"<?= $Page->cantidad_en_pedido->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->cantidad_en_pedido->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_articulo_cantidad_en_pedido" class="ew-search-field2 d-none">
<input type="<?= $Page->cantidad_en_pedido->getInputTextType() ?>" name="y_cantidad_en_pedido" id="y_cantidad_en_pedido" data-table="articulo" data-field="x_cantidad_en_pedido" value="<?= $Page->cantidad_en_pedido->EditValue2 ?>" size="30" placeholder="<?= HtmlEncode($Page->cantidad_en_pedido->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cantidad_en_pedido->formatPattern()) ?>"<?= $Page->cantidad_en_pedido->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->cantidad_en_pedido->getErrorMessage(false) ?></div>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->cantidad_en_transito->Visible) { // cantidad_en_transito ?>
<?php
if (!$Page->cantidad_en_transito->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_cantidad_en_transito" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->cantidad_en_transito->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_cantidad_en_transito" class="ew-search-caption ew-label"><?= $Page->cantidad_en_transito->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_cantidad_en_transito" id="z_cantidad_en_transito" class="form-select ew-operator-select" data-ew-action="search-operator">
<?php foreach ($Page->cantidad_en_transito->SearchOperators as $opr) { ?>
<option value="<?= HtmlEncode($opr) ?>"<?= $Page->cantidad_en_transito->AdvancedSearch->SearchOperator == $opr ? " selected" : "" ?>><?= $Language->phrase($opr == "=" ? "EQUAL" : $opr) ?></option>
<?php } ?>
</select>
</div>
        </div>
        <div id="el_articulo_cantidad_en_transito" class="ew-search-field">
<input type="<?= $Page->cantidad_en_transito->getInputTextType() ?>" name="x_cantidad_en_transito" id="x_cantidad_en_transito" data-table="articulo" data-field="x_cantidad_en_transito" value="<?= $Page->cantidad_en_transito->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->cantidad_en_transito->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cantidad_en_transito->formatPattern()) ?>"<?= $Page->cantidad_en_transito->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->cantidad_en_transito->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_articulo_cantidad_en_transito" class="ew-search-field2 d-none">
<input type="<?= $Page->cantidad_en_transito->getInputTextType() ?>" name="y_cantidad_en_transito" id="y_cantidad_en_transito" data-table="articulo" data-field="x_cantidad_en_transito" value="<?= $Page->cantidad_en_transito->EditValue2 ?>" size="30" placeholder="<?= HtmlEncode($Page->cantidad_en_transito->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cantidad_en_transito->formatPattern()) ?>"<?= $Page->cantidad_en_transito->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->cantidad_en_transito->getErrorMessage(false) ?></div>
</div>
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
            <label for="x_activo" class="ew-search-caption ew-label"><?= $Page->activo->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_activo" id="z_activo" value="=">
</div>
        </div>
        <div id="el_articulo_activo" class="ew-search-field">
    <select
        id="x_activo"
        name="x_activo"
        class="form-select ew-select<?= $Page->activo->isInvalidClass() ?>"
        <?php if (!$Page->activo->IsNativeSelect) { ?>
        data-select2-id="farticulosrch_x_activo"
        <?php } ?>
        data-table="articulo"
        data-field="x_activo"
        data-value-separator="<?= $Page->activo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->activo->getPlaceHolder()) ?>"
        <?= $Page->activo->editAttributes() ?>>
        <?= $Page->activo->selectOptionListHtml("x_activo") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->activo->getErrorMessage(false) ?></div>
<?php if (!$Page->activo->IsNativeSelect) { ?>
<script>
loadjs.ready("farticulosrch", function() {
    var options = { name: "x_activo", selectId: "farticulosrch_x_activo" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (farticulosrch.lists.activo?.lookupOptions.length) {
        options.data = { id: "x_activo", form: "farticulosrch" };
    } else {
        options.ajax = { id: "x_activo", form: "farticulosrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.articulo.fields.activo.selectOptions);
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="farticulosrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="farticulosrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="farticulosrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="farticulosrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<input type="hidden" name="t" value="articulo">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_articulo" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_articulolist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Page->foto->Visible) { // foto ?>
        <th data-name="foto" class="<?= $Page->foto->headerCellClass() ?>"><div id="elh_articulo_foto" class="articulo_foto"><?= $Page->renderFieldHeader($Page->foto) ?></div></th>
<?php } ?>
<?php if ($Page->codigo->Visible) { // codigo ?>
        <th data-name="codigo" class="<?= $Page->codigo->headerCellClass() ?>"><div id="elh_articulo_codigo" class="articulo_codigo"><?= $Page->renderFieldHeader($Page->codigo) ?></div></th>
<?php } ?>
<?php if ($Page->nombre_comercial->Visible) { // nombre_comercial ?>
        <th data-name="nombre_comercial" class="<?= $Page->nombre_comercial->headerCellClass() ?>"><div id="elh_articulo_nombre_comercial" class="articulo_nombre_comercial"><?= $Page->renderFieldHeader($Page->nombre_comercial) ?></div></th>
<?php } ?>
<?php if ($Page->principio_activo->Visible) { // principio_activo ?>
        <th data-name="principio_activo" class="<?= $Page->principio_activo->headerCellClass() ?>"><div id="elh_articulo_principio_activo" class="articulo_principio_activo"><?= $Page->renderFieldHeader($Page->principio_activo) ?></div></th>
<?php } ?>
<?php if ($Page->presentacion->Visible) { // presentacion ?>
        <th data-name="presentacion" class="<?= $Page->presentacion->headerCellClass() ?>"><div id="elh_articulo_presentacion" class="articulo_presentacion"><?= $Page->renderFieldHeader($Page->presentacion) ?></div></th>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
        <th data-name="fabricante" class="<?= $Page->fabricante->headerCellClass() ?>"><div id="elh_articulo_fabricante" class="articulo_fabricante"><?= $Page->renderFieldHeader($Page->fabricante) ?></div></th>
<?php } ?>
<?php if ($Page->categoria->Visible) { // categoria ?>
        <th data-name="categoria" class="<?= $Page->categoria->headerCellClass() ?>"><div id="elh_articulo_categoria" class="articulo_categoria"><?= $Page->renderFieldHeader($Page->categoria) ?></div></th>
<?php } ?>
<?php if ($Page->lista_pedido->Visible) { // lista_pedido ?>
        <th data-name="lista_pedido" class="<?= $Page->lista_pedido->headerCellClass() ?>"><div id="elh_articulo_lista_pedido" class="articulo_lista_pedido"><?= $Page->renderFieldHeader($Page->lista_pedido) ?></div></th>
<?php } ?>
<?php if ($Page->cantidad_en_mano->Visible) { // cantidad_en_mano ?>
        <th data-name="cantidad_en_mano" class="<?= $Page->cantidad_en_mano->headerCellClass() ?>"><div id="elh_articulo_cantidad_en_mano" class="articulo_cantidad_en_mano"><?= $Page->renderFieldHeader($Page->cantidad_en_mano) ?></div></th>
<?php } ?>
<?php if ($Page->cantidad_en_almacenes->Visible) { // cantidad_en_almacenes ?>
        <th data-name="cantidad_en_almacenes" class="<?= $Page->cantidad_en_almacenes->headerCellClass() ?>"><div id="elh_articulo_cantidad_en_almacenes" class="articulo_cantidad_en_almacenes"><?= $Page->renderFieldHeader($Page->cantidad_en_almacenes) ?></div></th>
<?php } ?>
<?php if ($Page->cantidad_en_pedido->Visible) { // cantidad_en_pedido ?>
        <th data-name="cantidad_en_pedido" class="<?= $Page->cantidad_en_pedido->headerCellClass() ?>"><div id="elh_articulo_cantidad_en_pedido" class="articulo_cantidad_en_pedido"><?= $Page->renderFieldHeader($Page->cantidad_en_pedido) ?></div></th>
<?php } ?>
<?php if ($Page->cantidad_en_transito->Visible) { // cantidad_en_transito ?>
        <th data-name="cantidad_en_transito" class="<?= $Page->cantidad_en_transito->headerCellClass() ?>"><div id="elh_articulo_cantidad_en_transito" class="articulo_cantidad_en_transito"><?= $Page->renderFieldHeader($Page->cantidad_en_transito) ?></div></th>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
        <th data-name="descuento" class="<?= $Page->descuento->headerCellClass() ?>"><div id="elh_articulo_descuento" class="articulo_descuento"><?= $Page->renderFieldHeader($Page->descuento) ?></div></th>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <th data-name="activo" class="<?= $Page->activo->headerCellClass() ?>"><div id="elh_articulo_activo" class="articulo_activo"><?= $Page->renderFieldHeader($Page->activo) ?></div></th>
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
    <?php if ($Page->foto->Visible) { // foto ?>
        <td data-name="foto"<?= $Page->foto->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_articulo_foto" class="el_articulo_foto">
<span>
<?= GetFileViewTag($Page->foto, $Page->foto->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->codigo->Visible) { // codigo ?>
        <td data-name="codigo"<?= $Page->codigo->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_articulo_codigo" class="el_articulo_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<?= $Page->codigo->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->nombre_comercial->Visible) { // nombre_comercial ?>
        <td data-name="nombre_comercial"<?= $Page->nombre_comercial->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_articulo_nombre_comercial" class="el_articulo_nombre_comercial">
<span<?= $Page->nombre_comercial->viewAttributes() ?>>
<?= $Page->nombre_comercial->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->principio_activo->Visible) { // principio_activo ?>
        <td data-name="principio_activo"<?= $Page->principio_activo->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_articulo_principio_activo" class="el_articulo_principio_activo">
<span<?= $Page->principio_activo->viewAttributes() ?>>
<?= $Page->principio_activo->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->presentacion->Visible) { // presentacion ?>
        <td data-name="presentacion"<?= $Page->presentacion->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_articulo_presentacion" class="el_articulo_presentacion">
<span<?= $Page->presentacion->viewAttributes() ?>>
<?= $Page->presentacion->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->fabricante->Visible) { // fabricante ?>
        <td data-name="fabricante"<?= $Page->fabricante->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_articulo_fabricante" class="el_articulo_fabricante">
<span<?= $Page->fabricante->viewAttributes() ?>>
<?= $Page->fabricante->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->categoria->Visible) { // categoria ?>
        <td data-name="categoria"<?= $Page->categoria->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_articulo_categoria" class="el_articulo_categoria">
<span<?= $Page->categoria->viewAttributes() ?>>
<?= $Page->categoria->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->lista_pedido->Visible) { // lista_pedido ?>
        <td data-name="lista_pedido"<?= $Page->lista_pedido->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_articulo_lista_pedido" class="el_articulo_lista_pedido">
<span<?= $Page->lista_pedido->viewAttributes() ?>>
<?= $Page->lista_pedido->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->cantidad_en_mano->Visible) { // cantidad_en_mano ?>
        <td data-name="cantidad_en_mano"<?= $Page->cantidad_en_mano->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_articulo_cantidad_en_mano" class="el_articulo_cantidad_en_mano">
<span<?= $Page->cantidad_en_mano->viewAttributes() ?>>
<?= $Page->cantidad_en_mano->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->cantidad_en_almacenes->Visible) { // cantidad_en_almacenes ?>
        <td data-name="cantidad_en_almacenes"<?= $Page->cantidad_en_almacenes->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_articulo_cantidad_en_almacenes" class="el_articulo_cantidad_en_almacenes">
<span<?= $Page->cantidad_en_almacenes->viewAttributes() ?>>
<?= $Page->cantidad_en_almacenes->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->cantidad_en_pedido->Visible) { // cantidad_en_pedido ?>
        <td data-name="cantidad_en_pedido"<?= $Page->cantidad_en_pedido->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_articulo_cantidad_en_pedido" class="el_articulo_cantidad_en_pedido">
<span<?= $Page->cantidad_en_pedido->viewAttributes() ?>>
<?= $Page->cantidad_en_pedido->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->cantidad_en_transito->Visible) { // cantidad_en_transito ?>
        <td data-name="cantidad_en_transito"<?= $Page->cantidad_en_transito->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_articulo_cantidad_en_transito" class="el_articulo_cantidad_en_transito">
<span<?= $Page->cantidad_en_transito->viewAttributes() ?>>
<?= $Page->cantidad_en_transito->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->descuento->Visible) { // descuento ?>
        <td data-name="descuento"<?= $Page->descuento->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_articulo_descuento" class="el_articulo_descuento">
<span<?= $Page->descuento->viewAttributes() ?>>
<?= $Page->descuento->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->activo->Visible) { // activo ?>
        <td data-name="activo"<?= $Page->activo->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_articulo_activo" class="el_articulo_activo">
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
    ew.addEventHandlers("articulo");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
