<?php

namespace PHPMaker2024\mandrake;

// Page object
$ContTerceroMdkAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cont_tercero_mdk: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fcont_tercero_mdkadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcont_tercero_mdkadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["tipo", [fields.tipo.visible && fields.tipo.required ? ew.Validators.required(fields.tipo.caption) : null], fields.tipo.isInvalid],
            ["origen_tabla", [fields.origen_tabla.visible && fields.origen_tabla.required ? ew.Validators.required(fields.origen_tabla.caption) : null], fields.origen_tabla.isInvalid],
            ["origen_id", [fields.origen_id.visible && fields.origen_id.required ? ew.Validators.required(fields.origen_id.caption) : null, ew.Validators.integer], fields.origen_id.isInvalid],
            ["nombre", [fields.nombre.visible && fields.nombre.required ? ew.Validators.required(fields.nombre.caption) : null], fields.nombre.isInvalid],
            ["rif", [fields.rif.visible && fields.rif.required ? ew.Validators.required(fields.rif.caption) : null], fields.rif.isInvalid],
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
            "tipo": <?= $Page->tipo->toClientList($Page) ?>,
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
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fcont_tercero_mdkadd" id="fcont_tercero_mdkadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_tercero_mdk">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->tipo->Visible) { // tipo ?>
    <div id="r_tipo"<?= $Page->tipo->rowAttributes() ?>>
        <label id="elh_cont_tercero_mdk_tipo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo->caption() ?><?= $Page->tipo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tipo->cellAttributes() ?>>
<span id="el_cont_tercero_mdk_tipo">
<template id="tp_x_tipo">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="cont_tercero_mdk" data-field="x_tipo" name="x_tipo" id="x_tipo"<?= $Page->tipo->editAttributes() ?>>
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
    data-table="cont_tercero_mdk"
    data-field="x_tipo"
    data-value-separator="<?= $Page->tipo->displayValueSeparatorAttribute() ?>"
    <?= $Page->tipo->editAttributes() ?>></selection-list>
<?= $Page->tipo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tipo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->origen_tabla->Visible) { // origen_tabla ?>
    <div id="r_origen_tabla"<?= $Page->origen_tabla->rowAttributes() ?>>
        <label id="elh_cont_tercero_mdk_origen_tabla" for="x_origen_tabla" class="<?= $Page->LeftColumnClass ?>"><?= $Page->origen_tabla->caption() ?><?= $Page->origen_tabla->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->origen_tabla->cellAttributes() ?>>
<span id="el_cont_tercero_mdk_origen_tabla">
<input type="<?= $Page->origen_tabla->getInputTextType() ?>" name="x_origen_tabla" id="x_origen_tabla" data-table="cont_tercero_mdk" data-field="x_origen_tabla" value="<?= $Page->origen_tabla->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->origen_tabla->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->origen_tabla->formatPattern()) ?>"<?= $Page->origen_tabla->editAttributes() ?> aria-describedby="x_origen_tabla_help">
<?= $Page->origen_tabla->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->origen_tabla->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->origen_id->Visible) { // origen_id ?>
    <div id="r_origen_id"<?= $Page->origen_id->rowAttributes() ?>>
        <label id="elh_cont_tercero_mdk_origen_id" for="x_origen_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->origen_id->caption() ?><?= $Page->origen_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->origen_id->cellAttributes() ?>>
<span id="el_cont_tercero_mdk_origen_id">
<input type="<?= $Page->origen_id->getInputTextType() ?>" name="x_origen_id" id="x_origen_id" data-table="cont_tercero_mdk" data-field="x_origen_id" value="<?= $Page->origen_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->origen_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->origen_id->formatPattern()) ?>"<?= $Page->origen_id->editAttributes() ?> aria-describedby="x_origen_id_help">
<?= $Page->origen_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->origen_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
    <div id="r_nombre"<?= $Page->nombre->rowAttributes() ?>>
        <label id="elh_cont_tercero_mdk_nombre" for="x_nombre" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nombre->caption() ?><?= $Page->nombre->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nombre->cellAttributes() ?>>
<span id="el_cont_tercero_mdk_nombre">
<input type="<?= $Page->nombre->getInputTextType() ?>" name="x_nombre" id="x_nombre" data-table="cont_tercero_mdk" data-field="x_nombre" value="<?= $Page->nombre->EditValue ?>" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->nombre->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nombre->formatPattern()) ?>"<?= $Page->nombre->editAttributes() ?> aria-describedby="x_nombre_help">
<?= $Page->nombre->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nombre->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->rif->Visible) { // rif ?>
    <div id="r_rif"<?= $Page->rif->rowAttributes() ?>>
        <label id="elh_cont_tercero_mdk_rif" for="x_rif" class="<?= $Page->LeftColumnClass ?>"><?= $Page->rif->caption() ?><?= $Page->rif->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->rif->cellAttributes() ?>>
<span id="el_cont_tercero_mdk_rif">
<input type="<?= $Page->rif->getInputTextType() ?>" name="x_rif" id="x_rif" data-table="cont_tercero_mdk" data-field="x_rif" value="<?= $Page->rif->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->rif->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->rif->formatPattern()) ?>"<?= $Page->rif->editAttributes() ?> aria-describedby="x_rif_help">
<?= $Page->rif->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->rif->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->estado->Visible) { // estado ?>
    <div id="r_estado"<?= $Page->estado->rowAttributes() ?>>
        <label id="elh_cont_tercero_mdk_estado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->estado->caption() ?><?= $Page->estado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->estado->cellAttributes() ?>>
<span id="el_cont_tercero_mdk_estado">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->estado->isInvalidClass() ?>" data-table="cont_tercero_mdk" data-field="x_estado" data-boolean name="x_estado" id="x_estado" value="1"<?= ConvertToBool($Page->estado->CurrentValue) ? " checked" : "" ?><?= $Page->estado->editAttributes() ?> aria-describedby="x_estado_help">
    <div class="invalid-feedback"><?= $Page->estado->getErrorMessage() ?></div>
</div>
<?= $Page->estado->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <div id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <label id="elh_cont_tercero_mdk_created_at" for="x_created_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_at->caption() ?><?= $Page->created_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_at->cellAttributes() ?>>
<span id="el_cont_tercero_mdk_created_at">
<input type="<?= $Page->created_at->getInputTextType() ?>" name="x_created_at" id="x_created_at" data-table="cont_tercero_mdk" data-field="x_created_at" value="<?= $Page->created_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->created_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_at->formatPattern()) ?>"<?= $Page->created_at->editAttributes() ?> aria-describedby="x_created_at_help">
<?= $Page->created_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->created_at->getErrorMessage() ?></div>
<?php if (!$Page->created_at->ReadOnly && !$Page->created_at->Disabled && !isset($Page->created_at->EditAttrs["readonly"]) && !isset($Page->created_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcont_tercero_mdkadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fcont_tercero_mdkadd", "x_created_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
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
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcont_tercero_mdkadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcont_tercero_mdkadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("cont_tercero_mdk");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
