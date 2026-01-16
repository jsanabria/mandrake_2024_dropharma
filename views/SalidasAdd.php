<?php

namespace PHPMaker2024\mandrake;

// Page object
$SalidasAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { salidas: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fsalidasadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fsalidasadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["tipo_documento", [fields.tipo_documento.visible && fields.tipo_documento.required ? ew.Validators.required(fields.tipo_documento.caption) : null], fields.tipo_documento.isInvalid],
            ["cliente", [fields.cliente.visible && fields.cliente.required ? ew.Validators.required(fields.cliente.caption) : null], fields.cliente.isInvalid],
            ["documento", [fields.documento.visible && fields.documento.required ? ew.Validators.required(fields.documento.caption) : null], fields.documento.isInvalid],
            ["doc_afectado", [fields.doc_afectado.visible && fields.doc_afectado.required ? ew.Validators.required(fields.doc_afectado.caption) : null], fields.doc_afectado.isInvalid],
            ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
            ["lista_pedido", [fields.lista_pedido.visible && fields.lista_pedido.required ? ew.Validators.required(fields.lista_pedido.caption) : null], fields.lista_pedido.isInvalid],
            ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
            ["dias_credito", [fields.dias_credito.visible && fields.dias_credito.required ? ew.Validators.required(fields.dias_credito.caption) : null], fields.dias_credito.isInvalid],
            ["consignacion", [fields.consignacion.visible && fields.consignacion.required ? ew.Validators.required(fields.consignacion.caption) : null], fields.consignacion.isInvalid],
            ["factura", [fields.factura.visible && fields.factura.required ? ew.Validators.required(fields.factura.caption) : null], fields.factura.isInvalid],
            ["nro_despacho", [fields.nro_despacho.visible && fields.nro_despacho.required ? ew.Validators.required(fields.nro_despacho.caption) : null], fields.nro_despacho.isInvalid],
            ["asesor_asignado", [fields.asesor_asignado.visible && fields.asesor_asignado.required ? ew.Validators.required(fields.asesor_asignado.caption) : null], fields.asesor_asignado.isInvalid],
            ["archivo_pedido", [fields.archivo_pedido.visible && fields.archivo_pedido.required ? ew.Validators.fileRequired(fields.archivo_pedido.caption) : null], fields.archivo_pedido.isInvalid],
            ["checker", [fields.checker.visible && fields.checker.required ? ew.Validators.required(fields.checker.caption) : null], fields.checker.isInvalid],
            ["checker_date", [fields.checker_date.visible && fields.checker_date.required ? ew.Validators.required(fields.checker_date.caption) : null, ew.Validators.datetime(fields.checker_date.clientFormatPattern)], fields.checker_date.isInvalid],
            ["packer", [fields.packer.visible && fields.packer.required ? ew.Validators.required(fields.packer.caption) : null], fields.packer.isInvalid],
            ["packer_date", [fields.packer_date.visible && fields.packer_date.required ? ew.Validators.required(fields.packer_date.caption) : null, ew.Validators.datetime(fields.packer_date.clientFormatPattern)], fields.packer_date.isInvalid],
            ["fotos", [fields.fotos.visible && fields.fotos.required ? ew.Validators.fileRequired(fields.fotos.caption) : null], fields.fotos.isInvalid]
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
            "cliente": <?= $Page->cliente->toClientList($Page) ?>,
            "documento": <?= $Page->documento->toClientList($Page) ?>,
            "moneda": <?= $Page->moneda->toClientList($Page) ?>,
            "lista_pedido": <?= $Page->lista_pedido->toClientList($Page) ?>,
            "dias_credito": <?= $Page->dias_credito->toClientList($Page) ?>,
            "consignacion": <?= $Page->consignacion->toClientList($Page) ?>,
            "factura": <?= $Page->factura->toClientList($Page) ?>,
            "asesor_asignado": <?= $Page->asesor_asignado->toClientList($Page) ?>,
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
<form name="fsalidasadd" id="fsalidasadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="salidas">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-multi-page"><!-- multi-page -->
<div class="ew-nav<?= $Page->MultiPages->containerClasses() ?>" id="pages_SalidasAdd"><!-- multi-page tabs -->
    <ul class="<?= $Page->MultiPages->navClasses() ?>" role="tablist">
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(1) ?>" data-bs-target="#tab_salidas1" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_salidas1" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(1)) ?>"><?= $Page->pageCaption(1) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(2) ?>" data-bs-target="#tab_salidas2" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_salidas2" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(2)) ?>"><?= $Page->pageCaption(2) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(3) ?>" data-bs-target="#tab_salidas3" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_salidas3" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(3)) ?>"><?= $Page->pageCaption(3) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(4) ?>" data-bs-target="#tab_salidas4" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_salidas4" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(4)) ?>"><?= $Page->pageCaption(4) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(5) ?>" data-bs-target="#tab_salidas5" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_salidas5" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(5)) ?>"><?= $Page->pageCaption(5) ?></button></li>
    </ul>
    <div class="<?= $Page->MultiPages->tabContentClasses() ?>"><!-- multi-page tabs .tab-content -->
        <div class="<?= $Page->MultiPages->tabPaneClasses(1) ?>" id="tab_salidas1" role="tabpanel"><!-- multi-page .tab-pane -->
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <div id="r_tipo_documento"<?= $Page->tipo_documento->rowAttributes() ?>>
        <label id="elh_salidas_tipo_documento" for="x_tipo_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_documento->caption() ?><?= $Page->tipo_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_salidas_tipo_documento">
    <select
        id="x_tipo_documento"
        name="x_tipo_documento"
        class="form-select ew-select<?= $Page->tipo_documento->isInvalidClass() ?>"
        <?php if (!$Page->tipo_documento->IsNativeSelect) { ?>
        data-select2-id="fsalidasadd_x_tipo_documento"
        <?php } ?>
        data-table="salidas"
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
loadjs.ready("fsalidasadd", function() {
    var options = { name: "x_tipo_documento", selectId: "fsalidasadd_x_tipo_documento" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsalidasadd.lists.tipo_documento?.lookupOptions.length) {
        options.data = { id: "x_tipo_documento", form: "fsalidasadd" };
    } else {
        options.ajax = { id: "x_tipo_documento", form: "fsalidasadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.salidas.fields.tipo_documento.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
    <div id="r_cliente"<?= $Page->cliente->rowAttributes() ?>>
        <label id="elh_salidas_cliente" for="x_cliente" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cliente->caption() ?><?= $Page->cliente->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cliente->cellAttributes() ?>>
<span id="el_salidas_cliente">
<div class="input-group flex-nowrap">
    <select
        id="x_cliente"
        name="x_cliente"
        class="form-control ew-select<?= $Page->cliente->isInvalidClass() ?>"
        data-select2-id="fsalidasadd_x_cliente"
        data-table="salidas"
        data-field="x_cliente"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->cliente->caption())) ?>"
        data-modal-lookup="true"
        data-page="1"
        data-value-separator="<?= $Page->cliente->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->cliente->getPlaceHolder()) ?>"
        <?= $Page->cliente->editAttributes() ?>>
        <?= $Page->cliente->selectOptionListHtml("x_cliente") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "cliente") && !$Page->cliente->ReadOnly) { ?>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_cliente" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->cliente->caption() ?>" data-title="<?= $Page->cliente->caption() ?>" data-ew-action="add-option" data-el="x_cliente" data-url="<?= GetUrl("ClienteAddopt") ?>"><i class="fa-solid fa-plus ew-icon"></i></button>
    <?php } ?>
</div>
<?= $Page->cliente->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cliente->getErrorMessage() ?></div>
<?= $Page->cliente->Lookup->getParamTag($Page, "p_x_cliente") ?>
<script>
loadjs.ready("fsalidasadd", function() {
    var options = { name: "x_cliente", selectId: "fsalidasadd_x_cliente" };
    if (fsalidasadd.lists.cliente?.lookupOptions.length) {
        options.data = { id: "x_cliente", form: "fsalidasadd" };
    } else {
        options.ajax = { id: "x_cliente", form: "fsalidasadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.salidas.fields.cliente.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
    <div id="r_documento"<?= $Page->documento->rowAttributes() ?>>
        <label id="elh_salidas_documento" for="x_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->documento->caption() ?><?= $Page->documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->documento->cellAttributes() ?>>
<span id="el_salidas_documento">
    <select
        id="x_documento"
        name="x_documento"
        class="form-select ew-select<?= $Page->documento->isInvalidClass() ?>"
        <?php if (!$Page->documento->IsNativeSelect) { ?>
        data-select2-id="fsalidasadd_x_documento"
        <?php } ?>
        data-table="salidas"
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
loadjs.ready("fsalidasadd", function() {
    var options = { name: "x_documento", selectId: "fsalidasadd_x_documento" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsalidasadd.lists.documento?.lookupOptions.length) {
        options.data = { id: "x_documento", form: "fsalidasadd" };
    } else {
        options.ajax = { id: "x_documento", form: "fsalidasadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.salidas.fields.documento.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
    <div id="r_doc_afectado"<?= $Page->doc_afectado->rowAttributes() ?>>
        <label id="elh_salidas_doc_afectado" for="x_doc_afectado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->doc_afectado->caption() ?><?= $Page->doc_afectado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->doc_afectado->cellAttributes() ?>>
<span id="el_salidas_doc_afectado">
<input type="<?= $Page->doc_afectado->getInputTextType() ?>" name="x_doc_afectado" id="x_doc_afectado" data-table="salidas" data-field="x_doc_afectado" value="<?= $Page->doc_afectado->EditValue ?>" data-page="1" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->doc_afectado->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->doc_afectado->formatPattern()) ?>"<?= $Page->doc_afectado->editAttributes() ?> aria-describedby="x_doc_afectado_help">
<?= $Page->doc_afectado->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->doc_afectado->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <div id="r_nota"<?= $Page->nota->rowAttributes() ?>>
        <label id="elh_salidas_nota" for="x_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota->caption() ?><?= $Page->nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nota->cellAttributes() ?>>
<span id="el_salidas_nota">
<textarea data-table="salidas" data-field="x_nota" data-page="1" name="x_nota" id="x_nota" cols="30" rows="3" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>"<?= $Page->nota->editAttributes() ?> aria-describedby="x_nota_help"><?= $Page->nota->EditValue ?></textarea>
<?= $Page->nota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->factura->Visible) { // factura ?>
    <div id="r_factura"<?= $Page->factura->rowAttributes() ?>>
        <label id="elh_salidas_factura" for="x_factura" class="<?= $Page->LeftColumnClass ?>"><?= $Page->factura->caption() ?><?= $Page->factura->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->factura->cellAttributes() ?>>
<span id="el_salidas_factura">
    <select
        id="x_factura"
        name="x_factura"
        class="form-select ew-select<?= $Page->factura->isInvalidClass() ?>"
        <?php if (!$Page->factura->IsNativeSelect) { ?>
        data-select2-id="fsalidasadd_x_factura"
        <?php } ?>
        data-table="salidas"
        data-field="x_factura"
        data-page="1"
        data-value-separator="<?= $Page->factura->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->factura->getPlaceHolder()) ?>"
        <?= $Page->factura->editAttributes() ?>>
        <?= $Page->factura->selectOptionListHtml("x_factura") ?>
    </select>
    <?= $Page->factura->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->factura->getErrorMessage() ?></div>
<?php if (!$Page->factura->IsNativeSelect) { ?>
<script>
loadjs.ready("fsalidasadd", function() {
    var options = { name: "x_factura", selectId: "fsalidasadd_x_factura" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsalidasadd.lists.factura?.lookupOptions.length) {
        options.data = { id: "x_factura", form: "fsalidasadd" };
    } else {
        options.ajax = { id: "x_factura", form: "fsalidasadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.salidas.fields.factura.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->archivo_pedido->Visible) { // archivo_pedido ?>
    <div id="r_archivo_pedido"<?= $Page->archivo_pedido->rowAttributes() ?>>
        <label id="elh_salidas_archivo_pedido" class="<?= $Page->LeftColumnClass ?>"><?= $Page->archivo_pedido->caption() ?><?= $Page->archivo_pedido->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->archivo_pedido->cellAttributes() ?>>
<span id="el_salidas_archivo_pedido">
<div id="fd_x_archivo_pedido" class="fileinput-button ew-file-drop-zone">
    <input
        type="file"
        id="x_archivo_pedido"
        name="x_archivo_pedido"
        class="form-control ew-file-input"
        title="<?= $Page->archivo_pedido->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="salidas"
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
        <div class="<?= $Page->MultiPages->tabPaneClasses(2) ?>" id="tab_salidas2" role="tabpanel"><!-- multi-page .tab-pane -->
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda"<?= $Page->moneda->rowAttributes() ?>>
        <label id="elh_salidas_moneda" for="x_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->moneda->cellAttributes() ?>>
<span id="el_salidas_moneda">
    <select
        id="x_moneda"
        name="x_moneda"
        class="form-select ew-select<?= $Page->moneda->isInvalidClass() ?>"
        <?php if (!$Page->moneda->IsNativeSelect) { ?>
        data-select2-id="fsalidasadd_x_moneda"
        <?php } ?>
        data-table="salidas"
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
loadjs.ready("fsalidasadd", function() {
    var options = { name: "x_moneda", selectId: "fsalidasadd_x_moneda" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsalidasadd.lists.moneda?.lookupOptions.length) {
        options.data = { id: "x_moneda", form: "fsalidasadd" };
    } else {
        options.ajax = { id: "x_moneda", form: "fsalidasadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.salidas.fields.moneda.selectOptions);
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
        <div class="<?= $Page->MultiPages->tabPaneClasses(3) ?>" id="tab_salidas3" role="tabpanel"><!-- multi-page .tab-pane -->
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->lista_pedido->Visible) { // lista_pedido ?>
    <div id="r_lista_pedido"<?= $Page->lista_pedido->rowAttributes() ?>>
        <label id="elh_salidas_lista_pedido" for="x_lista_pedido" class="<?= $Page->LeftColumnClass ?>"><?= $Page->lista_pedido->caption() ?><?= $Page->lista_pedido->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->lista_pedido->cellAttributes() ?>>
<span id="el_salidas_lista_pedido">
    <select
        id="x_lista_pedido"
        name="x_lista_pedido"
        class="form-select ew-select<?= $Page->lista_pedido->isInvalidClass() ?>"
        <?php if (!$Page->lista_pedido->IsNativeSelect) { ?>
        data-select2-id="fsalidasadd_x_lista_pedido"
        <?php } ?>
        data-table="salidas"
        data-field="x_lista_pedido"
        data-page="3"
        data-value-separator="<?= $Page->lista_pedido->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->lista_pedido->getPlaceHolder()) ?>"
        <?= $Page->lista_pedido->editAttributes() ?>>
        <?= $Page->lista_pedido->selectOptionListHtml("x_lista_pedido") ?>
    </select>
    <?= $Page->lista_pedido->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->lista_pedido->getErrorMessage() ?></div>
<?= $Page->lista_pedido->Lookup->getParamTag($Page, "p_x_lista_pedido") ?>
<?php if (!$Page->lista_pedido->IsNativeSelect) { ?>
<script>
loadjs.ready("fsalidasadd", function() {
    var options = { name: "x_lista_pedido", selectId: "fsalidasadd_x_lista_pedido" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsalidasadd.lists.lista_pedido?.lookupOptions.length) {
        options.data = { id: "x_lista_pedido", form: "fsalidasadd" };
    } else {
        options.ajax = { id: "x_lista_pedido", form: "fsalidasadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.salidas.fields.lista_pedido.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->dias_credito->Visible) { // dias_credito ?>
    <div id="r_dias_credito"<?= $Page->dias_credito->rowAttributes() ?>>
        <label id="elh_salidas_dias_credito" for="x_dias_credito" class="<?= $Page->LeftColumnClass ?>"><?= $Page->dias_credito->caption() ?><?= $Page->dias_credito->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->dias_credito->cellAttributes() ?>>
<span id="el_salidas_dias_credito">
    <select
        id="x_dias_credito"
        name="x_dias_credito"
        class="form-select ew-select<?= $Page->dias_credito->isInvalidClass() ?>"
        <?php if (!$Page->dias_credito->IsNativeSelect) { ?>
        data-select2-id="fsalidasadd_x_dias_credito"
        <?php } ?>
        data-table="salidas"
        data-field="x_dias_credito"
        data-page="3"
        data-value-separator="<?= $Page->dias_credito->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->dias_credito->getPlaceHolder()) ?>"
        <?= $Page->dias_credito->editAttributes() ?>>
        <?= $Page->dias_credito->selectOptionListHtml("x_dias_credito") ?>
    </select>
    <?= $Page->dias_credito->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->dias_credito->getErrorMessage() ?></div>
<?= $Page->dias_credito->Lookup->getParamTag($Page, "p_x_dias_credito") ?>
<?php if (!$Page->dias_credito->IsNativeSelect) { ?>
<script>
loadjs.ready("fsalidasadd", function() {
    var options = { name: "x_dias_credito", selectId: "fsalidasadd_x_dias_credito" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsalidasadd.lists.dias_credito?.lookupOptions.length) {
        options.data = { id: "x_dias_credito", form: "fsalidasadd" };
    } else {
        options.ajax = { id: "x_dias_credito", form: "fsalidasadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.salidas.fields.dias_credito.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nro_despacho->Visible) { // nro_despacho ?>
    <div id="r_nro_despacho"<?= $Page->nro_despacho->rowAttributes() ?>>
        <label id="elh_salidas_nro_despacho" for="x_nro_despacho" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_despacho->caption() ?><?= $Page->nro_despacho->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nro_despacho->cellAttributes() ?>>
<span id="el_salidas_nro_despacho">
<input type="<?= $Page->nro_despacho->getInputTextType() ?>" name="x_nro_despacho" id="x_nro_despacho" data-table="salidas" data-field="x_nro_despacho" value="<?= $Page->nro_despacho->EditValue ?>" data-page="3" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->nro_despacho->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nro_despacho->formatPattern()) ?>"<?= $Page->nro_despacho->editAttributes() ?> aria-describedby="x_nro_despacho_help">
<?= $Page->nro_despacho->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nro_despacho->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->asesor_asignado->Visible) { // asesor_asignado ?>
    <div id="r_asesor_asignado"<?= $Page->asesor_asignado->rowAttributes() ?>>
        <label id="elh_salidas_asesor_asignado" for="x_asesor_asignado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->asesor_asignado->caption() ?><?= $Page->asesor_asignado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->asesor_asignado->cellAttributes() ?>>
<span id="el_salidas_asesor_asignado">
    <select
        id="x_asesor_asignado"
        name="x_asesor_asignado"
        class="form-control ew-select<?= $Page->asesor_asignado->isInvalidClass() ?>"
        data-select2-id="fsalidasadd_x_asesor_asignado"
        data-table="salidas"
        data-field="x_asesor_asignado"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->asesor_asignado->caption())) ?>"
        data-modal-lookup="true"
        data-page="3"
        data-value-separator="<?= $Page->asesor_asignado->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->asesor_asignado->getPlaceHolder()) ?>"
        <?= $Page->asesor_asignado->editAttributes() ?>>
        <?= $Page->asesor_asignado->selectOptionListHtml("x_asesor_asignado") ?>
    </select>
    <?= $Page->asesor_asignado->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->asesor_asignado->getErrorMessage() ?></div>
<?= $Page->asesor_asignado->Lookup->getParamTag($Page, "p_x_asesor_asignado") ?>
<script>
loadjs.ready("fsalidasadd", function() {
    var options = { name: "x_asesor_asignado", selectId: "fsalidasadd_x_asesor_asignado" };
    if (fsalidasadd.lists.asesor_asignado?.lookupOptions.length) {
        options.data = { id: "x_asesor_asignado", form: "fsalidasadd" };
    } else {
        options.ajax = { id: "x_asesor_asignado", form: "fsalidasadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.salidas.fields.asesor_asignado.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
        <div class="<?= $Page->MultiPages->tabPaneClasses(4) ?>" id="tab_salidas4" role="tabpanel"><!-- multi-page .tab-pane -->
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->consignacion->Visible) { // consignacion ?>
    <div id="r_consignacion"<?= $Page->consignacion->rowAttributes() ?>>
        <label id="elh_salidas_consignacion" for="x_consignacion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->consignacion->caption() ?><?= $Page->consignacion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->consignacion->cellAttributes() ?>>
<span id="el_salidas_consignacion">
    <select
        id="x_consignacion"
        name="x_consignacion"
        class="form-select ew-select<?= $Page->consignacion->isInvalidClass() ?>"
        <?php if (!$Page->consignacion->IsNativeSelect) { ?>
        data-select2-id="fsalidasadd_x_consignacion"
        <?php } ?>
        data-table="salidas"
        data-field="x_consignacion"
        data-page="4"
        data-value-separator="<?= $Page->consignacion->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->consignacion->getPlaceHolder()) ?>"
        <?= $Page->consignacion->editAttributes() ?>>
        <?= $Page->consignacion->selectOptionListHtml("x_consignacion") ?>
    </select>
    <?= $Page->consignacion->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->consignacion->getErrorMessage() ?></div>
<?php if (!$Page->consignacion->IsNativeSelect) { ?>
<script>
loadjs.ready("fsalidasadd", function() {
    var options = { name: "x_consignacion", selectId: "fsalidasadd_x_consignacion" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsalidasadd.lists.consignacion?.lookupOptions.length) {
        options.data = { id: "x_consignacion", form: "fsalidasadd" };
    } else {
        options.ajax = { id: "x_consignacion", form: "fsalidasadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.salidas.fields.consignacion.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->checker->Visible) { // checker ?>
    <div id="r_checker"<?= $Page->checker->rowAttributes() ?>>
        <label id="elh_salidas_checker" for="x_checker" class="<?= $Page->LeftColumnClass ?>"><?= $Page->checker->caption() ?><?= $Page->checker->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->checker->cellAttributes() ?>>
<span id="el_salidas_checker">
<input type="<?= $Page->checker->getInputTextType() ?>" name="x_checker" id="x_checker" data-table="salidas" data-field="x_checker" value="<?= $Page->checker->EditValue ?>" data-page="4" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->checker->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->checker->formatPattern()) ?>"<?= $Page->checker->editAttributes() ?> aria-describedby="x_checker_help">
<?= $Page->checker->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->checker->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->checker_date->Visible) { // checker_date ?>
    <div id="r_checker_date"<?= $Page->checker_date->rowAttributes() ?>>
        <label id="elh_salidas_checker_date" for="x_checker_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->checker_date->caption() ?><?= $Page->checker_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->checker_date->cellAttributes() ?>>
<span id="el_salidas_checker_date">
<input type="<?= $Page->checker_date->getInputTextType() ?>" name="x_checker_date" id="x_checker_date" data-table="salidas" data-field="x_checker_date" value="<?= $Page->checker_date->EditValue ?>" data-page="4" maxlength="19" placeholder="<?= HtmlEncode($Page->checker_date->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->checker_date->formatPattern()) ?>"<?= $Page->checker_date->editAttributes() ?> aria-describedby="x_checker_date_help">
<?= $Page->checker_date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->checker_date->getErrorMessage() ?></div>
<?php if (!$Page->checker_date->ReadOnly && !$Page->checker_date->Disabled && !isset($Page->checker_date->EditAttrs["readonly"]) && !isset($Page->checker_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fsalidasadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fsalidasadd", "x_checker_date", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->packer->Visible) { // packer ?>
    <div id="r_packer"<?= $Page->packer->rowAttributes() ?>>
        <label id="elh_salidas_packer" for="x_packer" class="<?= $Page->LeftColumnClass ?>"><?= $Page->packer->caption() ?><?= $Page->packer->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->packer->cellAttributes() ?>>
<span id="el_salidas_packer">
<input type="<?= $Page->packer->getInputTextType() ?>" name="x_packer" id="x_packer" data-table="salidas" data-field="x_packer" value="<?= $Page->packer->EditValue ?>" data-page="4" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->packer->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->packer->formatPattern()) ?>"<?= $Page->packer->editAttributes() ?> aria-describedby="x_packer_help">
<?= $Page->packer->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->packer->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->packer_date->Visible) { // packer_date ?>
    <div id="r_packer_date"<?= $Page->packer_date->rowAttributes() ?>>
        <label id="elh_salidas_packer_date" for="x_packer_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->packer_date->caption() ?><?= $Page->packer_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->packer_date->cellAttributes() ?>>
<span id="el_salidas_packer_date">
<input type="<?= $Page->packer_date->getInputTextType() ?>" name="x_packer_date" id="x_packer_date" data-table="salidas" data-field="x_packer_date" value="<?= $Page->packer_date->EditValue ?>" data-page="4" maxlength="19" placeholder="<?= HtmlEncode($Page->packer_date->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->packer_date->formatPattern()) ?>"<?= $Page->packer_date->editAttributes() ?> aria-describedby="x_packer_date_help">
<?= $Page->packer_date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->packer_date->getErrorMessage() ?></div>
<?php if (!$Page->packer_date->ReadOnly && !$Page->packer_date->Disabled && !isset($Page->packer_date->EditAttrs["readonly"]) && !isset($Page->packer_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fsalidasadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fsalidasadd", "x_packer_date", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
        <div class="<?= $Page->MultiPages->tabPaneClasses(5) ?>" id="tab_salidas5" role="tabpanel"><!-- multi-page .tab-pane -->
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->fotos->Visible) { // fotos ?>
    <div id="r_fotos"<?= $Page->fotos->rowAttributes() ?>>
        <label id="elh_salidas_fotos" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fotos->caption() ?><?= $Page->fotos->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fotos->cellAttributes() ?>>
<span id="el_salidas_fotos">
<div id="fd_x_fotos" class="fileinput-button ew-file-drop-zone">
    <input
        type="file"
        id="x_fotos"
        name="x_fotos"
        class="form-control ew-file-input"
        title="<?= $Page->fotos->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="salidas"
        data-field="x_fotos"
        data-size="300"
        data-accept-file-types="<?= $Page->fotos->acceptFileTypes() ?>"
        data-max-file-size="<?= $Page->fotos->UploadMaxFileSize ?>"
        data-max-number-of-files="<?= $Page->fotos->UploadMaxFileCount ?>"
        data-disable-image-crop="<?= $Page->fotos->ImageCropper ? 0 : 1 ?>"
        data-page="5"
        multiple
        aria-describedby="x_fotos_help"
        <?= ($Page->fotos->ReadOnly || $Page->fotos->Disabled) ? " disabled" : "" ?>
        <?= $Page->fotos->editAttributes() ?>
    >
    <div class="text-body-secondary ew-file-text"><?= $Language->phrase("ChooseFiles") ?></div>
    <?= $Page->fotos->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->fotos->getErrorMessage() ?></div>
</div>
<input type="hidden" name="fn_x_fotos" id= "fn_x_fotos" value="<?= $Page->fotos->Upload->FileName ?>">
<input type="hidden" name="fa_x_fotos" id= "fa_x_fotos" value="0">
<table id="ft_x_fotos" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
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
<?php
    if (in_array("pagos", explode(",", $Page->getCurrentDetailTable())) && $pagos->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("pagos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "PagosGrid.php" ?>
<?php } ?>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fsalidasadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fsalidasadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("salidas");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here
    // document.write("page loaded");
    $(document).ready(function(){
      //$("#r_consignacion").hide();
    });

    /*
    $("#x_cliente").change(function(){
    	var cliente = $("#x_cliente").val();
    	var consignacion = "";
    	$.ajax({
    	  url : "verificar_cliente_consignacion.php",
    	  type: "GET",
    	  data : {id: cliente},
    	  beforeSend: function(){
    	    //$("#result").html("Espere. . . ");
    	  }
    	})
    	.done(function(data) {
    		//alert(data);
    		//$("#result").html(data);
    		consignacion = data;
    		if(consignacion == "S") {
    			//$("#r_consignacion").show();
    			$("#x_consignacion").val('S');
    		}
    		else {
    			$("#x_consignacion").val('N');
    			//$("#r_consignacion").hide();
    		}
    	})
    	.fail(function(data) {
    		alert( "error" + data );
    	})
    	.always(function(data) {
    		//alert( "complete" );
    		//$("#result").html("Espere. . . ");
    	});
    });
    */
});
</script>
