<?php

namespace PHPMaker2024\mandrake;

// Page object
$CobrosClienteDetalleAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cobros_cliente_detalle: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fcobros_cliente_detalleadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcobros_cliente_detalleadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["metodo_pago", [fields.metodo_pago.visible && fields.metodo_pago.required ? ew.Validators.required(fields.metodo_pago.caption) : null], fields.metodo_pago.isInvalid],
            ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
            ["monto_moneda", [fields.monto_moneda.visible && fields.monto_moneda.required ? ew.Validators.required(fields.monto_moneda.caption) : null, ew.Validators.float], fields.monto_moneda.isInvalid],
            ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
            ["banco", [fields.banco.visible && fields.banco.required ? ew.Validators.required(fields.banco.caption) : null, ew.Validators.integer], fields.banco.isInvalid]
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
            "metodo_pago": <?= $Page->metodo_pago->toClientList($Page) ?>,
            "moneda": <?= $Page->moneda->toClientList($Page) ?>,
            "banco": <?= $Page->banco->toClientList($Page) ?>,
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
<form name="fcobros_cliente_detalleadd" id="fcobros_cliente_detalleadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cobros_cliente_detalle">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->metodo_pago->Visible) { // metodo_pago ?>
    <div id="r_metodo_pago"<?= $Page->metodo_pago->rowAttributes() ?>>
        <label id="elh_cobros_cliente_detalle_metodo_pago" class="<?= $Page->LeftColumnClass ?>"><?= $Page->metodo_pago->caption() ?><?= $Page->metodo_pago->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->metodo_pago->cellAttributes() ?>>
<span id="el_cobros_cliente_detalle_metodo_pago">
    <select
        id="x_metodo_pago"
        name="x_metodo_pago"
        class="form-control ew-select<?= $Page->metodo_pago->isInvalidClass() ?>"
        data-select2-id="fcobros_cliente_detalleadd_x_metodo_pago"
        data-table="cobros_cliente_detalle"
        data-field="x_metodo_pago"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->metodo_pago->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->metodo_pago->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->metodo_pago->getPlaceHolder()) ?>"
        <?= $Page->metodo_pago->editAttributes() ?>>
        <?= $Page->metodo_pago->selectOptionListHtml("x_metodo_pago") ?>
    </select>
    <?= $Page->metodo_pago->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->metodo_pago->getErrorMessage() ?></div>
<?= $Page->metodo_pago->Lookup->getParamTag($Page, "p_x_metodo_pago") ?>
<script>
loadjs.ready("fcobros_cliente_detalleadd", function() {
    var options = { name: "x_metodo_pago", selectId: "fcobros_cliente_detalleadd_x_metodo_pago" };
    if (fcobros_cliente_detalleadd.lists.metodo_pago?.lookupOptions.length) {
        options.data = { id: "x_metodo_pago", form: "fcobros_cliente_detalleadd" };
    } else {
        options.ajax = { id: "x_metodo_pago", form: "fcobros_cliente_detalleadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.cobros_cliente_detalle.fields.metodo_pago.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
    <div id="r_referencia"<?= $Page->referencia->rowAttributes() ?>>
        <label id="elh_cobros_cliente_detalle_referencia" for="x_referencia" class="<?= $Page->LeftColumnClass ?>"><?= $Page->referencia->caption() ?><?= $Page->referencia->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->referencia->cellAttributes() ?>>
<span id="el_cobros_cliente_detalle_referencia">
<input type="<?= $Page->referencia->getInputTextType() ?>" name="x_referencia" id="x_referencia" data-table="cobros_cliente_detalle" data-field="x_referencia" value="<?= $Page->referencia->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->referencia->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->referencia->formatPattern()) ?>"<?= $Page->referencia->editAttributes() ?> aria-describedby="x_referencia_help">
<?= $Page->referencia->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->referencia->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto_moneda->Visible) { // monto_moneda ?>
    <div id="r_monto_moneda"<?= $Page->monto_moneda->rowAttributes() ?>>
        <label id="elh_cobros_cliente_detalle_monto_moneda" for="x_monto_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_moneda->caption() ?><?= $Page->monto_moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->monto_moneda->cellAttributes() ?>>
<span id="el_cobros_cliente_detalle_monto_moneda">
<input type="<?= $Page->monto_moneda->getInputTextType() ?>" name="x_monto_moneda" id="x_monto_moneda" data-table="cobros_cliente_detalle" data-field="x_monto_moneda" value="<?= $Page->monto_moneda->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->monto_moneda->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->monto_moneda->formatPattern()) ?>"<?= $Page->monto_moneda->editAttributes() ?> aria-describedby="x_monto_moneda_help">
<?= $Page->monto_moneda->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto_moneda->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda"<?= $Page->moneda->rowAttributes() ?>>
        <label id="elh_cobros_cliente_detalle_moneda" for="x_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->moneda->cellAttributes() ?>>
<span id="el_cobros_cliente_detalle_moneda">
    <select
        id="x_moneda"
        name="x_moneda"
        class="form-select ew-select<?= $Page->moneda->isInvalidClass() ?>"
        <?php if (!$Page->moneda->IsNativeSelect) { ?>
        data-select2-id="fcobros_cliente_detalleadd_x_moneda"
        <?php } ?>
        data-table="cobros_cliente_detalle"
        data-field="x_moneda"
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
loadjs.ready("fcobros_cliente_detalleadd", function() {
    var options = { name: "x_moneda", selectId: "fcobros_cliente_detalleadd_x_moneda" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fcobros_cliente_detalleadd.lists.moneda?.lookupOptions.length) {
        options.data = { id: "x_moneda", form: "fcobros_cliente_detalleadd" };
    } else {
        options.ajax = { id: "x_moneda", form: "fcobros_cliente_detalleadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.cobros_cliente_detalle.fields.moneda.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->banco->Visible) { // banco ?>
    <div id="r_banco"<?= $Page->banco->rowAttributes() ?>>
        <label id="elh_cobros_cliente_detalle_banco" class="<?= $Page->LeftColumnClass ?>"><?= $Page->banco->caption() ?><?= $Page->banco->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->banco->cellAttributes() ?>>
<span id="el_cobros_cliente_detalle_banco">
<?php
if (IsRTL()) {
    $Page->banco->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_banco" class="ew-auto-suggest">
    <input type="<?= $Page->banco->getInputTextType() ?>" class="form-control" name="sv_x_banco" id="sv_x_banco" value="<?= RemoveHtml($Page->banco->EditValue) ?>" autocomplete="off" size="30" placeholder="<?= HtmlEncode($Page->banco->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->banco->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->banco->formatPattern()) ?>"<?= $Page->banco->editAttributes() ?> aria-describedby="x_banco_help">
</span>
<selection-list hidden class="form-control" data-table="cobros_cliente_detalle" data-field="x_banco" data-input="sv_x_banco" data-value-separator="<?= $Page->banco->displayValueSeparatorAttribute() ?>" name="x_banco" id="x_banco" value="<?= HtmlEncode($Page->banco->CurrentValue) ?>"></selection-list>
<?= $Page->banco->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->banco->getErrorMessage() ?></div>
<script>
loadjs.ready("fcobros_cliente_detalleadd", function() {
    fcobros_cliente_detalleadd.createAutoSuggest(Object.assign({"id":"x_banco","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->banco->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.cobros_cliente_detalle.fields.banco.autoSuggestOptions));
});
</script>
<?= $Page->banco->Lookup->getParamTag($Page, "p_x_banco") ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcobros_cliente_detalleadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcobros_cliente_detalleadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("cobros_cliente_detalle");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
