<?php

namespace PHPMaker2024\mandrake;

// Page object
$PagosComprasEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fpagos_comprasedit" id="fpagos_comprasedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { pagos_compras: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fpagos_comprasedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpagos_comprasedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["proveedor", [fields.proveedor.visible && fields.proveedor.required ? ew.Validators.required(fields.proveedor.caption) : null], fields.proveedor.isInvalid],
            ["id_documento", [fields.id_documento.visible && fields.id_documento.required ? ew.Validators.required(fields.id_documento.caption) : null, ew.Validators.integer], fields.id_documento.isInvalid],
            ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(fields.fecha.clientFormatPattern)], fields.fecha.isInvalid],
            ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
            ["anexos", [fields.anexos.visible && fields.anexos.required ? ew.Validators.required(fields.anexos.caption) : null], fields.anexos.isInvalid],
            ["pivote2", [fields.pivote2.visible && fields.pivote2.required ? ew.Validators.required(fields.pivote2.caption) : null], fields.pivote2.isInvalid]
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

        // Dynamic selection lists
        .setLists({
            "proveedor": <?= $Page->proveedor->toClientList($Page) ?>,
            "id_documento": <?= $Page->id_documento->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="pagos_compras">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->proveedor->Visible) { // proveedor ?>
    <div id="r_proveedor"<?= $Page->proveedor->rowAttributes() ?>>
        <label id="elh_pagos_compras_proveedor" for="x_proveedor" class="<?= $Page->LeftColumnClass ?>"><?= $Page->proveedor->caption() ?><?= $Page->proveedor->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->proveedor->cellAttributes() ?>>
<span id="el_pagos_compras_proveedor">
    <select
        id="x_proveedor"
        name="x_proveedor"
        class="form-control ew-select<?= $Page->proveedor->isInvalidClass() ?>"
        data-select2-id="fpagos_comprasedit_x_proveedor"
        data-table="pagos_compras"
        data-field="x_proveedor"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->proveedor->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->proveedor->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->proveedor->getPlaceHolder()) ?>"
        <?= $Page->proveedor->editAttributes() ?>>
        <?= $Page->proveedor->selectOptionListHtml("x_proveedor") ?>
    </select>
    <?= $Page->proveedor->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->proveedor->getErrorMessage() ?></div>
<?= $Page->proveedor->Lookup->getParamTag($Page, "p_x_proveedor") ?>
<script>
loadjs.ready("fpagos_comprasedit", function() {
    var options = { name: "x_proveedor", selectId: "fpagos_comprasedit_x_proveedor" };
    if (fpagos_comprasedit.lists.proveedor?.lookupOptions.length) {
        options.data = { id: "x_proveedor", form: "fpagos_comprasedit" };
    } else {
        options.ajax = { id: "x_proveedor", form: "fpagos_comprasedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.pagos_compras.fields.proveedor.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->id_documento->Visible) { // id_documento ?>
    <div id="r_id_documento"<?= $Page->id_documento->rowAttributes() ?>>
        <label id="elh_pagos_compras_id_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_documento->caption() ?><?= $Page->id_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->id_documento->cellAttributes() ?>>
<span id="el_pagos_compras_id_documento">
<?php
if (IsRTL()) {
    $Page->id_documento->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_id_documento" class="ew-auto-suggest">
    <input type="<?= $Page->id_documento->getInputTextType() ?>" class="form-control" name="sv_x_id_documento" id="sv_x_id_documento" value="<?= RemoveHtml($Page->id_documento->EditValue) ?>" autocomplete="off" size="30" placeholder="<?= HtmlEncode($Page->id_documento->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->id_documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->id_documento->formatPattern()) ?>"<?= $Page->id_documento->editAttributes() ?> aria-describedby="x_id_documento_help">
</span>
<selection-list hidden class="form-control" data-table="pagos_compras" data-field="x_id_documento" data-input="sv_x_id_documento" data-value-separator="<?= $Page->id_documento->displayValueSeparatorAttribute() ?>" name="x_id_documento" id="x_id_documento" value="<?= HtmlEncode($Page->id_documento->CurrentValue) ?>"></selection-list>
<?= $Page->id_documento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->id_documento->getErrorMessage() ?></div>
<script>
loadjs.ready("fpagos_comprasedit", function() {
    fpagos_comprasedit.createAutoSuggest(Object.assign({"id":"x_id_documento","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->id_documento->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.pagos_compras.fields.id_documento.autoSuggestOptions));
});
</script>
<?= $Page->id_documento->Lookup->getParamTag($Page, "p_x_id_documento") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <label id="elh_pagos_compras_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha->cellAttributes() ?>>
<span id="el_pagos_compras_fecha">
<input type="<?= $Page->fecha->getInputTextType() ?>" name="x_fecha" id="x_fecha" data-table="pagos_compras" data-field="x_fecha" value="<?= $Page->fecha->EditValue ?>" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha->formatPattern()) ?>"<?= $Page->fecha->editAttributes() ?> aria-describedby="x_fecha_help">
<?= $Page->fecha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage() ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpagos_comprasedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fpagos_comprasedit", "x_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda"<?= $Page->moneda->rowAttributes() ?>>
        <label id="elh_pagos_compras_moneda" for="x_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->moneda->cellAttributes() ?>>
<span id="el_pagos_compras_moneda">
<input type="<?= $Page->moneda->getInputTextType() ?>" name="x_moneda" id="x_moneda" data-table="pagos_compras" data-field="x_moneda" value="<?= $Page->moneda->EditValue ?>" size="30" maxlength="6" placeholder="<?= HtmlEncode($Page->moneda->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->moneda->formatPattern()) ?>"<?= $Page->moneda->editAttributes() ?> aria-describedby="x_moneda_help">
<?= $Page->moneda->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->moneda->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->anexos->Visible) { // anexos ?>
    <div id="r_anexos"<?= $Page->anexos->rowAttributes() ?>>
        <label id="elh_pagos_compras_anexos" for="x_anexos" class="<?= $Page->LeftColumnClass ?>"><?= $Page->anexos->caption() ?><?= $Page->anexos->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->anexos->cellAttributes() ?>>
<span id="el_pagos_compras_anexos">
<textarea data-table="pagos_compras" data-field="x_anexos" name="x_anexos" id="x_anexos" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->anexos->getPlaceHolder()) ?>"<?= $Page->anexos->editAttributes() ?> aria-describedby="x_anexos_help"><?= $Page->anexos->EditValue ?></textarea>
<?= $Page->anexos->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->anexos->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pivote2->Visible) { // pivote2 ?>
    <div id="r_pivote2"<?= $Page->pivote2->rowAttributes() ?>>
        <label id="elh_pagos_compras_pivote2" for="x_pivote2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pivote2->caption() ?><?= $Page->pivote2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->pivote2->cellAttributes() ?>>
<span id="el_pagos_compras_pivote2">
<input type="<?= $Page->pivote2->getInputTextType() ?>" name="x_pivote2" id="x_pivote2" data-table="pagos_compras" data-field="x_pivote2" value="<?= $Page->pivote2->EditValue ?>" size="30" maxlength="1" placeholder="<?= HtmlEncode($Page->pivote2->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->pivote2->formatPattern()) ?>"<?= $Page->pivote2->editAttributes() ?> aria-describedby="x_pivote2_help">
<?= $Page->pivote2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pivote2->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="pagos_compras" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fpagos_comprasedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fpagos_comprasedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("pagos_compras");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
