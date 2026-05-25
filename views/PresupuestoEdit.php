<?php

namespace PHPMaker2024\mandrake;

// Page object
$PresupuestoEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fpresupuestoedit" id="fpresupuestoedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { presupuesto: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fpresupuestoedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpresupuestoedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null], fields.id.isInvalid],
            ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(fields.fecha.clientFormatPattern)], fields.fecha.isInvalid],
            ["cliente_potencial", [fields.cliente_potencial.visible && fields.cliente_potencial.required ? ew.Validators.required(fields.cliente_potencial.caption) : null], fields.cliente_potencial.isInvalid],
            ["rif", [fields.rif.visible && fields.rif.required ? ew.Validators.required(fields.rif.caption) : null], fields.rif.isInvalid],
            ["cliente", [fields.cliente.visible && fields.cliente.required ? ew.Validators.required(fields.cliente.caption) : null, ew.Validators.integer], fields.cliente.isInvalid],
            ["proyecto", [fields.proyecto.visible && fields.proyecto.required ? ew.Validators.required(fields.proyecto.caption) : null], fields.proyecto.isInvalid],
            ["estatus", [fields.estatus.visible && fields.estatus.required ? ew.Validators.required(fields.estatus.caption) : null], fields.estatus.isInvalid]
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
            "estatus": <?= $Page->estatus->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="presupuesto">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->id->Visible) { // id ?>
    <div id="r_id"<?= $Page->id->rowAttributes() ?>>
        <label id="elh_presupuesto_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id->caption() ?><?= $Page->id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->id->cellAttributes() ?>>
<span id="el_presupuesto_id">
<span<?= $Page->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id->getDisplayValue($Page->id->EditValue))) ?>"></span>
<input type="hidden" data-table="presupuesto" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <label id="elh_presupuesto_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha->cellAttributes() ?>>
<span id="el_presupuesto_fecha">
<input type="<?= $Page->fecha->getInputTextType() ?>" name="x_fecha" id="x_fecha" data-table="presupuesto" data-field="x_fecha" value="<?= $Page->fecha->EditValue ?>" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha->formatPattern()) ?>"<?= $Page->fecha->editAttributes() ?> aria-describedby="x_fecha_help">
<?= $Page->fecha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage() ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpresupuestoedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fpresupuestoedit", "x_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cliente_potencial->Visible) { // cliente_potencial ?>
    <div id="r_cliente_potencial"<?= $Page->cliente_potencial->rowAttributes() ?>>
        <label id="elh_presupuesto_cliente_potencial" for="x_cliente_potencial" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cliente_potencial->caption() ?><?= $Page->cliente_potencial->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cliente_potencial->cellAttributes() ?>>
<span id="el_presupuesto_cliente_potencial">
<input type="<?= $Page->cliente_potencial->getInputTextType() ?>" name="x_cliente_potencial" id="x_cliente_potencial" data-table="presupuesto" data-field="x_cliente_potencial" value="<?= $Page->cliente_potencial->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->cliente_potencial->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cliente_potencial->formatPattern()) ?>"<?= $Page->cliente_potencial->editAttributes() ?> aria-describedby="x_cliente_potencial_help">
<?= $Page->cliente_potencial->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cliente_potencial->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->rif->Visible) { // rif ?>
    <div id="r_rif"<?= $Page->rif->rowAttributes() ?>>
        <label id="elh_presupuesto_rif" for="x_rif" class="<?= $Page->LeftColumnClass ?>"><?= $Page->rif->caption() ?><?= $Page->rif->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->rif->cellAttributes() ?>>
<span id="el_presupuesto_rif">
<input type="<?= $Page->rif->getInputTextType() ?>" name="x_rif" id="x_rif" data-table="presupuesto" data-field="x_rif" value="<?= $Page->rif->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->rif->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->rif->formatPattern()) ?>"<?= $Page->rif->editAttributes() ?> aria-describedby="x_rif_help">
<?= $Page->rif->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->rif->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
    <div id="r_cliente"<?= $Page->cliente->rowAttributes() ?>>
        <label id="elh_presupuesto_cliente" for="x_cliente" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cliente->caption() ?><?= $Page->cliente->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cliente->cellAttributes() ?>>
<span id="el_presupuesto_cliente">
<input type="<?= $Page->cliente->getInputTextType() ?>" name="x_cliente" id="x_cliente" data-table="presupuesto" data-field="x_cliente" value="<?= $Page->cliente->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->cliente->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cliente->formatPattern()) ?>"<?= $Page->cliente->editAttributes() ?> aria-describedby="x_cliente_help">
<?= $Page->cliente->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cliente->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->proyecto->Visible) { // proyecto ?>
    <div id="r_proyecto"<?= $Page->proyecto->rowAttributes() ?>>
        <label id="elh_presupuesto_proyecto" for="x_proyecto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->proyecto->caption() ?><?= $Page->proyecto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->proyecto->cellAttributes() ?>>
<span id="el_presupuesto_proyecto">
<input type="<?= $Page->proyecto->getInputTextType() ?>" name="x_proyecto" id="x_proyecto" data-table="presupuesto" data-field="x_proyecto" value="<?= $Page->proyecto->EditValue ?>" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->proyecto->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->proyecto->formatPattern()) ?>"<?= $Page->proyecto->editAttributes() ?> aria-describedby="x_proyecto_help">
<?= $Page->proyecto->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->proyecto->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
    <div id="r_estatus"<?= $Page->estatus->rowAttributes() ?>>
        <label id="elh_presupuesto_estatus" class="<?= $Page->LeftColumnClass ?>"><?= $Page->estatus->caption() ?><?= $Page->estatus->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->estatus->cellAttributes() ?>>
<span id="el_presupuesto_estatus">
<template id="tp_x_estatus">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="presupuesto" data-field="x_estatus" name="x_estatus" id="x_estatus"<?= $Page->estatus->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_estatus" class="ew-item-list"></div>
<selection-list hidden
    id="x_estatus"
    name="x_estatus"
    value="<?= HtmlEncode($Page->estatus->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_estatus"
    data-target="dsl_x_estatus"
    data-repeatcolumn="5"
    class="form-control<?= $Page->estatus->isInvalidClass() ?>"
    data-table="presupuesto"
    data-field="x_estatus"
    data-value-separator="<?= $Page->estatus->displayValueSeparatorAttribute() ?>"
    <?= $Page->estatus->editAttributes() ?>></selection-list>
<?= $Page->estatus->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->estatus->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fpresupuestoedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fpresupuestoedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("presupuesto");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
