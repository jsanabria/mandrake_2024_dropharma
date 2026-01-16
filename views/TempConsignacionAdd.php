<?php

namespace PHPMaker2024\mandrake;

// Page object
$TempConsignacionAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { temp_consignacion: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var ftemp_consignacionadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftemp_consignacionadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["_username", [fields._username.visible && fields._username.required ? ew.Validators.required(fields._username.caption) : null], fields._username.isInvalid],
            ["nro_documento", [fields.nro_documento.visible && fields.nro_documento.required ? ew.Validators.required(fields.nro_documento.caption) : null], fields.nro_documento.isInvalid],
            ["id_documento", [fields.id_documento.visible && fields.id_documento.required ? ew.Validators.required(fields.id_documento.caption) : null, ew.Validators.integer], fields.id_documento.isInvalid],
            ["tipo_documento", [fields.tipo_documento.visible && fields.tipo_documento.required ? ew.Validators.required(fields.tipo_documento.caption) : null], fields.tipo_documento.isInvalid],
            ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null, ew.Validators.integer], fields.fabricante.isInvalid],
            ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null, ew.Validators.integer], fields.articulo.isInvalid],
            ["cantidad_movimiento", [fields.cantidad_movimiento.visible && fields.cantidad_movimiento.required ? ew.Validators.required(fields.cantidad_movimiento.caption) : null, ew.Validators.float], fields.cantidad_movimiento.isInvalid],
            ["cantidad_entre_fechas", [fields.cantidad_entre_fechas.visible && fields.cantidad_entre_fechas.required ? ew.Validators.required(fields.cantidad_entre_fechas.caption) : null, ew.Validators.float], fields.cantidad_entre_fechas.isInvalid],
            ["cantidad_acumulada", [fields.cantidad_acumulada.visible && fields.cantidad_acumulada.required ? ew.Validators.required(fields.cantidad_acumulada.caption) : null, ew.Validators.float], fields.cantidad_acumulada.isInvalid],
            ["cantidad_ajuste", [fields.cantidad_ajuste.visible && fields.cantidad_ajuste.required ? ew.Validators.required(fields.cantidad_ajuste.caption) : null, ew.Validators.float], fields.cantidad_ajuste.isInvalid]
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
<form name="ftemp_consignacionadd" id="ftemp_consignacionadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="temp_consignacion">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->_username->Visible) { // username ?>
    <div id="r__username"<?= $Page->_username->rowAttributes() ?>>
        <label id="elh_temp_consignacion__username" for="x__username" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_username->caption() ?><?= $Page->_username->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_username->cellAttributes() ?>>
<span id="el_temp_consignacion__username">
<input type="<?= $Page->_username->getInputTextType() ?>" name="x__username" id="x__username" data-table="temp_consignacion" data-field="x__username" value="<?= $Page->_username->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->_username->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_username->formatPattern()) ?>"<?= $Page->_username->editAttributes() ?> aria-describedby="x__username_help">
<?= $Page->_username->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_username->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <div id="r_nro_documento"<?= $Page->nro_documento->rowAttributes() ?>>
        <label id="elh_temp_consignacion_nro_documento" for="x_nro_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_documento->caption() ?><?= $Page->nro_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_temp_consignacion_nro_documento">
<input type="<?= $Page->nro_documento->getInputTextType() ?>" name="x_nro_documento" id="x_nro_documento" data-table="temp_consignacion" data-field="x_nro_documento" value="<?= $Page->nro_documento->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->nro_documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nro_documento->formatPattern()) ?>"<?= $Page->nro_documento->editAttributes() ?> aria-describedby="x_nro_documento_help">
<?= $Page->nro_documento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nro_documento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->id_documento->Visible) { // id_documento ?>
    <div id="r_id_documento"<?= $Page->id_documento->rowAttributes() ?>>
        <label id="elh_temp_consignacion_id_documento" for="x_id_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_documento->caption() ?><?= $Page->id_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->id_documento->cellAttributes() ?>>
<span id="el_temp_consignacion_id_documento">
<input type="<?= $Page->id_documento->getInputTextType() ?>" name="x_id_documento" id="x_id_documento" data-table="temp_consignacion" data-field="x_id_documento" value="<?= $Page->id_documento->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->id_documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->id_documento->formatPattern()) ?>"<?= $Page->id_documento->editAttributes() ?> aria-describedby="x_id_documento_help">
<?= $Page->id_documento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->id_documento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <div id="r_tipo_documento"<?= $Page->tipo_documento->rowAttributes() ?>>
        <label id="elh_temp_consignacion_tipo_documento" for="x_tipo_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_documento->caption() ?><?= $Page->tipo_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_temp_consignacion_tipo_documento">
<input type="<?= $Page->tipo_documento->getInputTextType() ?>" name="x_tipo_documento" id="x_tipo_documento" data-table="temp_consignacion" data-field="x_tipo_documento" value="<?= $Page->tipo_documento->EditValue ?>" size="30" maxlength="6" placeholder="<?= HtmlEncode($Page->tipo_documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->tipo_documento->formatPattern()) ?>"<?= $Page->tipo_documento->editAttributes() ?> aria-describedby="x_tipo_documento_help">
<?= $Page->tipo_documento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tipo_documento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <div id="r_fabricante"<?= $Page->fabricante->rowAttributes() ?>>
        <label id="elh_temp_consignacion_fabricante" for="x_fabricante" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fabricante->caption() ?><?= $Page->fabricante->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fabricante->cellAttributes() ?>>
<span id="el_temp_consignacion_fabricante">
<input type="<?= $Page->fabricante->getInputTextType() ?>" name="x_fabricante" id="x_fabricante" data-table="temp_consignacion" data-field="x_fabricante" value="<?= $Page->fabricante->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->fabricante->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fabricante->formatPattern()) ?>"<?= $Page->fabricante->editAttributes() ?> aria-describedby="x_fabricante_help">
<?= $Page->fabricante->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fabricante->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
    <div id="r_articulo"<?= $Page->articulo->rowAttributes() ?>>
        <label id="elh_temp_consignacion_articulo" for="x_articulo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->articulo->caption() ?><?= $Page->articulo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->articulo->cellAttributes() ?>>
<span id="el_temp_consignacion_articulo">
<input type="<?= $Page->articulo->getInputTextType() ?>" name="x_articulo" id="x_articulo" data-table="temp_consignacion" data-field="x_articulo" value="<?= $Page->articulo->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->articulo->formatPattern()) ?>"<?= $Page->articulo->editAttributes() ?> aria-describedby="x_articulo_help">
<?= $Page->articulo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->articulo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cantidad_movimiento->Visible) { // cantidad_movimiento ?>
    <div id="r_cantidad_movimiento"<?= $Page->cantidad_movimiento->rowAttributes() ?>>
        <label id="elh_temp_consignacion_cantidad_movimiento" for="x_cantidad_movimiento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidad_movimiento->caption() ?><?= $Page->cantidad_movimiento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cantidad_movimiento->cellAttributes() ?>>
<span id="el_temp_consignacion_cantidad_movimiento">
<input type="<?= $Page->cantidad_movimiento->getInputTextType() ?>" name="x_cantidad_movimiento" id="x_cantidad_movimiento" data-table="temp_consignacion" data-field="x_cantidad_movimiento" value="<?= $Page->cantidad_movimiento->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->cantidad_movimiento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cantidad_movimiento->formatPattern()) ?>"<?= $Page->cantidad_movimiento->editAttributes() ?> aria-describedby="x_cantidad_movimiento_help">
<?= $Page->cantidad_movimiento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cantidad_movimiento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cantidad_entre_fechas->Visible) { // cantidad_entre_fechas ?>
    <div id="r_cantidad_entre_fechas"<?= $Page->cantidad_entre_fechas->rowAttributes() ?>>
        <label id="elh_temp_consignacion_cantidad_entre_fechas" for="x_cantidad_entre_fechas" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidad_entre_fechas->caption() ?><?= $Page->cantidad_entre_fechas->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cantidad_entre_fechas->cellAttributes() ?>>
<span id="el_temp_consignacion_cantidad_entre_fechas">
<input type="<?= $Page->cantidad_entre_fechas->getInputTextType() ?>" name="x_cantidad_entre_fechas" id="x_cantidad_entre_fechas" data-table="temp_consignacion" data-field="x_cantidad_entre_fechas" value="<?= $Page->cantidad_entre_fechas->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->cantidad_entre_fechas->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cantidad_entre_fechas->formatPattern()) ?>"<?= $Page->cantidad_entre_fechas->editAttributes() ?> aria-describedby="x_cantidad_entre_fechas_help">
<?= $Page->cantidad_entre_fechas->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cantidad_entre_fechas->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cantidad_acumulada->Visible) { // cantidad_acumulada ?>
    <div id="r_cantidad_acumulada"<?= $Page->cantidad_acumulada->rowAttributes() ?>>
        <label id="elh_temp_consignacion_cantidad_acumulada" for="x_cantidad_acumulada" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidad_acumulada->caption() ?><?= $Page->cantidad_acumulada->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cantidad_acumulada->cellAttributes() ?>>
<span id="el_temp_consignacion_cantidad_acumulada">
<input type="<?= $Page->cantidad_acumulada->getInputTextType() ?>" name="x_cantidad_acumulada" id="x_cantidad_acumulada" data-table="temp_consignacion" data-field="x_cantidad_acumulada" value="<?= $Page->cantidad_acumulada->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->cantidad_acumulada->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cantidad_acumulada->formatPattern()) ?>"<?= $Page->cantidad_acumulada->editAttributes() ?> aria-describedby="x_cantidad_acumulada_help">
<?= $Page->cantidad_acumulada->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cantidad_acumulada->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cantidad_ajuste->Visible) { // cantidad_ajuste ?>
    <div id="r_cantidad_ajuste"<?= $Page->cantidad_ajuste->rowAttributes() ?>>
        <label id="elh_temp_consignacion_cantidad_ajuste" for="x_cantidad_ajuste" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidad_ajuste->caption() ?><?= $Page->cantidad_ajuste->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cantidad_ajuste->cellAttributes() ?>>
<span id="el_temp_consignacion_cantidad_ajuste">
<input type="<?= $Page->cantidad_ajuste->getInputTextType() ?>" name="x_cantidad_ajuste" id="x_cantidad_ajuste" data-table="temp_consignacion" data-field="x_cantidad_ajuste" value="<?= $Page->cantidad_ajuste->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->cantidad_ajuste->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cantidad_ajuste->formatPattern()) ?>"<?= $Page->cantidad_ajuste->editAttributes() ?> aria-describedby="x_cantidad_ajuste_help">
<?= $Page->cantidad_ajuste->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cantidad_ajuste->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="ftemp_consignacionadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="ftemp_consignacionadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("temp_consignacion");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
