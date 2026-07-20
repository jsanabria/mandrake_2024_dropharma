<?php

namespace PHPMaker2024\mandrake;

// Page object
$DocumentoConsecutivoAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { documento_consecutivo: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fdocumento_consecutivoadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdocumento_consecutivoadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["tipo_documento", [fields.tipo_documento.visible && fields.tipo_documento.required ? ew.Validators.required(fields.tipo_documento.caption) : null], fields.tipo_documento.isInvalid],
            ["serie", [fields.serie.visible && fields.serie.required ? ew.Validators.required(fields.serie.caption) : null], fields.serie.isInvalid],
            ["ultimo_numero", [fields.ultimo_numero.visible && fields.ultimo_numero.required ? ew.Validators.required(fields.ultimo_numero.caption) : null, ew.Validators.integer], fields.ultimo_numero.isInvalid],
            ["updated_at", [fields.updated_at.visible && fields.updated_at.required ? ew.Validators.required(fields.updated_at.caption) : null, ew.Validators.datetime(fields.updated_at.clientFormatPattern)], fields.updated_at.isInvalid]
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
<form name="fdocumento_consecutivoadd" id="fdocumento_consecutivoadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="documento_consecutivo">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <div id="r_tipo_documento"<?= $Page->tipo_documento->rowAttributes() ?>>
        <label id="elh_documento_consecutivo_tipo_documento" for="x_tipo_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_documento->caption() ?><?= $Page->tipo_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_documento_consecutivo_tipo_documento">
<input type="<?= $Page->tipo_documento->getInputTextType() ?>" name="x_tipo_documento" id="x_tipo_documento" data-table="documento_consecutivo" data-field="x_tipo_documento" value="<?= $Page->tipo_documento->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->tipo_documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->tipo_documento->formatPattern()) ?>"<?= $Page->tipo_documento->editAttributes() ?> aria-describedby="x_tipo_documento_help">
<?= $Page->tipo_documento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tipo_documento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->serie->Visible) { // serie ?>
    <div id="r_serie"<?= $Page->serie->rowAttributes() ?>>
        <label id="elh_documento_consecutivo_serie" for="x_serie" class="<?= $Page->LeftColumnClass ?>"><?= $Page->serie->caption() ?><?= $Page->serie->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->serie->cellAttributes() ?>>
<span id="el_documento_consecutivo_serie">
<input type="<?= $Page->serie->getInputTextType() ?>" name="x_serie" id="x_serie" data-table="documento_consecutivo" data-field="x_serie" value="<?= $Page->serie->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->serie->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->serie->formatPattern()) ?>"<?= $Page->serie->editAttributes() ?> aria-describedby="x_serie_help">
<?= $Page->serie->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->serie->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ultimo_numero->Visible) { // ultimo_numero ?>
    <div id="r_ultimo_numero"<?= $Page->ultimo_numero->rowAttributes() ?>>
        <label id="elh_documento_consecutivo_ultimo_numero" for="x_ultimo_numero" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ultimo_numero->caption() ?><?= $Page->ultimo_numero->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ultimo_numero->cellAttributes() ?>>
<span id="el_documento_consecutivo_ultimo_numero">
<input type="<?= $Page->ultimo_numero->getInputTextType() ?>" name="x_ultimo_numero" id="x_ultimo_numero" data-table="documento_consecutivo" data-field="x_ultimo_numero" value="<?= $Page->ultimo_numero->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->ultimo_numero->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ultimo_numero->formatPattern()) ?>"<?= $Page->ultimo_numero->editAttributes() ?> aria-describedby="x_ultimo_numero_help">
<?= $Page->ultimo_numero->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ultimo_numero->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
    <div id="r_updated_at"<?= $Page->updated_at->rowAttributes() ?>>
        <label id="elh_documento_consecutivo_updated_at" for="x_updated_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->updated_at->caption() ?><?= $Page->updated_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->updated_at->cellAttributes() ?>>
<span id="el_documento_consecutivo_updated_at">
<input type="<?= $Page->updated_at->getInputTextType() ?>" name="x_updated_at" id="x_updated_at" data-table="documento_consecutivo" data-field="x_updated_at" value="<?= $Page->updated_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->updated_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->updated_at->formatPattern()) ?>"<?= $Page->updated_at->editAttributes() ?> aria-describedby="x_updated_at_help">
<?= $Page->updated_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->updated_at->getErrorMessage() ?></div>
<?php if (!$Page->updated_at->ReadOnly && !$Page->updated_at->Disabled && !isset($Page->updated_at->EditAttrs["readonly"]) && !isset($Page->updated_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fdocumento_consecutivoadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fdocumento_consecutivoadd", "x_updated_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
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
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fdocumento_consecutivoadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fdocumento_consecutivoadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("documento_consecutivo");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
