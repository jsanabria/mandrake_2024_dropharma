<?php

namespace PHPMaker2024\mandrake;

// Page object
$BancoTipoPagoAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { banco_tipo_pago: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fbanco_tipo_pagoadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fbanco_tipo_pagoadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["banco", [fields.banco.visible && fields.banco.required ? ew.Validators.required(fields.banco.caption) : null, ew.Validators.integer], fields.banco.isInvalid],
            ["tipo_pago", [fields.tipo_pago.visible && fields.tipo_pago.required ? ew.Validators.required(fields.tipo_pago.caption) : null], fields.tipo_pago.isInvalid],
            ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid]
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
<form name="fbanco_tipo_pagoadd" id="fbanco_tipo_pagoadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="banco_tipo_pago">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->banco->Visible) { // banco ?>
    <div id="r_banco"<?= $Page->banco->rowAttributes() ?>>
        <label id="elh_banco_tipo_pago_banco" for="x_banco" class="<?= $Page->LeftColumnClass ?>"><?= $Page->banco->caption() ?><?= $Page->banco->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->banco->cellAttributes() ?>>
<span id="el_banco_tipo_pago_banco">
<input type="<?= $Page->banco->getInputTextType() ?>" name="x_banco" id="x_banco" data-table="banco_tipo_pago" data-field="x_banco" value="<?= $Page->banco->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->banco->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->banco->formatPattern()) ?>"<?= $Page->banco->editAttributes() ?> aria-describedby="x_banco_help">
<?= $Page->banco->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->banco->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo_pago->Visible) { // tipo_pago ?>
    <div id="r_tipo_pago"<?= $Page->tipo_pago->rowAttributes() ?>>
        <label id="elh_banco_tipo_pago_tipo_pago" for="x_tipo_pago" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_pago->caption() ?><?= $Page->tipo_pago->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tipo_pago->cellAttributes() ?>>
<span id="el_banco_tipo_pago_tipo_pago">
<input type="<?= $Page->tipo_pago->getInputTextType() ?>" name="x_tipo_pago" id="x_tipo_pago" data-table="banco_tipo_pago" data-field="x_tipo_pago" value="<?= $Page->tipo_pago->EditValue ?>" size="30" maxlength="2" placeholder="<?= HtmlEncode($Page->tipo_pago->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->tipo_pago->formatPattern()) ?>"<?= $Page->tipo_pago->editAttributes() ?> aria-describedby="x_tipo_pago_help">
<?= $Page->tipo_pago->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tipo_pago->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda"<?= $Page->moneda->rowAttributes() ?>>
        <label id="elh_banco_tipo_pago_moneda" for="x_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->moneda->cellAttributes() ?>>
<span id="el_banco_tipo_pago_moneda">
<input type="<?= $Page->moneda->getInputTextType() ?>" name="x_moneda" id="x_moneda" data-table="banco_tipo_pago" data-field="x_moneda" value="<?= $Page->moneda->EditValue ?>" size="30" maxlength="6" placeholder="<?= HtmlEncode($Page->moneda->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->moneda->formatPattern()) ?>"<?= $Page->moneda->editAttributes() ?> aria-describedby="x_moneda_help">
<?= $Page->moneda->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->moneda->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fbanco_tipo_pagoadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fbanco_tipo_pagoadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("banco_tipo_pago");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
