<?php

namespace PHPMaker2024\mandrake;

// Page object
$EntradasAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { entradas: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fentradasadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fentradasadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["tipo_documento", [fields.tipo_documento.visible && fields.tipo_documento.required ? ew.Validators.required(fields.tipo_documento.caption) : null], fields.tipo_documento.isInvalid],
            ["nro_documento", [fields.nro_documento.visible && fields.nro_documento.required ? ew.Validators.required(fields.nro_documento.caption) : null], fields.nro_documento.isInvalid],
            ["doc_afectado", [fields.doc_afectado.visible && fields.doc_afectado.required ? ew.Validators.required(fields.doc_afectado.caption) : null], fields.doc_afectado.isInvalid],
            ["nro_control", [fields.nro_control.visible && fields.nro_control.required ? ew.Validators.required(fields.nro_control.caption) : null], fields.nro_control.isInvalid],
            ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(fields.fecha.clientFormatPattern)], fields.fecha.isInvalid],
            ["proveedor", [fields.proveedor.visible && fields.proveedor.required ? ew.Validators.required(fields.proveedor.caption) : null], fields.proveedor.isInvalid],
            ["almacen", [fields.almacen.visible && fields.almacen.required ? ew.Validators.required(fields.almacen.caption) : null], fields.almacen.isInvalid],
            ["documento", [fields.documento.visible && fields.documento.required ? ew.Validators.required(fields.documento.caption) : null], fields.documento.isInvalid],
            ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
            ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
            ["consignacion", [fields.consignacion.visible && fields.consignacion.required ? ew.Validators.required(fields.consignacion.caption) : null], fields.consignacion.isInvalid],
            ["aplica_retencion", [fields.aplica_retencion.visible && fields.aplica_retencion.required ? ew.Validators.required(fields.aplica_retencion.caption) : null], fields.aplica_retencion.isInvalid],
            ["ref_municipal", [fields.ref_municipal.visible && fields.ref_municipal.required ? ew.Validators.required(fields.ref_municipal.caption) : null], fields.ref_municipal.isInvalid],
            ["fecha_registro_retenciones", [fields.fecha_registro_retenciones.visible && fields.fecha_registro_retenciones.required ? ew.Validators.required(fields.fecha_registro_retenciones.caption) : null, ew.Validators.datetime(fields.fecha_registro_retenciones.clientFormatPattern)], fields.fecha_registro_retenciones.isInvalid],
            ["descuento", [fields.descuento.visible && fields.descuento.required ? ew.Validators.required(fields.descuento.caption) : null, ew.Validators.float], fields.descuento.isInvalid],
            ["archivo_pedido", [fields.archivo_pedido.visible && fields.archivo_pedido.required ? ew.Validators.fileRequired(fields.archivo_pedido.caption) : null], fields.archivo_pedido.isInvalid]
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

        // Multi-Page
        .setMultiPage(true)

        // Dynamic selection lists
        .setLists({
            "tipo_documento": <?= $Page->tipo_documento->toClientList($Page) ?>,
            "proveedor": <?= $Page->proveedor->toClientList($Page) ?>,
            "almacen": <?= $Page->almacen->toClientList($Page) ?>,
            "documento": <?= $Page->documento->toClientList($Page) ?>,
            "moneda": <?= $Page->moneda->toClientList($Page) ?>,
            "consignacion": <?= $Page->consignacion->toClientList($Page) ?>,
            "aplica_retencion": <?= $Page->aplica_retencion->toClientList($Page) ?>,
        })
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fentradasadd" id="fentradasadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="entradas">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-multi-page"><!-- multi-page -->
<div class="ew-nav<?= $Page->MultiPages->containerClasses() ?>" id="pages_EntradasAdd"><!-- multi-page tabs -->
    <ul class="<?= $Page->MultiPages->navClasses() ?>" role="tablist">
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(1) ?>" data-bs-target="#tab_entradas1" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_entradas1" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(1)) ?>"><?= $Page->pageCaption(1) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(2) ?>" data-bs-target="#tab_entradas2" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_entradas2" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(2)) ?>"><?= $Page->pageCaption(2) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(3) ?>" data-bs-target="#tab_entradas3" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_entradas3" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(3)) ?>"><?= $Page->pageCaption(3) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(4) ?>" data-bs-target="#tab_entradas4" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_entradas4" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(4)) ?>"><?= $Page->pageCaption(4) ?></button></li>
    </ul>
    <div class="<?= $Page->MultiPages->tabContentClasses() ?>"><!-- multi-page tabs .tab-content -->
        <div class="<?= $Page->MultiPages->tabPaneClasses(1) ?>" id="tab_entradas1" role="tabpanel"><!-- multi-page .tab-pane -->
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <div id="r_tipo_documento"<?= $Page->tipo_documento->rowAttributes() ?>>
        <label id="elh_entradas_tipo_documento" for="x_tipo_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_documento->caption() ?><?= $Page->tipo_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_entradas_tipo_documento">
    <select
        id="x_tipo_documento"
        name="x_tipo_documento"
        class="form-select ew-select<?= $Page->tipo_documento->isInvalidClass() ?>"
        <?php if (!$Page->tipo_documento->IsNativeSelect) { ?>
        data-select2-id="fentradasadd_x_tipo_documento"
        <?php } ?>
        data-table="entradas"
        data-field="x_tipo_documento"
        data-page="1"
        data-value-separator="<?= $Page->tipo_documento->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tipo_documento->getPlaceHolder()) ?>"
        <?= $Page->tipo_documento->editAttributes() ?>>
        <?= $Page->tipo_documento->selectOptionListHtml("x_tipo_documento") ?>
    </select>
    <?= $Page->tipo_documento->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->tipo_documento->getErrorMessage() ?></div>
<?= $Page->tipo_documento->Lookup->getParamTag($Page, "p_x_tipo_documento") ?>
<?php if (!$Page->tipo_documento->IsNativeSelect) { ?>
<script>
loadjs.ready("fentradasadd", function() {
    var options = { name: "x_tipo_documento", selectId: "fentradasadd_x_tipo_documento" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fentradasadd.lists.tipo_documento?.lookupOptions.length) {
        options.data = { id: "x_tipo_documento", form: "fentradasadd" };
    } else {
        options.ajax = { id: "x_tipo_documento", form: "fentradasadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.entradas.fields.tipo_documento.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <div id="r_nro_documento"<?= $Page->nro_documento->rowAttributes() ?>>
        <label id="elh_entradas_nro_documento" for="x_nro_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_documento->caption() ?><?= $Page->nro_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_entradas_nro_documento">
<input type="<?= $Page->nro_documento->getInputTextType() ?>" name="x_nro_documento" id="x_nro_documento" data-table="entradas" data-field="x_nro_documento" value="<?= $Page->nro_documento->EditValue ?>" data-page="1" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->nro_documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nro_documento->formatPattern()) ?>"<?= $Page->nro_documento->editAttributes() ?> aria-describedby="x_nro_documento_help">
<?= $Page->nro_documento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nro_documento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
    <div id="r_doc_afectado"<?= $Page->doc_afectado->rowAttributes() ?>>
        <label id="elh_entradas_doc_afectado" for="x_doc_afectado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->doc_afectado->caption() ?><?= $Page->doc_afectado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->doc_afectado->cellAttributes() ?>>
<span id="el_entradas_doc_afectado">
<input type="<?= $Page->doc_afectado->getInputTextType() ?>" name="x_doc_afectado" id="x_doc_afectado" data-table="entradas" data-field="x_doc_afectado" value="<?= $Page->doc_afectado->EditValue ?>" data-page="1" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->doc_afectado->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->doc_afectado->formatPattern()) ?>"<?= $Page->doc_afectado->editAttributes() ?> aria-describedby="x_doc_afectado_help">
<?= $Page->doc_afectado->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->doc_afectado->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nro_control->Visible) { // nro_control ?>
    <div id="r_nro_control"<?= $Page->nro_control->rowAttributes() ?>>
        <label id="elh_entradas_nro_control" for="x_nro_control" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_control->caption() ?><?= $Page->nro_control->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nro_control->cellAttributes() ?>>
<span id="el_entradas_nro_control">
<input type="<?= $Page->nro_control->getInputTextType() ?>" name="x_nro_control" id="x_nro_control" data-table="entradas" data-field="x_nro_control" value="<?= $Page->nro_control->EditValue ?>" data-page="1" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->nro_control->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nro_control->formatPattern()) ?>"<?= $Page->nro_control->editAttributes() ?> aria-describedby="x_nro_control_help">
<?= $Page->nro_control->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nro_control->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <label id="elh_entradas_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha->cellAttributes() ?>>
<span id="el_entradas_fecha">
<input type="<?= $Page->fecha->getInputTextType() ?>" name="x_fecha" id="x_fecha" data-table="entradas" data-field="x_fecha" value="<?= $Page->fecha->EditValue ?>" data-page="1" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha->formatPattern()) ?>"<?= $Page->fecha->editAttributes() ?> aria-describedby="x_fecha_help">
<?= $Page->fecha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage() ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fentradasadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fentradasadd", "x_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->proveedor->Visible) { // proveedor ?>
    <div id="r_proveedor"<?= $Page->proveedor->rowAttributes() ?>>
        <label id="elh_entradas_proveedor" for="x_proveedor" class="<?= $Page->LeftColumnClass ?>"><?= $Page->proveedor->caption() ?><?= $Page->proveedor->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->proveedor->cellAttributes() ?>>
<span id="el_entradas_proveedor">
    <select
        id="x_proveedor"
        name="x_proveedor"
        class="form-control ew-select<?= $Page->proveedor->isInvalidClass() ?>"
        data-select2-id="fentradasadd_x_proveedor"
        data-table="entradas"
        data-field="x_proveedor"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->proveedor->caption())) ?>"
        data-modal-lookup="true"
        data-page="1"
        data-value-separator="<?= $Page->proveedor->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->proveedor->getPlaceHolder()) ?>"
        <?= $Page->proveedor->editAttributes() ?>>
        <?= $Page->proveedor->selectOptionListHtml("x_proveedor") ?>
    </select>
    <?= $Page->proveedor->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->proveedor->getErrorMessage() ?></div>
<?= $Page->proveedor->Lookup->getParamTag($Page, "p_x_proveedor") ?>
<script>
loadjs.ready("fentradasadd", function() {
    var options = { name: "x_proveedor", selectId: "fentradasadd_x_proveedor" };
    if (fentradasadd.lists.proveedor?.lookupOptions.length) {
        options.data = { id: "x_proveedor", form: "fentradasadd" };
    } else {
        options.ajax = { id: "x_proveedor", form: "fentradasadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.entradas.fields.proveedor.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
    <div id="r_documento"<?= $Page->documento->rowAttributes() ?>>
        <label id="elh_entradas_documento" for="x_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->documento->caption() ?><?= $Page->documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->documento->cellAttributes() ?>>
<span id="el_entradas_documento">
    <select
        id="x_documento"
        name="x_documento"
        class="form-select ew-select<?= $Page->documento->isInvalidClass() ?>"
        <?php if (!$Page->documento->IsNativeSelect) { ?>
        data-select2-id="fentradasadd_x_documento"
        <?php } ?>
        data-table="entradas"
        data-field="x_documento"
        data-page="1"
        data-value-separator="<?= $Page->documento->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->documento->getPlaceHolder()) ?>"
        <?= $Page->documento->editAttributes() ?>>
        <?= $Page->documento->selectOptionListHtml("x_documento") ?>
    </select>
    <?= $Page->documento->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->documento->getErrorMessage() ?></div>
<?php if (!$Page->documento->IsNativeSelect) { ?>
<script>
loadjs.ready("fentradasadd", function() {
    var options = { name: "x_documento", selectId: "fentradasadd_x_documento" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fentradasadd.lists.documento?.lookupOptions.length) {
        options.data = { id: "x_documento", form: "fentradasadd" };
    } else {
        options.ajax = { id: "x_documento", form: "fentradasadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.entradas.fields.documento.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
    <div id="r_descuento"<?= $Page->descuento->rowAttributes() ?>>
        <label id="elh_entradas_descuento" for="x_descuento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descuento->caption() ?><?= $Page->descuento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->descuento->cellAttributes() ?>>
<span id="el_entradas_descuento">
<input type="<?= $Page->descuento->getInputTextType() ?>" name="x_descuento" id="x_descuento" data-table="entradas" data-field="x_descuento" value="<?= $Page->descuento->EditValue ?>" data-page="1" size="10" maxlength="6" placeholder="<?= HtmlEncode($Page->descuento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->descuento->formatPattern()) ?>"<?= $Page->descuento->editAttributes() ?> aria-describedby="x_descuento_help">
<?= $Page->descuento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->descuento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->archivo_pedido->Visible) { // archivo_pedido ?>
    <div id="r_archivo_pedido"<?= $Page->archivo_pedido->rowAttributes() ?>>
        <label id="elh_entradas_archivo_pedido" class="<?= $Page->LeftColumnClass ?>"><?= $Page->archivo_pedido->caption() ?><?= $Page->archivo_pedido->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->archivo_pedido->cellAttributes() ?>>
<span id="el_entradas_archivo_pedido">
<div id="fd_x_archivo_pedido" class="fileinput-button ew-file-drop-zone">
    <input
        type="file"
        id="x_archivo_pedido"
        name="x_archivo_pedido"
        class="form-control ew-file-input"
        title="<?= $Page->archivo_pedido->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="entradas"
        data-field="x_archivo_pedido"
        data-size="255"
        data-accept-file-types="<?= $Page->archivo_pedido->acceptFileTypes() ?>"
        data-max-file-size="<?= $Page->archivo_pedido->UploadMaxFileSize ?>"
        data-max-number-of-files="null"
        data-disable-image-crop="<?= $Page->archivo_pedido->ImageCropper ? 0 : 1 ?>"
        data-page="1"
        aria-describedby="x_archivo_pedido_help"
        <?= ($Page->archivo_pedido->ReadOnly || $Page->archivo_pedido->Disabled) ? " disabled" : "" ?>
        <?= $Page->archivo_pedido->editAttributes() ?>
    >
    <div class="text-body-secondary ew-file-text"><?= $Language->phrase("ChooseFile") ?></div>
    <?= $Page->archivo_pedido->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->archivo_pedido->getErrorMessage() ?></div>
</div>
<input type="hidden" name="fn_x_archivo_pedido" id= "fn_x_archivo_pedido" value="<?= $Page->archivo_pedido->Upload->FileName ?>">
<input type="hidden" name="fa_x_archivo_pedido" id= "fa_x_archivo_pedido" value="0">
<table id="ft_x_archivo_pedido" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
        <div class="<?= $Page->MultiPages->tabPaneClasses(2) ?>" id="tab_entradas2" role="tabpanel"><!-- multi-page .tab-pane -->
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda"<?= $Page->moneda->rowAttributes() ?>>
        <label id="elh_entradas_moneda" for="x_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->moneda->cellAttributes() ?>>
<span id="el_entradas_moneda">
    <select
        id="x_moneda"
        name="x_moneda"
        class="form-select ew-select<?= $Page->moneda->isInvalidClass() ?>"
        <?php if (!$Page->moneda->IsNativeSelect) { ?>
        data-select2-id="fentradasadd_x_moneda"
        <?php } ?>
        data-table="entradas"
        data-field="x_moneda"
        data-page="2"
        data-value-separator="<?= $Page->moneda->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->moneda->getPlaceHolder()) ?>"
        <?= $Page->moneda->editAttributes() ?>>
        <?= $Page->moneda->selectOptionListHtml("x_moneda") ?>
    </select>
    <?= $Page->moneda->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->moneda->getErrorMessage() ?></div>
<?= $Page->moneda->Lookup->getParamTag($Page, "p_x_moneda") ?>
<?php if (!$Page->moneda->IsNativeSelect) { ?>
<script>
loadjs.ready("fentradasadd", function() {
    var options = { name: "x_moneda", selectId: "fentradasadd_x_moneda" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fentradasadd.lists.moneda?.lookupOptions.length) {
        options.data = { id: "x_moneda", form: "fentradasadd" };
    } else {
        options.ajax = { id: "x_moneda", form: "fentradasadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.entradas.fields.moneda.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
        <div class="<?= $Page->MultiPages->tabPaneClasses(3) ?>" id="tab_entradas3" role="tabpanel"><!-- multi-page .tab-pane -->
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->almacen->Visible) { // almacen ?>
    <div id="r_almacen"<?= $Page->almacen->rowAttributes() ?>>
        <label id="elh_entradas_almacen" for="x_almacen" class="<?= $Page->LeftColumnClass ?>"><?= $Page->almacen->caption() ?><?= $Page->almacen->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->almacen->cellAttributes() ?>>
<span id="el_entradas_almacen">
    <select
        id="x_almacen"
        name="x_almacen"
        class="form-select ew-select<?= $Page->almacen->isInvalidClass() ?>"
        <?php if (!$Page->almacen->IsNativeSelect) { ?>
        data-select2-id="fentradasadd_x_almacen"
        <?php } ?>
        data-table="entradas"
        data-field="x_almacen"
        data-page="3"
        data-value-separator="<?= $Page->almacen->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->almacen->getPlaceHolder()) ?>"
        <?= $Page->almacen->editAttributes() ?>>
        <?= $Page->almacen->selectOptionListHtml("x_almacen") ?>
    </select>
    <?= $Page->almacen->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->almacen->getErrorMessage() ?></div>
<?= $Page->almacen->Lookup->getParamTag($Page, "p_x_almacen") ?>
<?php if (!$Page->almacen->IsNativeSelect) { ?>
<script>
loadjs.ready("fentradasadd", function() {
    var options = { name: "x_almacen", selectId: "fentradasadd_x_almacen" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fentradasadd.lists.almacen?.lookupOptions.length) {
        options.data = { id: "x_almacen", form: "fentradasadd" };
    } else {
        options.ajax = { id: "x_almacen", form: "fentradasadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.entradas.fields.almacen.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <div id="r_nota"<?= $Page->nota->rowAttributes() ?>>
        <label id="elh_entradas_nota" for="x_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota->caption() ?><?= $Page->nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nota->cellAttributes() ?>>
<span id="el_entradas_nota">
<textarea data-table="entradas" data-field="x_nota" data-page="3" name="x_nota" id="x_nota" cols="30" rows="3" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>"<?= $Page->nota->editAttributes() ?> aria-describedby="x_nota_help"><?= $Page->nota->EditValue ?></textarea>
<?= $Page->nota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->consignacion->Visible) { // consignacion ?>
    <div id="r_consignacion"<?= $Page->consignacion->rowAttributes() ?>>
        <label id="elh_entradas_consignacion" for="x_consignacion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->consignacion->caption() ?><?= $Page->consignacion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->consignacion->cellAttributes() ?>>
<span id="el_entradas_consignacion">
    <select
        id="x_consignacion"
        name="x_consignacion"
        class="form-select ew-select<?= $Page->consignacion->isInvalidClass() ?>"
        <?php if (!$Page->consignacion->IsNativeSelect) { ?>
        data-select2-id="fentradasadd_x_consignacion"
        <?php } ?>
        data-table="entradas"
        data-field="x_consignacion"
        data-page="3"
        data-value-separator="<?= $Page->consignacion->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->consignacion->getPlaceHolder()) ?>"
        <?= $Page->consignacion->editAttributes() ?>>
        <?= $Page->consignacion->selectOptionListHtml("x_consignacion") ?>
    </select>
    <?= $Page->consignacion->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->consignacion->getErrorMessage() ?></div>
<?php if (!$Page->consignacion->IsNativeSelect) { ?>
<script>
loadjs.ready("fentradasadd", function() {
    var options = { name: "x_consignacion", selectId: "fentradasadd_x_consignacion" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fentradasadd.lists.consignacion?.lookupOptions.length) {
        options.data = { id: "x_consignacion", form: "fentradasadd" };
    } else {
        options.ajax = { id: "x_consignacion", form: "fentradasadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.entradas.fields.consignacion.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
        <div class="<?= $Page->MultiPages->tabPaneClasses(4) ?>" id="tab_entradas4" role="tabpanel"><!-- multi-page .tab-pane -->
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->aplica_retencion->Visible) { // aplica_retencion ?>
    <div id="r_aplica_retencion"<?= $Page->aplica_retencion->rowAttributes() ?>>
        <label id="elh_entradas_aplica_retencion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->aplica_retencion->caption() ?><?= $Page->aplica_retencion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->aplica_retencion->cellAttributes() ?>>
<span id="el_entradas_aplica_retencion">
<template id="tp_x_aplica_retencion">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="entradas" data-field="x_aplica_retencion" name="x_aplica_retencion" id="x_aplica_retencion"<?= $Page->aplica_retencion->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_aplica_retencion" class="ew-item-list"></div>
<selection-list hidden
    id="x_aplica_retencion"
    name="x_aplica_retencion"
    value="<?= HtmlEncode($Page->aplica_retencion->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_aplica_retencion"
    data-target="dsl_x_aplica_retencion"
    data-repeatcolumn="5"
    class="form-control<?= $Page->aplica_retencion->isInvalidClass() ?>"
    data-table="entradas"
    data-field="x_aplica_retencion"
    data-page="4"
    data-value-separator="<?= $Page->aplica_retencion->displayValueSeparatorAttribute() ?>"
    <?= $Page->aplica_retencion->editAttributes() ?>></selection-list>
<?= $Page->aplica_retencion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->aplica_retencion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ref_municipal->Visible) { // ref_municipal ?>
    <div id="r_ref_municipal"<?= $Page->ref_municipal->rowAttributes() ?>>
        <label id="elh_entradas_ref_municipal" for="x_ref_municipal" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ref_municipal->caption() ?><?= $Page->ref_municipal->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ref_municipal->cellAttributes() ?>>
<span id="el_entradas_ref_municipal">
<input type="<?= $Page->ref_municipal->getInputTextType() ?>" name="x_ref_municipal" id="x_ref_municipal" data-table="entradas" data-field="x_ref_municipal" value="<?= $Page->ref_municipal->EditValue ?>" data-page="4" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ref_municipal->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ref_municipal->formatPattern()) ?>"<?= $Page->ref_municipal->editAttributes() ?> aria-describedby="x_ref_municipal_help">
<?= $Page->ref_municipal->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ref_municipal->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha_registro_retenciones->Visible) { // fecha_registro_retenciones ?>
    <div id="r_fecha_registro_retenciones"<?= $Page->fecha_registro_retenciones->rowAttributes() ?>>
        <label id="elh_entradas_fecha_registro_retenciones" for="x_fecha_registro_retenciones" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha_registro_retenciones->caption() ?><?= $Page->fecha_registro_retenciones->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha_registro_retenciones->cellAttributes() ?>>
<span id="el_entradas_fecha_registro_retenciones">
<input type="<?= $Page->fecha_registro_retenciones->getInputTextType() ?>" name="x_fecha_registro_retenciones" id="x_fecha_registro_retenciones" data-table="entradas" data-field="x_fecha_registro_retenciones" value="<?= $Page->fecha_registro_retenciones->EditValue ?>" data-page="4" placeholder="<?= HtmlEncode($Page->fecha_registro_retenciones->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha_registro_retenciones->formatPattern()) ?>"<?= $Page->fecha_registro_retenciones->editAttributes() ?> aria-describedby="x_fecha_registro_retenciones_help">
<?= $Page->fecha_registro_retenciones->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha_registro_retenciones->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
    </div><!-- /multi-page tabs .tab-content -->
</div><!-- /multi-page tabs -->
</div><!-- /multi-page -->
<?php
    if (in_array("entradas_salidas", explode(",", $Page->getCurrentDetailTable())) && $entradas_salidas->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("entradas_salidas", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "EntradasSalidasGrid.php" ?>
<?php } ?>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fentradasadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fentradasadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("entradas");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here, no need to add script tags.
    $( document ).ready(function() {
        $("#r_doc_afectado").hide();
        $("#r_fecha_registro_retenciones").hide();
    });
    $("#x_documento").change(function() {
        if($("#x_documento").val() == "FC") {
        	$("#r_doc_afectado").hide();
        }
        else {
        	$("#r_doc_afectado").show();
        }
    });
});
</script>
