<?php

namespace PHPMaker2024\mandrake;

// Page object
$ConceptosBancariosAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { conceptos_bancarios: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fconceptos_bancariosadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fconceptos_bancariosadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["nombre_concepto", [fields.nombre_concepto.visible && fields.nombre_concepto.required ? ew.Validators.required(fields.nombre_concepto.caption) : null], fields.nombre_concepto.isInvalid],
            ["tipo", [fields.tipo.visible && fields.tipo.required ? ew.Validators.required(fields.tipo.caption) : null], fields.tipo.isInvalid],
            ["cuenta", [fields.cuenta.visible && fields.cuenta.required ? ew.Validators.required(fields.cuenta.caption) : null, ew.Validators.integer], fields.cuenta.isInvalid]
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
            "tipo": <?= $Page->tipo->toClientList($Page) ?>,
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
<form name="fconceptos_bancariosadd" id="fconceptos_bancariosadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="conceptos_bancarios">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->nombre_concepto->Visible) { // nombre_concepto ?>
    <div id="r_nombre_concepto"<?= $Page->nombre_concepto->rowAttributes() ?>>
        <label id="elh_conceptos_bancarios_nombre_concepto" for="x_nombre_concepto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nombre_concepto->caption() ?><?= $Page->nombre_concepto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nombre_concepto->cellAttributes() ?>>
<span id="el_conceptos_bancarios_nombre_concepto">
<input type="<?= $Page->nombre_concepto->getInputTextType() ?>" name="x_nombre_concepto" id="x_nombre_concepto" data-table="conceptos_bancarios" data-field="x_nombre_concepto" value="<?= $Page->nombre_concepto->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->nombre_concepto->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nombre_concepto->formatPattern()) ?>"<?= $Page->nombre_concepto->editAttributes() ?> aria-describedby="x_nombre_concepto_help">
<?= $Page->nombre_concepto->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nombre_concepto->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
    <div id="r_tipo"<?= $Page->tipo->rowAttributes() ?>>
        <label id="elh_conceptos_bancarios_tipo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo->caption() ?><?= $Page->tipo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tipo->cellAttributes() ?>>
<span id="el_conceptos_bancarios_tipo">
<template id="tp_x_tipo">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="conceptos_bancarios" data-field="x_tipo" name="x_tipo" id="x_tipo"<?= $Page->tipo->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_tipo" class="ew-item-list"></div>
<selection-list hidden
    id="x_tipo"
    name="x_tipo"
    value="<?= HtmlEncode($Page->tipo->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_tipo"
    data-target="dsl_x_tipo"
    data-repeatcolumn="5"
    class="form-control<?= $Page->tipo->isInvalidClass() ?>"
    data-table="conceptos_bancarios"
    data-field="x_tipo"
    data-value-separator="<?= $Page->tipo->displayValueSeparatorAttribute() ?>"
    <?= $Page->tipo->editAttributes() ?>></selection-list>
<?= $Page->tipo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tipo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
    <div id="r_cuenta"<?= $Page->cuenta->rowAttributes() ?>>
        <label id="elh_conceptos_bancarios_cuenta" for="x_cuenta" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cuenta->caption() ?><?= $Page->cuenta->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cuenta->cellAttributes() ?>>
<span id="el_conceptos_bancarios_cuenta">
<input type="<?= $Page->cuenta->getInputTextType() ?>" name="x_cuenta" id="x_cuenta" data-table="conceptos_bancarios" data-field="x_cuenta" value="<?= $Page->cuenta->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->cuenta->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cuenta->formatPattern()) ?>"<?= $Page->cuenta->editAttributes() ?> aria-describedby="x_cuenta_help">
<?= $Page->cuenta->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cuenta->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fconceptos_bancariosadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fconceptos_bancariosadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("conceptos_bancarios");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
