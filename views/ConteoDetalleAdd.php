<?php

namespace PHPMaker2024\mandrake;

// Page object
$ConteoDetalleAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { conteo_detalle: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fconteo_detalleadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fconteo_detalleadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["conteo", [fields.conteo.visible && fields.conteo.required ? ew.Validators.required(fields.conteo.caption) : null, ew.Validators.integer], fields.conteo.isInvalid],
            ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null, ew.Validators.integer], fields.articulo.isInvalid],
            ["cantidad", [fields.cantidad.visible && fields.cantidad.required ? ew.Validators.required(fields.cantidad.caption) : null, ew.Validators.integer], fields.cantidad.isInvalid],
            ["_switch", [fields._switch.visible && fields._switch.required ? ew.Validators.required(fields._switch.caption) : null, ew.Validators.integer], fields._switch.isInvalid]
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
<form name="fconteo_detalleadd" id="fconteo_detalleadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="conteo_detalle">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->conteo->Visible) { // conteo ?>
    <div id="r_conteo"<?= $Page->conteo->rowAttributes() ?>>
        <label id="elh_conteo_detalle_conteo" for="x_conteo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->conteo->caption() ?><?= $Page->conteo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->conteo->cellAttributes() ?>>
<span id="el_conteo_detalle_conteo">
<input type="<?= $Page->conteo->getInputTextType() ?>" name="x_conteo" id="x_conteo" data-table="conteo_detalle" data-field="x_conteo" value="<?= $Page->conteo->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->conteo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->conteo->formatPattern()) ?>"<?= $Page->conteo->editAttributes() ?> aria-describedby="x_conteo_help">
<?= $Page->conteo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->conteo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
    <div id="r_articulo"<?= $Page->articulo->rowAttributes() ?>>
        <label id="elh_conteo_detalle_articulo" for="x_articulo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->articulo->caption() ?><?= $Page->articulo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->articulo->cellAttributes() ?>>
<span id="el_conteo_detalle_articulo">
<input type="<?= $Page->articulo->getInputTextType() ?>" name="x_articulo" id="x_articulo" data-table="conteo_detalle" data-field="x_articulo" value="<?= $Page->articulo->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->articulo->formatPattern()) ?>"<?= $Page->articulo->editAttributes() ?> aria-describedby="x_articulo_help">
<?= $Page->articulo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->articulo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cantidad->Visible) { // cantidad ?>
    <div id="r_cantidad"<?= $Page->cantidad->rowAttributes() ?>>
        <label id="elh_conteo_detalle_cantidad" for="x_cantidad" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidad->caption() ?><?= $Page->cantidad->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cantidad->cellAttributes() ?>>
<span id="el_conteo_detalle_cantidad">
<input type="<?= $Page->cantidad->getInputTextType() ?>" name="x_cantidad" id="x_cantidad" data-table="conteo_detalle" data-field="x_cantidad" value="<?= $Page->cantidad->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->cantidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cantidad->formatPattern()) ?>"<?= $Page->cantidad->editAttributes() ?> aria-describedby="x_cantidad_help">
<?= $Page->cantidad->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cantidad->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_switch->Visible) { // switch ?>
    <div id="r__switch"<?= $Page->_switch->rowAttributes() ?>>
        <label id="elh_conteo_detalle__switch" for="x__switch" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_switch->caption() ?><?= $Page->_switch->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_switch->cellAttributes() ?>>
<span id="el_conteo_detalle__switch">
<input type="<?= $Page->_switch->getInputTextType() ?>" name="x__switch" id="x__switch" data-table="conteo_detalle" data-field="x__switch" value="<?= $Page->_switch->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->_switch->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_switch->formatPattern()) ?>"<?= $Page->_switch->editAttributes() ?> aria-describedby="x__switch_help">
<?= $Page->_switch->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_switch->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fconteo_detalleadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fconteo_detalleadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("conteo_detalle");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
