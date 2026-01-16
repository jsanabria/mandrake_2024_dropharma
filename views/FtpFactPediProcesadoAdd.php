<?php

namespace PHPMaker2024\mandrake;

// Page object
$FtpFactPediProcesadoAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { ftp_fact_pedi_procesado: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fftp_fact_pedi_procesadoadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fftp_fact_pedi_procesadoadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["factura", [fields.factura.visible && fields.factura.required ? ew.Validators.required(fields.factura.caption) : null], fields.factura.isInvalid],
            ["pedido", [fields.pedido.visible && fields.pedido.required ? ew.Validators.required(fields.pedido.caption) : null], fields.pedido.isInvalid],
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
<form name="fftp_fact_pedi_procesadoadd" id="fftp_fact_pedi_procesadoadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="ftp_fact_pedi_procesado">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->factura->Visible) { // factura ?>
    <div id="r_factura"<?= $Page->factura->rowAttributes() ?>>
        <label id="elh_ftp_fact_pedi_procesado_factura" for="x_factura" class="<?= $Page->LeftColumnClass ?>"><?= $Page->factura->caption() ?><?= $Page->factura->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->factura->cellAttributes() ?>>
<span id="el_ftp_fact_pedi_procesado_factura">
<input type="<?= $Page->factura->getInputTextType() ?>" name="x_factura" id="x_factura" data-table="ftp_fact_pedi_procesado" data-field="x_factura" value="<?= $Page->factura->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->factura->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->factura->formatPattern()) ?>"<?= $Page->factura->editAttributes() ?> aria-describedby="x_factura_help">
<?= $Page->factura->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->factura->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pedido->Visible) { // pedido ?>
    <div id="r_pedido"<?= $Page->pedido->rowAttributes() ?>>
        <label id="elh_ftp_fact_pedi_procesado_pedido" for="x_pedido" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pedido->caption() ?><?= $Page->pedido->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->pedido->cellAttributes() ?>>
<span id="el_ftp_fact_pedi_procesado_pedido">
<input type="<?= $Page->pedido->getInputTextType() ?>" name="x_pedido" id="x_pedido" data-table="ftp_fact_pedi_procesado" data-field="x_pedido" value="<?= $Page->pedido->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->pedido->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->pedido->formatPattern()) ?>"<?= $Page->pedido->editAttributes() ?> aria-describedby="x_pedido_help">
<?= $Page->pedido->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pedido->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha_hora->Visible) { // fecha_hora ?>
    <div id="r_fecha_hora"<?= $Page->fecha_hora->rowAttributes() ?>>
        <label id="elh_ftp_fact_pedi_procesado_fecha_hora" for="x_fecha_hora" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha_hora->caption() ?><?= $Page->fecha_hora->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha_hora->cellAttributes() ?>>
<span id="el_ftp_fact_pedi_procesado_fecha_hora">
<input type="<?= $Page->fecha_hora->getInputTextType() ?>" name="x_fecha_hora" id="x_fecha_hora" data-table="ftp_fact_pedi_procesado" data-field="x_fecha_hora" value="<?= $Page->fecha_hora->EditValue ?>" maxlength="19" placeholder="<?= HtmlEncode($Page->fecha_hora->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha_hora->formatPattern()) ?>"<?= $Page->fecha_hora->editAttributes() ?> aria-describedby="x_fecha_hora_help">
<?= $Page->fecha_hora->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha_hora->getErrorMessage() ?></div>
<?php if (!$Page->fecha_hora->ReadOnly && !$Page->fecha_hora->Disabled && !isset($Page->fecha_hora->EditAttrs["readonly"]) && !isset($Page->fecha_hora->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fftp_fact_pedi_procesadoadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fftp_fact_pedi_procesadoadd", "x_fecha_hora", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
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
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fftp_fact_pedi_procesadoadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fftp_fact_pedi_procesadoadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("ftp_fact_pedi_procesado");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
