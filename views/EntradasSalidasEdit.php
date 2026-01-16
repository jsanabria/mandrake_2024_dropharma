<?php

namespace PHPMaker2024\mandrake;

// Page object
$EntradasSalidasEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fentradas_salidasedit" id="fentradas_salidasedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { entradas_salidas: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fentradas_salidasedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fentradas_salidasedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null, ew.Validators.integer], fields.articulo.isInvalid],
            ["lote", [fields.lote.visible && fields.lote.required ? ew.Validators.required(fields.lote.caption) : null], fields.lote.isInvalid],
            ["fecha_vencimiento", [fields.fecha_vencimiento.visible && fields.fecha_vencimiento.required ? ew.Validators.required(fields.fecha_vencimiento.caption) : null, ew.Validators.datetime(fields.fecha_vencimiento.clientFormatPattern)], fields.fecha_vencimiento.isInvalid],
            ["cantidad_articulo", [fields.cantidad_articulo.visible && fields.cantidad_articulo.required ? ew.Validators.required(fields.cantidad_articulo.caption) : null, ew.Validators.float], fields.cantidad_articulo.isInvalid],
            ["descuento", [fields.descuento.visible && fields.descuento.required ? ew.Validators.required(fields.descuento.caption) : null, ew.Validators.float], fields.descuento.isInvalid],
            ["costo_unidad", [fields.costo_unidad.visible && fields.costo_unidad.required ? ew.Validators.required(fields.costo_unidad.caption) : null, ew.Validators.float], fields.costo_unidad.isInvalid],
            ["costo", [fields.costo.visible && fields.costo.required ? ew.Validators.required(fields.costo.caption) : null, ew.Validators.float], fields.costo.isInvalid],
            ["precio_unidad", [fields.precio_unidad.visible && fields.precio_unidad.required ? ew.Validators.required(fields.precio_unidad.caption) : null, ew.Validators.float], fields.precio_unidad.isInvalid],
            ["precio", [fields.precio.visible && fields.precio.required ? ew.Validators.required(fields.precio.caption) : null, ew.Validators.float], fields.precio.isInvalid],
            ["check_ne", [fields.check_ne.visible && fields.check_ne.required ? ew.Validators.required(fields.check_ne.caption) : null], fields.check_ne.isInvalid],
            ["newdata", [fields.newdata.visible && fields.newdata.required ? ew.Validators.required(fields.newdata.caption) : null], fields.newdata.isInvalid]
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
            "newdata": <?= $Page->newdata->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="entradas_salidas">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "entradas") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="entradas">
<input type="hidden" name="fk_tipo_documento" value="<?= HtmlEncode($Page->tipo_documento->getSessionValue()) ?>">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->id_documento->getSessionValue()) ?>">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "salidas") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="salidas">
<input type="hidden" name="fk_tipo_documento" value="<?= HtmlEncode($Page->tipo_documento->getSessionValue()) ?>">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->id_documento->getSessionValue()) ?>">
<?php } ?>
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->articulo->Visible) { // articulo ?>
    <div id="r_articulo"<?= $Page->articulo->rowAttributes() ?>>
        <label id="elh_entradas_salidas_articulo" for="x_articulo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->articulo->caption() ?><?= $Page->articulo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->articulo->cellAttributes() ?>>
<span id="el_entradas_salidas_articulo">
<input type="<?= $Page->articulo->getInputTextType() ?>" name="x_articulo" id="x_articulo" data-table="entradas_salidas" data-field="x_articulo" value="<?= $Page->articulo->EditValue ?>" size="20" placeholder="<?= HtmlEncode($Page->articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->articulo->formatPattern()) ?>"<?= $Page->articulo->editAttributes() ?> aria-describedby="x_articulo_help">
<?= $Page->articulo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->articulo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->lote->Visible) { // lote ?>
    <div id="r_lote"<?= $Page->lote->rowAttributes() ?>>
        <label id="elh_entradas_salidas_lote" for="x_lote" class="<?= $Page->LeftColumnClass ?>"><?= $Page->lote->caption() ?><?= $Page->lote->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->lote->cellAttributes() ?>>
<span id="el_entradas_salidas_lote">
<input type="<?= $Page->lote->getInputTextType() ?>" name="x_lote" id="x_lote" data-table="entradas_salidas" data-field="x_lote" value="<?= $Page->lote->EditValue ?>" size="10" maxlength="20" placeholder="<?= HtmlEncode($Page->lote->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->lote->formatPattern()) ?>"<?= $Page->lote->editAttributes() ?> aria-describedby="x_lote_help">
<?= $Page->lote->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->lote->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
    <div id="r_fecha_vencimiento"<?= $Page->fecha_vencimiento->rowAttributes() ?>>
        <label id="elh_entradas_salidas_fecha_vencimiento" for="x_fecha_vencimiento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha_vencimiento->caption() ?><?= $Page->fecha_vencimiento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha_vencimiento->cellAttributes() ?>>
<span id="el_entradas_salidas_fecha_vencimiento">
<input type="<?= $Page->fecha_vencimiento->getInputTextType() ?>" name="x_fecha_vencimiento" id="x_fecha_vencimiento" data-table="entradas_salidas" data-field="x_fecha_vencimiento" value="<?= $Page->fecha_vencimiento->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Page->fecha_vencimiento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha_vencimiento->formatPattern()) ?>"<?= $Page->fecha_vencimiento->editAttributes() ?> aria-describedby="x_fecha_vencimiento_help">
<?= $Page->fecha_vencimiento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha_vencimiento->getErrorMessage() ?></div>
<?php if (!$Page->fecha_vencimiento->ReadOnly && !$Page->fecha_vencimiento->Disabled && !isset($Page->fecha_vencimiento->EditAttrs["readonly"]) && !isset($Page->fecha_vencimiento->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fentradas_salidasedit", "datetimepicker"], function () {
    let format = "<?= DateFormat(7) ?>",
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
    ew.createDateTimePicker("fentradas_salidasedit", "x_fecha_vencimiento", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cantidad_articulo->Visible) { // cantidad_articulo ?>
    <div id="r_cantidad_articulo"<?= $Page->cantidad_articulo->rowAttributes() ?>>
        <label id="elh_entradas_salidas_cantidad_articulo" for="x_cantidad_articulo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidad_articulo->caption() ?><?= $Page->cantidad_articulo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cantidad_articulo->cellAttributes() ?>>
<span id="el_entradas_salidas_cantidad_articulo">
<input type="<?= $Page->cantidad_articulo->getInputTextType() ?>" name="x_cantidad_articulo" id="x_cantidad_articulo" data-table="entradas_salidas" data-field="x_cantidad_articulo" value="<?= $Page->cantidad_articulo->EditValue ?>" size="6" placeholder="<?= HtmlEncode($Page->cantidad_articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cantidad_articulo->formatPattern()) ?>"<?= $Page->cantidad_articulo->editAttributes() ?> aria-describedby="x_cantidad_articulo_help">
<?= $Page->cantidad_articulo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cantidad_articulo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
    <div id="r_descuento"<?= $Page->descuento->rowAttributes() ?>>
        <label id="elh_entradas_salidas_descuento" for="x_descuento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descuento->caption() ?><?= $Page->descuento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->descuento->cellAttributes() ?>>
<span id="el_entradas_salidas_descuento">
<input type="<?= $Page->descuento->getInputTextType() ?>" name="x_descuento" id="x_descuento" data-table="entradas_salidas" data-field="x_descuento" value="<?= $Page->descuento->EditValue ?>" size="6" placeholder="<?= HtmlEncode($Page->descuento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->descuento->formatPattern()) ?>"<?= $Page->descuento->editAttributes() ?> aria-describedby="x_descuento_help">
<?= $Page->descuento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->descuento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->costo_unidad->Visible) { // costo_unidad ?>
    <div id="r_costo_unidad"<?= $Page->costo_unidad->rowAttributes() ?>>
        <label id="elh_entradas_salidas_costo_unidad" for="x_costo_unidad" class="<?= $Page->LeftColumnClass ?>"><?= $Page->costo_unidad->caption() ?><?= $Page->costo_unidad->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->costo_unidad->cellAttributes() ?>>
<span id="el_entradas_salidas_costo_unidad">
<input type="<?= $Page->costo_unidad->getInputTextType() ?>" name="x_costo_unidad" id="x_costo_unidad" data-table="entradas_salidas" data-field="x_costo_unidad" value="<?= $Page->costo_unidad->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Page->costo_unidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->costo_unidad->formatPattern()) ?>"<?= $Page->costo_unidad->editAttributes() ?> aria-describedby="x_costo_unidad_help">
<?= $Page->costo_unidad->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->costo_unidad->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->costo->Visible) { // costo ?>
    <div id="r_costo"<?= $Page->costo->rowAttributes() ?>>
        <label id="elh_entradas_salidas_costo" for="x_costo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->costo->caption() ?><?= $Page->costo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->costo->cellAttributes() ?>>
<span id="el_entradas_salidas_costo">
<input type="<?= $Page->costo->getInputTextType() ?>" name="x_costo" id="x_costo" data-table="entradas_salidas" data-field="x_costo" value="<?= $Page->costo->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Page->costo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->costo->formatPattern()) ?>"<?= $Page->costo->editAttributes() ?> aria-describedby="x_costo_help">
<?= $Page->costo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->costo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->precio_unidad->Visible) { // precio_unidad ?>
    <div id="r_precio_unidad"<?= $Page->precio_unidad->rowAttributes() ?>>
        <label id="elh_entradas_salidas_precio_unidad" for="x_precio_unidad" class="<?= $Page->LeftColumnClass ?>"><?= $Page->precio_unidad->caption() ?><?= $Page->precio_unidad->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->precio_unidad->cellAttributes() ?>>
<span id="el_entradas_salidas_precio_unidad">
<input type="<?= $Page->precio_unidad->getInputTextType() ?>" name="x_precio_unidad" id="x_precio_unidad" data-table="entradas_salidas" data-field="x_precio_unidad" value="<?= $Page->precio_unidad->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Page->precio_unidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->precio_unidad->formatPattern()) ?>"<?= $Page->precio_unidad->editAttributes() ?> aria-describedby="x_precio_unidad_help">
<?= $Page->precio_unidad->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->precio_unidad->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
    <div id="r_precio"<?= $Page->precio->rowAttributes() ?>>
        <label id="elh_entradas_salidas_precio" for="x_precio" class="<?= $Page->LeftColumnClass ?>"><?= $Page->precio->caption() ?><?= $Page->precio->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->precio->cellAttributes() ?>>
<span id="el_entradas_salidas_precio">
<input type="<?= $Page->precio->getInputTextType() ?>" name="x_precio" id="x_precio" data-table="entradas_salidas" data-field="x_precio" value="<?= $Page->precio->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Page->precio->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->precio->formatPattern()) ?>"<?= $Page->precio->editAttributes() ?> aria-describedby="x_precio_help">
<?= $Page->precio->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->precio->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->check_ne->Visible) { // check_ne ?>
    <div id="r_check_ne"<?= $Page->check_ne->rowAttributes() ?>>
        <label id="elh_entradas_salidas_check_ne" class="<?= $Page->LeftColumnClass ?>"><?= $Page->check_ne->caption() ?><?= $Page->check_ne->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->check_ne->cellAttributes() ?>>
<span id="el_entradas_salidas_check_ne">
<span<?= $Page->check_ne->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->check_ne->getDisplayValue($Page->check_ne->EditValue) ?></span></span>
<input type="hidden" data-table="entradas_salidas" data-field="x_check_ne" data-hidden="1" name="x_check_ne" id="x_check_ne" value="<?= HtmlEncode($Page->check_ne->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->newdata->Visible) { // newdata ?>
    <div id="r_newdata"<?= $Page->newdata->rowAttributes() ?>>
        <label id="elh_entradas_salidas_newdata" class="<?= $Page->LeftColumnClass ?>"><?= $Page->newdata->caption() ?><?= $Page->newdata->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->newdata->cellAttributes() ?>>
<span id="el_entradas_salidas_newdata">
<template id="tp_x_newdata">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="entradas_salidas" data-field="x_newdata" name="x_newdata" id="x_newdata"<?= $Page->newdata->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_newdata" class="ew-item-list"></div>
<selection-list hidden
    id="x_newdata"
    name="x_newdata"
    value="<?= HtmlEncode($Page->newdata->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_newdata"
    data-target="dsl_x_newdata"
    data-repeatcolumn="5"
    class="form-control<?= $Page->newdata->isInvalidClass() ?>"
    data-table="entradas_salidas"
    data-field="x_newdata"
    data-value-separator="<?= $Page->newdata->displayValueSeparatorAttribute() ?>"
    <?= $Page->newdata->editAttributes() ?>></selection-list>
<?= $Page->newdata->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->newdata->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="entradas_salidas" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fentradas_salidasedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fentradas_salidasedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("entradas_salidas");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
