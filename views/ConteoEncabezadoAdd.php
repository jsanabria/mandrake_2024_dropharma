<?php

namespace PHPMaker2024\mandrake;

// Page object
$ConteoEncabezadoAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { conteo_encabezado: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fconteo_encabezadoadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fconteo_encabezadoadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(fields.fecha.clientFormatPattern)], fields.fecha.isInvalid],
            ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
            ["procesado", [fields.procesado.visible && fields.procesado.required ? ew.Validators.required(fields.procesado.caption) : null], fields.procesado.isInvalid],
            ["totalizar", [fields.totalizar.visible && fields.totalizar.required ? ew.Validators.required(fields.totalizar.caption) : null], fields.totalizar.isInvalid]
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
            "procesado": <?= $Page->procesado->toClientList($Page) ?>,
            "totalizar": <?= $Page->totalizar->toClientList($Page) ?>,
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
<form name="fconteo_encabezadoadd" id="fconteo_encabezadoadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="conteo_encabezado">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <label id="elh_conteo_encabezado_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha->cellAttributes() ?>>
<span id="el_conteo_encabezado_fecha">
<input type="<?= $Page->fecha->getInputTextType() ?>" name="x_fecha" id="x_fecha" data-table="conteo_encabezado" data-field="x_fecha" value="<?= $Page->fecha->EditValue ?>" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha->formatPattern()) ?>"<?= $Page->fecha->editAttributes() ?> aria-describedby="x_fecha_help">
<?= $Page->fecha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage() ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fconteo_encabezadoadd", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
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
    ew.createDateTimePicker("fconteo_encabezadoadd", "x_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <div id="r_nota"<?= $Page->nota->rowAttributes() ?>>
        <label id="elh_conteo_encabezado_nota" for="x_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota->caption() ?><?= $Page->nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nota->cellAttributes() ?>>
<span id="el_conteo_encabezado_nota">
<input type="<?= $Page->nota->getInputTextType() ?>" name="x_nota" id="x_nota" data-table="conteo_encabezado" data-field="x_nota" value="<?= $Page->nota->EditValue ?>" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nota->formatPattern()) ?>"<?= $Page->nota->editAttributes() ?> aria-describedby="x_nota_help">
<?= $Page->nota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->procesado->Visible) { // procesado ?>
    <div id="r_procesado"<?= $Page->procesado->rowAttributes() ?>>
        <label id="elh_conteo_encabezado_procesado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->procesado->caption() ?><?= $Page->procesado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->procesado->cellAttributes() ?>>
<span id="el_conteo_encabezado_procesado">
<template id="tp_x_procesado">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="conteo_encabezado" data-field="x_procesado" name="x_procesado" id="x_procesado"<?= $Page->procesado->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_procesado" class="ew-item-list"></div>
<selection-list hidden
    id="x_procesado"
    name="x_procesado"
    value="<?= HtmlEncode($Page->procesado->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_procesado"
    data-target="dsl_x_procesado"
    data-repeatcolumn="5"
    class="form-control<?= $Page->procesado->isInvalidClass() ?>"
    data-table="conteo_encabezado"
    data-field="x_procesado"
    data-value-separator="<?= $Page->procesado->displayValueSeparatorAttribute() ?>"
    <?= $Page->procesado->editAttributes() ?>></selection-list>
<?= $Page->procesado->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->procesado->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->totalizar->Visible) { // totalizar ?>
    <div id="r_totalizar"<?= $Page->totalizar->rowAttributes() ?>>
        <label id="elh_conteo_encabezado_totalizar" class="<?= $Page->LeftColumnClass ?>"><?= $Page->totalizar->caption() ?><?= $Page->totalizar->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->totalizar->cellAttributes() ?>>
<span id="el_conteo_encabezado_totalizar">
<template id="tp_x_totalizar">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="conteo_encabezado" data-field="x_totalizar" name="x_totalizar" id="x_totalizar"<?= $Page->totalizar->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_totalizar" class="ew-item-list"></div>
<selection-list hidden
    id="x_totalizar"
    name="x_totalizar"
    value="<?= HtmlEncode($Page->totalizar->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_totalizar"
    data-target="dsl_x_totalizar"
    data-repeatcolumn="5"
    class="form-control<?= $Page->totalizar->isInvalidClass() ?>"
    data-table="conteo_encabezado"
    data-field="x_totalizar"
    data-value-separator="<?= $Page->totalizar->displayValueSeparatorAttribute() ?>"
    <?= $Page->totalizar->editAttributes() ?>></selection-list>
<?= $Page->totalizar->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->totalizar->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fconteo_encabezadoadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fconteo_encabezadoadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("conteo_encabezado");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
