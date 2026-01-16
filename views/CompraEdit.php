<?php

namespace PHPMaker2024\mandrake;

// Page object
$CompraEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fcompraedit" id="fcompraedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { compra: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fcompraedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcompraedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null], fields.id.isInvalid],
            ["proveedor", [fields.proveedor.visible && fields.proveedor.required ? ew.Validators.required(fields.proveedor.caption) : null], fields.proveedor.isInvalid],
            ["tipo_documento", [fields.tipo_documento.visible && fields.tipo_documento.required ? ew.Validators.required(fields.tipo_documento.caption) : null], fields.tipo_documento.isInvalid],
            ["doc_afectado", [fields.doc_afectado.visible && fields.doc_afectado.required ? ew.Validators.required(fields.doc_afectado.caption) : null], fields.doc_afectado.isInvalid],
            ["documento", [fields.documento.visible && fields.documento.required ? ew.Validators.required(fields.documento.caption) : null], fields.documento.isInvalid],
            ["nro_control", [fields.nro_control.visible && fields.nro_control.required ? ew.Validators.required(fields.nro_control.caption) : null], fields.nro_control.isInvalid],
            ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(fields.fecha.clientFormatPattern)], fields.fecha.isInvalid],
            ["descripcion", [fields.descripcion.visible && fields.descripcion.required ? ew.Validators.required(fields.descripcion.caption) : null], fields.descripcion.isInvalid],
            ["aplica_retencion", [fields.aplica_retencion.visible && fields.aplica_retencion.required ? ew.Validators.required(fields.aplica_retencion.caption) : null], fields.aplica_retencion.isInvalid],
            ["monto_exento", [fields.monto_exento.visible && fields.monto_exento.required ? ew.Validators.required(fields.monto_exento.caption) : null, ew.Validators.float], fields.monto_exento.isInvalid],
            ["monto_gravado", [fields.monto_gravado.visible && fields.monto_gravado.required ? ew.Validators.required(fields.monto_gravado.caption) : null, ew.Validators.float], fields.monto_gravado.isInvalid],
            ["alicuota", [fields.alicuota.visible && fields.alicuota.required ? ew.Validators.required(fields.alicuota.caption) : null, ew.Validators.float], fields.alicuota.isInvalid],
            ["ref_iva", [fields.ref_iva.visible && fields.ref_iva.required ? ew.Validators.required(fields.ref_iva.caption) : null], fields.ref_iva.isInvalid],
            ["ref_islr", [fields.ref_islr.visible && fields.ref_islr.required ? ew.Validators.required(fields.ref_islr.caption) : null], fields.ref_islr.isInvalid],
            ["ref_municipal", [fields.ref_municipal.visible && fields.ref_municipal.required ? ew.Validators.required(fields.ref_municipal.caption) : null], fields.ref_municipal.isInvalid],
            ["fecha_registro", [fields.fecha_registro.visible && fields.fecha_registro.required ? ew.Validators.required(fields.fecha_registro.caption) : null, ew.Validators.datetime(fields.fecha_registro.clientFormatPattern)], fields.fecha_registro.isInvalid],
            ["anulado", [fields.anulado.visible && fields.anulado.required ? ew.Validators.required(fields.anulado.caption) : null], fields.anulado.isInvalid]
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
            "proveedor": <?= $Page->proveedor->toClientList($Page) ?>,
            "tipo_documento": <?= $Page->tipo_documento->toClientList($Page) ?>,
            "aplica_retencion": <?= $Page->aplica_retencion->toClientList($Page) ?>,
            "anulado": <?= $Page->anulado->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="compra">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-multi-page"><!-- multi-page -->
<div class="ew-nav<?= $Page->MultiPages->containerClasses() ?>" id="pages_CompraEdit"><!-- multi-page tabs -->
    <ul class="<?= $Page->MultiPages->navClasses() ?>" role="tablist">
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(1) ?>" data-bs-target="#tab_compra1" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_compra1" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(1)) ?>"><?= $Page->pageCaption(1) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(2) ?>" data-bs-target="#tab_compra2" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_compra2" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(2)) ?>"><?= $Page->pageCaption(2) ?></button></li>
    </ul>
    <div class="<?= $Page->MultiPages->tabContentClasses() ?>"><!-- multi-page tabs .tab-content -->
        <div class="<?= $Page->MultiPages->tabPaneClasses(1) ?>" id="tab_compra1" role="tabpanel"><!-- multi-page .tab-pane -->
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->id->Visible) { // id ?>
    <div id="r_id"<?= $Page->id->rowAttributes() ?>>
        <label id="elh_compra_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id->caption() ?><?= $Page->id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->id->cellAttributes() ?>>
<span id="el_compra_id">
<span<?= $Page->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id->getDisplayValue($Page->id->EditValue))) ?>"></span>
<input type="hidden" data-table="compra" data-field="x_id" data-hidden="1" data-page="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->proveedor->Visible) { // proveedor ?>
    <div id="r_proveedor"<?= $Page->proveedor->rowAttributes() ?>>
        <label id="elh_compra_proveedor" for="x_proveedor" class="<?= $Page->LeftColumnClass ?>"><?= $Page->proveedor->caption() ?><?= $Page->proveedor->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->proveedor->cellAttributes() ?>>
<span id="el_compra_proveedor">
    <select
        id="x_proveedor"
        name="x_proveedor"
        class="form-control ew-select<?= $Page->proveedor->isInvalidClass() ?>"
        data-select2-id="fcompraedit_x_proveedor"
        data-table="compra"
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
loadjs.ready("fcompraedit", function() {
    var options = { name: "x_proveedor", selectId: "fcompraedit_x_proveedor" };
    if (fcompraedit.lists.proveedor?.lookupOptions.length) {
        options.data = { id: "x_proveedor", form: "fcompraedit" };
    } else {
        options.ajax = { id: "x_proveedor", form: "fcompraedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.compra.fields.proveedor.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <div id="r_tipo_documento"<?= $Page->tipo_documento->rowAttributes() ?>>
        <label id="elh_compra_tipo_documento" for="x_tipo_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_documento->caption() ?><?= $Page->tipo_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_compra_tipo_documento">
    <select
        id="x_tipo_documento"
        name="x_tipo_documento"
        class="form-select ew-select<?= $Page->tipo_documento->isInvalidClass() ?>"
        <?php if (!$Page->tipo_documento->IsNativeSelect) { ?>
        data-select2-id="fcompraedit_x_tipo_documento"
        <?php } ?>
        data-table="compra"
        data-field="x_tipo_documento"
        data-page="1"
        data-value-separator="<?= $Page->tipo_documento->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tipo_documento->getPlaceHolder()) ?>"
        <?= $Page->tipo_documento->editAttributes() ?>>
        <?= $Page->tipo_documento->selectOptionListHtml("x_tipo_documento") ?>
    </select>
    <?= $Page->tipo_documento->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->tipo_documento->getErrorMessage() ?></div>
<?php if (!$Page->tipo_documento->IsNativeSelect) { ?>
<script>
loadjs.ready("fcompraedit", function() {
    var options = { name: "x_tipo_documento", selectId: "fcompraedit_x_tipo_documento" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fcompraedit.lists.tipo_documento?.lookupOptions.length) {
        options.data = { id: "x_tipo_documento", form: "fcompraedit" };
    } else {
        options.ajax = { id: "x_tipo_documento", form: "fcompraedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.compra.fields.tipo_documento.selectOptions);
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
        <label id="elh_compra_doc_afectado" for="x_doc_afectado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->doc_afectado->caption() ?><?= $Page->doc_afectado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->doc_afectado->cellAttributes() ?>>
<span id="el_compra_doc_afectado">
<input type="<?= $Page->doc_afectado->getInputTextType() ?>" name="x_doc_afectado" id="x_doc_afectado" data-table="compra" data-field="x_doc_afectado" value="<?= $Page->doc_afectado->EditValue ?>" data-page="1" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->doc_afectado->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->doc_afectado->formatPattern()) ?>"<?= $Page->doc_afectado->editAttributes() ?> aria-describedby="x_doc_afectado_help">
<?= $Page->doc_afectado->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->doc_afectado->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
    <div id="r_documento"<?= $Page->documento->rowAttributes() ?>>
        <label id="elh_compra_documento" for="x_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->documento->caption() ?><?= $Page->documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->documento->cellAttributes() ?>>
<span id="el_compra_documento">
<input type="<?= $Page->documento->getInputTextType() ?>" name="x_documento" id="x_documento" data-table="compra" data-field="x_documento" value="<?= $Page->documento->EditValue ?>" data-page="1" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->documento->formatPattern()) ?>"<?= $Page->documento->editAttributes() ?> aria-describedby="x_documento_help">
<?= $Page->documento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->documento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nro_control->Visible) { // nro_control ?>
    <div id="r_nro_control"<?= $Page->nro_control->rowAttributes() ?>>
        <label id="elh_compra_nro_control" for="x_nro_control" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_control->caption() ?><?= $Page->nro_control->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nro_control->cellAttributes() ?>>
<span id="el_compra_nro_control">
<input type="<?= $Page->nro_control->getInputTextType() ?>" name="x_nro_control" id="x_nro_control" data-table="compra" data-field="x_nro_control" value="<?= $Page->nro_control->EditValue ?>" data-page="1" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->nro_control->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nro_control->formatPattern()) ?>"<?= $Page->nro_control->editAttributes() ?> aria-describedby="x_nro_control_help">
<?= $Page->nro_control->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nro_control->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <label id="elh_compra_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha->cellAttributes() ?>>
<span id="el_compra_fecha">
<input type="<?= $Page->fecha->getInputTextType() ?>" name="x_fecha" id="x_fecha" data-table="compra" data-field="x_fecha" value="<?= $Page->fecha->EditValue ?>" data-page="1" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha->formatPattern()) ?>"<?= $Page->fecha->editAttributes() ?> aria-describedby="x_fecha_help">
<?= $Page->fecha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage() ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcompraedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fcompraedit", "x_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <div id="r_descripcion"<?= $Page->descripcion->rowAttributes() ?>>
        <label id="elh_compra_descripcion" for="x_descripcion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descripcion->caption() ?><?= $Page->descripcion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->descripcion->cellAttributes() ?>>
<span id="el_compra_descripcion">
<textarea data-table="compra" data-field="x_descripcion" data-page="1" name="x_descripcion" id="x_descripcion" cols="30" rows="3" placeholder="<?= HtmlEncode($Page->descripcion->getPlaceHolder()) ?>"<?= $Page->descripcion->editAttributes() ?> aria-describedby="x_descripcion_help"><?= $Page->descripcion->EditValue ?></textarea>
<?= $Page->descripcion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->descripcion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha_registro->Visible) { // fecha_registro ?>
    <div id="r_fecha_registro"<?= $Page->fecha_registro->rowAttributes() ?>>
        <label id="elh_compra_fecha_registro" for="x_fecha_registro" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha_registro->caption() ?><?= $Page->fecha_registro->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha_registro->cellAttributes() ?>>
<span id="el_compra_fecha_registro">
<input type="<?= $Page->fecha_registro->getInputTextType() ?>" name="x_fecha_registro" id="x_fecha_registro" data-table="compra" data-field="x_fecha_registro" value="<?= $Page->fecha_registro->EditValue ?>" data-page="1" placeholder="<?= HtmlEncode($Page->fecha_registro->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha_registro->formatPattern()) ?>"<?= $Page->fecha_registro->editAttributes() ?> aria-describedby="x_fecha_registro_help">
<?= $Page->fecha_registro->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha_registro->getErrorMessage() ?></div>
<?php if (!$Page->fecha_registro->ReadOnly && !$Page->fecha_registro->Disabled && !isset($Page->fecha_registro->EditAttrs["readonly"]) && !isset($Page->fecha_registro->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcompraedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fcompraedit", "x_fecha_registro", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
        <div class="<?= $Page->MultiPages->tabPaneClasses(2) ?>" id="tab_compra2" role="tabpanel"><!-- multi-page .tab-pane -->
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->aplica_retencion->Visible) { // aplica_retencion ?>
    <div id="r_aplica_retencion"<?= $Page->aplica_retencion->rowAttributes() ?>>
        <label id="elh_compra_aplica_retencion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->aplica_retencion->caption() ?><?= $Page->aplica_retencion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->aplica_retencion->cellAttributes() ?>>
<span id="el_compra_aplica_retencion">
<template id="tp_x_aplica_retencion">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="compra" data-field="x_aplica_retencion" name="x_aplica_retencion" id="x_aplica_retencion"<?= $Page->aplica_retencion->editAttributes() ?>>
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
    data-table="compra"
    data-field="x_aplica_retencion"
    data-page="2"
    data-value-separator="<?= $Page->aplica_retencion->displayValueSeparatorAttribute() ?>"
    <?= $Page->aplica_retencion->editAttributes() ?>></selection-list>
<?= $Page->aplica_retencion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->aplica_retencion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto_exento->Visible) { // monto_exento ?>
    <div id="r_monto_exento"<?= $Page->monto_exento->rowAttributes() ?>>
        <label id="elh_compra_monto_exento" for="x_monto_exento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_exento->caption() ?><?= $Page->monto_exento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->monto_exento->cellAttributes() ?>>
<span id="el_compra_monto_exento">
<input type="<?= $Page->monto_exento->getInputTextType() ?>" name="x_monto_exento" id="x_monto_exento" data-table="compra" data-field="x_monto_exento" value="<?= $Page->monto_exento->EditValue ?>" data-page="2" size="30" placeholder="<?= HtmlEncode($Page->monto_exento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->monto_exento->formatPattern()) ?>"<?= $Page->monto_exento->editAttributes() ?> aria-describedby="x_monto_exento_help">
<?= $Page->monto_exento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto_exento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto_gravado->Visible) { // monto_gravado ?>
    <div id="r_monto_gravado"<?= $Page->monto_gravado->rowAttributes() ?>>
        <label id="elh_compra_monto_gravado" for="x_monto_gravado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_gravado->caption() ?><?= $Page->monto_gravado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->monto_gravado->cellAttributes() ?>>
<span id="el_compra_monto_gravado">
<input type="<?= $Page->monto_gravado->getInputTextType() ?>" name="x_monto_gravado" id="x_monto_gravado" data-table="compra" data-field="x_monto_gravado" value="<?= $Page->monto_gravado->EditValue ?>" data-page="2" size="30" placeholder="<?= HtmlEncode($Page->monto_gravado->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->monto_gravado->formatPattern()) ?>"<?= $Page->monto_gravado->editAttributes() ?> aria-describedby="x_monto_gravado_help">
<?= $Page->monto_gravado->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto_gravado->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->alicuota->Visible) { // alicuota ?>
    <div id="r_alicuota"<?= $Page->alicuota->rowAttributes() ?>>
        <label id="elh_compra_alicuota" for="x_alicuota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->alicuota->caption() ?><?= $Page->alicuota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->alicuota->cellAttributes() ?>>
<span id="el_compra_alicuota">
<input type="<?= $Page->alicuota->getInputTextType() ?>" name="x_alicuota" id="x_alicuota" data-table="compra" data-field="x_alicuota" value="<?= $Page->alicuota->EditValue ?>" data-page="2" size="30" placeholder="<?= HtmlEncode($Page->alicuota->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->alicuota->formatPattern()) ?>"<?= $Page->alicuota->editAttributes() ?> aria-describedby="x_alicuota_help">
<?= $Page->alicuota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->alicuota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ref_iva->Visible) { // ref_iva ?>
    <div id="r_ref_iva"<?= $Page->ref_iva->rowAttributes() ?>>
        <label id="elh_compra_ref_iva" for="x_ref_iva" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ref_iva->caption() ?><?= $Page->ref_iva->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ref_iva->cellAttributes() ?>>
<span id="el_compra_ref_iva">
<input type="<?= $Page->ref_iva->getInputTextType() ?>" name="x_ref_iva" id="x_ref_iva" data-table="compra" data-field="x_ref_iva" value="<?= $Page->ref_iva->EditValue ?>" data-page="2" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ref_iva->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ref_iva->formatPattern()) ?>"<?= $Page->ref_iva->editAttributes() ?> aria-describedby="x_ref_iva_help">
<?= $Page->ref_iva->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ref_iva->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ref_islr->Visible) { // ref_islr ?>
    <div id="r_ref_islr"<?= $Page->ref_islr->rowAttributes() ?>>
        <label id="elh_compra_ref_islr" for="x_ref_islr" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ref_islr->caption() ?><?= $Page->ref_islr->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ref_islr->cellAttributes() ?>>
<span id="el_compra_ref_islr">
<input type="<?= $Page->ref_islr->getInputTextType() ?>" name="x_ref_islr" id="x_ref_islr" data-table="compra" data-field="x_ref_islr" value="<?= $Page->ref_islr->EditValue ?>" data-page="2" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ref_islr->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ref_islr->formatPattern()) ?>"<?= $Page->ref_islr->editAttributes() ?> aria-describedby="x_ref_islr_help">
<?= $Page->ref_islr->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ref_islr->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ref_municipal->Visible) { // ref_municipal ?>
    <div id="r_ref_municipal"<?= $Page->ref_municipal->rowAttributes() ?>>
        <label id="elh_compra_ref_municipal" for="x_ref_municipal" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ref_municipal->caption() ?><?= $Page->ref_municipal->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ref_municipal->cellAttributes() ?>>
<span id="el_compra_ref_municipal">
<input type="<?= $Page->ref_municipal->getInputTextType() ?>" name="x_ref_municipal" id="x_ref_municipal" data-table="compra" data-field="x_ref_municipal" value="<?= $Page->ref_municipal->EditValue ?>" data-page="2" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ref_municipal->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ref_municipal->formatPattern()) ?>"<?= $Page->ref_municipal->editAttributes() ?> aria-describedby="x_ref_municipal_help">
<?= $Page->ref_municipal->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ref_municipal->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->anulado->Visible) { // anulado ?>
    <div id="r_anulado"<?= $Page->anulado->rowAttributes() ?>>
        <label id="elh_compra_anulado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->anulado->caption() ?><?= $Page->anulado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->anulado->cellAttributes() ?>>
<span id="el_compra_anulado">
<template id="tp_x_anulado">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="compra" data-field="x_anulado" name="x_anulado" id="x_anulado"<?= $Page->anulado->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_anulado" class="ew-item-list"></div>
<selection-list hidden
    id="x_anulado"
    name="x_anulado"
    value="<?= HtmlEncode($Page->anulado->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_anulado"
    data-target="dsl_x_anulado"
    data-repeatcolumn="5"
    class="form-control<?= $Page->anulado->isInvalidClass() ?>"
    data-table="compra"
    data-field="x_anulado"
    data-page="2"
    data-value-separator="<?= $Page->anulado->displayValueSeparatorAttribute() ?>"
    <?= $Page->anulado->editAttributes() ?>></selection-list>
<?= $Page->anulado->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->anulado->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
    </div><!-- /multi-page tabs .tab-content -->
</div><!-- /multi-page tabs -->
</div><!-- /multi-page -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcompraedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcompraedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("compra");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here, no need to add script tags.
    $( document ).ready(function() {
    	if($("#x_tipo_documento").val() == "FC") {
        	$("#r_doc_afectado").hide();
        }
        else {
        	$("#r_doc_afectado").show();
        }
    });
    $("#x_tipo_documento").change(function() {
        if($("#x_tipo_documento").val() == "FC") {
        	$("#r_doc_afectado").hide();
        }
        else {
        	$("#r_doc_afectado").show();
        }
    });
});
</script>
