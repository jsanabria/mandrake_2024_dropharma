<?php

namespace PHPMaker2024\mandrake;

// Page object
$PresupustoDetalleAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { presupusto_detalle: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fpresupusto_detalleadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpresupusto_detalleadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["presupuesto", [fields.presupuesto.visible && fields.presupuesto.required ? ew.Validators.required(fields.presupuesto.caption) : null, ew.Validators.integer], fields.presupuesto.isInvalid],
            ["grupo1", [fields.grupo1.visible && fields.grupo1.required ? ew.Validators.required(fields.grupo1.caption) : null], fields.grupo1.isInvalid],
            ["grupo2", [fields.grupo2.visible && fields.grupo2.required ? ew.Validators.required(fields.grupo2.caption) : null], fields.grupo2.isInvalid],
            ["numero", [fields.numero.visible && fields.numero.required ? ew.Validators.required(fields.numero.caption) : null, ew.Validators.integer], fields.numero.isInvalid],
            ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null], fields.articulo.isInvalid],
            ["linea", [fields.linea.visible && fields.linea.required ? ew.Validators.required(fields.linea.caption) : null], fields.linea.isInvalid],
            ["imagen", [fields.imagen.visible && fields.imagen.required ? ew.Validators.required(fields.imagen.caption) : null], fields.imagen.isInvalid],
            ["descripcion", [fields.descripcion.visible && fields.descripcion.required ? ew.Validators.required(fields.descripcion.caption) : null], fields.descripcion.isInvalid],
            ["cantidad", [fields.cantidad.visible && fields.cantidad.required ? ew.Validators.required(fields.cantidad.caption) : null, ew.Validators.integer], fields.cantidad.isInvalid],
            ["precio", [fields.precio.visible && fields.precio.required ? ew.Validators.required(fields.precio.caption) : null, ew.Validators.float], fields.precio.isInvalid],
            ["total", [fields.total.visible && fields.total.required ? ew.Validators.required(fields.total.caption) : null, ew.Validators.float], fields.total.isInvalid]
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
<form name="fpresupusto_detalleadd" id="fpresupusto_detalleadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="presupusto_detalle">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->presupuesto->Visible) { // presupuesto ?>
    <div id="r_presupuesto"<?= $Page->presupuesto->rowAttributes() ?>>
        <label id="elh_presupusto_detalle_presupuesto" for="x_presupuesto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->presupuesto->caption() ?><?= $Page->presupuesto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->presupuesto->cellAttributes() ?>>
<span id="el_presupusto_detalle_presupuesto">
<input type="<?= $Page->presupuesto->getInputTextType() ?>" name="x_presupuesto" id="x_presupuesto" data-table="presupusto_detalle" data-field="x_presupuesto" value="<?= $Page->presupuesto->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->presupuesto->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->presupuesto->formatPattern()) ?>"<?= $Page->presupuesto->editAttributes() ?> aria-describedby="x_presupuesto_help">
<?= $Page->presupuesto->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->presupuesto->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->grupo1->Visible) { // grupo1 ?>
    <div id="r_grupo1"<?= $Page->grupo1->rowAttributes() ?>>
        <label id="elh_presupusto_detalle_grupo1" for="x_grupo1" class="<?= $Page->LeftColumnClass ?>"><?= $Page->grupo1->caption() ?><?= $Page->grupo1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->grupo1->cellAttributes() ?>>
<span id="el_presupusto_detalle_grupo1">
<input type="<?= $Page->grupo1->getInputTextType() ?>" name="x_grupo1" id="x_grupo1" data-table="presupusto_detalle" data-field="x_grupo1" value="<?= $Page->grupo1->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->grupo1->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->grupo1->formatPattern()) ?>"<?= $Page->grupo1->editAttributes() ?> aria-describedby="x_grupo1_help">
<?= $Page->grupo1->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->grupo1->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->grupo2->Visible) { // grupo2 ?>
    <div id="r_grupo2"<?= $Page->grupo2->rowAttributes() ?>>
        <label id="elh_presupusto_detalle_grupo2" for="x_grupo2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->grupo2->caption() ?><?= $Page->grupo2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->grupo2->cellAttributes() ?>>
<span id="el_presupusto_detalle_grupo2">
<input type="<?= $Page->grupo2->getInputTextType() ?>" name="x_grupo2" id="x_grupo2" data-table="presupusto_detalle" data-field="x_grupo2" value="<?= $Page->grupo2->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->grupo2->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->grupo2->formatPattern()) ?>"<?= $Page->grupo2->editAttributes() ?> aria-describedby="x_grupo2_help">
<?= $Page->grupo2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->grupo2->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->numero->Visible) { // numero ?>
    <div id="r_numero"<?= $Page->numero->rowAttributes() ?>>
        <label id="elh_presupusto_detalle_numero" for="x_numero" class="<?= $Page->LeftColumnClass ?>"><?= $Page->numero->caption() ?><?= $Page->numero->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->numero->cellAttributes() ?>>
<span id="el_presupusto_detalle_numero">
<input type="<?= $Page->numero->getInputTextType() ?>" name="x_numero" id="x_numero" data-table="presupusto_detalle" data-field="x_numero" value="<?= $Page->numero->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->numero->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->numero->formatPattern()) ?>"<?= $Page->numero->editAttributes() ?> aria-describedby="x_numero_help">
<?= $Page->numero->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->numero->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
    <div id="r_articulo"<?= $Page->articulo->rowAttributes() ?>>
        <label id="elh_presupusto_detalle_articulo" for="x_articulo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->articulo->caption() ?><?= $Page->articulo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->articulo->cellAttributes() ?>>
<span id="el_presupusto_detalle_articulo">
<input type="<?= $Page->articulo->getInputTextType() ?>" name="x_articulo" id="x_articulo" data-table="presupusto_detalle" data-field="x_articulo" value="<?= $Page->articulo->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->articulo->formatPattern()) ?>"<?= $Page->articulo->editAttributes() ?> aria-describedby="x_articulo_help">
<?= $Page->articulo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->articulo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->linea->Visible) { // linea ?>
    <div id="r_linea"<?= $Page->linea->rowAttributes() ?>>
        <label id="elh_presupusto_detalle_linea" for="x_linea" class="<?= $Page->LeftColumnClass ?>"><?= $Page->linea->caption() ?><?= $Page->linea->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->linea->cellAttributes() ?>>
<span id="el_presupusto_detalle_linea">
<input type="<?= $Page->linea->getInputTextType() ?>" name="x_linea" id="x_linea" data-table="presupusto_detalle" data-field="x_linea" value="<?= $Page->linea->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->linea->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->linea->formatPattern()) ?>"<?= $Page->linea->editAttributes() ?> aria-describedby="x_linea_help">
<?= $Page->linea->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->linea->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->imagen->Visible) { // imagen ?>
    <div id="r_imagen"<?= $Page->imagen->rowAttributes() ?>>
        <label id="elh_presupusto_detalle_imagen" for="x_imagen" class="<?= $Page->LeftColumnClass ?>"><?= $Page->imagen->caption() ?><?= $Page->imagen->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->imagen->cellAttributes() ?>>
<span id="el_presupusto_detalle_imagen">
<input type="<?= $Page->imagen->getInputTextType() ?>" name="x_imagen" id="x_imagen" data-table="presupusto_detalle" data-field="x_imagen" value="<?= $Page->imagen->EditValue ?>" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->imagen->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->imagen->formatPattern()) ?>"<?= $Page->imagen->editAttributes() ?> aria-describedby="x_imagen_help">
<?= $Page->imagen->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->imagen->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <div id="r_descripcion"<?= $Page->descripcion->rowAttributes() ?>>
        <label id="elh_presupusto_detalle_descripcion" for="x_descripcion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descripcion->caption() ?><?= $Page->descripcion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->descripcion->cellAttributes() ?>>
<span id="el_presupusto_detalle_descripcion">
<input type="<?= $Page->descripcion->getInputTextType() ?>" name="x_descripcion" id="x_descripcion" data-table="presupusto_detalle" data-field="x_descripcion" value="<?= $Page->descripcion->EditValue ?>" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->descripcion->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->descripcion->formatPattern()) ?>"<?= $Page->descripcion->editAttributes() ?> aria-describedby="x_descripcion_help">
<?= $Page->descripcion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->descripcion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cantidad->Visible) { // cantidad ?>
    <div id="r_cantidad"<?= $Page->cantidad->rowAttributes() ?>>
        <label id="elh_presupusto_detalle_cantidad" for="x_cantidad" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidad->caption() ?><?= $Page->cantidad->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cantidad->cellAttributes() ?>>
<span id="el_presupusto_detalle_cantidad">
<input type="<?= $Page->cantidad->getInputTextType() ?>" name="x_cantidad" id="x_cantidad" data-table="presupusto_detalle" data-field="x_cantidad" value="<?= $Page->cantidad->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->cantidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cantidad->formatPattern()) ?>"<?= $Page->cantidad->editAttributes() ?> aria-describedby="x_cantidad_help">
<?= $Page->cantidad->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cantidad->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
    <div id="r_precio"<?= $Page->precio->rowAttributes() ?>>
        <label id="elh_presupusto_detalle_precio" for="x_precio" class="<?= $Page->LeftColumnClass ?>"><?= $Page->precio->caption() ?><?= $Page->precio->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->precio->cellAttributes() ?>>
<span id="el_presupusto_detalle_precio">
<input type="<?= $Page->precio->getInputTextType() ?>" name="x_precio" id="x_precio" data-table="presupusto_detalle" data-field="x_precio" value="<?= $Page->precio->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->precio->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->precio->formatPattern()) ?>"<?= $Page->precio->editAttributes() ?> aria-describedby="x_precio_help">
<?= $Page->precio->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->precio->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
    <div id="r_total"<?= $Page->total->rowAttributes() ?>>
        <label id="elh_presupusto_detalle_total" for="x_total" class="<?= $Page->LeftColumnClass ?>"><?= $Page->total->caption() ?><?= $Page->total->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->total->cellAttributes() ?>>
<span id="el_presupusto_detalle_total">
<input type="<?= $Page->total->getInputTextType() ?>" name="x_total" id="x_total" data-table="presupusto_detalle" data-field="x_total" value="<?= $Page->total->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->total->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->total->formatPattern()) ?>"<?= $Page->total->editAttributes() ?> aria-describedby="x_total_help">
<?= $Page->total->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->total->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fpresupusto_detalleadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fpresupusto_detalleadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("presupusto_detalle");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
