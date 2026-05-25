<?php

namespace PHPMaker2024\mandrake;

// Page object
$TransferenciaBancoAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { transferencia_banco: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var ftransferencia_bancoadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftransferencia_bancoadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["banco_origen", [fields.banco_origen.visible && fields.banco_origen.required ? ew.Validators.required(fields.banco_origen.caption) : null, ew.Validators.integer], fields.banco_origen.isInvalid],
            ["banco_destino", [fields.banco_destino.visible && fields.banco_destino.required ? ew.Validators.required(fields.banco_destino.caption) : null, ew.Validators.integer], fields.banco_destino.isInvalid],
            ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(fields.fecha.clientFormatPattern)], fields.fecha.isInvalid],
            ["descripcion", [fields.descripcion.visible && fields.descripcion.required ? ew.Validators.required(fields.descripcion.caption) : null], fields.descripcion.isInvalid],
            ["monto", [fields.monto.visible && fields.monto.required ? ew.Validators.required(fields.monto.caption) : null, ew.Validators.float], fields.monto.isInvalid],
            ["comprobante", [fields.comprobante.visible && fields.comprobante.required ? ew.Validators.required(fields.comprobante.caption) : null, ew.Validators.integer], fields.comprobante.isInvalid],
            ["_username", [fields._username.visible && fields._username.required ? ew.Validators.required(fields._username.caption) : null], fields._username.isInvalid]
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
<form name="ftransferencia_bancoadd" id="ftransferencia_bancoadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="transferencia_banco">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->banco_origen->Visible) { // banco_origen ?>
    <div id="r_banco_origen"<?= $Page->banco_origen->rowAttributes() ?>>
        <label id="elh_transferencia_banco_banco_origen" for="x_banco_origen" class="<?= $Page->LeftColumnClass ?>"><?= $Page->banco_origen->caption() ?><?= $Page->banco_origen->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->banco_origen->cellAttributes() ?>>
<span id="el_transferencia_banco_banco_origen">
<input type="<?= $Page->banco_origen->getInputTextType() ?>" name="x_banco_origen" id="x_banco_origen" data-table="transferencia_banco" data-field="x_banco_origen" value="<?= $Page->banco_origen->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->banco_origen->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->banco_origen->formatPattern()) ?>"<?= $Page->banco_origen->editAttributes() ?> aria-describedby="x_banco_origen_help">
<?= $Page->banco_origen->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->banco_origen->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->banco_destino->Visible) { // banco_destino ?>
    <div id="r_banco_destino"<?= $Page->banco_destino->rowAttributes() ?>>
        <label id="elh_transferencia_banco_banco_destino" for="x_banco_destino" class="<?= $Page->LeftColumnClass ?>"><?= $Page->banco_destino->caption() ?><?= $Page->banco_destino->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->banco_destino->cellAttributes() ?>>
<span id="el_transferencia_banco_banco_destino">
<input type="<?= $Page->banco_destino->getInputTextType() ?>" name="x_banco_destino" id="x_banco_destino" data-table="transferencia_banco" data-field="x_banco_destino" value="<?= $Page->banco_destino->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->banco_destino->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->banco_destino->formatPattern()) ?>"<?= $Page->banco_destino->editAttributes() ?> aria-describedby="x_banco_destino_help">
<?= $Page->banco_destino->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->banco_destino->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <label id="elh_transferencia_banco_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha->cellAttributes() ?>>
<span id="el_transferencia_banco_fecha">
<input type="<?= $Page->fecha->getInputTextType() ?>" name="x_fecha" id="x_fecha" data-table="transferencia_banco" data-field="x_fecha" value="<?= $Page->fecha->EditValue ?>" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha->formatPattern()) ?>"<?= $Page->fecha->editAttributes() ?> aria-describedby="x_fecha_help">
<?= $Page->fecha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage() ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["ftransferencia_bancoadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("ftransferencia_bancoadd", "x_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <div id="r_descripcion"<?= $Page->descripcion->rowAttributes() ?>>
        <label id="elh_transferencia_banco_descripcion" for="x_descripcion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descripcion->caption() ?><?= $Page->descripcion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->descripcion->cellAttributes() ?>>
<span id="el_transferencia_banco_descripcion">
<input type="<?= $Page->descripcion->getInputTextType() ?>" name="x_descripcion" id="x_descripcion" data-table="transferencia_banco" data-field="x_descripcion" value="<?= $Page->descripcion->EditValue ?>" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->descripcion->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->descripcion->formatPattern()) ?>"<?= $Page->descripcion->editAttributes() ?> aria-describedby="x_descripcion_help">
<?= $Page->descripcion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->descripcion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
    <div id="r_monto"<?= $Page->monto->rowAttributes() ?>>
        <label id="elh_transferencia_banco_monto" for="x_monto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto->caption() ?><?= $Page->monto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->monto->cellAttributes() ?>>
<span id="el_transferencia_banco_monto">
<input type="<?= $Page->monto->getInputTextType() ?>" name="x_monto" id="x_monto" data-table="transferencia_banco" data-field="x_monto" value="<?= $Page->monto->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->monto->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->monto->formatPattern()) ?>"<?= $Page->monto->editAttributes() ?> aria-describedby="x_monto_help">
<?= $Page->monto->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
    <div id="r_comprobante"<?= $Page->comprobante->rowAttributes() ?>>
        <label id="elh_transferencia_banco_comprobante" for="x_comprobante" class="<?= $Page->LeftColumnClass ?>"><?= $Page->comprobante->caption() ?><?= $Page->comprobante->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->comprobante->cellAttributes() ?>>
<span id="el_transferencia_banco_comprobante">
<input type="<?= $Page->comprobante->getInputTextType() ?>" name="x_comprobante" id="x_comprobante" data-table="transferencia_banco" data-field="x_comprobante" value="<?= $Page->comprobante->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->comprobante->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->comprobante->formatPattern()) ?>"<?= $Page->comprobante->editAttributes() ?> aria-describedby="x_comprobante_help">
<?= $Page->comprobante->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->comprobante->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <div id="r__username"<?= $Page->_username->rowAttributes() ?>>
        <label id="elh_transferencia_banco__username" for="x__username" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_username->caption() ?><?= $Page->_username->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_username->cellAttributes() ?>>
<span id="el_transferencia_banco__username">
<input type="<?= $Page->_username->getInputTextType() ?>" name="x__username" id="x__username" data-table="transferencia_banco" data-field="x__username" value="<?= $Page->_username->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->_username->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_username->formatPattern()) ?>"<?= $Page->_username->editAttributes() ?> aria-describedby="x__username_help">
<?= $Page->_username->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_username->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="ftransferencia_bancoadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="ftransferencia_bancoadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("transferencia_banco");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
