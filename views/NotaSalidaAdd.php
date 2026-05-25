<?php

namespace PHPMaker2024\mandrake;

// Page object
$NotaSalidaAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { nota_salida: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fnota_salidaadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fnota_salidaadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["salida", [fields.salida.visible && fields.salida.required ? ew.Validators.required(fields.salida.caption) : null, ew.Validators.integer], fields.salida.isInvalid],
            ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
            ["usuario", [fields.usuario.visible && fields.usuario.required ? ew.Validators.required(fields.usuario.caption) : null], fields.usuario.isInvalid],
            ["fecha_hora", [fields.fecha_hora.visible && fields.fecha_hora.required ? ew.Validators.required(fields.fecha_hora.caption) : null, ew.Validators.datetime(fields.fecha_hora.clientFormatPattern)], fields.fecha_hora.isInvalid]
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
<form name="fnota_salidaadd" id="fnota_salidaadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="nota_salida">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->salida->Visible) { // salida ?>
    <div id="r_salida"<?= $Page->salida->rowAttributes() ?>>
        <label id="elh_nota_salida_salida" for="x_salida" class="<?= $Page->LeftColumnClass ?>"><?= $Page->salida->caption() ?><?= $Page->salida->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->salida->cellAttributes() ?>>
<span id="el_nota_salida_salida">
<input type="<?= $Page->salida->getInputTextType() ?>" name="x_salida" id="x_salida" data-table="nota_salida" data-field="x_salida" value="<?= $Page->salida->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->salida->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->salida->formatPattern()) ?>"<?= $Page->salida->editAttributes() ?> aria-describedby="x_salida_help">
<?= $Page->salida->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->salida->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <div id="r_nota"<?= $Page->nota->rowAttributes() ?>>
        <label id="elh_nota_salida_nota" for="x_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota->caption() ?><?= $Page->nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nota->cellAttributes() ?>>
<span id="el_nota_salida_nota">
<input type="<?= $Page->nota->getInputTextType() ?>" name="x_nota" id="x_nota" data-table="nota_salida" data-field="x_nota" value="<?= $Page->nota->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nota->formatPattern()) ?>"<?= $Page->nota->editAttributes() ?> aria-describedby="x_nota_help">
<?= $Page->nota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->usuario->Visible) { // usuario ?>
    <div id="r_usuario"<?= $Page->usuario->rowAttributes() ?>>
        <label id="elh_nota_salida_usuario" for="x_usuario" class="<?= $Page->LeftColumnClass ?>"><?= $Page->usuario->caption() ?><?= $Page->usuario->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->usuario->cellAttributes() ?>>
<span id="el_nota_salida_usuario">
<input type="<?= $Page->usuario->getInputTextType() ?>" name="x_usuario" id="x_usuario" data-table="nota_salida" data-field="x_usuario" value="<?= $Page->usuario->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->usuario->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->usuario->formatPattern()) ?>"<?= $Page->usuario->editAttributes() ?> aria-describedby="x_usuario_help">
<?= $Page->usuario->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->usuario->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha_hora->Visible) { // fecha_hora ?>
    <div id="r_fecha_hora"<?= $Page->fecha_hora->rowAttributes() ?>>
        <label id="elh_nota_salida_fecha_hora" for="x_fecha_hora" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha_hora->caption() ?><?= $Page->fecha_hora->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha_hora->cellAttributes() ?>>
<span id="el_nota_salida_fecha_hora">
<input type="<?= $Page->fecha_hora->getInputTextType() ?>" name="x_fecha_hora" id="x_fecha_hora" data-table="nota_salida" data-field="x_fecha_hora" value="<?= $Page->fecha_hora->EditValue ?>" placeholder="<?= HtmlEncode($Page->fecha_hora->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha_hora->formatPattern()) ?>"<?= $Page->fecha_hora->editAttributes() ?> aria-describedby="x_fecha_hora_help">
<?= $Page->fecha_hora->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha_hora->getErrorMessage() ?></div>
<?php if (!$Page->fecha_hora->ReadOnly && !$Page->fecha_hora->Disabled && !isset($Page->fecha_hora->EditAttrs["readonly"]) && !isset($Page->fecha_hora->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fnota_salidaadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fnota_salidaadd", "x_fecha_hora", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fnota_salidaadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fnota_salidaadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("nota_salida");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
