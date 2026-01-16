<?php

namespace PHPMaker2024\mandrake;

// Page object
$SalidasList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { salidas: currentTable } });
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
    // Client script
    // Write your client script here, no need to add script tags.
    function convertir(id) {
    	var url = "convertir_a_factura.php?id=" + id;
    	if(confirm("Está seguro que desear procesar este pedido?")) {
    		window.location.href = url;
    		return true;
    	}
    	else return false;
    }

    function verificar(id) {
    	var url = "verificar_venta.php?id=" + id;
    	if(confirm("Valida que este pedido de venta está correcto?")) {
    		window.location.href = url;
    		return true;
    	}
    	else return false;
    }

    function anular(id) {
    	var url = "anular_venta.php?id=" + id;
    	if(confirm("Esta seguro que desea anular este pedido de venta?")) {
    		window.location.href = url;
    		return true;
    	}
    	else return false;
    }
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
<form name="fsalidassrch" id="fsalidassrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" autocomplete="off">
<div id="fsalidassrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { salidas: currentTable } });
var currentForm;
var fsalidassrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fsalidassrch")
        .setPageId("list")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["nro_documento", [], fields.nro_documento.isInvalid],
            ["fecha", [ew.Validators.datetime(fields.fecha.clientFormatPattern)], fields.fecha.isInvalid],
            ["y_fecha", [ew.Validators.between], false],
            ["cliente", [], fields.cliente.isInvalid],
            ["lista_pedido", [], fields.lista_pedido.isInvalid],
            ["estatus", [], fields.estatus.isInvalid],
            ["asesor", [], fields.asesor.isInvalid],
            ["consignacion", [], fields.consignacion.isInvalid],
            ["factura", [], fields.factura.isInvalid]
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
            "cliente": <?= $Page->cliente->toClientList($Page) ?>,
            "lista_pedido": <?= $Page->lista_pedido->toClientList($Page) ?>,
            "estatus": <?= $Page->estatus->toClientList($Page) ?>,
            "asesor": <?= $Page->asesor->toClientList($Page) ?>,
            "consignacion": <?= $Page->consignacion->toClientList($Page) ?>,
            "factura": <?= $Page->factura->toClientList($Page) ?>,
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
        <div id="el_salidas_nro_documento" class="ew-search-field">
<input type="<?= $Page->nro_documento->getInputTextType() ?>" name="x_nro_documento" id="x_nro_documento" data-table="salidas" data-field="x_nro_documento" value="<?= $Page->nro_documento->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->nro_documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nro_documento->formatPattern()) ?>"<?= $Page->nro_documento->editAttributes() ?>>
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
        <div id="el_salidas_fecha" class="ew-search-field">
<input type="<?= $Page->fecha->getInputTextType() ?>" name="x_fecha" id="x_fecha" data-table="salidas" data-field="x_fecha" value="<?= $Page->fecha->EditValue ?>" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha->formatPattern()) ?>"<?= $Page->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage(false) ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fsalidassrch", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fsalidassrch", "x_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_salidas_fecha" class="ew-search-field2">
<input type="<?= $Page->fecha->getInputTextType() ?>" name="y_fecha" id="y_fecha" data-table="salidas" data-field="x_fecha" value="<?= $Page->fecha->EditValue2 ?>" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha->formatPattern()) ?>"<?= $Page->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage(false) ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fsalidassrch", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fsalidassrch", "y_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
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
        <div id="el_salidas_cliente" class="ew-search-field">
    <select
        id="x_cliente"
        name="x_cliente"
        class="form-control ew-select<?= $Page->cliente->isInvalidClass() ?>"
        data-select2-id="fsalidassrch_x_cliente"
        data-table="salidas"
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
loadjs.ready("fsalidassrch", function() {
    var options = { name: "x_cliente", selectId: "fsalidassrch_x_cliente" };
    if (fsalidassrch.lists.cliente?.lookupOptions.length) {
        options.data = { id: "x_cliente", form: "fsalidassrch" };
    } else {
        options.ajax = { id: "x_cliente", form: "fsalidassrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.salidas.fields.cliente.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
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
        <div id="el_salidas_lista_pedido" class="ew-search-field">
    <select
        id="x_lista_pedido"
        name="x_lista_pedido"
        class="form-select ew-select<?= $Page->lista_pedido->isInvalidClass() ?>"
        <?php if (!$Page->lista_pedido->IsNativeSelect) { ?>
        data-select2-id="fsalidassrch_x_lista_pedido"
        <?php } ?>
        data-table="salidas"
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
loadjs.ready("fsalidassrch", function() {
    var options = { name: "x_lista_pedido", selectId: "fsalidassrch_x_lista_pedido" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsalidassrch.lists.lista_pedido?.lookupOptions.length) {
        options.data = { id: "x_lista_pedido", form: "fsalidassrch" };
    } else {
        options.ajax = { id: "x_lista_pedido", form: "fsalidassrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.salidas.fields.lista_pedido.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
<?php
if (!$Page->estatus->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_estatus" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->estatus->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_estatus" class="ew-search-caption ew-label"><?= $Page->estatus->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_estatus" id="z_estatus" value="=">
</div>
        </div>
        <div id="el_salidas_estatus" class="ew-search-field">
    <select
        id="x_estatus"
        name="x_estatus"
        class="form-select ew-select<?= $Page->estatus->isInvalidClass() ?>"
        <?php if (!$Page->estatus->IsNativeSelect) { ?>
        data-select2-id="fsalidassrch_x_estatus"
        <?php } ?>
        data-table="salidas"
        data-field="x_estatus"
        data-value-separator="<?= $Page->estatus->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->estatus->getPlaceHolder()) ?>"
        <?= $Page->estatus->editAttributes() ?>>
        <?= $Page->estatus->selectOptionListHtml("x_estatus") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->estatus->getErrorMessage(false) ?></div>
<?php if (!$Page->estatus->IsNativeSelect) { ?>
<script>
loadjs.ready("fsalidassrch", function() {
    var options = { name: "x_estatus", selectId: "fsalidassrch_x_estatus" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsalidassrch.lists.estatus?.lookupOptions.length) {
        options.data = { id: "x_estatus", form: "fsalidassrch" };
    } else {
        options.ajax = { id: "x_estatus", form: "fsalidassrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.salidas.fields.estatus.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->asesor->Visible) { // asesor ?>
<?php
if (!$Page->asesor->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_asesor" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->asesor->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_asesor" class="ew-search-caption ew-label"><?= $Page->asesor->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_asesor" id="z_asesor" value="=">
</div>
        </div>
        <div id="el_salidas_asesor" class="ew-search-field">
    <select
        id="x_asesor"
        name="x_asesor"
        class="form-control ew-select<?= $Page->asesor->isInvalidClass() ?>"
        data-select2-id="fsalidassrch_x_asesor"
        data-table="salidas"
        data-field="x_asesor"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->asesor->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->asesor->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->asesor->getPlaceHolder()) ?>"
        <?= $Page->asesor->editAttributes() ?>>
        <?= $Page->asesor->selectOptionListHtml("x_asesor") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->asesor->getErrorMessage(false) ?></div>
<?= $Page->asesor->Lookup->getParamTag($Page, "p_x_asesor") ?>
<script>
loadjs.ready("fsalidassrch", function() {
    var options = { name: "x_asesor", selectId: "fsalidassrch_x_asesor" };
    if (fsalidassrch.lists.asesor?.lookupOptions.length) {
        options.data = { id: "x_asesor", form: "fsalidassrch" };
    } else {
        options.ajax = { id: "x_asesor", form: "fsalidassrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.salidas.fields.asesor.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->consignacion->Visible) { // consignacion ?>
<?php
if (!$Page->consignacion->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_consignacion" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->consignacion->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_consignacion" class="ew-search-caption ew-label"><?= $Page->consignacion->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_consignacion" id="z_consignacion" value="=">
</div>
        </div>
        <div id="el_salidas_consignacion" class="ew-search-field">
    <select
        id="x_consignacion"
        name="x_consignacion"
        class="form-select ew-select<?= $Page->consignacion->isInvalidClass() ?>"
        <?php if (!$Page->consignacion->IsNativeSelect) { ?>
        data-select2-id="fsalidassrch_x_consignacion"
        <?php } ?>
        data-table="salidas"
        data-field="x_consignacion"
        data-value-separator="<?= $Page->consignacion->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->consignacion->getPlaceHolder()) ?>"
        <?= $Page->consignacion->editAttributes() ?>>
        <?= $Page->consignacion->selectOptionListHtml("x_consignacion") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->consignacion->getErrorMessage(false) ?></div>
<?php if (!$Page->consignacion->IsNativeSelect) { ?>
<script>
loadjs.ready("fsalidassrch", function() {
    var options = { name: "x_consignacion", selectId: "fsalidassrch_x_consignacion" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsalidassrch.lists.consignacion?.lookupOptions.length) {
        options.data = { id: "x_consignacion", form: "fsalidassrch" };
    } else {
        options.ajax = { id: "x_consignacion", form: "fsalidassrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.salidas.fields.consignacion.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->factura->Visible) { // factura ?>
<?php
if (!$Page->factura->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_factura" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->factura->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_factura" class="ew-search-caption ew-label"><?= $Page->factura->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_factura" id="z_factura" value="=">
</div>
        </div>
        <div id="el_salidas_factura" class="ew-search-field">
    <select
        id="x_factura"
        name="x_factura"
        class="form-select ew-select<?= $Page->factura->isInvalidClass() ?>"
        <?php if (!$Page->factura->IsNativeSelect) { ?>
        data-select2-id="fsalidassrch_x_factura"
        <?php } ?>
        data-table="salidas"
        data-field="x_factura"
        data-value-separator="<?= $Page->factura->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->factura->getPlaceHolder()) ?>"
        <?= $Page->factura->editAttributes() ?>>
        <?= $Page->factura->selectOptionListHtml("x_factura") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->factura->getErrorMessage(false) ?></div>
<?php if (!$Page->factura->IsNativeSelect) { ?>
<script>
loadjs.ready("fsalidassrch", function() {
    var options = { name: "x_factura", selectId: "fsalidassrch_x_factura" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsalidassrch.lists.factura?.lookupOptions.length) {
        options.data = { id: "x_factura", form: "fsalidassrch" };
    } else {
        options.ajax = { id: "x_factura", form: "fsalidassrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.salidas.fields.factura.selectOptions);
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fsalidassrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fsalidassrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fsalidassrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fsalidassrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<input type="hidden" name="t" value="salidas">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_salidas" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_salidaslist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <th data-name="tipo_documento" class="<?= $Page->tipo_documento->headerCellClass() ?>"><div id="elh_salidas_tipo_documento" class="salidas_tipo_documento"><?= $Page->renderFieldHeader($Page->tipo_documento) ?></div></th>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <th data-name="nro_documento" class="<?= $Page->nro_documento->headerCellClass() ?>"><div id="elh_salidas_nro_documento" class="salidas_nro_documento"><?= $Page->renderFieldHeader($Page->nro_documento) ?></div></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th data-name="fecha" class="<?= $Page->fecha->headerCellClass() ?>"><div id="elh_salidas_fecha" class="salidas_fecha"><?= $Page->renderFieldHeader($Page->fecha) ?></div></th>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
        <th data-name="cliente" class="<?= $Page->cliente->headerCellClass() ?>"><div id="elh_salidas_cliente" class="salidas_cliente"><?= $Page->renderFieldHeader($Page->cliente) ?></div></th>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
        <th data-name="documento" class="<?= $Page->documento->headerCellClass() ?>"><div id="elh_salidas_documento" class="salidas_documento"><?= $Page->renderFieldHeader($Page->documento) ?></div></th>
<?php } ?>
<?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
        <th data-name="doc_afectado" class="<?= $Page->doc_afectado->headerCellClass() ?>"><div id="elh_salidas_doc_afectado" class="salidas_doc_afectado"><?= $Page->renderFieldHeader($Page->doc_afectado) ?></div></th>
<?php } ?>
<?php if ($Page->monto_total->Visible) { // monto_total ?>
        <th data-name="monto_total" class="<?= $Page->monto_total->headerCellClass() ?>"><div id="elh_salidas_monto_total" class="salidas_monto_total"><?= $Page->renderFieldHeader($Page->monto_total) ?></div></th>
<?php } ?>
<?php if ($Page->alicuota_iva->Visible) { // alicuota_iva ?>
        <th data-name="alicuota_iva" class="<?= $Page->alicuota_iva->headerCellClass() ?>"><div id="elh_salidas_alicuota_iva" class="salidas_alicuota_iva"><?= $Page->renderFieldHeader($Page->alicuota_iva) ?></div></th>
<?php } ?>
<?php if ($Page->iva->Visible) { // iva ?>
        <th data-name="iva" class="<?= $Page->iva->headerCellClass() ?>"><div id="elh_salidas_iva" class="salidas_iva"><?= $Page->renderFieldHeader($Page->iva) ?></div></th>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
        <th data-name="total" class="<?= $Page->total->headerCellClass() ?>"><div id="elh_salidas_total" class="salidas_total"><?= $Page->renderFieldHeader($Page->total) ?></div></th>
<?php } ?>
<?php if ($Page->lista_pedido->Visible) { // lista_pedido ?>
        <th data-name="lista_pedido" class="<?= $Page->lista_pedido->headerCellClass() ?>"><div id="elh_salidas_lista_pedido" class="salidas_lista_pedido"><?= $Page->renderFieldHeader($Page->lista_pedido) ?></div></th>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
        <th data-name="nota" class="<?= $Page->nota->headerCellClass() ?>"><div id="elh_salidas_nota" class="salidas_nota"><?= $Page->renderFieldHeader($Page->nota) ?></div></th>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
        <th data-name="_username" class="<?= $Page->_username->headerCellClass() ?>"><div id="elh_salidas__username" class="salidas__username"><?= $Page->renderFieldHeader($Page->_username) ?></div></th>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
        <th data-name="estatus" class="<?= $Page->estatus->headerCellClass() ?>"><div id="elh_salidas_estatus" class="salidas_estatus"><?= $Page->renderFieldHeader($Page->estatus) ?></div></th>
<?php } ?>
<?php if ($Page->asesor->Visible) { // asesor ?>
        <th data-name="asesor" class="<?= $Page->asesor->headerCellClass() ?>"><div id="elh_salidas_asesor" class="salidas_asesor"><?= $Page->renderFieldHeader($Page->asesor) ?></div></th>
<?php } ?>
<?php if ($Page->dias_credito->Visible) { // dias_credito ?>
        <th data-name="dias_credito" class="<?= $Page->dias_credito->headerCellClass() ?>"><div id="elh_salidas_dias_credito" class="salidas_dias_credito"><?= $Page->renderFieldHeader($Page->dias_credito) ?></div></th>
<?php } ?>
<?php if ($Page->consignacion->Visible) { // consignacion ?>
        <th data-name="consignacion" class="<?= $Page->consignacion->headerCellClass() ?>"><div id="elh_salidas_consignacion" class="salidas_consignacion"><?= $Page->renderFieldHeader($Page->consignacion) ?></div></th>
<?php } ?>
<?php if ($Page->unidades->Visible) { // unidades ?>
        <th data-name="unidades" class="<?= $Page->unidades->headerCellClass() ?>"><div id="elh_salidas_unidades" class="salidas_unidades"><?= $Page->renderFieldHeader($Page->unidades) ?></div></th>
<?php } ?>
<?php if ($Page->factura->Visible) { // factura ?>
        <th data-name="factura" class="<?= $Page->factura->headerCellClass() ?>"><div id="elh_salidas_factura" class="salidas_factura"><?= $Page->renderFieldHeader($Page->factura) ?></div></th>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
        <th data-name="nombre" class="<?= $Page->nombre->headerCellClass() ?>"><div id="elh_salidas_nombre" class="salidas_nombre"><?= $Page->renderFieldHeader($Page->nombre) ?></div></th>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
        <th data-name="comprobante" class="<?= $Page->comprobante->headerCellClass() ?>"><div id="elh_salidas_comprobante" class="salidas_comprobante"><?= $Page->renderFieldHeader($Page->comprobante) ?></div></th>
<?php } ?>
<?php if ($Page->nro_despacho->Visible) { // nro_despacho ?>
        <th data-name="nro_despacho" class="<?= $Page->nro_despacho->headerCellClass() ?>"><div id="elh_salidas_nro_despacho" class="salidas_nro_despacho"><?= $Page->renderFieldHeader($Page->nro_despacho) ?></div></th>
<?php } ?>
<?php if ($Page->asesor_asignado->Visible) { // asesor_asignado ?>
        <th data-name="asesor_asignado" class="<?= $Page->asesor_asignado->headerCellClass() ?>"><div id="elh_salidas_asesor_asignado" class="salidas_asesor_asignado"><?= $Page->renderFieldHeader($Page->asesor_asignado) ?></div></th>
<?php } ?>
<?php if ($Page->id_documento_padre->Visible) { // id_documento_padre ?>
        <th data-name="id_documento_padre" class="<?= $Page->id_documento_padre->headerCellClass() ?>"><div id="elh_salidas_id_documento_padre" class="salidas_id_documento_padre"><?= $Page->renderFieldHeader($Page->id_documento_padre) ?></div></th>
<?php } ?>
<?php if ($Page->archivo_pedido->Visible) { // archivo_pedido ?>
        <th data-name="archivo_pedido" class="<?= $Page->archivo_pedido->headerCellClass() ?>"><div id="elh_salidas_archivo_pedido" class="salidas_archivo_pedido"><?= $Page->renderFieldHeader($Page->archivo_pedido) ?></div></th>
<?php } ?>
<?php if ($Page->checker->Visible) { // checker ?>
        <th data-name="checker" class="<?= $Page->checker->headerCellClass() ?>"><div id="elh_salidas_checker" class="salidas_checker"><?= $Page->renderFieldHeader($Page->checker) ?></div></th>
<?php } ?>
<?php if ($Page->checker_date->Visible) { // checker_date ?>
        <th data-name="checker_date" class="<?= $Page->checker_date->headerCellClass() ?>"><div id="elh_salidas_checker_date" class="salidas_checker_date"><?= $Page->renderFieldHeader($Page->checker_date) ?></div></th>
<?php } ?>
<?php if ($Page->packer->Visible) { // packer ?>
        <th data-name="packer" class="<?= $Page->packer->headerCellClass() ?>"><div id="elh_salidas_packer" class="salidas_packer"><?= $Page->renderFieldHeader($Page->packer) ?></div></th>
<?php } ?>
<?php if ($Page->packer_date->Visible) { // packer_date ?>
        <th data-name="packer_date" class="<?= $Page->packer_date->headerCellClass() ?>"><div id="elh_salidas_packer_date" class="salidas_packer_date"><?= $Page->renderFieldHeader($Page->packer_date) ?></div></th>
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
    <?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <td data-name="tipo_documento"<?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_tipo_documento" class="el_salidas_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <td data-name="nro_documento"<?= $Page->nro_documento->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_nro_documento" class="el_salidas_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->fecha->Visible) { // fecha ?>
        <td data-name="fecha"<?= $Page->fecha->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_fecha" class="el_salidas_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->cliente->Visible) { // cliente ?>
        <td data-name="cliente"<?= $Page->cliente->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_cliente" class="el_salidas_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->documento->Visible) { // documento ?>
        <td data-name="documento"<?= $Page->documento->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_documento" class="el_salidas_documento">
<span<?= $Page->documento->viewAttributes() ?>>
<?= $Page->documento->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
        <td data-name="doc_afectado"<?= $Page->doc_afectado->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_doc_afectado" class="el_salidas_doc_afectado">
<span<?= $Page->doc_afectado->viewAttributes() ?>>
<?= $Page->doc_afectado->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->monto_total->Visible) { // monto_total ?>
        <td data-name="monto_total"<?= $Page->monto_total->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_monto_total" class="el_salidas_monto_total">
<span<?= $Page->monto_total->viewAttributes() ?>>
<?php if (!EmptyString($Page->monto_total->getViewValue()) && $Page->monto_total->linkAttributes() != "") { ?>
<a<?= $Page->monto_total->linkAttributes() ?>><?= $Page->monto_total->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->monto_total->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->alicuota_iva->Visible) { // alicuota_iva ?>
        <td data-name="alicuota_iva"<?= $Page->alicuota_iva->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_alicuota_iva" class="el_salidas_alicuota_iva">
<span<?= $Page->alicuota_iva->viewAttributes() ?>>
<?= $Page->alicuota_iva->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->iva->Visible) { // iva ?>
        <td data-name="iva"<?= $Page->iva->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_iva" class="el_salidas_iva">
<span<?= $Page->iva->viewAttributes() ?>>
<?= $Page->iva->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->total->Visible) { // total ?>
        <td data-name="total"<?= $Page->total->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_total" class="el_salidas_total">
<span<?= $Page->total->viewAttributes() ?>>
<?= $Page->total->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->lista_pedido->Visible) { // lista_pedido ?>
        <td data-name="lista_pedido"<?= $Page->lista_pedido->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_lista_pedido" class="el_salidas_lista_pedido">
<span<?= $Page->lista_pedido->viewAttributes() ?>>
<?= $Page->lista_pedido->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->nota->Visible) { // nota ?>
        <td data-name="nota"<?= $Page->nota->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_nota" class="el_salidas_nota">
<span<?= $Page->nota->viewAttributes() ?>>
<?= $Page->nota->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->_username->Visible) { // username ?>
        <td data-name="_username"<?= $Page->_username->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas__username" class="el_salidas__username">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->estatus->Visible) { // estatus ?>
        <td data-name="estatus"<?= $Page->estatus->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_estatus" class="el_salidas_estatus">
<span<?= $Page->estatus->viewAttributes() ?>>
<?= $Page->estatus->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->asesor->Visible) { // asesor ?>
        <td data-name="asesor"<?= $Page->asesor->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_asesor" class="el_salidas_asesor">
<span<?= $Page->asesor->viewAttributes() ?>>
<?= $Page->asesor->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->dias_credito->Visible) { // dias_credito ?>
        <td data-name="dias_credito"<?= $Page->dias_credito->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_dias_credito" class="el_salidas_dias_credito">
<span<?= $Page->dias_credito->viewAttributes() ?>>
<?= $Page->dias_credito->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->consignacion->Visible) { // consignacion ?>
        <td data-name="consignacion"<?= $Page->consignacion->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_consignacion" class="el_salidas_consignacion">
<span<?= $Page->consignacion->viewAttributes() ?>>
<?= $Page->consignacion->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->unidades->Visible) { // unidades ?>
        <td data-name="unidades"<?= $Page->unidades->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_unidades" class="el_salidas_unidades">
<span<?= $Page->unidades->viewAttributes() ?>>
<?= $Page->unidades->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->factura->Visible) { // factura ?>
        <td data-name="factura"<?= $Page->factura->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_factura" class="el_salidas_factura">
<span<?= $Page->factura->viewAttributes() ?>>
<?= $Page->factura->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->nombre->Visible) { // nombre ?>
        <td data-name="nombre"<?= $Page->nombre->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_nombre" class="el_salidas_nombre">
<span<?= $Page->nombre->viewAttributes() ?>>
<?= $Page->nombre->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->comprobante->Visible) { // comprobante ?>
        <td data-name="comprobante"<?= $Page->comprobante->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_comprobante" class="el_salidas_comprobante">
<span<?= $Page->comprobante->viewAttributes() ?>>
<?php if (!EmptyString($Page->comprobante->getViewValue()) && $Page->comprobante->linkAttributes() != "") { ?>
<a<?= $Page->comprobante->linkAttributes() ?>><?= $Page->comprobante->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->comprobante->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->nro_despacho->Visible) { // nro_despacho ?>
        <td data-name="nro_despacho"<?= $Page->nro_despacho->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_nro_despacho" class="el_salidas_nro_despacho">
<span<?= $Page->nro_despacho->viewAttributes() ?>>
<?= $Page->nro_despacho->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->asesor_asignado->Visible) { // asesor_asignado ?>
        <td data-name="asesor_asignado"<?= $Page->asesor_asignado->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_asesor_asignado" class="el_salidas_asesor_asignado">
<span<?= $Page->asesor_asignado->viewAttributes() ?>>
<?= $Page->asesor_asignado->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->id_documento_padre->Visible) { // id_documento_padre ?>
        <td data-name="id_documento_padre"<?= $Page->id_documento_padre->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_id_documento_padre" class="el_salidas_id_documento_padre">
<span<?= $Page->id_documento_padre->viewAttributes() ?>>
<?= $Page->id_documento_padre->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->archivo_pedido->Visible) { // archivo_pedido ?>
        <td data-name="archivo_pedido"<?= $Page->archivo_pedido->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_archivo_pedido" class="el_salidas_archivo_pedido">
<span<?= $Page->archivo_pedido->viewAttributes() ?>>
<?= GetFileViewTag($Page->archivo_pedido, $Page->archivo_pedido->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->checker->Visible) { // checker ?>
        <td data-name="checker"<?= $Page->checker->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_checker" class="el_salidas_checker">
<span<?= $Page->checker->viewAttributes() ?>>
<?= $Page->checker->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->checker_date->Visible) { // checker_date ?>
        <td data-name="checker_date"<?= $Page->checker_date->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_checker_date" class="el_salidas_checker_date">
<span<?= $Page->checker_date->viewAttributes() ?>>
<?= $Page->checker_date->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->packer->Visible) { // packer ?>
        <td data-name="packer"<?= $Page->packer->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_packer" class="el_salidas_packer">
<span<?= $Page->packer->viewAttributes() ?>>
<?= $Page->packer->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->packer_date->Visible) { // packer_date ?>
        <td data-name="packer_date"<?= $Page->packer_date->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_salidas_packer_date" class="el_salidas_packer_date">
<span<?= $Page->packer_date->viewAttributes() ?>>
<?= $Page->packer_date->getViewValue() ?></span>
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
    ew.addEventHandlers("salidas");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here
    // document.write("page loaded");
    $(document).ready(function() {
    	//$(".ewAdd").hide(); // hide Add button
    	//$(".ewDetailAdd").hide(); // hide Add master/detail button
    	//$('[data-phrase="MasterDetailListLink"]').hide();
    	//ew-detail-option ew-list-option-separator text-nowrap
    	//$('.ew-list-option-separator').hide();
    	$('.ew-detail-add').hide();
    });
    $("#btnResumen").click(function(){
    	var xfecha = $("#x_fecha").val();
    	var yfecha = $("#y_fecha").val();
    	var xcliente = $("#x_cliente").val();
    	var xasesor = $("#x_asesor").val();
    	var xboton = $("#btnResumen").text();
    	var url = "";
    	if(xboton == "Resumen de Venta")
    		url = "reportes/resumen_de_venta.php?xtitulo=" + xboton + "&xcliente=" + xcliente + "&xasesor=" + xasesor + "&xfecha=" + xfecha + "&yfecha=" + yfecha;
    	else
    		url = "reportes/resumen_de_facturacion.php?xtitulo=" + xboton + "&xcliente=" + xcliente + "&xasesor=" + xasesor + "&xfecha=" + xfecha + "&yfecha=" + yfecha;
    	if(xfecha == "" || yfecha == "") {
    		alert("Debe indicar fecha desde y hasta");
    		return false;
    	}
    	window.open(url, 'Resumen');	
    });
    $("#btnGanancia").click(function(){
    	var xfecha = $("#x_fecha").val();
    	var yfecha = $("#y_fecha").val();
    	var xcliente = $("#x_cliente").val();
    	var xasesor = $("#x_asesor").val();
    	var xboton = $("#btnResumen").text();
    	var url = "";

    	//if(confirm("Resumen según último costo compra? de lo contrario según la fecha de compra del lote."))
    		url = "reportes/resumen_de_ganancia_ultimo_precio.php?xtitulo=" + xboton + "&xcliente=" + xcliente + "&xasesor=" + xasesor + "&xfecha=" + xfecha + "&yfecha=" + yfecha;
    	/*else
    		url = "reportes/resumen_de_ganancia_lote_precio.php?xtitulo=" + xboton + "&xcliente=" + xcliente + "&xasesor=" + xasesor + "&xfecha=" + xfecha + "&yfecha=" + yfecha;
    	*/
    	if(xfecha == "" || yfecha == "") {
    		alert("Debe indicar fecha desde y hasta");
    		return false;
    	}
    	window.open(url, 'Resumen');	
    });
    $("#btnResumen2").click(function(){
    	var xfecha = $("#x_fecha").val();
    	var yfecha = $("#y_fecha").val();
    	var xcliente = $("#x_cliente").val();
    	var xasesor = $("#x_asesor").val();
    	var xboton = $("#btnResumen2").text();
    	var url = "";

    	//if(xboton == "Resumen de Venta")
    		//url = "reportes/resumen_de_venta.php?xtitulo=" + xboton + "&xcliente=" + xcliente + "&xasesor=" + xasesor + "&xfecha=" + xfecha + "&yfecha=" + yfecha;
    	//else
    		url = "reportes/resumen_de_facturacion2.php?xtitulo=" + xboton + "&xcliente=" + xcliente + "&xasesor=" + xasesor + "&xfecha=" + xfecha + "&yfecha=" + yfecha;
    	if(xfecha == "" || yfecha == "") {
    		alert("Debe indicar fecha desde y hasta");
    		return false;
    	}
    	window.open(url, 'Resumen');	
    });
});
</script>
<?php } ?>
