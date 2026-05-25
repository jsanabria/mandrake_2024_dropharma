<?php

namespace PHPMaker2024\mandrake;

// Page object
$ReporteSeniatNeEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="freporte_seniat_needit" id="freporte_seniat_needit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { reporte_seniat_ne: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var freporte_seniat_needit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("freporte_seniat_needit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null], fields.id.isInvalid],
            ["periodo", [fields.periodo.visible && fields.periodo.required ? ew.Validators.required(fields.periodo.caption) : null], fields.periodo.isInvalid],
            ["cantidad", [fields.cantidad.visible && fields.cantidad.required ? ew.Validators.required(fields.cantidad.caption) : null, ew.Validators.integer], fields.cantidad.isInvalid],
            ["monto_total", [fields.monto_total.visible && fields.monto_total.required ? ew.Validators.required(fields.monto_total.caption) : null, ew.Validators.float], fields.monto_total.isInvalid],
            ["email_destino", [fields.email_destino.visible && fields.email_destino.required ? ew.Validators.required(fields.email_destino.caption) : null], fields.email_destino.isInvalid],
            ["enviado_en", [fields.enviado_en.visible && fields.enviado_en.required ? ew.Validators.required(fields.enviado_en.caption) : null, ew.Validators.datetime(fields.enviado_en.clientFormatPattern)], fields.enviado_en.isInvalid],
            ["usuario", [fields.usuario.visible && fields.usuario.required ? ew.Validators.required(fields.usuario.caption) : null], fields.usuario.isInvalid]
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
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="reporte_seniat_ne">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->id->Visible) { // id ?>
    <div id="r_id"<?= $Page->id->rowAttributes() ?>>
        <label id="elh_reporte_seniat_ne_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id->caption() ?><?= $Page->id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->id->cellAttributes() ?>>
<span id="el_reporte_seniat_ne_id">
<span<?= $Page->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id->getDisplayValue($Page->id->EditValue))) ?>"></span>
<input type="hidden" data-table="reporte_seniat_ne" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->periodo->Visible) { // periodo ?>
    <div id="r_periodo"<?= $Page->periodo->rowAttributes() ?>>
        <label id="elh_reporte_seniat_ne_periodo" for="x_periodo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->periodo->caption() ?><?= $Page->periodo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->periodo->cellAttributes() ?>>
<span id="el_reporte_seniat_ne_periodo">
<input type="<?= $Page->periodo->getInputTextType() ?>" name="x_periodo" id="x_periodo" data-table="reporte_seniat_ne" data-field="x_periodo" value="<?= $Page->periodo->EditValue ?>" size="30" maxlength="7" placeholder="<?= HtmlEncode($Page->periodo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->periodo->formatPattern()) ?>"<?= $Page->periodo->editAttributes() ?> aria-describedby="x_periodo_help">
<?= $Page->periodo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->periodo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cantidad->Visible) { // cantidad ?>
    <div id="r_cantidad"<?= $Page->cantidad->rowAttributes() ?>>
        <label id="elh_reporte_seniat_ne_cantidad" for="x_cantidad" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidad->caption() ?><?= $Page->cantidad->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cantidad->cellAttributes() ?>>
<span id="el_reporte_seniat_ne_cantidad">
<input type="<?= $Page->cantidad->getInputTextType() ?>" name="x_cantidad" id="x_cantidad" data-table="reporte_seniat_ne" data-field="x_cantidad" value="<?= $Page->cantidad->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->cantidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cantidad->formatPattern()) ?>"<?= $Page->cantidad->editAttributes() ?> aria-describedby="x_cantidad_help">
<?= $Page->cantidad->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cantidad->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto_total->Visible) { // monto_total ?>
    <div id="r_monto_total"<?= $Page->monto_total->rowAttributes() ?>>
        <label id="elh_reporte_seniat_ne_monto_total" for="x_monto_total" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_total->caption() ?><?= $Page->monto_total->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->monto_total->cellAttributes() ?>>
<span id="el_reporte_seniat_ne_monto_total">
<input type="<?= $Page->monto_total->getInputTextType() ?>" name="x_monto_total" id="x_monto_total" data-table="reporte_seniat_ne" data-field="x_monto_total" value="<?= $Page->monto_total->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->monto_total->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->monto_total->formatPattern()) ?>"<?= $Page->monto_total->editAttributes() ?> aria-describedby="x_monto_total_help">
<?= $Page->monto_total->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto_total->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->email_destino->Visible) { // email_destino ?>
    <div id="r_email_destino"<?= $Page->email_destino->rowAttributes() ?>>
        <label id="elh_reporte_seniat_ne_email_destino" for="x_email_destino" class="<?= $Page->LeftColumnClass ?>"><?= $Page->email_destino->caption() ?><?= $Page->email_destino->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->email_destino->cellAttributes() ?>>
<span id="el_reporte_seniat_ne_email_destino">
<input type="<?= $Page->email_destino->getInputTextType() ?>" name="x_email_destino" id="x_email_destino" data-table="reporte_seniat_ne" data-field="x_email_destino" value="<?= $Page->email_destino->EditValue ?>" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->email_destino->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->email_destino->formatPattern()) ?>"<?= $Page->email_destino->editAttributes() ?> aria-describedby="x_email_destino_help">
<?= $Page->email_destino->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->email_destino->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->enviado_en->Visible) { // enviado_en ?>
    <div id="r_enviado_en"<?= $Page->enviado_en->rowAttributes() ?>>
        <label id="elh_reporte_seniat_ne_enviado_en" for="x_enviado_en" class="<?= $Page->LeftColumnClass ?>"><?= $Page->enviado_en->caption() ?><?= $Page->enviado_en->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->enviado_en->cellAttributes() ?>>
<span id="el_reporte_seniat_ne_enviado_en">
<input type="<?= $Page->enviado_en->getInputTextType() ?>" name="x_enviado_en" id="x_enviado_en" data-table="reporte_seniat_ne" data-field="x_enviado_en" value="<?= $Page->enviado_en->EditValue ?>" placeholder="<?= HtmlEncode($Page->enviado_en->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->enviado_en->formatPattern()) ?>"<?= $Page->enviado_en->editAttributes() ?> aria-describedby="x_enviado_en_help">
<?= $Page->enviado_en->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->enviado_en->getErrorMessage() ?></div>
<?php if (!$Page->enviado_en->ReadOnly && !$Page->enviado_en->Disabled && !isset($Page->enviado_en->EditAttrs["readonly"]) && !isset($Page->enviado_en->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["freporte_seniat_needit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("freporte_seniat_needit", "x_enviado_en", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->usuario->Visible) { // usuario ?>
    <div id="r_usuario"<?= $Page->usuario->rowAttributes() ?>>
        <label id="elh_reporte_seniat_ne_usuario" for="x_usuario" class="<?= $Page->LeftColumnClass ?>"><?= $Page->usuario->caption() ?><?= $Page->usuario->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->usuario->cellAttributes() ?>>
<span id="el_reporte_seniat_ne_usuario">
<input type="<?= $Page->usuario->getInputTextType() ?>" name="x_usuario" id="x_usuario" data-table="reporte_seniat_ne" data-field="x_usuario" value="<?= $Page->usuario->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->usuario->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->usuario->formatPattern()) ?>"<?= $Page->usuario->editAttributes() ?> aria-describedby="x_usuario_help">
<?= $Page->usuario->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->usuario->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="freporte_seniat_needit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="freporte_seniat_needit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("reporte_seniat_ne");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
