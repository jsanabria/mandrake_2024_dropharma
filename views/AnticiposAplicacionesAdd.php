<?php

namespace PHPMaker2024\mandrake;

// Page object
$AnticiposAplicacionesAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { anticipos_aplicaciones: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fanticipos_aplicacionesadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fanticipos_aplicacionesadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["anticipo_cobro_id", [fields.anticipo_cobro_id.visible && fields.anticipo_cobro_id.required ? ew.Validators.required(fields.anticipo_cobro_id.caption) : null, ew.Validators.integer], fields.anticipo_cobro_id.isInvalid],
            ["cobro_factura_id", [fields.cobro_factura_id.visible && fields.cobro_factura_id.required ? ew.Validators.required(fields.cobro_factura_id.caption) : null, ew.Validators.integer], fields.cobro_factura_id.isInvalid],
            ["salida_id", [fields.salida_id.visible && fields.salida_id.required ? ew.Validators.required(fields.salida_id.caption) : null, ew.Validators.integer], fields.salida_id.isInvalid],
            ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(fields.fecha.clientFormatPattern)], fields.fecha.isInvalid],
            ["_username", [fields._username.visible && fields._username.required ? ew.Validators.required(fields._username.caption) : null], fields._username.isInvalid],
            ["monto_moneda", [fields.monto_moneda.visible && fields.monto_moneda.required ? ew.Validators.required(fields.monto_moneda.caption) : null, ew.Validators.float], fields.monto_moneda.isInvalid],
            ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
            ["tasa_factura", [fields.tasa_factura.visible && fields.tasa_factura.required ? ew.Validators.required(fields.tasa_factura.caption) : null, ew.Validators.float], fields.tasa_factura.isInvalid]
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
<form name="fanticipos_aplicacionesadd" id="fanticipos_aplicacionesadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="anticipos_aplicaciones">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->anticipo_cobro_id->Visible) { // anticipo_cobro_id ?>
    <div id="r_anticipo_cobro_id"<?= $Page->anticipo_cobro_id->rowAttributes() ?>>
        <label id="elh_anticipos_aplicaciones_anticipo_cobro_id" for="x_anticipo_cobro_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->anticipo_cobro_id->caption() ?><?= $Page->anticipo_cobro_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->anticipo_cobro_id->cellAttributes() ?>>
<span id="el_anticipos_aplicaciones_anticipo_cobro_id">
<input type="<?= $Page->anticipo_cobro_id->getInputTextType() ?>" name="x_anticipo_cobro_id" id="x_anticipo_cobro_id" data-table="anticipos_aplicaciones" data-field="x_anticipo_cobro_id" value="<?= $Page->anticipo_cobro_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->anticipo_cobro_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->anticipo_cobro_id->formatPattern()) ?>"<?= $Page->anticipo_cobro_id->editAttributes() ?> aria-describedby="x_anticipo_cobro_id_help">
<?= $Page->anticipo_cobro_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->anticipo_cobro_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cobro_factura_id->Visible) { // cobro_factura_id ?>
    <div id="r_cobro_factura_id"<?= $Page->cobro_factura_id->rowAttributes() ?>>
        <label id="elh_anticipos_aplicaciones_cobro_factura_id" for="x_cobro_factura_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cobro_factura_id->caption() ?><?= $Page->cobro_factura_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cobro_factura_id->cellAttributes() ?>>
<span id="el_anticipos_aplicaciones_cobro_factura_id">
<input type="<?= $Page->cobro_factura_id->getInputTextType() ?>" name="x_cobro_factura_id" id="x_cobro_factura_id" data-table="anticipos_aplicaciones" data-field="x_cobro_factura_id" value="<?= $Page->cobro_factura_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->cobro_factura_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cobro_factura_id->formatPattern()) ?>"<?= $Page->cobro_factura_id->editAttributes() ?> aria-describedby="x_cobro_factura_id_help">
<?= $Page->cobro_factura_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cobro_factura_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->salida_id->Visible) { // salida_id ?>
    <div id="r_salida_id"<?= $Page->salida_id->rowAttributes() ?>>
        <label id="elh_anticipos_aplicaciones_salida_id" for="x_salida_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->salida_id->caption() ?><?= $Page->salida_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->salida_id->cellAttributes() ?>>
<span id="el_anticipos_aplicaciones_salida_id">
<input type="<?= $Page->salida_id->getInputTextType() ?>" name="x_salida_id" id="x_salida_id" data-table="anticipos_aplicaciones" data-field="x_salida_id" value="<?= $Page->salida_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->salida_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->salida_id->formatPattern()) ?>"<?= $Page->salida_id->editAttributes() ?> aria-describedby="x_salida_id_help">
<?= $Page->salida_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->salida_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <label id="elh_anticipos_aplicaciones_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha->cellAttributes() ?>>
<span id="el_anticipos_aplicaciones_fecha">
<input type="<?= $Page->fecha->getInputTextType() ?>" name="x_fecha" id="x_fecha" data-table="anticipos_aplicaciones" data-field="x_fecha" value="<?= $Page->fecha->EditValue ?>" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha->formatPattern()) ?>"<?= $Page->fecha->editAttributes() ?> aria-describedby="x_fecha_help">
<?= $Page->fecha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage() ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fanticipos_aplicacionesadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fanticipos_aplicacionesadd", "x_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <div id="r__username"<?= $Page->_username->rowAttributes() ?>>
        <label id="elh_anticipos_aplicaciones__username" for="x__username" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_username->caption() ?><?= $Page->_username->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_username->cellAttributes() ?>>
<span id="el_anticipos_aplicaciones__username">
<input type="<?= $Page->_username->getInputTextType() ?>" name="x__username" id="x__username" data-table="anticipos_aplicaciones" data-field="x__username" value="<?= $Page->_username->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->_username->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_username->formatPattern()) ?>"<?= $Page->_username->editAttributes() ?> aria-describedby="x__username_help">
<?= $Page->_username->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_username->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto_moneda->Visible) { // monto_moneda ?>
    <div id="r_monto_moneda"<?= $Page->monto_moneda->rowAttributes() ?>>
        <label id="elh_anticipos_aplicaciones_monto_moneda" for="x_monto_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_moneda->caption() ?><?= $Page->monto_moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->monto_moneda->cellAttributes() ?>>
<span id="el_anticipos_aplicaciones_monto_moneda">
<input type="<?= $Page->monto_moneda->getInputTextType() ?>" name="x_monto_moneda" id="x_monto_moneda" data-table="anticipos_aplicaciones" data-field="x_monto_moneda" value="<?= $Page->monto_moneda->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->monto_moneda->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->monto_moneda->formatPattern()) ?>"<?= $Page->monto_moneda->editAttributes() ?> aria-describedby="x_monto_moneda_help">
<?= $Page->monto_moneda->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto_moneda->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda"<?= $Page->moneda->rowAttributes() ?>>
        <label id="elh_anticipos_aplicaciones_moneda" for="x_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->moneda->cellAttributes() ?>>
<span id="el_anticipos_aplicaciones_moneda">
<input type="<?= $Page->moneda->getInputTextType() ?>" name="x_moneda" id="x_moneda" data-table="anticipos_aplicaciones" data-field="x_moneda" value="<?= $Page->moneda->EditValue ?>" size="30" maxlength="6" placeholder="<?= HtmlEncode($Page->moneda->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->moneda->formatPattern()) ?>"<?= $Page->moneda->editAttributes() ?> aria-describedby="x_moneda_help">
<?= $Page->moneda->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->moneda->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tasa_factura->Visible) { // tasa_factura ?>
    <div id="r_tasa_factura"<?= $Page->tasa_factura->rowAttributes() ?>>
        <label id="elh_anticipos_aplicaciones_tasa_factura" for="x_tasa_factura" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tasa_factura->caption() ?><?= $Page->tasa_factura->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tasa_factura->cellAttributes() ?>>
<span id="el_anticipos_aplicaciones_tasa_factura">
<input type="<?= $Page->tasa_factura->getInputTextType() ?>" name="x_tasa_factura" id="x_tasa_factura" data-table="anticipos_aplicaciones" data-field="x_tasa_factura" value="<?= $Page->tasa_factura->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->tasa_factura->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->tasa_factura->formatPattern()) ?>"<?= $Page->tasa_factura->editAttributes() ?> aria-describedby="x_tasa_factura_help">
<?= $Page->tasa_factura->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tasa_factura->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fanticipos_aplicacionesadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fanticipos_aplicacionesadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("anticipos_aplicaciones");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
