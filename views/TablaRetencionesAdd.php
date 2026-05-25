<?php

namespace PHPMaker2024\mandrake;

// Page object
$TablaRetencionesAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { tabla_retenciones: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var ftabla_retencionesadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftabla_retencionesadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["codigo", [fields.codigo.visible && fields.codigo.required ? ew.Validators.required(fields.codigo.caption) : null], fields.codigo.isInvalid],
            ["tipo", [fields.tipo.visible && fields.tipo.required ? ew.Validators.required(fields.tipo.caption) : null], fields.tipo.isInvalid],
            ["base_imponible", [fields.base_imponible.visible && fields.base_imponible.required ? ew.Validators.required(fields.base_imponible.caption) : null, ew.Validators.float], fields.base_imponible.isInvalid],
            ["tarifa", [fields.tarifa.visible && fields.tarifa.required ? ew.Validators.required(fields.tarifa.caption) : null, ew.Validators.float], fields.tarifa.isInvalid],
            ["sustraendo", [fields.sustraendo.visible && fields.sustraendo.required ? ew.Validators.required(fields.sustraendo.caption) : null, ew.Validators.float], fields.sustraendo.isInvalid],
            ["pagos_mayores", [fields.pagos_mayores.visible && fields.pagos_mayores.required ? ew.Validators.required(fields.pagos_mayores.caption) : null, ew.Validators.float], fields.pagos_mayores.isInvalid],
            ["activo", [fields.activo.visible && fields.activo.required ? ew.Validators.required(fields.activo.caption) : null], fields.activo.isInvalid]
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
            "activo": <?= $Page->activo->toClientList($Page) ?>,
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
<form name="ftabla_retencionesadd" id="ftabla_retencionesadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="tabla_retenciones">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->codigo->Visible) { // codigo ?>
    <div id="r_codigo"<?= $Page->codigo->rowAttributes() ?>>
        <label id="elh_tabla_retenciones_codigo" for="x_codigo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->codigo->caption() ?><?= $Page->codigo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->codigo->cellAttributes() ?>>
<span id="el_tabla_retenciones_codigo">
<input type="<?= $Page->codigo->getInputTextType() ?>" name="x_codigo" id="x_codigo" data-table="tabla_retenciones" data-field="x_codigo" value="<?= $Page->codigo->EditValue ?>" size="30" maxlength="6" placeholder="<?= HtmlEncode($Page->codigo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->codigo->formatPattern()) ?>"<?= $Page->codigo->editAttributes() ?> aria-describedby="x_codigo_help">
<?= $Page->codigo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->codigo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
    <div id="r_tipo"<?= $Page->tipo->rowAttributes() ?>>
        <label id="elh_tabla_retenciones_tipo" for="x_tipo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo->caption() ?><?= $Page->tipo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tipo->cellAttributes() ?>>
<span id="el_tabla_retenciones_tipo">
<input type="<?= $Page->tipo->getInputTextType() ?>" name="x_tipo" id="x_tipo" data-table="tabla_retenciones" data-field="x_tipo" value="<?= $Page->tipo->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->tipo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->tipo->formatPattern()) ?>"<?= $Page->tipo->editAttributes() ?> aria-describedby="x_tipo_help">
<?= $Page->tipo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tipo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->base_imponible->Visible) { // base_imponible ?>
    <div id="r_base_imponible"<?= $Page->base_imponible->rowAttributes() ?>>
        <label id="elh_tabla_retenciones_base_imponible" for="x_base_imponible" class="<?= $Page->LeftColumnClass ?>"><?= $Page->base_imponible->caption() ?><?= $Page->base_imponible->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->base_imponible->cellAttributes() ?>>
<span id="el_tabla_retenciones_base_imponible">
<input type="<?= $Page->base_imponible->getInputTextType() ?>" name="x_base_imponible" id="x_base_imponible" data-table="tabla_retenciones" data-field="x_base_imponible" value="<?= $Page->base_imponible->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->base_imponible->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->base_imponible->formatPattern()) ?>"<?= $Page->base_imponible->editAttributes() ?> aria-describedby="x_base_imponible_help">
<?= $Page->base_imponible->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->base_imponible->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tarifa->Visible) { // tarifa ?>
    <div id="r_tarifa"<?= $Page->tarifa->rowAttributes() ?>>
        <label id="elh_tabla_retenciones_tarifa" for="x_tarifa" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tarifa->caption() ?><?= $Page->tarifa->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tarifa->cellAttributes() ?>>
<span id="el_tabla_retenciones_tarifa">
<input type="<?= $Page->tarifa->getInputTextType() ?>" name="x_tarifa" id="x_tarifa" data-table="tabla_retenciones" data-field="x_tarifa" value="<?= $Page->tarifa->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->tarifa->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->tarifa->formatPattern()) ?>"<?= $Page->tarifa->editAttributes() ?> aria-describedby="x_tarifa_help">
<?= $Page->tarifa->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tarifa->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->sustraendo->Visible) { // sustraendo ?>
    <div id="r_sustraendo"<?= $Page->sustraendo->rowAttributes() ?>>
        <label id="elh_tabla_retenciones_sustraendo" for="x_sustraendo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->sustraendo->caption() ?><?= $Page->sustraendo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->sustraendo->cellAttributes() ?>>
<span id="el_tabla_retenciones_sustraendo">
<input type="<?= $Page->sustraendo->getInputTextType() ?>" name="x_sustraendo" id="x_sustraendo" data-table="tabla_retenciones" data-field="x_sustraendo" value="<?= $Page->sustraendo->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->sustraendo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->sustraendo->formatPattern()) ?>"<?= $Page->sustraendo->editAttributes() ?> aria-describedby="x_sustraendo_help">
<?= $Page->sustraendo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->sustraendo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pagos_mayores->Visible) { // pagos_mayores ?>
    <div id="r_pagos_mayores"<?= $Page->pagos_mayores->rowAttributes() ?>>
        <label id="elh_tabla_retenciones_pagos_mayores" for="x_pagos_mayores" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pagos_mayores->caption() ?><?= $Page->pagos_mayores->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->pagos_mayores->cellAttributes() ?>>
<span id="el_tabla_retenciones_pagos_mayores">
<input type="<?= $Page->pagos_mayores->getInputTextType() ?>" name="x_pagos_mayores" id="x_pagos_mayores" data-table="tabla_retenciones" data-field="x_pagos_mayores" value="<?= $Page->pagos_mayores->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->pagos_mayores->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->pagos_mayores->formatPattern()) ?>"<?= $Page->pagos_mayores->editAttributes() ?> aria-describedby="x_pagos_mayores_help">
<?= $Page->pagos_mayores->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pagos_mayores->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <div id="r_activo"<?= $Page->activo->rowAttributes() ?>>
        <label id="elh_tabla_retenciones_activo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->activo->caption() ?><?= $Page->activo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->activo->cellAttributes() ?>>
<span id="el_tabla_retenciones_activo">
<template id="tp_x_activo">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="tabla_retenciones" data-field="x_activo" name="x_activo" id="x_activo"<?= $Page->activo->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_activo" class="ew-item-list"></div>
<selection-list hidden
    id="x_activo"
    name="x_activo"
    value="<?= HtmlEncode($Page->activo->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_activo"
    data-target="dsl_x_activo"
    data-repeatcolumn="5"
    class="form-control<?= $Page->activo->isInvalidClass() ?>"
    data-table="tabla_retenciones"
    data-field="x_activo"
    data-value-separator="<?= $Page->activo->displayValueSeparatorAttribute() ?>"
    <?= $Page->activo->editAttributes() ?>></selection-list>
<?= $Page->activo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->activo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="ftabla_retencionesadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="ftabla_retencionesadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("tabla_retenciones");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
