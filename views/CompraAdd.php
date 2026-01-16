<?php

namespace PHPMaker2024\mandrake;

// Page object
$CompraAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { compra: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fcompraadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcompraadd")
        .setPageId("add")

        // Add fields
        .setFields([
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
            ["alicuota", [fields.alicuota.visible && fields.alicuota.required ? ew.Validators.required(fields.alicuota.caption) : null, ew.Validators.float], fields.alicuota.isInvalid]
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
<form name="fcompraadd" id="fcompraadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="compra">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-multi-page"><!-- multi-page -->
<div class="ew-nav<?= $Page->MultiPages->containerClasses() ?>" id="pages_CompraAdd"><!-- multi-page tabs -->
    <ul class="<?= $Page->MultiPages->navClasses() ?>" role="tablist">
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(1) ?>" data-bs-target="#tab_compra1" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_compra1" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(1)) ?>"><?= $Page->pageCaption(1) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(2) ?>" data-bs-target="#tab_compra2" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_compra2" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(2)) ?>"><?= $Page->pageCaption(2) ?></button></li>
    </ul>
    <div class="<?= $Page->MultiPages->tabContentClasses() ?>"><!-- multi-page tabs .tab-content -->
        <div class="<?= $Page->MultiPages->tabPaneClasses(1) ?>" id="tab_compra1" role="tabpanel"><!-- multi-page .tab-pane -->
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->proveedor->Visible) { // proveedor ?>
    <div id="r_proveedor"<?= $Page->proveedor->rowAttributes() ?>>
        <label id="elh_compra_proveedor" for="x_proveedor" class="<?= $Page->LeftColumnClass ?>"><?= $Page->proveedor->caption() ?><?= $Page->proveedor->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->proveedor->cellAttributes() ?>>
<span id="el_compra_proveedor">
    <select
        id="x_proveedor"
        name="x_proveedor"
        class="form-control ew-select<?= $Page->proveedor->isInvalidClass() ?>"
        data-select2-id="fcompraadd_x_proveedor"
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
loadjs.ready("fcompraadd", function() {
    var options = { name: "x_proveedor", selectId: "fcompraadd_x_proveedor" };
    if (fcompraadd.lists.proveedor?.lookupOptions.length) {
        options.data = { id: "x_proveedor", form: "fcompraadd" };
    } else {
        options.ajax = { id: "x_proveedor", form: "fcompraadd", limit: ew.LOOKUP_PAGE_SIZE };
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
        data-select2-id="fcompraadd_x_tipo_documento"
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
loadjs.ready("fcompraadd", function() {
    var options = { name: "x_tipo_documento", selectId: "fcompraadd_x_tipo_documento" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fcompraadd.lists.tipo_documento?.lookupOptions.length) {
        options.data = { id: "x_tipo_documento", form: "fcompraadd" };
    } else {
        options.ajax = { id: "x_tipo_documento", form: "fcompraadd", limit: ew.LOOKUP_PAGE_SIZE };
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
loadjs.ready(["fcompraadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fcompraadd", "x_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
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
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
        <div class="<?= $Page->MultiPages->tabPaneClasses(2) ?>" id="tab_compra2" role="tabpanel"><!-- multi-page .tab-pane -->
<div class="ew-add-div"><!-- page* -->
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
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
    </div><!-- /multi-page tabs .tab-content -->
</div><!-- /multi-page tabs -->
</div><!-- /multi-page -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcompraadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcompraadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("compra");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here
    // document.write("page loaded");
    $(document).ready(function() {
      var alicuota = 0;
      <?php
      	$sql = "SELECT alicuota FROM alicuota WHERE activo = 'S' AND codigo = 'GEN';";
      	echo "alicuota = " . floatval(ExecuteScalar($sql)) . ";";
      ?>
      $("#x_alicuota").val(alicuota);
    });
    $( document ).ready(function() {
        $("#r_doc_afectado").hide();
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
