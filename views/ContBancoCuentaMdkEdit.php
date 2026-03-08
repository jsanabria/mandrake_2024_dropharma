<?php

namespace PHPMaker2024\mandrake;

// Page object
$ContBancoCuentaMdkEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fcont_banco_cuenta_mdkedit" id="fcont_banco_cuenta_mdkedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cont_banco_cuenta_mdk: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fcont_banco_cuenta_mdkedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcont_banco_cuenta_mdkedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null], fields.id.isInvalid],
            ["banco_id", [fields.banco_id.visible && fields.banco_id.required ? ew.Validators.required(fields.banco_id.caption) : null, ew.Validators.integer], fields.banco_id.isInvalid],
            ["cuenta_id", [fields.cuenta_id.visible && fields.cuenta_id.required ? ew.Validators.required(fields.cuenta_id.caption) : null, ew.Validators.integer], fields.cuenta_id.isInvalid],
            ["estado", [fields.estado.visible && fields.estado.required ? ew.Validators.required(fields.estado.caption) : null], fields.estado.isInvalid],
            ["created_at", [fields.created_at.visible && fields.created_at.required ? ew.Validators.required(fields.created_at.caption) : null, ew.Validators.datetime(fields.created_at.clientFormatPattern)], fields.created_at.isInvalid]
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
            "estado": <?= $Page->estado->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="cont_banco_cuenta_mdk">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->id->Visible) { // id ?>
    <div id="r_id"<?= $Page->id->rowAttributes() ?>>
        <label id="elh_cont_banco_cuenta_mdk_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id->caption() ?><?= $Page->id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->id->cellAttributes() ?>>
<span id="el_cont_banco_cuenta_mdk_id">
<span<?= $Page->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id->getDisplayValue($Page->id->EditValue))) ?>"></span>
<input type="hidden" data-table="cont_banco_cuenta_mdk" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->banco_id->Visible) { // banco_id ?>
    <div id="r_banco_id"<?= $Page->banco_id->rowAttributes() ?>>
        <label id="elh_cont_banco_cuenta_mdk_banco_id" for="x_banco_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->banco_id->caption() ?><?= $Page->banco_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->banco_id->cellAttributes() ?>>
<span id="el_cont_banco_cuenta_mdk_banco_id">
<input type="<?= $Page->banco_id->getInputTextType() ?>" name="x_banco_id" id="x_banco_id" data-table="cont_banco_cuenta_mdk" data-field="x_banco_id" value="<?= $Page->banco_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->banco_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->banco_id->formatPattern()) ?>"<?= $Page->banco_id->editAttributes() ?> aria-describedby="x_banco_id_help">
<?= $Page->banco_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->banco_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cuenta_id->Visible) { // cuenta_id ?>
    <div id="r_cuenta_id"<?= $Page->cuenta_id->rowAttributes() ?>>
        <label id="elh_cont_banco_cuenta_mdk_cuenta_id" for="x_cuenta_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cuenta_id->caption() ?><?= $Page->cuenta_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cuenta_id->cellAttributes() ?>>
<span id="el_cont_banco_cuenta_mdk_cuenta_id">
<input type="<?= $Page->cuenta_id->getInputTextType() ?>" name="x_cuenta_id" id="x_cuenta_id" data-table="cont_banco_cuenta_mdk" data-field="x_cuenta_id" value="<?= $Page->cuenta_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->cuenta_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cuenta_id->formatPattern()) ?>"<?= $Page->cuenta_id->editAttributes() ?> aria-describedby="x_cuenta_id_help">
<?= $Page->cuenta_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cuenta_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->estado->Visible) { // estado ?>
    <div id="r_estado"<?= $Page->estado->rowAttributes() ?>>
        <label id="elh_cont_banco_cuenta_mdk_estado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->estado->caption() ?><?= $Page->estado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->estado->cellAttributes() ?>>
<span id="el_cont_banco_cuenta_mdk_estado">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->estado->isInvalidClass() ?>" data-table="cont_banco_cuenta_mdk" data-field="x_estado" data-boolean name="x_estado" id="x_estado" value="1"<?= ConvertToBool($Page->estado->CurrentValue) ? " checked" : "" ?><?= $Page->estado->editAttributes() ?> aria-describedby="x_estado_help">
    <div class="invalid-feedback"><?= $Page->estado->getErrorMessage() ?></div>
</div>
<?= $Page->estado->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <div id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <label id="elh_cont_banco_cuenta_mdk_created_at" for="x_created_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_at->caption() ?><?= $Page->created_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_at->cellAttributes() ?>>
<span id="el_cont_banco_cuenta_mdk_created_at">
<input type="<?= $Page->created_at->getInputTextType() ?>" name="x_created_at" id="x_created_at" data-table="cont_banco_cuenta_mdk" data-field="x_created_at" value="<?= $Page->created_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->created_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_at->formatPattern()) ?>"<?= $Page->created_at->editAttributes() ?> aria-describedby="x_created_at_help">
<?= $Page->created_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->created_at->getErrorMessage() ?></div>
<?php if (!$Page->created_at->ReadOnly && !$Page->created_at->Disabled && !isset($Page->created_at->EditAttrs["readonly"]) && !isset($Page->created_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcont_banco_cuenta_mdkedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fcont_banco_cuenta_mdkedit", "x_created_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
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
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcont_banco_cuenta_mdkedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcont_banco_cuenta_mdkedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("cont_banco_cuenta_mdk");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
