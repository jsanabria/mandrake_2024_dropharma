<?php

namespace PHPMaker2024\mandrake;

// Page object
$SalidasEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="fsalidasedit" id="fsalidasedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { salidas: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fsalidasedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fsalidasedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["tipo_documento", [fields.tipo_documento.visible && fields.tipo_documento.required ? ew.Validators.required(fields.tipo_documento.caption) : null], fields.tipo_documento.isInvalid],
            ["nro_documento", [fields.nro_documento.visible && fields.nro_documento.required ? ew.Validators.required(fields.nro_documento.caption) : null], fields.nro_documento.isInvalid],
            ["nro_control", [fields.nro_control.visible && fields.nro_control.required ? ew.Validators.required(fields.nro_control.caption) : null], fields.nro_control.isInvalid],
            ["cliente", [fields.cliente.visible && fields.cliente.required ? ew.Validators.required(fields.cliente.caption) : null], fields.cliente.isInvalid],
            ["documento", [fields.documento.visible && fields.documento.required ? ew.Validators.required(fields.documento.caption) : null], fields.documento.isInvalid],
            ["doc_afectado", [fields.doc_afectado.visible && fields.doc_afectado.required ? ew.Validators.required(fields.doc_afectado.caption) : null], fields.doc_afectado.isInvalid],
            ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
            ["monto_total", [fields.monto_total.visible && fields.monto_total.required ? ew.Validators.required(fields.monto_total.caption) : null], fields.monto_total.isInvalid],
            ["alicuota_iva", [fields.alicuota_iva.visible && fields.alicuota_iva.required ? ew.Validators.required(fields.alicuota_iva.caption) : null], fields.alicuota_iva.isInvalid],
            ["iva", [fields.iva.visible && fields.iva.required ? ew.Validators.required(fields.iva.caption) : null], fields.iva.isInvalid],
            ["total", [fields.total.visible && fields.total.required ? ew.Validators.required(fields.total.caption) : null], fields.total.isInvalid],
            ["tasa_dia", [fields.tasa_dia.visible && fields.tasa_dia.required ? ew.Validators.required(fields.tasa_dia.caption) : null, ew.Validators.float], fields.tasa_dia.isInvalid],
            ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
            ["estatus", [fields.estatus.visible && fields.estatus.required ? ew.Validators.required(fields.estatus.caption) : null], fields.estatus.isInvalid],
            ["dias_credito", [fields.dias_credito.visible && fields.dias_credito.required ? ew.Validators.required(fields.dias_credito.caption) : null], fields.dias_credito.isInvalid],
            ["entregado", [fields.entregado.visible && fields.entregado.required ? ew.Validators.required(fields.entregado.caption) : null], fields.entregado.isInvalid],
            ["pagado", [fields.pagado.visible && fields.pagado.required ? ew.Validators.required(fields.pagado.caption) : null], fields.pagado.isInvalid],
            ["bultos", [fields.bultos.visible && fields.bultos.required ? ew.Validators.required(fields.bultos.caption) : null, ew.Validators.integer], fields.bultos.isInvalid],
            ["consignacion", [fields.consignacion.visible && fields.consignacion.required ? ew.Validators.required(fields.consignacion.caption) : null], fields.consignacion.isInvalid],
            ["descuento", [fields.descuento.visible && fields.descuento.required ? ew.Validators.required(fields.descuento.caption) : null, ew.Validators.float], fields.descuento.isInvalid],
            ["nro_despacho", [fields.nro_despacho.visible && fields.nro_despacho.required ? ew.Validators.required(fields.nro_despacho.caption) : null], fields.nro_despacho.isInvalid],
            ["asesor_asignado", [fields.asesor_asignado.visible && fields.asesor_asignado.required ? ew.Validators.required(fields.asesor_asignado.caption) : null], fields.asesor_asignado.isInvalid],
            ["archivo_pedido", [fields.archivo_pedido.visible && fields.archivo_pedido.required ? ew.Validators.fileRequired(fields.archivo_pedido.caption) : null], fields.archivo_pedido.isInvalid],
            ["checker", [fields.checker.visible && fields.checker.required ? ew.Validators.required(fields.checker.caption) : null], fields.checker.isInvalid],
            ["checker_date", [fields.checker_date.visible && fields.checker_date.required ? ew.Validators.required(fields.checker_date.caption) : null], fields.checker_date.isInvalid],
            ["packer", [fields.packer.visible && fields.packer.required ? ew.Validators.required(fields.packer.caption) : null], fields.packer.isInvalid],
            ["packer_date", [fields.packer_date.visible && fields.packer_date.required ? ew.Validators.required(fields.packer_date.caption) : null], fields.packer_date.isInvalid],
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
            "moneda": <?= $Page->moneda->toClientList($Page) ?>,
            "estatus": <?= $Page->estatus->toClientList($Page) ?>,
            "dias_credito": <?= $Page->dias_credito->toClientList($Page) ?>,
            "entregado": <?= $Page->entregado->toClientList($Page) ?>,
            "pagado": <?= $Page->pagado->toClientList($Page) ?>,
            "consignacion": <?= $Page->consignacion->toClientList($Page) ?>,
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
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="salidas">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-multi-page"><!-- multi-page -->
<div class="ew-nav<?= $Page->MultiPages->containerClasses() ?>" id="pages_SalidasEdit"><!-- multi-page tabs -->
    <ul class="<?= $Page->MultiPages->navClasses() ?>" role="tablist">
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(1) ?>" data-bs-target="#tab_salidas1" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_salidas1" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(1)) ?>"><?= $Page->pageCaption(1) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(2) ?>" data-bs-target="#tab_salidas2" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_salidas2" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(2)) ?>"><?= $Page->pageCaption(2) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(3) ?>" data-bs-target="#tab_salidas3" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_salidas3" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(3)) ?>"><?= $Page->pageCaption(3) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(4) ?>" data-bs-target="#tab_salidas4" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_salidas4" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(4)) ?>"><?= $Page->pageCaption(4) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(5) ?>" data-bs-target="#tab_salidas5" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_salidas5" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(5)) ?>"><?= $Page->pageCaption(5) ?></button></li>
    </ul>
    <div class="<?= $Page->MultiPages->tabContentClasses() ?>"><!-- multi-page tabs .tab-content -->
        <div class="<?= $Page->MultiPages->tabPaneClasses(1) ?>" id="tab_salidas1" role="tabpanel"><!-- multi-page .tab-pane -->
<div class="ew-edit-div"><!-- page* -->
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
        data-select2-id="fsalidasedit_x_tipo_documento"
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
loadjs.ready("fsalidasedit", function() {
    var options = { name: "x_tipo_documento", selectId: "fsalidasedit_x_tipo_documento" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsalidasedit.lists.tipo_documento?.lookupOptions.length) {
        options.data = { id: "x_tipo_documento", form: "fsalidasedit" };
    } else {
        options.ajax = { id: "x_tipo_documento", form: "fsalidasedit", limit: ew.LOOKUP_PAGE_SIZE };
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
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <div id="r_nro_documento"<?= $Page->nro_documento->rowAttributes() ?>>
        <label id="elh_salidas_nro_documento" for="x_nro_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_documento->caption() ?><?= $Page->nro_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_salidas_nro_documento">
<input type="<?= $Page->nro_documento->getInputTextType() ?>" name="x_nro_documento" id="x_nro_documento" data-table="salidas" data-field="x_nro_documento" value="<?= $Page->nro_documento->EditValue ?>" data-page="1" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->nro_documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nro_documento->formatPattern()) ?>"<?= $Page->nro_documento->editAttributes() ?> aria-describedby="x_nro_documento_help">
<?= $Page->nro_documento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nro_documento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nro_control->Visible) { // nro_control ?>
    <div id="r_nro_control"<?= $Page->nro_control->rowAttributes() ?>>
        <label id="elh_salidas_nro_control" for="x_nro_control" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_control->caption() ?><?= $Page->nro_control->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nro_control->cellAttributes() ?>>
<span id="el_salidas_nro_control">
<input type="<?= $Page->nro_control->getInputTextType() ?>" name="x_nro_control" id="x_nro_control" data-table="salidas" data-field="x_nro_control" value="<?= $Page->nro_control->EditValue ?>" data-page="1" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->nro_control->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nro_control->formatPattern()) ?>"<?= $Page->nro_control->editAttributes() ?> aria-describedby="x_nro_control_help">
<?= $Page->nro_control->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nro_control->getErrorMessage() ?></div>
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
        data-select2-id="fsalidasedit_x_cliente"
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
loadjs.ready("fsalidasedit", function() {
    var options = { name: "x_cliente", selectId: "fsalidasedit_x_cliente" };
    if (fsalidasedit.lists.cliente?.lookupOptions.length) {
        options.data = { id: "x_cliente", form: "fsalidasedit" };
    } else {
        options.ajax = { id: "x_cliente", form: "fsalidasedit", limit: ew.LOOKUP_PAGE_SIZE };
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
<span<?= $Page->documento->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->documento->getDisplayValue($Page->documento->EditValue) ?></span></span>
<input type="hidden" data-table="salidas" data-field="x_documento" data-hidden="1" data-page="1" name="x_documento" id="x_documento" value="<?= HtmlEncode($Page->documento->CurrentValue) ?>">
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
<?php if ($Page->estatus->Visible) { // estatus ?>
    <div id="r_estatus"<?= $Page->estatus->rowAttributes() ?>>
        <label id="elh_salidas_estatus" for="x_estatus" class="<?= $Page->LeftColumnClass ?>"><?= $Page->estatus->caption() ?><?= $Page->estatus->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->estatus->cellAttributes() ?>>
<span id="el_salidas_estatus">
    <select
        id="x_estatus"
        name="x_estatus"
        class="form-select ew-select<?= $Page->estatus->isInvalidClass() ?>"
        <?php if (!$Page->estatus->IsNativeSelect) { ?>
        data-select2-id="fsalidasedit_x_estatus"
        <?php } ?>
        data-table="salidas"
        data-field="x_estatus"
        data-page="1"
        data-value-separator="<?= $Page->estatus->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->estatus->getPlaceHolder()) ?>"
        <?= $Page->estatus->editAttributes() ?>>
        <?= $Page->estatus->selectOptionListHtml("x_estatus") ?>
    </select>
    <?= $Page->estatus->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->estatus->getErrorMessage() ?></div>
<?php if (!$Page->estatus->IsNativeSelect) { ?>
<script>
loadjs.ready("fsalidasedit", function() {
    var options = { name: "x_estatus", selectId: "fsalidasedit_x_estatus" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsalidasedit.lists.estatus?.lookupOptions.length) {
        options.data = { id: "x_estatus", form: "fsalidasedit" };
    } else {
        options.ajax = { id: "x_estatus", form: "fsalidasedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.salidas.fields.estatus.selectOptions);
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
<input type="hidden" name="fa_x_archivo_pedido" id= "fa_x_archivo_pedido" value="<?= (Post("fa_x_archivo_pedido") == "0") ? "0" : "1" ?>">
<table id="ft_x_archivo_pedido" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
        <div class="<?= $Page->MultiPages->tabPaneClasses(2) ?>" id="tab_salidas2" role="tabpanel"><!-- multi-page .tab-pane -->
<div class="ew-edit-div"><!-- page* -->
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
        data-select2-id="fsalidasedit_x_moneda"
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
loadjs.ready("fsalidasedit", function() {
    var options = { name: "x_moneda", selectId: "fsalidasedit_x_moneda" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsalidasedit.lists.moneda?.lookupOptions.length) {
        options.data = { id: "x_moneda", form: "fsalidasedit" };
    } else {
        options.ajax = { id: "x_moneda", form: "fsalidasedit", limit: ew.LOOKUP_PAGE_SIZE };
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
<?php if ($Page->monto_total->Visible) { // monto_total ?>
    <div id="r_monto_total"<?= $Page->monto_total->rowAttributes() ?>>
        <label id="elh_salidas_monto_total" for="x_monto_total" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_total->caption() ?><?= $Page->monto_total->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->monto_total->cellAttributes() ?>>
<span id="el_salidas_monto_total">
<span<?= $Page->monto_total->viewAttributes() ?>>
<?php if (!EmptyString($Page->monto_total->EditValue) && $Page->monto_total->linkAttributes() != "") { ?>
<a<?= $Page->monto_total->linkAttributes() ?>><input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->monto_total->getDisplayValue($Page->monto_total->EditValue))) ?>"></a>
<?php } else { ?>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->monto_total->getDisplayValue($Page->monto_total->EditValue))) ?>">
<?php } ?>
</span>
<input type="hidden" data-table="salidas" data-field="x_monto_total" data-hidden="1" data-page="2" name="x_monto_total" id="x_monto_total" value="<?= HtmlEncode($Page->monto_total->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->alicuota_iva->Visible) { // alicuota_iva ?>
    <div id="r_alicuota_iva"<?= $Page->alicuota_iva->rowAttributes() ?>>
        <label id="elh_salidas_alicuota_iva" for="x_alicuota_iva" class="<?= $Page->LeftColumnClass ?>"><?= $Page->alicuota_iva->caption() ?><?= $Page->alicuota_iva->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->alicuota_iva->cellAttributes() ?>>
<span id="el_salidas_alicuota_iva">
<span<?= $Page->alicuota_iva->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->alicuota_iva->getDisplayValue($Page->alicuota_iva->EditValue))) ?>"></span>
<input type="hidden" data-table="salidas" data-field="x_alicuota_iva" data-hidden="1" data-page="2" name="x_alicuota_iva" id="x_alicuota_iva" value="<?= HtmlEncode($Page->alicuota_iva->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->iva->Visible) { // iva ?>
    <div id="r_iva"<?= $Page->iva->rowAttributes() ?>>
        <label id="elh_salidas_iva" for="x_iva" class="<?= $Page->LeftColumnClass ?>"><?= $Page->iva->caption() ?><?= $Page->iva->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->iva->cellAttributes() ?>>
<span id="el_salidas_iva">
<span<?= $Page->iva->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->iva->getDisplayValue($Page->iva->EditValue))) ?>"></span>
<input type="hidden" data-table="salidas" data-field="x_iva" data-hidden="1" data-page="2" name="x_iva" id="x_iva" value="<?= HtmlEncode($Page->iva->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
    <div id="r_total"<?= $Page->total->rowAttributes() ?>>
        <label id="elh_salidas_total" for="x_total" class="<?= $Page->LeftColumnClass ?>"><?= $Page->total->caption() ?><?= $Page->total->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->total->cellAttributes() ?>>
<span id="el_salidas_total">
<span<?= $Page->total->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->total->getDisplayValue($Page->total->EditValue))) ?>"></span>
<input type="hidden" data-table="salidas" data-field="x_total" data-hidden="1" data-page="2" name="x_total" id="x_total" value="<?= HtmlEncode($Page->total->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tasa_dia->Visible) { // tasa_dia ?>
    <div id="r_tasa_dia"<?= $Page->tasa_dia->rowAttributes() ?>>
        <label id="elh_salidas_tasa_dia" for="x_tasa_dia" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tasa_dia->caption() ?><?= $Page->tasa_dia->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tasa_dia->cellAttributes() ?>>
<span id="el_salidas_tasa_dia">
<input type="<?= $Page->tasa_dia->getInputTextType() ?>" name="x_tasa_dia" id="x_tasa_dia" data-table="salidas" data-field="x_tasa_dia" value="<?= $Page->tasa_dia->EditValue ?>" data-page="2" size="10" placeholder="<?= HtmlEncode($Page->tasa_dia->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->tasa_dia->formatPattern()) ?>"<?= $Page->tasa_dia->editAttributes() ?> aria-describedby="x_tasa_dia_help">
<?= $Page->tasa_dia->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tasa_dia->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
    <div id="r_descuento"<?= $Page->descuento->rowAttributes() ?>>
        <label id="elh_salidas_descuento" for="x_descuento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descuento->caption() ?><?= $Page->descuento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->descuento->cellAttributes() ?>>
<span id="el_salidas_descuento">
<input type="<?= $Page->descuento->getInputTextType() ?>" name="x_descuento" id="x_descuento" data-table="salidas" data-field="x_descuento" value="<?= $Page->descuento->EditValue ?>" data-page="2" size="6" placeholder="<?= HtmlEncode($Page->descuento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->descuento->formatPattern()) ?>"<?= $Page->descuento->editAttributes() ?> aria-describedby="x_descuento_help">
<?= $Page->descuento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->descuento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
        <div class="<?= $Page->MultiPages->tabPaneClasses(3) ?>" id="tab_salidas3" role="tabpanel"><!-- multi-page .tab-pane -->
<div class="ew-edit-div"><!-- page* -->
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
        data-select2-id="fsalidasedit_x_dias_credito"
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
loadjs.ready("fsalidasedit", function() {
    var options = { name: "x_dias_credito", selectId: "fsalidasedit_x_dias_credito" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsalidasedit.lists.dias_credito?.lookupOptions.length) {
        options.data = { id: "x_dias_credito", form: "fsalidasedit" };
    } else {
        options.ajax = { id: "x_dias_credito", form: "fsalidasedit", limit: ew.LOOKUP_PAGE_SIZE };
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
        data-select2-id="fsalidasedit_x_asesor_asignado"
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
loadjs.ready("fsalidasedit", function() {
    var options = { name: "x_asesor_asignado", selectId: "fsalidasedit_x_asesor_asignado" };
    if (fsalidasedit.lists.asesor_asignado?.lookupOptions.length) {
        options.data = { id: "x_asesor_asignado", form: "fsalidasedit" };
    } else {
        options.ajax = { id: "x_asesor_asignado", form: "fsalidasedit", limit: ew.LOOKUP_PAGE_SIZE };
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
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->entregado->Visible) { // entregado ?>
    <div id="r_entregado"<?= $Page->entregado->rowAttributes() ?>>
        <label id="elh_salidas_entregado" for="x_entregado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->entregado->caption() ?><?= $Page->entregado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->entregado->cellAttributes() ?>>
<span id="el_salidas_entregado">
    <select
        id="x_entregado"
        name="x_entregado"
        class="form-select ew-select<?= $Page->entregado->isInvalidClass() ?>"
        <?php if (!$Page->entregado->IsNativeSelect) { ?>
        data-select2-id="fsalidasedit_x_entregado"
        <?php } ?>
        data-table="salidas"
        data-field="x_entregado"
        data-page="4"
        data-value-separator="<?= $Page->entregado->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->entregado->getPlaceHolder()) ?>"
        <?= $Page->entregado->editAttributes() ?>>
        <?= $Page->entregado->selectOptionListHtml("x_entregado") ?>
    </select>
    <?= $Page->entregado->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->entregado->getErrorMessage() ?></div>
<?php if (!$Page->entregado->IsNativeSelect) { ?>
<script>
loadjs.ready("fsalidasedit", function() {
    var options = { name: "x_entregado", selectId: "fsalidasedit_x_entregado" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsalidasedit.lists.entregado?.lookupOptions.length) {
        options.data = { id: "x_entregado", form: "fsalidasedit" };
    } else {
        options.ajax = { id: "x_entregado", form: "fsalidasedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.salidas.fields.entregado.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pagado->Visible) { // pagado ?>
    <div id="r_pagado"<?= $Page->pagado->rowAttributes() ?>>
        <label id="elh_salidas_pagado" for="x_pagado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pagado->caption() ?><?= $Page->pagado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->pagado->cellAttributes() ?>>
<span id="el_salidas_pagado">
    <select
        id="x_pagado"
        name="x_pagado"
        class="form-select ew-select<?= $Page->pagado->isInvalidClass() ?>"
        <?php if (!$Page->pagado->IsNativeSelect) { ?>
        data-select2-id="fsalidasedit_x_pagado"
        <?php } ?>
        data-table="salidas"
        data-field="x_pagado"
        data-page="4"
        data-value-separator="<?= $Page->pagado->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->pagado->getPlaceHolder()) ?>"
        <?= $Page->pagado->editAttributes() ?>>
        <?= $Page->pagado->selectOptionListHtml("x_pagado") ?>
    </select>
    <?= $Page->pagado->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->pagado->getErrorMessage() ?></div>
<?php if (!$Page->pagado->IsNativeSelect) { ?>
<script>
loadjs.ready("fsalidasedit", function() {
    var options = { name: "x_pagado", selectId: "fsalidasedit_x_pagado" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsalidasedit.lists.pagado?.lookupOptions.length) {
        options.data = { id: "x_pagado", form: "fsalidasedit" };
    } else {
        options.ajax = { id: "x_pagado", form: "fsalidasedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.salidas.fields.pagado.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->bultos->Visible) { // bultos ?>
    <div id="r_bultos"<?= $Page->bultos->rowAttributes() ?>>
        <label id="elh_salidas_bultos" for="x_bultos" class="<?= $Page->LeftColumnClass ?>"><?= $Page->bultos->caption() ?><?= $Page->bultos->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->bultos->cellAttributes() ?>>
<span id="el_salidas_bultos">
<input type="<?= $Page->bultos->getInputTextType() ?>" name="x_bultos" id="x_bultos" data-table="salidas" data-field="x_bultos" value="<?= $Page->bultos->EditValue ?>" data-page="4" size="6" placeholder="<?= HtmlEncode($Page->bultos->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->bultos->formatPattern()) ?>"<?= $Page->bultos->editAttributes() ?> aria-describedby="x_bultos_help">
<?= $Page->bultos->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->bultos->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
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
        data-select2-id="fsalidasedit_x_consignacion"
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
loadjs.ready("fsalidasedit", function() {
    var options = { name: "x_consignacion", selectId: "fsalidasedit_x_consignacion" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fsalidasedit.lists.consignacion?.lookupOptions.length) {
        options.data = { id: "x_consignacion", form: "fsalidasedit" };
    } else {
        options.ajax = { id: "x_consignacion", form: "fsalidasedit", limit: ew.LOOKUP_PAGE_SIZE };
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
<span<?= $Page->checker->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->checker->getDisplayValue($Page->checker->EditValue))) ?>"></span>
<input type="hidden" data-table="salidas" data-field="x_checker" data-hidden="1" data-page="4" name="x_checker" id="x_checker" value="<?= HtmlEncode($Page->checker->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->checker_date->Visible) { // checker_date ?>
    <div id="r_checker_date"<?= $Page->checker_date->rowAttributes() ?>>
        <label id="elh_salidas_checker_date" for="x_checker_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->checker_date->caption() ?><?= $Page->checker_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->checker_date->cellAttributes() ?>>
<span id="el_salidas_checker_date">
<span<?= $Page->checker_date->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->checker_date->getDisplayValue($Page->checker_date->EditValue))) ?>"></span>
<input type="hidden" data-table="salidas" data-field="x_checker_date" data-hidden="1" data-page="4" name="x_checker_date" id="x_checker_date" value="<?= HtmlEncode($Page->checker_date->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->packer->Visible) { // packer ?>
    <div id="r_packer"<?= $Page->packer->rowAttributes() ?>>
        <label id="elh_salidas_packer" for="x_packer" class="<?= $Page->LeftColumnClass ?>"><?= $Page->packer->caption() ?><?= $Page->packer->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->packer->cellAttributes() ?>>
<span id="el_salidas_packer">
<span<?= $Page->packer->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->packer->getDisplayValue($Page->packer->EditValue))) ?>"></span>
<input type="hidden" data-table="salidas" data-field="x_packer" data-hidden="1" data-page="4" name="x_packer" id="x_packer" value="<?= HtmlEncode($Page->packer->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->packer_date->Visible) { // packer_date ?>
    <div id="r_packer_date"<?= $Page->packer_date->rowAttributes() ?>>
        <label id="elh_salidas_packer_date" for="x_packer_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->packer_date->caption() ?><?= $Page->packer_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->packer_date->cellAttributes() ?>>
<span id="el_salidas_packer_date">
<span<?= $Page->packer_date->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->packer_date->getDisplayValue($Page->packer_date->EditValue))) ?>"></span>
<input type="hidden" data-table="salidas" data-field="x_packer_date" data-hidden="1" data-page="4" name="x_packer_date" id="x_packer_date" value="<?= HtmlEncode($Page->packer_date->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
        <div class="<?= $Page->MultiPages->tabPaneClasses(5) ?>" id="tab_salidas5" role="tabpanel"><!-- multi-page .tab-pane -->
<div class="ew-edit-div"><!-- page* -->
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
<input type="hidden" name="fa_x_fotos" id= "fa_x_fotos" value="<?= (Post("fa_x_fotos") == "0") ? "0" : "1" ?>">
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
    <input type="hidden" data-table="salidas" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?php
    if (in_array("entradas_salidas", explode(",", $Page->getCurrentDetailTable())) && $entradas_salidas->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("entradas_salidas", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "EntradasSalidasGrid.php" ?>
<?php } ?>
<?php
    if (in_array("pagos", explode(",", $Page->getCurrentDetailTable())) && $pagos->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("pagos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "PagosGrid.php" ?>
<?php } ?>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fsalidasedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fsalidasedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
</main>
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
    $(document).ready(function() {
    	<?php
    	$sql = "SELECT tipo_documento FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "';";
    	$tipo = ExecuteScalar($sql);
    	switch($tipo) {
    	case "TDCPDV":
    		if(!VerificaFuncion('006')) {
    			echo '$("#r_estatus").hide()';
    		}
    		break;
    	case "TDCNET":
    		if(!VerificaFuncion('007')) {
    			echo '$("#r_estatus").hide()';
    		}
    		break;
    	case "TDCFCV":
    		if(!VerificaFuncion('008')) {
    			echo '$("#r_estatus").hide()';
    		}
    		break;
    	case "TDCASA":
    		if(!VerificaFuncion('010')) {
    			echo '$("#r_estatus").hide()';
    		}
    		break;
    	}
    	?>
    	//$("#x_nota").val("<?php echo CurrentUserName(); ?>");
    });
});
</script>
