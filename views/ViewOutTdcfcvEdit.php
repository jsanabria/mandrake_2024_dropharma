<?php

namespace PHPMaker2024\mandrake;

// Page object
$ViewOutTdcfcvEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fview_out_tdcfcvedit" id="fview_out_tdcfcvedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { view_out_tdcfcv: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fview_out_tdcfcvedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fview_out_tdcfcvedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["documento", [fields.documento.visible && fields.documento.required ? ew.Validators.required(fields.documento.caption) : null], fields.documento.isInvalid],
            ["nro_documento", [fields.nro_documento.visible && fields.nro_documento.required ? ew.Validators.required(fields.nro_documento.caption) : null], fields.nro_documento.isInvalid],
            ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(fields.fecha.clientFormatPattern)], fields.fecha.isInvalid],
            ["nro_control", [fields.nro_control.visible && fields.nro_control.required ? ew.Validators.required(fields.nro_control.caption) : null], fields.nro_control.isInvalid],
            ["cliente", [fields.cliente.visible && fields.cliente.required ? ew.Validators.required(fields.cliente.caption) : null], fields.cliente.isInvalid],
            ["doc_afectado", [fields.doc_afectado.visible && fields.doc_afectado.required ? ew.Validators.required(fields.doc_afectado.caption) : null], fields.doc_afectado.isInvalid],
            ["monto_total", [fields.monto_total.visible && fields.monto_total.required ? ew.Validators.required(fields.monto_total.caption) : null], fields.monto_total.isInvalid],
            ["alicuota_iva", [fields.alicuota_iva.visible && fields.alicuota_iva.required ? ew.Validators.required(fields.alicuota_iva.caption) : null], fields.alicuota_iva.isInvalid],
            ["iva", [fields.iva.visible && fields.iva.required ? ew.Validators.required(fields.iva.caption) : null], fields.iva.isInvalid],
            ["total", [fields.total.visible && fields.total.required ? ew.Validators.required(fields.total.caption) : null], fields.total.isInvalid],
            ["igtf", [fields.igtf.visible && fields.igtf.required ? ew.Validators.required(fields.igtf.caption) : null], fields.igtf.isInvalid],
            ["monto_base_igtf", [fields.monto_base_igtf.visible && fields.monto_base_igtf.required ? ew.Validators.required(fields.monto_base_igtf.caption) : null, ew.Validators.float], fields.monto_base_igtf.isInvalid],
            ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
            ["estatus", [fields.estatus.visible && fields.estatus.required ? ew.Validators.required(fields.estatus.caption) : null], fields.estatus.isInvalid],
            ["dias_credito", [fields.dias_credito.visible && fields.dias_credito.required ? ew.Validators.required(fields.dias_credito.caption) : null], fields.dias_credito.isInvalid],
            ["nro_despacho", [fields.nro_despacho.visible && fields.nro_despacho.required ? ew.Validators.required(fields.nro_despacho.caption) : null], fields.nro_despacho.isInvalid],
            ["asesor_asignado", [fields.asesor_asignado.visible && fields.asesor_asignado.required ? ew.Validators.required(fields.asesor_asignado.caption) : null], fields.asesor_asignado.isInvalid]
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
            "cliente": <?= $Page->cliente->toClientList($Page) ?>,
            "igtf": <?= $Page->igtf->toClientList($Page) ?>,
            "estatus": <?= $Page->estatus->toClientList($Page) ?>,
            "dias_credito": <?= $Page->dias_credito->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="view_out_tdcfcv">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-multi-page"><!-- multi-page -->
<div class="ew-nav<?= $Page->MultiPages->containerClasses() ?>" id="pages_ViewOutTdcfcvEdit"><!-- multi-page tabs -->
    <ul class="<?= $Page->MultiPages->navClasses() ?>" role="tablist">
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(1) ?>" data-bs-target="#tab_view_out_tdcfcv1" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_view_out_tdcfcv1" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(1)) ?>"><?= $Page->pageCaption(1) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(2) ?>" data-bs-target="#tab_view_out_tdcfcv2" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_view_out_tdcfcv2" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(2)) ?>"><?= $Page->pageCaption(2) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(3) ?>" data-bs-target="#tab_view_out_tdcfcv3" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_view_out_tdcfcv3" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(3)) ?>"><?= $Page->pageCaption(3) ?></button></li>
    </ul>
    <div class="<?= $Page->MultiPages->tabContentClasses() ?>"><!-- multi-page tabs .tab-content -->
        <div class="<?= $Page->MultiPages->tabPaneClasses(1) ?>" id="tab_view_out_tdcfcv1" role="tabpanel"><!-- multi-page .tab-pane -->
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->documento->Visible) { // documento ?>
    <div id="r_documento"<?= $Page->documento->rowAttributes() ?>>
        <label id="elh_view_out_tdcfcv_documento" for="x_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->documento->caption() ?><?= $Page->documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->documento->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_documento">
<span<?= $Page->documento->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->documento->getDisplayValue($Page->documento->EditValue) ?></span></span>
<input type="hidden" data-table="view_out_tdcfcv" data-field="x_documento" data-hidden="1" data-page="1" name="x_documento" id="x_documento" value="<?= HtmlEncode($Page->documento->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <div id="r_nro_documento"<?= $Page->nro_documento->rowAttributes() ?>>
        <label id="elh_view_out_tdcfcv_nro_documento" for="x_nro_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_documento->caption() ?><?= $Page->nro_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_nro_documento">
<input type="<?= $Page->nro_documento->getInputTextType() ?>" name="x_nro_documento" id="x_nro_documento" data-table="view_out_tdcfcv" data-field="x_nro_documento" value="<?= $Page->nro_documento->EditValue ?>" data-page="1" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->nro_documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nro_documento->formatPattern()) ?>"<?= $Page->nro_documento->editAttributes() ?> aria-describedby="x_nro_documento_help">
<?= $Page->nro_documento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nro_documento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <label id="elh_view_out_tdcfcv_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_fecha">
<input type="<?= $Page->fecha->getInputTextType() ?>" name="x_fecha" id="x_fecha" data-table="view_out_tdcfcv" data-field="x_fecha" value="<?= $Page->fecha->EditValue ?>" data-page="1" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha->formatPattern()) ?>"<?= $Page->fecha->editAttributes() ?> aria-describedby="x_fecha_help">
<?= $Page->fecha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage() ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fview_out_tdcfcvedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fview_out_tdcfcvedit", "x_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nro_control->Visible) { // nro_control ?>
    <div id="r_nro_control"<?= $Page->nro_control->rowAttributes() ?>>
        <label id="elh_view_out_tdcfcv_nro_control" for="x_nro_control" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_control->caption() ?><?= $Page->nro_control->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nro_control->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_nro_control">
<input type="<?= $Page->nro_control->getInputTextType() ?>" name="x_nro_control" id="x_nro_control" data-table="view_out_tdcfcv" data-field="x_nro_control" value="<?= $Page->nro_control->EditValue ?>" data-page="1" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->nro_control->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nro_control->formatPattern()) ?>"<?= $Page->nro_control->editAttributes() ?> aria-describedby="x_nro_control_help">
<?= $Page->nro_control->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nro_control->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
    <div id="r_cliente"<?= $Page->cliente->rowAttributes() ?>>
        <label id="elh_view_out_tdcfcv_cliente" for="x_cliente" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cliente->caption() ?><?= $Page->cliente->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cliente->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_cliente">
<div class="input-group flex-nowrap">
    <select
        id="x_cliente"
        name="x_cliente"
        class="form-control ew-select<?= $Page->cliente->isInvalidClass() ?>"
        data-select2-id="fview_out_tdcfcvedit_x_cliente"
        data-table="view_out_tdcfcv"
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
loadjs.ready("fview_out_tdcfcvedit", function() {
    var options = { name: "x_cliente", selectId: "fview_out_tdcfcvedit_x_cliente" };
    if (fview_out_tdcfcvedit.lists.cliente?.lookupOptions.length) {
        options.data = { id: "x_cliente", form: "fview_out_tdcfcvedit" };
    } else {
        options.ajax = { id: "x_cliente", form: "fview_out_tdcfcvedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.view_out_tdcfcv.fields.cliente.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
    <div id="r_doc_afectado"<?= $Page->doc_afectado->rowAttributes() ?>>
        <label id="elh_view_out_tdcfcv_doc_afectado" for="x_doc_afectado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->doc_afectado->caption() ?><?= $Page->doc_afectado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->doc_afectado->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_doc_afectado">
<input type="<?= $Page->doc_afectado->getInputTextType() ?>" name="x_doc_afectado" id="x_doc_afectado" data-table="view_out_tdcfcv" data-field="x_doc_afectado" value="<?= $Page->doc_afectado->EditValue ?>" data-page="1" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->doc_afectado->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->doc_afectado->formatPattern()) ?>"<?= $Page->doc_afectado->editAttributes() ?> aria-describedby="x_doc_afectado_help">
<?= $Page->doc_afectado->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->doc_afectado->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <div id="r_nota"<?= $Page->nota->rowAttributes() ?>>
        <label id="elh_view_out_tdcfcv_nota" for="x_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota->caption() ?><?= $Page->nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nota->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_nota">
<textarea data-table="view_out_tdcfcv" data-field="x_nota" data-page="1" name="x_nota" id="x_nota" cols="35" rows="3" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>"<?= $Page->nota->editAttributes() ?> aria-describedby="x_nota_help"><?= $Page->nota->EditValue ?></textarea>
<?= $Page->nota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
    <div id="r_estatus"<?= $Page->estatus->rowAttributes() ?>>
        <label id="elh_view_out_tdcfcv_estatus" for="x_estatus" class="<?= $Page->LeftColumnClass ?>"><?= $Page->estatus->caption() ?><?= $Page->estatus->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->estatus->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_estatus">
    <select
        id="x_estatus"
        name="x_estatus"
        class="form-select ew-select<?= $Page->estatus->isInvalidClass() ?>"
        <?php if (!$Page->estatus->IsNativeSelect) { ?>
        data-select2-id="fview_out_tdcfcvedit_x_estatus"
        <?php } ?>
        data-table="view_out_tdcfcv"
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
loadjs.ready("fview_out_tdcfcvedit", function() {
    var options = { name: "x_estatus", selectId: "fview_out_tdcfcvedit_x_estatus" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fview_out_tdcfcvedit.lists.estatus?.lookupOptions.length) {
        options.data = { id: "x_estatus", form: "fview_out_tdcfcvedit" };
    } else {
        options.ajax = { id: "x_estatus", form: "fview_out_tdcfcvedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.view_out_tdcfcv.fields.estatus.selectOptions);
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
        <div class="<?= $Page->MultiPages->tabPaneClasses(2) ?>" id="tab_view_out_tdcfcv2" role="tabpanel"><!-- multi-page .tab-pane -->
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->monto_total->Visible) { // monto_total ?>
    <div id="r_monto_total"<?= $Page->monto_total->rowAttributes() ?>>
        <label id="elh_view_out_tdcfcv_monto_total" for="x_monto_total" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_total->caption() ?><?= $Page->monto_total->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->monto_total->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_monto_total">
<span<?= $Page->monto_total->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->monto_total->getDisplayValue($Page->monto_total->EditValue))) ?>"></span>
<input type="hidden" data-table="view_out_tdcfcv" data-field="x_monto_total" data-hidden="1" data-page="2" name="x_monto_total" id="x_monto_total" value="<?= HtmlEncode($Page->monto_total->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->alicuota_iva->Visible) { // alicuota_iva ?>
    <div id="r_alicuota_iva"<?= $Page->alicuota_iva->rowAttributes() ?>>
        <label id="elh_view_out_tdcfcv_alicuota_iva" for="x_alicuota_iva" class="<?= $Page->LeftColumnClass ?>"><?= $Page->alicuota_iva->caption() ?><?= $Page->alicuota_iva->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->alicuota_iva->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_alicuota_iva">
<span<?= $Page->alicuota_iva->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->alicuota_iva->getDisplayValue($Page->alicuota_iva->EditValue))) ?>"></span>
<input type="hidden" data-table="view_out_tdcfcv" data-field="x_alicuota_iva" data-hidden="1" data-page="2" name="x_alicuota_iva" id="x_alicuota_iva" value="<?= HtmlEncode($Page->alicuota_iva->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->iva->Visible) { // iva ?>
    <div id="r_iva"<?= $Page->iva->rowAttributes() ?>>
        <label id="elh_view_out_tdcfcv_iva" for="x_iva" class="<?= $Page->LeftColumnClass ?>"><?= $Page->iva->caption() ?><?= $Page->iva->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->iva->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_iva">
<span<?= $Page->iva->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->iva->getDisplayValue($Page->iva->EditValue))) ?>"></span>
<input type="hidden" data-table="view_out_tdcfcv" data-field="x_iva" data-hidden="1" data-page="2" name="x_iva" id="x_iva" value="<?= HtmlEncode($Page->iva->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
    <div id="r_total"<?= $Page->total->rowAttributes() ?>>
        <label id="elh_view_out_tdcfcv_total" for="x_total" class="<?= $Page->LeftColumnClass ?>"><?= $Page->total->caption() ?><?= $Page->total->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->total->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_total">
<span<?= $Page->total->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->total->getDisplayValue($Page->total->EditValue))) ?>"></span>
<input type="hidden" data-table="view_out_tdcfcv" data-field="x_total" data-hidden="1" data-page="2" name="x_total" id="x_total" value="<?= HtmlEncode($Page->total->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->igtf->Visible) { // igtf ?>
    <div id="r_igtf"<?= $Page->igtf->rowAttributes() ?>>
        <label id="elh_view_out_tdcfcv_igtf" for="x_igtf" class="<?= $Page->LeftColumnClass ?>"><?= $Page->igtf->caption() ?><?= $Page->igtf->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->igtf->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_igtf">
    <select
        id="x_igtf"
        name="x_igtf"
        class="form-select ew-select<?= $Page->igtf->isInvalidClass() ?>"
        <?php if (!$Page->igtf->IsNativeSelect) { ?>
        data-select2-id="fview_out_tdcfcvedit_x_igtf"
        <?php } ?>
        data-table="view_out_tdcfcv"
        data-field="x_igtf"
        data-page="2"
        data-value-separator="<?= $Page->igtf->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->igtf->getPlaceHolder()) ?>"
        <?= $Page->igtf->editAttributes() ?>>
        <?= $Page->igtf->selectOptionListHtml("x_igtf") ?>
    </select>
    <?= $Page->igtf->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->igtf->getErrorMessage() ?></div>
<?php if (!$Page->igtf->IsNativeSelect) { ?>
<script>
loadjs.ready("fview_out_tdcfcvedit", function() {
    var options = { name: "x_igtf", selectId: "fview_out_tdcfcvedit_x_igtf" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fview_out_tdcfcvedit.lists.igtf?.lookupOptions.length) {
        options.data = { id: "x_igtf", form: "fview_out_tdcfcvedit" };
    } else {
        options.ajax = { id: "x_igtf", form: "fview_out_tdcfcvedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.view_out_tdcfcv.fields.igtf.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto_base_igtf->Visible) { // monto_base_igtf ?>
    <div id="r_monto_base_igtf"<?= $Page->monto_base_igtf->rowAttributes() ?>>
        <label id="elh_view_out_tdcfcv_monto_base_igtf" for="x_monto_base_igtf" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_base_igtf->caption() ?><?= $Page->monto_base_igtf->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->monto_base_igtf->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_monto_base_igtf">
<input type="<?= $Page->monto_base_igtf->getInputTextType() ?>" name="x_monto_base_igtf" id="x_monto_base_igtf" data-table="view_out_tdcfcv" data-field="x_monto_base_igtf" value="<?= $Page->monto_base_igtf->EditValue ?>" data-page="2" size="30" placeholder="<?= HtmlEncode($Page->monto_base_igtf->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->monto_base_igtf->formatPattern()) ?>"<?= $Page->monto_base_igtf->editAttributes() ?> aria-describedby="x_monto_base_igtf_help">
<?= $Page->monto_base_igtf->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto_base_igtf->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
        <div class="<?= $Page->MultiPages->tabPaneClasses(3) ?>" id="tab_view_out_tdcfcv3" role="tabpanel"><!-- multi-page .tab-pane -->
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->dias_credito->Visible) { // dias_credito ?>
    <div id="r_dias_credito"<?= $Page->dias_credito->rowAttributes() ?>>
        <label id="elh_view_out_tdcfcv_dias_credito" for="x_dias_credito" class="<?= $Page->LeftColumnClass ?>"><?= $Page->dias_credito->caption() ?><?= $Page->dias_credito->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->dias_credito->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_dias_credito">
    <select
        id="x_dias_credito"
        name="x_dias_credito"
        class="form-select ew-select<?= $Page->dias_credito->isInvalidClass() ?>"
        <?php if (!$Page->dias_credito->IsNativeSelect) { ?>
        data-select2-id="fview_out_tdcfcvedit_x_dias_credito"
        <?php } ?>
        data-table="view_out_tdcfcv"
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
loadjs.ready("fview_out_tdcfcvedit", function() {
    var options = { name: "x_dias_credito", selectId: "fview_out_tdcfcvedit_x_dias_credito" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fview_out_tdcfcvedit.lists.dias_credito?.lookupOptions.length) {
        options.data = { id: "x_dias_credito", form: "fview_out_tdcfcvedit" };
    } else {
        options.ajax = { id: "x_dias_credito", form: "fview_out_tdcfcvedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.view_out_tdcfcv.fields.dias_credito.selectOptions);
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
        <label id="elh_view_out_tdcfcv_nro_despacho" for="x_nro_despacho" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_despacho->caption() ?><?= $Page->nro_despacho->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nro_despacho->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_nro_despacho">
<input type="<?= $Page->nro_despacho->getInputTextType() ?>" name="x_nro_despacho" id="x_nro_despacho" data-table="view_out_tdcfcv" data-field="x_nro_despacho" value="<?= $Page->nro_despacho->EditValue ?>" data-page="3" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->nro_despacho->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nro_despacho->formatPattern()) ?>"<?= $Page->nro_despacho->editAttributes() ?> aria-describedby="x_nro_despacho_help">
<?= $Page->nro_despacho->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nro_despacho->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->asesor_asignado->Visible) { // asesor_asignado ?>
    <div id="r_asesor_asignado"<?= $Page->asesor_asignado->rowAttributes() ?>>
        <label id="elh_view_out_tdcfcv_asesor_asignado" for="x_asesor_asignado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->asesor_asignado->caption() ?><?= $Page->asesor_asignado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->asesor_asignado->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_asesor_asignado">
    <select
        id="x_asesor_asignado"
        name="x_asesor_asignado"
        class="form-control ew-select<?= $Page->asesor_asignado->isInvalidClass() ?>"
        data-select2-id="fview_out_tdcfcvedit_x_asesor_asignado"
        data-table="view_out_tdcfcv"
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
loadjs.ready("fview_out_tdcfcvedit", function() {
    var options = { name: "x_asesor_asignado", selectId: "fview_out_tdcfcvedit_x_asesor_asignado" };
    if (fview_out_tdcfcvedit.lists.asesor_asignado?.lookupOptions.length) {
        options.data = { id: "x_asesor_asignado", form: "fview_out_tdcfcvedit" };
    } else {
        options.ajax = { id: "x_asesor_asignado", form: "fview_out_tdcfcvedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.view_out_tdcfcv.fields.asesor_asignado.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
    </div><!-- /multi-page tabs .tab-content -->
</div><!-- /multi-page tabs -->
</div><!-- /multi-page -->
    <input type="hidden" data-table="view_out_tdcfcv" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?php
    if (in_array("view_out", explode(",", $Page->getCurrentDetailTable())) && $view_out->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("view_out", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "ViewOutGrid.php" ?>
<?php } ?>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fview_out_tdcfcvedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fview_out_tdcfcvedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
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
    $( document ).ready(function() {
        <?php 
        if(isset($_REQUEST["my_estatus"])) {
            echo '$("#x_estatus").value("' . $_REQUEST["my_estatus"] . '");';
        }
        ?>
    });
    $(document).ready(function() {

        function gestionarEstadoInput(esLectura) {
            if (esLectura) {
                $("#x_monto_base_igtf").attr("readonly", true).css("background-color", "#e9ecef");
            } else {
                $("#x_monto_base_igtf").removeAttr("readonly").css("background-color", "#fff").focus();
            }
        }

        function toggleIGTF() {
            if ($("#x_igtf").val() === "S") {
                $("#r_monto_base_igtf").show();
                $('#modalIGTF').modal({backdrop: 'static', keyboard: false});
                $('#modalIGTF').modal('show');
            } else {
                $("#r_monto_base_igtf").hide();
                $("#x_monto_base_igtf").val(0);
            }
        }

        // Evento al cambiar el select
        $("#x_igtf").change(function() {
            toggleIGTF();
        });

        // Lógica de los botones de la modal
        $(".btn-opcion").click(function() {
            var origen = $(this).data("origen");
            var montoCalculado = 0;
            var totalMaximo = parseFloat($("#x_total").val()) || 0;
            if (origen === 'otro') {
                gestionarEstadoInput(false); // Habilitar escritura
                $("#x_monto_base_igtf").val(0);
            } else {
                gestionarEstadoInput(true); // Bloquear escritura
                if (origen === 'subtotal') montoCalculado = $("#x_monto_total").val();
                else if (origen === 'iva') montoCalculado = $("#x_iva").val();
                else if (origen === 'total') montoCalculado = totalMaximo;
                $("#x_monto_base_igtf").val(montoCalculado);
            }
            $('#modalIGTF').modal('hide');
        });

        // PLUS: Validación en tiempo real si eligen "Otro"
        $("#x_monto_base_igtf").on('keyup change', function() {
            // Solo validamos si NO es readonly (es decir, si eligieron "Otro")
            if (!$(this).prop('readonly')) {
                var valorIngresado = parseFloat($(this).val()) || 0;
                var totalMaximo = parseFloat($("#x_total").val()) || 0;
                if (valorIngresado > totalMaximo) {
                    ew.alert("El monto base IGTF no puede ser mayor al Total de la factura (" + totalMaximo + ")");
                    $(this).val(totalMaximo); // Lo reseteamos al máximo permitido
                }
            }
        });
    });
});
</script>
